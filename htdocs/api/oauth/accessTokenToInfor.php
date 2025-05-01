<?php
require_once('server.php');
require_once('../include.inc.php');

$accessToken                    = filterString($_GET['accessToken']);

if(strlen($accessToken) != 40) {
    $RS                     = [];
    $RS['status']           = 'error';
    $RS['message']          = 'accessToken is invalid';
    header('Content-Type: application/json');
    print_R(json_encode($RS));
    exit;
}

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

$expires = $tokenData['expires'];
if($expires > time() && $tokenData['user_id'] != '')  {
    //未过期
    $RS                     = [];
    $RS['status']           = 'ok';
    $RS['access_token']     = $tokenData['access_token'];
    $RS['client_id']        = $tokenData['client_id'];
    $RS['expires']          = $tokenData['expires'];
    $RS['user_id']          = $tokenData['user_id'];

    $USER_ID = $tokenData['user_id'];
    $sql    = "select * from data_user where USER_ID='$USER_ID'";
    $rs		= $db->Execute($sql);
    $UserInfo = $rs->fields;
    if($UserInfo['USER_ID']!="")  {
        $userData               = [];
        $userData['id']         = $UserInfo['id'];
        $userData['USER_ID']    = $UserInfo['USER_ID'];
        $userData['USER_NAME']  = $UserInfo['USER_NAME'];
        $userData['EMAIL']      = $UserInfo['EMAIL'];
        $userData['DEPT_ID']    = $UserInfo['DEPT_ID'];
        $userData['DEPT_NAME']  = returntablefield("data_department","id",$UserInfo['DEPT_ID'],"DEPT_NAME")['DEPT_NAME'];
        $userData['PRIV_NAME']  = returntablefield("data_role","id",$UserInfo['USER_PRIV'],"name")['name'];
        $userData['USER_PRIV']  = $UserInfo['USER_PRIV'];
        $userData['avatar']     = '/images/avatars/1.png';
        $userData['username']   = $UserInfo['USER_ID'];
        $userData['email']      = $UserInfo['EMAIL'];
        $userData['role']       = $userData['PRIV_NAME'];
        $userData['type']       = "User";
        $RS['userData']         = $userData;
    }
    else {
        $sql    = "select * from data_student where 学号='$USER_ID'";
        $rs		= $db->Execute($sql);
        $StudentInfo = $rs->fields;
        $userData               = [];
        $userData['id']         = $StudentInfo['id'];
        $userData['USER_ID']    = $StudentInfo['学号'];
        $userData['USER_NAME']  = $StudentInfo['姓名'];
        $userData['学号']       = $StudentInfo['学号'];
        $userData['姓名']       = $StudentInfo['姓名'];
        $userData['班级']       = $StudentInfo['班级'];
        $userData['专业']       = $StudentInfo['专业'];
        $userData['系部']       = $StudentInfo['系部'];
        $userData['PRIV_NAME']  = "学生";
        $userData['avatar']     = '/images/avatars/1.png';
        $userData['username']   = $StudentInfo['学号'];
        $userData['role']       = "学生";
        $userData['type']       = "Student";
        $RS['userData']         = $userData;
    }
    header('Content-Type: application/json');
    print_R(json_encode($RS));
    exit;
}
else {
    //已过期
    $RS                     = [];
    $RS['status']           = 'error';
    $RS['message']          = 'Access token has expired';
    $RS['access_token']     = $tokenData['access_token'];
    $RS['client_id']        = $tokenData['client_id'];
    $RS['expires']          = $tokenData['expires'];
    header('Content-Type: application/json');
    print_R(json_encode($RS));
    exit;
}


