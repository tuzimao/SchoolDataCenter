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
$ForbiddenAccessUrlList = ['form_formflow_edit_default_7.php'];
if(in_array($CurrentUrlFileName, $ForbiddenAccessUrlList)) exit;


//#########################################################################################################################
//NodeFlow#################################################################################################################
//#########################################################################################################################
$edit_default_7 = [];

$sql    = "select MenuOneName from data_menuone order by SortNumber asc, MenuOneName asc";
$rsf    = $db->Execute($sql);
$rsf_a  = $rsf->GetArray();
$MenuOneNameArray = [];
foreach($rsf_a as $Item)  {
    $MenuOneNameArray[] = ['value'=>$Item['MenuOneName'],'label'=>$Item['MenuOneName']];
}
$NodeType_Array    = [];
$NodeType_Array[]  = ['value'=>"工作流",'label'=>"工作流"];
$NodeType_Array[]  = ['value'=>"菜单",'label'=>"菜单"];

$edit_default_7['Menu_Location'][] = ['name' => "NodeType", 'show'=>true, 'type'=>'select', 'options'=>$NodeType_Array, 'label' => __("NodeType"), 'value' => $NodeType_Array[1]['value'], 'placeholder' => "", 'helptext' => __("NodeTypeHelpText"), 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>4]];
$edit_default_7['Menu_Location'][] = ['name' => "NextStep", 'show'=>true, 'type'=>"input", 'label' => __("NextStep"), 'value' => "", 'placeholder' => "", 'helptext' => __("NextStepHelpText"), 'rules' => ['required' => false,'xs'=>12, 'sm'=>4, 'disabled' => false]];
$MenuTab_Options = [];
$MenuTab_Options[] = ['value'=>'Yes', 'label'=>__('Yes')];
$MenuTab_Options[] = ['value'=>'No', 'label'=>__('No')];
$edit_default_7['Menu_Location'][] = ['name' => "IsStartNode", 'show'=>true, 'type'=>'select', 'options'=>$MenuTab_Options, 'label' => __("IsStartNode"), 'value' => "No", 'placeholder' => "", 'helptext' => __(""), 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>2]];

$StepNameArray = [];
$StepNameArray[] = ['value'=>0, 'label'=>"未设置"];
for($X=1;$X<=15;$X++) {
    $StepNameArray[] = ['value'=>$X, 'label'=>$X];
}
$edit_default_7['Menu_Location'][] = ['name' => "StepName", 'show'=>true, 'type'=>'select', 'options'=>$StepNameArray, 'label' => __("StepName"), 'value' => 0, 'placeholder' => "", 'helptext' => __(""), 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>2]];
$edit_default_7['Menu_Location'][] = ['name' => "Menu_One", 'show'=>true, 'type'=>'select', 'options'=>$MenuOneNameArray, 'label' => __("Menu_One"), 'value' => $MetaColumnNamesOptionsAll[1]['value'], 'placeholder' => "", 'helptext' => __("Allow_Repeat"), 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>4]];
$edit_default_7['Menu_Location'][] = ['name' => "Menu_Two", 'show'=>true, 'type'=>"input", 'label' => __("Menu_Two"), 'value' => "", 'placeholder' => "", 'helptext' => __("Allow_Repeat"), 'rules' => ['required' => false,'xs'=>12, 'sm'=>4, 'disabled' => false]];
$edit_default_7['Menu_Location'][] = ['name' => "Menu_Three", 'show'=>true, 'type'=>"input", 'label' => __("Menu_Three"), 'value' => "", 'placeholder' => "", 'helptext' => __("Optional"), 'rules' => ['required' => false,'xs'=>12, 'sm'=>4, 'disabled' => false]];
$edit_default_7['Menu_Location'][] = ['name' => "FaceTo", 'show'=>true, 'type'=>'select', 'options'=>$FaceToOptions, 'label' => __("Face_To"), 'value' => "AuthUser", 'placeholder' => "", 'helptext' => __(""), 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>4]];
$edit_default_7['Menu_Location'][] = ['name' => "Menu_Three_Icon", 'show'=>true, 'type'=>'autocompletemdi', 'options'=>[], 'label' => __("Menu_Three_Icon"), 'value' => "account-outline", 'placeholder' => "", 'helptext' => __(""), 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>4]];
$MenuTab_Options = [];
$MenuTab_Options[] = ['value'=>'Yes', 'label'=>__('Yes')];
$MenuTab_Options[] = ['value'=>'No', 'label'=>__('No')];
$edit_default_7['Menu_Location'][] = ['name' => "MenuTab", 'show'=>true, 'type'=>'select', 'options'=>$MenuTab_Options, 'label' => __("Menu_Tab"), 'value' => "Yes", 'placeholder' => "", 'helptext' => __(""), 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>4]];

