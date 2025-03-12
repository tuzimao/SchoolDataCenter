<?php
require_once('../cors.php');
header('Content-Type: application/json; charset=utf-8');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require_once('../include.inc.php');

CheckAuthUserLoginStatus();

$payload        = file_get_contents('php://input');
$_POST          = json_decode($payload,true);

if($_GET['action'] == 'MyNewWorkflow')      {
    $sql = "select form_formflow.id as FlowId, form_formflow.FlowName, form_formflow.FormId, form_formname.Memo, form_formname.FormGroup from form_formflow, form_formname 
            where 
                form_formflow.NodeType = '工作流' and 
                form_formflow.Step >= '1' and 
                form_formflow.FaceTo = 'AuthUser' and 
                form_formflow.FormId = form_formname.id
            order by 
                form_formflow.Step asc
                ";
    $rs     = $db->Execute($sql);
    $rs_a   = $rs->GetArray();
    //重置数组
    $MAP = [];
    foreach($rs_a as $Item) {
        $Item['FlowId']             = EncryptID($Item['FlowId']);
        $MAP[$Item['FormGroup']][]  = $Item;
    }
    $MAP['办公用品'] = $MAP['资产'];
    $MAP['学生工作'] = $MAP['资产'];
    $MAP['教务管理'] = $MAP['资产'];
    $MAP['迎新迎新'] = $MAP['资产'];
    $MAP['后勤管理'] = $MAP['资产'];

    $RS     = [];
    $RS['data']     = $MAP;
    $RS['status']   = 'ok';
    print_R(json_encode($RS));
    exit;
}

$keyword = $_GET['keyword'];
if($_GET['action'] == 'SearchWorkflow' && $keyword != '')      {
    $sql = "select form_formflow.id as FlowId, form_formflow.FlowName, form_formflow.FormId, form_formname.Memo, form_formname.FormGroup from form_formflow, form_formname 
            where 
                form_formflow.NodeType = '工作流' and 
                form_formflow.Step >= '1' and 
                form_formflow.FaceTo = 'AuthUser' and 
                form_formflow.FormId = form_formname.id and 
                form_formflow.FlowName like '%".$keyword."%'
            order by 
                form_formflow.Step asc
                ";
    $rs     = $db->Execute($sql);
    $rs_a   = $rs->GetArray();
    //重置数组
    $MAP = [];
    foreach($rs_a as $Item) {
        $Item['FlowId']             = EncryptID($Item['FlowId']);
        $MAP[$Item['FormGroup']][]  = $Item;
    }
    $MAP['办公用品'] = $MAP['资产'];
    $MAP['学生工作'] = $MAP['资产'];
    $MAP['教务管理'] = $MAP['资产'];
    $MAP['迎新迎新'] = $MAP['资产'];
    $MAP['后勤管理'] = $MAP['资产'];

    $RS     = [];
    $RS['data']     = $MAP;
    $RS['status']   = 'ok';
    print_R(json_encode($RS));
    exit;
}

$FlowId = intval(DecryptID($_POST['FlowId']));
if($_GET['action'] == 'NewWorkflow' && $FlowId > 0)      {
    $sql        = "select * from form_formflow where id='$FlowId'";
    $rs         = $db->Execute($sql);
    $FromInfo   = $rs->fields;
    $FormId     = $FromInfo['FormId'];
    $FlowId     = $FromInfo['id'];
    $FlowName   = $FromInfo['FlowName'];
    $Step       = $FromInfo['Step'];
    $Setting    = $FromInfo['Setting'];
    $FaceTo     = $FromInfo['FaceTo'];

    $sql        = "select * from form_formname where id='$FormId'";
    $rs         = $db->Execute($sql);
    $FromInfo   = $rs->fields;
    $FormName   = $FromInfo['FullName'];

    //获得ID
    $sql        = "select MAX(id) AS NUM from form_flow_run where FlowId='$FlowId'";
    $rs         = $db->Execute($sql);
    $rs_a       = $rs->GetArray();
    $工作ID     = intval($rs_a[0]['NUM']) + 100000 + 1;
    $工作名称   = "No. ".$工作ID." ".$工作ID."-".$FormName."-".$GLOBAL_USER->USER_NAME;
    
    //我的工作
    $Element = []; 
    $Element['FormId'] = $FormId;
    $Element['FlowId'] = $FlowId;
    $Element['工作ID'] = $工作ID;
    $Element['工作名称'] = $工作名称;
    $Element['发起用户'] = $GLOBAL_USER->USER_ID;
    $Element['发起部门'] = $GLOBAL_USER->DEPT_ID;
    $Element['开始时间'] = date('Y-m-d H:i:s');
    $Element['FormId'] = $FormId;
    [$rs,$sql]      = InsertOrUpdateTableByArray("form_flow_run",$Element,"工作ID,FlowId",0,'Insert');
    $InsertID       = $db->Insert_ID('form_flow_run');
    
    //我的工作-流程申请
    $Element = []; 
    $Element['FormId'] = $FormId;
    $Element['FlowId'] = $FlowId;
    $Element['RunId']  = $InsertID;
    $Element['用户ID'] = $GLOBAL_USER->USER_ID;
    $Element['工作ID'] = $工作ID;
    $Element['工作接收时间']    = date('Y-m-d H:i:s');
    $Element['工作转交办结']    = "";
    $Element['步骤状态']        = "";
    $Element['流程步骤ID']      = $Step;
    $Element['工作创建时间']    = date('Y-m-d H:i:s');
    [$rs,$sql] = InsertOrUpdateTableByArray("form_flow_run_process",$Element,"工作ID,用户ID,流程步骤ID",0,'Insert');

    $data                  = [];
    $data['工作名称']       = $工作名称; 
    $data['表单名称']       = $FormName; 
    $data['步骤名称']       = "主办(第1步： ".$FlowName.")"; 
    $data['工作等级']       = "普通";
    $RS             = [];
    $RS['data']     = $data;
    $RS['status']   = 'ok';
    print_R(json_encode($RS));
    exit;
}






?>