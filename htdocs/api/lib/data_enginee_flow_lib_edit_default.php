<?php
//禁止当前文件直接在URL中访问
$CurrentUrlFileName = basename($_SERVER['PHP_SELF']);
$ForbiddenAccessUrlList = ['data_enginee_flow_lib_edit_default.php'];
if(in_array($CurrentUrlFileName, $ForbiddenAccessUrlList)) exit;

if( ( ($_GET['action']=="edit_default"&&in_array('Edit',$Actions_In_List_Row_Array))  ) && $_GET['id']!="")  {
    if($TableName=="data_user" && $SettingMap['Init_Action_Value']=="edit_default" && $SettingMap['Init_Action_FilterValue']=="email") {
        $EMAIL  = $GLOBAL_USER->email;
        $id     = returntablefield("data_user","EMAIL",$EMAIL,"id")["id"];
    }
    else if($TableName=="data_user" && $SettingMap['Init_Action_Value']=="edit_default" && $SettingMap['Init_Action_FilterValue']=="USER_ID") {
        $USER_ID  = $GLOBAL_USER->USER_ID;
        $id     = returntablefield($TableName,"USER_ID",$USER_ID,"id")["id"];
    }
    else if($TableName=="data_xiaoyou_member" && $SettingMap['Init_Action_Value']=="edit_default" && $SettingMap['Init_Action_FilterValue']=="USER_ID") {
        $USER_ID  = $GLOBAL_USER->USER_ID;
        $id     = returntablefield($TableName,"学生学号",$USER_ID,"id")["id"];
    }
    else if($TableName=="data_student" && $SettingMap['Init_Action_Value']=="edit_default" && $SettingMap['Init_Action_FilterValue']=="学号") {
        $学号    = $GLOBAL_USER->学号;
        $id     = returntablefield($TableName,"学号",$学号,"id")["id"];
    }
    else if($SettingMap['Init_Action_Value']=="edit_default" && $SettingMap['Init_Action_FilterValue']!="") {
        $id     = intval($SettingMap['Init_Action_FilterValue']);
    }
    else {
        $id     = intval(DecryptID($_GET['id']));
    }
    if($id==0)   {
        $RS = [];
        $RS['status'] = "ERROR";
        $RS['msg'] = __("Error Id Value");
        $RS['_GET'] = $_GET;
        $RS['_POST'] = $_POST;
        print json_encode($RS);
        exit;
    }

    //functionNameIndividual
    $functionNameIndividual = "plugin_".$TableName."_".$Step."_edit_default";
    if(function_exists($functionNameIndividual))  {
        $functionNameIndividual($id);
    }

    //Get Row Data
    $sql    = "select * from `$TableName` where id = '$id'";
    $rsf    = $db->Execute($sql);
    $data   = $rsf->fields;

    foreach($AllFieldsFromTable as $Item)  {
        $CurrentFieldType = $AllShowTypesArray[$AllFieldsMap[$Item['FieldName']]['ShowType']]['EDIT'];
        if(array_key_exists($Item['FieldName'], $defaultValuesEdit)) {
            switch($CurrentFieldType) {
                case 'avatar':
                    $data[$Item['FieldName']] = AttachFieldValueToUrl($TableName,$id,$Item['FieldName'],'avatar',$data[$Item['FieldName']]);
                    break;
                case 'images':
                case 'images2':
                    $data[$Item['FieldName']] = AttachFieldValueToUrl($TableName,$id,$Item['FieldName'],'images',$data[$Item['FieldName']]);
                    break;
                case 'files':
                case 'files2':
                    $data[$Item['FieldName']] = AttachFieldValueToUrl($TableName,$id,$Item['FieldName'],'files',$data[$Item['FieldName']]);
                    break;
                case 'file':
                    $data[$Item['FieldName']] = AttachFieldValueToUrl($TableName,$id,$Item['FieldName'],'file',$data[$Item['FieldName']]);
                    break;
                case 'xlsx':
                    $data[$Item['FieldName']] = AttachFieldValueToUrl($TableName,$id,$Item['FieldName'],'xlsx',$data[$Item['FieldName']]);
                    break;
                case 'password':
                    $data[$Item['FieldName']] = "******";
                    break;
                case 'ProvinceAndCity':
                    //行政区 三级
                    global $微信小程序_省市区_子选项;
                    $sql	= "select * from edu_xingzhengdaima where length(代码)='12' order by 代码";
                    $rs		= $db->Execute($sql);
                    $rs_a	= $rs->GetArray();
                    $微信小程序_省市区_子选项     = [];
                    $Element           = [];
                    $默认行政代码         = $data[$Item['FieldName']];
                    if($默认行政代码=="") {
                        $默认行政代码 = "110000000000";
                    }
                    for($R=0;$R<sizeof($rs_a);$R++)							{
                        $行政区	= $rs_a[$R]['行政区'];
                        $代码	= $rs_a[$R]['代码'];
                        if(substr($代码,2,10)=='0000000000')			{
                            $省 			= $行政区;
                            $市             = '';
                            //$微信小程序_省市区_子选项[$省] 	= $省
                            if($省=='台湾省' || $省=='香港特别行政区' || $省=='澳门特别行政区')
                                $微信小程序_省市区_子选项[$省][$省][] 	= array("id"=>(STRING)$代码,"name"=>(STRING)$省);
                            if(substr($代码,0,2)==substr($默认行政代码,0,2))		{
                                $微信小程序_用户选择名称['省'] = $行政区;
                            }
                            //处理北京市-北京市-东城区这样的情况
                            if(substr($代码,4,8)=='00000000')			{
                                $市 = str_replace($省,'',$行政区);
                                if($市=="") $市 = $行政区;
                                if(substr($代码,0,4)==substr($默认行政代码,0,4))		{
                                    $微信小程序_用户选择名称['市'] = $市;
                                }
                            }
                        }
                        elseif(substr($代码,4,8)=='00000000')			{
                            $市 = str_replace($省,'',$行政区);
                            if($市=="") $市 = $行政区;
                            if(substr($代码,0,4)==substr($默认行政代码,0,4))		{
                                $微信小程序_用户选择名称['市'] = $市;
                            }
                        }
                        else		{
                            if($市=='') $市 = $省;
                            $区名称 = str_replace($市,'',$行政区);
                            $微信小程序_省市区_子选项[$省][$市][] 	= array("id"=>(STRING)$代码,"name"=>(STRING)$区名称);
                            if(substr($代码,0,6)==substr($默认行政代码,0,6))		{
                                $微信小程序_用户选择名称['区'] = $区名称;
                            }
                        }
                    }
                    $微信小程序_用户选择索引			= array();
                    $微信小程序_左右下拉数组			= array();
                    $左侧数组 						   = array_keys($微信小程序_省市区_子选项);;
                    $微信小程序_左右下拉数组[0] 		= $左侧数组;
                    if(is_array($微信小程序_省市区_子选项[$微信小程序_用户选择名称['省']])) {
                        $微信小程序_左右下拉数组[1] 	= array_keys($微信小程序_省市区_子选项[$微信小程序_用户选择名称['省']]);
                    }
                    else {
                        $微信小程序_左右下拉数组[1]     = [];
                    }
                    if(is_array($微信小程序_省市区_子选项[$微信小程序_用户选择名称['省']][$微信小程序_用户选择名称['市']])) {
                        $微信小程序_左右下拉数组[2] 	= idname_array_get_namelist($微信小程序_省市区_子选项[$微信小程序_用户选择名称['省']][$微信小程序_用户选择名称['市']]);
                    }
                    else {
                        $微信小程序_左右下拉数组[2]     = [];
                    }
                    //把省市区中的值转化为索引.
                    $ARRY_FLIP 						  = array_flip($微信小程序_左右下拉数组[0]);
                    $微信小程序_用户选择索引['省']	    = $ARRY_FLIP[$微信小程序_用户选择名称['省']];
                    $ARRY_FLIP 						  = array_flip($微信小程序_左右下拉数组[1]);
                    $微信小程序_用户选择索引['市']	    = $ARRY_FLIP[$微信小程序_用户选择名称['市']];
                    $ARRY_FLIP 						  = array_flip($微信小程序_左右下拉数组[2]);
                    $微信小程序_用户选择索引['区']	    = $ARRY_FLIP[$微信小程序_用户选择名称['区']];
                    $微信小程序_用户选择索引	        = array_values($微信小程序_用户选择索引);

                    $data["ProvinceAndCity_".$Item['id']]['微信小程序_用户选择索引']      = $微信小程序_用户选择索引;
                    $data["ProvinceAndCity_".$Item['id']]['微信小程序_用户选择名称']      = $微信小程序_用户选择名称;
                    $data["ProvinceAndCity_".$Item['id']]['微信小程序_省选项']            = array_keys($微信小程序_省市区_子选项);
                    $data["ProvinceAndCity_".$Item['id']]['微信小程序_省市区_子选项']     = $微信小程序_省市区_子选项;
                    $data["ProvinceAndCity_".$Item['id']]['微信小程序_左右下拉数组']      = $微信小程序_左右下拉数组;
                    $data["ProvinceAndCity_".$Item['id']]['行政区代码']                  = $默认行政代码;
                    $data["ProvinceAndCity_".$Item['id']]['FieldName']                  = $Item['FieldName'];
                    break;
                case 'ProvinceAndCityOneLine':
                    //行政区 三级
                    global $微信小程序_省市区_子选项;
                    $sql	= "select * from edu_xingzhengdaima where length(代码)='12' order by 代码";
                    $rs		= $db->Execute($sql);
                    $rs_a	= $rs->GetArray();
                    $微信小程序_省市区_子选项     = [];
                    $Element           = [];
                    $默认省市区         = $data[$Item['FieldName']];
                    if($默认省市区=="") {
                        $默认省市区 = "北京市-北京市-东城区";
                    }
                    $默认省市区Array    = explode('-',$默认省市区);
                    for($R=0;$R<sizeof($rs_a);$R++)							{
                        $行政区	= $rs_a[$R]['行政区'];
                        $代码	= $rs_a[$R]['代码'];
                        if(substr($代码,2,10)=='0000000000')			{
                            $省 			= $行政区;
                            $市             = '';
                            //$微信小程序_省市区_子选项[$省] 	= $省
                            if($省=='台湾省' || $省=='香港特别行政区' || $省=='澳门特别行政区')
                                $微信小程序_省市区_子选项[$省][$省][] 	= array("id"=>(STRING)$代码,"name"=>(STRING)$省);
                            if($省==$默认省市区Array[0])		{
                                $微信小程序_用户选择名称['省'] = $行政区;
                            }
                            //处理北京市-北京市-东城区这样的情况
                            if(substr($代码,4,8)=='00000000')			{
                                $市 = str_replace($省,'',$行政区);
                                if($市=="") $市 = $行政区;
                                if($市==$默认省市区Array[1])		{
                                    $微信小程序_用户选择名称['市'] = $市;
                                }
                            }
                        }
                        elseif(substr($代码,4,8)=='00000000')			{
                            $市 = str_replace($省,'',$行政区);
                            if($市=="") $市 = $行政区;
                            if($市==$默认省市区Array[1])		{
                                $微信小程序_用户选择名称['市'] = $市;
                            }
                        }
                        else		{
                            if($市=='') $市 = $省;
                            $区名称 = str_replace($市,'',$行政区);
                            $微信小程序_省市区_子选项[$省][$市][] 	= array("id"=>(STRING)$代码,"name"=>(STRING)$区名称);
                            if($区名称==$默认省市区Array[2])		{
                                $微信小程序_用户选择名称['区'] = $区名称;
                            }
                        }
                    }
                    $微信小程序_用户选择索引			= array();
                    $微信小程序_左右下拉数组			= array();
                    $左侧数组 						   = array_keys($微信小程序_省市区_子选项);;
                    $微信小程序_左右下拉数组[0] 		= $左侧数组;
                    if(is_array($微信小程序_省市区_子选项[$微信小程序_用户选择名称['省']])) {
                        $微信小程序_左右下拉数组[1] 	= array_keys($微信小程序_省市区_子选项[$微信小程序_用户选择名称['省']]);
                    }
                    else {
                        $微信小程序_左右下拉数组[1]     = [];
                    }
                    if(is_array($微信小程序_省市区_子选项[$微信小程序_用户选择名称['省']][$微信小程序_用户选择名称['市']])) {
                        $微信小程序_左右下拉数组[2] 	= idname_array_get_namelist($微信小程序_省市区_子选项[$微信小程序_用户选择名称['省']][$微信小程序_用户选择名称['市']]);
                    }
                    else {
                        $微信小程序_左右下拉数组[2]     = [];
                    }
                    //把省市区中的值转化为索引.
                    $ARRY_FLIP 						  = array_flip($微信小程序_左右下拉数组[0]);
                    $微信小程序_用户选择索引['省']	    = $ARRY_FLIP[$微信小程序_用户选择名称['省']];
                    $ARRY_FLIP 						  = array_flip($微信小程序_左右下拉数组[1]);
                    $微信小程序_用户选择索引['市']	    = $ARRY_FLIP[$微信小程序_用户选择名称['市']];
                    $ARRY_FLIP 						  = array_flip($微信小程序_左右下拉数组[2]);
                    $微信小程序_用户选择索引['区']	    = $ARRY_FLIP[$微信小程序_用户选择名称['区']];
                    $微信小程序_用户选择索引	        = array_values($微信小程序_用户选择索引);

                    $data["ProvinceAndCity_".$Item['id']]['微信小程序_用户选择索引']      = $微信小程序_用户选择索引;
                    $data["ProvinceAndCity_".$Item['id']]['微信小程序_用户选择名称']      = $微信小程序_用户选择名称;
                    $data["ProvinceAndCity_".$Item['id']]['微信小程序_省选项']            = array_keys($微信小程序_省市区_子选项);
                    $data["ProvinceAndCity_".$Item['id']]['微信小程序_省市区_子选项']     = $微信小程序_省市区_子选项;
                    $data["ProvinceAndCity_".$Item['id']]['微信小程序_左右下拉数组']      = $微信小程序_左右下拉数组;
                    $data["ProvinceAndCity_".$Item['id']]['FieldName']                  = $Item['FieldName'];
                    break;
            }
        }
        //Decrypt Field Value
        $FieldName                      = $Item['FieldName'];
        $SettingTempMap                 = $AllFieldsMap[$FieldName]['Setting'];
        $DataFieldEncryptMethod         = $SettingTempMap['DataFieldEncryptMethod'];
        $DataFieldEncryptKey            = $SettingTempMap['DataFieldEncryptKey'];
        if($DataFieldEncryptMethod==1&&$DataFieldEncryptKey!="") {
            $data[$FieldName]           = DecryptIDStorage($data[$FieldName], $DataFieldEncryptKey);
        }
    }

    $RS = [];
    $RS['status'] = "OK";
    $RS['data'] = $data;

    //检查工作流部分的设定, 当工作流中工作被办结时, 并且去查看表单的时候, 需要展示只读, 而非可以编辑的表单
    $整个页面是否只读   = false;
    $processid = intval($_GET['processid']);
    if($processid > 0) {
        $sql        = "select 步骤状态 from form_flow_run_process where id = '$processid' ";
        $rs         = $db->Execute($sql);
        $步骤状态    = $rs->fields['步骤状态'];
        if($步骤状态 == "办结") {
            $整个页面是否只读   = true;
            //强制处理子表记录为只读
            $Relative_Child_Table_Add_Priv      = $SettingMap['Relative_Child_Table_Add_Priv']    = "No";
            $Relative_Child_Table_Edit_Priv     = $SettingMap['Relative_Child_Table_Edit_Priv']   = "No";
            $Relative_Child_Table_Delete_Priv   = $SettingMap['Relative_Child_Table_Delete_Priv'] = "No";
        }
    }

    if($SettingMap['Debug_Sql_Show_On_Api']=="Yes" && in_array($GLOBAL_USER->USER_ID, ['admin', 'admin001']))  $RS['sql'] = $sql;
    $RS['msg'] = __("Get Data Success");
    if($_GET['IsGetStructureFromEditDefault']==1)  {
        if($整个页面是否只读 == true) {
            foreach($allFieldsEdit as $ModeName=>$allFieldItem)     {
                for($iX=0;$iX<sizeof($allFieldItem);$iX++)   {   
                    $allFieldItem[$iX]['rules']['disabled']     = true;
                    //$allFieldItem[$iX]['type']                  = 'readonly';
                    $allFieldsEdit[$ModeName][$iX]              = $allFieldItem[$iX];
                }
            }
        }
        $edit_default['allFields']      = $allFieldsEdit;
        $edit_default['allFieldsMode']  = [['value'=>"Default", 'label'=>__("")]];
        $edit_default['defaultValues']  = $defaultValuesEdit;
        $edit_default['dialogContentHeight']  = "90%";
        $edit_default['submitaction']   = "edit_default_data";
        $edit_default['submittext']     = __("Submit");
        $edit_default['componentsize']  = "small";
        $edit_default['canceltext']     = "";
        $edit_default['titletext']      = "";
        $edit_default['titlememo']      = "";
        $edit_default['tablewidth']     = 650;
        if($整个页面是否只读 == true) {
            $edit_default['submittext']     = "";
        }
        if($_GET['processid'] > 0 && $_GET['runid'] > 0) {
            $edit_default['submittext']     = "";
        }
    }
    //Relative Child Table Support
    $Relative_Child_Table                   = $SettingMap['Relative_Child_Table'];
    $Relative_Child_Table_Type              = $SettingMap['Relative_Child_Table_Type'];
    $Relative_Child_Table_Field_Name        = $SettingMap['Relative_Child_Table_Field_Name'];
    $Relative_Child_Table_Parent_Field_Name = $SettingMap['Relative_Child_Table_Parent_Field_Name'];
    $Relative_Child_Table_Add_Priv          = $SettingMap['Relative_Child_Table_Add_Priv'];
    $Relative_Child_Table_Edit_Priv         = $SettingMap['Relative_Child_Table_Edit_Priv'];
    $Relative_Child_Table_Delete_Priv       = $SettingMap['Relative_Child_Table_Delete_Priv'];
    $Relative_Child_Table_Select_Priv       = $SettingMap['Relative_Child_Table_Select_Priv'];
    
    if($Relative_Child_Table>0 && $Relative_Child_Table_Parent_Field_Name!="" && in_array($Relative_Child_Table_Parent_Field_Name,$MetaColumnNames)) {
        $ChildSettingMap = returntablefield("form_formflow",'id',$Relative_Child_Table,'Setting')['Setting'];
        $ChildSettingMap = unserialize(base64_decode($ChildSettingMap));
        $ChildFormId                = returntablefield("form_formflow",'id',$Relative_Child_Table,'FormId')['FormId'];
        $ChildTableName             = returntablefield("form_formname",'id',$ChildFormId,'TableName')['TableName'];
        $ChildMetaColumnNames       = GLOBAL_MetaColumnNames($ChildTableName);
        if($Relative_Child_Table_Field_Name!="" && in_array($Relative_Child_Table_Field_Name, $ChildMetaColumnNames) ) {
            //Get All Fields
            $sql        = "select * from $ChildTableName where $Relative_Child_Table_Parent_Field_Name = '".$data[$Relative_Child_Table_Parent_Field_Name]."';";
            $rs         = $db->Execute($sql);
            $rs_a       = $rs->GetArray();
            $readonlyIdArray            = [];
            $deleteChildTableItemArray  = [];
            $RS['childtable']['sql']    = $sql;
            $RS['childtable']['data']   = $rs_a;
            $RS['childtable']['ChildItemCounter'] = sizeof($rs_a);
            for($X=0;$X<sizeof($rs_a);$X++) {
                $Line = $rs_a[$X];
                foreach($Line AS $LineKey=>$LineValue) {
                    $data['ChildTable____'.$X.'____'.$LineKey] = $LineValue;
                }
                //LimitEditAndDelete
                if($ChildSettingMap['LimitEditAndDelete_Edit_Field_One']!="" && $ChildSettingMap['LimitEditAndDelete_Edit_Field_One']!="None" && in_array($ChildSettingMap['LimitEditAndDelete_Edit_Field_One'], $ChildMetaColumnNames)) {
                    $LimitEditAndDelete_Edit_Value_One_Array = explode(',',$ChildSettingMap['LimitEditAndDelete_Edit_Value_One']);
                    if(in_array($Line[$ChildSettingMap['LimitEditAndDelete_Edit_Field_One']],$LimitEditAndDelete_Edit_Value_One_Array)) {
                        $readonlyIdArray[] = $Line['id'];
                        $deleteChildTableItemArray[] = $X;
                    }
                }
            }
            $RS['childtable']['readonlyIdArray']                = $readonlyIdArray;
            $RS['childtable']['deleteChildTableItemArray']      = $deleteChildTableItemArray;
            $RS['data']  = $data;
        }
    }
    $RS['edit_default'] = $edit_default;
    if($_GET['IsGetStructureFromEditDefault']==1)  {
        $RS['forceuse'] = true;
        
        //Relative Child Table Support
        $Relative_Child_Table                   = $SettingMap['Relative_Child_Table'];
        $Relative_Child_Table_Type              = $SettingMap['Relative_Child_Table_Type'];
        $Relative_Child_Table_Field_Name        = $SettingMap['Relative_Child_Table_Field_Name'];
        $Relative_Child_Table_Parent_Field_Name = $SettingMap['Relative_Child_Table_Parent_Field_Name'];
        $Relative_Child_Table_Add_Priv          = $SettingMap['Relative_Child_Table_Add_Priv'];
        $Relative_Child_Table_Edit_Priv         = $SettingMap['Relative_Child_Table_Edit_Priv'];
        $Relative_Child_Table_Delete_Priv       = $SettingMap['Relative_Child_Table_Delete_Priv'];
        $Relative_Child_Table_Select_Priv       = $SettingMap['Relative_Child_Table_Select_Priv'];
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
                $RS['add_default']['childtable']['Add']                = $Relative_Child_Table_Add_Priv == "Yes" && strpos($ChildSettingMap['Actions_In_List_Header'],'Add')!==false?true:false;
                $RS['add_default']['childtable']['Edit']               = $Relative_Child_Table_Edit_Priv == "Yes" && strpos($ChildSettingMap['Actions_In_List_Row'],'Edit')!==false?true:false;
                $RS['add_default']['childtable']['Delete']             = $Relative_Child_Table_Delete_Priv == "Yes" && strpos($ChildSettingMap['Actions_In_List_Row'],'Delete')!==false?true:false;
                $RS['add_default']['childtable']['Select']             = $Relative_Child_Table_Select_Priv == "Yes"?true:false;
                $RS['add_default']['childtable']['Type']               = $Relative_Child_Table_Type;

                $allFieldsEdit   = getAllFields($ChildAllFieldsFromTable, $AllShowTypesArray, 'EDIT', true, $ChildSettingMap);
                foreach($allFieldsEdit as $ModeName=>$allFieldItem) {
                    $allFieldItemIndex = 0;
                    foreach($allFieldItem as $ITEM) {
                        $defaultValuesEditChild[$ITEM['name']] = $ITEM['value'];
                        if($ITEM['code']!="") {
                            $defaultValuesEditChild[$ITEM['code']] = $ITEM['value'];
                        }
                        if(strpos($ChildSettingMap['Actions_In_List_Row'],'Edit')===false || $Relative_Child_Table_Edit_Priv == "No") {
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
                $RS['edit_default']['childtable']['Add']                = $Relative_Child_Table_Add_Priv == "Yes" && strpos($ChildSettingMap['Actions_In_List_Header'],'Add')!==false?true:false;
                $RS['edit_default']['childtable']['Edit']               = $Relative_Child_Table_Edit_Priv == "Yes" && strpos($ChildSettingMap['Actions_In_List_Row'],'Edit')!==false?true:false;
                $RS['edit_default']['childtable']['Delete']             = $Relative_Child_Table_Delete_Priv == "Yes" && strpos($ChildSettingMap['Actions_In_List_Row'],'Delete')!==false?true:false;
                $RS['edit_default']['childtable']['Select']             = $Relative_Child_Table_Select_Priv == "Yes"?true:false;
                $RS['edit_default']['childtable']['Type']               = $Relative_Child_Table_Type;
                
                global $WholePageModel;
                if($WholePageModel == "Workflow")  {
                    $RS['edit_default']['submittext']                   = '';
                }

                if($Relative_Child_Table_Type == "从子表中选择记录")   {
                    $ChileTable_init_default = ChildTable_Init_Default_Structure($ChildFormId, $ChildTableName, $ChildMetaColumnNames, $ChildSettingMap);
                    $RS['edit_default']['childtable']['init_default'] = $ChileTable_init_default;
                }
            }
        }
    }

    //Filter Data For Readonly Edit
    //编辑只读的时候,把用户名转为用户姓名
    foreach($allFieldsEdit as $ModeName=>$allFieldItemTemp) {
        $CounterTemp = 0;
        $allFieldItem = $allFieldsView[$ModeName];

        $allFieldItemTempMap = [];
        foreach($allFieldItemTemp as $ITEM) {
            $allFieldItemTempMap[$ITEM['name']] = $ITEM;
        }
        
        foreach($allFieldItem as $ITEM) {
            //print_R($ITEM['name'] . "-" .$allFieldItemTemp[$CounterTemp]['type']. "<BR>");
            if($allFieldItemTempMap[$ITEM['name']]['type']=='readonly')  {
                $FieldName              = $ITEM['name'];
                $CurrentFieldTypeArray  = $ITEM['FieldTypeArray'];
                switch($CurrentFieldTypeArray[0])   {
                    case 'autocomplete':
                        $FieldName              = $ITEM['code'];
                    case 'radiogroup':
                    case 'radiogroupcolor':
                    case 'tablefilter':
                    case 'tablefiltercolor':
                        $TableNameTemp      = $CurrentFieldTypeArray[1];
                        $KeyField           = $CurrentFieldTypeArray[2];
                        $ValueField         = $CurrentFieldTypeArray[3];
                        $DefaultValue       = $CurrentFieldTypeArray[4];
                        $WhereField         = ForSqlInjection($CurrentFieldTypeArray[5]);
                        $WhereValue         = ForSqlInjection($CurrentFieldTypeArray[6]);
                        $MetaColumnNamesTemp    = GLOBAL_MetaColumnNames($TableNameTemp);
                        if($WhereField!="" && $WhereValue!="" && $MetaColumnNamesTemp[$KeyField]!="" && $RS['data'][$FieldName]!="") {
                            $sql = "select `".$MetaColumnNamesTemp[$ValueField]."` as label from $TableNameTemp where $WhereField = '".$WhereValue."' and `".$MetaColumnNamesTemp[$KeyField]."`='".ForSqlInjection($RS['data'][$FieldName])."' ;";
                            $rs = $db->Execute($sql) or print($sql);
                            $RS['data'][$FieldName] = $rs->fields['label'];
                        }
                        elseif($MetaColumnNamesTemp[$KeyField]!="" && $RS['data'][$FieldName]!="")    {
                            $sql = "select `".$MetaColumnNamesTemp[$ValueField]."` as label from $TableNameTemp where `".$MetaColumnNamesTemp[$KeyField]."`='".ForSqlInjection($RS['data'][$FieldName])."' ;";
                            $rs = $db->Execute($sql) or print($sql);
                            $RS['data'][$FieldName] = $rs->fields['label'];
                        }
                        break;
                    case 'autocompletemulti':
                        //print_R($CurrentFieldTypeArray);
                        $TableNameTemp      = $CurrentFieldTypeArray[1];
                        $KeyField           = $CurrentFieldTypeArray[2];
                        $ValueField         = $CurrentFieldTypeArray[3];
                        $DefaultValue       = $CurrentFieldTypeArray[4];
                        $WhereField         = ForSqlInjection($CurrentFieldTypeArray[5]);
                        $WhereValue         = ForSqlInjection($CurrentFieldTypeArray[6]);
                        $MetaColumnNamesTemp    = GLOBAL_MetaColumnNames($TableNameTemp);
                        $MultiValueArray        = explode(',',$RS['data'][$FieldName]);
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
                        $RS['data'][$FieldName] = join(',',$MultiValueRS);
                        break;
                    case 'password':
                        $RS['data'][$FieldName] = "******";
                        break;
                    default:
                        break;
                }
            }
            $CounterTemp ++;
        }
    }

    print json_encode($RS);
    exit;
}
?>