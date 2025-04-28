<?php
require_once('server.php');

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
echo json_encode([
    'user_id' => $tokenData['user_id'],
    'client_id' => $tokenData['client_id'],
    'message' => 'Protected resource accessed!'
]);
