<?php
require 'inc.checkhead.php';
$pagetitle = "系统人员";
$urlquery = "&s_user_group_id=".urlencode($s_user_group_id)."&s_loginname=".urlencode($s_loginname)."&s_truename=".urlencode($s_truename)."";
if(!user_permission(array($mp=>'list'))){show_no_permission();}
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
	if(!user_permission(array($mp=>'add'))){show_no_permission();}
	if($act=="addsave"){
		if(!checkint($user_group_id)){$errmsg .= '@@请选择所属组别；';}
		if(strlength($loginname)<4){$errmsg .= '@@为了账户安全登录账户不得小于4个字节；';}
		if(strlength($loginpwd)<6){$errmsg .= '@@为了账户安全登录密码不得小于6个字节；';}
		if(!$truename){$errmsg .= '@@请输入联系姓名；';}
	
		if(!$errmsg){
			$rs=$db->fetch_first("select user_id from {$dbtablepre}user where loginname='".$loginname."'");
			if ($rs){
				$errmsg .= '@@此登录账户已经被使用，请用其他用户名重试；';
			}
		}
		if(!$errmsg){
			$db->query("insert into {$dbtablepre}user(user_group_id,loginname,loginpwd,truename,date_added,status,comment)values('$user_group_id','$loginname','".md5($loginpwd)."','$truename','". SYS_DATETIME ."','$status','$comment')");
			showTips('信息添加成功','?mp='. $mp .'&action=add&user_group_id='.$user_group_id);
		}
		showErrTips($errmsg);
	}
?>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="tablebk">
<form name="Form1" action="?<?php echo 'mp='.$mp;?>&action=add&act=addsave" method="post" target="postpanel">
  <tr>
    <td width="38%" align="right">所属组别：</td>
    <td><select name="user_group_id" id="user_group_id">
    <option value="">--请选择--</option>
    <?php
	$query=$db->query("SELECT * FROM {$dbtablepre}user_group ORDER BY sort_order ASC , user_group_id ASC");
	while($rs = $db->fetch_array($query)){
	?>
    	<option value="<?php echo $rs['user_group_id']?>"<?php echo setselected($user_group_id,$rs['user_group_id']);?>><?php echo $rs['name']?></option>
    <?php
	}
	?>
    </select>
    </td>
  </tr>
  <tr>
    <td align="right">登录账户：</td>
    <td><input name="loginname" type="text" class="input" size="30" value="<?php echo $loginname;?>"></td>
  </tr>
  <tr>
    <td align="right">登录密码：</td>
    <td><input name="loginpwd" type="password" class="input" size="30"></td>
  </tr>
  <tr>
    <td align="right">联系姓名：</td>
    <td><input name="truename" type="text" class="input" size="30" value="<?php echo $truename;?>"></td>
  </tr>
  <tr>
    <td align="right">联系邮箱：</td>
    <td><input name="email" type="text" class="input" size="30" value="<?php echo $val['email'];?>"></td>
  </tr>
  <tr>
    <td align="right">其他备注：</td>
    <td><textarea name="comment" cols="40" rows="4" class="textarea" id="comment"><?php echo $comment;?></textarea></td>
  </tr>
  <tr>
    <td align="right">账户状态：</td>
    <td><input type="radio" name="status" value="1"<?php if($val['status']==''){echo(' checked="checked"');}else{echo(setchecked($val['status'],'1'));}?>>正常 &nbsp; 
      <input type="radio" name="status" value="0"<?php echo setchecked($status,'0')?>>锁定</td>
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
<select name="s_user_group_id" id="s_user_group_id">
    <option value="">--所有分类--</option>
    <?php
	$query=$db->query("SELECT * FROM {$dbtablepre}user_group ORDER BY sort_order ASC , user_group_id ASC");
	while($rs = $db->fetch_array($query)){
	?>
    	<option value="<?php echo $rs['user_group_id']?>"<?php echo setselected($s_user_group_id,$rs['user_group_id']);?>><?php echo $rs['name']?></option>
    <?php
	}
	?>
