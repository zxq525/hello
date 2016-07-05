<?php
/*
函数名：Alert
作  用：javascript提示、转向、关闭
参  数：msg ----提示字符  goUrl ----"back"后退,"close"关闭,其他为转向地址
*/
function jsalert($msg,$gourl,$target="parent"){
	$msg = str_replace("'","\'",$msg);
  	if ($gourl=="back") {
		$jsstr = "alert('".$msg."');".$target.".history.go(-1);";
	}elseif ($gourl=="this") {
		$jsstr = "alert('".$msg."');";
	}elseif ($gourl=="close"){
		$jsstr = "alert('".$msg."');".$target.".close();";
	}elseif ($gourl=="refresh"){
		$jsstr = "alert('".$msg."');".$target.".location.reload();";
	}elseif ($msg=="url"){
		$jsstr = "".$target.".location.href='".$gourl."'";
	}elseif ($msg=="script"){
		$jsstr = $gourl;
	}else{
		$jsstr = "alert('".$msg."');".$target.".location.href='".$gourl."'";
	}
	echo '<meta http-equiv="Content-Type" content="text/html; charset='.SYS_CHARSET.'"><script type="text/javascript">'.$jsstr.'</script>';
	die();
}

function showTips($msg,$gourl,$target="parent"){


	$jsstr = 'showTips("'.$msg.'");';

	$jsstr .= $target.'.location.href="'.$gourl.'"';
	
	echo '<meta http-equiv="Content-Type" content="text/html; charset='.SYS_CHARSET.'"><script type="text/javascript">'.$jsstr.'</script>';die();
}



function dhtmlspecialchars($string) {
	if(is_array($string)) {
		foreach($string as $key => $val) {
			$string[$key] = dhtmlspecialchars($val);
		}
	} else {
		$string = preg_replace('/&amp;((#(\d{3,5}|x[a-fA-F0-9]{4})|[a-zA-Z][a-z0-9]{2,5});)/', '&\\1',
		str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $string));
	}
	return $string;
}

function daddslashes($string, $force = 0) {
	!defined('MAGIC_QUOTES_GPC') && define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());
	if(!MAGIC_QUOTES_GPC || $force) {
		if(is_array($string)) {
			foreach($string as $key => $val) {
				$string[$key] = daddslashes($val, $force);
			}
		} else {
				$string = addslashes($string);
		}
	}
	return $string;
}

function gaddslashes($string) {
	return $string;
}

function getip(){
	if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
		$onlineip = getenv('HTTP_CLIENT_IP');
	} elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
		$onlineip = getenv('HTTP_X_FORWARDED_FOR');
	} elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
		$onlineip = getenv('REMOTE_ADDR');
	} elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
		$onlineip = $_SERVER['REMOTE_ADDR'];
	}else{
		$onlineip = "0.0.0.0";
	}
	return $onlineip;
}


/*    
函数作用：检查是否外部提交
返 回 值：$post: True
*/ 
function checksubmit(){
	global $_SERVER;
	if( empty($_SERVER['HTTP_REFERER']) || strpos($_SERVER['HTTP_REFERER'],$_SERVER['SERVER_NAME']) !== 7 ){
		return false;
	} else{
		return true;
	}
}

//检查是否正确邮箱
function checkemail($email)
{
	return strlen($email) > 6 && preg_match("/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/", $email);
}


function seterrmsg($msgstr){
	if(Trim($msgstr)==""){ 
		return "";
		exit;
	}
	$tempmsg=explode("@@",$msgstr);
	If((count($tempmsg)-1)>2){
		return "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=".SYS_CHARSET."\"><script type=\"text/javascript\">alert(\"@@".$tempmsg[1]."\\n@@".$tempmsg[2]."\\n@@".$tempmsg[3]."\");</script>";
	}else{
		return "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=".SYS_CHARSET."\"><script type=\"text/javascript\">alert(\"".str_replace('@@','\n@@',$msgstr)."\");</script>";
	}
}

function showErrTips($msgstr){
	if(Trim($msgstr)==""){ 
		return "";
		exit;
	}
	$tempmsg=explode("@@",$msgstr);
	for($k=1;$k<count($tempmsg);$k++)
	{
		$showstr .= '<li>'.$tempmsg[$k].'</li>';
		$k++;
		if($k>=2){break;}
	}
	echo '<meta http-equiv="Content-Type" content="text/html; charset='.SYS_CHARSET.'"><script type="text/javascript">showTips("<ul>'.$showstr.'</ul>","err");</script>';die();
}

