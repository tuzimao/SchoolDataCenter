<?php
/*
* 基础架构: 单点低代码开发平台
* 版权所有: 郑州单点科技软件有限公司
* Email: moodle360@qq.com
* Copyright (c) 2007-2025
* License: 商业授权
*/

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
require_once('cors.php');
//session_start(); // 必须位于 headers 之后

require_once('include.inc.php');
require_once('./lib/data_enginee_function.php');

/**
 * IMPORTANT:
 * You must specify supported algorithms for your application. See
 * https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40
 * for a list of spec-compliant algorithms.
 */

global $EncryptApiEnable;

$EncryptApiEnable = 1;
$difficulty = '0000';

if($_GET['action']=='pow') {
    header('Content-Type: application/json');
    $challenge = bin2hex(random_bytes(16)); // 32 字符
    print json_encode(['challenge' => $challenge, 'difficulty' => $difficulty]);
    RedisAddElement("JWT_POW_CHALLENGE_CHAR", $challenge, 600);
    exit;
}

if($_GET['action']=="login")                {

    JWT::$leeway    = $NEXT_PUBLIC_JWT_EXPIRATION;
    $payload        = file_get_contents('php://input');
    $_POST          = json_decode($payload,true);
    $Data           = $_POST['Data'];
    $Data           = decodeBase58(decodeBase58($Data));
    $_POST          = json_decode($Data, true);
    
    //POW计算证明校验
    $challenge      = ForSqlInjection($_POST['challenge']);
    $nonce          = ForSqlInjection($_POST['nonce']);
    $hash           = ForSqlInjection($_POST['hash']);
    if(strlen($challenge)!=32 && strlen($hash)!=64)  {
        $RS = [];
        $RS['status']   = "ERROR";
        $RS['msg']      = $RS['email']    = "用户客户端工作量证明内容失败";
        print_R(EncryptApiData($RS, (Object)['USER_ID'=>time()], true));
        exit;
    }
    $challengeValue = RedisGetElement("JWT_POW_CHALLENGE_CHAR", $challenge);
    if(strlen($challengeValue)==32) {
        $test = $challengeValue . $nonce;
        $hash = hash('sha256', $test);
        if(substr($hash, 0, strlen($difficulty)) != $difficulty) {
            $RS['status']   = "ERROR";
            $RS['msg']      = $RS['email']    = "用户客户端工作量证明验证失败";
            print_R(EncryptApiData($RS, (Object)['USER_ID'=>time()], true));
            exit;
        }
    }

    $EMAIL          = ForSqlInjection($_POST['email']);
    $USER_ID        = ForSqlInjection($_POST['username']);
    $password       = ForSqlInjection($_POST['password']);
    $rememberMe     = ForSqlInjection($_POST['rememberMe']);
    $UserType       = ForSqlInjection($_POST['UserType']);

    $getRealIP      = getRealIP();
    //每个外部IP仅限登录10次-过期自动清除
    $限制外部IP登录时间   = $redis->hGet("USER_LOGIN_IP_ADDRESS_LAST_TIME", $getRealIP);
    if($限制外部IP登录时间 > 0 && (time() - $限制外部IP登录时间) > 60) {
        $redis->hSet("USER_LOGIN_IP_ADDRESS_LAST_TIME", $getRealIP, 0);
        $redis->hSet("USER_LOGIN_IP_ADDRESS_LIMIT", $getRealIP, 0);
    }
    else {
        //每个外部IP仅限登录10次-开始记录
        $限制外部IP登录次数 = (int)$redis->hGet("USER_LOGIN_IP_ADDRESS_LIMIT", $getRealIP);
        if($限制外部IP登录次数 > 3) {
            $RS             = [];
            $RS['status']   = "ERROR";
            $RS['msg']      = __("Malicious ip");
            $redis->hSet("USER_LOGIN_IP_ADDRESS_LAST_TIME", $getRealIP, time());
            print_R(EncryptApiData($RS, (Object)['USER_ID'=>time()], true));
            exit;
        }
    }
    //判断用户密码错误次数-过期自动清除
    $用户登录错误时间   = $redis->hGet("USER_LOGIN_ERROR_LAST_TIME", $USER_ID);
    //print (time() - $用户登录错误时间);
    if($用户登录错误时间 > 0 && (time() - $用户登录错误时间) > 60) {
        $redis->hSet("USER_LOGIN_ERROR_LAST_TIME", $USER_ID, 0);
        $redis->hSet("USER_LOGIN_ERROR_TIMES_LIMIT", $USER_ID, 0);
    }
    else {
        //判断用户密码错误次数-开始记录
        $用户登录错误次数   = (int)$redis->hGet("USER_LOGIN_ERROR_TIMES_LIMIT", $USER_ID);
        if($用户登录错误次数 > 3) {
            $RS             = [];
            $RS['status']   = "ERROR";
            $RS['msg']      = __("Malicious login");
            $redis->hSet("USER_LOGIN_ERROR_LAST_TIME", $USER_ID, time());
            print_R(EncryptApiData($RS, (Object)['USER_ID'=>time()], true));
            exit;
        }
    }
    if($USER_ID!="")   {
        if($EMAIL!="")   {
            $sql = "select * from data_user where EMAIL='$EMAIL'";
        }
        else {
            $sql = "select * from data_user where USER_ID='$USER_ID'";
        }
        $rs		= $db->Execute($sql);
        $UserInfo = $rs->fields;
        if($UserInfo['USER_ID']==""&&$UserType!="校友")  {
            $sql    = "select * from data_student where 学号='$USER_ID'";
            $rs		= $db->Execute($sql);
            $StudentInfo = $rs->fields;
            if($StudentInfo['学号']=="")  {
                $RS = [];
                $RS['status']   = "ERROR";
                $RS['msg']      = $RS['email']    = __("USER NOT EXIST OR PASSWORD IS ERROR!");
                //$RS['sql']      = $sql;
                //$RS['_POST']    = $_POST;
                $redis->hSet("USER_LOGIN_ERROR_TIMES_LIMIT", $USER_ID, $用户登录错误次数+1);
                $redis->hSet("USER_LOGIN_IP_ADDRESS_LIMIT", $getRealIP, $限制外部IP登录次数+1);
                print_R(EncryptApiData($RS, (Object)['USER_ID'=>time()], true));
                SystemLogRecord("Login", __('USER NOT EXIST'), __("USER NOT EXIST OR PASSWORD IS ERROR!"),$USER_ID);
                exit;
            }
            $PASSWORD_IN_DB         = $StudentInfo['密码'];
            if($password!=""&&$PASSWORD_IN_DB!=""&&password_check($password,$PASSWORD_IN_DB))  {
                //Reform userData
                $userData = [];
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
                $userData['ExpireTime'] = time() + (3 * 60 * 30);
                $accessToken            = EncryptID(JWT::encode($userData, $NEXT_PUBLIC_JWT_SECRET, 'HS256'));
                $RS['accessToken']      = $accessToken;
                $RS['accessKey']        = GetAccessKey($userData['USER_ID']);
                $RS['userData']         = $userData;
                //$_SESSION['DANDIAN_OAUTH_SERVER_USER_ID']   = $userData['USER_ID'];
                //$_SESSION['DANDIAN_OAUTH_SERVER_USER_NAME'] = $userData['USER_NAME'];
                //$_SESSION['DANDIAN_OAUTH_SERVER_USER_TYPE'] = $userData['type'];

                //形成个人信息展示页面的数据列表
                $USER_PROFILE 	    = array();
                $USER_PROFILE[] 	= array("左边"=>"用户类型","右边"=>"学生");
                $USER_PROFILE[] 	= array("左边"=>"学号","右边"=>$userData['USER_ID']);
                $USER_PROFILE[] 	= array("左边"=>"姓名","右边"=>$userData['USER_NAME']);
                $USER_PROFILE[] 	= array("左边"=>"班级","右边"=>$userData['班级']);
                $USER_PROFILE[] 	= array("左边"=>"专业","右边"=>$userData['专业']);
                $USER_PROFILE[] 	= array("左边"=>"系部","右边"=>$userData['系部']);
                $RS['USER_PROFILE'] = $USER_PROFILE;
                $redis->hSet("USER_LOGIN_ERROR_TIMES_LIMIT", $USER_ID, 0);
                $redis->hSet("USER_LOGIN_IP_ADDRESS_LIMIT", $getRealIP, 0);
                print_R(EncryptApiData($RS, (Object)['USER_ID'=>time()], true));
                SystemLogRecord("Login", __("Success"), __("Success"),$USER_ID);
                exit;
            }
            $RS = [];
            $RS['status']   = "ERROR";
            $RS['msg']      = $RS['email']    = __("USER NOT EXIST OR PASSWORD IS ERROR!");
            //$RS['sql']      = $sql;
            //$RS['_POST']    = $_POST;
            $redis->hSet("USER_LOGIN_ERROR_TIMES_LIMIT", $USER_ID, $用户登录错误次数+1);
            $redis->hSet("USER_LOGIN_IP_ADDRESS_LIMIT", $getRealIP, $限制外部IP登录次数+1);
            print_R(EncryptApiData($RS, (Object)['USER_ID'=>time()], true));
            SystemLogRecord("Login", __('PASSWORD IS ERROR'), __("USER NOT EXIST OR PASSWORD IS ERROR!"),$USER_ID);
            exit;
        }
        if($UserInfo['USER_ID']==""&&$UserType=="校友")  {
            $sql    = "select * from data_xiaoyou_member where 学生姓名='$USER_ID'";
            $rs		= $db->Execute($sql);
            $StudentInfo = $rs->fields;
            if($StudentInfo['学生姓名']=="")  {
                $RS = [];
                $RS['status']   = "ERROR";
                $RS['msg']      = $RS['email']    = __("USER NOT EXIST OR PASSWORD IS ERROR!");
                //$RS['sql']      = $sql;
                //$RS['_POST']    = $_POST;
                SystemLogRecord("Login", __('USER NOT EXIST'), __("USER NOT EXIST OR PASSWORD IS ERROR!"),$USER_ID);
                $redis->hSet("USER_LOGIN_ERROR_TIMES_LIMIT", $USER_ID, $用户登录错误次数+1);
                $redis->hSet("USER_LOGIN_IP_ADDRESS_LIMIT", $getRealIP, $限制外部IP登录次数+1);
                print_R(EncryptApiData($RS, (Object)['USER_ID'=>time()], true));
                exit;
            }
            $PASSWORD_IN_DB         = $StudentInfo['身份证件号'];
            if($password!=""&&$PASSWORD_IN_DB!=""&&$password==$PASSWORD_IN_DB)  {
                //Reform userData
                $userData = [];
                $userData['id']         = $StudentInfo['id'];
                $userData['USER_ID']    = $StudentInfo['学生学号'];
                $userData['USER_NAME']  = $StudentInfo['学生姓名'];
                $userData['学号']       = $StudentInfo['学生学号'];
                $userData['姓名']       = $StudentInfo['学生姓名'];
                $userData['班级']       = $StudentInfo['班级'];
                $userData['专业']       = $StudentInfo['专业'];
                $userData['系部']       = $StudentInfo['院系'];
                $userData['PRIV_NAME']  = "校友";
                $userData['avatar']     = '/images/avatars/1.png';
                $userData['username']   = $StudentInfo['学生学号'];
                $userData['role']       = "校友";
                $userData['type']       = "Schoolmate";
                $userData['ExpireTime'] = time() + (3 * 60 * 30);
                $accessToken            = EncryptID(JWT::encode($userData, $NEXT_PUBLIC_JWT_SECRET, 'HS256'));
                $RS['accessToken']      = $accessToken;
                $RS['accessKey']        = GetAccessKey($userData['USER_ID']);
                $RS['userData']         = $userData;
                //$_SESSION['DANDIAN_OAUTH_SERVER_USER_ID']   = $userData['USER_ID'];
                //$_SESSION['DANDIAN_OAUTH_SERVER_USER_NAME'] = $userData['USER_NAME'];
                //$_SESSION['DANDIAN_OAUTH_SERVER_USER_TYPE'] = $userData['type'];
                $RS['status']           = "OK";
                $RS['msg']              = __("Success");

                //形成个人信息展示页面的数据列表
                $USER_PROFILE 	    = array();
                $USER_PROFILE[] 	= array("左边"=>"用户类型","右边"=>"校友");
                $USER_PROFILE[] 	= array("左边"=>"学号","右边"=>$userData['USER_ID']);
                $USER_PROFILE[] 	= array("左边"=>"姓名","右边"=>$userData['USER_NAME']);
                $USER_PROFILE[] 	= array("左边"=>"班级","右边"=>$userData['班级']);
                $USER_PROFILE[] 	= array("左边"=>"专业","右边"=>$userData['专业']);
                $USER_PROFILE[] 	= array("左边"=>"系部","右边"=>$userData['系部']);
                $RS['USER_PROFILE'] = $USER_PROFILE;
                $redis->hSet("USER_LOGIN_ERROR_TIMES_LIMIT", $USER_ID, 0);
                $redis->hSet("USER_LOGIN_IP_ADDRESS_LIMIT", $getRealIP, 0);
                print_R(EncryptApiData($RS, (Object)['USER_ID'=>time()], true));
                SystemLogRecord("Login", __("Success"), __("Success"),$USER_ID);
                $LOGIN_USER_OPENID = $_POST['LOGIN_USER_OPENID'];
                if($LOGIN_USER_OPENID!="")   {
                    $sql = "update data_xiaoyou_member set OPENID='$LOGIN_USER_OPENID' where id='".$StudentInfo['id']."'";
                    $db->Execute($sql);
                }
                exit;
            }
            $RS = [];
            $RS['status']   = "ERROR";
            $RS['msg']      = $RS['email']    = __("USER NOT EXIST OR PASSWORD IS ERROR!");
            //$RS['sql']      = $sql;
            //$RS['_POST']    = $_POST;
            SystemLogRecord("Login", __('PASSWORD IS ERROR'), __("USER NOT EXIST OR PASSWORD IS ERROR!"),$USER_ID);
            $redis->hSet("USER_LOGIN_ERROR_TIMES_LIMIT", $USER_ID, $用户登录错误次数+1);
            $redis->hSet("USER_LOGIN_IP_ADDRESS_LIMIT", $getRealIP, $限制外部IP登录次数+1);
            print_R(EncryptApiData($RS, (Object)['USER_ID'=>time()], true));
            exit;
        }
        $PASSWORD_IN_DB         = $UserInfo['PASSWORD'];
        if($password!=""&&$PASSWORD_IN_DB!=""&&password_check($password,$PASSWORD_IN_DB))  {
            //Reform userData
            $userData = [];
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
            $userData['ExpireTime'] = time() + (3 * 60 * 30);
            $accessToken            = EncryptID(JWT::encode($userData, $NEXT_PUBLIC_JWT_SECRET, 'HS256'));
            $RS['accessToken']      = $accessToken;
            $RS['accessKey']        = GetAccessKey($userData['USER_ID']);
            $RS['userData']         = $userData;
            //$_SESSION['DANDIAN_OAUTH_SERVER_USER_ID']   = $userData['USER_ID'];
            //$_SESSION['DANDIAN_OAUTH_SERVER_USER_NAME'] = $userData['USER_NAME'];
            //$_SESSION['DANDIAN_OAUTH_SERVER_USER_TYPE'] = $userData['type'];

            $GO_SYSTEM                          = [];
            $userInfoX                          = [];
            $userInfoX['userToken']             = $accessToken;
            $userInfoX['tokenName']             = "satoken";
            $userInfoX['userId']                = $UserInfo['id'];
            $userInfoX['userName']              = $UserInfo['USER_ID'];
            $userInfoX['nickName']              = $UserInfo['USER_NAME'];
            $userInfoX['t']                     = "function H(...q){return $(re=>Reflect.apply(er.translate,null,[re,...q]),()=>er.parseTranslateArgs(...q),\"translate\",re=>Reflect.apply(re.t,re,[...q]),re=>re,re=>Re.isString(re))}";
            $GO_SYSTEM['userInfo']              = $userInfoX;
            $GO_SYSTEM['fetchInfo']['OSSUrl']   = "/api/goview/bucket/";
            $RS['GO_SYSTEM']    = $GO_SYSTEM;

            $RS['status']       = "OK";
            //形成个人信息展示页面的数据列表
            $USER_PROFILE 	    = array();
            $USER_PROFILE[] 	= array("左边"=>"用户类型","右边"=>"教职工");
            $USER_PROFILE[] 	= array("左边"=>"用户名","右边"=>$userData['USER_ID']);
            $USER_PROFILE[] 	= array("左边"=>"姓名","右边"=>$userData['USER_NAME']);
            $USER_PROFILE[] 	= array("左边"=>"部门","右边"=>$userData['DEPT_NAME']);
            $USER_PROFILE[] 	= array("左边"=>"角色","右边"=>$userData['PRIV_NAME']);
            $RS['USER_PROFILE'] = $USER_PROFILE;
            $redis->hSet("USER_LOGIN_ERROR_TIMES_LIMIT", $USER_ID, 0);
            $redis->hSet("USER_LOGIN_IP_ADDRESS_LIMIT", $getRealIP, 0);
            print_R(EncryptApiData($RS, (Object)['USER_ID'=>time()], true));
            SystemLogRecord("Login", __("Success"), __("Success"),$USER_ID);
            exit;
        }
        else {
            $RS = [];
            $RS['status']   = "ERROR";
            $RS['msg']      = $RS['email']    = __("USER NOT EXIST OR PASSWORD IS ERROR!");
            //$RS['sql']      = $sql;
            //$RS['_POST']    = $_POST;
            $redis->hSet("USER_LOGIN_ERROR_TIMES_LIMIT", $USER_ID, $用户登录错误次数+1);
            $redis->hSet("USER_LOGIN_IP_ADDRESS_LIMIT", $getRealIP, $限制外部IP登录次数+1);
            print_R(EncryptApiData($RS, (Object)['USER_ID'=>time()], true));
            SystemLogRecord("Login", __('PASSWORD IS ERROR'), __("USER NOT EXIST OR PASSWORD IS ERROR!"),$USER_ID);
            exit;
        }
    }
    else {
        $RS = [];
        $RS['status']   = "ERROR";
        //$RS['_POST']    = $_POST;
        $redis->hSet("USER_LOGIN_ERROR_TIMES_LIMIT", $USER_ID, $用户登录错误次数+1);
        $redis->hSet("USER_LOGIN_IP_ADDRESS_LIMIT", $getRealIP, $限制外部IP登录次数+1);
        print_R(EncryptApiData($RS, (Object)['USER_ID'=>time()], true));
        SystemLogRecord("Login", __('USER NOT EXIST'), __("USER NOT EXIST"),"");
        exit;
    }
}

