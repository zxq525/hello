<?php
/*
** 程序版本:pagecls分页类 1.0
** 调试环境:win2003+php5.2
** 制作日期:2007-09-20
** 程序设计:哆啦a梦 (http://www.vwen.com  noerr#163.com)
** 如您使用本程序，请保留以上信息。
*/

/*
** 这里讲解下我phplib的db_mysql.inc结合应用：
** $db->query("select * from 表 order by id asc");
** require_once(ROOT_PATH."inc/class.page.php");
** $pagecls = new pagecls($db->nf(),15,$page,"action=list{$urlquery}");
** while($db->next_record()) {
** echo $db->f("id");
** }
** echo $pagecls->pageinfo;		//这里输出分页信息，可以放在新建类后的后面任意位置
*/
class pagecls{
	var $recordtotal;				//记录总数
	var $pagesize;					//每页显示记录数
	var $pagenum;					//当前页码
	var $pagetotal;					//总页数
	var $pagestrname;				//分页的一些文字
	var $pageurl;					//页面的url
	var $pageinfo;					//最终的导航信息
	var $startrecord;				//起始纪录
	/*
     ** 构造函数
     */
    function pagecls($recordtotal,$pagesize=10,$pagenum=1,$pageurl)
    {     
        $this->recordtotal = $recordtotal;				//记录总数
        $this->pagesize = $pagesize;					//每页显示记录数
        $this->pagenum = (int)$pagenum;						//当前页码
		$this->pageurl = $pageurl;						//当前页码
		$this->pagetotal = ceil($recordtotal / $this->pagesize);
		
		$this->check_getdate();
		$this->get_startrecord();
		
		$this->pageinfo = $this->get_pageinfo();
    }

	function get_startrecord(){
		if($this->pagenum == 1 || $this->pagenum == 0){
			$this->startrecord = 0;
		}else{
			$this->startrecord = ($this->pagenum-1) * $this->pagesize;
		}
	}
	
	function check_getdate(){
		if($this->pagenum < 1) $this->pagenum = 1;
		if($this->pagenum > $this->pagetotal) $this->pagenum = $this->pagetotal;
	}


	function get_pageinfo(){
		if($this->pagetotal > 1){
			if($this->pagetotal<=5){
				$begin=1;$end=$this->pagetotal;
			}elseif($this->pagenum<=3){
				$begin=1;$end=5;
			}elseif(($this->pagetotal - $this->pagenum)<=3){
				$begin=$this->pagetotal - 4;$end=$this->pagetotal;
			}else{
				$begin=$this->pagenum-2;$end=$this->pagenum+2;
			}
		}else{
			$begin=0;$end=0;
		}
		
		
		if($this -> pagenum < 2){
			$pre=1;
		}else{
		  	$pre=$this->pagenum-1;
		}
		
		if ($this -> pagenum < $this->pagetotal){
			$next=$this->pagenum+1;
		}else{
		  	$next=$this->pagenum;
		}
		
		
	
		$array['numbtn']=$this->get_numberbtn();
		$array['page']=$this->pagenum;
		$array['total']=$this->recordtotal;
		$array['pagetotal']=$this->pagetotal;
		$array['pagesize']=$this->pagesize;
		$array['begin']=$begin;
		$array['end']=$end;
		$array['next']=$next;
		$array['pre']=$pre;
		$array['url']=$this->pageurl;
		return $array;
	}

	function get_numberbtn(){
		if($this->pagetotal > 1){
			if($this->pagetotal<=5){
				$begin=1;$end=$this->pagetotal;
			}elseif($this->pagenum<=3){
				$begin=1;$end=5;
			}elseif(($this->pagetotal - $this->pagenum)<=3){
				$begin=$this->pagetotal - 4;$end=$this->pagetotal;
			}else{
				$begin=$this->pagenum-2;$end=$this->pagenum+2;
			}
			for($i=$begin; $i<=$end; $i++) {
				$tempstr .=($this->pagenum != $i) ? '<a href="'.str_replace('{page}',$i,$this->pageurl).'" title="第'.$i.'页">'.$i.'</a>' : '<a href="'.str_replace('{page}',$i,$this->pageurl).'" title="第$i页" class="currpage">'.$i.'</a>';
			}
			return $tempstr;
		}else{
			return "";
		}
	}

}
?>