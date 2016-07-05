<?php
/**
 * FCKeditor保存远程图片插件
 * @author slime09(slime09@gmail.com)
 * @license LGPL
 * @version 1.02 2009.4.18
 * @copyright  Copyright (c) 2009,  LinJiong (http://www.cn09.com)
 * 
 */
//说明：由本人多处修改优化,QQ:19519959，E-mail:noerr@vip.qq.com
require_once './config.php';
require_once './ServerXMLHTTP.php';
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style>
<!--
body {
font-size:10pt;
}
.table tr td{ padding:3px 5px;}
-->
</style>
<body leftmargin="10" rightmargin="10">
<SCRIPT LANGUAGE="JavaScript">
  var dialog = window.parent;
  var oEditor = dialog.InnerDialogLoaded();
  var FCKLang = oEditor.FCKLang;
  var xEditor = oEditor.FCK;
  var a = xEditor.GetXHTML();
  dialog.SetOkButton(true);
  function Ok(){return true;}
<?php
	set_time_limit(0);
	$files=$_POST['files'];
	$fileNum=count($files);
	$realFileNum=0;
	$imgArray=array('.gif','.jpg','.png','.jpeg','.bmp');

	$typeArray=array();
	ob_start();
	for($i=0;$i<$fileNum;$i++)
	{
		$type=strrchr(trim($files[$i]),".");
		if($files[$i]!='' && in_array($type,$imgArray))
		{   $suiji=rand(0,100000);
			$now=date("Ymd").$suiji;
			$filename=$now.strrchr(trim($files[$i]),".");
			//$filename=md5_file(trim($files[$i])).strrchr(trim($files[$i]),".");
			$savetime['file'][] = trim($files[$i]);
			$savetime['newfile'][] = $displayUrl.'/'.$filename;
			$savetime['time'][]= SaveHTTPFile(trim($files[$i]),$saveFilesAbsolutePath,$filename);
?>
			a=a.replace("<?=trim($files[$i])?>","<?=$displayUrl.'/'.$filename?>");
<?php
		}
	}
	ob_end_flush();
?>
xEditor.SetHTML(a);
</script>
<div style="color:#900; padding-bottom:6px; font-size:14px; font-weight:bold;">文件已经保存成功,点击“确定”完成图片上传。</div>
<table width="100%" border="0" cellpadding="0" cellspacing="1" bgcolor="#CCCCCC" class="table">
  <tr>
    <td height="24" align="center" bgcolor="#FFFFFF">序号</td>
    <td align="center" bgcolor="#FFFFFF">远程图片</td>
    <td align="center" bgcolor="#FFFFFF">执行时间</td>
    </tr>
  <?php 
  for($i=0;$i<count($savetime['file']);$i++){?>
  <tr>
    <td height="24" align="center" bgcolor="#FFFFFF"><?php echo ($i+1);?></td>
    <td align="left" bgcolor="#FFFFFF" width="500"><div title="<?php echo $savetime['file'][$i];?>" style="width:450px; overflow:hidden;"><?php echo $savetime['file'][$i];?></div></td>
    <td align="center" bgcolor="#FFFFFF"><?php echo $savetime['time'][$i];?></td>
    </tr>
  <?php 
  }?>
</table>
</body>