<?php

$redirect_uri       = 'http://localhost:8888/api/oauth/client/callback.php';
$client_id          = 'b721eb9bef5dbbffd625893d94089763';                                           //From registerClient.php
$client_secret      = '38f5eb5a441675ad7df78484960d368805f28eb0df36198cd992633d9923dda9';           //From registerClient.php
$authorize_uri      = 'http://localhost:8888/api/oauth/authorization.php';
$access_token_uri   = 'http://localhost:8888/api/oauth/accessTokenToUserInfo.php';
$code_token_uri   = 'http://localhost:8888/api/oauth/codeToToken.php';


function httpRequest($url, $method = 'GET', $data = null, $headers = []) {
    $ch = curl_init();
    
    // 基础配置
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // 跟随重定向
    
    // 请求方法
    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, is_array($data) ? http_build_query($data) : $data);
        }
    }
    
    // 自定义 Headers
    if (!empty($headers)) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }
    
    // SSL 配置
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    
    // 执行请求
    $response = curl_exec($ch);
    $error = curl_error($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    curl_close($ch);
    
    if ($error) {
        throw new Exception("cURL Error: " . $error);
    }
    
    return [
        'status' => $httpCode,
        'body' => $response
    ];
}

?>