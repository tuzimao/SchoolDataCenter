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
$ForbiddenAccessUrlList = ['form_formflow_edit_default_4.php'];
if(in_array($CurrentUrlFileName, $ForbiddenAccessUrlList)) exit;


//#########################################################################################################################
//Bottom Button############################################################################################################
//#########################################################################################################################
$edit_default_4 = [];

$Msg_Reminder_Rule_Method = [];
$Msg_Reminder_Rule_Method[] = ['value'=>"=", 'label'=>__("=")];
$Msg_Reminder_Rule_Method[] = ['value'=>"in", 'label'=>__("in")];
$Msg_Reminder_Rule_Method[] = ['value'=>"not in", 'label'=>__("not in")];

$MaxMsgSections = 3; // other setting in data_enginee_function.php
for($i=1;$i<=$MaxMsgSections;$i++)     {
    $edit_default_4['Msg_Reminder_Rule_'.$i][] = ['name' => "Msg_Reminder_Rule_Field_Name_{$i}_1", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("Msg_Reminder_Rule_Field_Name_1"), 'value' => $MetaColumnNamesOptionsAll[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>4]];
    $edit_default_4['Msg_Reminder_Rule_'.$i][] = ['name' => "Msg_Reminder_Rule_Field_Method_{$i}_1", 'show'=>true, 'type'=>'select', 'options'=>$Msg_Reminder_Rule_Method, 'label' => __("Msg_Reminder_Rule_Field_Method_1"), 'value' => $Msg_Reminder_Rule_Method[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>3]];
    $edit_default_4['Msg_Reminder_Rule_'.$i][] = ['name' => "Msg_Reminder_Rule_Field_Value_{$i}_1", 'show'=>true, 'type'=>"input", 'label' => __("Msg_Reminder_Rule_Field_Value_1"), 'value' => "", 'placeholder' => "", 'helptext' => __("E.g.: *, NULL, or other value"), 'rules' => ['required' => false,'xs'=>12, 'sm'=>5, 'disabled' => false]];

    $edit_default_4['Msg_Reminder_Rule_'.$i][] = ['name' => "Msg_Reminder_Rule_Field_Name_{$i}_2", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("Msg_Reminder_Rule_Field_Name_2"), 'value' => $MetaColumnNamesOptionsAll[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>4]];
    $edit_default_4['Msg_Reminder_Rule_'.$i][] = ['name' => "Msg_Reminder_Rule_Field_Method_{$i}_2", 'show'=>true, 'type'=>'select', 'options'=>$Msg_Reminder_Rule_Method, 'label' => __("Msg_Reminder_Rule_Field_Method_2"), 'value' => $Msg_Reminder_Rule_Method[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>3]];
    $edit_default_4['Msg_Reminder_Rule_'.$i][] = ['name' => "Msg_Reminder_Rule_Field_Value_{$i}_2", 'show'=>true, 'type'=>"input", 'label' => __("Msg_Reminder_Rule_Field_Value_2"), 'value' => "", 'placeholder' => "", 'helptext' => __("E.g.: *, NULL, or other value"), 'rules' => ['required' => false,'xs'=>12, 'sm'=>5, 'disabled' => false]];
    $edit_default_4['Msg_Reminder_Rule_'.$i][] = ['name' => "Msg_Reminder_Rule_Content_{$i}", 'show'=>true, 'type'=>"textarea", 'label' => __("Msg_Reminder_Rule_Content"), 'value' => "", 'placeholder' => "", 'helptext' => __("[FieldName] will replace the real value"), 'rules' => ['required' => false,'xs'=>12, 'sm'=>12, 'disabled' => false]];

    $edit_default_4['Msg_Reminder_Rule_'.$i][] = ['name' => "divider", 'show'=>true, 'type'=>"divider", 'label' => __("divider"), 'value' => "", 'placeholder' => "", 'helptext' => __(""), 'rules' => ['required' => false,'xs'=>12, 'sm'=>12, 'disabled' => false]];
    $FieldName = "";
    $CurrentUserFieldTypeArray = explode(":","autocompletemulti:data_user:1:2:admin");
    $TableNameTemp      = $CurrentUserFieldTypeArray[1];
    $KeyField           = $CurrentUserFieldTypeArray[2];
    $ValueField         = $CurrentUserFieldTypeArray[3];
    $DefaultValue       = $CurrentUserFieldTypeArray[4];
    $WhereField         = ForSqlInjection($CurrentUserFieldTypeArray[5]);
    $WhereValue         = ForSqlInjection($CurrentUserFieldTypeArray[6]);
    $MetaColumnNamesTemp    = GLOBAL_MetaColumnNames($TableNameTemp);
    if($TableNameTemp=="form_formdict" && sizeof($CurrentUserFieldTypeArray)==7)   {
        $sql = "select `".$MetaColumnNamesTemp[$KeyField]."` as value, `".$MetaColumnNamesTemp[$ValueField]."` as label,ExtraControl from $TableNameTemp where $AddSqlTemp $WhereField = '".$WhereValue."' order by SortNumber asc, `".$MetaColumnNamesTemp[$ValueField]."` asc";
    }
    elseif(sizeof($CurrentUserFieldTypeArray)==7)   {
        $sql = "select `".$MetaColumnNamesTemp[$KeyField]."` as value, `".$MetaColumnNamesTemp[$ValueField]."` as label from $TableNameTemp where $AddSqlTemp $WhereField = '".$WhereValue."' order by SortNumber asc, `".$MetaColumnNamesTemp[$ValueField]."` asc";
    }
    elseif(sizeof($CurrentUserFieldTypeArray)==5||sizeof($CurrentUserFieldTypeArray)==4)   {
        $sql = "select `".$MetaColumnNamesTemp[$KeyField]."` as value, `".$MetaColumnNamesTemp[$ValueField]."` as label from $TableNameTemp where 1=1 $AddSqlTemp order by `".$MetaColumnNamesTemp[$ValueField]."` asc, id asc";
    }
    else {
        print "autocompletemulti para error!";exit;
    }
    $rs = $db->Execute($sql) or print($sql);
    $UserRecords = $rs->GetArray();
    $DefaultValueTemp = $SettingMap["Msg_Reminder_Object_Select_Users_{$i}"];
    $FieldCodeName = $FieldName;
    $edit_default_4['Msg_Reminder_Rule_'.$i][] = ['name' => "Msg_Reminder_Object_Select_Users_{$i}", 'code' => "Msg_Reminder_Object_Select_Users_{$i}", 'FieldTypeArray'=>$CurrentUserFieldTypeArray, 'show'=>true, 'type'=>"autocompletemulti", 'options'=>$UserRecords, 'label' => __("Msg_Reminder_Object_Select_Users"), 'value' => $DefaultValueTemp, 'placeholder' => __(""), 'helptext' => __(""), 'rules' => ['required' => false,'xs'=>12, 'sm'=>12,'disabled' => false]];
    $edit_default_4['Msg_Reminder_Rule_'.$i][] = ['name' => "divider", 'show'=>true, 'type'=>"divider", 'label' => __("divider"), 'value' => "", 'placeholder' => "", 'helptext' => __(""), 'rules' => ['required' => false,'xs'=>12, 'sm'=>12, 'disabled' => false]];

    $edit_default_4['Msg_Reminder_Rule_'.$i][] = ['name' => "Msg_Reminder_Rule_Storage_StudentCode_{$i}", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("Msg_Reminder_Rule_Storage_StudentCode"), 'value' => $MetaColumnNamesOptionsAll[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>6]];
    $edit_default_4['Msg_Reminder_Rule_'.$i][] = ['name' => "Msg_Reminder_Rule_Storage_StudentClass_{$i}", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("Msg_Reminder_Rule_Storage_StudentClass"), 'value' => $MetaColumnNamesOptionsAll[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>6]];
    $Msg_Reminder_Rule_Storage_StudentCodeAndClass_Object = [];
    $Msg_Reminder_Rule_Storage_StudentCodeAndClass_Object[] = ['value'=>"学生", 'label'=>__("学生")];
    //$Msg_Reminder_Rule_Storage_StudentCodeAndClass_Object[] = ['value'=>"家长", 'label'=>__("家长")];
    $Msg_Reminder_Rule_Storage_StudentCodeAndClass_Object[] = ['value'=>"班主任", 'label'=>__("班主任")];
    $Msg_Reminder_Rule_Storage_StudentCodeAndClass_Object[] = ['value'=>"年段长", 'label'=>__("年段长")];
    $Msg_Reminder_Rule_Storage_StudentCodeAndClass_Object[] = ['value'=>"宿管员", 'label'=>__("宿管员")];
    $Msg_Reminder_Rule_Storage_StudentCodeAndClass_Object[] = ['value'=>"系部", 'label'=>__("系部")];
    $Msg_Reminder_Rule_Storage_StudentCodeAndClass_Object[] = ['value'=>"专业", 'label'=>__("专业")];
    $Msg_Reminder_Rule_Storage_StudentCodeAndClass_Object[] = ['value'=>"本班所有学生", 'label'=>__("本班所有学生")];
    $Msg_Reminder_Rule_Storage_StudentCodeAndClass_Object[] = ['value'=>"本专业所有学生", 'label'=>__("本专业所有学生")];
    $Msg_Reminder_Rule_Storage_StudentCodeAndClass_Object[] = ['value'=>"本系所有学生", 'label'=>__("本系所有学生")];
    $Msg_Reminder_Rule_Storage_StudentCodeAndClass_Object[] = ['value'=>"本校所有学生", 'label'=>__("本校所有学生")];

    $edit_default_4['Msg_Reminder_Rule_'.$i][] = ['name' => "Msg_Reminder_Rule_Storage_StudentCodeAndClass_Reminder_Object_{$i}", 'show'=>true, 'type'=>'checkbox', 'options'=>$Msg_Reminder_Rule_Storage_StudentCodeAndClass_Object, 'label' => __("Msg_Reminder_Rule_Storage_StudentCodeAndClass_Reminder_Object"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true, 'disabled' => false, 'row'=>true, 'xs'=>12, 'sm'=>12]];


    $edit_default_4['Msg_Reminder_Rule_'.$i][] = ['name' => "divider", 'show'=>true, 'type'=>"divider", 'label' => __("divider"), 'value' => "", 'placeholder' => "", 'helptext' => __(""), 'rules' => ['required' => false,'xs'=>12, 'sm'=>12, 'disabled' => false]];
    $edit_default_4['Msg_Reminder_Rule_'.$i][] = ['name' => "Msg_Reminder_Rule_Strorage_User_{$i}", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("Msg_Reminder_Rule_Strorage_User"), 'value' => $MetaColumnNamesOptionsAll[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>6]];
    $edit_default_4['Msg_Reminder_Rule_'.$i][] = ['name' => "Msg_Reminder_Rule_Strorage_OtherStudentCode_{$i}", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("Msg_Reminder_Rule_Strorage_OtherStudentCode"), 'value' => $MetaColumnNamesOptionsAll[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>6]];

    $edit_default_4['Msg_Reminder_Rule_'.$i][] = ['name' => "divider", 'show'=>true, 'type'=>"divider", 'label' => __("divider"), 'value' => "", 'placeholder' => "", 'helptext' => __(""), 'rules' => ['required' => false,'xs'=>12, 'sm'=>12, 'disabled' => false]];
    $edit_default_4['Msg_Reminder_Rule_'.$i][] = ['name' => "Msg_Reminder_Rule_Strorage_DeptID_{$i}", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("Msg_Reminder_Rule_Strorage_DeptID"), 'value' => $MetaColumnNamesOptionsAll[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>6]];
    $Msg_Reminder_Rule_Strorage_Dept_Object = [];
    $Msg_Reminder_Rule_Strorage_Dept_Object[] = ['value'=>"MANAGER", 'label'=>__("MANAGER")];
    $Msg_Reminder_Rule_Strorage_Dept_Object[] = ['value'=>"LEADER1", 'label'=>__("LEADER1")];
    $Msg_Reminder_Rule_Strorage_Dept_Object[] = ['value'=>"LEADER2", 'label'=>__("LEADER2")];
    $edit_default_4['Msg_Reminder_Rule_'.$i][] = ['name' => "Msg_Reminder_Rule_Strorage_Dept_Object_{$i}", 'show'=>true, 'type'=>'select', 'options'=>$Msg_Reminder_Rule_Strorage_Dept_Object, 'label' => __("Msg_Reminder_Rule_Strorage_Dept_Object"), 'value' => $Msg_Reminder_Rule_Strorage_Dept_Object[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>6]];

    $edit_default_4['Msg_Reminder_Rule_'.$i][] = ['name' => "divider", 'show'=>true, 'type'=>"divider", 'label' => __("divider"), 'value' => "", 'placeholder' => "", 'helptext' => __(""), 'rules' => ['required' => false,'xs'=>12, 'sm'=>12, 'disabled' => false]];
    $edit_default_4['Msg_Reminder_Rule_'.$i][] = ['name' => "Msg_Reminder_Rule_Strorage_FacultyID_{$i}", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("Msg_Reminder_Rule_Strorage_FacultyID"), 'value' => $MetaColumnNamesOptionsAll[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>6]];
    $edit_default_4['Msg_Reminder_Rule_'.$i][] = ['name' => "Msg_Reminder_Rule_Strorage_Faculty_Object_{$i}", 'show'=>true, 'type'=>'select', 'options'=>$Faculty_Filter_Field, 'label' => __("Msg_Reminder_Rule_Strorage_Faculty_Object"), 'value' => $Faculty_Filter_Field[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>6]];

    $edit_default_4['Msg_Reminder_Rule_'.$i][] = ['name' => "divider", 'show'=>true, 'type'=>"divider", 'label' => __("divider"), 'value' => "", 'placeholder' => "", 'helptext' => __(""), 'rules' => ['required' => false,'xs'=>12, 'sm'=>12, 'disabled' => false]];
    $Msg_Reminder_Rule_Reminder_Method = [];
    $Msg_Reminder_Rule_Reminder_Method[] = ['value'=>"PC", 'label'=>__("PC")];
    $edit_default_4['Msg_Reminder_Rule_'.$i][] = ['name' => "Msg_Reminder_Rule_Reminder_Method_{$i}", 'show'=>true, 'type'=>'select', 'options'=>$Msg_Reminder_Rule_Reminder_Method, 'label' => __("Msg_Reminder_Rule_Strorage_Faculty_Object"), 'value' => $Msg_Reminder_Rule_Reminder_Method[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>12]];

    $edit_default_4_mode[] = ['value'=>"Msg_Reminder_Rule_{$i}", 'label'=>__("Msg_Reminder_Rule_{$i}")];
}


