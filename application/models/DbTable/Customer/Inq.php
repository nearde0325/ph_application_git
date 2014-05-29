<?php
class Model_DbTable_Customer_Inq extends Zend_Db_Table_Abstract {

	protected $_name = 'customer_inq';

	public function getInq( $idInq){

		$row = $this->fetchRow("`id_inq` = ". $idInq);
		if(!$row) return false;
		return $row->toArray();

	}

	public function addInq( $shopHead , $contentInq , $dateInq , $timeInq, $qtype = 1){

		$data = array(
					
				"shop_head" =>  $shopHead ,
				"content_inq" =>  $contentInq ,
				"date_inq" =>  $dateInq ,
				"time_inq" =>  $timeInq,
				"qtype" => $qtype

		);
		$this->insert($data);
		return $id = $this->getAdapter()->lastInsertId();

	}
	public function updateStaffName($idInq,$idStaff){
		
		$data = array(
				"id_staff" => $idStaff
				);
		$this->update($data,"`id_inq` = ". $idInq);
	}
	public function updateFeedBack($idInq,$dateFeedback,$etaFeedback,$content){
		
		$data = array(
				"feedback_date" => $dateFeedback,
				"eta_date" => $etaFeedback,
				"content" => $content
				);
		
		$this->update($data,"`id_inq` = ". $idInq);
	}

	public function updateInq(  $idInq ,  $shopHead , $contentInq , $dateInq , $timeInq){
		$data = array(
					
				"shop_head" =>  $shopHead ,
				"content_inq" =>  $contentInq ,
				"date_inq" =>  $dateInq ,
				"time_inq" =>  $timeInq

		);
			
		$this->update($data,"`id_inq` = ". $idInq);
	}

	public function deleteInq( $idInq){

		$this->delete("`id_inq` = ". $idInq);

	}

	public function listAll(){

		$rows =$this->fetchAll("1","id_inq DESC ");
		if(!$rows) return false;
		return $rows->toArray();

	}
	public function listInqByDate($dateBegin,$dateEnd){
		$strWhere = "`date_inq` >= '".$dateBegin."'  AND `date_inq` <= '".$dateEnd."'";
		$rows = $this->fetchAll($strWhere,"date_inq DESC");
		if(!$rows) return false;
		return $rows->toArray();	
	}
	public function shopRankDate($dateBegin,$dateEnd){
		
		$sqlstr = "SELECT DISTINCT `shop_head` , count( `shop_head` ) AS count
				FROM `customer_inq` 
				WHERE `date_inq`>='".$dateBegin."' AND `date_inq` <= '".$dateEnd."'
				GROUP BY `shop_head`
				ORDER BY count DESC";
		
		$db = $this->getAdapter();
		$stmt = $db->prepare($sqlstr);
		$stmt->execute();
		$result = $stmt->fetchAll();
		
		if(!$result) return false;
		return $result;
		
	}
}

?>