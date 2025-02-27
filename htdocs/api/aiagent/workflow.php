<?php
require_once('../cors.php');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require_once('../include.inc.php');

CheckAuthUserLoginStatus();


$USER_ID        = $GLOBAL_USER->USER_ID;

$payload        = file_get_contents('php://input');
if($payload)    {
    $_POST      = json_decode($payload,true);
}

$action         = FilterString($_GET['action']);
$type           = FilterString($_POST['type']);
$search         = FilterString($_POST['search']);
$pageid         = intval($_POST['pageid']);
$pagesize       = intval($_POST['pagesize']);


if($action == "listmyapp" && $pagesize >= 6)  {
    $From   = $pageid * $pagesize;
    $sql    = "select * from data_ai_app where AppType != 'Database' and UserId = '".$GLOBAL_USER->USER_ID."' order by id desc limit $From, $pagesize";
    $rs     = $db->Execute($sql);
    $rs_a   = $rs->GetArray();
    for($i=0;$i<sizeof($rs_a);$i++) {
        $rs_a[$i]['_id'] = EncryptID($rs_a[$i]['id']);
    }
    $RS     = [];
    $RS['pageid']   = $pageid;
    $RS['pagesize'] = $pagesize;
    $RS['data']     = $rs_a;
    $RS['from']     = $From;
    $RS['total']    = sizeof($rs_a);
    $RS['allpages'] = ceil($RS['total']/$pagesize);

    print json_encode($RS);
    exit;
}


if($action == "listallapp" && $pagesize >= 6)  {
    $From   = $pageid * $pagesize;
    $sql    = "select * from data_ai_app where AppType != 'Database' order by id desc limit $From, $pagesize";
    $rs     = $db->Execute($sql);
    $rs_a   = $rs->GetArray();
    for($i=0;$i<sizeof($rs_a);$i++) {
        $rs_a[$i]['_id'] = EncryptID($rs_a[$i]['id']);
    }
    $RS     = [];
    $RS['pageid']   = $pageid;
    $RS['pagesize'] = $pagesize;
    $RS['data']     = $rs_a;
    $RS['from']     = $From;
    $RS['total']    = sizeof($rs_a);
    $RS['allpages'] = ceil($RS['total']/$pagesize);

    print json_encode($RS);
    exit;
}


$appId      = FilterString($_POST['appId']);
if($action == "deletemyapp" && $appId != '')  {
    $appId  = DecryptID($appId);
    if($appId>0)  {
        $sql    = "delete from data_ai_app where id='$appId' and UserId = '".$GLOBAL_USER->USER_ID."'";
        //$db->Execute($sql);
        $RS             = [];
        $RS['sql']      = $sql;
        $RS['status']   = 'ok';
        $RS['msg']      = _('Delete Success');
    }
    else {
        $RS             = [];
        $RS['status']   = 'error';
        $RS['msg']      = _('Id Not Exist');
    }

    print json_encode($RS);
    exit;
}


$GroupOne         = FilterString($_POST['GroupOne']);
$GroupTwo         = FilterString($_POST['GroupTwo']);
$AppIntro         = FilterString($_POST['AppIntro']);
$AppName          = FilterString($_POST['AppName']);
$IsPublic         = FilterString($_POST['IsPublic']);
$AppType          = FilterString($_POST['AppType']);
$data             = $_POST['data'];
if($action == "addapp" && $AppName != '')   {    
    $Element        = [];
    $Element['GroupOne']    = $GroupOne;
    $Element['GroupTwo']    = $GroupTwo;
    $Element['AppName']     = $AppName;
    $Element['AppIntro']    = $AppIntro;
    $Element['IsPublic']    = $IsPublic == 'public' ? '是' : '否';
    $Element['AppType']     = $AppType;
    $Element['UserId']      = $GLOBAL_USER->USER_ID;
    $Element['Scope']       = '全校';
    $Element['AppAvatar']   = 'mdi:teacher';
    $Element['AppData']     = base64_encode(json_encode($data));
    $KEYS       = array_keys($Element);
    $VALUES     = array_values($Element);
    $sql        = "insert into data_ai_app (".join(',',$KEYS).") values('".join("','",$VALUES)."');";
    $db->Execute($sql);
    $InsertID       = $db->Insert_ID('data_ai_app');
    $RS             = [];
    $RS['InsertID'] = EncryptID($InsertID);
    $RS['sql']      = $sql;
    $RS['status']   = 'ok';
    $RS['msg']      = _('Add Success');

    print json_encode($RS);
    exit;
}

$appId            = FilterString($_POST['appId']);
$AppName          = FilterString($_POST['name']);
$AppIntro         = FilterString($_POST['intro']);
$IsPublic         = FilterString($_POST['permission']);
$AppType          = FilterString($_POST['type']);
$data             = base64_encode(json_encode($_POST['data']));
$avatar           = FilterString($_POST['avatar']);
if($action == "editapp" && $appId != '')   {
    $appId  = DecryptID($appId);  
    if($appId>0) {
        $Element        = [];
        $IsPublic       = $IsPublic == 'public' ? '是' : '否';
        $sql = "update data_ai_app set AppName='$AppName', AppAvatar='$avatar', AppIntro='$AppIntro', AppType='$AppType', IsPublic='$IsPublic', AppData='$data' where id ='$appId'";
        $db->Execute($sql);
        $RS             = [];
        $RS['sql']      = $sql;
        $RS['status']   = 'ok';
        $RS['msg']      = _('Update Success');
        print json_encode($RS);
        exit;
    }
    else {
        $RS             = [];
        $RS['status']   = 'error';
        $RS['msg']      = _('Id Not Exist');
        print json_encode($RS);
        exit;
    }
}

