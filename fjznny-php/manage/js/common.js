function strlength(strTemp)
{
 var i,sum;
 sum=0;
 for(i=0;i<strTemp.length;i++)
 {
  if ((strTemp.charCodeAt(i)>=0) && (strTemp.charCodeAt(i)<=255))
   sum=sum+1;
  else
   sum=sum+2;
 }
 return sum;
}


function checkemail(strEmail)
{
	var myReg = /^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+[a-zA-Z0-9_-]{2,3}$/; 
	
	if(myReg.test(strEmail))
	{
		return true;
	}
	else
	{
		return false;
	}
}

function checkempty(obj,msg){   
   if(obj.value=="")
{
   alert(msg);
   obj.focus();
   return false;}
   else
{
   return true;}
}

function checkclick(msg,url,target){
	if(target=="" || target==undefined){
		target="window";
	}
	if(confirm(msg)){
		eval(target +".location.href='"+ url +"';");
	}
}

function checkall(form,formname)  {
  for (var i=0;i<form.elements.length;i++)    {
    var e = form.elements[i];
    if (e.name ==formname){
	e.checked = form.chkall.checked;
	}
   }
  }

function checkdate(str)
{

  var   r   =   str.match(/^(\d{1,4})(-|\/)(\d{1,2})\2(\d{1,2})$/);     
  if(r==null) return   false;     
  var  d=  new  Date(r[1],   r[3]-1,   r[4]);     
  return  (d.getFullYear()==r[1]&&(d.getMonth()+1)==r[3]&&d.getDate()==r[4]);   

}



function checkint(num)  //判断是否是数字,中间是点和减号
{
	var re=/^[0-9]+$/i;
	if(re.test(num)){
		return true;
	}else{
		return false;
	}
}

function fclass(formobj1,formobj1name,arrname1)
	{
	formobj1.length = 0;
	formobj1.options[0] = new Option(formobj1name,"");
	for (var i=0; i<arrname1.length; i++){
		formobj1.options[formobj1.length] = new Option(arrname1[i][1], arrname1[i][0]);
	}
}

function nclass(formobj1,formobj2,formobj2name,arrname2){	

	formobj2.length = 0;
	formobj2.options[0] = new Option(formobj2name,"");
	for(var i=0;i<arrname2.length;i++) 
		{
			if(arrname2[i][0]==formobj1.value) 
			{
				formobj2.options[formobj2.length] = new Option(arrname2[i][2], arrname2[i][1]);
			}
		}

}

function setclass1(formobj1,formobj1name,arrname1)
	{
	formobj1.length = 0;
	formobj1.options[0] = new Option(formobj1name,"");
	for (var i=0; i<arrname1.length; i++){
		formobj1.options[formobj1.length] = new Option(arrname1[i][1], arrname1[i][0]);
	}
}

function setclass2(formobj1,formobj2,formobj2name,arrname2){	

	formobj2.length = 0;
	formobj2.options[0] = new Option(formobj2name,"");
for(var i=0;i<arrname2.length;i++) 
		{
			if(arrname2[i][0]==formobj1.value) 
			{
				formobj2.options[formobj2.length] = new Option(arrname2[i][2], arrname2[i][1]);
			}
		}

}

function setclass3(formobj2,formobj3,formobj3name,arrname3){	
	formobj3.length = 0;
	formobj3.options[0] = new Option(formobj3name,"");
	for(var i=0;i<arrname3.length;i++){
		if(arrname3[i][1]==formobj2.value) 
		{
				formobj3.options[formobj3.length] = new Option(arrname3[i][3], arrname3[i][2]);
		}
	}

}

function dselect(theform,sid){
	for(i=0; i<theform.length; i++) {
		if(theform.options[i].value==sid){theform.options[i].selected=true;return;}
	}
}

function CheckForm(form){
	if(form=="" || form==undefined){
		form = $("input[name=operamode]");
	}
	if (form.val()==""){alert("请选择要执行的操作方式；");return false;}
	if(form.val()=="delete"){return confirm('确定要删除所选的信息吗？\n\n注意：执行删除后将不可恢复数据！');}
	return true;
}

