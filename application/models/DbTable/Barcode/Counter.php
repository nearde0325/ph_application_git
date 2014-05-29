<?php
/*
 this is the class which send automatic email when:
Something Done:

Something Wrong:
1) If download failed, Email me


*/
class Model_DbTable_Barcode_Counter extends Zend_Db_Table_Abstract {
	
	protected $_name = 'barcode_counter';
	
	
	public function getCounter($id){
		
		$row = $this->fetchRow('`id_counter`='.(int)$id);
		if(!$row){
			return false;
		
		}
		return $row->toArray();			
		}
		
	public function addCounter($idSubType,$idModel,$counter){
		
		$data = array(
				"id_product_sub_type" =>$idSubType,
				"id_model_specialcode"=>$idModel,
				"counter"=> $counter
				);
		
		$this->insert($data);
	}	
		
	public function updateCounter($id,$count){
		
		$data = array(
				"counter" =>$count
				);
		$this->update($data,"`id_counter` =".$id);
		
	}
	public function searchCounter($idSubType,$idModel){
		
		$row = $this->fetchRow("`id_product_sub_type`= ".$idSubType." AND `id_model_specialcode` =".$idModel);
		
		if(!$row){
			return false;
		
		}
		return $row->toArray();
		
		
	}
	public function searchCounterByCode($codeSubType,$codeModel){
		
		$subType = new Model_DbTable_Barcodeproductsubtype();		
		//model 
		$model = new Model_DbTable_Barcodemodel();
		
		$idSubType = $subType->getSubtypeIDByCode($codeSubType);
		$idModel = $model->getModelIDByCode($codeModel);
		
		return self::searchCounter($idSubType, $idModel);
		
		
	}
	public function searchCounterByCodeSpCode($codeSubType,$codeSpCode){
		
		$subType = new Model_DbTable_Barcodeproductsubtype();
		$spCode = new Model_DbTable_Barcode_Specialcode();
		
		$idMainType = $subType->getMainTypeIDByCode($codeSubType);
		$idSubType = $subType->getSubtypeIDByCode($codeSubType);
		$idSpCode = $spCode->getSpecialByCode($codeSpCode, $idMainType);
		
		return self::searchCounter($idSubType, $idSpCode['id_special']);
		
	}
	
	
	public function runCounter($idSubType,$idModel,$counter){
		
		$counterRow = self::searchCounter($idSubType, $idModel);
		self::updateCounter($counterRow['id_counter'], $counter);
	}	
	
		
		
}
?>