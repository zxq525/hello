<?php
/**
 * vwen.com
 *
 * @copyright  Copyright (c) 2010 VWEN.COM Inc. http://www.vwen.com
 * QQ: 19519959 E-mail:noerr@vip.qq.com
 */

*/
class clsUpload{
	var $saveName;// 保存名
	var $savePath;// 保存路径
	var $fileFormat = array('gif','jpg','doc','application/octet-stream');// 文件格式&MIME限定
	var $overwrite = 0;// 覆盖模式
	var $maxSize = 0;// 文件最大字节
	var $ext;// 文件扩展名
	var $thumb = 0;// 是否生成缩略图
	var $thumbWidth = 130;// 缩略图宽
	var $thumbHeight = 130;// 缩略图高
	var $thumbPrefix = "";// 缩略图前缀
	var $thumbSavePath;// 缩略图路径
	var $errno;// 错误代号
	var $returnArray= array();// 所有文件的返回信息
	var $returninfo= array();// 每个文件返回信息


// 构造函数
// @param $savePath 文件保存路径
// @param $fileFormat 文件格式限制数组
// @param $maxSize 文件最大尺寸
// @param $overwriet 是否覆盖 1 允许覆盖 0 禁止覆盖
	function clsUpload($savePath, $fileFormat='',$maxSize = 0, $overwrite = 0) {
		$this->setSavepath($savePath);
		$this->setFileformat($fileFormat);
		$this->setMaxsize($maxSize);
		$this->setOverwrite($overwrite);
		$this->setThumb($this->thumb, $this->thumbWidth,$this->thumbHeight);
		$this->errno = 0;
	}

// 上传
// @param $fileInput 网页Form(表单)中input的名称
// @param $changeName 是否更改文件名
	function run($fileInput,$changeName = 1){
		if(isset($_FILES[$fileInput])){
			$fileArr = $_FILES[$fileInput];
			if(is_array($fileArr['name'])){//上传同文件域名称多个文件
				for($i = 0; $i < count($fileArr['name']); $i++){
					$ar['tmp_name'] = $fileArr['tmp_name'][$i];
					$ar['name'] = $fileArr['name'][$i];
					$ar['type'] = $fileArr['type'][$i];
					$ar['size'] = $fileArr['size'][$i];
					$ar['error'] = $fileArr['error'][$i];
					$this->getExt($ar['name']);//取得扩展名，赋给$this->ext，下次循环会更新
					if(is_int($changeName)){
						$this->setSavename($changeName == 1 ? '' : $ar['name']);//设置保存文件名
					}else{
						$this->setSavename($changeName);
					}
					if($this->copyfile($ar)){
						$this->returnArray[] =  $this->returninfo;
					}else{
						$this->returninfo['error'] = $this->errmsg();
						$this->returnArray[] =  $this->returninfo;
					}
				}
				return $this->errno ?  false :  true;
			}else{//上传单个文件
				$this->getExt($fileArr['name']);//取得扩展名
				if(is_int($changeName)){
					$this->setSavename($changeName == 1 ? '' : $fileArr['name']);//设置保存文件名
				}else{
					$this->setSavename($changeName);
				}
				if($this->copyfile($fileArr)){
					$this->returnArray[] =  $this->returninfo;
				}else{
					$this->returninfo['error'] = $this->errmsg();
					$this->returnArray[] =  $this->returninfo;
				}
				return $this->errno ?  false :  true;
			}
			return false;
		}else{
			$this->errno = 10;
			return false;
		}
	}

// 单个文件上传
// @param $fileArray 文件信息数组
	function copyfile($fileArray){
		$this->returninfo = array();
		// 返回信息
		$this->returninfo['name'] = $fileArray['name'];
		$this->returninfo['saveName'] = $this->saveName;
		$this->returninfo['size'] = number_format( ($fileArray['size'])/1024 , 0, '.', ' ');//以KB为单位
		$this->returninfo['type'] = $fileArray['type'];
		// 检查文件格式
		if (!$this->validateFormat()){
			$this->errno = 11;
			return false;
		}
		// 检查目录是否可写
		if(!@is_writable($this->savePath)){
			$this->errno = 12;
			return false;
		}
		// 如果不允许覆盖，检查文件是否已经存在
		if($this->overwrite == 0 && @file_exists($this->savePath.$fileArray['name'])){
			$this->errno = 13;
			return false;
		}
		// 如果有大小限制，检查文件是否超过限制
		if ($this->maxSize != 0 ){
			if ($fileArray["size"] > $this->maxSize){
				$this->errno = 14;
				return false;
			}
		}
		// 文件上传
		if(!@copy($fileArray["tmp_name"], $this->savePath.$this->saveName)){
			$this->errno = $fileArray["error"];
			return false;
		}elseif( $this->thumb ){// 创建缩略图
			$CreateFunction = "imagecreatefrom".($this->ext == 'jpg' ? 'jpeg' : $this->ext);
			$SaveFunction = "image".($this->ext == 'jpg' ? 'jpeg' : $this->ext);
			if (strtolower($CreateFunction) == "imagecreatefromgif" 
				&& !function_exists("imagecreatefromgif")) {
				$this->errno = 16;
				return false;
			} elseif (strtolower($CreateFunction) == "imagecreatefromjpeg" 
				&& !function_exists("imagecreatefromjpeg")) {
				$this->errno = 17;
				return false;
			} elseif (!function_exists($CreateFunction)) {
				$this->errno = 18;
				return false;
			}

			$Original = @$CreateFunction($this->savePath.$this->saveName);
			if (!$Original) {$this->errno = 19; return false;}
			$originalHeight = ImageSY($Original);
			$originalWidth = ImageSX($Original);
			$this->returninfo['originalHeight'] = $originalHeight;
			$this->returninfo['originalWidth'] = $originalWidth;
			if (($originalHeight < $this->thumbHeight 
				&& $originalWidth < $this->thumbWidth)) {
				// 如果比期望的缩略图小，那只Copy
				copy($this->savePath.$this->saveName, 
					$this->thumbSavePath.$this->thumbPrefix.$this->saveName);
			} else {
				if( $originalWidth > $this->thumbWidth ){// 宽 > 设定宽度
					$thumbWidth = $this->thumbWidth ;
					$thumbHeight = $this->thumbWidth * ( $originalHeight / $originalWidth );
					if($thumbHeight > $this->thumbHeight){// 高 > 设定高度
						$thumbWidth = $this->thumbHeight * ( $thumbWidth / $thumbHeight );
						$thumbHeight = $this->thumbHeight ;
					}
				}elseif( $originalHeight > $this->thumbHeight ){// 高 > 设定高度
					$thumbHeight = $this->thumbHeight ;
					$thumbWidth = $this->thumbHeight * ( $originalWidth / $originalHeight );
					if($thumbWidth > $this->thumbWidth){// 宽 > 设定宽度
						$thumbHeight = $this->thumbWidth * ( $thumbHeight / $thumbWidth );
						$thumbWidth = $this->thumbWidth ;
					}
				}
				if ($thumbWidth == 0) $thumbWidth = 1;
				if ($thumbHeight == 0) $thumbHeight = 1;
				$createdThumb = imagecreatetruecolor($thumbWidth, $thumbHeight);
				if ( !$createdThumb ) {$this->errno = 20; return false;}
				if ( !imagecopyresampled($createdThumb, $Original, 0, 0, 0, 0, 
					$thumbWidth, $thumbHeight, $originalWidth, $originalHeight) )
					{$this->errno = 21; return false;}
				if ( !$SaveFunction($createdThumb, 
					$this->thumbSavePath.$this->thumbPrefix.$this->saveName) )
					{$this->errno = 22; return false;}
			}
		}
		// 删除临时文件
		if(!@$this->del($fileArray["tmp_name"])){
			return false;
		}
		return true;
	}

// 文件格式检查,MIME检测
	function validateFormat(){
		if(!is_array($this->fileFormat) 
			|| in_array(strtolower($this->ext), $this->fileFormat) 
			|| in_array(strtolower($this->returninfo['type']), $this->fileFormat) )
			return true;
		else
			return false;
	}
// 获取文件扩展名
// @param $fileName 上传文件的原文件名
	function getExt($fileName){
		$ext = explode(".", $fileName);
		$ext = $ext[count($ext) - 1];
		$this->ext = strtolower($ext);
	}

// 设置上传文件的最大字节限制
// @param $maxSize 文件大小(bytes) 0:表示无限制
	function setMaxsize($maxSize){
		$this->maxSize = $maxSize;
	}
// 设置文件格式限定
// @param $fileFormat 文件格式数组
	function setFileformat($fileFormat){
		if(is_array($fileFormat)){$this->fileFormat = $fileFormat ;}
	}

// 设置覆盖模式
// @param overwrite 覆盖模式 1:允许覆盖 0:禁止覆盖
	function setOverwrite($overwrite){
		$this->overwrite = $overwrite;
	}


// 设置保存路径
// @param $savePath 文件保存路径：以 "/" 结尾，若没有 "/"，则补上
	function setSavepath($savePath){
		$this->savePath = substr( str_replace("\\","/", $savePath) , -1) == "/" 
		? $savePath : $savePath."/";
		$this->makeDirectory($this->savePath);
		$this->makeDirectory($this->thumbSavePath);
	}

// 设置缩略图
// @param $thumb = 1 产生缩略图 $thumbWidth,$thumbHeight 是缩略图的宽和高
	function setThumb($thumb, $thumbWidth = 0,$thumbHeight = 0,$thumbSavePath = ""){
		$this->thumb = $thumb;
		if($thumbWidth) $this->thumbWidth = $thumbWidth;
		if($thumbHeight) $this->thumbHeight = $thumbHeight;
		if($thumbSavePath) $this->thumbSavePath = $thumbSavePath;
		$this->makeDirectory($this->thumbSavePath);
	}

// 设置文件保存名
// @param $saveName 保存名，如果为空，则系统自动生成一个随机的文件名
	function setSavename($saveName){
		if ($saveName == ''){  // 如果未设置文件名，则生成一个随机文件名
			$name = date('YmdHis')."_".rand(100,999).'.'.$this->ext;
		} else {
			$name = $saveName;
		}
		$this->saveName = $name;
	}

// 删除文件
// @param $fileName 所要删除的文件名
	function del($fileName){
		if(!@unlink($fileName)){
			$this->errno = 15;
			return false;
		}
		return true;
	}

// 返回上传文件的信息
	function getInfo(){
		return $this->returnArray;
	}

// 得到错误信息
	function errmsg(){
		$uploadClassError = array(
			0	=>'文件上传成功',
			1	=>'上传文件超过系统设定大小',	//php.ini的upload_max_filesize
			2	=>'上传文件超过系统设定大小',	//HTML 的 MAX_FILE_SIZE
			3	=>'仅部分文件被上传',
			4	=>'没有文件上传',
			6	=>'缺少临时文件夹',
			7	=>'磁盘不能写入文件',
			10	=>'文件上传失败',
			11	=>'上传文件格式不正确',
			12	=>'文件夹没有写入权限',
			13	=>'不允许覆盖存在文件',
			14	=>'上传文件超过最大限制',
			15	=>'删除文件失败',
			16	=>'此PHP版本不能生成GIF缩略图',
			17	=>'此PHP版本不能生成JPEG缩略图',
			18	=>'此PHP版本不能生成图片缩略图',
			19	=>'An error occurred while attempting to copy the source image . 
					Your version of php ('.phpversion().') may not have this image type support.',
			20	=>'An error occurred while attempting to create a new image.',
			21	=>'An error occurred while copying the source image to the thumbnail image.',
			22	=>'An error occurred while saving the thumbnail image to the filesystem. 
					Are you sure that PHP has been configured with both read and write access on this folder?',
			);
		if ($this->errno == 0)
			return false;
		else
			//return $this->errno;	//返回错误数字代码
			return $uploadClassError[$this->errno];
	}
	
