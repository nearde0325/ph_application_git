<?php
class Model_DbTable_Roster_Earlylateevent extends Zend_Db_Table_Abstract {

	protected $_name = 'early_late_event';

	public function getEarlylateevent( $idEvent){

		$row = $this->fetchRow("`id_event` = ". $idEvent);
		if(!$row) return false;
		return $row->toArray();

	}

	public function addEarlylateevent( $idStaff , $dateEvent , $eventCode , $staffComment){

		$data = array(
					
				"id_staff" =>  $idStaff ,
				"date_event" =>  $dateEvent ,
				"event_code" =>  $eventCode ,
				"staff_comment" =>  $staffComment

		);
		$this->insert($data);
		return $this->getAdapter()->lastInsertId();
	}

	public function updateEarlylateevent(  $idEvent ,  $idStaff , $dateEvent , $eventCode , $staffComment){
		$data = array(
					
				"id_staff" =>  $idStaff ,
				"date_event" =>  $dateEvent ,
				"event_code" =>  $eventCode ,
				"staff_comment" =>  $staffComment

		);
			
		$this->update($data,"`id_event` = ". $idEvent);
	}

	public function deleteEarlylateevent( $idEvent){

		$this->delete("`id_event` = ". $idEvent);

	}

	public function listAll(){

		$rows =$this->fetchAll("1","id_event DESC ");
		if(!$rows) return false;
		return $rows->toArray();

	}
	public function getLateEvent($idStaff,$dateBegin,$dateEnd){
		
		$intDateBegin = Model_DatetimeHelper::tranferToInt($dateBegin);
		$intDateEnd = Model_DatetimeHelper::tranferToInt($dateEnd) + 86400;
		
		$whereStr = "`id_staff` =".$idStaff." AND `event_code` =".START_LATE." AND `date_event` > ".$intDateBegin." AND `date_event` < ".$intDateEnd; 
		$rows = $this->fetchAll($whereStr);
		if(!$rows) return false;
		return count($rows);
	}
}
?>