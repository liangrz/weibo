<?php
class PageBase{
	public $rowsEach;
	public $rowsTotal;
	public $pageNow;
	public $pageMax;
	public $flag;
	public function __construct($rows,$table,$pageNow){
		$this->rowsTotal = $this->getRowsTotal($table);//��ȡ������
		$this->rowsEach = $rows;//ÿ����ҳչʾ��������
		$this->pageNow = $pageNow;
		$this->pageMax = ceil($this->rowsTotal/$rows);//���ҳ��
		$this->pageNow = $this->fixPage();//������ǰ���ϸ�ҳ��
		$this->flag = $this->startOrEnd();
		//goto judge if pageTotal>5
	}
	private function getRowsTotal($table){
		include('./config/db.conf.php');
		$db = new DBMysqlBase();
		$db->select($table,'*','','','');
		if(empty($db->num_rows)){
			exit('�����������д���');
		}
		return $db->num_rows;
	}
	private function fixPage(){//��鵱ǰҳ���Ƿ�Ϊ�����ߴ������ҳ�棬������
		if(empty($this->pageNow) ||
			$this->pageNow<=0){
			return 1;
		}else if($this->pageNow>$this->pageMax){
			return $this->pageMax;
		}else{
			return $this->pageNow;
		}
	}
	private function showAllPage(){//ȫ��չʾ
		$str = '';
		for($i=1;$i<$this->pageMax+1;$i++){
			$str .= " <a class = 'page' href = '?p={$i}'>{$i}</a> ";
		}
		return $str;
	}
	private function showFivePage(){//չʾ��ҳ
		$now = $this->pageNow;
		$max = $this->pageMax;
		$str = '';
		for($i=$now-2;$i<$now+3;$i++){
			if($now+2>$max){//�ж��Ƿ������1�� 2ҳ
				$now--;
				$i=$now-2-1;
			}else if($i < 1 ){//�ж��Ƿ��ڵ�1����2ҳ
				$now++;
			//}else if($i+4 > $max_page){
			}else{
				$str .= " <a class = 'page' href = '?p={$i}'>{$i}</a> ";
			}
		}
		return $str;
	}
	private function startOrEnd(){//�жϵ�ǰҳ���Ƿ���ҳ����βҳ����ҳΪ1��βҳΪ2������Ϊ0
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
			$str = " <a href='?p=1'>��ҳ</a> ";
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
			$str .= " <a href=?p={$max}>βҳ</a> ";
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
