<?php

class Model_Products_Location extends Zend_Db_Table_Abstract {

	protected $_name = 'products_v1_location';
	
	public function getLocation( $idLocation){
		
		$row = $this->fetchRow("`id_location` = ". $idLocation);
		if(!$row) return false;
		return $row->toArray();		
		
		}
		
	public function addLocation( $codeProduct , $codeLocation , $typeLocation , $stockProduct , $lastUpdate , $lastUpdateTime , $updateBy){
		
		$data = array(
			
         "code_product" =>  $codeProduct ,
         "code_location" =>  $codeLocation ,
         "type_location" =>  $typeLocation ,
         "stock_product" =>  $stockProduct ,
         "last_update" =>  $lastUpdate ,
         "last_update_time" =>  $lastUpdateTime ,
         "update_by" =>  $updateBy 
	
			);
		$this->insert($data);
		
		}
		
	public function updateLocation(  $idLocation ,  $codeProduct , $codeLocation , $typeLocation , $stockProduct , $lastUpdate , $lastUpdateTime , $updateBy){
		$data = array(
			
         "code_product" =>  $codeProduct ,
         "code_location" =>  $codeLocation ,
         "type_location" =>  $typeLocation ,
         "stock_product" =>  $stockProduct ,
         "last_update" =>  $lastUpdate ,
         "last_update_time" =>  $lastUpdateTime ,
         "update_by" =>  $updateBy 
	
			);
			
		$this->update($data,"`id_location` = ". $idLocation);
		}
		
	public function deleteLocation( $idLocation){
		
		$this->delete("`id_location` = ". $idLocation);
		
		}	
	
	public function listAll(){
		
		$rows =$this->fetchAll("1","id_location DESC ");
		if(!$rows) return false;
		return $rows->toArray();			
		
		}
	public function getProductsByLocation($codeLocation){
		$whereStr = "`code_location` LIKE '".trim($codeLocation)."'";
		$rows = $this->fetchAll($whereStr,"type_location")->toArray();
		if(empty($rows)){
			return false;
		}
		return $rows;
	}
	public function getLocationsByProduct($codeProduct){
		$whereStr = "`code_product` LIKE '".trim($codeProduct)."'";
		$rows = $this->fetchAll($whereStr,"type_location")->toArray();
		if(empty($rows)){
			return false;
		}
		return $rows;		
		
		
	}
	public function getPLByProduct($codeProduct){
	
		$row = $this->fetchRow("`code_product` LIKE '".trim($codeProduct)."'");
		if(!$row) return false;
		
		return $row['code_location'];
	}
	public function removeProductFromLocation ($codeProduct,$codeLocation){
		
		$whereStr = $whereStr = "`code_product` LIKE '".trim($codeProduct)."' AND `code_location` LIKE '".$codeLocation."'";
		$this->delete($whereStr);
	}
	
}


?>