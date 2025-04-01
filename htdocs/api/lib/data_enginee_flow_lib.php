<?php
/*
* 基础架构: 单点低代码开发平台
* 版权所有: 郑州单点科技软件有限公司
* Email: moodle360@qq.com
* Copyright (c) 2007-2025
* License: 商业授权
*/
use Overtrue\Pinyin\Pinyin;
header("Content-Type: application/json");
$TIME_BEGIN = time();
require_once('./cors.php');
require_once('./include.inc.php');
require_once('data_enginee_function.php');

//print "TIME EXCEUTE 0:".(time()-$TIME_BEGIN)."<BR>\n";
//$FormId = 16;
//$Step = 1;
//print_R($_POST);exit;
//限制特定文件不能在URL中使用
$CurrentUrlFileName = basename($_SERVER['PHP_SELF']);
$ForbiddenAccessUrlList = ['data_enginee_flow.php','data_enginee_flow_lib.php'];
if(in_array($CurrentUrlFileName, $ForbiddenAccessUrlList)) exit;

global $GLOBAL_EXEC_KEY_SQL;
$GLOBAL_EXEC_KEY_SQL        = [];
$AdditionalPermissionsSQL   = "";

$AdminFilterTipTextArray    = [];
$AdminFilterOrTipTextArray  = [];

//Get Form Flow Setting
$FlowId     = intval($FlowId);
if($FlowId > 0)  {
	$sql    = "select * from form_formflow where id='$FlowId'";
}
else {
	$sql    = "select * from form_formflow where FormId='$FormId' and Step='$Step'";
}
$rs           = $db->Execute($sql);
$FromInfo     = $rs->fields;
$FormId  	  = $FromInfo['FormId'];
$FlowId  	  = $FromInfo['id'];
$FlowName  	  = $FromInfo['FlowName'];
$Step  		  = $FromInfo['Step'];
$Setting  	  = $FromInfo['Setting'];
$FaceTo  	  = $FromInfo['FaceTo'];

//print "TIME EXCEUTE 1:".(time()-$TIME_BEGIN)."<BR>\n";
$rowHeight = 38;
$sqlList = [];

global $SettingMap;
$SettingMap = unserialize(base64_decode($Setting));
$Actions_In_List_Row_Array = explode(',',$SettingMap['Actions_In_List_Row']);
$Actions_In_List_Header_Array = explode(',',$SettingMap['Actions_In_List_Header']);
//print_R($SettingMap);exit;
//print "TIME EXCEUTE 2:".(time()-$TIME_BEGIN)."<BR>\n";


if($FaceTo=="AuthUser" && $WholePageModel == "PageFunction")         {
  //Check User Login or Not
  CheckAuthUserLoginStatus();
  CheckAuthUserRoleHaveMenu($FlowId);
  CheckCsrsToken();
}
if($FaceTo=="Student" && $WholePageModel == "PageFunction")         {
  //Check User Login or Not
  CheckAuthUserLoginStatus();
  //CheckAuthUserRoleHaveMenu($FlowId);
  CheckCsrsToken();
}
if($FaceTo=="AuthUser" && $WholePageModel == "Workflow")         {
    //Check User Login or Not
    CheckAuthUserLoginStatus();
    //需要增加权限判断
}
if($FaceTo=="Student" && $WholePageModel == "Workflow")         {
    //Check User Login or Not
    CheckAuthUserLoginStatus();
    //需要增加权限判断
}

//Get Table Infor
$sql        = "select * from form_formname where id='$FormId'";
$rs         = $db->Execute($sql);
$FromInfo   = $rs->fields;
$TableName  = $FromInfo['TableName'];
global $FormName;
$FormName   = $FromInfo['FullName'];

//EnablePluginsForIndividual
if($SettingMap['EnablePluginsForIndividual']=="Enable" && $TableName!="" && $Step>0 && is_file("../plugins/plugin_".$TableName."_".$Step.".php"))    {
    require_once("../plugins/plugin_".$TableName."_".$Step.".php");
}

//Get form_formfield_showtype
$sql        = "select * from form_formfield_showtype";
$rs         = $db->Execute($sql);
$AllShowTypes   = $rs->GetArray();
$AllShowTypesArray = [];
foreach($AllShowTypes as $Item)  {
    $AllShowTypesArray[$Item['Name']] = $Item;
}
//print "TIME EXCEUTE 3:".(time()-$TIME_BEGIN)."<BR>\n";

//Get All Fields
$sql        = "select * from form_formfield where FormId='$FormId' and IsEnable='1' order by SortNumber asc, id asc";
$rs         = $db->Execute($sql);
$AllFieldsFromTable   = $rs->GetArray();
$AllFieldsMap = [];
foreach($AllFieldsFromTable as $Item)  {
    $Item['Setting']                    = json_decode($Item['Setting'],true);
    $AllFieldsMap[$Item['FieldName']]   = $Item;
    $LocaleFieldArray[$Item['EnglishName']] = $Item['FieldName'];
    $LocaleFieldArray[$Item['ChineseName']] = $Item['FieldName'];
}
//print "TIME EXCEUTE 4:".(time()-$TIME_BEGIN)."<BR>\n";
if($TableName == null) {
    print "TableName is null";
    exit;
}
$MetaColumnNames    = GLOBAL_MetaColumnNames($TableName);
$UniqueKey          = $MetaColumnNames[1];

//Extra Role
$AddSql = " where 1=1 ";
require_once('data_enginee_filter_role.php');

//print "TIME EXCEUTE 6:".(time()-$TIME_BEGIN)."<BR>\n";

global $InsertOrUpdateFieldArrayForSql;
$InsertOrUpdateFieldArrayForSql['ADD']  = [];
$InsertOrUpdateFieldArrayForSql['EDIT'] = [];

$defaultValuesAdd  = [];
$defaultValuesEdit = [];

$allFieldsAdd   = getAllFields($AllFieldsFromTable, $AllShowTypesArray, 'ADD', true, $SettingMap);
foreach($allFieldsAdd as $ModeName=>$allFieldItem) {
    foreach($allFieldItem as $ITEM) {
        $defaultValuesAdd[$ITEM['name']] = $ITEM['value'];
        if($ITEM['code']!="") {
            $defaultValuesAdd[$ITEM['code']] = $ITEM['value'];
        }
    }
}

$allFieldsEdit  = getAllFields($AllFieldsFromTable, $AllShowTypesArray, 'EDIT', true, $SettingMap);
foreach($allFieldsEdit as $ModeName=>$allFieldItem) {
    foreach($allFieldItem as $ITEM) {
        $defaultValuesEdit[$ITEM['name']] = $ITEM['value'];
    }
}

$allFieldsView  = getAllFields($AllFieldsFromTable, $AllShowTypesArray, 'VIEW', true, $SettingMap);
foreach($allFieldsView as $ModeName=>$allFieldItem) {
    foreach($allFieldItem as $ITEM) {
        $allFieldsView[$ITEM['name']] = $ITEM['value'];
    }
}

//Import Page Structure
$Import_Rule_Method = [];
$Import_Rule_Method[] = ['value'=>"BothInsertAndUpdate", 'label'=>__("BothInsertAndUpdate")];
$Import_Rule_Method[] = ['value'=>"OnlyUpdate", 'label'=>__("OnlyUpdate")];
$Import_Rule_Method[] = ['value'=>"OnlyInsert", 'label'=>__("OnlyInsert")];
$allFieldsImport['Default'][] = ['name' => "Import_Rule_Method", 'show'=>true, 'type'=>'select', 'options'=>$Import_Rule_Method, 'label' => __("Step1_Choose_Import_Rule"), 'value' => $Import_Rule_Method[0]['value'], 'placeholder' => "", 'helptext' => __(""), 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>12]];

$Import_Fields          = [];
$Import_Fields_Default  = [];
foreach($AllFieldsFromTable as $Item)  {
    if($SettingMap["FieldImport_".$Item['FieldName']]=="true" || $SettingMap["FieldImport_".$Item['FieldName']]=="1")   {
        $Import_Fields[]            = ['value'=>$Item['FieldName'], 'label'=>$Item['ChineseName']];
        $Import_Fields_Default[]    = $Item['FieldName'];
    }
}
$allFieldsImport['Default'][] = ['name' => "Import_Fields", 'show'=>true, 'type'=>'checkbox', 'options'=>$Import_Fields, 'label' => __("Step2_Choose_Import_Fields"), 'value' => join(',', $Import_Fields_Default), 'placeholder' => "", 'helptext' => __(""), 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>12, 'row'=>true]];

$TEMPARRAY                      = [];
$TEMPARRAY['TableName']         = $TableName;
$TEMPARRAY['Action']            = "export_template";
$TEMPARRAY['FormId']            = $FormId;
$TEMPARRAY['FlowId']            = $FlowId;
$TEMPARRAY['FileName']          = $FormName;
$TEMPARRAY['Time']              = time();
$DATATEMP                       = EncryptID(serialize($TEMPARRAY));
$URLTEMP                        = "data_export_json.php?DATA=".$DATATEMP;
$allFieldsImport['Default'][] = ['name' => "Import_Template", 'show'=>true, 'FieldTypeArray'=>[], 'type'=>'buttonurl', 'label' => __("Import_Template_File"), 'value' => $URLTEMP, 'placeholder' => "", 'helptext' => __(""), 'rules' => ['required' => false,'xs'=>12, 'sm'=>12, 'disabled' => false] ];

$allFieldsImport['Default'][] = ['name' => "Import_File", 'show'=>true, 'FieldTypeArray'=>[], 'type'=>'xlsx', 'label' => __("Step3_Upload_Excel_File"), 'value' => "", 'placeholder' => "", 'helptext' => __(""), 'rules' => ['required' => true,'xs'=>12, 'sm'=>12, 'disabled' => false] ];


foreach($allFieldsImport as $ModeName=>$allFieldItem) {
    foreach($allFieldItem as $ITEM) {
        $defaultValuesImport[$ITEM['name']] = $ITEM['value'];
    }
}

$allFieldsExport        = [];
foreach($allFieldsView as $ModeName=>$allFieldItem) {
    foreach($allFieldItem as $ITEM) {
        if($SettingMap["FieldExport_".$ITEM['name']]=="true" || $SettingMap["FieldExport_".$ITEM['name']]=="1" || $SettingMap["FieldExport_".$ITEM['code']]=="true" || $SettingMap["FieldExport_".$ITEM['code']]=="1")   {
            $allFieldsExport[$ModeName][] = $ITEM;
        }
    }
}

//print "TIME EXCEUTE 7:".(time()-$TIME_BEGIN)."<BR>\n";
//UpdateOtherTableFieldAfterFormSubmit($id);

if($_GET['action']=="option_multi_approval")  {
    $option_multi_approval = option_multi_approval_exection($_POST['selectedRows'], $_POST['multiReviewInputValue'], $Reminder=1, $UpdateOtherTableField=1);
    print $option_multi_approval;
    exit;
}

if($_GET['action']=="option_multi_refuse")  {
    $option_multi_refuse = option_multi_refuse_exection($_POST['selectedRows'], $_POST['multiReviewInputValue'], $Reminder=1, $UpdateOtherTableField=1);
    print $option_multi_refuse;
    exit;
}

if($_GET['action']=="option_multi_cancel")  {
    $option_multi_cancel = option_multi_cancel_exection($_POST['selectedRows'], $_POST['multiReviewInputValue'], $Reminder=1, $UpdateOtherTableField=1);
    print $option_multi_cancel;
    exit;
}

if($_GET['action']=="option_multi_setting_one")  {
    $option_multi_setting_one = option_multi_setting_one_exection($_POST['selectedRows'], $_POST['multiReviewInputValue'], $Reminder=1, $UpdateOtherTableField=1);
    print $option_multi_setting_one;
    exit;
}

if($_GET['action']=="option_multi_setting_two")  {
    $option_multi_setting_two = option_multi_setting_two_exection($_POST['selectedRows'], $_POST['multiReviewInputValue'], $Reminder=1, $UpdateOtherTableField=1);
    print $option_multi_setting_two;
    exit;
}



require_once('data_enginee_flow_lib_import_default_data.php');

//编辑页面时的启用字段列表
require_once('data_enginee_flow_lib_add_default_data.php');

require_once('data_enginee_flow_lib_edit_default_data.php');

require_once('data_enginee_flow_lib_edit_default_configsetting_data.php');

require_once('data_enginee_flow_lib_edit_default.php');