$defaultValues_4 = [];
foreach($edit_default_4 as $ModeName=>$allFieldItem) {
    foreach($allFieldItem as $ITEM) {
        if($ITEM['type'] == "autocompletemulti")  {
            $defaultValues_4[$ITEM['code']] = $ITEM['value'];
        }
        else {
            $defaultValues_4[$ITEM['name']] = $ITEM['value'];
        }
    }
}

if($_GET['action']=="edit_default_4"&&$id!='')         {
    $sql    = "select * from form_formflow where id = '$id'";
    $rs     = $db->Execute($sql);
    $FlowName   = $rs->fields['FlowName'];
    $Setting    = $rs->fields['Setting'];
    $SettingMap = unserialize(base64_decode($Setting));
    $FlowName   = $rs->fields['FlowName'];
    if(is_array($SettingMap))   {
        $defaultValues_4_keys = array_keys($defaultValues_4);
        foreach($SettingMap as $value => $label)  {
            if(in_array($value, $defaultValues_4_keys))  {
                $defaultValues_4[$value] = $label;
            }
        }
    }
    $edit_default['allFields']      = $edit_default_4;
    $edit_default['allFieldsMode']  = $edit_default_4_mode;
    $edit_default['defaultValues']  = $defaultValues_4;
    $edit_default['dialogContentHeight']  = "90%";
    $edit_default['componentsize']  = "small";
    $edit_default['submitaction']   = "edit_default_4_data";
    $edit_default['submittext']     = __("Submit");
    $edit_default['canceltext']     = __("Cancel");
    $edit_default['titletext']      = __("Design Form Field Type");
    $edit_default['titlememo']      = __("Manage All Form Fields in Table");
    $edit_default['tablewidth']     = 550;

    $RS['edit_default'] = $edit_default;
    $RS['status'] = "OK";
    $RS['data'] = $defaultValues_4;
    $RS['sql'] = $sql;
    $RS['forceuse'] = true;
    $RS['msg'] = __("Get Data Success");
    print_R(json_encode($RS));
    exit;
}

?>