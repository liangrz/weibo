<?php
include('./Base/DBMysqlBase.class.php');
include('./Base/LoginBase.class.php');
session_start();
var_dump($_SESSION);
if($_POST){
	$tmp = login($_POST['user'],$_POST['pwd']);
}
header('location:./index.php');

?>