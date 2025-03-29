<?php
/*
* 基础架构: 单点低代码开发平台
* 版权所有: 郑州单点科技软件有限公司
* Email: moodle360@qq.com
* Copyright (c) 2007-2025
* License: 商业授权
*/
header("Content-Type: application/json");
require_once('cors.php');
require_once('include.inc.php');

CheckAuthUserLoginStatus();
CheckAuthUserRoleHaveMenu(0, "/form/formname");

$TableName      = "form_formflow";

$externalId     = intval($_REQUEST['externalId']);
$id             = FilterString($_REQUEST['id']);
$selectedRows   = FilterString($_REQUEST['selectedRows']);
if($externalId==""&&$id!="")    {
    $externalId = returntablefield("form_formflow","id",$id,"FormId")['FormId'];
}
if($externalId==""&&$selectedRows!="")    {
    $selectedRowsArray = explode(',',$selectedRows);
    $externalId = returntablefield("form_formflow","id",$selectedRowsArray[0],"FormId")['FormId'];
}
$FormInfor = returntablefield("form_formname","id",$externalId,"TableName,FullName");
$TableNameTarget = $FormInfor['TableName'];
$ShortNameTarget = $FormInfor['FullName'];
if($TableNameTarget=="")  {
    $RS = [];
    $RS['init_default']['data'] = [];
    $RS['init_default']['total'] = [];
    $RS['init_default']['params'] = [];
    $RS['init_default']['filter'] = [];
    $RS['init_default']['button_search']    = "";
    $RS['init_default']['button_add']       = "";
    $RS['init_default']['columns'] = [];
    $RS['add_default'] = [];
    $RS['edit_default'] = [];
    $RS['view_default'] = [];
    $RS['export_default'] = [];
    $RS['import_default'] = [];
    $RS['status'] = "ERROR";
    $RS['msg'] = "Missing externalId(FormId) value";
    print json_encode($RS);
    exit;
}

$columnNames = [];
$sql = "show columns from form_formflow";
$rs = $db->Execute($sql);
$rs_a = $rs->GetArray();
foreach ($rs_a as $Line) {
    $columnNames[] = $Line['Field'];
}

$ShowTypeMap = [];
$sql = "select * from form_formfield where FormId='$externalId' order by SortNumber asc, id asc";
$rs = $db->Execute($sql);
$rs_a = $rs->GetArray();
foreach ($rs_a as $Line) {
    $ShowTypeMap[$Line['FieldName']] = $Line['ShowType'];
}

//新建页面时的启用字段列表
$FaceToOptions = [];
$FaceToOptions[] = ['value'=>'AuthUser', 'label'=>__('AuthUser')];
$FaceToOptions[] = ['value'=>'AnonymousUser', 'label'=>__('AnonymousUser')];
$FaceToOptions[] = ['value'=>'Student', 'label'=>__('Student')];
//$FaceToOptions[] = ['value'=>'Parent', 'label'=>__('Parent')];
$allFieldsAdd = [];
$allFieldsAdd['Default'][] = ['name' => 'FlowName', 'show'=>true, 'type'=>'input', 'label' => __('FlowName'), 'value' => '', 'placeholder' => 'FlowName', 'helptext' => 'FlowName', 'rules' => ['required' => true,'xs'=>12, 'sm'=>12, 'disabled' => false]];
$allFieldsAdd['Default'][] = ['name' => 'FaceTo', 'show'=>true, 'type'=>'select', 'options'=>$FaceToOptions, 'label' => __('FaceTo'), 'value' => 'AuthUser', 'placeholder' => '', 'helptext' => "", 'rules' => ['required' => true, 'disabled' => false, 'xs'=>12, 'sm'=>12]];

foreach($allFieldsAdd as $ModeName=>$allFieldItem) {
    foreach($allFieldItem as $ITEM) {
        $defaultValues[$ITEM['name']] = $ITEM['value'];
    }
}

//编辑页面时的启用字段列表
$allFieldsEdit = $allFieldsAdd;