require_once('data_enginee_flow_lib_edit_default_configsetting_data.php');

require_once('data_enginee_flow_lib_view_default.php');

require_once('data_enginee_flow_lib_delete_array.php');


if( $_GET['action']=="edit_default_1" && $_GET['id']!="" )  {
  $functionNameIndividual = "plugin_".$TableName."_".$Step."_edit_default_1";
  if(function_exists($functionNameIndividual))  {
      $RS = $functionNameIndividual($_GET['id']);
  }
  exit;
}
if( $_GET['action']=="edit_default_1_data" && $_GET['id']!="" )  {
  $functionNameIndividual = "plugin_".$TableName."_".$Step."_edit_default_1_data";
  if(function_exists($functionNameIndividual))  {
      $RS = $functionNameIndividual($_GET['id']);
  }
  exit;
}

if( $_GET['action']=="edit_default_2" && $_GET['id']!="" )  {
  $functionNameIndividual = "plugin_".$TableName."_".$Step."_edit_default_2";
  if(function_exists($functionNameIndividual))  {
      $RS = $functionNameIndividual($_GET['id']);
  }
  exit;
}
if( $_GET['action']=="edit_default_2_data" && $_GET['id']!="" )  {
  $functionNameIndividual = "plugin_".$TableName."_".$Step."_edit_default_2_data";
  if(function_exists($functionNameIndividual))  {
      $RS = $functionNameIndividual($_GET['id']);
  }
  exit;
}


if($_GET['action']=="updateone")  {
    $id     = intval(DecryptID($_POST['id']));
    $field  = FilterString($_POST['field']);
    $value  = FilterString($_POST['value']);
    $primary_key = $MetaColumnNames[0];
    //Check Field Valid
    if($id>0&&$field!=""&&in_array($field,$MetaColumnNames)&&$primary_key!=$field&&($SettingMap['FieldEditable_'.$field]=='true' || $SettingMap['FieldEditable_'.$field]=='1')) {
        $sql    = "update $TableName set $field = '$value' where $primary_key = '$id'";
        $db->Execute($sql);
        //functionNameIndividual
        $functionNameIndividual = "plugin_".$TableName."_".$Step."_updateone";
        if(function_exists($functionNameIndividual))  {
            $functionNameIndividual($id);
        }
        //SystemLogRecord
        if(in_array($SettingMap['OperationLogGrade'],["EditAndDeleteOperation","AddEditAndDeleteOperation","AllOperation"]))  {
            SystemLogRecord("updateone", '', json_encode([$sql]));
        }
        $RS = [];
        $RS['status'] = "OK";
        $RS['msg'] = __("Update Success");
        print json_encode($RS);
        exit;
    }
    else {
        $RS = [];
        $RS['status'] = "ERROR";
        $RS['msg'] = __("Params Error");
        $RS['_GET'] = $_GET;
        $RS['_POST'] = $_POST;
        print json_encode($RS);
        exit;
    }
}

if($_GET['action']=="Reset_Password_Abcd1234")  {
    $selectedRows  = ForSqlInjection($_POST['selectedRows']);
    $selectedRows = explode(',',$selectedRows);
    $primary_key = $MetaColumnNames[0];
    foreach($selectedRows as $id) {
        $id     = intval(DecryptID($id));
        if($id>0)  {
            if(1)  {
                $sql    = "select * from $TableName where $primary_key = '$id'";
                $rs     = $db->Execute($sql);
                SystemLogRecord("Reset_Password_Abcd1234", '', json_encode($rs->fields));
            }
            $密码       = password_make("Abcd1234!");
            if(in_array("密码",$MetaColumnNames)) {
                $sql        = "update $TableName set 密码='$密码' where $primary_key = '$id'";
                $db->Execute($sql);
            }
            if(in_array("PASSWORD",$MetaColumnNames)) {
                $sql        = "update $TableName set `PASSWORD`='$密码' where $primary_key = '$id'";
                $db->Execute($sql);
            }
            //functionNameIndividual
            //$functionNameIndividual = "plugin_".$TableName."_".$Step."_delete_array";
            //if(function_exists($functionNameIndividual))  {
            //    $functionNameIndividual($id);
            //}
        }
    }
    $RS = [];
    $RS['status'] = "OK";
    if($SettingMap['Debug_Sql_Show_On_Api']=="Yes" && in_array($GLOBAL_USER->USER_ID, ['admin', 'admin001']))  $RS['sql'] = $sql;
    $RS['msg'] = __("Change Password Success");
    print json_encode($RS);
    exit;
}

if($_GET['action']=="Reset_Password_ID_Last6PinYin")  {
    $selectedRows  = ForSqlInjection($_POST['selectedRows']);
    $selectedRows = explode(',',$selectedRows);
    $primary_key = $MetaColumnNames[0];
    $PasswordChangeLog = [];
    foreach($selectedRows as $id) {
        $id     = intval(DecryptID($id));
        if($id>0)  {
            $sql    = "select * from $TableName where $primary_key = '$id'";
            $rs     = $db->Execute($sql);
            SystemLogRecord("Reset_Password_ID_Last6PinYin", '', json_encode($rs->fields));
            $身份证件号 = $rs->fields['身份证件号'];
            $姓名       = $rs->fields['姓名'];

            //Decrypt Field Value
            $SettingTempMap                 = $AllFieldsMap['身份证件号']['Setting'];
            $DataFieldEncryptMethod         = $SettingTempMap['DataFieldEncryptMethod'];
            $DataFieldEncryptKey            = $SettingTempMap['DataFieldEncryptKey'];
            if($DataFieldEncryptMethod==1&&$DataFieldEncryptKey!="") {
                $身份证件号     = DecryptIDStorage($身份证件号, $DataFieldEncryptKey);
            }

            if(strlen($身份证件号)>6) {
                $身份证件号6  = substr($身份证件号,-6);
                $pinyin      = new Pinyin();
                $身份证件号6 .= str_replace(" ", "", strtolower($pinyin->abbr($姓名)));
            }
            else {
                $身份证件号6  = "Abcd1234!";
            }
            $PasswordChangeLog[] = $身份证件号6;
            $密码       = password_make($身份证件号6);
            if(in_array("密码",$MetaColumnNames)) {
                $sql        = "update $TableName set 密码='$密码' where $primary_key = '$id'";
                $db->Execute($sql);
            }
            if(in_array("PASSWORD",$MetaColumnNames)) {
                $sql        = "update $TableName set `PASSWORD`='$密码' where $primary_key = '$id'";
                $db->Execute($sql);
            }
            //functionNameIndividual
            //$functionNameIndividual = "plugin_".$TableName."_".$Step."_delete_array";
            //if(function_exists($functionNameIndividual))  {
            //    $functionNameIndividual($id);
            //}
        }
    }
    $RS = [];
    $RS['status']       = "OK";
    $RS['msg']          = __("Change Password Success");
    if($SettingMap['Debug_Sql_Show_On_Api']=="Yes" && in_array($GLOBAL_USER->USER_ID, ['admin', 'admin001']))  $RS['sql'] = $sql;
    if($SettingMap['Debug_Sql_Show_On_Api']=="Yes" && in_array($GLOBAL_USER->USER_ID, ['admin', 'admin001']))  $RS['PasswordChangeLog'] = $PasswordChangeLog;
    print json_encode($RS);
    exit;
}


//列表页面时的启用字段列表
$init_default_columns   = [];
$columnsactions         = [];
if(in_array('View',$Actions_In_List_Row_Array)) {
    $columnsactions[]   = ['action'=>'view_default','text'=>__('View'),'mdi'=>'mdi:eye-outline'];
}
if(in_array('Edit',$Actions_In_List_Row_Array)) {
    $columnsactions[]   = ['action'=>'edit_default','text'=>$SettingMap['Rename_List_Edit_Button'],'mdi'=>'mdi:pencil-outline'];
}
if(in_array('Delete',$Actions_In_List_Row_Array)) {
    $columnsactions[]   = ['action'=>'delete_array','text'=>$SettingMap['Rename_List_Delete_Button'],'mdi'=>'mdi:delete-outline','double_check'=>__('Do you want to delete this item?')];
}
$init_default_columns[] = ['flex' => 0.1, 'minWidth' => 120, 'sortable' => false, 'field' => "actions", 'headerName' => __("Actions"), 'show'=>true, 'type'=>'actions', 'actions' => $columnsactions];


$ApprovalNodeFieldsArray        = explode(',',$SettingMap['ApprovalNodeFields']);
$ApprovalNodeFieldsArrayFlip    = array_flip($ApprovalNodeFieldsArray);
if($SettingMap['ApprovalNodeTitle']!="")   {
    $RS['init_default']['ApprovalNodeFields']['AllNodes']       = $ApprovalNodeFieldsArray;
    $RS['init_default']['ApprovalNodeFields']['CurrentNode']    = $SettingMap['ApprovalNodeCurrentField'];
    $RS['init_default']['ApprovalNodeFields']['ActiveStep']     = $ApprovalNodeFieldsArrayFlip[$SettingMap['ApprovalNodeCurrentField']];
    $RS['init_default']['ApprovalNodeFields']['ApprovalNodeTitle']  = $SettingMap['ApprovalNodeTitle'];
}
else    {
    $RS['init_default']['ApprovalNodeFields']['AllNodes']       = [];
    $RS['init_default']['ApprovalNodeFields']['CurrentNode']    = "";
    $RS['init_default']['ApprovalNodeFields']['ActiveStep']     = 0;
    $RS['init_default']['ApprovalNodeFields']['ApprovalNodeTitle']  = "";
}



