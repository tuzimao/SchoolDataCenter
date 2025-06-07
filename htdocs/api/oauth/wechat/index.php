<?php
header("Content-Type: application/json");
require_once('../../cors.php');
require_once('../../include.inc.php');

global $EncryptApiEnable;
$EncryptApiEnable = 1;

$RS['openid'] 	=  "o8qXQ65lQmGyK2cB4KQAkWcK4_Zo";
$RS['nickname'] =  "王纪云";
$RS['sex'] 		=  0;
$RS['language'] =  "";
$RS['city'] 	=  "";
$RS['province'] =  "";
$RS['country'] 	=  "";
$RS['headimgurl'] 	=  "https://thirdwx.qlogo.cn/mmopen/vi_32/DYAIOgq83eq57YjNN6fhEoeoMhads3q216W4YnnKq5EuuDhsp88rDaosicJIDmfAqyB3jnYsMvkribcTz3Cpzia6Q/132";
$RS['privilege'] 	= [ ];
$RS['unionid'] 		=  "ol6PQ66dJE0XNaECZeCHJv6JpJF8";

$openid 			= $RS['openid'];
if($openid != "")  {
	$USER_ID = returntablefield('data_oauth_wechat', "openid", $openid, 'USER_ID')['USER_ID'];
	if($USER_ID != '')  {
		ReturnLoginUserInfo($USER_ID, 'Wechat');
	}
}
exit;

$code 	= $_GET['code'];
$appid 	= 'wx8731239b834cd9ca';
$secret = 'ba73792c174e72a0cb079f30fc6f0ef1';

// 请求 access_token
$url		= "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$secret&code=$code&grant_type=authorization_code";
$response 	= file_get_contents($url);
$data 		= json_decode($response, true);

// 获取用户基本信息（可选）
$access_token 	= $data['access_token'];
$openid 		= $data['openid'];
$userInfoUrl 	= "https://api.weixin.qq.com/sns/userinfo?access_token=$access_token&openid=$openid";
$userInfo 		= json_decode(file_get_contents($userInfoUrl), true);

print_R($userInfo);


?>