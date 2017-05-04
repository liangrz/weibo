<?php
/*
class login{
	private $user;
	private $pwd;
	public function __construct($user,$pwd){
		$this->user = $user;
		$this->pwd = $pwd;
	}
	public function check_empty(){
		
	}
	public function check_yzm(){
		
	}
	public function check_len(){
		
	}
	public function check_user(){
		
	}
	public function check_pwd(){
		
	}
	public function write_session(){
		
	}
	public function write_cookie(){
		
	}
}
*/

//class register extends login{
	//public function write_sql(){
		
	//}
//}


function has_cookie(){//如果有cookie就注入并验证
	if(array_key_exists('name',$_COOKIE)&&array_key_exists('pwd',$_COOKIE))	
		return true;
	else{
		return false;
	}
}
function deal_session(){
	session_start();
	if(!array_key_exists('user',$_SESSION)){
		header('location:./login.php');
		exit;
	}
}
function login($user,$pwd){
	if($user == ''||$pwd ==''){
		return $err = 'empty';
	}
	//验证码——————————
	//验证长度
	if(strlen($user) > 16 || strlen($pwd) >16){
		return 'len';
	}
	//验证是否存在该账号
	$db = new DBMysqlBase('ex42weibo');
	$rs = $db->select('user','*',"`user`='{$user}'",'','');
	if(!$rs){
		return 'noexist';
	}
	//验证密码
	$pwd = md5($pwd);
	if($rs[0]['pwd'] != $pwd){
		return 'errpwd';
	}
	//写入session cookie
	$_SESSION['user'] = $user;
	$_SESSION['id'] = $rs[0]['id'];
}
function register($user,$pwd1,$pwd2){
	if($user == ''||$pwd1 ==''||$pwd2 ==''){
		return $err = 'empty';
	}
	//验证码
	//验证长度
	if(strlen($user) > 16 || strlen($pwd1) >16){
		return 'len';
	}
	//密码统一
	if($pwd1 != $pwd2){
		return 'pwd';
	}	
	//验证是否存在该账号
	$db = new DBMysqlBase('ex42weibo');
	$rs = $db->select('user','*',"`user`='{$user}'",'','');
	if($rs){
		return 'exist';
	}
	//写入sql session cookie
	$pwd = md5($pwd1);
	$arr = array('user'=>$user,'pwd'=>$pwd);
	$tmp = $db->insert('user',$arr);
	if(!$tmp){
		return 'err';
	}
	$_SESSION['user'] = $user;
	$_SESSION['id'] = $tmp;
}
function logout(){
	unset($_SESSION['user']);
	unset($_SESSION['id']);
	setcookie('name','',time()-1);
	setcookie('pwd','',time()-1);
	header('location:./index.php');
}

function login_cookie($user,$pwd){
	setcookie('user',$user,time()+3600);
	setcookie('pwd',$pwd,time()+3600);
	session_start();
	$_SESSION['user'] = $user;
}
?>