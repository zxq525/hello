<?php
require 'inc.checkhead.php';
$pagetitle = "管理分类";
$urlquery = "&s_classname=". urlencode($s_classname) ."&s_keyword=". urlencode($s_keyword);
$upconfig = array('ext'=>'gif|jpg','size'=>1024*500,'savepath'=>$uppath['video_image'],'addpath'=>'');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 friendlinkal//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-friendlinkal.dtd">
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
		
		if(!$classname){$errmsg .= '@@请输入分类名称；';}
		if(!checkint($sort_order)){$errmsg .= '@@请正确输入信息排序格式；';}

		if(!$errmsg && $_FILES['classimg']['name']){
			require(ROOT_PATH.'classes/class.upload.php');
			$f = new upload("classimg", $upconfig['savepath'], $upconfig['addpath'],$upconfig['ext'], $upconfig['size']);
			if($f->error != 0){
				$errmsg.="@@".$f->errormsg()."；\\n";
			}else{
				$classimg=$f->uploadedfiles[0]['addpath'].$f->uploadedfiles[0]['savename'];	
			}
		}

		if(!$errmsg){
			$db->query("insert into {$dbtablepre}video_class(`classname`,classimg,sort_order)values('$classname','$classimg','$sort_order')");

			showTips('信息添加成功','?mp='. $mp .'&action=add&s_classid='.$s_classid);
		}
		showErrTips($errmsg);
	}
?>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="tablebk">
<form name="Form1" action="?<?php echo 'mp='.$mp;?>&action=add&act=addsave<?php echo $urlquery;?>" method="post" enctype="multipart/form-data" target="postpanel">
  <tr>
    <td width="40%" align="right">分类名称：</td>
    <td><input name="classname" type="text" class="input" size="40" value="<?php echo $val['classname'];?>"></td>
  </tr>
  <tr>
    <td height="24" align="right">分类图片：</td>
    <td><input name="classimg" type="file" class="input" value="<?php echo $val['classimg'];?>" size="30" style="height:22px;" /></td>
  </tr>
  <tr>
    <td height="24" align="right">信息排序：</td>
    <td><input name="sort_order" type="text" class="input" size="10" value="<?php if($sort_order==""){echo(getmaxnum("video_class"));}else{echo($sort_order);}?>">
数字，越小越前面</td>
  </tr>
  <tr>
    <td colspan="2" align="center"><input type="submit" name="Submit" value="提 交"> 
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
  	<th colspan="10"><h2><?php echo $pagetitle;?>列表</h2></th>
  </tr>
  <tr>
    <td colspan="10" class="table_trbg01"><table width="100%" border="0" cellspacing="0" cellpadding="0">
    <form name="Form2" method="get" action="?">
	<input type="hidden" name="action" value="list" /><input type="hidden" name="mp" value="<?php echo $mp;?>" />
	<input type="hidden" name="s_classid" value="<?php echo $s_classid;?>" />
      <tr>
        <td align="right">
		分类名称:
		  <input name="s_classname" type="text" size="14" class="input" value="<?php echo $s_classname;?>" /> &nbsp; 
        模糊查询:<input name="s_keyword" type="text" size="14" class="input" value="<?php echo $s_keyword;?>" />
		<input type="submit" value="搜索" /> <input type="button" value="重置" onclick="window.location='?mp=<?php echo $mp;?>&action=list';" /></td>
      </tr></form>
    </table>	
	</td>
  </tr>
<form name="Form1" method="post" action="?<?php echo 'mp='.$mp;?>&action=upsave&page=<?php echo $page;?><?php echo $urlquery;?>" target="postpanel">
  <tr>
    <td align="center"><strong>ID</strong></td>
    <td align="center"><strong>分类名称</strong></td>
    <td align="center"><strong>信息排序</strong></td>
 	<td align="center"><strong>操作</strong></td>
	<td align="center"><strong>选择</strong></td>
  </tr>
<?php
if($s_classname!=""){$sql2 .= " and classname like '%{$s_classname}%' ";}

if($s_keyword!=""){$sql2 .= " and (classname like '%{$s_keyword}%') ";}

$query = "select * from {$dbtablepre}video_class  where 1 $sql2 order by sort_order asc,video_class_id asc";
$num=$db->num_rows($db->query($query));
require_once(ROOT_PATH."classes/class.page.php");
$pagecls = new pagecls($num,10,$page,'?mp='.$mp.'&action=list'.$urlquery);
if($num<=0){echo("<tr><td height=\"30\" align=\"center\" colspan=\"10\" class=\"table_trbg02\">没有任何信息！</td></tr>");}
$query=$db->query("$query LIMIT {$pagecls->startrecord},{$pagecls->pagesize}");
while($rs = $db->fetch_array($query)) {
?>
  <tr>
  	<td align="center"><?php echo $rs["video_class_id"];?></td>
    <td align="left">&nbsp;<a href="?<?php echo 'mp='.$mp;?>&action=edit&video_class_id=<?php echo $rs['video_class_id'];?>" title="编辑信息" class="cboxframe"><?php echo getcutstr($rs['classname'],40);?></a></td>
    <td align="center"><input type="text" name="sort_order[]" size="8" class="input" value="<?php echo $rs["sort_order"];?>"></td>
	<td align="center"><a href="?<?php echo 'mp='.$mp;?>&action=edit&video_class_id=<?php echo $rs['video_class_id'];?>" title="编辑信息" class="cboxframe">编辑</a></td>
	<td align="center"><input type="checkbox" name="selectid[]" value="<?php echo $rs["video_class_id"];?>"><input type="hidden" name="hideid[]" value="<?php echo $rs["video_class_id"];?>"></td>
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
        <input type="button" value="更新" onclick="javascript:operation('update');" />
        <input type="button" value="删除" onClick="javascript:operation('delete');" /></td>
  </tr>
</table>
      </td>
    </tr>
  </form>
   <tr>
     <td colspan="9" align="center"><?php echo $pagecls->pageinfo;?></td>
  </tr>
</table>
<?php
}

