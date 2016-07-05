<?php
require 'inc.checkhead.php';
$urlquery = "&classid=".urlencode($classid);

if(!checkint($classid)){jsalert("没有找到相关信息，请重试；","back","window");}
$class=$db->fetch_first("select * from {$dbtablepre}article_class where id =".$classid."");
if(!$class){
	jsalert("没有找到相关信息，请重试；","back","window");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 friendlinkal//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-friendlinkal.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>管理</title>
<link href="style/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/common.js"></script>
<script type="text/javascript" src="js/selectdatetime.js"></script>
</head>
<body>
<div class="bodybox" id="bodybox">
<?php
if($action=="edit"){
	//读取数据库原有资料
	$val=$db->fetch_first("select * from {$dbtablepre}article where classid =".$classid."");
	if(!$val){
		$topic=addslashes($class['classname']);
		$hitnum=0;
		$orders=0;
		$db->query("insert into {$dbtablepre}article(classid,topic,content,joindate,hitnum,orders)values($classid,'$topic','$content','".time()."','$hitnum','$orders')");
	}
	
	
	if($act=="editsave"){
		//if($topic==""){$errmsg=$errmsg."@@请输入信息标题；\\n";}
		if(!checkdatetime($joindate))$errmsg=$errmsg."@@请选择正确的添加时间；\\n";

		if(!$errmsg){
			$joindate=strtotime($joindate);
			$db->query("update {$dbtablepre}article set content='$content',joindate='$joindate' where id=".$val['id']."");
			showTips("编辑保存成功；","?mp=". $mp ."&action=edit&classid=$classid&page=$page{$urlquery}");
		}
		showErrTips($errmsg);
	}
?>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="tablebk">
<form name="Form1" action="?<?php echo 'mp='.$mp;?>&action=edit&act=editsave&classid=<?php echo $classid;?>&page=<?php echo $page;?><?php echo $urlquery;?>" method="post" target="postpanel">
  <tr>
	<th colspan="2"><h2><?php echo $val['topic'];?> - 编辑</h2></th>
  </tr>
  <tr>
    <td width="18%" height="24" align="right"><strong>添加时间：</strong></td>
    <td><input name="joindate" type="text" class="input" size="30" value="<?php if(!checkint($val[joindate])){echo SYS_DATETIME;}else{echo date("Y-m-d H:i:s",$val[joindate]);}?>">  <img src="images/date.gif" width="24" height="24" align="absmiddle" onClick="CalendarWebControl.show(joindate,true,joindate.value);" style="cursor:pointer;"></td>
  </tr>
  </tr>
  <tr>
    <td height="24" align="right"><strong>信息内容：</strong></td>
    <td style="padding-right:20px;"><?php 
		require '../plugins/editor/fckeditor/fckeditor.php';
		$oFCKeditor = new FCKeditor('content');
		$oFCKeditor->BasePath=EDITOR_BASEPATH;
		$oFCKeditor->Value=$val['content'];
		$oFCKeditor->Create(); 
	?></td>
  </tr>
  <tr>
    <td height="40" colspan="2" align="center">
      <input type="submit" name="Submit" value="提  交"> &nbsp; 
      <input type="reset" name="Submit" value="重  置"></td>
  </tr>
  </form>
</table>
<?php
}
?>