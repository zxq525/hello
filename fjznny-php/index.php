<?php require('inc.common.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $config['config_home_title'];?></title>
<meta name="keywords" content="<?php echo $config['config_home_keywords'];?>" />
<meta name="description" content="<?php echo $config['config_home_description'];?>" />
<link href="favicon.ico" rel="shortcut icon" />
<link href="style/style.css" rel="stylesheet" type="text/css" />
<link href="style/slider.css" rel="stylesheet" type="text/css" />
<link href="style/slidebox.css" rel="stylesheet" type="text/css" />
<link href="style/rcarousel.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/common.js"></script>
<script type="text/javascript" src="js/slider.js"></script>
<script type="text/javascript" src="js/slidebox.js"></script>
<script type="text/javascript" src="js/jcarousellite.js"></script>
<script type="text/javascript" src="js/mousewheel.js"></script>
</head>

<body>
<?php require('inc.header.php'); ?>
<div class="slideblock">
<div class="slides">
    <ul id="slides">
    <?php
	$topad = groupad(1);
	if($topad){
	foreach($topad as $value){
	?>
        <li style="background-image: url('<?php echo $value['linkimg'];?>');"><a href="<?php if($value['linkurl']){?><?php echo $value['linkurl'].'" target="_blank"';}else{?>javascript:void(0);"<?php }?>"></a></li>
    <?php
	}}
	?>
    </ul>
</div>
</div>

<div id="mainWrapper">
<div class="block1">
	<div class="bleft">
		<div class="switchimg">
	<div id="mSlidebox_3" class="slidebox">
		<ul>
    <?php
	$topad = groupad(2);
	if($topad){
	foreach($topad as $value){
	?>
    		<li><a href="<?php if($value['linkurl']){?><?php echo $value['linkurl'].'" target="_blank"';}else{?>javascript:void(0);"<?php }?>><img src="<?php echo $value['linkimg'];?>" title="<?php echo $value['linkname'];?>" width="300" height="200" /></a></li>
    <?php
	}}
	?>
		</ul>
	</div>
    <script type="text/javascript">
	$("#mSlidebox_3").mSlidebox({
		easeType:"swing",
		numberedThumbnails:true,
		pauseOnHover:true
	});
    </script>
		</div>
		<div class="info_sd">
			<div class="head">
				<div class="title">专家建议</div>
				<div class="more"><a href="suggest.php">更多 &gt;&gt;</a></div>
			</div>
			<div class="list">
				<ul>
<?php
	$query=$db->query("select A.*,B.classname,B.classpage from {$dbtablepre}article as A left join {$dbtablepre}article_class as B on A.classid=B.id where B.id = 22 order by A.joindate desc,A.id desc limit 4");
	while($rs = $db->fetch_array($query)){
?>
					<li><a href="<?php echo $rs['classpage'];?>_detail.php?id=<?php echo $rs['id'];?>"><?php echo getcutstr($rs['topic'], 40);?></a></li>
<?php
	}
?>
				</ul>
			</div>
		</div>
	</div>
    
	<div class="bmid">
		<div class="info_new">
			<div class="head">
            	<div class="headbg">
					<div class="title">最新资讯</div>

                </div>
			</div>
			<div class="list">
			<?php
			$id_img = 0;
			$data_img=$db->fetch_first("select A.*,B.classname,B.classpage from {$dbtablepre}article as A left join {$dbtablepre}article_class as B on A.classid=B.id where B.id in (6,8) AND A.infoimg<>'' order by A.joindate desc,A.id desc limit 1");
			if($data_img){
				$id_img = $data_img['id'];
			?>
            	<div class="topinfo">
                    <div class="title"><a href="<?php echo $data_img['classpage'];?>_detail.php?id=<?php echo $data_img['id'];?>" title="<?php echo $data_img['topic'];?>"><?php echo getcutstr($data_img['topic'], 36);?></a></div>
                    <div class="description"><?php echo getcutstr($data_img['infointro'], 140);?>..<a href="<?php echo $data_img['classpage'];?>_detail.php?id=<?php echo $data_img['id'];?>">[查看全文]</a></div>
                </div>
			<?php }?>
				<ul>
<?php
	$query=$db->query("select A.*,B.classname,B.classpage from {$dbtablepre}article as A left join {$dbtablepre}article_class as B on A.classid=B.id where B.id in (6,8) order by A.joindate desc,A.id desc limit 7");
	while($rs = $db->fetch_array($query)){
?>
					<li><div><a href="<?php echo $rs['classpage'];?>_detail.php?id=<?php echo $rs['id'];?>"><?php echo getcutstr($rs['topic'], 45);?></a></div><span><?php echo date('Y-m-d',$rs['joindate']);?></span></li>
<?php
	}
?>
				</ul>
			</div>
		</div>
	</div>
    
	<div class="bright">
		<div class="info_rd">
			<div class="head">
            	<div class="headbg">
					<div class="title">热点信息</div>

                </div>
			</div>
			<div class="list">
			<?php
			$id_img = 0;
			$data_img=$db->fetch_first("select A.*,B.classname,B.classpage from {$dbtablepre}article as A left join {$dbtablepre}article_class as B on A.classid=B.id where B.id in (6,8) AND A.infoimg<>'' order by A.hitnum desc,A.id desc limit 1");
			if($data_img){
				$id_img = $data_img['id'];
			?>
            	<div class="topinfo">
					<div class="image"><a href="<?php echo $data_img['classpage'];?>_detail.php?id=<?php echo $data_img['id'];?>"><img src="<?php echo $uppath['article'].$data_img['infoimg'];?>" width="87" height="59" /></a></div>
                    <div class="title"><a href="<?php echo $data_img['classpage'];?>_detail.php?id=<?php echo $data_img['id'];?>"><?php echo getcutstr($data_img['topic'], 56);?></a></div>
                    <div class="description"><?php echo getcutstr($data_img['infointro'], 94);?>..<a href="<?php echo $data_img['classpage'];?>_detail.php?id=<?php echo $data_img['id'];?>">[查看全文]</a></div>
                </div>
			<?php }?>
				<ul>
<?php
	$i=2;
	$query=$db->query("select A.*,B.classname,B.classpage from {$dbtablepre}article as A left join {$dbtablepre}article_class as B on A.classid=B.id where B.id in (6,8) order by A.hitnum desc,A.joindate desc,A.id desc limit 6");
	while($rs = $db->fetch_array($query)){
?>
				<li><span><img src="images/num<?php echo $i;?>.png" width="19" height="14" /></span><div><a href="<?php echo $rs['classpage'];?>_detail.php?id=<?php echo $rs['id'];?>"><?php echo getcutstr($rs['topic'], 32);?></a></div></li>
<?php
	$i++;}
?>
				</ul>
			</div>
		</div>
	</div>
</div>
<div class="clear"></div>
<div class="ban01">
	<?php echo viewad(3,980,120);?>
</div>

<div class="clear"></div>
<div class="block2">
	<div class="b2left">
			<div class="head">
            	<div class="headbg">
					<div class="title">解决方案</div>
					<div class="more"><a href="solution.php">更多 &gt;&gt;</a></div>
                </div>
			</div>
			<div class="list">
			<?php
			$id_img = 0;
			$data_img=$db->fetch_first("select A.*,B.classname,B.classpage from {$dbtablepre}article as A left join {$dbtablepre}article_class as B on A.classid=B.id where B.id =10 AND A.infoimg<>'' order by A.hitnum desc,A.id desc limit 1");
			if($data_img){
				$id_img = $data_img['id'];
			?>
            	<div class="topinfo">
					<div class="image"><a href="<?php echo $data_img['classpage'];?>_detail.php?id=<?php echo $data_img['id'];?>"><img src="<?php echo $uppath['article'].$data_img['infoimg'];?>" width="87" height="59" /></a></div>
                    <div class="title"><a href="<?php echo $data_img['classpage'];?>_detail.php?id=<?php echo $data_img['id'];?>" title="<?php echo $data_img['topic'];?>"><?php echo getcutstr($data_img['topic'], 26);?></a></div>
                    <div class="description"><?php echo getcutstr($data_img['infointro'], 94);?>..<a href="<?php echo $data_img['classpage'];?>_detail.php?id=<?php echo $data_img['id'];?>">[查看全文]</a></div>
                </div>
			<?php }?>
				<ul>
<?php
	$query=$db->query("select A.*,B.classname,B.classpage from {$dbtablepre}article as A left join {$dbtablepre}article_class as B on A.classid=B.id where B.id =10 order by A.hitnum desc,A.joindate desc,A.id desc limit 5");
	while($rs = $db->fetch_array($query)){
?>
				<li><a href="<?php echo $rs['classpage'];?>_detail.php?id=<?php echo $rs['id'];?>"><?php echo getcutstr($rs['topic'], 45);?></a></li>
<?php
	}
?>
				</ul>
			</div>
	</div>

	<div class="b2mid">
			<div class="head">
            	<div class="headbg">
					<div class="title">物联网应用</div>
					<div class="more"><a href="apply.php">更多 &gt;&gt;</a></div>
                </div>
			</div>
			<div class="list">
			<?php
			$id_img = 0;
			$data_img=$db->fetch_first("select A.*,B.classname,B.classpage from {$dbtablepre}article as A left join {$dbtablepre}article_class as B on A.classid=B.id where B.id =24 AND A.infoimg<>'' order by A.hitnum desc,A.id desc limit 1");
			if($data_img){
				$id_img = $data_img['id'];
			?>
            	<div class="topinfo">
					<div class="image"><a href="<?php echo $data_img['classpage'];?>_detail.php?id=<?php echo $data_img['id'];?>"><img src="<?php echo $uppath['article'].$data_img['infoimg'];?>" width="87" height="59" /></a></div>
                    <div class="title"><a href="<?php echo $data_img['classpage'];?>_detail.php?id=<?php echo $data_img['id'];?>" title="<?php echo $data_img['topic'];?>"><?php echo getcutstr($data_img['topic'], 26);?></a></div>
                    <div class="description"><?php echo getcutstr($data_img['infointro'], 94);?>..<a href="<?php echo $data_img['classpage'];?>_detail.php?id=<?php echo $data_img['id'];?>">[查看全文]</a></div>
                </div>
			<?php }?>
				<ul>
<?php
	$query=$db->query("select A.*,B.classname,B.classpage from {$dbtablepre}article as A left join {$dbtablepre}article_class as B on A.classid=B.id where B.id =24 order by A.hitnum desc,A.joindate desc,A.id desc limit 5");
	while($rs = $db->fetch_array($query)){
?>
				<li><a href="<?php echo $rs['classpage'];?>_detail.php?id=<?php echo $rs['id'];?>"><?php echo getcutstr($rs['topic'], 45);?></a></li>
<?php
	}
?>
				</ul>
			</div>
	</div>

	<div class="b2right">
			<div class="head">
            	<div class="headbg">
					<div class="title">聚焦农业</div>
					<div class="more"><a href="infoclass.php?classid=30">更多 &gt;&gt;</a></div>
                </div>
			</div>
			<div class="list">
			<?php
			$id_img = 0;
			$data_img=$db->fetch_first("select A.*,B.classname,B.classpage from {$dbtablepre}article as A left join {$dbtablepre}article_class as B on A.classid=B.id where B.id = 30 AND A.infoimg<>'' order by A.joindate desc,A.id desc limit 1");
			if($data_img){
				$id_img = $data_img['id'];
			?>
            	<div class="topinfo">
                    <div class="title"><a href="<?php echo $data_img['classpage'];?>_detail.php?id=<?php echo $data_img['id'];?>" title="<?php echo $data_img['topic'];?>"><?php echo getcutstr($data_img['topic'], 36);?></a></div>
                    <div class="description"><?php echo getcutstr($data_img['infointro'], 94);?>..<a href="<?php echo $data_img['classpage'];?>_detail.php?id=<?php echo $data_img['id'];?>">[查看全文]</a></div>
                </div>
			<?php }?>
				<ul>
<?php
	$query=$db->query("select A.*,B.classname,B.classpage from {$dbtablepre}article as A left join {$dbtablepre}article_class as B on A.classid=B.id where B.id = 30 order by A.joindate desc,A.id desc limit 5");
	while($rs = $db->fetch_array($query)){
?>
					<li><a href="<?php echo $rs['classpage'];?>_detail.php?id=<?php echo $rs['id'];?>"><?php echo getcutstr($rs['topic'], 35);?></a></li>
<?php
	}
?>
				</ul>
			</div>
	</div>
</div>

<div class="clear"></div>
<div class="block3">
	<div class="info_pi">
			<div class="head">
					<div class="title"><a href="product.php">物联产品</a></div>
					<div class="more"><a href="product.php">MORE..</a></div>
			</div>
			<div class="list">
			<div class="container" id="container">
			<div class="carousel" id="carousel">
				<ul>
<?php
	$query=$db->query("select A.*,B.classname,B.classpage from {$dbtablepre}article as A left join {$dbtablepre}article_class as B on A.classid=B.id where B.id = 16 AND infoimg<>'' order by A.joindate desc,A.id desc limit 10");
	while($rs = $db->fetch_array($query)){
?>
				<li><a href="<?php echo $rs['classpage'];?>_detail.php?id=<?php echo $rs['id'];?>"><img src="<?php echo $uppath['article'].$rs['infoimg'];?>" width="169" height="119" /></a><p><a href="<?php echo $rs['classpage'];?>_detail.php?id=<?php echo $rs['id'];?>" title="<?php echo $rs['topic'];?>"><?php echo getcutstr($rs['topic'], 20);?></a></p></li>
<?php }?>
				</ul>
			</div>
			</div>
			</div>
	</div>
</div>
<script type="text/javascript">
	$(".carousel").jCarouselLite({
		auto: 2000,
		speed: 1000,
		visible: 5,
		onMouse:true
	});
</script>

<div class="clear"></div>
<div class="block4">
	<div class="b3left">
			<div class="head">
            	<div class="headbg">
					<div class="title">应用案例</div>
					<div class="more"><a href="case.php">更多 &gt;&gt;</a></div>
                </div>
			</div>
			<div class="list">
<?php
	$i=0;
	$query=$db->query("select A.*,B.classname,B.classpage from {$dbtablepre}article as A left join {$dbtablepre}article_class as B on A.classid=B.id where B.id = 18 AND infoimg<>'' order by A.joindate desc,A.id desc limit 4");
	while($rs = $db->fetch_array($query)){
?>
            	<div class="topinfo">
					<div class="image"><a href="<?php echo $rs['classpage'];?>_detail.php?id=<?php echo $rs['id'];?>"><img src="<?php echo $uppath['article'].$rs['infoimg'];?>" width="150" height="100" /></a></div>
                    <div class="title"><a href="<?php echo $rs['classpage'];?>_detail.php?id=<?php echo $rs['id'];?>" title="<?php echo $rs['topic'];?>"><?php echo getcutstr($rs['topic'], 20);?></a></div>
                    <div class="description"><?php echo getcutstr($rs['infointro'], 94);?>..<a href="<?php echo $rs['classpage'];?>_detail.php?id=<?php echo $rs['id'];?>">[查看全文]</a></div>
                </div>
<?php $i++;
if($i==2){echo '<div class="line"></div>';}
}?>
			</div>
	</div>
	<div class="b3right">
			<div class="head">
            	<div class="headbg">
					<div class="title">商业机会</div>
					<div class="more"><a href="opportunity.php">更多 &gt;&gt;</a></div>
                </div>
			</div>
			<div class="list">
			<?php
			$id_img = 0;
			$data_img=$db->fetch_first("select A.*,B.classname,B.classpage from {$dbtablepre}article as A left join {$dbtablepre}article_class as B on A.classid=B.id where B.id = 6 AND A.isgood=1 order by A.joindate desc,A.id desc limit 1");
			if($data_img){
				$id_img = $data_img['id'];
			?>
            	<div class="topinfo">
                    <div class="title"><a href="<?php echo $data_img['classpage'];?>_detail.php?id=<?php echo $data_img['id'];?>" title="<?php echo $data_img['topic'];?>"><?php echo getcutstr($data_img['topic'], 36);?></a></div>
                    <div class="description"><?php echo getcutstr($data_img['infointro'], 94);?>..<a href="<?php echo $data_img['classpage'];?>_detail.php?id=<?php echo $data_img['id'];?>">[查看全文]</a></div>
                </div>
			<?php }?>
				<ul>
<?php
	$query=$db->query("select A.*,B.classname,B.classpage from {$dbtablepre}article as A left join {$dbtablepre}article_class as B on A.classid=B.id where B.id = 6 order by A.joindate desc,A.id desc limit 5");
	while($rs = $db->fetch_array($query)){
?>
					<li><a href="<?php echo $rs['classpage'];?>_detail.php?id=<?php echo $rs['id'];?>"><?php echo getcutstr($rs['topic'], 35);?></a></li>
<?php
	}
?>
				</ul>
			</div>
	</div>
</div>

<div class="clear"></div>
<div class="block7">
	<div class="info_pi2">
			<div class="head">
					<div class="title"><a href="infoclass2.php?classid=32">农产品信息</a></div>
					<div class="more"><a href="infoclass2.php?classid=32">MORE..</a></div>
			</div>
			<div class="list">
			<div class="container" id="container2">
			<div class="carousel" id="carousel2">
				<ul>
<?php
	$query=$db->query("select A.*,B.classname,B.classpage from {$dbtablepre}article as A left join {$dbtablepre}article_class as B on A.classid=B.id where B.id = 32 AND infoimg<>'' order by A.joindate desc,A.id desc limit 10");
	while($rs = $db->fetch_array($query)){
?>
				<li><a href="<?php echo $rs['classpage'];?>_detail.php?id=<?php echo $rs['id'];?>"><img src="<?php echo $uppath['article'].$rs['infoimg'];?>" width="169" height="119" /></a><p><a href="<?php echo $rs['classpage'];?>_detail.php?id=<?php echo $rs['id'];?>" title="<?php echo $rs['topic'];?>"><?php echo getcutstr($rs['topic'], 20);?></a></p></li>
<?php }?>
				</ul>
			</div>
			</div>
			</div>
	</div>
</div>
<script type="text/javascript">
	$("#carousel2").jCarouselLite({
		auto: 2000,
		speed: 1000,
		visible: 5,
		onMouse:true
	});
</script>
<div class="clear"></div>
<div class="block5">
	<div class="b5left">
			<div class="head">
            	<div class="headbg">
					<div class="title">政策法规</div>
					<div class="more"><a href="regulations.php">更多 &gt;&gt;</a></div>
                </div>
			</div>
			<div class="list">
				<ul>
<?php
	$query=$db->query("select A.*,B.classname,B.classpage from {$dbtablepre}article as A left join {$dbtablepre}article_class as B on A.classid=B.id where B.id = 8 order by A.joindate desc,A.id desc limit 6");
	while($rs = $db->fetch_array($query)){
?>
					<li><a href="<?php echo $rs['classpage'];?>_detail.php?id=<?php echo $rs['id'];?>"><?php echo getcutstr($rs['topic'], 50);?></a></li>
<?php
	}
?>
				</ul>
			</div>
	</div>

	<div class="b5mid">
			<div class="head">
            	<div class="headbg">
					<div class="title">企业专栏</div>
					<div class="more"><a href="infoclass.php?classid=26">更多 &gt;&gt;</a></div>
                </div>
			</div>
			<div class="list">
				<ul>
<?php
	$query=$db->query("select A.*,B.classname,B.classpage from {$dbtablepre}article as A left join {$dbtablepre}article_class as B on A.classid=B.id where B.id = 26 order by A.joindate desc,A.id desc limit 6");
	while($rs = $db->fetch_array($query)){
?>
					<li><a href="<?php echo $rs['classpage'];?>_detail.php?id=<?php echo $rs['id'];?>"><?php echo getcutstr($rs['topic'], 50);?></a></li>
<?php
	}
?>
				</ul>
			</div>
	</div>

	<div class="b5right">
			<div class="head">
            	<div class="headbg">
					<div class="title">行业标准</div>
					<div class="more"><a href="infoclass.php?classid=28">更多 &gt;&gt;</a></div>
                </div>
			</div>
			<div class="list">
				<ul>
<?php
	$query=$db->query("select A.*,B.classname,B.classpage from {$dbtablepre}article as A left join {$dbtablepre}article_class as B on A.classid=B.id where B.id = 28 order by A.joindate desc,A.id desc limit 6");
	while($rs = $db->fetch_array($query)){
?>
					<li><a href="<?php echo $rs['classpage'];?>_detail.php?id=<?php echo $rs['id'];?>"><?php echo getcutstr($rs['topic'], 34);?></a></li>
<?php
	}
?>
				</ul>
			</div>
	</div>
</div>

<div class="clear"></div>
<div class="block7">
	<div class="info_pi2">
			<div class="head">
					<div class="title"><a href="infoclass2.php?classid=34">名企介绍</a></div>
					<div class="more" style="padding-top:35px;"><a href="infoclass2.php?classid=34">MORE..</a></div>
			</div>
			<div class="list2" id="zmqy">
				<div class="border">
				<div class="listrow">
<?php
	$i=0;
	$query=$db->query("select A.*,B.classname,B.classpage from {$dbtablepre}article as A left join {$dbtablepre}article_class as B on A.classid=B.id where B.id = 34 AND infoimg<>'' order by A.joindate desc,A.id desc limit 18");
	$num = $db->num_rows($query);
	while($rs = $db->fetch_array($query)){
?>
				<div><a href="<?php echo $rs['classpage'];?>_detail.php?id=<?php echo $rs['id'];?>" title="<?php echo $rs['topic'];?>"><img src="<?php echo $uppath['article'].$rs['infoimg'];?>" width="145" height="60" alt="<?php echo $rs['topic'];?>" /></a></div>
<?php 
$i++;
if($i % 6==0 && $i!=$num){
	echo '</div><div class="listrow">';
}
}?>
				</div>
				</div>
			</div>
	</div>
</div>

<script language="javascript" type="text/javascript">
<!--
    OnScrool();
    var timer_onScrool = null;
    var isUpScrool = true;
    //定时进行向上滚动
    function OnScrool() {
       timer_onScrool=setInterval(ScroolMsg,3000);
   }

   function StartUpScrool() {
       isUpScrool = true;
   }

   //停止向上滚动
   function EndUpScrool() {
       isUpScrool = false;
   }

    //滚动
   function ScroolMsg() {
       if (isUpScrool) {
           $("#zmqy").find("div.listrow").each(function (i) {
               if (i == 0) {
                   $("#zmqy").find("div.listrow").last()
                   $("<div class='listrow'></div>").html($(this).html()).insertAfter($("#zmqy").find("div.listrow").last());
                   $(this).slideUp("slow", ScroolEnd);
               }
           });
       }
    }

    //移出原有的节点

    function ScroolEnd() {
        $(this).remove();
    }
-->
</script>

<div class="clear"></div>
<div class="block6">
	<div class="bbg">
			<div class="head">
					<div class="title">友情链接</div>
			</div>
			<div class="list">
				<ul>
<?php
	$i=0;
	$query=$db->query("select * from {$dbtablepre}friendlink where linktype=1 order by sort_order desc,id desc");
	while($rs = $db->fetch_array($query)) {
?>
	<li><a href="<?php echo $rs['linkurl'];?>" title="<?php echo $rs['linkname'];?>" target="_blank"><?php echo $rs['linkname'];?></a></li>
<?php
	$i++;
	}
?>
				</ul>
			</div>
	</div>
</div>

</div>
<?php require('inc.footer.php'); ?>
</body>
</html>