$ApprovalNodeFieldsArray = explode(',',$SettingMap['ApprovalNodeFields']);
$ApprovalNodeFieldsHidden = [];
$ApprovalNodeFieldsStatus = [];
foreach($ApprovalNodeFieldsArray as $TempField) {
    //$ApprovalNodeFieldsHidden[] = $TempField."审核状态";
    //$ApprovalNodeFieldsHidden[] = $TempField."申请时间";
    //$ApprovalNodeFieldsHidden[] = $TempField."申请人";
    $ApprovalNodeFieldsHidden[] = $TempField."审核时间";
    $ApprovalNodeFieldsHidden[] = $TempField."审核人";
    $ApprovalNodeFieldsHidden[] = $TempField."审核意见";
    $ApprovalNodeFieldsStatus[$TempField."审核状态"] = $TempField."审核状态";
}
$ApprovalNodeFieldsStatus = array_keys($ApprovalNodeFieldsStatus);
$searchField = [];
$groupField = [];
$FieldNameToType = [];
$UpdateFields = [];
foreach($AllFieldsFromTable as $Item)  {
    $FieldName      = $Item['FieldName'];
    $EnglishName    = $Item['EnglishName'];
    $ShowType       = $Item['ShowType'];
    $IsSearch       = $Item['IsSearch'];
    $IsGroupFilter  = $Item['IsGroupFilter'];
    $ColumnWidth    = intval($Item['ColumnWidth']);
    $IsHiddenGroupFilter = $Item['IsHiddenGroupFilter'];
    $CurrentFieldType = $AllShowTypesArray[$ShowType]['LIST'];
    $CurrentFieldTypeArray = explode(':',$CurrentFieldType);
    $FieldNameToType[$FieldName] = $CurrentFieldType;
    //print $FieldName.":".$ShowType.":".$CurrentFieldType.'<BR>';
    if(in_array($FieldName,$ApprovalNodeFieldsHidden)) {
        continue;
    }

    global $GLOBAL_LANGUAGE;
    switch($GLOBAL_LANGUAGE) {
        case 'zhCN':
            $ShowTextName    = $Item['ChineseName'];
            break;
        case 'enUS':
            $ShowTextName    = $Item['EnglishName'];
            break;
        default:
            $ShowTextName    = $Item['EnglishName'];
            break;
    }

    $editable = false;
    if($SettingMap['FieldEditable_'.$FieldName]=='true' || $SettingMap['FieldEditable_'.$FieldName]=='1')   {
        $editable = true;
        $UpdateFields[] = $FieldName;
    }

    //Filter Field Type
    $FieldTypeInFlow = $SettingMap['FieldType_'.$FieldName];
    $FieldTypeInFlow_Map = [];
    switch($FieldTypeInFlow)   {
        case 'View_Use_ListAddEdit_NotUse':
        case 'Disable':
        case '':
            $CurrentFieldTypeArray[0] = "Disable";
            break;
    }
    //print $FieldName.":".$FieldTypeInFlow." ".$CurrentFieldTypeArray[0]."\n";

    switch($CurrentFieldTypeArray[0])   {
        case 'Disable':
        case '':
            break;
        case 'api1':
            $init_default_columns[] = ['flex' => 0.1, 'type'=>'api', 'minWidth' => $ColumnWidth, 'maxWidth' => $ColumnWidth+100, 'field' => $FieldName, 'headerName' => $ShowTextName, 'show'=>true, 'renderCell' => NULL, 'editable'=>$editable,'apimdi'=>'mdi:chart-donut','apicolor'=>'info.main', 'apiaction' => "edit_default_1"];
            break;
        case 'api2':
            $init_default_columns[] = ['flex' => 0.1, 'type'=>'api', 'minWidth' => $ColumnWidth, 'maxWidth' => $ColumnWidth+100, 'field' => $FieldName, 'headerName' => $ShowTextName, 'show'=>true, 'renderCell' => NULL, 'editable'=>$editable,'apimdi'=>'mdi:cog-outline','apicolor'=>'info.main', 'apiaction' => "edit_default_2"];
            break;
        case 'api3':
            $init_default_columns[] = ['flex' => 0.1, 'type'=>'api', 'minWidth' => $ColumnWidth, 'maxWidth' => $ColumnWidth+100, 'field' => $FieldName, 'headerName' => $ShowTextName, 'show'=>true, 'renderCell' => NULL, 'editable'=>$editable,'apimdi'=>'mdi:border-bottom','apicolor'=>'info.main', 'apiaction' => "edit_default_3"];
            break;
        case 'api4':
            $init_default_columns[] = ['flex' => 0.1, 'type'=>'api', 'minWidth' => $ColumnWidth, 'maxWidth' => $ColumnWidth+100, 'field' => $FieldName, 'headerName' => $ShowTextName, 'show'=>true, 'renderCell' => NULL, 'editable'=>$editable,'apimdi'=>'mdi:cellphone','apicolor'=>'info.main', 'apiaction' => "edit_default_4"];
            break;
        case 'api5':
            $init_default_columns[] = ['flex' => 0.1, 'type'=>'api', 'minWidth' => $ColumnWidth, 'maxWidth' => $ColumnWidth+100, 'field' => $FieldName, 'headerName' => $ShowTextName, 'show'=>true, 'renderCell' => NULL, 'editable'=>$editable,'apimdi'=>'mdi:message-bulleted','apicolor'=>'info.main', 'apiaction' => "edit_default_5"];
            break;
        case 'api6':
            $init_default_columns[] = ['flex' => 0.1, 'type'=>'api', 'minWidth' => $ColumnWidth, 'maxWidth' => $ColumnWidth+100, 'field' => $FieldName, 'headerName' => $ShowTextName, 'show'=>true, 'renderCell' => NULL, 'editable'=>$editable,'apimdi'=>'mdi:chart-donut','apicolor'=>'info.main', 'apiaction' => "edit_default_6"];
            break;
        case 'tablefilter':
        case 'tablefiltercolor':
        case 'autocomplete':
            $init_default_columns[] = ['flex' => 0.1, 'type'=>$CurrentFieldTypeArray[0], 'minWidth' => $ColumnWidth, 'maxWidth' => $ColumnWidth+100, 'field' => $FieldName, 'headerName' => $ShowTextName, 'show'=>true, 'renderCell' => NULL, 'editable'=>$editable];
            break;
        case 'autocompletemulti':
            $init_default_columns[] = ['flex' => 0.1, 'type'=>$CurrentFieldTypeArray[0], 'minWidth' => $ColumnWidth, 'maxWidth' => $ColumnWidth+200, 'field' => $FieldName, 'headerName' => $ShowTextName, 'show'=>true, 'renderCell' => NULL, 'editable'=>$editable];
            break;
        case 'radiogroup':
        case 'radiogroupcolor':
            $init_default_columns[] = ['flex' => 0.1, 'type'=>$CurrentFieldTypeArray[0], 'minWidth' => $ColumnWidth, 'maxWidth' => $ColumnWidth+100, 'field' => $FieldName, 'headerName' => $ShowTextName, 'show'=>true, 'renderCell' => NULL, 'editable'=>$editable];
            break;
        case 'avatar':
            $init_default_columns[] = ['flex' => 0.1, 'type'=>$CurrentFieldTypeArray[0], 'minWidth' => $ColumnWidth, 'maxWidth' => $ColumnWidth+100, 'field' => $FieldName, 'headerName' => $ShowTextName, 'show'=>true, 'renderCell' => NULL, 'editable'=>$editable];
            break;
        case 'images':
        case 'images2':
            $init_default_columns[] = ['flex' => 0.1, 'type'=>$CurrentFieldTypeArray[0], 'minWidth' => $ColumnWidth, 'maxWidth' => $ColumnWidth+100, 'field' => $FieldName, 'headerName' => $ShowTextName, 'show'=>true, 'renderCell' => NULL, 'editable'=>$editable];
            break;
        case 'files':
        case 'files2':
            $init_default_columns[] = ['flex' => 0.1, 'type'=>$CurrentFieldTypeArray[0], 'minWidth' => $ColumnWidth, 'maxWidth' => $ColumnWidth+100, 'field' => $FieldName, 'headerName' => $ShowTextName, 'show'=>true, 'renderCell' => NULL, 'editable'=>$editable];
            break;
        case 'file':
            $init_default_columns[] = ['flex' => 0.1, 'type'=>$CurrentFieldTypeArray[0], 'minWidth' => $ColumnWidth, 'maxWidth' => $ColumnWidth+100, 'field' => $FieldName, 'headerName' => $ShowTextName, 'show'=>true, 'renderCell' => NULL, 'editable'=>$editable];
            break;
        case 'xlsx':
            $init_default_columns[] = ['flex' => 0.1, 'type'=>$CurrentFieldTypeArray[0], 'minWidth' => $ColumnWidth, 'maxWidth' => $ColumnWidth+100, 'field' => $FieldName, 'headerName' => $ShowTextName, 'show'=>true, 'renderCell' => NULL, 'editable'=>$editable];
            break;
        case 'ExternalUrl':
            $init_default_columns[] = ['flex' => 0.1, 'type'=>$CurrentFieldTypeArray[0], "href"=>"", "target"=>"_blank", 'minWidth' => $ColumnWidth, 'maxWidth' => $ColumnWidth+100, 'field' => $FieldName, 'headerName' => $ShowTextName, 'show'=>true, 'renderCell' => NULL, 'editable'=>$editable];
            break;
        default:
            $FieldType = "string";
            if(in_array($FieldName,$ApprovalNodeFieldsStatus))  {
                $FieldType = "approvalnode";
                $ColumnWidth = 265;
                $rowHeight = 45;
            }
            //print_R($FieldName);
            //print_R($ApprovalNodeFieldsStatus);
            $init_default_columns[] = ['flex' => 0.1, 'type'=>$FieldType, 'minWidth' => $ColumnWidth, 'maxWidth' => $ColumnWidth+100, 'field' => $FieldName, 'headerName' => $ShowTextName, 'show'=>true, 'renderCell' => NULL, 'editable'=>$editable];
            break;
    }
    if($IsSearch==1&&($SettingMap['FieldSearch_'.$FieldName]=='true'||$SettingMap['FieldSearch_'.$FieldName]=='1'))   {
        $searchField[] = ['label' => $ShowTextName, 'value' => $FieldName];
    }
    if($SettingMap['FieldGroup_'.$FieldName]=='true'||$SettingMap['FieldGroup_'.$FieldName]=='1')   { //$IsGroupFilter==1&&
        $groupField[] = $FieldName;
    }

}

//Search Field
$RS['init_default']['searchFieldArray'] = $searchField;
$RS['init_default']['searchFieldText'] = __("Search Item");
if($_REQUEST['searchFieldName']=="") $_REQUEST['searchFieldName'] = $MetaColumnNames[1];
$RS['init_default']['searchFieldName'] = ForSqlInjection($_REQUEST['searchFieldName']);

$searchFieldName     = ForSqlInjection($_REQUEST['searchFieldName']);
$searchFieldValue    = ForSqlInjection($_REQUEST['searchFieldValue']);
if ($searchFieldName != "" && $searchFieldValue != "" && in_array($searchFieldName, $MetaColumnNames) ) {
    $AddSql .= " and ($searchFieldName like '%" . $searchFieldValue . "%')";
}
$RS['init_default']['searchFieldValue'] = ForSqlInjection($_REQUEST['searchFieldValue']);

//Extra_Priv_Filter_Field
Extra_Priv_Filter_Field_To_SQL();

$functionNameIndividual = "plugin_".$TableName."_".$Step."_init_default";
if(function_exists($functionNameIndividual))  {
    $functionNameIndividual($id);
}

//Group Filter
$RS['init_default']['filter'] = [];
foreach($groupField as $FieldName) {
    $sql    = "select $FieldName as name, $FieldName as value, count(*) AS num from $TableName $AddSql group by $FieldName order by $FieldName desc";
    $rs     = $db->Execute($sql) or print $sql;
    $rs_a   = $rs->GetArray();
    $ShowType   = $AllFieldsMap[$FieldName]['ShowType'];
    $FieldType  = $AllShowTypesArray[$ShowType]['LIST'];
    $FieldTypeArray = explode(":",$FieldType);
    switch($FieldTypeArray[0]) {
        case 'tablefilter':
        case 'tablefiltercolor':
        case 'radiogroup':
        case 'radiogroupcolor':
            $TempTableName      = $FieldTypeArray[1];
            $TempKeyIndex       = $FieldTypeArray[2];
            $TempValueIndex     = $FieldTypeArray[3];
            if($TempKeyIndex!=$TempValueIndex)  {
                $TempColumnNames    = GLOBAL_MetaColumnNames($TempTableName);
                for($i=0;$i<sizeof($rs_a);$i++)  {
                    if($rs_a[$i]['value']!="")   {
                        $rs_a[$i]['name'] = returntablefield($TempTableName,$TempColumnNames[$TempKeyIndex],$rs_a[$i]['value'],$TempColumnNames[$TempValueIndex])[$TempColumnNames[$TempValueIndex]];
                    }
                    else {
                        $rs_a[$i]['name']   = __("NULL");
                        $rs_a[$i]['value']  = "NULL";
                    }
                }
            }
            break;
    }
    for($i=0;$i<sizeof($rs_a);$i++)  {
        if($rs_a[$i]['value']=="")   {
            $rs_a[$i]['name']   = __("NULL");
            $rs_a[$i]['value']  = "NULL";
        }
    }
    $ALL_NUM = 0;
    foreach($rs_a as $Item) {
        $ALL_NUM += $Item['num'];
    }
    global $GLOBAL_LANGUAGE;
    switch($GLOBAL_LANGUAGE) {
        case 'zhCN':
            $ShowTextName    = $AllFieldsMap[$FieldName]['ChineseName'];
            break;
        case 'enUS':
            $ShowTextName    = $AllFieldsMap[$FieldName]['EnglishName'];
            break;
        default:
            $ShowTextName    = $AllFieldsMap[$FieldName]['EnglishName'];
            break;
    }
    array_unshift($rs_a,['name'=>__('All Data'), 'value'=>'All Data', 'num'=>$ALL_NUM]);
    if($_POST[$FieldName]!="") {
        $selected = ForSqlInjection($_POST[$FieldName]);
    }
    else if(strpos($FieldName, "学期") !== false) {
        $selected = getCurrentXueQi();
        global $AddSql;
        if(in_array("当前学期", $MetaColumnNames) && !isset($_GET['当前学期']) ) {
            $AddSql .= " and 当前学期='$selected'";
        }
        elseif(in_array("学期", $MetaColumnNames) && !isset($_GET['学期']) ) {
            $AddSql .= " and 学期='$selected'";
        }
        elseif(in_array("学期名称", $MetaColumnNames) && !isset($_GET['学期名称']) ) {
            $AddSql .= " and 学期名称='$selected'";
        }
    }
    else {
        $selected = "All Data";
    }
    $RS['init_default']['filter'][] = ['name' => $FieldName, 'text' => $ShowTextName, 'list' => $rs_a, 'selected' => $selected];
    //Sql Filter
    if(is_array($_REQUEST[$FieldName]))         {
        $TempArray = $_REQUEST[$FieldName];
        $NewFilterArray = [];
        $IsHaveAllData  = 0;
        foreach($TempArray as $Temp) {
            if ($Temp != "" && $Temp != "NULL" && $Temp != "All Data") {
                $NewFilterArray[] = ForSqlInjection($Temp);
            }
            else if ($Temp == "NULL") {
                $NewFilterArray[] = '';
            }
            else if ($Temp == "All Data") {
                $IsHaveAllData = 1;
            }
        }
        if($IsHaveAllData) {
            //Get All Data
        }
        elseif(sizeof($NewFilterArray)==1) {
            $AddSql .= " and `$FieldName` = '".$NewFilterArray[0]."'";
        }
        elseif(sizeof($NewFilterArray)>0) {
            $AddSql .= " and `$FieldName` in ('".join("','",$NewFilterArray)."')";
        }
    }
    else    {
        $SqlFilterValue = ForSqlInjection($_REQUEST[$FieldName]);
        if ($SqlFilterValue != "" && $SqlFilterValue != "NULL" && $SqlFilterValue != "All Data") {
            $AddSql .= " and (`$FieldName` = '" . $SqlFilterValue . "')";
        }
        else if ($SqlFilterValue == "NULL") {
            $AddSql .= " and (`$FieldName` = '')";
        }
        else {
            //Get All Data
        }
    }
}