if( ($_GET['action']=="add_default_data") && $_POST['FlowName']!="" && $externalId!="")  {
    $MetaColumnNames    = $db->MetaColumnNames($TableName);
    $MetaColumnNames    = array_values($MetaColumnNames);
    $FieldsArray                    = [];
    $FieldsArray['FormId']          = $externalId;
    $sql        = "select max(Step) as Step from $TableName where FormId='".$externalId."'";
    $rsf        = $db->Execute($sql);
    $Step       = $rsf->fields['Step'];
    $Step       = $Step + 1;
    $FieldsArray['Step']            = $Step;
    $FieldsArray['FlowName']        = $_POST['FlowName'];
    $FieldsArray['FaceTo']          = $_POST['FaceTo'];
    $FieldsArray['Setting']         = base64_encode(serialize(['FaceTo'=>$_POST['FaceTo']]));
    $FieldsArray['Creator']         = "admin";
    $FieldsArray['CreateTime']      = date("Y-m-d H:i:s");
    if(1)   {
        [$rs,$sql] = InsertOrUpdateTableByArray($TableName,$FieldsArray,"FormId,Step",0,"Insert");
        if($rs->EOF) {
            $RS['status'] = "OK";
            $RS['msg'] = __("Submit Success");
            print json_encode($RS);
            exit;
        }
        else {
            $RS = [];
            $RS['status'] = "ERROR";
            $RS['msg'] = __("sql execution failed");
            $RS['sql'] = $sql;
            $RS['_POST'] = $_POST;
            print json_encode($RS);
            exit;
        }
    }
}
//#########################################################################################################################
//Edit Define Initial######################################################################################################
//#########################################################################################################################
$MetaColumnNamesTarget    = $db->MetaColumnNames($TableNameTarget);
$MetaColumnNamesTarget    = array_values($MetaColumnNamesTarget);
$MetaColumnNamesOptions = [];
$MetaColumnNamesOptionsOnlyShowStatus = [];
$MetaColumnNamesOptionsOnlyShowStatus[] = ['value'=>"Disabled", 'label'=>__("Disabled")];
$MetaColumnNamesOptionsOnlyshowPerson = [];
$MetaColumnNamesOptionsOnlyshowPerson[] = ['value'=>"Disabled", 'label'=>__("Disabled")];
$MetaColumnNamesOptionsOnlyShowDateTime = [];
$MetaColumnNamesOptionsOnlyShowDateTime[] = ['value'=>"Disabled", 'label'=>__("Disabled")];
$MetaColumnNamesOptionsOnlyShowOpinion = [];
$MetaColumnNamesOptionsOnlyShowOpinion[] = ['value'=>"Disabled", 'label'=>__("Disabled")];
$MetaColumnNamesOptionsAll = [];
$MetaColumnNamesOptionsAll[] = ['value'=>"Disabled", 'label'=>__("Disabled")];
foreach($MetaColumnNamesTarget AS $Item) {
    if($Item!="id")   {
        $MetaColumnNamesOptions[] = ['value'=>$Item, 'label'=>$Item];
    }
    $MetaColumnNamesOptionsAll[] = ['value'=>$Item, 'label'=>$Item];
    if(strpos($Item,'提交状态')!==false)   {
        $MetaColumnNamesOptionsOnlyShowStatus[] = ['value'=>$Item, 'label'=>$Item];
    }
    if(strpos($Item,'审核状态')>0)   {
        $MetaColumnNamesOptionsOnlyShowStatus[] = ['value'=>$Item, 'label'=>$Item];
    }
    if(strpos($Item,'审核人')>0 || strpos($Item,'用户名')>0)   {
        $MetaColumnNamesOptionsOnlyshowPerson[] = ['value'=>$Item, 'label'=>$Item];
    }
    if(strpos($Item,'审核时间')>0)   {
        $MetaColumnNamesOptionsOnlyShowDateTime[] = ['value'=>$Item, 'label'=>$Item];
    }
    if(strpos($Item,'审核意见')>0)   {
        $MetaColumnNamesOptionsOnlyShowOpinion[] = ['value'=>$Item, 'label'=>$Item];
    }
}
$YesOrNotOptions = [];
$YesOrNotOptions[] = ['value'=>'Yes', 'label'=>__('Yes')];
$YesOrNotOptions[] = ['value'=>'No', 'label'=>__('No')];

//处理其它几个编辑页面的结构定义
include_once('form_formflow_edit_default_1.php');
include_once('form_formflow_edit_default_2.php');
include_once('form_formflow_edit_default_3.php');
include_once('form_formflow_edit_default_4.php');
include_once('form_formflow_edit_default_5.php');
include_once('form_formflow_edit_default_7.php');

if(($_GET['action']=="edit_default_6_data") && $id!="")     {
    $sql        = "select * from form_formflow where id = '$id'";
    $rs         = $db->Execute($sql);
    $FieldsArray                = $rs->fields;
    $FieldsArray['id']          = NULL;
    $FieldsArray['FlowName']    = ForSqlInjection($_POST['FlowName']);
    $FieldsArray['FaceTo']      = ForSqlInjection($_POST['FaceTo']);

    $sql        = "select Max(Step) AS Step from form_formflow where FormId = '".$FieldsArray['FormId']."'";
    $rs         = $db->Execute($sql);
    $FieldsArray['Step']        = $rs->fields['Step']+1;
    [$rs,$sql] = InsertOrUpdateTableByArray("form_formflow",$FieldsArray,'FormId,Step',0);
    if($rs->EOF) {
        $RS['status'] = "OK";
        $RS['_POST'] = $_POST;
        $RS['msg'] = __("Submit Success");
        print json_encode($RS);
        exit;
    }
    else {
        $RS = [];
        $RS['status'] = "ERROR";
        $RS['msg'] = __("sql execution failed");
        $RS['sql'] = $sql;
        $RS['_GET'] = $_GET;
        $RS['_POST'] = $_POST;
        print json_encode($RS);
        exit;
    }
}

