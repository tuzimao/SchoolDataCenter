<?php
/*
* 基础架构: 单点低代码开发平台
* 版权所有: 郑州单点科技软件有限公司
* Email: moodle360@qq.com
* Copyright (c) 2007-2025
* License: 商业授权
*/

//#########################################################################################################################
//MobileEnd################################################################################################################
//#########################################################################################################################
$edit_default_5 = [];
$edit_default_5_mode[] = ['value'=>"MenuAndIcon", 'label'=>__("MenuAndIcon")];
$edit_default_5_mode[] = ['value'=>"ListTemplate1", 'label'=>__("ListTemplate1")];
$edit_default_5_mode[] = ['value'=>"ListTemplate2", 'label'=>__("ListTemplate2")];
//$edit_default_5_mode[] = ['value'=>"NewsTemplate1", 'label'=>__("NewsTemplate1")];
//$edit_default_5_mode[] = ['value'=>"ZiXun", 'label'=>__("ZiXun")];
//$edit_default_5_mode[] = ['value'=>"Activity", 'label'=>__("Activity")];
//$edit_default_5_mode[] = ['value'=>"Schoolmate", 'label'=>__("Schoolmate")];


$MobileEnd = [];
$MobileEnd[] = ['value'=>'Yes', 'label'=>__('Yes')];
$MobileEnd[] = ['value'=>'No', 'label'=>__('No')];
$edit_default_5['MenuAndIcon'][] = ['name' => "MobileEnd", 'show'=>true, 'type'=>'select', 'options'=>$MobileEnd, 'label' => __("MobileEnd"), 'value' => "No", 'placeholder' => "", 'helptext' => __(""), 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>6]];

$MobileEndShowType = [];
$MobileEndShowType[] = ['value'=>"ListTemplate1", 'label'=>__("ListTemplate1")];
$MobileEndShowType[] = ['value'=>"ListTemplate2", 'label'=>__("ListTemplate2")];
//$MobileEndShowType[] = ['value'=>"NewsTemplate1", 'label'=>__("NewsTemplate1")];
//$MobileEndShowType[] = ['value'=>"ZiXun", 'label'=>__("ZiXun")];
//$MobileEndShowType[] = ['value'=>"Activity", 'label'=>__("Activity")];
//$MobileEndShowType[] = ['value'=>"Schoolmate", 'label'=>__("Schoolmate")];
//$MobileEndShowType[] = ['value'=>"NotificationTemplate1", 'label'=>__("NotificationTemplate1")];
//$MobileEndShowType[] = ['value'=>"NotificationTemplate2", 'label'=>__("NotificationTemplate2")];
$edit_default_5['MenuAndIcon'][] = ['name' => "MobileEndShowType", 'show'=>true, 'type'=>'select', 'options'=>$MobileEndShowType, 'label' => __("MobileEndShowType"), 'value' => 'List', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>6]];

$edit_default_5['MenuAndIcon'][] = ['name' => "MobileEndShowSearch", 'show'=>true, 'type'=>'select', 'options'=>$YesOrNotOptions, 'label' => __("MobileEndShowSearch"), 'value' => 'Yes', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>6]];
$edit_default_5['MenuAndIcon'][] = ['name' => "MobileEndShowGroupFilter", 'show'=>true, 'type'=>'select', 'options'=>$YesOrNotOptions, 'label' => __("MobileEndShowGroupFilter"), 'value' => 'Yes', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>6]];
//$edit_default_5['MenuAndIcon'][] = ['name' => "MobileEndShowBanners", 'show'=>true, 'FieldTypeArray'=>$CurrentUserFieldTypeArray, 'type'=>'files', 'label' => __("MobileEndShowBanners"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>12, 'disabled' => false], 'RemoveAll'=>__('RemoveAll') ];

