<?php
//禁止当前文件直接在URL中访问
$CurrentUrlFileName = basename($_SERVER['PHP_SELF']);
$ForbiddenAccessUrlList = ['data_enginee_flow_lib_add_default_data.php'];
if(in_array($CurrentUrlFileName, $ForbiddenAccessUrlList)) exit;

if( $_GET['action']=="add_default_data" && in_array('Add',$Actions_In_List_Header_Array) && $TableName!="")  {

    //Filter data when do add save operation
    require_once('data_enginee_filter_post.php');
    $MetaColumns    = $db->MetaColumns($TableName);
    $MetaColumns    = array_values($MetaColumns);
    $MetaColumnsInDb = [];
    foreach($MetaColumns as $Item)  {
        $MetaColumnsInDb[$Item->name]       = $Item->type;
    }
    $MetaColumnNames    = GLOBAL_MetaColumnNames($TableName);

    //functionNameIndividual
    $functionNameIndividual = "plugin_".$TableName."_".$Step."_add_default_data_before_submit";
    if(function_exists($functionNameIndividual))  {
        $functionNameIndividual();
    }

    $FieldsArray        = [];
    $IsExecutionSQL     = 0;
    foreach($AllFieldsFromTable as $Item)  {
        if($_POST[$Item['FieldName']]!="") {
            $IsExecutionSQL = 1;
        }
        if($_POST[$Item['FieldName']]=="undefined") {
            $_POST[$Item['FieldName']] = "";
        }
        // Give a default value for date and number
        $FieldType = $MetaColumnsInDb[$Item['FieldName']];
        if($_POST[$Item['FieldName']]=="") {
            switch($FieldType)  {
                case 'int':
                    $_POST[$Item['FieldName']] = 0;
                    break;
                case 'date':
                    //$_POST[$Item['FieldName']] = "1971-01-01";
                    break;
                case 'datetime':
                    //$_POST[$Item['FieldName']] = "1971-01-01 00:00:00";
                    break;
            }
            $CurrentFieldType = $AllShowTypesArray[$AllFieldsMap[$Item['FieldName']]['ShowType']]['ADD'];
            switch($CurrentFieldType) {
                case '32位全局唯一编码字符串':
                    $学校十位代码   = returntablefield("ods_zzxxgkjcsj","id",1,"XXDM")['XXDM'];
                    $数据项编号     = $AllFieldsMap[$Item['FieldName']]['Placeholder'];
                    $唯一编码前缀   = $学校十位代码.$数据项编号;
                    $剩余位数       = 32-strlen($唯一编码前缀);
                    $sql = "select max(id) as NUM from $TableName";
                    $rs  = $db->Execute($sql);
                    $NUM = intval($rs->fields['NUM']);
                    $NUM += 1;
                    $补齐0数量      = $剩余位数-strlen($NUM);
                    while($补齐0数量>0) {
                        $唯一编码前缀 .= "0";
                        $补齐0数量 --;
                    }
                    $_POST[$Item['FieldName']] = $唯一编码前缀.$NUM;
                    break;
                case 'autoincrement':
                    $sql = "select max(id) as NUM from $TableName";
                    $rs  = $db->Execute($sql);
                    $NUM = intval($rs->fields['NUM']);
                    $NUM += 1;
                    $FROM = 100000;
                    $NUM += $FROM;
                    $_POST[$Item['FieldName']] = $NUM;
                    break;
                case 'autoincrementdate':
                    $sql = "select max(id) as NUM from $TableName";
                    $rs  = $db->Execute($sql);
                    $NUM = $rs->fields['NUM'];
                    $NUM += 1;
                    $FROM = date('Ymd');
                    if(strlen($NUM)==1) {
                        $NUM = $FROM."000".$NUM;
                    }
                    else if(strlen($NUM)==2) {
                        $NUM = $FROM."00".$NUM;
                    }
                    else if(strlen($NUM)==3) {
                        $NUM = $FROM."0".$NUM;
                    }
                    $_POST[$Item['FieldName']] = $NUM;
                    break;
                case 'avatar':
                    if(is_array($_FILES[$Item['FieldName']]))    {
                        ImageUploadToDisk($Item['FieldName']);
                        $FieldsArray[$Item['FieldName']]    = addslashes($_POST[$Item['FieldName']]);
                    }
                    elseif(strpos($_POST[$Item['FieldName']], "data_image.php?")!==false)  {
                        //Delete this Key from FieldsArray
                        $FieldsArray = array_diff_key($FieldsArray,[$Item['FieldName']=>""]);
                    }
                    break;
                case 'images':
                case 'images2':
                    if(is_array($_FILES[$Item['FieldName']]))    {
                        FilesUploadToDisk($Item['FieldName']);
                        $FieldsArray[$Item['FieldName']]    = addslashes($_POST[$Item['FieldName']]);
                    }
                    elseif(strpos($_POST[$Item['FieldName']], "data_image.php?")!==false)  {
                        //Delete this Key from FieldsArray
                        $FieldsArray = array_diff_key($FieldsArray,[$Item['FieldName']=>""]);
                    }
                    break;
                case 'files':
                case 'files2':
                    if(is_array($_FILES[$Item['FieldName']]))    {
                        FilesUploadToDisk($Item['FieldName']);
                        $FieldsArray[$Item['FieldName']]    = addslashes($_POST[$Item['FieldName']]);
                    }
                    elseif(strpos($_POST[$Item['FieldName']], "data_image.php?")!==false)  {
                        //Delete this Key from FieldsArray
                        $FieldsArray = array_diff_key($FieldsArray,[$Item['FieldName']=>""]);
                    }
                    break;
                case 'file':
                    if(is_array($_FILES[$Item['FieldName']]))    {
                        FilesUploadToDisk($Item['FieldName']);
                        $FieldsArray[$Item['FieldName']]    = addslashes($_POST[$Item['FieldName']]);
                    }
                    elseif(strpos($_POST[$Item['FieldName']], "data_image.php?")!==false)  {
                        //Delete this Key from FieldsArray
                        $FieldsArray = array_diff_key($FieldsArray,[$Item['FieldName']=>""]);
                    }
                    break;
                case 'xlsx':
                    if(is_array($_FILES[$Item['FieldName']]))    {
                        FilesUploadToDisk($Item['FieldName']);
                        $FieldsArray[$Item['FieldName']]    = addslashes($_POST[$Item['FieldName']]);
                    }
                    elseif(strpos($_POST[$Item['FieldName']], "data_image.php?")!==false)  {
                        //Delete this Key from FieldsArray
                        $FieldsArray = array_diff_key($FieldsArray,[$Item['FieldName']=>""]);
                    }
                    break;
            }
        }
        $FieldsArray[$Item['FieldName']]        = addslashes($_POST[$Item['FieldName']]);
        //To check need encrypt field value
        $FieldName                      = $Item['FieldName'];
        $SettingTempMap                 = $AllFieldsMap[$FieldName]['Setting'];
        $DataFieldEncryptMethod         = $SettingTempMap['DataFieldEncryptMethod'];
        $DataFieldEncryptKey            = $SettingTempMap['DataFieldEncryptKey'];
        if($DataFieldEncryptMethod==1&&$DataFieldEncryptKey!="") {
            $FieldsArray[$FieldName]            = EncryptIDStorage($FieldsArray[$FieldName], $DataFieldEncryptKey);
        }
    }
    if($IsExecutionSQL)   {
        global $InsertOrUpdateFieldArrayForSql; //Define in data_enginee_function.php
            foreach($InsertOrUpdateFieldArrayForSql['ADD'] as $FieldName=>$FieldValue)  {
                if($FieldValue=="EncryptField"&&$_POST[$FieldName]=="") {
                    //Not Need To Update Field Value
                }
                else if($FieldValue=="EncryptField"&&$_POST[$FieldName]!="") {
                    $FieldsArray[$FieldName]       = addslashes($_POST[$FieldName]);
                }
                else if($FieldValue!="")   {
                    $FieldsArray[$FieldName]        = $FieldValue;
                }
        }

        //Split Multi Records
        $Add_Page_Split_Multi_Records_Value_Array = [];
        $Add_Page_Split_Multi_Records = $SettingMap['AddPageSplitMultiRecords'];
        if($Add_Page_Split_Multi_Records!="" && $Add_Page_Split_Multi_Records!="None" && in_array($Add_Page_Split_Multi_Records,$MetaColumnNames) )      {
            $Add_Page_Split_Multi_Records_Value_Array = explode(',', $FieldsArray[$Add_Page_Split_Multi_Records]);
        }
        else {
            //Default a Value for Not Need To Split
            $Add_Page_Split_Multi_Records = "id";
            $Add_Page_Split_Multi_Records_Value_Array = [NULL];
        }
        //Begin to Split Multi Records
        foreach($Add_Page_Split_Multi_Records_Value_Array as $Add_Page_Split_Multi_Records_Value)    {
            $FieldsArray[$Add_Page_Split_Multi_Records] = $Add_Page_Split_Multi_Records_Value;
            //Syncing To Other Fields
            if($Add_Page_Split_Multi_Records=="学号" || $Add_Page_Split_Multi_Records=="学生学号") {
                $sql     = "select * from data_student where 学号 = '".ForSqlInjection($Add_Page_Split_Multi_Records_Value)."'";
                $rsf     = $db->Execute($sql);
                in_array("系部",$MetaColumnNames) ? $FieldsArray['系部'] = $rsf->fields['系部'] : '';
                in_array("专业",$MetaColumnNames) ? $FieldsArray['专业'] = $rsf->fields['专业'] : '';
                in_array("班级",$MetaColumnNames) ? $FieldsArray['班级'] = $rsf->fields['班级'] : '';
                in_array("姓名",$MetaColumnNames) ? $FieldsArray['姓名'] = $rsf->fields['姓名'] : '';
                in_array("学生班级",$MetaColumnNames) ? $FieldsArray['学生班级'] = $rsf->fields['学生班级'] : '';
                in_array("学生姓名",$MetaColumnNames) ? $FieldsArray['学生姓名'] = $rsf->fields['学生姓名'] : '';
                in_array("身份证号",$MetaColumnNames) ? $FieldsArray['身份证号'] = $rsf->fields['身份证号'] : '';
                in_array("出生日期",$MetaColumnNames) ? $FieldsArray['出生日期'] = $rsf->fields['出生日期'] : '';
                in_array("性别",$MetaColumnNames) ? $FieldsArray['性别'] = $rsf->fields['性别'] : '';
                in_array("座号",$MetaColumnNames) ? $FieldsArray['座号'] = $rsf->fields['座号'] : '';
                in_array("学生宿舍",$MetaColumnNames) ? $FieldsArray['学生宿舍'] = $rsf->fields['学生宿舍'] : '';
                in_array("学生状态",$MetaColumnNames) ? $FieldsArray['学生状态'] = $rsf->fields['学生状态'] : '';
                in_array("学生手机",$MetaColumnNames) ? $FieldsArray['学生手机'] = $rsf->fields['学生手机'] : '';
            }
            //Unique Fields
            $SQL_Unique_Fields = ['1=1'];
            if($SettingMap['Unique_Fields_1']!="" && $SettingMap['Unique_Fields_1']!="None" && in_array($SettingMap['Unique_Fields_1'],$MetaColumnNames) ) {
                $SQL_Unique_Fields[] = $SettingMap['Unique_Fields_1']." = '".$FieldsArray[$SettingMap['Unique_Fields_1']]."' ";
            }
            if($SettingMap['Unique_Fields_2']!="" && $SettingMap['Unique_Fields_2']!="None" && in_array($SettingMap['Unique_Fields_2'],$MetaColumnNames) ) {
                $SQL_Unique_Fields[] = $SettingMap['Unique_Fields_2']." = '".$FieldsArray[$SettingMap['Unique_Fields_2']]."' ";
            }
            if($SettingMap['Unique_Fields_3']!="" && $SettingMap['Unique_Fields_3']!="None" && in_array($SettingMap['Unique_Fields_3'],$MetaColumnNames) ) {
                $SQL_Unique_Fields[] = $SettingMap['Unique_Fields_3']." = '".$FieldsArray[$SettingMap['Unique_Fields_3']]."' ";
            }
            if(sizeof($SQL_Unique_Fields)>1) {
                $sql    = "select COUNT(*) AS NUM from $TableName where ".join(" and ", $SQL_Unique_Fields)."";
                $rsTemp = $db->Execute($sql);
                if($rsTemp->fields['NUM']>=1) {
                    $RS = [];
                    $RS['status'] = "ERROR";
                    $RS['msg'] = $SettingMap['Unique_Fields_Repeat_Text']?$SettingMap['Unique_Fields_Repeat_Text']:__('Unique_Fields_Repeat_Text');
                    if($SettingMap['Debug_Sql_Show_On_Api']=="Yes" && in_array($GLOBAL_USER->USER_ID, ['admin', 'admin001']))  $RS['sql'] = $sql;
                    if($SettingMap['Debug_Sql_Show_On_Api']=="Yes" && in_array($GLOBAL_USER->USER_ID, ['admin', 'admin001']))  $RS['_GET'] = $_GET;
                    if($SettingMap['Debug_Sql_Show_On_Api']=="Yes" && in_array($GLOBAL_USER->USER_ID, ['admin', 'admin001']))  $RS['_POST'] = $_POST;
                    print json_encode($RS);
                    exit;
                }
            }

            //Execute Insert SQL
            $KEYS			= array_keys($FieldsArray);
            $VALUES			= array_values($FieldsArray);
            $sql	        = "insert into $TableName(`".join('`,`',$KEYS)."`) values('".join("','",$VALUES)."')";
            $rs             = $db->Execute($sql);
        }
        if($rs->EOF) {
            $NewId = $db->Insert_ID();
            UpdateOtherTableFieldAfterFormSubmit($NewId);
            $Msg_Reminder_Object_From_Add_Or_Edit_Result = Msg_Reminder_Object_From_Add_Or_Edit($TableName, $NewId);
            $RS['status'] = "OK";
            $RS['msg'] = $SettingMap['Tip_When_Add_Success'];
            $RS['Msg_Reminder_Object_From_Add_Or_Edit_Result'] = $Msg_Reminder_Object_From_Add_Or_Edit_Result;
            if($SettingMap['Debug_Sql_Show_On_Api']=="Yes" && in_array($GLOBAL_USER->USER_ID, ['admin', 'admin001']))  {
                $RS['sql'] = $sql;
                global $GLOBAL_EXEC_KEY_SQL;
                $RS['GLOBAL_EXEC_KEY_SQL'] = $GLOBAL_EXEC_KEY_SQL;
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
                if($Relative_Child_Table_Field_Name!="" && in_array($Relative_Child_Table_Field_Name, $ChildMetaColumnNames) &&strpos($ChildSettingMap['Actions_In_List_Row'],'Edit')!==false) {
                    //Get All Fields
                    $db->BeginTrans();
                    $MultiSql                   = [];
                    $sql                        = "delete from $ChildTableName where $Relative_Child_Table_Parent_Field_Name = '".$FieldsArray[$Relative_Child_Table_Parent_Field_Name]."';";
                    $db->Execute($sql);
                    $MultiSql[]                 = $sql;
                    $sql                        = "select * from form_formfield where FormId='$ChildFormId' and IsEnable='1' order by SortNumber asc, id asc";
                    $rs                         = $db->Execute($sql);
                    $ChildAllFieldsFromTable    = $rs->GetArray();
                    $ChildAllFieldsMap          = [];
                    $ChildItemCounter           = intval($_POST['ChildItemCounter']);
                    for($X=0;$X<$ChildItemCounter;$X++)                    {
                        $ChildElement = [];
                        foreach($ChildAllFieldsFromTable as $Item)  {
                            $ChildFieldName = $Item['FieldName'];
                            switch($Item['ShowType']) {
                                case 'Hidden:Createtime':
                                    $ChildElement[$ChildFieldName] = date('Y-m-d H:i:s');
                                    break;
                                case 'Hidden:CurrentUserIdAdd':
                                case 'Hidden:CurrentUserIdAddEdit':
                                    $ChildElement[$ChildFieldName] = $GLOBAL_USER->USER_ID;
                                    break;
                                case 'Hidden:CurrentStudentCodeAdd':
                                case 'Hidden:CurrentStudentCodeAddEdit':
                                    if($GLOBAL_USER->学号=="") $GLOBAL_USER->学号 = $GLOBAL_USER->USER_ID;
                                    $ChildElement[$ChildFieldName] = $GLOBAL_USER->学号;
                                    break;
                                default:
                                    $ChildElement[$ChildFieldName] = ForSqlInjection($_POST['ChildTable____'.$X.'____'.$ChildFieldName]);
                                    break;
                            }
                        }
                        $deleteChildTableItemArray = explode(',', ForSqlInjection($_POST['deleteChildTableItemArray']));
                        if(!in_array($X, $deleteChildTableItemArray)) {
                            $ChildElement[$Relative_Child_Table_Parent_Field_Name] = $FieldsArray[$Relative_Child_Table_Parent_Field_Name];
                            $ChildKeys      = array_keys($ChildElement);
                            $ChildValues    = array_values($ChildElement);
                            $sql            = "insert into $ChildTableName (".join(',',$ChildKeys).") values('".join("','",$ChildValues)."');";
                            $db->Execute($sql);
                            $MultiSql[]     = $sql;
                        }
                    }
                    $db->CommitTrans();
                    $RS['MultiSql'] = $MultiSql;
                }
            }

            //functionNameIndividual
            $functionNameIndividual = "plugin_".$TableName."_".$Step."_add_default_data_after_submit";
            if(function_exists($functionNameIndividual))  {
                $functionNameIndividual($NewId);
            }
            //SystemLogRecord
            if(in_array($SettingMap['OperationLogGrade'],["AddEditAndDeleteOperation","AllOperation"]))  {
                $sql    = "select * from $TableName where ".$MetaColumnNames[0]." = '$NewId'";
                $Record = $db->Execute($sql);
                SystemLogRecord("add_default_data", '', json_encode($Record->fields));
            }
            print json_encode($RS);
            exit;
        }
        else {
            $RS = [];
            $RS['status'] = "ERROR";
            $RS['msg'] = __("sql execution failed");
            if($SettingMap['Debug_Sql_Show_On_Api']=="Yes" && in_array($GLOBAL_USER->USER_ID, ['admin', 'admin001']))  $RS['sql'] = $sql;
            if($SettingMap['Debug_Sql_Show_On_Api']=="Yes" && in_array($GLOBAL_USER->USER_ID, ['admin', 'admin001']))  $RS['_GET'] = $_GET;
            if($SettingMap['Debug_Sql_Show_On_Api']=="Yes" && in_array($GLOBAL_USER->USER_ID, ['admin', 'admin001']))  $RS['_POST'] = $_POST;
            print json_encode($RS);
            exit;
        }
    }
    else {
        $RS = [];
        $RS['status'] = "ERROR";
        $RS['msg'] = __("No POST Infor");
        if($SettingMap['Debug_Sql_Show_On_Api']=="Yes" && in_array($GLOBAL_USER->USER_ID, ['admin', 'admin001']))  $RS['sql'] = $sql;
        if($SettingMap['Debug_Sql_Show_On_Api']=="Yes" && in_array($GLOBAL_USER->USER_ID, ['admin', 'admin001']))  $RS['_GET'] = $_GET;
        if($SettingMap['Debug_Sql_Show_On_Api']=="Yes" && in_array($GLOBAL_USER->USER_ID, ['admin', 'admin001']))  $RS['_POST'] = $_POST;
        print json_encode($RS);
        exit;
    }
}

?>