<?php
// 计数器文件路径
$counterFile = 'counter.txt';

// 简单加锁读取/写入，避免并发丢失
if (file_exists($counterFile)) {
    $fp = fopen($counterFile, 'r+');
    if (flock($fp, LOCK_EX)) {
        $count = intval(fread($fp, filesize($counterFile)));
        $count++;
        ftruncate($fp, 0);
        rewind($fp);
        fwrite($fp, $count);
        flock($fp, LOCK_UN);
        fclose($fp);
    } else {
        // 无法加锁时降级处理：直接读取+1
        $count = intval(file_get_contents($counterFile)) + 1;
        file_put_contents($counterFile, $count);
    }
} else {
    // 首次运行，创建文件并写入1
    $count = 1;
    file_put_contents($counterFile, $count);
}

//补零到6位
$formattedCount = str_pad($count, 6, '0', STR_PAD_LEFT);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<HTML lang="ja">

<HEAD>
    <TITLE>★オムライス大好き同盟★</TITLE>
    <meta http-equiv="Content-Type" content="text/html" ; charset=UTF-8>
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">

    <style type="text/css">
        body {
            cursor: url('danchaofanshubiao.cur'), auto;
            background-color: #ADD8E6;
        }

        .site-header {
            /*这是居中用的 (awa) */
            height: 100px;
            text-align: center;
        }

        .px20 {
            line-height: 20px;
        }

        .px30 {
            line-height: 30px;
        }

        .px40 {
            line-height: 40px;
        }

        .px50 {
            line-height: 50px;
        }

        .px60 {
            line-height: 60px;
        }
    </style>
</HEAD>

<BODY>
    <!-- by mingh  -->
    <div class="site-header">
        <div class="px20">
            <h1>★オムライス大好き同盟★</h1>
            <h4>ようこそ！このウェブサイトは站长（サイト運営者）☽ヤチヨ☾が運営しており、卵とじ飯に関するコンテンツを掲載しています。</h4>
        </div>
        <br>

        <p><span>あなたは</span></p>
        <div class="counter-box"><?php echo $formattedCount; ?></div>
        <p><span>番目のお客様です！</span></p>

        <a href="web_clap.php">Web 拍手</a>
        <a href="d.php">オムライスキッチン</a>
        <h2>◆ お知らせ ◆アルを予定しています！</p>
            <p>時間は未定ですが、近
        </h2>
        <p>肌寒い季節になってきましたが、神々のみんなは元気にお過ごしですか？</p>
        <p>（八千代は重度の家に閉じこもっているので、外の気温なんてまったく分からない＞＜；）</p>
        <p>近々サイトのリニューづいたらまたお知らせしますね。</p>
        <p>相互リンク随時募集中☆　ご連絡はBBSまで♪</p>

        <hr>
        <p>copyright by mingh (c)2003</p>
        <p>オムライス大好き同盟</p>
        <P>私たちは公式ではありません！</P>
    </div>
</BODY>

</HTML>