$edit_default_5['MenuAndIcon'][] = ['name' => "MobileEndIconName", 'show'=>true, 'type'=>"input", 'label' => __("IconName"), 'value' => $SettingMap['Menu_Three'], 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>6, 'disabled' => false]];
// Loading all images as the wechat app icons
$ImagesList = [];
$readdir = "./images/wechatIcon";
if (is_dir($readdir)) {
    if ($dh = opendir($readdir)) {
        while (($file = readdir($dh)) !== false) {
            if($file!='.' && $file!='..')  {
                $ImagesList[] = ['value'=>substr($file,0,-4), 'label'=>"/images/wechatIcon/".$file];
            }
        }
        closedir($dh);
    }
}
$edit_default_5['MenuAndIcon'][] = ['name' => "MobileEndIconImage", 'show'=>true, 'type'=>"autocompleteicons", 'options'=>$ImagesList, 'label' => __("Icon"), 'value' => '', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>6, 'disabled' => false]];

$edit_default_5['MenuAndIcon'][] = ['name' => "MobileEndTitleName", 'show'=>true, 'type'=>"input", 'label' => __("TitleName"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>6, 'disabled' => false]];

$MobileEndIconType = [];
$MobileEndIconType[] = ["value"=>"Disable", "label"=>"禁用"];
$MobileEndIconType[] = ["value"=>"UserAvator", "label"=>"用户头像"];
$MobileEndIconType[] = ["value"=>"ImageField", "label"=>"图片字段"];
$edit_default_5['ListTemplate1'][] = ['name' => "MobileEndIconType", 'show'=>true, 'type'=>'select', 'options'=>$MobileEndIconType, 'label' => __("MobileEndIconType"), 'value' => 'Disable', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>6, 'sm'=>4]];
$edit_default_5['ListTemplate1'][] = ['name' => "MobileEndIconField", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("MobileEndIconField"), 'value' => 'Disable', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>6, 'sm'=>4]];
$edit_default_5['ListTemplate1'][] = ['name' => "MobileEndFirstLine", 'show'=>true, 'type'=>"input", 'label' => __("FirstLine"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>12, 'disabled' => false]];
$edit_default_5['ListTemplate1'][] = ['name' => "MobileEndSecondLineLeft", 'show'=>true, 'type'=>"input", 'label' => __("SecondLineLeft"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>4, 'disabled' => false]];
$edit_default_5['ListTemplate1'][] = ['name' => "MobileEndSecondLineLeftColorField", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("MobileEndSecondLineLeftColorField"), 'value' => 'Disable', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>4]];
$edit_default_5['ListTemplate1'][] = ['name' => "MobileEndSecondLineLeftColorRule", 'show'=>true, 'type'=>"input", 'label' => __("MobileEndSecondLineLeftColorRule"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>4, 'disabled' => false]];
$edit_default_5['ListTemplate1'][] = ['name' => "MobileEndSecondLineRight", 'show'=>true, 'type'=>"input", 'label' => __("SecondLineRight"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>4, 'disabled' => false]];
$edit_default_5['ListTemplate1'][] = ['name' => "MobileEndSecondLineRightColorField", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("MobileEndSecondLineRightColorField"), 'value' => 'Disable', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>4]];
$edit_default_5['ListTemplate1'][] = ['name' => "MobileEndSecondLineRightColorRule", 'show'=>true, 'type'=>"input", 'label' => __("MobileEndSecondLineRightColorRule"), 'value' => "", 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false,'xs'=>12, 'sm'=>4, 'disabled' => false]];


$edit_default_5['ListTemplate2'][] = ['name' => "MobileEndField1", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("MobileEndField1"), 'value' => 'Disable', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>4]];
$edit_default_5['ListTemplate2'][] = ['name' => "MobileEndField2", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("MobileEndField2"), 'value' => 'Disable', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>4]];
$edit_default_5['ListTemplate2'][] = ['name' => "MobileEndField3", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("MobileEndField3"), 'value' => 'Disable', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>4]];
$edit_default_5['ListTemplate2'][] = ['name' => "MobileEndField4", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("MobileEndField4"), 'value' => 'Disable', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>4]];
$edit_default_5['ListTemplate2'][] = ['name' => "MobileEndField5", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("MobileEndField5"), 'value' => 'Disable', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>4]];
$edit_default_5['ListTemplate2'][] = ['name' => "MobileEndField6", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("MobileEndField6"), 'value' => 'Disable', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>4]];
$edit_default_5['ListTemplate2'][] = ['name' => "MobileEndField7", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("MobileEndField7"), 'value' => 'Disable', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>4]];
$edit_default_5['ListTemplate2'][] = ['name' => "MobileEndField8", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("MobileEndField8"), 'value' => 'Disable', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>4]];
$edit_default_5['ListTemplate2'][] = ['name' => "MobileEndField9", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("MobileEndField9"), 'value' => 'Disable', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>4]];

