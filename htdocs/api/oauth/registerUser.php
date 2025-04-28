<?php
session_start();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $OauthDb->prepare('INSERT INTO users (username, password) VALUES (:username, :password)');
    try {
        $stmt->execute([
            ':username' => $username,
            ':password' => password_hash($password, PASSWORD_BCRYPT) // 加密保存
        ]);
        header('Location: login.php');
        exit;
    } catch (PDOException $e) {
        $error = "注册失败，用户名可能已存在";
    }
}
?>

<h2>注册</h2>
<?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
<form method="post">
    <label>用户名：<input type="text" name="username" required></label><br>
    <label>密码：<input type="password" name="password" required></label><br>
    <button type="submit">注册</button>
</form>
<p>已有账户？<a href="login.php">登录</a></p>
