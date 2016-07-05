<?php
require 'inc.checkhead.php';
if(!user_permission(array($mp=>'list'))){show_no_permission();}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>管理登录</title>
<link href="style/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/common.js"></script>
</head>
<body>
<div class="bodybox" id="bodybox">
<?php
if($action=="list"){
	if($act=="editsave"){
		if($password==""){$errmsg .= '@@请输入旧登陆密码；';}
		if(strlen($password2)<6 || strlen($password2)>20){$errmsg .= '@@为了账户安全登陆密码应该在6-20个字符；';}
		if($password2!=$password3){$errmsg .= '@@两次输入的密码不一致；';}
		//echo("select id from vn_account where password='".md5($password)."' and id=".$a_userid."");die();
		if(!$errmsg){
			$rs=$db->fetch_first("select user_id from {$dbtablepre}user where `loginpwd`='".md5($password)."' and user_id=".$user['user_id']."");
			if (!$rs){
				$errmsg .= '@@旧密码输入错误，请重新输入；';
			}
		}
		if(!$errmsg){
			$db->query("update {$dbtablepre}user set `loginpwd`='".md5($password2)."' where user_id=".$user['user_id']."");
			//setcookie($app_name."user_loginpwd",$password2);
			showTips('新密码保存成功，请重新登陆；','?mp='. $mp .'&action=list');
		}
		showErrTips($errmsg);
	}
?>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="tablebk">
<form name="Form1" action="?<?php echo 'mp='.$mp;?>&action=list&act=editsave" method="post" target="postpanel">
  <tr>
  	<th colspan="2"><h2>修改登录密码</h2></th>
  </tr>
  <tr>
    <td width="45%" height="24" align="right">当前用户名：</td>
    <td><strong><?php echo $user['loginname'];?></strong></td>
  </tr>
  <tr>
    <td height="24" align="right">输入旧密码：</td>
    <td><input name="password" type="password" class="input" size="30">    </td>
  </tr>
  <tr>
    <td height="24" align="right">输入新密码：</td>
    <td><input name="password2" type="password" class="input" size="30">    </td>
  </tr>
  <tr>
    <td height="24" align="right">确认新密码：</td>
    <td><input name="password3" type="password" class="input" size="30"></td>
  </tr>
  <tr>
    <td height="40" colspan="2" align="center"><input type="submit" name="Submit" value="提 交"> &nbsp; 
      <input name="Submit" type="reset" value="重 置"></td>
  </tr>
  </form>
</table>
<?php
}
?>

<?php require('inc.bottom.php');?>
</div>
</body>
</html>