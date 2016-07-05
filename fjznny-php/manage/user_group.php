<?php
require 'inc.checkhead.php';
$pagetitle = "管理组别";
$urlquery = "&s_groupname=". urlencode($s_groupname) ."&s_keyword=". urlencode($s_keyword);
if(!user_permission(array($mp=>'list'))){show_no_permission();}
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
	if(!user_permission(array($mp=>'add'))){show_no_permission();}
	if($act=="addsave"){
		
		if(!$groupname){$errmsg .= '@@请输入组别名称；';}
		if(!checkint($sort_order)){$errmsg .= '@@请正确输入信息排序格式；';}

		if(!$errmsg){
			$db->query("insert into {$dbtablepre}user_group(`name`,sort_order)values('$groupname','$sort_order')");

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
    <td height="24" align="right">信息排序：</td>
    <td><input name="sort_order" type="text" class="input" size="10" value="<?php if($sort_order==""){echo(getmaxnum("user_group"));}else{echo($sort_order);}?>">
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
		<input type="submit" value="搜索" /> <input type="button" value="重置" onclick="window.location='?mp=<?php echo $mp;?>&action=list';" /></td>
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

$query = "select * from {$dbtablepre}user_group  where 1 $sql2 order by sort_order asc,user_group_id asc";
$num=$db->num_rows($db->query($query));
require_once(ROOT_PATH."classes/class.page.php");
$pagecls = new pagecls($num,10,$page,'?mp='.$mp.'&action=list'.$urlquery);
if($num<=0){echo("<tr><td height=\"30\" align=\"center\" colspan=\"10\" class=\"table_trbg02\">没有任何信息！</td></tr>");}
$query=$db->query("$query LIMIT {$pagecls->startrecord},{$pagecls->pagesize}");
while($rs = $db->fetch_array($query)) {
?>
  <tr>
  	<td align="center"><?php echo $rs["user_group_id"];?></td>
    <td align="left">&nbsp;<a href="?<?php echo 'mp='.$mp;?>&action=edit&user_group_id=<?php echo $rs['user_group_id'];?>" title="编辑信息" class="cboxframe"><?php echo getcutstr($rs["name"],40);?></a></td>
    <td align="center"><input type="text" name="sort_order[]" size="8" class="input" value="<?php echo $rs["sort_order"];?>"></td>
	<td align="center"><a href="?<?php echo 'mp='.$mp;?>&action=edit&user_group_id=<?php echo $rs['user_group_id'];?>" title="编辑信息" class="cboxframe">编辑</a> <a href="?mp=user_permission&action=edit&user_group_id=<?php echo $rs['user_group_id'];?>" title="权限编辑信息" class="cboxframe">权限</a></td>
	<td align="center"><input type="checkbox" name="selectid[]" value="<?php echo $rs["user_group_id"];?>"><input type="hidden" name="hideid[]" value="<?php echo $rs["user_group_id"];?>"></td>
  </tr>
<?php
}
?>
   <tr>
   		<td colspan="10">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="50%"><?php if(user_permission(array($mp=>'add'))){?><input type="button" name="button" value="新增信息" title="新增信息" alt="?<?php echo 'mp='.$mp;?>&action=add" class="cboxframe" /><?php }?></td>
    <td align="right"><input type="hidden" name="operamode" value="">
		<input type="checkbox" name="chkall" value="on" onClick="checkall(this.form,'selectid[]')" />全选
        <?php if(user_permission(array($mp=>'update'))){?><input type="button" value="更新" onclick="javascript:operation('update');" /><?php }?>
        <?php if(user_permission(array($mp=>'delete'))){?><input type="button" value="删除" onClick="javascript:operation('delete');" /><?php }?></td>
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
	if(!checkint($user_group_id)){jsalert('script','alert("参数传递出错，请重试；");parent.closeFrame();');}
	$val=$db->fetch_first("select * from {$dbtablepre}user_group where user_group_id =".$user_group_id."");
	if(!$val){
		jsalert('script','alert("没有找到相关信息，请重试；");parent.closeFrame();');
	}

	if($act=="editsave"){
		if(!user_permission(array($mp=>'edit'))){show_no_permission();}

		if(!$groupname){$errmsg .= '@@请输入组别名称；';}
		if(!checkint($sort_order)){$errmsg .= '@@请正确输入信息排序格式；';}
		

		if(!$errmsg){
			$db->query("update {$dbtablepre}user_group set `name`='$groupname',sort_order='$sort_order' where user_group_id=".$val['user_group_id']."");
			showTips('编辑保存成功；','?mp='. $mp .'&action=edit&user_group_id='.$user_group_id);
		}
		showErrTips($errmsg);
	}
?>

<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="tablebk">
<form name="Form1" action="?<?php echo 'mp='.$mp;?>&action=edit&act=editsave&user_group_id=<?php echo $user_group_id;?>" method="post" target="postpanel">
  <tr>
    <td width="40%" align="right"><img src="images/icon01.gif" align="absmiddle" />组别名称：</td>
    <td><input name="groupname" type="text" class="input" size="40" value="<?php echo $val['name'];?>"></td>
  </tr>
  <tr>
    <td height="24" align="right">信息排序：</td>
    <td><input name="sort_order" type="text" class="input" size="10" value="<?php if($val['sort_order']==""){echo(getmaxnum("user_group"));}else{echo($val['sort_order']);}?>">
数字，越小越前面</td>
  </tr>
  <tr>
    <td colspan="2" align="center"><?php if(user_permission(array($mp=>'edit'))){?><input type="submit" name="Submit" value="提 交"> &nbsp; <?php }?>
      <input type="button" name="Submit" value="返回列表" onClick="parent.closeFrame();reloadmain();"></td>
  </tr>
  </form>
</table>
<?php
}
if($action=="upsave"){
	if(!$operamode){showErrTips('@@请选择要执行的操作方式');}
	if($operamode=="update"){
		if(!user_permission(array($mp=>'update'))){show_no_permission();}
		for ($i = 0; $i < count($hideid); $i++) {
			$db->query("update {$dbtablepre}user_group Set sort_order={$sort_order[$i]} where user_group_id={$hideid[$i]}");
		}
		showTips('信息更新成功；','?mp='. $mp .'&action=list&page='.$page.$urlquery);
	}
	if($operamode=="delete"){
		if(!user_permission(array($mp=>'delete'))){show_no_permission();}
		if(!$selectid){showErrTips('@@请选择要删除的信息');}
		for ($i = 0; $i < count($selectid); $i++) {
			$db->query("delete from {$dbtablepre}user_group where user_group_id = ".intval($selectid[$i])."");
		}
		showTips('所选信息删除成功；','?mp='. $mp .'&action=list&page='.$page.$urlquery);
	}
}
?>
<?php require('inc.bottom.php');?>
</div>
</body>
</html>