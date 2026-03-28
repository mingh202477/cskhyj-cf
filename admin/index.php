<?php
session_start();
//设置
require __DIR__ . '/../config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $captcha  = strtoupper(trim($_POST['captcha'] ?? ''));

    if (empty($captcha) || !isset($_SESSION['captcha_code']) || $captcha !== $_SESSION['captcha_code']) {
        $error = '验证码错误，请重新输入！';
    } else {
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($mysqli->connect_error) {
            $error = '数据库连接失败: ' . $mysqli->connect_error;
        } else {
            $stmt = $mysqli->prepare("SELECT id, username, email, password_hash, type FROM useradmins WHERE username = ? AND email = ?");
            $stmt->bind_param("ss", $username, $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 0) {
                $error = '用户名与邮箱不匹配，请检查输入！';
            } else {
                $user = $result->fetch_assoc();
                // 使用 Argon2 验证密码
                if (password_verify($password, $user['password_hash'])) {
                    // 检查 type 是否为 0（未验证）
                    if ($user['type'] == 0) {
                        // 账户未验证，拒绝登录，提供重新发送验证邮件链接
                        $resendLink = 'resend_verification.php?username=' . urlencode($username) . '&email=' . urlencode($email);
                        $error = '您没有通过邮箱验证，请先验证您的邮箱。';
                        $error .= '<br><a href="' . $resendLink . '">点击重新发送验证邮件</a>';
                    } else {
                        // 已验证，登录成功
                        $_SESSION['user_logged'] = true;
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['username'] = $user['username'];
                        $success = '登录成功！正在跳转...';
                        header("Refresh:2; url=index1.php");
                        exit; // 重要：防止后续输出
                    }
                } else {
                    $error = '密码错误，请重新输入！';
                }
            }
            $stmt->close();
            $mysqli->close();
        }
    }
    unset($_SESSION['captcha_code']);
}

