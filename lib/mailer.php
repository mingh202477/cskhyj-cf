<?php
// lib/mailer.php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../PHPMailer/src/Exception.php';
require_once __DIR__ . '/../PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../PHPMailer/src/SMTP.php';
require_once __DIR__ . "/../config.php";

/**
 * 发送管理员邮箱验证邮件
 * @param string $toEmail 收件人邮箱
 * @param int $userId 用户ID（未使用但可保留）
 * @param string $username 用户名
 * @param string $token 验证令牌
 * @return true|string 成功返回 true，失败返回错误信息
 */
function sendVerificationEmail($toEmail, $userId, $username, $token) {
    global $email_host, $SMTPAuth, $email_Username, $email_Password, $email_SMTPSecure, $email_Port, $setFrom1, $add1, $yu;

    $mail = new PHPMailer(true);
    try {
        // 服务器配置
        $mail->CharSet = "UTF-8";
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = $email_host;
        $mail->SMTPAuth = $SMTPAuth;
        $mail->Username = $email_Username;
        $mail->Password = $email_Password;
        $mail->SMTPSecure = $email_SMTPSecure;
        $mail->Port = $email_Port;

        $mail->setFrom($setFrom1, 'Mailer');
        $mail->addAddress($toEmail, $username);
        $mail->addReplyTo($add1, 'info');

        // 内容
        $mail->isHTML(true);
        $mail->Subject = '验证管理员账号 - ' . date('Y-m-d H:i:s');
        $verificationLink = rtrim($yu, '/') . '/admin/verify.php?token=' . urlencode($token);
        $mail->Body = "<h1>验证管理员账号</h1><p>您好 {$username}，请点击以下链接验证您的管理员账号：</p><p><a href=\"{$verificationLink}\">验证链接</a></p><p>如果您没有请求验证管理员账号，请忽略此邮件。</p>";
        $mail->AltBody = "验证管理员账号！请点击以下链接验证您的管理员账号：{$verificationLink} 如果您没有请求验证管理员账号，请忽略此邮件。";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return $mail->ErrorInfo;
    }
}