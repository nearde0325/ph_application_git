<?php
/*
 this is the class which send automatic email when:
Something Done:

Something Wrong:
1) If download failed, Email me


*/
class Model_DbTable_Barcodeproducttype extends Zend_Db_Table_Abstract {
	
	protected $_name = 'barcode_product_type';
	
	public function getType($id){
		
		$row = $this->fetchRow('`id_type`='.(int)$id);
		if(!$row){
			return false;
		}
		return $row->toArray();		
		
	}
	
	public function getTypeDes($id){
		$row = self::getType($id);
		
		if(!$row){
			return false;
		}
		return $row['des_type'];		
		
	}
	public function listType(){
		
		$rows = $this->fetchAll('1','id_type');
		
		return $rows;		
		
	}
		
}
?>