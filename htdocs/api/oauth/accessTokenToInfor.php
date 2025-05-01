<?php
require_once('server.php');
require_once('../include.inc.php');

$accessToken                    = filterString($_GET['accessToken']);

$_POST                          = [];
$_SERVER['REQUEST_METHOD']      = 'GET';
$_SERVER['HTTP_AUTHORIZATION']  = 'Bearer ' . $accessToken;

$request = OAuth2\Request::createFromGlobals();
$response = new OAuth2\Response();

// 验证 access_token 是否有效
if (!$server->verifyResourceRequest($request, $response)) {
    $response->send();
    exit;
}

// 如果 token 验证通过，返回受保护的资源
$tokenData = $server->getAccessTokenData($request);

header('Content-Type: application/json');

print_R(json_encode($tokenData));
