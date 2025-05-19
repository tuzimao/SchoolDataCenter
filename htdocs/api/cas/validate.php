<?php
require_once('../config.inc.php');
require_once('../include.inc.php');

$service    = ForSqlInjection($_GET['service']);
$ticket     = ForSqlInjection($_GET['ticket']);

if (!$service) {
    $RS                     = [];
    $RS['status']           = 'error';
    $RS['message']          = 'Client url is null';
    header('Content-Type: application/json');
    print_R(json_encode($RS));
    exit;
}
if (!$ticket) {
    $RS                     = [];
    $RS['status']           = 'error';
    $RS['message']          = 'Ticket is null';
    header('Content-Type: application/json');
    print_R(json_encode($RS));
    exit;
}

//Check ticket
$sql    = "select * from data_cas_ticket where 令牌='$ticket'";
$rs     = $db->Execute($sql);
$rs_a   = $rs->GetArray();
if($rs_a == null || sizeof($rs_a) == 0) {
    $RS                     = [];
    $RS['status']           = 'error';
    $RS['message']          = 'Ticket is invalid';
    header('Content-Type: application/json');
    print_R(json_encode($RS));
    exit;
}
$是否有效 = $rs_a[0]['是否有效'];
if($是否有效 == 0)  {
    $RS                     = [];
    $RS['status']           = 'error';
    $RS['message']          = 'Ticket expired';
    header('Content-Type: application/json');
    print_R(json_encode($RS));
    exit;
}
$用户名 = $rs_a[0]['用户名'];
if($用户名 == '')  {
    $RS                     = [];
    $RS['status']           = 'error';
    $RS['message']          = 'Ticket not relative user file';
    header('Content-Type: application/json');
    print_R(json_encode($RS));
    exit;
}
$客户端地址 = $rs_a[0]['客户端地址'];
if($客户端地址 != $service)  {
    $RS                     = [];
    $RS['status']           = 'error';
    $RS['message']          = 'Client url is invalid';
    header('Content-Type: application/json');
    print_R(json_encode($RS));
    exit;
}
$过期时间 = $rs_a[0]['过期时间'];
if($过期时间 < date('Y-m-d H:i:s'))  {
    $RS                     = [];
    $RS['status']           = 'error';
    $RS['message']          = 'Ticket is invalid and expired';
    header('Content-Type: application/json');
    print_R(json_encode($RS));
    exit;
}

$id     = $rs_a[0]['id'];
$用户名 = $rs_a[0]['用户名'];
$用户名 = base64_decode($用户名);
if($用户名 != '')  {
    //设置过期
    $sql    = "update data_cas_ticket set 是否有效='0' where id='$id'";
    $db->Execute($sql);

    //返回数据
    $RS                     = [];
    $RS['status']           = 'ok';
    $RS['user_id']          = $用户名;
    
    $USER_ID = $用户名;
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
    exit;
}
else {
    //已过期
    $RS                     = [];
    $RS['status']           = 'error';
    $RS['message']          = 'Token has error';
    header('Content-Type: application/json');
    print_R(json_encode($RS));
    exit;
}


