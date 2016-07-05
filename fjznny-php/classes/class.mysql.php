<?php
/**
 * vwen.com
 *
 * @copyright  Copyright (c) 2010 VWEN.COM Inc. http://www.vwen.com
 * QQ: 19519959 E-mail:noerr@vip.qq.com
 */

class db_mysql {

	var $version = '';
	var $querynum = 0;
	var $link;
	var $dbname;

	function connect($dbhost, $dbuser, $dbpw, $dbname = '', $pconnect = 0, $halt = TRUE, $dbcharset = '') {
		$this->dbname = $dbname;
		$func = empty($pconnect) ? 'mysql_connect' : 'mysql_pconnect';
		if(!$this->link = @$func($dbhost, $dbuser, $dbpw, 1)) {
			$halt && $this->halt('Can not connect to MySQL server');
		} else {
			if($this->version() > '4.1') {
				$thecharset = $dbcharset ? $dbcharset : '';
				$thecharset = !$thecharset && in_array(strtolower(SYS_CHARSET), array('gbk', 'big5', 'utf-8')) ? str_replace('-', '', SYS_CHARSET) : $thecharset;
				$serverset = $thecharset ? 'character_set_connection='.$thecharset.', character_set_results='.$thecharset.', character_set_client=binary' : '';
				$serverset .= $this->version() > '5.0.1' ? ((empty($serverset) ? '' : ',').'sql_mode=\'\'') : '';
				$serverset && mysql_query("SET $serverset", $this->link);
			}
			$dbname && @mysql_select_db($dbname, $this->link);
		}

	}

	/*	选择数据库 	*/
	function select_db($dbname) {
		return mysql_select_db($dbname, $this->link);
	}

	/*	取回查询结果的所有行到数组 	*/
	function fetch_array($query, $result_type = MYSQL_ASSOC) {
		return mysql_fetch_array($query, $result_type);
	}
	
	/*	取回查询结果的一行记录到数组 	*/
	function fetch_first($sql) {
		return $this->fetch_array($this->query($sql));
	}
	
	/*	取回查询结果的第一个字段值 	*/
	function result_first($sql) {
		return $this->result($this->query($sql), 0);
	}

	/*	执行查询语句，返回查询的ID句柄 	*/
	function query($sql, $type = '') {
		global $debug, $discuz_starttime, $sqldebug, $sqlspenttimes;

		$func = $type == 'UNBUFFERED' && @function_exists('mysql_unbuffered_query') ?
			'mysql_unbuffered_query' : 'mysql_query';
		if(!($query = $func($sql, $this->link))) {
			if($type != 'SILENT' && substr($type, 5) != 'SILENT') {
				$this->halt('MySQL Query Error', $sql);
			}
		}

		$this->querynum++;
		return $query;
		
	}

	/*	取回查询结果的影响行数 	*/
	function affected_rows() {
		return mysql_affected_rows($this->link);
	}

	function error() {
		return (($this->link) ? mysql_error($this->link) : mysql_error());
	}

	function errno() {
		return intval(($this->link) ? mysql_errno($this->link) : mysql_errno());
	}

	function result($query, $row) {
		$query = @mysql_result($query, $row);
		return $query;
	}

	function num_rows($query) {
		$query = mysql_num_rows($query);
		return $query;
	}

	function num_fields($query) {
		return mysql_num_fields($query);
	}

	function free_result($query) {
		return mysql_free_result($query);
	}

	function insert_id() {
		return ($id = mysql_insert_id($this->link)) >= 0 ? $id : $this->result($this->query("SELECT last_insert_id()"), 0);
	}

	function fetch_row($query) {
		$query = mysql_fetch_row($query);
		return $query;
	}

	function fetch_fields($query) {
		return mysql_fetch_field($query);
	}

	function tables()
	{
		$tables = array();
		$result = $this->query("SHOW TABLES");
		while($r = $this->fetch_array($result))
		{
			$tables[] = $r['Tables_in_'.$this->dbname];
		}
		//$this->free_result($result);
		return $tables;
	}

	function query_replace($sql, $replace)
	{
		$sql = trim($sql);
		$sql = preg_replace("/COUNT\(\s?\*\s?\)/Ui", ''. $replace .'', $sql, 1);
		return $sql;
	}

	function version() {
		if(empty($this->version)) {
			$this->version = mysql_get_server_info($this->link);
		}
		return $this->version;
	}

	function close() {
		return mysql_close($this->link);
	}

	function halt($message = '', $sql = '') {
		echo "<html><head><title>$very[name]</title><style type='text/css'>P,BODY{FONT-FAMILY:tahoma,arial,sans-serif;FONT-SIZE:12px;}A { TEXT-DECORATION: none;}a:hover{ text-decoration: underline;}TD { BORDER-RIGHT: 1px; BORDER-TOP: 0px; FONT-SIZE: 12px; COLOR: #000000;}</style><body>\n\n";
		echo "<div style='border:1px solid #FF000;padding:10px;margin:auto'>$message";
		echo "<br><br><b>The URL is</b>:<br>http://$_SERVER[HTTP_HOST]$REQUEST_URI";
		echo "<br><br><b>MySQL server error</b>:<br>".$this->error()."  ( ".$this->errno()." )";
		echo "</div>";
		exit;
	}
}

?>