$Page_Role_Array = [];
$Page_Role_Array[] = ['value'=>"None", 'label'=>__("None")];
$Page_Role_Array[] = ['value'=>"Student", 'label'=>__("Student")];
//$Page_Role_Array[] = ['value'=>"Parent", 'label'=>__("Parent")];
$Page_Role_Array[] = ['value'=>"ClassMaster", 'label'=>__("ClassMaster")];
$Page_Role_Array[] = ['value'=>"ClassTeacher", 'label'=>__("ClassTeacher")];
$Page_Role_Array[] = ['value'=>"Faculty", 'label'=>__("Faculty")];
$Page_Role_Array[] = ['value'=>"Dormitory", 'label'=>__("Dormitory")];
$Page_Role_Array[] = ['value'=>"Department", 'label'=>__("Department")];
$Page_Role_Array[] = ['value'=>"Vice-president", 'label'=>__("Vice-president")];
$Page_Role_Array[] = ['value'=>"President", 'label'=>__("President")];

$Extra_Priv_Filter_Method = [];
$Extra_Priv_Filter_Method[] = ['value'=>"=", 'label'=>__("=")];
$Extra_Priv_Filter_Method[] = ['value'=>"!=", 'label'=>__("!=")];
$Extra_Priv_Filter_Method[] = ['value'=>">", 'label'=>__(">")];
$Extra_Priv_Filter_Method[] = ['value'=>">=", 'label'=>__(">=")];
$Extra_Priv_Filter_Method[] = ['value'=>"<", 'label'=>__("<")];
$Extra_Priv_Filter_Method[] = ['value'=>"<=", 'label'=>__("<=")];
$Extra_Priv_Filter_Method[] = ['value'=>"in", 'label'=>__("in")];
$Extra_Priv_Filter_Method[] = ['value'=>"not in", 'label'=>__("not in")];
$Extra_Priv_Filter_Method[] = ['value'=>"like", 'label'=>__("like")];
$Extra_Priv_Filter_Method[] = ['value'=>"Today", 'label'=>__("Today")];
$Extra_Priv_Filter_Method[] = ['value'=>"<->", 'label'=>__("<->")];
$Extra_Priv_Filter_Method[] = ['value'=>"BeforeDays", 'label'=>__("BeforeDays")];
$Extra_Priv_Filter_Method[] = ['value'=>"AfterDays", 'label'=>__("AfterDays")];
$Extra_Priv_Filter_Method[] = ['value'=>"BeforeAndAfterDays", 'label'=>__("BeforeAndAfterDays")];
$Extra_Priv_Filter_Method[] = ['value'=>"CurrentSemester", 'label'=>__("CurrentSemester")];

