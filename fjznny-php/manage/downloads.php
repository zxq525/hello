<?php
require 'inc.checkhead.php';

$path_incfile = ROOT_PATH.'includes/inc.downloads_class.php'; 
if(file_exists($path_incfile)){
	require($path_incfile);
}

$urlquery = "&s_soft_name=". urlencode($s_soft_name) ."&s_keywords=". urlencode($s_keywords)."&s_isgood=". urlencode($s_isgood);
$upconfig = array('ext'=>'gif|jpg','size'=>1024*500,'savepath'=>$uppath['downloads_image'],'addpath'=>'');
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
		if($soft_name==""){$errmsg.="@@请输入软件名称；\\n";}
		if(!checkdatetime($joindate))$errmsg.="@@请选择正确的添加时间；\\n";
		
		if(!checkint($isgood)){$isgood=0;}
		
		
		if(!$errmsg && $_FILES['soft_img']['name']){
			require(ROOT_PATH.'classes/class.upload.php');
			$f = new upload("soft_img", $upconfig['savepath'], $upconfig['addpath'],$upconfig['ext'], $upconfig['size']);
			if($f->error != 0){
				$errmsg.="@@".$f->errormsg()."；\\n";
			}else{
				$soft_img=$f->uploadedfiles[0]['addpath'].$f->uploadedfiles[0]['savename'];	
			}
		}

		if(!$errmsg){
			$joindate=strtotime($joindate);
			$sort_order=999;
			$db->query("insert into {$dbtablepre}downloads(soft_name,soft_shiyong,soft_download,soft_img,content,isgood,joindate,sort_order)values('$soft_name','$soft_shiyong','$soft_download','$soft_img','$content','$isgood','$joindate','$sort_order')");
			showTips('信息添加成功；','?mp='. $mp .'&action=add&s_classid='. $classid);
		}
		showErrTips($errmsg);
	}
?>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="tablebk">
<form name="Form1" action="?<?php echo 'mp='.$mp;?>&action=add&act=addsave<?php echo $urlquery;?>" method="post" enctype="multipart/form-data" target="postpanel">
  <tr>
	<th colspan="2"><h2>下载信息添加</h2></th>
  </tr>
  <tr>
    <td height="40" colspan="2" align="center"><input type="submit" name="Submit" value="提 交"> 
      &nbsp;
      <input type="button" name="Submit2" value="返回列表" onclick="parent.closeFrame();reloadmain();" /></td>
  </tr>
  <tr>
    <td width="18%" height="24" align="right"><strong><img src="images/icon01.gif" align="absmiddle" />软件名称：</strong></td>
    <td><input name="soft_name" type="text" class="input" size="70" value="<?php echo $val['soft_name'];?>"></td>
  </tr>
  <tr>
    <td height="24" align="right"><strong>软件适用：</strong></td>
    <td><input name="soft_shiyong" type="text" class="input" size="30" value="<?php echo $val['soft_shiyong'];?>"></td>
  </tr>
  <tr>
    <td height="24" align="right"><strong>下载地址：</strong></td>
    <td><input name="soft_download" type="text" class="input" size="80" value="<?php echo $val['soft_download'];?>"></td>
  </tr>
  <tr>
    <td height="24" align="right"><strong>信息图片：</strong></td>
    <td><input name="soft_img" type="file" class="input" value="<?php echo $val['soft_img'];?>" size="30" style="height:22px;" /></td>
  </tr>
  <tr>
    <td height="24" align="right"><strong>信息参数：</strong></td>
    <td><input type="checkbox" name="isgood" value="1"<?php echo setchecked2($isgood,1);?>>推荐 &nbsp;
    </td>
  </tr>
  <tr>
    <td height="24" align="right"><strong>添加时间：</strong></td>
    <td><input name="joindate" type="text" class="input" size="30" value="<?php if(!checkint($val[joindate])){echo SYS_DATETIME;}else{echo date("Y-m-d H:i:s",$val[joindate]);}?>" readonly="true">  <img src="images/date.gif" width="24" height="24" align="absmiddle" onClick="CalendarWebControl.show(joindate,true,joindate.value);" style="cursor:pointer;"></td>
  </tr>
  <tr>
    <td height="24" align="right"><strong>信息内容：</strong></td>
    <td><?php
		require '../plugins/editor/fckeditor/fckeditor.php';
		$oFCKeditor = new FCKeditor('content');
		$oFCKeditor->BasePath=EDITOR_BASEPATH;
		$oFCKeditor->Value=$val[content];
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
	<th colspan="10"><h2>下载信息列表</h2></th>
  </tr>
  <tr>
    <td colspan="10" class="table_trbg01">
	<form name="Form2" method="get" action="?">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" id="searchdiv">
      <tr>
        <td align="right">
        <input type="hidden" name="action" value="list" /><input type="hidden" name="mp" value="<?php echo $mp;?>" />

  <select name="s_isgood">
          <option value="">所有信息</option>
		  <option value="1"<?php echo setselected($s_isgood,1);?>>推荐</option>
		  <option value="0"<?php echo setselected($s_isgood,0);?>>非推荐</option>
        </select>
		软件名称:
		<input name="s_soft_name" type="text" size="14" class="input" value="<?php echo $s_soft_name;?>" /> &nbsp; 
        模糊查询:<input name="s_keywords" type="text" size="14" class="input" value="<?php echo $s_keywords;?>" />
		<input type="submit" value="搜索" /> <input type="button" value="重置" onclick="window.location='?mp=<?php echo $mp;?>&action=list';" /></td>
      </tr>
    </table></form>
	</td>
  </tr>
