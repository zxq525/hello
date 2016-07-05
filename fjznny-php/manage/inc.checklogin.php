<?php
require_once("inc.common.php");
@header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
@header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
@header("Cache-Control: no-cache, must-revalidate");
@header("Pragma: no-cache");

define('SYS_IN_MP', TRUE);

$this_loginname=$_COOKIE[$app_name."user_loginname"];
$this_loginpwd=$_COOKIE[$app_name."user_loginpwd"];

//echo($loginpwd);die();

//if(empty($adminname)){jsalert("登陆超时或您还没有登陆，请重新登陆！","./admin_login.php");}
if(empty($this_loginname)){jsalert("url","login.php","top");die();}

$rs=$db->fetch_first("select A.user_id,A.loginname,A.loginpwd,A.truename,A.status,B.name as groupname,B.permission from {$dbtablepre}user AS A left join {$dbtablepre}user_group AS B on A.user_group_id=B.user_group_id where A.loginname='".$this_loginname."' and A.loginpwd='".$this_loginpwd."'");
//echo $rs;die();
if ($rs){
	foreach($rs as $key => $value) {
		$user[$key]= gaddslashes($value);
	}
	if($user['permission']){
		$user['permission'] = unserialize($user['permission']);	
	}else{
		show_no_permission();	
	}
	if($user['status']==0){jsalert("此账户处于锁定状态，无法登陆！","login.php","top");}
}else{
	jsalert("url","login.php","top");
	die();
}

$config = array(); 
$query = $db->query("SELECT * FROM {$dbtablepre}setting WHERE `group` = 'config'");
while($result = $db->fetch_array($query)){
	if (!$result['serialized']) {
		$config[$result['key']] = $result['value'];
	} else {
		$config[$result['key']] = unserialize($result['value']);
	}
}

function showerrmsg($errstr){
	global $site_charset;
	echo "<html>\n";
	echo "<head>\n";
	echo "<title>管理</title>\n";
	echo "<meta http-equiv=\"content-type\" content=\"text/html; charset={$site_charset}\">\n";
	echo "<link rel=\"stylesheet\" href=\"style/style.css\">\n";
	echo "</head>\n";
	echo "<body>\n";
	echo "<table width=\"99%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"1\" class=\"tablebk\">\n";
	echo "  <tr>\n";
	echo "    <th colspan=\"2\"><div class=\"table_titlebg\"><img src=\"images/icon02.gif\" align=\"absmiddle\" /> 出现以下错误</div></th>\n";
	echo "  </tr>\n";
	echo "  <tr>\n";
	echo "    <td class=\"table_trbg02\" style=\"padding:5px;\">".$errstr."</td>\n";
	echo "  </tr>\n";
	echo "  <tr>\n";
	echo "    <td height=\"40\" colspan=\"2\" align=\"center\" class=\"table_trbg02\"><input type=\"button\" name=\"submit\" value=\"返回上一页\" onclick=\"javascript:window.history.go(-1);\">\n";
	echo "      &nbsp;\n";
	echo "      <input type=\"button\" name=\"submit\" value=\"返回管理首页\" onclick=\"javascript:window.location='manage_main.php';\"></td>\n";
	echo "  </tr>\n";
	echo "</table>\n";
	echo "</body>\n";
	echo "</html>";
	die();
}

function checklevel($currentlevel,$isshow=true,$showstr="<li>对不起，您没有操作当前页面的权限</li>；"){
	global $user;
	if(strpos(strval($user["userlevel"]),strval(','.$currentlevel.','))===false){
		if($isshow){
			echo showerrmsg($showstr);die();
		}else{
			return false;
		}
	}else{
		return true;
	}
}

function checkleveldie($currentlevel){
	if(!checklevel($currentlevel,false)){
		die();
	}
}

function checklevel2($currentlevel){
	if(!checklevel($currentlevel,false)){
		return false;
	}else{
		return true;
	}
}

function checklevel_all($level_all){
	$level_alls=explode(",",$level_all);
	foreach($level_alls as $key => $value){
		if(checklevel($value,false)){
			return true;
			break;
		}
	}
}

function show_no_permission(){
	require 'not_permission.php';
}

function user_permission($array_code){
	global $user;
	$return = false;
	if(is_array($array_code)){
		foreach($array_code as $code => $action){
			if($user['permission'][$code] && is_array($user['permission'][$code])){
				if(in_array($action,$user['permission'][$code])){
					$return = true;
					break;
				}
			}
		}
	}
	return $return;
}

function getCategories($parent_id = 0,$level = 1) {
	global $db,$dbtablepre;
	$category_data = array();
	$query = $db->query("SELECT * FROM {$dbtablepre}category c LEFT JOIN {$dbtablepre}category_description cd ON (c.category_id = cd.category_id) WHERE c.parent_id = '" . (int)$parent_id . "' AND cd.language_id = '1' ORDER BY c.sort_order, cd.name ASC");
	while($result = $db->fetch_array($query)) {
		$category_data[] = array(
			'category_id' => $result['category_id'],
			'parent_id'   => $result['parent_id'],
			'name'        => $result['name'],
			'status'  	  => $result['status'],
			'sort_order'  => $result['sort_order'],
			'level'  => $level
		);
		$category_data = array_merge($category_data, getCategories($result['category_id'],$level+1));
	}
		
	return $category_data;
}
?>