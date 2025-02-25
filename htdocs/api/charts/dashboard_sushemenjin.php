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

//得到最新加分或是扣分的几条记录
$sql = "select 宿舍楼,concat(地点, ' ', DATE_FORMAT(时间, '%m-%d %H:%i'))  as 二级指标, 事件 as 积分分值, concat(姓名, ' ', 班级) as 积分项目, 房间 from data_dorm_menjin where 1=1 $whereSql and 事件='出' order by 时间 desc limit 5";
$rs = $db->Execute($sql);
$rs_a = $rs->GetArray();
for($i=0;$i<sizeof($rs_a);$i++) {
    $rs_a[$i]['项目图标'] = 'ion:log-out-outline';
    $rs_a[$i]['图标颜色'] = 'error';
    $rs_a[$i]['积分分值'] = $rs_a[$i]['积分分值'];
}
$AnalyticsDepositWithdraw['加分']['Title']             = "出";
$AnalyticsDepositWithdraw['加分']['TopRightButton']    = [];
$AnalyticsDepositWithdraw['加分']['data']              = $rs_a;

$sql = "select 宿舍楼,concat(地点, ' ', DATE_FORMAT(时间, '%m-%d %H:%i'))  as 二级指标, 事件 as 积分分值, concat(姓名, ' ', 班级) as 积分项目, 房间 from data_dorm_menjin where 1=1 $whereSql and 事件='入' order by 时间 desc limit 5";
$rs = $db->Execute($sql);
$rs_a = $rs->GetArray();
for($i=0;$i<sizeof($rs_a);$i++) {
    $rs_a[$i]['项目图标'] = 'ion:log-in-outline';
    $rs_a[$i]['图标颜色'] = 'success';
    $rs_a[$i]['积分分值'] = $rs_a[$i]['积分分值'];
}
$AnalyticsDepositWithdraw['扣分']['Title']              = "入";
$AnalyticsDepositWithdraw['扣分']['TopRightButton']     = [];
$AnalyticsDepositWithdraw['扣分']['data']               = $rs_a;
$AnalyticsDepositWithdraw['grid']                       = 8;
$AnalyticsDepositWithdraw['type']                       = "AnalyticsDepositWithdraw";
$AnalyticsDepositWithdraw['sql']                        = $sql;


//设备终端
$colorArray = ['primary','success','warning','info','info'];
$iconArray  = ['mdi:trending-up','mdi:account-outline','mdi:cellphone-link','mdi:currency-usd','mdi:currency-usd','mdi:currency-usd'];
$sql    = "select 地点 as 姓名, Count(*) AS 积分分值 from data_dorm_menjin where 1=1 $whereSql group by 地点 order by 地点 desc limit 5";
$rs     = $db->Execute($sql);
$rs_a   = $rs->GetArray();
$Item   = [];
$Index  = 0;
for($i=0;$i<sizeof($rs_a);$i++) {
    $rs_a[$i]['图标颜色']   = $colorArray[$i];
    $rs_a[$i]['头像']       = '/images/avatars/'.(($i)+1).'.png';
}
$AnalyticsSalesByCountries['Title']       = "出入最多的闸机";
$AnalyticsSalesByCountries['SubTitle']    = "";
$AnalyticsSalesByCountries['data']        = $rs_a;
$AnalyticsSalesByCountries['TopRightOptions']      = $TopRightOptions;
$AnalyticsSalesByCountries['grid']                 = 4;
$AnalyticsSalesByCountries['type']                 = "AnalyticsSalesByCountries";
$AnalyticsSalesByCountries['sql']                  = $sql;


//ApexLineChart
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


$ApexLineChart['Title']         = "每天宿舍门禁闸机通过次数";
$ApexLineChart['SubTitle']      = "按天统计每天宿舍门禁闸机通过次数";
$ApexLineChart['dataX']         = $dataX;
$ApexLineChart['dataY']         = $dataY;
$ApexLineChart['sql']           = $sql;
$ApexLineChart['TopRightOptions']       = $TopRightOptions;
$ApexLineChart['grid']                  = 8;
$ApexLineChart['type']                  = "ApexLineChart";



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

$ApexDonutChart['Title']       = "宿舍楼统计出入次数";
$ApexDonutChart['SubTitle']    = "按宿舍楼统计出入次数的百分比";
$ApexDonutChart['dataX']       = $dataX;
$ApexDonutChart['dataY']       = $dataY;
$ApexDonutChart['sql']         = $sql;
$ApexDonutChart['colors']      = ['#fdd835','#32baff','#00d4bd','#7367f0','#FFA1A1'];
$ApexDonutChart['TopRightOptions']      = $TopRightOptions;
$ApexDonutChart['grid']                 = 4;
$ApexDonutChart['type']                 = "ApexDonutChart";
$ApexDonutChart['sql']                  = $sql;


$RS                             = [];
$RS['defaultValue']             = $optionsMenuItem;
$RS['optionsMenuItem']          = $optionsMenuItem;

$RS['charts'][]       = $AnalyticsSalesByCountries;
$RS['charts'][]       = $AnalyticsDepositWithdraw;
$RS['charts'][]       = $ApexLineChart;
$RS['charts'][]       = $ApexDonutChart;


print_R(json_encode($RS));



?>
