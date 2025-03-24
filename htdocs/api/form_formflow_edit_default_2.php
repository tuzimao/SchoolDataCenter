<?php
/*
* 基础架构: 单点低代码开发平台
* 版权所有: 郑州单点科技软件有限公司
* Email: moodle360@qq.com
* Copyright (c) 2007-2025
* License: 商业授权
*/


//#########################################################################################################################
//Interface################################################################################################################
//#########################################################################################################################
$edit_default_2 = [];

$edit_default_2['Tip_In_Interface'][] = ['name' => "List_Title_Name", 'show'=>true, 'type'=>"input", 'label' => __("List_Title_Name"), 'value' => $ShortNameTarget.__("List"), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true,'xs'=>12, 'sm'=>4, 'disabled' => false]];
$edit_default_2['Tip_In_Interface'][] = ['name' => "Import_Title_Name", 'show'=>true, 'type'=>"input", 'label' => __("Import_Title_Name"), 'value' => __("Import")."".$ShortNameTarget, 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true,'xs'=>12, 'sm'=>4, 'disabled' => false]];
$edit_default_2['Tip_In_Interface'][] = ['name' => "Export_Title_Name", 'show'=>true, 'type'=>"input", 'label' => __("Export_Title_Name"), 'value' => __("Export")."".$ShortNameTarget, 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true,'xs'=>12, 'sm'=>4, 'disabled' => false]];
$edit_default_2['Tip_In_Interface'][] = ['name' => "Add_Title_Name", 'show'=>true, 'type'=>"input", 'label' => __("Add_Title_Name"), 'value' => __("Add")."".$ShortNameTarget, 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true,'xs'=>12, 'sm'=>4, 'disabled' => false]];
$edit_default_2['Tip_In_Interface'][] = ['name' => "Add_Subtitle_Name", 'show'=>true, 'type'=>"input", 'label' => __("Add_Subtitle_Name"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>8, 'disabled' => false]];
$edit_default_2['Tip_In_Interface'][] = ['name' => "Edit_Title_Name", 'show'=>true, 'type'=>"input", 'label' => __("Edit_Title_Name"), 'value' => __("Edit")."".$ShortNameTarget, 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true,'xs'=>12, 'sm'=>4, 'disabled' => false]];
$edit_default_2['Tip_In_Interface'][] = ['name' => "Edit_Subtitle_Name", 'show'=>true, 'type'=>"input", 'label' => __("Edit_Subtitle_Name"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>8, 'disabled' => false]];
$edit_default_2['Tip_In_Interface'][] = ['name' => "View_Title_Name", 'show'=>true, 'type'=>"input", 'label' => __("View_Title_Name"), 'value' => __("View")."".$ShortNameTarget, 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true,'xs'=>12, 'sm'=>4, 'disabled' => false]];
$edit_default_2['Tip_In_Interface'][] = ['name' => "View_Subtitle_Name", 'show'=>true, 'type'=>"input", 'label' => __("View_Subtitle_Name"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>8, 'disabled' => false]];