//print "TIME EXCEUTE 8:".(time()-$TIME_BEGIN)."<BR>\n";

$pageNumberArray = $SettingMap['pageNumberArray'];
if($pageNumberArray=="" || true) {
    $pageNumberArray = [10,15,20,30,40,50,100];
}
$page       = intval($_REQUEST['page']);
$pageSize   = intval($_REQUEST['pageSize']);
if(!in_array($pageSize,$pageNumberArray) || $pageSize == 10)  {
	$pageSize = intval($SettingMap['Page_Number_In_List']);
}
$fromRecord = $page * $pageSize;


//print "TIME EXCEUTE 9:".(time()-$TIME_BEGIN)."<BR>\n";
if($FromInfo['TableName']!="")   {
    $RS['init_default']['searchtitle']  = $FromInfo['FullName'];
}
else {
    $RS['init_default']['searchtitle']  = "Unknown Form";
}
$RS['init_default']['searchtitle']  = $SettingMap['List_Title_Name'];

$RS['init_default']['primarykey']   = $MetaColumnNames[0];


//print "TIME EXCEUTE 10:".(time()-$TIME_BEGIN)."<BR>\n";

if($_REQUEST['sortColumn']=="")   {
    //order default
    $order_by_array = [];
    $Default_Order_Method_By_Field_One = $SettingMap['Default_Order_Method_By_Field_One'];
    $Default_Order_Method_By_Desc_One = $SettingMap['Default_Order_Method_By_Desc_One'];
    if(in_array($Default_Order_Method_By_Field_One, $MetaColumnNames))  {
        $order_by_array[] = "".$Default_Order_Method_By_Field_One." ".$Default_Order_Method_By_Desc_One;
    }
    $Default_Order_Method_By_Field_Two = $SettingMap['Default_Order_Method_By_Field_Two'];
    $Default_Order_Method_By_Desc_Two = $SettingMap['Default_Order_Method_By_Desc_Two'];
    if(in_array($Default_Order_Method_By_Field_Two, $MetaColumnNames))  {
        $order_by_array[] = "".$Default_Order_Method_By_Field_Two." ".$Default_Order_Method_By_Desc_Two;
    }
    $Default_Order_Method_By_Field_Three = $SettingMap['Default_Order_Method_By_Field_Three'];
    $Default_Order_Method_By_Desc_Three = $SettingMap['Default_Order_Method_By_Desc_Three'];
    if(in_array($Default_Order_Method_By_Field_Three, $MetaColumnNames))  {
        $order_by_array[] = "".$Default_Order_Method_By_Field_Three." ".$Default_Order_Method_By_Desc_Three;
    }
    if(sizeof($order_by_array)>0) {
        $orderby = "order by ".join(',',$order_by_array)."";
    }
}
else {
    if($_REQUEST['sortMethod']=="desc"&&in_array($_REQUEST['sortColumn'], $MetaColumnNames)) {
        $orderby = "order by `".$_REQUEST['sortColumn']."` desc";
    }
    elseif(in_array($_REQUEST['sortColumn'], $MetaColumnNames)) {
        $orderby = "order by `".$_REQUEST['sortColumn']."` asc";
    }
}


$ForbiddenSelectRow = [];
$ForbiddenViewRow   = [];
$ForbiddenEditRow   = [];
$ForbiddenDeleteRow = [];
$ForbiddenSelectRowOriginal = [];
$ForbiddenViewRowOriginal   = [];
$ForbiddenEditRowOriginal   = [];
$ForbiddenDeleteRowOriginal = [];

//Get Total Records Number
$sql    = "select count(*) AS NUM from $TableName " . $AddSql . "";
$sqlList[] = $sql;
$rs     = $db->Execute($sql);
$RS['init_default']['total'] = intval($rs->fields['NUM']);