$Faculty_Filter_Field = [];
$Faculty_Filter_Field[] = ['value'=>"None", 'label'=>__("None")];
$Faculty_Filter_Field[] = ['value'=>"学籍二级管理", 'label'=>__("学籍二级管理")];
$Faculty_Filter_Field[] = ['value'=>"学生请假二级管理", 'label'=>__("学生请假二级管理")];
$Faculty_Filter_Field[] = ['value'=>"奖惩补助二级管理", 'label'=>__("奖惩补助二级管理")];
$Faculty_Filter_Field[] = ['value'=>"教学计划二级管理", 'label'=>__("教学计划二级管理")];
$Faculty_Filter_Field[] = ['value'=>"量化考核二级管理", 'label'=>__("量化考核二级管理")];
$Faculty_Filter_Field[] = ['value'=>"岗位实习二级管理", 'label'=>__("岗位实习二级管理")];
$Faculty_Filter_Field[] = ['value'=>"学生考勤二级管理", 'label'=>__("学生考勤二级管理")];
$Faculty_Filter_Field[] = ['value'=>"学生成绩二级管理", 'label'=>__("学生成绩二级管理")];
$Faculty_Filter_Field[] = ['value'=>"班级事务二级管理", 'label'=>__("班级事务二级管理")];
$Faculty_Filter_Field[] = ['value'=>"固定资产二级管理", 'label'=>__("固定资产二级管理")];
$Faculty_Filter_Field[] = ['value'=>"学生健康二级管理", 'label'=>__("学生健康二级管理")];

$Dormitory_Filter_Field = [];
$Dormitory_Filter_Field[] = ['value'=>"None", 'label'=>__("None")];
$Dormitory_Filter_Field[] = ['value'=>"宿舍楼", 'label'=>__("宿舍楼")];
$Dormitory_Filter_Field[] = ['value'=>"宿舍房间", 'label'=>__("宿舍房间")];

$EnableFields = [];
//$EnableFields['Faculty'] = ["Faculty_Filter_Field"];
$DisableFields = [];
$edit_default_7['Page_Role'][] = ['name' => "Page_Role_Name", 'show'=>true, 'type'=>'select', 'options'=>$Page_Role_Array, 'label' => __("Page_Role_Name"), 'value' => $Page_Role_Array[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>12], 'EnableFields'=>$EnableFields, 'DisableFields'=>$DisableFields];
$edit_default_7['Page_Role'][] = ['name' => "Faculty_Filter_Field", 'show'=>true, 'type'=>'select', 'options'=>$Faculty_Filter_Field, 'label' => __("Faculty_Filter_Field"), 'value' => $Faculty_Filter_Field[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>6]];
$edit_default_7['Page_Role'][] = ['name' => "Dormitory_Filter_Field", 'show'=>true, 'type'=>'select', 'options'=>$Dormitory_Filter_Field, 'label' => __("Dormitory_Filter_Field"), 'value' => $Dormitory_Filter_Field[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>6]];

$edit_default_7['Page_Role'][] = ['name' => "Extra_Priv_Filter_Field_One", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("Extra_Priv_Filter_Field_One"), 'value' => $MetaColumnNamesOptionsAll[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>4]];
$edit_default_7['Page_Role'][] = ['name' => "Extra_Priv_Filter_Method_One", 'show'=>true, 'type'=>'select', 'options'=>$Extra_Priv_Filter_Method, 'label' => __("Extra_Priv_Filter_Method_One"), 'value' => $Extra_Priv_Filter_Method[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>4]];
$edit_default_7['Page_Role'][] = ['name' => "Extra_Priv_Filter_Value_One", 'show'=>true, 'type'=>"input", 'label' => __("Extra_Priv_Filter_Value_One"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>4, 'disabled' => false]];

$edit_default_7['Page_Role'][] = ['name' => "Extra_Priv_Filter_Field_Two", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("Extra_Priv_Filter_Field_Two"), 'value' => $MetaColumnNamesOptionsAll[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>4]];
$edit_default_7['Page_Role'][] = ['name' => "Extra_Priv_Filter_Method_Two", 'show'=>true, 'type'=>'select', 'options'=>$Extra_Priv_Filter_Method, 'label' => __("Extra_Priv_Filter_Method_Two"), 'value' => $Extra_Priv_Filter_Method[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>4]];
$edit_default_7['Page_Role'][] = ['name' => "Extra_Priv_Filter_Value_Two", 'show'=>true, 'type'=>"input", 'label' => __("Extra_Priv_Filter_Value_Two"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>4, 'disabled' => false]];

