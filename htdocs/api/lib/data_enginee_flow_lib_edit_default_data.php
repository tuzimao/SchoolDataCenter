<?php
//禁止当前文件直接在URL中访问
$CurrentUrlFileName = basename($_SERVER['PHP_SELF']);
$ForbiddenAccessUrlList = ['data_enginee_flow_lib_edit_default_data.php'];
if(in_array($CurrentUrlFileName, $ForbiddenAccessUrlList)) exit;

if( $_GET['action']=="edit_default_data" && in_array('Edit',$Actions_In_List_Row_Array) && $_GET['id']!="" && $TableName!="")  {
    if($TableName=="data_user" && $SettingMap['Init_Action_Value']=="edit_default" && $SettingMap['Init_Action_FilterValue']=="email") {
        $EMAIL  = $GLOBAL_USER->email;
        $id     = returntablefield($TableName,"EMAIL",$EMAIL,"id")["id"];
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
    $MetaColumnNames    = GLOBAL_MetaColumnNames($TableName);
    $FieldsArray        = [];
    $FieldsArray['id']  = $id;
    $IsExecutionSQL     = 0;
    $IsExecutionSQLChildTable     = 0;
    //Filter data when do edit save operation
    require_once('data_enginee_filter_post.php');

    //functionNameIndividual
    $functionNameIndividual = "plugin_".$TableName."_".$Step."_edit_default_data_before_submit";
    if(function_exists($functionNameIndividual))  {
        $functionNameIndividual($id);
    }

    global $InsertOrUpdateFieldArrayForSql; //Define in data_enginee_function.php
    //print_R($InsertOrUpdateFieldArrayForSql);exit;
    foreach($InsertOrUpdateFieldArrayForSql['EDIT'] as $FieldName=>$FieldValue)  {
        if($FieldValue=="EncryptField"&&$_POST[$FieldName]=="") {
            //Not Need To Update Field Value
        }
        else if($FieldValue=="EncryptField"&&$_POST[$FieldName]!="") {
            $FieldsArray[$FieldName]       = addslashes($_POST[$FieldName]);
        }
        else if($FieldValue==""&&is_string($_POST[$FieldName]))   {
            $FieldsArray[$FieldName]       = addslashes($_POST[$FieldName]);
            //To check need encrypt field value
            $SettingTempMap                 = $AllFieldsMap[$FieldName]['Setting'];
            $DataFieldEncryptMethod         = $SettingTempMap['DataFieldEncryptMethod'];
            $DataFieldEncryptKey            = $SettingTempMap['DataFieldEncryptKey'];
            if($DataFieldEncryptMethod==1&&$DataFieldEncryptKey!="") {
                $FieldsArray[$FieldName]    = EncryptIDStorage($FieldsArray[$FieldName], $DataFieldEncryptKey);
            }
        }
        else if($FieldValue==""&&is_array($_POST[$FieldName]))   {
            $FieldsArray[$FieldName]       = $_POST[$FieldName];
        }
        else {
            $FieldsArray[$FieldName]       = $FieldValue;
        }
        if($_POST[$FieldName]!="") {
            $IsExecutionSQL = 1;
        }
    }
    if($_POST['ChildItemCounter']>0 && $SettingMap['Relative_Child_Table_Edit_Priv'] == "Yes") {
        $IsExecutionSQLChildTable = 1;
    }
    //Check Permission For This Record
    //LimitEditAndDelete
    $sql            = "select * from $TableName where ".$MetaColumnNames[0]." = '$id'";
    $RecordOriginal = $db->Execute($sql);
    if($SettingMap['LimitEditAndDelete_Edit_Field_One']!="" && $SettingMap['LimitEditAndDelete_Edit_Field_One']!="None" && in_array($SettingMap['LimitEditAndDelete_Edit_Field_One'], $MetaColumnNames)) {
        $LimitEditAndDelete_Edit_Value_One_Array = explode(',',$SettingMap['LimitEditAndDelete_Edit_Value_One']);
        if(in_array($RecordOriginal->fields[$SettingMap['LimitEditAndDelete_Edit_Field_One']],$LimitEditAndDelete_Edit_Value_One_Array)) {
            $RS = [];
            $RS['status'] = "ERROR";
            $RS['msg'] = __("LimitEditAndDelete");
            $RS['_GET'] = $_GET;
            $RS['_POST'] = $_POST;
            print json_encode($RS);
            exit;
        }
    }
    if($SettingMap['LimitEditAndDelete_Edit_Field_Two']!="" && $SettingMap['LimitEditAndDelete_Edit_Field_Two']!="None" && in_array($SettingMap['LimitEditAndDelete_Edit_Field_Two'], $MetaColumnNames)) {
        $LimitEditAndDelete_Edit_Value_Two_Array = explode(',',$SettingMap['LimitEditAndDelete_Edit_Value_Two']);
        if(in_array($RecordOriginal->fields[$SettingMap['LimitEditAndDelete_Edit_Field_Two']],$LimitEditAndDelete_Edit_Value_Two_Array)) {
            $RS = [];
            $RS['status'] = "ERROR";
            $RS['msg'] = __("LimitEditAndDelete");
            $RS['_GET'] = $_GET;
            $RS['_POST'] = $_POST;
            print json_encode($RS);
            exit;
        }
    }
    foreach($AllFieldsFromTable as $Item)  {
        $CurrentFieldType = $AllShowTypesArray[$AllFieldsMap[$Item['FieldName']]['ShowType']]['EDIT'];
        $AllowEditFiledInEditMode = array_keys($InsertOrUpdateFieldArrayForSql['EDIT']);
        if(in_array($Item['FieldName'], $AllowEditFiledInEditMode)) {
            switch($CurrentFieldType) {
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
                    if(is_array($_POST[$Item['FieldName']."_OriginalFieldValue"]))  {
                        $OriginalValue = $RecordOriginal->fields[$Item['FieldName']];
                        $FieldsArray[$Item['FieldName']]    = AttachValueMinusOneFile($OriginalValue, $_POST[$Item['FieldName']."_OriginalFieldValue"], $FieldsArray[$Item['FieldName']]);
                    }
                    if(!is_array($_FILES[$Item['FieldName']]) && !is_array($_POST[$Item['FieldName']."_OriginalFieldValue"]))    {
                        $FieldsArray[$Item['FieldName']]    = "";
                    }
                    break;
                case 'files':
                case 'files2':
                    if(is_array($_FILES[$Item['FieldName']]))    {
                        FilesUploadToDisk($Item['FieldName']);
                        $FieldsArray[$Item['FieldName']]    = addslashes($_POST[$Item['FieldName']]);
                    }
                    if(is_array($_POST[$Item['FieldName']."_OriginalFieldValue"]))  {
                        $OriginalValue = $RecordOriginal->fields[$Item['FieldName']];
                        $FieldsArray[$Item['FieldName']]    = AttachValueMinusOneFile($OriginalValue, $_POST[$Item['FieldName']."_OriginalFieldValue"], $FieldsArray[$Item['FieldName']]);
                    }
                    if(!is_array($_FILES[$Item['FieldName']]) && !is_array($_POST[$Item['FieldName']."_OriginalFieldValue"]))    {
                        $FieldsArray[$Item['FieldName']]    = "";
                    }
                    break;
                case 'file':
                    if(is_array($_FILES[$Item['FieldName']]))    {
                        FilesUploadToDisk($Item['FieldName']);
                        $FieldsArray[$Item['FieldName']]    = addslashes($_POST[$Item['FieldName']]);
                    }
                    if(is_array($_POST[$Item['FieldName']."_OriginalFieldValue"]))  {
                        $OriginalValue = $RecordOriginal->fields[$Item['FieldName']];
                        $FieldsArray[$Item['FieldName']]    = AttachValueMinusOneFile($OriginalValue, $_POST[$Item['FieldName']."_OriginalFieldValue"], $FieldsArray[$Item['FieldName']]);
                    }
                    if(!is_array($_FILES[$Item['FieldName']]) && !is_array($_POST[$Item['FieldName']."_OriginalFieldValue"]))    {
                        $FieldsArray[$Item['FieldName']]    = "";
                    }
                    break;
                case 'xlsx':
                    if(is_array($_FILES[$Item['FieldName']]))    {
                        FilesUploadToDisk($Item['FieldName']);
                        $FieldsArray[$Item['FieldName']]    = addslashes($_POST[$Item['FieldName']]);
                    }
                    if(is_array($_POST[$Item['FieldName']."_OriginalFieldValue"]))  {
                        $OriginalValue = $RecordOriginal->fields[$Item['FieldName']];
                        $FieldsArray[$Item['FieldName']]    = AttachValueMinusOneFile($OriginalValue, $_POST[$Item['FieldName']."_OriginalFieldValue"], $FieldsArray[$Item['FieldName']]);
                    }
                    if(!is_array($_FILES[$Item['FieldName']]) && !is_array($_POST[$Item['FieldName']."_OriginalFieldValue"]))    {
                        $FieldsArray[$Item['FieldName']]    = "";
                    }
                    break;
            }
        }
    }
    if($IsExecutionSQL)   {
        //先更新主表,再更新子表
        if($IsExecutionSQLChildTable == 1)  {
            [$Record,$sql]  = InsertOrUpdateTableByArray($TableName, $FieldsArray, 'id', 0, "InsertOrUpdate");
        }
        else {
            [$Record,$sql]  = InsertOrUpdateTableByArray($TableName, $FieldsArray, 'id', 0, "Update");
        }
        if($Record->EOF) {
            UpdateOtherTableFieldAfterFormSubmit($FieldsArray['id']);
            $Msg_Reminder_Object_From_Add_Or_Edit_Result = Msg_Reminder_Object_From_Add_Or_Edit($TableName, $FieldsArray['id']);
            $RS['status'] = "OK";
            $RS['msg'] = $SettingMap['Tip_When_Edit_Success'];
            $RS['Msg_Reminder_Object_From_Add_Or_Edit_Result'] = $Msg_Reminder_Object_From_Add_Or_Edit_Result;
            if($SettingMap['Debug_Sql_Show_On_Api']=="Yes" && in_array($GLOBAL_USER->USER_ID, ['admin', 'admin001']))  {
                global $GLOBAL_EXEC_KEY_SQL;
                $RS['sql'] = $sql;
                $RS['GLOBAL_EXEC_KEY_SQL'] = $GLOBAL_EXEC_KEY_SQL;
            }
            if($SettingMap['Debug_Sql_Show_On_Api']=="Yes" && in_array($GLOBAL_USER->USER_ID, ['admin', 'admin001']))  $RS['sql'] = $sql;
            if($SettingMap['Debug_Sql_Show_On_Api']=="Yes" && in_array($GLOBAL_USER->USER_ID, ['admin', 'admin001']))  $RS['_POST'] = $_POST;
            if($SettingMap['Debug_Sql_Show_On_Api']=="Yes" && in_array($GLOBAL_USER->USER_ID, ['admin', 'admin001']))  $RS['_FILES'] = $_FILES;
            //Batch_Approval
            $Batch_Approval_Status_Field    = $SettingMap['Batch_Approval_Status_Field'];
            $Batch_Approval_Status_Value    = $SettingMap['Batch_Approval_Status_Value'];
            if($Batch_Approval_Status_Value!="" && $_POST[$Batch_Approval_Status_Field]==$Batch_Approval_Status_Value)  {
                option_multi_approval_exection($FieldsArray['id'], $multiReviewInputValue='', $Reminder=0, $UpdateOtherTableField=0);
            }
            //Batch_Cancel
            $Batch_Cancel_Status_Field    = $SettingMap['Batch_Cancel_Status_Field'];
            $Batch_Cancel_Status_Value    = $SettingMap['Batch_Cancel_Status_Value'];
            if($Batch_Cancel_Status_Value!="" && $_POST[$Batch_Cancel_Status_Field]==$Batch_Cancel_Status_Value)  {
                option_multi_cancel_exection($FieldsArray['id'], $multiReviewInputValue='', $Reminder=0, $UpdateOtherTableField=0);
            }
            //Batch_Refuse
            $Batch_Refuse_Status_Field    = $SettingMap['Batch_Refuse_Status_Field'];
            $Batch_Refuse_Status_Value    = $SettingMap['Batch_Refuse_Status_Value'];
            if($Batch_Refuse_Status_Value!="" && $_POST[$Batch_Refuse_Status_Field]==$Batch_Refuse_Status_Value)  {
                option_multi_refuse_exection($FieldsArray['id'], $multiReviewInputValue='', $Reminder=0, $UpdateOtherTableField=0);
            }
            //Relative Child Table Support
            $Relative_Child_Table                   = $SettingMap['Relative_Child_Table'];
            $Relative_Child_Table_Type              = $SettingMap['Relative_Child_Table_Type'];
            $Relative_Child_Table_Field_Name        = $SettingMap['Relative_Child_Table_Field_Name'];
            $Relative_Child_Table_Parent_Field_Name = $SettingMap['Relative_Child_Table_Parent_Field_Name'];
            if($IsExecutionSQLChildTable == 1 && $Relative_Child_Table>0 && $Relative_Child_Table_Parent_Field_Name!="" && in_array($Relative_Child_Table_Parent_Field_Name,$MetaColumnNames)) {
                $ChildSettingMap = returntablefield("form_formflow",'id',$Relative_Child_Table,'Setting')['Setting'];
                $ChildSettingMap = unserialize(base64_decode($ChildSettingMap));
                $ChildFormId                = returntablefield("form_formflow",'id',$Relative_Child_Table,'FormId')['FormId'];
                $ChildTableName             = returntablefield("form_formname",'id',$ChildFormId,'TableName')['TableName'];
                $ChildMetaColumnNames       = GLOBAL_MetaColumnNames($ChildTableName);
                if($Relative_Child_Table_Field_Name!="" && in_array($Relative_Child_Table_Field_Name, $ChildMetaColumnNames) &&strpos($ChildSettingMap['Actions_In_List_Row'],'Edit')!==false) {
                    //Get All Fields
                    $readonlyIdArray            = explode(',',ForSqlInjection($_POST['readonlyIdArray']));
                    $db->BeginTrans();
                    $MultiSql                   = [];
                    $sql                        = "delete from $ChildTableName where $Relative_Child_Table_Parent_Field_Name = '".$RecordOriginal->fields[$Relative_Child_Table_Parent_Field_Name]."' and id not in ('".join("','",$readonlyIdArray)."');";
                    $db->Execute($sql);
                    $MultiSql[]                 = $sql;
                    $sql                        = "select * from form_formfield where FormId='$ChildFormId' and IsEnable='1' order by SortNumber asc, id asc";
                    $rs                         = $db->Execute($sql);
                    $ChildAllFieldsFromTable    = $rs->GetArray();
                    $ChildAllFieldsMap          = [];
                    $ChildItemCounter           = $_POST['ChildItemCounter'];
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
                        $deleteChildTableItemArray = explode(',',$_POST['deleteChildTableItemArray']);
                        if(!in_array($X, $deleteChildTableItemArray)) {
                            $ChildElement[$Relative_Child_Table_Parent_Field_Name] = $RecordOriginal->fields[$Relative_Child_Table_Parent_Field_Name];
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
            $functionNameIndividual = "plugin_".$TableName."_".$Step."_edit_default_data_after_submit";
            if(function_exists($functionNameIndividual))  {
                $functionNameIndividual($id);
            }
            //SystemLogRecord
            if(in_array($SettingMap['OperationLogGrade'],["EditAndDeleteOperation","AddEditAndDeleteOperation","AllOperation"]))  {
                $sql            = "select * from $TableName where ".$MetaColumnNames[0]." = '$id'";
                $Record         = $db->Execute($sql);
                SystemLogRecord("edit_default_data", json_encode($RecordOriginal->fields), json_encode($Record->fields));
            }
            print_R(EncryptApiData($RS, $GLOBAL_USER));
            exit;
        }
        else {
            $RS = [];
            $RS['status'] = "ERROR";
            $RS['msg'] = __("sql execution failed");
            if($SettingMap['Debug_Sql_Show_On_Api']=="Yes" && in_array($GLOBAL_USER->USER_ID, ['admin', 'admin001']))  $RS['sql'] = $sql;
            if($SettingMap['Debug_Sql_Show_On_Api']=="Yes" && in_array($GLOBAL_USER->USER_ID, ['admin', 'admin001']))  $RS['_GET'] = $_GET;
            if($SettingMap['Debug_Sql_Show_On_Api']=="Yes" && in_array($GLOBAL_USER->USER_ID, ['admin', 'admin001']))  $RS['_POST'] = $_POST;
            print_R(EncryptApiData($RS, $GLOBAL_USER));
            exit;
        }
    }
    else if($IsExecutionSQL == 0 && $IsExecutionSQLChildTable == 1)   {
        //只更新子表, 不更新主表
        //Relative Child Table Support
        $Relative_Child_Table                   = $SettingMap['Relative_Child_Table'];
        $Relative_Child_Table_Type              = $SettingMap['Relative_Child_Table_Type'];
        $Relative_Child_Table_Field_Name        = $SettingMap['Relative_Child_Table_Field_Name'];
        $Relative_Child_Table_Parent_Field_Name = $SettingMap['Relative_Child_Table_Parent_Field_Name'];
        $RS['status']   = "OK";
        $RS['msg']      = $SettingMap['Tip_When_Edit_Success'];
        if($Relative_Child_Table>0 && $Relative_Child_Table_Parent_Field_Name!="" && in_array($Relative_Child_Table_Parent_Field_Name,$MetaColumnNames)) {
            $ChildSettingMap = returntablefield("form_formflow",'id',$Relative_Child_Table,'Setting')['Setting'];
            $ChildSettingMap = unserialize(base64_decode($ChildSettingMap));
            $ChildFormId                = returntablefield("form_formflow",'id',$Relative_Child_Table,'FormId')['FormId'];
            $ChildTableName             = returntablefield("form_formname",'id',$ChildFormId,'TableName')['TableName'];
            $ChildMetaColumnNames       = GLOBAL_MetaColumnNames($ChildTableName);
            if($Relative_Child_Table_Field_Name!="" && in_array($Relative_Child_Table_Field_Name, $ChildMetaColumnNames) &&strpos($ChildSettingMap['Actions_In_List_Row'],'Edit')!==false) {
                //Get All Fields
                $readonlyIdArray            = explode(',',ForSqlInjection($_POST['readonlyIdArray']));
                $db->BeginTrans();
                $MultiSql                   = [];
                $sql                        = "delete from $ChildTableName where $Relative_Child_Table_Parent_Field_Name = '".$RecordOriginal->fields[$Relative_Child_Table_Parent_Field_Name]."' and id not in ('".join("','",$readonlyIdArray)."');";
                $db->Execute($sql);
                $MultiSql[]                 = $sql;
                $sql                        = "select * from form_formfield where FormId='$ChildFormId' and IsEnable='1' order by SortNumber asc, id asc";
                $rs                         = $db->Execute($sql);
                $ChildAllFieldsFromTable    = $rs->GetArray();
                $ChildAllFieldsMap          = [];
                $ChildItemCounter           = $_POST['ChildItemCounter'];
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
                    $deleteChildTableItemArray = explode(',',$_POST['deleteChildTableItemArray']);
                    if(!in_array($X, $deleteChildTableItemArray)) {
                        $ChildElement[$Relative_Child_Table_Parent_Field_Name] = $RecordOriginal->fields[$Relative_Child_Table_Parent_Field_Name];
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
        $functionNameIndividual = "plugin_".$TableName."_".$Step."_edit_default_data_after_submit";
        if(function_exists($functionNameIndividual))  {
            $functionNameIndividual($id);
        }
        //SystemLogRecord
        if(in_array($SettingMap['OperationLogGrade'],["EditAndDeleteOperation","AddEditAndDeleteOperation","AllOperation"]))  {
            $sql            = "select * from $TableName where ".$MetaColumnNames[0]." = '$id'";
            $Record         = $db->Execute($sql);
            SystemLogRecord("edit_default_data", json_encode($RecordOriginal->fields), json_encode($Record->fields));
        }
        print_R(EncryptApiData($RS, $GLOBAL_USER));
        exit;
    }
    else if($IsExecutionSQL == 0 && $_POST['ChildItemCounter']>0)   {
        $RS = [];
        $RS['status'] = "OK";
        $RS['msg']    = $SettingMap['Tip_When_Edit_Success'];
        $RS['memo']   = "当前节点为只读操作, 没有执行数据库操作";
        print_R(EncryptApiData($RS, $GLOBAL_USER));
        exit;
    }
    else {
        $RS = [];
        $RS['status']       = "ERROR";
        $RS['msg']          = __("No POST Infor");
        $RS['IsExecutionSQL']           = $IsExecutionSQL;
        $RS['IsExecutionSQLChildTable'] = $IsExecutionSQLChildTable;
        $RS['_POST'] = $_POST;
        if($SettingMap['Debug_Sql_Show_On_Api']=="Yes" && in_array($GLOBAL_USER->USER_ID, ['admin', 'admin001']))  $RS['sql'] = $sql;
        if($SettingMap['Debug_Sql_Show_On_Api']=="Yes" && in_array($GLOBAL_USER->USER_ID, ['admin', 'admin001']))  $RS['_GET'] = $_GET;
        if($SettingMap['Debug_Sql_Show_On_Api']=="Yes" && in_array($GLOBAL_USER->USER_ID, ['admin', 'admin001']))  $RS['_POST'] = $_POST;
        if($SettingMap['Debug_Sql_Show_On_Api']=="Yes" && in_array($GLOBAL_USER->USER_ID, ['admin', 'admin001']))  $RS['IsExecutionSQL'] = $IsExecutionSQL;
        if($SettingMap['Debug_Sql_Show_On_Api']=="Yes" && in_array($GLOBAL_USER->USER_ID, ['admin', 'admin001']))  $RS['IsExecutionSQLChildTable'] = $IsExecutionSQLChildTable;
        print_R(EncryptApiData($RS, $GLOBAL_USER));
        exit;
    }
}
?>