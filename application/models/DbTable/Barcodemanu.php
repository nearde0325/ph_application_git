<?php
/*
 this is the class which send automatic email when:
Something Done:

Something Wrong:
1) If download failed, Email me


*/
class Model_DbTable_Barcodemanu extends Zend_Db_Table_Abstract {
	
	protected $_name = 'barcode_manu';
	
	
	public function getManu($id){
		
		$row = $this->fetchRow('`id_barcode`='.(int)$id);
		if(!$row){
			return false;
		
		}
		return $row->toArray();			
		}
	public function listManu(){
		
		$rows = $this->fetchAll('1','id_barcode');
		
		return $rows->toArray();	
		
		}
	public function getManuByCode($code){
	
		$row = $this->fetchRow("`code_barcode` LIKE '".trim($code)."'");
		if(!$row){
			return false;
		
		}
		return $row->toArray();			
		
		}

	public function getManuDesByCode($code){
		$row = self::getManuByCode($code);
		
		if(!$row){
			return false;
		
		}
		return $row['des_barcode'];		
		
		}	
	public function getManuDesByID($id){
		$row = self::getManu($id);
		if(!$row){
			return false;
		
		}
		return $row['des_barcode'];					
		
		}	
	public function addManu($id,$code,$des){
		
		$data = array(
				"id_barcode" => $id,
				"code_barcode" =>$code,
				"des_barcode"=>$des
				);
		$this->insert($data);	
		
		}
	public function updateManu($id,$code,$des){
		
		$data = array(
				"code_barcode" =>$code,
				"des_barcode"=>$des
		);		
		
		$this->update($data,"`id_barcode` = ".$id);
		
		}
	public function deleteManu($id){
		
		$this->delete("`id_barcode` = ".$id);
		
		}		
		
		
		
}
?>