$edit_default_7['Page_Role'][] = ['name' => "Extra_Priv_Filter_Field_Three", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("Extra_Priv_Filter_Field_Three"), 'value' => $MetaColumnNamesOptionsAll[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>4]];
$edit_default_7['Page_Role'][] = ['name' => "Extra_Priv_Filter_Method_Three", 'show'=>true, 'type'=>'select', 'options'=>$Extra_Priv_Filter_Method, 'label' => __("Extra_Priv_Filter_Method_Three"), 'value' => $Extra_Priv_Filter_Method[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>4]];
$edit_default_7['Page_Role'][] = ['name' => "Extra_Priv_Filter_Value_Three", 'show'=>true, 'type'=>"input", 'label' => __("Extra_Priv_Filter_Value_Three"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>4, 'disabled' => false]];

$edit_default_7['Page_Role'][] = ['name' => "Extra_Priv_Filter_Field_Four", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("Extra_Priv_Filter_Field_Four"), 'value' => $MetaColumnNamesOptionsAll[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>4]];
$edit_default_7['Page_Role'][] = ['name' => "Extra_Priv_Filter_Method_Four", 'show'=>true, 'type'=>'select', 'options'=>$Extra_Priv_Filter_Method, 'label' => __("Extra_Priv_Filter_Method_Four"), 'value' => $Extra_Priv_Filter_Method[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>4]];
$edit_default_7['Page_Role'][] = ['name' => "Extra_Priv_Filter_Value_Four", 'show'=>true, 'type'=>"input", 'label' => __("Extra_Priv_Filter_Value_Four"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>4, 'disabled' => false]];

$edit_default_7['Page_Role'][] = ['name' => "Extra_Priv_Filter_Field_Five", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("Extra_Priv_Filter_Field_Five"), 'value' => $MetaColumnNamesOptionsAll[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>4]];
$edit_default_7['Page_Role'][] = ['name' => "Extra_Priv_Filter_Method_Five", 'show'=>true, 'type'=>'select', 'options'=>$Extra_Priv_Filter_Method, 'label' => __("Extra_Priv_Filter_Method_Five"), 'value' => $Extra_Priv_Filter_Method[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>4]];
$edit_default_7['Page_Role'][] = ['name' => "Extra_Priv_Filter_Value_Five", 'show'=>true, 'type'=>"input", 'label' => __("Extra_Priv_Filter_Value_Five"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>4, 'disabled' => false]];


$edit_default_7['Page_Role'][] = ['name' => "Extra_Priv_Filter_Or_Field_One", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("Extra_Priv_Filter_Or_Field_One"), 'value' => $MetaColumnNamesOptionsAll[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>4]];
$edit_default_7['Page_Role'][] = ['name' => "Extra_Priv_Filter_Or_Method_One", 'show'=>true, 'type'=>'select', 'options'=>$Extra_Priv_Filter_Method, 'label' => __("Extra_Priv_Filter_Or_Method_One"), 'value' => $Extra_Priv_Filter_Method[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>4]];
$edit_default_7['Page_Role'][] = ['name' => "Extra_Priv_Filter_Or_Value_One", 'show'=>true, 'type'=>"input", 'label' => __("Extra_Priv_Filter_Or_Value_One"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>4, 'disabled' => false]];

$edit_default_7['Page_Role'][] = ['name' => "Extra_Priv_Filter_Or_Field_Two", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("Extra_Priv_Filter_Or_Field_Two"), 'value' => $MetaColumnNamesOptionsAll[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>4]];
$edit_default_7['Page_Role'][] = ['name' => "Extra_Priv_Filter_Or_Method_Two", 'show'=>true, 'type'=>'select', 'options'=>$Extra_Priv_Filter_Method, 'label' => __("Extra_Priv_Filter_Or_Method_Two"), 'value' => $Extra_Priv_Filter_Method[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>4]];
$edit_default_7['Page_Role'][] = ['name' => "Extra_Priv_Filter_Or_Value_Two", 'show'=>true, 'type'=>"input", 'label' => __("Extra_Priv_Filter_Or_Value_Two"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>4, 'disabled' => false]];

