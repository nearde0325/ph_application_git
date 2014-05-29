<?php
/*
 Faulty Status Change 
*/
class Model_DbTable_Faulty_Status extends Zend_Db_Table_Abstract {
	
	protected $_name = 'faulty_status_record';
	
	
	public function getRecord(){
		
	}
	public function addRecord($idFaulty,$idStatus,$date,$staffName,$remark,$rmano= NULL,$tracking = NULL,$creditNote = NULL){
		
		$data = array(
				"id_rma" => $idFaulty,
				"id_status" => $idStatus,
				"date_record" => $date,
				"staff_name" => $staffName,
				"remark_status" => $remark,
				"sp_rma" => $rmano,
				"post_tracking" => $tracking,
				"credit_note" => $creditNote
				);		
		$this->insert($data);		
		
	}
	public function getLastRecord($idRma){
		$row = $this->fetchRow("`id_rma` =".$idRma,"id_faulty_status DESC");
		
		if(!$row) return false;
		
		return $row->toArray();		
	}
	public function getSupplierRma($idRma){
		$row = $this->fetchRow("`id_rma` =".$idRma." AND `sp_rma` IS NOT NULL ","id_faulty_status DESC");
		
		if(!$row) return false;
		
		return $row->toArray();		
		
		
	}
	public function listLastModifiedRecord(){
		
	}
	public function listRecordByRMAID(){
		
	}
	public function listByStatusHead($stHead,$exactly = false){
		$rows = $this->fetchAll("`remark_status` LIKE '".$stHead."%' ","date_record");
		if($exactly){
			
			$rows = $this->fetchAll("`remark_status` LIKE '".$stHead."' ","date_record");
		}
		if(!$rows) return false;
		return $rows->toArray();
		
	}
	public function listBySupplierRMA($rmaid){
		
	}
	public function listTrackingNo($trackingNo){
		
	}
		
		
}
?>