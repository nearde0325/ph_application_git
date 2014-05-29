<?php
/*
 this is the class which send automatic email when:
Something Done:

Something Wrong:
1) If download failed, Email me


*/
class Model_DbTable_Barcodemodel extends Zend_Db_Table_Abstract {
	
	protected $_name = 'barcode_model';
	
	
	public function getModel($id){
		
		$row = $this->fetchRow('`id_model`='.(int)$id);
		if(!$row){
			return false;
		
		}
		return $row->toArray();
		
	}
	public function getModelDesByCode($modelCode){
		
		$row = $this->fetchRow("`code_model` LIKE '".trim($modelCode)."'");
		if(!$row){
			return false;
		
		}
		$rowArr =$row->toArray();
		
		return $rowArr['des_model'];
		
	}
	
	public function getModelIDByCode($modelCode){
	
		$row = $this->fetchRow("`code_model` LIKE '".trim($modelCode)."'");
		if(!$row){
			return false;
	
		}
		$rowArr =$row->toArray();
	
		return $rowArr['id_model'];
	
	}
		
	public function addmodel($idManu,$codeModel,$desModel){
		$data = array(
				"id_manu_model"=>$idManu,
				"code_model" =>$codeModel,
				"des_model" =>$desModel				
				);
		$this->insert($data);
		
	}
	public function updateModel($idModel,$idManu,$codeModel,$desModel){
		
		$data = array(
				"id_manu_model"=>$idManu,
				"code_model" =>$codeModel,
				"des_model" =>$desModel
		);		
		
		$this->update($data,"`id_model` =".$idModel);
	}
	public function deleteModel($idModel){
		
		$this->delete("`id_model` = ".$idModel);
		
	}
	public function listModelByManuID($idManu){
		
		$rows = $this->fetchAll('`id_manu_model` ='.$idManu,'id_model');
		
		return $rows->toArray();
		
	}
	
	public function listModelByManuCode($codeManu){
		$manu = new Model_DbTable_Barcodemanu();
		$row = $manu->getManuByCode($codeManu);
		//var_dump($row);
		if($row){
			
			return self::listModelByManuID($row['id_barcode']);
		}
		
		return false;
		
	}
	public function listAllModel(){
		$rows = $this->fetchAll('1','id_model');
		return $rows->toArray();		
	}
	

	
}
?>