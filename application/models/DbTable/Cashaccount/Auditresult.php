<?php

class Model_DbTable_Cashaccount_Auditresult extends Zend_Db_Table_Abstract {

	protected $_name = 'cashaccount_audit';
	
	public function getAuditresult( $id){
		
		$row = $this->fetchRow("`id` = ". $id);
		if(!$row) return false;
		return $row->toArray();		
		
		}
		
	public function addAuditresult( $shopCode , $dateBegin , $auditPass , $auditComment , $staffName , $auditDate){
		
		$data = array(
			
         "shop_code" =>  $shopCode ,
         "date_begin" =>  $dateBegin ,
         "audit_pass" =>  $auditPass ,
         "audit_comment" =>  $auditComment ,
         "staff_name" =>  $staffName ,
         "audit_date" =>  $auditDate 
	
			);
		$this->insert($data);
		
		}
		
	public function updateAuditresult(  $id ,  $shopCode , $dateBegin , $auditPass , $auditComment , $staffName , $auditDate){
		$data = array(
			
         "shop_code" =>  $shopCode ,
         "date_begin" =>  $dateBegin ,
         "audit_pass" =>  $auditPass ,
         "audit_comment" =>  $auditComment ,
         "staff_name" =>  $staffName ,
         "audit_date" =>  $auditDate 
	
			);
			
		$this->update($data,"`id` = ". $id);
		}
		
	public function deleteAuditresult( $id){
		
		$this->delete("`id` = ". $id);
		
		}	
	
	public function listAll(){
		
		$rows =$this->fetchAll("1","id DESC ");
		if(!$rows) return false;
		return $rows->toArray();			
		
		}	
	public function getAuditByShopByDate($shopCode,$dateBegin){
		
		$whereStr = "`shop_code` LIKE '{$shopCode}' AND `date_begin` = '{$dateBegin}'";
		$rows =$this->fetchAll($whereStr);
		if(empty($rows)) return false;
		return $rows->toArray();
	}	
	
	
}



?>