<?php
/*
* 基础架构: 单点低代码开发平台
* 版权所有: 郑州单点科技软件有限公司
* Email: moodle360@qq.com
* Copyright (c) 2007-2025
* License: 商业授权
*/
header("Content-Type: application/json");
require_once('cors.php');
require_once('include.inc.php');

//$externalId = 16;

//Get Table Infor
$sql        = "select * from data_interface";
$rs         = $db->Execute($sql);
$Info       = $rs->fields;

print_R(json_encode($Info, true));

?>