$edit_default_2['Tip_In_Interface'][] = ['name' => "Rename_Add_Submit_Button", 'show'=>true, 'type'=>"input", 'label' => __("Rename_Add_Submit_Button"), 'value' => __("Submit"), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true,'xs'=>12, 'sm'=>3, 'disabled' => false]];
$edit_default_2['Tip_In_Interface'][] = ['name' => "Rename_Edit_Submit_Button", 'show'=>true, 'type'=>"input", 'label' => __("Rename_Edit_Submit_Button"), 'value' => __("Submit"), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true,'xs'=>12, 'sm'=>3, 'disabled' => false]];
$edit_default_2['Tip_In_Interface'][] = ['name' => "Rename_List_Add_Button", 'show'=>true, 'type'=>"input", 'label' => __("Rename_List_Add_Button"), 'value' => __("Add"), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true,'xs'=>12, 'sm'=>2, 'disabled' => false]];
$edit_default_2['Tip_In_Interface'][] = ['name' => "Rename_List_Edit_Button", 'show'=>true, 'type'=>"input", 'label' => __("Rename_List_Edit_Button"), 'value' => __("Edit"), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true,'xs'=>12, 'sm'=>2, 'disabled' => false]];
$edit_default_2['Tip_In_Interface'][] = ['name' => "Rename_List_Delete_Button", 'show'=>true, 'type'=>"input", 'label' => __("Rename_List_Delete_Button"), 'value' => __("Delete"), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true,'xs'=>12, 'sm'=>2, 'disabled' => false]];
$edit_default_2['Tip_In_Interface'][] = ['name' => "Rename_List_Import_Button", 'show'=>true, 'type'=>"input", 'label' => __("Rename_List_Import_Button"), 'value' => __("Import"), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true,'xs'=>12, 'sm'=>2, 'disabled' => false]];
$edit_default_2['Tip_In_Interface'][] = ['name' => "Rename_List_Export_Button", 'show'=>true, 'type'=>"input", 'label' => __("Rename_List_Export_Button"), 'value' => __("Export"), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true,'xs'=>12, 'sm'=>2, 'disabled' => false]];
$edit_default_2['Tip_In_Interface'][] = ['name' => "Rename_Import_Submit_Button", 'show'=>true, 'type'=>"input", 'label' => __("Rename_Import_Submit_Button"), 'value' => __("Import"), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true,'xs'=>12, 'sm'=>2, 'disabled' => false]];

$edit_default_2['Tip_In_Interface'][] = ['name' => "Tip_When_Add_Success", 'show'=>true, 'type'=>"input", 'label' => __("Tip_When_Add_Success"), 'value' => __("Add Data Success!"), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true,'xs'=>12, 'sm'=>6, 'disabled' => false]];
$edit_default_2['Tip_In_Interface'][] = ['name' => "Tip_When_Edit_Success", 'show'=>true, 'type'=>"input", 'label' => __("Tip_When_Edit_Success"), 'value' => __("Edit Data Success!"), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true,'xs'=>12, 'sm'=>6, 'disabled' => false]];
$edit_default_2['Tip_In_Interface'][] = ['name' => "Tip_When_Delete_Success", 'show'=>true, 'type'=>"input", 'label' => __("Tip_When_Delete_Success"), 'value' => __("Delete Data Success!"), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true,'xs'=>12, 'sm'=>6, 'disabled' => false]];
$edit_default_2['Tip_In_Interface'][] = ['name' => "Tip_Title_When_Delete", 'show'=>true, 'type'=>"input", 'label' => __("Tip_Title_When_Delete"), 'value' => __("Delete Item"), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true,'xs'=>12, 'sm'=>6, 'disabled' => false]];
$edit_default_2['Tip_In_Interface'][] = ['name' => "Tip_Content_When_Delete", 'show'=>true, 'type'=>"input", 'label' => __("Tip_Content_When_Delete"), 'value' => __("Do you really want to delete this item? This operation will delete table and data in Database."), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true,'xs'=>12, 'sm'=>6, 'disabled' => false]];
$edit_default_2['Tip_In_Interface'][] = ['name' => "Tip_Button_When_Delete", 'show'=>true, 'type'=>"input", 'label' => __("Tip_Button_When_Delete"), 'value' => __("Confirm Delete"), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true,'xs'=>12, 'sm'=>6, 'disabled' => false]];

$Rules_When_Import = [];
$Rules_When_Import[] = ['value'=>"Import_And_Export", 'label'=>__("Import_And_Export")];
$Rules_When_Import[] = ['value'=>"Only_Import", 'label'=>__("Only_Import")];
$Rules_When_Import[] = ['value'=>"Only_Export", 'label'=>__("Only_Export")];
$edit_default_2['Tip_In_Interface'][] = ['name' => "Rules_When_Import", 'show'=>true, 'type'=>'select', 'options'=>$Rules_When_Import, 'label' => __("Rules_When_Import"), 'value' => $Rules_When_Import[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>4]];

$Page_Number_In_List = [];
$Page_Number_In_List[] = ['value'=>10, 'label'=>10];
$Page_Number_In_List[] = ['value'=>15, 'label'=>15];
$Page_Number_In_List[] = ['value'=>20, 'label'=>20];
$Page_Number_In_List[] = ['value'=>30, 'label'=>30];
$Page_Number_In_List[] = ['value'=>40, 'label'=>40];
$Page_Number_In_List[] = ['value'=>50, 'label'=>50];
$Page_Number_In_List[] = ['value'=>100, 'label'=>100];
$edit_default_2['Tip_In_Interface'][] = ['name' => "Page_Number_In_List", 'show'=>true, 'type'=>'select', 'options'=>$Page_Number_In_List, 'label' => __("Page_Number_In_List"), 'value' => $Page_Number_In_List[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>4]];