	//建目录函数，其中参数$directoryName最后没有"/"，
	//要是有的话，以'/'打散为数组的时候，最后将会出现一个空值
	function makeDirectory($directoryName) {
		$directoryName = str_replace("\\","/",$directoryName);
		$dirNames = explode('/', $directoryName);
		$total = count($dirNames) ;
		$temp = '';
		$tempi=0;
		if($dirNames[$total]==""){$tempi=1;}
		for($i=0; $i<($total-$tempi); $i++) {
			$temp .= $dirNames[$i].'/';
			if (!is_dir($temp)) {
				$oldmask = umask(0);
				if (!mkdir($temp, 0777)) exit("不能建立目录 $temp");
				chmod($temp,0777);
				umask($oldmask);
			}
			//chmod($temp,0777);
		}
		return true;
	}
	
}
	
/*
上传文件例子：

if($_FILES['uploadinput']['name'] <> ""){
   //包含上传文件类
   require_once ('class_upload.php');
   //设置文件上传目录
   $savePath = "upload";
   //创建目录
   makeDirectory($savePath);
   //允许的文件类型
   $fileFormat = array('gif','jpg','jpge','png');
   //文件大小限制，单位: Byte，1KB = 1000 Byte
   //0 表示无限制，但受php.ini中upload_max_filesize设置影响
   $maxSize = 0;
   //覆盖原有文件吗？ 0 不允许   1 允许 
   $overwrite = 0;
   //初始化上传类
   $f = new clsUpload( $savePath, $fileFormat, $maxSize, $overwrite);
   //如果想生成缩略图，则调用成员函数 $f->setThumb();
   //参数列表: setThumb($thumb, $thumbWidth = 0,$thumbHeight = 0)
   //$thumb=1 表示要生成缩略图，不调用时，其值为 0
   //$thumbWidth   缩略图宽，单位是像素(px)，留空则使用默认值 130
   //$thumbHeight 缩略图高，单位是像素(px)，留空则使用默认值 130
   $f->setThumb(1);
  
   //参数中的uploadinput是表单中上传文件输入框input的名字
   //后面的0表示不更改文件名，若为1，则由系统生成随机文件名
   if (!$f->run('uploadinput',0)){
    //通过$f->errmsg()只能得到最后一个出错的信息，
    //详细的信息在$f->getInfo()中可以得到。
    echo $f->errmsg()."<br>\n";
   }
   //上传结果保存在数组returnArray中。
   echo "<pre>";
   print_r($f->getInfo());
   echo "</pre>";
}

<form enctype="multipart/form-data" action="" method="POST">
Send this file: <br />
<input name="uploadinput[]" type="file"><br />
<input name="uploadinput[]" type="file"><br />
<input type="submit" value="Send File"><br />
</form> 

*/
?>