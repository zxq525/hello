<?php
require 'inc.checkhead.php';
if(!checkint($information_category_id)){jsalert('script','alert("参数传递出错，请重试；");parent.closeFrame();');}
$category=$db->fetch_first("select * from {$dbtablepre}information_category where information_category_id =".$information_category_id."");
if(!$category){
	jsalert('script','alert("没有找到相关信息，请重试；");parent.closeFrame();');
}
$urlquery = "&information_category_id=".urlencode($information_category_id)."&s_username=".urlencode($s_username)."&s_truename=".urlencode($s_truename)."";
$upconfig = array('ext'=>'gif|jpg','size'=>1024*500,'savepath'=>$uppath['image'].$uppath['product'],'addpath'=>date('Ymd'));
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
		if(!$title){$errmsg .= '@@请输入信息标题；';}
		if(!checkdatetime($date_added))$errmsg.="@@请选择正确的添加时间；";
	
		if(!$errmsg){
			$bottom=0;
			$sort_order=0;
			$language_id='1';
			$db->query("INSERT INTO `{$dbtablepre}information` (information_category_id,bottom,sort_order,status,date_added)values('$information_category_id','$bottom','$sort_order','$status','$date_added')");
			$information_id=$db->insert_id();
			
			$db->query("INSERT INTO {$dbtablepre}information_description(information_id,language_id,title,source,author,description)values('$information_id','$language_id','$title','$source','$author','$description')");

			$db->query("INSERT INTO {$dbtablepre}information_to_store(information_id,store_id)values('$information_id','0')");
			
			showTips('信息添加成功','?mp='. $mp .'&action=add&information_category_id='.$information_category_id);
		}
		showErrTips($errmsg);
	}
?>
<script src="editor/ckeditor/ckeditor.js"></script>
<script src="editor/ckeditor/ckfinder.js"></script>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="tablebk">
<form name="Form1" action="?<?php echo 'mp='.$mp;?>&action=add&act=addsave&information_category_id=<?php echo $information_category_id;?>" method="post" enctype="multipart/form-data" target="postpanel">
  <tr>
    <td width="18%" align="right">所属分类：</td>
    <td><?php echo $category['name'];?></td>
  </tr>
  <tr>
    <td align="right">信息标题：</td>
    <td><input name="title" type="text" class="input" size="60" value="<?php echo $val['title'];?>"></td>
  </tr>
  <tr>
    <td align="right">信息来源：</td>
    <td><input name="source" type="text" class="input" size="30" value="<?php echo $val['source'];?>"></td>
  </tr>
  <tr>
    <td align="right">信息作者：</td>
    <td><input name="author" type="text" class="input" size="30" value="<?php echo $val['author'];?>"></td>
  </tr>
  <tr>
    <td align="right">信息时间：</td>
    <td><input name="date_added" type="text" class="input" size="30" value="<?php if(!$val['date_added']){echo date('Y-m-d H:i:s');}else{echo $val['date_added'];}?>"></td>
  </tr>
  <tr>
    <td align="right">信息内容：</td>
    <td><textarea name="description" id="description" cols="40" rows="4" class="textarea ckeditor"><?php echo $val['description'];?></textarea></td>
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
<script type="text/javascript">
	getEditor("description",'<?php echo EDITOR_BASEPATH;?>',240)
