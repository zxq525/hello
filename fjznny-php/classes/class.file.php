<?php
/**
 * vwen.com
 *
 * @copyright  Copyright (c) 2010 VWEN.COM Inc. http://www.vwen.com
 * QQ: 19519959 E-mail:noerr@vip.qq.com
 */

/*
 本类用于对文件系统的处理
*/
class IO {
 var $result;// 操作返回结果，如方法返回值为 mixed，则成功操作结果可在此获得
 var $exec_cmd;//执行方法，暂时没应用到
 var $exist_dir;//创建目录时最后存在的目录，现用于 copy() 和 move()
 var $buffer_size;//文件读取缓冲区大小，根据服务应用规模和服务器配置修改，建议默认值
 /**
     * @]Class Name[= IO
     * @]Class URI[= System.IO
     * @]Purpose[=
     *     本类用于对文件系统的处理
     * @]Author[= SNakeVil <51JS,BU,PHPx> (snakevil@qq.com)
     * @]Version[= 1.1.1
     * @]Create[= 17:13 2004-3-25
     * @]Modifications[=
     *     4:04 2004-3-30
     *     + 修复 generate_path() 方法中存在的一些 BUG
     *     + 重新设计方法 no_comment()
     *     4:32 2004-3-29
     *     + 简化方法 list_dir() 的返回值
     *     + 增加方法 file_info() 获取文件或目录信息
     *     5:35 2004-3-28
     *     + 整理优化算法
     *     7:31 2004-3-27
     *     + 将错误处理抽象为基类
     *     + 增加方法 no_comment() 删除文件中 C 规范注释
     * @]See[=
     */
function IO(){
  //parent::SnkClass();
  $this->result=array();
  $this->exec_cmd="";
  $this->exist_dir="";
  $this->buffer_size=8192;
  return $this;
}

function error_occur($error_id=0xffff,$error_related="") { // ----0xffff---- 发生错误，但错误原因未知 
	if (is_int($error_id)) $this->error_id = $error_id; // 获取错误号 
	$this->error_related = $error_related; 
	return false; // 错误发生时返回 false 方便进一步处理 
} 

 /**
         * @]Method Name[= list_dir()
         * @]Purpose[=
         *     读取指定目录内容，返回内容数组
         * @]Parameter[=
         *     string $dir_path 指定目录路径，默认为当前目录
         * @]Return[= mixed 错误返回 FALSE，否则返回
         *     array(
         *         array("name","location","type"),
         *         ......
         *     )
         * @]Author[= SNakeVil <51JS,BU,PHPx> (snakevil@qq.com)
         * @]See[=
         */

 // 读取指定目录内容，返回内容数组
 function list_dir($path="."){
  if(!is_dir($path)) return $this->error_occur(oxooob,__FUNCTION__);
  if(!is_readable($path)) return $this->error_occur(0x0002,$path);
  $dh=@opendir($path);
  $result=array();
  $path=realpath($path);
  //// 保证目录绝对地址后带目录分隔符
  if($path[strlen($path)-1]!=DIRECTORY_SEPARATOR) $path.=DIRECTORY_SEPARATOR;

  // 使用 !== 防止处理名称为 0 或 FALSE 的文件、目录
  while(FALSE!=($fh=readdir($dh))){
   if($fh=="." || $fh=="..") continue;
   $i=$path.$fh;
   $t=array("name"=>$fh,"location"=>$i,"type"=>is_file($i)?1:(is_dir($i)?0:-1));
   $result[]=$t;
  }
  closedir($dh);
  unset($dh,$fh,$t,$i);
  clearstatcache();// 清除文件系统缓存
  return $this->result=$result;
 }
/**
         * @]Method Name[= file_info()
         * @]Purpose[=
         *     获取指定文件或目录的属性
         * @]Parameter[=
         *     string $dir_path 指定目录路径，默认为当前目录
         * @]Return[= mixed 错误返回 FALSE，否则返回
         *     array("name","location","type","size","access","change","modify","read","write"),
         * @]Author[= SNakeVil <51JS,BU,PHPx> (snakevil@qq.com)
         * @]See[=
         */

