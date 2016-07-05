<?php
require 'inc.checkhead.php';
$pagetitle = "管理组别";
$urlquery = "&s_groupname=". urlencode($s_groupname) ."&s_keyword=". urlencode($s_keyword);
if(!user_permission(array('user'=>'edit'))){show_no_permission();}
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
if($action=="edit"){
	//读取数据库原有资料
	if(!checkint($user_id)){jsalert('script','alert("参数传递出错，请重试；");parent.closeFrame();');}
	$val=$db->fetch_first("select A.*,B.name as groupname from {$dbtablepre}user AS A left join {$dbtablepre}user_group AS B on A.user_group_id=B.user_group_id where A.user_id=".$user_id."");
	if(!$val){
		jsalert('script','alert("没有找到相关信息，请重试；");parent.closeFrame();');
	}

	if($act=="editsave"){
		if(!$errmsg){
			$permission_school = trim(str_replace("\r\n","\n",$permission_school));
			$array_permission_school = explode("\n", $permission_school);
			$array_school = array();
			foreach($array_permission_school as $school){
				if(trim($school)){
					$array_school[] = $school;
				}
			}
			if($array_school){
				$array_school = array_unique($array_school);
				$permission_school = implode(',', $array_school);
			}
			
			$db->query("update {$dbtablepre}user set `permission_school`='$permission_school' where user_id=".$val['user_id']."");
			showTips('编辑保存成功；','?mp='. $mp .'&action=edit&user_id='.$user_id);
		}
		showErrTips($errmsg);
	}
?>

<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="tablebk">
<form name="Form1" action="?<?php echo 'mp='.$mp;?>&action=edit&act=editsave&user_id=<?php echo $user_id;?>" method="post" target="postpanel">
  <tr>
    <td width="28%" align="right">所属组别：</td>
    <td><?php echo $val['groupname'];?></td>
  </tr>
  <tr>
    <td align="right">登录账户：</td>
    <td><?php echo $val['loginname'];?></td>
  </tr>
  <tr>
    <td align="right" valign="top">大学权限：<div>大学名称,一行一个</div><div>不填为所有大学</div><div>输入的不匹配数据将自动删除</div></td>
    <td><textarea name="permission_school" cols="70" rows="15" class="textarea"><?php if($val['permission_school']){echo str_replace(",","\n",$val['permission_school']);}?></textarea></td>
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
?>
<?php require('inc.bottom.php');?>
</div>
</body>
</html>