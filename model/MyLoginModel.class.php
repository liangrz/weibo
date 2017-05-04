<?php
class MyLoginModel extends LoginModel{
	public function on(){
		$loginTip = "<input id = 'write' type = 'button' class = 'fl' value='发布'>
		<a href = './logout.php' class = 'fl'>退出</a><a href = '?' class = 'fr'>所有微博</a>";
		$this->setLoginTip($loginTip);
	}
}
?>