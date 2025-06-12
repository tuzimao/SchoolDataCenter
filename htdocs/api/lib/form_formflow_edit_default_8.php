<?php
/*
* 基础架构: 单点低代码开发平台
* 版权所有: 郑州单点科技软件有限公司
* Email: moodle360@qq.com
* Copyright (c) 2007-2025
* License: 商业授权
*/
//禁止当前文件直接在URL中访问
$CurrentUrlFileName = basename($_SERVER['PHP_SELF']);
$ForbiddenAccessUrlList = ['form_formflow_edit_default_8.php'];
if(in_array($CurrentUrlFileName, $ForbiddenAccessUrlList)) exit;


//#########################################################################################################################
//NodeFlow#################################################################################################################
//#########################################################################################################################
$edit_default_8 = [];

$sql    = "select MenuOneName from data_menuone order by SortNumber asc, MenuOneName asc";
$rsf    = $db->Execute($sql);
$rsf_a  = $rsf->GetArray();
$MenuOneNameArray = [];
foreach($rsf_a as $Item)  {
    $MenuOneNameArray[] = ['value'=>$Item['MenuOneName'],'label'=>$Item['MenuOneName']];
}


$表头选项 = [];
$表头选项[] = ['value'=>'3', 'label'=>'表头3行'];
$表头选项[] = ['value'=>'2', 'label'=>'表头2行'];
$表头选项[] = ['value'=>'1', 'label'=>'表头1行'];

$左侧结构选项 = [];
$左侧结构选项[] = ['value'=>'用户名/姓名', 'label'=>'用户名/姓名'];
$左侧结构选项[] = ['value'=>'用户名/姓名/部门', 'label'=>'用户名/姓名/部门'];
$左侧结构选项[] = ['value'=>'部门/用户名/姓名', 'label'=>'部门/用户名/姓名'];
$左侧结构选项[] = ['value'=>'学生/姓名/班级', 'label'=>'学生/姓名/班级'];
$左侧结构选项[] = ['value'=>'班级/学生/姓名', 'label'=>'班级/学生/姓名'];
$左侧结构选项[] = ['value'=>'班级/专业/系部', 'label'=>'班级/专业/系部'];
$左侧结构选项[] = ['value'=>'系部/专业/班级', 'label'=>'系部/专业/班级'];
$左侧结构选项[] = ['value'=>'动态数据做为左侧列', 'label'=>'动态数据做为左侧列'];
$左侧结构选项[] = ['value'=>'无', 'label'=>'无'];

$左侧数据是否全部显示 = [];
$左侧数据是否全部显示[] = ['value'=>'只显示有数据的记录', 'label'=>'只显示有数据的记录'];
$左侧数据是否全部显示[] = ['value'=>'全部显示所有记录', 'label'=>'全部显示所有记录'];

$MenuTab_Options = [];
$MenuTab_Options[] = ['value'=>'Yes', 'label'=>__('Yes')];
$MenuTab_Options[] = ['value'=>'No', 'label'=>__('No')];
$edit_default_8['ReportSetting'][] = ['name' => "EnableReport", 'show'=>true, 'type'=>'select', 'options'=>$MenuTab_Options, 'label' => __("EnableReport"), 'value' => "No", 'placeholder' => "", 'helptext' => __(""), 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>12]];

$edit_default_8['ReportSetting1'][] = ['name' => "divider", 'show'=>true, 'type'=>"divider", 'label' => __("divider"), 'value' => "", 'placeholder' => "", 'helptext' => __(""), 'rules' => ['required' => false,'xs'=>12, 'sm'=>12, 'disabled' => false]];

$edit_default_8['ReportSetting1'][] = ['name' => "Report_1_Name", 'show'=>true, 'type'=>"input", 'label' => __("Report_1_Name"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>6, 'disabled' => false]];

$edit_default_8['ReportSetting1'][] = ['name' => "Report_1_HeaderCount", 'show'=>true, 'type'=>'select', 'options'=>$表头选项, 'label' => __("Report_1_HeaderCount"), 'value' => "2", 'placeholder' => "", 'helptext' => __(""), 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>6]];

$edit_default_8['ReportSetting1'][] = ['name' => "Report_1_LeftColumnDefine", 'show'=>true, 'type'=>'select', 'options'=>$左侧结构选项, 'label' => __("Report_1_LeftColumnDefine"), 'value' => "用户名/姓名", 'placeholder' => "", 'helptext' => __(""), 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>4]];

$edit_default_8['ReportSetting1'][] = ['name' => "Report_1_LeftColumnField", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("Report_1_LeftColumnField"), 'value' => "", 'placeholder' => "", 'helptext' => __(""), 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>4]];

