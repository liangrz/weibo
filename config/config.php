<?php
//include('./smarty/smarty.class.php');
/*
$smarty = new Smarty();
$smarty->setTemplateDir('./html');
$smarty->setCompileDir('./html_c');
*/
function __autoload($class_name){
	/*
	Action: xxxAction.class.php
	Base: xxxBase.class.php
	Model: xxxModel.class.php
	*/
	if(strpos($class_name,'Action')){
		$path = './action/';
	}else if(strpos($class_name,'Base')){
		$path = './base/';
	}else if(strpos($class_name,'Model')){
		$path = './model/';
	}else{
		exit($class_name.' not found');
	}
	$path = $path.$class_name.'.class.php';
	include($path);
}
?>