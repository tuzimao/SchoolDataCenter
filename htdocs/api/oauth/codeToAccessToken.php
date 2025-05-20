<?php
require_once('../vendor/autoload.php');
require_once('../config.inc.php');
require_once('../include.inc.php');

//记录调用日志
$datetime = date('Y-m-d H:i:s');
$redis->hset("TEST_INFO_POST", $datetime, json_encode($_POST));
$redis->hset("TEST_INFO_GET", $datetime, json_encode($_GET));
$redis->hset("TEST_INFO_PAYLOAD", $datetime, file_get_contents('php://input'));
$TEST_INFO_TIME = $redis->get("TEST_INFO_TIME");
$TEST_INFO_TIME = (array)json_decode($TEST_INFO_TIME, true);
array_unshift($TEST_INFO_TIME, $datetime);
$redis->set("TEST_INFO_TIME", json_encode($TEST_INFO_TIME));

//判断传入条件
if (!isset($_POST['code'])) {
    die('No auth code received');
}

if (!isset($_POST['redirect_uri'])) {
    die('No auth redirect_uri received');
}

$getRealIP  = getRealIP();
//每个外部IP仅限登录10次-过期自动清除
$限制外部IP登录时间   = $redis->hGet("OAUTH_CodeToAccessToken_ADDRESS_LAST_TIME", $getRealIP);
if($限制外部IP登录时间 > 0 && (time() - $限制外部IP登录时间) > 60) {
    $redis->hSet("OAUTH_CodeToAccessToken_ADDRESS_LAST_TIME", $getRealIP, 0);
    $redis->hSet("OAUTH_CodeToAccessToken_ADDRESS_LIMIT", $getRealIP, 0);
}
else {
    //每个外部IP仅限登录10次-开始记录
    $限制外部IP登录次数 = (int)$redis->hGet("OAUTH_CodeToAccessToken_ADDRESS_LIMIT", $getRealIP);
    if($限制外部IP登录次数 > 5) {
        $RS             = [];
        $RS['status']   = "ERROR";
        $RS['msg']      = __("Malicious ip");
        $redis->hSet("OAUTH_CodeToAccessToken_ADDRESS_LAST_TIME", $getRealIP, time());
        print_R(EncryptApiData($RS, (Object)['USER_ID'=>time()], true));
        exit;
    }
}

$code               = ForSqlInjection($_POST['code']);
$redirect_uri       = ForSqlInjection($_POST['redirect_uri']);
$client_id          = ForSqlInjection($_POST['client_id']);
$client_secret      = ForSqlInjection($_POST['client_secret']);

// 模拟 POST 请求来替代 curl
$_SERVER['REQUEST_METHOD'] = 'POST';
$_POST = [
  'grant_type' => 'authorization_code',
  'code' => $code,
  'redirect_uri' => $redirect_uri,
  'client_id' => $client_id,
  'client_secret' => $client_secret
];
//print_R($_POST);exit;

require_once('server.php');
$server = new OAuth2\Server($storage, [
    'access_lifetime' => 3600,
    'enforce_state' => true,
    'allow_implicit' => false
]);

// 支持的授权类型
$server->addGrantType(new OAuth2\GrantType\AuthorizationCode($storage));
$server->addGrantType(new OAuth2\GrantType\ClientCredentials($storage));
$server->addGrantType(new OAuth2\GrantType\UserCredentials($storage));
$server->addGrantType(new OAuth2\GrantType\RefreshToken($storage));

$request = OAuth2\Request::createFromGlobals();
$response = new OAuth2\Response();
// 处理 token 请求（code 换 token）
$server->handleTokenRequest($request, $response);

$response = $server->handleTokenRequest($request, $response);

$statusCode = $response->getStatusCode();
$headers    = $response->getHttpHeaders();
$body       = $response->getResponseBody();
$bodyArray  = json_decode($body, true);

$access_token = $bodyArray['access_token'];

//把 access_token 转换为 用户信息
if($access_token != '')  {
    $RS = [];
    $RS['access_token']     = $access_token;
    $RS['tokenKey']         = $access_token;
    $RS['token_type']       = 'bearer';
    $RS['expires_in	']      = 86400;
    header('Content-Type: application/json');
    print json_encode($RS);
    $redis->hSet("CAS_CodeToAccessToken_ADDRESS_LIMIT", $getRealIP, 0);
    exit;
}
else {
    header('Content-Type: application/json');
    print json_encode($bodyArray);
    $redis->hSet("CAS_CodeToAccessToken_ADDRESS_LIMIT", $getRealIP, $限制外部IP登录次数+1);
    exit;
}
