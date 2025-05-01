<?php
session_start();
require_once('server.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $OauthDb->prepare('SELECT * FROM users WHERE username = :username');
    $stmt->execute([':username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['DANDIAN_OAUTH_SERVER_USER_ID']    = $user['username'];

        // 如果登录前是 authorize 重定向来的，跳回
        if (isset($_SESSION['redirect_after_login'])) {
            $redirect = $_SESSION['redirect_after_login'];
            unset($_SESSION['redirect_after_login']);
            header("Location: $redirect");
        } else {
            echo "登录成功！";
        }
        exit;
    } else {
        $error = "用户名或密码错误";
    }
}
?>

<h2>登录</h2>
<?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
<form method="post">
    <label>用户名：<input type="text" name="username" required></label><br>
    <label>密码：<input type="password" name="password" required></label><br>
    <button type="submit">登录</button>
</form>
<p>没有账户？<a href="registerUser.php">注册</a></p>
