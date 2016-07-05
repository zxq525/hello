<?php
require 'inc.checkhead.php';
require(ROOT_PATH.'classes/class.file.php');

$path_incfile = ROOT_PATH.'includes/inc.article_class.php'; 
if(file_exists($path_incfile)){
	require($path_incfile);
}

$urlquery = "&s_classname=".urlencode($s_classname)."&s_classid=".urlencode($s_classid);
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
</head>
<body>
<div class="bodybox" id="bodybox">
<?
if($action=="add"){
	if($act=="addsave"){
		if($classname==""){$errmsg=$errmsg."@@请输入分类名称；\\n";}
		$sort_order = (int)$sort_order;
		
		if(!checkint($parentid)){
			$classid=0;
			$parentid=0;
			$classpath="";
			$classdepth=1;
		}else{
			if(!$errmsg){
				$rs=$db->fetch_first("select * from {$dbtablepre}article_class where id=$parentid");
				if(!$rs){
					$errmsg=$errmsg."@@没有找到所选的分类名称；\\n";
				}
			}
			$parentid=$rs[id];
			if($rs[classpath]){
				$classpath=$rs[classpath].",$rs[id]";
			}else{
				$classpath="$rs[id]";
			}
			$classdepth=$rs[classdepth]+1;
		}

		if(!$errmsg){
			$childcount=0;
			$db->query("insert into {$dbtablepre}article_class(classname,parentid,classpath,childcount,classdepth,sort_order)values('$classname',$parentid,'$classpath',$childcount,$classdepth,$sort_order)");
			$insertid=$db->insert_id();
			catalog_cache();
			catalog_update($insertid);
			catalog_upall(0);
			showTips('信息添加成功；','?mp='. $mp .'&action=add&parentid='. $parentid . $urlquery .'');
		}
		showErrTips($errmsg);
	}
?>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="tablebk">
<form name="Form1" action="?<?php echo 'mp='.$mp;?>&action=add&act=addsave<?php echo $urlquery;?>" method="post" enctype="multipart/form-data" target="postpanel">
  <tr>
  	<th colspan="2"><h2>文章分类添加</h2></th>
  </tr>
  <tr>
    <td width="30%" height="24" align="right" class="table_trbg02"><strong>分类名称：</strong></td>
    <td class="table_trbg02"><input name="classname" type="text" class="input" size="50" value="<?php echo $classname;?>"></td>
  </tr>
  <tr>
    <td height="24" align="right" class="table_trbg02"><strong>所属分类：</strong></td>
    <td class="table_trbg02"><select name="parentid">
            <option value="">作为顶级分类</option>
<?=select_catalog($parentid,0,1) ?>
      </select></td>
  </tr>
  <tr>
    <td height="32" align="right" class="table_trbg02"><strong>分类排序：</strong></td>
    <td class="table_trbg02"><input name="sort_order" type="text" class="input" size="10" value="<?php if(checkint($sort_order)){echo $sort_order;}else{echo getmaxnum("article_class");}?>">
      数字，越大越前面</td>
  </tr>
  <tr>
    <td height="40" colspan="2" align="center" class="table_trbg02"><input type="submit" name="Submit" value="提 交"> 
      &nbsp; 
      <input type="button" name="Submit" value="返回列表" onClick="parent.closeFrame();reloadmain();"></td>
  </tr>
  </form>
</table>
<?php
}
if($action=="list"){
	if($act=="del"){
		//读取数据库原有资料
		if(!checkint($id)){jsalert("参数传递出错，请重试；","back","window");}
		$val=$db->fetch_first("select * from {$dbtablepre}article_class where id =".$id."");
		if(!$val){
			jsalert("没有找到相关信息，请重试；","back","window");
		}
		if($val[childidarr]){jsalert("此栏目有下属栏目，请先删除下属栏目；","back","window");}
		$procount=$db->result_first("select count(*) from {$dbtablepre}article where classid =".$id."");
		if($procount > 0){
			jsalert("此栏目含有商品数据，请先删除或转移所属分类的商品数据；","back","window");
		}
		
		$db->query("delete from {$dbtablepre}article_class where id=$id");
		catalog_cache();
		catalog_upall(0);
		jsalert('所选分类删除成功；','?mp='. $mp .'action=list'. $urlquery .'');
	}
?>
<script language="javascript">
function check_thisform(theForm){
	if (theForm.point.value==""){alert("请选择要执行的操作方式；");return false;}
	if(theForm.point.value=="2"){return confirm('确定要删除所选的信息吗？\n\n注意：执行删除后将不可恢复数据！');}
	return true;
}
</script>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="tablebk">
  <tr>
	<th colspan="10"><h2>文章分类列表</h2></th>
  </tr>
  <tr>
    <td colspan="10" class="table_trbg01"><table width="100%" border="0" cellspacing="0" cellpadding="0">
	 <form name="Form2" method="get" action="?">
	 <input type="hidden" name="action" value="list" /><input type="hidden" name="mp" value="<?php echo $mp;?>" />
      <tr>
        <td align="right">
分类名称:<input name="s_classname" type="text" size="15" class="input" value="<?php echo $s_classname;?>" />
		 						<input type="submit" value="搜索" /></td>
      </tr></form>
    </table></td>
  </tr>
<form name="Form1" method="post" action="?<?php echo 'mp='.$mp;?>&action=upsave&page=<?php echo $page;?><?php echo $urlquery;?>" onSubmit="return check_thisform(Form1);" target="postpanel">
  <tr>
    <td height="24" align="center" class="table_trbg02"><strong>ID</strong></td>
    <td align="center" class="table_trbg02"><strong>栏目名称</strong></td>
    <td align="center" class="table_trbg02"><strong>分类排序</strong></td>
    <td align="center" class="table_trbg02"><strong>操作</strong></td>
  </tr>
<?php
	echo catalog(0);
?>
   <tr>
	<td colspan="10">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="50%"><input type="button" name="button" value="新增信息" title="新增信息" alt="?mp=<?php echo $mp;?>&action=add&page=<?php echo $page.$urlquery;?>" class="cboxframe" /></td>
    <td align="right"><input type="submit" name="Submit" value="执 行"></td>
  </tr>
</table>
      </td>
	</tr></form>
   <tr>
     <td height="24" colspan="10" align="center" class="table_trbg02"><?php echo $pagecls->pageinfo;?></td>
   </tr>
</table>
<?php
}

