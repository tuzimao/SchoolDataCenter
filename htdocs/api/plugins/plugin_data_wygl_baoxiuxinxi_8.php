<?php

//FlowName: 报修信息

function plugin_data_wygl_baoxiuxinxi_8_init_default()  {
    global $db;
    global $SettingMap;
    global $MetaColumnNames;
    global $GLOBAL_USER;
    global $TableName;
    //Here is your write code
}

function plugin_data_wygl_baoxiuxinxi_8_init_default_filter_RS($RS)  {
    global $db;
    global $SettingMap;
    global $MetaColumnNames;
    global $GLOBAL_USER;
    global $TableName;
    //Here is your write code

    return $RS;
}

function plugin_data_wygl_baoxiuxinxi_8_add_default_data_before_submit()  {
    global $db;
    global $SettingMap;
    global $MetaColumnNames;
    global $GLOBAL_USER;
    global $TableName;
    //Here is your write code
}

function plugin_data_wygl_baoxiuxinxi_8_add_default_data_after_submit($id)  {
    global $db;
    global $SettingMap;
    global $MetaColumnNames;
    global $GLOBAL_USER;
    global $TableName;
    //Here is your write code
    /*
    $sql        = "select * from `$TableName` where id = '$id'";
    $rs         = $db->Execute($sql);
    $rs_a       = $rs->GetArray();
    foreach($rs_a as $Line)  {
        //
    }
    */
}

function plugin_data_wygl_baoxiuxinxi_8_edit_default($id)  {
    global $db;
    global $SettingMap;
    global $MetaColumnNames;
    global $GLOBAL_USER;
    global $TableName;
    //Here is your write code
}

function plugin_data_wygl_baoxiuxinxi_8_edit_default_data_before_submit($id)  {
    global $db;
    global $SettingMap;
    global $MetaColumnNames;
    global $GLOBAL_USER;
    global $TableName;
    //Here is your write code
}

function plugin_data_wygl_baoxiuxinxi_8_edit_default_data_after_submit($id)  {
    global $db;
    global $SettingMap;
    global $MetaColumnNames;
    global $GLOBAL_USER;
    global $TableName;
    //Here is your write code
}

function plugin_data_wygl_baoxiuxinxi_8_edit_default_configsetting_data($id)  {
    global $db;
    global $SettingMap;
    global $MetaColumnNames;
    global $GLOBAL_USER;
    global $TableName;
    //Here is your write code
    /*
    $sql        = "select * from `$TableName` where id = '$id'";
    $rs         = $db->Execute($sql);
    $rs_a       = $rs->GetArray();
    foreach($rs_a as $Line)  {
        //
    }
    */
}

function plugin_data_wygl_baoxiuxinxi_8_view_default($id)  {
    global $db;
    global $SettingMap;
    global $MetaColumnNames;
    global $GLOBAL_USER;
    global $TableName;
    //Here is your write code
}

function plugin_data_wygl_baoxiuxinxi_8_delete_array($id)  {
    global $db;
    global $SettingMap;
    global $MetaColumnNames;
    global $GLOBAL_USER;
    global $TableName;
    //Here is your write code
}

function plugin_data_wygl_baoxiuxinxi_8_updateone($id)  {
    global $db;
    global $SettingMap;
    global $MetaColumnNames;
    global $GLOBAL_USER;
    global $TableName;
    //Here is your write code
}

function plugin_data_wygl_baoxiuxinxi_8_import_default_data_before_submit($Element)  {
    global $db;
    global $SettingMap;
    global $MetaColumnNames;
    global $GLOBAL_USER;
    global $TableName;
    //Here is your write code
    return $Element;
}

function plugin_data_wygl_baoxiuxinxi_8_import_default_data_after_submit()  {
    global $db;
    global $SettingMap;
    global $MetaColumnNames;
    global $GLOBAL_USER;
    global $TableName;
    //Here is your write code
}

?>
