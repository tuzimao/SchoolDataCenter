<?php


function 工作流中修改学生状态为正常状态() {
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
            $学号 = $RecordInfo['学号'];
            $sql = "update data_student set 学生状态 = '正常状态' where 学号='$学号'";
            $db->Execute($sql);
            //结束----处理主要的业务逻辑部分代码
        }
    }
}

function 工作流中修改学生状态为休学状态() {
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
            $学号 = $RecordInfo['学号'];
            $sql = "update data_student set 学生状态 = '休学状态' where 学号='$学号'";
            $db->Execute($sql);
            //结束----处理主要的业务逻辑部分代码
        }
    }
}

function 工作流中修改学生状态为退学状态() {
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
            $学号 = $RecordInfo['学号'];
            $sql = "update data_student set 学生状态 = '退学状态' where 学号='$学号'";
            $db->Execute($sql);
            //结束----处理主要的业务逻辑部分代码
        }
    }
}

function 工作流中执行学生调班操作() {
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
            $学号   = $RecordInfo['学号'];
            $新班级 = $RecordInfo['新班级'];
            $sql    = "update data_student set 班级 = '$新班级' where 学号='$学号'";
            $db->Execute($sql);
            //结束----处理主要的业务逻辑部分代码
        }
    }
}

function 工作流中学生申请助学金最终审核成功处理事件() {
    global $db;
    global $GLOBAL_USER;
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
            $学号 = $RecordInfo['学号'];
            $sql = "update $TableName set 最终审核状态 = '通过',最终审核人 = '".$GLOBAL_USER->USER_ID."',最终审核时间 = '".date("Y-m-d H:i:s")."' where id = '$工作ID' ";
            $db->Execute($sql);
            //结束----处理主要的业务逻辑部分代码
        }
    }
}

function 工作流中学生申请助奖学金最终审核成功处理事件() {
    global $db;
    global $GLOBAL_USER;
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
            $学号 = $RecordInfo['学号'];
            $sql = "update $TableName set 最终审核状态 = '通过',最终审核人 = '".$GLOBAL_USER->USER_ID."',最终审核时间 = '".date("Y-m-d H:i:s")."' where id = '$工作ID' ";
            $db->Execute($sql);
            //结束----处理主要的业务逻辑部分代码
        }
    }
}


?>