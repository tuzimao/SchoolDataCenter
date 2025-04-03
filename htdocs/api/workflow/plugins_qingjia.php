<?php

function 工作流中执行学生请假的处理操作() {
    global $db;
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
            //print $Step;print $FlowName;print_R($RecordInfo);exit;
            //结束----处理主要的业务逻辑部分代码
        }
    }
}

?>