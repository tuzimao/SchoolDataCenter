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
    global $db, $Step, $GLOBAL_USER, $SettingMap, $MetaColumnNames;

    $TableName = $SettingMap['Report_1_TableName'];
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
    if($SettingMap['EnableReport'] == 'Yes' && $SettingMap['Report_3_Name'] != '')   {
        $ReportData['Init_Action_Value']    = 'report_default'; //只能放到这个位置
        $ReportButtonList[]             = ['name'=>$SettingMap['Report_3_Name'], 'code'=>'Report_3'];
    }
    if($SettingMap['EnableReport'] == 'Yes' && $SettingMap['Report_4_Name'] != '')   {
        $ReportData['Init_Action_Value']    = 'report_default'; //只能放到这个位置
        $ReportButtonList[]             = ['name'=>$SettingMap['Report_4_Name'], 'code'=>'Report_4'];
    }
    if($SettingMap['EnableReport'] == 'Yes' && $SettingMap['Report_5_Name'] != '')   {
        $ReportData['Init_Action_Value']    = 'report_default'; //只能放到这个位置
        $ReportButtonList[]             = ['name'=>$SettingMap['Report_5_Name'], 'code'=>'Report_5'];
    }
    if($SettingMap['EnableReport'] == 'Yes' && $SettingMap['Report_6_Name'] != '')   {
        $ReportData['Init_Action_Value']    = 'report_default'; //只能放到这个位置
        $ReportButtonList[]             = ['name'=>$SettingMap['Report_6_Name'], 'code'=>'Report_6'];
    }
    if($SettingMap['EnableReport'] == 'Yes' && $SettingMap['Report_7_Name'] != '')   {
        $ReportData['Init_Action_Value']    = 'report_default'; //只能放到这个位置
        $ReportButtonList[]             = ['name'=>$SettingMap['Report_7_Name'], 'code'=>'Report_7'];
    }
    if($SettingMap['EnableReport'] == 'Yes' && $SettingMap['Report_8_Name'] != '')   {
        $ReportData['Init_Action_Value']    = 'report_default'; //只能放到这个位置
        $ReportButtonList[]             = ['name'=>$SettingMap['Report_8_Name'], 'code'=>'Report_8'];
    }
    $ReportData['ButtonList'] = $ReportButtonList;
    return $ReportData;
}

