<?php
require_once('../vendor/autoload.php');
require_once('../config.inc.php');

//删除日志
$redis->del("TEST_INFO_POST");
$redis->del("TEST_INFO_GET");
$redis->del("TEST_INFO_PAYLOAD");
$redis->del("TEST_INFO_TIME");

//输出日志
$TEST_INFO_TIME = $redis->get("TEST_INFO_TIME");
$TEST_INFO_TIME = (array)json_decode($TEST_INFO_TIME, true);

foreach($TEST_INFO_TIME as $TIME) {
	print "Access Time: $TIME<BR>";
	$INFOR = $redis->hget("TEST_INFO_POST", $TIME);
	$INFOR = json_decode($INFOR, true);
	print "_POST:";
	print_R($INFOR);
	print "<BR>";

	$INFOR = $redis->hget("TEST_INFO_GET", $TIME);
	$INFOR = json_decode($INFOR, true);
	print "_GET:";
	print_R($INFOR);
	print "<BR>";


	$INFOR = $redis->hget("TEST_INFO_PAYLOAD", $TIME);
	$INFOR = json_decode($INFOR, true);
	print "PAYLOAD:";
	print_R($INFOR);
	print "<BR>";
	print "<HR>";
}




?>