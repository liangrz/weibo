<?php
class MyWeiboModel extends WeiboModel{
	public function __construct(){
		$dbMysql = new DBMysqlBase();
		$rows = $dbMysql->left('weibo','content,create_time,weibo.id,zan,user','user','weibo.uid=user.id',"uid={$_SESSION['id']}",'id');
		$this->setRows($rows);
		//goto бщжЄ
	}
}
?>