if($action=="edit"){

	//读取数据库原有资料
	if(!checkint($id)){jsalert("参数传递出错，请重试；","back","window");}
	$val=$db->fetch_first("select * from {$dbtablepre}article_class where id =".$id."");
	if(!$val){
		jsalert("没有找到相关信息，请重试；","back","window");
	}
	if($act=="editsave"){
		if($classname==""){$errmsg=$errmsg."@@请输入分类名称；\\n";}
		$sort_order = (int)$sort_order;

		if(!checkint($parentid)){
			$parentid=0;
			$classpath="";
			$classdepth=1;
		}else{
			if(!$errmsg){
				$rs=$db->fetch_first("select * from {$dbtablepre}article_class where id=$parentid");
				if(!$rs){
					$errmsg=$errmsg."@@没有找到所选的分类名称；\\n";
				}else{
					if($rs[id]==$val[id]){
						$errmsg=$errmsg."@@修改的ID和父ID重复；\\n";
					}
				}
			}
			$parentid=$rs[id];
			if($rs[classpath]){
				$classpath=$rs[classpath].",$rs[id]";
			}else{
				$classpath="$rs[id]";
			}
			$classdepth=$rs[classdepth]+1;
		}

		if(!$errmsg){
			if($filepath != $val[classbnr]){
				@unlink($upsavepath.$val[classbnr]);
			}
		
			$childcount=0;	//待开发
			if($parentid != $val[parentid]){
				catalog_upparentid($parentid,$id);
			}
			
			$db->query("update {$dbtablepre}article_class set classname='$classname',parentid=$parentid,classpath='$classpath',childcount=$childcount,classdepth=$classdepth,sort_order=$sort_order where id=$id");
			
			if($classname != $val[classname]){
				$db->query("update {$dbtablepre}article set classname='$classname' where classid=$id");
			}
			
			catalog_cache();
			if($parentid != $val[parentid]){
				catalog_upall(0);
			}
			showTips('编辑保存成功；','?mp='. $mp .'&action=edit&id='. $id . $urlquery .'');
		}
		showErrTips($errmsg);
	}
?>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="tablebk">
<form name="Form1" action="?<?php echo 'mp='.$mp;?>&action=edit&act=editsave&id=<?php echo $id;?>&page=<?php echo $page;?><?php echo $urlquery;?>" enctype="multipart/form-data" method="post" target="postpanel">
  <tr>
	<th colspan="2"><h2>文章分类编辑</h2></th>
  </tr>
  <tr>
    <td width="30%" height="24" align="right" class="table_trbg02"><strong>分类名称：</strong></td>
    <td class="table_trbg02"><input name="classname" type="text" class="input" size="50" value="<?php echo $val[classname];?>"></td>
  </tr>
  <tr>
    <td height="24" align="right" class="table_trbg02"><strong>所属分类：</strong></td>
    <td class="table_trbg02"><select name="parentid">
            <option value="">作为顶级分类</option>
<?=select_catalog($val['parentid'],0,1) ?>
      </select></td>
  </tr>
  <tr>
    <td height="32" align="right" class="table_trbg02"><strong>分类排序：</strong></td>
    <td class="table_trbg02"><input name="sort_order" type="text" class="input" size="10" value="<?php if(checkint($val[sort_order])){echo $val[sort_order];}else{echo getmaxnum("article_class");}?>">
      数字，越大越前面</td>
  </tr>
  <tr>
    <td height="40" colspan="2" align="center" class="table_trbg02">
      <input type="submit" name="Submit" value="提 交"> &nbsp; 
      <input type="button" name="Submit" value="返回列表" onClick="parent.closeFrame();reloadmain();"></td>
  </tr>
  </form>
</table>
<?php
}
if($action=="upsave"){
	for ($i = 0; $i < count($hideid); $i++) {
		$db->query("update {$dbtablepre}article_class Set classname='{$classname[$i]}',sort_order={$sort_order[$i]} where id={$hideid[$i]}");
	}
	catalog_cache();
	showTips('信息更新成功；','?mp='. $mp .'&action=list'.$urlquery);
}

