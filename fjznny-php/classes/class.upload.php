<?php
/**
 * vwen.com
 *
 * @copyright  Copyright (c) 2010 VWEN.COM Inc. http://www.vwen.com
 * QQ: 19519959 E-mail:noerr@vip.qq.com
 */

class upload
{
	var $savepath;
	var $addpath;
	var $alowexts;
	var $maxsize;
	var $overwrite;
	var $files = array();
	var $uploads = 0;
	var $errlang = 'cn';
	var $uploadeds = 0;
	var $imageexts = array('gif', 'jpg', 'jpeg', 'png', 'bmp');
	var $uploadedfiles = array();
	var $error;

	function upload($inputname, $savepath = '', $addpath = "", $alowexts = 'jpg|jpeg|gif|bmp|png|doc|docx|xls|ppt|pdf|txt|rar|zip', $maxsize = 0, $savename = '', $overwrite = 0)
	{
		if(!isset($_FILES[$inputname]) && !is_array($_FILES)) return false;
		$savepath = str_replace("\\", '/', $savepath);
		$this->set_savepath($savepath);
		$this->set_addpath($addpath);
		$this->savename = $savename;
		$this->alowexts = $alowexts;
		$this->maxsize = $maxsize;
		$this->overwrite = $overwrite;
		$this->uploads = count($_FILES[$inputname]['name']);
		$this->error = 0;

		if($this->uploads == 1){
			$this->uploads = 1;
			$uploadfiles[0] = array('tmp_name' => $_FILES[$inputname]['tmp_name'], 'name' => $_FILES[$inputname]['name'], 'type' => $_FILES[$inputname]['type'], 'size' => $_FILES[$inputname]['size'], 'ext' => $this->get_ext($_FILES[$inputname]['name']), 'error' => $_FILES[$inputname]['error']);
		}
		else
		{
			foreach($_FILES[$inputname]['name'] as $key => $error) 
			{
				if($_FILES[$inputname]['tmp_name'][$key]){
				$uploadfiles[$key] = array('tmp_name' => $_FILES[$inputname]['tmp_name'][$key], 'name' => $_FILES[$inputname]['name'][$key], 'type' => $_FILES[$inputname]['type'][$key], 'size' => $_FILES[$inputname]['size'][$key], 'ext' => $this->get_ext($_FILES[$inputname]['name'][$key]), 'error' => $_FILES[$inputname]['error'][$key], 'description'=>$description[$key]);
				}
			}
			
		}
		if(!$this->create_forder($this->savepath.$this->addpath))
		{
			$this->error = 8;
			return false;
		}
		if(!is_dir($this->savepath.$this->addpath))
		{
			$this->error = 8;
			return false;
		}

		@chmod($this->savepath, 0777);
		if(!is_writeable($this->savepath) && ($this->savepath != '/'))
		{
			$this->error = 9;
			return false;
		}
		$this->files = $uploadfiles;
		//开始执行上传验证
		foreach($uploadfiles as $k=>$file)
		{
			if(!preg_match("/^(".$this->alowexts.")$/", $file['ext']))
			{
				$this->error = '10';
				return false;
			}
			if($this->maxsize && $file['size'] > $this->maxsize)
			{
				$this->error = '11';
				return false;
			}
			if(!$this->isuploadedfile($file['tmp_name']))
			{
				$this->error = '12';
				return false;
			}
		}
		//开始执行上传
		$aids = array();
		foreach($uploadfiles as $k=>$file)
		{
			$savename=$this->set_savename($file['ext']);
			$savefile = $this->savepath.$this->addpath.$savename;
			$savefile = preg_replace("/(php|phtml|php3|php4|jsp|exe|dll|asp|cer|asa|shtml|shtm|aspx|asax|cgi|fcgi|pl)(\.|$)/i", "_\\1\\2", $savefile);
			if(!$this->overwrite && file_exists($savefile)) continue;
			if(move_uploaded_file($file['tmp_name'], $savefile) || @copy($file['tmp_name'], $savefile))
			{
				$this->uploadeds++;
				@chmod($savefile, 0644);
				@unlink($file['tmp_name']);
				$this->uploadedfiles[] = array('savename'=>$savename, 'filename'=>$file['name'], 'filepath'=>$this->savepath, 'addpath'=>$this->addpath, 'filetype'=>$file['type'], 'filesize'=>$file['size'], 'fileext'=>$file['ext'], 'description'=>$file['description']);
			}
		}
		return $this->uploadedfiles;
	}

