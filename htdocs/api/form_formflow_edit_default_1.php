<?php
/*
* 基础架构: 单点低代码开发平台
* 版权所有: 郑州单点科技软件有限公司
* Email: moodle360@qq.com
* Copyright (c) 2007-2025
* License: 商业授权
*/


//#########################################################################################################################
//Field Type###############################################################################################################
//#########################################################################################################################
if($_GET['action']=="edit_default_1"&&$id!='')         {
    $sql    = "select * from form_formflow where id = '$id'";
    $rs     = $db->Execute($sql);
    $FlowName   = $rs->fields['FlowName'];
    $Setting    = $rs->fields['Setting'];
    $SettingMap = unserialize(base64_decode($Setting));
}

$FormFieldSelectOptions = [];
$FormFieldSelectOptions[] = ['value'=>'FieldTypeFollowByFormSetting', 'label'=>__('FieldTypeFollowByFormSetting')];
$FormFieldSelectOptions[] = ['value'=>'List_Use_AddEditView_NotUse', 'label'=>__('List_Use_AddEditView_NotUse')];
$FormFieldSelectOptions[] = ['value'=>'ListView_Use_AddEdit_NotUse', 'label'=>__('ListView_Use_AddEdit_NotUse')];
$FormFieldSelectOptions[] = ['value'=>'View_Use_ListAddEdit_NotUse', 'label'=>__('View_Use_ListAddEdit_NotUse')];
$FormFieldSelectOptions[] = ['value'=>'ListAddView_Use_Edit_Readonly', 'label'=>__('ListAddView_Use_Edit_Readonly')];
$FormFieldSelectOptions[] = ['value'=>'ListView_Use_AddEdit_Readonly', 'label'=>__('ListView_Use_AddEdit_Readonly')];
$FormFieldSelectOptions[] = ['value'=>'ListAddEdit_Use_View_NotUse', 'label'=>__('ListAddEdit_Use_View_NotUse')];
$FormFieldSelectOptions[] = ['value'=>'Disable', 'label'=>__('Disable')];
$FormFieldSelectOptions[] = ['value'=>'HiddenUserID', 'label'=>__('HiddenUserID')];
$FormFieldSelectOptions[] = ['value'=>'HiddenUsername', 'label'=>__('HiddenUsername')];
$FormFieldSelectOptions[] = ['value'=>'HiddenDeptID', 'label'=>__('HiddenDeptID')];
$FormFieldSelectOptions[] = ['value'=>'HiddenDeptName', 'label'=>__('HiddenDeptName')];
$FormFieldSelectOptions[] = ['value'=>'HiddenStudentID', 'label'=>__('HiddenStudentID')];
$FormFieldSelectOptions[] = ['value'=>'HiddenStudentName', 'label'=>__('HiddenStudentName')];
$FormFieldSelectOptions[] = ['value'=>'HiddenStudentClass', 'label'=>__('HiddenStudentClass')];
$YesOrNotOptions = [];
$YesOrNotOptions[] = ['value'=>'Yes', 'label'=>__('Yes')];
$YesOrNotOptions[] = ['value'=>'No', 'label'=>__('No')];
$edit_default_1 = [];
$defaultValues_1 = [];
//for($i=1;$i<sizeof($MetaColumnNamesTarget);$i++)   {
foreach($ShowTypeMap as $FieldName=>$ShowTypeMapItem) {
    //$FieldName = $MetaColumnNamesTarget[$i];
    //$ShowTypeMapItem = $ShowTypeMap[$FieldName];
    if($ShowTypeMapItem!="Disable")  {
        //Check the default from the first column value
        //当第一次建立流程的时候,什么数据都是空的,这个时候需要默认为启用,如果是已经有数据,而新增加进入的字段,这个时候需要默认为禁用
        if($SettingMap["FieldType_".$MetaColumnNamesTarget[1]]!="Disable" && $SettingMap["FieldType_".$MetaColumnNamesTarget[1]]!="")  {
            $FormFieldDefaultValue = $FormFieldSelectOptions[7]['value'];
        }
        else {
            //First initial, default enable
            $FormFieldDefaultValue = $FormFieldSelectOptions[0]['value'];
        }
        //print_R($FormFieldDefaultValue);
        //$edit_default_1['Default'][] = ['FieldName'=>$FieldName,'FieldType'=>"FieldTypeFollowByFormSetting",'FieldGoup'=>"No",'FieldSearch'=>"No",'FieldImport'=>"No"];
        //$FormFieldDefaultValue = $FormFieldSelectOptions[0]['value'];
        if(strpos($FieldName,"审核状态")>0) {
            $FormFieldDefaultValue = "ListView_Use_AddEdit_NotUse";
        }
        elseif(strpos($FieldName,"审核时间")>0) {
            $FormFieldDefaultValue = "ListView_Use_AddEdit_NotUse";
        }
        elseif(strpos($FieldName,"审核人")>0) {
            $FormFieldDefaultValue = "ListView_Use_AddEdit_NotUse";
        }
        elseif(strpos($FieldName,"审核意见")>0) {
            $FormFieldDefaultValue = "ListView_Use_AddEdit_NotUse";
        }
        elseif(strpos($FieldName,"申请人")>0) {
            $FormFieldDefaultValue = "ListView_Use_AddEdit_NotUse";
        }
        elseif(strpos($FieldName,"申请状态")>0) {
            $FormFieldDefaultValue = "ListView_Use_AddEdit_NotUse";
        }
        elseif(strpos($FieldName,"提交状态")>0) {
            $FormFieldDefaultValue = "ListView_Use_AddEdit_NotUse";
        }
        elseif(strpos($FieldName,"申请时间")>0) {
            $FormFieldDefaultValue = "ListView_Use_AddEdit_NotUse";
        }


        $defaultValues_1["FieldType_".$FieldName] = $FormFieldDefaultValue;
        $edit_default_1['Default'][] = ['name' => "FieldType_".$FieldName, 'show'=>true, 'type'=>'select', 'options'=>$FormFieldSelectOptions, 'label' => $FieldName, 'value' => $FormFieldSelectOptions[7]['value'], 'placeholder' => $FieldName, 'helptext' => "", 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>6]];

        if(in_array($FieldName,["学期","学期名称","班级","班级名称","课程","课程名称"])) {
            $defaultValues_1["FieldGroup_".$FieldName] = true;
        }
        else {
            $defaultValues_1["FieldGroup_".$FieldName] = false;
        }
        $edit_default_1['Default'][] = ['name' => "FieldGroup_".$FieldName, 'show'=>true, 'type'=>'Switch', 'label' => __("Field Group"), 'value' => false, 'placeholder' => $FieldName, 'helptext' => "", 'rules' => ['required' => true, 'disabled' => false, 'xs'=>4, 'sm'=>2]];

        $defaultValues_1["FieldSearch_".$FieldName] = true;
        $edit_default_1['Default'][] = ['name' => "FieldSearch_".$FieldName, 'show'=>true, 'type'=>'Switch', 'label' => __("Search"), 'value' => false, 'placeholder' => $FieldName, 'helptext' => "", 'rules' => ['required' => true, 'disabled' => false, 'xs'=>4, 'sm'=>1]];

        $defaultValues_1["FieldImport_".$FieldName] = true;
        $edit_default_1['Default'][] = ['name' => "FieldImport_".$FieldName, 'show'=>true, 'type'=>'Switch', 'label' => __("Import"), 'value' => false, 'placeholder' => $FieldName, 'helptext' => "", 'rules' => ['required' => true, 'disabled' => false, 'xs'=>4, 'sm'=>1]];

        $defaultValues_1["FieldExport_".$FieldName] = true;
        $edit_default_1['Default'][] = ['name' => "FieldExport_".$FieldName, 'show'=>true, 'type'=>'Switch', 'label' => __("Export"), 'value' => false, 'placeholder' => $FieldName, 'helptext' => "", 'rules' => ['required' => true, 'disabled' => false, 'xs'=>4, 'sm'=>1]];

        $defaultValues_1["FieldEditable_".$FieldName] = false;
        $edit_default_1['Default'][] = ['name' => "FieldEditable_".$FieldName, 'show'=>true, 'type'=>'Switch', 'label' => __("List Editable"), 'value' => false, 'placeholder' => $FieldName, 'helptext' => "", 'rules' => ['required' => true, 'disabled' => false, 'xs'=>4, 'sm'=>1]];
    }
}

