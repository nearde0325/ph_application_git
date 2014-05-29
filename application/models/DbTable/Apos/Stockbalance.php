<?php

class Model_DbTable_Apos_Stockbalance extends Zend_Db_Table_Abstract {
	
	public function getStockBalance($sCode,$location){
		$whereStr = "SCODE LIKE '".$sCode."' AND LOC LIKE '".$location."'";
		$row = $this->fetchRow($whereStr);
		if(!$row) return false;
		return $row->toArray();
		
	}
	
	public function listStockWhHave(){
		
		$whereStr = "LOC LIKE 'WH' AND BALANCE > 0 ";
		$rows = $this->fetchAll($whereStr);
		if(!$rows) return false;
		return $rows ->toArray();		
		
	}
	public function listStockWhEmpty(){
		
		$whereStr = "LOC LIKE 'WH' AND BALANCE <= 0 ";
		$rows = $this->fetchAll($whereStr);
		if(!$rows) return false;
		return $rows ->toArray();		
	}
	public function listShopHave($shop){
		
		$whereStr = "LOC LIKE '{$shop}' AND BALANCE > 0 ";
		$rows = $this->fetchAll($whereStr);
		if(!$rows) return false;
		return $rows ->toArray();
	}
	public function listShopEmpty($shop){
		
		$whereStr = "LOC LIKE '{$shop}' AND BALANCE <= 0 ";
		$rows = $this->fetchAll($whereStr);
		if(!$rows) return false;
		return $rows ->toArray();		
		
	}
	
}

?>