$Actions_In_List_Header = [];
$Actions_In_List_Header[] = ['value'=>"Add", 'label'=>__("Add")];
$Actions_In_List_Header[] = ['value'=>"Export", 'label'=>__("Export")];
$Actions_In_List_Header[] = ['value'=>"Import", 'label'=>__("Import")];
$edit_default_2['Tip_In_Interface'][] = ['name' => "Actions_In_List_Header", 'show'=>true, 'type'=>'checkbox', 'options'=>$Actions_In_List_Header, 'label' => __("Actions_In_List_Header"), 'value' => "Add,Import,Export", 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true, 'disabled' => false, 'row'=>true, 'xs'=>12, 'sm'=>4]];

$Actions_In_List_Row = [];
$Actions_In_List_Row[] = ['value'=>"Edit", 'label'=>__("Edit")];
$Actions_In_List_Row[] = ['value'=>"Delete", 'label'=>__("Delete")];
$Actions_In_List_Row[] = ['value'=>"View", 'label'=>__("View")];
$edit_default_2['Tip_In_Interface'][] = ['name' => "Actions_In_List_Row", 'show'=>true, 'type'=>'checkbox', 'options'=>$Actions_In_List_Row, 'label' => __("Actions_In_List_Row"), 'value' => "Edit,Delete,View", 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true, 'disabled' => false, 'row'=>true, 'xs'=>12, 'sm'=>4]];

$edit_default_2['LimitEditAndDelete'][] = ['name' => "LimitEditAndDelete_Edit_Field_One", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("LimitEditAndDelete_Edit_Field_One"), 'value' => $MetaColumnNamesOptionsAll[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>3]];
$edit_default_2['LimitEditAndDelete'][] = ['name' => "LimitEditAndDelete_Edit_Value_One", 'show'=>true, 'type'=>'input', 'label' => __("LimitEditAndDelete_Edit_Value_One"), 'value' => '', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>3]];
$edit_default_2['LimitEditAndDelete'][] = ['name' => "LimitEditAndDelete_Edit_Field_Two", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("LimitEditAndDelete_Edit_Field_Two"), 'value' => $MetaColumnNamesOptionsAll[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>3]];
$edit_default_2['LimitEditAndDelete'][] = ['name' => "LimitEditAndDelete_Edit_Value_Two", 'show'=>true, 'type'=>'input', 'label' => __("LimitEditAndDelete_Edit_Value_Two"), 'value' => '', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>3]];
$edit_default_2['LimitEditAndDelete'][] = ['name' => "LimitEditAndDelete_Delete_Field_One", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("LimitEditAndDelete_Delete_Field_One"), 'value' => $MetaColumnNamesOptionsAll[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>3]];
$edit_default_2['LimitEditAndDelete'][] = ['name' => "LimitEditAndDelete_Delete_Value_One", 'show'=>true, 'type'=>'input', 'label' => __("LimitEditAndDelete_Delete_Value_One"), 'value' => '', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>3]];
$edit_default_2['LimitEditAndDelete'][] = ['name' => "LimitEditAndDelete_Delete_Field_Two", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("LimitEditAndDelete_Delete_Field_Two"), 'value' => $MetaColumnNamesOptionsAll[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>3]];
$edit_default_2['LimitEditAndDelete'][] = ['name' => "LimitEditAndDelete_Delete_Value_Two", 'show'=>true, 'type'=>'input', 'label' => __("LimitEditAndDelete_Delete_Value_Two"), 'value' => '', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>3]];

$edit_default_2['OperationAfterSubmit'][] = ['name' => "OperationAfterSubmit_Which_Field_Name", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("OperationAfterSubmit_Which_Field_Name"), 'value' => $MetaColumnNamesOptionsAll[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>6]];
$edit_default_2['OperationAfterSubmit'][] = ['name' => "OperationAfterSubmit_Which_Field_Value", 'show'=>true, 'type'=>"input", 'label' => __("OperationAfterSubmit_Which_Field_Value"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>6, 'disabled' => false]];