if(($_GET['action']=="edit_default_1_data" || $_GET['action']=="edit_default_2_data" || $_GET['action']=="edit_default_3_data" || $_GET['action']=="edit_default_4_data" || $_GET['action']=="edit_default_5_data" || $_GET['action']=="edit_default_7_data") && $id!="")     {

    if($id!=""&&$_POST['NodeType']=="工作流")   {
        $sql        = "delete from data_menutwo where FlowId = '$id'";
        $rs         = $db->Execute($sql);
    }
    if($_POST['Menu_One']!=""&&$_POST['Menu_Two']!=""&&$id!=""&&$_POST['NodeType']=="菜单")   {
        $FieldsArray = [];
        $FieldsArray['MenuOneName']    = $_POST['Menu_One'];
        $FieldsArray['MenuTwoName']    = $_POST['Menu_Two'];
        $FieldsArray['MenuThreeName']  = $_POST['Menu_Three'];
        $FieldsArray['FaceTo']         = $_POST['FaceTo'];
        $FieldsArray['MenuTab']        = $_POST['MenuTab'];
        $FieldsArray['Menu_Three_Icon']= $_POST['Menu_Three_Icon'];
        $FieldsArray['FlowId']         = $id;
        $FieldsArray['MenuType']       = "Flow";
        $FieldsArray['SortNumber']     = $id;
        $FieldsArray['Creator']        = "admin";
        $FieldsArray['CreateTime']     = date("Y-m-d H:i:s");
        [$rs,$sql] = InsertOrUpdateTableByArray("data_menutwo",$FieldsArray,'FlowId',0);
        //Write Interface File In Apps Dir
        $sql        = "select id from data_menutwo where FlowId = '$id'";
        $rs         = $db->Execute($sql);
        $MenuTwoId  = $rs->fields['id'];
        $MenuTwoInterfaceFilePath = "apps/apps_".$MenuTwoId.".php";
        if($MenuTwoId>0&&!is_file($MenuTwoInterfaceFilePath)||1)   {
            $Content = '<?php
/*
* 基础架构: 单点低代码开发平台
* 版权所有: 郑州单点科技软件有限公司
* Email: moodle360@qq.com
* Copyright (c) 2007-2025
* License: 商业授权
*/
header("Content-Type: application/json");
require_once("../cors.php");
require_once("../include.inc.php");
$FlowId   = '.$id.';
require_once("../data_enginee_flow.php");
?>';
            $rs = file_put_contents($MenuTwoInterfaceFilePath,$Content);
            if($rs==false) {
                $RS = [];
                $RS['status'] = "ERROR";
                $RS['msg'] = "Failed to create PHP interface file. Please check whether the 'apps' directory has corresponding write permissions.";
                print json_encode($RS);
                exit;
            }
        }
    }

    if($_POST['MobileEnd']!=""&&$id!=""&&$_POST['NodeType']=="菜单")   {
      $FieldsArray                        = [];
      $FieldsArray['FlowId']              = $id;
      $FieldsArray['IsMobile']            = $_POST['MobileEnd'] == "No" ? "否" : "是";
      $FieldsArray['MobileEndIconImage']  = $_POST['MobileEndIconImage'];
      $FieldsArray['Creator']             = "admin";
      $FieldsArray['CreateTime']          = date("Y-m-d H:i:s");
      [$rs,$sql] = InsertOrUpdateTableByArray("data_menutwo",$FieldsArray,'FlowId',0);
    }

    //Make Plugin File
    if($_POST['EnablePluginsForIndividual']=='Enable')   {
        $FormId     = returntablefield("form_formflow","id",$id,"FormId")['FormId'];;
        $Step       = returntablefield("form_formflow","id",$id,"Step")['Step'];
        $FlowName   = returntablefield("form_formflow","id",$id,"FlowName")['FlowName'];
        $TableName  = returntablefield("form_formname","id",$FormId,"TableName")['TableName'];
        $EnablePluginsForIndividual = "plugins/plugin_".$TableName."_".$Step.".php";
        if($Step>0 && !is_file($EnablePluginsForIndividual) && $TableName!="")   {
            $Content    = file_get_contents("plugins/plugin_tablename_step.php");
            $Content    = str_replace("tablename",$TableName,$Content);
            $Content    = str_replace("step",$Step,$Content);
            $Content    = str_replace("[FlowName]",$FlowName,$Content);
            $rs         = file_put_contents($EnablePluginsForIndividual,$Content);
            if($rs==false) {
                $RS = [];
                $RS['status'] = "ERROR";
                $RS['msg'] = "Failed to Plugin file. Please check whether the 'plugins' directory has corresponding write permissions.";
                print json_encode($RS);
                exit;
            }
        }
    }

    $sql        = "select * from form_formflow where id = '$id'";
    $rs         = $db->Execute($sql);
    $FormId     = intval($rs->fields['FormId']);
    $Step       = intval($rs->fields['Step']);
    $FlowName   = $rs->fields['FlowName'];
    $Setting    = $rs->fields['Setting'];
    $SettingMap = unserialize(base64_decode($Setting));
    $FlowName   = $rs->fields['FlowName'];
    foreach($_POST as $value => $label)  {
        $SettingMap[$value] = $label;
    }
    $FieldsArray = [];
    //$FieldsArray['FlowName']  = $SettingMap['FlowName'];
    $FieldsArray['FormId']      = $FormId;
    $FieldsArray['Step']        = $Step;
    if($_POST['FaceTo']!="")   {
        $FieldsArray['FaceTo']  = $_POST['FaceTo'];
    }
    if($_POST['Init_Action_Value']=="edit_default_configsetting")   {
        $FieldsArray['PageType']  = "ConfigSetting";
    }
    else {
        $FieldsArray['PageType']  = "FunctionPage";
    }
    if($_POST['MobileEnd']!="")  {
        $FieldsArray['MobileEnd']   = $_POST['MobileEnd'];
    }
    if($_POST['NodeType']!="")  {
        $FieldsArray['NodeType']   = $_POST['NodeType'];
    }
    if($_POST['NextStep']!="")  {
        $FieldsArray['NextStep']   = $_POST['NextStep'];
    }
    if($_POST['NodeFlow_AuthorizedUser']!="")  {
        $FieldsArray['AuthorizedUser']   = $_POST['NodeFlow_AuthorizedUser'];
    }
    if($_POST['NodeFlow_AuthorizedDept']!="")  {
        $FieldsArray['AuthorizedDept']   = $_POST['NodeFlow_AuthorizedDept'];
    }
    if($_POST['NodeFlow_AuthorizedRole']!="")  {
        $FieldsArray['AuthorizedRole']   = $_POST['NodeFlow_AuthorizedRole'];
    }
    $FieldsArray['Setting']     = base64_encode(serialize($SettingMap));
    $FieldsArray['Creator']     = "admin";
    $FieldsArray['CreateTime'] = date("Y-m-d H:i:s");
    [$rs,$sql] = InsertOrUpdateTableByArray("form_formflow",$FieldsArray,'FormId,Step',0);
    if($rs->EOF) {
        $RS['status'] = "OK";
        $RS['_POST'] = $_POST;
        $RS['msg'] = __("Submit Success");
        print json_encode($RS);
        exit;
    }
    else {
        $RS = [];
        $RS['status'] = "ERROR";
        $RS['msg'] = __("sql execution failed");
        $RS['sql'] = $sql;
        $RS['_GET'] = $_GET;
        $RS['_POST'] = $_POST;
        print json_encode($RS);
        exit;
    }
}


