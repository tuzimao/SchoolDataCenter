<?php
file_put_contents(__DIR__ . '/callback_debug.log', "[" . date('Y-m-d H:i:s') . "] Called with code: " . ($_GET['code'] ?? 'none') . "\n", FILE_APPEND);

if (!isset($_GET['code'])) {
    die('No auth code received');
}

echo "<h3>Callback triggered with code: " . htmlspecialchars($_GET['code']) . "</h3>";

// 模拟 POST 请求来替代 curl
$_SERVER['REQUEST_METHOD'] = 'POST';
$_POST = [
  'grant_type' => 'authorization_code',
  'code' => $_GET['code'],
  'redirect_uri' => 'http://localhost/api/oauth/client/callback.php',
  'client_id' => 'f4fd703bbd1582e689b5311840db55ed',
  'client_secret' => '7547b341efcb298cf63970164f8268bca8a9a28726a2ce45460243d7b15431d3'
];

require '../../vendor/autoload.php'; // 使用 composer 安装 bshaffer/oauth2-server-php


$dsn        = "mysql:dbname=myedu;host=localhost:3386";
$username   = "root";
$password   = "6jF0^#12x6^S2zQ#t";

$oauthDb = new PDO("mysql:host=localhost:3386;dbname=myedu;charset=utf8mb4", "root", "6jF0^#12x6^S2zQ#t");
$oauthDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$storage = new OAuth2\Storage\Pdo(array('dsn' => $dsn, 'username' => $username, 'password' => $password));

$server = new OAuth2\Server($storage, [
    'access_lifetime' => 3600,
    'enforce_state' => true,
    'allow_implicit' => false
]);

// 支持的授权类型
$server->addGrantType(new OAuth2\GrantType\AuthorizationCode($storage));
$server->addGrantType(new OAuth2\GrantType\ClientCredentials($storage));
$server->addGrantType(new OAuth2\GrantType\UserCredentials($storage));
$server->addGrantType(new OAuth2\GrantType\RefreshToken($storage));

$request = OAuth2\Request::createFromGlobals();
$response = new OAuth2\Response();
// 处理 token 请求（code 换 token）
$server->handleTokenRequest($request, $response)->send();
