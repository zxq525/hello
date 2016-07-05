<?php 
//-------------------------------------------------------------------
//数据库连接配置
$dbtablepre = 'fjznny_';
$dbhostname = "localhost";		//数据库主机
$dbusername = "root";			//数据库用户
$dbpassword = "123456";			//数据库密码
$dbdataname = "fjznny";			//数据库名称
$dbconntype = 0;				//连接方式，1为持续连接,0为一般链接(虚拟主机用户推荐)

//-------------------------------------------------------------------
//系统其他配置
define('SYS_AUTHKEY', 'vwen_com');

// 设置时区
define('SYS_TIMEZONE', 'Asia/Shanghai');
if(function_exists('date_default_timezone_set')) {
    date_default_timezone_set(SYS_TIMEZONE);
}

define('SYS_INSYS', TRUE);
define('SYS_DATETIME', date("Y-m-d H:i:s"));
define('SYS_SESSIONTIME', 1440);
define('SYS_CHARSET', 'utf-8');

//-------------------------------------------------------------------
//系统上传目录配置
$uppath = array();
$uppath['article']=ROOT_PATH."uploads/article/";
$uppath['articlesmall']=ROOT_PATH."uploads/articlesmall/";
$uppath['temp']=ROOT_PATH."data/temp/";
$uppath['ggimage']=ROOT_PATH."uploads/ggimage/";
$uppath['product']=ROOT_PATH."uploads/product/";
$uppath['proclass']=ROOT_PATH."uploads/proclass/";
$uppath['downloads_image']=ROOT_PATH."uploads/downloads-img/";
$uppath['friendlink']=ROOT_PATH."uploads/friendlink/";
$uppath['video_image']=ROOT_PATH."uploads/video-img/";
$uppath['video']=ROOT_PATH."uploads/video/";
?>