$edit_default_2['OperationAfterSubmit'][] = ['name' => "OperationAfterSubmit_Need_Update_Table_Name", 'show'=>true, 'type'=>"input", 'label' => __("OperationAfterSubmit_Need_Update_Table_Name"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>4, 'disabled' => false]];
$edit_default_2['OperationAfterSubmit'][] = ['name' => "OperationAfterSubmit_Need_Update_Table_Field_Name", 'show'=>true, 'type'=>"input", 'label' => __("OperationAfterSubmit_Need_Update_Table_Field_Name"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>4, 'disabled' => false]];
$edit_default_2['OperationAfterSubmit'][] = ['name' => "OperationAfterSubmit_Need_Update_Table_Field_Value", 'show'=>true, 'type'=>"input", 'label' => __("OperationAfterSubmit_Need_Update_Table_Field_Value"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>4, 'disabled' => false]];

$edit_default_2['OperationAfterSubmit'][] = ['name' => "OperationAfterSubmit_SameField_This_Table", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("OperationAfterSubmit_SameField_This_Table"), 'value' => $MetaColumnNamesOptionsAll[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>4]];
$edit_default_2['OperationAfterSubmit'][] = ['name' => "OperationAfterSubmit_SameField_Other_Table", 'show'=>true, 'type'=>"input", 'label' => __("OperationAfterSubmit_SameField_Other_Table"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>4, 'disabled' => false]];
$OperationAfterSubmit_Update_Mode = [];
$OperationAfterSubmit_Update_Mode[] = ['value'=>"Update One Record", 'label'=>__("Update One Record")];
$OperationAfterSubmit_Update_Mode[] = ['value'=>"Update All Records", 'label'=>__("Update All Records")];
$edit_default_2['OperationAfterSubmit'][] = ['name' => "OperationAfterSubmit_Update_Mode", 'show'=>true, 'type'=>'select', 'options'=>$OperationAfterSubmit_Update_Mode, 'label' => __("OperationAfterSubmit_Update_Mode"), 'value' => $OperationAfterSubmit_Update_Mode[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>4]];

$EnablePluginsForIndividual = [];
$EnablePluginsForIndividual[] = ['value'=>"Disable", 'label'=>__("Disable")];
$EnablePluginsForIndividual[] = ['value'=>"Enable", 'label'=>__("Enable")];
$edit_default_2['OperationAfterSubmit'][] = ['name' => "EnablePluginsForIndividual", 'show'=>true, 'type'=>'select', 'options'=>$EnablePluginsForIndividual, 'label' => __("EnablePluginsForIndividual"), 'value' => $EnablePluginsForIndividual[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>6]];

$OperationLogGrade = [];
$OperationLogGrade[] = ['value'=>"None", 'label'=>__("None")];
$OperationLogGrade[] = ['value'=>"DeleteOperation", 'label'=>__("DeleteOperation")];
$OperationLogGrade[] = ['value'=>"EditAndDeleteOperation", 'label'=>__("EditAndDeleteOperation")];
$OperationLogGrade[] = ['value'=>"AddEditAndDeleteOperation", 'label'=>__("AddEditAndDeleteOperation")];
$OperationLogGrade[] = ['value'=>"AllOperation", 'label'=>__("AllOperation")];
$edit_default_2['OperationAfterSubmit'][] = ['name' => "OperationLogGrade", 'show'=>true, 'type'=>'select', 'options'=>$OperationLogGrade, 'label' => __("OperationLogGrade"), 'value' => $OperationLogGrade[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>6]];

$edit_default_2['OperationAfterSubmit'][] = ['name' => "AddPageSplitMultiRecords", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("Add_Page_Split_Multi_Records"), 'value' => $MetaColumnNamesOptionsAll[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>6]];


$Default_Order_Method_By_Desc = [];
$Default_Order_Method_By_Desc[] = ['value'=>"Desc", 'label'=>__("Desc")];
$Default_Order_Method_By_Desc[] = ['value'=>"Asc", 'label'=>__("Asc")];

