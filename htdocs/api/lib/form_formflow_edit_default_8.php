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

$MenuTab_Options = [];
$MenuTab_Options[] = ['value'=>'Yes', 'label'=>__('Yes')];
$MenuTab_Options[] = ['value'=>'No', 'label'=>__('No')];
$edit_default_8['ReportSetting1'][] = ['name' => "EnableReport", 'show'=>true, 'type'=>'select', 'options'=>$MenuTab_Options, 'label' => __("EnableReport"), 'value' => "No", 'placeholder' => "", 'helptext' => __(""), 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>6]];

$MenuTab_Options = [];
$MenuTab_Options[] = ['value'=>'3', 'label'=>'表头3行'];
$MenuTab_Options[] = ['value'=>'2', 'label'=>'表头2行'];
$MenuTab_Options[] = ['value'=>'1', 'label'=>'表头1行'];
$edit_default_8['ReportSetting1'][] = ['name' => "ReportHeaderCount", 'show'=>true, 'type'=>'select', 'options'=>$MenuTab_Options, 'label' => __("ReportHeaderCount"), 'value' => "2", 'placeholder' => "", 'helptext' => __(""), 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>6]];

$MenuTab_Options = [];
$MenuTab_Options[] = ['value'=>'用户名/姓名', 'label'=>'用户名/姓名'];
$MenuTab_Options[] = ['value'=>'用户名/姓名/部门', 'label'=>'用户名/姓名/部门'];
$MenuTab_Options[] = ['value'=>'部门/用户名/姓名', 'label'=>'部门/用户名/姓名'];
$MenuTab_Options[] = ['value'=>'学生/姓名/班级', 'label'=>'学生/姓名/班级'];
$MenuTab_Options[] = ['value'=>'班级/学生/姓名', 'label'=>'班级/学生/姓名'];
$MenuTab_Options[] = ['value'=>'班级/专业/系部', 'label'=>'班级/专业/系部'];
$MenuTab_Options[] = ['value'=>'系部/专业/班级', 'label'=>'系部/专业/班级'];
$MenuTab_Options[] = ['value'=>'动态数据做为左侧列', 'label'=>'动态数据做为左侧列'];
$edit_default_8['ReportSetting1'][] = ['name' => "ReportLeftColumnDefine", 'show'=>true, 'type'=>'select', 'options'=>$MenuTab_Options, 'label' => __("ReportLeftColumnDefine"), 'value' => "用户名/姓名", 'placeholder' => "", 'helptext' => __(""), 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>6]];

$MenuTab_Options = [];
$MenuTab_Options[] = ['value'=>'只显示有数据的记录', 'label'=>'只显示有数据的记录'];
$MenuTab_Options[] = ['value'=>'全部显示所有记录', 'label'=>'全部显示所有记录'];
$edit_default_8['ReportSetting1'][] = ['name' => "ReportLeftColumnDataShow", 'show'=>true, 'type'=>'select', 'options'=>$MenuTab_Options, 'label' => __("ReportLeftColumnDataShow"), 'value' => "只显示有数据的记录", 'placeholder' => "", 'helptext' => __(""), 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>6]];

$edit_default_8['ReportSetting1'][] = ['name' => "ReportDataColumn_1_Name", 'show'=>true, 'type'=>"input", 'label' => __("ReportDataColumn_1_Name"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>2, 'disabled' => false]];
$edit_default_8['ReportSetting1'][] = ['name' => "ReportDataColumn_1_SQL", 'show'=>true, 'type'=>"input", 'label' => __("ReportDataColumn_1_SQL"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>10, 'disabled' => false]];
$edit_default_8['ReportSetting1'][] = ['name' => "ReportDataColumn_2_Name", 'show'=>true, 'type'=>"input", 'label' => __("ReportDataColumn_2_Name"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>2, 'disabled' => false]];
$edit_default_8['ReportSetting1'][] = ['name' => "ReportDataColumn_2_SQL", 'show'=>true, 'type'=>"input", 'label' => __("ReportDataColumn_2_SQL"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>10, 'disabled' => false]];
$edit_default_8['ReportSetting1'][] = ['name' => "ReportDataColumn_3_Name", 'show'=>true, 'type'=>"input", 'label' => __("ReportDataColumn_3_Name"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>2, 'disabled' => false]];
$edit_default_8['ReportSetting1'][] = ['name' => "ReportDataColumn_3_SQL", 'show'=>true, 'type'=>"input", 'label' => __("ReportDataColumn_3_SQL"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>10, 'disabled' => false]];
$edit_default_8['ReportSetting1'][] = ['name' => "ReportDataColumn_4_Name", 'show'=>true, 'type'=>"input", 'label' => __("ReportDataColumn_4_Name"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>2, 'disabled' => false]];
$edit_default_8['ReportSetting1'][] = ['name' => "ReportDataColumn_4_SQL", 'show'=>true, 'type'=>"input", 'label' => __("ReportDataColumn_4_SQL"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>10, 'disabled' => false]];
$edit_default_8['ReportSetting1'][] = ['name' => "ReportDataColumn_5_Name", 'show'=>true, 'type'=>"input", 'label' => __("ReportDataColumn_5_Name"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>2, 'disabled' => false]];
$edit_default_8['ReportSetting1'][] = ['name' => "ReportDataColumn_5_SQL", 'show'=>true, 'type'=>"input", 'label' => __("ReportDataColumn_5_SQL"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>10, 'disabled' => false]];
$edit_default_8['ReportSetting1'][] = ['name' => "ReportDataColumn_6_Name", 'show'=>true, 'type'=>"input", 'label' => __("ReportDataColumn_6_Name"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>2, 'disabled' => false]];
$edit_default_8['ReportSetting1'][] = ['name' => "ReportDataColumn_6_SQL", 'show'=>true, 'type'=>"input", 'label' => __("ReportDataColumn_6_SQL"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>10, 'disabled' => false]];
$edit_default_8['ReportSetting1'][] = ['name' => "ReportDataColumn_7_Name", 'show'=>true, 'type'=>"input", 'label' => __("ReportDataColumn_7_Name"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>2, 'disabled' => false]];
$edit_default_8['ReportSetting1'][] = ['name' => "ReportDataColumn_7_SQL", 'show'=>true, 'type'=>"input", 'label' => __("ReportDataColumn_7_SQL"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>10, 'disabled' => false]];
$edit_default_8['ReportSetting1'][] = ['name' => "ReportDataColumn_8_Name", 'show'=>true, 'type'=>"input", 'label' => __("ReportDataColumn_8_Name"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>2, 'disabled' => false]];
$edit_default_8['ReportSetting1'][] = ['name' => "ReportDataColumn_8_SQL", 'show'=>true, 'type'=>"input", 'label' => __("ReportDataColumn_8_SQL"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>10, 'disabled' => false]];
$edit_default_8['ReportSetting1'][] = ['name' => "divider", 'show'=>true, 'type'=>"divider", 'label' => __("divider"), 'value' => "", 'placeholder' => "", 'helptext' => __(""), 'rules' => ['required' => false,'xs'=>12, 'sm'=>12, 'disabled' => false]];

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


$edit_default_8_mode[] = ['value'=>"ReportSetting1", 'label'=>__("ReportSetting1")];
$edit_default_8_mode[] = ['value'=>"Page_Role", 'label'=>__("Page_Role")];
$edit_default_8_mode[] = ['value'=>"AuthorizedControl", 'label'=>__("AuthorizedControl")];

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