<form name="Form1" method="post" action="?mp=<?php echo $mp;?>&action=upsave&page=<?php echo $page;?><?php echo $urlquery;?>" target="postpanel">
  <tr>
    <td height="24" align="center"><strong>ID</strong></td>
    <td align="center"><strong>软件名称</strong></td>
 	<td align="center"><strong>推荐</strong></td>
	<td align="center"><strong>发布时间</strong></td>
	<td align="center"><strong>信息排序</strong></td>
    <td align="center"><strong>操作</strong></td>
	<td align="center"><strong>选择</strong></td>
  </tr>
<?php
if($s_soft_name!=""){$sql2 .= " and soft_name like '%{$s_soft_name}%' ";}

if(checkint($s_isgood)){$sql2 .= " and isgood = ".intval($s_isgood)." ";}
if($s_soft_shiyong!=""){$sql2 .= " and soft_shiyong like '%{$s_keywords}%' ";}

$query = "select * from {$dbtablepre}downloads where 1 $sql2 order by sort_order desc, joindate desc,id desc";
$num=$db->num_rows($db->query($query));
require_once(ROOT_PATH."classes/class.page.php");
$pagecls = new pagecls($num,10,$page,"?action=list{$urlquery}");
if($num<=0){echo("<tr><td height=\"30\" align=\"center\" colspan=\"10\" class=\"table_trbg02\">没有任何信息！</td></tr>");}
$query=$db->query("$query LIMIT {$pagecls->startrecord},{$pagecls->pagesize}");
while($rs = $db->fetch_array($query)) {
?>
  <tr>
    <td height="24" align="center"><?php echo $rs["id"];?></td>
    <td align="left">&nbsp;<a href="?mp=<?php echo $mp;?>&action=edit&id=<?php echo $rs["id"].$urlquery;?>" title="编辑信息" class="cboxframe"><?php echo getcutstr($rs["soft_name"],40);?></a></td>
	<td align="center" class="table_trbg02"><?php if($rs["isgood"]==0){echo "否";}else{echo "<span class='blue'>是</span>";};?></td>
	<td align="center"><?php echo date("Y-m-d H:i",$rs["joindate"]);?></td>
	<td align="center"><input type="text" name="sort_order[]" size="8" class="input" value="<?php echo $rs["sort_order"];?>"></td>
    <td align="center"><a href="?mp=<?php echo $mp;?>&action=edit&id=<?php echo $rs["id"].$urlquery;?>" title="编辑信息" class="cboxframe">编辑</a></td>
	<td align="center"><input type="checkbox" name="selectid[]" value="<?php echo $rs["id"];?>"><input type="hidden" name="hideid[]" value="<?php echo $rs["id"];?>"></td>
  </tr>
<?php
}
?>
   <tr>
   		<td height="24" colspan="10">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="50%"><input type="button" name="button" value="新增信息" title="新增信息" alt="?mp=<?php echo $mp;?>&action=add&page=<?php echo $page.$urlquery;?>" class="cboxframe" /></td>
    <td align="right"><input type="hidden" name="operamode" value="">
		<input type="checkbox" name="chkall" value="on" onClick="checkall(this.form,'selectid[]')" />全选
		<input type="button" value="推荐" onClick="javascript:operation('isgood');" />
        <input type="button" value="取消推荐" onClick="javascript:operation('un_isgood');" />
        <input type="button" value="更新" onclick="javascript:operation('update');" />
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
	$val=$db->fetch_first("select * from {$dbtablepre}downloads where id =".$id."");
	if(!$val){
		jsalert("没有找到相关信息，请重试；","back","window");
	}

	if($act=="delimg"){
		@unlink($upconfig['savepath'].$val["soft_img"]);
		$db->query("update {$dbtablepre}downloads set soft_img='$soft_img' where id=".$val['id']."");
		$val["soft_img"]='';
		jsalert("url","?action=edit&id=$id&page=$page{$urlquery}");
	}

	if($act=="editsave"){
		if($soft_name==""){$errmsg.="@@请输入软件名称；\\n";}
		if(!checkdatetime($joindate))$errmsg.="@@请选择正确的添加时间；\\n";
		
		if(!checkint($isgood)){$isgood=0;}
		
		
		if(!$errmsg){
			if($_FILES['soft_img']['name']){
				require(ROOT_PATH.'classes/class.upload.php');
				$f = new upload("soft_img", $upconfig['savepath'], $upconfig['addpath'],$upconfig['ext'], $upconfig['size']);
				if($f->error != 0){
					$errmsg.="@@".$f->errormsg()."；\\n";
				}else{
					$soft_img=$f->uploadedfiles[0]['addpath'].$f->uploadedfiles[0]['savename'];	
				}
			}
		}
		
		if(!$errmsg){
			if($soft_img != ""){
				@unlink($upconfig['savepath'].$val['soft_img']);
			}else{
				$soft_img=$val['soft_img'];
			}
			$joindate=strtotime($joindate);
			$db->query("update {$dbtablepre}downloads set soft_name='$soft_name',soft_shiyong='$soft_shiyong',soft_download='$soft_download',soft_img='$soft_img',content='$content',isgood=$isgood,joindate='$joindate' where id=".$val['id']."");
			showTips('编辑保存成功；','?mp='. $mp .'&action=edit&id='. $id . $urlquery .'');
		}
		showErrTips($errmsg);
	}
