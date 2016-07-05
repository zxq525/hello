<?php
require_once("inc.checklogin.php");
if($mp){
	if(file_exists($mp.'.php')){
		require $mp.'.php';
	}else{
		require 'not_found.php';
	}
	die();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>管理 - <?php echo $config["config_name"];?></title>
<link href="style/style.css" rel="stylesheet" type="text/css" />
<link href="style/colorbox.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/common.js"></script>
<script type="text/javascript" src="js/colorbox.js"></script>
<style type="text/css">
html,body { margin:0;padding:0; height:100%;overflow:hidden;}  
</style>
<script type="text/javascript">
var timer;
$(document).ready(function(){
		//$("#aaa").html("");
});
</script>
</head>
<body>
<iframe src="index.php?mp=inc.index" style="width:100%; height:100%; border:0" scrolling="no" name="rootframe" id="rootframe"></iframe>
</body>
</html>