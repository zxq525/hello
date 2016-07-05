<?php 
require_once("inc.checklogin.php");
$urlquery = "&s_linkname=". urlencode($s_linkname) ."&s_linkurl=". urlencode($s_linkurl);

$upconfig = array('ext'=>'gif|jpg','size'=>1024*500,'savepath'=>$uppath['friendlink'],'addpath'=>'');
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
</head>
<body>
<div class="bodybox" id="bodybox">
<?php
if($action=="add"){
	if($act=="addsave"){
		if(!$linkname){$errmsg=$errmsg."@@请输入链接名称；\\n";}
		if(!checkint($sort_order))$errmsg=$errmsg."@@请正确输入信息排序格式；\\n";
		
		
		if(!$errmsg && $_FILES['linkimg']['name']){
			require(ROOT_PATH.'classes/class.upload.php');
			$f = new upload("linkimg", $upconfig['savepath'], $upconfig['addpath'],$upconfig['ext'], $upconfig['size']);
			if($f->error != 0){
				$errmsg=$errmsg."@@".$f->errormsg()."；\\n";
			}else{
				$linkimg=$f->uploadedfiles[0]['addpath'].$f->uploadedfiles[0]['savename'];	
			}
		}

		if(!$errmsg){
			$db->query("insert into {$dbtablepre}friendlink(linkname,linktype,linkimg,linkurl,sort_order)values('$linkname','$linktype','$linkimg','$linkurl','$sort_order')");

			showTips("信息添加成功；","?mp=". $mp ."&action=add&s_classid=$s_classid");
		}
		showErrTips($errmsg);
	}
?>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="tablebk">
<form name="Form1" action="?mp=<?php echo $mp;?>&action=add&act=addsave<?php echo $urlquery;?>" method="post" enctype="multipart/form-data" target="postpanel">
  <tr>
	<th colspan="2"><h2>友情链接信息添加</h2></th>
  </tr>
  <tr>
    <td height="40" colspan="2" align="center"><input type="submit" name="Submit" value="提 交"> 
      &nbsp;
      <input type="button" name="Submit" value="返回列表" onClick="parent.closeFrame();reloadmain();"></td>
  </tr>
  <tr>
    <td width="33%" height="24" align="right"><strong><img src="images/icon01.gif" align="absmiddle" />链接名称：</strong></td>
    <td><input name="linkname" type="text" class="input" size="70" value="<?php echo $val['linkname'];?>"></td>
  </tr>
  <tr>
    <td height="24" align="right"><strong>链接地址：</strong></td>
    <td><input name="linkurl" type="text" class="input" size="70" value="<?php echo $val['linkurl'];?>"></td>
  </tr>
  <tr>
    <td height="24" align="right"><strong>链接类型：</strong></td>
    <td><input type="radio" name="linktype" value="1"<?php if($val['linktype']==""){echo(" checked=\"checked\"");}else{echo(setchecked($val['linktype'],"1"));}?> />
      文本
      &nbsp;
      <input type="radio" name="linktype" value="2"<?php echo setchecked($val['linktype'],"2")?> />
      图片</td>
  </tr>
  <tr>
    <td height="24" align="right"><strong>信息图片：</strong></td>
    <td><input name="linkimg" type="file" class="input" value="<?php echo $val['linkimg'];?>" size="30" style="height:22px;" /></td>
  </tr>
  <tr>
    <td height="24" align="right"><strong>信息排序：</strong></td>
    <td class="table_trbg02"><input name="sort_order" type="text" class="input" size="10" value="<?php if($sort_order==""){echo(getmaxnum("friendlink"));}else{echo($sort_order);}?>">
数字，越大越前面</td>
  </tr>
  <tr>
    <td height="40" colspan="2" align="center"><input type="submit" name="Submit" value="提 交"> 
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
	<th colspan="9"><h2>友情链接信息列表</h2></th>
  </tr>
  <tr>
    <td colspan="9" class="table_trbg01"><table width="100%" border="0" cellspacing="0" cellpadding="0">
    <form name="Form2" method="get" action="?"><input type="hidden" name="action" value="list" /><input type="hidden" name="mp" value="<?php echo $mp;?>" />
      <tr>
        <td align="right">
		链接名称:
		<input name="s_linkname" type="text" size="14" class="input" value="<?php echo $s_linkname;?>" /> &nbsp; 
        模糊查询:<input name="s_linkurl" type="text" size="14" class="input" value="<?php echo $s_linkurl;?>" />
		<input type="submit" value="搜索" /></td>
      </tr></form>
    </table>	</td>
  </tr>
<form name="Form1" method="post" action="?mp=<?php echo $mp;?>&action=upsave&page=<?php echo $page;?><?php echo $urlquery;?>" target="postpanel">
  <tr>
    <td height="24" align="center"><strong>ID</strong></td>
    <td align="center"><strong>链接名称</strong></td>
    <td align="center"><strong>链接类型</strong></td>
    <td align="center"><strong>信息排序</strong></td>
    <td align="center"><strong>操作</strong></td>
	<td align="center"><strong>选择</strong></td>
  </tr>
<?php
if($s_linkname!=""){$sql2 .= " and A.linkname like '%{$s_linkname}%' ";}

if(checkint($s_classid)){
	$childidarr=$db->result_first("select childidarr from {$dbtablepre}friendlink_class where id=$s_classid");
	if($childidarr){
		$sql2 .= " and (A.classid = ".intval($s_classid)." or A.classid in ($childidarr)) ";
	}else{
		$sql2 .= " and A.classid = ".intval($s_classid)." ";
	}
}
if(checkint($s_ismember)){$sql2 .= " and ismember = ".intval($s_ismember)." ";}
if($s_linkurl!=""){$sql2 .= " and (A.linkname like '%{$s_linkurl}%' or A.content like '%{$s_linkurl}%' or A.author like '%{$s_linkurl}%' or A.source like '%{$s_linkurl}%' ) ";}

$query = "select * from {$dbtablepre}friendlink where 1 $sql2 order by sort_order desc,id desc";
$num=$db->num_rows($db->query($query));
require_once(ROOT_PATH."classes/class.page.php");
$pagecls = new pagecls($num,10,$page,"?action=list{$urlquery}");
if($num<=0){echo("<tr><td height=\"30\" align=\"center\" colspan=\"10\" class=\"table_trbg02\">没有任何信息！</td></tr>");}
$query=$db->query("$query LIMIT {$pagecls->startrecord},{$pagecls->pagesize}");
while($rs = $db->fetch_array($query)) {
?>
  <tr>
    <td height="24" align="center"><?php echo $rs["id"];?></td>
    <td height="24" align="center">&nbsp;<a href="?mp=<?php echo $mp;?>&action=edit&id=<?php echo $rs["id"].$urlquery;?>" title="编辑信息" class="cboxframe"><?php echo getcutstr($rs["linkname"],40);?></a></td>
    <td align="center"><?php if($rs["linktype"]==1){echo '文本';}else{echo '图片';}?></td>
    <td align="center"><input type="text" name="sort_order[]" size="8" class="input" value="<?php echo $rs["sort_order"];?>"></td>
    <td align="center"><a href="?mp=<?php echo $mp;?>&action=edit&id=<?php echo $rs["id"].$urlquery;?>" title="编辑信息" class="cboxframe">编辑</a></td>
	<td align="center"><input type="checkbox" name="selectid[]" value="<?php echo $rs["id"];?>"><input type="hidden" name="hideid[]" value="<?php echo $rs["id"];?>"></td>
  </tr>
<?php
}
?>
   <tr>
   		<td height="24" colspan="9">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="50%"><input type="button" name="button" value="新增信息" title="新增信息" alt="?mp=<?php echo $mp;?>&action=add" class="cboxframe" /></td>
    <td align="right"><input type="hidden" name="operamode" value="">
		<input type="checkbox" name="chkall" value="on" onClick="checkall(this.form,'selectid[]')" />全选

        <input type="button" value="更新排序" onClick="javascript:operation('update');" />
        <input type="button" value="删除" onClick="javascript:operation('delete');" /></td>
  </tr>
</table>      </td>
    </tr>
  </form>
   <tr>
     <td height="24" colspan="9" align="center"><?php echo $pagecls->pageinfo;?></td>
  </tr>
</table>
<?php
}

