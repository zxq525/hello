<?php
require 'inc.checkhead.php';
$pagetitle = "视频信息";
$urlquery = "&s_video_class_id=".urlencode($s_video_class_id)."&s_videoname=".urlencode($s_videoname)."&s_truename=".urlencode($s_truename)."";
$upconfig = array('ext'=>'gif|jpg','size'=>1024*500,'savepath'=>$uppath['video_image'],'addpath'=>'');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $pagetitle;?></title>
<link href="style/style.css" rel="stylesheet" type="text/css" />
<link href="style/colorbox.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/common.js"></script>
<script type="text/javascript" src="js/colorbox.js"></script>
</head>
<body>
<div class="bodybox" id="bodybox">
<?php
if($action=="add"){
	if($act=="addsave"){
		if(!checkint($video_class_id)){$errmsg .= '@@请选择所属分类；';}
		if(!$videoname){$errmsg .= '@@请输入视频名称；';}
	
		if(!$errmsg && $_FILES['videoimg']['name']){
			require(ROOT_PATH.'classes/class.upload.php');
			$f = new upload("videoimg", $upconfig['savepath'], $upconfig['addpath'],$upconfig['ext'], $upconfig['size']);
			if($f->error != 0){
				$errmsg.="@@".$f->errormsg()."；\\n";
			}else{
				$videoimg=$f->uploadedfiles[0]['addpath'].$f->uploadedfiles[0]['savename'];	
			}
		}

		if(!$errmsg){
			$joindate = time();
			$db->query("insert into {$dbtablepre}video(video_class_id,videoname,videoimg,videourl,content,joindate)values('$video_class_id','$videoname','$videoimg','$videourl','$content','$joindate')");
			showTips('信息添加成功','?mp='. $mp .'&action=add&video_class_id='.$video_class_id);
		}
		showErrTips($errmsg);
	}
?>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="tablebk">
<form name="Form1" action="?<?php echo 'mp='.$mp;?>&action=add&act=addsave" method="post" enctype="multipart/form-data" target="postpanel">
  <tr>
    <td width="18%" align="right">所属分类：</td>
    <td><select name="video_class_id" id="video_class_id">
    <option value="">--请选择--</option>
    <?php
	$query=$db->query("SELECT * FROM {$dbtablepre}video_class ORDER BY sort_order ASC , video_class_id ASC");
	while($rs = $db->fetch_array($query)){
	?>
    	<option value="<?php echo $rs['video_class_id']?>"<?php echo setselected($video_class_id,$rs['video_class_id']);?>><?php echo $rs['classname']?></option>
    <?php
	}
	?>
    </select>
    </td>
  </tr>
  <tr>
    <td align="right">视频名称：</td>
    <td><input name="videoname" type="text" class="input" size="30" value="<?php echo $data['videoname'];?>"></td>
  </tr>
  <tr>
    <td align="right">视频图片：</td>
    <td><input name="videoimg" type="file" class="input" value="<?php echo $data['videoimg'];?>" size="30" style="height:22px;" /></td>
  </tr>
  <tr>
    <td align="right">视频地址：</td>
    <td><input name="videourl" type="text" class="input" size="80" value="<?php echo $data['videourl'];?>"></td>
  </tr>
  <tr>
    <td height="24" align="right"><strong>视频简介：</strong></td>
    <td><?php
		require '../plugins/editor/fckeditor/fckeditor.php';
		$oFCKeditor = new FCKeditor('content');
		$oFCKeditor->BasePath=EDITOR_BASEPATH;
		$oFCKeditor->Value=$data[content];
		$oFCKeditor->Create(); 
	?></td>
  </tr>
  <tr>
    <td height="40" colspan="2" align="center"><input type="submit" name="Submit" value="提交"> 
      &nbsp; 
      <input type="button" name="Submit" value="返回列表" onClick="parent.closeFrame();reloadmain();"></td>
  </tr>
  </form>
</table>
<?php
}
if($action=="list"){
?>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="tablebk">
  <tr>
  	<th colspan="8"><h2><?php echo $pagetitle;?>列表</h2></th>
  </tr>
  <tr>
    <td colspan="8" class="table_trbg01">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" id="searchtd">
        <form name="Form2" method="get" action="?">
        <input type="hidden" name="action" value="list" /><input type="hidden" name="mp" value="<?php echo $mp;?>" />
      <tr>
        <td align="right">
<select name="s_video_class_id" id="s_video_class_id">
    <option value="">--所有分类--</option>
    <?php
	$query=$db->query("SELECT * FROM {$dbtablepre}video_class ORDER BY sort_order ASC , video_class_id ASC");
	while($rs = $db->fetch_array($query)){
	?>
    	<option value="<?php echo $rs['video_class_id']?>"<?php echo setselected($s_video_class_id,$rs['video_class_id']);?>><?php echo $rs['classname']?></option>
    <?php
	}
	?>
</select>        
账户:<input name="s_videoname" type="text" size="15" class="input" value="<?php echo $s_videoname;?>" /> &nbsp; 
姓名:<input name="s_truename" type="text" size="15" class="input" value="<?php echo $s_truename;?>" />
		 						<input type="submit" value="搜索" /></td>
        
      </tr></form>
    </table></td>
  </tr>
<form name="Form1" method="post" action="?<?php echo 'mp='.$mp;?>&action=upsave&page=<?php echo $page;?><?php echo $urlquery;?>" target="postpanel">
  <tr>
    <td height="24" align="center"><strong>ID</strong></td>
    <td align="center"><strong>所属组</strong></td>
    <td align="center"><strong>视频名称</strong></td>
    <td align="center"><strong>操作</strong></td>
	<td align="center"><strong>选择</strong></td>
  </tr>
<?php
if(checkint($s_video_class_id)){$sql2 .= " and A.video_class_id=$s_video_class_id ";}
if($s_videoname){$sql2 .= " and A.videoname like '%{$s_videoname}%' ";}
if($s_truename){$sql2 .= " and A.truename like '%{$s_truename}%' ";}

$query = "select A.*,B.classname from {$dbtablepre}video AS A left join {$dbtablepre}video_class AS B on A.video_class_id=B.video_class_id where 1 $sql2 order by A.video_id desc";
$num=$db->num_rows($db->query($query));
require_once(ROOT_PATH."classes/class.page.php");
$pagecls = new pagecls($num,15,$page,'?mp='.$mp.'&action=list'.$urlquery);
if($num<=0){echo("<tr><td height=\"30\" align=\"center\" colspan=\"10\" class=\"table_trbg02\">没有任何信息！</td></tr>");}
$query=$db->query("$query LIMIT {$pagecls->startrecord},{$pagecls->pagesize}");
while($rs = $db->fetch_array($query)) {
?>
  <tr>
    <td height="24" align="center"><?php echo $rs["video_id"];?></td>
    <td align="center"><?php echo $rs["classname"];?></td>
    <td align="center"><a href="?<?php echo 'mp='.$mp;?>&action=edit&video_id=<?php echo $rs["video_id"].$urlquery;?>" title="编辑信息" class="cboxframe"><?php echo $rs["videoname"];?></a></td>
    <td align="center"><a href="?<?php echo 'mp='.$mp;?>&action=edit&video_id=<?php echo $rs["video_id"].$urlquery;?>" title="编辑信息" class="cboxframe">编辑</a>
    <?php if(user_permission(array($mp=>'edit'))){?> <a href="?mp=user_permission_school&action=edit&video_id=<?php echo $rs['video_id'];?>" title="大学权限编辑" class="cboxframe">大学权限</a><?php }?></td>
	<td align="center"><input type="checkbox" name="selectid[]" value="<?php echo $rs["video_id"];?>"><input type="hidden" name="hideid[]" value="<?php echo $rs["video_id"];?>"></td>
  </tr>
<?php
}
?>
   <tr>
   		<td colspan="10">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="50%"><input type="button" name="button" value="新增信息" title="新增信息" alt="?<?php echo 'mp='.$mp;?>&action=add" class="cboxframe" /></td>
    <td align="right"><input type="hidden" name="operamode" value="">
		<input type="checkbox" name="chkall" value="on" onClick="checkall(this.form,'selectid[]')" />全选
        <input type="button" value="删除" onClick="javascript:operation('delete');" /></td>
  </tr>
</table>

      </td>
  </tr></form>
   <tr>
     <td height="24" colspan="8" align="center"><?php echo $pagecls->pageinfo;?></td>
   </tr>
</table>
<?php
}

