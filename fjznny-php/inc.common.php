<?php
define('ROOT_PATH', "");
session_start();
require_once(ROOT_PATH."includes/common.php"); 
require_once(ROOT_PATH."classes/class.mysql.php");

$db = new DB_MySQL;
$db -> connect($dbhostname,$dbusername,$dbpassword,$dbdataname);
unset($dbhostname,$dbusername,$dbpassword,$dbconntype,$dbdataname);

mysql_query("SET NAMES 'UTF8'");

$config = array(); 
$query = $db->query("SELECT * FROM {$dbtablepre}setting WHERE `group` = 'config'");
while($result = $db->fetch_array($query)){
	if (!$result['serialized']) {
		$config[$result['key']] = $result['value'];
	} else {
		$config[$result['key']] = unserialize($result['value']);
	}
}

if($config["config_close_status"]){
	echo $config["config_close_description"];
	die();
}

$config["skinurl"]=$siteconfig['skinurl'];
$config["serviceqq"]=$siteconfig['serviceqq'];
$config["visitcount"]=$siteconfig['visitcount'];
$homepage='./';


if($_SESSION[$app_name.'member_loginname']=="" || $_SESSION[$app_name.'member_loginpwd']==""){
	$member['loginstate']=0;
}else{
	$member=$db->fetch_first("select * From {$dbtablepre}member where loginname='".$_SESSION[$app_name.'member_loginname']."' and loginpwd='".$_SESSION[$app_name.'member_loginpwd']."' limit 1");
	if ($member){
		if($member['userstatus']==1){
			unset($_SESSION[$app_name.'member_loginname'],$_SESSION[$app_name.'member_loginpwd']);
			jsalert("您的帐户处于未审核状态，系统将退出您的登录！","./");
		}
		$member['loginstate']=1;
	}else{
		$member['loginstate']=0;
	}
}

function check_memberlogin(){
	global $member;
	if($member['loginstate']==0){
		jsalert("对不起，您还没有登录!","login.php");
	}
}
?>