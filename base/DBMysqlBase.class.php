<?php
abstract class database{
	abstract public function __construct();
	abstract public function connect($database);
	abstract public function create_database($database);
	abstract public function drop_database($database);
	abstract public function create_table($table);
	abstract public function drop_table();
	abstract public function select($table,$select,$where,$order,$limit);
	abstract public function insert($table, $arr);
	abstract public function update($table,$arr,$where);
	abstract public function alter($table,$old,$new);
	abstract public function del($table, $where);
}
class DBMysqlBase extends database{
	public $db;
	public $error;
	public $num_rows;
	private $ip;
	private $user;
	private $pwd;
	private $database;
	public function create_example(){
		//$db = new db_mysql('ex36lyb');
		//$db->create_table('user');
		//$db->alter_add('user','user');
		//$db->alter_add('user','pwd');
	}
	
	public function __construct(){
		$db = include('./config/db.conf.php');
		$this->ip = $db['ip'];
		$this->user = $db['user'];
		$this->pwd = $db['pwd'];
		$this->database = $db['database'];
		$this->connect($this->database);
	}
	public function connect($database){//已测试
		$db = new mysqli($this->ip,$this->user,$this->pwd,$database);
		if($db->connect_errno){//错误调整
			$this->error = $db->connect_error;
			return false;
		}
		$db->set_charset('utf8');
		$this->db = $db;
		return true;
	}
	public function create_database($database){
		if($this->db->query("create database if not exists {$database}")){
			return true;
		}else{
			return false;
		}
	}
	public function drop_database($database){
		if($this->db->query("drop database if exists {$database}")){
			return true;
		}else{
			return false;
		}
	}
	public function create_table($table){
		$tmp = $this->db->query("create table if not exists {$table}(
			id int(11) not null auto_increment comment 'id',
			primary key(id)
			)engine='MyISAM' default character set utf8"
		);
		return $tmp;
	}
	public function drop_table(){
		
	}
	public function select($table,$select,$where,$order,$limit){//已测试
		if($where){
			$where = " where {$where}";
		}
		if($order){
			$order = " order by {$order} desc";
		}
		if($limit){
			$limit = " limit {$limit}";
		}
		$sql = "select {$select} from {$table}{$where}{$order}{$limit}";
		$mysql_result = $this->db->query($sql);
		if(!$mysql_result){
			return false;
		}
		$this->num_rows = $mysql_result->num_rows;
		$arr = array();
		while($tmp = $mysql_result->fetch_array(MYSQL_ASSOC)){
			$arr[] = $tmp;
		}
		$mysql_result->free_result();
		return $arr;
	}
	function left($table,$select,$table2,$on,$where,$order){
		if($where){
			$where = " where {$where}";
		}
		if($order){
			$order = " order by {$order} desc";
		}
		$sql = "select {$select} from `{$table}` left join `{$table2}` on {$on}{$where}{$order}";
		$mysql_result = $this->db->query($sql);
		if(!$mysql_result){
			return false;
		}
		$this->num_rows = $mysql_result->num_rows;
		$arr = array();
		while($tmp = $mysql_result->fetch_array(MYSQL_ASSOC)){
			$arr[] = $tmp;
		}
		$mysql_result->free_result();
		return $arr;
}
	public function insert($table,$arr){//已测试
		//INSERT INTO `{table}` (`k1`,`k2`,`k3`) VALUES (`v1` , `v2` , `v3`);
		$sql = "INSERT INTO `{$table}` ( ";
		foreach( $arr as $k=>$v ){
			$sql .= "`{$k}`,";
		}
		$sql = trim($sql,',');
		$sql .= ") VALUES (";
		foreach($arr as $v){
			$sql .= "'{$v}',";
		}
		$sql = trim($sql,',');
		$sql .= ')';
		if($rs = $this->db->query($sql)){
			return $this->db->insert_id;//返回插入的这条数据的id是多少
		}else{
			$this->error = $this->db->error;//错误
			return false;
		}
	}
	public function update($table,$arr,$where){//已测试、单数据单或多字段更新,$arr允许非数组
		if($where){
			$where = " where {$where}";
		}
		if(is_array($arr)){
			$set = '';
			foreach($arr as $k=>$v){
				$set .= "`{$k}`='{$v}',";
			}
		}else{
			$set = $arr;
		}
		$set = trim($set,',');
		$sql = "update `{$table}` set {$set} {$where}";
		$rs = $this->db->query($sql);
		return $rs;
	}
	public function alter($table,$old,$new){//已测试
		$sql = "alter table {$table} change {$old} {$new}";
		return $this->db->query($sql);
	}
	public function alter_add($table,$field){
		$sql = "alter table {$table} add {$field}";
		return $this->db->query($sql);
	}
	public function del($table, $where){
		$sql = "delete from {$table} where {$where}";
		return $this->db->query($sql);
	}
}
?>