<?php
header("Content-Type: application/json");
require_once('../cors.php');
require_once('../include.inc.php');

//CheckAuthUserLoginStatus();

$optionsMenuItem = $_GET['optionsMenuItem'];
if($optionsMenuItem=="")  {
    $optionsMenuItem = "最近一月";
}

$TopRightOptions    = [];
$TopRightOptions[]  = ['name'=>'最近一周','selected'=>$optionsMenuItem=='最近一周'?true:false];
$TopRightOptions[]  = ['name'=>'最近一月','selected'=>$optionsMenuItem=='最近一月'?true:false];
$TopRightOptions[]  = ['name'=>'最近三月','selected'=>$optionsMenuItem=='最近三月'?true:false];
$TopRightOptions[]  = ['name'=>'最近半年','selected'=>$optionsMenuItem=='最近半年'?true:false];
$TopRightOptions[]  = ['name'=>'最近一年','selected'=>$optionsMenuItem=='最近一年'?true:false];

$学期       = getCurrentXueQi();

$USER_ID    = ForSqlInjection($GLOBAL_USER->USER_ID);

$图标和颜色 = [];
$图标和颜色['AA']   = ['颜色'=> 'error', '图标'=> 'uil:usd-circle'];
$图标和颜色['BB']   = ['颜色'=> 'success', '图标'=> 'mdi:account-star'];
//$图标和颜色['在线充值'] = ['颜色'=> 'warning', '图标'=> 'mdi:cash-edit'];
//$图标和颜色['在线充值'] = ['颜色'=> 'info', '图标'=> 'drawing-box'];
//$图标和颜色['在线充值'] = ['颜色'=> 'primary', '图标'=> 'worker'];

switch($optionsMenuItem) {
    case '最近一周':
        $whereSql = " and 时间 >= DATE_SUB(CURDATE(), INTERVAL 1 WEEK)";
        break;
    case '最近一月':
        $whereSql = " and 时间 >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)";
        break;
    case '最近三月':
        $whereSql = " and 时间 >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)";
        break;
    case '最近半年':
        $whereSql = " and 时间 >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)";
        break;
    case '最近一年':
        $whereSql = " and 时间 >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)";
        break;
}

//奖杯模块
$sql = "select Count(*) AS NUM from data_dorm_menjin where 1=1 and 宿舍楼='收银'  $whereSql";
$rs = $db->Execute($sql);
$AnalyticsTrophy['Welcome']     = "您好,".$GLOBAL_USER->USER_NAME."!🥳";
$AnalyticsTrophy['SubTitle']    = "宿舍门禁总金额(万元) - " . $optionsMenuItem;
$AnalyticsTrophy['TotalScore']  = $rs->fields['NUM'];
//$AnalyticsTrophy['ViewButton']['name']  = "查看明细";
//$AnalyticsTrophy['ViewButton']['url']   = "/apps/421";
$AnalyticsTrophy['TopRightOptions']     = [];
$AnalyticsTrophy['grid']        = 4;
$AnalyticsTrophy['type']        = "AnalyticsTrophy";
$AnalyticsTrophy['sql']         = $sql;

//按宿舍楼统计积分
$sql = "select 宿舍楼 AS title, Count(*) AS NUM from data_dorm_menjin where 1=1 $whereSql group by 宿舍楼 order by 宿舍楼 asc";
$rs = $db->Execute($sql);
$rs_a = $rs->GetArray();
$Item = [];
$dataMap = [];
$Index = 0;
foreach($rs_a as $Element)   {
    $dataMap[$Element['title']] = ['title'=>$Element['title'],'stats'=>$Element['NUM'],'color'=>$图标和颜色[$Element['title']]['颜色'],'icon'=>$图标和颜色[$Element['title']]['图标']];
    $Index ++;
}
$data = [];
$data[] = $dataMap['AA'];
$data[] = $dataMap['BB'];
$AnalyticsTransactionsCard['Title']       = "宿舍门禁";
$AnalyticsTransactionsCard['SubTitle']    = "按类别统计总金额(万元)";
$AnalyticsTransactionsCard['data']        = $data;
$AnalyticsTransactionsCard['TopRightOptions']      = $TopRightOptions;
$AnalyticsTransactionsCard['grid']                 = 8;
$AnalyticsTransactionsCard['type']                 = "AnalyticsTransactionsCard";
$AnalyticsTransactionsCard['sql']                  = $sql;

