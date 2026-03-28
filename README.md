# cskhyj-cf
超时空辉夜姬的炒饭页面！
非常基础，只完成了基础的功能，是这样的（就像一坨大分）
目前运行在fan.mingh.net.cn
有日语和中文（后台是中文的）
</br>
如果你的验证图片加载不出来（我的服务器是这样的）
请替换为(这个文件在lib/captcha.php)：
</br>
<p><?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// 验证码字符集
$chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
$length = 5;
$code = '';
for ($i = 0; $i < $length; $i++) {
    $code .= $chars[mt_rand(0, strlen($chars) - 1)];
}
$_SESSION['captcha_code'] = $code;

// 图片尺寸
$width = 120;
$height = 40;

// 创建图片
$im = imagecreatetruecolor($width, $height);

// 颜色
$bgColor = imagecolorallocate($im, 245, 245, 245);
$textColor = imagecolorallocate($im, 50, 50, 150);
$lineColor = imagecolorallocate($im, 200, 200, 200);
$pixelColor = imagecolorallocate($im, 150, 150, 150);

// 填充背景
imagefilledrectangle($im, 0, 0, $width, $height, $bgColor);

// 绘制验证码文字（固定位置）
imagestring($im, 5, 40, 12, $code, $textColor);

// 添加干扰线
for ($i = 0; $i < 3; $i++) {
    imageline($im, mt_rand(0, $width), mt_rand(0, $height), mt_rand(0, $width), mt_rand(0, $height), $lineColor);
}

// 添加噪点
for ($i = 0; $i < 80; $i++) {
    imagesetpixel($im, mt_rand(0, $width), mt_rand(0, $height), $pixelColor);
}

// 输出图片
header('Content-Type: image/png');
imagepng($im);
imagedestroy($im);
</P>