</script>
<?php
}
if($action=="list"){
?>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="tablebk">
  <tr>
  	<th colspan="8"><h2><?php echo $category['name'];?>列表</h2></th>
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
	$query=$db->query("SELECT * FROM {$dbtablepre}user_group ORDER BY sort_order ASC , user_group_id ASC");
	while($rs = $db->fetch_array($query)){
	?>
    	<option value="<?php echo $rs['user_group_id']?>"<?php echo setselected($s_user_group_id,$rs['user_group_id']);?>><?php echo $rs['name']?></option>
    <?php
	}
	?>
</select>        
账户:<input name="s_username" type="text" size="15" class="input" value="<?php echo $s_username;?>" /> &nbsp; 
姓名:<input name="s_truename" type="text" size="15" class="input" value="<?php echo $s_truename;?>" />
		 						<input type="submit" value="搜索" /></td>
        
      </tr></form>
    </table></td>
  </tr>
<form name="Form1" method="post" action="?<?php echo 'mp='.$mp;?>&action=upsave&page=<?php echo $page;?><?php echo $urlquery;?>" target="postpanel">
  <tr>
    <td align="center"><strong>ID</strong></td>
    <td align="center"><strong>所属分类</strong></td>
    <td align="center"><strong>标题</strong></td>
    <td align="center"><strong>添加日期</strong></td>
    <td align="center"><strong>状态</strong></td>
    <td align="center"><strong>操作</strong></td>
	<td align="center"><strong>选择</strong></td>
  </tr>
<?php
if(checkint($s_user_group_id)){$sql2 .= " and A.user_group_id=$s_user_group_id ";}
if($s_username){$sql2 .= " and A.username like '%{$s_username}%' ";}
if($s_truename){$sql2 .= " and A.truename like '%{$s_truename}%' ";}

require_once(ROOT_PATH."classes/class.page.php");
$query = "SELECT COUNT(*) FROM {$dbtablepre}information i LEFT JOIN {$dbtablepre}information_description id ON (i.information_id = id.information_id) LEFT JOIN {$dbtablepre}information_category ic ON (i.information_category_id = ic.information_category_id) WHERE id.language_id = '1' and i.information_category_id=". $information_category_id ."";
$num=$db->result_first($query);
$query=$db->query_replace($query, "*,ic.name as information_category_name");
$query.=" ORDER BY i.date_added DESC, i.information_id DESC";
$pagecls = new pagecls($num,15,$page,'?mp='.$mp.'&action=list'.$urlquery);
if($num<=0){echo("<tr><td height=\"30\" align=\"center\" colspan=\"10\" class=\"table_trbg02\">没有任何信息！</td></tr>");}
$query=$db->query("$query LIMIT {$pagecls->startrecord},{$pagecls->pagesize}");
while($rs = $db->fetch_array($query)) {
?>
  <tr>
    <td align="center"><?php echo $rs["information_id"];?></td>
    <td align="center"><?php echo $rs["information_category_name"];?></td>
    <td align="left"><a href="?<?php echo 'mp='.$mp;?>&action=edit&information_id=<?php echo $rs["information_id"].$urlquery;?>" title="编辑信息" class="cboxframe"><?php echo $rs["title"];?></a></td>
    <td align="center"><?php echo date('Y-m-d',strtotime($rs['date_added']));?></td>
    <td align="center"><?php if($rs["status"]==1){echo("启用");}else{echo("<span class=\"red\">禁用</span>");}?></td>
    <td align="center"><a href="?<?php echo 'mp='.$mp;?>&action=edit&information_id=<?php echo $rs["information_id"].$urlquery;?>" title="编辑信息" class="cboxframe">编辑</a></td>
	<td align="center"><input type="checkbox" name="selectid[]" value="<?php echo $rs["information_id"];?>"><input type="hidden" name="hideid[]" value="<?php echo $rs["information_id"];?>"></td>
  </tr>
<?php
}
?>
   <tr>
   		<td colspan="10">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="50%"><input type="button" name="button" value="新增信息" title="新增信息" alt="?<?php echo 'mp='.$mp;?>&action=add&information_category_id=<?php echo $information_category_id;?>" class="cboxframe" /></td>
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
	if(!checkint($information_id)){jsalert('script','alert("参数传递出错，请重试；");parent.closeFrame();');}
	$val=$db->fetch_first("SELECT *,ic.name as information_category_name FROM {$dbtablepre}information i LEFT JOIN {$dbtablepre}information_description id ON (i.information_id = id.information_id) LEFT JOIN {$dbtablepre}information_category ic ON (i.information_category_id = ic.information_category_id) WHERE id.language_id = '1' and i.information_id=". $information_id ."");
	if(!$val){
		jsalert('script','alert("没有找到相关信息，请重试；");parent.closeFrame();');
	}
	
	
	if($act=="editsave"){
		if(!$title){$errmsg .= '@@请输入信息标题；';}
		if(!checkdatetime($date_added))$errmsg.="@@请选择正确的添加时间；";
	
		if(!$errmsg){
			$db->query("update {$dbtablepre}information set status='$status',date_added='$date_added' where information_id=".$information_id."");
			$db->query("update {$dbtablepre}information_description set title='$title',source='$source',author='$author',description='$description' where information_id=".$information_id."");

			showTips('编辑保存成功；','?mp='. $mp .'&action=edit&information_id='.$information_id.'&information_category_id='.$information_category_id);
		}
		showErrTips($errmsg);
	}
?>
<script src="editor/ckeditor/ckeditor.js"></script>
<script src="editor/ckeditor/ckfinder.js"></script>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="tablebk">
<form name="Form1" action="?<?php echo 'mp='.$mp;?>&action=edit&act=editsave&information_id=<?php echo $information_id;?>&information_category_id=<?php echo $information_category_id;?>" method="post" enctype="multipart/form-data" target="postpanel">
  <tr>
    <td width="18%" align="right">所属分类：</td>
    <td><?php echo $category['name'];?></td>
  </tr>
  <tr>
    <td align="right">信息标题：</td>
    <td><input name="title" type="text" class="input" size="60" value="<?php echo $val['title'];?>"></td>
  </tr>
  <tr>
    <td align="right">信息来源：</td>
    <td><input name="source" type="text" class="input" size="30" value="<?php echo $val['source'];?>"></td>
  </tr>
  <tr>
    <td align="right">信息作者：</td>
    <td><input name="author" type="text" class="input" size="30" value="<?php echo $val['author'];?>"></td>
  </tr>
  <tr>
    <td align="right">信息时间：</td>
    <td><input name="date_added" type="text" class="input" size="30" value="<?php echo $val['date_added'];?>"></td>
  </tr>
  <tr>
    <td align="right">信息内容：</td>
    <td><textarea name="description" id="description" cols="40" rows="4" class="textarea ckeditor"><?php echo $val['description'];?></textarea></td>
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
			//$rs=$db->fetch_first("select image from {$dbtablepre}information where product_id=".intval($selectid[$i])."");
			//if($rs){
			//	@unlink($uppath['image'].$rs["image"]);
			//}
			$db->query("delete from {$dbtablepre}information where information_id=".intval($selectid[$i])."");
			$db->query("delete from {$dbtablepre}information_description where information_id=".intval($selectid[$i])."");
			$db->query("delete from {$dbtablepre}information_to_store where information_id=".intval($selectid[$i])."");
			$db->query("delete from {$dbtablepre}information_to_layout where information_id=".intval($selectid[$i])."");
		}
		showTips('所选信息删除成功！','?mp='. $mp .'&action=list&page='.$page.$urlquery);
	}
}
?>

<?php require('inc.bottom.php');?>
</div>
</body>
</html>