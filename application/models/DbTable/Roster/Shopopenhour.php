<?php
//namespace application\models\DbTable\Roster;

class Model_DbTable_Roster_Shopopenhour extends Zend_Db_Table_Abstract {

	protected $_name = 'shop_open_hours';
	
	public function getShopOpenTime($shopHead,$date){
		$row = $this->fetchRow("`shop_head` LIKE '".$shopHead."'");
		$day = (int)date("N",strtotime($date));
		$rV = false;
		switch($day){
			case(1): $rV = $row["ot_mon"];break;
			case(2): $rV = $row["ot_tue"];break;
			case(3): $rV = $row["ot_wed"];break;
			case(4): $rV = $row["ot_thu"];break;
			case(5): $rV = $row["ot_fri"];break;
			case(6): $rV = $row["ot_sat"];break;
			case(7): $rV = $row["ot_sun"];break;			
		}
		
		return $rV;
	}
	public function getShopOpenTimeDay($shopHead,$dayOfWeek){
		
		$row = $this->fetchRow("`shop_head` LIKE '".$shopHead."'");
		$rV = false;
		switch($dayOfWeek){
			case(1): $rV = $row["ot_mon"];break;
			case(2): $rV = $row["ot_tue"];break;
			case(3): $rV = $row["ot_wed"];break;
			case(4): $rV = $row["ot_thu"];break;
			case(5): $rV = $row["ot_fri"];break;
			case(6): $rV = $row["ot_sat"];break;
			case(7): $rV = $row["ot_sun"];break;
		}
		
		return $rV;
		
		
	}
	public function getShopCloseTime($shopHead,$date){
		
		$row = $this->fetchRow("`shop_head` LIKE '".$shopHead."'");
		$day = (int)date("N",strtotime($date));
		$rV = false;
		switch($day){
			case(1): $rV = $row["ct_mon"];break;
			case(2): $rV = $row["ct_tue"];break;
			case(3): $rV = $row["ct_wed"];break;
			case(4): $rV = $row["ct_thu"];break;
			case(5): $rV = $row["ct_fri"];break;
			case(6): $rV = $row["ct_sat"];break;
			case(7): $rV = $row["ct_sun"];break;
		}
		
		return $rV;		
	}
	public function getShopCloseTimeDay($shopHead,$dayOfWeek){
	
		$row = $this->fetchRow("`shop_head` LIKE '".$shopHead."'");
		$rV = false;
		switch($dayOfWeek){
			case(1): $rV = $row["ct_mon"];break;
			case(2): $rV = $row["ct_tue"];break;
			case(3): $rV = $row["ct_wed"];break;
			case(4): $rV = $row["ct_thu"];break;
			case(5): $rV = $row["ct_fri"];break;
			case(6): $rV = $row["ct_sat"];break;
			case(7): $rV = $row["ct_sun"];break;
		}
	
		return $rV;
	}	
}

?>