/*
$edit_default_5['NewsTemplate1'][] = ['name' => "MobileEndNewsTitle", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("MobileEndNewsTitle"), 'value' => 'Disable', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>6]];
$edit_default_5['NewsTemplate1'][] = ['name' => "MobileEndNewsGroup", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("MobileEndNewsGroup"), 'value' => 'Disable', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>6]];
$edit_default_5['NewsTemplate1'][] = ['name' => "MobileEndNewsContent", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("MobileEndNewsContent"), 'value' => 'Disable', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>6]];
$edit_default_5['NewsTemplate1'][] = ['name' => "MobileEndNewsReadCounter", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("MobileEndNewsReadCounter"), 'value' => 'Disable', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>6]];
$edit_default_5['NewsTemplate1'][] = ['name' => "MobileEndNewsReadUsers", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("MobileEndNewsReadUsers"), 'value' => 'Disable', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>6]];
$edit_default_5['NewsTemplate1'][] = ['name' => "MobileEndNewsCreator", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("MobileEndNewsCreator"), 'value' => 'Disable', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>6]];
$edit_default_5['NewsTemplate1'][] = ['name' => "MobileEndNewsCreateTime", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("MobileEndNewsCreateTime"), 'value' => 'Disable', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>6]];
$edit_default_5['NewsTemplate1'][] = ['name' => "MobileEndNewsLeftImage", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("MobileEndNewsLeftImage"), 'value' => 'Disable', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>6]];



$edit_default_5['ZiXun'][] = ['name' => "MobileEndNewsTitle", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("MobileEndNewsTitle"), 'value' => 'Disable', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>6]];
$edit_default_5['ZiXun'][] = ['name' => "MobileEndNewsGroup", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("MobileEndNewsGroup"), 'value' => 'Disable', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>6]];
$edit_default_5['ZiXun'][] = ['name' => "MobileEndNewsContent", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("MobileEndNewsContent"), 'value' => 'Disable', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>6]];
$edit_default_5['ZiXun'][] = ['name' => "MobileEndNewsReadCounter", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("MobileEndNewsReadCounter"), 'value' => 'Disable', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>6]];
$edit_default_5['ZiXun'][] = ['name' => "MobileEndNewsLikeCounter", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("MobileEndNewsLikeCounter"), 'value' => 'Disable', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>6]];
$edit_default_5['ZiXun'][] = ['name' => "MobileEndNewsFavoriteCounter", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("MobileEndNewsFavoriteCounter"), 'value' => 'Disable', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>6]];
$edit_default_5['ZiXun'][] = ['name' => "MobileEndNewsCreator", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("MobileEndNewsCreator"), 'value' => 'Disable', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>6]];
$edit_default_5['ZiXun'][] = ['name' => "MobileEndNewsCreateTime", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("MobileEndNewsCreateTime"), 'value' => 'Disable', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>6]];
$edit_default_5['ZiXun'][] = ['name' => "MobileEndNewsLeftImage", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("MobileEndNewsLeftImage"), 'value' => 'Disable', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>6]];



$edit_default_5['Activity'][] = ['name' => "MobileEndNewsTitle", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("MobileEndNewsTitle"), 'value' => 'Disable', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>6]];
$edit_default_5['Activity'][] = ['name' => "MobileEndNewsGroup", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("MobileEndNewsGroup"), 'value' => 'Disable', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>6]];
$edit_default_5['Activity'][] = ['name' => "MobileEndNewsContent", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("MobileEndNewsContent"), 'value' => 'Disable', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>6]];
$edit_default_5['Activity'][] = ['name' => "MobileEndNewsEnrollment", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("MobileEndNewsEnrollment"), 'value' => 'Disable', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>6]];
$edit_default_5['Activity'][] = ['name' => "MobileEndNewsLocation", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("MobileEndNewsLocation"), 'value' => 'Disable', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>6]];
$edit_default_5['Activity'][] = ['name' => "MobileEndNewsLocation2", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("MobileEndNewsLocation2"), 'value' => 'Disable', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>6]];
$edit_default_5['Activity'][] = ['name' => "MobileEndNewsProcess", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("MobileEndNewsProcess"), 'value' => 'Disable', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>6]];
$edit_default_5['Activity'][] = ['name' => "MobileEndNewsTopAvator", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("MobileEndNewsTopAvator"), 'value' => 'Disable', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>6]];

$edit_default_5['Activity'][] = ['name' => "MobileEndActivityEnrollEndDate", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("MobileEndActivityEnrollEndDate"), 'value' => 'Disable', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>6]];
$edit_default_5['Activity'][] = ['name' => "MobileEndActivityDate", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("MobileEndActivityDate"), 'value' => 'Disable', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>6]];
$edit_default_5['Activity'][] = ['name' => "MobileEndActivityFee", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("MobileEndActivityFee"), 'value' => 'Disable', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>6]];
$edit_default_5['Activity'][] = ['name' => "MobileEndActivityContact", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("MobileEndActivityContact"), 'value' => 'Disable', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>6]];

$edit_default_5['Activity'][] = ['name' => "MobileEndNewsReadCounter", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("MobileEndNewsReadCounter"), 'value' => 'Disable', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>6]];
$edit_default_5['Activity'][] = ['name' => "MobileEndNewsLikeCounter", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("MobileEndNewsLikeCounter"), 'value' => 'Disable', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>6]];
$edit_default_5['Activity'][] = ['name' => "MobileEndNewsFavoriteCounter", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("MobileEndNewsFavoriteCounter"), 'value' => 'Disable', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>6]];
$edit_default_5['Activity'][] = ['name' => "MobileEndNewsLeftImage", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("MobileEndNewsLeftImage"), 'value' => 'Disable', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>6]];
$edit_default_5['Activity'][] = ['name' => "MobileEndNewsCreator", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("MobileEndNewsCreator"), 'value' => 'Disable', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>6]];
$edit_default_5['Activity'][] = ['name' => "MobileEndNewsCreatorGroup", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("MobileEndNewsCreatorGroup"), 'value' => 'Disable', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>6]];
$edit_default_5['Activity'][] = ['name' => "MobileEndNewsCreateTime", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("MobileEndNewsCreateTime"), 'value' => 'Disable', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>6]];
$edit_default_5['Activity'][] = ['name' => "MobileEndNewsEnableEnroll", 'show'=>true, 'type'=>'select', 'options'=>$YesOrNotOptions, 'label' => __("MobileEndNewsEnableEnroll"), 'value' => 'Yes', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>6]];


$edit_default_5['Schoolmate'][] = ['name' => "MobileEndNewsTopAvator", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("MobileEndNewsTopAvator"), 'value' => 'Disable', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>6]];
$edit_default_5['Schoolmate'][] = ['name' => "MobileEndNewsCreator", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("MobileEndNewsCreator"), 'value' => 'Disable', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>6]];
$edit_default_5['Schoolmate'][] = ['name' => "MobileEndNewsCreatorGroup", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("MobileEndNewsCreatorGroup"), 'value' => 'Disable', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>6]];
$edit_default_5['Schoolmate'][] = ['name' => "MobileEndSchoolmateCity", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("MobileEndSchoolmateCity"), 'value' => 'Disable', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>6]];
$edit_default_5['Schoolmate'][] = ['name' => "MobileEndSchoolmateCompany", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("MobileEndSchoolmateCompany"), 'value' => 'Disable', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>6]];
$edit_default_5['Schoolmate'][] = ['name' => "MobileEndSchoolmateIndustry", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("MobileEndSchoolmateIndustry"), 'value' => 'Disable', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>6]];
$edit_default_5['Schoolmate'][] = ['name' => "MobileEndSchoolmateFirstYear", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("MobileEndSchoolmateFirstYear"), 'value' => 'Disable', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>6]];
$edit_default_5['Schoolmate'][] = ['name' => "MobileEndSchoolmateLastYear", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("MobileEndSchoolmateLastYear"), 'value' => 'Disable', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>6]];
$edit_default_5['Schoolmate'][] = ['name' => "MobileEndSchoolmateAcademic", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("MobileEndSchoolmateAcademic"), 'value' => 'Disable', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>6]];
$edit_default_5['Schoolmate'][] = ['name' => "MobileEndSchoolmateLastActivity", 'show'=>true, 'type'=>'select', 'options'=>$MetaColumnNamesOptionsAll, 'label' => __("MobileEndSchoolmateLastActivity"), 'value' => 'Disable', 'placeholder' => "", 'helptext' => "", 'rules' => ['required' => false, 'disabled' => false, 'xs'=>12, 'sm'=>6]];
*/