$edit_default_8['ReportSetting1'][] = ['name' => "Report_1_LeftColumnDataShow", 'show'=>true, 'type'=>'select', 'options'=>$左侧数据是否全部显示, 'label' => __("Report_1_LeftColumnDataShow"), 'value' => "只显示有数据的记录", 'placeholder' => "", 'helptext' => __(""), 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>4]];

$edit_default_8['ReportSetting1'][] = ['name' => "Report_1_DataColumn_1_Name", 'show'=>true, 'type'=>"input", 'label' => __("Report_1_DataColumn_1_Name"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>2, 'disabled' => false]];
$edit_default_8['ReportSetting1'][] = ['name' => "Report_1_DataColumn_1_SQL", 'show'=>true, 'type'=>"input", 'label' => __("Report_1_DataColumn_1_SQL"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>10, 'disabled' => false]];
$edit_default_8['ReportSetting1'][] = ['name' => "Report_1_DataColumn_2_Name", 'show'=>true, 'type'=>"input", 'label' => __("Report_1_DataColumn_2_Name"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>2, 'disabled' => false]];
$edit_default_8['ReportSetting1'][] = ['name' => "Report_1_DataColumn_2_SQL", 'show'=>true, 'type'=>"input", 'label' => __("Report_1_DataColumn_2_SQL"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>10, 'disabled' => false]];
$edit_default_8['ReportSetting1'][] = ['name' => "Report_1_DataColumn_3_Name", 'show'=>true, 'type'=>"input", 'label' => __("Report_1_DataColumn_3_Name"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>2, 'disabled' => false]];
$edit_default_8['ReportSetting1'][] = ['name' => "Report_1_DataColumn_3_SQL", 'show'=>true, 'type'=>"input", 'label' => __("Report_1_DataColumn_3_SQL"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>10, 'disabled' => false]];
$edit_default_8['ReportSetting1'][] = ['name' => "Report_1_DataColumn_4_Name", 'show'=>true, 'type'=>"input", 'label' => __("Report_1_DataColumn_4_Name"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>2, 'disabled' => false]];
$edit_default_8['ReportSetting1'][] = ['name' => "Report_1_DataColumn_4_SQL", 'show'=>true, 'type'=>"input", 'label' => __("Report_1_DataColumn_4_SQL"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>10, 'disabled' => false]];
$edit_default_8['ReportSetting1'][] = ['name' => "Report_1_DataColumn_5_Name", 'show'=>true, 'type'=>"input", 'label' => __("Report_1_DataColumn_5_Name"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>2, 'disabled' => false]];
$edit_default_8['ReportSetting1'][] = ['name' => "Report_1_DataColumn_5_SQL", 'show'=>true, 'type'=>"input", 'label' => __("Report_1_DataColumn_5_SQL"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>10, 'disabled' => false]];
$edit_default_8['ReportSetting1'][] = ['name' => "Report_1_DataColumn_6_Name", 'show'=>true, 'type'=>"input", 'label' => __("Report_1_DataColumn_6_Name"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>2, 'disabled' => false]];
$edit_default_8['ReportSetting1'][] = ['name' => "Report_1_DataColumn_6_SQL", 'show'=>true, 'type'=>"input", 'label' => __("Report_1_DataColumn_6_SQL"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>10, 'disabled' => false]];
$edit_default_8['ReportSetting1'][] = ['name' => "Report_1_DataColumn_7_Name", 'show'=>true, 'type'=>"input", 'label' => __("Report_1_DataColumn_7_Name"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>2, 'disabled' => false]];
$edit_default_8['ReportSetting1'][] = ['name' => "Report_1_DataColumn_7_SQL", 'show'=>true, 'type'=>"input", 'label' => __("Report_1_DataColumn_7_SQL"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>10, 'disabled' => false]];
$edit_default_8['ReportSetting1'][] = ['name' => "Report_1_DataColumn_8_Name", 'show'=>true, 'type'=>"input", 'label' => __("Report_1_DataColumn_8_Name"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>2, 'disabled' => false]];
$edit_default_8['ReportSetting1'][] = ['name' => "Report_1_DataColumn_8_SQL", 'show'=>true, 'type'=>"input", 'label' => __("Report_1_DataColumn_8_SQL"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>10, 'disabled' => false]];
$edit_default_8['ReportSetting1'][] = ['name' => "Report_1_Memo_Title", 'show'=>true, 'type'=>"input", 'label' => __("Report_1_Memo_Title"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>12, 'disabled' => false]];
$edit_default_8['ReportSetting1'][] = ['name' => "Report_1_Memo_Content", 'show'=>true, 'type'=>"textarea", 'label' => __("Report_1_Memo_Content"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>12, 'disabled' => false]];

################################################################################################