 //获取指定文件或目录的属性
 function file_info($path="."){
  $path=realpath($path);
  if(!$path) return $this->error_occur(oxoooa,__FUNCTION__);
  $result=array(
   "name"=>substr($path,strpos($path,DIRECTORY_SEPARATOR)+1),
   "location"=>$path,
   "type"=>is_file($i)?1:(is_dir($i)?0:-1),
   "size"=>filesize($path),
   "access"=>fileatime($path),
   "modify"=>filemtime($path),
   "change"=>filectime($path),
   "read"=>is_readable($path),
   "write"=>is_writeable($path)
  );
  clearstatcache();
  return $this->result=$result;
 }
/**
         * @]Method Name[= seek_file()
         * @]Purpose[=
         *     根据正则表达式条件，在相应目录及给定层次的子目录中搜索匹配的文件、目录
         * @]Parameter[=
         *     string $pattern 兼容 PERL 标准的正则表达式指明搜索匹配要求，会添加 /^ $/，默认为 .*
         *     string $path 进行搜索的目录路径，默认为当前路径
         *     enum $seesk_type 有 -1 0 1 三种可能值，0 仅文件夹，1 仅文件，-1 两者都包括，默认为 1
         *     int $sub_dir 搜索的子目录深度，指定目录不算，建议不要超过 5，默认为 0
         *     limit $limit 搜索结果限制，避免过度浪费系统资源，默认为 100
         * @]Return[= mixed 错误返回 FALSE，否则
         *     array(
         *         array(
         *             "name","locate","type"
         *         ),
         *         ......
         *     )
         * @]Author[= SNakeVil <51JS,BU,PHPx> (snakevil@qq.com)
         * @]See[=
         */

 //根据正则表达式条件，在相应目录及给定层次的子目录中搜索匹配的文件、目录
 function seek_file($path=".*",$path=".",$seek_type=1,$sub_dir_level=0,$limit=100){
  //check parameter
  $is_error=$seek_type!=1&&$seek_type!=0&&$seek_type!=1;
  $is_error=$is_error && (!is_int($sub_dir_level) || $sub_dir_level < 0);
  $is_error = $is_error && (!is_int($limit) || $limit < 1);
        if ($is_error) return $this->error_occur(0x000B, __FUNCTION__);
        unset($is_error);
        $result = array();
        /* array() == FALSE，所以需要使用 === */
        if (FALSE===$i=$this->list_dir($path)) return FALSE; // 如果不能列举目录，返回
        for ($j=0,$k=count($i);$j<$k;$j++) {
             if ($i[$j]["type"]==-1) continue; // 对于非目录非文件项目，跳过
             if ($i[$j]["type"]==0&&$sub_dir_level) { // 如果需要搜索下层目录
                if (FALSE===$l=$this->seek_file($pattern,$i[$j]["location"],$seek_type,($sub_dir_level - 1),$limit)) return FALSE;
                    $result = array_merge($result, $l); // 将下层目录搜索结果添加
                }
                if ($seek_type+$i[$j]["type"]==1||!preg_match("/^".$pattern."$/", $i[$j]["name"])) continue; // 如果不搜索当前类型，跳过
                $result[] = $i[$j];
                if (count($result)>=$limit) { // 截去超过要求的长度，离开列举
                    array_splice($result, $limit);
                    break;
                }
            }
        unset($i, $j, $k, $l);
        return $this->result = $result;

 }

