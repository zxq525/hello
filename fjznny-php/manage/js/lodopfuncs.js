function getLodop(oOBJECT,oEMBED){
/**************************
  本函数根据浏览器类型决定采用哪个对象作为控件实例：
  IE系列、IE内核系列的浏览器采用oOBJECT，
  其它浏览器(Firefox系列、Chrome系列、Opera系列、Safari系列等)采用oEMBED,
  对于64位浏览器指向64位的安装程序install_lodop64.exe。
**************************/
        var strHtmInstall="由于浏览器的版本繁多,为了更好的打印效果,必须安装打印控件才可以打印!<br /><br />请点击这里<a href='../downloads/install_lodop32.exe' target='_self'>执行安装</a>网页打印控件,安装后请刷新页面或重新进入。";
        var strHtmUpdate="打印控件需要升级!<br /><br />请点击这里<a href='../downloads/install_lodop32.exe' target='_self'>执行升级</a>,升级后请刷新页面或重新进入。";
        var strHtm64_Install="由于浏览器的版本繁多,为了更好的打印效果,必须安装打印控件才可以打印!<br /><br />请点击这里<a href='../downloads/install_lodop64.exe' target='_self'>执行安装</a>网页打印控件,安装后请刷新页面或重新进入。";
        var strHtm64_Update="打印控件需要升级!<br /><br />请点击这里<a href='../downloads/install_lodop64.exe' target='_self'>执行升级</a>,升级后请刷新页面或重新进入。";
        var strHtmFireFox="<br><br>注意：<br>1：如曾安装过Lodop旧版附件npActiveXPLugin,请在【工具】->【附加组件】->【扩展】中先卸它。";
        var LODOP=oEMBED;	
		var tempString = "";	
	try{	
	     var isIE	 =  (navigator.userAgent.indexOf('MSIE')>=0) || (navigator.userAgent.indexOf('Trident')>=0);
	     var is64IE  = isIE && (navigator.userAgent.indexOf('x64')>=0);
	     if (isIE) LODOP=oOBJECT;
	     if ((LODOP==null)||(typeof(LODOP.VERSION)=="undefined")) {
		 //if (navigator.userAgent.indexOf('Firefox')>=0){$.prompt(strHtmFireFox,{buttons:{确定:true}});return;};
		 if (is64IE) {$.prompt(strHtm64_Install,{buttons:{确定:true}});} else		 
	 	 if (isIE)   {$.prompt(strHtmInstall,{buttons:{确定:true}});} else 
  	                     {
							 if (navigator.userAgent.indexOf('Firefox')>=0){tempString = strHtmFireFox}
							 $.prompt(strHtmInstall + tempString,{buttons:{确定:true}});return;};	 
		 return LODOP; 
	     } else
	     if (LODOP.VERSION<"6.1.6.4") {
		if (is64IE){$.prompt(strHtm64_Update,{buttons:{确定:true}});} else
		if (isIE)  {$.prompt(strHtmUpdate,{buttons:{确定:true}});} else
		           {
					   if (navigator.userAgent.indexOf('Firefox')>=0){tempString = strHtmFireFox}
							 $.prompt(strHtmInstall + tempString,{buttons:{确定:true}});return;
					   };
		return LODOP;
	     }
	     //=====如下空白位置适合调用统一功能:=====	     
		LODOP.SET_LICENSES("","296102931069650100112112561061","688858710010010811411756128900","");

	     //=======================================
	     return LODOP; 
	}catch(err){
		if (is64IE)
		{
			$.prompt(strHtm64_Install + tempString,{buttons:{确定:true}});
			}else{
			$.prompt(strHtmInstall + tempString,{buttons:{确定:true}});
		}
	    return LODOP; 
	}
}