$edit_default_8['ReportSetting2'][] = ['name' => "divider", 'show'=>true, 'type'=>"divider", 'label' => __("divider"), 'value' => "", 'placeholder' => "", 'helptext' => __(""), 'rules' => ['required' => false,'xs'=>12, 'sm'=>12, 'disabled' => false]];

$edit_default_8['ReportSetting2'][] = ['name' => "Report_2_Name", 'show'=>true, 'type'=>"input", 'label' => __("Report_2_Name"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>6, 'disabled' => false]];

$edit_default_8['ReportSetting2'][] = ['name' => "Report_2_HeaderCount", 'show'=>true, 'type'=>'select', 'options'=>$表头选项, 'label' => __("Report_2_HeaderCount"), 'value' => "2", 'placeholder' => "", 'helptext' => __(""), 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>6]];

$edit_default_8['ReportSetting2'][] = ['name' => "Report_2_LeftColumnDefine", 'show'=>true, 'type'=>'select', 'options'=>$左侧结构选项, 'label' => __("Report_2_LeftColumnDefine"), 'value' => "用户名/姓名", 'placeholder' => "", 'helptext' => __(""), 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>4]];

$edit_default_8['ReportSetting2'][] = ['name' => "Report_2_LeftColumnField", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("Report_2_LeftColumnField"), 'value' => "", 'placeholder' => "", 'helptext' => __(""), 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>4]];

$edit_default_8['ReportSetting2'][] = ['name' => "Report_2_LeftColumnDataShow", 'show'=>true, 'type'=>'select', 'options'=>$左侧数据是否全部显示, 'label' => __("Report_2_LeftColumnDataShow"), 'value' => "只显示有数据的记录", 'placeholder' => "", 'helptext' => __(""), 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>4]];

$edit_default_8['ReportSetting2'][] = ['name' => "Report_2_DataColumn_1_Name", 'show'=>true, 'type'=>"input", 'label' => __("Report_2_DataColumn_1_Name"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>2, 'disabled' => false]];
$edit_default_8['ReportSetting2'][] = ['name' => "Report_2_DataColumn_1_SQL", 'show'=>true, 'type'=>"input", 'label' => __("Report_2_DataColumn_1_SQL"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>10, 'disabled' => false]];
$edit_default_8['ReportSetting2'][] = ['name' => "Report_2_DataColumn_2_Name", 'show'=>true, 'type'=>"input", 'label' => __("Report_2_DataColumn_2_Name"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>2, 'disabled' => false]];
$edit_default_8['ReportSetting2'][] = ['name' => "Report_2_DataColumn_2_SQL", 'show'=>true, 'type'=>"input", 'label' => __("Report_2_DataColumn_2_SQL"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>10, 'disabled' => false]];
$edit_default_8['ReportSetting2'][] = ['name' => "Report_2_DataColumn_3_Name", 'show'=>true, 'type'=>"input", 'label' => __("Report_2_DataColumn_3_Name"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>2, 'disabled' => false]];
$edit_default_8['ReportSetting2'][] = ['name' => "Report_2_DataColumn_3_SQL", 'show'=>true, 'type'=>"input", 'label' => __("Report_2_DataColumn_3_SQL"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>10, 'disabled' => false]];
$edit_default_8['ReportSetting2'][] = ['name' => "Report_2_DataColumn_4_Name", 'show'=>true, 'type'=>"input", 'label' => __("Report_2_DataColumn_4_Name"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>2, 'disabled' => false]];
$edit_default_8['ReportSetting2'][] = ['name' => "Report_2_DataColumn_4_SQL", 'show'=>true, 'type'=>"input", 'label' => __("Report_2_DataColumn_4_SQL"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>10, 'disabled' => false]];
$edit_default_8['ReportSetting2'][] = ['name' => "Report_2_DataColumn_5_Name", 'show'=>true, 'type'=>"input", 'label' => __("Report_2_DataColumn_5_Name"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>2, 'disabled' => false]];
$edit_default_8['ReportSetting2'][] = ['name' => "Report_2_DataColumn_5_SQL", 'show'=>true, 'type'=>"input", 'label' => __("Report_2_DataColumn_5_SQL"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>10, 'disabled' => false]];
$edit_default_8['ReportSetting2'][] = ['name' => "Report_2_DataColumn_6_Name", 'show'=>true, 'type'=>"input", 'label' => __("Report_2_DataColumn_6_Name"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>2, 'disabled' => false]];
$edit_default_8['ReportSetting2'][] = ['name' => "Report_2_DataColumn_6_SQL", 'show'=>true, 'type'=>"input", 'label' => __("Report_2_DataColumn_6_SQL"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>10, 'disabled' => false]];
$edit_default_8['ReportSetting2'][] = ['name' => "Report_2_DataColumn_7_Name", 'show'=>true, 'type'=>"input", 'label' => __("Report_2_DataColumn_7_Name"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>2, 'disabled' => false]];
$edit_default_8['ReportSetting2'][] = ['name' => "Report_2_DataColumn_7_SQL", 'show'=>true, 'type'=>"input", 'label' => __("Report_2_DataColumn_7_SQL"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>10, 'disabled' => false]];
$edit_default_8['ReportSetting2'][] = ['name' => "Report_2_DataColumn_8_Name", 'show'=>true, 'type'=>"input", 'label' => __("Report_2_DataColumn_8_Name"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>2, 'disabled' => false]];
$edit_default_8['ReportSetting2'][] = ['name' => "Report_2_DataColumn_8_SQL", 'show'=>true, 'type'=>"input", 'label' => __("Report_2_DataColumn_8_SQL"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>10, 'disabled' => false]];
$edit_default_8['ReportSetting2'][] = ['name' => "Report_2_Memo_Title", 'show'=>true, 'type'=>"input", 'label' => __("Report_2_Memo_Title"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>12, 'disabled' => false]];
$edit_default_8['ReportSetting2'][] = ['name' => "Report_2_Memo_Content", 'show'=>true, 'type'=>"textarea", 'label' => __("Report_2_Memo_Content"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>12, 'disabled' => false]];

