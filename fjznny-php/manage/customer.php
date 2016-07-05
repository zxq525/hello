<?php
require 'inc.checkhead.php';
$pagetitle = "团供帐户";
$urlquery = "&s_tg_periods_school_id=".urlencode($s_tg_periods_school_id)."&tg_periods_college_id=".urlencode($tg_periods_college_id)."&s_tg_periods_grade_id=".urlencode($s_tg_periods_grade_id)."&s_tg_periods_profession_id=".urlencode($s_tg_periods_profession_id)."&s_truename=".urlencode($s_truename)."&s_studentno=".urlencode($s_studentno)."&s_loginname=".urlencode($s_loginname)."&s_email=".urlencode($s_email)."&s_telephone=".urlencode($s_telephone)."&s_description=".urlencode($s_description);
if(!user_permission(array($mp=>'list'))){show_no_permission();}
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
if($action=="add"){
	if($act=="addsave"){
		if(!user_permission(array($mp=>'add'))){show_no_permission();}

		if(!checkint($tg_periods_school_id)){$errmsg .= '@@请选择所属大学；';}
		if(!$truename){$errmsg .= '@@请输入姓名；';}
		if(!$studentno){$errmsg .= '@@请输入学号；';}
		if(!$loginname){$errmsg .= '@@请输入帐户；';}
		if(!$loginpwd){$errmsg .= '@@请输入密码；';}
		
		if(!$errmsg){
			$rs=$db->fetch_first("select * from {$dbtablepre}tg_periods_school where tg_periods_id=". (int)$default_periods['tg_periods_id'] ." and tg_periods_school_id=". $tg_periods_school_id ."");
			if (!$rs){
				$errmsg .= '@@选择的大学不存在；';
			}else{
				$tg_periods_school_name = $rs['name'];	
			}
		}

		if(!$errmsg && $tg_periods_college_id){
			$rs=$db->fetch_first("select * from {$dbtablepre}tg_periods_college where tg_periods_id=". (int)$default_periods['tg_periods_id'] ." and tg_periods_school_id=". $tg_periods_school_id ." and tg_periods_college_id=". $tg_periods_college_id ."");
			if (!$rs){
				$errmsg .= '@@选择的学院不存在；';
			}else{
				$tg_periods_college_name = $rs['name'];	
			}
		}
		
		if(!$errmsg && $tg_periods_grade_id){
			$rs=$db->fetch_first("select * from {$dbtablepre}tg_periods_grade where tg_periods_id= ". (int)$default_periods['tg_periods_id'] ." AND tg_periods_school_id=". $tg_periods_school_id ." and tg_periods_college_id=". $tg_periods_college_id ." and tg_periods_grade_id=". $tg_periods_grade_id ."");
			if (!$rs){
				$errmsg .= '@@选择的年级不存在；';
			}else{
				$tg_periods_grade_name = $rs['name'];	
			}
		}
		
		if(!$errmsg && $tg_periods_profession_id){
			$rs=$db->fetch_first("select * from {$dbtablepre}tg_periods_profession where tg_periods_id= ". (int)$default_periods['tg_periods_id'] ." AND tg_periods_school_id=". $tg_periods_school_id ." and tg_periods_college_id=". $tg_periods_college_id ." and tg_periods_grade_id=". $tg_periods_grade_id ." and tg_periods_profession_id=". $tg_periods_profession_id ."");
			if (!$rs){
				$errmsg .= '@@选择的专业不存在；';
			}else{
				$tg_periods_profession_name = $rs['name'];
			}
		}
		
		if(!$errmsg){
			$rs=$db->fetch_first("select * from {$dbtablepre}tg_customer where tg_periods_id= ". (int)$default_periods['tg_periods_id'] ." AND  `loginname`='".$loginname."'");
			if ($rs){
				$errmsg .= '@@此帐户已经存在；';
			}
		}
		
		if(!$errmsg){
		
			$ip=getip();
			$date_added = SYS_DATETIME;
			
			$db->query("insert into {$dbtablepre}tg_customer(tg_periods_id,tg_periods_school_id,tg_periods_school_name,tg_periods_college_id,tg_periods_college_name,tg_periods_grade_id,tg_periods_grade_name,tg_periods_profession_id,tg_periods_profession_name,truename,studentno,telephone,loginname,email,loginpwd,ip,status,date_added)values('". $default_periods['tg_periods_id'] ."','". (int)$tg_periods_school_id ."','$tg_periods_school_name','". (int)$tg_periods_college_id ."','$tg_periods_college_name','". (int)$tg_periods_grade_id ."','$tg_periods_grade_name','". (int)$tg_periods_profession_id ."','$tg_periods_profession_name','$truename','$studentno','$telephone','$loginname','$email','". $loginpwd ."','$ip','$status','$date_added')");
			
			showTips('添加保存成功；','?mp='. $mp .'&action=add&s_tg_periods_school_id='.$tg_periods_school_id);
		}
		showErrTips($errmsg);
	}
?>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="tablebk">
<form name="Form1" action="?<?php echo 'mp='.$mp;?>&action=add&act=addsave&tg_customer_id=<?php echo $tg_customer_id;?>" method="post" target="postpanel">
  <tr>
    <td width="38%" align="right">所属大学：</td>
    <td><select name="tg_periods_school_id" id="tg_periods_school_id" onchange="javascript:getTgPeriodsCollege($('#tg_periods_school_id'),$('#tg_periods_college_id'),$('#tg_periods_school_id').find('option:selected').val(),0);getTgPeriodsGrade($('#tg_periods_college_id'),$('#tg_periods_grade_id'),$('#tg_periods_college_id').find('option:selected').val(),0);getTgPeriodsProfession($('#tg_periods_grade_id'),$('#tg_periods_profession_id'),$('#tg_periods_grade_id').find('option:selected').val(),0);">
    <option value="">--请选择--</option>
    <?php
	$query=$db->query("SELECT * FROM {$dbtablepre}tg_periods_school WHERE tg_periods_id= ". (int)$default_periods['tg_periods_id'] ." ORDER BY tg_periods_school_id ASC");
	while($rs = $db->fetch_array($query)){
	?>
    	<option value="<?php echo $rs['tg_periods_school_id']?>"><?php echo $rs['name']?></option>
    <?php
	}
	?>
    </select></td>
  </tr>
  <tr>
    <td align="right">所属学院：</td>
    <td><select name="tg_periods_college_id" id="tg_periods_college_id" onchange="javascript:getTgPeriodsGrade($('#tg_periods_college_id'),$('#tg_periods_grade_id'),$('#tg_periods_college_id').find('option:selected').val(),0);getTgPeriodsProfession($('#tg_periods_grade_id'),$('#tg_periods_profession_id'),$('#tg_periods_grade_id').find('option:selected').val(),0);">
    <option value="">--请选择--</option>
    </select></td>
  </tr>
  <tr>
    <td align="right">所属年级：</td>
    <td><select name="tg_periods_grade_id" id="tg_periods_grade_id" onchange="javascript:getTgPeriodsProfession($('#tg_periods_grade_id'),$('#tg_periods_profession_id'),$('#tg_periods_grade_id').find('option:selected').val(),0);">
    <option value="">--请选择--</option>
    </select></td>
  </tr>
  <tr>
    <td align="right">所属专业：</td>
    <td><select name="tg_periods_profession_id" id="tg_periods_profession_id">
    <option value="">--请选择--</option>
    </select></td>
  </tr>
  <tr>
    <td align="right">姓名：</td>
    <td><input name="truename" type="text" class="input" size="30" value="<?php echo $data['truename'];?>" /></td>
  </tr>
  <tr>
    <td align="right">学号：</td>
    <td><input name="studentno" type="text" class="input" size="30" value="<?php echo $data['studentno'];?>" /></td>
  </tr>
  <tr>
    <td align="right">电话：</td>
    <td><input name="telephone" type="text" class="input" size="30" value="<?php echo $data['telephone'];?>" /></td>
  </tr>
  <tr>
    <td align="right">帐户：</td>
    <td><input name="loginname" type="text" class="input" size="30" value="<?php echo $data['loginname'];?>" />
      必填</td>
  </tr>
  <tr>
    <td align="right">邮箱：</td>
    <td><input name="email" type="text" class="input" size="30" value="<?php echo $data['email'];?>" />
      订金或定价必须填一个</td>
  </tr>
  <tr>
    <td align="right">密码：</td>
    <td><input name="loginpwd" type="text" class="input" size="30" /> 不修改无需输入</td>
  </tr>
  <tr>
    <td align="right">备注：</td>
    <td><input name="description" type="text" class="input" size="30" value="<?php echo $data['description'];?>" />
      </td>
  </tr>
  <tr>
    <td align="right">状态：</td>
    <td><input type="radio" name="status" value="1"<?php if($val['status']==''){echo(' checked="checked"');}else{echo(setchecked($val['status'],'1'));}?>>正常 &nbsp; 
      <input type="radio" name="status" value="0"<?php echo setchecked($status,'0')?>>锁定</td>
  </tr>
  <tr>
    <td height="40" colspan="2" align="center"><?php if(user_permission(array($mp=>'edit'))){?><input type="submit" name="Submit" value="提 交"> &nbsp; <?php }?>
      <input type="button" name="Submit" value="返回列表" onClick="parent.closeFrame();reloadmain();"></td>
  </tr>
  </form>
</table>
<script type="text/javascript">
getTgPeriodsSchool($('#tg_periods_id'),$('#tg_periods_school_id'),<?php echo (int)$default_periods['tg_periods_id'];?>,<?php echo (int)$s_tg_periods_school_id;?>);
getTgPeriodsCollege($('#tg_periods_school_id'),$('#tg_periods_college_id'),<?php echo (int)$s_tg_periods_school_id;?>,<?php echo (int)$data['tg_periods_college_id'];?>);
</script>
<?php
}
if($action=="list"){
?>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="tablebk">
  <tr>
  	<th colspan="14"><h2><?php echo $pagetitle;?>列表</h2></th>
  </tr>
  <tr>
    <td colspan="14" class="table_trbg01">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" id="searchtd">
        <form name="Form2" method="get" action="?">
        <input type="hidden" name="action" value="list" /><input type="hidden" name="mp" value="<?php echo $mp;?>" />
      <tr>
        <td align="right">
<select name="s_tg_periods_school_id" id="s_tg_periods_school_id" onchange="javascript:getTgPeriodsCollege($('#s_tg_periods_school_id'),$('#s_tg_periods_college_id'),$('#s_tg_periods_school_id').find('option:selected').val(),0);getTgPeriodsProfession($('#s_tg_periods_grade_id'),$('#s_tg_periods_profession_id'),$('#s_tg_periods_grade_id').find('option:selected').val(),0);">
    <option value="">--所有大学--</option>
    <?php
	if($sql_permission_school){$sql_permission_school2 = " AND tg_periods_school_id in ($sql_permission_school) ";}
	$query = $db->query("SELECT * FROM {$dbtablepre}tg_periods_school WHERE tg_periods_id=". $default_periods['tg_periods_id'] ." $sql_permission_school2 ORDER BY tg_periods_school_id ASC");
	while($rs = $db->fetch_array($query)){
	?>
    	<option value="<?php echo $rs['tg_periods_school_id']?>"<?php echo setselected($s_tg_periods_school_id,$rs['tg_periods_school_id']);?>><?php echo $rs['name']?></option>
    <?php
	}
	?>
    </select>
<select name="s_tg_periods_college_id" id="s_tg_periods_college_id" onchange="javascript:getTgPeriodsGrade($('#s_tg_periods_college_id'),$('#s_tg_periods_grade_id'),$('#s_tg_periods_college_id').find('option:selected').val(),0);getTgPeriodsProfession($('#s_tg_periods_grade_id'),$('#s_tg_periods_profession_id'),$('#s_tg_periods_grade_id').find('option:selected').val(),0);">
    <option value="">--所有学院--</option>
    </select>
<select name="s_tg_periods_grade_id" id="s_tg_periods_grade_id" onchange="javascript:getTgPeriodsProfession($('#s_tg_periods_grade_id'),$('#s_tg_periods_profession_id'),$('#s_tg_periods_grade_id').find('option:selected').val(),0);">
    <option value="">--所有年级--</option>
    </select>
<select name="s_tg_periods_profession_id" id="s_tg_periods_profession_id">
    <option value="">--所有专业--</option>
    </select>
	<div style="padding-top:2px;">
姓名:<input name="s_truename" type="text" size="8" class="input" value="<?php echo $s_truename;?>" />
学号:<input name="s_studentno" type="text" size="8" class="input" value="<?php echo $s_studentno;?>" />
帐户:<input name="s_loginname" type="text" size="8" class="input" value="<?php echo $s_loginname;?>" />
邮箱:<input name="s_email" type="text" size="15" class="input" value="<?php echo $s_email;?>" />
电话:<input name="s_telephone" type="text" size="10" class="input" value="<?php echo $s_telephone;?>" />
备注:<input name="s_description" type="text" size="14" class="input" value="<?php echo $s_description;?>" />
<input type="submit" value="搜索" /></div></td>
        
      </tr></form>
    </table></td>
  </tr>
<form name="Form1" method="post" action="?<?php echo 'mp='.$mp;?>&action=upsave&page=<?php echo $page;?><?php echo $urlquery;?>" target="postpanel">
  <tr>
    <td height="24" align="center"><strong>ID</strong></td>
    <td align="center"><strong>所属大学</strong></td>
    <td align="center"><strong>所属学院</strong></td>
    <td align="center"><strong>所属年级</strong></td>
    <td align="center"><strong>所属专业</strong></td>
    <td align="center"><strong>帐户</strong></td>
    <td align="center"><strong>姓名</strong></td>    
    <td align="center"><strong>学号</strong></td>
    <td align="center"><strong>邮箱</strong></td>
    <td align="center"><strong>电话</strong></td>
    <td align="center"><strong>操作</strong></td>
	<td align="center"><strong>选择</strong></td>
  </tr>
<?php
$sql2 = " and A.tg_periods_id=". $default_periods['tg_periods_id'] ." ";
if(checkint($s_tg_periods_school_id)){$sql2 .= " and A.tg_periods_school_id=$s_tg_periods_school_id ";}
if(checkint($s_tg_periods_grade_id)){$sql2 .= " and A.tg_periods_grade_id=$s_tg_periods_grade_id ";}
if(checkint($s_tg_periods_profession_id)){$sql2 .= " and A.tg_periods_profession_id=$s_tg_periods_profession_id ";}
if($s_truename){$sql2 .= " and A.truename like '%{$s_truename}%' ";}
if($s_studentno){$sql2 .= " and A.studentno like '%{$s_studentno}%' ";}
if($s_loginname){$sql2 .= " and A.loginname like '%{$s_loginname}%' ";}
if($s_email){$sql2 .= " and A.email like '%{$s_email}%' ";}
if($s_telephone){$sql2 .= " and A.telephone like '%{$s_telephone}%' ";}
if($s_description){$sql2 .= " and A.description like '%{$s_description}%' ";}
if($sql_permission_school){$sql_permission_school2 = " AND A.tg_periods_school_id in ($sql_permission_school) ";}

$query = "select A.*,B.name as periods_school_name,F.name as periods_college_name,D.name as periods_grade_name,E.name as periods_profession_name from {$dbtablepre}tg_customer as A left join {$dbtablepre}tg_periods_school AS B on A.tg_periods_school_id=B.tg_periods_school_id left join {$dbtablepre}tg_periods_grade AS D on A.tg_periods_grade_id=D.tg_periods_grade_id left join {$dbtablepre}tg_periods_profession AS E on A.tg_periods_profession_id=E.tg_periods_profession_id left join {$dbtablepre}tg_periods_college AS F on A.tg_periods_college_id=F.tg_periods_college_id where 1 $sql2 $sql_permission_school2 order by A.tg_customer_id desc";
$num=$db->num_rows($db->query($query));
require_once(ROOT_PATH."classes/class.page.php");
$pagecls = new pagecls($num,15,$page,'?mp='.$mp.'&action=list'.$urlquery);
if($num<=0){echo("<tr><td height=\"30\" align=\"center\" colspan=\"12\" class=\"table_trbg02\">没有任何信息！</td></tr>");}
$query=$db->query("$query LIMIT {$pagecls->startrecord},{$pagecls->pagesize}");
while($rs = $db->fetch_array($query)) {
?>
  <tr>
    <td height="24" align="center"><?php echo $rs["tg_customer_id"];?></td>
    <td align="center"><?php echo $rs["tg_periods_school_name"];?></td>
    <td align="center"><?php echo $rs["tg_periods_college_name"];?></td>
    <td align="center"><?php echo $rs["tg_periods_grade_name"];?></td>
    <td align="center"><?php echo $rs["tg_periods_profession_name"];?></td>
    <td align="center"><?php echo $rs["loginname"];?></td>
    <td align="center"><a href="?<?php echo 'mp='.$mp;?>&action=edit&tg_customer_id=<?php echo $rs["tg_customer_id"].$urlquery;?>" title="编辑信息" class="cboxframe"><?php echo $rs["truename"];?></a></td>
    <td align="center"><?php echo $rs["studentno"];?></td>
    <td align="center"><?php echo $rs["email"];?></td>
    <td align="center"><?php echo $rs["telephone"];?></td>
    <td align="center"><a href="?<?php echo 'mp='.$mp;?>&action=edit&tg_customer_id=<?php echo $rs["tg_customer_id"].$urlquery;?>" title="编辑信息" class="cboxframe">编辑</a></td>
	<td align="center"><input type="checkbox" name="selectid[]" value="<?php echo $rs["tg_customer_id"];?>"><input type="hidden" name="hideid[]" value="<?php echo $rs["tg_customer_id"];?>"></td>
  </tr>
<?php
}
?>
   <tr>
   		<td colspan="14">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="50%"><?php if(user_permission(array($mp=>'add'))){?><input type="button" name="button" value="新增信息" title="新增信息" alt="?<?php echo 'mp='.$mp;?>&action=add" class="cboxframe" /><?php }?></td>
    <td align="right"><input type="hidden" name="operamode" value="">
		<input type="checkbox" name="chkall" value="on" onClick="checkall(this.form,'selectid[]')" />全选
        <?php if(user_permission(array($mp=>'delete'))){?><input type="button" value="删除" onClick="javascript:operation('delete');" />
		<input type="button" value="删除当前所有" onClick="javascript:if(confirm('确定要删除当前筛选的所有的信息吗？\n\n注意：执行删除后将不可恢复数据！')){operation('delete-all');}" />
		<?php }?></td>
  </tr>
</table>

      </td>
  </tr></form>
   <tr>
     <td height="24" colspan="14" align="center"><?php echo $pagecls->pageinfo;?></td>
   </tr>
</table>
<?php if(checkint($s_tg_periods_school_id)){?>
<script type="text/javascript">
getTgPeriodsCollege($('#s_tg_periods_school_id'),$('#s_tg_periods_college_id'),<?php echo (int)$s_tg_periods_school_id;?>,<?php echo (int)$s_tg_periods_college_id;?>);
getTgPeriodsGrade($('#tg_periods_college_id'),$('#s_tg_periods_grade_id'),<?php echo (int)$s_tg_periods_college_id;?>,<?php echo (int)$s_tg_periods_grade_id;?>);
getTgPeriodsProfession($('#s_tg_periods_grade_id'),$('#s_tg_periods_profession_id'),<?php echo intval($s_tg_periods_grade_id);?>,<?php echo intval($s_tg_periods_profession_id);?>);
</script>
<?php }?>
<?php
}