$defaultValues_5 = [];
foreach($edit_default_5 as $ModeName=>$allFieldItem) {
    foreach($allFieldItem as $ITEM) {
        if($ITEM['type'] == "autocompletemulti")  {
            $defaultValues_5[$ITEM['code']] = $ITEM['value'];
        }
        else {
            $defaultValues_5[$ITEM['name']] = $ITEM['value'];
        }
    }
}

if($_GET['action']=="edit_default_5"&&$id!='')         {
    $sql    = "select * from form_formflow where id = '$id'";
    $rs     = $db->Execute($sql);
    $FormId     = $rs->fields['FormId'];
    $Step       = $rs->fields['Step'];
    $FlowName   = $rs->fields['FlowName'];
    $Setting    = $rs->fields['Setting'];
    $SettingMap = unserialize(base64_decode($Setting));
    $FlowName   = $rs->fields['FlowName'];
    if(is_array($SettingMap))   {
        $defaultValues_5_keys = array_keys($defaultValues_5);
        foreach($SettingMap as $value => $label)  {
            if(in_array($value, $defaultValues_5_keys))  {
                $defaultValues_5[$value] = $label;
            }
        }
        if($defaultValues_5['MobileEndIconName']=="" || $defaultValues_5['MobileEndIconName']==null)     {
            $defaultValues_5['MobileEndIconName'] = $SettingMap['Menu_Three']!=""?$SettingMap['Menu_Three']:$SettingMap['Menu_Two'];
        }
        if($defaultValues_5['MobileEndTitleName']=="" || $defaultValues_5['MobileEndTitleName']==null)     {
            $defaultValues_5['MobileEndTitleName'] = $SettingMap['Menu_Three']!=""?$SettingMap['Menu_Three']:$SettingMap['Menu_Two'];
        }
    }
    if($FormId!="" && $Step>1)   {
        $sql        = "select * from form_formflow where FormId = '$FormId' and Step='".($Step-1)."'";
        $rs         = $db->Execute($sql);
        $SettingPrevious    = $rs->fields['Setting'];
        $SettingMapPrevious = unserialize(base64_decode($SettingPrevious));
        $SettingMapKeys     = array_keys($SettingMap);
        if($defaultValues_5['MobileEndIconName']==""&&isset($SettingMapPrevious['MobileEndIconName']))               $defaultValues_5['MobileEndIconName'] = $SettingMapPrevious['MobileEndIconName'];
        if($defaultValues_5['MobileEndTitleName']==""&&isset($SettingMapPrevious['MobileEndTitleName']))              $defaultValues_5['MobileEndTitleName'] = $SettingMapPrevious['MobileEndTitleName'];
        if($defaultValues_5['MobileEndFirstLine']==""&&isset($SettingMapPrevious['MobileEndFirstLine']))              $defaultValues_5['MobileEndFirstLine'] = $SettingMapPrevious['MobileEndFirstLine'];
        if($defaultValues_5['MobileEndSecondLineLeft']==""&&isset($SettingMapPrevious['MobileEndSecondLineLeft']))         $defaultValues_5['MobileEndSecondLineLeft'] = $SettingMapPrevious['MobileEndSecondLineLeft'];
        if($defaultValues_5['MobileEndSecondLineLeftColorField']==""&&isset($SettingMapPrevious['MobileEndSecondLineLeftColorField']))         $defaultValues_5['MobileEndSecondLineLeftColorField'] = $SettingMapPrevious['MobileEndSecondLineLeftColorField'];
        if($defaultValues_5['MobileEndSecondLineLeftColorRule']==""&&isset($SettingMapPrevious['MobileEndSecondLineLeftColorRule']))         $defaultValues_5['MobileEndSecondLineLeftColorRule'] = $SettingMapPrevious['MobileEndSecondLineLeftColorRule'];


        if($defaultValues_5['MobileEndSecondLineRight']==""&&isset($SettingMapPrevious['MobileEndSecondLineRight']))         $defaultValues_5['MobileEndSecondLineRight'] = $SettingMapPrevious['MobileEndSecondLineRight'];
        if($defaultValues_5['MobileEndSecondLineRightColorField']==""&&isset($SettingMapPrevious['MobileEndSecondLineRightColorField']))         $defaultValues_5['MobileEndSecondLineRightColorField'] = $SettingMapPrevious['MobileEndSecondLineRightColorField'];
        if($defaultValues_5['MobileEndSecondLineRightColorRule']==""&&isset($SettingMapPrevious['MobileEndSecondLineRightColorRule']))         $defaultValues_5['MobileEndSecondLineRightColorRule'] = $SettingMapPrevious['MobileEndSecondLineRightColorRule'];

    }
    if($defaultValues_5['MobileEndFirstLine']=="undefined")                           $defaultValues_5['MobileEndFirstLine'] = "";
    if($defaultValues_5['MobileEndSecondLineLeft']=="undefined")                      $defaultValues_5['MobileEndSecondLineLeft'] = "";
    if($defaultValues_5['MobileEndSecondLineLeftColorField']=="undefined")            $defaultValues_5['MobileEndSecondLineLeftColorField'] = "";
    if($defaultValues_5['MobileEndSecondLineLeftColorRule']=="undefined")             $defaultValues_5['MobileEndSecondLineLeftColorRule'] = "";
    if($defaultValues_5['MobileEndSecondLineRight']=="undefined")                     $defaultValues_5['MobileEndSecondLineRight'] = "";
    if($defaultValues_5['MobileEndSecondLineRightColorField']=="undefined")           $defaultValues_5['MobileEndSecondLineRightColorField'] = "";
    if($defaultValues_5['MobileEndSecondLineRightColorRule']=="undefined")            $defaultValues_5['MobileEndSecondLineRightColorRule'] = "";
    if($defaultValues_5['MobileEndSecondLineRightColorRule']=="undefined")            $defaultValues_5['MobileEndSecondLineRightColorRule'] = "";

    $edit_default['allFields']      = $edit_default_5;
    $edit_default['allFieldsMode']  = $edit_default_5_mode;
    $edit_default['defaultValues']  = $defaultValues_5;
    $edit_default['dialogContentHeight']  = "90%";
    $edit_default['componentsize']  = "small";
    $edit_default['submitaction']   = "edit_default_5_data";
    $edit_default['submittext']     = __("Submit");
    $edit_default['canceltext']     = __("Cancel");
    $edit_default['titletext']      = __("Mobile End Setting");
    $edit_default['titlememo']      = __("");
    $edit_default['tablewidth']     = 550;

    $RS['edit_default'] = $edit_default;
    $RS['status'] = "OK";
    $RS['data'] = $defaultValues_5;
    $RS['sql'] = $sql;
    $RS['forceuse'] = true;
    $RS['msg'] = __("Get Data Success");
    print_R(json_encode($RS));
    exit;
}


?>