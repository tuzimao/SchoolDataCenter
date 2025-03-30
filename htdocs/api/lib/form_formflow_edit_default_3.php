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
$ForbiddenAccessUrlList = ['form_formflow_edit_default_3.php'];
if(in_array($CurrentUrlFileName, $ForbiddenAccessUrlList)) exit;


//#########################################################################################################################
//Bottom Button############################################################################################################
//#########################################################################################################################
$edit_default_3 = [];

$Bottom_Button_Actions = [];
//$Bottom_Button_Actions[] = ['value'=>"Edit", 'label'=>__("Edit")];
$Bottom_Button_Actions[] = ['value'=>"Delete", 'label'=>__("Delete")];
$Bottom_Button_Actions[] = ['value'=>"Batch_Approval", 'label'=>__("Batch_Approval")];
$Bottom_Button_Actions[] = ['value'=>"Batch_Cancel", 'label'=>__("Batch_Cancel")];
$Bottom_Button_Actions[] = ['value'=>"Batch_Reject", 'label'=>__("Batch_Reject")];
$Bottom_Button_Actions[] = ['value'=>"Reset_Password_Abcd1234", 'label'=>__("Reset_Password_Abcd1234")];
$Bottom_Button_Actions[] = ['value'=>"Reset_Password_ID_Last6PinYin", 'label'=>__("Reset_Password_ID_Last6PinYin")];
$Bottom_Button_Actions[] = ['value'=>"Batch_Setting_One", 'label'=>__("Batch_Setting_One")];
$Bottom_Button_Actions[] = ['value'=>"Batch_Setting_Two", 'label'=>__("Batch_Setting_Two")];
$edit_default_3['Setting_Buttons'][] = ['name' => "Bottom_Button_Actions", 'show'=>true, 'type'=>'checkbox', 'options'=>$Bottom_Button_Actions, 'label' => __("Bottom_Button_Actions"), 'value' => "Edit,Delete", 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true, 'disabled' => false, 'row'=>true, 'xs'=>12, 'sm'=>12]];

$edit_default_3['Setting_Buttons'][] = ['name' => "ApprovalNodeFields", 'show'=>true, 'type'=>"input", 'label' => __("Approval Node Fields"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>12, 'disabled' => false]];
$edit_default_3['Setting_Buttons'][] = ['name' => "ApprovalNodeCurrentField", 'show'=>true, 'type'=>"input", 'label' => __("Approval Node Current Field"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>4, 'disabled' => false]];
$edit_default_3['Setting_Buttons'][] = ['name' => "ApprovalNodeTitle", 'show'=>true, 'type'=>"input", 'label' => __("Approval Node Title"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>8, 'disabled' => false]];

$edit_default_3['Setting_Buttons'][] = ['name' => "Batch_Setting_One_Name", 'show'=>true, 'type'=>"input", 'label' => __("Batch_Setting_One_Name"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>3, 'disabled' => false]];
$edit_default_3['Setting_Buttons'][] = ['name' => "Batch_Setting_One_Change_Field", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("Batch_Setting_One_Change_Field"), 'value' => 'Disable', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>3]];
$edit_default_3['Setting_Buttons'][] = ['name' => "Batch_Setting_One_Additional_Display_Field", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("Batch_Setting_One_Additional_Display_Field"), 'value' => $MetaColumnNamesOptions[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>3]];
$edit_default_3['Setting_Buttons'][] = ['name' => "Batch_Setting_One_Change_Value", 'show'=>true, 'type'=>"input", 'label' => __("Batch_Setting_One_Change_Value"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>3, 'disabled' => false]];

$edit_default_3['Setting_Buttons'][] = ['name' => "Batch_Setting_Two_Name", 'show'=>true, 'type'=>"input", 'label' => __("Batch_Setting_Two_Name"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>3, 'disabled' => false]];
$edit_default_3['Setting_Buttons'][] = ['name' => "Batch_Setting_Two_Change_Field", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("Batch_Setting_Two_Change_Field"), 'value' => '', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>3]];
$edit_default_3['Setting_Buttons'][] = ['name' => "Batch_Setting_Two_Additional_Display_Field", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("Batch_Setting_Two_Additional_Display_Field"), 'value' => $MetaColumnNamesOptions[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>3]];
$edit_default_3['Setting_Buttons'][] = ['name' => "Batch_Setting_Two_Change_Value", 'show'=>true, 'type'=>"input", 'label' => __("Batch_Setting_Two_Change_Value"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>3, 'disabled' => false]];

