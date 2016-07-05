<?php
require 'inc.checkhead.php';
$pagetitle = "图片广告组别";
$urlquery = "&s_groupname=". urlencode($s_groupname) ."&s_keyword=". urlencode($s_keyword);
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
		
		if(!$groupname){$errmsg .= '@@请输入组别名称；';}
		if(!checkint($sort_order)){$errmsg .= '@@请正确输入信息排序格式；';}

		if(!$errmsg){
			$db->query("insert into {$dbtablepre}banner(`name`,status,sort_order)values('$groupname','$status','$sort_order')");

			showTips('信息添加成功','?mp='. $mp .'&action=add&s_classid='.$s_classid);
		}
		showErrTips($errmsg);
	}
?>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="tablebk">
<form name="Form1" action="?<?php echo 'mp='.$mp;?>&action=add&act=addsave<?php echo $urlquery;?>" method="post" target="postpanel">
  <tr>
    <td width="40%" align="right">组别名称：</td>
    <td><input name="groupname" type="text" class="input" size="40" value="<?php echo $val['groupname'];?>"></td>
  </tr>
  <tr>
    <td align="right">组别状态：</td>
    <td><input type="radio" name="status" value="1"<?php if($val['status']==''){echo(' checked="checked"');}else{echo(setchecked($val['status'],'1'));}?>>启用 &nbsp; 
      <input type="radio" name="status" value="0"<?php echo setchecked($status,'0')?>>禁用</td>
  </tr>
  <tr>
    <td height="24" align="right">信息排序：</td>
    <td><input name="sort_order" type="text" class="input" size="10" value="<?php if($sort_order==""){echo(getmaxnum("banner"));}else{echo($sort_order);}?>">
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
		组别名称:
		  <input name="s_groupname" type="text" size="14" class="input" value="<?php echo $s_groupname;?>" /> &nbsp; 
        模糊查询:<input name="s_keyword" type="text" size="14" class="input" value="<?php echo $s_keyword;?>" />
		<input type="submit" value="搜索" /> <input type="button" value="重置" onclick="window.location='?action=list';" /></td>
      </tr></form>
    </table>	
	</td>
  </tr>
<form name="Form1" method="post" action="?<?php echo 'mp='.$mp;?>&action=upsave&page=<?php echo $page;?><?php echo $urlquery;?>" target="postpanel">
  <tr>
    <td align="center"><strong>ID</strong></td>
    <td align="center"><strong>组别名称</strong></td>
    <td align="center"><strong>信息排序</strong></td>
 	<td align="center"><strong>操作</strong></td>
	<td align="center"><strong>选择</strong></td>
  </tr>
<?php
if($s_groupname!=""){$sql2 .= " and `name` like '%{$s_groupname}%' ";}

if($s_keyword!=""){$sql2 .= " and (`name` like '%{$s_keyword}%') ";}

$query = "select * from {$dbtablepre}banner  where 1 $sql2 order by sort_order asc,banner_id asc";
$num=$db->num_rows($db->query($query));
require_once(ROOT_PATH."classes/class.page.php");
$pagecls = new pagecls($num,10,$page,'?mp='.$mp.'&action=list'.$urlquery);
if($num<=0){echo("<tr><td height=\"30\" align=\"center\" colspan=\"10\" class=\"table_trbg02\">没有任何信息！</td></tr>");}
$query=$db->query("$query LIMIT {$pagecls->startrecord},{$pagecls->pagesize}");
while($rs = $db->fetch_array($query)) {
?>
  <tr>
  	<td align="center"><?php echo $rs["banner_id"];?></td>
    <td align="left">&nbsp;<a href="?<?php echo 'mp='.$mp;?>&action=edit&banner_id=<?php echo $rs['banner_id'];?>" title="编辑信息" class="cboxframe"><?php echo getcutstr($rs["name"],40);?></a></td>
    <td align="center"><input type="text" name="sort_order[]" size="8" class="input" value="<?php echo $rs["sort_order"];?>"></td>
	<td align="center"><a href="?<?php echo 'mp='.$mp;?>&action=edit&banner_id=<?php echo $rs['banner_id'];?>" title="编辑信息" class="cboxframe">编辑</a></td>
	<td align="center"><input type="checkbox" name="selectid[]" value="<?php echo $rs["banner_id"];?>"><input type="hidden" name="hideid[]" value="<?php echo $rs["banner_id"];?>"></td>
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
	if(!checkint($banner_id)){jsalert('script','alert("参数传递出错，请重试；");parent.closeFrame();');}
	$val=$db->fetch_first("select * from {$dbtablepre}banner where banner_id =".$banner_id."");
	if(!$val){
		jsalert('script','alert("没有找到相关信息，请重试；");parent.closeFrame();');
	}

	if($act=="editsave"){
		if(!$groupname){$errmsg .= '@@请输入组别名称；';}
		if(!checkint($sort_order)){$errmsg .= '@@请正确输入信息排序格式；';}
		

		if(!$errmsg){
			$db->query("update {$dbtablepre}banner set `name`='$groupname',status='$status',sort_order='$sort_order' where banner_id=".$val['banner_id']."");
			showTips('编辑保存成功；','?mp='. $mp .'&action=edit&banner_id='.$banner_id);
		}
		showErrTips($errmsg);
	}
?>

<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="tablebk">
<form name="Form1" action="?<?php echo 'mp='.$mp;?>&action=edit&act=editsave&banner_id=<?php echo $banner_id;?>" method="post" target="postpanel">
  <tr>
    <td width="40%" align="right"><img src="images/icon01.gif" align="absmiddle" />组别名称：</td>
    <td><input name="groupname" type="text" class="input" size="40" value="<?php echo $val['name'];?>"></td>
  </tr>
  <tr>
    <td align="right">组别状态：</td>
    <td><input type="radio" name="status" value="1"<?php if($val['status']==''){echo(' checked="checked"');}else{echo(setchecked($val['status'],'1'));}?>>启用 &nbsp; 
      <input type="radio" name="status" value="0"<?php echo setchecked($val['status'],'0')?>>禁用</td>
  </tr>
  <tr>
    <td height="24" align="right">信息排序：</td>
    <td><input name="sort_order" type="text" class="input" size="10" value="<?php if($val['sort_order']==""){echo(getmaxnum("banner"));}else{echo($val['sort_order']);}?>">
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
if($action=="upsave"){
	if(!$operamode){showErrTips('@@请选择要执行的操作方式');}
	if($operamode=="update"){
		for ($i = 0; $i < count($hideid); $i++) {
			$db->query("update {$dbtablepre}banner Set sort_order={$sort_order[$i]} where banner_id={$hideid[$i]}");
		}
		showTips('信息更新成功；','?mp='. $mp .'&action=list&page='.$page.$urlquery);
	}
	if($operamode=="delete"){
		if(!$selectid){showErrTips('@@请选择要删除的信息');}
		for ($i = 0; $i < count($selectid); $i++) {
			$rs=$db->fetch_first("select image from {$dbtablepre}banner_image where banner_id=".intval($selectid[$i])."");
			if($rs){
				@unlink($uppath['image'].$rs["image"]);
			}
			$db->query("delete from {$dbtablepre}banner_image where banner_id=".intval($selectid[$i])."");
			$db->query("delete from {$dbtablepre}banner_image_description where banner_id = ".intval($selectid[$i])."");
			$db->query("delete from {$dbtablepre}banner where banner_id = ".intval($selectid[$i])."");
		}
		showTips('所选信息删除成功；','?mp='. $mp .'&action=list&page='.$page.$urlquery);
	}
}
?>
<?php require('inc.bottom.php');?>
</div>
</body>
</html>