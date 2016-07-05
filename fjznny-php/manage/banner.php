<?php
require 'inc.checkhead.php';
$pagetitle = "图片广告信息";
$urlquery = "&s_user_group_id=".urlencode($s_user_group_id)."&s_banner_title=".urlencode($s_banner_title)."&s_banner_link=".urlencode($s_banner_link)."";
$upconfig = array('ext'=>'gif|jpg|png','size'=>1024*500,'savepath'=>$uppath['image'].$uppath['banner'],'addpath'=>'');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
if($action=='add'){
	if($act=="addsave"){
		if(!checkint($banner_id)){$errmsg .= '@@请选择所属分类；';}
		if(!$banner_title){$errmsg .= '@@请输入商品名称；';}
		if(!checkint($sort_order)){$errmsg .= '@@请正确输入信息排序格式；';}
	
		if(!$errmsg){
			foreach ($array_category_id as $category_id) {
			$category=$db->fetch_first("select banner_id from {$dbtablepre}banner where banner_id=$banner_id");
				if(!$category){
					$errmsg .= "@@没有找到所选的所属分类；\\n";
					break;
				}
			}
		}
	
		if(!$errmsg && $_FILES['banner_image']['name']){
			require(ROOT_PATH.'classes/class.upload.php');
			$f = new upload("banner_image", $upconfig['savepath'], $upconfig['addpath'],$upconfig['ext'], $upconfig['size']);
			if($f->error != 0){
				$errmsg .= "@@".$f->errormsg()."；\\n";
			}else{
				$banner_image=$uppath['banner'].$f->uploadedfiles[0]['addpath'].$f->uploadedfiles[0]['savename'];	
			}
		}
	
		if(!$errmsg){
			$db->query("INSERT INTO `{$dbtablepre}banner_image` (banner_id,link,image,sort_order,status)values('$banner_id','$banner_link','$banner_image','$sort_order','$status')");
			$banner_image_id=$db->insert_id();
			
			$db->query("INSERT INTO {$dbtablepre}banner_image_description(banner_image_id,language_id,banner_id,title)values('$banner_image_id','1','$banner_id','$banner_title')");
			
			showTips('信息添加成功','?mp='. $mp .'&action=add&banner_id='.$banner_id);
		}
		showErrTips($errmsg);
	}
?>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="tablebk">
<form name="Form1" action="?<?php echo 'mp='.$mp;?>&action=add&act=addsave" method="post" enctype="multipart/form-data" target="postpanel">
  <tr>
    <td width="38%" align="right">所属组别：</td>
    <td><select name="banner_id" id="banner_id">
    <option value="">--请选择--</option>
    <?php
	$query=$db->query("SELECT * FROM {$dbtablepre}banner ORDER BY sort_order ASC , banner_id ASC");
	while($rs = $db->fetch_array($query)){
	?>
    	<option value="<?php echo $rs['banner_id']?>"<?php echo setselected($banner_id,$rs['banner_id']);?>><?php echo $rs['name']?></option>
    <?php
	}
	?>
    </select>
    </td>
  </tr>
  <tr>
    <td align="right">图片标题：</td>
    <td><input name="banner_title" type="text" class="input" size="30" value="<?php echo $val['banner_title'];?>"></td>
  </tr>
  <tr>
    <td align="right">图片链接：</td>
    <td><input name="banner_link" type="text" class="input" size="30" value="<?php echo $val['banner_link'];?>"></td>
  </tr>
  <tr>
    <td align="right">图片文件：</td>
    <td><input name="banner_image" type="file" class="inputfile" size="30"> 格式：<?php echo $upconfig['ext'];?>,文件大小：<?php echo getrealsize($upconfig['size']);?></td>
  </tr>
  <tr>
    <td height="24" align="right">信息排序：</td>
    <td><input name="sort_order" type="text" class="input" size="10" value="<?php if($sort_order==""){echo(getmaxnum("banner_image"));}else{echo($sort_order);}?>">
数字，越小越前面</td>
  </tr>
  <tr>
    <td align="right">信息状态：</td>
    <td><input type="radio" name="status" value="1"<?php if($val['status']==''){echo(' checked="checked"');}else{echo(setchecked($val['status'],'1'));}?>>启用 &nbsp; 
      <input type="radio" name="status" value="0"<?php echo setchecked($val['status'],'0')?>>禁用</td>
  </tr>
  <tr>
    <td height="40" colspan="2" align="center"><input type="submit" name="Submit" value="提交"> 
      &nbsp; 
      <input type="button" name="Submit" value="返回列表" onClick="parent.closeFrame();reloadmain();"></td>
  </tr>
  </form>
</table>
<?php
}
if($action=="list"){
?>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="tablebk">
  <tr>
  	<th colspan="8"><h2><?php echo $pagetitle;?>列表</h2></th>
  </tr>
  <tr>
    <td colspan="8" class="table_trbg01">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" id="searchtd">
        <form name="Form2" method="get" action="?">
        <input type="hidden" name="action" value="list" /><input type="hidden" name="mp" value="<?php echo $mp;?>" />
      <tr>
        <td align="right">
<select name="s_user_group_id" id="s_user_group_id">
    <option value="">--所有分类--</option>
    <?php
	$query=$db->query("SELECT * FROM {$dbtablepre}banner ORDER BY sort_order ASC , banner_id ASC");
	while($rs = $db->fetch_array($query)){
	?>
    	<option value="<?php echo $rs['banner_id']?>"<?php echo setselected($s_banner_id,$rs['banner_id']);?>><?php echo $rs['name']?></option>
    <?php
	}
	?>
</select>        
标题:<input name="s_banner_title" type="text" size="15" class="input" value="<?php echo $s_banner_title;?>" /> &nbsp; 
链接:<input name="s_banner_link" type="text" size="15" class="input" value="<?php echo $s_banner_link;?>" />
		 						<input type="submit" value="搜索" /></td>
        
      </tr></form>
    </table></td>
  </tr>
<form name="Form1" method="post" action="?<?php echo 'mp='.$mp;?>&action=upsave&page=<?php echo $page;?><?php echo $urlquery;?>" target="postpanel">
  <tr>
    <td align="center"><strong>ID</strong></td>
    <td align="center"><strong>所属分类</strong></td>
    <td align="center"><strong>标题</strong></td>
    <td align="center"><strong>状态</strong></td>
    <td align="center"><strong>排序</strong></td>
    <td align="center"><strong>操作</strong></td>
	<td align="center"><strong>选择</strong></td>
  </tr>
<?php
if(checkint($s_banner_id)){$sql2 .= " and bi.banner_id=$s_banner_id ";}
if($s_banner_title){$sql2 .= " and bid.title like '%{$s_banner_title}%' ";}
if($s_banner_link){$sql2 .= " and bi.link like '%{$s_banner_link}%' ";}

require_once(ROOT_PATH."classes/class.page.php");
$query = "SELECT COUNT(*) FROM {$dbtablepre}banner_image bi LEFT JOIN {$dbtablepre}banner_image_description bid ON (bi.banner_image_id = bid.banner_image_id) LEFT JOIN {$dbtablepre}banner b ON (b.banner_id = bi.banner_id) WHERE bid.language_id = '1'";
$num=$db->result_first($query);
$query=$db->query_replace($query, "bi.*,bid.title,b.name as banner_group_name");
$query.=" ORDER BY bi.sort_order, bi.banner_image_id DESC";
$pagecls = new pagecls($num,15,$page,'?mp='.$mp.'&action=list'.$urlquery);
if($num<=0){echo("<tr><td height=\"30\" align=\"center\" colspan=\"10\" class=\"table_trbg02\">没有任何信息！</td></tr>");}
$query=$db->query("$query LIMIT {$pagecls->startrecord},{$pagecls->pagesize}");
while($rs = $db->fetch_array($query)) {
?>
  <tr>
    <td align="center"><?php echo $rs["banner_image_id"];?></td>
    <td align="center"><?php echo $rs["banner_group_name"];?></td>
    <td align="left"><a href="?<?php echo 'mp='.$mp;?>&action=edit&banner_image_id=<?php echo $rs["banner_image_id"].$urlquery;?>" title="编辑信息" class="cboxframe"><?php echo $rs["title"];?></a></td>
    <td align="center"><?php if($rs["status"]==1){echo("启用");}else{echo("<span class=\"red\">禁用</span>");}?></td>
    <td align="center"><input type="text" name="sort_order[]" size="8" class="input" value="<?php echo $rs["sort_order"];?>"></td>
    <td align="center"><a href="?<?php echo 'mp='.$mp;?>&action=edit&banner_image_id=<?php echo $rs["banner_image_id"].$urlquery;?>" title="编辑信息" class="cboxframe">编辑</a></td>
	<td align="center"><input type="checkbox" name="selectid[]" value="<?php echo $rs["banner_image_id"];?>"><input type="hidden" name="hideid[]" value="<?php echo $rs["banner_image_id"];?>"></td>
  </tr>
<?php
}
?>
   <tr>
   		<td colspan="10">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="50%"><input type="button" name="button" value="新增信息" title="新增信息" alt="?<?php echo 'mp='.$mp;?>&action=add" class="cboxframe" /></td>
    <td align="right"><input type="hidden" name="operamode" value="">
		<input type="checkbox" name="chkall" value="on" onClick="checkall(this.form,'selectid[]')" />全选
        <input type="button" value="删除" onClick="javascript:operation('delete');" /></td>
  </tr>
</table>

      </td>
  </tr></form>
   <tr>
     <td height="24" colspan="8" align="center"><?php echo $pagecls->pageinfo;?></td>
   </tr>
</table>
<?php
}

