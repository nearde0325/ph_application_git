<?php
/*
 this is the class which send automatic email when:
Something Done:

Something Wrong:
1) If download failed, Email me


*/
class Model_DbTable_Updatenews extends Zend_Db_Table_Abstract {
	
	protected $_name = 'update_news';
	
	public function getUpdate($id){
		$rows = $this->fetchRow("`id_update` = ".$id);
		return $rows->toArray();
	}
	
	public function getUpdateTitle($title){
		
		$row = $this->fetchRow("`title_update` LIKE '".$title."'","date_update DESC");
		if(!$row) return false;
		return $row['id_update'];
	}
	
	public function addUpdate($type,$title,$subType){
		$dateToday = Model_DatetimeHelper::dateToday();
		$data = array(
				"id_type" => $type,
				"id_sub_type"=>$subType,
				"date_update" => $dateToday,
				"title_update" => $title
				);
		$this->insert($data);
		
	}	
	public function listUpdate(){
		
		$dateToday = Model_DatetimeHelper::dateToday();
		$weekAgo = Model_DatetimeHelper::adjustDays("sub",$dateToday,30);

		$rows = $this->fetchAll("`date_update` > '".$weekAgo."'","date_update desc");
		if($rows){
			return  $rows->toArray();
		}
		
		return false;

	}
	
	public function deleteUpdate($id){
		
		$this->delete("`id_update` =".$id);
	}
	
	public function ifPriceUpdate($dateToCheck){
		
		$whereStr = "`title_update` LIKE 'Repair Parts Price/Description Has been Updated on ' AND `date_update` = '{$dateToCheck}' ";
		$rows = $this->fetchRow($whereStr);
		if(!$rows) return false;
		return true;
	}

	
}
?>