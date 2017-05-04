<?php
if(empty($_GET['action'])){
	return 'err';
}
$action = $_GET['action'];
include('./base/DBMysqlBase.class.php');
$db = new DBMysqlBase('ex42weibo');
if($action == 'weibo'){
	
}else if($action == 'ask'){
	$rs = $db->select('comment',"*","wid={$_POST['wid']}",'id','');
	echo  json_encode($rs);
}else if($action == 'comment'){
	$arr= array();
	foreach($_POST as $k=>$v){
		$arr[$k] = $v;
	}
	$arr['create_time'] = time();
	$tmp = $db->insert('comment',$arr);
	if(!$tmp){
		echo 'err';
	}
}else if($action == 'del'){
	//echo "id={$_POST['wid']}";exit;
	$tmp1 =$db->del('weibo',"id={$_POST['wid']}");
	$tmp2 = $db->del('comment',"wid={$_POST['wid']}");
	if(!$tmp1||!$tmp2){
		echo 'err';
	}
}else if($action == 'write'){
	$arr = array(
		'create_time'=>time(),
		'uid'=>$_POST['uid'],
		'content'=>$_POST['content'],
		'zan'=>0
	);
	$tmp = $db->insert('weibo',$arr);
	echo $tmp;
}else if($action == 'zan'){
	$set = 'zan=zan+1';
	$wid = "id={$_POST['wid']}";
	$tmp = $db->update('weibo',$set,$wid);
	if(!$tmp){
		echo 'err';
	}
}else if($action == 'check'){
	$rs = $db->select('user','pwd',"user='{$_POST['user']}'",'','');
	$pwd = md5($_POST['pwd']);
	if($rs&&$pwd == $rs[0]['pwd']){
		$arr = array(true,'');
	}else{
		$arr = array(false,'账号或者密码错误');
	}
	echo json_encode($arr);
}

?>