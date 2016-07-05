<?php
require 'inc.common.php';
if(!checkint($classid)){
	jsalert("没有找到相关信息，请重试；","back","window");
}else{
	$class=$db->fetch_first("SELECT * FROM {$dbtablepre}video_class where video_class_id=$classid order by sort_order asc, video_class_id asc limit 1");
	if(!$class){
		jsalert("没有找到相关信息，请重试；","back","window");
	}
}
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
    	<span>当前位置：<a href="./">首页</a> &gt; <a href="video.php">视频专区</a> &gt; <?php echo $class['classname'];?></span>
    </div>
	
    <div class="ny-left">
		
        <div class="ny-nr">
			<div class="video-4">
<?php
$query = "select A.*,B.classname from {$dbtablepre}video as A left join {$dbtablepre}video_class as B on A.video_class_id=B.video_class_id where A.video_class_id=". (int)$class['video_class_id'] ." order by A.joindate desc,A.video_id desc";
$num=$db->num_rows($db->query($query));
require_once(ROOT_PATH."classes/class.page.php");
$pagecls = new pagecls($num,12,$page,"?classid=".$classid);
$query=$db->query("$query LIMIT {$pagecls->startrecord},{$pagecls->pagesize}");
$i=0;
while($rs = $db->fetch_array($query)) {
?>
        	<div class="video-4-li">
            	<p><a href="video_detail.php?id=<?php echo $rs['video_id'];?>"><img src="<?php if($rs['videoimg']){echo $uppath['video_image'] . $rs['videoimg'];}?>" width="160" height="100" /></a></p>
                <p><a href="video_detail.php?id=<?php echo $rs['video_id'];?>"><?php echo getcutstr($rs['videoname'],24); ?></a></p>
            </div>
<?php
	$i++;
}
?>
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

<div id="bottom">
  <div class="bottom">
    	<div class="bottom-left">
        	<div class="bottom-left1">
        	<ul>
            	<li class="b-1" style="height:30px">合作机构</li>
                <li><a href="">建设银行</a></li>
                <li><a href="">建设银行</a></li>
                <li><a href="">建设银行</a></li>
                <li><a href="">建设银行</a></li>
            </ul>
            </div>
            <div class="bottom-left1">
        	<ul>
            	<li class="b-1" style="height:30px">合作机构</li>
                <li><a href="">建设银行</a></li>
                <li><a href="">建设银行</a></li>
                <li><a href="">建设银行</a></li>
                <li><a href="">建设银行</a></li>
            </ul>
            </div>
            <div class="bottom-left1">
        	<ul>
            	<li class="b-1" style="height:30px">合作机构</li>
                <li><a href="">建设银行</a></li>
                <li><a href="">建设银行</a></li>
                <li><a href="">建设银行</a></li>
                <li><a href="">建设银行</a></li>
            </ul>
            </div>
            <div class="bottom-left1">
        	<ul>
            	<li class="b-1" style="height:30px">合作机构</li>
                <li><a href="">建设银行</a></li>
                <li><a href="">建设银行</a></li>
                <li><a href="">建设银行</a></li>
                <li><a href="">建设银行</a></li>
            </ul>
            </div>
        </div>
        
        <div class="bottom-right">
        <span style="margin-top:20px">泉州总部地址：福建省泉州市鲤城区南俊巷180号A2</span><br />
        <span>电话：400-099-2766</span><br />
        <span><img src="images/pic-5.jpg" width="465" height="50" /></span>
        </div>
        
    <div class="foot">
        <span><a href="">关于我们</a>  |  <a href="">法律声明</a>  |  <a href="">版权声明</a>  |  <a href="">免责声明</a>  |  <a href="">诚聘英才</a>  |  <a href="">征稿启事</a>  |  <a href="">隐私政策</a>  |  <a href="">联系我们</a></span>
        <span>Copyright 钱隆财富网 All Rights Reserved 版权所有</span>
        </div>
    </div>
</div>
<script type="text/javascript" src="js/gg.js"></script>
</body>
</html>
