<?php
//禁止当前文件直接在URL中访问
$CurrentUrlFileName = basename($_SERVER['PHP_SELF']);
$ForbiddenAccessUrlList = ['data_enginee_flow_lib_report_default.php'];
if(in_array($CurrentUrlFileName, $ForbiddenAccessUrlList)) exit;

$currentReport = ForSqlInjection($_GET['currentReport']);
if( $_GET['action']=="report_default" && $currentReport!="")  {
    if($currentReport!="Report_1"&&$currentReport!="Report_2"&&$currentReport!="Report_3"&&$currentReport!="Report_4"&&$currentReport!="Report_5"&&$currentReport!="Report_6"&&$currentReport!="Report_7"&&$currentReport!="Report_8")   {
        $RS = [];
        $RS['status'] = "ERROR";
        $RS['msg'] = __("Error Id Value");
        $RS['_GET'] = $_GET;
        $RS['_POST'] = $_POST;
        print_R(EncryptApiData($RS, $GLOBAL_USER));
        exit;
    }

    $RS = getReportStructureDataSingle($currentReport);
    print_R(EncryptApiData($RS, $GLOBAL_USER));
    exit;
}

function getReportStructureData() {
    global $db, $TableName, $Step, $GLOBAL_USER, $SettingMap, $MetaColumnNames;

    //functionNameIndividual
    $functionNameIndividual = "plugin_".$TableName."_".$Step."_report_default";
    if(function_exists($functionNameIndividual))  {
        $functionNameIndividual($id);
    }

    $ReportData                         = [];
    $ReportButtonList = [];
    if($SettingMap['EnableReport'] == 'Yes' && $SettingMap['Report_1_Name'] != '')   {
        $ReportData['Init_Action_Value']    = 'report_default'; //只能放到这个位置
        $ReportButtonList[]             = ['name'=>$SettingMap['Report_1_Name'], 'code'=>'Report_1'];
    }
    if($SettingMap['EnableReport'] == 'Yes' && $SettingMap['Report_2_Name'] != '')   {
        $ReportData['Init_Action_Value']    = 'report_default'; //只能放到这个位置
        $ReportButtonList[]             = ['name'=>$SettingMap['Report_2_Name'], 'code'=>'Report_2'];
    }
    $ReportData['ButtonList'] = $ReportButtonList;
    return $ReportData;
}

