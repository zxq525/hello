<?php
require 'inc.checkhead.php';
$upconfig = array('ext'=>'gif|jpg','size'=>1024*500,'savepath'=>$uppath['image'].$uppath['product_category'],'addpath'=>'');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>管理登录</title>
<link href="style/style.css" rel="stylesheet" type="text/css" />
<link href="style/colorbox.css" rel="stylesheet" type="text/css" />
<link href="style/ztree.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/common.js"></script>
<script type="text/javascript" src="js/colorbox.js"></script>
<script type="text/javascript" src="js/ztree.js"></script>
<style type="text/css">
div.left{float:left;width: 320px;}
div.zTreeDemoBackground {width:100%;height:460px;text-align:left;}
ul.ztree {margin-top:10px;border:1px solid #6CC;background: #f0f6e4;width:98%;height:400px;overflow-y:scroll;overflow-x:auto;}
</style>
</head>
<body>
<div class="bodybox" id="bodybox">
<?php
if($action=='list'){
?>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="tablebk">
  <tr>
  	<th colspan="3"><h2>商品分类</h2></th>
  </tr>
  <tr>
    <td class="table_trbg02">分类列表</td>
    <td class="table_trbg02">操作</td>
  </tr>
  <tr>
    <td width="50%" class="table_trbg02">
	<div class="zTreeDemoBackground left">
		<ul id="treeDemo" class="ztree"></ul>
	</div>
    </td>
    <td align="center" valign="top" style="padding-top:20px;">
    <div>
    <input type="button" name="button" value="新增分类" title="新增分类" alt="?<?php echo 'mp='.$mp;?>&action=add" class="cboxframe" style="width:160px;" />
    </div>
    <div id="category_order">
    <input type="button" name="button" value="分类排序" title="分类排序" alt="?<?php echo 'mp='.$mp;?>&action=order" class="cboxframe" style="width:160px;" />
    </div>
    <div id="category_delete">
    <input type="button" name="button" value="删除信息" title="删除信息" alt="?<?php echo 'mp='.$mp;?>&action=delete" class="cboxframe" style="width:160px;" />
    </div>
    <div id="category_edit">
    	
    </div>
    </td>    
  </tr>
</table>
	<SCRIPT type="text/javascript">
		<!--
var setting = {
	data:{
		simpleData: {
			enable: true,
			idKey: "id",
			pIdKey: "pId",
			rootPId: 0
		}
	},
	callback: {
				onClick: onClick
	}
};

var treeNodes =[
<?php
	$category_array = getCategories(0);
	$i=1;
	foreach($category_array as $category){  
        $id=$category['category_id'];
        $pid=$category['parent_id'];
        $name=$category['name'];
        $level=$category['level'];
        $pCheck='';  
        echo "{ id:'".$id."',pId:'".$pid."',name:'[ID:". $id ."] ". $name ."',open:".(( $level < 5 )?"true":"false")  
        .($pCheck==""?"":((($level < 5)?", halfCheck:true":"")))."}";  
          
        if($i!=count($category_array)){
            echo ",";
        }
		$i++;
	}
?>
];

	function onClick(event, treeId, treeNode, clickFlag) {
		if(checkint(treeNode.id)){
				$("#category_edit").html('<input type="button" name="button" value="编辑'+ treeNode.name +'" title="编辑'+ treeNode.name +'" alt="?<?php echo 'mp='.$mp;?>&action=edit&category_id='+ treeNode.id +'" class="cboxframe" style="width:160px;" />');
				parentcb_init('input.cboxframe');
		}
	}

	$(document).ready(function(){
		$.fn.zTree.init($("#treeDemo"), setting, treeNodes);
	});
//-->
</SCRIPT>
<?php
}
if($action=='add'){
	if($act=="addsave"){
		if(!checkint($parent_id)){$errmsg .= '@@请选择所属分类；';}
		if(!$category_name){$errmsg .= '@@请输入分类名称；';}
		if(!checkint($sort_order)){$errmsg .= '@@输入的排序格式不正确；';}
	
		if(!$errmsg && $parent){
			$parent=$db->fetch_first("select * from {$dbtablepre}category where category_id=$parent_id");
			if(!$parent){
				$errmsg .= "@@没有找到所选的所属分类；\\n";
			}
		}
	
		if(!$errmsg && $_FILES['category_image']['name']){
			require(ROOT_PATH.'classes/class.upload.php');
			$f = new upload("category_image", $upconfig['savepath'], $upconfig['addpath'],$upconfig['ext'], $upconfig['size']);
			if($f->error != 0){
				$errmsg .= "@@".$f->errormsg()."；\\n";
			}else{
				$category_image=$uppath['product_category'].$f->uploadedfiles[0]['addpath'].$f->uploadedfiles[0]['savename'];	
			}
		}
	
		if(!$errmsg){
			$db->query("insert into {$dbtablepre}category(image,parent_id,top,`column`,sort_order,status,date_added,date_modified)values('$category_image','$parent_id','0','0','$sort_order','$status','". SYS_DATETIME ."','". SYS_DATETIME ."')");
			$category_id=$db->insert_id();
			$db->query("insert into {$dbtablepre}category_to_store(category_id,store_id)values('$category_id','0')");
			$db->query("insert into {$dbtablepre}category_description(category_id,language_id,name,description,meta_description,meta_keyword)values('$category_id','1','$category_name','$description','$meta_description','$meta_keyword')");
			
			$level=0;
			$query = $db->query("SELECT * FROM `{$dbtablepre}category_path` WHERE category_id = '" . $parent_id . "' ORDER BY `level` ASC");
			while($result = $db->fetch_array($query)) {
				$db->query("insert into {$dbtablepre}category_path(category_id,path_id,level)values('$category_id','". $result['path_id'] ."','$level')");
				$level++;
			}
			$db->query("insert into {$dbtablepre}category_path(category_id,path_id,level)values('$category_id','$category_id','$level')");
			
			showTips('信息添加成功','?mp='. $mp .'&action=add&parent_id='.$parent_id);
		}
		showErrTips($errmsg);
	}
?>
<script src="editor/ckeditor/ckeditor.js"></script>
<script src="editor/ckeditor/ckfinder.js"></script>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="tablebk">
<form name="Form1" action="?<?php echo 'mp='.$mp;?>&action=add&act=addsave" method="post" enctype="multipart/form-data" target="postpanel">
  <tr>
    <td width="15%" align="right">所属分类：</td>
    <td><select name="parent_id">
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
    <td align="right">分类名称：</td>
    <td><input name="category_name" type="text" class="input" size="40" value="<?php echo $val['category_name'];?>"></td>
  </tr>
  <tr>
    <td align="right">分类关键词：</td>
    <td><input name="meta_keyword" type="text" class="input" size="40" value="<?php echo $val['meta_keyword'];?>"></td>
  </tr>
  <tr>
    <td align="right">分类描述：</td>
    <td><input name="meta_description" type="text" class="input" size="70" value="<?php echo $val['meta_description'];?>"></td>
  </tr>
  <tr>
    <td align="right">分类图片：</td>
    <td><input name="category_image" type="file" class="inputfile" size="30"> 格式：<?php echo $upconfig['ext'];?>,文件大小：<?php echo getrealsize($upconfig['size']);?></td>
  </tr>
  <tr>
    <td align="right">分类简介：</td>
    <td><textarea name="description" id="description" cols="40" rows="4" class="textarea ckeditor"><?php echo $val['description'];?></textarea></td>
  </tr>
  <tr>
    <td align="right">分类排序：</td>
    <td><input name="sort_order" type="text" class="input" size="30" value="<?php if(!$val['sort_order']){echo(getmaxnum("category"));}else{echo $val['sort_order'];}?>" /> 越小越前面</td>
  </tr>
  <tr>
    <td align="right">分类状态：</td>
    <td><input type="radio" name="status" value="1"<?php if($status==""){echo(" checked=\"checked\"");}else{echo(setchecked($status,"1"));}?>>
      启用
      &nbsp; 
      <input type="radio" name="status" value="0"<?php echo setchecked($status,"0")?>>
      停用</td>
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
if($action=='edit'){
	if(!checkint($category_id)){jsalert('script','alert("参数传递出错，请重试；");parent.closeFrame();');}
	$val=$db->fetch_first("SELECT * FROM {$dbtablepre}category c LEFT JOIN {$dbtablepre}category_description cd ON (c.category_id = cd.category_id) WHERE c.category_id = '" . $category_id . "' AND cd.language_id = '1' ORDER BY c.sort_order, cd.name ASC");
	if(!$val){
		jsalert('script','alert("没有找到相关信息，请重试；");parent.closeFrame();');
	}
	
	if($act=="delimg"){
		@unlink($uppath['image'].$val["image"]);
		$db->query("update {$dbtablepre}category set image='' where category_id=".$category_id."");
		jsalert("url",'?mp='.$mp.'&action=edit&category_id='.$category_id);
	}
	
	if($act=="editsave"){
		if(!checkint($parent_id)){$errmsg .= '@@请选择所属分类；';}
		if(!$category_name){$errmsg .= '@@请输入分类名称；';}
		if(!checkint($sort_order)){$errmsg .= '@@输入的排序格式不正确；';}
	
		if(!$errmsg && $parent){
			$parent=$db->fetch_first("select * from {$dbtablepre}category where category_id=$parent_id");
			if(!$parent){
				$errmsg .= '@@没有找到所选的所属分类；\\n';
			}
		}
	
		if(!$errmsg && $_FILES['category_image']['name']){
			require(ROOT_PATH.'classes/class.upload.php');
			$f = new upload("category_image", $upconfig['savepath'], $upconfig['addpath'],$upconfig['ext'], $upconfig['size']);
			if($f->error != 0){
				$errmsg .= '@@'.$f->errormsg().'；\\n';
			}else{
				$category_image=$uppath['product_category'].$f->uploadedfiles[0]['addpath'].$f->uploadedfiles[0]['savename'];	
			}
		}
	
		if(!$errmsg){
			if($category_image){
				@unlink($uppath['image'].$val['image']);
			}else{
				$category_image=$val['image'];
			}
			$db->query("update {$dbtablepre}category set image='$category_image',parent_id='$parent_id',sort_order='$sort_order',date_modified='". SYS_DATETIME ."' where category_id=".$category_id."");
			$db->query("update {$dbtablepre}category_description set name='$category_name',description='$description',meta_description='$meta_description',meta_keyword='$meta_keyword' where category_id=".$category_id."");


		// MySQL Hierarchical Data Closure Table Pattern
		$rs=$db->fetch_first("SELECT count(*) FROM `{$dbtablepre}category_path` WHERE path_id = '" . $category_id . "' ORDER BY level ASC");
		if ($rs) {
			$query = $db->query("SELECT * FROM `{$dbtablepre}category_path` WHERE path_id = '" . $category_id . "' ORDER BY level ASC");
			while($category_path = $db->fetch_array($query)) {
				// Delete the path below the current one
				$db->query("DELETE FROM `{$dbtablepre}category_path` WHERE category_id = '" . $category_path['category_id'] . "' AND level < '" . $category_path['level'] . "'");
				
				$path = array();
				
				// Get the nodes new parents
				$query2 = $db->query("SELECT * FROM `{$dbtablepre}category_path` WHERE category_id = '" . $parent_id . "' ORDER BY level ASC");
				while($result = $db->fetch_array($query2)) {
					$path[] = $result['path_id'];
				}
				
				// Get whats left of the nodes current path
				$query2 = $db->query("SELECT * FROM `{$dbtablepre}category_path` WHERE category_id = '" . $category_path['category_id'] . "' ORDER BY level ASC");
				
				while($result = $db->fetch_array($query2)) {
					$path[] = $result['path_id'];
				}
				
				// Combine the paths with a new level
				$level = 0;
				foreach ($path as $path_id) {
					$db->query("REPLACE INTO `{$dbtablepre}category_path` SET category_id = '" . $category_path['category_id'] . "', `path_id` = '" . $path_id . "', level = '" . $level . "'");
					$level++;
				}
			}
		} else {
			// Delete the path below the current one
			$db->query("DELETE FROM `{$dbtablepre}category_path` WHERE category_id = '" . $category_id . "'");
			
			// Fix for records with no paths
			$level = 0;
			
			$query = $db->query("SELECT * FROM `{$dbtablepre}category_path` WHERE category_id = '" . $parent_id . "' ORDER BY level ASC");
			while($result = $db->fetch_array($query)) {
				$db->query("INSERT INTO `{$dbtablepre}category_path` SET category_id = '" . $category_id . "', `path_id` = '" . $result['path_id'] . "', level = '" . $level . "'");
				
				$level++;
			}
			
			$db->query("REPLACE INTO `{$dbtablepre}category_path` SET category_id = '" . $category_id . "', `path_id` = '" . $category_id . "', level = '" . $level . "'");
		}
			
			showTips('编辑保存成功；','?mp='. $mp .'&action=edit&category_id='.$category_id);
		}
		showErrTips($errmsg);
	}
?>
<script src="editor/ckeditor/ckeditor.js"></script>
<script src="editor/ckeditor/ckfinder.js"></script>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="tablebk">
<form name="Form1" action="?<?php echo 'mp='.$mp;?>&action=edit&act=editsave&category_id=<?php echo $category_id;?>" method="post" enctype="multipart/form-data" target="postpanel">
  <tr>
    <td width="15%" align="right">所属分类：</td>
    <td><select name="parent_id">
          <option value="0">顶级分类</option>
    <?php
	$category_array = getCategories(0);
	$i=0;
    foreach($category_array as $category)
	{
		echo "\n";
		echo '<option value="'. $category['category_id'] .'" '. setselected($val['parent_id'],$category['category_id']) .'>'. str_repeat("　",$category['level']-1) . $category['name'] .'</option>';
		$i++;
	}
	?>
</select>
    </td>
  </tr>
  <tr>
    <td align="right">分类名称：</td>
    <td><input name="category_name" type="text" class="input" size="40" value="<?php echo $val['name'];?>"></td>
  </tr>
  <tr>
    <td align="right">分类关键词：</td>
    <td><input name="meta_keyword" type="text" class="input" size="40" value="<?php echo $val['meta_keyword'];?>"></td>
  </tr>
  <tr>
    <td align="right">分类描述：</td>
    <td><input name="meta_description" type="text" class="input" size="70" value="<?php echo $val['meta_description'];?>"></td>
  </tr>
  <tr>
    <td align="right">分类图片：</td>
    <td><input name="category_image" type="file" class="inputfile" size="30"> 格式：<?php echo $upconfig['ext'];?>,文件大小：<?php echo getrealsize($upconfig['size']);?>
<?php
    if($val['image']){
		echo '<span> &nbsp; <a href="'.$uppath['image'].$val['image'].'" target="_blank" class="colorbox"><img src="'.$uppath['image'].$val['image'].'" height="50" /></a></span>';
		echo ' <a href="?mp='.$mp.'&action=edit&act=delimg&category_id='.$category_id.'" target="postpanel">删除图片</a>';
	}
?>
    </td>
  </tr>
  <tr>
    <td align="right">分类简介：</td>
    <td><textarea name="description" id="description" cols="40" rows="4" class="textarea ckeditor"><?php echo $val['description'];?></textarea></td>
  </tr>
  <tr>
    <td align="right">分类排序：</td>
    <td><input name="sort_order" type="text" class="input" size="30" value="<?php if(!$val['sort_order']){echo(getmaxnum("category"));}else{echo $val['sort_order'];}?>" /> 越小越前面</td>
  </tr>
  <tr>
    <td align="right">分类状态：</td>
    <td><input type="radio" name="status" value="1"<?php if($val['status']==""){echo(' checked="checked"');}else{echo(setchecked($val['status'],'1'));}?>>
      启用
      &nbsp; 
      <input type="radio" name="status" value="0"<?php echo setchecked($status,'0')?>>
      停用</td>
  </tr>
  <tr>
    <td height="40" colspan="2" align="center"><input type="submit" name="Submit" value="提交"> 
      &nbsp; 
      <input type="button" name="Submit" value="返回列表" onClick="parent.closeFrame();reloadmain();"></td>
  </tr>
  </form>
</table>
<script type="text/javascript">
	getEditor("description","<?php echo EDITOR_BASEPATH;?>",180);
</script>
<?php
}
if($action=='order'){
?>
<script type="text/javascript" src="js/ztree.excheck.js"></script>
<script type="text/javascript" src="js/ztree.exedit.js"></script>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="tablebk">
  <tr>
  	<th colspan="3"><h2>商品分类</h2></th>
  </tr>
  <tr>
    <td class="table_trbg02">分类列表</td>
    <td class="table_trbg02">操作</td>
  </tr>
  <tr>
    <td width="40%" class="table_trbg02">
	<div class="zTreeDemoBackground left">
		<ul id="treeDemo" class="ztree"></ul>
	</div>
    </td>
    <td valign="top" style="padding-top:20px;">
<div id="category_log">
      <ul id="log" class="log"></ul>
</div>
    </td>    
  </tr>
</table>
	<SCRIPT type="text/javascript">
		<!--
var setting = {
	edit: {
				drag: {
					autoExpandTrigger: true,
					prev: dropPrev,
					inner: dropInner,
					next: dropNext
				},
				enable: true,
				showRemoveBtn: false,
				showRenameBtn: false
			},
	data:{
		simpleData: {
			enable: true,
			idKey: "id",
			pIdKey: "pId",
			rootPId: 0
		}
	},
	callback: {
				beforeDrag: beforeDrag,
				beforeDrop: beforeDrop,
				beforeDragOpen: beforeDragOpen,
				onDrag: onDrag,
				onDrop: onDrop,
				onExpand: onExpand
	}
};

var treeNodes =[
<?php
	$category_array = getCategories(0);
	$i=1;
	foreach($category_array as $category){  
        $id=$category['category_id'];
        $pid=$category['parent_id'];
        $name=$category['name'];
        $level=$category['level'];
        $pCheck='';  
        echo "{ id:'".$id."',pId:'".$pid."',name:'".$name . '-' . $category['category_id'] . '-' . $category['sort_order'] ."',open:".(( $level < 5 )?"true":"false")  
        .($pCheck==""?"":((($level < 5)?", halfCheck:true":""))).",childOuter:false, dropRoot:false}";  
          
        if($i!=count($category_array)){
            echo ",";
        }
		$i++;
	}
?>
];

		function dropPrev(treeId, nodes, targetNode) {
			var pNode = targetNode.getParentNode();
			if (pNode && pNode.dropInner === false) {
				return false;
			} else {
				for (var i=0,l=curDragNodes.length; i<l; i++) {
					var curPNode = curDragNodes[i].getParentNode();
					if (curPNode && curPNode !== targetNode.getParentNode() && curPNode.childOuter === false) {
						return false;
					}
				}
			}
			return true;
		}
		function dropInner(treeId, nodes, targetNode) {
			if (targetNode && targetNode.dropInner === false) {
				return false;
			} else {
				for (var i=0,l=curDragNodes.length; i<l; i++) {
					if (!targetNode && curDragNodes[i].dropRoot === false) {
						return false;
					} else if (curDragNodes[i].parentTId && curDragNodes[i].getParentNode() !== targetNode && curDragNodes[i].getParentNode().childOuter === false) {
						return false;
					}
				}
			}
			return true;
		}
		function dropNext(treeId, nodes, targetNode) {
			var pNode = targetNode.getParentNode();
			if (pNode && pNode.dropInner === false) {
				return false;
			} else {
				for (var i=0,l=curDragNodes.length; i<l; i++) {
					var curPNode = curDragNodes[i].getParentNode();
					if (curPNode && curPNode !== targetNode.getParentNode() && curPNode.childOuter === false) {
						return false;
					}
				}
			}
			return true;
		}

		var log, className = "dark", curDragNodes, autoExpandNode;
		function beforeDrag(treeId, treeNodes) {
			className = (className === "dark" ? "":"dark");
			for (var i=0,l=treeNodes.length; i<l; i++) {
				if (treeNodes[i].drag === false) {
					curDragNodes = null;
					return false;
				} else if (treeNodes[i].parentTId && treeNodes[i].getParentNode().childDrag === false) {
					curDragNodes = null;
					return false;
				}
			}
			curDragNodes = treeNodes;
			return true;
		}
		function beforeDragOpen(treeId, treeNode) {
			autoExpandNode = treeNode;
			return true;
		}
		function beforeDrop(treeId, treeNodes, targetNode, moveType, isCopy) {
			className = (className === "dark" ? "":"dark");
			return true;
		}
		function onDrag(event, treeId, treeNodes) {
			className = (className === "dark" ? "":"dark");
		}
		function onDrop(event, treeId, treeNodes, targetNode, moveType, isCopy) {
			className = (className === "dark" ? "":"dark");
			if(isCopy==null){
				showLog(getTime() + " 取消移动排序");
			}
			if(!isCopy && treeNodes!=null){
					for(var t=0;t<treeNodes.length;t++)
					{
						if(t==0){
							moveData(treeNodes[t].id,targetNode.id);
						}else{
							moveData(treeNodes[t].id,treeNodes[t-1].id);
						}
							showLog(getTime() + " ["+ treeNodes[t].id +":"+ treeNodes[t].name +"]"+ (moveType=='prev'?"向上":"向下") +"移动到 ["+ targetNode.id +":"+ targetNode.name +"]后面");
					}
			}
		}
		
		function moveData(treeNodesId, targetNodeId) {
			$.ajax({url: "result_category.php",
				timeout:28000,
				type:"get",
				dataType:"json",
				data: "action=move&formid="+treeNodesId+"&targetid="+targetNodeId,
				beforeSend:function(){
					
				},
				error:function(){
					alert("排序更新失败");
					window.location.reload();
				},	
				success:function(result){
					if(result.status==1){
						alert(result.errmsg);
					}
		
				}
			});
		}
		
		function onExpand(event, treeId, treeNode) {
			if (treeNode === autoExpandNode) {
				className = (className === "dark" ? "":"dark");
				//showLog("[ "+getTime()+" onExpand ]&nbsp;&nbsp;&nbsp;&nbsp;" + treeNode.name);
			}
		}

		function showLog(str) {
			if (!log) log = $("#log");
			log.append("<li class='"+className+"'>"+str+"</li>");
			if(log.children("li").length > 18) {
				log.get(0).removeChild(log.children("li")[0]);
			}
		}
		function getTime() {
			var now= new Date(),
			h=now.getHours(),
			m=now.getMinutes(),
			s=now.getSeconds(),
			ms=now.getMilliseconds();
			return (new Date().toLocaleString());
		}

	$(document).ready(function(){
		$.fn.zTree.init($("#treeDemo"), setting, treeNodes);
	});
//-->
</SCRIPT>
<?php
}
if($action=='delete'){
?>
<script type="text/javascript" src="js/ztree.excheck.js"></script>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="tablebk">
  <tr>
  	<th colspan="3"><h2>商品分类</h2></th>
  </tr>
  <tr>
    <td class="table_trbg02">分类列表</td>
    <td class="table_trbg02">操作</td>
  </tr>
  <tr>
    <td width="40%" class="table_trbg02">
	<div class="zTreeDemoBackground left">
		<ul id="treeDemo" class="ztree"></ul>
	</div>
    </td>
    <td valign="top" style="padding-top:20px;">
<div id="deleteText"></div>
<input type="hidden" name="array_category_id" class="input" size="80" />
<input type="button" name="button1" value="确定删除" onclick="javascript:if(confirm('确定要删除所选的分类以及所属子分类吗？\n\n注意：执行删除后将不可恢复数据，并且影响产品的归类！')){resultDelete();}" style="display:none;" />
    </td>    
  </tr>
</table>
	<SCRIPT type="text/javascript">
		<!--
var deleteString = "";
var setting = {
			check: {
				enable: true,
				chkboxType: {"Y":"", "N":""}
			},
			view: {
				dblClickExpand: false
			},
	data:{
		simpleData: {
			enable: true,
			idKey: "id",
			pIdKey: "pId",
			rootPId: 0
		}
	},
			callback: {
				beforeClick: beforeClick,
				onCheck: onCheck
			}
};

var treeNodes =[
<?php
	$category_array = getCategories(0);
	$i=1;
	foreach($category_array as $category){  
        $id=$category['category_id'];
        $pid=$category['parent_id'];
        $name=$category['name'];
        $level=$category['level'];
        $pCheck='';  
        echo "{ id:'".$id."',pId:'".$pid."',name:'".$name . '-' . $category['category_id'] . '-' . $category['sort_order'] ."',open:".(( $level < 5 )?"true":"false")  
        .($pCheck==""?"":((($level < 5)?", halfCheck:true":"")))."}";  
          
        if($i!=count($category_array)){
            echo ",";
        }
		$i++;
	}
?>
];

		function beforeClick(treeId, treeNode) {
			//var zTree = $.fn.zTree.getZTreeObj("treeDemo");
			//zTree.checkNode(treeNode, !treeNode.checked, null, true);
			return false;
		}
		
		function onCheck(e, treeId, treeNode) {
			deleteString="";
			var zTree = $.fn.zTree.getZTreeObj("treeDemo"),
			nodes = zTree.getCheckedNodes(true),
			v = "";
			for (var i=0, l=nodes.length; i<l; i++) {
				v += nodes[i].id + ",";
				deleteString+="<div>选择删除["+ nodes[i].id +":"+ nodes[i].name +"]</div>";
			}
			if (v.length > 0 ) v = v.substring(0, v.length-1);
			$("input[name=array_category_id]").val(v);
			if(v!=""){
					$("#deleteText").html(deleteString);
					$("input[name=button1]").show();
			}else{
					$("input[name=button1]").hide();
			}
		}

		function resultDelete() {
			var array_category_id=$("input[name=array_category_id]").val();
			if(array_category_id==''){alert("请勾选要删除的分类.");}
			$.ajax({url: "result_category.php",
				timeout:28000,
				type:"get",
				dataType:"json",
				data: "action=delete&array_category_id="+array_category_id,
				beforeSend:function(){
					
				},
				error:function(){
					alert("删除操作失败");
					window.location.reload();
				},	
				success:function(result){
					if(result.status==1){
						alert(result.errmsg);
					}else{
						alert("删除成功");
						window.location.reload();
					}
				}
			});
		}

	$(document).ready(function(){
		$.fn.zTree.init($("#treeDemo"), setting, treeNodes);
	});
//-->
</SCRIPT>
<?php
}
?>
<?php require('inc.bottom.php');?>
</div>
</body>
</html>