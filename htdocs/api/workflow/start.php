<?php
require_once('../cors.php');
header('Content-Type: application/json; charset=utf-8');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require_once('../include.inc.php');

CheckAuthUserLoginStatus();

if($GLOBAL_USER->type == 'Student') {
    $FaceTo = "Student";
}
else {
    $FaceTo = "AuthUser";
}

$payload        = file_get_contents('php://input');
$_POST          = json_decode($payload,true);

if($_GET['action'] == 'MyNewWorkflow')       {
    $sql = "select form_formflow.id as FlowId, form_formflow.FlowName, form_formflow.FormId, form_formname.Memo, form_formname.FormGroup from form_formflow, form_formname 
            where 
                form_formflow.NodeType = '工作流' and 
                form_formflow.IsStartNode = 'Yes' and 
                form_formflow.FaceTo = '$FaceTo' and 
                form_formflow.FormId = form_formname.id
            order by 
                form_formname.id asc, form_formflow.Step asc
                ";
    $rs     = $db->Execute($sql);
    $rs_a   = $rs->GetArray();
    //重置数组
    $MAP = [];
    foreach($rs_a as $Item) {
        $Item['FlowId']             = EncryptID($Item['FlowId']);
        $MAP[$Item['FormGroup']][]  = $Item;
    }

    $RS     = [];
    $RS['data']     = $MAP;
    $RS['status']   = 'ok';
    print_R(json_encode($RS));
    exit;
}

$keyword = $_GET['keyword'];
if($_GET['action'] == 'SearchWorkflow')      {
    if( $keyword != '')  {
        $AddSql = "and form_formflow.FlowName like '%".$keyword."%'";
    }
    else {
        $AddSql = "";
    }
    $sql = "select form_formflow.id as FlowId, form_formflow.FlowName, form_formflow.FormId, form_formname.Memo, form_formname.FormGroup from form_formflow, form_formname 
            where 
                form_formflow.NodeType = '工作流' and 
                form_formflow.IsStartNode = 'Yes' and 
                form_formflow.FaceTo = '$FaceTo' and 
                form_formflow.FormId = form_formname.id 
                $AddSql
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

    $RS     = [];
    $RS['data']     = $MAP;
    $RS['status']   = 'ok';
    print_R(json_encode($RS));
    exit;
}

