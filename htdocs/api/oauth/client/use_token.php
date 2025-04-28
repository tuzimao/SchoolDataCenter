<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accessToken = $_POST['access_token'] ?? '';

    if (!$accessToken) {
        echo "Access Token is required.";
        exit;
    }

    echo "<h2>Simulating access to protected resource with token:</h2>";
    echo "<pre>" . htmlspecialchars($accessToken) . "</pre>";

    // 清除 POST 中的 access_token，防止重复验证方式
    $_POST = [];

    // 模拟 HTTP 请求
    $_SERVER['REQUEST_METHOD'] = 'GET';
    $_SERVER['HTTP_AUTHORIZATION'] = 'Bearer ' . $accessToken;

    echo "<h2>Response from resource.php:</h2>";
    include '../resource.php';

    echo '<hr><a href="use_token.php">Back</a>';
    exit;
}
?>

<h2>Use Access Token to Access Protected Resource</h2>
<form method="post">
    <label for="access_token">Access Token:</label><br>
    <input type="text" name="access_token" size="80" required><br><br>
    <input type="submit" value="Access Resource">
</form>
