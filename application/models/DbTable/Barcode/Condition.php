<?php
/*
 this is the class which send automatic email when:
Something Done:

Something Wrong:
1) If download failed, Email me


*/
class Model_DbTable_Barcode_Condition extends Zend_Db_Table_Abstract {
	
	protected $_name = 'barcode_condition';
	
	public function getCond($id){
		$row = $this->fetchRow('`id_cond`='.(int)$id);
		if(!$row){
			return false;
		}
		return $row->toArray();	
		
		}
	public function getCondDes($id){
		$row = self::getCond($id);
		
		return $row['des_cond'];
		
	}
	public function getCondByCode($codeCond){
		
		$row = $this->fetchRow("`code_cond` LIKE '".trim($codeCond)."'");
		if(!$row){
			return false;
		
		}
		return $row->toArray();
		
	
	}
	public function getCondDesByCode($codeCond){

		$row = self::getCondByCode($codeCond);
		return $row['des_cond'];
		
	}	
	public function listAllConds(){
		$rows = $this->fetchAll('1','id_cond');
		
		return $rows;	
	}
	
}
?>