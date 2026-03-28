<?php
// 設定ファイル読み込み
require __DIR__ . '/config.php';

// タイムゾーン設定
date_default_timezone_set('Asia/Shanghai');

// データベース接続
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if (!$conn) {
    die('データベース接続失敗: ' . mysqli_connect_error());
}
mysqli_set_charset($conn, 'utf8mb4');

// ページング設定
$per_page_options = [30, 50, 100, 200];
$per_page = (isset($_GET['per_page']) && in_array((int)$_GET['per_page'], $per_page_options))
    ? (int)$_GET['per_page']
    : 30;

$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $per_page;

// 総件数を取得
$count_query = "SELECT COUNT(*) AS total FROM clap";
$count_result = mysqli_query($conn, $count_query);
$total_row = mysqli_fetch_assoc($count_result);
$total = (int)$total_row['total'];
mysqli_free_result($count_result);

$total_pages = ($per_page > 0) ? ceil($total / $per_page) : 1;
if ($page > $total_pages && $total_pages > 0) {
    $page = $total_pages;
    $offset = ($page - 1) * $per_page;
}

// メッセージ一覧取得（ID降順）
$query = "SELECT name, text, time, reply, type 
          FROM clap 
          ORDER BY id DESC 
          LIMIT ? OFFSET ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'ii', $per_page, $offset);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$claps = [];
while ($row = mysqli_fetch_assoc($result)) {
    $claps[] = $row;
}
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>拍手ボード · メッセージ一覧</title>
<style type="text/css">
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
/* ページネーションコントロール */
.pagination-controls {
    margin-bottom: 20px;
    text-align: right;
    font-size: 12px;
    border-bottom: 1px solid #dddddd;
    padding-bottom: 8px;
}
.pagination-controls select, .pagination-controls a {
    margin-left: 8px;
    border: 1px solid #8caccc;
    background-color: #e6eef7;
    padding: 2px 6px;
    text-decoration: none;
    color: #2c577c;
    font-size: 12px;
}
.pagination-controls a:hover {
    background-color: #d4e2f0;
    border-color: #5f7e9e;
}
.pagination-controls .current-page {
    background-color: #b0c4de;
    border-color: #7f9db9;
    color: #1f3a4b;
    font-weight: bold;
}
.clap-list {
    margin-top: 8px;
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
        <p>応援メッセージ 一覧（閲覧専用）</p>
    </div>

    <!-- ページネーションコントロール -->
    <div class="pagination-controls">
        <form method="get" style="display: inline;">
            <label>表示件数：</label>
            <select name="per_page" onchange="this.form.submit()">
                <?php foreach ($per_page_options as $opt): ?>
                <option value="<?php echo $opt; ?>" <?php if ($per_page == $opt) echo 'selected'; ?>><?php echo $opt; ?>件</option>
                <?php endforeach; ?>
            </select>
            <input type="hidden" name="page" value="1">
        </form>

        <div style="display: inline-block; margin-left: 20px;">
            <?php if ($total_pages > 1): ?>
                <?php if ($page > 1): ?>
                <a href="?per_page=<?php echo $per_page; ?>&page=1">≪ 最初</a>
                <a href="?per_page=<?php echo $per_page; ?>&page=<?php echo $page-1; ?>">‹ 前へ</a>
                <?php endif; ?>
                <span class="current-page"><?php echo $page; ?></span> / <?php echo $total_pages; ?>
                <?php if ($page < $total_pages): ?>
                <a href="?per_page=<?php echo $per_page; ?>&page=<?php echo $page+1; ?>">次へ ›</a>
                <a href="?per_page=<?php echo $per_page; ?>&page=<?php echo $total_pages; ?>">最後 ≫</a>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <span style="margin-left: 15px;">全<?php echo $total; ?>件</span>
    </div>

    <div class="clap-list">
        <div class="list-title">拍手メッセージ（新しい順）</div>
        <?php if (empty($claps)): ?>
            <div class="empty-note">まだメッセージはありません。</div>
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
    </div>
    <div class="footer-note">
        ©オムライス大好き同盟</br>
        copyright by mingh (c)2003
    </div>
</div>

</body>
</html>