$FlowId     = intval(DecryptID($_POST['FlowId']));
$processid  = intval($_POST['processid']);
$runid      = intval($_POST['runid']);
if($_GET['action'] == 'NewWorkflow' && $FlowId > 0 && $processid == 0)              {
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
    $StepName   = $SettingMap['StepName'];
    if($StepName == null) $StepName = $Step;

    //加载工作流插件
    require_once('plugins.php');
    $NodeFlow_Approval_Execute_Function = $SettingMap['NodeFlow_Approval_Execute_Function']; 
    if(function_exists($NodeFlow_Approval_Execute_Function))  {
        $NodeFlow_Approval_Execute_Function();
    }

    $sql        = "select * from form_formname where id='$FormId'";
    $rs         = $db->Execute($sql);
    $FormInfo   = $rs->fields;
    $FormName       = $FormInfo['FullName'];
    $TableName      = $FormInfo['TableName'];

    //获得ID
    $sql        = "select MAX(id) AS NUM from form_flow_run";
    $rs         = $db->Execute($sql);
    $rs_a       = $rs->GetArray();
    $工作ID     = intval($rs_a[0]['NUM']) + 100000 + 1;
    $工作名称   = "No. ".$工作ID." ".$FormName."-".$GLOBAL_USER->USER_NAME;

    //业务数据表-初始化一条记录进去
    $DefaultValue['id'] = $工作ID;
    AddOneRecordToTable($TableName, $FormId, $FlowId, $DefaultValue);
    
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
    [$rs,$sql]  = InsertOrUpdateTableByArray("form_flow_run",$Element,"工作ID,FlowId",0,'Insert');
    $RunId      = $db->Insert_ID('form_flow_run');
    
    //我的工作-流程申请
    $Element = []; 
    $Element['FormId'] = $FormId;
    $Element['FlowId'] = $FlowId;
    $Element['FlowName'] = $FlowName;
    $Element['经办步骤'] = "(第".$StepName."步： ".$FlowName.")"; 
    $Element['RunId']  = $RunId;
    $Element['用户ID'] = $GLOBAL_USER->USER_ID;
    $Element['工作ID'] = $工作ID;
    $Element['工作接收时间']    = date('Y-m-d H:i:s');
    $Element['工作转交办结']    = "";
    $Element['步骤状态']        = "未办理";
    $Element['流程步骤ID']      = $Step;
    $Element['工作创建时间']    = date('Y-m-d H:i:s');
    [$rs,$sql]  = InsertOrUpdateTableByArray("form_flow_run_process",$Element,"工作ID,用户ID,流程步骤ID",0,'Insert');
    $ProcessId  = $db->Insert_ID('form_flow_run_process');

    $data                   = [];
    $data['id']             = EncryptID($工作ID); 
    $data['工作ID']         = $工作ID; 
    $data['processid']      = $ProcessId;
    $data['runid']          = $RunId; 
    $data['工作名称']       = $工作名称; 
    $data['表单名称']       = $FormName; 
    $data['步骤名称']       = "主办(第".$StepName."步： ".$FlowName.")";
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

    $sql        = "select * from form_formname where id='$FormId'";
    $rs         = $db->Execute($sql);
    $FormInfo   = $rs->fields;
    $TableName  = $FormInfo['TableName'];

    //得到所有用户信息 
    $sql    = "select id, USER_ID, USER_NAME, DEPT_ID, USER_PRIV, USER_ID as value, USER_NAME as label from data_user";
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

    //个性化代码-非通用代码-网上报修-根据楼房属性来判断流程走向-开始
    if(($Step == 1 || $Step == 8) && $TableName == "data_wygl_baoxiuxinxi")  {
        $sql        = "select 工作ID from form_flow_run_process where id = '$processid'";
        $rs         = $db->Execute($sql);
        $工作ID     = $rs->fields['工作ID'];
        $sql = "select 楼房属性, 楼房名称 from $TableName where id = '$工作ID' ";
        $rs         = $db->Execute($sql);
        $涉及记录    = $rs->fields;
        $楼房属性    = $涉及记录['楼房属性'];
        if($楼房属性 == "学生宿舍")  {
            $NextStep = 2;
        }
        else {
            $NextStep = 3;
        }
    }
    //个性化代码-非通用代码-网上报修-根据楼房属性来判断流程走向-结束

    if($NextStep != "" && $NextStep != "[结束]")    {
        $NextStepArray = explode(',', $NextStep);
        $sql        = "select * from form_formflow where step in ('".join("','", $NextStepArray)."') and FormId = '$FormId'";
        $rs         = $db->Execute($sql);
        $rs_a       = $rs->GetArray();
        $下一步骤可选节点 = [];
        $下一步骤可选节点序号 = [];
        foreach($rs_a as $item)             {

            $可选节点                    = [];
            $NodeFlow_AuthorizedUser    = [];

            $SettingData    = unserialize(base64_decode($item['Setting']));
            $StepName       = $SettingData['StepName'];
            if($StepName == null) $StepName = $item['Step'];

            //目标节点的前置条件判断
            $目标节点的前置条件判断 = true;
            $NodeFlow_Authorized_Requirement_Array = explode(',', $SettingData['NodeFlow_Authorized_Requirement']);
            foreach($NodeFlow_Authorized_Requirement_Array as $NodeFlow_Authorized_Requirement_Item) {
                $NodeFlow_Authorized_Requirement_Item_Array = explode(':', $NodeFlow_Authorized_Requirement_Item);
                $规则名称 = $NodeFlow_Authorized_Requirement_Item_Array[0];
                $规则的值1 = $NodeFlow_Authorized_Requirement_Item_Array[1];
                $规则的值2 = $NodeFlow_Authorized_Requirement_Item_Array[2];
                switch($规则名称) {
                    case '发起人部门条件限制':
                        $部门名称 = $GLOBAL_USER->DEPT_NAME;
                        switch($规则的值1) {
                            case '系部':
                                if(substr($部门名称, -2) != '系') {
                                    $目标节点的前置条件判断 = false; //此值默认为true, 所以只需要记录为false的情况
                                }
                                break;
                            case '非系部':
                                if(substr($部门名称, -2) == '系') {
                                    $目标节点的前置条件判断 = false; //此值默认为true, 所以只需要记录为false的情况
                                }
                                break;
                        }
                        break;
                    case '表单字段限制':
                        $sql        = "select 工作ID from form_flow_run_process where id = '$processid'";
                        $rs         = $db->Execute($sql);
                        $工作ID     = $rs->fields['工作ID'];
                        if($工作ID != "" && $TableName != "")  {
                            $sql        = "select * from $TableName where id = '$工作ID'";
                            $rs         = $db->Execute($sql);
                            $表单数据 = $rs->fields;
                            //匹配指定的字段在表单中是否有值
                            $匹配指定的字段在表单中的值 = $表单数据[$规则的值1];
                            //判断条件
                            if(substr($规则的值2, 0, 2) == '!=')  {
                                $规则的值2Value = substr($规则的值2, 2, strlen($规则的值2));
                                //判断 != 的情况, 但是只记录合法的记录, 所以判断要使用 ==
                                if($规则的值2Value == $匹配指定的字段在表单中的值)  {
                                    $目标节点的前置条件判断 = false; //此值默认为true, 所以只需要记录为false的情况
                                }
                            }
                            //判断 == 的情况
                            else {
                                //判断 == 的情况, 但是只记录不合法的记录, 所以判断要使用 !=
                                if($规则的值2 != $匹配指定的字段在表单中的值)  {
                                    $目标节点的前置条件判断 = false; //此值默认为true, 所以只需要记录为false的情况
                                }
                            }
                        }
                        break;
                }
            }
            if($目标节点的前置条件判断 == false)  {
                break;
            }

            $Page_Role_Name = $SettingData['Page_Role_Name'];
            if($Page_Role_Name == "ClassMaster")    {
                //额外限制权限为: 班主任
                $sql        = "select 工作ID from form_flow_run_process where id = '$processid'";
                $rs         = $db->Execute($sql);
                $工作ID     = $rs->fields['工作ID'];
                if($工作ID != "" && $TableName != "")  {
                    $sql = "select * from $TableName where id = '$工作ID' ";
                    $rs         = $db->Execute($sql);
                    $涉及记录    = $rs->fields;
                    $班级       = $涉及记录['班级'];
                    if($班级 == "" && $涉及记录['班级名称'] != "" ) $班级   = $涉及记录['班级名称'];
                    if($班级 == "" && $涉及记录['学生班级'] != "" ) $班级   = $涉及记录['学生班级'];
                    $班主任用户名 = returntablefield("data_banji", "班级名称", $班级, "班主任用户名")['班主任用户名'];
                    if($班主任用户名 != "")  {
                        $NodeFlow_AuthorizedUser[] = $USER_MAP[$班主任用户名];
                    }
                }
            }
            if($Page_Role_Name == "Dormitory")      {
                //额外限制权限为: 宿舍管理员
                $sql        = "select 工作ID from form_flow_run_process where id = '$processid'";
                $rs         = $db->Execute($sql);
                $工作ID     = $rs->fields['工作ID'];
                if($工作ID != "" && $TableName == "data_wygl_baoxiuxinxi")  {
                    $sql = "select 楼房属性, 楼房名称 from $TableName where id = '$工作ID' ";
                    $rs         = $db->Execute($sql);
                    $涉及记录    = $rs->fields;
                    $楼房属性    = $涉及记录['楼房属性'];
                    $楼房名称    = $涉及记录['楼房名称'];
                    if($楼房属性 == "学生宿舍" && $楼房名称 != "")  {
                        $生管老师 = returntablefield("data_dorm_building", "宿舍楼名称", $楼房名称, "生管老师一,生管老师二,生管老师三,生管老师四,生管老师五,生管老师六,生管老师七,生管老师八,生管老师九,生管老师十");
                        $生管老师VALUES = array_values($生管老师);
                        $生管老师KEYS   = array_flip($生管老师VALUES);
                        foreach($生管老师KEYS as $生管老师KEY => $NOT_USE) {
                            if($生管老师KEY != "") {
                                $NodeFlow_AuthorizedUser[] = $USER_MAP[$生管老师KEY];
                            }
                        }
                    }
                }
            }
            $Faculty_Filter_Field = $SettingData['Faculty_Filter_Field'];
            if($Page_Role_Name == "Faculty" && $Faculty_Filter_Field != "")     {
                //额外限制权限为: 院系
                $sql        = "select 工作ID from form_flow_run_process where id = '$processid'";
                $rs         = $db->Execute($sql);
                $工作ID     = $rs->fields['工作ID'];
                if($工作ID != "" && $TableName != "")  {
                    $sql = "select * from $TableName where id = '$工作ID' ";
                    $rs         = $db->Execute($sql);
                    $涉及记录    = $rs->fields;
                    $班级       = $涉及记录['班级'];
                    if($班级 == "" && $涉及记录['班级名称'] != "" ) $班级   = $涉及记录['班级名称'];
                    if($班级 == "" && $涉及记录['学生班级'] != "" ) $班级   = $涉及记录['学生班级'];
                    $所属系部 = returntablefield("data_banji", "班级名称", $班级, "所属系部")['所属系部'];
                    if($所属系部 != "")  {
                        $系部管理员Text = returntablefield("data_xi", "系部名称", $所属系部, $Faculty_Filter_Field)[$Faculty_Filter_Field];
                        if($系部管理员Text != "" )  {
                            $系部管理员Array = explode(',', $系部管理员Text);
                            foreach($系部管理员Array as $用户名) {
                                $NodeFlow_AuthorizedUser[] = $USER_MAP[$用户名];
                            }
                        }
                    }
                }
            }
            //网上报修-维修组长派单-维修组长列表
            if($item['FlowName'] == "维修组长派单")  {
                $sql        = "select 工作ID from form_flow_run_process where id = '$processid'";
                $rs         = $db->Execute($sql);
                $工作ID     = $rs->fields['工作ID'];
                if($工作ID != "" && $TableName != "")  {
                    $sql = "select 负责人,维修人员 from data_wygl_biaoxiuxiangmu ";
                    $rs         = $db->Execute($sql);
                    $涉及记录    = $rs->GetArray();
                    $负责人     = [];
                    foreach($涉及记录 as $List) {
                        $负责人[] = $List['负责人'];
                    }
                    $负责人TEXT  = join(',', $负责人);
                    $负责人Array = explode(',', $负责人TEXT);
                    $负责人Flip  = array_flip($负责人Array);
                    foreach($负责人Flip as $List=>$NOT_USE)   {
                        if($List != '' && is_array($USER_MAP[$List]) )     {
                            $NodeFlow_AuthorizedUser[] = $USER_MAP[$List];                            
                        }
                    }
                }
            }
            //网上报修-确认维修-得到维修人员列表
            if($item['FlowName'] == "确认维修")  {
                $sql        = "select 工作ID from form_flow_run_process where id = '$processid'";
                $rs         = $db->Execute($sql);
                $工作ID     = $rs->fields['工作ID'];
                if($工作ID != "" && $TableName != "")  {
                    $sql = "select 报修项目 from $TableName where id = '$工作ID' ";
                    $rs         = $db->Execute($sql);
                    $报修项目    = $rs->fields['报修项目'];
                    $sql = "select 负责人,维修人员 from data_wygl_biaoxiuxiangmu where 名称 = '$报修项目'";
                    $rs         = $db->Execute($sql);
                    $涉及记录    = $rs->GetArray();
                    $负责人Array = explode(',', $涉及记录[0]['维修人员']);
                    $负责人Flip  = array_flip($负责人Array);
                    foreach($负责人Flip as $List=>$NOT_USE)   {
                        if($List != '' && is_array($USER_MAP[$List]) )     {
                            $NodeFlow_AuthorizedUser[] = $USER_MAP[$List];                            
                        }
                    }
                }
            }
            //网上报修-服务评价-返回发起人
            if($item['FlowName'] == "服务评价")  {
                $sql        = "select 工作ID from form_flow_run_process where id = '$processid'";
                $rs         = $db->Execute($sql);
                $工作ID     = $rs->fields['工作ID'];
                if($工作ID != "" && $TableName != "")  {
                    $sql = "select 报修人,学生学号 from $TableName where id = '$工作ID' ";
                    $rs         = $db->Execute($sql);
                    $报修人      = $rs->fields['报修人'];
                    $学生学号    = $rs->fields['学生学号'];
                    if($报修人 != '' && is_array($USER_MAP[$报修人]) )     {
                        $NodeFlow_AuthorizedUser[] = $USER_MAP[$报修人];                            
                    }
                    if($学生学号 != '')     {
                        $NodeFlow_AuthorizedUser[] = returntablefield("data_student", "学号", $学生学号, "id, 学号 as USER_ID, 姓名 as USER_NAME, 学号 as value, 姓名 as label, 班级");
                    }
                }
            }
            
            //手动指定审核人
            if($SettingData['NodeFlow_AuthorizedUser'] != null && $SettingData['NodeFlow_AuthorizedUser'] != "") {
                $NodeFlow_AuthorizedUser_Array = explode(',', $SettingData['NodeFlow_AuthorizedUser']);
                foreach($NodeFlow_AuthorizedUser_Array as $itemX) {
                    $NodeFlow_AuthorizedUser[] = $USER_MAP[$itemX];
                }
            }

            $NodeFlow_AuthorizedDept = [];
            if($SettingData['NodeFlow_AuthorizedDept'] != null && $SettingData['NodeFlow_AuthorizedDept'] != "") {
                $NodeFlow_AuthorizedDept_Array = explode(',', $SettingData['NodeFlow_AuthorizedDept']);
                foreach($NodeFlow_AuthorizedDept_Array as $itemX) {
                    $NodeFlow_AuthorizedDept = [...$NodeFlow_AuthorizedDept, ...(array)$USER_MAP_DEPT[$itemX]];
                }
            }
            
            $NodeFlow_AuthorizedRole = [];
            if($SettingData['NodeFlow_AuthorizedRole'] != null && $SettingData['NodeFlow_AuthorizedRole'] != "") {
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
            $可选节点['经办步骤']                   = "(第".$StepName."步： ".$item['FlowName'].")";
            $可选节点['经办步骤id']                 = $item['id'];
            $可选节点['经办步骤Step']               = $item['Step'];
            $可选节点['经办步骤FlowId']             = $item['id'];
            if(count($可选节点['NodeFlow_AuthorizedUser']) > 0) {
                $下一步骤可选节点[] = $可选节点;
                $下一步骤可选节点序号[] = $item['Step'];
            }
        }

    }

    $RS             = [];
    $RS['data']     = $下一步骤可选节点;
    $RS['status']   = 'ok';
    $RS['NextStep'] = join(',', $下一步骤可选节点序号);
    print_R(json_encode($RS));
    exit;
}

$processid      = intval($_POST['processid']);
$selectedText   = FilterString($_POST['selectedText']);
$selectedUsers  = $_POST['selectedUsers'];
if($_GET['action'] == 'GoToNextStep' && $FlowId > 0 && $processid > 0 && $selectedText != '' && is_array($selectedUsers) )          {
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

    //加载工作流插件
    require_once('plugins.php');
    $NodeFlow_Approval_Execute_Function = $SettingMap['NodeFlow_Approval_Execute_Function']; 
    if(function_exists($NodeFlow_Approval_Execute_Function))  {
        $NodeFlow_Approval_Execute_Function(); 
    }
    //审核通过时处理一些简单的字段修改
    $sql        = "select * from form_formname where id='$FormId'";
    $rs         = $db->Execute($sql);
    $FormInfo   = $rs->fields;
    $FormName           = $FormInfo['FullName'];
    $TableName          = $FormInfo['TableName'];
    $MetaColumnNames    = GLOBAL_MetaColumnNames($TableName);
    $NodeFlow_Approval_Change_Field_Name            = $SettingMap['NodeFlow_Approval_Change_Field_Name'];
    $NodeFlow_Approval_Change_Field_Value           = $SettingMap['NodeFlow_Approval_Change_Field_Value'];
    $NodeFlow_Approval_Change_Field_To_DateTime     = $SettingMap['NodeFlow_Approval_Change_Field_To_DateTime'];
    $NodeFlow_Approval_Change_Field_To_UserId       = $SettingMap['NodeFlow_Approval_Change_Field_To_UserId'];
    $AddSqlApproval = [];
    if($NodeFlow_Approval_Change_Field_Name != "" && in_array($NodeFlow_Approval_Change_Field_Name, $MetaColumnNames)) {
        $AddSqlApproval[] = " $NodeFlow_Approval_Change_Field_Name = '".$NodeFlow_Approval_Change_Field_Value."' ";
    }
    if($NodeFlow_Approval_Change_Field_To_DateTime != "" && in_array($NodeFlow_Approval_Change_Field_To_DateTime, $MetaColumnNames)) {
        $AddSqlApproval[] = " $NodeFlow_Approval_Change_Field_To_DateTime = '".date('Y-m-d H:i:s')."' ";
    }
    if($NodeFlow_Approval_Change_Field_To_UserId != "" && in_array($NodeFlow_Approval_Change_Field_To_UserId, $MetaColumnNames)) {
        $AddSqlApproval[] = " $NodeFlow_Approval_Change_Field_To_UserId = '".$GLOBAL_USER->USER_ID."' ";
    }

    $NextStep = $SettingMap['NextStep'];
    $流程名称MAP = [];
    if($NextStep != "") {
        $NextStepArray = explode(',', $NextStep);
        $sql        = "select * from form_formflow where step in ('".join("','", $NextStepArray)."') and FormId = '$FormId'";
        $rs         = $db->Execute($sql);
        $rs_a       = $rs->GetArray();
        foreach($rs_a as $item) {
            $SettingData    = unserialize(base64_decode($item['Setting']));
            $StepName       = $SettingData['StepName'];
            if($StepName == null) $StepName = $item['Step'];
            $流程名称MAP[$item['Step']]['FlowName'] = "(第".$StepName."步： ".$item['FlowName'].")"; ;
            $流程名称MAP[$item['Step']]['FlowId']   = $item['id'];
        }
    }

    $sql        = "select * from form_flow_run_process where id = '$processid'";
    $rs         = $db->Execute($sql);
    $ProcessInfo= $rs->fields;

    //执行操作-审核通过时处理一些简单的字段修改
    if(sizeof($AddSqlApproval)>0 && $ProcessInfo['工作ID'] != '') {
        $sql = "update $TableName set ".join(',', $AddSqlApproval)." where id = '".$ProcessInfo['工作ID']."'";
        $db->Execute($sql);
    }

    //修改子表的记录信息    
    $NodeFlow_Approval_Change_ChildTable_Field_Name            = $SettingMap['NodeFlow_Approval_Change_ChildTable_Field_Name'];
    $NodeFlow_Approval_Change_ChildTable_Field_Value           = $SettingMap['NodeFlow_Approval_Change_ChildTable_Field_Value'];
    $NodeFlow_Approval_Change_ChildTable_Field_To_DateTime     = $SettingMap['NodeFlow_Approval_Change_ChildTable_Field_To_DateTime'];
    $NodeFlow_Approval_Change_ChildTable_Field_To_UserId       = $SettingMap['NodeFlow_Approval_Change_ChildTable_Field_To_UserId'];
    //Relative Child Table Support
    $Relative_Child_Table                   = $SettingMap['Relative_Child_Table'];
    $Relative_Child_Table_Type              = $SettingMap['Relative_Child_Table_Type'];
    $Relative_Child_Table_Field_Name        = $SettingMap['Relative_Child_Table_Field_Name'];
    $Relative_Child_Table_Parent_Field_Name = $SettingMap['Relative_Child_Table_Parent_Field_Name'];
    if($Relative_Child_Table>0 && $Relative_Child_Table_Parent_Field_Name!="" && in_array($Relative_Child_Table_Parent_Field_Name,$MetaColumnNames) && $ProcessInfo['工作ID'] != '') {
        $ChildSettingMap = returntablefield("form_formflow",'id',$Relative_Child_Table,'Setting')['Setting'];
        $ChildSettingMap = unserialize(base64_decode($ChildSettingMap));
        $ChildFormId                = returntablefield("form_formflow",'id',$Relative_Child_Table,'FormId')['FormId'];
        $ChildTableName             = returntablefield("form_formname",'id',$ChildFormId,'TableName')['TableName'];
        $ChildMetaColumnNames       = GLOBAL_MetaColumnNames($ChildTableName);
        $UpdateSqlArray             = [];
        if($Relative_Child_Table_Field_Name!="" && in_array($Relative_Child_Table_Field_Name, $ChildMetaColumnNames)) {
            if(in_array($NodeFlow_Approval_Change_ChildTable_Field_Name, $ChildMetaColumnNames)) {
                preg_match_all('/\[(.*?)\]/', $NodeFlow_Approval_Change_ChildTable_Field_Value, $NodeFlow_Approval_Change_ChildTable_Field_Value_Filter);
                if($NodeFlow_Approval_Change_ChildTable_Field_Value_Filter[1] != "") {
                    //值中带有[], 表示是同步字段信息, 而不是一个固定的值
                    $ParentTableFieldName = $NodeFlow_Approval_Change_ChildTable_Field_Value_Filter[1][0];
                    if($ParentTableFieldName != "" && in_array($ParentTableFieldName,$MetaColumnNames)) {
                        $Relative_Child_Table_Parent_Field_Name_Value = returntablefield($TableName,'id',$ProcessInfo['工作ID'],$ParentTableFieldName)[$ParentTableFieldName];
                        $UpdateSqlArray[] = " $NodeFlow_Approval_Change_ChildTable_Field_Name = '".$Relative_Child_Table_Parent_Field_Name_Value."' ";
                    }
                }
                else {
                    $UpdateSqlArray[] = " $NodeFlow_Approval_Change_ChildTable_Field_Name = '".$NodeFlow_Approval_Change_ChildTable_Field_Value."' ";
                }
            }
            if(in_array($NodeFlow_Approval_Change_ChildTable_Field_To_DateTime, $ChildMetaColumnNames)) {
                $UpdateSqlArray[] = " $NodeFlow_Approval_Change_ChildTable_Field_To_DateTime = '".date('Y-m-d H:i:s')."' ";
            }
            if(in_array($NodeFlow_Approval_Change_ChildTable_Field_To_UserId, $ChildMetaColumnNames)) {
                $UpdateSqlArray[] = " $NodeFlow_Approval_Change_ChildTable_Field_To_UserId = '".$GLOBAL_USER->USER_ID."' ";
            }
            $selectedRowsArray = explode(",", $_POST['selectedRows']);
            $selectedRows = [];
            foreach($selectedRowsArray as $Item) {
                $selectedRows[] = DecryptID($Item);
            }
            //print_R($UpdateSqlArray);
            if(sizeof($UpdateSqlArray)>0 && sizeof($selectedRows)>0 && $selectedRows[0]>0) {
                $Relative_Child_Table_Parent_Field_Name_Value = returntablefield($TableName,'id',$ProcessInfo['工作ID'],$Relative_Child_Table_Parent_Field_Name)[$Relative_Child_Table_Parent_Field_Name];
                $sql = "update $ChildTableName set ".join(',', $UpdateSqlArray)." where id in ('".join("','", $selectedRows)."')";
                $db->Execute($sql);
                //print $sql;exit;
            }
        }
    }
    //print $sql;exit;

    //继续处理工作流部分的代码
    foreach($selectedUsers as $Step => $User)  {
        if($User && $User['USER_ID'] != '')   {
            $NewProcess = [];
            $NewProcess['FormId'] = $ProcessInfo['FormId'];
            $NewProcess['FlowId'] = $流程名称MAP[$Step]['FlowId'];
            $NewProcess['RunId'] = $ProcessInfo['RunId'];
            $NewProcess['用户ID'] = $User['USER_ID'];
            $NewProcess['工作ID'] = $ProcessInfo['工作ID'];
            $NewProcess['工作接收时间'] = date("Y-m-d H:i:s");
            $NewProcess['步骤状态'] = "未办理";
            $NewProcess['流程步骤ID'] = intval($Step);
            $NewProcess['是否主办'] = $ProcessInfo['是否主办'];
            $NewProcess['上一步ProcessId'] = $processid;
            $NewProcess['是否超时'] = "否";
            $NewProcess['工作创建时间'] = date("Y-m-d H:i:s");
            $NewProcess['FlowName'] = $ProcessInfo['FlowName'];
            $NewProcess['经办步骤'] = $流程名称MAP[$Step]['FlowName'];
            [$rs,$sql] = InsertOrUpdateTableByArray("form_flow_run_process",$NewProcess,"工作ID,用户ID,流程步骤ID,FlowId",0);
            //print $sql;
        }
    }

    $sql = "update form_flow_run_process set 主办说明 = '".$selectedText."', 步骤状态 = '办结' where id = '$processid'";
    $db->Execute($sql);
    //print $sql;

    $RS             = [];
    $RS['msg']      = "工作办理成功";
    $RS['status']   = 'ok';
    print_R(json_encode($RS));
    exit;
}

$processid      = intval($_POST['processid']);
$GobackToStep   = FilterString($_POST['GobackToStep']);
$selectedText   = FilterString($_POST['selectedText']);
if($_GET['action'] == 'GobackToPreviousStep' && $FlowId > 0 && $processid > 0 && $selectedText == '' && $GobackToStep != '' )       {
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

    $NextStep       = $SettingMap['NextStep']; 
    $sql            = "select * from form_flow_run_process where id = '$processid'";
    $rs             = $db->Execute($sql);
    $ProcessInfo    = $rs->fields;
    $上一步ProcessId    = $ProcessInfo['上一步ProcessId'];

    $当前经办步骤    = '';
    $sql            = "select * from form_flow_run_process where id = '$上一步ProcessId'";
    $rsT            = $db->Execute($sql);
    $当前经办步骤    = $rsT->fields['经办步骤'];
    $FlowId         = $rsT->fields['FlowId'];
    $流程步骤ID      = $rsT->fields['流程步骤ID'];
    $上一步ProcessId     = $rsT->fields['上一步ProcessId'];
    $FlowName           = $rsT->fields['FlowName'];

    //上一步骤的Flow信息
    $sql        = "select * from form_formflow where id='$FlowId'";
    $rs         = $db->Execute($sql);
    $FormInfo   = $rs->fields;
    $上一步骤SettingMap = unserialize(base64_decode($FormInfo['Setting']));

    //审核通过时处理一些简单的字段修改
    $sql        = "select * from form_formname where id='$FormId'";
    $rs         = $db->Execute($sql);
    $FormInfo   = $rs->fields;
    $FormName           = $FormInfo['FullName'];
    $TableName          = $FormInfo['TableName'];
    $MetaColumnNames    = GLOBAL_MetaColumnNames($TableName);
    $NodeFlow_Approval_Change_Field_Name            = $上一步骤SettingMap['NodeFlow_Approval_Change_Field_Name'];
    $NodeFlow_Approval_Change_Field_Value           = $上一步骤SettingMap['NodeFlow_Approval_Change_Field_Value'];
    $NodeFlow_Approval_Change_Field_To_DateTime     = $上一步骤SettingMap['NodeFlow_Approval_Change_Field_To_DateTime'];
    $NodeFlow_Approval_Change_Field_To_UserId       = $上一步骤SettingMap['NodeFlow_Approval_Change_Field_To_UserId'];
    $AddSqlApproval = [];
    if($NodeFlow_Approval_Change_Field_Name != "" && in_array($NodeFlow_Approval_Change_Field_Name, $MetaColumnNames)) {
        $AddSqlApproval[] = " $NodeFlow_Approval_Change_Field_Name = '' ";
    }
    if($NodeFlow_Approval_Change_Field_To_DateTime != "" && in_array($NodeFlow_Approval_Change_Field_To_DateTime, $MetaColumnNames)) {
        $AddSqlApproval[] = " $NodeFlow_Approval_Change_Field_To_DateTime = '' ";
    }
    if($NodeFlow_Approval_Change_Field_To_UserId != "" && in_array($NodeFlow_Approval_Change_Field_To_UserId, $MetaColumnNames)) {
        $AddSqlApproval[] = " $NodeFlow_Approval_Change_Field_To_UserId = '' ";
    }
    //执行操作-审核通过时处理一些简单的字段修改
    if(sizeof($AddSqlApproval)>0 && $ProcessInfo['工作ID']!='') {
        $sql = "update $TableName set ".join(',', $AddSqlApproval)." where id = '".$ProcessInfo['工作ID']."'";
        $db->Execute($sql);
    }

    $NewProcess = [];
    $NewProcess['FormId'] = $ProcessInfo['FormId'];
    $NewProcess['FlowId'] = $FlowId;
    $NewProcess['RunId']  = $ProcessInfo['RunId'];
    $NewProcess['用户ID'] = $ProcessInfo['用户ID'];
    $NewProcess['工作ID'] = $ProcessInfo['工作ID'];
    $NewProcess['工作接收时间'] = date("Y-m-d H:i:s");
    $NewProcess['步骤状态']     = "未办理";
    $NewProcess['流程步骤ID']   = $流程步骤ID;
    $NewProcess['是否主办']     = $ProcessInfo['是否主办'];
    $NewProcess['上一步ProcessId'] = $上一步ProcessId;
    $NewProcess['是否超时']     = "否";
    $NewProcess['工作创建时间']  = date("Y-m-d H:i:s");
    $NewProcess['FlowName']     = $FlowName;
    $NewProcess['经办步骤']     = $当前经办步骤;
    [$rs,$sql] = InsertOrUpdateTableByArray("form_flow_run_process",$NewProcess,"工作ID,用户ID,流程步骤ID,FlowId,步骤状态",0);

    $sql = "update form_flow_run_process set 主办说明 = '".$selectedText."', 步骤状态 = '退回' where id = '$processid'";
    $db->Execute($sql);
    //print $sql;

    $RS             = [];
    $RS['msg']      = "工作退回成功";
    $RS['status']   = 'ok';
    print_R(json_encode($RS));
    exit;
}

$runid          = intval($_POST['runid']);
if($_GET['action'] == 'GoToEndWork' && $FlowId > 0 && $processid > 0 && $runid > 0)      {
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

    //加载工作流插件
    require_once('plugins.php');
    $NodeFlow_Approval_Execute_Function = $SettingMap['NodeFlow_Approval_Execute_Function']; 
    if(function_exists($NodeFlow_Approval_Execute_Function))  {
        $NodeFlow_Approval_Execute_Function();
    }
    //审核通过时处理一些简单的字段修改
    $sql        = "select * from form_formname where id='$FormId'";
    $rs         = $db->Execute($sql);
    $FormInfo   = $rs->fields;
    $FormName           = $FormInfo['FullName'];
    $TableName          = $FormInfo['TableName'];
    $MetaColumnNames    = GLOBAL_MetaColumnNames($TableName);
    $NodeFlow_Approval_Change_Field_Name            = $SettingMap['NodeFlow_Approval_Change_Field_Name'];
    $NodeFlow_Approval_Change_Field_Value           = $SettingMap['NodeFlow_Approval_Change_Field_Value'];
    $NodeFlow_Approval_Change_Field_To_DateTime     = $SettingMap['NodeFlow_Approval_Change_Field_To_DateTime'];
    $NodeFlow_Approval_Change_Field_To_UserId       = $SettingMap['NodeFlow_Approval_Change_Field_To_UserId'];
    $AddSqlApproval = [];
    if($NodeFlow_Approval_Change_Field_Name != "" && in_array($NodeFlow_Approval_Change_Field_Name, $MetaColumnNames)) {
        $AddSqlApproval[] = " $NodeFlow_Approval_Change_Field_Name = '".$NodeFlow_Approval_Change_Field_Value."' ";
    }
    if($NodeFlow_Approval_Change_Field_To_DateTime != "" && in_array($NodeFlow_Approval_Change_Field_To_DateTime, $MetaColumnNames)) {
        $AddSqlApproval[] = " $NodeFlow_Approval_Change_Field_To_DateTime = '".date('Y-m-d H:i:s')."' ";
    }
    if($NodeFlow_Approval_Change_Field_To_UserId != "" && in_array($NodeFlow_Approval_Change_Field_To_UserId, $MetaColumnNames)) {
        $AddSqlApproval[] = " $NodeFlow_Approval_Change_Field_To_UserId = '".$GLOBAL_USER->USER_ID."' ";
    }
    $sql        = "select * from form_flow_run_process where id = '$processid'";
    $rs         = $db->Execute($sql);
    $ProcessInfo= $rs->fields;
    //执行操作-审核通过时处理一些简单的字段修改
    if(sizeof($AddSqlApproval)>0 && $ProcessInfo['工作ID']!='') {
        $sql = "update $TableName set ".join(',', $AddSqlApproval)." where id = '".$ProcessInfo['工作ID']."'";
        $db->Execute($sql);
    }

    //修改子表的记录信息    
    $NodeFlow_Approval_Change_ChildTable_Field_Name            = $SettingMap['NodeFlow_Approval_Change_ChildTable_Field_Name'];
    $NodeFlow_Approval_Change_ChildTable_Field_Value           = $SettingMap['NodeFlow_Approval_Change_ChildTable_Field_Value'];
    $NodeFlow_Approval_Change_ChildTable_Field_To_DateTime     = $SettingMap['NodeFlow_Approval_Change_ChildTable_Field_To_DateTime'];
    $NodeFlow_Approval_Change_ChildTable_Field_To_UserId       = $SettingMap['NodeFlow_Approval_Change_ChildTable_Field_To_UserId'];
    //Relative Child Table Support
    $Relative_Child_Table                   = $SettingMap['Relative_Child_Table'];
    $Relative_Child_Table_Type              = $SettingMap['Relative_Child_Table_Type'];
    $Relative_Child_Table_Field_Name        = $SettingMap['Relative_Child_Table_Field_Name'];
    $Relative_Child_Table_Parent_Field_Name = $SettingMap['Relative_Child_Table_Parent_Field_Name'];
    if($Relative_Child_Table>0 && $Relative_Child_Table_Parent_Field_Name!="" && in_array($Relative_Child_Table_Parent_Field_Name,$MetaColumnNames) && $ProcessInfo['工作ID'] != '') {
        $ChildSettingMap = returntablefield("form_formflow",'id',$Relative_Child_Table,'Setting')['Setting'];
        $ChildSettingMap = unserialize(base64_decode($ChildSettingMap));
        $ChildFormId                = returntablefield("form_formflow",'id',$Relative_Child_Table,'FormId')['FormId'];
        $ChildTableName             = returntablefield("form_formname",'id',$ChildFormId,'TableName')['TableName'];
        $ChildMetaColumnNames       = GLOBAL_MetaColumnNames($ChildTableName);
        $UpdateSqlArray             = [];
        if($Relative_Child_Table_Field_Name!="" && in_array($Relative_Child_Table_Field_Name, $ChildMetaColumnNames)) {
            if(in_array($NodeFlow_Approval_Change_ChildTable_Field_Name, $ChildMetaColumnNames)) {
                preg_match_all('/\[(.*?)\]/', $NodeFlow_Approval_Change_ChildTable_Field_Value, $NodeFlow_Approval_Change_ChildTable_Field_Value_Filter);
                if($NodeFlow_Approval_Change_ChildTable_Field_Value_Filter[1] != "") {
                    //值中带有[], 表示是同步字段信息, 而不是一个固定的值
                    $ParentTableFieldName = $NodeFlow_Approval_Change_ChildTable_Field_Value_Filter[1][0];
                    if($ParentTableFieldName != "" && in_array($ParentTableFieldName,$MetaColumnNames)) {
                        $Relative_Child_Table_Parent_Field_Name_Value = returntablefield($TableName,'id',$ProcessInfo['工作ID'],$ParentTableFieldName)[$ParentTableFieldName];
                        $UpdateSqlArray[] = " $NodeFlow_Approval_Change_ChildTable_Field_Name = '".$Relative_Child_Table_Parent_Field_Name_Value."' ";
                    }
                }
                else {
                    $UpdateSqlArray[] = " $NodeFlow_Approval_Change_ChildTable_Field_Name = '".$NodeFlow_Approval_Change_ChildTable_Field_Value."' ";
                }
            }
            if(in_array($NodeFlow_Approval_Change_ChildTable_Field_To_DateTime, $ChildMetaColumnNames)) {
                $UpdateSqlArray[] = " $NodeFlow_Approval_Change_ChildTable_Field_To_DateTime = '".date('Y-m-d H:i:s')."' ";
            }
            if(in_array($NodeFlow_Approval_Change_ChildTable_Field_To_UserId, $ChildMetaColumnNames)) {
                $UpdateSqlArray[] = " $NodeFlow_Approval_Change_ChildTable_Field_To_UserId = '".$GLOBAL_USER->USER_ID."' ";
            }
            $selectedRowsArray = explode(",", $_POST['selectedRows']);
            $selectedRows = [];
            foreach($selectedRowsArray as $Item) {
                $selectedRows[] = DecryptID($Item);
            }
            //print_R($UpdateSqlArray);
            if(sizeof($UpdateSqlArray)>0 && sizeof($selectedRows)>0 && $selectedRows[0]>0) {
                $Relative_Child_Table_Parent_Field_Name_Value = returntablefield($TableName,'id',$ProcessInfo['工作ID'],$Relative_Child_Table_Parent_Field_Name)[$Relative_Child_Table_Parent_Field_Name];
                $sql = "update $ChildTableName set ".join(',', $UpdateSqlArray)." where id in ('".join("','", $selectedRows)."')";
                $db->Execute($sql);
                //print $sql;exit;
            }
        }
    }
    //print $sql;exit;

    $sql1 = "update form_flow_run_process set 主办说明 = '".$selectedText."', 步骤状态 = '办结' where id = '$processid' and 步骤状态 !='办结'";
    $db->Execute($sql1);

    $sql2 = "update form_flow_run set 结束时间 = '".date('Y-m-d H:i:s')."' where id = '$runid' and 结束时间 = ''";
    $db->Execute($sql2);
    
    $RS             = [];
    $RS['msg']      = "工作办理成功";
    $RS['status']   = 'ok';
    $RS['sql1']     = $sql1;
    $RS['sql2']     = $sql2;
    print_R(json_encode($RS));
    exit;
}

$runid          = intval($_POST['runid']);
if($_GET['action'] == 'getApprovalNodes' && $runid > 0)         {
    $sql        = "select 用户ID, USER_NAME, 工作接收时间, 步骤状态, 流程步骤ID, FlowName, 主办说明, 经办步骤 from form_flow_run_process, data_user where data_user.USER_ID = 用户ID and runid = '$runid' and (步骤状态 = '办结' or 步骤状态 = '退回') order by 工作接收时间 desc ";
    $rs         = $db->Execute($sql);
    $rsT        = $rs->GetArray();
    $RS                 = [];
    $RS['status']       = 'ok';
    $RS['sql']          = $sql;
    $RS['data']         = $rsT;
    print_R(json_encode($RS));
    exit;
}

$workType = FilterString($_POST['workType']);
if($_GET['action'] == 'GetMyWorkList' && $workType != '')       {
    $pageid     = intval($_POST['pageid']);
    $pageSize   = intval($_POST['pageSize']);
    if($pageSize<=0)    $pageSize   = 15;
    $From   = $pageid * $pageSize;
    $To     = $pageSize;
    $search = FilterString($_POST['search']);
    if($search != "")  {
        $AddSql = " and 工作名称 like '%$search%' ";
    }
    else {
        $AddSql = "";
    }

    switch($workType) {
        case 'todo':
            $AddSql .= " and 步骤状态 in ('未办理','办理中') "; 
            break;
        case 'done':
            $AddSql .= " and 步骤状态 = '办结' "; 
            break;
    }

    $sql    = "select count(p.id) as NUM
                FROM form_flow_run_process p
                JOIN form_flow_run r ON r.工作ID = p.工作ID
                WHERE p.id IN (
                    SELECT MAX(p2.id)
                    FROM form_flow_run_process p2
                    WHERE p2.用户ID = '".$GLOBAL_USER->USER_ID."' 
                    $AddSql
                    GROUP BY p2.工作ID
                )
                AND p.用户ID = '".$GLOBAL_USER->USER_ID."' 
                $AddSql ";
    $rs     = $db->Execute($sql);
    $rs_a   = $rs->GetArray();
    $totalCount = $rs_a[0]['NUM'];

    $sql    = " SELECT 
                    p.id as id,
                    r.id as runid,
                    r.FormId,
                    p.FlowId,
                    p.工作ID,
                    r.工作名称,
                    r.开始时间,
                    r.删除标记,
                    r.是否归档,
                    r.工作等级,
                    r.发起人,
                    r.发起人姓名,
                    p.工作接收时间,
                    p.流程步骤ID,
                    p.工作转交办结,
                    p.步骤状态,
                    p.经办步骤,
                    r.结束时间,
                    p.id as processid,
                    p.上一步ProcessId as 上一步ProcessId
                FROM form_flow_run_process p
                JOIN form_flow_run r ON r.工作ID = p.工作ID
                WHERE p.id IN (
                    -- 子查询：获取每个工作ID对应的最大ID
                    SELECT MAX(p2.id)
                    FROM form_flow_run_process p2
                    WHERE p2.用户ID = '".$GLOBAL_USER->USER_ID."' 
                    $AddSql
                    GROUP BY p2.工作ID
                )
                AND p.用户ID = '".$GLOBAL_USER->USER_ID."' 
                $AddSql 
                ORDER BY p.id DESC limit $From, $To";

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
    $RS['workType']     = $workType;
    print_R(json_encode($RS));
    exit;
}


?>