if($action=="edit"){
	//读取数据库原有资料
	if(!checkint($video_class_id)){jsalert('script','alert("参数传递出错，请重试；");parent.closeFrame();');}
	$val=$db->fetch_first("select * from {$dbtablepre}video_class where video_class_id =".$video_class_id."");
	if(!$val){
		jsalert('script','alert("没有找到相关信息，请重试；");parent.closeFrame();');
	}

	if($act=="delimg"){
		@unlink($upconfig['savepath'].$val["classimg"]);
		$db->query("update {$dbtablepre}video_class set classimg='$classimg' where video_class_id=".$val['video_class_id']."");
		$val["classimg"]='';
		jsalert("url","?action=edit&video_class_id=$video_class_id&page=$page{$urlquery}");
	}

	if($act=="editsave"){

		if(!$classname){$errmsg .= '@@请输入分类名称；';}
		if(!checkint($sort_order)){$errmsg .= '@@请正确输入信息排序格式；';}
		
		if(!$errmsg){
			if($_FILES['classimg']['name']){
				require(ROOT_PATH.'classes/class.upload.php');
				$f = new upload("classimg", $upconfig['savepath'], $upconfig['addpath'],$upconfig['ext'], $upconfig['size']);
				if($f->error != 0){
					$errmsg.="@@".$f->errormsg()."；\\n";
				}else{
					$classimg=$f->uploadedfiles[0]['addpath'].$f->uploadedfiles[0]['savename'];	
				}
			}
		}

		if(!$errmsg){
			if($classimg != ""){
				@unlink($upconfig['savepath'].$val['classimg']);
			}else{
				$classimg=$val['classimg'];
			}

			$db->query("update {$dbtablepre}video_class set classname='$classname',classimg='$classimg',sort_order='$sort_order' where video_class_id=".$val['video_class_id']."");
			showTips('编辑保存成功；','?mp='. $mp .'&action=edit&video_class_id='.$video_class_id);
		}
		showErrTips($errmsg);
	}
?>

<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="tablebk">
<form name="Form1" action="?<?php echo 'mp='.$mp;?>&action=edit&act=editsave&video_class_id=<?php echo $video_class_id;?>" method="post" enctype="multipart/form-data" target="postpanel">
  <tr>
    <td width="40%" align="right"><img src="images/icon01.gif" align="absmiddle" />分类名称：</td>
    <td><input name="classname" type="text" class="input" size="40" value="<?php echo $val['classname'];?>"></td>
  </tr>
  <tr>
    <td height="24" align="right">分类图片：</td>
    <td><input name="classimg" type="file" class="input" value="<?php echo $val['classimg'];?>" size="30" style="height:22px; margin-right:50px;" />
    <?php
    if($val['classimg']){
		echo '<span><a href="'.$upconfig['savepath'].$val['classimg'].'" target="_blank"><img src="'.$upconfig['savepath'].$val['classimg'].'" height="50" /></a></span>';
		echo ' <a href="?action=edit&act=delimg&id='.$id.'&page='.$page.$urlquery.'" target="postpanel">删除图片</a>';
	}
	?>    </td>
  </tr>
  <tr>
    <td height="24" align="right">信息排序：</td>
    <td><input name="sort_order" type="text" class="input" size="10" value="<?php if($val['sort_order']==""){echo(getmaxnum("video_class"));}else{echo($val['sort_order']);}?>">
数字，越小越前面</td>
  </tr>
  <tr>
    <td colspan="2" align="center"><input type="submit" name="Submit" value="提 交"> &nbsp; 
      <input type="button" name="Submit" value="返回列表" onClick="parent.closeFrame();reloadmain();"></td>
  </tr>
  </form>
</table>
<?php
}
if($action=="upsave"){
	if(!$operamode){showErrTips('@@请选择要执行的操作方式');}
	if($operamode=="update"){
		for ($i = 0; $i < count($hideid); $i++) {
			$db->query("update {$dbtablepre}video_class Set sort_order={$sort_order[$i]} where video_class_id={$hideid[$i]}");
		}
		showTips('信息更新成功；','?mp='. $mp .'&action=list&page='.$page.$urlquery);
	}
	if($operamode=="delete"){
		if(!$selectid){jsalert("请选择要删除的信息!","this");}
		for ($i = 0; $i < count($selectid); $i++) {
			$rs=$db->fetch_first("select classimg from {$dbtablepre}video_class where video_class_id=".intval($selectid[$i])."");
			if($rs){
				@unlink($upconfig['savepath'].$rs["classimg"]);
			}
			$db->query("delete from {$dbtablepre}video_class where video_class_id = ".intval($selectid[$i])."");
		}
		jsalert("所选信息删除成功；","?mp=". $mp ."&action=list&page=".$page.$urlquery);
	}
}
?>
<?php require('inc.bottom.php');?>
</div>
</body>
</html>