//得到最新加分或是扣分的几条记录
$sql = "select 宿舍楼,concat(地点, ' ', DATE_FORMAT(时间, '%m:%d %H:%i'))  as 二级指标, 事件 as 积分分值, 事件 as 积分项目, 房间 from data_dorm_menjin where 1=1 $whereSql and 事件='出' order by 时间 desc limit 5";
$rs = $db->Execute($sql);
$rs_a = $rs->GetArray();
for($i=0;$i<sizeof($rs_a);$i++) {
    $rs_a[$i]['项目图标'] = $图标和颜色[$rs_a[$i]['宿舍楼']]['图标'];
    $rs_a[$i]['图标颜色'] = $图标和颜色[$rs_a[$i]['宿舍楼']]['颜色'];
    $rs_a[$i]['积分分值'] = '-'.$rs_a[$i]['积分分值'];
}
$AnalyticsDepositWithdraw['加分']['Title']             = "收银";
$AnalyticsDepositWithdraw['加分']['TopRightButton']    = ['name'=>'查看所有','url'=>'/apps/421'];
$AnalyticsDepositWithdraw['加分']['data']              = $rs_a;

$sql = "select 宿舍楼,concat(地点, ' ', DATE_FORMAT(时间, '%m:%d %H:%i'))  as 二级指标, 事件 as 积分分值, 事件 as 积分项目, 房间 from data_dorm_menjin where 1=1 $whereSql and 事件='入' order by 时间 desc limit 5";
$rs = $db->Execute($sql);
$rs_a = $rs->GetArray();
for($i=0;$i<sizeof($rs_a);$i++) {
    $rs_a[$i]['项目图标'] = $图标和颜色[$rs_a[$i]['宿舍楼']]['图标'];
    $rs_a[$i]['图标颜色'] = $图标和颜色[$rs_a[$i]['宿舍楼']]['颜色'];
    $rs_a[$i]['积分分值'] = '+'.$rs_a[$i]['积分分值'];
}
$AnalyticsDepositWithdraw['扣分']['Title']              = "充值";
$AnalyticsDepositWithdraw['扣分']['TopRightButton']     = ['name'=>'查看所有','url'=>'/apps/421'];
$AnalyticsDepositWithdraw['扣分']['data']               = $rs_a;
$AnalyticsDepositWithdraw['grid']                       = 8;
$AnalyticsDepositWithdraw['type']                       = "AnalyticsDepositWithdraw";
$AnalyticsDepositWithdraw['sql']                        = $sql;


//设备终端
$colorArray = ['primary','success','warning','info','info'];
$iconArray  = ['mdi:trending-up','mdi:account-outline','mdi:cellphone-link','mdi:currency-usd','mdi:currency-usd','mdi:currency-usd'];
$sql    = "select 姓名, Count(*) AS 积分分值 from data_dorm_menjin where 1=1 $whereSql group by 地点 order by 地点 desc limit 5";
$rs     = $db->Execute($sql);
$rs_a   = $rs->GetArray();
$Item   = [];
$Index  = 0;
for($i=0;$i<sizeof($rs_a);$i++) {
    $rs_a[$i]['图标颜色']   = $colorArray[$i];
    $rs_a[$i]['头像']       = '/images/avatars/'.(($i)+1).'.png';
}
$AnalyticsSalesByCountries['Title']       = "消费最多的设备终端(万元)";
$AnalyticsSalesByCountries['SubTitle']    = "按设备终端统计消费总金额";
$AnalyticsSalesByCountries['data']        = $rs_a;
$AnalyticsSalesByCountries['TopRightOptions']      = $TopRightOptions;
$AnalyticsSalesByCountries['grid']                 = 4;
$AnalyticsSalesByCountries['type']                 = "AnalyticsSalesByCountries";
$AnalyticsSalesByCountries['sql']                  = $sql;


//ApexAreaChart
$sql = "select DATE_FORMAT(时间, '%Y-%m-%d') as 时间, Count(*) AS NUM from data_dorm_menjin where 1=1 $whereSql group by DATE_FORMAT(时间, '%Y-%m-%d') order by 时间 asc";
$rs = $db->Execute($sql);
$rs_a = $rs->GetArray();
$输出数据 = [];
for($i=0;$i<sizeof($rs_a);$i++) {
    $输出数据[$rs_a[$i]['时间']] = $rs_a[$i]['NUM'];
}
$dataY = [];
$dataX = array_keys($输出数据);
$dataY[] = ["name"=>"班级总积分","data"=>array_values($输出数据)];

