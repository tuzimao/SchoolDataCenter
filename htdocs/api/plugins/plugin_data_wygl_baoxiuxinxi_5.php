<?php

//FlowName: 确认维修

function plugin_data_wygl_baoxiuxinxi_5_init_default()  {
    global $db;
    global $SettingMap;
    global $MetaColumnNames;
    global $GLOBAL_USER;
    global $TableName;
    //Here is your write code
    global $AddSql, $USER_ID;
    //Here is your write code
    $sql        = "select * from data_wygl_biaoxiuxiangmu where find_in_set('$USER_ID',维修人员)";
    $rs         = $db->CacheExecute(10,$sql);
    $rs_a       = $rs->GetArray();
    $报修项目Array = [];
    $TopRightOptions = [];
    foreach($rs_a as $Line) {
        $报修项目Array[]    = ForSqlInjection($Line['名称']);
    }
    global $AddSql;
    $AddSql .= " and 报修项目 in ('".join("','",$报修项目Array)."')";
    $AddSql .= " and 维修人员 ='$USER_ID'";
}

function plugin_data_wygl_baoxiuxinxi_5_add_default_data_before_submit()  {
    global $db;
    global $SettingMap;
    global $MetaColumnNames;
    global $GLOBAL_USER;
    global $TableName;
    //Here is your write code
}

function plugin_data_wygl_baoxiuxinxi_5_add_default_data_after_submit($id)  {
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

function plugin_data_wygl_baoxiuxinxi_5_edit_default($id)  {
    global $db;
    global $SettingMap;
    global $MetaColumnNames;
    global $GLOBAL_USER;
    global $TableName;
    //Here is your write code
}

function plugin_data_wygl_baoxiuxinxi_5_edit_default_data_before_submit($id)  {
    global $db;
    global $SettingMap;
    global $MetaColumnNames;
    global $GLOBAL_USER;
    global $TableName;
    //Here is your write code
}

function plugin_data_wygl_baoxiuxinxi_5_edit_default_data_after_submit($id)  {
    global $db;
    global $SettingMap;
    global $MetaColumnNames;
    global $GLOBAL_USER;
    global $TableName;
    //Here is your write code
	
    $报修编号 = $id;
    $sql    = "delete from data_wygl_yongliaodengji where 报修编号='$报修编号'";
    $db->Execute($sql);
    $sql    = "select * from data_wygl_peijian";
    $rs     = $db->Execute($sql);
    $rs_a   = $rs->GetArray();
    $用料配件MAP = [];
    foreach($rs_a as $Item) {
        $用料配件MAP[$Item['物品名称']] = $Item;
    }
    $费用结算 = 0;
    $用料登记Array = explode(',',$_POST['用料登记']);
    foreach($用料登记Array as $Item) {
        $sql = "insert into data_wygl_yongliaodengji(报修编号, 所需配件, 数量, 单价, 总价, 配件编号) values('$报修编号', '$Item', '1', '".$用料配件MAP[$Item]['单价']."', '".$用料配件MAP[$Item]['单价']."', '".$用料配件MAP[$Item]['id']."');";
        $db->Execute($sql);
        $费用结算 += $用料配件MAP[$Item]['单价'];
    }
    $sql = "update data_wygl_baoxiuxinxi set 费用结算='$费用结算' where id ='$id' ";
    $db->Execute($sql);	
}

function plugin_data_wygl_baoxiuxinxi_5_edit_default_configsetting_data($id)  {
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

function plugin_data_wygl_baoxiuxinxi_5_view_default($id)  {
    global $db;
    global $SettingMap;
    global $MetaColumnNames;
    global $GLOBAL_USER;
    global $TableName;
    //Here is your write code
}

function plugin_data_wygl_baoxiuxinxi_5_delete_array($id)  {
    global $db;
    global $SettingMap;
    global $MetaColumnNames;
    global $GLOBAL_USER;
    global $TableName;
    //Here is your write code
}

function plugin_data_wygl_baoxiuxinxi_5_updateone($id)  {
    global $db;
    global $SettingMap;
    global $MetaColumnNames;
    global $GLOBAL_USER;
    global $TableName;
    //Here is your write code
}

function plugin_data_wygl_baoxiuxinxi_5_import_default_data_before_submit($Element)  {
    global $db;
    global $SettingMap;
    global $MetaColumnNames;
    global $GLOBAL_USER;
    global $TableName;
    //Here is your write code
    return $Element;
}

function plugin_data_wygl_baoxiuxinxi_5_import_default_data_after_submit()  {
    global $db;
    global $SettingMap;
    global $MetaColumnNames;
    global $GLOBAL_USER;
    global $TableName;
    //Here is your write code
}

?>