<?php
// 禁用输出缓冲
@ini_set('output_buffering', 'off');
@ini_set('zlib.output_compression', 0);
@ini_set('implicit_flush', 1);

exit;

ini_set('max_execution_time', 0);
ini_set('memory_limit', '512M');
ignore_user_abort(false); // 客户端断开时停止脚本

header('Content-Type: text/event-stream'); // 必须设置
header('Cache-Control: no-cache');        // 禁用缓存
header('Connection: keep-alive');         // 保持连接
header('X-Accel-Buffering: no');          // 针对Nginx的特殊配置

require_once('../include.inc.php');

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Authorization, Content-Type");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

CheckAuthUserLoginStatus();

// 输出初始注释（可选）
ob_flush();
flush();

// 模拟实时数据推送
$counter = 0;
while (true && $GLOBAL_USER->USER_ID != "") {
    // 检查客户端是否断开（PHP默认检测不到，需要手动处理）
    if (connection_aborted()) {
        file_put_contents('sse.log', '客户端断开: '.date('Y-m-d H:i:s')."\n", FILE_APPEND);
        exit();
    }

    $sql     = "select * from data_msgreminder where MSG_ISREAD = 0  and MSG_TO = '".$GLOBAL_USER->USER_ID."' order by id desc limit 1";
    $rs      = $db->Execute($sql);
    $Element = $rs->fields;

    //设置为已读
    $sql = "update data_msgreminder set MSG_ISREAD = 1 where id = '".$Element['id']."'";
    $db->Execute($sql);

    // 三种消息格式示例（任选其一）：
    
    // 1. 仅发送数据（客户端监听默认message事件）
    // echo 'data: ' . json_encode($data) . "\n\n";
    
    // 2. 发送带事件类型的数据（客户端可监听特定事件）
    if($Element)  {
        echo 'data: ' . json_encode($Element) . "\n\n";
    }
    
    // 3. 发送带ID的数据（客户端会自动记录lastEventId）
    // echo "id: " . uniqid() . "\n";
    // echo 'data: ' . json_encode($data) . "\n\n";

    ob_flush();
    flush();
    
    // 控制推送频率（秒）
    sleep(1);
}
?>