<?php
//复杂的业务逻辑放入单独的文件进行处理

//学生请假相关工作流插件
require_once('plugins_qingjia.php');

//学籍异动相关工作流插件, 复学, 休学, 调班, 退学
require_once('plugins_student.php');

//老师调代课, 课表
require_once('plugins_schedule.php');

//老师调代课, 课表
require_once('plugins_fixedasset.php');




?>