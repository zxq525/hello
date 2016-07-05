<?php
require 'inc.checkhead.php';

$path_incfile = ROOT_PATH.'includes/inc.article_class.php'; 
if(file_exists($path_incfile)){
	require($path_incfile);
}

if(!checkint($topclassid)){jsalert("参数传递出错，请重试；","back","window");}
$topclass=$db->fetch_first("select * from {$dbtablepre}article_class where id =".$topclassid."");
if(!$topclass){
	jsalert("没有找到相关信息，请重试；","back","this");
}

$urlquery = "&s_topic=". urlencode($s_topic) ."&s_keyword=". urlencode($s_keyword)."&topclassid=". urlencode($topclassid);
$upconfig = array('ext'=>'gif|jpg','size'=>1024*500,'savepath'=>$uppath['article'],'addpath'=>'');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 friendlinkal//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-friendlinkal.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>管理</title>
<link href="style/style.css" rel="stylesheet" type="text/css" />
<link href="style/colorbox.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/common.js"></script>
<script type="text/javascript" src="js/colorbox.js"></script>
<script type="text/javascript" src="js/selectdatetime.js"></script>
</head>
<body>
<div class="bodybox" id="bodybox">
<?php
if($action=="add"){
	if($act=="addsave"){
		if(!checkint($topclassid))$errmsg=$errmsg."@@请选择所属分类；\\n";
		if($topic==""){$errmsg=$errmsg."@@请输入信息标题；\\n";}
		if(!checkdatetime($joindate))$errmsg=$errmsg."@@请选择正确的添加时间；\\n";
		
		if(!checkint($ishide)){$ishide=0;}
		
		
		if(!$errmsg && $_FILES['infoimg']['name']){
			require(ROOT_PATH.'classes/class.upload.php');
			$f = new upload("infoimg", $upconfig['savepath'], $upconfig['addpath'],$upconfig['ext'], $upconfig['size']);
			if($f->error != 0){
				$errmsg=$errmsg."@@".$f->errormsg()."；\\n";
			}else{
				$infoimg=$f->uploadedfiles[0]['addpath'].$f->uploadedfiles[0]['savename'];	
			}
		}

		if(!$errmsg){
			$sort_order = getmaxnum("article");
			$joindate=strtotime($joindate);
			$hitnum=0;
			$db->query("insert into {$dbtablepre}article(classid,topic,infoimg,infointro,content,joindate,hitnum,sort_order)values($topclassid,'$topic','$infoimg','$infointro','$content','$joindate','$hitnum','$sort_order')");

			showTips("信息添加成功；",'?mp='. $mp .'&action=add&topclassid='. $topclassid);
		}
		showErrTips($errmsg);
	}
?>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="tablebk">
<form name="Form1" action="?<?php echo 'mp='.$mp;?>&action=add&act=addsave<?php echo $urlquery;?>" method="post" enctype="multipart/form-data" target="postpanel">
  <tr>
	<th colspan="2"><h2><?php echo $topclass['classname'];?> - 文章信息添加</h2></th>
  </tr>
  <tr>
    <td height="40" colspan="2" align="center"><input type="submit" name="Submit" value="提 交"> 
      &nbsp;
      <input type="button" name="Submit2" value="返回列表" onclick="parent.closeFrame();reloadmain();" /></td>
  </tr>
  <tr>
    <td width="18%" height="24" align="right"><strong><img src="images/icon01.gif" align="absmiddle" />所属分类：</strong></td>
    <td><?php echo $topclass['classname'];?></td>
  </tr>
  <tr>
    <td height="24" align="right"><strong><img src="images/icon01.gif" align="absmiddle" />信息标题：</strong></td>
    <td><input name="topic" type="text" class="input" size="70" value="<?php echo $val['topic'];?>"></td>
  </tr>
  <tr>
    <td height="24" align="right"><strong>信息图片：</strong></td>
    <td><input name="infoimg" type="file" class="input" value="<?php echo $val['infoimg'];?>" size="30" style="height:22px;" /></td>
  </tr>
  <tr>
    <td height="24" align="right"><strong>添加时间：</strong></td>
    <td><input name="joindate" type="text" class="input" size="30" value="<?php if(!checkint($val[joindate])){echo SYS_DATETIME;}else{echo date("Y-m-d H:i:s",$val[joindate]);}?>" readonly="true">  <img src="images/date.gif" width="24" height="24" align="absmiddle" onClick="CalendarWebControl.show(joindate,true,joindate.value);" style="cursor:pointer;"></td>
  </tr>
  <tr>
    <td height="24" align="right"><strong>信息简介：</strong></td>
    <td><textarea name="infointro" cols="80%" rows="6" class="textarea"><?php echo $val['infointro']?></textarea></td>
  </tr>
  <tr>
    <td height="24" align="right"><strong>信息内容：</strong></td>
    <td><?php 
		require '../plugins/editor/fckeditor/fckeditor.php';
		$oFCKeditor = new FCKeditor('content');
		$oFCKeditor->Height = 400;
		$oFCKeditor->BasePath=EDITOR_BASEPATH;
		$oFCKeditor->Value=$val['content'];
		$oFCKeditor->Create();
	?></td>
  </tr>
  <tr>
    <td height="40" colspan="2" align="center"><input type="submit" name="Submit" value="提 交"> 
      &nbsp;
      <input type="button" name="Submit2" value="返回列表" onclick="parent.closeFrame();reloadmain();" /></td>
  </tr>
  </form>
</table>
<?php
}
if($action=="list"){
?>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="tablebk">
  <tr>
	<th colspan="10"><h2><?php echo $topclass['classname'];?> - 文章信息列表</h2></th>
  </tr>
  <tr>
    <td colspan="10" class="table_trbg01"><table width="100%" border="0" cellspacing="0" cellpadding="0">
    <form name="Form2" method="get" action="?"><input type="hidden" name="action" value="list" />
	<input type="hidden" name="mp" value="<?php echo $mp;?>" />
	<input type="hidden" name="topclassid" value="<?php echo $topclassid;?>" />
      <tr>
        <td align="right">
		信息标题:
		<input name="s_topic" type="text" size="14" class="input" value="<?php echo $s_topic;?>" /> &nbsp; 
        模糊查询:<input name="s_keyword" type="text" size="14" class="input" value="<?php echo $s_keyword;?>" />
		<input type="submit" value="搜索" /></td>
      </tr></form>
    </table>	
	</td>
  </tr>
<form name="Form1" method="post" action="?mp=<?php echo $mp;?>&action=upsave&page=<?php echo $page;?><?php echo $urlquery;?>" target="postpanel">
  <tr>
    <td height="24" align="center"><strong>ID</strong></td>
    <td align="center"><strong>所属分类</strong></td>
    <td align="center"><strong>信息标题</strong></td>
	<td align="center"><strong>推荐</strong></td>
	<td align="center"><strong>发布时间</strong></td>
    <td align="center"><strong>信息排序</strong></td>
    <td align="center"><strong>操作</strong></td>
	<td align="center"><strong>选择</strong></td>
  </tr>
<?php
if($s_topic!=""){$sql2 .= " and A.topic like '%{$s_topic}%' ";}

if(checkint($topclassid)){
	$childidarr=$db->result_first("select childidarr from {$dbtablepre}article_class where id=$topclassid");
	if($childidarr){
		$sql2 .= " and (A.classid = ".intval($topclassid)." or A.classid in ($childidarr)) ";
	}else{
		$sql2 .= " and A.classid = ".intval($topclassid)." ";
	}
}
if(checkint($s_ismember)){$sql2 .= " and ismember = ".intval($s_ismember)." ";}
if($s_keyword!=""){$sql2 .= " and (A.topic like '%{$s_keyword}%' or A.content like '%{$s_keyword}%' or A.author like '%{$s_keyword}%' or A.source like '%{$s_keyword}%' ) ";}

$query = "select A.*,B.classname from {$dbtablepre}article as A left join {$dbtablepre}article_class as B on A.classid=B.id where 1 $sql2 order by A.sort_order desc, A.joindate desc,A.id desc";
$num=$db->num_rows($db->query($query));
require_once(ROOT_PATH."classes/class.page.php");
$pagecls = new pagecls($num,15,$page,'?mp='.$mp.'&action=list'.$urlquery);
if($num<=0){echo("<tr><td height=\"30\" align=\"center\" colspan=\"10\" class=\"table_trbg02\">没有任何信息！</td></tr>");}
$query=$db->query("$query LIMIT {$pagecls->startrecord},{$pagecls->pagesize}");
while($rs = $db->fetch_array($query)) {
?>
  <tr>
    <td height="24" align="center"><?php echo $rs["id"];?></td>
    <td height="24" align="center"><?php echo $rs["classname"];?></td>
    <td align="left">&nbsp;<a href="?<?php echo 'mp='.$mp;?>&action=edit&id=<?php echo $rs["id"].$urlquery;?>&keepThis=true&TB_iframe=true" title="编辑信息" class="cboxframe"><?php echo getcutstr($rs["topic"],40);?></a></td>
	<td align="center" class="table_trbg02"><?php if($rs["isgood"]==0){echo "否";}else{echo "<span class='blue'>是</span>";};?></td>
	<td align="center"><?php echo date("Y-m-d H:i",$rs["joindate"]);?></td>
    <td align="center"><input type="text" name="sort_order[]" size="8" class="input" value="<?php echo $rs["sort_order"];?>"></td>
    <td align="center"><a href="?<?php echo 'mp='.$mp;?>&action=edit&id=<?php echo $rs["id"].$urlquery;?>&keepThis=true&TB_iframe=true" title="编辑信息" class="cboxframe">编辑</a></td>
	<td align="center"><input type="checkbox" name="selectid[]" value="<?php echo $rs["id"];?>"><input type="hidden" name="hideid[]" value="<?php echo $rs["id"];?>"></td>
  </tr>
<?php
}
?>
   <tr>
   		<td height="24" colspan="10">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="50%"><input type="button" name="button" value="新增信息" title="新增信息" alt="?mp=<?php echo $mp;?>&action=add&page=<?php echo $page.$urlquery;?>&keepThis=true&TB_iframe=true" class="cboxframe" /></td>
    <td align="right"><input type="hidden" name="operamode" value="">
		<input type="checkbox" name="chkall" value="on" onClick="checkall(this.form,'selectid[]')" />全选
		<input type="button" value="推荐" onClick="javascript:operation('isgood');" />
        <input type="button" value="取消推荐" onClick="javascript:operation('un_isgood');" />
        <input type="button" value="更新排序" onClick="javascript:operation('update');" />
        <input type="button" value="删除" onClick="javascript:operation('delete');" /></td>
  </tr>
</table>

      </td>
    </tr>
  </form>
   <tr>
     <td height="24" colspan="10" align="center"><?php echo $pagecls->pageinfo;?></td>
  </tr>
</table>
<?php
}

