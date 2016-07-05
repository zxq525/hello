<?php
define('ROOT_PATH', "../");
define('PHP_SELF', $_SERVER['PHP_SELF']);
session_start();
require_once(ROOT_PATH."includes/common.php"); 
require_once(ROOT_PATH."classes/class.mysql.php");

$db = new DB_MySQL;
$db -> connect($dbhostname,$dbusername,$dbpassword,$dbdataname);
unset($dbhostname,$dbusername,$dbpassword,$dbconntype,$dbdataname);
mysql_query("SET NAMES 'UTF8'");

define('EDITOR_BASEPATH', (str_replace('\\','/',dirname(dirname($_SERVER['REQUEST_URI'])))=='/'?'/':str_replace('\\','/',dirname(dirname($_SERVER['REQUEST_URI']))).'/')."plugins/editor/fckeditor/");
?>