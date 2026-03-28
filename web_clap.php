<?php
// 設定ファイル読み込み
require __DIR__ . '/config.php';

// タイムゾーン設定
date_default_timezone_set('Asia/Shanghai');

// ========== 手动加载 PHPMailer（根据实际路径调整） ==========
require_once __DIR__ . '/PHPMailer/src/Exception.php';
require_once __DIR__ . '/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// データベース接続
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if (!$conn) {
    die('データベース接続失敗: ' . mysqli_connect_error());
}
mysqli_set_charset($conn, 'utf8mb4');

// テーブル存在確認と初期化
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS forbidden_words (
    id INT AUTO_INCREMENT PRIMARY KEY,
    words TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$name = $email = $text = '';
$errors = [];
$success_message = '';

// 違禁キーワードの取得（カンマ区切り）
$forbidden_words_str = '';
$result = mysqli_query($conn, "SELECT words FROM forbidden_words LIMIT 1");
if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $forbidden_words_str = $row['words'];
}
mysqli_free_result($result);

$forbidden_list = [];
if (!empty($forbidden_words_str)) {
    $temp = explode(',', $forbidden_words_str);
    foreach ($temp as $word) {
        $word = trim($word);
        if ($word !== '') {
            $forbidden_list[] = $word;
        }
    }
}

// フォーム送信処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $text = trim($_POST['text'] ?? '');

    // バリデーション
    if (empty($name)) {
        $errors[] = 'お名前を入力してください';
    } elseif (mb_strlen($name) > 100) {
        $errors[] = 'お名前は100文字以内でお願いします';
    }

    if (empty($email)) {
        $errors[] = 'メールアドレスを入力してください';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'メールアドレスの形式が正しくありません';
    } elseif (mb_strlen($email) > 100) {
        $errors[] = 'メールアドレスは100文字以内でお願いします';
    }

    if (empty($text)) {
        $errors[] = 'メッセージを入力してください';
    }

    // 違禁キーワードチェック
    if (empty($errors)) {
        $has_forbidden = false;
        foreach ($forbidden_list as $word) {
            if (mb_stripos($name, $word) !== false || mb_stripos($text, $word) !== false) {
                $has_forbidden = true;
                break;
            }
        }
        if ($has_forbidden) {
            $errors[] = '入力内容に不適切な言葉が含まれています。';
        }
    }

    // データベースへの挿入
    if (empty($errors)) {
        $current_time = date('Y-m-d H:i:s');
        $type = '1';  // 未返信状態

        $stmt = mysqli_prepare($conn, "INSERT INTO clap (name, email, text, type, time) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, 'sssss', $name, $email, $text, $type, $current_time);

        if (mysqli_stmt_execute($stmt)) {
            $success_message = '拍手ありがとうございます！メッセージを投稿しました。';
            $name = $email = $text = '';

            // ★ 未返信拍手数をチェックし、閾値を超えていたらメール送信
            checkAndNotifyAdmins($conn);
        } else {
            $errors[] = 'データベースエラー: ' . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    }
}

// 最新10件のメッセージ一覧
$query = "SELECT name, text, time, reply, type FROM clap ORDER BY id DESC LIMIT 10";
$result = mysqli_query($conn, $query);
$claps = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $claps[] = $row;
    }
    mysqli_free_result($result);
}

mysqli_close($conn);