//Get All Data
$sql         = "select * from $TableName " . $AddSql . " $orderby limit $fromRecord,$pageSize";
$sqlList[]   = $sql;
//print $sql;
$NewRSA = [];
$rs     = $db->Execute($sql) or print $sql;
$rs_a   = $rs->GetArray();
$FieldDataColorValue = [];
$GetAllIDList = [];
$MobileEndData = [];
foreach ($rs_a as $Line) {
    $Line2              = $Line;
    $OriginalID         = $Line['id'];
    $GetAllIDList[]     = $Line['id'];
    $Line['id2']        = $Line['id'];
    $Line['id']         = EncryptID($Line['id']);

    $MobileEndItem                                      = [];
    //List Template
    $MobileEndItem['MobileEndFirstLine']                    = strval($SettingMap['MobileEndFirstLine']);
    $MobileEndItem['MobileEndSecondLineLeft']               = strval($SettingMap['MobileEndSecondLineLeft']);
    $MobileEndItem['MobileEndSecondLineLeftColorField']     = strval($SettingMap['MobileEndSecondLineLeftColorField']);
    $MobileEndItem['MobileEndSecondLineLeftColorRule']      = strval($SettingMap['MobileEndSecondLineLeftColorRule']);
    $MobileEndItem['MobileEndSecondLineRight']              = strval($SettingMap['MobileEndSecondLineRight']);
    $MobileEndItem['MobileEndSecondLineRightColorField']    = strval($SettingMap['MobileEndSecondLineRightColorField']);
    $MobileEndItem['MobileEndSecondLineRightColorRule']     = strval($SettingMap['MobileEndSecondLineRightColorRule']);
    //News Template
    $MobileEndItem['MobileEndNewsTitle']                = strval($Line[$SettingMap['MobileEndNewsTitle']]);
    $MobileEndItem['MobileEndNewsGroup']                = strval($Line[$SettingMap['MobileEndNewsGroup']]);
    $MobileEndItem['MobileEndNewsContent']              = strip_tags($Line[$SettingMap['MobileEndNewsContent']]);
    $MobileEndItem['MobileEndNewsReadCounter']          = strval($Line[$SettingMap['MobileEndNewsReadCounter']]);
    $MobileEndItem['MobileEndNewsLikeCounter']          = strval($Line[$SettingMap['MobileEndNewsLikeCounter']]);
    $MobileEndItem['MobileEndNewsFavoriteCounter']      = strval($Line[$SettingMap['MobileEndNewsFavoriteCounter']]);
    $MobileEndItem['MobileEndNewsReadUsers']            = strval($Line[$SettingMap['MobileEndNewsReadUsers']]);

    $MobileEndItem['MobileEndSchoolmateCity']           = strval($Line[$SettingMap['MobileEndSchoolmateCity']]);
    $MobileEndItem['MobileEndSchoolmateCompany']        = strval($Line[$SettingMap['MobileEndSchoolmateCompany']]);
    $MobileEndItem['MobileEndSchoolmateIndustry']       = strval($Line[$SettingMap['MobileEndSchoolmateIndustry']]);
    $MobileEndItem['MobileEndSchoolmateFirstYear']      = strval($Line[$SettingMap['MobileEndSchoolmateFirstYear']]);
    $MobileEndItem['MobileEndSchoolmateLastYear']       = strval($Line[$SettingMap['MobileEndSchoolmateLastYear']]);
    $MobileEndItem['MobileEndSchoolmateAcademic']       = strval($Line[$SettingMap['MobileEndSchoolmateAcademic']]);
    $MobileEndItem['MobileEndSchoolmateLastActivity']   = strval($Line[$SettingMap['MobileEndSchoolmateLastActivity']]);

    $MobileEndNewsCreator = strval(returntablefield("data_user","USER_ID",$Line[$SettingMap['MobileEndNewsCreator']],"USER_NAME")["USER_NAME"]);;
    if($MobileEndNewsCreator!="") {
        $MobileEndItem['MobileEndNewsCreator']          = $MobileEndNewsCreator;
    }
    else {
        $MobileEndItem['MobileEndNewsCreator']          = $Line[$SettingMap['MobileEndNewsCreator']];
    }
    $MobileEndItem['MobileEndNewsCreatorGroup']         = strval($Line[$SettingMap['MobileEndNewsCreatorGroup']]);
    $MobileEndItem['MobileEndActivityFee']              = strval($Line[$SettingMap['MobileEndActivityFee']]);
    $MobileEndItem['MobileEndActivityContact']          = strval($Line[$SettingMap['MobileEndActivityContact']]);
    $MobileEndItem['MobileEndNewsEnrollment']           = strval($Line[$SettingMap['MobileEndNewsEnrollment']]);
    $MobileEndItem['MobileEndNewsLocation']             = strval($Line[$SettingMap['MobileEndNewsLocation']]);
    $MobileEndItem['MobileEndNewsLocation2']            = strval($Line[$SettingMap['MobileEndNewsLocation2']]);
    $MobileEndItem['MobileEndNewsCreateTime']           = substr($Line[$SettingMap['MobileEndNewsCreateTime']],5,11);
    if($MobileEndItem['MobileEndNewsLocation']!="") {
        $TempArray = explode('-', $MobileEndItem['MobileEndNewsLocation']);
        $MobileEndItem['MobileEndNewsLocation']         = $TempArray[1]." ".$TempArray[2];
        $MobileEndItem['MobileEndNewsCreateTime']       = substr($Line[$SettingMap['MobileEndNewsCreateTime']],5,5);
    }
    $MobileEndItem['MobileEndNewsProcess']              = strval($Line[$SettingMap['MobileEndNewsProcess']]);
    $MobileEndItem['MobileEndNewsTopAvator']            = strval($Line[$SettingMap['MobileEndNewsTopAvator']]);
    $MobileEndItem['MobileEndActivityEnrollEndDate']    = strval($Line[$SettingMap['MobileEndActivityEnrollEndDate']]);
    $MobileEndItem['MobileEndActivityDate']             = strval($Line[$SettingMap['MobileEndActivityDate']]);
    if($MobileEndItem['MobileEndActivityEnrollEndDate']!="") {
        if($MobileEndItem['MobileEndActivityEnrollEndDate']<date("Y-m-d")) {
            $MobileEndItem['MobileEndActivityStatus'] = "报名结束";
        }
        else {
            $MobileEndItem['MobileEndActivityStatus'] = "报名中";
        }
    }
    if($MobileEndItem['MobileEndActivityDate']!="") {
        if($MobileEndItem['MobileEndActivityDate']==date("Y-m-d")) {
            $MobileEndItem['MobileEndActivityStatus'] = "进行中";
        }
    }

    if($SettingMap['MobileEndIconType']=="ImageField" && $Line[$SettingMap['MobileEndIconField']] != "") {
        $TempValue = AttachFieldValueToUrl($TableName,$OriginalID,$SettingMap['MobileEndIconField'],'images',$Line[$SettingMap['MobileEndIconField']]);
        $Line[$SettingMap['MobileEndIconField']]    = $TempValue[0]['webkitRelativePath'];
    }
    else if($SettingMap['MobileEndIconType']=="UserAvator" && $Line[$SettingMap['MobileEndIconField']] != "") {
        //$TempValue = AttachFieldValueToUrl($TableName,$OriginalID,$SettingMap['MobileEndIconField'],'avatar',$Line[$SettingMap['MobileEndIconField']]);
        //$Line[$SettingMap['MobileEndIconField']]    = $TempValue[0]['webkitRelativePath'];
    }
    else if($Line[$SettingMap['MobileEndIconField']]=="") {
        $Line[$SettingMap['MobileEndIconField']]    = "/images/wechat/logo_icampus_left.png";
    }
    $MobileEndItem['MobileEndNewsLeftImage']            = strval($Line[$SettingMap['MobileEndIconField']]);
    //Notification Template

    foreach($Line as $FieldName=>$FieldValue) {
        if($FieldValue=="1971-01-01" || $FieldValue=="1971-01-01 00:00:00" || $FieldValue=="1971-01")  {
            $Line[$FieldName] = "";
        }
        //Decrypt Field Value
        $SettingTempMap                 = $AllFieldsMap[$FieldName]['Setting'];
        $DataFieldEncryptMethod         = $SettingTempMap['DataFieldEncryptMethod'];
        $DataFieldEncryptKey            = $SettingTempMap['DataFieldEncryptKey'];
        if($DataFieldEncryptMethod==1&&$DataFieldEncryptKey!="") {
            $Line[$FieldName]           = DecryptIDStorage($Line[$FieldName], $DataFieldEncryptKey);
        }
        // filter data to show on the list page -- begin
        $CurrentFieldType = $FieldNameToType[$FieldName];
        $CurrentFieldTypeArray = explode(':',$CurrentFieldType);
        switch($CurrentFieldTypeArray[0])   {
            case 'radiogroup':
            case 'radiogroupcolor':
            case 'tablefilter':
            case 'tablefiltercolor':
            case 'autocomplete':
                $TableNameTemp      = $CurrentFieldTypeArray[1];
                $KeyField           = $CurrentFieldTypeArray[2];
                $ValueField         = $CurrentFieldTypeArray[3];
                $DefaultValue       = $CurrentFieldTypeArray[4];
                $WhereField         = ForSqlInjection($CurrentFieldTypeArray[5]);
                $WhereValue         = ForSqlInjection($CurrentFieldTypeArray[6]);
                $MetaColumnNamesTemp    = GLOBAL_MetaColumnNames($TableNameTemp);
                if($WhereField!="" && $WhereValue!="" && $MetaColumnNamesTemp[$KeyField]!="" && $Line[$FieldName]!="") {
                    $sql = "select `".$MetaColumnNamesTemp[$ValueField]."` as label from $TableNameTemp where $WhereField = '".$WhereValue."' and `".$MetaColumnNamesTemp[$KeyField]."`='".ForSqlInjection($Line[$FieldName])."' ;";
                    $rs = $db->Execute($sql) or print($sql);
                    $Line[$FieldName] = $rs->fields['label'];
                    if($Line[$FieldName]=="") $Line[$FieldName] = $WhereValue;
                    $FieldDataColorValue[$FieldName][$Line[$FieldName]] = "#";
                    //print "TIME EXCEUTE 12:".(time()-$TIME_BEGIN)." ".$Line[$FieldName]." $sql <BR>\n";
                }
                elseif($MetaColumnNamesTemp[$KeyField]!="" && $Line[$FieldName]!="")    {
                    $sql = "select `".$MetaColumnNamesTemp[$ValueField]."` as label from $TableNameTemp where `".$MetaColumnNamesTemp[$KeyField]."`='".ForSqlInjection($Line[$FieldName])."' ;";
                    $rs = $db->Execute($sql) or print($sql);
                    if($rs->fields['label']!="")  {
                        $Line[$FieldName] = $rs->fields['label'];
                    }
                    $FieldDataColorValue[$FieldName][$Line[$FieldName]] = "#";
                    //print "TIME EXCEUTE 13:".(time()-$TIME_BEGIN)." ".$Line[$FieldName]." $sql <BR>\n";
                }
                break;
            case 'autocompletemulti':
                $TableNameTemp      = $CurrentFieldTypeArray[1];
                $KeyField           = $CurrentFieldTypeArray[2];
                $ValueField         = $CurrentFieldTypeArray[3];
                $DefaultValue       = $CurrentFieldTypeArray[4];
                $WhereField         = ForSqlInjection($CurrentFieldTypeArray[5]);
                $WhereValue         = ForSqlInjection($CurrentFieldTypeArray[6]);
                $MetaColumnNamesTemp    = GLOBAL_MetaColumnNames($TableNameTemp);
                $MultiValueArray        = explode(',',$Line[$FieldName]);
                $MultiValueRS           = [];
                foreach($MultiValueArray as $MultiValue) {
                    if($WhereField!="" && $WhereValue!="" && $MetaColumnNamesTemp[$KeyField]!="" && $MultiValue!="") {
                        $sql = "select `".$MetaColumnNamesTemp[$ValueField]."` as label from $TableNameTemp where $WhereField = '".$WhereValue."' and `".$MetaColumnNamesTemp[$KeyField]."`='".ForSqlInjection($MultiValue)."' ;";
                        $rs = $db->Execute($sql) or print($sql);
                        $MultiValueRS[] = $rs->fields['label'];
                    }
                    elseif($MetaColumnNamesTemp[$KeyField]!="" && $MultiValue!="")    {
                        $sql = "select `".$MetaColumnNamesTemp[$ValueField]."` as label from $TableNameTemp where `".$MetaColumnNamesTemp[$KeyField]."`='".ForSqlInjection($MultiValue)."' ;";
                        $rs = $db->Execute($sql) or print($sql);
                        $MultiValueRS[] = $rs->fields['label'];
                    }
                }
                $Line[$FieldName] = join(',',$MultiValueRS);
                $FieldDataColorValue[$FieldName][$Line[$FieldName]] = "#";
                //print "TIME EXCEUTE 13:".(time()-$TIME_BEGIN)."<BR>\n";
                break;
            case 'avatar':
                $Line[$FieldName] = AttachFieldValueToUrl($TableName,$OriginalID,$FieldName,'avatar',$Line2[$FieldName]);
                break;
            case 'images':
            case 'images2':
                $ImagesValue = AttachFieldValueToUrl($TableName,$OriginalID,$FieldName,'images',$Line2[$FieldName]);
                $Line[$FieldName] = $ImagesValue;
                break;
            case 'files':
            case 'files2':
                $Line[$FieldName] = AttachFieldValueToUrl($TableName,$OriginalID,$FieldName,'files',$Line2[$FieldName]);
                break;
            case 'file':
                $Line[$FieldName] = AttachFieldValueToUrl($TableName,$OriginalID,$FieldName,'file',$Line2[$FieldName]);
                break;
            case 'xlsx':
                $Line[$FieldName] = AttachFieldValueToUrl($TableName,$OriginalID,$FieldName,'xlsx',$Line2[$FieldName]);
                break;
            case 'password':
                $Line[$FieldName] = "******";
                break;
            case 'datetime':
                $Line[$FieldName] = substr($FieldValue, 5, 11);
                break;
        }
        //Data Mask
        $SettingTempMap = $AllFieldsMap[$FieldName]['Setting'];
        $DataMask       = $SettingTempMap['DataMask'];
        switch($DataMask) {
            case 'Last6digitsPlusStar':
                if(strlen($Line[$FieldName])>6) {
                    $Line[$FieldName] = "******".substr($Line[$FieldName],-6);
                }
                break;
            case 'Pre6digitsPlusStar':
                if(strlen($Line[$FieldName])>6) {
                    $Line[$FieldName] = substr($Line[$FieldName],0,-6)."******";
                }
                break;
        }
        // filter data to show on the list page -- End
        // Mobile End Data Filter
        // List Template 1
        $MobileEndItem['MobileEndFirstLine']            = str_replace("[".$FieldName."]", (String)$Line[$FieldName], $MobileEndItem['MobileEndFirstLine']);
        $MobileEndItem['MobileEndSecondLineLeft']       = str_replace("[".$FieldName."]", (String)$Line[$FieldName], $MobileEndItem['MobileEndSecondLineLeft']);
        $MobileEndItem['MobileEndSecondLineRight']      = str_replace("[".$FieldName."]", (String)$Line[$FieldName], $MobileEndItem['MobileEndSecondLineRight']);
        $MobileEndItem['MobileEndSecondLineLeft']       = str_replace("()","",$MobileEndItem['MobileEndSecondLineLeft']);
        $MobileEndItem['MobileEndSecondLineRight']      = str_replace("()","",$MobileEndItem['MobileEndSecondLineRight']);
        $MobileEndItem['MobileEndSecondLineRight']      = str_replace(":0",":",$MobileEndItem['MobileEndSecondLineRight']);

        $MobileEndSecondLineLeftColor = [];
        $MobileEndSecondLineLeftColorArray = explode(',', $MobileEndItem['MobileEndSecondLineLeftColorRule']);
        foreach($MobileEndSecondLineLeftColorArray as $MobileEndSecondLineLeftColorItem) {
            $MobileEndSecondLineLeftColorItemArray = explode(':', $MobileEndSecondLineLeftColorItem);
            $MobileEndSecondLineLeftColor[$MobileEndSecondLineLeftColorItemArray[0]] = $MobileEndSecondLineLeftColorItemArray[1];
        }
        $MobileEndItem['MobileEndSecondLineLeftColor'] = $MobileEndSecondLineLeftColor[$Line[$MobileEndItem['MobileEndSecondLineLeftColorField']]];

        $MobileEndSecondLineRightColor = [];
        $MobileEndSecondLineRightColorArray = explode(',', $MobileEndItem['MobileEndSecondLineRightColorRule']);
        foreach($MobileEndSecondLineRightColorArray as $MobileEndSecondLineRightColorItem) {
            $MobileEndSecondLineRightColorItemArray = explode(':', $MobileEndSecondLineRightColorItem);
            $MobileEndSecondLineRightColor[$MobileEndSecondLineRightColorItemArray[0]] = $MobileEndSecondLineRightColorItemArray[1];
        }
        $MobileEndItem['MobileEndSecondLineRightColor'] = $MobileEndSecondLineRightColor[$Line[$MobileEndItem['MobileEndSecondLineRightColorField']]];
        if($MobileEndItem['MobileEndSecondLineRightColor'] == null) {
            $MobileEndItem['MobileEndSecondLineRightColor'] = "primary";
        }

        /*
        $MobileEndItem['MobileEndSecondLineRightColor']  = $SettingMap['MobileEndSecondLineRightColor'];
        if($FieldName == $SettingMap['MobileEndWhenField1'] && $SettingMap['MobileEndWhenFieldIsEqual1'] == $FieldValue) {
            $MobileEndItem['MobileEndSecondLineRightColor'] = $SettingMap['MobileEndWhenFieldShowColor1'];
        }
        if($FieldName == $SettingMap['MobileEndWhenField2'] && $SettingMap['MobileEndWhenFieldIsEqual2'] == $FieldValue) {
            $MobileEndItem['MobileEndSecondLineRightColor'] = $SettingMap['MobileEndWhenFieldShowColor2'];
        }
        */

        $MobileEndItem['MobileEndIconImage']        = "/images/wechatIcon/".$SettingMap['MobileEndIconImage'].".png";
        //print_R($SettingMap);exit;
    }
    $MobileEndItem['MobileEndSecondLineLeftColorField']     = $Line[$MobileEndItem['MobileEndSecondLineLeftColorField']];
    $MobileEndItem['MobileEndSecondLineRightColorField']    = $Line[$MobileEndItem['MobileEndSecondLineRightColorField']];
    if($SettingMap['MobileEndField1']!=""&&$SettingMap['MobileEndField1']!="Disabled")  {
        $MobileEndItem['MobileEndField1Name']     = $SettingMap['MobileEndField1'];
        $MobileEndItem['MobileEndField1Value']    = $Line[$SettingMap['MobileEndField1']];
        $MobileEndItem['MobileEndField1Colspan']  = 2;
    }
    if($SettingMap['MobileEndField2']!=""&&$SettingMap['MobileEndField2']!="Disabled")  {
        $MobileEndItem['MobileEndField2Name']     = $SettingMap['MobileEndField2'];
        $MobileEndItem['MobileEndField2Value']    = $Line[$SettingMap['MobileEndField2']];
        $MobileEndItem['MobileEndField2Colspan']  = 2;
    }
    if($SettingMap['MobileEndField3']!=""&&$SettingMap['MobileEndField3']!="Disabled")  {
        $MobileEndItem['MobileEndField3Name']     = $SettingMap['MobileEndField3'];
        $MobileEndItem['MobileEndField3Value']    = $Line[$SettingMap['MobileEndField3']];
        $MobileEndItem['MobileEndField3Colspan']  = 2;
    }
    if($SettingMap['MobileEndField4']!=""&&$SettingMap['MobileEndField4']!="Disabled")  {
        $MobileEndItem['MobileEndField4Name']     = $SettingMap['MobileEndField4'];
        $MobileEndItem['MobileEndField4Value']    = $Line[$SettingMap['MobileEndField4']];
        $MobileEndItem['MobileEndField4Colspan']  = 2;
    }
    if($SettingMap['MobileEndField5']!=""&&$SettingMap['MobileEndField5']!="Disabled")  {
        $MobileEndItem['MobileEndField5Name']     = $SettingMap['MobileEndField5'];
        $MobileEndItem['MobileEndField5Value']    = $Line[$SettingMap['MobileEndField5']];
        $MobileEndItem['MobileEndField5Colspan']  = 2;
    }
    if($SettingMap['MobileEndField6']!=""&&$SettingMap['MobileEndField6']!="Disabled")  {
        $MobileEndItem['MobileEndField6Name']     = $SettingMap['MobileEndField6'];
        $MobileEndItem['MobileEndField6Value']    = $Line[$SettingMap['MobileEndField6']];
        $MobileEndItem['MobileEndField6Colspan']  = 2;
    }
    if($SettingMap['MobileEndField7']!=""&&$SettingMap['MobileEndField7']!="Disabled")  {
        $MobileEndItem['MobileEndField7Name']     = $SettingMap['MobileEndField7'];
        $MobileEndItem['MobileEndField7Value']    = $Line[$SettingMap['MobileEndField7']];
        $MobileEndItem['MobileEndField7Colspan']  = 2;
    }
    if($SettingMap['MobileEndField8']!=""&&$SettingMap['MobileEndField8']!="Disabled")  {
        $MobileEndItem['MobileEndField8Name']     = $SettingMap['MobileEndField8'];
        $MobileEndItem['MobileEndField8Value']    = $Line[$SettingMap['MobileEndField8']];
        $MobileEndItem['MobileEndField8Colspan']  = 2;
    }
    if($SettingMap['MobileEndField9']!=""&&$SettingMap['MobileEndField9']!="Disabled")  {
        $MobileEndItem['MobileEndField9Name']     = $SettingMap['MobileEndField9'];
        $MobileEndItem['MobileEndField9Value']    = $Line[$SettingMap['MobileEndField9']];
        $MobileEndItem['MobileEndField9Colspan']  = 2;
    }

    //LimitEditAndDelete
    if($SettingMap['LimitEditAndDelete_Edit_Field_One']!="" && $SettingMap['LimitEditAndDelete_Edit_Field_One']!="None" && in_array($SettingMap['LimitEditAndDelete_Edit_Field_One'], $MetaColumnNames)) {
        $LimitEditAndDelete_Edit_Value_One_Array = explode(',',$SettingMap['LimitEditAndDelete_Edit_Value_One']);
        if(in_array($Line[$SettingMap['LimitEditAndDelete_Edit_Field_One']],$LimitEditAndDelete_Edit_Value_One_Array)) {
            $ForbiddenEditRow[$Line['id']] = $Line['id'];
            $ForbiddenSelectRow[$Line['id']] = $Line['id'];
            $ForbiddenEditRowOriginal[$OriginalID] = $OriginalID;
            $ForbiddenSelectRowOriginal[$OriginalID] = $OriginalID;
        }
    }
    if($SettingMap['LimitEditAndDelete_Edit_Field_Two']!="" && $SettingMap['LimitEditAndDelete_Edit_Field_Two']!="None" && in_array($SettingMap['LimitEditAndDelete_Edit_Field_Two'], $MetaColumnNames)) {
        $LimitEditAndDelete_Edit_Value_Two_Array = explode(',',$SettingMap['LimitEditAndDelete_Edit_Value_Two']);
        if(in_array($Line[$SettingMap['LimitEditAndDelete_Edit_Field_Two']],$LimitEditAndDelete_Edit_Value_Two_Array)) {
            $ForbiddenEditRow[$Line['id']] = $Line['id'];
            $ForbiddenSelectRow[$Line['id']] = $Line['id'];
            $ForbiddenEditRowOriginal[$OriginalID] = $OriginalID;
            $ForbiddenSelectRowOriginal[$OriginalID] = $OriginalID;
        }
    }
    if($SettingMap['LimitEditAndDelete_Delete_Field_One']!="" && $SettingMap['LimitEditAndDelete_Delete_Field_One']!="None" && in_array($SettingMap['LimitEditAndDelete_Delete_Field_One'], $MetaColumnNames)) {
        $LimitEditAndDelete_Delete_Value_One_Array = explode(',',$SettingMap['LimitEditAndDelete_Delete_Value_One']);
        if(in_array($Line[$SettingMap['LimitEditAndDelete_Delete_Field_One']],$LimitEditAndDelete_Delete_Value_One_Array)) {
            $ForbiddenDeleteRow[$Line['id']] = $Line['id'];
            $ForbiddenSelectRow[$Line['id']] = $Line['id'];
            $ForbiddenDeleteRowOriginal[$OriginalID] = $OriginalID;
            $ForbiddenSelectRowOriginal[$OriginalID] = $OriginalID;
        }
    }
    if($SettingMap['LimitEditAndDelete_Delete_Field_Two']!="" && $SettingMap['LimitEditAndDelete_Delete_Field_Two']!="None" && in_array($SettingMap['LimitEditAndDelete_Delete_Field_Two'], $MetaColumnNames)) {
        $LimitEditAndDelete_Delete_Value_Two_Array = explode(',',$SettingMap['LimitEditAndDelete_Delete_Value_Two']);
        if(in_array($Line[$SettingMap['LimitEditAndDelete_Delete_Field_Two']],$LimitEditAndDelete_Delete_Value_Two_Array)) {
            $ForbiddenDeleteRow[$Line['id']] = $Line['id'];
            $ForbiddenSelectRow[$Line['id']] = $Line['id'];
            $ForbiddenDeleteRowOriginal[$OriginalID] = $OriginalID;
            $ForbiddenSelectRowOriginal[$OriginalID] = $OriginalID;
        }
    }
    $NewRSA[] = $Line;
    if(in_array($Line['TableName'],['data_user','data_department','data_role','form_formfield'])) {
        $ForbiddenSelectRow[$Line['id']] = $Line['id'];
        //$ForbiddenViewRow[$Line['id']] = $Line['id'];
        //$ForbiddenEditRow[$Line['id']] = $Line['id'];
        $ForbiddenDeleteRow[$Line['id']] = $Line['id'];
        $ForbiddenDeleteRowOriginal[$OriginalID] = $OriginalID;
        $ForbiddenSelectRowOriginal[$OriginalID] = $OriginalID;
    }
    if($ForbiddenEditRow[$Line['id']]=="" && in_array('Edit',$Actions_In_List_Row_Array)) {
        $MobileEndItem['EditUrl']   = "?action=edit_default&pageid=$page&id=".$Line['id'];
    }
    if($ForbiddenDeleteRow[$Line['id']]=="" && in_array('Delete',$Actions_In_List_Row_Array)) {
        $MobileEndItem['DeleteUrl'] = "?action=delete_array&pageid=$page";
    }
    if($ForbiddenViewRow[$Line['id']]=="" && in_array('View',$Actions_In_List_Row_Array)) {
        $MobileEndItem['ViewUrl']   = "?action=view_default&pageid=$page&id=".$Line['id'];
    }
    $MobileEndItem['EditIcon']  = "mdi:pencil-outline";
    $MobileEndItem['PageId']    = $page;
    $MobileEndItem['Id']        = $Line['id'];
    $MobileEndItem['Id2']       = md5("Dandian_".$Line['id2']);
    $MobileEndItem['Template']  = "List";
    $functionNameIndividual = "plugin_".$TableName."_".$Step."_init_default_mobile_line_control";
    if(function_exists($functionNameIndividual))  {
      $MobileEndItem = $functionNameIndividual($MobileEndItem);
    }
    foreach($MobileEndItem as $MobileEndItemKey=>$MobileEndItemValue)  {
      if($MobileEndItemValue == "undefined")  {
        $MobileEndItem[$MobileEndItemKey] = "";
      }
    }
    $MobileEndData[] = $MobileEndItem;

}

