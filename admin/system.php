<?php
require_once __DIR__ . '/../lib/auth_check.php';

if (!isLoggedIn()) {
    header('Location: index.php');
    exit;
}
echo "当前管理：" . getCurrentUser();

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
     "http://www.w3.org/TR/html4/strict.dtd">
<HTML lang="zh">
   <HEAD>
      <TITLE>★オムライス大好き同盟★</TITLE>
      <meta http-equiv="Content-Type" content="text/html"; charset=UTF-8>
      <!-- 兼容sb.IE浏览器  -->
      <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
      <!-- 兼容现代浏览器 -->
     <link rel="icon" href="/favicon.ico" type="image/x-icon">
     
    <style type="text/css">

    </style>
    </HEAD>
   <BODY>
        <?php
            require_once __DIR__ . '/../admin/lib/head.php';
        ?>
        <h1>系统设置</h1>
        <P>系统概述：</P>
        <p>注意：php要大于等于7.3 测试环境php7.3.11</p>
        <P>PHP版本：<?php echo phpversion();  ?></P>
        <p>PHP详细信息：<a href="phps.php">查看</a></p>
        <p>服务器ip：
        <?php 
        $serverIP = gethostbyname(gethostname());
        echo $serverIP;
         ?>
        </p>
        <p>系统信息：<?php         echo php_uname();  ?>
        </p>
        <hr>
        <p>关闭管理员登录请去config.php中的：</p>
        <p>$register_enabled = 1;</p>
        <p>修改为2保存即可</p>
        <hr>
        <p>电子邮件（发件）设置请去config.php</p>
        <hr>
        



   </BODY>
   </HTML>