function getReportStructureDataSingle($currentReport) {
    global $db, $Step, $GLOBAL_USER, $SettingMap, $MetaColumnNames;

    $TableName = $SettingMap[$currentReport.'_TableName'];

    $payload        = file_get_contents('php://input');
    $_POST          = json_decode($payload,true);

    //functionNameIndividual
    $functionNameIndividual = "plugin_".$TableName."_".$Step."_report_default";
    if(function_exists($functionNameIndividual))  {
        $functionNameIndividual($id);
    }

    $报表页面 = [];
    $报表页面['搜索区域'] = [];
    $报表页面['搜索区域']['标题'] = $SettingMap[$currentReport.'_Name'];

    //主要数据输出
    $USER_ID    = ForSqlInjection($GLOBAL_USER->USER_ID);
    $USER_NAME  = ForSqlInjection($GLOBAL_USER->USER_NAME);
    $DEPT_ID    = ForSqlInjection($GLOBAL_USER->DEPT_ID);

    $Report_X_LeftColumnDefine      = $SettingMap[$currentReport.'_LeftColumnDefine'];
    $Report_X_LeftColumnField       = $SettingMap[$currentReport.'_LeftColumnField'];
    $Report_X_LeftColumnDataShow    = $SettingMap[$currentReport.'_LeftColumnDataShow'];
    //判断统计字段是否为数据表字段
    if(!in_array($Report_X_LeftColumnField, $MetaColumnNames) && $Report_X_LeftColumnField != '' && $Report_X_LeftColumnField != 'None') {
        $RS = [];
        $RS['status'] = "ERROR";
        $RS['msg'] = "Report_X_LeftColumnField not a field in $TableName";
        print_R(EncryptApiData($RS, $GLOBAL_USER));
        exit;
    }

    $sql    = "select ShowType, FieldName from form_formfield where FormName = '$TableName'";
    $rs     = $db->Execute($sql);
    $rs_a   = $rs->GetArray();
    $字段的显示类型 = [];
    foreach($rs_a as $Item)  {
        $字段的显示类型[$Item['FieldName']] = $Item['ShowType'];
    }
    //print_R($字段的显示类型);
    
    //处理搜索时的SQL条件过滤
    $WhereSql = "";
    for($X=1;$X<=6;$X++)    {
        $字段名称 = $SettingMap[$currentReport.'_SearchField_'.$X];
        if(in_array($字段名称, $MetaColumnNames) && $字段名称 != '') {
            $字段类型           = $字段的显示类型[$字段名称];
            $字段类型Edit       = returntablefield("form_formfield_showtype","`Name`",$字段类型,"Edit")['Edit'];
            $字段类型EditArray  = explode(':', $字段类型Edit);
            $字段类型2          = $字段类型EditArray[0];
            if($字段类型 == 'Input:input') {
                $字段类型2  = 'input';
            }
            $POST字段的值   = ForSqlInjection($_POST[$字段名称]);
            //print $POST字段的值; print $字段名称; print $字段类型2.'\n'; 
            switch($字段类型2) {
                case 'input':
                    if($POST字段的值 == "全部数据") {
                        $WhereSql .= "";
                    }
                    else if($POST字段的值 == "空值") {
                        $WhereSql .= " and $字段名称 = '' ";
                    }
                    else if($POST字段的值 != "") {
                        $WhereSql .= " and $字段名称 like '%".$POST字段的值."%' ";
                    }
                    break;
                case 'autocomplete':
                case 'tablefilter':
                case 'tablefiltercolor':
                case 'radiofilter':
                case 'radiofiltercolor':
                    if($POST字段的值 == "全部数据") {
                        $WhereSql .= "";
                    }
                    else if($POST字段的值 == "空值") {
                        $WhereSql .= " and $字段名称 = '' ";
                    }
                    else if($POST字段的值 != "") {
                        $WhereSql .= " and $字段名称 = '".$POST字段的值."' ";
                    }
                    break;
                case 'autocompletemulti':
                    if($POST字段的值 == "全部数据") {
                        $WhereSql .= "";
                    }
                    else if($POST字段的值 == "空值") {
                        $WhereSql .= " and $字段名称 = '' ";
                    }
                    else if($POST字段的值 != "") {
                        $POST字段的值Array = explode(',', $POST字段的值);
                        $WhereSql .= " and $字段名称 in ('".join("','", $POST字段的值Array)."') ";
                    }
                    break;
            }
        }
    }

    //生成左侧区域数据
    $左侧区域数据 = [];
    if($Report_X_LeftColumnField != "" && $$Report_X_LeftColumnField != "无")   {
        $右侧数据关联字段  = '';
        switch($Report_X_LeftColumnDefine)  {
            case '班级/专业/系部':
                $右侧数据关联字段  = '班级';
                $报表页面['数据区域']['头部'][0][]   = ['name'=>'序号', 'col'=>1, 'row'=>2, 'link'=>'', 'wrap'=>'No', 'align'=>'Center'];
                $报表页面['数据区域']['头部'][0][]   = ['name'=>'班级', 'col'=>1, 'row'=>2, 'link'=>'', 'wrap'=>'No', 'align'=>'Center'];
                $报表页面['数据区域']['头部'][0][]   = ['name'=>'专业', 'col'=>1, 'row'=>2, 'link'=>'', 'wrap'=>'No', 'align'=>'Center'];
                $报表页面['数据区域']['头部'][0][]   = ['name'=>'系部', 'col'=>1, 'row'=>2, 'link'=>'', 'wrap'=>'No', 'align'=>'Center'];
                $sql        = "select 班级名称 as 班级, 所属专业 as 专业, 所属系部 as 系部 from data_banji where (是否毕业='否' or 是否毕业='0')";
                $rs         = $db->Execute($sql);
                $rs_a       = $rs->GetArray();
                $Counter    = 0;
                foreach($rs_a as $Line) {
                    $左侧区域数据[] = $Line;
                    $Counter ++;
                }
                break;
            case '系部/专业/班级':
                $右侧数据关联字段  = '班级';
                $报表页面['数据区域']['头部'][0][]   = ['name'=>'序号', 'col'=>1, 'row'=>2, 'link'=>'', 'wrap'=>'No', 'align'=>'Center'];
                $报表页面['数据区域']['头部'][0][]   = ['name'=>'系部', 'col'=>1, 'row'=>2, 'link'=>'', 'wrap'=>'No', 'align'=>'Center'];
                $报表页面['数据区域']['头部'][0][]   = ['name'=>'专业', 'col'=>1, 'row'=>2, 'link'=>'', 'wrap'=>'No', 'align'=>'Center'];
                $报表页面['数据区域']['头部'][0][]   = ['name'=>'班级', 'col'=>1, 'row'=>2, 'link'=>'', 'wrap'=>'No', 'align'=>'Center'];
                $sql        = "select 班级名称 as 班级, 所属专业 as 专业, 所属系部 as 系部 from data_banji where (是否毕业='否' or 是否毕业='0') order by 所属系部, 所属专业, 班级名称";
                $rs         = $db->Execute($sql);
                $rs_a       = $rs->GetArray();
                $Counter    = 0;
                $组织结构    = [];
                foreach($rs_a as $Line) {
                    $左侧区域数据[] = $Line;
                    $Counter ++;
                    $组织结构[$Line['系部']][$Line['专业']][] = $Line['班级'];
                }
                break;
            case '用户名/姓名/部门':
                $右侧数据关联字段  = '用户名';
                $报表页面['数据区域']['头部'][0][]   = ['name'=>'序号', 'col'=>1, 'row'=>2, 'link'=>'', 'wrap'=>'No', 'align'=>'Center'];
                $报表页面['数据区域']['头部'][0][]   = ['name'=>'用户名', 'col'=>1, 'row'=>2, 'link'=>'', 'wrap'=>'No', 'align'=>'Center'];
                $报表页面['数据区域']['头部'][0][]   = ['name'=>'姓名', 'col'=>1, 'row'=>2, 'link'=>'', 'wrap'=>'No', 'align'=>'Center'];
                $报表页面['数据区域']['头部'][0][]   = ['name'=>'部门', 'col'=>1, 'row'=>2, 'link'=>'', 'wrap'=>'No', 'align'=>'Center'];
                $sql        = "select USER_ID as 用户名, USER_NAME as 姓名, DEPT_NAME as 部门 from data_user, data_department where data_user.DEPT_ID = data_department.id and data_user.NOT_LOGIN = '0' order by data_user.DEPT_ID, data_user.USER_ID";
                $rs         = $db->Execute($sql);
                $rs_a       = $rs->GetArray();
                $Counter    = 0;
                foreach($rs_a as $Line) {
                    $左侧区域数据[] = $Line;
                    $Counter ++;
                }
                break;
            case '部门/用户名/姓名':
                $右侧数据关联字段  = '用户名';
                $报表页面['数据区域']['头部'][0][]   = ['name'=>'序号', 'col'=>1, 'row'=>2, 'link'=>'', 'wrap'=>'No', 'align'=>'Center'];
                $报表页面['数据区域']['头部'][0][]   = ['name'=>'用户名', 'col'=>1, 'row'=>2, 'link'=>'', 'wrap'=>'No', 'align'=>'Center'];
                $报表页面['数据区域']['头部'][0][]   = ['name'=>'姓名', 'col'=>1, 'row'=>2, 'link'=>'', 'wrap'=>'No', 'align'=>'Center'];
                $报表页面['数据区域']['头部'][0][]   = ['name'=>'部门', 'col'=>1, 'row'=>2, 'link'=>'', 'wrap'=>'No', 'align'=>'Center'];
                $sql        = "select USER_ID as 用户名, USER_NAME as 姓名, DEPT_NAME as 部门 from data_user, data_department where data_user.DEPT_ID = data_department.id and data_user.NOT_LOGIN = '0' order by data_user.DEPT_ID, data_user.USER_ID";
                $rs         = $db->Execute($sql);
                $rs_a       = $rs->GetArray();
                $Counter    = 0;
                foreach($rs_a as $Line) {
                    $左侧区域数据[] = $Line;
                    $Counter ++;
                }
                break;
            case '动态数据做为左侧列':
                $右侧数据关联字段  = $SettingMap[$currentReport.'_LeftColumnField'];
                $报表页面['数据区域']['头部'][0][]   = ['name'=>'序号', 'col'=>1, 'row'=>2, 'link'=>'', 'wrap'=>'No', 'align'=>'Center'];
                $报表页面['数据区域']['头部'][0][]   = ['name'=>$右侧数据关联字段, 'col'=>1, 'row'=>2, 'link'=>'', 'wrap'=>'No', 'align'=>'Center'];
                $sql        = "select distinct $右侧数据关联字段 from $TableName where 1=1 order by $右侧数据关联字段 asc";
                $rs         = $db->Execute($sql);
                $rs_a       = $rs->GetArray();
                $Counter    = 0;
                foreach($rs_a as $Line) {
                    $左侧区域数据[] = $Line;
                    $Counter ++;
                }
                break;
        }

        //报表头部数组
        $报表头部数组       = [];

        //右侧数据区域字段1
        $Report_X_DataColumn_1_Name = $SettingMap[$currentReport.'_DataColumn_1_Name'];
        //判断统计字段是否为数据表字段
        if(!in_array($Report_X_DataColumn_1_Name, $MetaColumnNames) && $Report_X_DataColumn_1_Name != '') {
            $RS = [];
            $RS['status'] = "ERROR";
            $RS['msg'] = "$Report_X_DataColumn_1_Name not a field in $TableName";
            print_R(EncryptApiData($RS, $GLOBAL_USER));
            exit;
        }
        $右侧数据区域字段1  = [];
        $sql        = "select count(*) AS NUM, $Report_X_LeftColumnField, $Report_X_DataColumn_1_Name from $TableName where 1=1 $WhereSql group by $Report_X_LeftColumnField, $Report_X_DataColumn_1_Name order by $Report_X_DataColumn_1_Name asc";
        $rs         = $db->Execute($sql);
        $rs_a       = $rs->GetArray();
        $数据区域SQL = $sql;
        foreach($rs_a as $Line) {
            if($Line[$Report_X_DataColumn_1_Name] == '') $Line[$Report_X_DataColumn_1_Name] = '空值';
            $报表头部数组[$Report_X_DataColumn_1_Name][$Line[$Report_X_DataColumn_1_Name]] = $Line[$Report_X_DataColumn_1_Name];
            $右侧数据区域字段1[$Line[$Report_X_LeftColumnField]][$Report_X_DataColumn_1_Name][$Line[$Report_X_DataColumn_1_Name]] = $Line['NUM'];
        }
        
        $右侧数据区域字段1_列名 = array_keys((array)$报表头部数组[$Report_X_DataColumn_1_Name]);
        foreach($右侧数据区域字段1_列名 as $右侧数据区域字段1_列名Value) {
            $报表页面['数据区域']['头部'][1][]   = ['name'=>$右侧数据区域字段1_列名Value, 'col'=>1, 'row'=>1, 'link'=>'', 'wrap'=>'No', 'align'=>'Center'];
        }
        $报表页面['数据区域']['头部'][0][]   = ['name'=>$Report_X_DataColumn_1_Name, 'col'=>count($右侧数据区域字段1_列名), 'row'=>1, 'link'=>'', 'wrap'=>'No', 'align'=>'Center'];

        //右侧数据区域字段2
        $Report_X_DataColumn_2_Name = $SettingMap[$currentReport.'_DataColumn_2_Name'];
        //判断统计字段是否为数据表字段
        if(!in_array($Report_X_DataColumn_2_Name, $MetaColumnNames) && $Report_X_DataColumn_2_Name != '') {
            $RS = [];
            $RS['status'] = "ERROR";
            $RS['msg'] = "$Report_X_DataColumn_2_Name not a field in $TableName";
            print_R(EncryptApiData($RS, $GLOBAL_USER));
            exit;
        }
        $右侧数据区域字段2  = [];
        $sql        = "select count(*) AS NUM, $Report_X_LeftColumnField, $Report_X_DataColumn_2_Name from $TableName where 1=1 $WhereSql group by $Report_X_LeftColumnField, $Report_X_DataColumn_2_Name order by $Report_X_DataColumn_2_Name asc";
        $rs         = $db->Execute($sql);
        $rs_a       = $rs->GetArray();
        //print_R($rs_a);
        foreach($rs_a as $Line) {
            if($Line[$Report_X_DataColumn_2_Name] == '') $Line[$Report_X_DataColumn_2_Name] = '空值';
            $报表头部数组[$Report_X_DataColumn_2_Name][$Line[$Report_X_DataColumn_2_Name]] = $Line[$Report_X_DataColumn_2_Name];
            $右侧数据区域字段2[$Line[$Report_X_LeftColumnField]][$Report_X_DataColumn_2_Name][$Line[$Report_X_DataColumn_2_Name]] = $Line['NUM'];
        }
        
        $右侧数据区域字段2_列名 = array_keys((array)$报表头部数组[$Report_X_DataColumn_2_Name]);
        foreach($右侧数据区域字段2_列名 as $右侧数据区域字段2_列名Value) {
            $报表页面['数据区域']['头部'][1][]   = ['name'=>$右侧数据区域字段2_列名Value, 'col'=>1, 'row'=>1, 'link'=>'', 'wrap'=>'No', 'align'=>'Center'];
        }
        $报表页面['数据区域']['头部'][0][]   = ['name'=>$Report_X_DataColumn_2_Name, 'col'=>count($右侧数据区域字段2_列名), 'row'=>1, 'link'=>'', 'wrap'=>'No', 'align'=>'Center'];

        //print_R($报表页面); print $Report_X_LeftColumnDefine; print $Report_X_LeftColumnField; exit;

    } //$Report_X_LeftColumnField != "" && $$Report_X_LeftColumnField != "无"


    $报表页面['搜索区域']['搜索按钮']   = "开始查询";
    $报表页面['搜索区域']['搜索事件']   = "action=search";

    for($X=1;$X<=6;$X++)    {
        $字段名称 = $SettingMap[$currentReport.'_SearchField_'.$X];
        if(in_array($字段名称, $MetaColumnNames) && $字段名称 != '') {
            $字段类型       = $字段的显示类型[$字段名称];
            $字段类型Edit   = returntablefield("form_formfield_showtype","`Name`",$字段类型,"Edit")['Edit'];
            $字段类型EditArray  = explode(':', $字段类型Edit);
            $字段类型2          = $字段类型EditArray[0];
            if($字段类型 == 'Input:input') {
                $字段类型2 = 'input';
            }
            switch($字段类型2) {
                case 'input':
                    $报表页面['搜索区域']['搜索条件'][] = ['name'=>$字段名称, 'sm'=>4, 'type'=>'input', 'field'=>$字段名称, 'default'=> '', 'placeholder'=>$字段名称];
                    break;
                case 'autocomplete':
                case 'tablefilter':
                case 'tablefiltercolor':
                case 'radiofilter':
                case 'radiofiltercolor':
                    $sql    = "select $字段名称, COUNT(*) AS NUM from $TableName group by $字段名称 order by ".$字段名称." asc";
                    $rs     = $db->Execute($sql);
                    $rs_a   = $rs->GetArray();
                    $NewArray = [];
                    $NewArray[] = ['name'=>'全部数据', 'value'=>'全部数据'];
                    for($F=0;$F<sizeof($rs_a);$F++)  {
                        if($rs_a[$F][$字段名称] == "")  {
                            $NewArray[] = ['name'=>"空值"."(".$rs_a[$F]['NUM'].")", 'value'=>'空值'];
                        }
                        else {
                            $NewArray[] = ['name'=>$rs_a[$F][$字段名称]."(".$rs_a[$F]['NUM'].")", 'value'=>$rs_a[$F][$字段名称]];
                        }
                    }
                    $报表页面['搜索区域']['搜索条件'][] = ['name'=>$字段名称, 'sm'=>4, 'type'=> $字段类型2=='autocomplete' ? 'autocomplete' : 'select', 'field'=>$字段名称, 'default'=> '全部数据', 'placeholder'=>$字段名称, 'data'=>$NewArray];
                    break;
                case 'autocompletemulti':
                    $sql    = "select $字段名称, COUNT(*) AS NUM from $TableName group by $字段名称 order by ".$字段名称." asc";
                    $rs     = $db->Execute($sql);
                    $rs_a   = $rs->GetArray();
                    $NewArray = [];
                    $NewArray[] = ['name'=>'全部数据', 'value'=>'全部数据'];
                    for($F=0;$F<sizeof($rs_a);$F++)  {
                        if($rs_a[$F][$字段名称] == "")  {
                            $NewArray[] = ['name'=>"空值"."(".$rs_a[$F]['NUM'].")", 'value'=>'空值'];
                        }
                        else {
                            $NewArray[] = ['name'=>$rs_a[$F][$字段名称]."(".$rs_a[$F]['NUM'].")", 'value'=>$rs_a[$F][$字段名称]];
                        }
                    }
                    $报表页面['搜索区域']['搜索条件'][] = ['name'=>$字段名称, 'sm'=>4, 'type'=>'autocompletemulti', 'field'=>$字段名称, 'default'=> '全部数据', 'placeholder'=>$字段名称, 'data'=>$NewArray];
                    break;
            }
            //print $字段类型2;//print_R($字段类型EditArray);//exit;
        }
    }

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

    for($i=0;$i<sizeof($左侧区域数据);$i++)   {
        $报表页面['数据区域']['数据'][$i]['序号']   = $i + 1;
        $左侧单元 = $左侧区域数据[$i];
        foreach($左侧单元 as $左侧单元Item => $左侧单元Value)   {
            $报表页面['数据区域']['数据'][$i][$左侧单元Item]    = $左侧单元Value;
        }
        $右侧数据关联字段Value = $左侧单元[$右侧数据关联字段];
        if(sizeof($右侧数据区域字段1) > 0) {
            foreach($右侧数据区域字段1_列名 as $右侧数据区域字段1_列名_Value) {
                $报表页面['数据区域']['数据'][$i][$右侧数据区域字段1_列名_Value]    = $右侧数据区域字段1[$右侧数据关联字段Value][$Report_X_DataColumn_1_Name][$右侧数据区域字段1_列名_Value];
            }
        }
        if(sizeof($右侧数据区域字段2) > 0) {
            foreach($右侧数据区域字段2_列名 as $右侧数据区域字段2_列名_Value) {
                $报表页面['数据区域']['数据'][$i][$右侧数据区域字段2_列名_Value]    = $右侧数据区域字段2[$右侧数据关联字段Value][$Report_X_DataColumn_2_Name][$右侧数据区域字段2_列名_Value];
            }
        }
    }
    //print_R($报表页面);
    //print_R($右侧数据区域字段2);
    if($SettingMap[$currentReport.'_Detail_Fields']) {
        $报表页面['数据区域']['链接']   = true;
    }

    $报表页面['底部区域']['备注']['标题']   = $SettingMap[$currentReport.'_Memo_Title'];
    $报表页面['底部区域']['备注']['内容']   = $SettingMap[$currentReport.'_Memo_Content'];

    $报表页面['底部区域']['功能按钮']       = ['打印', '导出Excel', '导出Pdf'];

    $报表页面['status'] = 'OK';
    
    $报表页面['SQL']['数据区域SQL'] = $数据区域SQL;

    return $报表页面;
}
?>