function catalog_cache(){		//更新缓存文件
	global $db,$dbtablepre,$path_incfile;
	$query=$db->query("SELECT * FROM {$dbtablepre}article_class ORDER BY `sort_order` asc , `id` ASC");
	$num_rows = 0;
	while ( $row = @mysql_fetch_object($query) ) {
		$rs_arr[$num_rows] = $row;
		$num_rows++;
	}
	$i = 0;
	foreach( $rs_arr as $row ) {
		$rs[$i] = (array) $row;
		$i++;
	}
	
	for ($i=0;$i<count($rs);$i++){
		$_catalog_array[$rs[$i]['parentid']][$rs[$i]['id']] = $rs[$i];
	}
	
	//var_dump($_catalog_array);
	
	$io=new IO();
	$io->write_file($path_incfile,"<?php\n \$_catalog_array=".da_var_export($_catalog_array).'?>');
}
function da_var_export($input,$f = 1,$t = null) {
	$output = '';
	if(is_array($input)){
		$output .= "array(\r\n";
		foreach($input as $key => $value){
			$output .= $t."\t".da_var_export($key,$f,$t."\t").' => '.da_var_export($value,$f,$t."\t");
			$output .= ",\r\n";
		}
		$output .= $t.')';
	} elseif(is_string($input)){
		$output .= $f ? "'".str_replace(array("\\","'"),array("\\\\","\'"),$input)."'" : "'$input'";
	} elseif(is_int($input) || is_double($input)){
		$output .= "'".(string)$input."'";
	} elseif(is_bool($input)){
		$output .= $input ? 'true' : 'false';
	} else{
		$output .= 'NULL';
	}
	return $output;
}

function catalog($cid =0, $level = 1){
	global $_catalog_array,$menuid,$mp;
	global $page,$urlquery;
	if(isset($_catalog_array[$cid])){
		foreach($_catalog_array[$cid] AS $root=>$cata){
			$t=$level=='1'?"":"├";
			$_catalog.='<tr><td height="24" align="center" class="table_trbg02">'. $cata['id'] .'</td><td align="left" class="table_trbg02"> &nbsp; '.str_repeat('│　', $level-1).'<input type="text" name="classname[]" size="40" class="input" value="'. $cata['classname'] .'" /></td><td align="center" class="table_trbg02">'.str_repeat('&nbsp; &nbsp; &nbsp; &nbsp;', $level-1).'<input type="text" name="sort_order[]" size="8" class="input" value="'. $cata['sort_order'] .'"><input type="hidden" name="hideid[]" value="'. $cata['id'] .'"></td><td align="center" class="table_trbg02"><a href="?mp='. $mp .'&action=add&parentid='. $cata[id] . $urlquery .'" class="cboxframe">添加子栏目</a> | <a href="?mp='. $mp .'&action=edit&id='. $cata[id] .'&page='. $page . $urlquery .'" class="cboxframe">编辑</a>'.($cata[id]!=$gift_classid?' | <a href="?mp='. $mp .'&action=list&act=del&id='. $cata[id] .'&page='. $page . $urlquery .'" onClick="return confirm(\'确定要删除此栏目,删除栏目必须先删除栏目所属的所有信息!\');" target="postpanel">删除</a>':'').'</td></tr>'. chr(10) .catalog($cata['id'], $level+1);
		}
	}
	unset($_catalog_array);
	return $_catalog;
}

