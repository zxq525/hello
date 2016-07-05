<?php
require 'inc.common.php';

if(!checkint($id)){jsalert("没有找到相关信息，请重试；","back","window");}
$detail=$db->fetch_first("select * from {$dbtablepre}article where id=".(int)$id."");
if(!$detail){
	jsalert("没有找到相关信息，请重试；","back","window");
}

$class=$db->fetch_first("SELECT * FROM {$dbtablepre}article_class where id=". (int)$detail['classid'] ."");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $detail['topic'];?></title>
<link href="style/css.css" rel="stylesheet" type="text/css" />
<link href="style/base.css" rel="stylesheet" type="text/css" />
<!--<script type="text/javascript" src="js/js.js"></script>-->
</head>

<body>
<?php require('inc.header.php'); ?>
<div id="main" style="margin-bottom:20px">
<div class="main">
	<div class="ny-nav">
    	<span><a href="./">首页</a> &gt; <?php echo $class['classname'];?></span>
    </div>
	
    <div class="ny-left">
    	<div class="ny-left-tit"><?php echo $detail['topic']; ?></div>
        <div class="ny-left-time">发布时间：<?php echo date('Y-m-d',$row['joindate']); ?></div>
        <div class="ny-nr"><?php
				echo $detail['content'];
				?></div>
    </div>

    <div class="ny-right">
    	<div class="bidu">
        	<div class="tit"><span><a href="news_class.php?classid=2">/更多</a></span><p>今日必读</p></div>
            <div class="bidu-nr">
            	<ul>
<?php
	$query=$db->query("select A.*,B.classname from {$dbtablepre}article as A left join {$dbtablepre}article_class as B on A.classid=B.id where A.classid=2 order by A.joindate desc,A.id desc limit 8");
	while($data = $db->fetch_array($query)) {
?>
                	<li><a href="newsshow.php?id=<?php echo $data['id'];?>"><?php echo getcutstr($data['topic'],28); ?></a></li>
<?php }?>
                </ul>
            </div>
        </div>
    	<div class="bidu" style="margin-top:20px">
        	<div class="tit"><span><a href="news_class.php?classid=15">/更多</a></span><p>赢在钱隆</p></div>
            <div class="bidu-nr">
            	<ul>
<?php
	$query=$db->query("select A.*,B.classname from {$dbtablepre}article as A left join {$dbtablepre}article_class as B on A.classid=B.id where A.classid=15 order by A.joindate desc,A.id desc limit 9");
	while($data = $db->fetch_array($query)) {
?>
                	<li><a href="newsshow.php?id=<?php echo $data['id'];?>"><?php echo getcutstr($data['topic'],40); ?></a></li>
<?php }?>
                </ul>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>
<?php require('inc.footer.php'); ?>
</body>
</html>