################################################################################################




$defaultValues_8 = [];
foreach($edit_default_8 as $ModeName=>$allFieldItem) {
    foreach($allFieldItem as $ITEM) {
        if($ITEM['type'] == "autocompletemulti")  {
            $defaultValues_8[$ITEM['code']] = $ITEM['value'];
        }
        else {
            $defaultValues_8[$ITEM['name']] = $ITEM['value'];
        }
    }
}


$edit_default_8_mode[] = ['value'=>"ReportSetting", 'label'=>__("ReportSetting")];
$edit_default_8_mode[] = ['value'=>"ReportSetting1", 'label'=>__("ReportSetting1")];
$edit_default_8_mode[] = ['value'=>"ReportSetting2", 'label'=>__("ReportSetting2")];
$edit_default_8_mode[] = ['value'=>"ReportSetting3", 'label'=>__("ReportSetting3")];
$edit_default_8_mode[] = ['value'=>"ReportSetting4", 'label'=>__("ReportSetting4")];

if($_GET['action']=="edit_default_8"&&$id!='')         {
    $sql    = "select * from form_formflow where id = '$id'";
    $rs     = $db->Execute($sql);
    $FlowName   = $rs->fields['FlowName'];
    $Setting    = $rs->fields['Setting'];
    $SettingMap = unserialize(base64_decode($Setting));
    $FlowName   = $rs->fields['FlowName'];
    if(is_array($SettingMap))   {
        $defaultValues_8_keys = array_keys($defaultValues_8);
        foreach($SettingMap as $value => $label)  {
            if(in_array($value, $defaultValues_8_keys) && $value!="Init_Action_Page_ConfigSettingUrl")  {
                $defaultValues_8[$value] = $label;
            }
        }
    }

    $EnableFields = [];
    switch($defaultValues_8['Page_Role_Name']) {
        case '院系':
            $EnableFields[] = "Faculty_Filter_Field";
            break;
    }

    //临时启用
    //$defaultValues_8['Menu_One'] = $SettingMap['Menu_One'];
    //$defaultValues_8['Menu_Two'] = $SettingMap['Menu_Two'];
    //$defaultValues_8['Menu_Three'] = $SettingMap['Menu_Three'];
    //$defaultValues_8['FaceTo'] = $SettingMap['FaceTo'];
    $edit_default['allFields']      = $edit_default_8;
    $edit_default['allFieldsMode']  = $edit_default_8_mode;
    $edit_default['defaultValues']  = $defaultValues_8;
    $edit_default['dialogContentHeight']  = "90%";
    $edit_default['componentsize']  = "small";
    $edit_default['submitaction']   = "edit_default_8_data";
    $edit_default['submittext']     = __("Submit");
    $edit_default['canceltext']     = __("Cancel");
    $edit_default['titletext']      = __("Design Form Field Type");
    $edit_default['titlememo']      = __("Manage All Form Fields in Table");
    $edit_default['tablewidth']     = 550;

    $RS['edit_default'] = $edit_default;
    $RS['EnableFields'] = $EnableFields;
    $RS['status'] = "OK";
    $RS['data'] = $defaultValues_8;
    $RS['sql']  = $sql;
    $RS['forceuse'] = true;
    $RS['msg'] = __("Get Data Success");
    print_R(json_encode($RS));
    exit;
}


?>