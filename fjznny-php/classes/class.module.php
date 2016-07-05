<?php
class Module {

	public $smarty = null;

	function Module()
	{
		global $smarty;
		$this->smarty = $smarty;
	}
	function LoadModule($modulename)
	{
		echo $modulename;
		//require $modulename.'/common.php';
	}
}

?>