if($action=="edit"){
	if(!checkint($banner_image_id)){jsalert('script','alert("参数传递出错，请重试；");parent.closeFrame();');}
	$val=$db->fetch_first("SELECT bi.*,bid.title,b.name as banner_group_name FROM {$dbtablepre}banner_image bi LEFT JOIN {$dbtablepre}banner_image_description bid ON (bi.banner_image_id = bid.banner_image_id) LEFT JOIN {$dbtablepre}banner b ON (b.banner_id = bi.banner_id) WHERE bid.language_id = '1' and bi.banner_image_id=". $banner_image_id ."");
	if(!$val){
		jsalert('script','alert("没有找到相关信息，请重试；");parent.closeFrame();');
	}
	
	if($act=="delimg"){
		@unlink($uppath['image'].$val["image"]);
		$db->query("update {$dbtablepre}banner_image set image='' where banner_image_id=".$banner_image_id."");
		jsalert("url",'?mp='.$mp.'&action=edit&banner_image_id='.$banner_image_id);
	}
	
	if($act=="editsave"){
		if(!checkint($banner_id)){$errmsg .= '@@请选择所属分类；';}
		if(!$banner_title){$errmsg .= '@@请输入商品名称；';}
		if(!checkint($sort_order)){$errmsg .= '@@请正确输入信息排序格式；';}
	
		if(!$errmsg){
			foreach ($array_category_id as $category_id) {
			$category=$db->fetch_first("select banner_id from {$dbtablepre}banner where banner_id=$banner_id");
				if(!$category){
					$errmsg .= "@@没有找到所选的所属分类；\\n";
					break;
				}
			}
		}
	
		if(!$errmsg && $_FILES['banner_image']['name']){
			require(ROOT_PATH.'classes/class.upload.php');
			$f = new upload("banner_image", $upconfig['savepath'], $upconfig['addpath'],$upconfig['ext'], $upconfig['size']);
			if($f->error != 0){
				$errmsg .= '@@'.$f->errormsg().'；\\n';
			}else{
				$banner_image=$uppath['banner'].$f->uploadedfiles[0]['addpath'].$f->uploadedfiles[0]['savename'];	
			}
		}
	
		if(!$errmsg){
			if($banner_image){
				@unlink($uppath['image'].$val['image']);
			}else{
				$banner_image=$val['image'];
			}
			
			$db->query("update {$dbtablepre}banner_image set image='$banner_image',link='$banner_link',sort_order='$sort_order',status='$status' where banner_image_id=".$banner_image_id."");
			$db->query("update {$dbtablepre}banner_image_description set title='$banner_title' where banner_image_id=".$banner_image_id."");
			
			showTips('编辑保存成功；','?mp='. $mp .'&action=edit&banner_image_id='.$banner_image_id);
		}
		showErrTips($errmsg);
	}
?>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="tablebk">
<form name="Form1" action="?<?php echo 'mp='.$mp;?>&action=edit&act=editsave&banner_image_id=<?php echo $banner_image_id;?>" method="post" enctype="multipart/form-data" target="postpanel">
  <tr>
    <td width="32%" align="right">所属组别：</td>
    <td><select name="banner_id" id="banner_id">
    <option value="">--请选择--</option>
    <?php
	$query=$db->query("SELECT * FROM {$dbtablepre}banner ORDER BY sort_order ASC , banner_id ASC");
	while($rs = $db->fetch_array($query)){
	?>
    	<option value="<?php echo $rs['banner_id']?>"<?php echo setselected($val['banner_id'],$rs['banner_id']);?>><?php echo $rs['name']?></option>
    <?php
	}
	?>
    </select>
    </td>
  </tr>
  <tr>
    <td align="right">图片标题：</td>
    <td><input name="banner_title" type="text" class="input" size="30" value="<?php echo $val['title'];?>"></td>
  </tr>
  <tr>
    <td align="right">图片链接：</td>
    <td><input name="banner_link" type="text" class="input" size="30" value="<?php echo $val['link'];?>"></td>
  </tr>
  <tr>
    <td align="right">图片文件：</td>
    <td><input name="banner_image" type="file" class="inputfile" size="30"> 格式：<?php echo $upconfig['ext'];?>,文件大小：<?php echo getrealsize($upconfig['size']);?>
<?php
    if($val['image']){
		echo '<span> &nbsp; <a href="'.$uppath['image'].$val['image'].'" target="_blank" class="colorbox"><img src="'.$uppath['image'].$val['image'].'" height="50" /></a></span>';
		echo ' <a href="?mp='.$mp.'&action=edit&act=delimg&banner_image_id='.$banner_image_id.'" target="postpanel">删除图片</a>';
	}
?></td>
  </tr>
  <tr>
    <td height="24" align="right">信息排序：</td>
    <td><input name="sort_order" type="text" class="input" size="10" value="<?php if($val['sort_order']==""){echo(getmaxnum("banner_image"));}else{echo($val['sort_order']);}?>">
数字，越小越前面</td>
  </tr>
  <tr>
    <td align="right">信息状态：</td>
    <td><input type="radio" name="status" value="1"<?php if($val['status']==''){echo(' checked="checked"');}else{echo(setchecked($val['status'],'1'));}?>>启用 &nbsp; 
      <input type="radio" name="status" value="0"<?php echo setchecked($val['status'],'0')?>>禁用</td>
  </tr>
  <tr>
    <td height="40" colspan="2" align="center">
      <input type="submit" name="Submit" value="提交"> &nbsp; 
      <input type="button" name="Submit" value="返回列表" onClick="parent.closeFrame();reloadmain();"></td>
  </tr>
  </form>
</table>
<?php
}
if($action=="upsave"){

	if(!$operamode){showErrTips('@@请选择要执行的操作方式');}
	if($operamode=="delete"){
		if(!$selectid){showErrTips('@@请选择要删除的信息');}
		for ($i = 0; $i < count($selectid); $i++) {
			$rs=$db->fetch_first("select image from {$dbtablepre}banner_image where banner_image_id=".intval($selectid[$i])."");
			if($rs){
				@unlink($uppath['image'].$rs["image"]);
			}
			$db->query("delete from {$dbtablepre}banner_image where banner_image_id=".intval($selectid[$i])."");
		}
		showTips('所选信息删除成功！','?mp='. $mp .'&action=list&page='.$page.$urlquery);
	}
}
?>

<?php require('inc.bottom.php');?>
</div>
</body>
</html>