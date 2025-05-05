<?php
//SchoolAI 统一身份认证 Demo程序
//2025-05-05


$redirect_uri       = 'http://localhost:8888/api/oauth/client/callback.php';                        //授权成功以后, 第三方应用的地址, 在这个地址中, 获得当前用户的信息
$client_id          = 'b721eb9bef5dbbffd625893d94089763';                                           //在SchoolAI -> 统一身份认证 -> 第三方应用中获取
$client_secret      = '38f5eb5a441675ad7df78484960d368805f28eb0df36198cd992633d9923dda9';           //在SchoolAI -> 统一身份认证 -> 第三方应用中获取

$OAuthServerUri     = 'http://localhost:8888/api/oauth';
$authorize_uri      = $OAuthServerUri . '/authorization.php';
$access_token_uri   = $OAuthServerUri . '/accessTokenToUserInfo.php';
$code_token_uri     = $OAuthServerUri . '/codeToToken.php';


//http请求函数
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