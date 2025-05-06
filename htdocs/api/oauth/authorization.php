<?php
session_start();
require_once('../cors.php');
require_once('../include.inc.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $params = http_build_query([ 'response_type' => $_GET['response_type'], 'client_id' => $_GET['client_id'], 'redirect_uri' => $_GET['redirect_uri'], 'state' => $_GET['state'] ]);
        
    header('Location: http://localhost:3000/oauth?' . $params); //开发环境使用

    //生产环境使用
    if ($_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
        header('Location: https://' . $_SERVER['HTTP_HOST'].'/oauth/?' . $params);
    }
    else {
        header('Location: /oauth/?' . $params);
    }
    
    exit;
}

$payload        = file_get_contents('php://input');
$_POST          = json_decode($payload,true);
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['authorized'] == 'Yes') {

    require_once('server.php');
    $request    = OAuth2\Request::createFromGlobals();
    $response   = new OAuth2\Response();

    // 检查授权请求是否合法
    if (!$server->validateAuthorizeRequest($request, $response)) {
        $response->send();
        //file_put_contents('debug.log', "[" . date('Y-m-d H:i:s') . "] Invalid authorize request\n", FILE_APPEND);
        exit;
    }

    CheckAuthUserLoginStatus();
    global $GLOBAL_USER;
    //同意授权
    $isAuthorized = true;
    $response = $server->handleAuthorizeRequest($request, $response, $isAuthorized, $GLOBAL_USER->USER_ID);
    $statusCode = $response->getStatusCode();
    $headers    = $response->getHttpHeaders();
    $body       = $response->getResponseBody();
    $RS         = [];
    $RS['Location']     = $headers['Location'];
    $RS['statusCode']   = $statusCode;
    $RS['authorized']   = $_POST['authorized'];
    print_R(json_encode($RS));
    exit;
}
