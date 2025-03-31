<?php
/*
* 基础架构: 单点低代码开发平台
* 版权所有: 郑州单点科技软件有限公司
* Email: moodle360@qq.com
* Copyright (c) 2007-2025
* License: 商业授权
*/
header("Content-Type: application/json");
$TIME_BEGIN = time();
require_once('cors.php');
require_once('include.inc.php');


global $WholePageModel;

$WholePageModel = "Workflow";

if($_GET['FlowId'] != "")       $FlowId = intval(DecryptID($_GET['FlowId']));       //edit_default
if($_POST['FlowId'] != "")      $FlowId = intval(DecryptID($_POST['FlowId']));      //edit_default_data

//$_GET['id'] = "alZJdVRHNHBOQnZHNEVCSUptM3dYZ3x8OjpBQnJzY1lLdDdfQWZ5RDh6THoyN2lRfHw|";

require_once('lib/data_enginee_flow_lib.php');

?>