$edit_default_3['Setting_Buttons'][] = ['name' => "Which_Field_Store_Password_When_Enable_Change_Password", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("Which_Field_Store_Password_When_Enable_Change_Password"), 'value' => $MetaColumnNamesOptions[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>12]];


//$edit_default_3['Batch_Approval'][] = ['name' => "Divider1", 'show'=>true, 'type'=>"divider", 'label' => __("Divider"), 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>12]];
$edit_default_3['Batch_Approval'][]  = ['name' => "Batch_Approval_Status_Field", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsOnlyShowStatus, 'label' => __("Batch_Approval_Status_Field"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>8]];
$edit_default_3['Batch_Approval'][]  = ['name' => "Batch_Approval_Status_Value", 'show'=>true, 'type'=>"input", 'label' => __("Batch_Approval_Status_Value"), 'value' => __("Approval"), 'placeholder' => "", 'helptext' => "", 'rules' => ['false' => true,'xs'=>12, 'sm'=>4, 'disabled' => false]];

$edit_default_3['Batch_Approval'][]  = ['name' => "Batch_Approval_DateTime_Field", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsOnlyShowDateTime, 'label' => __("Batch_Approval_DateTime_Field"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>8]];
$Batch_Approval_DateTime_Format = [];
$Batch_Approval_DateTime_Format[] = ['value'=>"DateTime", 'label'=>__("DateTime")];
$Batch_Approval_DateTime_Format[] = ['value'=>"Date", 'label'=>__("Date")];
$edit_default_3['Batch_Approval'][]  = ['name' => "Batch_Approval_DateTime_Format", 'show'=>true, 'type'=>'radiogroup', 'options'=>$Batch_Approval_DateTime_Format, 'label' => __("Batch_Approval_DateTime_Format"), 'value' => "DateTime", 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>4]];

$edit_default_3['Batch_Approval'][]  = ['name' => "Batch_Approval_User_Field", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsOnlyshowPerson, 'label' => __("Batch_Approval_User_Field"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>8]];
$Batch_Approval_User_Format = [];
$Batch_Approval_User_Format[] = ['value'=>"UserID", 'label'=>__("UserID")];
$Batch_Approval_User_Format[] = ['value'=>"UserName", 'label'=>__("UserName")];
$edit_default_3['Batch_Approval'][]  = ['name' => "Batch_Approval_User_Format", 'show'=>true, 'type'=>'radiogroup', 'options'=>$Batch_Approval_User_Format, 'label' => __("Batch_Approval_User_Format"), 'value' => "DateTime", 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>4, 'row'=>true]];

