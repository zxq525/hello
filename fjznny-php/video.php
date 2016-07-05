<?php require('inc.common.php'); ?>
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
	<div class="ny-banner"><script src="js/banner.js" language="javascript" type="text/javascript"></script></div>
    <?php
	$query=$db->query("SELECT * FROM {$dbtablepre}video_class ORDER BY sort_order ASC , video_class_id ASC");
	while($rs = $db->fetch_array($query)){
	?>
    <div class="video-list">
    	<div class="video-1"><img src="<?php echo $uppath['video_image'].$rs['classimg'];?>" width="146" height="92" /></div>
        <div class="video-2">
        	<p><img src="images/pic-8.jpg" width="227" height="31" /></p>
            <span>节目名称：<?php echo $rs['classname'];?><br /></span>
			<div class="clear"></div>
            <span><a href="video_class.php?classid=<?php echo $rs['video_class_id'];?>" style="float:left" class="button button-rounded button-flat-primary radius">立即观看</a>
            <a href="video_class.php?classid=<?php echo $rs['video_class_id'];?>" style="float:left; margin-left:20px" class="button button-rounded button-flat-primary radius">更多视频</a>
            </span>
        </div>
        <div class="video-3">
<?php
	$query=$db->query("select A.*,B.classname from {$dbtablepre}video as A left join {$dbtablepre}video_class as B on A.video_class_id=B.video_class_id where A.video_class_id=". $rs['video_class_id'] ." order by A.joindate desc,A.video_id desc limit 3");
	while($data = $db->fetch_array($query)) {
?>
        	<div class="video-3-li">
            	<p><a href="video_detail.php?id=<?php echo $data['video_id'];?>"><img src="<?php if($data['videoimg']){echo $uppath['video_image'] . $data['videoimg'];}?>" width="160" height="100" /></a></p>
                <p><a href="video_detail.php?id=<?php echo $data['video_id'];?>"><?php echo getcutstr($data['videoname'],24); ?></a></p>
            </div>
<?php }?>
        </div>
    </div>
<?php }?>
</div>
</div>
</div>
</div>

<?php require('inc.footer.php'); ?>
</body>
</html>
