<?php
require 'inc.checkhead.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>管理 - <?php echo $config["site_name"];?></title>
<link href="style/style.css" rel="stylesheet" type="text/css" />
<link href="style/thickbox.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/common.js"></script>
<script type="text/javascript" src="js/thickbox.js"></script>
<script type="text/javascript">
$(document).ready(function(){
		//$.prompt('Example 1');
});
</script>
</head>
<?php
echo "<frameset rows=\"45,2,*\" border=0 frameborder=\"yes\" name=\"top_frame\" id=\"top_frame\">\n";
echo "<!-- 顶部框架 -->\n";
echo "  <frame src=\"index.php?mp=inc.head\" frameborder=\"no\" name=\"ads\" scrolling=\"NO\"  marginwidth=\"0\" marginheight=\"0\" noresize=\"noresize\">\n";
echo "        <frame src=\"index.php?mp=inc.hidden&action=top\" scrolling=\"no\" name=\"hidelist\" marginwidth=\"0\" marginheight=\"0\" noresize=\"noresize\">\n";
echo "<frameset rows=\"675\" cols=\"170,2,*\" border=0 name=\"framemain\" id=\"framemain\" framespacing=\"0\">\n";
echo "        <frame src=\"index.php?mp=inc.menu\"  name=\"list\" marginwidth=\"0\" marginheight=\"0\">\n";
echo "        <frame src=\"index.php?mp=inc.hidden&action=left\" scrolling=\"no\" name=\"hidelist\" marginwidth=\"0\" marginheight=\"0\" noresize=\"noresize\">\n";
echo "        <frame src=\"index.php?mp=welcome\" name=\"main\" id=\"main\" scrolling=\"yes\" marginwidth=\"0\" marginheight=\"0\">\n";
echo "</frameset>\n";
echo "</frameset>\n";
echo "<noframes><body>对不起,您的浏览器不支持框架!</body></noframes>";
?>
</html>