// Add List Page Data Color Array
for($i=0;$i<sizeof($init_default_columns);$i++)    {
    $Item = $init_default_columns[$i];
    if($Item['type']=="radiogroupcolor" && is_array($FieldDataColorValue[$Item['field']]))   {
        $FieldItemAll = @array_keys(@$FieldDataColorValue[$Item['field']]);
        $Color = ArrayToColorStyle1($FieldItemAll);
        $init_default_columns[$i]['color'] = $Color;
        //print_R($init_default_columns[$i]);
    }
    elseif($Item['type']=="tablefiltercolor" && is_array($FieldDataColorValue[$Item['field']]))   {
        $FieldItemAll = @array_keys(@$FieldDataColorValue[$Item['field']]);
        $Color = ArrayToColorStyle2($FieldItemAll);
        $init_default_columns[$i]['color'] = $Color;
        //print_R($init_default_columns[$i]);
    }
}

$RS['init_default']['button_search']    = __("Search");
$RS['init_default']['button_add']       = $SettingMap['Rename_List_Add_Button'];
$RS['init_default']['button_import']    = $SettingMap['Rename_List_Import_Button']?$SettingMap['Rename_List_Import_Button']:__("Import");
$RS['init_default']['button_export']    = $SettingMap['Rename_List_Export_Button']?$SettingMap['Rename_List_Export_Button']:__("Export");
$RS['init_default']['columns']          = $init_default_columns;
$RS['init_default']['columnsactions']   = $columnsactions;

if($SettingMap['OperationLogGrade']=="AllOperation")  {
    SystemLogRecord("init_default", $BeforeRecord='', $AfterRecord='');
}

$RS['init_default']['data']                     = $NewRSA;
$RS['init_default']['MobileEndData']            = $MobileEndData;
$RS['init_default']['MobileEndShowType']        = $SettingMap['MobileEndShowType'];
$RS['init_default']['MobileEndShowSearch']      = $SettingMap['MobileEndShowSearch'];
$RS['init_default']['MobileEndShowGroupFilter'] = $SettingMap['MobileEndShowGroupFilter'];
$RS['init_default']['MainImageList']	        = array("/images/wechat/logo_18.png","/images/wechat/logo_icampus.png");

$RS['init_default']['ForbiddenSelectRow']   = array_keys($ForbiddenSelectRow);
$RS['init_default']['ForbiddenViewRow']     = array_keys($ForbiddenViewRow);
$RS['init_default']['ForbiddenEditRow']     = array_keys($ForbiddenEditRow);
$RS['init_default']['ForbiddenDeleteRow']   = array_keys($ForbiddenDeleteRow);

if($SettingMap['Init_Action_Value'] == "") {
    $RS['init_action']['action']      = "init_default";
    $RS['init_action']['actionValue'] = "";
}
else if($SettingMap['Init_Action_Value'] == "SoulChatList")  {
  $RS['init_action']['action']        = "init_default";
  $RS['init_action']['actionValue']   = "SoulChatList";
}
else if($SettingMap['Init_Action_Value'] == "AiChatList")  {
  $RS['init_action']['action']        = "init_default";
  $RS['init_action']['actionValue']   = "AiChatList";
}
else if($SettingMap['Init_Action_Value'] == "AiQuestionList")  {
  $RS['init_action']['action']        = "init_default";
  $RS['init_action']['actionValue']   = "AiQuestionList";
}
else {
  $RS['init_action']['actionValue']   = "";
  $RS['init_action']['action']        = $SettingMap['Init_Action_Value'];
}
$RS['init_action']['id']                            = EncryptID($GetAllIDList[0]); //USE THIS VALUE IN EDIT_DEFAULT SINGLE RECORD
$RS['init_action']['IsGetStructureFromEditDefault'] = 0;

$currentUrlAccessFileName                           = basename($_SERVER['PHP_SELF']);
$currentUrlAccessFileName                           = str_replace("apps_", "", $currentUrlAccessFileName);
$currentUrlAccessFileName                           = str_replace(".php", "", $currentUrlAccessFileName);
$RS['init_action']['AppId']                         = EncryptID(intval($currentUrlAccessFileName));

