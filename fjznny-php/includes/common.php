<?php
error_reporting(E_ALL & ~E_NOTICE);					//错误等级
set_magic_quotes_runtime(0);			//关闭自动转为含有反斜线溢出字符
$mtime = explode(' ', microtime());
$discuz_starttime = $mtime[1] + $mtime[0];

if(PHP_VERSION < '4.1.0') {
	$_GET = &$HTTP_GET_VARS;
	$_POST = &$HTTP_POST_VARS;
	$_COOKIE = &$HTTP_COOKIE_VARS;
	$_SERVER = &$HTTP_SERVER_VARS;
	$_ENV = &$HTTP_ENV_VARS;
	$_FILES = &$HTTP_POST_FILES;
}
define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());
require ROOT_PATH.'data/inc.config.php';
require 'func.global.php';

@header('Content-Type: text/html; charset='.SYS_CHARSET);

foreach(array('_COOKIE', '_POST', '_GET') as $_request) {
	foreach($$_request as $_key => $_value) {
		$_key{0} != '_' && $$_key = daddslashes($_value);
	}
}
(!MAGIC_QUOTES_GPC) && $_FILES = daddslashes($_FILES);
?>