if($_GET['action']=="updateone")  {
    $id     = ForSqlInjection($_POST['id']);
    $field  = FilterString($_POST['field']);
    $value  = FilterString($_POST['value']);
    $primary_key = $columnNames[0];
    if($id!=""&&$field!=""&&in_array($field,$columnNames)&&$primary_key!=$field) {
        $sql    = "update form_formflow set $field = '$value' where $primary_key = '$id'";
        $db->Execute($sql);
        $RS = [];
        $RS['status'] = "OK";
        $RS['msg'] = __("Update Success");
        print json_encode($RS);
        exit;
    }
    else {
        $RS = [];
        $RS['status'] = "ERROR";
        $RS['msg'] = __("Params Error!");
        $RS['_GET'] = $_GET;
        $RS['_POST'] = $_POST;
        print json_encode($RS);
        exit;
    }
}

if($_GET['action']=="delete_array")  {
    $MetaColumnNames    = $db->MetaColumnNames($TableName);
    $MetaColumnNames    = array_values($MetaColumnNames);
    $selectedRows  = ForSqlInjection($_POST['selectedRows']);
    $selectedRows = explode(',',$selectedRows);
    $primary_key = $MetaColumnNames[0];
    foreach($selectedRows as $id) {
        $sql    = "delete from $TableName where $primary_key = '$id'";
        $db->Execute($sql);
    }
    $RS = [];
    $RS['sql'] = $sql;
    $RS['status'] = "OK";
    $RS['msg'] = "Drop Item Success!";
    print json_encode($RS);
    exit;
}