// ========== 管理者通知関数 ==========
function checkAndNotifyAdmins($conn) {
    // 未返信の拍手数
    $result = mysqli_query($conn, "SELECT COUNT(*) AS unreplied_count FROM clap WHERE type = '1'");
    if (!$result) return;
    $row = mysqli_fetch_assoc($result);
    $unreplied_count = (int)$row['unreplied_count'];
    mysqli_free_result($result);

    if ($unreplied_count <= 0) return;

    // 閾値が設定されている管理者を取得
    $admin_query = "SELECT id, username, email, ma, last_notify_time FROM useradmins WHERE ma > 0";
    $admin_result = mysqli_query($conn, $admin_query);
    if (!$admin_result) return;

 while ($admin = mysqli_fetch_assoc($admin_result)) {
    if ($unreplied_count >= $admin['ma']) {
        $last_time = $admin['last_notify_time'];
        if ($last_time === null || (time() - strtotime($last_time)) > 24 * 3600) {
            $subject = "【拍手板】未回复的拍手数已达到 {$admin['ma']} 件";
            $body = "管理员 {$admin['username']} 先生/女士：\n\n";
            $body .= "当前未回复的拍手消息共有 {$unreplied_count} 件。\n";
            $body .= "您设置的阈值是 {$admin['ma']} 件，现已超过，请及时处理。\n\n";
            $body .= "请登录管理后台进行回复：\n";
            $body .= $GLOBALS['yu'] . "admin/index.php\n\n";
            $body .= "※ 本邮件由系统自动发送，请勿直接回复。";
                

                $mail_sent = sendSmtpMail(
                    $admin['email'],
                    $subject,
                    $body,
                    $GLOBALS['setFrom1'],
                    $GLOBALS['add1']
                );

                if ($mail_sent) {
                    $update_sql = "UPDATE useradmins SET last_notify_time = NOW() WHERE id = " . (int)$admin['id'];
                    mysqli_query($conn, $update_sql);
                }
            }
        }
    }
    mysqli_free_result($admin_result);
}

/**
 * SMTP メール送信関数（PHPMailer）
 */
