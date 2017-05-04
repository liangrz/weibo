<?php
class PageBase{
	public $rowsEach;
	public $rowsTotal;
	public $pageNow;
	public $pageMax;
	public $flag;
	public function __construct($rows,$table,$pageNow){
		$this->rowsTotal = $this->getRowsTotal($table);//获取总条数
		$this->rowsEach = $rows;//每个分页展示的数据数
		$this->pageNow = $pageNow;
		$this->pageMax = ceil($this->rowsTotal/$rows);//最大页码
		$this->pageNow = $this->fixPage();//修正当前不合格页码
		$this->flag = $this->startOrEnd();
		//goto judge if pageTotal>5
	}
	private function getRowsTotal($table){
		include('./config/db.conf.php');
		$db = new DBMysqlBase();
		$db->select($table,'*','','','');
		if(empty($db->num_rows)){
			exit('数据总条数有错误');
		}
		return $db->num_rows;
	}
	private function fixPage(){//检查当前页面是否为负或者大于最大页面，并修正
		if(empty($this->pageNow) ||
			$this->pageNow<=0){
			return 1;
		}else if($this->pageNow>$this->pageMax){
			return $this->pageMax;
		}else{
			return $this->pageNow;
		}
	}
	private function showAllPage(){//全部展示
		$str = '';
		for($i=1;$i<$this->pageMax+1;$i++){
			$str .= " <a class = 'page' href = '?p={$i}'>{$i}</a> ";
		}
		return $str;
	}
	private function showFivePage(){//展示五页
		$now = $this->pageNow;
		$max = $this->pageMax;
		$str = '';
		for($i=$now-2;$i<$now+3;$i++){
			if($now+2>$max){//判断是否在最后1、 2页
				$now--;
				$i=$now-2-1;
			}else if($i < 1 ){//判断是否在第1、第2页
				$now++;
			//}else if($i+4 > $max_page){
			}else{
				$str .= " <a class = 'page' href = '?p={$i}'>{$i}</a> ";
			}
		}
		return $str;
	}
	private function startOrEnd(){//判断当前页面是否首页或者尾页，首页为1，尾页为2，其他为0
		$now = $this->pageNow;
		$max = $this->pageMax;
		if($now <= 1){
			return 1;
		}else if($now >= $max){
			return 2;
		}else{
			return 0;
		}
	}
	private function showPrevPage(){
		$flag = $this->flag;
		$prev = $this->pageNow-1;
		if($flag != 1){
			$str = " <a href='?p=1'>首页</a> ";
			$str .= "<a href = '?p={$prev}'>pre</a>";
			return $str;
		}
	}
	private function showNextPage(){
		$flag = $this->flag;
		$next = $this->pageNow+1;
		$max = $this->pageMax;
		if($flag != 2){
			$str = "<a href = '?p={$next}'>next</a>";
			$str .= " <a href=?p={$max}>尾页</a> ";
			return $str;
		}
	}
	public function showPage(){
		$str = $this->showPrevPage();
		if($this->pageMax>5){
			$str .= $this->showFivePage();
		}else{
			$str .= $this->showAllPage();
		}
		$str .= $this->showNextPage();
		return $str;
	}
}


?>
