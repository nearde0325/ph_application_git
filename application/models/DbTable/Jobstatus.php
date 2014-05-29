<?php
/*
 this is the class which send automatic email when:
Something Done:

Something Wrong:
1) If download failed, Email me


*/
class Model_DbTable_Jobstatus extends Zend_Db_Table_Abstract {
	
	protected $_name = 'repair_job_status';
	
	public function getStatus($id){
		$row = $this->fetchRow('`id_status`='.(int)$id);
		if(!$row){
			return false;
		}
		return $row->toArray();		

	}
	
	public function getStatusDes($id){

		$statusRow = self::getStatus($id);
		return $statusRow['des_status'];
	
	}
	
	public function listStatus(){

		$rows = $this->fetchAll('1');
		return $rows->toArray();	
		
	}
		
}
?>