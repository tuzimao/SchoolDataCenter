<?php
require_once('server.php');
require_once('../include.inc.php');

$getRealIP  = getRealIP();
//每个外部IP仅限登录10次-过期自动清除
$限制外部IP登录时间   = $redis->hGet("OAUTH_AccessTokenToUser_ADDRESS_LAST_TIME", $getRealIP);
if($限制外部IP登录时间 > 0 && (time() - $限制外部IP登录时间) > 60) {
    $redis->hSet("OAUTH_AccessTokenToUser_ADDRESS_LAST_TIME", $getRealIP, 0);
    $redis->hSet("OAUTH_AccessTokenToUser_ADDRESS_LIMIT", $getRealIP, 0);
}
else {
    //每个外部IP仅限登录10次-开始记录
    $限制外部IP登录次数 = (int)$redis->hGet("OAUTH_AccessTokenToUser_ADDRESS_LIMIT", $getRealIP);
    if($限制外部IP登录次数 > 5) {
        $RS             = [];
        $RS['status']   = "ERROR";
        $RS['msg']      = __("Malicious ip");
        $redis->hSet("OAUTH_AccessTokenToUser_ADDRESS_LAST_TIME", $getRealIP, time());
        print_R(EncryptApiData($RS, (Object)['USER_ID'=>time()], true));
        exit;
    }
}

$accessToken            = ForSqlInjection($_POST['access_token']);

if(strlen($accessToken) != 40) {
    $RS                     = [];
    $RS['status']           = 'error';
    $RS['message']          = 'access_token is invalid';
    header('Content-Type: application/json');
    print_R(json_encode($RS));
    $redis->hSet("CAS_AccessTokenToUser_ADDRESS_LIMIT", $getRealIP, $限制外部IP登录次数+1);
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
        $RS['id']               = $UserInfo['id'];
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
        $RS['id']               = $StudentInfo['id'];
    }
    header('Content-Type: application/json');
    print_R(json_encode($RS));
    $redis->hSet("CAS_AccessTokenToUser_ADDRESS_LIMIT", $getRealIP, 0);
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
    $redis->hSet("CAS_AccessTokenToUser_ADDRESS_LIMIT", $getRealIP, $限制外部IP登录次数+1);
    exit;
}