$ApexAreaChart['Title']       = "食堂每天消费总金额";
$ApexAreaChart['SubTitle']    = "按天统计食堂每天消费总金额(万元)";
$ApexAreaChart['dataX']       = $dataX;
$ApexAreaChart['dataY']       = $dataY;
$ApexAreaChart['sql']         = $sql;
$ApexAreaChart['TopRightOptions']      = $TopRightOptions;
$ApexAreaChart['grid']                  = 8;
$ApexAreaChart['type']                  = "ApexAreaChart";
$ApexAreaChart['sql']                   = $sql;


$ApexLineChart['Title']         = "食堂每天消费总金额";
$ApexLineChart['SubTitle']      = "按天统计食堂每天消费总金额(万元)";
$ApexLineChart['dataX']         = $dataX;
$ApexLineChart['dataY']         = $dataY;
$ApexLineChart['sql']           = $sql;
$ApexLineChart['TopRightOptions']       = $TopRightOptions;
$ApexLineChart['grid']                  = 8;
$ApexLineChart['type']                  = "ApexLineChart";


//AnalyticsWeeklyOverview
$sql = "select DATE_FORMAT(时间, '%Y-%m') AS 月份, Count(*) AS NUM from data_dorm_menjin where 1=1 $whereSql  group by DATE_FORMAT(时间, '%Y-%m') order by 月份 asc";
$rs = $db->Execute($sql);
$rs_a = $rs->GetArray();
$输出数据 = [];
for($i=0;$i<sizeof($rs_a);$i++) {
    $输出数据[$rs_a[$i]['月份']] = $rs_a[$i]['NUM'];
}
$dataY      = [];
$dataX      = array_keys($输出数据);
$dataYItem  = array_values($输出数据);
$dataY[]    = ["name"=>"每月消费总金额","data"=>$dataYItem];

$AnalyticsWeeklyOverview['Title']         = "每月消费总金额";
$AnalyticsWeeklyOverview['SubTitle']      = "每月消费总金额";
$AnalyticsWeeklyOverview['dataX']         = $dataX;
$AnalyticsWeeklyOverview['dataY']         = $dataY;
$AnalyticsWeeklyOverview['sql']           = $sql;
$AnalyticsWeeklyOverview['TopRightOptions']         = $TopRightOptions;

$当月金额 = $dataYItem[sizeof($dataYItem)-1];
$上月金额 = $dataYItem[sizeof($dataYItem)-2];
$AnalyticsWeeklyOverview['BottomText']['Left']      = $当月金额;
$AnalyticsWeeklyOverview['BottomText']['Right']     = "上个月为".$上月金额."";
if($上月金额 > 0 && $当月金额 > $上月金额)  {
    $增加比例 = intval(($当月金额 - $上月金额)* 100 / $上月金额);
    $AnalyticsWeeklyOverview['BottomText']['Right'] .= ", 比上月增加".$增加比例."%";
}
if($上月金额 > 0 && $当月金额 < $上月金额)  {
    $增加比例 = intval(($当月金额 - $上月金额)* 100 / $上月金额);
    $AnalyticsWeeklyOverview['BottomText']['Right'] .= ", 比上月下降".$增加比例."%";
}

$AnalyticsWeeklyOverview['ViewButton']['name']  = "明细";
$AnalyticsWeeklyOverview['ViewButton']['url']   = "/apps/421";
$AnalyticsWeeklyOverview['grid']                = 4;
$AnalyticsWeeklyOverview['type']                = "AnalyticsWeeklyOverview";
$AnalyticsWeeklyOverview['sql']                 = $sql;

//AnalyticsPerformance
$sql = "select 宿舍楼,Count(*) AS NUM from data_dorm_menjin where 1=1 $whereSql group by 宿舍楼 order by 宿舍楼 asc";
$rs = $db->Execute($sql);
$rs_a = $rs->GetArray();
$输出数据 = [];
for($i=0;$i<sizeof($rs_a);$i++) {
    $输出数据[$rs_a[$i]['宿舍楼']] = $rs_a[$i]['NUM'];
}
$dataY = [];
$dataX = array_keys($输出数据);
$dataY[] = ["name"=>"宿舍楼","data"=>array_values($输出数据)];

