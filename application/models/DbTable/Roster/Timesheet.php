<?php
class Model_DbTable_Roster_Timesheet extends Zend_Db_Table_Abstract {

	protected $_name = 'time_sheet';

	public function getTimesheet( $idSheet){

		$row = $this->fetchRow("`id_sheet` = ". $idSheet);
		if(!$row) return false;
		return $row->toArray();

	}

	public function addTimesheet( $idStaff , $shopHead , $shiftBegin , $shiftEnd){

		$data = array(
					
				"id_staff" =>  $idStaff ,
				"shop_head" =>  $shopHead ,
				"shift_begin" =>  $shiftBegin ,
				"shift_end" =>  $shiftEnd

		);
		$this->insert($data);

	}
	public function addSuTimesheet($idStaff,$shopHead,$shiftBegin,$shiftEnd){
		
		$data = array(
					
				"id_staff" =>  $idStaff ,
				"arra_shift_begin" =>  $shiftBegin ,
				"arra_shift_end" =>  $shiftEnd,
				"shop_head" =>  $shopHead ,
				"shift_begin" =>  $shiftBegin ,
				"shift_end" =>  $shiftEnd,
				"confirm_shift_begin" =>  $shiftBegin ,
				"confirm_shift_end" =>  $shiftEnd,
				"break_time" => 0, 
				"is_su" => 1
		);
		$this->insert($data);		
		
	}

	public function updateTimesheet(  $idSheet ,  $idStaff , $shopHead , $shiftBegin , $shiftEnd){
		$data = array(
					
				"id_staff" =>  $idStaff ,
				"shop_head" =>  $shopHead ,
				"shift_begin" =>  $shiftBegin ,
				"shift_end" =>  $shiftEnd

		);
			
		$this->update($data,"`id_sheet` = ". $idSheet);
	}
	public function managerAddTimesheet( $idStaff , $shopHead , $shiftBegin , $shiftEnd,$breakTime,$lock = null){
	
		$data = array(
					
				"id_staff" =>  $idStaff ,
				"shop_head" =>  $shopHead ,
				"shift_begin" =>  $shiftBegin ,
				"shift_end" =>  $shiftEnd,
				"confirm_shift_begin" =>  $shiftBegin ,
				"confirm_shift_end" =>  $shiftEnd,
				"break_time" =>$breakTime,
				"lock" => $lock
	
		);
		$this->insert($data);
	
	}
	public function managerArrangeTimesheet($idStaff,$shopHead,$shiftBegin,$shiftEnd,$breakTime,$approval = null){

		$data = array(
					
				"id_staff" =>  $idStaff ,
				"shop_head" =>  $shopHead ,
				"shift_begin" =>  $shiftBegin ,
				"shift_end" =>  $shiftEnd,
				"arra_shift_begin" =>  $shiftBegin ,
				"arra_shift_end" =>  $shiftEnd,
				"break_time" =>$breakTime,
				"arra_approval" => $approval
		
		);
		
		$this->insert($data);
		
	}
	public function managerConfirmArrangeTimesheet($idSheet,$shiftBegin,$shiftEnd,$breakTime,$approval = null){
		
		$data = array(
				"arra_shift_begin" =>  $shiftBegin ,
				"arra_shift_end" =>  $shiftEnd,
				"shift_begin" =>  $shiftBegin ,
				"shift_end" =>  $shiftEnd,
				"break_time" =>$breakTime,
				"arra_approval" => $approval
		);
		
		$this->update($data,"`id_sheet` = ". $idSheet);		
		
	}
	
	public function managerConfirmTimeSheet($idSheet,$shiftBegin,$shiftEnd,$breakTime,$lock = null){
		$data = array(
				"confirm_shift_begin" =>  $shiftBegin ,
				"confirm_shift_end" =>  $shiftEnd,
				"break_time" =>$breakTime,
				"lock" => $lock
				);
		echo "I will update";
		$this->update($data,"`id_sheet` = ". $idSheet);
	}	
	public function deleteTimesheet( $idSheet){

		$this->delete("`id_sheet` = ". $idSheet);

	}

	public function listAll(){

		$rows =$this->fetchAll("1","id_sheet DESC ");
		if(!$rows) return false;
		return $rows->toArray();

	}
	public function listTimesheetByStaffId($idStaff){
		$rows = $this->fetchAll("`id_staff` =".$idStaff,"id_sheet DESC");
		if(!$rows) return false;
		return $rows->toArray();		
		
	}
	
