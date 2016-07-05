<?php
require 'inc.common.php';

if(!checkint($id)){jsalert("没有找到相关信息，请重试；","back","window");}
$detail=$db->fetch_first("select * from {$dbtablepre}video where video_id=".(int)$id."");
if(!$detail){
	jsalert("没有找到相关信息，请重试；","back","window");
}

$class=$db->fetch_first("SELECT * FROM {$dbtablepre}video_class where video_class_id=". (int)$detail['classid'] ."");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $class['classname'];?>,<?php echo $config['config_name'];?></title>
<link href="style/css.css" rel="stylesheet" type="text/css" />
<link href="style/base.css" rel="stylesheet" type="text/css" />
<!--<script type="text/javascript" src="js/js.js"></script>-->
</head>

<body>
<?php require('inc.header.php'); ?>
<div id="main" style="margin-bottom:20px">
<div class="main">
	<div class="ny-nav">
    	<span>当前位置：<a href="./">首页</a> > <a href="video.php">视频专区</a> > <a href="video_class.php?classid=<?php echo $class['video_class_id'];?>"><?php echo $class['classname'];?></a> > <?php echo $detail['videoname'];?></span>
    </div>
	
    <div class="ny-left">
		
        <div class="ny-nr">
			<div class="video-4" style=" text-align:center">
            <embed src="<?php echo $detail['videourl'];?>" allowfullscreen="true" quality="high" width="649" height="534" align="middle" allowscriptaccess="always" type="application/x-shockwave-flash">
            
            
            <div class="video-tit"></div>
<?php
	$query=$db->query("select A.*,B.classname from {$dbtablepre}video as A left join {$dbtablepre}video_class as B on A.video_class_id=B.video_class_id order by A.joindate desc,A.video_id desc limit 4");
	while($data = $db->fetch_array($query)) {
?>
            <div class="video-4-li">
            	<p><a href="video_detail.php?id=<?php echo $data['video_id'];?>"><img src="<?php if($data['videoimg']){echo $uppath['video_image'] . $data['videoimg'];}?>" width="160" height="100" /></a></p>
                <p><a href="video_detail.php?id=<?php echo $data['video_id'];?>"><?php echo getcutstr($data['videoname'],24); ?></a></p>
            </div>
<?php }?>
            
            </div>
        </div>
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
