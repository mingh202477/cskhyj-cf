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
            require_once __DIR__ . '/lib/head.php';
        ?>
        <h1>新闻发布</h1>
        <P>快速管理新闻（网站日志）的发布，和网站部分信息的更新</P>
        
   </BODY>
   </HTML>
