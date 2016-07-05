<?php
require 'inc.checkhead.php';
$pagetitle = "商品信息";
$urlquery = "&s_user_group_id=".urlencode($s_user_group_id)."&s_username=".urlencode($s_username)."&s_truename=".urlencode($s_truename)."";
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
		if(!is_array($array_category_id)){$errmsg .= '@@请选择所属分类；';}
		if(!$product_name){$errmsg .= '@@请输入商品名称；';}
		if(!$product_model){$errmsg .= '@@请输入商品编号；';}
		if(!checkfloat($product_price)){$errmsg .= '@@请输入正确的商品价格；';}
	
		if(!$errmsg){
			foreach ($array_category_id as $category_id) {
			$category=$db->fetch_first("select category_id from {$dbtablepre}category where category_id=$category_id");
				if(!$category){
					$errmsg .= "@@没有找到所选的所属分类；\\n";
					break;
				}
			}
		}

		if(!$errmsg){
			$rs=$db->fetch_first("select product_id from {$dbtablepre}product where model='".$model."'");
			if($rs){
				$errmsg.="@@商品编号已经存在；\\n";
			}
		}
	
		if(!$errmsg && $_FILES['product_image']['name']){
			require(ROOT_PATH.'classes/class.upload.php');
			$f = new upload("product_image", $upconfig['savepath'], $upconfig['addpath'],$upconfig['ext'], $upconfig['size']);
			if($f->error != 0){
				$errmsg .= "@@".$f->errormsg()."；\\n";
			}else{
				$product_image=$uppath['product'].$f->uploadedfiles[0]['addpath'].$f->uploadedfiles[0]['savename'];	
			}
		}
	
		if(!$errmsg){
			$model=$product_model;
			$sku='';
			$isbn='';
			$location='';
			$quantity='999';
			$stock_status_id='1';
			$image=$product_image;
			$manufacturer_id='0';
			$shipping='1';
			$price=$product_price;
			$points='0';
			$tax_class_id='0';
			$date_available=date('Y-m-d');
			$weight='0';
			$weight_class_id='1';
			$length='0';
			$width='0';
			$height='0';
			$length_class_id='1';
			$subtract='0';		//扣减库存
			$minimum='1';
			$sort_order='0';
			$status=$status;
			$date_added=SYS_DATETIME;
			$date_modified=SYS_DATETIME;
			$viewed='0';
			$db->query("INSERT INTO `{$dbtablepre}product` (model,sku,isbn,location,quantity,stock_status_id,image,manufacturer_id,shipping,price,points,tax_class_id,date_available,weight,weight_class_id,length,width,height,length_class_id,subtract,minimum,sort_order,status,date_added,date_modified,viewed)values('$model','$sku','$isbn','$location','$quantity','$stock_status_id','$image','$manufacturer_id','$shipping','$price','$points','$tax_class_id','$date_available','$weight','$weight_class_id','$length','$width','$height','$length_class_id','$subtract','$minimum','$sort_order','$status','$date_added','$date_modified','$viewed')");
			$product_id=$db->insert_id();
			
			$db->query("INSERT INTO {$dbtablepre}product_description(product_id,language_id,name,description,meta_description,meta_keyword,tag)values('$product_id','1','$product_name','$description','$meta_description','$meta_keyword','')");

			$db->query("INSERT INTO {$dbtablepre}product_to_store(product_id,store_id)values('$product_id','0')");
			foreach ($array_category_id as $category_id) {
				$db->query("INSERT INTO {$dbtablepre}product_to_category(product_id,category_id)values('$product_id','$category_id')");
			}
			
			showTips('信息添加成功','?mp='. $mp .'&action=add&category_id='.$category_id);
		}
		showErrTips($errmsg);
	}
?>
<script src="editor/ckeditor/ckeditor.js"></script>
<script src="editor/ckeditor/ckfinder.js"></script>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="tablebk">
<form name="Form1" action="?<?php echo 'mp='.$mp;?>&action=add&act=addsave" method="post" enctype="multipart/form-data" target="postpanel">
  <tr>
    <td width="18%" align="right">所属分类：</td>
    <td><select name="array_category_id[]" size="8" multiple="multiple">
          <option value="0">顶级分类</option>
    <?php
	$category_array = getCategories(0);
	$i=0;
    foreach($category_array as $category)
	{
		echo "\n";
		echo '<option value="'. $category['category_id'] .'" '. setselected($parent_id,$category['category_id']) .'>'. str_repeat("　",$category['level']-1) . $category['name'] .'</option>';
		$i++;
	}
	?>
