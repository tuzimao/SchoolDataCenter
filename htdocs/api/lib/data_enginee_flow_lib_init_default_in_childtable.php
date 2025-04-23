<?php
//禁止当前文件直接在URL中访问
$CurrentUrlFileName = basename($_SERVER['PHP_SELF']);
$ForbiddenAccessUrlList = ['data_enginee_flow_lib_init_default.php'];
if(in_array($CurrentUrlFileName, $ForbiddenAccessUrlList)) exit;

function ChildTable_Init_Default_Structure($FormId, $TableName, $MetaColumnNames, $SettingMap) {

global $GLOBAL_LANGUAGE;
global $db;
global $AllShowTypesArray;
global $AddSql;
//重置$AddSql的值, 这个地方只作用于子表的列表显示SQL
$AddSql = " where 1=1 ";

$Actions_In_List_Row_Array = explode(',',$SettingMap['Actions_In_List_Row']);
$Actions_In_List_Header_Array = explode(',',$SettingMap['Actions_In_List_Header']);

//列表页面时的启用字段列表
$init_default_columns   = [];
$columnsactions         = [];


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
Extra_Priv_Filter_Field_To_SQL($SettingMap, $MetaColumnNames);

$functionNameIndividual = "plugin_".$TableName."_".$Step."_init_default";
if(function_exists($functionNameIndividual))  {
    $functionNameIndividual($id);
}

//Group Filter
$RS['init_default']['filter'] = [];

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

$RS['init_default']['data']                     = $NewRSA;


$RS['init_default']['multireview']          = [];
$RS['init_default']['checkboxSelection']    = true;

$RS['init_default']['rowHeight']        = $rowHeight;
$RS['init_default']['dialogContentHeight']  = "90%";
$RS['init_default']['dialogMaxWidth']   = $SettingMap['Init_Action_AddEditWidth']?$SettingMap['Init_Action_AddEditWidth']:'md';// xl lg md sm xs
$RS['init_default']['timeline']         = time();
$RS['init_default']['pageNumber']       = $pageSize;
$RS['init_default']['pageCount']        = ceil($RS['init_default']['total']/$pageSize);
$RS['init_default']['pageId']           = $page;
$RS['init_default']['pageNumberArray']  = $pageNumberArray;
if(in_array($GLOBAL_USER->USER_ID, ['admin', 'admin001']))  {
    $RS['init_default']['sql']                              = $sqlList;
    $RS['init_default']['ApprovalNodeFields']['DebugSql']   = $sqlList;
}

$RS['init_default']['pinnedColumns']  = [];

$RS['init_default']['dataGridLanguageCode']  = $GLOBAL_LANGUAGE;

$RS['init_default']['ForbiddenSelectRow']   = array_keys($ForbiddenSelectRow);
$RS['init_default']['ForbiddenViewRow']     = array_keys($ForbiddenViewRow);
$RS['init_default']['ForbiddenEditRow']     = array_keys($ForbiddenEditRow);
$RS['init_default']['ForbiddenDeleteRow']   = array_keys($ForbiddenDeleteRow);

return $RS['init_default'];

}
?>