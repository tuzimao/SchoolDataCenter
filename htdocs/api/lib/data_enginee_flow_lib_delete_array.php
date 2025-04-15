<?php
//禁止当前文件直接在URL中访问
$CurrentUrlFileName = basename($_SERVER['PHP_SELF']);
$ForbiddenAccessUrlList = ['data_enginee_flow_lib_delete_array.php'];
if(in_array($CurrentUrlFileName, $ForbiddenAccessUrlList)) exit;

if($_GET['action']=="delete_array")  {
    $selectedRows  = ForSqlInjection($_POST['selectedRows']);
    $selectedRows = explode(',',$selectedRows);
    $primary_key = $MetaColumnNames[0];
    foreach($selectedRows as $id) {
        $id     = intval(DecryptID($id));
        if($id>0)  {
            //Check Permission For This Record
            //LimitEditAndDelete
            $sql            = "select * from $TableName where ".$MetaColumnNames[0]." = '$id'";
            $RecordOriginal = $db->Execute($sql);
            if($SettingMap['LimitEditAndDelete_Delete_Field_One']!="" && $SettingMap['LimitEditAndDelete_Delete_Field_One']!="None" && in_array($SettingMap['LimitEditAndDelete_Delete_Field_One'], $MetaColumnNames)) {
                $LimitEditAndDelete_Delete_Value_One_Array = explode(',',$SettingMap['LimitEditAndDelete_Delete_Value_One']);
                if(in_array($RecordOriginal->fields[$SettingMap['LimitEditAndDelete_Delete_Field_One']],$LimitEditAndDelete_Delete_Value_One_Array)) {
                    $RS = [];
                    $RS['status'] = "ERROR";
                    $RS['msg'] = __("Error Id Value");
                    $RS['_GET'] = $_GET;
                    $RS['_POST'] = $_POST;
                    print json_encode($RS);
                    exit;
                }
            }
            if($SettingMap['LimitEditAndDelete_Delete_Field_Two']!="" && $SettingMap['LimitEditAndDelete_Delete_Field_Two']!="None" && in_array($SettingMap['LimitEditAndDelete_Delete_Field_Two'], $MetaColumnNames)) {
                $LimitEditAndDelete_Delete_Value_Two_Array = explode(',',$SettingMap['LimitEditAndDelete_Delete_Value_Two']);
                if(in_array($RecordOriginal->fields[$SettingMap['LimitEditAndDelete_Delete_Field_Two']],$LimitEditAndDelete_Delete_Value_Two_Array)) {
                    $RS = [];
                    $RS['status'] = "ERROR";
                    $RS['msg'] = __("Error Id Value");
                    $RS['_GET'] = $_GET;
                    $RS['_POST'] = $_POST;
                    print json_encode($RS);
                    exit;
                }
            }
            if(in_array($SettingMap['OperationLogGrade'],["DeleteOperation","EditAndDeleteOperation","AddEditAndDeleteOperation","AllOperation"]))  {
                SystemLogRecord("delete_array", '', json_encode($RecordOriginal->fields));
            }

            $db->BeginTrans();
            $MultiSql   = [];
            $sql        = "delete from $TableName where $primary_key = '$id'";
            $db->Execute($sql);
            $MultiSql[] = $sql;
            //Relative Child Table Support
            $Relative_Child_Table                   = $SettingMap['Relative_Child_Table'];
            $Relative_Child_Table_Type              = $SettingMap['Relative_Child_Table_Type'];
            $Relative_Child_Table_Field_Name        = $SettingMap['Relative_Child_Table_Field_Name'];
            $Relative_Child_Table_Parent_Field_Name = $SettingMap['Relative_Child_Table_Parent_Field_Name'];
            if($Relative_Child_Table>0 && $Relative_Child_Table_Parent_Field_Name!="" && in_array($Relative_Child_Table_Parent_Field_Name,$MetaColumnNames)) {
                $ChildSettingMap = returntablefield("form_formflow",'id',$Relative_Child_Table,'Setting')['Setting'];
                $ChildSettingMap = unserialize(base64_decode($ChildSettingMap));
                $ChildFormId                = returntablefield("form_formflow",'id',$Relative_Child_Table,'FormId')['FormId'];
                $ChildTableName             = returntablefield("form_formname",'id',$ChildFormId,'TableName')['TableName'];
                $ChildMetaColumnNames       = GLOBAL_MetaColumnNames($ChildTableName);
                if($Relative_Child_Table_Field_Name!="" && in_array($Relative_Child_Table_Field_Name, $ChildMetaColumnNames) &&strpos($ChildSettingMap['Actions_In_List_Row'],'Edit')!==false) {
                    //Get All Fields

                    $sql                    = "delete from $ChildTableName where $Relative_Child_Table_Parent_Field_Name = '".$RecordOriginal->fields[$Relative_Child_Table_Parent_Field_Name]."';";
                    $db->Execute($sql);
                    $MultiSql[]             = $sql;
                }
            }
            $db->CommitTrans();

            //functionNameIndividual
            $functionNameIndividual = "plugin_".$TableName."_".$Step."_delete_array";
            if(function_exists($functionNameIndividual))  {
                $functionNameIndividual($id);
            }
        }
    }
    $RS = [];
    $RS['status']   = "OK";
    $RS['MultiSql'] = $MultiSql;
    $RS['msg']      = __("Drop Item Success");
    print json_encode($RS);
    exit;
}
?>