	<div class="nright2">
		<div class="info_r1">
			<div class="head">
            	<div class="headbg">
					<div class="title">热门排行</div>
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

	<div class="info_r2">
			<div class="head">
            	<div class="headbg">
					<div class="title">最新信息</div>
                </div>
			</div>
			<div class="list">
			<?php
			$id_img = 0;
			$data_img=$db->fetch_first("select A.*,B.classname,B.classpage from {$dbtablepre}article as A left join {$dbtablepre}article_class as B on A.classid=B.id where B.id in (6,8) AND A.isgood=1 order by A.joindate desc,A.id desc limit 1");
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
	$query=$db->query("select A.*,B.classname,B.classpage from {$dbtablepre}article as A left join {$dbtablepre}article_class as B on A.classid=B.id where B.id in (6,8) order by A.joindate desc,A.id desc limit 5");
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