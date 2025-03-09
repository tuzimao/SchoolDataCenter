<?php
require_once('../cors.php');
header('Content-Type: application/json; charset=utf-8');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require_once('../include.inc.php');

//CheckAuthUserLoginStatus();

if($_GET['action'] == 'MyNewWorkflow')      {
    $sql = "select form_formflow.FlowName, form_formflow.FormId, form_formname.Memo, form_formname.FormGroup from form_formflow, form_formname 
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
        $MAP[$Item['FormGroup']][] = $Item;
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











?>