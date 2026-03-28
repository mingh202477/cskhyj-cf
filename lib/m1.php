<?php
//发件


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
//引入需要的
require __DIR__ . '/../PHPMailer/src/Exception.php';
require __DIR__ . '/../PHPMailer/src/PHPMailer.php';
require __DIR__ . '/../PHPMailer/src/SMTP.php';
require __DIR__ . "/../config.php";

$mail = new PHPMailer(true);  
try {
    //服务器配置
    $mail->CharSet ="UTF-8";                     //设定邮件编码
    $mail->SMTPDebug = 0;                        // 调试模式输出
    $mail->isSMTP();                             // 使用SMTP
    $mail->Host = $email_host;  
    $mail->SMTPAuth = $SMTPAuth; 
    $mail->Username = $email_Username; 
    $mail->Password = $email_Password;
    $mail->SMTPSecure = $email_SMTPSecure; 
    $mail->Port = $email_Port;

    $mail->setFrom($setFrom1, 'Mailer');  //发件人
    $mail->addAddress('1492657723@qq.com', 'Joe');  // 收件人
    $mail->addReplyTo($add1, 'info'); //回复的时候回复给哪个邮箱 建议和发件人一致


    //Content
    $mail->isHTML(true);                                  // 是否以HTML文档格式发送  发送后客户端可直接显示对应HTML内容
    $mail->Subject = '验证管理员账号' . time();
    $mail->Body    = '<h1>验证管理员账号！</h1><p>请点击以下链接验证您的管理员账号：</p><p><a href="' . $yu . 'admin/verify.php">验证链接</a></p> <p>如果您没有请求验证管理员账号，请忽略此邮件。</p><p>收到此邮件是因为您是管理员账号的注册者。</p>';
    $mail->AltBody = '验证管理员账号！请点击以下链接验证您的管理员账号：' . $yu . 'admin/verify.php 如果您没有请求验证管理员账号，请忽略此邮件。收到此邮件是因为您是管理员账号的注册者。';

    $mail->send();
    echo '邮件发送成功';
} catch (Exception $e) {
    echo '邮件发送失败: ', $mail->ErrorInfo;
}