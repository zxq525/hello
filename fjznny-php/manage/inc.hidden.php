<?php
require 'inc.checkhead.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>隐藏菜单按钮</title>
<style type="text/css">
<!--
html {margin:0;padding:0; background-color:#0099CC; height:100%;}
body {margin:0;padding:0; background-color:#0099CC; height:100%;}
.hiddenl{background:url(images/hideleft_arr2.gif) no-repeat left center;}
.hiddenr{background:url(images/hideleft_arr1.gif) no-repeat left center;}

.hiddent{background:url(images/hidetop_arr2.gif) no-repeat center top;}
.hiddenb{background:url(images/hidetop_arr1.gif) no-repeat center top;}
-->
</style>
<script type="text/javascript" src="js/jquery.js"></script>
<script language=javascript>
var isopenLeft=1;
var isopenTop=1;
function HideLeft()
{
	if (isopenLeft)
	{
		$("table td").addClass("hiddenr").removeClass("hiddenl");
		$(window.parent.document).find("#framemain").attr("cols","0,2,*");
		isopenLeft = 0;
	}
	else
	{
		$("table td").addClass("hiddenl").removeClass("hiddenr");
		$(window.parent.document).find("#framemain").attr("cols","170,2,*");
		isopenLeft = 1;
	}
}
function HideTop()
{
	if (isopenTop)
	{
		$("table td").addClass("hiddenb").removeClass("hiddent");
		$(window.parent.document).find("#top_frame").attr("rows","0,2,*");
		isopenTop = 0;
	}
	else
	{
		$("table td").addClass("hiddent").removeClass("hiddenb");
		$(window.parent.document).find("#top_frame").attr("rows","45,2,*");
		isopenTop = 1;
	}
}
</script>
</head>
<body>
<?php if($action=='left'){?>
<table height="100%" width="3" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td class="hiddenl" onClick="HideLeft()" style="cursor:pointer;">&nbsp;</td>
  </tr>
</table>
<?php }?>
<?php if($action=='top'){?>
<table height="3" width="100%"  border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td class="hiddent" onClick="HideTop()" style="cursor:pointer;">&nbsp;</td>
  </tr>
</table>
<?php }?>
</body>
</html>