function getReportStructureDataSingle($currentReport) {
    global $db, $TableName, $Step, $GLOBAL_USER, $SettingMap, $MetaColumnNames;

    //functionNameIndividual
    $functionNameIndividual = "plugin_".$TableName."_".$Step."_report_default";
    if(function_exists($functionNameIndividual))  {
        $functionNameIndividual($id);
    }

    $sql    = "select * from `$TableName` where id = '$id'";
    $rsf    = $db->Execute($sql);
    $data   = $rsf->fields;

    $报表页面 = [];
    $报表页面['搜索区域'] = [];
    $报表页面['搜索区域']['标题'] = $SettingMap['Report_1_Name'];

    //主要数据输出
    $USER_ID    = ForSqlInjection($GLOBAL_USER->USER_ID);
    $USER_NAME  = ForSqlInjection($GLOBAL_USER->USER_NAME);
    $DEPT_ID    = ForSqlInjection($GLOBAL_USER->DEPT_ID);

    $Report_1_LeftColumnDefine      = $SettingMap['Report_1_LeftColumnDefine'];
    $Report_1_LeftColumnField       = $SettingMap['Report_1_LeftColumnField'];
    $Report_1_LeftColumnDataShow    = $SettingMap['Report_1_LeftColumnDataShow'];
    //判断统计字段是否为数据表字段
    if(!in_array($Report_1_LeftColumnField, $MetaColumnNames) && $Report_1_LeftColumnField != '') {
        $RS = [];
        $RS['status'] = "ERROR";
        $RS['msg'] = "Report_1_LeftColumnField not a field in $TableName";
        print_R(EncryptApiData($RS, $GLOBAL_USER));
        exit;
    }

    if($Report_1_LeftColumnField != "" && $$Report_1_LeftColumnField != "无")   {
        //生成左侧区域数据
        $左侧区域数据 = [];
        switch($Report_1_LeftColumnDefine) {
            case '班级/专业/系部':
                $报表页面['数据区域']['头部'][0][]   = ['name'=>'序号', 'col'=>1, 'row'=>2, 'link'=>'', 'wrap'=>'No', 'align'=>'Center'];
                $报表页面['数据区域']['头部'][0][]   = ['name'=>'班级', 'col'=>1, 'row'=>2, 'link'=>'', 'wrap'=>'No', 'align'=>'Center'];
                $报表页面['数据区域']['头部'][0][]   = ['name'=>'专业', 'col'=>1, 'row'=>2, 'link'=>'', 'wrap'=>'No', 'align'=>'Center'];
                $报表页面['数据区域']['头部'][0][]   = ['name'=>'系部', 'col'=>1, 'row'=>2, 'link'=>'', 'wrap'=>'No', 'align'=>'Center'];
                $左侧区域数据['cols'] = 3;
                $sql        = "select 班级名称 as 班级, 所属专业 as 专业, 所属系部 as 系部 from data_banji where (是否毕业='否' or 是否毕业='0')";
                $rs         = $db->Execute($sql);
                $rs_a       = $rs->GetArray();
                $Counter    = 0;
                foreach($rs_a as $Line) {
                    $左侧区域数据['data'][] = $Line;
                    $Counter ++;
                }
                break;
            case '系部/专业/班级':
                break;
        }

        //报表头部数组
        $报表头部数组       = [];

        //右侧数据区域字段1
        $Report_1_DataColumn_1_Name = $SettingMap['Report_1_DataColumn_1_Name'];
        //判断统计字段是否为数据表字段
        if(!in_array($Report_1_DataColumn_1_Name, $MetaColumnNames) && $Report_1_DataColumn_1_Name != '') {
            $RS = [];
            $RS['status'] = "ERROR";
            $RS['msg'] = "$Report_1_DataColumn_1_Name not a field in $TableName";
            print_R(EncryptApiData($RS, $GLOBAL_USER));
            exit;
        }
        $右侧数据区域字段1  = [];
        $sql        = "select count(*) AS NUM, $Report_1_LeftColumnField, $Report_1_DataColumn_1_Name from $TableName group by $Report_1_LeftColumnField, $Report_1_DataColumn_1_Name order by $Report_1_DataColumn_1_Name asc";
        $rs         = $db->Execute($sql);
        $rs_a       = $rs->GetArray();
        //print_R($rs_a);
        foreach($rs_a as $Line) {
            $报表头部数组[$Report_1_DataColumn_1_Name][$Line[$Report_1_DataColumn_1_Name]] = $Line[$Report_1_DataColumn_1_Name];
            $右侧数据区域字段1[$Line[$Report_1_LeftColumnField]][$Report_1_DataColumn_1_Name][$Line[$Report_1_DataColumn_1_Name]] = $Line['NUM'];
        }
        
        $右侧数据区域字段1_列名 = array_keys($报表头部数组[$Report_1_DataColumn_1_Name]);
        foreach($右侧数据区域字段1_列名 as $右侧数据区域字段1_列名Value) {
            $报表页面['数据区域']['头部'][1][]   = ['name'=>$右侧数据区域字段1_列名Value, 'col'=>1, 'row'=>1, 'link'=>'', 'wrap'=>'No', 'align'=>'Center'];
        }

        $报表页面['数据区域']['头部'][0][]   = ['name'=>$Report_1_DataColumn_1_Name, 'col'=>count($右侧数据区域字段1_列名), 'row'=>1, 'link'=>'', 'wrap'=>'No', 'align'=>'Center'];

        //print_R($报表页面); print $Report_1_LeftColumnDefine; print $Report_1_LeftColumnField; exit;

    } //$Report_1_LeftColumnField != "" && $$Report_1_LeftColumnField != "无"

/*
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
    $报表页面['搜索区域']['搜索条件'][] = ['name'=>'固定资产名称', 'sm'=>4, 'type'=>'input', 'field'=>'固定资产名称', 'default'=> '000', 'placeholder'=>'固定资产名称'];
    $报表页面['搜索区域']['搜索条件'][] = ['name'=>'存放地点', 'sm'=>4, 'type'=>'input', 'field'=>'存放地点', 'default'=> '111', 'placeholder'=>'存放地点'];
    $报表页面['搜索区域']['搜索条件'][] = ['name'=>'归属班级', 'sm'=>4, 'type'=>'input', 'field'=>'归属班级', 'default'=> '222', 'placeholder'=>'归属班级'];
    $报表页面['搜索区域']['搜索按钮']   = "开始查询";
    $报表页面['搜索区域']['搜索事件']   = "action=search";
*/
    $报表页面['数据区域']['数据']   = [];

    $左侧数组 = $左侧区域数据['data'];
    for($i=0;$i<sizeof($左侧数组);$i++)   {
        $报表页面['数据区域']['数据'][$i]['序号']   = $i + 1;
        $左侧单元 = $左侧数组[$i];
        foreach($左侧单元 as $左侧单元Item => $左侧单元Value)   {
            $报表页面['数据区域']['数据'][$i][$左侧单元Item]    = $左侧单元Value;
        }
        $临时变量 = array_values($报表页面['数据区域']['数据'][$i]);
        $每行的主KEy = $临时变量[1];
        if(sizeof($右侧数据区域字段1) > 0) {
            foreach($右侧数据区域字段1_列名 as $右侧数据区域字段1_列名_Value) {
                $报表页面['数据区域']['数据'][$i][$右侧数据区域字段1_列名_Value]    = $右侧数据区域字段1[$每行的主KEy][$Report_1_DataColumn_1_Name][$右侧数据区域字段1_列名_Value];
            }
        }
    }
    //print_R($报表页面);
    //print_R($右侧数据区域字段1);


    $报表页面['底部区域']['备注']['标题']   = '底部区域-备注代课-';
    $报表页面['底部区域']['备注']['内容']   = '底部区域-备注代课-因私代课-因私代课-因私代课-因私代课-因私代课-因私代课-因私代课-因私代课-因私代课-\n0000000因私代课-因私代课-因私代课-因私代课-因私代课-因私代课-因私代课-因私';

    $报表页面['底部区域']['功能按钮']       = ['打印', '导出Excel', '导出Pdf'];

    $报表页面['status'] = 'OK';

    return $报表页面;
}
?>