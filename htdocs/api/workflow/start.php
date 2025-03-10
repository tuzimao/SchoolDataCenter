<?php
require_once('../cors.php');
header('Content-Type: application/json; charset=utf-8');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require_once('../include.inc.php');

//CheckAuthUserLoginStatus();

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
if($_GET['action'] == 'GetWorkflowStep' && $FlowId > 0)      {
    $sql = "select form_formflow.id as FlowId, form_formflow.FlowName, form_formflow.FormId from form_formflow 
            where  id = '$FlowId'
            order by form_formflow.Step asc
            ";
    $rs     = $db->Execute($sql);
    $rs_a   = $rs->GetArray();
    $RS     = [];
    $data   = $rs_a[0];
    $data['Title']      = "No. 100884 100884-课程标准审批表-总务处/信息中心-管理员001"; 
    $data['FormName']   = "广东省高新技术高级技工学校课程标准审批表"; 
    $data['StepName']   = "主办(第1步： 专业负责人发起)"; 
    $data['FlowType']   = "普通";
    $RS['data']     = $data;
    $RS['status']   = 'ok';
    print_R(json_encode($RS));
    exit;
}






?>