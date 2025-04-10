<?php
require_once '../vendor/autoload.php';

OAuth2\Autoloader::register();

// 创建一个PDO实例
$dsn        = "mysql:dbname=myedu;host=localhost:3386";
$username   = "root";
$password   = "6jF0^#12x6^S2zQ#t";

$storage = new OAuth2\Storage\Pdo(array('dsn' => $dsn, 'username' => $username, 'password' => $password));
$server = new OAuth2\Server($storage);
$server->addGrantType(new OAuth2\GrantType\AuthorizationCode($storage)); // or any grant type you like!
$server->handleTokenRequest(OAuth2\Request::createFromGlobals())->send();


print_R($server);
exit;

// 创建一个存储库实例
$storage = new OAuth2StoragePdo($pdo);

// 创建一个授权服务器实例
$server = new OAuth2Server($storage);

// 添加支持的授权类型
$server->addGrantType(new OAuth2GrantTypeAuthorizationCode($storage));

// 处理授权请求
$request = OAuth2Request::createFromGlobals();
$response = new OAuth2Response();
if (!$server->validateAuthorizeRequest($request, $response)) {
    $response->send();
    die;
}
// 显示授权页面
if (empty($_POST)) {
    exit('
        <form method="post">
          <label for="username">Username:</label>
          <input type="text" id="username" name="username"><br><br>
          <label for="password">Password:</label>
          <input type="password" id="password" name="password"><br><br>
          <input type="submit" value="Authorize">
        </form>
    ');
}

// 处理授权请求
$is_authorized = ($_POST['username'] == 'admin' && $_POST['password'] == 'admin');
$server->handleAuthorizeRequest($request, $response, $is_authorized);
if ($is_authorized) {
    $response->send();
} else {
    echo '授权失败';
}