if($action=="edit"){
	//读取数据库原有资料
	if(!checkint($tg_customer_id)){jsalert('script','alert("参数传递出错，请重试；");parent.closeFrame();');}
	$data=$db->fetch_first("select A.*,B.name as periods_school_name,D.name as periods_grade_name,E.name as periods_profession_name from {$dbtablepre}tg_customer as A left join {$dbtablepre}tg_periods_school AS B on A.tg_periods_school_id=B.tg_periods_school_id left join {$dbtablepre}tg_periods_grade AS D on A.tg_periods_grade_id=D.tg_periods_grade_id left join {$dbtablepre}tg_periods_profession AS E on A.tg_periods_profession_id=E.tg_periods_profession_id where tg_customer_id=".$tg_customer_id."");
	if(!$data){
		jsalert('script','alert("没有找到相关信息，请重试；");parent.closeFrame();');
	}
	
	$school=$db->fetch_first("select tg_periods_school_id from {$dbtablepre}tg_periods_school where tg_periods_id=". (int)$default_periods['tg_periods_id'] ." and name='". addslashes($data['tg_periods_school_name']) ."'");
	if($school){
		$data['tg_periods_school_id'] = $school['tg_periods_school_id'];
	}else{
		$data['tg_periods_school_id'] = 0;	
	}
	
	$college=$db->fetch_first("select tg_periods_college_id from {$dbtablepre}tg_periods_college where tg_periods_id=". (int)$default_periods['tg_periods_id'] ." and name='". addslashes($data['tg_periods_college_name']) ."' AND tg_periods_school_id=". (int)$data['tg_periods_school_id'] ."");
	if($college){
		$data['tg_periods_college_id'] = $college['tg_periods_college_id'];
	}else{
		$data['tg_periods_college_id'] = 0;	
	}
	
	$grade=$db->fetch_first("select tg_periods_grade_id from {$dbtablepre}tg_periods_grade where tg_periods_id=". (int)$default_periods['tg_periods_id'] ." and name='". addslashes($data['tg_periods_grade_name']) ."' AND tg_periods_school_id=". (int)$data['tg_periods_school_id'] ." AND tg_periods_college_id=". (int)$data['tg_periods_college_id'] ."");
	if($grade){
		$data['tg_periods_grade_id'] = $grade['tg_periods_grade_id'];
	}else{
		$data['tg_periods_grade_id'] = 0;	
	}
	
	$profession=$db->fetch_first("select tg_periods_profession_id from {$dbtablepre}tg_periods_profession where tg_periods_id=". (int)$default_periods['tg_periods_id'] ." and name='". addslashes($data['tg_periods_profession_name']) ."' AND tg_periods_school_id=". (int)$data['tg_periods_school_id'] ." AND tg_periods_college_id=". (int)$data['tg_periods_college_id'] ." AND tg_periods_grade_id=". (int)$data['tg_periods_grade_id'] ."");
	if($profession){
		$data['tg_periods_profession_id'] = $profession['tg_periods_profession_id'];
	}else{
		$data['tg_periods_profession_id'] = 0;	
	}
	

	if($act=="editsave"){
		if(!user_permission(array($mp=>'edit'))){show_no_permission();}

		if(!checkint($tg_periods_school_id)){$errmsg .= '@@请选择所属大学；';}
		if(!checkint($tg_periods_college_id)){$errmsg .= '@@请选择所属学院；';}		
		if(!checkint($tg_periods_grade_id)){$errmsg .= '@@请选择所属年级；';}
		if(!checkint($tg_periods_profession_id)){$errmsg .= '@@请选择所属专业；';}
		if(!$truename){$errmsg .= '@@请输入姓名；';}
		if(!$studentno){$errmsg .= '@@请输入学号；';}
		
		if(!$errmsg){
			$rs=$db->fetch_first("select * from {$dbtablepre}tg_periods_school where tg_periods_id=". (int)$data['tg_periods_id'] ." and tg_periods_school_id=". $tg_periods_school_id ."");
			if (!$rs){
				$errmsg .= '@@选择的大学不存在；';
			}else{
				$tg_periods_school_name = $rs['name'];
			}
		}

		if(!$errmsg){
			$rs=$db->fetch_first("select * from {$dbtablepre}tg_periods_college where tg_periods_id=". (int)$data['tg_periods_id'] ." and tg_periods_school_id=". $tg_periods_school_id ." and tg_periods_college_id=". $tg_periods_college_id ."");
			if (!$rs){
				$errmsg .= '@@选择的学院不存在；';
			}else{
				$tg_periods_college_name = $rs['name'];
			}
		}
		
		if(!$errmsg){
			$rs=$db->fetch_first("select * from {$dbtablepre}tg_periods_grade where tg_periods_id= ". (int)$data['tg_periods_id'] ." AND tg_periods_school_id=". $tg_periods_school_id ." and tg_periods_college_id=". $tg_periods_college_id ." and tg_periods_grade_id=". $tg_periods_grade_id ."");
			if (!$rs){
				$errmsg .= '@@选择的年级不存在；';
			}else{
				$tg_periods_grade_name = $rs['name'];
			}
		}
		
		if(!$errmsg){
			$rs=$db->fetch_first("select * from {$dbtablepre}tg_periods_profession where tg_periods_id= ". (int)$data['tg_periods_id'] ." AND tg_periods_school_id=". $tg_periods_school_id ." and tg_periods_college_id=". $tg_periods_college_id ." and tg_periods_grade_id=". $tg_periods_grade_id ." and tg_periods_profession_id=". $tg_periods_profession_id ."");
			if (!$rs){
				$errmsg .= '@@选择的专业不存在；';
			}else{
				$tg_periods_profession_name = $rs['name'];
			}
		}
		
		if(!$errmsg){
			$rs=$db->fetch_first("select * from {$dbtablepre}tg_customer where tg_periods_id= ". (int)$data['tg_periods_id'] ." AND tg_periods_school_id=". $tg_periods_school_id ." and tg_periods_college_id=". $tg_periods_college_id ." and tg_periods_grade_id=". $tg_periods_grade_id ." and tg_periods_profession_id=". $tg_periods_profession_id ." AND `loginname`='".$loginname."' AND tg_customer_id<>$tg_customer_id");
			if ($rs){
				$errmsg .= '@@此帐户已经存在；';
			}
		}
		
		if(!$errmsg){
			$sql2 = '';
			if($loginpwd){
				$sql2=" ,loginpwd='".$loginpwd."' ";
			}
			$db->query("update {$dbtablepre}tg_customer set tg_periods_school_id='$tg_periods_school_id',tg_periods_school_name='$tg_periods_school_name',tg_periods_college_id='$tg_periods_college_id',tg_periods_college_name='$tg_periods_college_name',tg_periods_grade_id='$tg_periods_grade_id',tg_periods_grade_name='$tg_periods_grade_name',tg_periods_profession_id='$tg_periods_profession_id',tg_periods_profession_name='$tg_periods_profession_name',`truename`='$truename',`studentno`='$studentno', telephone='$telephone', loginname='$loginname', email='$email', status='$status', description='$description' $sql2 where tg_customer_id=$tg_customer_id");
			
			showTips('编辑保存成功；','?mp='. $mp .'&action=edit&tg_customer_id='.$tg_customer_id);
		}
		showErrTips($errmsg);
	}
?>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="tablebk">
<form name="Form1" action="?<?php echo 'mp='.$mp;?>&action=edit&act=editsave&tg_customer_id=<?php echo $tg_customer_id;?>" method="post" target="postpanel">
  <tr>
    <td align="right" width="38%">注册日期：</td>
    <td><?php echo $data['date_added'];?></td>
  </tr>
  <tr>
    <td align="right">注册 I P：</td>
    <td><?php echo $data['ip'];?></td>
  </tr>
  <tr>
    <td align="right">所属大学：</td>
    <td><select name="tg_periods_school_id" id="tg_periods_school_id" onchange="javascript:getTgPeriodsCollege($('#tg_periods_school_id'),$('#tg_periods_college_id'),$('#tg_periods_school_id').find('option:selected').val(),0);getTgPeriodsGrade($('#tg_periods_college_id'),$('#tg_periods_grade_id'),$('#tg_periods_college_id').find('option:selected').val(),0);getTgPeriodsProfession($('#tg_periods_grade_id'),$('#tg_periods_profession_id'),$('#tg_periods_grade_id').find('option:selected').val(),0);">
    <option value="">--请选择--</option>
    <?php
	$query=$db->query("SELECT * FROM {$dbtablepre}tg_periods_school WHERE tg_periods_id= ". (int)$data['tg_periods_id'] ." ORDER BY tg_periods_school_id ASC");
	while($rs = $db->fetch_array($query)){
	?>
    	<option value="<?php echo $rs['tg_periods_school_id']?>"<?php echo setselected($data['tg_periods_school_id'],$rs['tg_periods_school_id']);?>><?php echo $rs['name']?></option>
    <?php
	}
	?>
    </select></td>
  </tr>
  <tr>
    <td align="right">所属学院：</td>
    <td><select name="tg_periods_college_id" id="tg_periods_college_id" onchange="javascript:getTgPeriodsGrade($('#tg_periods_college_id'),$('#tg_periods_grade_id'),$('#tg_periods_college_id').find('option:selected').val(),0);getTgPeriodsProfession($('#tg_periods_grade_id'),$('#tg_periods_profession_id'),$('#tg_periods_grade_id').find('option:selected').val(),0);">
    <option value="">--请选择--</option>
    </select></td>
  </tr>
  <tr>
    <td align="right">所属年级：</td>
    <td><select name="tg_periods_grade_id" id="tg_periods_grade_id" onchange="javascript:getTgPeriodsProfession($('#tg_periods_grade_id'),$('#tg_periods_profession_id'),$('#tg_periods_grade_id').find('option:selected').val(),0);">
    <option value="">--请选择--</option>
    </select></td>
  </tr>
  <tr>
    <td align="right">所属专业：</td>
    <td><select name="tg_periods_profession_id" id="tg_periods_profession_id">
    <option value="">--请选择--</option>
    </select></td>
  </tr>
  <tr>
    <td align="right">姓名：</td>
    <td><input name="truename" type="text" class="input" size="30" value="<?php echo $data['truename'];?>" /></td>
  </tr>
  <tr>
    <td align="right">学号：</td>
    <td><input name="studentno" type="text" class="input" size="30" value="<?php echo $data['studentno'];?>" /></td>
  </tr>
  <tr>
    <td align="right">电话：</td>
    <td><input name="telephone" type="text" class="input" size="30" value="<?php echo $data['telephone'];?>" /></td>
  </tr>
  <tr>
    <td align="right">帐户：</td>
    <td><input name="loginname" type="text" class="input" size="30" value="<?php echo $data['loginname'];?>" />
      必填</td>
  </tr>
  <tr>
    <td align="right">邮箱：</td>
    <td><input name="email" type="text" class="input" size="30" value="<?php echo $data['email'];?>" />
      订金或定价必须填一个</td>
  </tr>
  <tr>
    <td align="right">密码：</td>
    <td><input name="loginpwd" type="text" class="input" size="30" /> 不修改无需输入</td>
  </tr>
  <tr>
    <td align="right">备注：</td>
    <td><input name="description" type="text" class="input" size="30" value="<?php echo $data['description'];?>" />
      </td>
  </tr>
  <tr>
    <td align="right">状态：</td>
    <td><input type="radio" name="status" value="1"<?php if($val['status']==''){echo(' checked="checked"');}else{echo(setchecked($val['status'],'1'));}?>>正常 &nbsp; 
      <input type="radio" name="status" value="0"<?php echo setchecked($status,'0')?>>锁定</td>
  </tr>
  <tr>
    <td height="40" colspan="2" align="center"><?php if(user_permission(array($mp=>'edit'))){?><input type="submit" name="Submit" value="提 交"> &nbsp; <?php }?>
      <input type="button" name="Submit" value="返回列表" onClick="parent.closeFrame();reloadmain();"></td>
  </tr>
  </form>
</table>
<?php if(checkint($data['tg_periods_school_id'])){?>
<script type="text/javascript">
getTgPeriodsSchool($('#tg_periods_id'),$('#tg_periods_school_id'),<?php echo (int)$data['tg_periods_id'];?>,<?php echo (int)$data['tg_periods_school_id'];?>);
getTgPeriodsCollege($('#tg_periods_school_id'),$('#tg_periods_college_id'),<?php echo (int)$data['tg_periods_school_id'];?>,<?php echo (int)$data['tg_periods_college_id'];?>);
getTgPeriodsGrade($('#tg_periods_college_id'),$('#tg_periods_grade_id'),<?php echo (int)$data['tg_periods_college_id'];?>,<?php echo (int)$data['tg_periods_grade_id'];?>);
getTgPeriodsProfession($('#tg_periods_grade_id'),$('#tg_periods_profession_id'),<?php echo (int)$data['tg_periods_grade_id'];?>,<?php echo (int)$data['tg_periods_profession_id'];?>);
</script>
<?php }?>
<?php
}
if($action=="upsave"){

	if(!$operamode){showErrTips('@@请选择要执行的操作方式');}
	if($operamode=="delete"){
		if(!user_permission(array($mp=>'delete'))){show_no_permission();}
		if(!$selectid){showErrTips('@@请选择要删除的信息');}
		for ($i = 0; $i < count($selectid); $i++) {
			$db->query("delete from {$dbtablepre}tg_customer where tg_customer_id=".intval($selectid[$i])."");
		}
		showTips('所选信息删除成功！','?mp='. $mp .'&action=list&page='.$page.$urlquery);
	}
	
	
	if($operamode=="delete-all"){
		if(!user_permission(array($mp=>'delete'))){show_no_permission();}
		$sql2 = " and A.tg_periods_id=". $default_periods['tg_periods_id'] ." ";
		if(checkint($s_tg_periods_school_id)){$sql2 .= " and A.tg_periods_school_id=$s_tg_periods_school_id ";}
		if(checkint($s_tg_periods_grade_id)){$sql2 .= " and A.tg_periods_grade_id=$s_tg_periods_grade_id ";}
		if(checkint($s_tg_periods_profession_id)){$sql2 .= " and A.tg_periods_profession_id=$s_tg_periods_profession_id ";}
		if($s_truename){$sql2 .= " and A.truename like '%{$s_truename}%' ";}
		if($s_studentno){$sql2 .= " and A.studentno like '%{$s_studentno}%' ";}
		if($s_loginname){$sql2 .= " and A.loginname like '%{$s_loginname}%' ";}
		if($s_email){$sql2 .= " and A.email like '%{$s_email}%' ";}
		if($s_telephone){$sql2 .= " and A.telephone like '%{$s_telephone}%' ";}
		if($s_description){$sql2 .= " and A.description like '%{$s_description}%' ";}
		if($sql_permission_school){$sql_permission_school2 = " AND A.tg_periods_school_id in ($sql_permission_school) ";}
		
		$query = $db->query("select A.tg_customer_id from {$dbtablepre}tg_customer as A left join {$dbtablepre}tg_periods_school AS B on A.tg_periods_school_id=B.tg_periods_school_id left join {$dbtablepre}tg_periods_grade AS D on A.tg_periods_grade_id=D.tg_periods_grade_id left join {$dbtablepre}tg_periods_profession AS E on A.tg_periods_profession_id=E.tg_periods_profession_id where 1 $sql2 $sql_permission_school2 order by A.tg_customer_id desc");
		while($rs = $db->fetch_array($query)) {
			$db->query("delete from {$dbtablepre}tg_customer where tg_customer_id=".intval($rs['tg_customer_id'])."");
		}
		showTips('当前筛选的信息删除成功！','?mp='. $mp .'&action=list&page='.$page.$urlquery);
	}	
	
}
?>

<?php require('inc.bottom.php');?>
</div>
</body>
</html>