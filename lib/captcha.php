<?php
session_start();

// 第一步：使用简化版的基础框架
$width = 120;
$height = 40;
$im = imagecreatetruecolor($width, $height);
$bgColor = imagecolorallocate($im, 245, 245, 245);
$textColor = imagecolorallocate($im, 50, 50, 150);
imagefilledrectangle($im, 0, 0, $width, $height, $bgColor);

// 生成验证码=
$chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
$length = 5;
$code = '';
for ($i = 0; $i < $length; $i++) {
    $code .= $chars[mt_rand(0, strlen($chars) - 1)];
}
$_SESSION['captcha_code'] = $code;

// 绘制文字（使用内置字体）
$font = 5;
$fontWidth = imagefontwidth($font);
$fontHeight = imagefontheight($font);
$x = ($width - $fontWidth * $length) / 2;
$y = ($height - $fontHeight) / 2;
for ($i = 0; $i < $length; $i++) {
    imagestring($im, $font, $x + $i * $fontWidth, $y, $code[$i], $textColor);
}

header('Content-Type: image/png');
imagepng($im);
imagedestroy($im);