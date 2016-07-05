<?php
/**
 * vwen.com
 *
 * @copyright  Copyright (c) 2010 VWEN.COM Inc. http://www.vwen.com
 * QQ: 19519959 E-mail:noerr@vip.qq.com
 */

$w = 65; //����ͼƬ��͸�
$h = 24;
if($_GET['w'] && intval($_GET['w'])>=0 && intval($_GET['w'])<=100){$w=intval($_GET['w']);}
if($_GET['h'] && intval($_GET['h'])>=0 && intval($_GET['h'])<=45){$h=intval($_GET['h']);}
$len = 4;//���ɼ�λ��֤��
$str = Array(); //�����洢�����
$string = "0123456789";//�����ѡ����4���ַ���Ҳ����ѡ����࣬ע��ѭ����ʱ����ϣ�����ʵ�����
for($i = 0;$i < 4;$i++){
   $str[$i] = $string[rand(0,9)];
   $vcode .= $str[$i];
}
session_start(); //���ó�ȫ�ֱ���session
$_SESSION["verifycode"] = $vcode;
$im = imagecreatetruecolor($w,$h);
$white = imagecolorallocate($im,255,255,255); //��һ�ε������ñ���ɫ
$black = imagecolorallocate($im,0,0,0); //�߿���ɫ
imagefilledrectangle($im,0,0,$w,$h,$white); //��һ�������

//����ѩ������
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

//����֤��д��ͼ��
$size = $w/$len;
for($i = 0;$i < count($str);$i++){
	$x = $left+($i*$size);
	$y = rand($size+2, $size+6);
	$textColor = imagecolorallocate($im,mt_rand(0,225),mt_rand(0,150),mt_rand(0,225));
	$rand = rand(1,3);
	//$rand = 1;	//�����ʹ�ñ�׼����
	if ($rand < 1 OR $rand > 4) {
		$rand = 1;
	}
	$font = realpath("../includes/fonts/".$rand.".ttf"); 
	$randsize = rand($size-$size/10, $size+$size/10);
	@imagettftext($im, $randsize, rand(-10,10), $x,$y, $textColor, $font, $str[$i]); 
}
header("Content-type:image/jpeg"); //��jpeg��ʽ�����ע�����治������κ��ַ����������
imagejpeg($im);
imagedestroy($im);
?>