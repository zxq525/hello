<?php 
require_once("inc.checklogin.php");

if(!checkint($classid)){jsalert("没有找到相关信息，请重试；","back","window");}
$class=$db->fetch_first("select * from {$dbtablepre}ggclass where id =".$classid."");
if(!$class){
	jsalert("没有找到相关信息，请重试；","back","window");
}

$urlquery = "&classid=". urlencode($classid) ."&s_keyword=". urlencode($s_keyword);

$upconfig = array('ext'=>'gif|jpg|swf|png','size'=>1024*500,'savepath'=>$uppath['ggimage'],'addpath'=>'');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 friendlinkal//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-friendlinkal.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>管理</title>
<link href="style/style.css" rel="stylesheet" type="text/css" />
<link href="style/colorbox.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/common.js"></script>
<script type="text/javascript" src="js/colorbox.js"></script>
<script type="text/javascript" src="js/selectdatetime.js"></script>
</head>
<body>
<div class="bodybox" id="bodybox">
<?php
if($action=="add"){
	if($act=="addsave"){
		if(!checkint($sort_order))$errmsg.="@@请正确输入信息排序格式；\\n";
				
		if(!$errmsg){
			if(!$_FILES['linkimg']['name']){$errmsg.="@@请选择上传的链接图片；\\n";}	
		}
		
		if(!$errmsg){
			require(ROOT_PATH.'classes/class.upload.php');
			$f = new upload("linkimg", $upconfig['savepath'], $upconfig['addpath'],$upconfig['ext'], $upconfig['size']);
			if($f->error != 0){
				$errmsg.="@@".$f->errormsg()."；\\n";
			}else{
				$thumbimg = '';
				$linkimg=$f->uploadedfiles[0]['addpath'].$f->uploadedfiles[0]['savename'];
				if($class['thumbnail'])
				{	
					$thumbsize = explode(",", $class['thumbnail']);
					require_once (ROOT_PATH.'classes/class.imgthumb.php');
					$thumb = new image();
					$thumbimg='s_'.$linkimg;
					$thumb->thumb($upconfig['savepath'].$linkimg, $upconfig['savepath'].'s_'.$linkimg, $thumbsize[0], $thumbsize[1], '', $thumbsize[2]);
				}
			}
		}
		if(!$errmsg){
			$db->query("insert into {$dbtablepre}ggimage(classid,linkname,linktype,linkimg,thumbimg,linkurl,infointro,sort_order)values('$classid','$linkname','$linktype','$linkimg','$thumbimg','$linkurl','$infointro','$sort_order')");

			showTips("信息添加成功；","?mp=$mp&action=add".$urlquery);
		}
		showErrTips($errmsg);
	}
?>
<form name="Form1" action="?mp=<?php echo $mp;?>&action=add&act=addsave<?php echo $urlquery;?>" method="post" enctype="multipart/form-data" target="postpanel">
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="tablebk">
  <tr>
    <th colspan="10"><h2><?php echo $class['classname'];?>图片添加</h2></th>
  </tr>
  <tr>
    <td width="28%" height="24" align="right"><strong>信息名称：</strong></td>
    <td><input name="linkname" type="text" class="input" size="50" value="<?php echo $val['linkname'];?>"></td>
  </tr>
  <tr>
    <td height="24" align="right"><strong>文件类型：</strong></td>
    <td><input type="radio" name="linktype" value="0"<?php if($val['linktype']==""){echo(" checked=\"checked\"");}else{echo(setchecked($val['linktype'],"0"));}?>>图片 &nbsp; 
      <input type="radio" name="linktype" value="1"<?php echo setchecked($val['linktype'],"1")?>>Flash</td>
  </tr>
  <tr>
    <td height="24" align="right"><strong>信息图片：</strong></td>
    <td><input name="linkimg" type="file" class="input" value="<?php echo $val['linkimg'];?>" size="30" style="height:22px;" /></td>
  </tr>
  <tr>
    <td height="24" align="right"><strong>链接地址：</strong></td>
    <td><input name="linkurl" type="text" class="input" size="50" value="<?php echo $val['linkurl'];?>"></td>
  </tr>
  <tr>
    <td height="24" align="right"><strong>信息描述：</strong></td>
    <td><input name="infointro" type="text" class="input" size="80" value="<?php echo $val['infointro'];?>"></td>
  </tr>
  <tr>
    <td height="24" align="right"><strong>信息排序：</strong></td>
    <td class="table_trbg02"><input name="sort_order" type="text" class="input" size="10" value="<?php if($sort_order==""){echo(getmaxnum("ggimage"));}else{echo($sort_order);}?>">
数字，越大越前面</td>
  </tr>
  <tr>
    <td height="40" colspan="2" align="center">
    <?php 
		$infocount=$db->result_first("select count(*) from {$dbtablepre}ggimage where classid =".$classid."");
		if($class['isunit']==0 || $infocount<=0){?>
    <input type="submit" name="Submit" value="提 交"> 
      &nbsp;
      <?php
}
?>
      <input type="button" name="Submit" value="返回列表" onclick="parent.closeFrame();reloadmain();" /></td>
  </tr>
</table>
  </form>
<?php
}
if($action=="list"){
?>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="tablebk">
  <tr>
    <th colspan="10"><h2><?php echo $class['classname'];?>图片列表</h2></th>
  </tr>
  <tr>
    <td colspan="9" class="table_trbg01"><table width="100%" border="0" cellspacing="0" cellpadding="0">
    <form name="Form2" method="get" action="?">
	<input type="hidden" name="action" value="list" /><input type="hidden" name="mp" value="<?php echo $mp;?>" />
	<input type="hidden" name="classid" value="<?php echo $classid;?>" />
      <tr>
        <td align="right">      
        模糊查询:<input name="s_keyword" type="text" size="14" class="input" value="<?php echo $s_keyword;?>" />
		<input type="submit" value="搜索" /></td>
      </tr></form>
    </table></td>
  </tr>
<form name="Form1" method="post" action="?mp=<?php echo $mp;?>&action=upsave&page=<?php echo $page;?><?php echo $urlquery;?>" target="postpanel">
  <tr>
    <td height="24" align="center"><strong>ID</strong></td>
    <td align="center"><strong>信息名称</strong></td>
    <td align="center"><strong>文件类型</strong></td>
    <td align="center"><strong>信息排序</strong></td>
    <td align="center"><strong>操作</strong></td>
	<td align="center"><strong>选择</strong></td>
  </tr>
<?php
if($s_keyword!=""){$sql2 .= " and (linkname like '%{$s_keyword}%' or linkurl like '%{$s_keyword}%') ";}

$query = "select * from {$dbtablepre}ggimage where classid=$classid $sql2 order by sort_order desc, id desc";
$num=$db->num_rows($db->query($query));
require_once(ROOT_PATH."classes/class.page.php");
$pagecls = new pagecls($num,15,$page,'?mp='.$mp.'&action=list'.$urlquery);
if($num<=0){echo("<tr><td height=\"30\" align=\"center\" colspan=\"10\" class=\"table_trbg02\">没有任何信息！</td></tr>");}
$query=$db->query("$query LIMIT {$pagecls->startrecord},{$pagecls->pagesize}");
while($rs = $db->fetch_array($query)) {
?>
  <tr>
    <td height="24" align="center"><?php echo $rs["id"];?></td>
    <td align="center"><a href="?mp=<?php echo $mp;?>&action=edit&id=<?php echo $rs["id"].$urlquery;?>" title="编辑信息" class="cboxframe"><?php echo getcutstr($rs["linkname"],40);?></a></td>
    <td align="center"><?php if($rs['linktype']==0){echo "图片";}else{echo "Flash";}?></td>
    <td align="center"><input type="text" name="sort_order[]" size="8" class="input" value="<?php echo $rs["sort_order"];?>"></td>
    <td align="center"><a href="?mp=<?php echo $mp;?>&action=edit&id=<?php echo $rs["id"].$urlquery;?>" title="编辑信息" class="cboxframe">编辑</a></td>
	<td align="center"><input type="checkbox" name="selectid[]" value="<?php echo $rs["id"];?>"><input type="hidden" name="hideid[]" value="<?php echo $rs["id"];?>"></td>
  </tr>
<?php
}
?>
   <tr>
   		<td height="24" colspan="9">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="50%"><?php 
		$infocount=$db->result_first("select count(*) from {$dbtablepre}ggimage where classid =".$classid."");
		if($class['isunit']==0 || $infocount<=0){?>
              <input type="button" name="button" value="新增信息" title="新增信息" alt="?mp=<?php echo $mp;?>&action=add&page=<?php echo $page.$urlquery;?>" class="cboxframe" /><?php }?></td>
            <td align="right">
        <input type="hidden" name="operamode" value="">
		<input type="checkbox" name="chkall" value="on" onClick="checkall(this.form,'selectid[]')" />全选
		<input type="button" value="更新" onclick="javascript:operation('update');" />
		<input type="button" value="删除" onclick="javascript:operation('delete');" />
        	</td>
          </tr>
        </table>
		</td>
    </tr>
  </form>
   <tr>
     <td height="24" colspan="9" align="center"><?php echo $pagecls->pageinfo;?></td>
  </tr>
</table>
<?php
}

