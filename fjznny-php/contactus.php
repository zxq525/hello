<?php
require 'inc.common.php';
$parentclassid = 19;
$parentclass=$db->fetch_first("SELECT * FROM {$dbtablepre}article_class where id=". $parentclassid ." limit 1");
if(!checkint($classid)){
	$class=$db->fetch_first("SELECT * FROM {$dbtablepre}article_class where parentid=$parentclassid order by sort_order asc, id asc limit 1");
}else{
	$class=$db->fetch_first("SELECT * FROM {$dbtablepre}article_class where parentid=$parentclassid and id=$classid order by sort_order asc, id asc limit 1");
	if(!$class){
		$class=$db->fetch_first("SELECT * FROM {$dbtablepre}article_class where parentid=$parentclassid order by sort_order asc, id asc limit 1");
	}
}
if($class){
	$detail=$db->fetch_first("select A.*,B.classname from {$dbtablepre}article as A left join {$dbtablepre}article_class as B on A.classid=B.id where B.id=".(int)$class['id']." AND B.parentid=". (int)$parentclass['id'] ."");
}else{
	jsalert("没有找到相关信息，请重试；","back","window");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $class['classname'];?>,<?php echo $config['config_name'];?></title>
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
	<div class="nleft1">
		<h3><?php echo $parentclass['classname'];?></h3>
		<div class="nmenu_left">
			<ul>
       <?php
		$query=$db->query("SELECT * FROM {$dbtablepre}article_class where parentid=$parentclassid order by sort_order asc, id asc");
		while($rs = $db->fetch_array($query)){
			echo '<li'. ($rs['id']==$class['id']?' class="selected"':'') .'><a href="?classid='. $rs['id'] .'">'. $rs['classname'] .'</a></li>';
		}
		?>
			</ul>
		</div>
	</div>
	<div class="nright1">
		<div class="position">当前位置：<a href="./">首页</a> &gt; <a href="contactus.php"><?php echo $parentclass['classname'];?></a> &gt; <?php echo $class['classname'];?></div>
		<div class="textbox">
				<div class="head">
					<div class="headbg">
						<div class="title"><?php echo $class['classname'];?></div>
					</div>
				</div>
				<div class="contentbox">
					    <?php echo $detail['content'];?>
				</div>
		</div>
	</div>
</div>
<?php require('inc.footer.php'); ?>
</body>
</html>