function sendSmtpMail($to, $subject, $body, $from, $replyTo) {
    global $email_host, $SMTPAuth, $email_Username, $email_Password, $email_SMTPSecure, $email_Port;

    try {
        $mail = new PHPMailer(true);   // 修正：使用 use 后的简化写法
        
        // SMTP 設定
        $mail->isSMTP();
        $mail->Host       = $email_host;
        $mail->SMTPAuth   = $SMTPAuth;
        $mail->Username   = $email_Username;
        $mail->Password   = $email_Password;
        $mail->SMTPSecure = $email_SMTPSecure;
        $mail->Port       = $email_Port;
        
        $mail->CharSet = 'UTF-8';
        $mail->setFrom($from, '拍手板システム');
        $mail->addAddress($to);
        $mail->addReplyTo($replyTo);
        
        $mail->isHTML(false);
        $mail->Subject = $subject;
        $mail->Body    = $body;
        
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Mailer Error: " . $mail->ErrorInfo);
        return false;
    }
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>拍手ボード · 応援メッセージ</title>
<style type="text/css">
/* 样式保持不变（您原来的样式） */
body {
    background-color: #b0d4ff;
    margin: 0;
    padding: 40px 20px;
    font-family: 'Meiryo', 'MS PGothic', 'Hiragino Kaku Gothic ProN', 'Noto Sans CJK JP', sans-serif;
    font-size: 14px;
    line-height: 1.4;
    color: #222;
}
.white-box {
    width: 700px;
    margin: 0 auto;
    background-color: #ffffff;
    border: 1px solid #7f9db9;
    padding: 25px 30px 30px 30px;
}
.title-area {
    border-bottom: 1px solid #cccccc;
    margin-bottom: 20px;
    padding-bottom: 8px;
}
.title-area h1 {
    margin: 0;
    font-size: 24px;
    font-weight: normal;
    letter-spacing: 1px;
    color: #336699;
}
.title-area p {
    margin: 5px 0 0 0;
    font-size: 12px;
    color: #666;
}
.form-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}
.form-table th {
    text-align: right;
    width: 90px;
    padding: 8px 8px 8px 0;
    font-weight: normal;
    vertical-align: top;
}
.form-table td {
    padding: 6px 0;
}
input.text, textarea {
    border: 1px solid #8caccc;
    background-color: #fff;
    padding: 5px;
    font-family: 'Courier New', monospace;
    font-size: 13px;
    width: 280px;
}
textarea {
    width: 380px;
    height: 80px;
}
input.text:focus, textarea:focus {
    background-color: #fef8e7;
    border-color: #6688aa;
}
.btn {
    background-color: #e6eef7;
    border: 1px solid #7f9db9;
    color: #2c577c;
    padding: 4px 18px;
    font-size: 14px;
    font-family: inherit;
    cursor: pointer;
}
.btn:hover {
    background-color: #d4e2f0;
    border-color: #5f7e9e;
}
.error-msg {
    background: #ffe0e0;
    background: linear-gradient(to bottom, #ffe8e8, #ffc8c8);
    border: 1px solid #cc5555;
    padding: 8px 12px;
    margin-bottom: 18px;
    color: #aa3333;
    font-size: 13px;
}
.success-msg {
    background: #e0ffe0;
    background: linear-gradient(to bottom, #e8ffe8, #c8ffc8);
    border: 1px solid #55aa55;
    padding: 8px 12px;
    margin-bottom: 18px;
    color: #2c6e2c;
    font-size: 13px;
}
.clap-list {
    margin-top: 28px;
    border-top: 2px solid #dddddd;
    padding-top: 12px;
}
.list-title {
    font-size: 16px;
    font-weight: bold;
    color: #336699;
    margin-bottom: 12px;
}
.clap-item {
    border-bottom: 1px dotted #cccccc;
    padding: 12px 0 10px 0;
}
.clap-name {
    font-weight: bold;
    color: #2c577c;
    margin-right: 12px;
}
.clap-time {
    font-size: 11px;
    color: #888888;
}
.clap-text {
    margin-top: 6px;
    line-height: 1.5;
    font-size: 13px;
    word-break: break-all;
    white-space: pre-wrap;
}
.reply-area {
    margin-top: 8px;
    padding-left: 12px;
    border-left: 3px solid #9bc0e6;
    background-color: #f9fbfd;
    font-size: 12px;
    color: #3a6a9a;
}
.reply-label {
    font-weight: bold;
    color: #4a7cac;
}
.empty-note {
    color: #888;
    padding: 15px 0;
    text-align: center;
    font-style: italic;
}
.footer-note {
    font-size: 11px;
    text-align: center;
    margin-top: 25px;
    color: #7f8c8d;
    border-top: 1px solid #eeeeee;
    padding-top: 12px;
}
</style>
</head>
<body>

<div class="white-box">
    <div class="title-area">
        <h1>拍手ボード</h1>
        <p>応援のメッセージをお寄せください</p>
    </div>

    <?php if (!empty($errors)): ?>
    <div class="error-msg">
        <strong>⚠ 入力内容に誤りがあります：</strong><br>
        <?php foreach ($errors as $err): ?>
        &nbsp;• <?php echo htmlspecialchars($err, ENT_QUOTES, 'UTF-8'); ?><br>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if (!empty($success_message)): ?>
    <div class="success-msg">
        ✔ <?php echo htmlspecialchars($success_message, ENT_QUOTES, 'UTF-8'); ?>
    </div>
    <?php endif; ?>

    <form method="post" action="">
        <table class="form-table" cellspacing="0">
            <tr>
                <th>お名前：</th>
                <td><input type="text" name="name" class="text" value="<?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>" maxlength="100">            </td>
            </tr>
            <tr>
                <th>メール：</th>
                <td><input type="text" name="email" class="text" value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>" maxlength="100">            </td>
            </tr>
            <tr>
                <th>メッセージ：</th>
                <td><textarea name="text"><?php echo htmlspecialchars($text, ENT_QUOTES, 'UTF-8'); ?></textarea>            </td>
            </tr>
            <tr>
                <th></th>
                <td><input type="submit" name="submit" class="btn" value="拍手して送信">            </td>
            </tr>
        </table>
    </form>

    <div class="clap-list">
        <div class="list-title">拍手メッセージ一覧（最新10件）</div>
        <?php if (empty($claps)): ?>
            <div class="empty-note">まだメッセージはありません。最初の拍手を送ってみませんか？</div>
        <?php else: ?>
            <?php foreach ($claps as $item): ?>
            <div class="clap-item">
                <span class="clap-name"><?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?></span>
                <span class="clap-time"><?php echo htmlspecialchars($item['time'], ENT_QUOTES, 'UTF-8'); ?></span>
                <div class="clap-text"><?php echo nl2br(htmlspecialchars($item['text'], ENT_QUOTES, 'UTF-8')); ?></div>
                <?php if ($item['type'] == '2' && !empty($item['reply'])): ?>
                <div class="reply-area">
                    <span class="reply-label">返信：</span><br>
                    <?php echo nl2br(htmlspecialchars($item['reply'], ENT_QUOTES, 'UTF-8')); ?>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
        <div style="text-align: center;">
            <a href="clap_al.php">すべて表示</a>
        </div>
    </div>
    <div class="footer-note">
        ©オムライス大好き同盟<br>
        copyright by mingh (c)2003
    </div>
</div>

</body>
</html>