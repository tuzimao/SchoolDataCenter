<?php
session_start();
require_once('../include.inc.php');

if (!isset($_SESSION['DANDIAN_OAUTH_SERVER_USER_ID'])) {
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    $params = http_build_query([ 'response_type' => $_GET['response_type'], 'client_id' => $_GET['client_id'], 'redirect_uri' => $_GET['redirect_uri'], 'state' => $_GET['state'] ]);
    header('Location: http://localhost:3000/oauth?' . $params);
    exit;
}

require_once('server.php');

$request    = OAuth2\Request::createFromGlobals();
$response   = new OAuth2\Response();

// 检查授权请求是否合法
if (!$server->validateAuthorizeRequest($request, $response)) {
    $response->send();
    //file_put_contents('debug.log', "[" . date('Y-m-d H:i:s') . "] Invalid authorize request\n", FILE_APPEND);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    ?>
    <form method="post">
        <h2>授权请求</h2>
        <p>应用 <strong><?= htmlspecialchars($_GET['client_id']) ?></strong> 请求访问你的账户。</p>
        <button name="authorized" value="Yes" type="submit">同意</button>
        <button name="authorized" value="No" type="submit">拒绝</button>
    </form>
    <?php
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['authorized'] == 'Yes') {
    //同意授权
    $isAuthorized = true;
    $server->handleAuthorizeRequest($request, $response, $isAuthorized, $_SESSION['DANDIAN_OAUTH_SERVER_USER_ID']);
    $response->send();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['authorized'] == 'No') {
    //不同意授权
}

