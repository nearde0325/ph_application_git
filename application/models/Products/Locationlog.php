<?php


class Model_Products_Locationlog extends Zend_Db_Table_Abstract {

	protected $_name = 'products_v1_location_action_log';
	
	public function getLocationlog( $idLog){
		
		$row = $this->fetchRow("`id_log` = ". $idLog);
		if(!$row) return false;
		return $row->toArray();		
		
		}
		
	public function addLocationlog( $codeProduct , $codeLocation , $dateAction , $timeAction , $idUser , $action){
		
		$data = array(
			
         "code_product" =>  $codeProduct ,
         "code_location" =>  $codeLocation ,
         "date_action" =>  $dateAction ,
         "time_action" =>  $timeAction ,
         "id_user" =>  $idUser ,
         "action" =>  $action 
	
			);
		$this->insert($data);
		
		}
		
	public function updateLocationlog(  $idLog ,  $codeProduct , $codeLocation , $dateAction , $timeAction , $idUser , $action){
		$data = array(
			
         "code_product" =>  $codeProduct ,
         "code_location" =>  $codeLocation ,
         "date_action" =>  $dateAction ,
         "time_action" =>  $timeAction ,
         "id_user" =>  $idUser ,
         "action" =>  $action 
	
			);
			
		$this->update($data,"`id_log` = ". $idLog);
		}
		
	public function deleteLocationlog( $idLog){
		
		$this->delete("`id_log` = ". $idLog);
		
		}	
	
	public function listAll(){
		
		$rows =$this->fetchAll("1","id_log DESC ");
		if(!$rows) return false;
		return $rows->toArray();			
		
		}	
}


?>