if($action=="edit"){
	//读取数据库原有资料
	if(!checkint($video_id)){jsalert('script','alert("参数传递出错，请重试；");parent.closeFrame();');}
	$data=$db->fetch_first("select * from {$dbtablepre}video where video_id=".$video_id."");
	if(!$data){
		jsalert('script','alert("没有找到相关信息，请重试；");parent.closeFrame();');
	}

	if($act=="delimg"){
		@unlink($upconfig['savepath'].$data["videoimg"]);
		$db->query("update {$dbtablepre}video set videoimg='$videoimg' where video_id=".$data['video_id']."");
		$data["videoimg"]='';
		jsalert("url","?action=edit&video_id=$video_id&page=$page{$urlquery}");
	}

	if($act=="editsave"){
		if(!checkint($video_class_id)){$errmsg .= '@@请选择所属分类；';}
		if(!$videoname){$errmsg .= '@@请输入视频名称；';}
	
		if(!$errmsg){
			if($_FILES['videoimg']['name']){
				require(ROOT_PATH.'classes/class.upload.php');
				$f = new upload("videoimg", $upconfig['savepath'], $upconfig['addpath'],$upconfig['ext'], $upconfig['size']);
				if($f->error != 0){
					$errmsg.="@@".$f->errormsg()."；\\n";
				}else{
					$videoimg=$f->uploadedfiles[0]['addpath'].$f->uploadedfiles[0]['savename'];	
				}
			}
		}

		if(!$errmsg){
			if($videoimg != ""){
				@unlink($upconfig['savepath'].$data['videoimg']);
			}else{
				$videoimg=$data['videoimg'];
			}
			$db->query("update {$dbtablepre}video set video_class_id='$video_class_id', videoname='$videoname', videoimg='$videoimg', videourl='$videourl', content='$content' where video_id=$video_id");
			
			showTips('编辑保存成功；','?mp='. $mp .'&action=edit&video_id='.$video_id);
		}
		showErrTips($errmsg);
	}
?>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="tablebk">
<form name="Form1" action="?<?php echo 'mp='.$mp;?>&action=edit&act=editsave&video_id=<?php echo $video_id;?>" method="post" enctype="multipart/form-data" target="postpanel">
  <tr>
  	<th colspan="2"><h2><?php echo $pagetitle;?>编辑</h2></th>
  </tr>
  <tr>
    <td width="18%" align="right">所属分类：</td>
    <td><select name="video_class_id" id="video_class_id">
    <option value="">--请选择--</option>
    <?php
	$query=$db->query("SELECT * FROM {$dbtablepre}video_class ORDER BY sort_order ASC , video_class_id ASC");
	while($rs = $db->fetch_array($query)){
	?>
    	<option value="<?php echo $rs['video_class_id'];?>"<?php echo setselected($data['video_class_id'],$rs['video_class_id']);?>><?php echo $rs['classname']?></option>
    <?php
	}
	?>
    </select>
    </td>
  </tr>
  <tr>
    <td align="right">视频名称：</td>
    <td><input name="videoname" type="text" class="input" size="30" value="<?php echo $data['videoname'];?>"></td>
  </tr>
  <tr>
    <td align="right">视频图片：</td>
    <td><input name="videoimg" type="file" class="input" value="<?php echo $data['videoimg'];?>" size="30" style="height:22px; margin-right:50px;" />
    <?php
    if($data['videoimg']){
		echo '<span><a href="'.$upconfig['savepath'].$data['videoimg'].'" target="_blank"><img src="'.$upconfig['savepath'].$data['videoimg'].'" height="50" /></a></span>';
		echo ' <a href="?action=edit&act=delimg&id='.$id.'&page='.$page.$urlquery.'" target="postpanel">删除图片</a>';
	}
	?>    </td>
  </tr>
  <tr>
    <td align="right">视频地址：</td>
    <td><input name="videourl" type="text" class="input" size="80" value="<?php echo $data['videourl'];?>"></td>
  </tr>
  <tr>
    <td height="24" align="right"><strong>视频简介：</strong></td>
    <td><?php
		require '../plugins/editor/fckeditor/fckeditor.php';
		$oFCKeditor = new FCKeditor('content');
		$oFCKeditor->BasePath=EDITOR_BASEPATH;
		$oFCKeditor->Value=$data[content];
		$oFCKeditor->Create(); 
	?></td>
  </tr>
  <tr>
    <td height="40" colspan="2" align="center">
      <input type="submit" name="Submit" value="提 交"> &nbsp; 
      <input type="button" name="Submit" value="返回列表" onClick="parent.closeFrame();reloadmain();"></td>
  </tr>
  </form>
</table>
<?php
}
if($action=="upsave"){

	if(!$operamode){showErrTips('@@请选择要执行的操作方式');}
	if($operamode=="delete"){
		if(!$selectid){jsalert("请选择要删除的信息!","this");}
		for ($i = 0; $i < count($selectid); $i++) {
			$rs=$db->fetch_first("select videoimg from {$dbtablepre}video where video_id=".intval($selectid[$i])."");
			if($rs){
				@unlink($upconfig['savepath'].$rs["videoimg"]);
			}
			$db->query("delete from {$dbtablepre}video where video_id = ".intval($selectid[$i])."");
		}
		jsalert("所选信息删除成功；","?mp=". $mp ."&action=list&page=".$page.$urlquery);
	}
}
?>

<?php require('inc.bottom.php');?>
</div>
</body>
</html>