$AddSql = " where 1=1 and FormId='$externalId'";

$columnsactions     = [];
$columnsactions[]   = ['action'=>'delete_array','text'=>__('Delete'),'mdi'=>'mdi:delete-outline','double_check'=>__('Do you want to delete this item?')];
$columnsactions[]   = ['action'=>'edit_default_6','text'=>__('Copy'),'mdi'=>'mdi:content-copy','double_check'=>'Do you want to copy this item?'];
$init_default_columns[] = ['flex' => 0.1, 'minWidth' => 120, 'sortable' => false, 'field' => "actions", 'headerName' => __("Actions"), 'show'=>true, 'type'=>'actions', 'actions' => $columnsactions];
//$columnName = "TableName";      $init_default_columns[] = ['flex' => 0.1, 'minWidth' => 200, 'maxWidth' => 300, 'field' => $columnName, 'headerName' => __($columnName), 'editable'=>false, 'show'=>true, 'type'=>'string', 'renderCell' => NULL];
//$columnName = "FullName";    $init_default_columns[] = ['flex' => 0.1, 'minWidth' => 150, 'maxWidth' => 300, 'field' => $columnName, 'headerName' => __($columnName), 'editable'=>false, 'show'=>true, 'type'=>'string', 'renderCell' => NULL];
$columnName = "Step";           $init_default_columns[] = ['flex' => 0.1, 'minWidth' => 100, 'maxWidth' => 200, 'field' => $columnName, 'headerName' => __($columnName), 'editable'=>false, 'show'=>true, 'type'=>'string', 'renderCell' => NULL];
$columnName = "FlowName";       $init_default_columns[] = ['flex' => 0.1, 'minWidth' => 150, 'maxWidth' => 300, 'field' => $columnName, 'headerName' => __($columnName), 'editable'=>true, 'show'=>true, 'type'=>'string', 'renderCell' => NULL];
$columnName = "Field Type";     $init_default_columns[] = ['flex' => 0.1, 'minWidth' => 130, 'maxWidth' => 300, 'field' => $columnName, 'headerName' => __($columnName), 'editable'=>false, 'show'=>true, 'type'=>'api','apimdi'=>'mdi:chart-donut','apicolor'=>'success.main', 'apiaction' => "edit_default_1", 'renderCell' => NULL ];
$columnName = "NodeType";    $init_default_columns[] = ['flex' => 0.1, 'minWidth' => 130, 'maxWidth' => 250, 'field' => $columnName, 'headerName' => __($columnName), 'editable'=>false, 'show'=>true, 'type'=>'apivalue','apimdi'=>'hugeicons:flow','apicolor'=>'error.main', 'apiaction' => "edit_default_7", 'renderCell' => NULL ];
$columnName = "Interface";      $init_default_columns[] = ['flex' => 0.1, 'minWidth' => 130, 'maxWidth' => 250, 'field' => $columnName, 'headerName' => __($columnName), 'editable'=>false, 'show'=>true, 'type'=>'api','apimdi'=>'mdi:cog-outline','apicolor'=>'warning.main', 'apiaction' => "edit_default_2", 'renderCell' => NULL ];
$columnName = "Batch Approval";  $init_default_columns[] = ['flex' => 0.1, 'minWidth' => 130, 'maxWidth' => 250, 'field' => $columnName, 'headerName' => __($columnName), 'editable'=>false, 'show'=>true, 'type'=>'api','apimdi'=>'mdi:border-bottom','apicolor'=>'info.main', 'apiaction' => "edit_default_3", 'renderCell' => NULL ];
$columnName = "MobileEnd";  $init_default_columns[] = ['flex' => 0.1, 'minWidth' => 100, 'maxWidth' => 250, 'field' => $columnName, 'headerName' => __($columnName), 'editable'=>false, 'show'=>true, 'type'=>'api','apimdi'=>'mdi:cellphone','apicolor'=>'info.main', 'apiaction' => "edit_default_5", 'renderCell' => NULL ];
$columnName = "Msg Reminder";  $init_default_columns[] = ['flex' => 0.1, 'minWidth' => 130, 'maxWidth' => 250, 'field' => $columnName, 'headerName' => __($columnName), 'editable'=>false, 'show'=>true, 'type'=>'api','apimdi'=>'mdi:message-bulleted','apicolor'=>'info.main', 'apiaction' => "edit_default_4", 'renderCell' => NULL ];
$columnName = "FaceTo";         $init_default_columns[] = ['flex' => 0.1, 'minWidth' => 100, 'maxWidth' => 300, 'field' => $columnName, 'headerName' => __($columnName), 'editable'=>true, 'show'=>true, 'type'=>'string', 'renderCell' => NULL];


