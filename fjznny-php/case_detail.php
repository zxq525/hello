<?php
require 'inc.common.php';
$parentclassid = 17;
$parentclass=$db->fetch_first("SELECT * FROM {$dbtablepre}article_class where id=$parentclassid limit 1");

if(!checkint($id)){jsalert("没有找到相关信息，请重试；","back","window");}
$detail=$db->fetch_first("select * from {$dbtablepre}article where id=".(int)$id."  order by joindate desc,id desc limit 1");
if(!$detail){
	jsalert("没有找到相关信息，请重试；","back","window");
}else{
	$db->query("UPDATE {$dbtablepre}article SET hitnum=hitnum+1 where id=". (int)$detail['id'] ."");
}

$class=$db->fetch_first("SELECT * FROM {$dbtablepre}article_class where id=". (int)$detail['classid'] ." limit 1");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $detail['topic'];?>,<?php echo $class['classname'];?>,<?php echo $config['config_name'];?></title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<link href="style/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/common.js"></script>
</head>

<body>
<?php require('inc.header.php'); ?>
<div class="nba" style="background-image:url(images/5.png);"></div>
<div id="mainWrapper">
<div class="title_pos">
<h3><?php echo $class['classname'];?></h3><div class="position">当前位置：<a href="./">首页</a> &gt; <a href="case.php"><?php echo $class['classname'];?></a> &gt; 信息正文</div>
</div>
<div class="clear"></div>

	<div class="nleft2">
		
		<div class="listbox">
				<div class="head">
					<div class="headbg">
						<div class="title"><?php echo $class['classname'];?></div>
					</div>
				</div>
				<div class="detail">
					    <h2><?php echo $detail['topic'];?></h2>
						<div class="content"><?php echo $detail['content'];?></div>
				</div>
		</div>
	</div>
	<?php require('inc.right.php'); ?>
</div>
<?php require('inc.footer.php'); ?>
</body>
</html>