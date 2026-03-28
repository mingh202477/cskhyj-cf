
<?php
session_start();
require __DIR__ . '/../config.php';

$username = trim($_GET['username'] ?? '');
$email = trim($_GET['email'] ?? '');
$message = '';

if (empty($username) || empty($email)) {
    $message = '缺少必要参数。';
} else {
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($mysqli->connect_error) {
        $message = '数据库连接失败：' . $mysqli->connect_error;
    } else {
        // 检查用户是否存在且 type=0
        $stmt = $mysqli->prepare("SELECT id, username, email, type FROM useradmins WHERE username = ? AND email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            $message = '用户名与邮箱不匹配。';
        } else {
            $user = $result->fetch_assoc();
            if ($user['type'] != 0) {
                $message = '该账户已经验证，无需重新发送。';
            } else {
                // 生成新令牌
                $token = bin2hex(random_bytes(32));
                // 更新数据库中的令牌
                $update = $mysqli->prepare("UPDATE useradmins SET verification_token = ? WHERE id = ?");
                $update->bind_param("si", $token, $user['id']);
                if ($update->execute()) {
                    // 调用发送邮件函数
                    require_once __DIR__ . '/../lib/mailer.php';
                    $mailResult = sendVerificationEmail($user['email'], $user['id'], $user['username'], $token);
                    if ($mailResult === true) {
                        $message = '验证邮件已重新发送，请查收邮箱。';
                    } else {
                        $message = '邮件发送失败：' . $mailResult;
                    }
                } else {
                    $message = '数据库更新失败，请稍后重试。';
                }
                $update->close();
            }
        }
        $stmt->close();
        $mysqli->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>重新发送验证邮件</title>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; text-align: center; margin-top: 100px; }
        .message { margin: 20px auto; padding: 20px; border: 1px solid #ccc; width: 500px; background: #f9f9f9; }
        a { color: #3a7ca5; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="message">
        <h2><?php echo htmlspecialchars($message); ?></h2>
        <p><a href="index.php">返回登录页面</a></p>
    </div>
</body>
</html>