$AnalyticsPerformance['Title']       = "宿舍楼统计宿舍门禁";
$AnalyticsPerformance['SubTitle']    = "按宿舍楼统计宿舍门禁";
$AnalyticsPerformance['dataX']       = $dataX;
$AnalyticsPerformance['dataY']       = $dataY;
$AnalyticsPerformance['sql']         = $sql;
$AnalyticsPerformance['colors']      = ['#fdd835','#32baff','#00d4bd','#7367f0','#FFA1A1'];
$AnalyticsPerformance['TopRightOptions']      = $TopRightOptions;
$AnalyticsPerformance['grid']                 = 4;
$AnalyticsPerformance['type']                 = "AnalyticsPerformance";
$AnalyticsPerformance['sql']                  = $sql;


//ApexDonutChart
$sql = "select 宿舍楼,Count(*) AS NUM from data_dorm_menjin where 1=1 $whereSql group by 宿舍楼 order by 宿舍楼 asc";
$rs = $db->Execute($sql);
$rs_a = $rs->GetArray();
$输出数据 = [];
for($i=0;$i<sizeof($rs_a);$i++) {
    $输出数据[$rs_a[$i]['宿舍楼']] = intval($rs_a[$i]['NUM']);
}
$dataY = [];
$dataX = array_keys($输出数据);
$dataY[] = ["name"=>"班级总积分百分比","data"=>array_values($输出数据)];

$ApexDonutChart['Title']       = "宿舍楼统计消费金额";
$ApexDonutChart['SubTitle']    = "按宿舍楼统计消费金额的百分比";
$ApexDonutChart['dataX']       = $dataX;
$ApexDonutChart['dataY']       = $dataY;
$ApexDonutChart['sql']         = $sql;
$ApexDonutChart['colors']      = ['#fdd835','#32baff','#00d4bd','#7367f0','#FFA1A1'];
$ApexDonutChart['TopRightOptions']      = $TopRightOptions;
$ApexDonutChart['grid']                 = 4;
$ApexDonutChart['type']                 = "ApexDonutChart";
$ApexDonutChart['sql']                  = $sql;



//ApexRadialBarChart
$sql = "select 宿舍楼,Count(*) AS NUM from data_dorm_menjin where 1=1 $whereSql group by 宿舍楼 order by 宿舍楼 asc";
$rs = $db->Execute($sql);
$rs_a = $rs->GetArray();
$输出数据 = [];
for($i=0;$i<sizeof($rs_a);$i++) {
    $输出数据[$rs_a[$i]['宿舍楼']] = intval($rs_a[$i]['NUM']);
}
$dataY = [];
$dataX = array_keys($输出数据);
$dataY[] = ["name"=>"宿舍门禁金额百分比","data"=>array_values($输出数据)];

$ApexRadialBarChart['Title']       = "宿舍楼统计消费总金额";
$ApexRadialBarChart['SubTitle']    = "按宿舍楼统计消费总金额";
$ApexRadialBarChart['dataX']       = $dataX;
$ApexRadialBarChart['dataY']       = $dataY;
$ApexRadialBarChart['sql']         = $sql;
$ApexRadialBarChart['colors']      = ['#fdd835','#32baff','#00d4bd','#7367f0','#FFA1A1'];
$ApexRadialBarChart['TopRightOptions']      = $TopRightOptions;
$ApexRadialBarChart['grid']                 = 4;
$ApexRadialBarChart['type']                 = "ApexRadialBarChart";
$ApexRadialBarChart['sql']                  = $sql;


$RS                             = [];
$RS['defaultValue']             = $optionsMenuItem;
$RS['optionsMenuItem']          = $optionsMenuItem;

$RS['charts'][]       = $AnalyticsTrophy;
$RS['charts'][]       = $AnalyticsTransactionsCard;
$RS['charts'][]       = $AnalyticsSalesByCountries;
$RS['charts'][]       = $AnalyticsDepositWithdraw;
$RS['charts'][]       = $AnalyticsWeeklyOverview;
//$RS['charts'][]       = $ApexAreaChart;
$RS['charts'][]       = $ApexLineChart;
$RS['charts'][]       = $AnalyticsPerformance;
$RS['charts'][]       = $ApexDonutChart;
$RS['charts'][]       = $ApexRadialBarChart;


print_R(json_encode($RS));



?>
