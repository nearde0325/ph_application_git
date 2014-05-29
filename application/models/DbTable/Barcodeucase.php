<?php
/*
 this is the class which send automatic email when:
Something Done:

Something Wrong:
1) If download failed, Email me


*/
class Model_DbTable_B4 extends Zend_Db_Table_Abstract {
	
	protected $_name = 'barcode_color_code';
	
	public function getCode($idColor){
		$row = $this->fetchRow('`id_color`='.(int)$idColor);
		if(!$row){
			return false;
		}
		return $row->toArray();	
		
		}
	public function getColorDes($idColor){
		$row = self::getCode($idColor);
		
		return $row['des_color'];
		
	}	
	public function listAllColors(){
		$rows = $this->fetchAll('1','id_color');
		
		return $rows;	
	}
	
}
?>