$CSRF_DATA                          = [];
$CSRF_DATA['GetAllIDList']          = $GetAllIDList;
$CSRF_DATA['ForbiddenSelectRow']    = $ForbiddenSelectRowOriginal;
$CSRF_DATA['ForbiddenViewRow']      = $ForbiddenViewRowOriginal;
$CSRF_DATA['ForbiddenEditRow']      = $ForbiddenEditRowOriginal;
$CSRF_DATA['ForbiddenDeleteRow']    = $ForbiddenDeleteRowOriginal;
$CSRF_DATA['UpdateFields']          = $UpdateFields;
$CSRF_DATA['Actions_In_List_Row_Array'] = $Actions_In_List_Row_Array;
$CSRF_DATA['Bottom_Button_Actions_Array'] = explode(',',$SettingMap['Bottom_Button_Actions']);
$CSRF_DATA['Time']                  = time();
$RS['init_default']['CSRF_TOKEN']   = EncryptID(serialize($CSRF_DATA));
$RS['init_default']['CSRF_DATA']    = $CSRF_DATA;

$RS['init_default']['params']   = ['FormGroup' => '', 'role' => '', 'status' => '', 'q' => ''];

$RS['init_default']['rowdelete']    = [];
$RS['init_default']['rowdelete'][]  = ["text"=>$SettingMap['Tip_Title_When_Delete'],"action"=>"delete_array","title"=>$SettingMap['Tip_Title_When_Delete'],"content"=>$SettingMap['Tip_Content_When_Delete'],"memoname"=>"","inputmust"=>false,"inputmusttip"=>"","submit"=>$SettingMap['Tip_Button_When_Delete'],"cancel"=>__("Cancel")];

//MultiReview
$multireview = [];
$Bottom_Button_Actions_Array = explode(',',$SettingMap['Bottom_Button_Actions']);
$multireview['input']['placeholder'] = __("Review Opinion");
if(in_array('Delete',$Bottom_Button_Actions_Array))   {
    $multireview['multireview'][] = ["text"=>__("Delete Selected"),"action"=>"delete_array","title"=>__("Delete multi items one time"),"content"=>__("Do you really want to delete this item? This operation will delete table and data in Database."),"memoname"=>"","inputmust"=>false,"inputmusttip"=>"","submit"=>__("Submit"),"cancel"=>__("Cancel")];
}
if(in_array('Batch_Approval',$Bottom_Button_Actions_Array))   {
    $multireview['multireview'][] = ["text"=>__("Multi Approval"),"action"=>"option_multi_approval","title"=>__("Approval multi items one time"),"content"=>__("Do you really want to approval multi items at this time?"),"memoname"=>$SettingMap['Batch_Approval_Review_Field'],"inputmust"=>$SettingMap['Batch_Approval_Review_Field']?true:false,"inputmusttip"=>__("Opinion must input"),"submit"=>__("Submit"),"cancel"=>__("Cancel")];
}
if(in_array('Batch_Cancel',$Bottom_Button_Actions_Array))   {
    $multireview['multireview'][] = ["text"=>__("Multi Cancel"),"action"=>"option_multi_cancel","title"=>__("Cancel multi items one time"),"content"=>__("Do you really want to cancel multi items at this time?"),"memoname"=>$SettingMap['Batch_Approval_Review_Field'],"inputmust"=>$SettingMap['Batch_Approval_Review_Field']?true:false,"inputmusttip"=>__("Opinion must input"),"submit"=>__("Submit"),"cancel"=>__("Cancel")];
}
if(in_array('Batch_Reject',$Bottom_Button_Actions_Array))   {
    $multireview['multireview'][] = ["text"=>__("Multi Refuse"),"action"=>"option_multi_refuse","title"=>__("Refuse multi items one time"),"content"=>__("Do you really want to approval multi items at this time?"),"memoname"=>$SettingMap['Batch_Approval_Review_Field'],"inputmust"=>$SettingMap['Batch_Approval_Review_Field']?true:false,"inputmusttip"=>__("Opinion must input"),"submit"=>__("Submit"),"cancel"=>__("Cancel")];
}
if(in_array('Reset_Password_Abcd1234',$Bottom_Button_Actions_Array))   {
    $multireview['multireview'][] = ["text"=>__("Reset_Password_Abcd1234"),"action"=>"Reset_Password_Abcd1234","title"=>__("Modify user passwords in batches"),"content"=>__("Modify the password of the selected record at one time to Abcd1234"),"memoname"=>"","inputmust"=>false,"inputmusttip"=>"","submit"=>__("Submit"),"cancel"=>__("Cancel")];
}
if(in_array('Reset_Password_ID_Last6PinYin',$Bottom_Button_Actions_Array))   {
    $multireview['multireview'][] = ["text"=>__("Reset_Password_ID_Last6PinYin"),"action"=>"Reset_Password_ID_Last6PinYin","title"=>__("Modify user passwords in batches"),"content"=>__("Modify the password of the selected record to the last six digits of the ID number, if no ID number is set, the password is Abcd1234"),"memoname"=>"","inputmust"=>false,"inputmusttip"=>"","submit"=>__("Submit"),"cancel"=>__("Cancel")];
}
if(in_array('Batch_Setting_One',$Bottom_Button_Actions_Array))   {
    $multireview['multireview'][] = ["text"=>$SettingMap["Batch_Setting_One_Name"],"action"=>"option_multi_setting_one","title"=>__("Change multiple item values one time"),"content"=>__("Do you really want to change multiple item values at this time?")."\n批量把[".$SettingMap['Batch_Setting_Two_Change_Field']."]列修改为:".$SettingMap['Batch_Setting_Two_Change_Value']."","memoname"=>$SettingMap['Batch_Approval_Review_Field'],"inputmust"=>$SettingMap['Batch_Approval_Review_Field']?true:false,"inputmusttip"=>__("Opinion must input"),"submit"=>__("Submit"),"cancel"=>__("Cancel")];
}
if(in_array('Batch_Setting_Two',$Bottom_Button_Actions_Array))   {
    $multireview['multireview'][] = ["text"=>$SettingMap["Batch_Setting_Two_Name"],"action"=>"option_multi_setting_two","title"=>__("Change multiple item values one time"),"content"=>__("Do you really want to change multiple item values at this time?")."\n批量把[".$SettingMap['Batch_Setting_Two_Change_Field']."]列修改为:".$SettingMap['Batch_Setting_Two_Change_Value']."","memoname"=>$SettingMap['Batch_Approval_Review_Field'],"inputmust"=>$SettingMap['Batch_Approval_Review_Field']?true:false,"inputmusttip"=>__("Opinion must input"),"submit"=>__("Submit"),"cancel"=>__("Cancel")];
}
$multireview['Bottom_Button_Actions_Array'] = $Bottom_Button_Actions_Array;
//$multireview['multireview'][] = ["text"=>"Multi Change Status","action"=>"option_multi_change_status","title"=>"option_multi_change_status Item","content"=>"Do you really to delete this item?Do you really to delete this item?","memoname"=>"审核意见3","inputmust"=>false,"inputmusttip"=>"","submit"=>"Submit","cancel"=>__("Cancel")];
$RS['init_default']['multireview'] = $multireview;
$RS['init_default']['checkboxSelection']  = is_array($multireview['multireview']) && count($multireview['multireview'])>0 ? true : false;

$RS['import_default']['allFields']        = $allFieldsImport;
$RS['import_default']['allFieldsMode']    = [['value'=>"Default", 'label'=>__("")]];
$RS['import_default']['defaultValues']    = $defaultValuesImport;
$RS['import_default']['dialogContentHeight']  = "90%";
$RS['import_default']['submitaction']     = "import_default_data";
$RS['import_default']['componentsize']    = "small";
$RS['import_default']['submittext']       = $SettingMap['Rename_Import_Submit_Button'];
$RS['import_default']['canceltext']       = __("Cancel");
$RS['import_default']['titletext']        = $SettingMap['Import_Title_Name'];
$RS['import_default']['titlememo']        = $SettingMap['Import_Subtitle_Name'];
$RS['import_default']['tablewidth']       = 650;
$RS['import_default']['submitloading']    = __("SubmitLoading");
$RS['import_default']['loading']          = __("Loading");
$RS['import_default']['ImportLoading']    = __("ImportLoading");


$TEMPARRAY                      = [];
$TEMPARRAY['TableName']         = $TableName;
$TEMPARRAY['Action']            = "export_data";
$TEMPARRAY['FormId']            = $FormId;
$TEMPARRAY['FlowId']            = $FlowId;
$TEMPARRAY['FileName']          = $FormName;
$TEMPARRAY['AddSql']            = $AddSql;
$TEMPARRAY['orderby']           = $orderby;
$TEMPARRAY['Time']              = time();
$DATATEMP                       = EncryptID(serialize($TEMPARRAY));
$exportUrl                      = "data_export_json.php?DATA=".$DATATEMP;
$RS['export_default']['allFields']        = $allFieldsExport;
$RS['export_default']['allFieldsMode']    = [['value'=>"Default", 'label'=>__("")]];
$RS['export_default']['defaultValues']    = [];
$RS['export_default']['dialogContentHeight']  = "90%";
$RS['export_default']['submitaction']     = "export_default_data";
$RS['export_default']['componentsize']    = "small";
$RS['export_default']['submittext']       = $SettingMap['Rename_Export_Submit_Button'];
$RS['export_default']['canceltext']       = __("Cancel");
$RS['export_default']['titletext']        = $SettingMap['Export_Title_Name'];
$RS['export_default']['titlememo']        = $SettingMap['Export_Subtitle_Name'];
$RS['export_default']['tablewidth']       = 650;
$RS['export_default']['submitloading']    = __("SubmitLoading");
$RS['export_default']['ExportLoading']    = __("ExportLoading");
$RS['export_default']['loading']          = __("Loading");
if(sizeof(array_keys($allFieldsExport))>0 && in_array('Export',$Actions_In_List_Header_Array)) {
    $RS['export_default']['exportUrl']        = $exportUrl;
}

$RS['add_default']['allFields']     = $allFieldsAdd;
$RS['add_default']['allFieldsMode']  = [['value'=>"Default", 'label'=>__("")]];
$RS['add_default']['defaultValues'] = $defaultValuesAdd;
$RS['add_default']['dialogContentHeight']  = "90%";
$RS['add_default']['submitaction']  = "add_default_data";
$RS['add_default']['componentsize'] = "small";
$RS['add_default']['submittext']    = $SettingMap['Rename_Add_Submit_Button'];
$RS['add_default']['canceltext']    = __("Cancel");
$RS['add_default']['titletext']     = $SettingMap['Add_Title_Name'];
$RS['add_default']['titlememo']     = $SettingMap['Add_Subtitle_Name'];
$RS['add_default']['tablewidth']    = 650;
$RS['add_default']['submitloading'] = __("SubmitLoading");
$RS['add_default']['loading']       = __("Loading");

$RS['edit_default']['allFields']        = $allFieldsEdit;
$RS['edit_default']['allFieldsMode']    = [['value'=>"Default", 'label'=>__("")]];
$RS['edit_default']['defaultValues']    = $defaultValuesEdit;
$RS['edit_default']['dialogContentHeight']  = "90%";
$RS['edit_default']['submitaction']     = "edit_default_data";
$RS['edit_default']['componentsize']    = "small";
$RS['edit_default']['submittext']       = $SettingMap['Rename_Edit_Submit_Button'];
$RS['edit_default']['canceltext']       = __("Cancel");
$RS['edit_default']['titletext']        = $SettingMap['Edit_Title_Name'];
$RS['edit_default']['titlememo']        = $SettingMap['Edit_Subtitle_Name'];
$RS['edit_default']['tablewidth']       = 650;
$RS['edit_default']['submitloading']    = __("SubmitLoading");
$RS['edit_default']['loading']          = __("Loading");

$RS['view_default']               = $RS['add_default'];
$RS['view_default']['allFields']  = $allFieldsView;
$RS['view_default']['titletext']  = $SettingMap['View_Title_Name'];
$RS['view_default']['titlememo']  = $SettingMap['View_Subtitle_Name'];
$RS['view_default']['componentsize'] = "small";