$MetaColumnNamesOptionsAllForPinned = $MetaColumnNamesOptions;
array_unshift($MetaColumnNamesOptionsAllForPinned,['value'=>"actions", 'label'=>__("actions")]);
array_unshift($MetaColumnNamesOptionsAllForPinned,['value'=>"Disabled", 'label'=>__("Disabled")]);

$edit_default_2['Columns_Pinned'][] = ['name' => "Columns_Pinned_Left_Field_One", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAllForPinned, 'label' => __("Columns_Pinned_Left_Field_One"), 'value' => $MetaColumnNamesOptionsAllForPinned[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>6]];
$edit_default_2['Columns_Pinned'][] = ['name' => "Columns_Pinned_Left_Field_Two", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAllForPinned, 'label' => __("Columns_Pinned_Left_Field_Two"), 'value' => $MetaColumnNamesOptionsAllForPinned[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>6]];
$edit_default_2['Columns_Pinned'][] = ['name' => "Columns_Pinned_Left_Field_Three", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAllForPinned, 'label' => __("Columns_Pinned_Left_Field_Three"), 'value' => $MetaColumnNamesOptionsAllForPinned[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>6]];
$edit_default_2['Columns_Pinned'][] = ['name' => "Columns_Pinned_Left_Field_Four", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAllForPinned, 'label' => __("Columns_Pinned_Left_Field_Four"), 'value' => $MetaColumnNamesOptionsAllForPinned[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>6]];
$edit_default_2['Columns_Pinned'][] = ['name' => "Columns_Pinned_Right_Field_One", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAllForPinned, 'label' => __("Columns_Pinned_Right_Field_One"), 'value' => $MetaColumnNamesOptionsAllForPinned[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>6]];
$edit_default_2['Columns_Pinned'][] = ['name' => "Columns_Pinned_Right_Field_Two", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAllForPinned, 'label' => __("Columns_Pinned_Right_Field_Two"), 'value' => $MetaColumnNamesOptionsAllForPinned[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>6]];
$edit_default_2['Columns_Pinned'][] = ['name' => "Columns_Pinned_Right_Field_Three", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAllForPinned, 'label' => __("Columns_Pinned_Right_Field_Three"), 'value' => $MetaColumnNamesOptionsAllForPinned[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>6]];
$edit_default_2['Columns_Pinned'][] = ['name' => "Columns_Pinned_Right_Field_Four", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAllForPinned, 'label' => __("Columns_Pinned_Right_Field_Four"), 'value' => $MetaColumnNamesOptionsAllForPinned[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>6]];

$edit_default_2['Page_Sort'][] = ['name' => "Default_Order_Method_By_Field_One", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("Default_Order_Method_By_Field_One"), 'value' => $MetaColumnNamesOptionsAll[1]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>4]];
$edit_default_2['Page_Sort'][] = ['name' => "Default_Order_Method_By_Desc_One", 'show'=>true, 'type'=>'select', 'options'=>$Default_Order_Method_By_Desc, 'label' => __("Desc_Or_Asc_One"), 'value' => 'Desc', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>2]];

$edit_default_2['Page_Sort'][] = ['name' => "Default_Order_Method_By_Field_Two", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("Default_Order_Method_By_Field_Two"), 'value' => $MetaColumnNamesOptionsAll[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>4]];
$edit_default_2['Page_Sort'][] = ['name' => "Default_Order_Method_By_Desc_Two", 'show'=>true, 'type'=>'select', 'options'=>$Default_Order_Method_By_Desc, 'label' => __("Desc_Or_Asc_Two"), 'value' => 'Desc', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>2]];

$edit_default_2['Page_Sort'][] = ['name' => "Default_Order_Method_By_Field_Three", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("Default_Order_Method_By_Field_Three"), 'value' => $MetaColumnNamesOptionsAll[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>4]];
$edit_default_2['Page_Sort'][] = ['name' => "Default_Order_Method_By_Desc_Three", 'show'=>true, 'type'=>'select', 'options'=>$Default_Order_Method_By_Desc, 'label' => __("Desc_Or_Asc_Three"), 'value' => 'Desc', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>2]];