	/**
     * 设置保存路径
     * @param string 文件保存路径：以 "/" 结尾
     */
    function set_savepath($savepath)
    {
		$savepath = str_replace("\\", "/", $savepath);
	    $savepath = substr($savepath,-1)=="/" ? $savepath : $savepath."/";
        $this->savepath = $savepath;
		return $this->savepath;
    }
	
	/**
     * 设置保存路径
     * @param string 文件保存路径：以 "/" 结尾
     */
    function set_addpath($addpath)
    {
		$addpath = str_replace("\\", "/", $addpath);
	    $addpath = substr($addpath,-1)=="/" ? $addpath : $addpath."/";
		if($addpath=="/")$addpath="";
        $this->addpath = $addpath;
		return $this->addpath;
    }	
	
	/**
     * 设置保存文件名
     * @param savename 文件名
     */
    function set_savename($ext)
    {
		if($savename==""){
			$this->savename = date('YmdHis')."_".rand(100,999).'.'.$ext;
		}else{
			$this->savename = $this->savename;	
		}
		return $this->savename;
    }
	
	/**
     * 获取文件扩展名
     * @param string 文件保存路径：以 "/" 结尾
     */
	function get_ext($filename){
		$ext = explode(".", $filename);
		$ext = $ext[count($ext) - 1];
		return strtolower($ext);
	}

	function isuploadedfile($file)
	{
		return is_uploaded_file($file) || is_uploaded_file(str_replace('\\\\', '\\', $file));
	}

	function create_forder($path, $mode = 0777){
		//这个\没考虑
		$detail=explode("/",$path);
		foreach($detail AS $key=>$value){
			if($value=='' && $key!=0){
				//continue;
			}
			$newpath.="$value/";
			if((eregi("^\/",$newpath)||eregi(":",$newpath))){continue;}
			if( !is_dir($newpath) ){
				if(substr($newpath,-1)=='\\'||substr($newpath,-1)=='/')
				{
					$_newpath=substr($newpath,0,-1);
				}
				else
				{
					$_newpath=$newpath;
				}
				if(!mkdir($_newpath)&&!file_exists($_newpath)){
					return 'false';
				}
				@chmod($newpath,$mode);
			}
		}
		return $path;
	}

	function errormsg()
	{
		$UPLOAD_ERROR = array('cn' => array(0 => '文件上传成功',
								1 => '上传的文件大小超过了服务器限制的上传大小',
								2 => '上传的文件大小超过了表单限制的上传大小',
								3 => '文件只有部分被上传',
								4 => '没有选择的文件上传,请选择要上传的文件',
								5 => '',
								6 => '找不到临时文件夹。',
								7 => '文件写入临时文件夹失败',
								8 => '附件目录创建不成功',
								9 => '附件目录没有写入权限',
								10 => '不允许上传该类型文件',
								11 => '文件超过了管理员限定的大小',
								12 => '非法上传文件',
								13 => '发现同名文件',
							 ),
							'en' => array(0 => 'file upload successful',
								1 => 'upload the file size of more than the server restrictions of the upload size',
								2 => 'upload the file size of the form of more than limit upload size',
								3 => 'file is only partly by uploading',
								4 => 'not choose file upload, please choose to upload the file',
								5 => '',
								6 => 'can\'t find temporary folder. ',
								7 => 'file to write to temporary folder failure',
								8 => 'accessories directory create unsuccessful',
								9 => 'accessories directory write access without',
								10 => 'not allowed to upload this type file',
								11 => 'documents more than the administrator of the size of the limited',
								12 => 'illegal uploading file',
								13 => 'found the file',
							 ));
		return $UPLOAD_ERROR[$this->errlang][$this->error];
	}
}

/*
Array ( [0] => Array ( [savename] => 20090710154334_586.jpg [filename] => 0c1926dda1.jpg [filepath] => ../upfiles/product/ [addpath] => 200907/ [filetype] => image/pjpeg [filesize] => 72984 [fileext] => jpg [description] => ) ) 
*/
?>