/**************************************************
'函数名：SetSelected
'作  用：下拉框默认值
'**************************************************/
function setselected($val1,$val2){
	if($val1===NULL || $val2===Null){return false;}
	if(strval($val1)==strval($val2)){
		return " selected=\"selected\"";
	}else{
		return false;
	}
}

function setselected2($val1,$val2){
	if($val1===NULL || $val2===Null){return false;}
	if(strpos(strval($val1),strval($val2))===false){
		return false;
	}else{
		return " selected=\"selected\"";
	}
}

/**************************************************
'函数名：SetChecked
'作  用：单选默认值
'**************************************************/
function setchecked($val1,$val2){
	if($val1===NULL || $val2===Null){return false;}
	if(strval($val1)==strval($val2)){
		return " checked=\"checked\"";
	}else{
		return false;
	}
}

/**************************************************
'函数名：SetChecked2
'作  用：多选默认值
'**************************************************/
function setchecked2($val1,$val2){
	if($val1===NULL || $val2===Null){return false;}
	if(strpos(strval($val1),strval($val2))===false){
		return false;
	}else{
		return " checked=\"checked\"";
	}
}

 function getcutstr($str, $length, $position=0, $type = 1){
	 $startPos = strlen($str);
	 $startByte = 0;
	 $endPos = strlen($str);
	 $count = 0;
	 for($i = 0; $i < strlen($str); $i++){
		 if($count >= $position && $startPos > $i){
			 $startPos = $i;
			 $startByte = $count;
			 }
		if(($count - $startByte) >= $length){
			 $endPos = $i;
			 break;
			 }
		$value = ord($str[$i]);
		 if($value > 127){
			 $count++;
			 if($value >= 192 && $value <= 223) $i++;
			 elseif($value >= 224 && $value <= 239) $i = $i + 2;
			 elseif($value >= 240 && $value <= 247) $i = $i + 3;
			 else return self :: raiseError("\"$str\" Not a UTF-8 compatible string", 0, __CLASS__, __METHOD__, __FILE__, __LINE__);
			 } 
		$count++;
		
		 }
	if($type == 1 && ($endPos-$startPos) > $length){
		 //return substr($str, $startPos, $endPos - $startPos) . "…";
		 return substr($str, $startPos, $endPos - $startPos);
		 } else{
		 return substr($str, $startPos, $endPos - $startPos);
		 }
	
	} 

function checkmobile($num){
	if(strlength($num)!=11){
		return false;
	}else{
		return true;
	}
}

/*
function getcutstr($string, $length, $dot = '…') {

	if(strlen($string) <= $length) {
		return $string;
	}

	//$string = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array('&', '"', '<', '>'), $string);

	$strcut = '';
	if(strtolower(SYS_CHARSET) == 'utf-8') {

		$n = $tn = $noc = 0;
		while($n < strlen($string)) {

			$t = ord($string[$n]);
			if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
				$tn = 1; $n++; $noc++;
			} elseif(194 <= $t && $t <= 223) {
				$tn = 2; $n += 2; $noc += 2;
			} elseif(224 <= $t && $t < 239) {
				$tn = 3; $n += 3; $noc += 2;
			} elseif(240 <= $t && $t <= 247) {
				$tn = 4; $n += 4; $noc += 2;
			} elseif(248 <= $t && $t <= 251) {
				$tn = 5; $n += 5; $noc += 2;
			} elseif($t == 252 || $t == 253) {
				$tn = 6; $n += 6; $noc += 2;
			} else {
				$n++;
			}

			if($noc >= $length) {
				break;
			}

		}
		if($noc > $length) {
			$n -= $tn;
		}

		$strcut = substr($string, 0, $n);

	} else {
		for($i = 0; $i < $length; $i++) {
			$strcut .= ord($string[$i]) > 127 ? $string[$i].$string[++$i] : $string[$i];
		}
	}

	//$strcut = str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $strcut);

	return $strcut.$dot;
}
*/