if($action=="edit"){
	//读取数据库原有资料
	if(!checkint($id)){jsalert("参数传递出错，请重试；","back","window");}
	$val=$db->fetch_first("select * from {$dbtablepre}ggimage where id =".$id."");
	if(!$val){
		jsalert("没有找到相关信息，请重试；","back","window");
	}

	if($act=="editsave"){
		if(!$linkname){$errmsg.="@@请输入信息名称；\\n";}
		if(!checkint($sort_order))$errmsg.="@@请正确输入信息排序格式；\\n";
		
		$rs=$db->fetch_first("select id from {$dbtablepre}ggimage where linkname='".$linkname."' and id <> $id ");
		if ($rs){
			$errmsg.="@@此信息名称已经被使用，请用其它重试；\\n";
		}
		
		if(!$errmsg){
			if($_FILES['linkimg']['name']){
				require(ROOT_PATH.'classes/class.upload.php');
				$f = new upload("linkimg", $upconfig['savepath'], $upconfig['addpath'],$upconfig['ext'], $upconfig['size']);
				if($f->error != 0){
					$errmsg.="@@".$f->errormsg()."；\\n";
				}else{
					$linkimg=$f->uploadedfiles[0]['addpath'].$f->uploadedfiles[0]['savename'];	
				}
			}
		}
		if(!$errmsg){
			if($linkimg != ""){
				@unlink($upconfig['savepath'].$val['linkimg']);
				@unlink($upconfig['savepath'].$val['thumbimg']);
				if($class['thumbnail'])
				{	
					$thumbsize = explode(",", $class['thumbnail']);
					require_once (ROOT_PATH.'classes/class.imgthumb.php');
					$thumb = new image();
					$thumbimg='s_'.$linkimg;
					$thumb->thumb($upconfig['savepath'].$linkimg, $upconfig['savepath'].'s_'.$linkimg, $thumbsize[0], $thumbsize[1], '', $thumbsize[2]);
				}
			}else{
				$linkimg=$val['linkimg'];
				$thumbimg=$val['thumbimg'];
			}
			$db->query("update {$dbtablepre}ggimage set linkname='$linkname',linktype='$linktype',linkimg='$linkimg',thumbimg='$thumbimg',linkurl='$linkurl',infointro='$infointro',sort_order='$sort_order' where id=".$val['id']."");
			showTips("编辑保存成功；","?mp=$mp&action=edit&id=$id&page=$page$urlquery");
		}
		showErrTips($errmsg);
	}
?>

<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="tablebk">
<form name="Form1" action="?mp=<?php echo $mp;?>&action=edit&act=editsave&id=<?php echo $id;?>&page=<?php echo $page;?><?php echo $urlquery;?>" method="post" enctype="multipart/form-data" target="postpanel">
  <tr>
  	<th colspan="10"><h2><?php echo $class['classname'];?>图片编辑</h2></th>
  </tr>
  <tr>
    <td width="28%" height="24" align="right"><strong>信息名称：</strong></td>
    <td><input name="linkname" type="text" class="input" size="50" value="<?php echo $val['linkname'];?>"></td>
  </tr>
  <tr>
    <td height="24" align="right"><strong>文件类型：</strong></td>
    <td><input type="radio" name="linktype" value="0"<?php if($val['linktype']==""){echo(" checked=\"checked\"");}else{echo(setchecked($val['linktype'],"0"));}?>>图片 &nbsp; 
      <input type="radio" name="linktype" value="1"<?php echo setchecked($val['linktype'],"1")?>>Flash</td>
  </tr>
  <tr>
    <td height="24" align="right"><strong>信息文件：</strong></td>
    <td><input name="linkimg" type="file" class="input" value="<?php echo $val['linkimg'];?>" size="30" style="height:22px; margin-right:50px;" />
    <?php
if(strtolower(substr($val['linkimg'],-3))=="swf")
{
	echo '<span><a href="'.$upconfig['savepath'].$val['linkimg'].'" target="_blank">查看Flash</a></span>';
}else{
    if($val['linkimg']){
		echo '<span><a href="'.$upconfig['savepath'].$val['linkimg'].'" target="_blank"><img src="'.$upconfig['savepath'].$val['linkimg'].'" height="50" /></a></span>';
	}
}
?>    </td>
  </tr>
  <tr>
    <td height="24" align="right"><strong>链接地址：</strong></td>
    <td><input name="linkurl" type="text" class="input" size="50" value="<?php echo $val['linkurl'];?>"></td>
  </tr>
  <tr>
    <td height="24" align="right"><strong>信息描述：</strong></td>
    <td><input name="infointro" type="text" class="input" size="80" value="<?php echo $val['infointro'];?>"></td>
  </tr>
  <tr>
    <td height="24" align="right"><strong>信息排序：</strong></td>
    <td class="table_trbg02"><input name="sort_order" type="text" class="input" size="10" value="<?php if($val['sort_order']==""){echo(getmaxnum("ggimage"));}else{echo($val['sort_order']);}?>">
数字，越大越前面</td>
  </tr>
  <tr>
    <td height="40" colspan="2" align="center"><input type="submit" name="Submit" value="保 存"> 
      &nbsp;
      <input type="button" name="Submit" value="返回列表" onclick="parent.closeFrame();reloadmain();" /></td>
  </tr>
  </form>
</table>
<?php
}
if($action=="upsave"){
	if(!$operamode){jsalert("请选择要执行的操作方式!","this");}
	if($operamode=="update"){
			for ($i = 0; $i < count($hideid); $i++) {
				$db->query("update {$dbtablepre}ggimage SET sort_order={$sort_order[$i]} where id={$hideid[$i]}");
			}
			showTips("信息更新成功；","?mp=$mp&action=list&page=".$page.$urlquery);
	}
	
	if($operamode=="delete"){
		if(!$selectid){jsalert("请选择要删除的信息!","this");}

		$rs=$db->fetch_first("select linkimg from {$dbtablepre}ggimage where id=".intval($selectid[$i])."");
		if($rs){	
			@unlink($upconfig['savepath'].$rs['linkimg']);
			@unlink($upconfig['savepath'].$rs['thumbimg']);
		}
		for ($i = 0; $i < count($selectid); $i++) {
			$db->query("delete from {$dbtablepre}ggimage where id = ".intval($selectid[$i])."");
		}
		showTips("所选信息删除成功；","?mp=$mp&action=list&page=".$page.$urlquery);
	}
}
?>
<?php require('inc.bottom.php');?>
</div>
</body>
</html>