$edit_default_7['Page_Role'][] = ['name' => "Extra_Priv_Filter_Or_Field_Three", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("Extra_Priv_Filter_Or_Field_Three"), 'value' => $MetaColumnNamesOptionsAll[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>4]];
$edit_default_7['Page_Role'][] = ['name' => "Extra_Priv_Filter_Or_Method_Three", 'show'=>true, 'type'=>'select', 'options'=>$Extra_Priv_Filter_Method, 'label' => __("Extra_Priv_Filter_Or_Method_Three"), 'value' => $Extra_Priv_Filter_Method[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>4]];
$edit_default_7['Page_Role'][] = ['name' => "Extra_Priv_Filter_Or_Value_Three", 'show'=>true, 'type'=>"input", 'label' => __("Extra_Priv_Filter_Or_Value_Three"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>4, 'disabled' => false]];

$edit_default_7['AuthorizedControl'][] = ['name' => "divider", 'show'=>true, 'type'=>"divider", 'label' => __("divider"), 'value' => "", 'placeholder' => "", 'helptext' => __(""), 'rules' => ['required' => false,'xs'=>12, 'sm'=>12, 'disabled' => false]];
$edit_default_7['AuthorizedControl'][] = ['name' => "NodeFlow_AuthorizedUser_名称", 'code' => "NodeFlow_AuthorizedUser", 'FieldTypeArray'=>$CurrentUserFieldTypeArray, 'show'=>true, 'type'=>"autocompletemulti", 'options'=>$UserRecords, 'label' => __("NodeFlow_AuthorizedUser"), 'value' => '', 'placeholder' => __(""), 'helptext' => __("NodeFlow_AuthorizedHelpText"), 'rules' => ['required' => false,'xs'=>12, 'sm'=>12,'disabled' => false]];

$CurrentDepartmentFieldTypeArray = explode(":","autocompletemulti:data_department:0:1:");
$TableNameTemp      = $CurrentDepartmentFieldTypeArray[1];
$KeyField           = $CurrentDepartmentFieldTypeArray[2];
$ValueField         = $CurrentDepartmentFieldTypeArray[3];
$DefaultValue       = $CurrentDepartmentFieldTypeArray[4];
$WhereField         = ForSqlInjection($CurrentDepartmentFieldTypeArray[5]);
$WhereValue         = ForSqlInjection($CurrentDepartmentFieldTypeArray[6]);
$MetaColumnNamesTemp    = GLOBAL_MetaColumnNames($TableNameTemp);
if(sizeof($CurrentDepartmentFieldTypeArray)==5||sizeof($CurrentDepartmentFieldTypeArray)==4)   {
    $sql = "select `".$MetaColumnNamesTemp[$KeyField]."` as value, `".$MetaColumnNamesTemp[$ValueField]."` as label from $TableNameTemp where 1=1 $AddSqlTemp order by `".$MetaColumnNamesTemp[$ValueField]."` asc, id asc";
}
else {
    print "autocompletemulti para error!";exit;
}
$rs = $db->Execute($sql) or print($sql);
$DepartmentRecords = $rs->GetArray();
$edit_default_7['AuthorizedControl'][] = ['name' => "NodeFlow_AuthorizedDept_名称", 'code' => "NodeFlow_AuthorizedDept", 'FieldTypeArray'=>$CurrentDepartmentFieldTypeArray, 'show'=>true, 'type'=>"autocompletemulti", 'options'=>$DepartmentRecords, 'label' => __("NodeFlow_AuthorizedDept"), 'value' => '', 'placeholder' => __(""), 'helptext' => __("NodeFlow_AuthorizedHelpText"), 'rules' => ['required' => false,'xs'=>12, 'sm'=>12,'disabled' => false]];

