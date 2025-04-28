<?php
require '../vendor/autoload.php'; // 使用 composer 安装 bshaffer/oauth2-server-php


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


