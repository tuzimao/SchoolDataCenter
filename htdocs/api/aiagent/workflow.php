<?php
require_once('../cors.php');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require_once('../include.inc.php');

CheckAuthUserLoginStatus();


$USER_ID        = $GLOBAL_USER->USER_ID;

$payload        = file_get_contents('php://input');
$_POST          = json_decode($payload,true);

$action         = FilterString($_GET['action']);
$type           = FilterString($_POST['type']);
$search         = FilterString($_POST['search']);
$pageid         = intval($_POST['pageid']);
$pagesize       = intval($_POST['pagesize']);


if($action == "listmyapp" && $pagesize >= 6)  {
    $From   = $pageid * $pagesize;
    $sql    = "select * from data_ai_app where AppType != 'Database' and UserId = '".$GLOBAL_USER->USER_ID."' order by id desc limit $From, $pagesize";
    $rs     = $db->Execute($sql);
    $rs_a   = $rs->GetArray();
    for($i=0;$i<sizeof($rs_a);$i++) {
        $rs_a[$i]['_id'] = EncryptID($rs_a[$i]['id']);
    }
    $RS     = [];
    $RS['pageid']   = $pageid;
    $RS['pagesize'] = $pagesize;
    $RS['data']     = $rs_a;
    $RS['from']     = $From;
    $RS['total']    = sizeof($rs_a);
    $RS['allpages'] = ceil($RS['total']/$pagesize);

    print json_encode($RS);
    exit;
}


if($action == "listallapp" && $pagesize >= 6)  {
    $From   = $pageid * $pagesize;
    $sql    = "select * from data_ai_app where AppType != 'Database' order by id desc limit $From, $pagesize";
    $rs     = $db->Execute($sql);
    $rs_a   = $rs->GetArray();
    for($i=0;$i<sizeof($rs_a);$i++) {
        $rs_a[$i]['_id'] = EncryptID($rs_a[$i]['id']);
    }
    $RS     = [];
    $RS['pageid']   = $pageid;
    $RS['pagesize'] = $pagesize;
    $RS['data']     = $rs_a;
    $RS['from']     = $From;
    $RS['total']    = sizeof($rs_a);
    $RS['allpages'] = ceil($RS['total']/$pagesize);

    print json_encode($RS);
    exit;
}


$appId      = FilterString($_POST['appId']);
if($action == "deletemyapp" && $appId != '')  {
    $appId  = DecryptID($appId);
    if($appId>0)  {
        $sql    = "delete from data_ai_app where id='$appId' and UserId = '".$GLOBAL_USER->USER_ID."'";
        //$db->Execute($sql);
        $RS             = [];
        $RS['sql']      = $sql;
        $RS['status']   = 'ok';
        $RS['msg']      = _('Delete Success');
    }
    else {
        $RS             = [];
        $RS['status']   = 'error';
        $RS['msg']      = _('Id Not Exist');
    }

    print json_encode($RS);
    exit;
}


$GroupOne         = FilterString($_POST['GroupOne']);
$GroupTwo         = FilterString($_POST['GroupTwo']);
$AppIntro         = FilterString($_POST['AppIntro']);
$AppName          = FilterString($_POST['AppName']);
$IsPublic         = FilterString($_POST['IsPublic']);
$AppType          = FilterString($_POST['AppType']);
$data             = $_POST['data'];
if($action == "addapp" && $AppName != '')  {    
    $Element        = [];
    $Element['GroupOne']    = $GroupOne;
    $Element['GroupTwo']    = $GroupTwo;
    $Element['AppName']     = $AppName;
    $Element['AppIntro']    = $AppIntro;
    $Element['IsPublic']    = $IsPublic == 'public' ? '是' : '否';
    $Element['AppType']     = $AppType;
    $Element['UserId']      = $GLOBAL_USER->USER_ID;
    $Element['Scope']       = '全校';
    $Element['AppAvatar']   = 'mdi:teacher';
    $Element['AppData']     = base64_encode(json_encode($data));
    $KEYS       = array_keys($Element);
    $VALUES     = array_values($Element);
    $sql        = "insert into data_ai_app (".join(',',$KEYS).") values('".join("','",$VALUES)."');";
    $db->Execute($sql);
    $InsertID       = $db->Insert_ID('data_ai_app');
    $RS             = [];
    $RS['InsertID'] = EncryptID($InsertID);
    $RS['sql']      = $sql;
    $RS['status']   = 'ok';
    $RS['msg']      = _('Add Success');

    print json_encode($RS);
    exit;
}



?>