$edit_default_2['Page_Sort'][] = ['name' => "Debug_Sql_Show_On_Api", 'show'=>true, 'type'=>'select', 'options'=>$YesOrNotOptions, 'label' => __("Debug_Sql_Show_On_Api"), 'value' => 'No', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>3]];

$Except_CSRF_Actions = [];
$Except_CSRF_Actions[] = ['value'=>"None", 'label'=>__("None")];
$Except_CSRF_Actions[] = ['value'=>"add_default", 'label'=>__("add_default")];
$Except_CSRF_Actions[] = ['value'=>"edit_default", 'label'=>__("edit_default")];
$Except_CSRF_Actions[] = ['value'=>"view_default", 'label'=>__("view_default")];
$Except_CSRF_Actions[] = ['value'=>"edit_view", 'label'=>__("edit_view")];
$Except_CSRF_Actions[] = ['value'=>"add_edit_view", 'label'=>__("add_edit_view")];
$Except_CSRF_Actions[] = ['value'=>"add_edit_view_delete", 'label'=>__("add_edit_view_delete")];
$Except_CSRF_Actions[] = ['value'=>"edit_default_configsetting", 'label'=>__("edit_default_configsetting")];
$edit_default_2['Page_Sort'][] = ['name' => "Except_CSRF_Actions", 'show'=>true, 'type'=>'select', 'options'=>$Except_CSRF_Actions, 'label' => __("Except_CSRF_Actions"), 'value' => 'None', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>3]];

$Init_Action_Value = [];
$Init_Action_Value[] = ['value'=>"init_default", 'label'=>__("init_default")];
$Init_Action_Value[] = ['value'=>"add_default", 'label'=>__("add_default")];
$Init_Action_Value[] = ['value'=>"edit_default", 'label'=>__("edit_default")];
$Init_Action_Value[] = ['value'=>"view_default", 'label'=>__("view_default")];
$Init_Action_Value[] = ['value'=>"edit_default_configsetting", 'label'=>__("edit_default_configsetting")];
$Init_Action_Value[] = ['value'=>"AiChatList", 'label'=>__("AiChatList")];
$Init_Action_Value[] = ['value'=>"SoulChatList", 'label'=>__("SoulChatList")];
$Init_Action_Value[] = ['value'=>"AiQuestionList", 'label'=>__("AiQuestionList")];

$edit_default_2['Init_Action'][] = ['name' => "Init_Action_Value", 'show'=>true, 'type'=>'select', 'options'=>$Init_Action_Value, 'label' => __("Init_Action_Value"), 'value' => 'init_default', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>4]];
$edit_default_2['Init_Action'][] = ['name' => "Init_Action_Field", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("Init_Action_Field"), 'value' => $MetaColumnNamesOptionsAll[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>4]];
$edit_default_2['Init_Action'][] = ['name' => "Init_Action_FilterValue", 'show'=>true, 'type'=>"input", 'label' => __("Init_Action_FilterValue"), 'value' => __(""), 'placeholder' => "", 'helptext' => __("Advanced operation, please do not operate if you do not understand"), 'rules' => ['required' => false,'xs'=>12, 'sm'=>4, 'disabled' => false]];
$edit_default_2['Init_Action'][] = ['name' => "Init_Action_Memo", 'show'=>true, 'type'=>"input", 'label' => __("Init_Action_Memo"), 'value' => __(""), 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>4, 'disabled' => false]];

$Init_Action_AddEditWidth = [];
$Init_Action_AddEditWidth[] = ['value'=>"xs", 'label'=>__("Extra Small")];
$Init_Action_AddEditWidth[] = ['value'=>"sm", 'label'=>__("Small")];
$Init_Action_AddEditWidth[] = ['value'=>"md", 'label'=>__("Medium")];
$Init_Action_AddEditWidth[] = ['value'=>"lg", 'label'=>__("Large")];
$Init_Action_AddEditWidth[] = ['value'=>"xl", 'label'=>__("Extra Large")];
$edit_default_2['Init_Action'][] = ['name' => "Init_Action_AddEditWidth", 'show'=>true, 'type'=>'select', 'options'=>$Init_Action_AddEditWidth, 'label' => __("Init_Action_AddEditWidth"), 'value' => 'md', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>4]];