</select>
    </td>
  </tr>
  <tr>
    <td align="right">商品名称：</td>
    <td><input name="product_name" type="text" class="input" size="30" value="<?php echo $val['product_name'];?>"></td>
  </tr>
  <tr>
    <td align="right">商品编号：</td>
    <td><input name="product_model" type="text" class="input" size="30" value="<?php echo $val['product_model'];?>"></td>
  </tr>
  <tr>
    <td align="right">商品价格：</td>
    <td><input name="product_price" type="text" class="input" size="30" value="<?php echo $val['product_price'];?>"></td>
  </tr>
  <tr>
    <td align="right">商品图片：</td>
    <td><input name="product_image" type="file" class="inputfile" size="30"> 格式：<?php echo $upconfig['ext'];?>,文件大小：<?php echo getrealsize($upconfig['size']);?></td>
  </tr>
  <tr>
    <td align="right">商品介绍：</td>
    <td><textarea name="description" id="description" cols="40" rows="4" class="textarea ckeditor"><?php echo $val['description'];?></textarea></td>
  </tr>
  <tr>
    <td align="right">商品状态：</td>
    <td><input type="radio" name="status" value="1"<?php if($val['status']==''){echo(' checked="checked"');}else{echo(setchecked($val['status'],'1'));}?>>上架 &nbsp; 
      <input type="radio" name="status" value="0"<?php echo setchecked($val['status'],'0')?>>下架</td>
  </tr>
  <tr>
    <td height="40" colspan="2" align="center"><input type="submit" name="Submit" value="提交"> 
      &nbsp; 
      <input type="button" name="Submit" value="返回列表" onClick="parent.closeFrame();reloadmain();"></td>
  </tr>
  </form>
</table>
<script type="text/javascript">
	getEditor("description",'<?php echo EDITOR_BASEPATH;?>',180)
</script>
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
商品编号:<input name="s_username" type="text" size="15" class="input" value="<?php echo $s_username;?>" /> &nbsp; 
ISBN<input name="s_username" type="text" size="15" class="input" value="<?php echo $s_username;?>" /> &nbsp; 
商品名称:<input name="s_truename" type="text" size="15" class="input" value="<?php echo $s_truename;?>" />
		 						<input type="submit" value="搜索" /></td>
        
      </tr></form>
    </table></td>
  </tr>
<form name="Form1" method="post" action="?<?php echo 'mp='.$mp;?>&action=upsave&page=<?php echo $page;?><?php echo $urlquery;?>" target="postpanel">
  <tr>
    <td align="center"><strong>货号</strong></td>
    <td align="center"><strong>所属分类</strong></td>
    <td align="center"><strong>商品名称</strong></td>
    <td align="center"><strong>价格</strong></td>
    <td align="center"><strong>状态</strong></td>
    <td align="center"><strong>操作</strong></td>
	<td align="center"><strong>选择</strong></td>
  </tr>
<?php
if(checkint($s_user_group_id)){$sql2 .= " and A.user_group_id=$s_user_group_id ";}
if($s_username){$sql2 .= " and A.username like '%{$s_username}%' ";}
if($s_truename){$sql2 .= " and A.truename like '%{$s_truename}%' ";}

