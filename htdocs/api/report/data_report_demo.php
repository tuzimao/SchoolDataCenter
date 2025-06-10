<?php
/*
* 基础架构: 单点低代码开发平台
* 版权所有: 郑州单点科技软件有限公司
* Email: moodle360@qq.com
* Copyright (c) 2007-2025
* License: 商业授权
*/
header("Content-Type: application/json"); 
require_once('../cors.php');
require_once('../include.inc.php');

//$externalId = 16;

CheckAuthUserLoginStatus();

$USER_ID    = ForSqlInjection($GLOBAL_USER->USER_ID);
$USER_NAME  = ForSqlInjection($GLOBAL_USER->USER_NAME);
$DEPT_ID    = ForSqlInjection($GLOBAL_USER->DEPT_ID);

sleep(1);

$报表页面 = [];
$报表页面['搜索区域'] = [];
$报表页面['搜索区域']['标题'] = "固定资产数据统计";

$sql    = "select 学期名称 as name, 学期名称 as value from data_xueqi order by id desc";
$rs     = $db->Execute($sql);
$rs_a   = $rs->GetArray();
$默认值 = getCurrentXueQi();
$报表页面['搜索区域']['搜索条件'][] = ['name'=>'选择学期', 'sm'=>4, 'type'=>'select', 'field'=>'选择学期', 'default'=> $默认值, 'placeholder'=>'选择学期', 'data'=>$rs_a];

$sql    = "select 系部名称 as name, 系部名称 as value from data_xi order by 系部名称 asc";
$rs     = $db->Execute($sql);
$rs_a   = $rs->GetArray();
$默认值 = $rs_a[0]['name'];
$报表页面['搜索区域']['搜索条件'][] = ['name'=>'选择系部', 'sm'=>4, 'type'=>'select', 'field'=>'选择系部', 'default'=> $默认值, 'placeholder'=>'选择系部', 'data'=>$rs_a];

$sql    = "select 专业名称 as name, 专业名称 as value from data_zhuanye order by 专业名称 asc";
$rs     = $db->Execute($sql);
$rs_a   = $rs->GetArray();
$默认值 = $rs_a[0]['name'];
$报表页面['搜索区域']['搜索条件'][] = ['name'=>'选择专业', 'sm'=>4, 'type'=>'select', 'field'=>'选择专业', 'default'=> $默认值, 'placeholder'=>'选择专业', 'data'=>$rs_a];

$sql    = "select 班级名称 as name, 班级名称 as value from data_banji where (是否毕业='否' or 是否毕业='0') and (find_in_set('$USER_NAME',实习班主任) or find_in_set('$USER_ID',实习班主任) or (班主任用户名='$USER_ID')) order by 班级名称 asc";
$rs     = $db->Execute($sql);
$rs_a   = $rs->GetArray();
$默认值 = $rs_a[0]['name'];
$报表页面['搜索区域']['搜索条件'][] = ['name'=>'我的班级', 'sm'=>4, 'type'=>'select', 'field'=>'我的班级', 'default'=> $默认值, 'placeholder'=>'我的班级', 'data'=>$rs_a];

$sql    = "select 班级名称 as name, 班级名称 as value from data_banji where (是否毕业='否' or 是否毕业='0') order by 班级名称 asc";
$rs     = $db->Execute($sql);
$rs_a   = $rs->GetArray();
$默认值 = $rs_a[0]['name'];
$报表页面['搜索区域']['搜索条件'][] = ['name'=>'选择单个班级', 'sm'=>4, 'type'=>'autocomplete', 'field'=>'选择班级', 'default'=> $默认值, 'placeholder'=>'选择班级', 'data'=>$rs_a];
$报表页面['搜索区域']['搜索条件'][] = ['name'=>'选择多个班级', 'sm'=>12, 'type'=>'autocompletemulti', 'field'=>'选择多个班级', 'default'=> $默认值, 'placeholder'=>'选择多个班级', 'data'=>$rs_a];