$RS['init_default']['button_search']    = __("Search");
$RS['init_default']['button_add']       = __("Add");
$RS['init_default']['columns']          = $init_default_columns;
$RS['init_default']['columnsactions']   = $columnsactions;

$columnName = "FlowName";        $searchField[] = ['label' => __($columnName), 'value' => $columnName];

$RS['init_action']['action']            = "init_default";
$RS['init_action']['actionValue']       = "";
$RS['init_action']['id']                = 999; //NOT USE THIS VALUE IN FRONT END

$RS['init_default']['searchFieldArray'] = $searchField;
$RS['init_default']['searchFieldText']  = __("Search Item");

$searchFieldName     = ForSqlInjection($_REQUEST['searchFieldName']);
$searchFieldValue    = ForSqlInjection($_REQUEST['searchFieldValue']);
if ($searchFieldName != "" && $searchFieldValue != "" && in_array($searchFieldName, $columnNames) ) {
    $AddSql .= " and ($searchFieldName like '%" . $searchFieldValue . "%')";
}

$RS['init_default']['filter'] = [];

$page       = intval($_REQUEST['page']);
$pageSize   = intval($_REQUEST['pageSize']);
if(!in_array($pageSize,[10,15,20,30,40,50,100]))  {
	$pageSize = 30;
}
$fromRecord = $page * $pageSize;

$sql    = "select count(*) AS NUM from form_formflow " . $AddSql . "";
$rs     = $db->Execute($sql);
$RS['init_default']['total']        = intval($rs->fields['NUM']);
$RS['init_default']['searchtitle']  = $ShortNameTarget . " - " . __("Design Flow") . " - " . $TableNameTarget;
$RS['init_default']['primarykey']   = $columnNames[0];
if(!in_array($_REQUEST['sortColumn'], $columnNames)) {
    $_REQUEST['sortColumn']         = $columnNames[0];
}
if($_REQUEST['sortColumn']=="")   {
    $_REQUEST['sortColumn'] = "id";
}
if($_REQUEST['sortMethod']=="desc") {
    $orderby = "order by `".$_REQUEST['sortColumn']."` desc";
}
else {
    $orderby = "order by `".$_REQUEST['sortColumn']."` asc";
}

$ForbiddenSelectRow = [];
$ForbiddenViewRow   = [];
$ForbiddenEditRow   = [];
$ForbiddenDeleteRow = [];
$sql    = "select * from form_formflow " . $AddSql . " $orderby limit $fromRecord,$pageSize";
//print $sql;
$NewRSA = [];
$rs     = $db->Execute($sql) or print $sql;
$rs_a   = $rs->GetArray();
foreach ($rs_a as $Line)            {
    $Line['id']             = intval($Line['id']);
    $Line['TableName']      = returntablefield("form_formname","id",$Line['FormId'],"TableName")['TableName'];
    $Line['FullName']      = returntablefield("form_formname","id",$Line['FormId'],"FullName")['FullName'];
    $NewRSA[]               = $Line;
    if(in_array($Line['TableName'],['data_user','data_department','data_role','data_unit','data_interface','data_menuone','data_menutwo','form_formflow'])) {
        $ForbiddenSelectRow[] = $Line['id'];
        //$ForbiddenViewRow[] = $Line['id'];
        //$ForbiddenEditRow[] = $Line['id'];
        //$ForbiddenDeleteRow[] = $Line['id'];
    }
}
$RS['init_default']['data'] = $NewRSA;
$RS['init_default']['ForbiddenSelectRow'] = $ForbiddenSelectRow;
$RS['init_default']['ForbiddenViewRow'] = $ForbiddenViewRow;
$RS['init_default']['ForbiddenEditRow'] = $ForbiddenEditRow;
$RS['init_default']['ForbiddenDeleteRow'] = $ForbiddenDeleteRow;

$RS['init_default']['params'] = ['FormGroup' => '', 'role' => '', 'status' => '', 'q' => ''];

$RS['init_default']['sql'] = $sql;
$RS['init_default']['ApprovalNodeFields']['DebugSql']   = "";
$RS['init_default']['ApprovalNodeFields']['Memo']       = "";
$RS['init_default']['ApprovalNodeFields']['AdminFilterTipText'] = "";


