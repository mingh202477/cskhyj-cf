<?php
//在这里书写你的域名(必须写https://或http:// 并写/，例子：https://www.example.com/，不写https://或http://或/会导致邮件中的链接无法点击和其他报错！！！！)
$yu = 'http://127.0.0.1/';
// 注册开关：1 开放注册，2 关闭注册（可在此处修改）
$register_enabled = 1;   // 修改为 2 即可关闭注册

// 数据库配置
define('DB_HOST', 'localhost');       
define('DB_USER', 'root');            
define('DB_PASS', '');                
define('DB_NAME', 'chaofan');  

//配置电子邮件的SMTP
$email_host = '';
$SMTPAuth = true;//允许 SMTP 认证
$email_Username = '';
$email_Password = '';
$email_SMTPSecure = 'ssl';// 允许 TLS 或者ssl协议
$email_Port = 465;//服务器端口
//设定发件人(163要求与SMTP用户名一致)
$setFrom1 = '';
//设定回复地址(建议与发件人一致)
$add1 = '';



