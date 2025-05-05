<?php
require_once('../vendor/autoload.php');
require_once('../config.inc.php');

$dsn        = "mysql:host=".$DB_HOST.";dbname=".$DB_DATABASE.";charset=utf8mb4";
$OAuthDb    = new PDO($dsn, $DB_USERNAME, $DB_PASSWORD);
$OAuthDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$storage = new OAuth2\Storage\Pdo(array('dsn' => $dsn, 'username' => $DB_USERNAME, 'password' => $DB_PASSWORD));

$server = new OAuth2\Server($storage, [
    'access_lifetime' => 86400,
    'enforce_state' => true,
    'allow_implicit' => false
]);

// 支持的授权类型
$server->addGrantType(new OAuth2\GrantType\AuthorizationCode($storage));
$server->addGrantType(new OAuth2\GrantType\ClientCredentials($storage));
$server->addGrantType(new OAuth2\GrantType\UserCredentials($storage));
$server->addGrantType(new OAuth2\GrantType\RefreshToken($storage));
