<?php
// 登录验证
require_once __DIR__ . '/../lib/auth_check.php';
if (!isLoggedIn()) {
    header('Location: index.php');
    exit;
}
$current_admin = getCurrentUser();

// 加载数据库配置
require_once __DIR__ . '/../config.php';

// 建立数据库连接
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if (!$conn) {
    die('数据库连接失败: ' . mysqli_connect_error());
}
mysqli_set_charset($conn, 'utf8mb4');

// --- 处理回复 ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'reply') {
    $id = (int)$_POST['id'];
    $reply = trim($_POST['reply']);
    if (!empty($reply)) {
        $stmt = mysqli_prepare($conn, "UPDATE clap SET reply = ?, type = '2' WHERE id = ?");
        mysqli_stmt_bind_param($stmt, 'si', $reply, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    header('Location: clap.php');
    exit;
}

// --- 处理批量删除 ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'batch_delete') {
    if (!empty($_POST['ids']) && is_array($_POST['ids'])) {
        $ids = array_map('intval', $_POST['ids']);
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmt = mysqli_prepare($conn, "DELETE FROM clap WHERE id IN ($placeholders)");
        if ($stmt) {
            $types = str_repeat('i', count($ids));
            mysqli_stmt_bind_param($stmt, $types, ...$ids);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }
    header('Location: clap.php');
    exit;
}

// --- 处理违禁关键词保存 ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_forbidden') {
    $words = trim($_POST['forbidden_words']);
    // 确保表存在
    mysqli_query($conn, "CREATE TABLE IF NOT EXISTS forbidden_words (
        id INT AUTO_INCREMENT PRIMARY KEY,
        words TEXT NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    // 如果表为空，插入；否则更新
    $result = mysqli_query($conn, "SELECT id FROM forbidden_words LIMIT 1");
    if (mysqli_num_rows($result) == 0) {
        $stmt = mysqli_prepare($conn, "INSERT INTO forbidden_words (words) VALUES (?)");
        mysqli_stmt_bind_param($stmt, 's', $words);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    } else {
        $stmt = mysqli_prepare($conn, "UPDATE forbidden_words SET words = ? WHERE id = 1");
        mysqli_stmt_bind_param($stmt, 's', $words);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    header('Location: clap.php');
    exit;
}

// --- 读取违禁关键词 ---
$forbidden_words = '';
$result = mysqli_query($conn, "SELECT words FROM forbidden_words LIMIT 1");
if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $forbidden_words = $row['words'];
}
mysqli_free_result($result);

// --- 读取未回复留言 (type = 1) ---
$unreplied = [];
$query = "SELECT id, name, email, text, time FROM clap WHERE type = '1' ORDER BY id DESC";
$result = mysqli_query($conn, $query);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $unreplied[] = $row;
    }
    mysqli_free_result($result);
}

// --- 读取已回复留言 (type = 2) ---
$replied = [];
$query = "SELECT id, name, email, text, reply, time FROM clap WHERE type = '2' ORDER BY id DESC";
$result = mysqli_query($conn, $query);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $replied[] = $row;
    }
    mysqli_free_result($result);
}