?>

<script language="javascript" src="js/selectdatetime.js"></script>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="tablebk">
<form name="Form1" action="?mp=<?php echo $mp;?>&action=edit&act=editsave&id=<?php echo $id;?>&page=<?php echo $page;?><?php echo $urlquery;?>" method="post" enctype="multipart/form-data" target="postpanel">
  <tr>
	<th colspan="2"><h2>下载信息编辑</h2></th>
  </tr>
  <tr>
    <td height="40" colspan="2" align="center"><input type="submit" name="Submit" value="保 存"> 
      &nbsp; 
      <input type="button" name="Submit" value="返回列表" onClick="parent.closeFrame();reloadmain();"></td>
  </tr>
  <tr>
    <td width="18%" height="24" align="right"><strong><img src="images/icon01.gif" align="absmiddle" />软件名称：</strong></td>
    <td><input name="soft_name" type="text" class="input" size="70" value="<?php echo $val['soft_name'];?>"></td>
  </tr>
  <tr>
    <td height="24" align="right"><strong>软件适用：</strong></td>
    <td><input name="soft_shiyong" type="text" class="input" size="30" value="<?php echo $val['soft_shiyong'];?>"></td>
  </tr>
  <tr>
    <td height="24" align="right"><strong>下载地址：</strong></td>
    <td><input name="soft_download" type="text" class="input" size="80" value="<?php echo $val['soft_download'];?>"></td>
  </tr>
  <tr>
    <td height="24" align="right"><strong>信息图片：</strong></td>
    <td><input name="soft_img" type="file" class="input" value="<?php echo $val['soft_img'];?>" size="30" style="height:22px; margin-right:50px;" />
    <?php
    if($val['soft_img']){
		echo '<span><a href="'.$upconfig['savepath'].$val['soft_img'].'" target="_blank"><img src="'.$upconfig['savepath'].$val['soft_img'].'" height="50" /></a></span>';
		echo ' <a href="?action=edit&act=delimg&id='.$id.'&page='.$page.$urlquery.'" target="postpanel">删除图片</a>';
	}
	?>    </td>
  </tr>
  <tr>
    <td height="24" align="right"><strong>信息参数：</strong></td>
    <td><input type="checkbox" name="isgood" value="1"<?php echo setchecked2($val['isgood'],1);?>>推荐 &nbsp;
    </td>
  </tr>
  <tr>
    <td height="24" align="right"><strong>添加时间：</strong></td>
    <td><input name="joindate" type="text" class="input" size="30" value="<?php if(!checkint($val[joindate])){echo $sys_datetime;}else{echo date("Y-m-d H:i:s",$val[joindate]);}?>" readonly="true">  <img src="images/date.gif" width="24" height="24" align="absmiddle" onClick="CalendarWebControl.show(joindate,true,joindate.value);" style="cursor:pointer;"></td>
  </tr>
  <tr>
    <td height="24" align="right"><strong>信息内容：</strong></td>
    <td><?php 
		require '../plugins/editor/fckeditor/fckeditor.php';
		$oFCKeditor = new FCKeditor('content');
		$oFCKeditor->BasePath=EDITOR_BASEPATH;
		$oFCKeditor->Value=$val['content'];
		$oFCKeditor->Create(); 
	?></td>
  </tr>
  <tr>
    <td height="40" colspan="2" align="center"><input type="submit" name="Submit" value="保 存"> 
      &nbsp; 
      <input type="button" name="Submit" value="返回列表" onClick="parent.closeFrame();reloadmain();"></td>
  </tr>
  </form>