$edit_default_1_mode = [['value'=>"Default", 'label'=>__("")]];

if($_GET['action']=="edit_default_1"&&$id!='')         {
    $sql    = "select * from form_formflow where id = '$id'";
    $rs     = $db->Execute($sql);
    $FlowName   = $rs->fields['FlowName'];
    $Setting    = $rs->fields['Setting'];
    $SettingMap = unserialize(base64_decode($Setting));
    if(is_array($SettingMap))   {
        $defaultValues_1_keys = array_keys($defaultValues_1);
        foreach($SettingMap as $value => $label)  {
            // && substr($value,0,strlen("FieldImport_"))!="FieldImport_"
            if(in_array($value, $defaultValues_1_keys))  {
                $defaultValues_1[$value] = $label;
            }
        }
    }
    $edit_default['allFields']      = $edit_default_1;
    $edit_default['allFieldsMode']  = $edit_default_1_mode;
    $edit_default['defaultValues']  = $defaultValues_1;
    $edit_default['dialogContentHeight']  = "90%";
    $edit_default['componentsize']  = "small";
    $edit_default['submitaction']   = "edit_default_1_data";
    $edit_default['submittext']     = __("Submit");
    $edit_default['canceltext']     = __("Cancel");
    $edit_default['titletext']      = __("Design Form Field Type");
    $edit_default['titlememo']      = __("Manage All Form Fields in Table");
    $edit_default['tablewidth']     = 550;

    $RS['edit_default'] = $edit_default;
    $RS['status'] = "OK";
    $RS['data'] = $defaultValues_1;
    $RS['sql'] = $sql;
    $RS['forceuse'] = true;
    $RS['msg'] = __("Get Data Success");
    print_R(json_encode($RS));
    exit;
}



?>