function strlength($str){	//可以统计中文字符
	$len=strlen($str);
	$i=0;
		while($i<$len){
		if(preg_match("/^[".chr(0xa1)."-".chr(0xff)."]+$/",$str[$i])){
			$i+=2;
		}else{
			$i+=1;
		}
		$n+=1;
	}
	return $n;
}
/*
function strlength($str){	//可以统计中文字符
	return mb_strwidth($str, 'GBK');
}
*/

function htmlencode($fstring)
{
 if($fstring!="")
 {
    $fstring = str_replace( '>', '&gt;',$fstring);
    $fstring = str_replace( '<', '&lt;',$fstring);
    //$fstring = str_replace( chr(32), '&nbsp;',$fstring);
	$fstring = str_replace( chr(32), '&nbsp;',$fstring);
    $fstring = str_replace( chr(13), ' ',$fstring);
    $fstring = str_replace( chr(10) & chr(10), '<br>',$fstring);
    $fstring = str_replace( chr(10), '<BR>',$fstring);
 }
    return $fstring;
}

function encodehtml($fstring)
{
 if($fstring!="")
 {
    $fstring = str_replace("&gt;" , ">", $fstring);
    $fstring = str_replace("&lt;", "<", $fstring);
    $fstring = str_replace("&nbsp;",chr(32),$fstring);
    $fstring = str_replace("",chr(13),$fstring);
    $fstring = str_replace("<br>",chr(10) & chr(10),$fstring);
    $fstring = str_replace("<BR>",chr(10),$fstring);
 }
    return $fstring;
}

function replacehtml($str){
	$farr = array(
		"/\s /", //过滤多余的空白
		"/<(\/?)(script|i?frame|style|html|body|title|link|meta|\?|\%)([^>]*?)>/isU", //过滤 <script 等可能引入恶意内容或恶意改变显示布局的代码,假如不需要插入flash等,还可以加入<object的过滤
		"/(<[^>]*)on[a-zA-Z] \s*=([^>]*>)/isU", //过滤javascript的on事件
	);
	$tarr = array(
		" ",
		"&lt\\1\\2\\3&gt", //假如要直接清除不安全的标签，这里可以留空
		"\\1\\2",
	);

	$str = preg_replace( $farr,$tarr,$str);
	return $str;
}


function getcontent($text){
	$text = strip_tags($text);  //清除
	$text = trim($text);
	$text = str_replace('\r','',$text);
	$text = str_replace('\n','',$text);
	$text = str_replace('&nbsp;',' ',$text);
	//$text = str_replace('\"','\\\"',$text);
	//$text = trimmed_title($text,24);
	return $text;
}

function get_editortext($text){
	$text = str_replace('</P>',chr(10),$text);
	$text = str_replace('<BR>',chr(10),$text);
	$text = str_replace('&nbsp;',' ',$text);
	$text = strip_tags($text);  //清除
	return $text;
}


function checkuser($username) {
	if (!ereg("^[a-zA-Z0-9]*[_-]*[a-zA-Z0-9]*$", $username)) return false; //特殊字符检验 
	return true; 
}

function checkpassword($passwd) 
{ 
	if (!ereg("^[_a-zA-Z0-9]*$", $passwd)) return false; //特殊字符检测 
	return true; 
}

function checkint($num) 
{ 
	if (!ereg("^[-]?[0-9]+$", $num)) return false;  
	return true; 
} 

//检测浮点
function checkfloat($num) 
{ 
	if (!ereg("^[-]?[0-9]*[\.]?[0-9]+$", $num)) return false;  
	return true; 
} 

//检查日期格式，返回0为不正确格式
function checkisdate($string)
{
    if ( preg_match("/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/",$string) ) 
    {
        $first_sep = strpos($string,"-");
        $year = substr($string,0,$first_sep);
        $part_month_day = substr($string,$first_sep+1);    
        $last_sep = strpos($part_month_day,"-");    
        $month = substr( $part_month_day,0,$last_sep );
        $day = substr($part_month_day,$last_sep+1);
        if ( checkdate($month, $day, $year) ) {
            return true;
        }else{
            return false;
        }
    }else {
        return false;
    }
}

