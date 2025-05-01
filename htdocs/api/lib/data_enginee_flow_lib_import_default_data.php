<?php
//禁止当前文件直接在URL中访问
$CurrentUrlFileName = basename($_SERVER['PHP_SELF']);
$ForbiddenAccessUrlList = ['data_enginee_flow_lib_import_default_data.php'];
if(in_array($CurrentUrlFileName, $ForbiddenAccessUrlList)) exit;

if( $_GET['action']=="import_default_data" && in_array('Import',$Actions_In_List_Header_Array) && $TableName!="")  {

    //Filter data when do add save operation
    require_once('data_enginee_filter_post.php');
    $MetaColumnNames    = GLOBAL_MetaColumnNames($TableName);

    $filePath = $_FILES['Import_File']['tmp_name']['0'];
    if(!is_file($filePath))  {
        $RS             = [];
        $RS['status']   = "ERROR";
        $RS['msg']      = __("Upload File Not Exist");
        $RS['data']     = $data;
        print json_encode($RS);
        exit;
    }

    //Read Data From Excel
    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
    $worksheet = $spreadsheet->getActiveSheet();
    $highestRow = $worksheet->getHighestRow();
    $highestColumn = $worksheet->getHighestColumn();
    $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);
    $data = [];
    for ($row = 1; $row <= $highestRow; $row++) {
        $rowData = [];
        for ($col = 1; $col <= $highestColumnIndex; $col++) {
            $cellValue = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
            $rowData[] = trim($cellValue);
        }
        $data[] = $rowData;
    }
    $Header         = $data[0];
    $FieldToIndex   = array_flip($Header);

    //Import Parse Data
    $Import_Fields_Unique_1 = $SettingMap['Import_Fields_Unique_1'];
    $Import_Fields_Unique_2 = $SettingMap['Import_Fields_Unique_2'];
    $Import_Fields_Unique_3 = $SettingMap['Import_Fields_Unique_3'];
    $ImportUniqueFields = [];
    if($Import_Fields_Unique_1!="Disabled" && $Import_Fields_Unique_1!="" && $Import_Fields_Unique_1!="id")  {
        $ImportUniqueFields[] = $Import_Fields_Unique_1;
    }
    if($Import_Fields_Unique_2!="Disabled" && $Import_Fields_Unique_2!="" && $Import_Fields_Unique_2!="id")  {
        $ImportUniqueFields[] = $Import_Fields_Unique_2;
    }
    if($Import_Fields_Unique_3!="Disabled" && $Import_Fields_Unique_3!="" && $Import_Fields_Unique_3!="id")  {
        $ImportUniqueFields[] = $Import_Fields_Unique_3;
    }
    if(sizeof($ImportUniqueFields)==0)   {
        $RS             = [];
        $RS['status']   = "OK";
        $RS['msg']      = __("Import Unique Fields Not Config");
        print json_encode($RS);
        exit;
    }
    //Body Data
    $Import_Fields_Array = explode(',', ForSqlInjection($_POST['Import_Fields']));
    for ($row = 1; $row < sizeof($data); $row++) {
        $Element        = [];
        $IsExecutionSQL = 0;
        for ($column = 0; $column < sizeof($Header); $column++)         {
            $FieldName  = $LocaleFieldArray[$Header[$column]];
            if( in_array($FieldName, $MetaColumnNames) && in_array($FieldName,$Import_Fields_Array))  {
                $Element[$FieldName] = trim($data[$row][$column]);
                if($Element[$FieldName]!="")   {
                    $IsExecutionSQL = 1;
                }
                //Decrypt Field Value
                $SettingTempMap                 = $AllFieldsMap[$FieldName]['Setting'];
                $DataFieldEncryptMethod         = $SettingTempMap['DataFieldEncryptMethod'];
                $DataFieldEncryptKey            = $SettingTempMap['DataFieldEncryptKey'];
                if($DataFieldEncryptMethod==1&&$DataFieldEncryptKey!="") {
                    $Element[$FieldName]        = EncryptIDStorage($Element[$FieldName], $DataFieldEncryptKey);
                }
            }
        }
        if(sizeof(array_keys($Element))<=sizeof($ImportUniqueFields)) {
            $RS             = [];
            $RS['status']   = "ERROR";
            $RS['msg']      = __("Import Fields Is Too Less");
            if($SettingMap['Debug_Sql_Show_On_Api']=="Yes" && in_array($GLOBAL_USER->USER_ID, ['admin', 'admin001']))  $RS['_GET']     = $_GET;
            if($SettingMap['Debug_Sql_Show_On_Api']=="Yes" && in_array($GLOBAL_USER->USER_ID, ['admin', 'admin001']))  $RS['_POST']    = $_POST;
            if($SettingMap['Debug_Sql_Show_On_Api']=="Yes" && in_array($GLOBAL_USER->USER_ID, ['admin', 'admin001']))  $RS['_FILES']   = $_FILES;
            if($SettingMap['Debug_Sql_Show_On_Api']=="Yes" && in_array($GLOBAL_USER->USER_ID, ['admin', 'admin001']))  $RS['sql']      = $sqlList;
            print json_encode($RS);
            exit;
        }
        if($IsExecutionSQL)    {
            //functionNameIndividual
            $functionNameIndividual = "plugin_".$TableName."_".$Step."_import_default_data_before_submit";
            if(function_exists($functionNameIndividual))  {
                $Element = $functionNameIndividual($Element);
            }

            $Import_Rule_Method = ForSqlInjection($_POST['Import_Rule_Method']);
            switch($Import_Rule_Method) {
                case 'BothInsertAndUpdate':
                    [$rs,$sql] = InsertOrUpdateTableByArray($TableName,$Element,join(',',$ImportUniqueFields),0,'InsertOrUpdate');
                    $sqlList[] = $sql;
                    break;
                case 'OnlyUpdate':
                    [$rs,$sql] = InsertOrUpdateTableByArray($TableName,$Element,join(',',$ImportUniqueFields),0,'Update');
                    $sqlList[] = $sql;
                    break;
                case 'OnlyInsert':
                    [$rs,$sql] = InsertOrUpdateTableByArray($TableName,$Element,join(',',$ImportUniqueFields),0,'Insert');
                    $sqlList[] = $sql;
                    break;
            }
            if($rs->EOF) {
            }
        }
        else {
            //Empty Row
        }
    }

    //functionNameIndividual
    $functionNameIndividual = "plugin_".$TableName."_".$Step."_import_default_data_after_submit";
    if(function_exists($functionNameIndividual))  {
        $functionNameIndividual();
    }

    if(1)   {
        $RS             = [];
        $RS['status']   = "OK";
        $RS['msg']      = __("Import Data Success");
        if($SettingMap['Debug_Sql_Show_On_Api']=="Yes" && in_array($GLOBAL_USER->USER_ID, ['admin', 'admin001']))  $RS['_GET']     = $_GET;
        if($SettingMap['Debug_Sql_Show_On_Api']=="Yes" && in_array($GLOBAL_USER->USER_ID, ['admin', 'admin001']))  $RS['_POST']    = $_POST;
        if($SettingMap['Debug_Sql_Show_On_Api']=="Yes" && in_array($GLOBAL_USER->USER_ID, ['admin', 'admin001']))  $RS['_FILES']   = $_FILES;
        if($SettingMap['Debug_Sql_Show_On_Api']=="Yes" && in_array($GLOBAL_USER->USER_ID, ['admin', 'admin001']))  $RS['sql']      = $sqlList;
        $RS['counter']  = sizeof($data);
        print json_encode($RS);
        exit;
    }

}

?>