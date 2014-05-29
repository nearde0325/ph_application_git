<?php
//namespace application\models\DbTable\Roster;

class Model_DbTable_Roster_Uploadlog extends Zend_Db_Table_Abstract {
	
	protected $_name = 'roster_upload_log';
	
	public function getLog($id){
		$row = $this->fetchRow("`id_log` =".$id);
		if(!$row){return false;}
		
		return $row->toArray();
		
	}
	public function createLogFileName($orgFileName){
		
		return $orgFileName.uniqid();
				
	}	
	public function addLog($shopHead,$managerName,$orgFileName,$savedFileName,$ip,$os,$date,$time){
		
		$data = array(
			"shop_head_log" => $shopHead,
			"manager_name_log" => $managerName,
			"filename_org" => $orgFileName,
			"filename_saved" => $savedFileName,
			"upload_ip" => $ip,
			"upload_os" => $os,
			"upload_date" =>$date,
			"upload_time"=>$time										
			);
		$this->insert($data);		
	}
	
	public function listLogByShop($shopHead){
		$rows = $this->fetchAll("`shop_head_log` LIKE '".$shopHead."'" , "id_log DESC",30,0);
		
		if(!$rows){return false;}
		return $rows->toArray();
	}
	public function lastLogByShop($shopHead){
		$rows = $this->fetchAll("`shop_head_log` LIKE '".$shopHead."'" , "id_log DESC",1,0);
	
		if(!$rows){
			return false;
		}
		return $rows->toArray();
	}	

}

?>