	public function listShiftByStaffIdToday($idStaff,$shopHead){
		
		
		$dateToday = Model_DatetimeHelper::dateToday();
		date_default_timezone_set('Australia/Melbourne');
		$intDateBegin = date("U",strtotime($dateToday));
		$intDateEnd = date("U",strtotime($dateToday))+86400;
		
		$row = $this->fetchRow(" `id_staff` = ".$idStaff." AND `shop_head` LIKE '".$shopHead."' AND `shift_begin` > ".$intDateBegin." AND `shift_end` < ".$intDateEnd);
		if(!$row) return false;
		return $row->toArray();
	}
	
	public function listShiftByStaffIdByDate($idStaff,$dateBegin,$dateEnd){
		date_default_timezone_set('Australia/Melbourne');
		$intDateBegin = date("U",strtotime($dateBegin));
		$intDateEnd = date("U",strtotime($dateEnd))+86400;
		
		$rows = $this->fetchAll(" `id_staff` = ".$idStaff." AND `shift_begin` > ".$intDateBegin." AND `shift_end` < ".$intDateEnd , "shop_head ASC");
		if(!$rows) return false;
		return $rows->toArray();		
		
	}
	public function listShiftByStaffIdByDateByShop($idStaff,$shopHead,$dateBegin,$dateEnd){
		date_default_timezone_set('Australia/Melbourne');
		$intDateBegin = date("U",strtotime($dateBegin));
		$intDateEnd = date("U",strtotime($dateEnd))+86400;
	
		$row = $this->fetchRow(" `id_staff` = ".$idStaff." AND `shift_begin` > ".$intDateBegin." AND `shift_end` < ".$intDateEnd." AND `shop_head` LIKE'".$shopHead."'  AND  ( ISNULL(`confirm_shift_begin`) OR `confirm_shift_begin` != `confirm_shift_end` ) ");
		if(!$row) return false;
		return $row->toArray();
	}
	public function listAllShiftByStaffIdByDateByShop($idStaff,$shopHead,$dateBegin,$dateEnd){
		date_default_timezone_set('Australia/Melbourne');
		$intDateBegin = date("U",strtotime($dateBegin));
		$intDateEnd = date("U",strtotime($dateEnd))+86400;
	
		$row = $this->fetchAll(" `id_staff` = ".$idStaff." AND `shift_begin` > ".$intDateBegin." AND `shift_end` < ".$intDateEnd." AND `shop_head` LIKE'".$shopHead."'");
		if(!$row) return false;
		return $row->toArray();
	}		
	public function listShiftByDateByShop($shopHead,$dateBegin,$dateEnd){
		date_default_timezone_set('Australia/Melbourne');
		$intDateBegin = date("U",strtotime($dateBegin));
		$intDateEnd = date("U",strtotime($dateEnd))+86400;		

		$rows = $this->fetchAll(" `shift_begin` > ".$intDateBegin." AND `shift_end` < ".$intDateEnd." AND `shop_head` LIKE '".$shopHead."'","id_staff ASC");
		if(!$rows) return false;
		return $rows->toArray();
		
	}
	public function listShiftByDate($dateBegin,$dateEnd){
		date_default_timezone_set('Australia/Melbourne');
		
		$intDateBegin = date("U",strtotime($dateBegin));
		$intDateEnd = date("U",strtotime($dateEnd))+86400;
		
		$rows = $this->fetchAll(" `shift_begin` > ".$intDateBegin." AND `shift_end` < ".$intDateEnd ,"id_staff ASC");
		if(!$rows) return false;
		return $rows->toArray();		
		
	}
	/**
	 * formate the time sheet to simple arr for confirm
	 */
	public static  function formatTsConfirm($arrTs,$shopHead){
		
		date_default_timezone_set('Australia/Melbourne');
		
		if($shopHead == "CLPC" || $shopHead == "CLIC" || $shopHead == "ADPC"  || $shopHead == "WLIC"  ){
			
			date_default_timezone_set('Australia/Adelaide');
		}
		
		$arrRes = array();
		
		foreach($arrTs as $ts){
			$tmpArr = array();
			
			$tmpArr[0] = $ts["id_staff"];
			$tmpArr[1] = ($ts["confirm_shift_begin"]=== null)?date("H",$ts["shift_begin"]):date("H",$ts["confirm_shift_begin"]);
			$tmpArr[2] = ($ts["confirm_shift_begin"]=== null)?date("i",$ts["shift_begin"]):date("i",$ts["confirm_shift_begin"]);
			$tmpArr[3] = ($ts["confirm_shift_end"]=== null)?date("H",$ts["shift_end"]):date("H",$ts["confirm_shift_end"]);
			$tmpArr[4] = ($ts["confirm_shift_end"]=== null)?date("i",$ts["shift_end"]):date("i",$ts["confirm_shift_end"]);			
			$tmpArr[5] = $ts["id_sheet"];
			$tmpArr[6] = "";//break time
			if($ts["break_time"] === null){
				if($ts["confirm_shift_begin"]=== null){
					//act
					$tmpArr[6]= (($ts["shift_end"]-$ts["shift_begin"])>=18000)?"30":"0";
				}
				else{
					$tmpArr[6]= (($ts["confirm_shift_end"]-$ts["confirm_shift_begin"])>=18000)?"30":"0";
				}
			}
			else{
				$tmpArr[6] = $ts["break_time"]/60;
			}
			$tmpArr[7] =($ts["confirm_shift_begin"]=== null)?round(($ts["shift_end"]-$ts["shift_begin"])/3600 - $tmpArr[6]/60,2):round(($ts["confirm_shift_end"]-$ts["confirm_shift_begin"])/3600 - $tmpArr[6]/60,2);
			$tmpArr[8] = $ts["is_su"];
			$arrRes[] = $tmpArr;
			
		}
		return $arrRes;
	}
	public static  function formatTsArra($arrTs,$shopHead){
	
		date_default_timezone_set('Australia/Melbourne');
	
		if($shopHead == "CLPC" || $shopHead == "CLIC" || $shopHead == "ADPC"  || $shopHead == "WLIC"  ){
				
			date_default_timezone_set('Australia/Adelaide');
		}
	
		$arrRes = array();
	
		foreach($arrTs as $ts){
			$tmpArr = array();
				
			$tmpArr[0] = $ts["id_staff"];
			$tmpArr[1] = date("H",$ts["arra_shift_begin"]);
			$tmpArr[2] = date("i",$ts["arra_shift_begin"]);
			$tmpArr[3] = date("H",$ts["arra_shift_end"]);
			$tmpArr[4] = date("i",$ts["arra_shift_end"]);
			$tmpArr[5] = $ts["id_sheet"];
			$tmpArr[6]= (($ts["arra_shift_end"]-$ts["arra_shift_begin"])>=18000)?"30":"0";		
			$tmpArr[7] =round(($ts["arra_shift_end"]-$ts["arra_shift_begin"])/3600 - $tmpArr[6]/60,2);
			$tmpArr[8] = $ts["is_su"];
			$tmpArr[9] = $ts["arra_approval"];
			
			$arrRes[] = $tmpArr;
				
		}
		return $arrRes;
	}
	public function listShiftByDateByShopWithFormat($shopHead,$dateBegin,$dateEnd){
		$arrTs = self::listShiftByDateByShop($shopHead, $dateBegin, $dateEnd);
		return self::formatTsConfirm($arrTs,$shopHead);
		
	}
	
