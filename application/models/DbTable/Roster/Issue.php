<?php


class Model_DbTable_Roster_Issue extends Zend_Db_Table_Abstract {

	protected $_name = 'att_issue';
	
	public function getIssue( $idIssue){
		
		$row = $this->fetchRow("`id_issue` = ". $idIssue);
		if(!$row) return false;
		return $row->toArray();		
		
		}
		
	public function addIssue( $idStaff , $dateIssue , $typeIssue , $detail){
		
		$data = array(
			
         "id_staff" =>  $idStaff ,
         "date_issue" =>  $dateIssue ,
         "type_issue" =>  $typeIssue ,
         "detail" =>  $detail 
	
			);
		$this->insert($data);
		
		}
		
	public function updateIssue(  $idIssue ,  $idStaff , $dateIssue , $typeIssue , $detail){
		$data = array(
			
         "id_staff" =>  $idStaff ,
         "date_issue" =>  $dateIssue ,
         "type_issue" =>  $typeIssue ,
         "detail" =>  $detail 
	
			);
			
		$this->update($data,"`id_issue` = ". $idIssue);
		}
		
	public function deleteIssue( $idIssue){
		
		$this->delete("`id_issue` = ". $idIssue);
		
		}	
	
	public function listAll(){
		
		$rows =$this->fetchAll("1","id_issue DESC ");
		if(!$rows) return false;
		return $rows->toArray();			
		
		}	
}



?>