function checkdatetime($string) { //检查时间是否合法时间
	if (!preg_match("/^((\d{2}(([02468][048])|([13579][26]))[\-\/\s]?((((0?[13578])|(1[02]))[\-\/\s]?((0?[1-9])|([1-2][0-9])|(3[01])))|(((0?[469])|(11))[\-\/\s]?((0?[1-9])|([1-2][0-9])|(30)))|(0?2[\-\/\s]?((0?[1-9])|([1-2][0-9])))))|(\d{2}(([02468][1235679])|([13579][01345789]))[\-\/\s]?((((0?[13578])|(1[02]))[\-\/\s]?((0?[1-9])|([1-2][0-9])|(3[01])))|(((0?[469])|(11))[\-\/\s]?((0?[1-9])|([1-2][0-9])|(30)))|(0?2[\-\/\s]?((0?[1-9])|(1[0-9])|(2[0-8]))))))(\s(((0?[0-9])|(([1-2][0-3])|([1][0-9])))\:([0-5]?[0-9])((\s)|(\:([0-5]?[0-9])))))?$/", $string)){return false;}else{return true;} 
}

function checktime($string) { //检查时间是否合法时间
	if (!preg_match("/^(0[1-9]|1\d|2[0-4]):[0-5]\d$/", $string)){return false;}else{return true;} 
}



function datediff($part,$date1,$date2){

	$date1=strtotime($date1);
	$date2=strtotime($date2);

	$year1=date("Y",$date1);
	$year2=date("Y",$date2);

	$month2=date("m",$date2);
	$month1=date("m",$date1);
	$diff=$date2-$date1;

	$part=strtolower($part);
	$ret=0;
	switch ($part) {
		case "year":
			$ret=$year2-$year1;
			break;
		case "month":
			$ret=($year2-$year1)*12+$month2-$month1;
			break;
		case "day":
			$ret=$diff/(3600*24);
			break;
		case "hour":
			$ret=$diff/3600;
			break;
		case "min":
			$ret=$diff/60;
			break;
		case "sec":
			$ret=$diff;
			break;
		default:
			return $ret;
			break;
	}
	return $ret;
}

// 单位自动转换函数
function getrealsize($size){ 
	$kb = 1024; // Kilobyte
	$mb = 1024 * $kb;   // Megabyte
	$gb = 1024 * $mb;   // Gigabyte
	$tb = 1024 * $gb;   // Terabyte
	if($size < $kb){ 
		return $size." B";
	}else if($size < $mb){ 
		return round($size/$kb,2)." KB";
	}else if($size < $gb){ 
		return round($size/$mb,2)." MB";
	}else if($size < $tb){ 
		return round($size/$gb,2)." GB";
	}else{ 
		return round($size/$tb,2)." TB";
	}
}

function dateadd($interval,$number, $date) {
	$date_time_array = getdate(strtotime($date));
	$hours = $date_time_array["hours"];
	$minutes = $date_time_array["minutes"];
	$seconds = $date_time_array["seconds"];
	$month = $date_time_array["mon"];
	$day = $date_time_array["mday"];
	$year = $date_time_array["year"];
	switch ($interval) {
		case "yyyy": $year +=$number; break;	//年
		case "q": $month +=($number*3); break;	//季度
		case "m": $month +=$number; break;		//月
		case "y":
		case "d":
		case "w": $day+=$number; break;
		case "ww": $day+=($number*7); break;	//周
		case "h": $hours+=$number; break;
		case "n": $minutes+=$number; break;
		case "s": $seconds+=$number; break;
	}
	$timestamp = mktime($hours ,$minutes, $seconds,$month ,$day, $year);
	return $timestamp;
}


function debug($str){
	$str=str_replace("\"","\\\"",$str);
	$str=str_replace("\\","\\\\",$str);
	die("<script>alert(\"$str\");</script>");
}

function getmaxnum($tablename,$fieldname="sort_order"){
	global $db,$dbtablepre;
	$rs=$db->fetch_first("select max({$fieldname}) as maxnum from {$dbtablepre}{$tablename}");
	if ($rs){
		$maxnum=$rs["maxnum"]+1;
	}else{
		$maxnum=1;
	}
	if(is_null($maxnum))$maxnum=1;
	return $maxnum;
}


