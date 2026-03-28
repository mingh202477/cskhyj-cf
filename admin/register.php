<?php
session_start();
require __DIR__ . '/../config.php';

//require_once __DIR__ .'/../lib/sm4.php';



$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'register') {
    if ($register_enabled != 1) {
        $error = '系统当前关闭注册，请稍后再试。';
    } else {
        $username = trim($_POST['username'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm  = $_POST['confirm_password'] ?? '';
        $captcha  = strtoupper(trim($_POST['captcha'] ?? ''));

        // 基本验证
        if (empty($username) || empty($email) || empty($password) || empty($confirm)) {
            $error = '所有字段均为必填项！';
        } elseif ($password !== $confirm) {
            $error = '两次输入的密码不一致！';
        } elseif (strlen($password) < 6) {
            $error = '密码长度至少为6位！';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = '邮箱格式不正确！';
        } elseif (empty($captcha) || !isset($_SESSION['captcha_code']) || $captcha !== $_SESSION['captcha_code']) {
            $error = '验证码错误！';
        } else {
            // 连接数据库
            $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            if ($mysqli->connect_error) {
                $error = '数据库连接失败：' . $mysqli->connect_error;
            } else {
                // 检查用户名或邮箱是否已存在
                $stmt = $mysqli->prepare("SELECT id FROM useradmins WHERE username = ? OR email = ?");
                $stmt->bind_param("ss", $username, $email);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $error = '用户名或邮箱已被注册！';
                } else {
                    // 使用 Argon2 哈希密码
                    $hash = password_hash($password, PASSWORD_ARGON2ID); // PHP 7.3+ 支持 Argon2id

                    // 插入新用户
                    $stmt = $mysqli->prepare("INSERT INTO useradmins (username, email, password_hash) VALUES (?, ?, ?)");
                    $stmt->bind_param("sss", $username, $email, $hash);
                    if ($stmt->execute()) {
                        $success = '注册成功！正在跳转到登录页面...';
                        header("Refresh:2; url=index.php");
                    } else {
                        $error = '注册失败：' . $stmt->error;
                    }
                }
                $stmt->close();
                $mysqli->close();
            }
        }
        unset($_SESSION['captcha_code']);
    }
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>管理注册</title>
</head>
<body>
<div align="center">
    <h2>管理注册</h2>
    <?php if ($register_enabled != 1): ?>
        <p style="color:red;"><strong>系统当前关闭注册，请稍后再试。</strong></p>
    <?php else: ?>
        <?php if ($error): ?>
            <p style="color:red;"><strong>错误：</strong><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <?php if ($success): ?>
            <p style="color:green;"><strong><?php echo htmlspecialchars($success); ?></strong></p>
        <?php endif; ?>
        <form method="post" action="register.php">
            <input type="hidden" name="action" value="register">
            <table border="0" align="center">
                <tr>
                    <td>用户名：</td>
                    <td><input type="text" name="username" required value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"></td>
                </tr>
                <tr>
                    <td>邮箱地址：</td>
                    <td><input type="email" name="email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"></td>
                </tr>
                <tr>
                    <td>密码：</td>
                    <td><input type="password" name="password" required minlength="6"></td>
                </tr>
                <tr>
                    <td>确认密码：</td>
                    <td><input type="password" name="confirm_password" required minlength="6"></td>
                </tr>
                <tr>
                    <td>验证码：</td>
                    <td>
                        <input type="text" name="captcha" required maxlength="6" autocomplete="off" style="text-transform:uppercase">
                        <img src="/../lib/captcha.php" alt="验证码" id="captchaImg" onclick="this.src='/../lib/captcha.php?t='+Math.random()" style="cursor:pointer; vertical-align:middle;">
                        <a href="javascript:void(0)" onclick="document.getElementById('captchaImg').src='/../lib/captcha.php?t='+Math.random()">换一张</a>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="center"><input type="submit" value="注 册"></td>
                </tr>
            </table>
        </form>
        <p>
            已有账号？ <a href="index.php">立即登录</a><br>
            
        </p>
    <?php endif; ?>
</div>
</body>
</html>