	public function listArrangedShiftByDateByShopWithFormat($shopHead,$dateBegin,$dateEnd){
		date_default_timezone_set('Australia/Melbourne');
		if($shopHead == "CLPC" || $shopHead == "CLIC" || $shopHead == "ADPC" || $shopHead == "WLIC" ){
			date_default_timezone_set('Australia/Adelaide');
		}
		$intDateBegin = date("U",strtotime($dateBegin));
		$intDateEnd = date("U",strtotime($dateEnd))+86400;
		
		$rows = $this->fetchAll(" `arra_shift_begin` > ".$intDateBegin." AND `arra_shift_end` < ".$intDateEnd." AND `shop_head` LIKE '".$shopHead."'","id_staff ASC");
		if(!$rows) return false;
		return self::formatTsArra($rows, $shopHead);
		//return $rows->toArray();
	}
	
	public function calShiftHours($date,$shiftStart,$shiftEnd,$shopLocation){
		
		$arrRes = array(0=>"",1=>"");
		date_default_timezone_set('Australia/Melbourne');
		$intDate6Pm = date("U",strtotime($date." 18:00"));
		
		if($shopLocation == 2){
			$intDate6Pm = date("U",strtotime($date." 18:30"));
		}
	
		if($shiftStart == "" || $shiftEnd == "") return $arrRes;
	
		if($shiftEnd <$intDate6Pm){
			$shiftTimeb6 = $shiftEnd - $shiftStart;
			$arrRes[0] = ($shiftTimeb6 >0)?round($shiftTimeb6/3600,2):0;
			$arrRes[1] = "0";
		}	
		else{
		$shiftTimeb6 = $intDate6Pm - $shiftStart;
		$arrRes[0] = ($shiftTimeb6 >0)?round($shiftTimeb6/3600,2):0;
		$shiftTimea6 = $shiftEnd - $intDate6Pm;
		$arrRes[1] = ($shiftTimea6 >0)?round($shiftTimea6/3600,2):0;
		}
		return $arrRes; 
		 
	}
	public function updateOnDutyEvent(  $idSheet ,$eventOnDuty){
		$data = array(					
				"event_on_duty" =>  $eventOnDuty,	
		);
			
		$this->update($data,"`id_sheet` = ". $idSheet);
	}
	public function updateOffDutyEvent(  $idSheet ,$eventOffDuty){
		$data = array(
				"event_off_duty" =>  $eventOffDuty,
		);
			
		$this->update($data,"`id_sheet` = ". $idSheet);
	}
	public function updateBreakTime($idSheet,$breakChoice){
		
		$breakTime = 1800;
		
		if($breakChoice == 9 ){
			$breakTime = 0;
		}
		
		$data = array(
				"break_time" => $breakTime 
		);
		
		$this->update($data,"`id_sheet` = ". $idSheet);
	}
	public function listShopNotFinish($intDateBegin,$intDateEnd){
		
		$whereStr ="`shift_begin` > ".$intDateBegin." AND `shift_end` < ".$intDateEnd." AND `shift_end` > `shift_begin` AND ISNULL(`confirm_shift_begin`)";   
		$select = $this->_db->select()
		->from($this->_name,array('shop_head'))
		->where($whereStr)
		->order('shop_head');
		$result = $this->getAdapter()->fetchAll($select);
		if(!$result) return false;
		return $result;
	}
	public function listShopNotArrange($intDateBegin,$intDateEnd){
		
		$whereStr ="`shift_begin` > ".$intDateBegin." AND `shift_end` < ".$intDateEnd;
		$select = $this->_db->select()
		->distinct()
		->from($this->_name,array('shop_head'))
		->where($whereStr)
		->order('shop_head');
		$result = $this->getAdapter()->fetchAll($select);
		if(!$result) return false;
		return $result;		
		
	}
	public function updateAdminAdjust($idSheet,$totalSeconds){
		$data = array(
				"admin_adjust" => $totalSeconds 
		);
		
		$this->update($data,"`id_sheet` = ". $idSheet);
		
	}

