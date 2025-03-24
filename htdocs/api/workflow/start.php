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
                form_formflow.Step = '1' and 
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

$FlowId     = intval(DecryptID($_POST['FlowId']));
$processid  = intval($_POST['processid']);
$runid      = intval($_POST['runid']);
if($_GET['action'] == 'NewWorkflow' && $FlowId > 0 && $processid == 0)      {
    $sql        = "select * from form_formflow where id='$FlowId'";
    $rs         = $db->Execute($sql);
    $FormInfo   = $rs->fields;
    $FormId     = $FormInfo['FormId'];
    $FlowId     = $FormInfo['id'];
    $FlowName   = $FormInfo['FlowName'];
    $Step       = $FormInfo['Step'];
    $Setting    = $FormInfo['Setting'];
    $FaceTo     = $FormInfo['FaceTo'];

    $sql        = "select * from form_formname where id='$FormId'";
    $rs         = $db->Execute($sql);
    $FormInfo   = $rs->fields;
    $FormName       = $FormInfo['FullName'];
    $TableName      = $FormInfo['TableName'];

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
    $Element['发起人'] = $GLOBAL_USER->USER_ID;
    $Element['发起人姓名'] = $GLOBAL_USER->USER_NAME;
    $Element['发起部门'] = $GLOBAL_USER->DEPT_ID;
    $Element['开始时间'] = date('Y-m-d H:i:s');
    $Element['FormId'] = $FormId;
    [$rs,$sql]      = InsertOrUpdateTableByArray("form_flow_run",$Element,"工作ID,FlowId",0,'Insert');
    $InsertID       = $db->Insert_ID('form_flow_run');
    
    //我的工作-流程申请
    $Element = []; 
    $Element['FormId'] = $FormId;
    $Element['FlowId'] = $FlowId;
    $Element['FlowName'] = $FlowName;
    $Element['经办步骤'] = "(第".$Step."步： ".$FlowName.")"; 
    $Element['RunId']  = $InsertID;
    $Element['用户ID'] = $GLOBAL_USER->USER_ID;
    $Element['工作ID'] = $工作ID;
    $Element['工作接收时间']    = date('Y-m-d H:i:s');
    $Element['工作转交办结']    = "";
    $Element['步骤状态']        = "办理中";
    $Element['流程步骤ID']      = $Step;
    $Element['工作创建时间']    = date('Y-m-d H:i:s');
    [$rs,$sql] = InsertOrUpdateTableByArray("form_flow_run_process",$Element,"工作ID,用户ID,流程步骤ID",0,'Insert');

    //业务数据表-初始化一条记录进去
    $DefaultValue['id'] = $工作ID;
    AddOneRecordToTable($TableName, $FormId, $DefaultValue);

    $data                   = [];
    $data['id']             = EncryptID($工作ID); 
    $data['工作ID']         = $工作ID; 
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

$processid  = intval($_POST['processid']);
if($_GET['action'] == 'GetNextApprovalUsers' && $FlowId > 0 && $processid > 0)      {
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

    //得到所有用户信息 
    $sql    = "select id, USER_ID, USER_NAME, DEPT_ID, USER_PRIV from data_user";
    $rs		= $db->Execute($sql);
    $rs_a   = $rs->GetArray();
    $USER_MAP = [];
    $USER_MAP_DEPT = [];
    $USER_MAP_PRIV = [];
    foreach($rs_a as $item) {
        $USER_MAP[$item['USER_ID']] = $item;
        $USER_MAP_DEPT[$item['DEPT_ID']][] = $item;
        $USER_MAP_PRIV[$item['USER_PRIV']][] = $item;
    }

    $NextStep = $SettingMap['NextStep']; 
    if($NextStep != "") {
        $NextStepArray = explode(',', $NextStep);
        $sql        = "select * from form_formflow where step in ('".join("','", $NextStepArray)."') and FormId = '$FormId'";
        $rs         = $db->Execute($sql);
        $rs_a       = $rs->GetArray();
        $下一步骤可选节点 = [];
        foreach($rs_a as $item) {
            $SettingData        = unserialize(base64_decode($item['Setting']));
            $可选节点            = [];
            $NodeFlow_AuthorizedUser = [];
            if($SettingData['NodeFlow_AuthorizedUser'] == null) {
                $NodeFlow_AuthorizedUser = array_values($USER_MAP);
            }
            else {
                $NodeFlow_AuthorizedUser_Array = explode(',', $SettingData['NodeFlow_AuthorizedUser']);
                foreach($NodeFlow_AuthorizedUser_Array as $itemX) {
                    $NodeFlow_AuthorizedUser[] = $USER_MAP[$itemX];
                }
            }

            $NodeFlow_AuthorizedDept = [];
            if($SettingData['NodeFlow_AuthorizedDept'] != null) {
                $NodeFlow_AuthorizedDept_Array = explode(',', $SettingData['NodeFlow_AuthorizedDept']);
                foreach($NodeFlow_AuthorizedDept_Array as $itemX) {
                    $NodeFlow_AuthorizedDept = [...$NodeFlow_AuthorizedDept, ...(array)$USER_MAP_DEPT[$itemX]];
                }
            }
            
            $NodeFlow_AuthorizedRole = [];
            if($SettingData['NodeFlow_AuthorizedRole'] != null) {
                $NodeFlow_AuthorizedRole_Array = explode(',', $SettingData['NodeFlow_AuthorizedRole']);
                foreach($NodeFlow_AuthorizedRole_Array as $itemX) {
                    $NodeFlow_AuthorizedRole = [...$NodeFlow_AuthorizedRole, ...(array)$USER_MAP_PRIV[$itemX]];
                }
            }
            
            //过滤重复数据 
            $ApprovalUsers = [];
            foreach($NodeFlow_AuthorizedUser as $itemF) {
                $ApprovalUsers[$itemF['USER_ID']] = $itemF;
            }
            foreach($NodeFlow_AuthorizedDept as $itemF) {
                $ApprovalUsers[$itemF['USER_ID']] = $itemF;
            }
            foreach($NodeFlow_AuthorizedRole as $itemF) {
                $ApprovalUsers[$itemF['USER_ID']] = $itemF;
            }
            $可选节点['授权允许访问的用户']          = $NodeFlow_AuthorizedUser; 
            $可选节点['授权允许访问的部门']          = $NodeFlow_AuthorizedDept; 
            $可选节点['授权允许访问的角色']          = $NodeFlow_AuthorizedRole;
            $可选节点['NodeFlow_AuthorizedUser']   = array_values($ApprovalUsers); 
            $可选节点['经办步骤']                   = $item['Step'] . " - ". $item['FlowName'];
            $可选节点['经办步骤id']                 = $item['id'];
            $可选节点['经办步骤Step']               = $item['Step'];
            $下一步骤可选节点[] = $可选节点;
        }

    }

    $data               = [];
    $data['下一步骤']    = $下一步骤可选节点; 
    $RS             = [];
    $RS['data']     = $data;
    $RS['status']   = 'ok';
    print_R(json_encode($RS));
    exit;
}


if($_GET['action'] == 'GetMyWorkList')      {

    $pageid     = intval($_POST['pageid']);
    $pageSize   = intval($_POST['pageSize']);
    if($pageSize<=0)    $pageSize   = 15;
    $From   = $pageid * $pageSize;
    $To     = $pageSize;
    $search = FilterString($_POST['search']);
    if($search != "")  {
        $AddSql = " and 工作名称 like '%$search%'";
    }
    else {
        $AddSql = "";
    }

    $sql    = "select count(form_flow_run.id) as NUM from 
                    form_flow_run, form_flow_run_process
                where 
                    form_flow_run.工作ID = form_flow_run_process.工作ID
                    and form_flow_run_process.用户ID='".$GLOBAL_USER->USER_ID."' 
                    $AddSql ";
    $rs     = $db->Execute($sql);
    $rs_a   = $rs->GetArray();
    $totalCount = $rs_a[0]['NUM'];

    $sql    = "select 
                    form_flow_run.id,
                    form_flow_run.FormId,
                    form_flow_run.FlowId,
                    form_flow_run.工作ID,
                    工作名称,开始时间,删除标记,是否归档,工作等级,发起人,发起人姓名,
                    form_flow_run_process.工作接收时间,
                    form_flow_run_process.流程步骤ID ,
                    form_flow_run_process.工作转交办结 ,
                    form_flow_run_process.步骤状态 ,
                    form_flow_run_process.经办步骤 ,
                    form_flow_run_process.id as processid
                from 
                    form_flow_run, form_flow_run_process
                where 
                    form_flow_run.工作ID = form_flow_run_process.工作ID
                    and form_flow_run_process.用户ID='".$GLOBAL_USER->USER_ID."' 
                    $AddSql 
                order by id desc limit $From, $To";
    $rs     = $db->Execute($sql);
    $rs_a   = $rs->GetArray();
    $NewRsa = [];
    foreach($rs_a as $Item) {
        $Item['FlowId'] = EncryptID($Item['FlowId']);
        $Item['工作ID2'] = EncryptID($Item['工作ID']);
        $NewRsa[]       = $Item;
    }
    $RS     = [];
    $RS['sql']          = $sql;
    $RS['data']         = $NewRsa;
    $RS['totalCount']   = $totalCount;
    $RS['status']       = 'ok';
    print_R(json_encode($RS));
    exit;
}


?>