<?php
require_once('server.php');

$request = OAuth2\Request::createFromGlobals();
$response = new OAuth2\Response();
// 处理 token 请求（code 换 token）
$server->handleTokenRequest($request, $response)->send();
