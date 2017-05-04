<?php
class LoginModel{
	private $loginTip;
	public function on(){
		$loginTip = "<input id = 'write' type = 'button' class = 'fl' value='发布'>
		<a href = './logout.php' class = 'fl'>退出</a><a href = '?go=My' class = 'fr'>我的微博</a>";
		$this->setLoginTip($loginTip);
	}
	public function off(){
		$loginTip = "<input id = 'register' type = 'button' class = 'fr' value = '注册'>
		<input id = 'login' type = 'button' class = 'fr' value='登录'>";
		$this->setLoginTip($loginTip);
	}
	public function setLoginTip($loginTip){
		$this->loginTip = $loginTip;
	}
	public function getLoginTip(){
		return $this->loginTip;
	}
}
?>