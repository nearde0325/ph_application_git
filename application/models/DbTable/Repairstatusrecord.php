<?php
/*
 this is the class which send automatic email when:
Something Done:

Something Wrong:
1) If download failed, Email me


*/
class Model_DbTable_Repairstatusrecord extends Zend_Db_Table_Abstract {
	
	protected $_name = 'repair_status_record';
	
	public function getStatus($id){
		
		$row = $this->fetchRow('`id`= 1');
		if(!$row){
			return false;
		}
		return $row->toArray();		
		
	}

	public function getLastStatus($idJob){
		
		$select = $this->_db->select()
		->from($this->_name,array('*'))
		->where('id_job = ?',$idJob)
		->order('time_record DESC')
		->limit(1,0);
		$result = $this->getAdapter()->fetchAll($select);
		
		if($result){
			return $result;
		}
		
		else{
			return false;
		}
				
		
	}
	
	public function getStatusList($idJob){
	
		$select = $this->_db->select()
				   	->from($this->_name,array('*'))
					->where('id_job = ?',$idJob)
					->order('time_record');
		$result = $this->getAdapter()->fetchAll($select);
		
		if($result){
			return $result;
			}

		else{
			return false;
			}		
		
	}
	public function insertStatus($idJob,$idStatus,$timeRecord,$staffRecord,$comment = null){
		
		$data = array(
				'id_job' => $idJob,
				'id_status' => $idStatus,
				'time_record' => $timeRecord,
				'staff_record'=>$staffRecord,
				'comment_record'=>$comment
				);
		$this->insert($data);
				
	}
	
	public function isJobReopen($idJob){
		$sList = self::getStatusList($idJob);
		
		foreach($sList as $key => $v){
			if($v["id_status"]==7){
				
				return $v["time_record"];
			}
			
		}
		
		return false;
		
		
	}
	
	public function jobBeenClosed($idJob){
		
		$whereStr = " `id_status` >= 80 AND `id_job` = {$idJob} ";
		$row = $this->fetchRow($whereStr);
		if(!$row) return false;
		return true;
	}
	
}
?>