function catalog_update($cid){
	global $db,$dbtablepre;
	$val_c=$db->fetch_first("SELECT * FROM {$dbtablepre}article_class where id=$cid");
	$val_f=$db->fetch_first("SELECT * FROM {$dbtablepre}article_class where id=$val_c[parentid]");
	if($val_f){
		if($val_f[childidarr]){
			$db->query("UPDATE {$dbtablepre}article_class set childidarr = concat(childidarr,',$cid') where id=$val_c[parentid]");
		}else{
			$db->query("UPDATE {$dbtablepre}article_class set childidarr = '$cid' where id=$val_c[parentid]");
		}
		catalog_update($val_c[parentid]);
	}else{
		return;
	}
}

function catalog_upall($parentid=0){
	global $db,$dbtablepre;
	$db->query("update {$dbtablepre}article_class set childidarr='' where parentid=$parentid");
	$query=$db->query("SELECT * FROM {$dbtablepre}article_class where parentid=$parentid ORDER BY `sort_order` asc , `id` ASC");	//父级ID是传参的值
	while($rs = $db->fetch_array($query)){
		$val_c=$db->fetch_first("SELECT * FROM {$dbtablepre}article_class where id=$rs[parentid]");	//父级ID的记录
		if($rs[classpath]){
			$query2=$db->query("SELECT * FROM {$dbtablepre}article_class where id in ($rs[classpath]) ORDER BY `sort_order` asc , `id` ASC"); //所有父级
			while($rs2 = $db->fetch_array($query2)){
				if($rs2[childidarr]){
					$db->query("UPDATE {$dbtablepre}article_class set childidarr = concat(childidarr,',$rs[id]') where id = $rs2[id]");
				}else{
					$db->query("UPDATE {$dbtablepre}article_class set childidarr = '$rs[id]' where id = $rs2[id]");
				}
			}
		}else{
			//if($rs[childidarr]){
			//	$db->query("UPDATE {$dbtablepre}article_class set childidarr = concat(childidarr,',$val_c[id]') where id=$rs[parentid]");
			//}else{
			//	$db->query("UPDATE {$dbtablepre}article_class set childidarr = '$val_c[id]' where id=$rs[parentid]");
			//}
		}
		catalog_upall($rs[id]);
	}
}

function catalog_upparentid($new_parentid,$currentid){		//改变为新的父ID
	global $db,$dbtablepre;
	$val_c=$db->fetch_first("SELECT * FROM {$dbtablepre}article_class where id=$new_parentid");	//父级ID的记录
	$val_n=$db->fetch_first("SELECT * FROM {$dbtablepre}article_class where id=$currentid");	//当前ID的记录
	if($val_c[classpath]){
		$db->query("UPDATE {$dbtablepre}article_class set classpath = '$val_c[classpath],$new_parentid' where id=$currentid");
	}else{
		$db->query("UPDATE {$dbtablepre}article_class set classpath = '$new_parentid' where id=$currentid");
	}
	$query2=$db->query("SELECT * FROM {$dbtablepre}article_class where parentid = $currentid ORDER BY `sort_order` asc , `id` ASC"); //所有父级
	while($rs2 = $db->fetch_array($query2)){
		$db->query("UPDATE {$dbtablepre}article_class set classpath = '$val_n[classpath],$currentid' where id=$rs2[id]");
		catalog_upparentid($currentid,$rs2[id]);
	}
}

function select_catalog($currentid="0",$cid="0",$level = 1){
	global $_catalog_array,$menuid;
	if(isset($_catalog_array[$cid])){
		foreach($_catalog_array[$cid] AS $root=>$cata){
			$t=$level=='1'?"":"├ ";
			$c=$level=='1'?"class_s1":"class_s2";
			$selected=($currentid==$cata['id'])?"selected='selected'":"";
			if(empty($cata['url'])){
					$_option.="<option value='{$cata['id']}' class='$c' $selected>".str_repeat("│　", $level-1).$t.$cata['classname']."</option>".select_catalog($currentid,$cata['id'],$level+1);
			}
		}
	}
	unset($_catalog_array);
	return $_option;
}
?>
<?php require('inc.bottom.php');?>
</div>
</body>
</html>