<?php

class Model_DbTable_Pr_Categorymodel extends Zend_Db_Table_Abstract {

	protected $_name = 'pr_category_model';
	
	public function getCateogrymodel( $id){
		
		$row = $this->fetchRow("`id` = ". $id);
		if(!$row) return false;
		return $row->toArray();		
		
		}
		
	public function addCateogrymodel( $idCategory , $mobileModel){
		
		$data = array(
			
         "id_category" =>  $idCategory ,
         "mobile_model" =>  $mobileModel 
	
			);
		$this->insert($data);
		
		}
		
	public function updateCateogrymodel(  $id ,  $idCategory , $mobileModel){
		$data = array(
			
         "id_category" =>  $idCategory ,
         "mobile_model" =>  $mobileModel 
	
			);
			
		$this->update($data,"`id` = ". $id);
		}
		
	public function deleteCateogrymodel( $id){
		
		$this->delete("`id` = ". $id);
		
		}	
	
	public function listAll(){
		
		$rows =$this->fetchAll("1","id ASC ");
		if(!$rows) return false;
		return $rows->toArray();			
		
		}
	public function getIdCategory($mobileModel){
		$strWhere = "`mobile_model` LIKE '{$mobileModel}'";
		$rows =$this->fetchRow($strWhere);
		if(!$rows) return false;
		return $rows->toArray();
		
	}		
}


?>