<?php
//禁止当前文件直接在URL中访问
$CurrentUrlFileName = basename($_SERVER['PHP_SELF']);
$ForbiddenAccessUrlList = ['data_enginee_flow_lib_view_default.php'];
if(in_array($CurrentUrlFileName, $ForbiddenAccessUrlList)) exit;

if( ( ($_GET['action']=="view_default"&&in_array('View',$Actions_In_List_Row_Array))  ) && $_GET['id']!="")  {
    $id     = intval(DecryptID($_GET['id']));
    if($id==0)   {
        $RS = [];
        $RS['status'] = "ERROR";
        $RS['msg'] = __("Error Id Value");
        $RS['_GET'] = $_GET;
        $RS['_POST'] = $_POST;
        print_R(EncryptApiData($RS, $GLOBAL_USER));
        exit;
    }

    //functionNameIndividual
    $functionNameIndividual = "plugin_".$TableName."_".$Step."_view_default";
    if(function_exists($functionNameIndividual))  {
        $functionNameIndividual($id);
    }

    //Read Counter ++
    if(in_array("浏览次数", $MetaColumnNames))  {
        $sql    = "update `$TableName` set 浏览次数=浏览次数+1 where id = '$id'";
        $db->Execute($sql);
    }
    else if(in_array("阅读次数", $MetaColumnNames))  {
        $sql    = "update `$TableName` set 阅读次数=阅读次数+1 where id = '$id'";
        $db->Execute($sql);
    }
    $sql    = "select * from `$TableName` where id = '$id'";
    $rsf    = $db->Execute($sql);
    $data   = $rsf->fields;

    foreach($AllFieldsFromTable as $Item)  {
        $CurrentFieldType = $AllShowTypesArray[$AllFieldsMap[$Item['FieldName']]['ShowType']]['EDIT'];
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
    if($SettingMap['Debug_Sql_Show_On_Api']=="Yes" && in_array($GLOBAL_USER->USER_ID, ['admin', 'admin001']))  $RS['sql'] = $sql;
    $RS['msg'] = __("Get Data Success");
    $view_default = [];
    if($_GET['IsGetStructureFromEditDefault']==1)  {
        $view_default['allFields']      = $allFieldsView;
        $view_default['allFieldsMode']  = [['value'=>"Default", 'label'=>__("")]];
        $view_default['defaultValues']  = $defaultValuesEdit;
        $view_default['dialogContentHeight']  = "90%";
        $view_default['componentsize']  = "small";
        $view_default['canceltext']     = "";
        $view_default['titletext']      = "";
        $view_default['titlememo']      = "";
        $view_default['tablewidth']     = 650;
    }
    //$RS['_SERVER'] = $_SERVER;
    $RS['view_default'] = $view_default;

    //Filter Data For View
    foreach($allFieldsView as $ModeName=>$allFieldItem) {
        foreach($allFieldItem as $ITEM) {
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
    }

    //Rerest the layout in View Model
    $LayoutWidth = [];
    foreach($allFieldsView as $ModeName=>$allFieldItem) {
        if(is_array($allFieldItem)) {
            for($i=0;$i<sizeof($allFieldItem);$i+=2)        {
                $FieldName              = $allFieldItem[$i]['name'];
                $Width                  = $allFieldItem[$i]['rules']['sm'];
                if($allFieldItem[$i]['rules']['sm']==12) {
                    $LayoutWidth[] = [$allFieldItem[$i]['name']];
                    $i -= 1;
                }
                else {
                    $LayoutWidth[] = [$allFieldItem[$i]['name'],$allFieldItem[$i+1]['name']];
                }
            }
        }
    }
    $RS['LayoutWidth']          = $LayoutWidth;

    //Convert data to Table
    $ApprovalNodeFieldsArray    = explode(',',$SettingMap['ApprovalNodeFields']);
    $ApprovalNodeFieldsHidden   = [];
    $ApprovalNodeFieldsStatus   = [];
    foreach($ApprovalNodeFieldsArray as $TempField) {
        $ApprovalNodeFieldsHidden[] = $TempField."审核状态";
        //$ApprovalNodeFieldsHidden[] = $TempField."申请时间";
        //$ApprovalNodeFieldsHidden[] = $TempField."申请人";
        $ApprovalNodeFieldsHidden[] = $TempField."审核时间";
        $ApprovalNodeFieldsHidden[] = $TempField."审核人";
        $ApprovalNodeFieldsHidden[] = $TempField."审核意见";
        $ApprovalNodeFieldsStatus[$TempField."审核状态"] = $TempField."审核状态";
    }
    $ApprovalNodeFieldsStatus = array_keys($ApprovalNodeFieldsStatus);
    $NewTableRowData    = [];
    $NewTableRowItem    = [];
    $FieldNameArray     = $allFieldsView['Default'];
    for($X=0;$X<sizeof($FieldNameArray);$X=$X+2)        {
        if($FieldNameArray[$X]['rules']['sm']==12) {
            $FieldName1     = $FieldNameArray[$X]['name'];
            if($FieldNameArray[$X]['type']=="autocomplete" && $FieldNameArray[$X]['code']!="") {
                $FieldName1 = $FieldNameArray[$X]['code'];
            }
            $RowData = [];
            if(!in_array($FieldName1,$ApprovalNodeFieldsHidden) && $FieldName1!="") {
                $RowData[0]['Name']     = $FieldName1;
                $RowData[0]['Value']    = $RS['data'][$FieldName1];
                $RowData[0]['FieldArray']   = $FieldNameArray[$X];
            }
            $NewTableRowItem[] = [$RowData];
            $X -= 1;
        }
        else {
            $FieldName1 = $FieldNameArray[$X]['name'];
            if($FieldNameArray[$X]['type']=="autocomplete" && $FieldNameArray[$X]['code']!="") {
                $FieldName1 = $FieldNameArray[$X]['code'];
            }
            $FieldName2 = $FieldNameArray[$X+1]['name'];
            if($FieldNameArray[$X+1]['type']=="autocomplete" && $FieldNameArray[$X+1]['code']!="") {
                $FieldName2 = $FieldNameArray[$X+1]['code'];
            }
            $RowData = [];
            $RowData1 = [];
            $RowData2 = [];
            if(!in_array($FieldName1,$ApprovalNodeFieldsHidden) && $FieldName1!="") {
                $RowData1['Name']     = $FieldName1;
                $RowData1['Value']    = $RS['data'][$FieldName1];
                $RowData1['FieldArray']     = $FieldNameArray[$X];
                $RowData[0]                 = $RowData1;
                $NewTableRowItem[]          = [$RowData1];
            }
            if(!in_array($FieldName2,$ApprovalNodeFieldsHidden) && $FieldName2!="") {
                $RowData2['Name']     = $FieldName2;
                $RowData2['Value']    = $RS['data'][$FieldName2];
                $RowData2['FieldArray']     = $FieldNameArray[$X+1];
                $RowData[1]                 = $RowData2;
                $NewTableRowItem[]          = [$RowData2];
            }

        }
        if(sizeof($RowData)>0) {
            $NewTableRowData[] = $RowData;
        }
    }
    if($_GET['isMobileData']=="true") {
        $RS['newTableRowData']          = $NewTableRowItem;
    }
    else {
        $RS['newTableRowData']          = $NewTableRowData;
    }
    $RS['_GET']          = $_GET;
    $RS['ApprovalNodes']['Nodes']   = $ApprovalNodeFieldsArray[0]!=""?$ApprovalNodeFieldsArray:[];
    $RS['ApprovalNodes']['Fields']  = ['审核结点','审核状态','审核时间','审核人','审核意见'];

    $RS['print']['text']            = __("Print");

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
            $sql        = "select * from $ChildTableName where $Relative_Child_Table_Parent_Field_Name = '".$data[$Relative_Child_Table_Parent_Field_Name]."';";
            $rs         = $db->Execute($sql);
            $rs_a       = $rs->GetArray();
            $RS['childtable']['sql']    = $sql;
            $RS['childtable']['data']   = $rs_a;
            $RS['childtable']['ChildItemCounter'] = sizeof($rs_a);

            //Get All Fields
            $sql                        = "select * from form_formfield where FormId='$ChildFormId' and IsEnable='1' order by SortNumber asc, id asc";
            $rs                         = $db->Execute($sql);
            $ChildAllFieldsFromTable    = $rs->GetArray();
            $allFieldsView   = getAllFields($ChildAllFieldsFromTable, $AllShowTypesArray, 'VIEW', true, $ChildSettingMap);
            foreach($allFieldsView as $ModeName=>$allFieldItem) {
                $allFieldItemIndex = 0;
                foreach($allFieldItem as $ITEM) {
                    //if(strpos($ChildSettingMap['Actions_In_List_Row'],'Edit')===false) {
                        //$allFieldsView[$ModeName][$allFieldItemIndex]['rules']['disabled'] = true;
                    //}
                    //$allFieldItemIndex ++;
                }
            }
            $RS['childtable']['allFields']  = $allFieldsView;

        }
    }

    if(in_array($SettingMap['MobileEndShowType'],["NewsTemplate1","ZiXun","Activity","Schoolmate","NotificationTemplate1","NotificationTemplate2"]))           {
        //News Template
        $RS['MobileEnd']['MobileEndNewsTitle']                = strval($data[$SettingMap['MobileEndNewsTitle']]);
        $RS['MobileEnd']['MobileEndNewsGroup']                = strval($data[$SettingMap['MobileEndNewsGroup']]);
        $RS['MobileEnd']['MobileEndNewsContent']              = strval($data[$SettingMap['MobileEndNewsContent']]);
        $RS['MobileEnd']['MobileEndNewsReadCounter']          = strval($data[$SettingMap['MobileEndNewsReadCounter']]);
        $RS['MobileEnd']['MobileEndNewsLikeCounter']          = strval($data[$SettingMap['MobileEndNewsLikeCounter']]);
        $RS['MobileEnd']['MobileEndNewsFavoriteCounter']      = strval($data[$SettingMap['MobileEndNewsFavoriteCounter']]);
        $RS['MobileEnd']['MobileEndNewsReadUsers']            = strval($data[$SettingMap['MobileEndNewsReadUsers']]);

        $RS['MobileEnd']['MobileEndSchoolmateCity']           = strval($data[$SettingMap['MobileEndSchoolmateCity']]);
        $RS['MobileEnd']['MobileEndSchoolmateCompany']        = strval($data[$SettingMap['MobileEndSchoolmateCompany']]);
        $RS['MobileEnd']['MobileEndSchoolmateIndustry']       = strval($data[$SettingMap['MobileEndSchoolmateIndustry']]);
        $RS['MobileEnd']['MobileEndSchoolmateFirstYear']      = strval($data[$SettingMap['MobileEndSchoolmateFirstYear']]);
        $RS['MobileEnd']['MobileEndSchoolmateLastYear']       = strval($data[$SettingMap['MobileEndSchoolmateLastYear']]);
        $RS['MobileEnd']['MobileEndSchoolmateAcademic']       = strval($data[$SettingMap['MobileEndSchoolmateAcademic']]);
        $RS['MobileEnd']['MobileEndSchoolmateLastActivity']   = strval($data[$SettingMap['MobileEndSchoolmateLastActivity']]);

        $MobileEndNewsCreator = strval(returntablefield("data_user","USER_ID",$data[$SettingMap['MobileEndNewsCreator']],"USER_NAME")["USER_NAME"]);;
        if($MobileEndNewsCreator!="") {
            $RS['MobileEnd']['MobileEndNewsCreator']          = $MobileEndNewsCreator;
        }
        else {
            $RS['MobileEnd']['MobileEndNewsCreator']          = $data[$SettingMap['MobileEndNewsCreator']];
        }
        $RS['MobileEnd']['MobileEndNewsCreatorGroup']         = strval($data[$SettingMap['MobileEndNewsCreatorGroup']]);
        $RS['MobileEnd']['MobileEndActivityFee']              = strval($data[$SettingMap['MobileEndActivityFee']]);
        $RS['MobileEnd']['MobileEndActivityContact']          = strval($data[$SettingMap['MobileEndActivityContact']]);
        $RS['MobileEnd']['MobileEndNewsEnrollment']           = strval($data[$SettingMap['MobileEndNewsEnrollment']]);
        $RS['MobileEnd']['MobileEndNewsLocation']             = strval($data[$SettingMap['MobileEndNewsLocation']]);
        $RS['MobileEnd']['MobileEndNewsLocation2']            = strval($data[$SettingMap['MobileEndNewsLocation2']]);
        $RS['MobileEnd']['MobileEndNewsCreateTime']           = substr($data[$SettingMap['MobileEndNewsCreateTime']],5,11);
        if($RS['MobileEnd']['MobileEndNewsLocation']!="") {
            $TempArray = explode('-', $RS['MobileEnd']['MobileEndNewsLocation']);
            $RS['MobileEnd']['MobileEndNewsLocation']         = $TempArray[1]." ".$TempArray[2];
            $RS['MobileEnd']['MobileEndNewsCreateTime']       = substr($data[$SettingMap['MobileEndNewsCreateTime']],5,5);
        }
        $RS['MobileEnd']['MobileEndNewsProcess']                = strval($data[$SettingMap['MobileEndNewsProcess']]);
        $RS['MobileEnd']['MobileEndNewsTopAvator']              = strval($data[$SettingMap['MobileEndNewsTopAvator']]);
        $RS['MobileEnd']['MobileEndActivityEnrollEndDate']      = strval($data[$SettingMap['MobileEndActivityEnrollEndDate']]);
        $RS['MobileEnd']['MobileEndActivityDate']               = strval($data[$SettingMap['MobileEndActivityDate']]);
        if($RS['MobileEnd']['MobileEndActivityEnrollEndDate']!="") {
            if($RS['MobileEnd']['MobileEndActivityEnrollEndDate']<date("Y-m-d")) {
                $RS['MobileEnd']['MobileEndActivityStatus'] = "结束";
            }
            else {
                $RS['MobileEnd']['MobileEndActivityStatus'] = "报名中";
            }
        }

        if($RS['MobileEnd']['MobileEndActivityDate']!="") {
            if($RS['MobileEnd']['MobileEndActivityDate']==date("Y-m-d")) {
                $RS['MobileEnd']['MobileEndActivityStatus'] = "进行中";
            }
        }

        if($SettingMap['MobileEndIconType']=="ImageField") {
            $data[$SettingMap['MobileEndNewsLeftImage']] = $data[$SettingMap['MobileEndIconField']];
        }
        if($SettingMap['MobileEndIconType']=="UserAvator") {

        }
        if($data[$SettingMap['MobileEndNewsLeftImage']]=="") {
            $data[$SettingMap['MobileEndNewsLeftImage']] = "/images/wechat/logo_icampus_left.png";
        }
        $RS['MobileEnd']['MobileEndNewsLeftImage']            = AttachFieldValueToUrl($TableName,$data['id'],$SettingMap['MobileEndNewsLeftImage'],'avatar',strval($data[$SettingMap['MobileEndNewsLeftImage']]));

        //Extra Logic
        if($SettingMap['MobileEndShowType']=="Activity") {
            $sql    = "select COUNT(*) AS NUM from data_xiaoyou_activity_record where 活动ID='".intval($data['id'])."' ";
            $rs     = $db->Execute($sql);
            $NUM    = intval($rs->fields['NUM']);
            $RS['MobileEnd']['MobileEndActivityHaveEnrollNumber'] = $NUM;
            $sql    = "select COUNT(*) AS NUM from data_xiaoyou_activity_record where 活动ID='".intval($data['id'])."' and 用户ID='".$GLOBAL_USER->USER_ID."' ";
            $rs     = $db->Execute($sql);
            $NUM    = intval($rs->fields['NUM']);
            $RS['MobileEnd']['MobileEndActivityMyEnrollStatus'] = $NUM;
        }
        $RS['MobileEnd']['MobileEndNewsEnableEnroll']               = $SettingMap['MobileEndNewsEnableEnroll'];
        $RS['MobileEnd']['MobileEndActionType']                     = $TableName;

        //Field Name
        $RS['MobileEnd']['MobileEndActivityFeeName']                = $SettingMap['MobileEndActivityFee'];
        $RS['MobileEnd']['MobileEndActivityContactName']            = $SettingMap['MobileEndActivityContact'];
        $RS['MobileEnd']['MobileEndNewsEnrollmentName']             = $SettingMap['MobileEndNewsEnrollment'];
        $RS['MobileEnd']['MobileEndNewsLocationName']               = $SettingMap['MobileEndNewsLocation'];
        $RS['MobileEnd']['MobileEndNewsLocation2Name']              = $SettingMap['MobileEndNewsLocation2'];
        $RS['MobileEnd']['MobileEndNewsCreateTimeName']             = $SettingMap['MobileEndNewsCreateTime'];
        $RS['MobileEnd']['MobileEndActivityDateName']               = $SettingMap['MobileEndActivityDate'];
        $RS['MobileEnd']['MobileEndActivityEnrollEndDateName']      = $SettingMap['MobileEndActivityEnrollEndDate'];
    }

    print_R(EncryptApiData($RS, $GLOBAL_USER));
    exit;
}
?>