</table>
<?php
}
if($action=="upsave"){
	if(!$operamode){jsalert("请选择要执行的操作方式!","this");}

	if($operamode=="update"){
		for ($i = 0; $i < count($hideid); $i++) {
			$db->query("update {$dbtablepre}downloads Set sort_order={$sort_order[$i]} where id={$hideid[$i]}");
		}
		showTips('信息更新成功；','?mp='. $mp .'&action=list&page='.$page.$urlquery);
	}

	if($operamode=="isgood"){
		for ($i = 0; $i < count($selectid); $i++) {
			$db->query("update {$dbtablepre}downloads Set isgood=1 where id=".intval($selectid[$i])."");
		}
		jsalert("信息更新成功；","?mp=". $mp ."&action=list&page=".$page.$urlquery);
	}
	
	if($operamode=="un_isgood"){
		for ($i = 0; $i < count($selectid); $i++) {
			$db->query("update {$dbtablepre}downloads Set isgood=0 where id=".intval($selectid[$i])."");
		}
		jsalert("信息更新成功；","?mp=". $mp ."&action=list&page=".$page.$urlquery);
	}
	
	if($operamode=="delete"){
		if(!$selectid){jsalert("请选择要删除的信息!","this");}
		for ($i = 0; $i < count($selectid); $i++) {
			$rs=$db->fetch_first("select soft_img from {$dbtablepre}downloads where id=".intval($selectid[$i])."");
			if($rs){
				@unlink($upconfig['savepath'].$rs["soft_img"]);
			}
			$db->query("delete from {$dbtablepre}downloads where id = ".intval($selectid[$i])."");
		}
		jsalert("所选信息删除成功；","?mp=". $mp ."&action=list&page=".$page.$urlquery);
	}
}
?>
<?php require('inc.bottom.php');?>
</div>
</body>
</html>