if($action=="edit"){
	//读取数据库原有资料
	if(!checkint($id)){jsalert("参数传递出错，请重试；","back","window");}
	$val=$db->fetch_first("select * from {$dbtablepre}article where id =".$id."");
	if(!$val){
		jsalert("没有找到相关信息，请重试；","back","window");
	}

	if($act=="delimg"){
		@unlink($upconfig['savepath'].$val["infoimg"]);
		$db->query("update {$dbtablepre}article set infoimg='$infoimg' where id=".$val['id']."");
		$val["infoimg"]='';
		jsalert("url",'?mp='. $mp .'&action=edit&id='. $id .'');
	}

	if($act=="editsave"){
		if(!checkint($topclassid))$errmsg=$errmsg."@@请选择所属分类；\\n";
		if($topic==""){$errmsg=$errmsg."@@请输入信息标题；\\n";}
		if(!checkdatetime($joindate))$errmsg=$errmsg."@@请选择正确的添加时间；\\n";
		
		if(!checkint($ishide)){$ishide=0;}
		
		
		if(!$errmsg){
			if($_FILES['infoimg']['name']){
				require(ROOT_PATH.'classes/class.upload.php');
				$f = new upload("infoimg", $upconfig['savepath'], $upconfig['addpath'],$upconfig['ext'], $upconfig['size']);
				if($f->error != 0){
					$errmsg=$errmsg."@@".$f->errormsg()."；\\n";
				}else{
					$infoimg=$f->uploadedfiles[0]['addpath'].$f->uploadedfiles[0]['savename'];	
				}
			}
		}
		
		if(!$errmsg){
			if($infoimg){
				@unlink($upconfig['savepath'].$val['infoimg']);
			}else{
				$infoimg=$val['infoimg'];
			}
			$joindate=strtotime($joindate);
			$db->query("update {$dbtablepre}article set classid=$topclassid,topic='$topic',infoimg='$infoimg',infointro='$infointro',content='$content',joindate='$joindate' where id=".$val['id']."");
		
			showTips("编辑保存成功；",'?mp='. $mp .'&action=edit&id='. $id . $urlquery .'');
		}
		showErrTips($errmsg);
	}
?>

<script language="javascript" src="js/selectdatetime.js"></script>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="tablebk">
<form name="Form1" action="?mp=<?php echo $mp;?>&action=edit&act=editsave&id=<?php echo $id;?>&page=<?php echo $page;?><?php echo $urlquery;?>" method="post" enctype="multipart/form-data" target="postpanel">
  <tr>
	<th colspan="2"><h2><?php echo $topclass['classname'];?> - 文章信息编辑</h2></th>
  </tr>
  <tr>
    <td height="40" colspan="2" align="center"><input type="submit" name="Submit" value="保 存"> 
      &nbsp; 
      <input type="button" name="Submit2" value="返回列表" onclick="parent.closeFrame();reloadmain();" /></td>
  </tr>
  <tr>
    <td width="18%" height="24" align="right"><strong><img src="images/icon01.gif" align="absmiddle" />所属分类：</strong></td>
    <td><?php echo $topclass['classname'];?></td>
  </tr>
  <tr>
    <td height="24" align="right"><strong><img src="images/icon01.gif" align="absmiddle" />信息标题：</strong></td>
    <td><input name="topic" type="text" class="input" size="70" value="<?php echo $val['topic'];?>"></td>
  </tr>
  <tr>
    <td height="24" align="right"><strong>信息图片：</strong></td>
    <td>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="50%" class="nospacing"><input name="infoimg" type="file" class="inputfile" size="30" /></td>
    <td>
<?php
    if($val['infoimg']){
		echo '<span><a href="'.$upconfig['savepath'].$val['infoimg'].'" target="_blank"><img src="'.$upconfig['savepath'].$val['infoimg'].'" height="50" /></a></span>';
		echo ' <a href="?action=edit&act=delimg&id='.$id.'&page='.$page.$urlquery.'" target="postpanel">删除图片</a>';
	}
?>
	</td>
  </tr>
</table>
    </td>
  </tr>
  <tr>
    <td height="24" align="right"><strong>添加时间：</strong></td>
    <td><input name="joindate" type="text" class="input" size="30" value="<?php if(!checkint($val[joindate])){echo $sys_datetime;}else{echo date("Y-m-d H:i:s",$val[joindate]);}?>" readonly="true">  <img src="images/date.gif" width="24" height="24" align="absmiddle" onClick="CalendarWebControl.show(joindate,true,joindate.value);" style="cursor:pointer;"></td>
  </tr>
  <tr>
    <td height="24" align="right"><strong>信息简介：</strong></td>
    <td><textarea name="infointro" cols="80%" rows="6" class="textarea"><?php echo $val['infointro']?></textarea></td>
  </tr>
  <tr>
    <td height="24" align="right"><strong>信息内容：</strong></td>
    <td><?php 
		require '../plugins/editor/fckeditor/fckeditor.php';
		$oFCKeditor = new FCKeditor('content');
		$oFCKeditor->Height = 400;
		$oFCKeditor->BasePath=EDITOR_BASEPATH;
		$oFCKeditor->Value=$val['content'];
		$oFCKeditor->Create(); 
	?></td>
  </tr>
  <tr>
    <td height="40" colspan="2" align="center"><input type="submit" name="Submit" value="保 存"> 
      &nbsp; 
      <input type="button" name="Submit2" value="返回列表" onclick="parent.closeFrame();reloadmain();" /></td>
  </tr>
  </form>
</table>
<?php
}
if($action=="upsave"){
	if(!$operamode){showErrTips("请选择要执行的操作方式!");}
	
	if($operamode=="isgood"){
		for ($i = 0; $i < count($selectid); $i++) {
			$db->query("update {$dbtablepre}article Set isgood=1 where id=".intval($selectid[$i])."");
		}
		showTips("信息更新成功；","?mp=". $mp ."&action=list&page=".$page.$urlquery);
	}
	
	if($operamode=="un_isgood"){
		for ($i = 0; $i < count($selectid); $i++) {
			$db->query("update {$dbtablepre}article Set isgood=0 where id=".intval($selectid[$i])."");
		}
		showTips("信息更新成功；","?mp=". $mp ."&action=list&page=".$page.$urlquery);
	}

	if($operamode=="update"){
		for ($i = 0; $i < count($hideid); $i++) {
			$db->query("update {$dbtablepre}article Set sort_order=".intval($sort_order[$i])." where id=".intval($hideid[$i])."");
		}
		showTips("信息更新成功；","?mp=". $mp ."&action=list&page=".$page.$urlquery);
	}

	if($operamode=="delete"){
		if(!$selectid){showErrTips("@@请选择要删除的信息!");}
		for ($i = 0; $i < count($selectid); $i++) {
			$rs=$db->fetch_first("select infoimg from {$dbtablepre}article where id=".intval($selectid[$i])."");
			if($rs){
				@unlink($upconfig['savepath'].$rs["infoimg"]);
			}
			$db->query("delete from {$dbtablepre}article where id = ".intval($selectid[$i])."");
		}
		showTips("所选信息删除成功；","?mp=". $mp ."&action=list&page=".$page.$urlquery);
	}
}

function select_catalog($currentid="0",$cid="0",$level = 1){
	global $_catalog_array,$menuid;
	if(isset($_catalog_array[$cid])){
		foreach($_catalog_array[$cid] AS $root=>$cata){
			if($cata['menuid'] == $menuid){
			$t=$level=='1'?"":"├ ";
			$c=$level=='1'?"class_s1":"class_s2";
			$selected=($currentid==$cata['id'])?"selected='selected'":"";
			if(empty($cata['url'])){
					$_option.="<option value='{$cata['id']}' class='$c' $selected>".str_repeat("│　", $level-1).$t.$cata['classname']."</option>".select_catalog($currentid,$cata['id'],$level+1);
			}
			}
		}
	}
	unset($_catalog_array);
	return $_option;
}
?>
<?php require('inc.bottom.php');?>
</div>
</body>
</html>