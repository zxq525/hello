<?php
/**
 * vwen.com
 *
 * @copyright  Copyright (c) 2010 VWEN.COM Inc. http://www.vwen.com
 * QQ: 19519959 E-mail:noerr@vip.qq.com
 */

$w = 65; //设置图片宽和高
$h = 24;
if($_GET['w'] && intval($_GET['w'])>=0 && intval($_GET['w'])<=100){$w=intval($_GET['w']);}
if($_GET['h'] && intval($_GET['h'])>=0 && intval($_GET['h'])<=45){$h=intval($_GET['h']);}
$len = 4;//生成几位验证码
$str = Array(); //用来存储随机码
$string = "0123456789";//随机挑选其中4个字符，也可以选择更多，注意循环的时候加上，宽度适当调整
for($i = 0;$i < 4;$i++){
   $str[$i] = $string[rand(0,9)];
   $vcode .= $str[$i];
}
session_start(); //启用超全局变量session
$_SESSION["verifycode"] = $vcode;
$im = imagecreatetruecolor($w,$h);
$white = imagecolorallocate($im,255,255,255); //第一次调用设置背景色
$black = imagecolorallocate($im,0,0,0); //边框颜色
imagefilledrectangle($im,0,0,$w,$h,$white); //画一矩形填充

//生成雪花背景
for($i = 1;$i < 300;$i++){
   $x = mt_rand(1,$w-9);
   $y = mt_rand(1,$h-9);
   $color = imagecolorallocate($im,mt_rand(200,255),mt_rand(200,255),mt_rand(200,255));
   imagechar($im,1,$x,$y,"*",$color);
}

for($j=0;$j<5;$j++)
{
$line_color = imagecolorallocate($im,mt_rand(0,225),mt_rand(0,150),mt_rand(0,225));
imageline($im,rand(0,80),rand(0,26),rand(0,80),rand(0,26),$line_color);
}

//将验证码写入图案
$size = $w/$len;
for($i = 0;$i < count($str);$i++){
	$x = $left+($i*$size);
	$y = rand($size+2, $size+6);
	$textColor = imagecolorallocate($im,mt_rand(0,225),mt_rand(0,150),mt_rand(0,225));
	$rand = rand(1,3);
	//$rand = 1;	//不随机使用标准字体
	if ($rand < 1 OR $rand > 4) {
		$rand = 1;
	}
	$font = realpath("../includes/fonts/".$rand.".ttf"); 
	$randsize = rand($size-$size/10, $size+$size/10);
	@imagettftext($im, $randsize, rand(-10,10), $x,$y, $textColor, $font, $str[$i]); 
}
header("Content-type:image/jpeg"); //以jpeg格式输出，注意上面不能输出任何字符，否则出错
imagejpeg($im);
imagedestroy($im);
?>