</select>        
账户:<input name="s_loginname" type="text" size="15" class="input" value="<?php echo $s_loginname;?>" /> &nbsp; 
姓名:<input name="s_truename" type="text" size="15" class="input" value="<?php echo $s_truename;?>" />
		 						<input type="submit" value="搜索" /></td>
        
      </tr></form>
    </table></td>
  </tr>
<form name="Form1" method="post" action="?<?php echo 'mp='.$mp;?>&action=upsave&page=<?php echo $page;?><?php echo $urlquery;?>" target="postpanel">
  <tr>
    <td height="24" align="center"><strong>ID</strong></td>
    <td align="center"><strong>所属组</strong></td>
    <td align="center"><strong>登录账户</strong></td>
    <td align="center"><strong>管理姓名</strong></td>
    <td align="center"><strong>账户状态</strong></td>
    <td align="center"><strong>操作</strong></td>
	<td align="center"><strong>选择</strong></td>
  </tr>
<?php
if(checkint($s_user_group_id)){$sql2 .= " and A.user_group_id=$s_user_group_id ";}
if($s_loginname){$sql2 .= " and A.loginname like '%{$s_loginname}%' ";}
if($s_truename){$sql2 .= " and A.truename like '%{$s_truename}%' ";}

$query = "select A.*,B.name as groupname from {$dbtablepre}user AS A left join {$dbtablepre}user_group AS B on A.user_group_id=B.user_group_id where 1 $sql2 order by A.user_id desc";
$num=$db->num_rows($db->query($query));
require_once(ROOT_PATH."classes/class.page.php");
$pagecls = new pagecls($num,15,$page,'?mp='.$mp.'&action=list'.$urlquery);
if($num<=0){echo("<tr><td height=\"30\" align=\"center\" colspan=\"10\" class=\"table_trbg02\">没有任何信息！</td></tr>");}
$query=$db->query("$query LIMIT {$pagecls->startrecord},{$pagecls->pagesize}");
while($rs = $db->fetch_array($query)) {
?>
  <tr>
    <td height="24" align="center"><?php echo $rs["user_id"];?></td>
    <td align="center"><?php echo $rs["groupname"];?></td>
    <td align="center"><a href="?<?php echo 'mp='.$mp;?>&action=edit&user_id=<?php echo $rs["user_id"].$urlquery;?>" title="编辑信息" class="cboxframe"><?php echo $rs["loginname"];?></a></td>
    <td align="center"><?php echo $rs["truename"];?></td>
    <td align="center"><?php if($rs["status"]==1){echo("正常");}else{echo("<span class=\"red\">锁定</span>");}?></td>
    <td align="center"><a href="?<?php echo 'mp='.$mp;?>&action=edit&user_id=<?php echo $rs["user_id"].$urlquery;?>" title="编辑信息" class="cboxframe">编辑</a></td>
	<td align="center"><input type="checkbox" name="selectid[]" value="<?php echo $rs["user_id"];?>"><input type="hidden" name="hideid[]" value="<?php echo $rs["user_id"];?>"></td>
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
        <?php if(user_permission(array($mp=>'delete'))){?><input type="button" value="删除" onClick="javascript:operation('delete');" /><?php }?></td>
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
	if(!checkint($user_id)){jsalert('script','alert("参数传递出错，请重试；");parent.closeFrame();');}
	$val=$db->fetch_first("select * from {$dbtablepre}user where user_id=".$user_id."");
	if(!$val){
		jsalert('script','alert("没有找到相关信息，请重试；");parent.closeFrame();');
	}

	if($act=="editsave"){
		if(!user_permission(array($mp=>'edit'))){show_no_permission();}

		if(!checkint($user_group_id)){$errmsg .= '@@请选择所属组别；';}
		if(strlength($loginname)<4){$errmsg .= '@@为了账户安全登录账户不得小于4个字节；';}
		if(strlength($loginpwd)<6 && $loginpwd!=""){$errmsg .= '@@为了账户安全登录密码不得小于6个字节；';}
		if(!$truename){$errmsg .= '@@请输入联系姓名；';}
	
		if(!$errmsg){
		$rs=$db->fetch_first("select user_id from {$dbtablepre}user where loginname='$loginname' and user_id!=$user_id");
			if ($rs){
				$errmsg .= '@@此登录账户已经被使用，请用其他用户名重试；';
			}
		}
		
		if(!$errmsg){
			if($loginpwd){
				$sql2=" ,loginpwd='".md5($loginpwd)."' ";
				if(strval($user["user_id"])==strval($user_id)){
					//setcookie($app_name."user_loginpwd",$loginpwd);
				}
			}
			$db->query("update {$dbtablepre}user set user_group_id='$user_group_id',loginname='$loginname',truename='$truename',email='$email',status='$status',comment='$comment' ".$sql2." where user_id=$user_id");
			
			showTips('编辑保存成功；','?mp='. $mp .'&action=edit&user_id='.$user_id);
		}
		showErrTips($errmsg);
	}
?>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="tablebk">
<form name="Form1" action="?<?php echo 'mp='.$mp;?>&action=edit&act=editsave&user_id=<?php echo $user_id;?>" method="post" target="postpanel">
  <tr>
  	<th colspan="2"><h2>管理人员编辑</h2></th>
  </tr>
  <tr>
    <td width="38%" align="right">所属组别：</td>
    <td><select name="user_group_id" id="user_group_id">
    <option value="">--请选择--</option>
    <?php
	$query=$db->query("SELECT * FROM {$dbtablepre}user_group ORDER BY sort_order ASC , user_group_id ASC");
	while($rs = $db->fetch_array($query)){
	?>
    	<option value="<?php echo $rs['user_group_id'];?>"<?php echo setselected($val['user_group_id'],$rs['user_group_id']);?>><?php echo $rs['name']?></option>
    <?php
	}
	?>
    </select>
    </td>
  </tr>
  <tr>
    <td align="right">登录账户：</td>
    <td><input name="loginname" type="text" class="input" size="30" value="<?php echo $val['loginname'];?>"></td>
  </tr>
  <tr>
    <td align="right">登录密码：</td>
    <td><input name="loginpwd" type="password" class="input" size="30"> 不修改请留空</td>
  </tr>
  <tr>
    <td align="right">联系姓名：</td>
    <td><input name="truename" type="text" class="input" size="30" value="<?php echo $val['truename'];?>"></td>
  </tr>
  <tr>
    <td align="right">联系邮箱：</td>
    <td><input name="email" type="text" class="input" size="30" value="<?php echo $val['email'];?>"></td>
  </tr>
  <tr>
    <td align="right">其他备注：</td>
    <td><textarea name="comment" cols="40" rows="4" class="textarea"><?php echo $val['comment'];?></textarea></td>
  </tr>
  <tr>
    <td align="right">账户状态：</td>
    <td><input type="radio" name="status" value="1"<?php if($val['status']==''){echo(' checked="checked"');}else{echo(setchecked($val['status'],'1'));}?>>正常 &nbsp; 
      <input type="radio" name="status" value="0"<?php echo setchecked($val['status'],'0')?>>锁定</td>
  </tr>
  <tr>
    <td height="40" colspan="2" align="center">
      <?php if(user_permission(array($mp=>'edit'))){?><input type="submit" name="Submit" value="提 交"> &nbsp; <?php }?>
      <input type="button" name="Submit" value="返回列表" onClick="parent.closeFrame();reloadmain();"></td>
  </tr>
  </form>
</table>
<?php
}
if($action=="upsave"){

	if(!$operamode){showErrTips('@@请选择要执行的操作方式');}
	if($operamode=="delete"){
		if(!user_permission(array($mp=>'delete'))){show_no_permission();}
		if(!$selectid){showErrTips('@@请选择要删除的信息');}
		for ($i = 0; $i < count($selectid); $i++) {
			$db->query("delete from {$dbtablepre}user where user_id=".intval($selectid[$i])."");
		}
		showTips('所选信息删除成功！','?mp='. $mp .'&action=list&page='.$page.$urlquery);
	}
}
?>

<?php require('inc.bottom.php');?>
</div>
</body>
</html>