$edit_default_2['Init_Action'][] = ['name' => "Init_Action_Page_ConfigSettingUrl", 'show'=>true, 'type'=>'buttonrouter', 'label' => __("ConfigSetting"), 'value' => '/form/configsetting/?FlowId='.$id, 'placeholder' => "", 'helptext' => "", 'target'=>'_blank', 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>4]];

//Unique_Fields
$edit_default_2['Unique_Fields'][] = ['name' => "Unique_Fields_1", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("Unique_Fields_1"), 'value' => $MetaColumnNamesOptionsAll[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>4]];
$edit_default_2['Unique_Fields'][] = ['name' => "Unique_Fields_2", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("Unique_Fields_2"), 'value' => $MetaColumnNamesOptionsAll[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>4]];
$edit_default_2['Unique_Fields'][] = ['name' => "Unique_Fields_3", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("Unique_Fields_3"), 'value' => $MetaColumnNamesOptionsAll[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>4]];
$edit_default_2['Unique_Fields'][] = ['name' => "Unique_Fields_Repeat_Text", 'show'=>true, 'type'=>"input", 'label' => __("Unique_Fields_Repeat_Text"), 'value' => __(""), 'placeholder' => "", 'helptext' => __("If exist, show text in the user end"), 'rules' => ['required' => false,'xs'=>12, 'sm'=>12, 'disabled' => false]];

//Import_Fields_Unique
$edit_default_2['Import_Fields_Unique'][] = ['name' => "Import_Fields_Unique_1", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("Import_Fields_Unique_1"), 'value' => $MetaColumnNamesOptionsAll[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>4]];
$edit_default_2['Import_Fields_Unique'][] = ['name' => "Import_Fields_Unique_2", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("Import_Fields_Unique_2"), 'value' => $MetaColumnNamesOptionsAll[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>4]];
$edit_default_2['Import_Fields_Unique'][] = ['name' => "Import_Fields_Unique_3", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("Import_Fields_Unique_3"), 'value' => $MetaColumnNamesOptionsAll[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>4]];

//Relative_Child_Table
$sql    = "select id,FlowName from form_formflow where FlowName!='' and FaceTo='AuthUser'";
$rsT    = $db->Execute($sql);
$rsA    = $rsT->GetArray();
$Relative_Child_Table = [];
$Relative_Child_Table[] = ['value'=>0, 'label'=>__("None")];
foreach($rsA as $Line) {
    $Relative_Child_Table[] = ['value'=>$Line['id'], 'label'=>$Line['FlowName']];
}
$edit_default_2['Relative_Child_Table'][] = ['name' => "Relative_Child_Table", 'show'=>true, 'type'=>'select', 'options'=>$Relative_Child_Table, 'label' => __("Relative_Child_Table"), 'value' => $Relative_Child_Table[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>4]];
$edit_default_2['Relative_Child_Table'][] = ['name' => "Relative_Child_Table_Field_Name", 'show'=>true, 'type'=>'input', 'label' => __("Relative_Child_Table_Field_Name"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>4]];
$edit_default_2['Relative_Child_Table'][] = ['name' => "Relative_Child_Table_Parent_Field_Name", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("Relative_Child_Table_Parent_Field_Name"), 'value' => $MetaColumnNamesOptionsAll[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>4]];

$Relative_Child_Table_Approval_Fields = [];
$Relative_Child_Table_Approval_Fields[] = ['value'=>"Yes", 'label'=>__("Yes")];
$Relative_Child_Table_Approval_Fields[] = ['value'=>"No", 'label'=>__("No")];

$edit_default_2['Relative_Child_Table'][] = ['name' => "Relative_Child_Table_Add_Priv", 'show'=>true, 'type'=>'select', 'options'=>$Relative_Child_Table_Approval_Fields, 'label' => __("Relative_Child_Table_Add_Priv"), 'value' => $Relative_Child_Table_Approval_Fields[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>4]];
$edit_default_2['Relative_Child_Table'][] = ['name' => "Relative_Child_Table_Edit_Priv", 'show'=>true, 'type'=>'select', 'options'=>$Relative_Child_Table_Approval_Fields, 'label' => __("Relative_Child_Table_Edit_Priv"), 'value' => $Relative_Child_Table_Approval_Fields[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>4]];
$edit_default_2['Relative_Child_Table'][] = ['name' => "Relative_Child_Table_Delete_Priv", 'show'=>true, 'type'=>'select', 'options'=>$Relative_Child_Table_Approval_Fields, 'label' => __("Relative_Child_Table_Delete_Priv"), 'value' => $Relative_Child_Table_Approval_Fields[0]['value'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>4]];