function authcode($string, $operation='', $key = '') {
	$key = md5($key ? $key : SYS_AUTHKEY);
	$key_length = strlen($key);

	$string = $operation == 'DECODE' ? base64_decode($string) : substr(md5($string.$key), 0, 8).$string;
	$string_length = strlen($string);

	$rndkey = $box = array();
	$result = '';

	for($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($key[$i % $key_length]);
		$box[$i] = $i;
	}

	for($j = $i = 0; $i < 256; $i++) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}

	for($a = $j = $i = 0; $i < $string_length; $i++) {
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}

	if($operation == 'DECODE') {
		if(substr($result, 0, 8) == substr(md5(substr($result, 8).$key), 0, 8)) {
			return substr($result, 8);
		} else {
			return '';
		}
	} else {
		return str_replace('=', '', base64_encode($result));
	}

}

function md5str($str){
	return md5(authcode($str));
	//return md5(md5($str));
}


function read_file($filename){
	$fp=fopen($FileName,"r");
	$data="";
	while(!feof($fp))
	{
		$data.=fread($fp,4096);
	}
	fclose($fp);
	return $data;
}

function write_file($filename,$data,$method="rb+",$iflock=1,$check=1,$chmod=1){
	$check && strpos($filename,'..')!==false && exit('What are you doing?');
	touch($filename);
	$handle=fopen($filename,$method);
	if($iflock){
		flock($handle,LOCK_EX);		//文件锁定
	}
	fwrite($handle,$data);
	if($method=="rb+") ftruncate($handle,strlen($data));
	fclose($handle);
	$chmod && @chmod($filename,0777);
}


function dreferer($default = '') {
	global $referer, $indexname;
	$default = empty($default) ? "index.php" : '';
	if(empty($referer) && isset($GLOBALS['_SERVER']['HTTP_REFERER'])) {
		$referer = preg_replace("/([\?&])((sid\=[a-z0-9]{6})(&|$))/i", '\\1', $GLOBALS['_SERVER']['HTTP_REFERER']);
		$referer = substr($referer, -1) == '?' ? substr($referer, 0, -1) : $referer;
	}else{
		$referer = dhtmlspecialchars($referer);
	}
	if(!preg_match("/(\.php|[a-z]+(\-\d+)+\.html)/", $referer) || strpos($referer, 'shopping_cart.php') || strpos($referer, 'shopping_confirm.php') ) {
		$referer = $default;
	}
	return $referer;
}

function formatmoney($money) {
	return round($money,2);
}


function currenturl($hashost=0){
	if($hashost==1){
		$url_this =  "http://".$_SERVER ['HTTP_HOST'];
		if($_SERVER['SERVER_PORT']!="80"){
			$url_this .= ":".$_SERVER[SERVER_PORT];
		}
	}else{
		$url_this="";
	}

	$url_this .= "/".substr($_SERVER['PHP_SELF'],1);
	if($_SERVER['QUERY_STRING']){
		$url_this .= "?".$_SERVER ['QUERY_STRING'];
	}
	return $url_this;
}

function randomstr($length){ 
	$hash = ""; 
	$chars = "abcdefghijklmnopqrstuvwxyz0123456789"; 
	$max = strlen($chars) - 1; 
	mt_srand((double)microtime() * 1000000); 
	for($i = 0; $i < $length; $i++) {
	$hash .= $chars[mt_rand(0, $max)]; 
	} 
	return $hash; 
}

//生成GUID
class System
{
	function currentTimeMillis()
	{
		list($usec, $sec) = explode(" ", microtime());
		return $sec.substr($usec, 2, 3);
	}
}
class NetAddress
{
	var $Name = 'localhost';
	var $IP = '127.0.0.1';

	function getLocalHost()
	{
		$address = new NetAddress();
		$address->Name = $_ENV["COMPUTERNAME"];
		$address->IP = $_SERVER["SERVER_ADDR"];
		return $address;
	}

	function toString()
	{
		return strtolower($this->Name.'/'.$this->IP);
	}
}
class Random
{
	function nextLong()
	{
		$tmp = rand(0, 1) ? '-' : '';
		return $tmp.rand(1000, 9999).rand(1000, 9999).rand(1000, 9999).rand(100,
			999).rand(100, 999);
	}
}
class Guid
{
	var $valueBeforeMD5;
	var $valueAfterMD5;

	function Guid()
	{
		$this->getGuid();
	}

	function getGuid()
	{
		$address = NetAddress::getLocalHost();
		$this->valueBeforeMD5 = $address->toString().':'.System
			::currentTimeMillis().':'.Random::nextLong();
		$this->valueAfterMD5 = md5($this->valueBeforeMD5);
	}