$CurrentRoleFieldTypeArray = explode(":","autocompletemulti:data_role:0:1:");
$TableNameTemp      = $CurrentRoleFieldTypeArray[1];
$KeyField           = $CurrentRoleFieldTypeArray[2];
$ValueField         = $CurrentRoleFieldTypeArray[3];
$DefaultValue       = $CurrentRoleFieldTypeArray[4];
$WhereField         = ForSqlInjection($CurrentRoleFieldTypeArray[5]);
$WhereValue         = ForSqlInjection($CurrentRoleFieldTypeArray[6]);
$MetaColumnNamesTemp    = GLOBAL_MetaColumnNames($TableNameTemp);
if(sizeof($CurrentRoleFieldTypeArray)==5||sizeof($CurrentRoleFieldTypeArray)==4)   {
    $sql = "select `".$MetaColumnNamesTemp[$KeyField]."` as value, `".$MetaColumnNamesTemp[$ValueField]."` as label from $TableNameTemp where 1=1 $AddSqlTemp order by `".$MetaColumnNamesTemp[$ValueField]."` asc, id asc";
}
else {
    print "autocompletemulti para error!";exit;
}
$rs = $db->Execute($sql) or print($sql);
$RoleRecords = $rs->GetArray();
$edit_default_7['AuthorizedControl'][] = ['name' => "NodeFlow_AuthorizedRole_名称", 'code' => "NodeFlow_AuthorizedRole", 'FieldTypeArray'=>$CurrentRoleFieldTypeArray, 'show'=>true, 'type'=>"autocompletemulti", 'options'=>$RoleRecords, 'label' => __("NodeFlow_AuthorizedRole"), 'value' => '', 'placeholder' => __(""), 'helptext' => __("NodeFlow_AuthorizedHelpText"), 'rules' => ['required' => false,'xs'=>12, 'sm'=>12,'disabled' => false]];
$edit_default_7['AuthorizedControl'][] = ['name' => "NodeFlow_Approval_Execute_Function", 'show'=>true, 'type'=>"input", 'label' => __("NodeFlow_Approval_Execute_Function"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>12, 'disabled' => false]];

$edit_default_7['AuthorizedControl'][] = ['name' => "NodeFlow_Approval_Change_Field_Name", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("NodeFlow_Approval_Change_Field_Name"), 'value' => $MetaColumnNamesOptionsAll[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>3]];
$edit_default_7['AuthorizedControl'][] = ['name' => "NodeFlow_Approval_Change_Field_Value", 'show'=>true, 'type'=>"input", 'label' => __("NodeFlow_Approval_Change_Field_Value"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>3, 'disabled' => false]];
$edit_default_7['AuthorizedControl'][] = ['name' => "NodeFlow_Approval_Change_Field_To_DateTime", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("NodeFlow_Approval_Change_Field_To_DateTime"), 'value' => $MetaColumnNamesOptionsAll[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>3]];
$edit_default_7['AuthorizedControl'][] = ['name' => "NodeFlow_Approval_Change_Field_To_UserId", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("NodeFlow_Approval_Change_Field_To_UserId"), 'value' => $MetaColumnNamesOptionsAll[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>3]];


$edit_default_7['AuthorizedControl'][] = ['name' => "NodeFlow_Approval_Change_ChildTable_Field_Name", 'show'=>true, 'type'=>'input', 'label' => __("NodeFlow_Approval_Change_ChildTable_Field_Name"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>3]];
$edit_default_7['AuthorizedControl'][] = ['name' => "NodeFlow_Approval_Change_ChildTable_Field_Value", 'show'=>true, 'type'=>"input", 'label' => __("NodeFlow_Approval_Change_ChildTable_Field_Value"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>3, 'disabled' => false]];
$edit_default_7['AuthorizedControl'][] = ['name' => "NodeFlow_Approval_Change_ChildTable_Field_To_DateTime", 'show'=>true, 'type'=>'input', 'label' => __("NodeFlow_Approval_Change_ChildTable_Field_To_DateTime"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>3]];
$edit_default_7['AuthorizedControl'][] = ['name' => "NodeFlow_Approval_Change_ChildTable_Field_To_UserId", 'show'=>true, 'type'=>'input', 'label' => __("NodeFlow_Approval_Change_ChildTable_Field_To_UserId"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>3]];

$defaultValues_7 = [];
foreach($edit_default_7 as $ModeName=>$allFieldItem) {
    foreach($allFieldItem as $ITEM) {
        if($ITEM['type'] == "autocompletemulti")  {
            $defaultValues_7[$ITEM['code']] = $ITEM['value'];
        }
        else {
            $defaultValues_7[$ITEM['name']] = $ITEM['value'];
        }
    }
}