//Relative Child Table Support
$Relative_Child_Table                   = $SettingMap['Relative_Child_Table'];
$Relative_Child_Table_Field_Name        = $SettingMap['Relative_Child_Table_Field_Name'];
$Relative_Child_Table_Parent_Field_Name = $SettingMap['Relative_Child_Table_Parent_Field_Name'];
if($Relative_Child_Table>0 && $Relative_Child_Table_Parent_Field_Name!="" && in_array($Relative_Child_Table_Parent_Field_Name,$MetaColumnNames)) {
    $ChildSettingMap = returntablefield("form_formflow",'id',$Relative_Child_Table,'Setting')['Setting'];
    $ChildSettingMap = unserialize(base64_decode($ChildSettingMap));
    $ChildFormId                = returntablefield("form_formflow",'id',$Relative_Child_Table,'FormId')['FormId'];
    $ChildTableName             = returntablefield("form_formname",'id',$ChildFormId,'TableName')['TableName'];
    $ChildMetaColumnNames       = GLOBAL_MetaColumnNames($ChildTableName);
    if($Relative_Child_Table_Field_Name!="" && in_array($Relative_Child_Table_Field_Name, $ChildMetaColumnNames) ) {
        //Get All Fields
        $sql                        = "select * from form_formfield where FormId='$ChildFormId' and IsEnable='1' order by SortNumber asc, id asc";
        $rs                         = $db->Execute($sql);
        $ChildAllFieldsFromTable    = $rs->GetArray();
        $ChildAllFieldsMap = [];
        foreach($ChildAllFieldsFromTable as $Item)  {
            $ChildAllFieldsMap[$Item['FieldName']] = $Item;
            $ChildLocaleFieldArray[$Item['EnglishName']] = $Item['FieldName'];
            $ChildLocaleFieldArray[$Item['ChineseName']] = $Item['FieldName'];
        }
        $defaultValuesAddChild  = [];
        $defaultValuesEditChild = [];
        $allFieldsAdd   = getAllFields($ChildAllFieldsFromTable, $AllShowTypesArray, 'ADD', true, $ChildSettingMap);
        foreach($allFieldsAdd as $ModeName=>$allFieldItem) {
            foreach($allFieldItem as $ITEM) {
                $defaultValuesAddChild[$ITEM['name']] = $ITEM['value'];
                if($ITEM['code']!="") {
                    $defaultValuesAddChild[$ITEM['code']] = $ITEM['value'];
                }
            }
        }
        $RS['add_default']['childtable']['allFields']        = $allFieldsAdd;
        $RS['add_default']['childtable']['defaultValues']    = $defaultValuesAddChild;
        $RS['add_default']['childtable']['submittext']       = __("NewItem");
        $RS['add_default']['childtable']['Add']                = strpos($ChildSettingMap['Actions_In_List_Header'],'Add')===false?false:true;
        $RS['add_default']['childtable']['Edit']               = strpos($ChildSettingMap['Actions_In_List_Row'],'Edit')===false?false:true;
        $RS['add_default']['childtable']['Delete']             = strpos($ChildSettingMap['Actions_In_List_Row'],'Delete')===false?false:true;

        $allFieldsEdit   = getAllFields($ChildAllFieldsFromTable, $AllShowTypesArray, 'EDIT', true, $ChildSettingMap);
        foreach($allFieldsEdit as $ModeName=>$allFieldItem) {
            $allFieldItemIndex = 0;
            foreach($allFieldItem as $ITEM) {
                $defaultValuesEditChild[$ITEM['name']] = $ITEM['value'];
                if($ITEM['code']!="") {
                    $defaultValuesEditChild[$ITEM['code']] = $ITEM['value'];
                }
                if(strpos($ChildSettingMap['Actions_In_List_Row'],'Edit')===false) {
                    $allFieldsEdit[$ModeName][$allFieldItemIndex]['rules']['disabled'] = true;
                }
                $allFieldItemIndex ++;
            }
        }
        if(is_array($ChildSettingMap))   {
            foreach($ChildSettingMap as $ModeName=>$allFieldItem) {
                $defaultValuesEditChild[$ModeName] = $allFieldItem;
            }
        }
        $RS['edit_default']['childtable']['allFields']          = $allFieldsEdit;
        $RS['edit_default']['childtable']['defaultValues']      = $defaultValuesEditChild;
        $RS['edit_default']['childtable']['submittext']         = __("NewItem");
        $RS['edit_default']['childtable']['Add']                = strpos($ChildSettingMap['Actions_In_List_Header'],'Add')===false?false:true;
        $RS['edit_default']['childtable']['Edit']               = strpos($ChildSettingMap['Actions_In_List_Row'],'Edit')===false?false:true;
        $RS['edit_default']['childtable']['Delete']             = strpos($ChildSettingMap['Actions_In_List_Row'],'Delete')===false?false:true;

    }
}

$RS['init_default']['delete_dialog_title']      = $SettingMap['Tip_Title_When_Delete'];
$RS['init_default']['delete_dialog_content']    = $SettingMap['Tip_Content_When_Delete'];
$RS['init_default']['delete_dialog_button']     = $SettingMap['Tip_Button_When_Delete'];

$RS['init_default']['rowHeight']        = $rowHeight;
$RS['init_default']['dialogContentHeight']  = "90%";
$RS['init_default']['dialogMaxWidth']   = $SettingMap['Init_Action_AddEditWidth']?$SettingMap['Init_Action_AddEditWidth']:'md';// xl lg md sm xs
$RS['init_default']['timeline']         = time();
$RS['init_default']['pageNumber']       = $pageSize;
$RS['init_default']['pageCount']        = ceil($RS['init_default']['total']/$pageSize);
$RS['init_default']['pageId']           = $page;
$RS['init_default']['pageNumberArray']  = $pageNumberArray;
if($SettingMap['Debug_Sql_Show_On_Api']=="Yes" && in_array($GLOBAL_USER->USER_ID, ['admin', 'admin001']))  {
    $RS['init_default']['sql']                              = $sqlList;
    $RS['init_default']['ApprovalNodeFields']['DebugSql']   = $sqlList;
}
$RS['init_default']['ApprovalNodeFields']['Memo']               = $SettingMap['Init_Action_Memo'];
if(in_array($GLOBAL_USER->USER_ID, ['admin', 'admin001']) && sizeof($AdminFilterTipTextArray) > 0) {
    $RS['init_default']['ApprovalNodeFields']['AdminFilterTipText'] = "过滤条件: ".join(' & ', $AdminFilterTipTextArray);
}
else {
    $RS['init_default']['ApprovalNodeFields']['AdminFilterTipText'] = "";
}


if($SettingMap['Init_Action_Value']=="edit_default_configsetting")   {
    //Get All Fields
    $sql                    = "select * from form_configsetting where FlowId='$FlowId' and IsEnable='1' order by SortNumber asc, id asc";
    $rs                     = $db->Execute($sql);
    $AllFieldsFromTable     = $rs->GetArray();
    $defaultValuesEdit      = [];
    $allFieldsEdit          = getAllFields($AllFieldsFromTable, $AllShowTypesArray, 'EDIT', $FilterFlowSetting=false, $SettingMap);
    foreach($allFieldsEdit as $ModeName=>$allFieldItem) {
        foreach($allFieldItem as $ITEM) {
            $defaultValuesEdit[$ITEM['name']] = $ITEM['value'];
        }
    }
    $ConfigSettingMap = returntablefield("form_formflow",'id',$FlowId,'ConfigSetting',$cache=0)['ConfigSetting'];
    $ConfigSettingMap = unserialize(base64_decode($ConfigSettingMap));
    if(is_array($ConfigSettingMap))   {
        foreach($ConfigSettingMap as $ModeName=>$allFieldItem) {
            $defaultValuesEdit[$ModeName] = $allFieldItem;
        }
    }
    //print_R($AllShowTypesArray);
    //print $sql;
    $RS['edit_default_configsetting']['ConfigSettingMap'] = $ConfigSettingMap;
    $RS['edit_default_configsetting']['allFields']        = $allFieldsEdit;
    $RS['edit_default_configsetting']['allFieldsMode']    = [['value'=>"Default", 'label'=>__("")]];
    $RS['edit_default_configsetting']['defaultValues']    = $defaultValuesEdit;
    $RS['edit_default_configsetting']['dialogContentHeight']  = "90%";
    $RS['edit_default_configsetting']['submitaction']     = "edit_default_configsetting_data";
    $RS['edit_default_configsetting']['componentsize']    = "small";
    $RS['edit_default_configsetting']['submittext']       = $SettingMap['Rename_Edit_Submit_Button'];
    $RS['edit_default_configsetting']['canceltext']       = __("Cancel");
    $RS['edit_default_configsetting']['titletext']        = $SettingMap['Edit_Title_Name'];
    $RS['edit_default_configsetting']['titlememo']        = $SettingMap['Edit_Subtitle_Name'];
    $RS['edit_default_configsetting']['tablewidth']       = 650;
    $RS['edit_default_configsetting']['submitloading']    = __("SubmitLoading");
    $RS['edit_default_configsetting']['loading']          = __("Loading");
}


if(sizeof($MetaColumnNames)>=5) {
    $pinnedColumnsLeft = [];
    $pinnedColumnsRight = [];
    if($SettingMap['Columns_Pinned_Left_Field_One']!="" && $SettingMap['Columns_Pinned_Left_Field_One']!="Disabled") {
        $pinnedColumnsLeft[$SettingMap['Columns_Pinned_Left_Field_One']] = $SettingMap['Columns_Pinned_Left_Field_One'];
    }
    if($SettingMap['Columns_Pinned_Left_Field_Two']!="" && $SettingMap['Columns_Pinned_Left_Field_Two']!="Disabled") {
        $pinnedColumnsLeft[$SettingMap['Columns_Pinned_Left_Field_Two']] = $SettingMap['Columns_Pinned_Left_Field_Two'];
    }
    if($SettingMap['Columns_Pinned_Left_Field_Three']!="" && $SettingMap['Columns_Pinned_Left_Field_Three']!="Disabled") {
        $pinnedColumnsLeft[$SettingMap['Columns_Pinned_Left_Field_Three']] = $SettingMap['Columns_Pinned_Left_Field_Three'];
    }
    if($SettingMap['Columns_Pinned_Left_Field_Four']!="" && $SettingMap['Columns_Pinned_Left_Field_Four']!="Disabled") {
        $pinnedColumnsLeft[$SettingMap['Columns_Pinned_Left_Field_Four']] = $SettingMap['Columns_Pinned_Left_Field_Four'];
    }
    if($SettingMap['Columns_Pinned_Right_Field_One']!="" && $SettingMap['Columns_Pinned_Right_Field_One']!="Disabled") {
        $pinnedColumnsRight[$SettingMap['Columns_Pinned_Right_Field_One']] = $SettingMap['Columns_Pinned_Right_Field_One'];
    }
    if($SettingMap['Columns_Pinned_Right_Field_Two']!="" && $SettingMap['Columns_Pinned_Right_Field_Two']!="Disabled") {
        $pinnedColumnsRight[$SettingMap['Columns_Pinned_Right_Field_Two']] = $SettingMap['Columns_Pinned_Right_Field_Two'];
    }
    if($SettingMap['Columns_Pinned_Right_Field_Three']!="" && $SettingMap['Columns_Pinned_Right_Field_Three']!="Disabled") {
        $pinnedColumnsRight[$SettingMap['Columns_Pinned_Right_Field_Three']] = $SettingMap['Columns_Pinned_Right_Field_Three'];
    }
    if($SettingMap['Columns_Pinned_Right_Field_Four']!="" && $SettingMap['Columns_Pinned_Right_Field_Four']!="Disabled") {
        $pinnedColumnsRight[$SettingMap['Columns_Pinned_Right_Field_Four']] = $SettingMap['Columns_Pinned_Right_Field_Four'];
    }
    $pinnedColumnsLeft  = array_keys($pinnedColumnsLeft);
    $pinnedColumnsRight = array_keys($pinnedColumnsRight);
    $pinnedColumns = ['left'=>$pinnedColumnsLeft,'right'=>$pinnedColumnsRight];
}
else {
    $pinnedColumns = ['left'=>[],'right'=>[]];
}
$RS['init_default']['pinnedColumns']  = $pinnedColumns;

$RS['init_default']['dataGridLanguageCode']  = $GLOBAL_LANGUAGE;

//Check Add Action In List Header
if(!in_array('Import',$Actions_In_List_Header_Array))  {
    $RS['import_default'] = [];
}
if(!in_array('Add',$Actions_In_List_Header_Array))  {
    $RS['add_default'] = [];
}
if(!in_array('Edit',$Actions_In_List_Row_Array))  {
    $RS['edit_default'] = [];
}

$RS['_GET']     = $_GET;
$RS['_POST']    = $_POST;

//functionNameIndividual
$functionNameIndividual = "plugin_".$TableName."_".$Step."_init_default_filter_RS";
if(function_exists($functionNameIndividual))  {
    $RS = $functionNameIndividual($RS);
}

print_R(EncryptApiData($RS, $GLOBAL_USER));
exit;