require_once(ROOT_PATH."classes/class.page.php");
$query = "SELECT COUNT(*) FROM {$dbtablepre}product p LEFT JOIN {$dbtablepre}product_description pd ON (p.product_id = pd.product_id) WHERE pd.language_id = '1'";
$num=$db->result_first($query);
$query=$db->query_replace($query, "*, (SELECT price FROM xh_product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '1' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special");
$query.=" ORDER BY p.sort_order, date_modified DESC, p.product_id DESC";
$pagecls = new pagecls($num,15,$page,'?mp='.$mp.'&action=list'.$urlquery);
if($num<=0){echo("<tr><td height=\"30\" align=\"center\" colspan=\"10\" class=\"table_trbg02\">没有任何信息！</td></tr>");}
$query=$db->query("$query LIMIT {$pagecls->startrecord},{$pagecls->pagesize}");
while($rs = $db->fetch_array($query)) {
	/*
	$special = '';
	$query2 = $db->query("SELECT * FROM {$dbtablepre}product_special WHERE product_id = '" . $rs['product_id'] . "' ORDER BY priority, price");
	while($product_special = $db->fetch_array($query2)) {
		if (($product_special['date_start'] == '0000-00-00' || $product_special['date_start'] < date('Y-m-d')) && ($product_special['date_end'] == '0000-00-00' || $product_special['date_end'] > date('Y-m-d'))) {
			$special = $product_special['price'];
			break;
		}					
	}
	*/
	$category = array();
	$query2 = $db->query("SELECT cd.name as category_name FROM {$dbtablepre}product_to_category p2c LEFT JOIN {$dbtablepre}category_description cd ON p2c.category_id=cd.category_id WHERE p2c.product_id = '" . $rs['product_id'] . "'");
	while($product_category = $db->fetch_array($query2)) {
		$category[] = $product_category['category_name'];
	}
?>
  <tr>
    <td align="center"><?php echo $rs["model"];?></td>
    <td align="center"><?php if(count($category)==1){echo $category[0];}elseif(count($category)>1){echo '<span title="'. implode(',',$category) .'">'.count($category).'个分类</span>';}?></td>
    <td align="left"><a href="?<?php echo 'mp='.$mp;?>&action=edit&product_id=<?php echo $rs["product_id"].$urlquery;?>" title="编辑信息" class="cboxframe"><?php echo $rs["name"];?></a></td>
    <td align="center"><?php if($rs['special']){echo '<span class="green">'.$rs['special'].'</span>';}else{echo $rs['price'];}?></td>
    <td align="center"><?php if($rs["status"]==1){echo("正常");}else{echo("<span class=\"red\">锁定</span>");}?></td>
    <td align="center"><a href="?<?php echo 'mp='.$mp;?>&action=edit&product_id=<?php echo $rs["product_id"].$urlquery;?>" title="编辑信息" class="cboxframe">编辑</a></td>
	<td align="center"><input type="checkbox" name="selectid[]" value="<?php echo $rs["product_id"];?>"><input type="hidden" name="hideid[]" value="<?php echo $rs["product_id"];?>"></td>
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
	if(!checkint($product_id)){jsalert('script','alert("参数传递出错，请重试；");parent.closeFrame();');}
	$val=$db->fetch_first("SELECT * FROM {$dbtablepre}product p LEFT JOIN {$dbtablepre}product_description pd ON (p.product_id = pd.product_id) WHERE pd.language_id = '1' and p.product_id=". $product_id ."");
	if(!$val){
		jsalert('script','alert("没有找到相关信息，请重试；");parent.closeFrame();');
	}
	
	$array_category = array();
	$query=$db->query("SELECT category_id FROM {$dbtablepre}product_to_category WHERE product_id=". $product_id ."");
	while($rs = $db->fetch_array($query)) {
		$array_category[] = 	$rs['category_id'];
	}
	
	if($act=="delimg"){
		@unlink($uppath['image'].$val["image"]);
		$db->query("update {$dbtablepre}product set image='' where product_id=".$product_id."");
		jsalert("url",'?mp='.$mp.'&action=edit&product_id='.$product_id);
	}
	
	if($act=="editsave"){
		if(!is_array($array_category_id)){$errmsg .= '@@请选择所属分类；';}
		if(!$product_name){$errmsg .= '@@请输入商品名称；';}
		if(!$product_model){$errmsg .= '@@请输入商品编号；';}
		if(!checkfloat($product_price)){$errmsg .= '@@请输入正确的商品价格；';}
	
		if(!$errmsg){
			foreach ($array_category_id as $category_id) {
			$category=$db->fetch_first("select category_id from {$dbtablepre}category where category_id=$category_id");
				if(!$category){
					$errmsg .= "@@没有找到所选的所属分类；\\n";
					break;
				}
			}
		}
		
		if(!$errmsg){
			$product=$db->fetch_first("select product_id,model from {$dbtablepre}product where product_id<>$product_id and model='$model'");
			if($product){
				$errmsg .= '@@相同编号的其他产品已经存在,请注意修改的编号是否正确；\\n';
			}
		}
	
		if(!$errmsg && $_FILES['product_image']['name']){
			require(ROOT_PATH.'classes/class.upload.php');
			$f = new upload("product_image", $upconfig['savepath'], $upconfig['addpath'],$upconfig['ext'], $upconfig['size']);
			if($f->error != 0){
				$errmsg .= '@@'.$f->errormsg().'；\\n';
			}else{
				$product_image=$uppath['product'].$f->uploadedfiles[0]['addpath'].$f->uploadedfiles[0]['savename'];	
			}
		}
	
		if(!$errmsg){
			if($product_image){
				@unlink($uppath['image'].$val['image']);
			}else{
				$product_image=$val['image'];
			}
			$db->query("update {$dbtablepre}product set image='$product_image',model='$product_model',price='$product_price',status='$status',date_modified='". SYS_DATETIME ."' where product_id=".$product_id."");
			$db->query("update {$dbtablepre}product_description set name='$product_name',description='$description' where product_id=".$product_id."");

			$db->query("DELETE FROM {$dbtablepre}product_to_category WHERE product_id = '$product_id'");
			foreach ($array_category_id as $category_id) {
				$db->query("INSERT INTO {$dbtablepre}product_to_category(product_id,category_id)values('$product_id','$category_id')");
			}
			
			showTips('编辑保存成功；','?mp='. $mp .'&action=edit&product_id='.$product_id);
		}
		showErrTips($errmsg);
	}
?>
<script src="editor/ckeditor/ckeditor.js"></script>
<script src="editor/ckeditor/ckfinder.js"></script>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="tablebk">
<form name="Form1" action="?<?php echo 'mp='.$mp;?>&action=edit&act=editsave&product_id=<?php echo $product_id;?>" method="post" enctype="multipart/form-data" target="postpanel">
  <tr>
    <td width="18%" align="right">所属分类：</td>
    <td><select name="array_category_id[]" size="8" multiple="multiple">
    <?php
	$category_array = getCategories(0);
	$i=0;
    foreach($category_array as $category)
	{
		echo "\n";
		echo '<option value="'. $category['category_id'] .'" '. (in_array($category['category_id'],$array_category)?' selected="selected"':'') .'>'. str_repeat("　",$category['level']-1) . $category['name'] .'</option>';
		$i++;
	}
	?>
</select>
    </td>
  </tr>
  <tr>
    <td align="right">商品名称：</td>
    <td><input name="product_name" type="text" class="input" size="30" value="<?php echo $val['name'];?>"></td>
  </tr>
  <tr>
    <td align="right">商品编号：</td>
    <td><input name="product_model" type="text" class="input" size="30" value="<?php echo $val['model'];?>"></td>
  </tr>
  <tr>
    <td align="right">商品价格：</td>
    <td><input name="product_price" type="text" class="input" size="30" value="<?php echo $val['price'];?>"></td>
  </tr>
  <tr>
    <td align="right">商品图片：</td>
    <td><input name="product_image" type="file" class="inputfile" size="30"> 格式：<?php echo $upconfig['ext'];?>,文件大小：<?php echo getrealsize($upconfig['size']);?>
<?php
    if($val['image']){
		echo '<span> &nbsp; <a href="'.$uppath['image'].$val['image'].'" target="_blank" class="colorbox"><img src="'.$uppath['image'].$val['image'].'" height="50" /></a></span>';
		echo ' <a href="?mp='.$mp.'&action=edit&act=delimg&product_id='.$product_id.'" target="postpanel">删除图片</a>';
	}
?></td>
  </tr>
  <tr>
    <td align="right">商品介绍：</td>
    <td><textarea name="description" id="description" cols="40" rows="4" class="textarea ckeditor"><?php echo $val['description'];?></textarea></td>
  </tr>
  <tr>
    <td align="right">商品状态：</td>
    <td><input type="radio" name="status" value="1"<?php if($val['status']==''){echo(' checked="checked"');}else{echo(setchecked($val['status'],'1'));}?>>上架 &nbsp; 
      <input type="radio" name="status" value="0"<?php echo setchecked($val['status'],'0')?>>下架</td>
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
			$rs=$db->fetch_first("select image from {$dbtablepre}product where product_id=".intval($selectid[$i])."");
			if($rs){
				@unlink($uppath['image'].$rs["image"]);
			}
			$db->query("delete from {$dbtablepre}product where product_id=".intval($selectid[$i])."");
		}
		showTips('所选信息删除成功！','?mp='. $mp .'&action=list&page='.$page.$urlquery);
	}
}
?>

<?php require('inc.bottom.php');?>
</div>
</body>
</html>