	function newGuid()
	{
		$Guid = new Guid();
		return $Guid;
	}

	function toString()
	{
		$raw = strtoupper($this->valueAfterMD5);
		return substr($raw, 0, 8).'-'.substr($raw, 8, 4).'-'.substr($raw, 12, 4).'-'.substr($raw, 16, 4).'-'.substr($raw, 20);
	}
}

function newguid(){
	$guid = new Guid();
	return $guid->toString();
}

function stripslashes_deep($value) {
	$value = is_array($value) ? array_map('stripslashes_deep', $value) : stripslashes($value);
	return $value;
}

function replace_path($value) {
	global $config;
	$value=preg_replace('%(\<(script|link|img|input|embed|object|base|area|map|table|td|th|tr|param) [^>]*(src|href|background|value)=(["\']))((\.\.\/)?)(images\/[^>]+\4[^>]*\>)%s',"\${1}skins/\${7}",$value);
	//$value=preg_replace('/<\!--((?!-->|<\!--).|\n)*-->/i','',$value);
	$value=preg_replace('/background((\-image)?):(.*)url\((.+)\)/i',"background\${2}:\${3}url(skins/\${4})",$value);
	$value=preg_replace('/(href=")(.+)(\.css")/',"\${1}skins/\${2}\${3}",$value);
	return $value;
}

function parseparameter($param, $nofix=1) {
	$paramarr = array();
	$sarr = explode('/', $param);
	if(empty($sarr)) return $paramarr;
	if(is_numeric($sarr[0])) $sarr = array_merge(array('uid'), $sarr);
	if(count($sarr)%2 != 0) $sarr = array_slice($sarr, 0, -1);
	for($i=0; $i<count($sarr); $i=$i+2) {
		if(!empty($sarr[$i+1])) $paramarr[$sarr[$i]] = addslashes(str_replace(array('/', '\\'), '', rawurldecode(stripslashes($sarr[$i+1]))));
	}
	return $paramarr;
}

function formatdate($date,$format="Y-m-d"){
	if($date==0 || is_null($date)){return "";}
	return date($format,strtotime($date));
}

function checksize($Standard_Width,$Standard_Height,$this_Widtht,$this_Height){
			if ($this_Height <= $Standard_Height && $this_Widtht <= $Standard_Width) {
				// 如果比期望的缩略图小，那只Copy
				$thumbWidth = $this_Widtht;
				$thumbHeight = $this_Height;
			}else{
				if( $this_Widtht > $Standard_Width ){// 宽 > 设定宽度
					$thumbWidth = $Standard_Width ;
					$thumbHeight = $Standard_Width * ( $this_Height / $this_Widtht );
					if($thumbHeight > $Standard_Height){// 高 > 设定高度
						$thumbWidth = $Standard_Height * ( $thumbWidth / $thumbHeight );
						$thumbHeight = $Standard_Height ;
					}
				}elseif( $this_Height > $Standard_Height ){// 高 > 设定高度
					$thumbHeight = $Standard_Height ;
					$thumbWidth = $Standard_Height * ( $this_Widtht / $this_Height );
					if($thumbWidth > $Standard_Width){// 宽 > 设定宽度
						$thumbHeight = $Standard_Width * ( $thumbHeight / $thumbWidth );
						$thumbWidth = $Standard_Width ;
					}
				}
				}
return " Height=$thumbHeight Width=$thumbWidth";
}


function sendmail($formemail,$formname,$toemail,$toname,$replyemail,$replyname,$ishtml,$subject,$mailbody,$altbody="",$issmtp=false,$smtpemail="",$smtphost="",$smtpname="",$smtppwd=""){
	require_once("class.phpmailer.php");
	$mail = new PHPMailer();
	$mail->CharSet = "utf-8"; // 设置编码
	if($issmtp==true){
		$mail->Sender = $smtpemail;
		$mail->IsSMTP();                                      // 设置发信使用SMTP
		$mail->Host = $smtphost;  // SMTP 服务器
		$mail->SMTPAuth = true;     // 打开 SMTP 认证
		$mail->Username = $smtpname;  // SMTP 账户
		$mail->Password = $smtppwd; // SMTP 密码
	}
	
	$mail->From = $formemail;
	$mail->FromName = $formname;
	$mail->AddAddress($toemail, $toname);
	//$mail->AddAddress("ellen@example.com");                  // name is optional
	if(!$replyemail==''){
		$mail->AddReplyTo($replyemail, $replyname);
	}
	
	$mail->IsHTML($ishtml);                               // send as HTML
	$mail->Subject = $subject;
	$mail->Body = $mailbody;		//文本邮件内容
	
	if(!$mail->Send()){
    	$mail->ClearAddresses();
    	$mail->ClearAttachments();
		return $mail->ErrorInfo;		//返回错误信息
		//exit;
	}
    $mail->ClearAddresses();
    $mail->ClearAttachments();
	return true;
}

