<?php
$class = $method = '';
include('./config/config.php');
//var_dump(__FILE__);
if(!empty($_GET['go'])&&$_GET['go']=='My'){
	$class = $_GET['go'];
	$method = 'index';
}else{
	$class = 'Index';
	$method = 'index';	
}

$class .= 'Action';
$obj = new $class;
$obj->$method();
?>