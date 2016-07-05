<?php
require 'inc.checklogin.php';
//set_time_limit(60);
@header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
@header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
@header("Cache-Control: no-cache, must-revalidate");
@header("Pragma: no-cache");

/**********		获取学院		**********/
if($action=='get-tg-periods-school')
{
	$i=1;
	$city_string = '';
	$query = $db->query("SELECT * FROM {$dbtablepre}tg_periods_school WHERE tg_periods_id=". $default_periods['tg_periods_id'] ." ORDER BY tg_periods_school_id ASC");
	while($result = $db->fetch_array($query)) {
			$city_string .= '<option value="'. $result['tg_periods_school_id'] .'">'. $result['name'] .'</option>';
			$i++;
	}
	
	$array = array(
		'status'=>1,
		'message'=>$city_string,
	);
	echo json_encode($array);die();
}

/**********		获取大学		**********/
if($action=='get-tg-periods-college')
{
	if(!checkint($tg_periods_school_id)){
		$array = array(
			'status'=>0,
			'message'=>'参数错误',
		);
		echo json_encode($array);die();
	}
	$i=1;
	$city_string = '';
	$query = $db->query("SELECT * FROM {$dbtablepre}tg_periods_college WHERE tg_periods_school_id=". $tg_periods_school_id ." ORDER BY tg_periods_college_id ASC");
	while($result = $db->fetch_array($query)) {
			$city_string .= '<option value="'. $result['tg_periods_college_id'] .'">'. $result['name'] .'</option>';
			$i++;
	}
	
	$array = array(
		'status'=>1,
		'message'=>$city_string,
	);
	echo json_encode($array);die();
}

/**********		获取年级		**********/
if($action=='get-tg-periods-grade')
{
	if(!checkint($tg_periods_college_id)){
		$array = array(
			'status'=>0,
			'message'=>'参数错误',
		);
		echo json_encode($array);die();
	}
	$i=1;
	$city_string = '';
	$query = $db->query("SELECT * FROM {$dbtablepre}tg_periods_grade WHERE tg_periods_college_id=". $tg_periods_college_id ." ORDER BY tg_periods_grade_id ASC");
	while($result = $db->fetch_array($query)) {
			$city_string .= '<option value="'. $result['tg_periods_grade_id'] .'">'. $result['name'] .'</option>';
			$i++;
	}
	
	$array = array(
		'status'=>1,
		'message'=>$city_string,
	);
	echo json_encode($array);die();
}

/**********		获取专业		**********/
if($action=='get-tg-periods-profession')
{
	if(!checkint($tg_periods_grade_id)){
		$array = array(
			'status'=>0,
			'message'=>'参数错误',
		);
		echo json_encode($array);die();
	}
	$i=1;
	$city_string = '';
	$query = $db->query("SELECT * FROM {$dbtablepre}tg_periods_profession WHERE tg_periods_grade_id=". $tg_periods_grade_id ." ORDER BY tg_periods_profession_id ASC");
	while($result = $db->fetch_array($query)) {
			$city_string .= '<option value="'. $result['tg_periods_profession_id'] .'">'. $result['name'] .'</option>';
			$i++;
	}
	
	$array = array(
		'status'=>1,
		'message'=>$city_string,
	);
	echo json_encode($array);die();
}


/**********		获取区县		**********/
if($action=='getcounty')
{
	if(!checkint($city_id)){
		$array = array(
			'status'=>0,
			'message'=>'选择市区错误',
		);
		echo json_encode($array);die();
	}
	
	if($city_id==0){
		$array = array(
			'status'=>1,
			'message'=>'',
		);
		echo json_encode($array);die();
	}

	$city=$db->fetch_first("select city_id from {$dbtablepre}city where city_id=$city_id");
	if(!$city){
		$array = array(
			'status'=>0,
			'message'=>'市区查询错误',
		);
		echo json_encode($array);die();
	}
	
	$i=1;
	$county_string = '';
	$query = $db->query("SELECT * FROM {$dbtablepre}county WHERE city_id = '" . (int)$city_id . "' AND status = '1' ORDER BY sort_order, county_id");
	while($result = $db->fetch_array($query)) {
			$county_string .= '<option value="'. $result['county_id'] .'">'. $result['name'] .'</option>';
			$i++;
	}
	
	$array = array(
		'status'=>1,
		'message'=>$county_string,
	);
	echo json_encode($array);die();
}

/**********		切换期数		**********/
if($action=='periods_change')
{
	$tg_periods=$db->fetch_first("select tg_periods_id from {$dbtablepre}tg_periods where tg_periods_id=$tg_periods_id");
	if(!$tg_periods){
		$array = array(
			'status'=>0,
			'message'=>'期数查询错误',
		);
		echo json_encode($array);die();
	}
	
	setcookie($app_name."default_tg_periods_id",$tg_periods["tg_periods_id"]);
	
	$array = array(
		'status'=>1,
		'message'=>'',
	);
	echo json_encode($array);die();
}

/**********		按书号获取教材资料		**********/
if($action=='get_product_info')
{
	if(!$old_model){
		$array = array(
			'status'=>0,
			'message'=>'书号不正确',
		);
		echo json_encode($array);die();
	}
	$product=$db->fetch_first("select `name` from {$dbtablepre}tg_periods_product where tg_periods_id=". $default_periods['tg_periods_id'] ." and `model`='". $old_model ."'");
	if(!$product){
		$array = array(
			'status'=>0,
			'message'=>'查找书号不存在',
		);
		echo json_encode($array);die();
	}
	
	$array = array(
		'status'=>1,
		'message'=>'',
		'name'=>$product['name'],
	);
	echo json_encode($array);die();
}

/**********		切换打印机		**********/
if($action=='printer_index_change')
{
	if(checkint($printindex)){
		setcookie($app_name."default_printer_index",$printindex);
	}
	$array = array(
		'status'=>1,
		'message'=>'',
	);
	echo json_encode($array);die();
}

/**********		设置打印机		**********/
if($action=='printer_setting')
{
	if(!checkint($topvalue)){
		$array = array(
			'status'=>1,
			'message'=>'请输入正确的上边距格式；',
		);
		echo json_encode($array);die();
	}
	if(!checkint($leftvalue)){
		$array = array(
			'status'=>1,
			'message'=>'请输入正确的左边距格式；',
		);
		echo json_encode($array);die();
	}
	if(checkint($printindex)){
		setcookie($app_name."default_printer_index",$printindex,time()+3600*24*365);
	}
	setcookie($app_name."default_printer_topvalue",trim($topvalue),time()+3600*24*365);
	setcookie($app_name."default_printer_leftvalue",trim($leftvalue),time()+3600*24*365);

	$array = array(
		'status'=>0,
		'message'=>'设置保存成功',
	);
	echo json_encode($array);die();
}
?>