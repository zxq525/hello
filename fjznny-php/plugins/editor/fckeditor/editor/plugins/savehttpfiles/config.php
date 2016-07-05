<?php
/**
 * FCKeditor保存远程图片插件
 * @author slime09(slime09@gmail.com)
 * @license LGPL
 * @version 1.02 2009.4.18
 * @copyright  Copyright (c) 2009,  LinJiong (http://www.cn09.com)
 * 
 */
//设置图片保存绝对路径
$saveFilePath= str_replace('\\','',str_replace('/plugins/editor/fckeditor/editor/plugins/savehttpfiles','',dirname($_SERVER['REQUEST_URI']))."/upfiles/contentfiles/uploads") ;

//设置显示的链接地址
$displayUrl='/upfiles/contentfiles/uploads';


$saveFilesAbsolutePath = str_replace('\plugins\editor\fckeditor\editor\plugins\savehttpfiles','',dirname(__FILE__))."\\upfiles\\contentfiles\\uploads" ;
?>