<?php
require 'inc.checkhead.php';
set_time_limit(60);
$upconfig = array('ext'=>'xls','size'=>1024*1024*5,'savepath'=>'xls/','addpath'=>'');
$importDir = $upconfig['savepath'].'customer-import/';
if(!user_permission(array($mp=>'list'))){show_no_permission();}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>管理登录</title>
<link href="style/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/common.js"></script>
</head>
<body>
<div class="bodybox" id="bodybox">
<?php
if($action=="list"){
	if($act=="addsave"){
		
		if(!$errmsg){
			$rs=$db->fetch_first("select * from {$dbtablepre}tg_periods where tg_periods_id=". $default_periods['tg_periods_id'] ."");
			if (!$rs){
				$errmsg .= '@@选择的期数不存在；';
			}
		}
		
		if(!$errmsg){
			if(!$_FILES['xlsfile']['name']){
				$errmsg .= '@@请选择上传的文件；\\n';
			}
		}
	
		if(!$errmsg && $_FILES['xlsfile']['name']){
			require(ROOT_PATH.'classes/class.upload.php');
			$f = new upload("xlsfile", $upconfig['savepath'], $upconfig['addpath'], $upconfig['ext'], $upconfig['size']);
			if($f->error != 0){
				$errmsg .= '@@'.$f->errormsg().'；\\n';
			}else{
				$xlsfile=$f->uploadedfiles[0]['addpath'].$f->uploadedfiles[0]['savename'];	
			}
		}

		if(!$errmsg){
			$filename=$upconfig['savepath'] . $xlsfile;
			require_once ROOT_PATH.'classes/PHPExcel/IOFactory.php';
			$objReader = PHPExcel_IOFactory::createReader('Excel5');
			$objReader->setReadDataOnly(true);
			$objReader->setLoadSheetsOnly(0);
			if(!$objReader->canRead($filename)){$errmsg .= '@@无法读取上传的Excel文件,可能不是Excel文件；\\n';	}
			$objPHPExcel = $objReader->load($filename);
			$xlsData = $objPHPExcel->getSheet()->toArray();
			$countnum = count($xlsData)-1;
			if($xlsData[0][0]!='大学'){
				$errmsg .= '@@文档没有检测到第一行第1列是(大学),请检查文档是否正确；\\n';	
			}
			if($xlsData[0][1]!='学号'){
				$errmsg .= '@@文档没有检测到第一行第2列是(学号),请检查文档是否正确；\\n';	
			}
			if($xlsData[0][2]!='姓名'){
				$errmsg .= '@@文档没有检测到第一行第3列是(姓名),请检查文档是否正确；\\n';	
			}
			if($xlsData[0][3]!='密码'){
				$errmsg .= '@@文档没有检测到第一行第4列是(密码),请检查文档是否正确；\\n';	
			}
			if($countnum<=0){
				$errmsg .= '@@文档没有检测到数据行,请检查文档是否正确；\\n';		
			}
		}

		if(!$errmsg){
			if($handle = @opendir($importDir))
			{
					while (false !== ($file = readdir($handle)))
					{ 
					if ($file != "." && $file != "..")
					{
						if(!is_dir($importDir.$file) && checkint($file))
						{
							if(intval($file) < (time()-86400))
							{
								@unlink($importDir.$file);
							}
						}
					}
					}
				closedir($handle);
			}
			$num=0;
			$importfile = time();
			$array = array();
			for($i=1;$i < count($xlsData);$i++){
				$array[$i]=array(
					'school'=>addslashes(trim($xlsData[$i][0])),
					'studentno'=>trim($xlsData[$i][1]),
					'truename'=>addslashes(trim($xlsData[$i][2])),
					'loginpwd'=>trim($xlsData[$i][3]),
				);
			}
			if($array){
				write_file($importDir . $importfile, serialize($array), 'a');
			}
			@unlink($filename);
		}

		if(!$errmsg){
			jsalert("提交成功, 点击确定进行下一步；",'?mp='. $mp .'&action=list1&importfile=' . $importfile.'&description=' . urlencode(trim($description)));
		}
		showErrTips($errmsg);
	}
?>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="tablebk">
<form action="?<?php echo 'mp='.$mp;?>&action=list&act=addsave" method="post" enctype="multipart/form-data" name="Form1" target="postpanel">
  <tr>
  	<th colspan="2"><h2>帐户导入</h2></th>
  </tr>
  <tr>
    <td align="right" width="38%">上传文件：</td>
    <td><input name="xlsfile" type="file" class="inputfile" size="50" /> 
      EXCEL文件 <a href="xls/demo-import-customer.xls">下载模板文件</a></td>
  </tr>
  <tr>
    <td width="38%" align="right">导入说明：</td>
    <td><input name="description" type="text" class="input" size="50" /> </td>
  </tr>
  <tr>
    <td align="right">操作说明：</td>
    <td>Excel文件包含列：大学，学号，姓名，密码</td>
  </tr>
  <tr>
    <td height="32" colspan="2" align="center"><input type="submit" name="Submit" value="提  交"></td>
  </tr>
  </form>
</table>
<?php
}
if($action=='list1'){
	if(!checkint($importfile)){
		jsalert("操作文件检测无效","back");
	}
	$filename=$importDir . $importfile;
	if(!file_exists($filename)){
		jsalert("操作文件不存在","back");
	}
	$filetxt = file_get_contents($filename);
	$arrData=unserialize($filetxt);
	//print_r($arrData);
	if($act=="addsave"){
		$num=0;
		$date_added = SYS_DATETIME;
		foreach($arrData as $key => $value){
			if($value['school']){
				$school=$db->fetch_first("select * from {$dbtablepre}tg_periods_school where tg_periods_id=". $default_periods['tg_periods_id'] ." and `name`='". $value['school'] ."'");
				if(!$school){
					$db->query("insert into {$dbtablepre}tg_periods_school(tg_periods_id,`name`)values('". $default_periods['tg_periods_id'] ."','". $value['school'] ."')");
					$tg_periods_school_id = $db->insert_id();
				}else{
					$tg_periods_school_id = $school['tg_periods_school_id'];
				}
				
				$customer=$db->fetch_first("select * from {$dbtablepre}tg_customer where loginname='". addslashes($value['studentno']) ."'");
				if(!$customer){
					$tg_periods_school_name = $value['school'];
					$tg_periods_college_id = 0;
					$tg_periods_grade_id = 0;
					$tg_periods_profession_id = 0;
					
					$truename = $value['truename'];
					$studentno = $value['studentno'];
					$loginname = $value['studentno'];
					$loginpwd = $value['loginpwd'];
					$status = 1;
					$db->query("insert into {$dbtablepre}tg_customer(tg_periods_id,tg_periods_school_id,tg_periods_school_name,tg_periods_college_id,tg_periods_college_name,tg_periods_grade_id,tg_periods_grade_name,tg_periods_profession_id,tg_periods_profession_name,truename,studentno,telephone,loginname,email,loginpwd,ip,status,date_added,is_import,description)values('". $default_periods['tg_periods_id'] ."','$tg_periods_school_id','$tg_periods_school_name','$tg_periods_college_id','$tg_periods_college_name','$tg_periods_grade_id','$tg_periods_grade_name','$tg_periods_profession_id','$tg_periods_profession_name','$truename','$studentno','$telephone','$loginname','$email','". $loginpwd ."','$ip','$status','$date_added','1','$description')");
					$num++;
				}else{
					/*
					$zhubian = $value['zhubian'];
					$banbie = $value['banbie'];
					$chubanshe = $value['chubanshe'];
					$model = trim($value['model']);
					$price_estimate = floatval($value['price_estimate']);
					$price = floatval($value['price']);
					$discount = intval($value['discount']);
					
					$db->query("update {$dbtablepre}tg_periods_product set kecheng='". $value['kecheng'] ."',`name`='". $value['product_name'] ."',zhubian='$zhubian',banbie='$banbie',chubanshe='$chubanshe',model='$model',price_estimate='$price_estimate',price='$price',discount='$discount' where tg_periods_product_id=". $product['tg_periods_product_id'] ."");
					$tg_periods_product_id = $product['tg_periods_product_id'];	
					*/
				}
				
			}
		}
		jsalert("导入成功",'?mp='. $mp .'&action=list');
	}
?>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="tablebk">
<form action="?<?php echo 'mp='.$mp;?>&action=list1&importfile=<?php echo $importfile;?>&act=addsave" method="post" name="Form1" target="postpanel">
  <tr>
  	<th colspan="2"><h2>团供EXCEL文件上传</h2></th>
  </tr>
  <tr>
    <td width="38%" align="right">导入说明：</td>
    <td><?php echo $description;?><input type="hidden" name="description" value="<?php echo $description;?>" /></td>
  </tr>
  <tr>
    <td align="right">上传文件：</td>
    <td>共有<?php echo count($arrData);?>条数据</td>
  </tr>
  <tr>
    <td align="right">错误记录：</td>
    <td>
<?php
$num=0;
foreach($arrData as $key => $value){
	$customer=$db->fetch_first("select * from {$dbtablepre}tg_customer where loginname='". addslashes($value['studentno']) ."'");
	if($customer){
		echo '<div>';
		echo '学号：'. $value['studentno'] .'存在';
		echo '</div>';
	}
}
?>
    </td>
  </tr>
  <tr>
    <td height="32" colspan="2" align="center"><input type="submit" name="Submit" value="提  交"></td>
  </tr>
  </form>
</table>
<?php
}
?>
<?php require('inc.bottom.php');?>
</div>
</body>
</html>