if($action=="edit"){
	//读取数据库原有资料
	if(!checkint($id)){jsalert("参数传递出错，请重试；","back","window");}
	$val=$db->fetch_first("select * from {$dbtablepre}friendlink where id =".$id."");
	if(!$val){
		jsalert("没有找到相关信息，请重试；","back","window");
	}

	if($act=="delimg"){
		@unlink($upconfig['savepath'].$val["linkimg"]);
		$db->query("update {$dbtablepre}friendlink set linkimg='$linkimg' where id=".$val['id']."");
		$val["linkimg"]='';
		jsalert("url","?action=edit&id=$id&page=$page{$urlquery}");
	}

	if($act=="editsave"){
		if(!$linkname){$errmsg=$errmsg."@@请输入链接名称；\\n";}
		if(!checkint($sort_order))$errmsg=$errmsg."@@请正确输入信息排序格式；\\n";
		
		if(!$errmsg){
			if($_FILES['linkimg']['name']){
				require(ROOT_PATH.'classes/class.upload.php');
				$f = new upload("linkimg", $upconfig['savepath'], $upconfig['addpath'],$upconfig['ext'], $upconfig['size']);
				if($f->error != 0){
					$errmsg=$errmsg."@@".$f->errormsg()."；\\n";
				}else{
					$linkimg=$f->uploadedfiles[0]['addpath'].$f->uploadedfiles[0]['savename'];	
				}
			}
		}
		
		if(!$errmsg){
			if($linkimg){
				@unlink($upconfig['savepath'].$val['linkimg']);
			}else{
				$linkimg=$val['linkimg'];
			}
			
			$db->query("update {$dbtablepre}friendlink set linkname='$linkname', linktype='$linktype', linkimg='$linkimg', linkurl='$linkurl', sort_order='$sort_order' where id=".$val['id']."");
		
			showTips("编辑保存成功；","?mp=". $mp ."&action=edit&id=$id&page=$page{$urlquery}");
		}
		showErrTips($errmsg);
	}
?>

<script language="javascript" src="js/selectdatetime.js"></script>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="tablebk">
<form name="Form1" action="?mp=<?php echo $mp;?>&action=edit&act=editsave&id=<?php echo $id;?>&page=<?php echo $page;?><?php echo $urlquery;?>" method="post" enctype="multipart/form-data" target="postpanel">
  <tr>
	<th colspan="2"><h2>友情链接信息编辑</h2></th>
  </tr>
  <tr>
    <td height="40" colspan="2" align="center"><input type="submit" name="Submit" value="保 存"> 
      &nbsp; 
      <input type="button" name="Submit" value="返回列表" onClick="parent.closeFrame();reloadmain();"></td>
  </tr>
  <tr>
    <td width="33%" height="24" align="right"><strong><img src="images/icon01.gif" align="absmiddle" />链接名称：</strong></td>
    <td><input name="linkname" type="text" class="input" size="70" value="<?php echo $val['linkname'];?>"></td>
  </tr>
  <tr>
    <td height="24" align="right"><strong>链接地址：</strong></td>
    <td><input name="linkurl" type="text" class="input" size="70" value="<?php echo $val['linkurl'];?>" /></td>
  </tr>
  <tr>
    <td height="24" align="right"><strong>链接类型：</strong></td>
    <td><input type="radio" name="linktype" value="1"<?php if($val['linktype']==""){echo(" checked=\"checked\"");}else{echo(setchecked($val['linktype'],"1"));}?> />
      文本
      &nbsp;
      <input type="radio" name="linktype" value="2"<?php echo setchecked($val['linktype'],"2")?> />
      图片</td>
  </tr>
  <tr>
    <td height="24" align="right"><strong>信息图片：</strong></td>
    <td>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="50%" class="nospacing"><input name="linkimg" type="file" class="inputfile" size="30" /></td>
    <td>
<?php
    if($val['linkimg']){
		echo '<span><a href="'.$upconfig['savepath'].$val['linkimg'].'" target="_blank"><img src="'.$upconfig['savepath'].$val['linkimg'].'" height="50" /></a></span>';
		echo ' <a href="?action=edit&act=delimg&id='.$id.'&page='.$page.$urlquery.'" target="postpanel">删除图片</a>';
	}
?>	</td>
  </tr>
</table>    </td>
  </tr>
  <tr>
    <td height="24" align="right"><strong>信息排序：</strong></td>
    <td class="table_trbg02"><input name="sort_order" type="text" class="input" size="10" value="<?php if($val['sort_order']==""){echo(getmaxnum("friendlink"));}else{echo($val['sort_order']);}?>">
数字，越大越前面</td>
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
	if(!$operamode){showErrTips("请选择要执行的操作方式!");}
	

	if($operamode=="update"){
		for ($i = 0; $i < count($hideid); $i++) {
			$db->query("update {$dbtablepre}friendlink Set sort_order=".intval($sort_order[$i])." where id=".intval($hideid[$i])."");
		}
		showTips("信息更新成功；","?mp=". $mp ."&action=list&page=".$page.$urlquery);
	}

	if($operamode=="delete"){
		if(!$selectid){showErrTips("@@请选择要删除的信息!");}
		for ($i = 0; $i < count($selectid); $i++) {
			$rs=$db->fetch_first("select linkimg from {$dbtablepre}friendlink where id=".intval($selectid[$i])."");
			if($rs){
				@unlink($upconfig['savepath'].$rs["linkimg"]);
			}
			$db->query("delete from {$dbtablepre}friendlink where id = ".intval($selectid[$i])."");
		}
		showTips("所选信息删除成功；","?mp=". $mp ."&action=list&page=".$page.$urlquery);
	}
}

?>
<?php require('inc.bottom.php');?>
</div>
</body>
</html>