function operation(modetype,form)
{
	if(form=="" || form==undefined){
		form = $("form[name=Form1]");
	}
	$('input[name=operamode]').val(modetype);
	if(CheckForm())
	{
		form.submit();
	}
	
}

function initLayout(){
	PageLayout();
	$(window).resize(function(){
		PageLayout();
	});
}


function headerMenu(indexID,url,target){
	$("#topmenu li").removeClass("select");
	$("#topmenu li").eq(indexID).addClass("select");
	
	if(target=="" || target==undefined){
		target="parent.main";
	}
	eval(target +".location.href='"+ url +"';");

}


function swfupload_init(inputid,postval,size,type,typeText,SuccessSub){
	settings = {
		flash_url : "js/swfupload/swfupload.swf",
		flash9_url : "js/swfupload/swfupload_fp9.swf",
		upload_url: "swfupload.php",
		post_params: postval,
		file_size_limit : "" + size + " MB",
		file_types : type,
		file_types_description : typeText,
		file_upload_limit : 100,
		file_queue_limit : 0,
		custom_settings : {
			divFileProgressContainer : "swfuProgress",
			startButtonId : "swfuBtnStart",
			cancelButtonId : "swfuBtnCancel",
			formInputID : inputid
		},
		debug: false,

		// Button settings
		button_image_url: "images/ButtonUpload.png",
		button_width: "61",
		button_height: "22",
		button_placeholder_id: "swfuBtnStart",
		button_text: '<span class="theFont">上传</span>',
		button_text_style: ".theFont{font-size: 12; text-align:center;}",
		
		// The event handler functions are defined in handlers.js
		swfupload_preload_handler : preLoad,
		swfupload_load_failed_handler : loadFailed,
		//file_queued_handler : fileQueued,
		file_queue_error_handler : fileQueueError,
		file_dialog_complete_handler : fileDialogComplete,
		upload_start_handler : uploadStart,
		upload_progress_handler : uploadProgress,
		upload_error_handler : uploadError,
		upload_success_handler : SuccessSub,
		upload_complete_handler : uploadComplete
		//queue_complete_handler : queueComplete	// Queue plugin event
	};
		swfu = new SWFUpload(settings);
		swfu.refreshCookies();
}

function reloadmain(){
	top.frames['rootframe'].window.frames['main'].document.location.reload();
}

function hrefmain(url){
	if(url=="" || url==undefined){
		url=top.frames['rootframe'].window.frames['main'].document.location.href;
	}
	top.frames['rootframe'].window.frames['main'].document.location.href=url;
}


function showTips( tips,style, time ,height ){
	var windowWidth  = top.document.documentElement.clientWidth;
	var tipsDiv = '<div class="tipsClass">' + tips + '</div>';
	
	if(style=="" || style==undefined){style='ok';}
	if(height=="" || height==undefined){height=0;}
	if(time=="" || time==undefined){time=3;}
	$(top.window.document).find( 'div.tipsClass').remove();
	$(top.window.document).find('body').append(tipsDiv);
	var TopDiv = $(top.window.document).find( 'div.tipsClass');
	$(top.window.document).find( 'div.tipsClass').css({
		'top'       : height + 'px',
		'left'      : ( windowWidth / 2 ) - (TopDiv.width() / 2)  + 'px'
	}).addClass('tips_'+style).show();
	top.hideTips(time);
}

function hideTips(time){
	clearTimeout(top.timer);
	top.timer=setTimeout(function(){$(top.window.document).find( 'div.tipsClass').fadeOut();},(time * 1000));
}

