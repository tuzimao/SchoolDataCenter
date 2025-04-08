<?php

//FlowName: 服务评价

function plugin_data_wygl_baoxiuxinxi_6_init_default()  {
    global $db;
    global $SettingMap;
    global $MetaColumnNames;
    global $GLOBAL_USER;
    global $TableName;
    //Here is your write code
}

function plugin_data_wygl_baoxiuxinxi_6_add_default_data_before_submit()  {
    global $db;
    global $SettingMap;
    global $MetaColumnNames;
    global $GLOBAL_USER;
    global $TableName;
    //Here is your write code
}

function plugin_data_wygl_baoxiuxinxi_6_add_default_data_after_submit($id)  {
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

function plugin_data_wygl_baoxiuxinxi_6_edit_default($id)  {
    global $db;
    global $SettingMap;
    global $MetaColumnNames;
    global $GLOBAL_USER;
    global $TableName;
    global $FlowId,$AllShowTypesArray;
    //Here is your write 
    
    $整个页面是否只读   = false;
    $processid = intval($_GET['processid']);
    if($processid > 0) {
        $sql        = "select 步骤状态 from form_flow_run_process where id = '$processid' ";
        $rs         = $db->Execute($sql);
        $步骤状态    = $rs->fields['步骤状态'];
        if($步骤状态 == "办结") {
            //不显示服务评价的代码
            return ;
        }
    }

    //Get All Fields
    $sql                    = "select * from form_configsetting where FlowId='$FlowId' and IsEnable='1' order by SortNumber asc, id asc";
    $rs                     = $db->Execute($sql);
    $AllFieldsFromTable     = $rs->GetArray();
    $defaultValuesEdit      = [];
    $allFieldsEdit          = getAllFields($AllFieldsFromTable, $AllShowTypesArray, 'EDIT', $FilterFlowSetting=false, $SettingMap);
    foreach($allFieldsEdit as $ModeName=>$allFieldItem) {
        foreach($allFieldItem as $ITEM) {
            $defaultValuesEdit[$ITEM['name']] = $ITEM['value'];
        }
    }
    //Value
    $sql  = "select * from data_wygl_weixiupingjia where 维修编号='$id'";
    $rs   = $db->Execute($sql);
    $rs_a = $rs->GetArray();
    foreach($rs_a as $Item) {
        $defaultValuesEdit[$Item['评价名称']] = $Item['评价等级'];
    }

    //print_R($AllShowTypesArray);
    //print $sql;
    $RS['edit_default']['allFields']        = $allFieldsEdit;
    $RS['edit_default']['allFieldsMode']    = [['value'=>"Default", 'label'=>__("")]];
    $RS['edit_default']['defaultValues']    = $defaultValuesEdit;
    $RS['edit_default']['dialogContentHeight']  = "90%";
    $RS['edit_default']['submitaction']     = "edit_default_data";
    $RS['edit_default']['componentsize']    = "small";
    $RS['edit_default']['submittext']       = "";
    $RS['edit_default']['canceltext']       = "";
    $RS['edit_default']['titletext']        = ''; //$SettingMap['Edit_Title_Name'];
    $RS['edit_default']['titlememo']        = '';
    $RS['edit_default']['tablewidth']       = 650;
    $RS['edit_default']['submitloading']    = __("SubmitLoading");
    $RS['edit_default']['loading']          = __("Loading");
    
    $RS['forceuse'] = true;
    $RS['status']   = "OK";
    $RS['data']     = $defaultValuesEdit;
    $RS['sql']      = $sql;
    $RS['msg']      = __("Get Data Success");
    print_R(json_encode($RS, true));
    exit;
}

function plugin_data_wygl_baoxiuxinxi_6_edit_default_data_before_submit($id)  {
    global $db;
    global $SettingMap;
    global $MetaColumnNames;
    global $GLOBAL_USER;
    global $TableName;
    //Here is your write code
    $sql  = "delete from data_wygl_weixiupingjia where 维修编号='$id'";
    $db->Execute($sql);

    $sql = "insert into data_wygl_weixiupingjia(维修编号,评价名称,评价人,评价等级,备注,创建人,创建时间) values ('$id','服务态度','".$GLOBAL_USER->USER_ID."','".$_POST['服务态度']."','备注','".$GLOBAL_USER->USER_ID."','".date('Y-m-d H:i:s')."') ";
    $db->Execute($sql);
    $sql = "insert into data_wygl_weixiupingjia(维修编号,评价名称,评价人,评价等级,备注,创建人,创建时间) values ('$id','维修质量','".$GLOBAL_USER->USER_ID."','".$_POST['维修质量']."','备注','".$GLOBAL_USER->USER_ID."','".date('Y-m-d H:i:s')."') ";
    $db->Execute($sql);
    $sql = "insert into data_wygl_weixiupingjia(维修编号,评价名称,评价人,评价等级,备注,创建人,创建时间) values ('$id','维修结果','".$GLOBAL_USER->USER_ID."','".$_POST['维修结果']."','备注','".$GLOBAL_USER->USER_ID."','".date('Y-m-d H:i:s')."') ";
    $db->Execute($sql);
    $sql = "insert into data_wygl_weixiupingjia(维修编号,评价名称,评价人,评价等级,备注,创建人,创建时间) values ('$id','意见建议','".$GLOBAL_USER->USER_ID."','".$_POST['意见建议']."','','".$GLOBAL_USER->USER_ID."','".date('Y-m-d H:i:s')."') ";
    $db->Execute($sql);

    $sql = "update data_wygl_baoxiuxinxi set 是否评价='是' where id ='$id' ";
    $db->Execute($sql);
    $RS['status']   = "OK";
    $RS['msg']      = $SettingMap['Tip_When_Edit_Success'];
    
    print_R(json_encode($RS, true));
    exit;
}

function plugin_data_wygl_baoxiuxinxi_6_edit_default_data_after_submit($id)  {
    global $db;
    global $SettingMap;
    global $MetaColumnNames;
    global $GLOBAL_USER;
    global $TableName;
    //Here is your write code
}

function plugin_data_wygl_baoxiuxinxi_6_edit_default_data($id)  {
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

function plugin_data_wygl_baoxiuxinxi_6_view_default($id)  {
    global $db;
    global $SettingMap;
    global $MetaColumnNames;
    global $GLOBAL_USER;
    global $TableName;
    //Here is your write code
}

function plugin_data_wygl_baoxiuxinxi_6_delete_array($id)  {
    global $db;
    global $SettingMap;
    global $MetaColumnNames;
    global $GLOBAL_USER;
    global $TableName;
    //Here is your write code
}

function plugin_data_wygl_baoxiuxinxi_6_updateone($id)  {
    global $db;
    global $SettingMap;
    global $MetaColumnNames;
    global $GLOBAL_USER;
    global $TableName;
    //Here is your write code
}

function plugin_data_wygl_baoxiuxinxi_6_import_default_data_before_submit($Element)  {
    global $db;
    global $SettingMap;
    global $MetaColumnNames;
    global $GLOBAL_USER;
    global $TableName;
    //Here is your write code
    return $Element;
}

function plugin_data_wygl_baoxiuxinxi_6_import_default_data_after_submit()  {
    global $db;
    global $SettingMap;
    global $MetaColumnNames;
    global $GLOBAL_USER;
    global $TableName;
    //Here is your write code
}

?>