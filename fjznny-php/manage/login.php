<?php
require('inc.common.php');
require(ROOT_PATH.'classes/class.file.php');
if($action=="loginchk"){
	if(empty($loginname)){jsalert("请输入登陆用户名！","back");}
	if(empty($loginpwd)){jsalert("请输入登陆密码！","back");}
	if(empty($checkcode)){jsalert("请输入安全码！","back");}
	if(strval(strtolower($checkcode)) != strval(strtolower($_SESSION['verifycode']))){jsalert("验证码输入不正确,请重新输入！","back");}
	
	$rs=$db->fetch_first("select user_id,loginname,loginpwd,status from {$dbtablepre}user where loginname='".$loginname."'");
	if ($rs){
		if(md5($loginpwd)==$rs["loginpwd"]){
			if($rs["status"]==0){jsalert("此账户处于锁定状态，无法登陆！","./login.php");}
			setcookie($app_name."user_loginname",$rs["loginname"]);
			setcookie($app_name."user_loginpwd",$rs["loginpwd"]);
			
			$getip = getip();
			$db->query("update {$dbtablepre}user set ip='". $getip ."' where user_id=".$rs['user_id']."");

			$io=new IO();
			$logfile='log/'.date('Y-m').'.php';
			if(!file_exists($logfile)){
				$content='<'. chr(63) .'php if(!defined(\'SITE\')) {exit(\'Access Denied\');}'. chr(63) .'>'.chr(10);
				$io->write_file($logfile,$content);
			}
			$content=$loginname.'	'. $sys_datetime .'	'. $getip ."\n";
			$io->write_file($logfile,$content,'a');
			$db->close();
			$_SESSION['verifycode']="";
			header("location:./");
			die();
		}else{
			$db->close();
			jsalert("用户名或密码错误001！","back");
		}
	}else{
		$db->close();
		jsalert("用户名或密码错误002！","back");
	}
}

if($action=="logout"){
	setcookie($app_name."user_loginname","");
	setcookie($app_name."user_loginpwd","");	//setcookie($app_name."admin_userpwd","",time()-3600);
	header("location:./");
	die();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>系统管理登录</title>
<link rel="stylesheet" href="style/style.css" type="text/css">
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/common.js"></script>
</head>
<body id="p-login">
	<div class="container">
		<div id="header">
			<div class="logo">
				<a href="#"><img src="images/logo.gif" width="303" height="43" /></a>
			</div>
		</div>
		<div id="wrapper" class="clearfix">
			<div class="login_box">
				<div class="login_title">系统管理登录</div>
				<div class="login_cont">
					<form name="loginform" method="post" action="?action=loginchk" onSubmit="return FormCheck(this);">
						<table class="form_table">
							<col width="90px" /><col />
							<tr>
								<th valign="middle">用户名：</th><td><input class="normal" type="text" name="loginname" alt="请填写用户名" style="width:170px" /></td>
							</tr>
							<tr>
								<th valign="middle">密&nbsp;&nbsp;码：</th><td><input class="normal" type="password" name="loginpwd" alt="请填写密码" style="width:170px" /></td>
							</tr>
							<tr>
								<th valign="middle">验证码：</th><td><input name="checkcode" type="text" size="20" style="width:60px;" class="normal" onfocus="this.select();" pattern='^\w{4,4}$' alt='填写下面图片所示的字符' style="width:85px"><label>填写下图所示字符</label></td>
						  	</tr>
							<tr class="low">
								<th></th>
								<td><img align='top' src='<?php echo(ROOT_PATH);?>classes/class.verifycode.php' style='cursor:pointer;' alt='请输入图片验证码' id="verifycode" onClick="this.src=this.src+'?'" /> <span class="light_gray">看不清？<a class="link" href="javascript:void(0);" onclick="$('#verifycode').attr('src','<?php echo(ROOT_PATH);?>classes/class.verifycode.php?'+Math.random());">换一张</a></span></td>
							</tr>
							<tr>
								<th valign="middle"></th><td><input class="submit" type="submit" value="登录" /><input class="submit" type="reset" value="取消" /></td>
							</tr>
						</table>
					</form>
				</div>
			</div>
		</div>
		<div id="login-footer">Copyright &copy; 2005-<?php echo date('Y');?></div>
	</div>
<script language="javascript">
function FormCheck(theForm){
	if(!checkempty(theForm.loginname,"请输入登录用户名！")) return false;
	if(!checkempty(theForm.loginpwd,"请输入登录密码！")) return false;
	if(!checkempty(theForm.checkcode,"请输入登录验证码！")) return false;
	return true;
}
$(document).ready(function(){
	$("input[name=loginname]").focus();
});
</script>
</body>
</html>