	public function listShiftWithEvent($intDateBegin,$intDateEnd){
		
		$intDateEnd += 86400;
		$whereStr ="`shift_begin` > ".$intDateBegin." AND `shift_end` < ".$intDateEnd." AND ( !ISNULL(`event_on_duty`) OR !ISNULL(`event_off_duty`) )"; 
		$rows = $this->fetchAll($whereStr,'id_staff ASC');
		
		if(!$rows) return false;
		return $rows->toArray();		
	}
	
	public function searchTimeSheet($idStaff,$shopHead,$intShiftBegin,$intShiftEnd){
		
		$whereStr = "`shift_begin` = ".$intShiftBegin." AND `shift_end` = ".$intShiftEnd." AND `id_staff` = ".$idStaff." AND `shop_head` LIKE '".$shopHead."'";
		$row = $this->fetchRow($whereStr);
		if(!$row) return false;
		return $row->toArray();
	}
	
	public function totalWorkingHours($idStaff,$intDateBegin,$intDateEnd,$shopHead){
		
		$intDateEnd += 86400;
		$whereStr = "`confirm_shift_begin` > ".$intDateBegin." AND `confirm_shift_end` < ".$intDateEnd." AND `id_staff` = ".$idStaff." AND `shop_head` LIKE '".$shopHead."'";
		$rows = $this->fetchAll($whereStr);
		if(!$rows) return false;
		
		$totalTime = 0;
		
		foreach($rows as $k => $v){
			
			$time = $v["confirm_shift_end"] - $v["confirm_shift_begin"] - $v["break_time"];
			if($time >0){
			$totalTime += $time;
			}
		}
		
		return Round($totalTime/3600,2);
		
		//return $rows->toArray();		
		
	}
	public function listFutureShift($idStaff,$intShiftBegin){
		
		$whereStr ="`arra_shift_begin` > ".$intShiftBegin." AND `id_staff` = ".$idStaff;
		$rows = $this->fetchAll($whereStr,'arra_shift_begin DESC');
		
		if(!$rows) return false;
		return $rows->toArray();		
	}
}
?>