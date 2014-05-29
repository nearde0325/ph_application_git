<?php

class Model_Payroll_Staffbonustype extends Zend_Db_Table_Abstract {

	protected $_name = 'staff_bonus_type';
	
	public function getStaffbonustype( $id){
		
		$row = $this->fetchRow("`id` = ". $id);
		if(!$row) return false;
		return $row->toArray();		
		
		}
		
	public function addStaffbonustype( $typeBonus , $commentType){
		
		$data = array(
			
         "type_bonus" =>  $typeBonus ,
         "comment_type" =>  $commentType 
	
			);
		$this->insert($data);
		
		}
		
	public function updateStaffbonustype(  $id ,  $typeBonus , $commentType){
		$data = array(
			
         "type_bonus" =>  $typeBonus ,
         "comment_type" =>  $commentType 
	
			);
			
		$this->update($data,"`id` = ". $id);
		}
		
	public function deleteStaffbonustype( $id){
		
		$this->delete("`id` = ". $id);
		
		}	
	
	public function listAll(){
		
		$rows =$this->fetchAll("1","id ASC ");
		if(!$rows) return false;
		return $rows->toArray();			
		
		}	
}



?>