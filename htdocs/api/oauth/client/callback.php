<?php
session_start();

//file_put_contents(__DIR__ . '/callback_debug.log', "[" . date('Y-m-d H:i:s') . "] Called with code: " . ($_GET['code'] ?? 'none') . "\n", FILE_APPEND);

if (!isset($_GET['code'])) {
    die('No auth code received');
}

echo "<h3>Callback triggered with code: " . htmlspecialchars($_GET['code']) . "</h3>";

// 模拟 POST 请求来替代 curl
require_once('config.inc.php');
$_SERVER['REQUEST_METHOD'] = 'POST';
$_POST = [
  'grant_type' => 'authorization_code',
  'code' => $_GET['code'],
  'redirect_uri' => $redirect_uri,
  'client_id' => $client_id,
  'client_secret' => $client_secret
];

require_once('../../vendor/autoload.php');
require_once('../../config.inc.php');

$dsn        = "mysql:host=".$DB_HOST.";dbname=".$DB_DATABASE.";charset=utf8mb4";
$OauthDb    = new PDO($dsn, $DB_USERNAME, $DB_PASSWORD);
$OauthDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$storage = new OAuth2\Storage\Pdo(array('dsn' => $dsn, 'username' => $DB_USERNAME, 'password' => $DB_PASSWORD));

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
print "access_token: ".$access_token."<BR>";

//把 access_token 转换为 用户信息
if($access_token != '')  {
  $url      = $access_token_uri."?accessToken=".$access_token;
  $content  = file_get_contents($url);
  //print_R($content);

  $UserInfor = json_decode($content, true);
  print "用户信息: "; print_R($UserInfor)."<BR>";
  print "用户登录SESSION:";
  $_SESSION['DANDIAN_OAUTH_CLIENT_USER_ID'] = $UserInfor['USER_ID'];
  print_R($_SESSION);

  exit;
}

print_R($statusCode);
print_R($bodyArray);