function viewad($classid,$filewidth,$fileheight){
	global $db,$dbtablepre,$uppath;
	//"select * from {$dbtablepre}publicize_region where thistag='$tagname' and infostatus=1"	
	$p_rs=$db->fetch_first("select * from {$dbtablepre}ggimage where classid=$classid order by sort_order desc,id desc limit 1");
	if($p_rs) {
		if(strtolower(substr($p_rs['linkimg'],-3))=="swf"){
			$publicize='<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="'.$filewidth.'" height="'.$fileheight.'">
  <param name="movie" value="'. $uppath['ggimage'].$p_rs['linkimg'] .'" />
  <param name="quality" value="high" />
  <embed src="'. $uppath['ggimage'].$p_rs['linkimg'] .'" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="'.$filewidth.'" height="'.$fileheight.'"></embed>
</object>';
		}else{
			if($p_rs[linkurl]!=""){
				$publicize= "<a href='$p_rs[linkurl]' target='_blank'><img src='".$uppath['ggimage'].$p_rs[linkimg]."' border='0' width=".$filewidth." height=".$fileheight." /></a>";
			}else{
				$publicize= "<img src='".$uppath['ggimage'].$p_rs[linkimg]."' border='0' width=".$filewidth." height=".$fileheight." />";
			}
		}
		
	}
	return $publicize;
}

function groupad($classid,$filewidth='100',$fileheight='100'){
	global $db,$dbtablepre,$uppath;
	//"select * from {$dbtablepre}publicize_region where thistag='$tagname' and infostatus=1"	
	$p_query=$db->query("select * from {$dbtablepre}ggimage where classid=$classid order by sort_order desc,id desc");
	$i = 0;
	while($p_rs = $db->fetch_array($p_query)) {
		if(strtolower(substr($p_rs['linkimg'],-3))=="swf"){
			$publicize[$i]['linkimg'] = '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="'.$filewidth.'" height="'.$fileheight.'">
  <param name="movie" value="'. $uppath['ggimage'].$p_rs['linkimg'] .'" />
  <param name="quality" value="high" />
  <embed src="'. $uppath['ggimage'].$p_rs['linkimg'] .'" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="'.$filewidth.'" height="'.$fileheight.'"></embed>
</object>';
		}else{
			$publicize[$i]['linkname'] = $p_rs['linkname'];
			$publicize[$i]['linkurl'] = $p_rs['linkurl'];
			$publicize[$i]['linkinfo'] = $p_rs['infointro'];
			$publicize[$i]['linkimg'] = $uppath['ggimage'].$p_rs['linkimg'];
			$publicize[$i]['thumbimg'] = $uppath['ggimage'].$p_rs['thumbimg'];
		}
		$i++;
	}
	return $publicize;
}

function excelTime($date, $time = false) {
	if(function_exists('GregorianToJD')){
		if (is_numeric ( $date )) {
		$jd = GregorianToJD ( 1, 1, 1970 );
		$gregorian = JDToGregorian ( $jd + intval ( $date ) - 25569 );
		$date = explode ( '/', $gregorian );
		$date_str = str_pad ( $date [2], 4, '0', STR_PAD_LEFT ) . "-" . str_pad ( $date [0], 2, '0', STR_PAD_LEFT ) . "-" . str_pad ( $date [1], 2, '0', STR_PAD_LEFT ) . ($time ? " 00:00:00" : '');
		return $date_str;
		}
	}else{
		$date=$date>25568?$date:25569;
		/*There was a bug if Converting date before 1-1-1970 (tstamp 0)*/
		$ofs=(70 * 365 + 17+2) * 86400;
		$date =  date("Y-m-d",($date * 86400) - $ofs). ($time ? " 00:00:00" : '');
	}
	return $date;
}
?>