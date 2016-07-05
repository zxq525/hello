<?php
require 'inc.checkhead.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>管理</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="style/style.css" type="text/css">
<script type="text/javascript" src="js/dtree.js"></script>
</head>
<body style="background:#F2F9FD url(images/bg_leftbg.gif) right top no-repeat;">
<div class="bodybox_left" id="bodybox_left">
<div class="welcometext">欢迎您,<?php echo $user["truename"];?></div>
    <div class="dtree">
	<script type="text/javascript">
		<!--
	d = new dTree('d');
	d.add(0,-1,'<strong>系统管理菜单</strong>');
<?php

show_menu('user_group,user,setting_print,user_modifypwd',
		  '	d.add("2","0","系统基本管理");');
show_menu('setting',
		  '	d.add("2.01","2","系统基本设置","index.php?mp=setting&action=list","","main");');
show_menu('user_group',
		  '	d.add("2.02","2","管理组别列表","index.php?mp=user_group&action=list","","main");');
show_menu('user',
		  '	d.add("2.03","2","管理人员列表","index.php?mp=user&action=list","","main");');
show_menu('user_modifypwd',
		  '	d.add("2.04","2","修改登陆密码","index.php?mp=user_modifypwd&action=list","","main");');

/*
show_menu('setting',
		  '	d.add("3","0","网站信息管理");');
show_menu('setting',
		  '	d.add("3.01","3","文章分类列表","index.php?mp=article_class&action=list","","main");');
show_menu('setting',
		  '	d.add("3.02","3","网站文章管理","index.php?mp=article&action=list","","main");');
*/
show_menu('setting',
		  '	d.add("10","0","网站信息管理");');
show_menu('setting','	d.add("10.01","10","关于我们");');
show_menu('setting','	d.add("10.011","10.01","网站简介","index.php?mp=article_block&action=edit&classid=2","","main");');
show_menu('setting','	d.add("10.012","10.01","网站荣誉","index.php?mp=article_block&action=edit&classid=3","","main");');
show_menu('setting','	d.add("10.013","10.01","网站文化","index.php?mp=article_block&action=edit&classid=4","","main");');
show_menu('setting','	d.add("10.02","10","商业机会列表","index.php?mp=article2&action=list&topclassid=6","","main");');
show_menu('setting','	d.add("10.03","10","政策法规列表","index.php?mp=article2&action=list&topclassid=8","","main");');
show_menu('setting','	d.add("10.04","10","解决方案列表","index.php?mp=article2&action=list&topclassid=10","","main");');
show_menu('setting','	d.add("10.05","10","专家建议列表","index.php?mp=article2&action=list&topclassid=22","","main");');
show_menu('setting','	d.add("10.06","10","物联网应用列表","index.php?mp=article2&action=list&topclassid=24","","main");');
show_menu('setting','	d.add("10.07","10","物联网产品列表","index.php?mp=article2&action=list&topclassid=16","","main");');
show_menu('setting','	d.add("10.08","10","应用案例列表","index.php?mp=article2&action=list&topclassid=18","","main");');
show_menu('setting','	d.add("10.09","10","联系我们内容","index.php?mp=article_block&action=edit&classid=20","","main");');
show_menu('setting','	d.add("10.10","10","聚焦农业列表","index.php?mp=article2&action=list&topclassid=30","","main");');
show_menu('setting','	d.add("10.11","10","农产品信息列表","index.php?mp=article2&action=list&topclassid=32","","main");');
show_menu('setting','	d.add("10.12","10","企业专栏列表","index.php?mp=article2&action=list&topclassid=26","","main");');
show_menu('setting','	d.add("10.13","10","行业标准列表","index.php?mp=article2&action=list&topclassid=28","","main");');
show_menu('setting','	d.add("10.14","10","名企介绍列表","index.php?mp=article2&action=list&topclassid=34","","main");');

show_menu('setting',
		  '	d.add("20","0","广告信息管理");');
show_menu('setting',
		  '	d.add("20.01","20","首页幻灯片","index.php?mp=ggimage&action=list&classid=1","","main");');
show_menu('setting',
		  '	d.add("20.02","20","首页新闻切换","index.php?mp=ggimage&action=list&classid=2","","main");');
show_menu('setting',
		  '	d.add("20.03","20","首页最新资讯下方","index.php?mp=ggimage&action=list&classid=3","","main");');

show_menu('setting',
		  '	d.add("30","0","其他信息管理");');
show_menu('setting',
		  '	d.add("30.01","30","友情链接列表","index.php?mp=friendlink&action=list","","main");');	
?>
	document.write(d);
		//-->
	</script>
    </div>
</div>
</div>
</body>
</html>
<?php
function show_menu($pages, $menu){
	if(checkint(strpos($pages,','))){
		$array_page = explode(',', $pages);
		foreach($array_page as $page){
			if(user_permission(array($page=>'list'))){echo $menu;break;}
		}
	}else{
		if(user_permission(array($pages=>'list'))){echo $menu;}
	}
}
?>