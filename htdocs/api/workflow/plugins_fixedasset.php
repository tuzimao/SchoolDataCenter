<?php

function 工作流中固定资产采购申请获得批准之后修改资产明细的状态为采购中()  {
    global $db;
    global $SettingMap;
    global $MetaColumnNames;
    global $GLOBAL_USER;
    global $TableName;
    $FlowId     = intval(DecryptID($_POST['FlowId']));
    $processid  = intval($_POST['processid']);
    $runid      = intval($_POST['runid']);
    if($FlowId > 0 && $processid > 0)      {
        $sql        = "select * from form_formflow where id='$FlowId'";
        $rs         = $db->Execute($sql);
        $FormInfo   = $rs->fields;
        $FormId     = $FormInfo['FormId'];
        $FlowId     = $FormInfo['id'];
        $FlowName   = $FormInfo['FlowName'];
        $Step       = $FormInfo['Step'];
        $Setting    = $FormInfo['Setting'];
        $FaceTo     = $FormInfo['FaceTo'];
        $SettingMap = unserialize(base64_decode($Setting));

        $sql        = "select * from form_formname where id='$FormId'";
        $rs         = $db->Execute($sql);
        $FormInfo   = $rs->fields;
        $FormName   = $FormInfo['FullName'];
        $TableName  = $FormInfo['TableName'];

        $sql        = "select 工作ID from form_flow_run_process where id = '$processid'";
        $rs         = $db->Execute($sql);
        $工作ID     = $rs->fields['工作ID'];
        if($工作ID != "" && $TableName != "")  {
            $sql        = "select * from $TableName where id = '$工作ID' ";
            $rs         = $db->Execute($sql);
            $RecordInfo = $rs->fields;
            //开始----处理主要的业务逻辑部分代码
            $资产采购描述 = $RecordInfo['资产采购描述'];
            if($资产采购描述 != "") {
                $db->BeginTrans();
                $sql    = "update data_fixedasset_in set 最终状态='采购申请通过' where id='$工作ID'";
                $db->Execute($sql);
                $sql    = "update data_fixedasset_in_detail set 采购状态='采购中', 资产采购描述='$资产采购描述' where 资产采购编码='$工作ID'";
                $db->Execute($sql);
                $db->CommitTrans();
            }
            //结束----处理主要的业务逻辑部分代码
        }
    }

    
}

function 工作流中固定资产完成入库流程以后进行的资产入库操作()  {
    global $db;
    global $SettingMap;
    global $MetaColumnNames;
    global $GLOBAL_USER;
    global $TableName;

    $FlowId     = intval(DecryptID($_POST['FlowId']));
    $processid  = intval($_POST['processid']);
    $runid      = intval($_POST['runid']);
    if($FlowId > 0 && $processid > 0)      {
        $sql        = "select * from form_formflow where id='$FlowId'";
        $rs         = $db->Execute($sql);
        $FormInfo   = $rs->fields;
        $FormId     = $FormInfo['FormId'];
        $FlowId     = $FormInfo['id'];
        $FlowName   = $FormInfo['FlowName'];
        $Step       = $FormInfo['Step'];
        $Setting    = $FormInfo['Setting'];
        $FaceTo     = $FormInfo['FaceTo'];
        $SettingMap = unserialize(base64_decode($Setting));

        $sql        = "select * from form_formname where id='$FormId'";
        $rs         = $db->Execute($sql);
        $FormInfo   = $rs->fields;
        $FormName   = $FormInfo['FullName'];
        $TableName  = $FormInfo['TableName'];

        $sql        = "select 工作ID from form_flow_run_process where id = '$processid'";
        $rs         = $db->Execute($sql);
        $工作ID     = $rs->fields['工作ID'];
        if($工作ID != "" && $TableName != "")  {
            $sql        = "select * from $TableName where id = '$工作ID' ";
            $rs         = $db->Execute($sql);
            $RecordInfo = $rs->fields;
            //开始----处理主要的业务逻辑部分代码
            //进行资产入库操作.
            固定资产_采购单_转_入库($RecordInfo['资产入库编码']);
            //结束----处理主要的业务逻辑部分代码
        }
    }

    
}

