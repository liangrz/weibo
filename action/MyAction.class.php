<?php
class MyAction{
	private $dbMysql;
	public function index(){
		session_start();
		//Í·
		$loginModel = new MyLoginModel();
		if(!empty($_SESSION['id'])){
			$loginModel->on();
		}else{
			$loginModel->off();
		}
		$loginTip = $loginModel->getLoginTip();
		include('./views/head.html');
		
		//ÖÐ
		$weiboModel = new MyWeiboModel();
		$rows = $weiboModel->getRows();
		foreach($rows as $arr){
			include('./views/model.html');
		}
		//Î²
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