$defaultValues_2 = [];
foreach($edit_default_2 as $ModeName=>$allFieldItem) {
    foreach($allFieldItem as $ITEM) {
        if($ITEM['type'] == "autocompletemulti")  {
            $defaultValues_2[$ITEM['code']] = $ITEM['value'];
        }
        else {
            $defaultValues_2[$ITEM['name']] = $ITEM['value'];
        }
    }
}

$edit_default_2_mode[] = ['value'=>"Tip_In_Interface", 'label'=>__("Tip_In_Interface")];
$edit_default_2_mode[] = ['value'=>"LimitEditAndDelete", 'label'=>__("LimitEditAndDelete")];
$edit_default_2_mode[] = ['value'=>"OperationAfterSubmit", 'label'=>__("OperationAfterSubmit")];
$edit_default_2_mode[] = ['value'=>"Columns_Pinned", 'label'=>__("Columns_Pinned")];
$edit_default_2_mode[] = ['value'=>"Page_Sort", 'label'=>__("Page_Sort")];
$edit_default_2_mode[] = ['value'=>"Init_Action", 'label'=>__("Init_Action")];
$edit_default_2_mode[] = ['value'=>"Unique_Fields", 'label'=>__("Unique_Fields")];
$edit_default_2_mode[] = ['value'=>"Import_Fields_Unique", 'label'=>__("Import_Fields_Unique")];
$edit_default_2_mode[] = ['value'=>"Relative_Child_Table", 'label'=>__("Relative_Child_Table")];

if($_GET['action']=="edit_default_2"&&$id!='')         {
    $sql    = "select * from form_formflow where id = '$id'";
    $rs     = $db->Execute($sql);
    $FlowName   = $rs->fields['FlowName'];
    $Setting    = $rs->fields['Setting'];
    $SettingMap = unserialize(base64_decode($Setting));
    $FlowName   = $rs->fields['FlowName'];
    if(is_array($SettingMap))   {
        $defaultValues_2_keys = array_keys($defaultValues_2);
        foreach($SettingMap as $value => $label)  {
            if(in_array($value, $defaultValues_2_keys))  {
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
            if(in_array($value, $defaultValues_2_keys) && $value!="Init_Action_Page_ConfigSettingUrl")  {
                $defaultValues_2[$value] = $label;
            }
        }
    }

    $EnableFields = [];
    switch($defaultValues_2['Page_Role_Name']) {
        case '院系':
            $EnableFields[] = "Faculty_Filter_Field";
            break;
    }

    //临时启用
    //$defaultValues_2['Menu_One'] = $SettingMap['Menu_One'];
    //$defaultValues_2['Menu_Two'] = $SettingMap['Menu_Two'];
    //$defaultValues_2['Menu_Three'] = $SettingMap['Menu_Three'];
    //$defaultValues_2['FaceTo'] = $SettingMap['FaceTo'];
    $edit_default['allFields']      = $edit_default_2;
    $edit_default['allFieldsMode']  = $edit_default_2_mode;
    $edit_default['defaultValues']  = $defaultValues_2;
    $edit_default['dialogContentHeight']  = "90%";
    $edit_default['componentsize']  = "small";
    $edit_default['submitaction']   = "edit_default_2_data";
    $edit_default['submittext']     = __("Submit");
    $edit_default['canceltext']     = __("Cancel");
    $edit_default['titletext']      = __("Design Form Field Type");
    $edit_default['titlememo']      = __("Manage All Form Fields in Table");
    $edit_default['tablewidth']     = 550;

    $RS['edit_default'] = $edit_default;
    $RS['EnableFields'] = $EnableFields;
    $RS['status'] = "OK";
    $RS['data'] = $defaultValues_2;
    $RS['sql'] = $sql;
    $RS['forceuse'] = true;
    $RS['msg'] = __("Get Data Success");
    print_R(json_encode($RS));
    exit;
}

?>