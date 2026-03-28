<?php
require_once __DIR__ . '/../lib/auth_check.php';

if (!isLoggedIn()) {
    header('Location: index.php');
    exit;
}
echo "当前管理：" . getCurrentUser();

// 处理 AJAX 保存请求
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_ma'])) {
    header('Content-Type: application/json; charset=utf-8');
    
    $maValue = trim($_POST['save_ma']);
    if (!is_numeric($maValue)) {
        echo json_encode(['success' => false, 'message' => '请输入有效的数字']);
        exit;
    }
    
    $maValue = intval($maValue);
    
    // 获取当前登录用户信息（假设 auth_check 提供了 getCurrentUserId 或使用用户名）
    // 如果 auth_check 中没有直接提供用户 ID，则通过用户名查询
    require_once __DIR__ . '/../config.php';
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($mysqli->connect_error) {
        echo json_encode(['success' => false, 'message' => '数据库连接失败']);
        exit;
    }
    
    $currentUser = getCurrentUser(); // 返回用户名
    $stmt = $mysqli->prepare("UPDATE useradmins SET ma = ? WHERE username = ?");
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'SQL 预处理失败']);
        exit;
    }
    $stmt->bind_param("is", $maValue, $currentUser);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => '保存成功']);
    } else {
        echo json_encode(['success' => false, 'message' => '保存失败: ' . $stmt->error]);
    }
    $stmt->close();
    $mysqli->close();
    exit;
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
     "http://www.w3.org/TR/html4/strict.dtd">
<HTML lang="zh">
   <HEAD>
      <TITLE>★オムライス大好き同盟★</TITLE>
      <meta http-equiv="Content-Type" content="text/html"; charset=UTF-8>
      <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
      <link rel="icon" href="/favicon.ico" type="image/x-icon">
   </HEAD>
   <BODY>
        <?php require_once __DIR__ . '/../admin/lib/head.php'; ?>
        <p>退出管理员登录：<a href="logout.php">退出登录</a></p>
        <p>我们允许您设置一个数字当没有回复的拍手达到这个数字的时候，给您发送邮件，让您来处理。</p>
        

        <table border="0">
            <tr>
                <td><textarea id="userText" rows="1" placeholder="输入数字"></textarea></td>
                <td><button id="saveBtn">保存</button></td>
            </tr>
        </table>
        
        <div id="message" style="margin-top:10px; color: #4CAF50;"></div>
        
        <script>
            document.getElementById('saveBtn').addEventListener('click', function() {
                var textarea = document.getElementById('userText');
                var value = textarea.value.trim();
                var msgDiv = document.getElementById('message');
                
                if (value === '') {
                    msgDiv.style.color = '#f44336';
                    msgDiv.innerText = '请输入数字';
                    return;
                }
                if (isNaN(value)) {
                    msgDiv.style.color = '#f44336';
                    msgDiv.innerText = '请输入有效的数字';
                    return;
                }
                
                var xhr = new XMLHttpRequest();
                xhr.open('POST', window.location.href, true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            msgDiv.style.color = '#4CAF50';
                            msgDiv.innerText = response.message;
                        } else {
                            msgDiv.style.color = '#f44336';
                            msgDiv.innerText = response.message;
                        }
                    }
                };
                xhr.send('save_ma=' + encodeURIComponent(value));
            });
        </script>
   </BODY>
</HTML>