$edit_default_3['Batch_Approval'][]  = ['name' => "Batch_Approval_Review_Field", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsOnlyShowOpinion, 'label' => __("Batch_Approval_Review_Field"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>8]];
$edit_default_3['Batch_Approval'][]  = ['name' => "Batch_Approval_Review_Opinion", 'show'=>true, 'type'=>"input", 'label' => __("Batch_Approval_Review_Opinion"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['false' => true,'xs'=>12, 'sm'=>4, 'disabled' => false]];

$edit_default_3['Batch_Approval'][]  = ['name' => "Change_Field_When_Batch_Approval_1", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsOnlyShowStatus, 'label' => __("Change_Field_When_Batch_Approval_1"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>4]];
$edit_default_3['Batch_Approval'][]  = ['name' => "Change_Into_Value_When_Batch_Approval_1", 'show'=>true, 'type'=>"input", 'label' => __("Change_Into_Value"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['false' => true,'xs'=>12, 'sm'=>2, 'disabled' => false]];

$edit_default_3['Batch_Approval'][]  = ['name' => "Change_Field_When_Batch_Approval_2", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsOnlyShowStatus, 'label' => __("Change_Field_When_Batch_Approval_2"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>4]];
$edit_default_3['Batch_Approval'][]  = ['name' => "Change_Into_Value_When_Batch_Approval_2", 'show'=>true, 'type'=>"input", 'label' => __("Change_Into_Value"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['false' => true,'xs'=>12, 'sm'=>2, 'disabled' => false]];

$edit_default_3['Batch_Approval'][]  = ['name' => "Change_Field_When_Batch_Approval_3", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsOnlyShowStatus, 'label' => __("Change_Field_When_Batch_Approval_3"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>4]];
$edit_default_3['Batch_Approval'][]  = ['name' => "Change_Into_Value_When_Batch_Approval_3", 'show'=>true, 'type'=>"input", 'label' => __("Change_Into_Value"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['false' => true,'xs'=>12, 'sm'=>2, 'disabled' => false]];

$edit_default_3['Batch_Approval'][]  = ['name' => "Change_Field_When_Batch_Approval_4", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("Change_Field_When_Batch_Approval_4"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>4]];
$edit_default_3['Batch_Approval'][]  = ['name' => "Change_Into_Value_When_Batch_Approval_4", 'show'=>true, 'type'=>"input", 'label' => __("Change_Into_Value"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['false' => true,'xs'=>12, 'sm'=>2, 'disabled' => false]];

$edit_default_3['Batch_Approval'][]  = ['name' => "Change_Field_When_Batch_Approval_5", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("Change_Field_When_Batch_Approval_5"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>4]];
$edit_default_3['Batch_Approval'][]  = ['name' => "Change_Into_Value_When_Batch_Approval_5", 'show'=>true, 'type'=>"input", 'label' => __("Change_Into_Value"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['false' => true,'xs'=>12, 'sm'=>2, 'disabled' => false]];

$edit_default_3['Batch_Approval'][]  = ['name' => "Change_Field_When_Batch_Approval_6", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("Change_Field_When_Batch_Approval_6"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>4]];
$edit_default_3['Batch_Approval'][]  = ['name' => "Change_Into_Value_When_Batch_Approval_6", 'show'=>true, 'type'=>"input", 'label' => __("Change_Into_Value"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['false' => true,'xs'=>12, 'sm'=>2, 'disabled' => false]];

$edit_default_3['Batch_Approval'][]  = ['name' => "Change_Field_When_Batch_Approval_7", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("Change_Field_When_Batch_Approval_7"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>4]];
$edit_default_3['Batch_Approval'][]  = ['name' => "Change_Into_Value_When_Batch_Approval_7", 'show'=>true, 'type'=>"input", 'label' => __("Change_Into_Value"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['false' => true,'xs'=>12, 'sm'=>2, 'disabled' => false]];

$edit_default_3['Batch_Approval'][]  = ['name' => "Change_Field_When_Batch_Approval_8", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("Change_Field_When_Batch_Approval_8"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>4]];
$edit_default_3['Batch_Approval'][]  = ['name' => "Change_Into_Value_When_Batch_Approval_8", 'show'=>true, 'type'=>"input", 'label' => __("Change_Into_Value"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['false' => true,'xs'=>12, 'sm'=>2, 'disabled' => false]];

//$edit_default_3['Batch_Approval'][]  = ['name' => "Divider1", 'show'=>true, 'type'=>"divider", 'label' => __("Divider"), 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>12]];

##################################################################################################################################
$edit_default_3['Batch_Refuse'][]  = ['name' => "Batch_Refuse_Status_Field", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsOnlyShowStatus, 'label' => __("Batch_Refuse_Status_Field"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>8]];
$edit_default_3['Batch_Refuse'][]  = ['name' => "Batch_Refuse_Status_Value", 'show'=>true, 'type'=>"input", 'label' => __("Batch_Refuse_Status_Value"), 'value' => __("Refuse"), 'placeholder' => "", 'helptext' => "", 'rules' => ['false' => true,'xs'=>12, 'sm'=>4, 'disabled' => false]];

$edit_default_3['Batch_Refuse'][]  = ['name' => "Batch_Refuse_DateTime_Field", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsOnlyShowDateTime, 'label' => __("Batch_Refuse_DateTime_Field"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>8]];
$Batch_Refuse_DateTime_Format = [];
$Batch_Refuse_DateTime_Format[] = ['value'=>"DateTime", 'label'=>__("DateTime")];
$Batch_Refuse_DateTime_Format[] = ['value'=>"Date", 'label'=>__("Date")];
$edit_default_3['Batch_Refuse'][]  = ['name' => "Batch_Refuse_DateTime_Format", 'show'=>true, 'type'=>'radiogroup', 'options'=>$Batch_Refuse_DateTime_Format, 'label' => __("Batch_Refuse_DateTime_Format"), 'value' => "DateTime", 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>4, 'row'=>true]];

$edit_default_3['Batch_Refuse'][]  = ['name' => "Batch_Refuse_User_Field", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsOnlyshowPerson, 'label' => __("Batch_Refuse_User_Field"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>8]];
$Batch_Refuse_User_Format = [];
$Batch_Refuse_User_Format[] = ['value'=>"UserID", 'label'=>__("UserID")];
$Batch_Refuse_User_Format[] = ['value'=>"UserName", 'label'=>__("UserName")];
$edit_default_3['Batch_Refuse'][]  = ['name' => "Batch_Refuse_User_Format", 'show'=>true, 'type'=>'radiogroup', 'options'=>$Batch_Refuse_User_Format, 'label' => __("Batch_Refuse_User_Format"), 'value' => "DateTime", 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>4, 'row'=>true]];

$edit_default_3['Batch_Refuse'][]  = ['name' => "Batch_Refuse_Review_Field", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsOnlyShowOpinion, 'label' => __("Batch_Refuse_Review_Field"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>8]];
$edit_default_3['Batch_Refuse'][]  = ['name' => "Batch_Refuse_Review_Opinion", 'show'=>true, 'type'=>"input", 'label' => __("Batch_Refuse_Review_Opinion"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['false' => true,'xs'=>12, 'sm'=>4, 'disabled' => false]];

$edit_default_3['Batch_Refuse'][]  = ['name' => "Change_Field_When_Batch_Refuse_1", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsOnlyShowStatus, 'label' => __("Change_Field_When_Batch_Refuse_1"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>4]];
$edit_default_3['Batch_Refuse'][]  = ['name' => "Change_Into_Value_When_Batch_Refuse_1", 'show'=>true, 'type'=>"input", 'label' => __("Change_Into_Value"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['false' => true,'xs'=>12, 'sm'=>2, 'disabled' => false]];

$edit_default_3['Batch_Refuse'][]  = ['name' => "Change_Field_When_Batch_Refuse_2", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsOnlyShowStatus, 'label' => __("Change_Field_When_Batch_Refuse_2"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>4]];
$edit_default_3['Batch_Refuse'][]  = ['name' => "Change_Into_Value_When_Batch_Refuse_2", 'show'=>true, 'type'=>"input", 'label' => __("Change_Into_Value"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['false' => true,'xs'=>12, 'sm'=>2, 'disabled' => false]];

$edit_default_3['Batch_Refuse'][]  = ['name' => "Change_Field_When_Batch_Refuse_3", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsOnlyShowStatus, 'label' => __("Change_Field_When_Batch_Refuse_3"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>4]];
$edit_default_3['Batch_Refuse'][]  = ['name' => "Change_Into_Value_When_Batch_Refuse_3", 'show'=>true, 'type'=>"input", 'label' => __("Change_Into_Value"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['false' => true,'xs'=>12, 'sm'=>2, 'disabled' => false]];

$edit_default_3['Batch_Refuse'][]  = ['name' => "Change_Field_When_Batch_Refuse_4", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("Change_Field_When_Batch_Refuse_4"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>4]];
$edit_default_3['Batch_Refuse'][]  = ['name' => "Change_Into_Value_When_Batch_Refuse_4", 'show'=>true, 'type'=>"input", 'label' => __("Change_Into_Value"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['false' => true,'xs'=>12, 'sm'=>2, 'disabled' => false]];

$edit_default_3['Batch_Refuse'][]  = ['name' => "Change_Field_When_Batch_Refuse_5", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("Change_Field_When_Batch_Refuse_5"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>4]];
$edit_default_3['Batch_Refuse'][]  = ['name' => "Change_Into_Value_When_Batch_Refuse_5", 'show'=>true, 'type'=>"input", 'label' => __("Change_Into_Value"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['false' => true,'xs'=>12, 'sm'=>2, 'disabled' => false]];

$edit_default_3['Batch_Refuse'][]  = ['name' => "Change_Field_When_Batch_Refuse_6", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("Change_Field_When_Batch_Refuse_6"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>4]];
$edit_default_3['Batch_Refuse'][]  = ['name' => "Change_Into_Value_When_Batch_Refuse_6", 'show'=>true, 'type'=>"input", 'label' => __("Change_Into_Value"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['false' => true,'xs'=>12, 'sm'=>2, 'disabled' => false]];

//$edit_default_3['Batch_Refuse'][]  = ['name' => "Divider2", 'show'=>true, 'type'=>"divider", 'label' => __("Divider"), 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>12]];

##################################################################################################################################
$edit_default_3['Batch_Cancel'][]  = ['name' => "Batch_Cancel_Status_Field", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsOnlyShowStatus, 'label' => __("Batch_Cancel_Status_Field"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>8]];
$edit_default_3['Batch_Cancel'][]  = ['name' => "Batch_Cancel_Status_Value", 'show'=>true, 'type'=>"input", 'label' => __("Batch_Cancel_Status_Value"), 'value' => __("Redo"), 'placeholder' => "", 'helptext' => "", 'rules' => ['false' => true,'xs'=>12, 'sm'=>4, 'disabled' => false]];

$edit_default_3['Batch_Cancel'][]  = ['name' => "Batch_Cancel_DateTime_Field", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsOnlyShowDateTime, 'label' => __("Batch_Cancel_DateTime_Field"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>8]];
$Batch_Cancel_DateTime_Format = [];
$Batch_Cancel_DateTime_Format[] = ['value'=>"DateTime", 'label'=>__("DateTime")];
$Batch_Cancel_DateTime_Format[] = ['value'=>"Date", 'label'=>__("Date")];
$edit_default_3['Batch_Cancel'][]  = ['name' => "Batch_Cancel_DateTime_Format", 'show'=>true, 'type'=>'radiogroup', 'options'=>$Batch_Cancel_DateTime_Format, 'label' => __("Batch_Cancel_DateTime_Format"), 'value' => "DateTime", 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>4, 'row'=>true]];

$edit_default_3['Batch_Cancel'][]  = ['name' => "Batch_Cancel_User_Field", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsOnlyshowPerson, 'label' => __("Batch_Cancel_User_Field"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>8]];
$Batch_Cancel_User_Format = [];
$Batch_Cancel_User_Format[] = ['value'=>"UserID", 'label'=>__("UserID")];
$Batch_Cancel_User_Format[] = ['value'=>"UserName", 'label'=>__("UserName")];
$edit_default_3['Batch_Cancel'][]  = ['name' => "Batch_Cancel_User_Format", 'show'=>true, 'type'=>'radiogroup', 'options'=>$Batch_Cancel_User_Format, 'label' => __("Batch_Cancel_User_Format"), 'value' => "DateTime", 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>4, 'row'=>true]];

$edit_default_3['Batch_Cancel'][]  = ['name' => "Batch_Cancel_Review_Field", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsOnlyShowOpinion, 'label' => __("Batch_Cancel_Review_Field"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>8]];
$edit_default_3['Batch_Cancel'][]  = ['name' => "Batch_Cancel_Review_Opinion", 'show'=>true, 'type'=>"input", 'label' => __("Batch_Cancel_Review_Opinion"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['false' => true,'xs'=>12, 'sm'=>4, 'disabled' => false]];

$edit_default_3['Batch_Cancel'][]  = ['name' => "Change_Field_When_Batch_Cancel_1", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsOnlyShowStatus, 'label' => __("Change_Field_When_Batch_Cancel_1"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>4]];
$edit_default_3['Batch_Cancel'][]  = ['name' => "Change_Into_Value_When_Batch_Cancel_1", 'show'=>true, 'type'=>"input", 'label' => __("Change_Into_Value"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['false' => true,'xs'=>12, 'sm'=>2, 'disabled' => false]];

$edit_default_3['Batch_Cancel'][]  = ['name' => "Change_Field_When_Batch_Cancel_2", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsOnlyShowStatus, 'label' => __("Change_Field_When_Batch_Cancel_2"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>4]];
$edit_default_3['Batch_Cancel'][]  = ['name' => "Change_Into_Value_When_Batch_Cancel_2", 'show'=>true, 'type'=>"input", 'label' => __("Change_Into_Value"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['false' => true,'xs'=>12, 'sm'=>2, 'disabled' => false]];

$edit_default_3['Batch_Cancel'][]  = ['name' => "Change_Field_When_Batch_Cancel_3", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsOnlyShowStatus, 'label' => __("Change_Field_When_Batch_Cancel_3"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>4]];
$edit_default_3['Batch_Cancel'][]  = ['name' => "Change_Into_Value_When_Batch_Cancel_3", 'show'=>true, 'type'=>"input", 'label' => __("Change_Into_Value"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['false' => true,'xs'=>12, 'sm'=>2, 'disabled' => false]];

$edit_default_3['Batch_Cancel'][]  = ['name' => "Change_Field_When_Batch_Cancel_4", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("Change_Field_When_Batch_Cancel_4"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>4]];
$edit_default_3['Batch_Cancel'][]  = ['name' => "Change_Into_Value_When_Batch_Cancel_4", 'show'=>true, 'type'=>"input", 'label' => __("Change_Into_Value"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['false' => true,'xs'=>12, 'sm'=>2, 'disabled' => false]];

$edit_default_3['Batch_Cancel'][]  = ['name' => "Change_Field_When_Batch_Cancel_5", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("Change_Field_When_Batch_Cancel_5"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>4]];
$edit_default_3['Batch_Cancel'][]  = ['name' => "Change_Into_Value_When_Batch_Cancel_5", 'show'=>true, 'type'=>"input", 'label' => __("Change_Into_Value"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['false' => true,'xs'=>12, 'sm'=>2, 'disabled' => false]];

$edit_default_3['Batch_Cancel'][]  = ['name' => "Change_Field_When_Batch_Cancel_6", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("Change_Field_When_Batch_Cancel_6"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>4]];
$edit_default_3['Batch_Cancel'][]  = ['name' => "Change_Into_Value_When_Batch_Cancel_6", 'show'=>true, 'type'=>"input", 'label' => __("Change_Into_Value"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['false' => true,'xs'=>12, 'sm'=>2, 'disabled' => false]];

$edit_default_3['Batch_Cancel'][]  = ['name' => "Change_Field_When_Batch_Cancel_7", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("Change_Field_When_Batch_Cancel_7"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>4]];
$edit_default_3['Batch_Cancel'][]  = ['name' => "Change_Into_Value_When_Batch_Cancel_7", 'show'=>true, 'type'=>"input", 'label' => __("Change_Into_Value"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['false' => true,'xs'=>12, 'sm'=>2, 'disabled' => false]];


$edit_default_3['Batch_Cancel'][]  = ['name' => "Change_Field_When_Batch_Cancel_8", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("Change_Field_When_Batch_Cancel_8"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>4]];
$edit_default_3['Batch_Cancel'][]  = ['name' => "Change_Into_Value_When_Batch_Cancel_8", 'show'=>true, 'type'=>"input", 'label' => __("Change_Into_Value"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['false' => true,'xs'=>12, 'sm'=>2, 'disabled' => false]];

$defaultValues_3 = [];
foreach($edit_default_3 as $ModeName=>$allFieldItem) {
    foreach($allFieldItem as $ITEM) {
        if($ITEM['type'] == "autocompletemulti")  {
            $defaultValues_3[$ITEM['code']] = $ITEM['value'];
        }
        else {
            $defaultValues_3[$ITEM['name']] = $ITEM['value'];
        }
    }
}

$edit_default_3_mode[] = ['value'=>"Setting_Buttons", 'label'=>__("Setting_Buttons")];
$edit_default_3_mode[] = ['value'=>"Batch_Approval", 'label'=>__("Batch_Approval")];
$edit_default_3_mode[] = ['value'=>"Batch_Cancel", 'label'=>__("Batch_Cancel")];
$edit_default_3_mode[] = ['value'=>"Batch_Refuse", 'label'=>__("Batch_Refuse")];


if($_GET['action']=="edit_default_3"&&$id!='')         {
    $sql    = "select * from form_formflow where id = '$id'";
    $rs     = $db->Execute($sql);
    $FlowName   = $rs->fields['FlowName'];
    $Setting    = $rs->fields['Setting'];
    $SettingMap = unserialize(base64_decode($Setting));
    $FlowName   = $rs->fields['FlowName'];
    if(is_array($SettingMap))   {
        $defaultValues_3_keys = array_keys($defaultValues_3);
        foreach($SettingMap as $value => $label)  {
            if(in_array($value, $defaultValues_3_keys))  {
                $defaultValues_3[$value] = $label;
            }
        }
    }
    $edit_default['allFields']      = $edit_default_3;
    $edit_default['allFieldsMode']  = $edit_default_3_mode;
    $edit_default['defaultValues']  = $defaultValues_3;
    $edit_default['dialogContentHeight']  = "90%";
    $edit_default['componentsize']  = "small";
    $edit_default['submitaction']   = "edit_default_3_data";
    $edit_default['submittext']     = __("Submit");
    $edit_default['canceltext']     = __("Cancel");
    $edit_default['titletext']      = __("Design Form Field Type");
    $edit_default['titlememo']      = __("Manage All Form Fields in Table");
    $edit_default['tablewidth']     = 550;

    $RS['edit_default'] = $edit_default;
    $RS['status'] = "OK";
    $RS['data'] = $defaultValues_3;
    $RS['sql'] = $sql;
    $RS['forceuse'] = true;
    $RS['msg'] = __("Get Data Success");
    print_R(json_encode($RS));
    exit;
}

?>