$isLogged = isset($_SESSION['user_logged']) && $_SESSION['user_logged'] === true;
if ($isLogged) {
    echo '<h2>欢迎回来，' . htmlspecialchars($_SESSION['username']) . '！</h2>';
    echo '<p>您已成功登录系统。</p>';
    echo '<p><a href="logout.php">退出登录</a> | <a href="register.php">注册新账户</a></p>';
    echo '<p>跳转中。。。。。。。请稍等</p>';
    echo '<a href="index1.php">没跳转？手动跳转！</a>';
     header("Refresh:2; url=index1.php");
  
    echo '<p>copyright by mingh (c)2003</p>';
    exit;
}
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
    
     body{
        /*  蛋炒饭鼠标  */
        cursor: url('danchaofanshubiao.cur'), auto;
        /*  背景      */
        background-color: #ADD8E6;
     }
     .site-header{
        /*这是居中用的 (awa) */
        height: 100px;
        text-align: center;
     }
     .px20{
        /*20px的行高*/
        line-height: 20px;
     }
     .px30{
        /*30px的行高*/
        line-height: 30px;
     }
     .px40{
        /*40px的行高*/
        line-height: 40px;
     }
     .px50{
        /*50px的行高*/
        line-height: 50px;
     }
     .px60{
        /*60px的行高*/
        line-height: 60px;
     }
     .right_up_jiao{
         /*右上角*/
         position: fixed;
         right: 20px;
     }
     
     /* ===== 浅蓝色复古风格（2000年Windows风） ===== */
     body {
         background: #b0e0e6 url('data:image/gif;base64,R0lGODlhIAAgAIAAAP///wAAACH5BAEAAAAALAAAAAAgACAAAAL+hI+py+0Po5y02ouz3rz7D4biSJbmiabqyrbuC8fyTNf2jef6zvf+DwwKh8Si8YhMKpfMpvMJjUqn1Kr1is1qt9yu9wsOi8fksvmMTqvX7Lb7DY/L5/S6/Y7P6/f8vv8PGCg4SFhoeIiYqLjI2Oj4CBkpOUlZaXmJmam5ydnp+QkaKjpKWmp6ipqqusra6voKGys7S1tre4ubq7vL2+v7CxwsPExcbHyMnKy8zNzs/AwdLT1NXW19jZ2tvc3d7f0OHi4+Tl5ufo6err7O3u7+Dh8vP09fb3+Pn6+/z9/v/wJwwoMKBAgQYAAwIAOw==') repeat;
         background-color: #b0e0e6;
         font-family: 'MS PGothic', 'Comic Sans MS', 'Times New Roman', monospace;
         margin: 0;
         padding: 20px;
         color: #2c3e50;
     }
     
     /* 主容器 - 经典银灰窗口 */
     .vintage-container {
         max-width: 700px;
         margin: 0 auto;
         background: #c0c0c0;
         border: 3px ridge #e0e0e0;
         border-right-color: #808080;
         border-bottom-color: #808080;
         padding: 15px 20px 20px 20px;
         color: #000000;
     }
     
     /* 标题区域 - 深蓝色调突出浅蓝主题 */
     .site-header h1 {
         background: #2c6e8f;
         color: #fff5cc;
         padding: 6px 10px;
         font-size: 24px;
         letter-spacing: 2px;
         border: 2px outset #ffdd99;
         display: inline-block;
         margin-top: 0;
         text-shadow: 2px 2px 0 #1a4a66;
         font-family: 'Comic Sans MS', 'MS PGothic', cursive;
     }
     
     .site-header h4 {
         background: #eef5ff;
         padding: 8px;
         border: 1px inset #808080;
         font-size: 14px;
         color: #2c6e8f;
     }
     
     /* 管理员登录标题 */
     h1:first-of-type {
         background: #6a9fb5;
         color: #fff0cc;
         border: 2px outset #c0d0e0;
         padding: 5px 15px;
         font-size: 22px;
         display: inline-block;
         font-family: 'Comic Sans MS', cursive;
         margin-top: 20px;
         margin-bottom: 15px;
     }
     
     /* 错误/成功消息统一风格 */
     .error-message, .success-message {
         background: #fff6cf;
         border: 2px ridge #b22222;
         padding: 6px;
         font-weight: bold;
         font-family: monospace;
         color: #000000;
         margin: 10px 0;
     }
     .success-message {
         border-color: #2e8b57;
         color: #006400;
     }
     
     /* 表单区域 */
     .login-form {
         background: #d4d0c8;
         border: 2px inset #ffffff;
         border-right-color: #808080;
         border-bottom-color: #808080;
         padding: 15px 20px;
         margin: 15px auto;
         width: 90%;
         max-width: 450px;
         text-align: left;
     }
     
     .form-row {
         clear: both;
         margin-bottom: 12px;
         overflow: hidden;
     }
     
     .form-row label {
         float: left;
         width: 90px;
         padding-top: 5px;
         font-weight: bold;
         color: #2c3e50;
         text-align: right;
         font-size: 14px;
     }
     
     .form-row .input-field {
         margin-left: 100px;
     }
     
     .form-row input[type="text"],
     .form-row input[type="email"],
     .form-row input[type="password"] {
         width: 220px;
         background: #fffef7;
         border: 2px inset #a0a0a0;
         padding: 4px;
         font-family: monospace;
         font-size: 13px;
         color: #000000;
     }
     
     /* 验证码区域 */
     .captcha-group {
         margin-left: 100px;
         display: inline-block;
     }
     
     .captcha-group input {
         width: 100px !important;
         margin-right: 6px;
         vertical-align: middle;
     }
     
     .captcha-group img {
         border: 2px outset #c0c0c0;
         background: #ffffff;
         vertical-align: middle;
         cursor: pointer;
     }
     
     .captcha-group a {
         color: #2c6e8f;
         font-size: 12px;
         margin-left: 6px;
         text-decoration: underline;
         cursor: pointer;
     }
     
     /* 提交按钮 */
     .submit-row {
         margin-top: 20px;
         text-align: center;
     }
     
     input[type="submit"] {
         background: #b0c4de;
         border: 2px outset #d4e3f0;
         border-right-color: #6c8ea0;
         border-bottom-color: #6c8ea0;
         padding: 5px 20px;
         font-family: 'MS PGothic', monospace;
         font-weight: bold;
         font-size: 14px;
         cursor: pointer;
         color: #1e3a5f;
     }
     
     input[type="submit"]:active {
         border: 2px inset #d4e3f0;
         background: #9cb0c0;
     }
     
     /* 底部信息 */
     hr {
         border: none;
         border-top: 2px ridge #c0c0c0;
         margin: 20px 0 10px;
     }
     
     footer p {
         font-size: 11px;
         color: #2c3e50;
         background: #e0e0e0;
         display: inline-block;
         padding: 3px 8px;
         border: 1px inset #808080;
         margin: 4px 0;
     }
     
     /* TIME 显示 */
     .site-header p:first-of-type {
         background: #2c6e8f;
         color: #fff5cc;
         font-family: monospace;
         padding: 4px;
         font-size: 12px;
         display: inline-block;
         border: 1px solid #ffdd99;
     }
     
     /* 右上角语言链接 */
     .right_up_jiao a {
         color: #fff5cc;
         background: #2c6e8f;
         padding: 2px 5px;
         text-decoration: none;
         font-weight: bold;
         border: 1px solid #ffdd99;
         font-size: 15px;
     }
     
     .right_up_jiao a:hover {
         background: #ffcc88;
         color: #2c6e8f;
     }
     
     /* 统一链接颜色 */
     a {
         color: #3a7ca5;
     }
     
     a:visited {
         color: #2c6e8f;
     }
     
     a:hover {
         color: #ffaa66;
     }
     
     /* 保持原有类名功能 */
     .site-header .px20, .site-header .px30, .site-header .px40 {
         margin: 0;
     }
     
     /* 修正原有背景色 */
     body {
         background-color: #b0e0e6;
     }
     </style>
   </HEAD>
   <BODY>
   <div class="right_up_jiao">
      <a href="#">zh（中文）</a>|<a href="index_ja.php">JA（日本語）</a>
   </div>
    <!-- by mingh  -->
    <div class="site-header">
        <div class="px20">
            <br>
            <h1>★オムライス大好き同盟★</h1>
            <h4>ようこそ！このウェブサイトは站长（サイト運営者）☽ヤチヨ☾が運営しており、卵とじ飯に関するコンテンツを掲載しています。</h4>
        </div>
        <br>
        
        <!-- 管理员登录区域 -->
        <div class="vintage-container">
            <h1>管理员登录</h1>
            <?php if ($error): ?>
                <p class="error-message"><strong>错误：</strong><?php echo $error; ?></p>
            <?php endif; ?>
            <?php if ($success): ?>
                <p class="success-message"><strong><?php echo htmlspecialchars($success); ?></strong></p>
            <?php endif; ?>
            
            <form method="post" action="index.php">
                <input type="hidden" name="action" value="login">
                <div class="login-form">
                    <!-- 用户名行 -->
                    <div class="form-row">
                        <label>用户名：</label>
                        <div class="input-field">
                            <input type="text" name="username" required placeholder="请输入用户名" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                        </div>
                    </div>
                    <!-- 邮箱行 -->
                    <div class="form-row">
                        <label>邮箱地址：</label>
                        <div class="input-field">
                            <input type="email" name="email" required placeholder="your@email.com" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                        </div>
                    </div>
                    <!-- 密码行 -->
                    <div class="form-row">
                        <label>密码：</label>
                        <div class="input-field">
                            <input type="password" name="password" required placeholder="请输入密码">
                        </div>
                    </div>
                    <!-- 验证码行 -->
                    <div class="form-row">
                        <label>验证码：</label>
                        <div class="captcha-group">
                            <input type="text" name="captcha" required maxlength="6" autocomplete="off" style="text-transform:uppercase">
                            <img src="/../lib/captcha.php" alt="验证码" id="captchaImg" onclick="this.src='/../lib/captcha.php?t='+Math.random()" style="cursor:pointer; vertical-align:middle;">
                            <a href="javascript:void(0)" onclick="document.getElementById('captchaImg').src='/../lib/captcha.php?t='+Math.random()">换一张</a>
                        </div>
                    </div>
                    <!-- 提交按钮行 -->
                    <div class="submit-row">
                        <input type="submit" value="登 录">
                    </div>
                </div>
            </form>
            <p></p>
            <br><br>
            <p>TIME：<?php echo date("Y-m-d H:i:s"); ?></p>
            <hr>
            <footer>
                <p>copyright by mingh (c)2003</p>
                <p>オムライス大好き同盟</p>
                <p>我们不是官方！</p>
            </footer>
        </div>
    </div>
   </BODY>
</HTML>