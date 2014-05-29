<?php


class Model_System_Oldpwd extends Zend_Db_Table_Abstract {

	protected $_name = 'old_password_pool';
	
	public function getOldpwd( $idPwd){
		
		$row = $this->fetchRow("`id_pwd` = ". $idPwd);
		if(!$row) return false;
		return $row->toArray();		
		
		}
		
	public function addOldpwd( $idStaff , $pwd){
		
		$data = array(
			
         "id_staff" =>  $idStaff ,
         "pwd" =>  $pwd 
	
			);
		$this->insert($data);
		
		}
		
	public function updateOldpwd(  $idPwd ,  $idStaff , $pwd){
		$data = array(
			
         "id_staff" =>  $idStaff ,
         "pwd" =>  $pwd 
	
			);
			
		$this->update($data,"`id_pwd` = ". $idPwd);
		}
		
	public function deleteOldpwd( $idPwd){
		
		$this->delete("`id_pwd` = ". $idPwd);
		
		}	
	
	public function listAll(){
		
		$rows =$this->fetchAll("1","id_pwd DESC ");
		if(!$rows) return false;
		return $rows->toArray();			
		
		}	
		
	public function matchingPwd($pwd){
		
		
		$row = $this->fetchRow("`pwd` = '". $pwd."'");
		if(!$row) return false;
		return true;
	
	}	
		
}


?>