if($_GET['action']=="refresh")              {
    $CheckAuthUserLoginStatus               = CheckAuthUserLoginStatus();
    $CheckAuthUserLoginStatus->ExpireTime   = time() + (3 * 60 * 30);
    $accessToken                = EncryptID(JWT::encode((array) $CheckAuthUserLoginStatus, $NEXT_PUBLIC_JWT_SECRET, 'HS256'));
    $RS['status']               = 'ok';
    $RS['accessToken']          = $accessToken;
    $RS['accessKey']            = GetAccessKey($CheckAuthUserLoginStatus->USER_ID);
    $RS['userData']             = (array) $CheckAuthUserLoginStatus;
    if($CheckAuthUserLoginStatus->USER_ID != "")  {
        //$_SESSION['DANDIAN_OAUTH_SERVER_USER_ID']   = $CheckAuthUserLoginStatus->USER_ID;
        //$_SESSION['DANDIAN_OAUTH_SERVER_USER_NAME'] = $CheckAuthUserLoginStatus->USER_NAME;
    }
    if($CheckAuthUserLoginStatus->学号 != "")  {
        //$_SESSION['DANDIAN_OAUTH_SERVER_USER_ID']   = $CheckAuthUserLoginStatus->学号;
        //$_SESSION['DANDIAN_OAUTH_SERVER_USER_NAME'] = $CheckAuthUserLoginStatus->姓名;
    }
    if($CheckAuthUserLoginStatus->type != "")  {
        //$_SESSION['DANDIAN_OAUTH_SERVER_USER_TYPE'] = $CheckAuthUserLoginStatus->type;
    }

    $RS['ClientInfo']       = false;
    //OAuth2
    $client_id          = ForSqlInjection($_GET['client_id']);
    if($client_id != "")  {
        $sql    = "select id, redirect_uri, 应用名称, 应用描述, 应用LOGO, 应用URL, grant_types from data_oauth_clients where client_id='$client_id'";
        $rs		= $db->Execute($sql);
        $rs->fields['Type']     = "OAuth2";
        $rs->fields['应用LOGO'] = AttachFieldValueToUrl("data_oauth_clients", $rs->fields['id'], '应用LOGO', 'avatar', $rs->fields['应用LOGO']);
        $ClientInfo = $rs->fields;
        $RS['ClientInfo']       = $ClientInfo;
    }
    //CAS
    $service          = ForSqlInjection($_GET['service']);
    if($service != "")  {
        $sql    = "select id, 名称, 客户端地址 from data_cas_client where 客户端地址='$service' and 启用='是'";
        $rs		= $db->Execute($sql);
        $rs->fields['Type'] = "CAS";
        $ClientInfo = $rs->fields;
        
        //生成CAS 令牌 
        $Element                = [];
        $Element['应用']        = ForSqlInjection($ClientInfo['名称']);
        $Element['客户端地址']   = ForSqlInjection($ClientInfo['客户端地址']);
        $Element['令牌']        = 'ST-' . generateRandomLetters(30);
        $Element['是否有效']    = '1';
        $Element['创建时间']    = date('Y-m-d H:i:s');
        $Element['过期时间']    = date('Y-m-d H:i:s', strtotime('+1 hours'));
        if($CheckAuthUserLoginStatus->USER_ID != "")  {
            $Element['用户名']      = base64_encode($CheckAuthUserLoginStatus->USER_ID);
        }
        else {
            $Element['用户名']      = base64_encode($CheckAuthUserLoginStatus->学号);
        }
        $KEYS   = array_keys($Element);
        $VALUES = array_values($Element);
        $sql    = "insert into data_cas_ticket(".join(',',$KEYS).") values('".join("','",$VALUES)."')";
        $db->Execute($sql);
        $ClientInfo['Ticket']   = $Element['令牌'];
        $RS['ClientInfo']       = $ClientInfo;
    }
    //$RS['_SESSION']             = $_SESSION;
    print_R(EncryptApiData($RS, (Object)['USER_ID'=>time()], true));
    exit;
}

if($_GET['action']=="Logout")                {
    $CheckAuthUserLoginStatus   = CheckAuthUserLoginStatus();
    $USER_ID        = $CheckAuthUserLoginStatus->USER_ID;
    $RS             = [];
    $RS['status']   = "ERROR";
    //$RS['_POST']    = $_POST;
    print_R(EncryptApiData($RS, (Object)['USER_ID'=>time()], true));
    SystemLogRecord("Logout", __('Logout'), __("USER NOT EXIST"),$USER_ID);
    exit;
}


?>