$RS['init_default']['rowdelete'] = [];
$RS['init_default']['rowdelete'][] = ["text"=>__("Delete Item"),"action"=>"delete_array","title"=>__("Delete Item"),"content"=>__("Do you really want to delete this item? This operation will delete table and data in Database."),"memoname"=>"","inputmust"=>false,"inputmusttip"=>"","submit"=>__("Confirm Delete"),"cancel"=>__("Cancel")];


$RS['add_default']['allFields']     = $allFieldsAdd;
$RS['add_default']['allFieldsMode']  = [['value'=>"Default", 'label'=>__("")]];
$RS['add_default']['defaultValues'] = $defaultValues;
$RS['add_default']['dialogContentHeight']  = "850px";
$RS['add_default']['submitaction']  = "add_default_data";
$RS['add_default']['componentsize'] = "small";
$RS['add_default']['componentsize'] = "small";
$RS['add_default']['submittext']    = __("Submit");
$RS['add_default']['canceltext']    = __("Cancel");
$RS['add_default']['titletext']     = __("Create Form");
$RS['add_default']['titlememo']     = __("Manage All Form Fields in Table");
$RS['add_default']['tablewidth']    = 650;

$RS['edit_default_1']['allFields']      = $edit_default_1;
$RS['edit_default_1']['allFieldsMode']  = $edit_default_1_mode;
$RS['edit_default_1']['defaultValues']  = $defaultValues_1;
$RS['edit_default_1']['dialogContentHeight']  = "850px";
$RS['edit_default_1']['submitaction']  = "edit_default_1_data";
$RS['edit_default_1']['componentsize'] = "small";
$RS['edit_default_1']['submittext']    = __("Submit");
$RS['edit_default_1']['canceltext']    = __("Cancel");
$RS['edit_default_1']['titletext']  = __("Design Flow Field Type");
$RS['edit_default_1']['titlememo']  = __("Manage All Field Show Types in Flow");
$RS['edit_default_1']['tablewidth']  = 650;

$RS['edit_default_2']['allFields']      = $edit_default_2;
$RS['edit_default_2']['allFieldsMode']  = $edit_default_2_mode;
$RS['edit_default_2']['defaultValues']  = $defaultValues_7;
$RS['edit_default_2']['dialogContentHeight']  = "850px";
$RS['edit_default_2']['submitaction']  = "edit_default_2_data";
$RS['edit_default_2']['componentsize'] = "small";
$RS['edit_default_2']['submittext']    = __("Submit");
$RS['edit_default_2']['canceltext']    = __("Cancel");
$RS['edit_default_2']['titletext']  = __("Design Flow Interface");
$RS['edit_default_2']['titlememo']  = __("Manage All Interface Attributes in Flow");
$RS['edit_default_2']['tablewidth']  = 650;

$RS['edit_default_3']['allFields']      = $edit_default_3;
$RS['edit_default_3']['allFieldsMode']  = $edit_default_3_mode;
$RS['edit_default_3']['defaultValues']  = $defaultValues_3;
$RS['edit_default_3']['dialogContentHeight']  = "850px";
$RS['edit_default_3']['submitaction']  = "edit_default_3_data";
$RS['edit_default_3']['componentsize'] = "small";
$RS['edit_default_3']['submittext']    = __("Submit");
$RS['edit_default_3']['canceltext']    = __("Cancel");
$RS['edit_default_3']['titletext']  = __("Design Form Bottom Button");
$RS['edit_default_3']['titlememo']  = __("Manage All Bottom Button Related Attributes in Flow");
$RS['edit_default_3']['tablewidth']  = 650;

$RS['edit_default_4']['allFields']      = $edit_default_4;
$RS['edit_default_4']['allFieldsMode']  = $edit_default_4_mode;
$RS['edit_default_4']['defaultValues']  = $defaultValues_4;
$RS['edit_default_4']['dialogContentHeight']  = "850px";
$RS['edit_default_4']['submitaction']  = "edit_default_4_data";
$RS['edit_default_4']['componentsize'] = "small";
$RS['edit_default_4']['submittext']    = __("Submit");
$RS['edit_default_4']['canceltext']    = __("Cancel");
$RS['edit_default_4']['titletext']  = __("Design Form Bottom Button");
$RS['edit_default_4']['titlememo']  = __("Manage All Bottom Button Related Attributes in Flow");
$RS['edit_default_4']['tablewidth']  = 650;
$RS['edit_default_4']['submitloading'] = __("SubmitLoading");
$RS['edit_default_4']['loading']       = __("Loading");

