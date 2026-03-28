<?php
session_start();
require __DIR__ . '/../config.php';

$token = trim($_GET['token'] ?? '');
$message = '';

if (empty($token)) {
    $message = '无效的验证链接。';
} else {
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($mysqli->connect_error) {
        $message = '数据库连接失败：' . $mysqli->connect_error;
    } else {
        // 查找令牌对应的未验证用户
        $stmt = $mysqli->prepare("SELECT id, username, email FROM useradmins WHERE verification_token = ? AND type = 0");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            $message = '验证链接无效或账户已经验证。';
        } else {
            $user = $result->fetch_assoc();
            // 更新 type 为 1，清空令牌
            $update = $mysqli->prepare("UPDATE useradmins SET type = 1, verification_token = NULL WHERE id = ?");
            $update->bind_param("i", $user['id']);
            if ($update->execute()) {
                $message = '恭喜，您的管理员账号已验证成功！现在可以登录了。';
            } else {
                $message = '验证失败，请稍后重试。';
            }
            $update->close();
        }
        $stmt->close();
        $mysqli->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>邮箱验证</title>
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