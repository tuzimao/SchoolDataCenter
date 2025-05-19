<?php
session_start();
require 'users.php';
require 'utils.php';

$service = $_GET['service'] ?? '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (isset($users[$username]) && $users[$username] === $password) {
        $_SESSION['user'] = $username;
        $ticket = generateTicket();
        saveTicket($ticket, $username);
        header("Location: $service?ticket=$ticket");
        exit;
    } else {
        $error = '用户名或密码错误';
    }
}

if (isset($_SESSION['user'])) {
    $ticket = generateTicket();
    saveTicket($ticket, $_SESSION['user']);
    header("Location: $service?ticket=$ticket");
    exit;
}
?>

<!-- 简单登录表单 -->
<form method="POST">
    用户名: <input type="text" name="username"><br>
    密码: <input type="password" name="password"><br>
    <input type="hidden" name="service" value="<?= htmlspecialchars($service) ?>">
    <button type="submit">登录</button>
</form>
<?php if ($error) echo "<p style='color:red;'>$error</p>"; ?>