$RS['edit_default']                 = $RS['add_default'];
$RS['edit_default']['allFields']    = $allFieldsEdit;
$RS['edit_default']['allFieldsMode']  = [['value'=>"Default", 'label'=>__("")]];
$RS['edit_default']['defaultValues']  = $defaultValues;
$RS['edit_default']['dialogContentHeight']  = "850px";
$RS['edit_default']['submitaction']  = "edit_default_data";
$RS['edit_default']['componentsize'] = "small";
$RS['edit_default']['componentsize'] = "small";
$RS['edit_default']['submittext']    = __("Submit");
$RS['edit_default']['canceltext']    = __("Cancel");
$RS['edit_default']['titletext']  = __("Edit Form");
$RS['edit_default']['titlememo']  = __("Manage All Form Fields in Table");
$RS['edit_default']['tablewidth']  = 650;
$RS['edit_default']['submitloading']    = __("SubmitLoading");
$RS['edit_default']['loading']          = __("Loading");

$RS['edit_default_5']['allFields']      = $edit_default_5;
$RS['edit_default_5']['allFieldsMode']  = $edit_default_5_mode;
$RS['edit_default_5']['defaultValues']  = $defaultValues_4;
$RS['edit_default_5']['dialogContentHeight']  = "850px";
$RS['edit_default_5']['submitaction']  = "edit_default_5_data";
$RS['edit_default_5']['componentsize'] = "small";
$RS['edit_default_5']['submittext']    = __("Submit");
$RS['edit_default_5']['canceltext']    = __("Cancel");
$RS['edit_default_5']['titletext']  = __("Design Form Bottom Button");
$RS['edit_default_5']['titlememo']  = __("Manage All Bottom Button Related Attributes in Flow");
$RS['edit_default_5']['tablewidth']  = 650;
$RS['edit_default_5']['submitloading'] = __("SubmitLoading");
$RS['edit_default_5']['loading']       = __("Loading");

$RS['edit_default_6']['allFields']      = $allFieldsAdd;
$RS['edit_default_6']['allFieldsMode']  = [['value'=>"Default", 'label'=>__("")]];
$RS['edit_default_6']['defaultValues']  = $defaultValues;
$RS['edit_default_6']['dialogContentHeight']  = "90%";
$RS['edit_default_6']['submitaction']   = "edit_default_6_data";
$RS['edit_default_6']['submittext']     = __("Submit");
$RS['edit_default_6']['canceltext']     = __("Cancel");
$RS['edit_default_6']['titletext']      = __("Copy Item");
$RS['edit_default_6']['tablewidth']     = 550;
$RS['edit_default_6']['submitloading']  = __("SubmitLoading");
$RS['edit_default_6']['loading']        = __("Loading");

$RS['edit_default_7']['allFields']      = $edit_default_7;
$RS['edit_default_7']['allFieldsMode']  = $edit_default_7_mode;
$RS['edit_default_7']['defaultValues']  = $defaultValues_7;
$RS['edit_default_7']['dialogContentHeight']  = "850px";
$RS['edit_default_7']['submitaction']  = "edit_default_7_data";
$RS['edit_default_7']['componentsize'] = "small";
$RS['edit_default_7']['submittext']    = __("Submit");
$RS['edit_default_7']['canceltext']    = __("Cancel");
$RS['edit_default_7']['titletext']  = __("Node Setting");
$RS['edit_default_7']['titlememo']  = __("Set Node Information");
$RS['edit_default_7']['tablewidth']  = 650;

$RS['view_default'] = $RS['add_default'];
$RS['view_default']['titletext']  = __("View Form");
$RS['view_default']['titlememo']  = __("View All Form Fields in Table");

$RS['export_default'] = [];
$RS['import_default'] = [];

$RS['init_default']['returnButton1']['status']  = true;
$RS['init_default']['returnButton1']['text']    = __("return");
$RS['init_default']['returnButton2']['status']  = true;
$RS['init_default']['returnButton2']['text']    = __("转到表单");
$RS['init_default']['returnButton2']['url']     = "/form/formname/formfield/?FormId=";
$RS['init_default']['rowHeight']        = 38;
$RS['init_default']['dialogContentHeight']  = "850px";
$RS['init_default']['dialogMaxWidth']   = "md";// xl lg md sm xs
$RS['init_default']['timeline']         = time();
$RS['init_default']['pageNumber']       = $pageSize;
$RS['init_default']['pageCount']        = ceil($RS['init_default']['total']/$pageSize);
$RS['init_default']['pageNumberArray']  = [10,15,20,30,40,50,100];

if(sizeof($columnNames)>5) {
    $pinnedColumns = ['left'=>[],'right'=>['Actions']];
}
else {
    $pinnedColumns = [];
}
$RS['init_default']['pinnedColumns']  = $pinnedColumns;

$RS['init_default']['dataGridLanguageCode']  = $GLOBAL_LANGUAGE;
$RS['init_default']['checkboxSelection']  = false;

$RS['_GET']     = $_GET;
$RS['_POST']    = $_POST;
print_R(json_encode($RS, true));



