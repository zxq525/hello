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
		$data = array(); 
		$query = $db->query("SELECT * FROM {$dbtablepre}setting WHERE `group` = 'config'");
		while($result = $db->fetch_array($query)){
			if (!$result['serialized']) {
				$data[$result['key']] = $result['value'];
			} else {
				$data[$result['key']] = unserialize($result['value']);
			}
		}
	
	if($act=="editsave"){
		if(!$config_name){$errmsg=$errmsg.'@@请输入系统名称；';}
		
		if(!$errmsg){
			foreach($_POST as $key => $value)
			{
				if(substr($key, 0, 6)=='config'){
					$setting=$db->fetch_first("SELECT * FROM {$dbtablepre}setting WHERE `group` = 'config' AND `key`='$key'");
					if($setting){
						$db->query("UPDATE {$dbtablepre}setting SET `value` = '". addslashes($value) ."' WHERE `group` = 'config' AND `key`='$key'");
					}else{
						$db->query("INSERT INTO {$dbtablepre}setting SET store_id='0', `group`='config', `key`='". $key ."', `value`='". addslashes($value) ."', serialized='0'");
					}
				}
			}
			showTips('信息保存成功','?mp='. $mp .'&action=list');
		}
		showErrTips($errmsg);
	}
?>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="tablebk">
<form name="Form1" action="?<?php echo 'mp='.$mp;?>&action=list&act=editsave" method="post" target="postpanel">
  <tr>
  	<th colspan="3"><h2>基本配制</h2></th>
  </tr>
  <tr>
    <td width="18%" align="right" class="table_trbg02">系统名称：</td>
    <td width="86%" colspan="2" class="table_trbg02"><input name="config_name" type="text" class="input" size="40" value="<?php echo $data["config_name"];?>" /></td>
  </tr>
  <tr>
    <td width="18%" align="right" class="table_trbg02">网站域名：</td>
    <td width="86%" colspan="2" class="table_trbg02"><input name="config_domain" type="text" class="input" size="40" value="<?php echo $data["config_domain"];?>" /></td>
  </tr>  
  <tr>
    <td align="right" class="table_trbg02">首页标题：</td>
    <td colspan="2" class="table_trbg02"><input name="config_home_title" type="text" class="input" size="40" value="<?php echo $data["config_home_title"];?>" /></td>
  </tr>
  <tr>
    <td align="right" class="table_trbg02">首页关键词：</td>
    <td colspan="2" class="table_trbg02"><input name="config_home_keywords" type="text" class="input" size="40" value="<?php echo $data["config_home_keywords"];?>" /></td>
  </tr>
  <tr>
    <td align="right" class="table_trbg02">首页描述：</td>
    <td colspan="2" class="table_trbg02"><input name="config_home_description" type="text" class="input" size="40" value="<?php echo $data["config_home_description"];?>" /></td>
  </tr>
  <tr>
    <td align="right" class="table_trbg02">网站状态：</td>
    <td colspan="2" class="table_trbg02"><input name="config_close_status" type="text" class="input" size="40" value="<?php echo $data["config_close_status"];?>" /></td>
  </tr>
  <tr>
    <td align="right" class="table_trbg02">关闭说明：</td>
    <td colspan="2" class="table_trbg02"><input name="config_close_description" type="text" class="input" size="40" value="<?php echo $data["config_close_description"];?>" /></td>
  </tr>
  <tr>
    <td height="40" colspan="3" align="center" class="table_trbg02"><input type="submit" name="Submit" value="提 交"> &nbsp; 
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