	function write_file($filename,$data,$method="rb+",$iflock=1,$chmod=1){
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

  /*
         * @]Method Name[= del()
         * @]Purpose[=
         *     删除目录或文件夹,不管该目录下是否有文件或子目录，全部删除
         * @]Parameter[=
         *     string $path 指定要删除的内容路径，文件或目录均可
         * @]Return[= boolean 错误返回 FALSE，否则 TRUE
         * @]Author[= SNakeVil <51JS,BU,PHPx> (snakevil@qq.com)
         * @]See[=
         */
        function del($path="") {
            $path = realpath($path);
            if (!$path) return $this->error_occur(0x000A, __FUNCTION__);
            if (!is_dir($path)) {
                if (@unlink($path)) return TRUE; // 文件删除成功
                return $this->error_occur(0x0004, $path);
            } else {
                if (FALSE===$i=$this->list_dir($path)) return FALSE; // 不能列举目录
                for ($j=0,$k=count($i);$j<$k;$j++)
                    if (!$this->del($i[$j]["location"])) return FALSE; // 删除目录内容出错
                unset($i, $j, $k);
                return TRUE;
            }
        }

        /**
         * @]Method Name[= generate_path()
         * @]Purpose[=
         *     获取现有或不存在文件、目录的绝对地址
         * @]Parameter[=
         *     string $path 要获取地址的文件、目录现有相对、绝对地址
         * @]Return[= string 获得的地址
         * @]Author[= SNakeVil <51JS,BU,PHPx> (snakevil@qq.com)
         * @]See[=
         */
        function generate_path($path="") {
            $i = "/"==DIRECTORY_SEPARATOR ? "\\" : "/"; // 统一目录分割符
            $path = str_replace($i, DIRECTORY_SEPARATOR, strval($path));
            if ($path[strlen($path)-1]!=DIRECTORY_SEPARATOR) $path .= DIRECTORY_SEPARATOR;
            $i = strpos($path, DIRECTORY_SEPARATOR); // 获得路径中首个目录分割符的位置
            $ext = substr($path, $i+1);
            $path = substr($path, 0, $i+1);
            if ($i=realpath($path)) $path = $i; // 得到基本路径
            else {
                $ext = $path.$ext;
                $path = realpath(".");
            }
            if (strlen($ext)) { // 对剩余内容处理
                $ext = preg_replace("/[\:\*\?\"\<\>\|]/", "", explode(DIRECTORY_SEPARATOR, $ext));
                array_pop($ext);
                $path = explode(DIRECTORY_SEPARATOR, $path); // 建立目录层轴
                if ($path[count($path)-1]=="") array_pop($path);
                while (count($ext)) {
                    $i = array_shift($ext);
                    if ($i==".."&&count($path)>1) array_pop($path);
                    elseif (""!=str_replace(".", "", $i)) $path[] = $i;
                }
                $path = implode(DIRECTORY_SEPARATOR, $path);
            }
            unset($ext, $i);
            return $path;
        }

        /**
         * @]Method Name[= create_forder()
         * @]Purpose[=
         *     建立任意文件夹，相对或绝对路径皆可，深层建立亦可
         * @]Parameter[=
         *     string $path 要建立的最终目录路径
         * @]Return[= boolean 错误返回 FALSE，否则 TRUE
         * @]Author[= SNakeVil <51JS,BU,PHPx> (snakevil@qq.com)
         * @]See[=
         */
        function create_forder($path="") {
            $i = explode(DIRECTORY_SEPARATOR, $this->generate_path($path)); // 生成目录路径
            $path = array_shift($i);
            for ($j=0,$k=count($i);$j<$k;$j++) {
                $path .= DIRECTORY_SEPARATOR.$i[$j];
                if (!is_dir($path)) {
                    if ($this->exist_dir=="") $this->exist_dir = $path; // 记录最后存在的目录路径
                    if (!@mkdir($path)) return $this->error_occur(0x0003, substr($path, 0, strrpos($path, DIRECTORY_SEPARATOR)));
                }
            }
            if ($this->exist_dir=="") $this->exist_dir = $path;
            return TRUE;
        }

        /**
         * @]Method Name[= verify_file()
         * @]Purpose[=
         *     使用 MD5 算法比较两个文件是否相同
         * @]Parameter[=
         *     string $src 源文件路径
         *     string $dst 目标文件路径
         *     boolean $interal 对于超过 1MB 文件，设置 FALSE 省去 MD5 检验步骤，减轻服务器负担
         * @]Return[= boolean 错误返回 FALSE，否则 TRUE
         * @]Author[= SNakeVil <51JS,BU,PHPx> (snakevil@qq.com)
         * @]See[=
         */
        function verify_file($src="", $dst="", $interal=TRUE) {
            if (!is_file($src)||!is_file($dst)) return $this->error_occur(0x000B, __FUNCTION__);
            if (!is_readable($src)) return $this->error_occur(0x0006, $src);
            if (!is_readable($dst)) return $this->error_occur(0x0006, $dst);
            $i = filesize($src);
            if (filesize($dst)!=$i) { // 文件大小不等
                unset($i);
                return FALSE;
            }
            if ($i>1024*1024*1024&&!$interal) { // 对于 1MB 的文件，如果不要求精确检查，跳过
                unset($i);
                return TRUE;
            }
            unset($i);
            if (md5_file($src)!=md5_file($dst)) return FALSE; // 文件 MD5 效验不符合，内容不相同
            return TRUE;
        }

        /**
         * @]Method Name[= copy()
         * @]Purpose[=
         *     对任意文件夹、文件进行复制，相对或绝对路径皆可，文件复制完成后会进行效验，检查是否出错数据错误
         * @]Parameter[=
         *     string $src_path 指定要复制的源内容路径，文件或目录均可
         *     string $dst_path 指定要复制的目标内容路径，文件或目录均可，性质由 $src_path 决定，可为 $src_path 下层目录
         * @]Return[= boolean 错误返回 FALSE，否则 TRUE
         * @]Author[= SNakeVil <51JS,BU,PHPx> (snakevil@qq.com)
         * @]See[=
         */
        function copy($src="", $dst="", $sub=FALSE) {
            if (!$src=realpath($src)) return $this->error_occur(0x000B, __FUNCTION__);
            $dst = $this->generate_path($dst);
            if (is_dir($src)) { // 处理目录
                /*
                 * 关于算法的说明：
                 *     本来打算使用很简单的递归算法，遇神杀神，遇魔斩魔的，后来发现一个问题：如果目标路径
                 *   为源路径的后代路径怎么办？这样算法会不停的探测下去…
                 *     于是添加了 $this->exist_dir 属性，用来记录这一情况下目标路径中存在的部分。于是新的问
                 *   题出来了：如何保存这一属性？
                 *     将整个功能整合到 $this->copy() 方法中，那么必然需要在这个函数中记录 $this->exist_dir
                 *   的变化，于是乎需要另外的一个有效的方法来阻止每一次操作中对其的更改。
                 *     作为变通，我使用的隐藏参数 $sub，这个参数无论如何，只要算法不变，永远在参数表的最
                 *   后一个。因此，方法开始变得不稳定，但这也没有办法，只能希望程序员自己不要故意破坏。
                 *     在外部调用时，因为默认 FALSE，所以对 $this->exist_dir 写。内部递归时，显性 TRUE，不
                 *   该属性，保证有效性。
                 */
                if (!is_readable($src)) return $this->error_occur(0x0002, $src);
                if ($dst[strlen($dst)-1]!=DIRECTORY_SEPARATOR) $dst .= DIRECTORY_SEPARATOR;
                if (TRUE===$sub&&$src==$this->exist_dir) return TRUE; // 源路径为记录的目标路径
                if (TRUE!==$sub) $this->exist_dir = ""; // 记录创建目录前目标目录路径中存在的目录路径
                if (!$this->create_forder($dst)) return FALSE; // 创建目录
                if (FALSE===$i=$this->list_dir($src)) return FALSE; // 读取目录出错
                for ($j=0,$k=count($i);$j<$k;$j++) if (!$this->copy($i[$j]["location"], $dst.$i[$j]["name"],TRUE)) return FALSE;
                unset($i, $j, $k);
                RETURN TRUE;
            } else {
                if (!is_readable($src)) return $this->error_occur(0x0006, $src);
                if ($this->verify_file($src,$dst)) return TRUE;
                if (!copy($src,$dst)) return $this->error_occur(0x0007, $dst);
                if (!$this->verify_file($src,$dst)) {
                    @unlink($dst); // 复制文件失败删除新文件
                    return $this->error_occur(0x0007, $dst);
                }
                return TRUE;
            }
        }

        /**
         * @]Method Name[= move()
         * @]Purpose[=
         *     对任意文件夹、文件进行移动，相对或绝对路径皆可，文件移动完成后会进行效验，检查是否出错数据错误
         * @]Parameter[=
         *     string $src_path 指定要移动的源内容路径，文件或目录均可
         *     string $dst_path 指定要移动的目标内容路径，文件或目录均可，性质由 $src_path 决定，可为 $src_path 下层目录
         * @]Return[= boolean 错误返回 FALSE，否则 TRUE
         * @]Author[= SNakeVil <51JS,BU,PHPx> (snakevil@qq.com)
         * @]See[=
         */
        function move($src="", $dst="", $sub=FALSE) {
            if (!$src=realpath($src)) return $this->error_occur(0x000B, __FUNCTION__);
            $dst = $this->generate_path($dst);
            if (is_dir($src)) { // 处理目录
                if (!is_readable($src)) return $this->error_occur(0x0002, $src);
                if ($dst[strlen($dst)-1]!=DIRECTORY_SEPARATOR) $dst .= DIRECTORY_SEPARATOR;
                if (TRUE===$sub&&$src==$this->exist_dir) return TRUE;
                if (TRUE!==$sub) $this->exist_dir = "";
                if (!$this->create_forder($dst)) return FALSE;
                if (FALSE===$i=$this->list_dir($src)) return FALSE;
                for ($j=0,$k=count($i);$j<$k;$j++) if (!$this->move($i[$j]["location"], $dst.$i[$j]["name"],TRUE)) return FALSE;
                unset($i, $j, $k);
                if (FALSE===strpos($this->exist_dir,$src))
                    if (!@rmdir($src)) return $this->error_occur(0x0004, $src); // 对非目标目录的上层目录，删除
                return TRUE;
            } else {
                if (!is_readable($src)) return $this->error_occur(0x0006, $src);
                if ($this->verify_file($src,$dst)) return TRUE;
                if (!copy($src,$dst)) return $this->error_occur(0x0007, $dst);
                if (!$this->verify_file($src,$dst)) {
                    @unlink($dst);
                    return $this->error_occur(0x0007, $dst);
                }
                if (!@unlink($src)) return $this->error_occur(0x0006, $src); // 删除源文件
                return TRUE;
            }
        }

        /**
         * @]Method Name[= no_comment()
         * @]Purpose[=
         *     清除文件中 C 规范的注释
         * @]Parameter[=
         *     string $path 指定要执行操作的文件
         * @]Return[= boolean 错误返回 FALSE，否则 TRUE
         * @]Author[= SNakeVil <51JS,BU,PHPx> (snakevil@qq.com)
         * @]See[=
         */
        function no_comment($path="") {
            if (!is_file($path)) return $this->error_occur(0x000B, __FUNCTION__);
            if (!is_readable($path)) return $this->error_occur(0x0006, $path);
            if (!is_writeable($path)) return $this->error_occur(0x0007, $path);
            if (!$th=tmpfile()) return $this->error_occur(0x000C, $path); // 创建临时文件
            $fh = fopen($path, "r+b");
            if (!flock($fh,LOCK_EX)) { // 锁定文件
                fclose($fh);
                unset($fh);
                return $this->error_occur(0x0009, $path);
            }
            $fbuffer = fread($fh, $this->buffer_size*2); // 文件读取缓冲区
            $tbuffer = ""; // 临时文件缓冲区
            $in_dq = $in_sq = $in_lc = $in_bc = FALSE;
            while ($fblen=strlen($fbuffer)) { // 处理原始数据
                $fstats = feof($fh);
                for ($i=0;$i<$fblen;$i++) { // 分析文件内容
                    if (!$fstats&&$i+5>$fblen) break; // 文件未完全读取时临近缓冲区读取完成读取下一块文件内容
                    $j = substr($fbuffer, $i, 2);
                    $k = $j[0];
                    if ($j=="/*"&&!$in_dq&&!$in_sq&&!$in_lc) { // 不在字符串和行注释中，块注释开始
                        $in_bc = TRUE;
                        $i++;
                    } elseif ($j=="*/"&&$in_bc) { // 块注释结束
                        $in_bc = FALSE;
                        $i+=2;
                    } elseif ($j=="//"&&!$in_dq&&!$in_sq&&!$in_bc) { // 行注释开始
                        $in_lc = TRUE;
                        $i++;
                    } elseif ($in_lc&&($k=="\r"||$k=="\n")) $in_lc = FALSE; // 行注释结束
                    elseif ($j=="\\\\"||$j=="\\\""||$j=="\\'") { // 转义字符
                        $tbuffer .= $j;
                        $i++;
                        continue;
                    } elseif ($k=="\""&&!$in_sq&&!$in_bc&&!$in_lc) $in_dq = !$in_dq; // 双引号字符串开始、结束
                    elseif ($k=="'"&&!$in_dq&&!$in_bc&&!$in_lc) $in_sq = !$in_sq; // 单引号字符串开始、结束
                    if ($in_lc||$in_bc) continue; // 在注释中，跳过
                    $tbuffer .= $fbuffer[$i];
                }
                $fbuffer = substr($fbuffer, $i); // 抛弃读取过的部分
                unset($i, $j, $k);
                if (!$fstats) $fbuffer .= fread($fh, $this->buffer_size);
                if ($fstats||strlen($tbuffer)>=$this->buffer_size) { // 写入合法数据到临时文件
                    if (!fwrite($th,$tbuffer)) { // 写入失败，空间不足
                        fclose($th);
                        flock($fh, LOCK_UN);
                        fclose($fh);
                        unset($th, $fh, $in_dq, $in_sq, $in_lc, $in_bc, $i, $j, $k);
                        return $this->error_occur(0x000D, "");
                    }
                    $tbuffer = "";
                }
            }
            unset($fbuffer, $tbuffer, $fstats, $in_dq, $in_sq, $in_lc, $in_bc);
            rewind($fh); // 回移文件指针到文件首
            rewind($th);
            $i = $j = "";
            $k = 0;
            while (!feof($th)) { // 将临时文件数据写回源文件
                $i = fgets($th, $this->buffer_size);
                if ($j=="") { // 获得文件系统的换行符
                    $j= substr($i, -2);
                    if ($j=="\r\n") $k = 2;
                    elseif ($j[1]=="\r"||$j[1]=="\n") {
                        $k = 1;
                        $j = $j[1];
                    } else $j = "";
                }
                if (substr($i, -$k)==$j) {
                    $i = rtrim(substr($i, 0, -$k), " \t");
                    if (strlen($i)) fwrite($fh, $i.$j); // 清除右方空格
                    else continue;
                } else fwrite($fh, rtrim($i, " \t"));
            }
            fflush($fh); // 保存、关闭文件
            ftruncate($fh, ftell($fh));
            fclose($th);
            flock($fh, LOCK_UN);
            fclose($fh);
            unset($i, $j, $k, $fh, $th);
            return TRUE;
        }
		
    /**
     * @]Error List[=
     *     0x0001  指定目录不存在
     *     0x0002  指定目录无读取权限
     *     0x0003  指定目录无写入权限
     *     0x0004  指定目录无删除权限
     *     0x0005  指定文件不存在
     *     0x0006  指定文件无读取权限
     *     0x0007  指定文件无写入权限
     *     0x0008  指定文件无删除权限
     *     0x0009  指定文件无法锁定
     *     0x000A  指定对象不存在
     *     0x000B  方法指定参数不正确
     *     0x000C  无法创建临时文件
     *     0x000D  磁盘空间不足
     *     0x000E  
     *     0x000F  
     *     0x0010  
     *     0x0011  
     *     
     */
}

?>