$appId      = FilterString($_POST['appId']);
if($action == "getmyapp" && $appId != '')   {
    $appId  = DecryptID($appId);  
    if($appId>0) {
        $sql    = "select * from data_ai_app where id='".$appId."'";
        $rs     = $db->Execute($sql);
        $rs_a   = $rs->GetArray();
        $RS     = json_decode(base64_decode($rs_a[0]['AppData']), true);
        $RS['status']       = 'ok';
        print json_encode($RS);
        exit;
    }
    else {
        $RS             = [];
        $RS['status']   = 'error';
        $RS['msg']      = _('Id Not Exist');
        print json_encode($RS);
        exit;
    }
}

if($action == "llms")   {
    $llms = [
        "llmModels" => [
            [
                "model" => "gpt-3.5-turbo",
                "name" => "Chatgpt 3.5",
                "maxContext" => 16000,
                "avatar" => "/imgs/model/openai.svg",
                "maxResponse" => 4000,
                "quoteMaxToken" => 13000,
                "maxTemperature" => 1.2,
                "charsPointsPrice" => 0,
                "censor" => false,
                "vision" => false,
                "datasetProcess" => true,
                "usedInClassify" => true,
                "usedInExtractFields" => true,
                "usedInToolCall" => true,
                "usedInQueryExtension" => true,
                "toolChoice" => true,
                "functionCall" => true,
                "customCQPrompt" => "",
                "customExtractPrompt" => "",
                "defaultSystemChatPrompt" => "",
                "defaultConfig" => []
            ],
            [
                "model" => "deepseek",
                "name" => "Deepseek",
                "avatar" => "/imgs/model/openai.svg",
                "maxContext" => 125000,
                "maxResponse" => 4000,
                "quoteMaxToken" => 100000,
                "maxTemperature" => 1.2,
                "charsPointsPrice" => 0,
                "censor" => false,
                "vision" => false,
                "datasetProcess" => false,
                "usedInClassify" => true,
                "usedInExtractFields" => true,
                "usedInToolCall" => true,
                "usedInQueryExtension" => true,
                "toolChoice" => true,
                "functionCall" => false,
                "customCQPrompt" => "",
                "customExtractPrompt" => "",
                "defaultSystemChatPrompt" => "",
                "defaultConfig" => []
            ],
            [
                "model" => "gemini-pro",
                "name" => "Gemini",
                "avatar" => "/imgs/model/google.png",
                "maxContext" => 128000,
                "maxResponse" => 4000,
                "quoteMaxToken" => 100000,
                "maxTemperature" => 1.2,
                "charsPointsPrice" => 0,
                "censor" => false,
                "vision" => true,
                "datasetProcess" => false,
                "usedInClassify" => false,
                "usedInExtractFields" => false,
                "usedInToolCall" => false,
                "usedInQueryExtension" => false,
                "toolChoice" => true,
                "functionCall" => false,
                "customCQPrompt" => "",
                "customExtractPrompt" => "",
                "defaultSystemChatPrompt" => "",
                "defaultConfig" => []
            ]
        ],
        "vectorModels" => [
            [
                "model" => "text-embedding-ada-002",
                "name" => "Embedding-2",
                "avatar" => "/imgs/model/openai.svg",
                "charsPointsPrice" => 0,
                "defaultToken" => 512,
                "maxToken" => 3000,
                "weight" => 100,
                "dbConfig" => [],
                "queryConfig" => []
            ]
        ],
        "reRankModels" => [],
        "audioSpeechModels" => [
            [
                "model" => "tts-1",
                "name" => "OpenAI TTS1",
                "charsPointsPrice" => 0,
                "voices" => [
                    ["label" => "Alloy", "value" => "alloy", "bufferId" => "openai-Alloy"],
                    ["label" => "Echo", "value" => "echo", "bufferId" => "openai-Echo"],
                    ["label" => "Fable", "value" => "fable", "bufferId" => "openai-Fable"],
                    ["label" => "Onyx", "value" => "onyx", "bufferId" => "openai-Onyx"],
                    ["label" => "Nova", "value" => "nova", "bufferId" => "openai-Nova"],
                    ["label" => "Shimmer", "value" => "shimmer", "bufferId" => "openai-Shimmer"]
                ]
            ]
        ],
        "whisperModel" => [
            "model" => "whisper-1",
            "name" => "Whisper1",
            "charsPointsPrice" => 0
        ]
    ];
    print json_encode($llms);
    exit;
}



if($action == "listdataset" && $pagesize >= 6)  {
    $From   = $pageid * $pagesize;
    $rs_a   = [];
    $RS     = [];
    $RS['pageid']   = $pageid;
    $RS['pagesize'] = $pagesize;
    $RS['data']     = $rs_a;
    $RS['from']     = $From;
    $RS['total']    = sizeof($rs_a);
    $RS['allpages'] = ceil($RS['total']/$pagesize);

    print json_encode($RS);
    exit;
}

?>