$报表页面['搜索区域']['搜索条件'][] = ['name'=>'开始时间', 'sm'=>4, 'type'=>'date1', 'field'=>'开始时间', 'default'=> date('Y-m-d'), 'placeholder'=>'请输入开始时间'];
$报表页面['搜索区域']['搜索条件'][] = ['name'=>'结束时间', 'sm'=>4, 'type'=>'date2', 'field'=>'结束时间', 'default'=> date('Y-m-d', strtotime('+3 days')), 'placeholder'=>'请输入结束时间'];
$报表页面['搜索区域']['搜索条件'][] = ['name'=>'开始月份', 'sm'=>4, 'type'=>'date1', 'field'=>'开始月份', 'default'=> date('Y-m-d'), 'placeholder'=>'请输入开始月份'];
$报表页面['搜索区域']['搜索条件'][] = ['name'=>'结束月份', 'sm'=>4, 'type'=>'date2', 'field'=>'结束月份', 'default'=> date('Y-m-d', strtotime('+3 days')), 'placeholder'=>'请输入结束月份'];
$报表页面['搜索区域']['搜索条件'][] = ['name'=>'固定资产名称', 'sm'=>4, 'type'=>'input', 'field'=>'固定资产名称', 'default'=> '', 'placeholder'=>'固定资产名称'];
$报表页面['搜索区域']['搜索条件'][] = ['name'=>'存放地点', 'sm'=>4, 'type'=>'input', 'field'=>'存放地点', 'default'=> '', 'placeholder'=>'存放地点'];
$报表页面['搜索区域']['搜索条件'][] = ['name'=>'归属班级', 'sm'=>4, 'type'=>'input', 'field'=>'归属班级', 'default'=> '', 'placeholder'=>'归属班级'];
$报表页面['搜索区域']['搜索按钮']   = "开始查询";
$报表页面['搜索区域']['搜索事件']   = "action=search";

$报表页面['数据区域']['头部'][0][]   = ['name'=>'序号', 'col'=>1, 'row'=>2, 'link'=>'', 'wrap'=>'No', 'align'=>'Center'];
$报表页面['数据区域']['头部'][0][]   = ['name'=>'教职工姓名', 'col'=>1, 'row'=>2, 'link'=>'', 'wrap'=>'No', 'align'=>'Center'];
$报表页面['数据区域']['头部'][0][]   = ['name'=>'调课', 'col'=>5, 'row'=>1, 'link'=>'', 'wrap'=>'No', 'align'=>'Center'];
$报表页面['数据区域']['头部'][0][]   = ['name'=>'相互调课', 'col'=>5, 'row'=>1, 'link'=>'', 'wrap'=>'No', 'align'=>'Center'];
$报表页面['数据区域']['头部'][0][]   = ['name'=>'代课', 'col'=>5, 'row'=>1, 'link'=>'', 'wrap'=>'No', 'align'=>'Center'];
$报表页面['数据区域']['头部'][0][]   = ['name'=>'代课(自习课)', 'col'=>1, 'row'=>2, 'link'=>'', 'wrap'=>'No', 'align'=>'Center'];

