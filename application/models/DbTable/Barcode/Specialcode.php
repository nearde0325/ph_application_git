<?php
/*
 this is the class which send automatic email when:
Something Done:

Something Wrong:
1) If download failed, Email me


*/
class Model_DbTable_Barcode_Specialcode extends Zend_Db_Table_Abstract {
	
	protected $_name = 'barcode_special_code';
	
	public function getCode($id){
		$row = $this->fetchRow('`id_special`='.(int)$id);
		if(!$row){
			return false;
		}
		return $row->toArray();	
		
		}
	public function getSpecialDes($id){
		$row = self::getCode($id);
		if(!$row){
			return false;
		}		
		return $row['des_special_code'];
		
	}
	public function getSpecialByCode($codeSpecial,$idProductType){
		
		$row = $this->fetchRow("`code_special_code` LIKE '".trim($codeSpecial)."' AND `id_product_type` =".$idProductType);
		if(!$row){
			return false;
		
		}
		return $row->toArray();		
		
	}
	public function getSpecialDesByCode($codeSpecial,$idProductType){

		$rowArr = self::getSpecialByCode($codeSpecial,$idProductType);
		
		if(!$rowArr){
			return false;
		
		}		
		return $rowArr['des_special_code'];
		
	}	
	public function getCodeIdByCode($codeSpecial,$idProductType){

		$rowArr = self::getSpecialByCode($codeSpecial,$idProductType);
		
		if(!$rowArr){
			return false;
		
		}
		return $rowArr['id_special'];		
		
	}
	public function addSpecialCode($idProType,$codeSpecial,$desSpecial){
		$data = array(
			"id_product_type" => $idProType,
			"code_special_code"=>$codeSpecial,
			"des_special_code"=>$desSpecial						
				);
		
		$this->insert($data);
		
	}
	public function updateSpecialCode($idSpecial,$idProType,$codeSpecial,$desSpecial){
		$data = array(
				"id_product_type" => $idProType,
				"code_special_code"=>$codeSpecial,
				"des_special_code"=>$desSpecial
		);
		$this->update($data,"`id_special`=".$idSpecial);		
		
	}
	public function deleteSpecialCode($idSpecial){
		
		$this->delete("`id_special`=".$idSpecial);
		
	}
	
	public function listSpecialCodeByProductTypeID($idProType){
	
		$rows = $this->fetchAll('`id_product_type` ='.$idProType,'id_special');
	
		return $rows->toArray();
	
	}
	
	public function listSpCodeBySubProTypeCode($codeSubType){
		
		$subType = new Model_DbTable_Barcodeproductsubtype();
		$idMainType = $subType->getMainTypeIDByCode($codeSubType);

		return self::listSpecialCodeByProductTypeID($idMainType);
	
	}



	
}
?>