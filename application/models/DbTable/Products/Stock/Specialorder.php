<?php

class Model_DbTable_Products_Stock_Specialorder extends Zend_Db_Table_Abstract {

	protected $_name = 'products_v1_stock_special_order';

	public function getSpecialorder( $idOrder){

		$row = $this->fetchRow("`id_order` = ". $idOrder);
		if(!$row) return false;
		return $row->toArray();

	}

	public function addSpecialorder( $shopCode , $dateRecord , $timeRecord , $staffName , $orderComment){

		$data = array(					
				"shop_code" =>  $shopCode ,
				"date_record" =>  $dateRecord ,
				"time_record" =>  $timeRecord ,
				"staff_name" =>  $staffName ,
				"order_comment" =>  $orderComment
		);
		$this->insert($data);

	}

	public function updateSpecialorder(  $idOrder ,  $shopCode , $dateRecord , $timeRecord , $staffName , $orderComment){
		
		$data = array(					
				"shop_code" =>  $shopCode ,
				"date_record" =>  $dateRecord ,
				"time_record" =>  $timeRecord ,
				"staff_name" =>  $staffName ,
				"order_comment" =>  $orderComment
		);
			
		$this->update($data,"`id_order` = ". $idOrder);
	}
	public function managerAudit($idOrder,$idManager , $managerAuditDate , $managerAuditTime,$orderComment){
		
		$data = array(
				"id_manager" =>  $idManager ,
				"manager_audit_date" =>  $managerAuditDate ,
				"manager_audit_time" =>  $managerAuditTime ,
				"order_comment" =>  $orderComment
		);
			
		$this->update($data,"`id_order` = ". $idOrder);		
	}
	public function getSpecialorderByDate($shopCode,$dateBegin,$dateEnd){
		$whereStr = "`shop_code` LIKE '".$shopCode."' AND `date_record` >= '".$dateBegin."' AND `date_record` <='".$dateEnd."'";
		$row = $this->fetchRow($whereStr);
		if(!$row) return false;
		return $row->toArray();
	}
	
	public function deleteSpecialorder( $idOrder){

		$this->delete("`id_order` = ". $idOrder);

	}
	public function listAll(){

		$rows =$this->fetchAll("1","id_order DESC ");
		if(!$rows) return false;
		return $rows->toArray();

	}
}
?>