<?php
//SchoolAI 统一身份认证 Demo程序
//2025-05-05

session_start();
require_once('config.inc.php');

if (!isset($_GET['code'])) {
    die('No auth code received');
}

//注意: code是一次性的, 不能进行刷新, 所以需要第三方应用程序来处理自己的业务逻辑

try {
  //第一步: 使用code拿到accessToken的值, code是一次性的, accessToken的值的有效期是24小时, 可以多次调用
  $url      = $code_token_uri."?code=".$_GET['code'];
  $_POST    = [
    'grant_type' => 'authorization_code',
    'code' => $_GET['code'],
    'redirect_uri' => $redirect_uri,
    'client_id' => $client_id
  ];
  //client_secret的值是不可以外泄漏的, 所以client_secret的值需要放到header里面进行传输
  $result = httpRequest($url, 'POST', $_POST, [ 'authorization: ' . $client_secret ]);
  $Data   = $result['body'];
  $RS     = json_decode($Data, true);
  
  //成功获取到access_token的值
  if($RS['status'] == "ok" && $RS['access_token'] != "") {
    
    //使用access_token的值来获取用户的信息, access_token的值为了不让在网络传输中被记录, access_token的值放入POST中进行传输
    $_POST    = [ 'accessToken' => $RS['access_token'] ];
    $result   = httpRequest($access_token_uri, 'POST', $_POST, []);
    $Data     = $result['body'];
    
    //得到用户的信息
    $用户信息 = json_decode($Data, true);

    //拿到用户信息以后, 请根据自己应用程序的需要, 自行处理用户授权信息
    print "用户信息获取成功!<BR>";
    print "用户信息: "; print_R($用户信息)."<BR>";
    print "用户登录SESSION:"; print_R($_SESSION);
    exit;
  }
  else {
    print_R($RS);
    exit;
  }
} 
catch (Exception $e) {
  echo "Error: " . $e->getMessage();
}