function 固定资产_采购单_转_入库($资产入库编码) {
    global $db;
    $sql    = "select * from data_fixedasset_in_detail where 资产入库编码='$资产入库编码' and 采购状态='采购完成' and 入库时间=''";//
    $rs     = $db->Execute($sql);
    $rs_a   = $rs->GetArray();
    foreach($rs_a AS $Line)     {
        固定资产_采购明细记录_转_入库($Line['id']);
    }
}

function 固定资产_采购明细记录_转_入库($id)     {
    global $db;
    global $GLOBAL_USER;
    $db->BeginTrans();
    $sql    = "select * from data_fixedasset_in_detail where id='$id' and 采购状态='采购完成' and 入库时间=''";//
    $rs     = $db->Execute($sql);
    $rs_a   = $rs->GetArray();
    foreach($rs_a AS $Line)     {
        //得到最大的资产编码
        $sql        = "select MAX(资产编码) AS 资产编码 from data_fixedasset";
        //print $sql."<BR>";
        $rs         = $db->Execute($sql);
        $资产编码   = intval($rs->fields['资产编码']);
        if($资产编码==0) {
            $资产编码 = 100000;
        }
        $最后五位   = substr($资产编码,-5);
        
        $最后五位   += 1;
        if(strlen($最后五位)==4) $最后五位 = "0".$最后五位;
        if(strlen($最后五位)==3) $最后五位 = "00".$最后五位;
        if(strlen($最后五位)==2) $最后五位 = "000".$最后五位;
        if(strlen($最后五位)==1) $最后五位 = "0000".$最后五位;
        $资产编码   = substr($资产编码,0,-5).$最后五位;
        $Element = [];
        $Element['资产状态'] = "购置未分配";
        $Element['维修状态'] = "正常";
        $Element['资产来源'] = "自购";
        $Element['资产编码'] = $资产编码;
        $Element['资产名称'] = $Line['资产名称'];
        $Element['分类代码'] = $Line['分类代码'];
        $Element['分类名称'] = $Line['分类名称'];
        $Element['数量'] = $Line['数量'];
        $Element['单价'] = $Line['单价'];
        $Element['金额'] = $Line['金额'];
        $Element['单位'] = $Line['单位'];
        $Element['使用方向'] = $Line['使用方向'];
        $Element['供应商名称'] = $Line['供应商名称'];

        $sql = "select * from data_fixedasset_provider where 供应商名称='".ForSqlInjection($Element['供应商名称'])."'";
        //print $sql."<BR>";
        $rsT = $db->Execute($sql);
        $Element['供应商联系人']    = $rsT->fields['供应商联系人'];
        $Element['供应商联系方式']  = $rsT->fields['供应商联系方式'];
        $Element['供应商网站']      = $rsT->fields['供应商网站'];

        $Element['资产采购编码'] = $Line['资产采购编码'];
        $Element['资产入库编码'] = $Line['资产入库编码'];
        $Element['购买方式']    = $Line['购买方式'];
        $Element['创建人']      = $GLOBAL_USER->USER_ID;
        $Element['创建时间']    = date("Y-m-d H:i:s");
        $KEYS = array_keys($Element);
        $VALUES = array_values($Element);
        $sql = "insert into data_fixedasset (".join(',',$KEYS).") values('".join("','",$VALUES)."')";
        //print $sql."<BR>";
        $db->Execute($sql) or print $sql."\n";
        $sql = "update data_fixedasset_in_detail set 入库时间='".date("Y-m-d H:i:s")."', 入库操作员='".$GLOBAL_USER->USER_ID."', 入库状态='已入库' where id='".$Line['id']."' ";
        //print $sql."<BR>";
        $db->Execute($sql);
    }
    $db->CommitTrans();
}
?>