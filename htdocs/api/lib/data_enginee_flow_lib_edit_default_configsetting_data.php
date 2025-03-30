<?php
//禁止当前文件直接在URL中访问
$CurrentUrlFileName = basename($_SERVER['PHP_SELF']);
$ForbiddenAccessUrlList = ['data_enginee_flow_lib_edit_default_configsetting_data.php'];
if(in_array($CurrentUrlFileName, $ForbiddenAccessUrlList)) exit;

if( $_GET['action']=="edit_default_configsetting_data" && $SettingMap['Init_Action_Value']=="edit_default_configsetting" && $FlowId!="")  {
    //functionNameIndividual
    $functionNameIndividual = "plugin_".$TableName."_".$Step."_edit_default_configsetting_data";
    if(function_exists($functionNameIndividual))  {
        $functionNameIndividual($NewId);
    }
    //$id = DecryptID($_GET['id']);
    $ConfigSetting = base64_encode(serialize($_POST));
    $sql = "update form_formflow set ConfigSetting='$ConfigSetting' where id='$FlowId'";
    $db->Execute($sql);
    $RS = [];
    $RS['status'] = "OK";
    $RS['msg'] = $SettingMap['Tip_When_Edit_Success'];
    if($SettingMap['Debug_Sql_Show_On_Api']=="Yes" && in_array($GLOBAL_USER->USER_ID, ['admin', 'admin001']))  $RS['sql'] = $sql;
    if($SettingMap['Debug_Sql_Show_On_Api']=="Yes" && in_array($GLOBAL_USER->USER_ID, ['admin', 'admin001']))  $RS['_GET'] = $_GET;
    if($SettingMap['Debug_Sql_Show_On_Api']=="Yes" && in_array($GLOBAL_USER->USER_ID, ['admin', 'admin001']))  $RS['_POST'] = $_POST;
    print json_encode($RS);
    exit;
}
?>