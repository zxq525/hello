<?php
require 'inc.common.php';
$parentclassid = 9;
$classid=10;
$parentclass=$db->fetch_first("SELECT * FROM {$dbtablepre}article_class where id=$parentclassid limit 1");
if(!checkint($classid)){
	$class=$db->fetch_first("SELECT * FROM {$dbtablepre}article_class where parentid=$parentclassid order by sort_order asc, id asc limit 1");
}else{
	$class=$db->fetch_first("SELECT * FROM {$dbtablepre}article_class where parentid=$parentclassid and id=$classid order by sort_order asc, id asc limit 1");
	if(!$class){
		$class=$db->fetch_first("SELECT * FROM {$dbtablepre}article_class where parentid=$parentclassid order by sort_order asc, id asc limit 1");
	}
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
<div class="title_pos">
<h3><?php echo $class['classname'];?></h3><div class="position">当前位置：<a href="./">首页</a> &gt; <?php echo $class['classname'];?></div>
</div>
<div class="clear"></div>

	<div class="nleft2">
		
		<div class="listbox">
				<div class="head">
					<div class="headbg">
						<div class="title"><?php echo $class['classname'];?></div>
					</div>
				</div>
				<div class="list">
					    <ul>
<?php
unset($daynum);
$query = "select * from {$dbtablepre}article where classid=". (int)$class['id'] ." order by sort_order desc, joindate desc,id desc";
$num=$db->num_rows($db->query($query));
require_once(ROOT_PATH."classes/class.page.php");
$pagecls = new pagecls($num,15,$page,"?");
$query=$db->query("$query LIMIT {$pagecls->startrecord},{$pagecls->pagesize}");
$i=0;
while($rs = $db->fetch_array($query)) {
?>
      <li><div class="topic"><a href="solution_detail.php?id=<?php echo $rs['id'];?>" target="_blank"><?php echo $rs['topic'];?></a></div><div class="date"><?php echo date('Y-m-d',$rs['joindate']);?></div></li>
<?php
	$i++;
}
?>
						</ul>
					<div class="pagenav"><?php echo $pagecls->pageinfo;?></div>
				</div>
		</div>
	</div>
	<?php require('inc.right.php'); ?>

</div>
<?php require('inc.footer.php'); ?>
</body>
</html>