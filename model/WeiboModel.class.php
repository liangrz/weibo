<?php
class WeiboModel{
	private $rows;
	public function __construct(){
		$dbMysql = new DBMysqlBase();
		$rows = $dbMysql->left('weibo','content,create_time,weibo.id,zan,user','user','weibo.uid=user.id',"",'id');
		$this->setRows($rows);
		//goto бщжЄ
	}
	public function setRows($rows){
		$this->rows = $rows;
	}
	public function getRows(){
		return $this->rows;
	}
}
?>