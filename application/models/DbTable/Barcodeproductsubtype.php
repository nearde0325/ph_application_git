<?php
/*
 this is the class which send automatic email when:
Something Done:

Something Wrong:
1) If download failed, Email me


*/
class Model_DbTable_Barcodeproductsubtype extends Zend_Db_Table_Abstract {
	
	protected $_name = 'barcode_product_sub_type';
	
	public function getSubType($id){
		
		$row = $this->fetchRow('`id_sub_type`='.(int)$id);
		if(!$row){
			return false;
		}
		return $row->toArray();
		
	}
	public function addSubType($idMainType,$codeType,$desType){
		$data = array(
				"id_product_type" =>$idMainType,
				"code_sub_type" => $codeType,
				"des_sub_type" =>$desType	
				);
		$this->insert($data);
		
	}
	public function updateSubType($idSubType,$idMainType,$codeType,$desType){
		$data = array(
				"id_product_type" =>$idMainType,
				"code_sub_type" => $codeType,
				"des_sub_type" =>$desType
		);
		$this->update($data, "`id_sub_type` =".$idSubType);		
		
	}
	public function deleteSubType($idSubType){
		
		$this->delete("`id_sub_type` =".$idSubType);
	}
	public function listSubTypeByMainTypeID($idMainType){
		
		$rows = $this->fetchAll("`id_product_type` =".$idMainType,"id_sub_type");
		return $rows;
		
	}
	public function getSubtypeDesByCode($subtypeCode){
	
		$row = $this->fetchRow("`code_sub_type` LIKE '".trim($subtypeCode)."'");
		if(!$row){
			return false;
		
		}
		$rowArr =$row->toArray();
		
		return $rowArr['des_sub_type'];
	
	}
	
	public function getSubtypeIDByCode($subtypeCode){
	
		$row = $this->fetchRow("`code_sub_type` LIKE '".trim($subtypeCode)."'");
		if(!$row){
			return false;
	
		}
		$rowArr =$row->toArray();
	
		return $rowArr['id_sub_type'];
	
	}
	public function getMainTypeIDByCode($subtypeCode){
		$row = $this->fetchRow("`code_sub_type` LIKE '".trim($subtypeCode)."'");
		if(!$row){
			return false;
		
		}
		$rowArr =$row->toArray();
		
		return $rowArr['id_product_type'];		
	}
	
	public function listAllSubType(){
		
		$rows = $this->fetchAll('1',"id_sub_type");
		return $rows->toArray();
	}
	
}
?>