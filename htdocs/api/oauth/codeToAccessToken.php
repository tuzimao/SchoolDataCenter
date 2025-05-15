<?php
session_start();
require_once('../vendor/autoload.php');
require_once('../config.inc.php');
require_once('../include.inc.php');

if (!isset($_GET['code'])) {
    die('No auth code received');
}

if (!isset($_SERVER['HTTP_AUTHORIZATION'])) {
    die('No client_secret received');
}

$code               = ForSqlInjection($_GET['code']);
$redirect_uri       = ForSqlInjection($_POST['redirect_uri']);
$client_id          = ForSqlInjection($_POST['client_id']);
$HTTP_AUTHORIZATION = ForSqlInjection($_SERVER['HTTP_AUTHORIZATION']);

// 模拟 POST 请求来替代 curl
$_SERVER['REQUEST_METHOD'] = 'POST';
$_POST = [
  'grant_type' => 'authorization_code',
  'code' => $code,
  'redirect_uri' => $redirect_uri,
  'client_id' => $client_id,
  'client_secret' => $HTTP_AUTHORIZATION
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
    print json_encode($RS);
    exit;
}
else {
    print json_encode($bodyArray);
    exit;
}
