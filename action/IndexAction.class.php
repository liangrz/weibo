<?php
class IndexAction{
	private $dbMysql;
	public function index(){
		session_start();
		//头
		$loginModel = new LoginModel();
		if(!empty($_SESSION['id'])){
			$loginModel->on();
		}else{
			$loginModel->off();
		}
		$loginTip = $loginModel->getLoginTip();
		include('./views/head.html');
		
		//中
		$weiboModel = new WeiboModel();
		$rows = $weiboModel->getRows();
		foreach($rows as $arr){
			include('./views/model.html');
		}
		//尾
		if(!empty($_SESSION['id'])){
			$uid = $_SESSION['id'];
			$uname = $_SESSION['user'];
		}else{
			$uid = '';
			$uname = '';
		}
		
		include('./views/foot.html');
	}
}
?>