$edit_default_7_mode[] = ['value'=>"Menu_Location", 'label'=>__("Menu_Location")];
$edit_default_7_mode[] = ['value'=>"Page_Role", 'label'=>__("Page_Role")];
$edit_default_7_mode[] = ['value'=>"AuthorizedControl", 'label'=>__("AuthorizedControl")];

if($_GET['action']=="edit_default_7"&&$id!='')         {
    $sql    = "select * from form_formflow where id = '$id'";
    $rs     = $db->Execute($sql);
    $FlowName   = $rs->fields['FlowName'];
    $Setting    = $rs->fields['Setting'];
    $SettingMap = unserialize(base64_decode($Setting));
    $FlowName   = $rs->fields['FlowName'];
    if(is_array($SettingMap))   {
        $defaultValues_7_keys = array_keys($defaultValues_7);
        foreach($SettingMap as $value => $label)  {
            if(in_array($value, $defaultValues_7_keys))  {
                if($value=="Menu_Three" && strpos($label,"班主任")!==false) {
                    $SettingMap['Menu_Three_Icon'] = "account-search";
                }
                else if($value=="Menu_Three" && strpos($label,"系部")!==false) {
                    $SettingMap['Menu_Three_Icon'] = "account-settings";
                }
                else if($value=="Menu_Three" && strpos($label,"教务")!==false) {
                    $SettingMap['Menu_Three_Icon'] = "account-multiple-plus";
                }
                else if($value=="Menu_Three" && strpos($label,"学工")!==false) {
                    $SettingMap['Menu_Three_Icon'] = "account-settings-variant";
                }
                else if($value=="Menu_Three" && strpos($label,"分管校长")!==false) {
                    $SettingMap['Menu_Three_Icon'] = "account-box-outline";
                }
                else if($value=="Menu_Three" && strpos($label,"校长")!==false) {
                    $SettingMap['Menu_Three_Icon'] = "account-box";
                }
                else if($value=="Menu_Three" && strpos($label,"所有")!==false) {
                    $SettingMap['Menu_Three_Icon'] = "table";
                }
            }
        }
        foreach($SettingMap as $value => $label)  {
            if(in_array($value, $defaultValues_7_keys) && $value!="Init_Action_Page_ConfigSettingUrl")  {
                $defaultValues_7[$value] = $label;
            }
        }
    }

    $EnableFields = [];
    switch($defaultValues_7['Page_Role_Name']) {
        case '院系':
            $EnableFields[] = "Faculty_Filter_Field";
            break;
    }

    //临时启用
    //$defaultValues_7['Menu_One'] = $SettingMap['Menu_One'];
    //$defaultValues_7['Menu_Two'] = $SettingMap['Menu_Two'];
    //$defaultValues_7['Menu_Three'] = $SettingMap['Menu_Three'];
    //$defaultValues_7['FaceTo'] = $SettingMap['FaceTo'];
    $edit_default['allFields']      = $edit_default_7;
    $edit_default['allFieldsMode']  = $edit_default_7_mode;
    $edit_default['defaultValues']  = $defaultValues_7;
    $edit_default['dialogContentHeight']  = "90%";
    $edit_default['componentsize']  = "small";
    $edit_default['submitaction']   = "edit_default_7_data";
    $edit_default['submittext']     = __("Submit");
    $edit_default['canceltext']     = __("Cancel");
    $edit_default['titletext']      = __("Design Form Field Type");
    $edit_default['titlememo']      = __("Manage All Form Fields in Table");
    $edit_default['tablewidth']     = 550;

    $RS['edit_default'] = $edit_default;
    $RS['EnableFields'] = $EnableFields;
    $RS['status'] = "OK";
    $RS['data'] = $defaultValues_7;
    $RS['sql']  = $sql;
    $RS['forceuse'] = true;
    $RS['msg'] = __("Get Data Success");
    print_R(json_encode($RS));
    exit;
}


?>