$报表页面['数据区域']['头部'][1][]   = ['name'=>'公假', 'col'=>1, 'row'=>1, 'link'=>'', 'wrap'=>'No', 'align'=>'Center'];
$报表页面['数据区域']['头部'][1][]   = ['name'=>'病假', 'col'=>1, 'row'=>1, 'link'=>'', 'wrap'=>'No', 'align'=>'Center'];
$报表页面['数据区域']['头部'][1][]   = ['name'=>'事假', 'col'=>1, 'row'=>1, 'link'=>'', 'wrap'=>'No', 'align'=>'Center'];
$报表页面['数据区域']['头部'][1][]   = ['name'=>'因公', 'col'=>1, 'row'=>1, 'link'=>'', 'wrap'=>'No', 'align'=>'Center'];
$报表页面['数据区域']['头部'][1][]   = ['name'=>'因私', 'col'=>1, 'row'=>1, 'link'=>'', 'wrap'=>'No', 'align'=>'Center'];
$报表页面['数据区域']['头部'][1][]   = ['name'=>'公假', 'col'=>1, 'row'=>1, 'link'=>'', 'wrap'=>'No', 'align'=>'Center'];
$报表页面['数据区域']['头部'][1][]   = ['name'=>'病假', 'col'=>1, 'row'=>1, 'link'=>'', 'wrap'=>'No', 'align'=>'Center'];
$报表页面['数据区域']['头部'][1][]   = ['name'=>'事假', 'col'=>1, 'row'=>1, 'link'=>'', 'wrap'=>'No', 'align'=>'Center'];
$报表页面['数据区域']['头部'][1][]   = ['name'=>'因公', 'col'=>1, 'row'=>1, 'link'=>'', 'wrap'=>'No', 'align'=>'Center'];
$报表页面['数据区域']['头部'][1][]   = ['name'=>'因私', 'col'=>1, 'row'=>1, 'link'=>'', 'wrap'=>'No', 'align'=>'Center'];
$报表页面['数据区域']['头部'][1][]   = ['name'=>'公假', 'col'=>1, 'row'=>1, 'link'=>'', 'wrap'=>'No', 'align'=>'Center'];
$报表页面['数据区域']['头部'][1][]   = ['name'=>'病假', 'col'=>1, 'row'=>1, 'link'=>'', 'wrap'=>'No', 'align'=>'Center'];
$报表页面['数据区域']['头部'][1][]   = ['name'=>'事假', 'col'=>1, 'row'=>1, 'link'=>'', 'wrap'=>'No', 'align'=>'Center'];
$报表页面['数据区域']['头部'][1][]   = ['name'=>'因公', 'col'=>1, 'row'=>1, 'link'=>'', 'wrap'=>'No', 'align'=>'Center'];
$报表页面['数据区域']['头部'][1][]   = ['name'=>'因私', 'col'=>1, 'row'=>1, 'link'=>'', 'wrap'=>'No', 'align'=>'Center'];


$报表页面['数据区域']['数据']   = [];

for($i=0;$i<3;$i++)   {
    $报表页面['数据区域']['数据'][$i]['序号']   = $i + 1;
    $报表页面['数据区域']['数据'][$i]['教职工姓名']   = rand(11111111, 99999999);
    $报表页面['数据区域']['数据'][$i]['调课-公假']   = "教职工姓名".rand(11111111, 99999999);
    $报表页面['数据区域']['数据'][$i]['调课-病假']   = "教职工姓名".rand(11111111, 99999999);
    $报表页面['数据区域']['数据'][$i]['调课-事假']   = "教职工姓名".rand(11111111, 99999999);
    $报表页面['数据区域']['数据'][$i]['调课-因公']   = "教职工姓名".rand(11111111, 99999999);
    $报表页面['数据区域']['数据'][$i]['调课-因私']   = "教职工姓名教职工姓名教职工姓名".rand(11111111, 99999999);
    
    $报表页面['数据区域']['数据'][$i]['相互调课-公假']   = rand(11111111, 99999999);
    $报表页面['数据区域']['数据'][$i]['相互调课-病假']   = rand(11111111, 99999999);
    $报表页面['数据区域']['数据'][$i]['相互调课-事假']   = rand(11111111, 99999999);
    $报表页面['数据区域']['数据'][$i]['相互调课-因公']   = rand(11111111, 99999999);
    $报表页面['数据区域']['数据'][$i]['相互调课-因私']   = rand(11111111, 99999999);
    
    $报表页面['数据区域']['数据'][$i]['代课-公假']   = rand(11111111, 99999999);
    $报表页面['数据区域']['数据'][$i]['代课-病假']   = rand(11111111, 99999999);
    $报表页面['数据区域']['数据'][$i]['代课-事假']   = rand(11111111, 99999999);
    $报表页面['数据区域']['数据'][$i]['代课-因公']   = rand(11111111, 99999999);
    $报表页面['数据区域']['数据'][$i]['代课-因私']   = rand(11111111, 99999999);
    
    $报表页面['数据区域']['数据'][$i]['代课(自习课)']   = 1;
}


$报表页面['底部区域']['备注']['标题']   = '底部区域-备注代课-';
$报表页面['底部区域']['备注']['内容']   = '底部区域-备注代课-因私代课-因私代课-因私代课-因私代课-因私代课-因私代课-因私代课-因私代课-因私代课-\n0000000因私代课-因私代课-因私代课-因私代课-因私代课-因私代课-因私代课-因私';

$报表页面['底部区域']['功能按钮']       = ['打印', '导出Excel', '导出Pdf'];

$报表页面['status'] = 'OK';
print json_encode($报表页面);

?>