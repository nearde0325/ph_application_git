<?php
//namespace application\models\DbTable\Products\Stock;
/**
 * 
 * @author Norman
 * @name Model_DbTable_Products_Stock_Transferadjuststatus
 * 
 */
class Model_DbTable_Products_Stock_Kaktstatusrecord extends Zend_Db_Table_Abstract {
	/**
	 * 
	 * @var string $_name Db table Name
	 */
	
	
	protected $_name = 'kakt_status_record';
	
	/**
	 * Get Single Line of Record
	 * @param int $id
	 * @return array
	 */
	public function getRecord($id){
		$row = $this->fetchRow("`id_record` =".$id );
		if(!$row) return false;
		return $row->toArray();
		
	}
	/**
	 * Insert one line of record
	 * @param int $idKakt  KAKT ID not the number
	 * @param int $statusCode check status code table 
	 * @param string $staffName staff Name
	 * @param string $comm Comment on the change
	 */
	public function addRecord($idKakt,$statusCode,$staffName,$comm = NULL){
		
		$dateToday = Model_DatetimeHelper::dateToday();
		$timeNow = Model_DatetimeHelper::timeNow();
		
		$data = array(
				"id_note" => $idKakt,
				"code_status" =>$statusCode,
				"date_change" => $dateToday,
				"time_change" => $timeNow,
				"staff_name" => $staffName,
				"comment" => $comm
				);
		
		$this->insert($data);
		
	}
	/**
	 * List All Status for Single KAKT ID
	 * @param int $idKakt
	 * @return array
	 */
	public function listRecordByID($idKakt){
		
		$rows = $this->fetchAll("`id_note` =".$idKakt,"id_record DESC");
		if(!$rows) return false;
		return $rows->toArray();
		
	}
}

?>