mysqli_close($conn);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="zh">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>拍手留言管理后台</title>
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <style type="text/css">
        body {
            background-color: #b0d4ff;
            margin: 0;
            padding: 20px;
            font-family: 'Meiryo', 'MS PGothic', 'Hiragino Kaku Gothic ProN', 'Noto Sans CJK JP', 'Microsoft YaHei', sans-serif;
            font-size: 14px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: #fff;
            border: 1px solid #7f9db9;
            padding: 20px;
        }
        h1 {
            font-size: 24px;
            color: #336699;
            margin: 0 0 10px 0;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
        }
        .admin-info {
            background: #e6eef7;
            border: 1px solid #8caccc;
            padding: 8px 12px;
            margin-bottom: 20px;
        }
        .section {
            margin-bottom: 30px;
            border: 1px solid #ddd;
            background: #fefefe;
        }
        .section h2 {
            background: #e6eef7;
            margin: 0;
            padding: 8px 12px;
            font-size: 18px;
            border-bottom: 1px solid #ccc;
            color: #336699;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            vertical-align: top;
            text-align: left;
        }
        th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .reply-form {
            margin-top: 5px;
        }
        .reply-form input[type="text"] {
            width: 200px;
            padding: 3px;
            border: 1px solid #8caccc;
        }
        .reply-form input[type="submit"] {
            background-color: #e6eef7;
            border: 1px solid #7f9db9;
            padding: 2px 8px;
            cursor: pointer;
        }
        .delete-checkbox {
            width: 30px;
            text-align: center;
        }
        .batch-delete {
            margin: 10px 0;
            text-align: right;
        }
        .batch-delete input {
            background-color: #ffdddd;
            border: 1px solid #cc5555;
            padding: 4px 12px;
            cursor: pointer;
        }
        .forbidden-area {
            background: #f9f9f9;
            border: 1px solid #ccc;
            padding: 12px;
            margin-bottom: 20px;
        }
        .forbidden-area textarea {
            width: 100%;
            height: 80px;
            border: 1px solid #8caccc;
            font-family: monospace;
        }
        .forbidden-area input {
            margin-top: 5px;
            background-color: #e6eef7;
            border: 1px solid #7f9db9;
            padding: 4px 12px;
            cursor: pointer;
        }
        .small-note {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>WEB拍手留言管理后台</h1>
    <div class="admin-info">
        当前管理员：<?php echo htmlspecialchars($current_admin, ENT_QUOTES, 'UTF-8'); ?>
        <?php
            require_once __DIR__ . '/../admin/lib/head.php';
        ?>
    </div>

    <!-- 违禁关键词设置区域 -->
    <div class="forbidden-area">
        <form method="post" action="">
            <input type="hidden" name="action" value="save_forbidden">
            <strong>违禁关键词设置：</strong><br>
            <textarea name="forbidden_words"><?php echo htmlspecialchars($forbidden_words, ENT_QUOTES, 'UTF-8'); ?></textarea>
            <div class="small-note">多个关键词请用英文逗号 “,” 隔开，例如：广告,色情,赌博</div>
            <input type="submit" value="保存关键词">
        </form>
    </div>

    <!-- 未回复留言列表 -->
    <div class="section">
        <h2>未回复留言 (<?php echo count($unreplied); ?>)</h2>
        <form method="post" action="" id="batch_form_unreplied">
            <input type="hidden" name="action" value="batch_delete">
            <table>
                <thead>
                    <tr>
                        <th class="delete-checkbox"><input type="checkbox" id="select_all_unreplied"></th>
                        <th>ID</th>
                        <th>姓名</th>
                        <th>邮箱</th>
                        <th>留言内容</th>
                        <th>时间</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($unreplied)): ?>
                    <tr><td colspan="7" style="text-align:center;">暂无未回复留言</td></tr>
                <?php else: ?>
                    <?php foreach ($unreplied as $msg): ?>
                    <tr>
                        <td class="delete-checkbox"><input type="checkbox" name="ids[]" value="<?php echo $msg['id']; ?>"></td>
                        <td><?php echo $msg['id']; ?></td>
                        <td><?php echo htmlspecialchars($msg['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($msg['email'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo nl2br(htmlspecialchars($msg['text'], ENT_QUOTES, 'UTF-8')); ?></td>
                        <td><?php echo $msg['time']; ?></td>
                        <td>
                            <form method="post" action="" style="display:inline;">
                                <input type="hidden" name="action" value="reply">
                                <input type="hidden" name="id" value="<?php echo $msg['id']; ?>">
                                <input type="text" name="reply" placeholder="输入回复内容" required>
                                <input type="submit" value="回复">
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
            <div class="batch-delete">
                <input type="submit" value="删除选中的留言" onclick="return confirm('确定要删除选中的留言吗？');">
            </div>
        </form>
    </div>

    <!-- 已回复留言列表 -->
    <div class="section">
        <h2>已回复留言 (<?php echo count($replied); ?>)</h2>
        <form method="post" action="" id="batch_form_replied">
            <input type="hidden" name="action" value="batch_delete">
            <table>
                <thead>
                    <tr>
                        <th class="delete-checkbox"><input type="checkbox" id="select_all_replied"></th>
                        <th>ID</th>
                        <th>姓名</th>
                        <th>邮箱</th>
                        <th>留言内容</th>
                        <th>回复内容</th>
                        <th>时间</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($replied)): ?>
                    <tr><td colspan="7" style="text-align:center;">暂无已回复留言</td></tr>
                <?php else: ?>
                    <?php foreach ($replied as $msg): ?>
                    <tr>
                        <td class="delete-checkbox"><input type="checkbox" name="ids[]" value="<?php echo $msg['id']; ?>"></td>
                        <td><?php echo $msg['id']; ?></td>
                        <td><?php echo htmlspecialchars($msg['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($msg['email'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo nl2br(htmlspecialchars($msg['text'], ENT_QUOTES, 'UTF-8')); ?></td>
                        <td><?php echo nl2br(htmlspecialchars($msg['reply'], ENT_QUOTES, 'UTF-8')); ?></td>
                        <td><?php echo $msg['time']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
            <div class="batch-delete">
                <input type="submit" value="删除选中的留言" onclick="return confirm('确定要删除选中的留言吗？');">
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
    // 全选/取消全选功能
    document.getElementById('select_all_unreplied').onclick = function() {
        var checkboxes = document.querySelectorAll('#batch_form_unreplied input[name="ids[]"]');
        for (var i = 0; i < checkboxes.length; i++) {
            checkboxes[i].checked = this.checked;
        }
    };
    document.getElementById('select_all_replied').onclick = function() {
        var checkboxes = document.querySelectorAll('#batch_form_replied input[name="ids[]"]');
        for (var i = 0; i < checkboxes.length; i++) {
            checkboxes[i].checked = this.checked;
        }
    };
</script>
</body>
</html>