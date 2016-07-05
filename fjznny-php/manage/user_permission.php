<?php
require 'inc.checkhead.php';
$pagetitle = "管理组别";
$urlquery = "&s_groupname=". urlencode($s_groupname) ."&s_keyword=". urlencode($s_keyword);
if(!user_permission(array('user_group'=>'edit'))){show_no_permission();}
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
	if(!checkint($user_group_id)){jsalert('script','alert("参数传递出错，请重试；");parent.closeFrame();');}
	$val=$db->fetch_first("select * from {$dbtablepre}user_group where user_group_id =".$user_group_id."");
	if(!$val){
		jsalert('script','alert("没有找到相关信息，请重试；");parent.closeFrame();');
	}

	if($act=="editsave"){
		if(!$errmsg){
			if($permission){
				$permission = serialize($permission);
			}
			
			$db->query("update {$dbtablepre}user_group set `permission`='$permission' where user_group_id=".$val['user_group_id']."");
			showTips('编辑保存成功；','?mp='. $mp .'&action=edit&user_group_id='.$user_group_id);
		}
		showErrTips($errmsg);
	}
	
	$array_permission = array();
	if($val['permission']){
		$array_permission = unserialize($val['permission']);
	}
?>

<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="tablebk">
<form name="Form1" action="?<?php echo 'mp='.$mp;?>&action=edit&act=editsave&user_group_id=<?php echo $user_group_id;?>" method="post" target="postpanel">
  <tr>
    <td width="20%" align="right"><img src="images/icon01.gif" align="absmiddle" />组别名称：</td>
    <td><?php echo $val['name'];?></td>
  </tr>
  <tr>
    <td height="24" align="right" valign="top">权限设置：</td>
    <td>
<?php
$array_string_action = array(
	'add'=>'新增',
	'edit'=>'修改',
	'list'=>'页面',
	'update'=>'更新',
	'delete'=>'删除',
);
$query = $db->query("SELECT * FROM {$dbtablepre}user_permission_group ORDER BY sort_order ASC,user_permission_group_id ASC");
while($rs = $db->fetch_array($query)) {
	echo '<div style="height:22px;">';
	echo '<strong>'.setaddblank($rs['name'],16)."</strong>";
	echo '</div>';
	echo '<div style="border-bottom:1px solid #CCC; margin-bottom:5px;">';
	$query2 = $db->query("SELECT * FROM {$dbtablepre}user_permission WHERE user_permission_group_id=". (int)$rs['user_permission_group_id'] ." ORDER BY sort_order ASC,user_permission_id ASC");
	while($rs2 = $db->fetch_array($query2)) {
		echo '<div style="height:22px;">';
		echo '<span>'.setaddblank($rs2['name'],16)."</span>"." &nbsp; ";
		if($rs2['actions']){
			$array_action=explode(",",$rs2['actions']);			
			foreach($array_action as $action){
				$checked = '';
				if($array_permission[$rs2["code"]] && is_array($array_permission[$rs2["code"]])){
					if(in_array($action, $array_permission[$rs2["code"]])){
						$checked = ' checked="checked"';
					}	
				}
				echo '<input type="checkbox" name="permission['.$rs2["code"].'][]" value="'. $action .'"'. $checked .' style="vertical-align:middle">'.setaddblank($array_string_action[$action],9).'';
			}
		}
		echo '</div>';
	}
	echo '</div>';
}
?>
<div>全选<input type="checkbox" name="chkall" value="on" onClick="if($(this).attr('checked')){$('input[name^=permission]').attr('checked',true);}else{$('input[name^=permission]').attr('checked',false);}" style="vertical-align:middle" /></div>
    </td>
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
<?php
function setaddblank($thisstr,$lennum=16){
	if($lennum>strlength($thisstr)){
	for($e=1;$e<=($lennum-strlength($thisstr));$e++){
		$tempstr.="&nbsp;";
	}
		return $thisstr.$tempstr;
	}else{
		return $thisstr;
	}
}
?>