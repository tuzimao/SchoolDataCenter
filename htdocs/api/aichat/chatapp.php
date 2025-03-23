<?php
header("Content-Type: application/json");
require_once('../cors.php');
require_once('../include.inc.php');

CheckAuthUserLoginStatus();

$USER_ID = $GLOBAL_USER->USER_ID;

$payload        = file_get_contents('php://input');
$_POST          = json_decode($payload,true);

if($_GET['action'] == 'getAppList')  {
  $sql          = "select * from data_ai_app order by OrderId asc";
  $rs           = $db->Execute($sql);
  $rs_a         = (array)$rs->GetArray();
  $Data         = [];
  foreach($rs_a as $Item)  {
    $GroupOne           = $Item['GroupOne'];
    $Data[$GroupOne][]  = $Item;
  }
  $AppList              = [];
  foreach($Data as $Key=>$Value) {
    $AppList[] = ['title'=>$Key, 'children'=>$Value];
  }
  $RS           = [];
  $RS['data']   = $AppList;
  $RS['status'] = "OK";
  $RS['msg']    = __("Success");
  print json_encode($RS);
  exit;
}

if($_GET['action'] == 'getApp')  {
  $id           = intval($_POST['id']);
  $sql          = "select * from data_ai_app where id = '$id'";
  $rs           = $db->Execute($sql);
  $rs_a         = (array)$rs->GetArray();
  $rs_a[0]['id2'] = EncryptID($rs_a[0]['id']);
  $RS           = [];
  $RS['data']   = $rs_a[0];
  $RS['status'] = "OK";
  $RS['msg']    = __("Success");
  print json_encode($RS);
  exit;
}

?>