function getEditor(editorname,editpath,height)
{
var editor = CKEDITOR.replace( editorname, 
{
	filebrowserBrowseUrl : editpath+'ckfinder/ckfinder.html',  
	filebrowserImageBrowseUrl : editpath+'ckfinder/ckfinder.html?type=Images',  
	filebrowserFlashBrowseUrl : editpath+'ckfinder/ckfinder.html?type=Flash',  
	filebrowserUploadUrl : editpath+'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',  
	filebrowserImageUploadUrl : editpath+'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',  
	filebrowserFlashUploadUrl : editpath+'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'  ,
	//uiColor: '#14B8C4',
	language : 'zh-cn',
	height:height,
	toolbar :
	[
		 ['FontSize','Font'],
		 ['TextColor', '-' ,'Bold', 'Italic', '-', 'NumberedList', 'BulletedList', '-', 'Link','Unlink','-','Cut','Copy','Paste','-','Image','Flash','Files','Maximize','Source']
	]				
	
});
CKFinder.setupCKEditor(editor,	'/ckfinder/');
}

function getTgPeriodsSchool(obj_tg_periods_id,obj_tg_periods_school_id,tg_periods_id,tg_periods_school_id){
	var first_text = obj_tg_periods_school_id.find("option:first").text();
	obj_tg_periods_school_id.empty();
	obj_tg_periods_school_id.append('<option value="">'+ first_text +'</option>');
	$.ajax({
		url: 'result_back.php',
		type: 'get',
		data: 'action=get-tg-periods-school&tg_periods_id=' + tg_periods_id,
		dataType: 'json',
		success: function(result) {
				obj_tg_periods_school_id.append(result.message);
				obj_tg_periods_school_id.find("option[value='"+ tg_periods_school_id +"']").prop("selected", true);
		}
	});
}

function getTgPeriodsCollege(obj_tg_periods_school_id,obj_tg_periods_college_id,tg_periods_school_id,tg_periods_college_id){
	var first_text = obj_tg_periods_college_id.find("option:first").text();
	obj_tg_periods_college_id.empty();
	obj_tg_periods_college_id.append('<option value="">'+ first_text +'</option>');
	$.ajax({
		url: 'result_back.php',
		type: 'get',
		data: 'action=get-tg-periods-college&tg_periods_school_id=' + tg_periods_school_id,
		dataType: 'json',
		success: function(result) {
				obj_tg_periods_college_id.append(result.message);
				obj_tg_periods_college_id.find("option[value='"+ tg_periods_college_id +"']").prop("selected", true);
		}
	});
}

function getTgPeriodsGrade(obj_tg_periods_college_id,obj_tg_periods_grade_id,tg_periods_college_id,tg_periods_grade_id){
	var first_text = obj_tg_periods_grade_id.find("option:first").text();
	obj_tg_periods_grade_id.empty();
	obj_tg_periods_grade_id.append('<option value="">'+ first_text +'</option>');
	$.ajax({
		url: 'result_back.php',
		type: 'get',
		data: 'action=get-tg-periods-grade&tg_periods_college_id=' + tg_periods_college_id,
		dataType: 'json',
		success: function(result) {
				obj_tg_periods_grade_id.append(result.message);
				obj_tg_periods_grade_id.find("option[value='"+ tg_periods_grade_id +"']").prop("selected", true);
		}
	});
}

function getTgPeriodsProfession(obj_tg_periods_grade_id,obj_tg_periods_profession_id,tg_periods_grade_id,tg_periods_profession_id){
	var first_text = obj_tg_periods_profession_id.find("option:first").text();
	obj_tg_periods_profession_id.empty();
	obj_tg_periods_profession_id.append('<option value="">'+ first_text +'</option>');
	$.ajax({
		url: 'result_back.php',
		type: 'get',
		data: 'action=get-tg-periods-profession&tg_periods_grade_id=' + tg_periods_grade_id,
		dataType: 'json',
		success: function(result) {
				obj_tg_periods_profession_id.append(result.message);
				obj_tg_periods_profession_id.find("option[value='"+ tg_periods_profession_id +"']").prop("selected", true);
		}
	});
}

$(function(){
	$('body').append("<iframe name=\"postpanel\" style=\"display:none;\"></iframe>");
});