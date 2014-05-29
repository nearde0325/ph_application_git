<?php
/*
 Faulty Status Change 
*/
class Model_DbTable_Faulty_Statuscode extends Zend_Db_Table_Abstract {
	
	protected $_name = 'faulty_status_code';
	
	
	public function getStatusCode($idHandle){
		
		$row = $this->fetchRow("`id_handle` = ".(int)$idHandle);
		if(!$row){return false;}
		
		return $row->toArray();
		
		
	}
	public function getStatusDes($idHandle){
		
		$row = self::getStatusCode($idHandle);
		if(!$row){
			return false;
		}
		
		return $row['method_handle'];		
	}
		
		
}
?>