<?php
require 'inc.checkhead.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>管理登录</title>
<link href="style/style.css" rel="stylesheet" type="text/css" />
<link href="style/colorbox.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/common.js"></script>
<script type="text/javascript" src="js/jquery.impromptu.js"></script>
<script type="text/javascript" src="js/colorbox.js"></script>
<script type="text/javascript">
$(document).ready(function(){
		//$.prompt('Example 1');
});
</script>
</head>
<body>
<div class="head_box">
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td valign="top" height="45" width="200">
<img src="images/main_top_logo.gif" />
		</td>
        <td align="center">&nbsp;</td>
        <td width="200">
	<a href="index.php?mp=welcome" target="main">管理首页</a> &nbsp;
	<a href="../" target="_blank">网站首页</a> &nbsp; 
	<a href="javascript:checkclick('确定要退出系统吗？','login.php?action=logout','top');">退出登录</a>
		</td>
	</tr>
</table>
</div>
</body>
</html>