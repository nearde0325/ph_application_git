<?php
class Model_DbTable_Roster_Attncase extends Zend_Db_Table_Abstract {

	protected $_name = 'att_case';
	
	public function getAttncase( $idCase){
		
		$row = $this->fetchRow("`id_case` = ". $idCase);
		if(!$row) return false;
		return $row->toArray();		
		
		}
		
	public function addAttncase( $caseIdCase , $idManager , $idStaff , $shopCode , $shiftDate , $manShiftBeginHour , $manShiftBeginMin , $manShiftEndHour , $manShiftEndMin , $staffShiftBeginHour , $staffShiftBeginMin , $staffShiftEndHour , $staffShiftEndMin , $breakFirstMin , $breakSecondMin , $linkImageOpen , $linkImageClose , $offlineInvOn , $offlineInvOff , $reasonComment , $detailExplain , $hourRequest , $hourAllocate ,$lodgeDate = null , $dicisionDate = null , $payOn = null , $payStatus = null ){
		
		$data = array(
			
         "case_id_case" =>  $caseIdCase ,
         "id_manager" =>  $idManager ,
         "id_staff" =>  $idStaff ,
         "shop_code" =>  $shopCode ,
         "shift_date" =>  $shiftDate ,
         "man_shift_begin_hour" =>  $manShiftBeginHour ,
         "man_shift_begin_min" =>  $manShiftBeginMin ,
         "man_shift_end_hour" =>  $manShiftEndHour ,
         "man_shift_end_min" =>  $manShiftEndMin ,
         "staff_shift_begin_hour" =>  $staffShiftBeginHour ,
         "staff_shift_begin_min" =>  $staffShiftBeginMin ,
         "staff_shift_end_hour" =>  $staffShiftEndHour ,
         "staff_shift_end_min" =>  $staffShiftEndMin ,
         "break_first_min" =>  $breakFirstMin ,
         "break_second_min" =>  $breakSecondMin ,
         "link_image_open" =>  $linkImageOpen ,
         "link_image_close" =>  $linkImageClose ,
         "offline_inv_on" =>  $offlineInvOn ,
         "offline_inv_off" =>  $offlineInvOff ,
         "reason_comment" =>  $reasonComment ,
         "detail_explain" =>  $detailExplain ,
         "hour_request" =>  $hourRequest ,
         "hour_allocate" =>  $hourAllocate ,
		 "lodge_date" =>  $lodgeDate ,
         "dicision_date" =>  $dicisionDate ,
         "pay_on" =>  $payOn ,
         "pay_status" =>  $payStatus 
	
			);
		$this->insert($data);
		
		return $this->getAdapter()->lastInsertId();
		}
		
	public function updateAttncase(  $idCase ,  $caseIdCase , $idManager , $idStaff , $shopCode , $shiftDate , $manShiftBeginHour , $manShiftBeginMin , $manShiftEndHour , $manShiftEndMin , $staffShiftBeginHour , $staffShiftBeginMin , $staffShiftEndHour , $staffShiftEndMin , $breakFirstMin , $breakSecondMin , $linkImageOpen , $linkImageClose , $offlineInvOn , $offlineInvOff , $reasonComment , $detailExplain , $hourRequest , $hourAllocate ,$lodgeDate, $dicisionDate , $payOn , $payStatus){
		$data = array(
			
         "case_id_case" =>  $caseIdCase ,
         "id_manager" =>  $idManager ,
         "id_staff" =>  $idStaff ,
         "shop_code" =>  $shopCode ,
         "shift_date" =>  $shiftDate ,
         "man_shift_begin_hour" =>  $manShiftBeginHour ,
         "man_shift_begin_min" =>  $manShiftBeginMin ,
         "man_shift_end_hour" =>  $manShiftEndHour ,
         "man_shift_end_min" =>  $manShiftEndMin ,
         "staff_shift_begin_hour" =>  $staffShiftBeginHour ,
         "staff_shift_begin_min" =>  $staffShiftBeginMin ,
         "staff_shift_end_hour" =>  $staffShiftEndHour ,
         "staff_shift_end_min" =>  $staffShiftEndMin ,
         "break_first_min" =>  $breakFirstMin ,
         "break_second_min" =>  $breakSecondMin ,
         "link_image_open" =>  $linkImageOpen ,
         "link_image_close" =>  $linkImageClose ,
         "offline_inv_on" =>  $offlineInvOn ,
         "offline_inv_off" =>  $offlineInvOff ,
         "reason_comment" =>  $reasonComment ,
         "detail_explain" =>  $detailExplain ,
         "hour_request" =>  $hourRequest ,
         "hour_allocate" =>  $hourAllocate ,
		 "lodge_date" =>  $lodgeDate ,
         "dicision_date" =>  $dicisionDate ,
         "pay_on" =>  $payOn ,
         "pay_status" =>  $payStatus 
	
			);
			
		$this->update($data,"`id_case` = ". $idCase);
		}
		
	public function deleteAttncase( $idCase){
		
		$this->delete("`id_case` = ". $idCase);
		
		}	
	
	public function listAll(){
		
		$rows =$this->fetchAll("1","id_case DESC ");
		if(!$rows) return false;
		return $rows->toArray();			
		
		}
	public function listUndecidedCase(){
		
		$rows =$this->fetchAll("dicision_date IS NULL","id_case DESC ");
		if(!$rows) return false;
		return $rows->toArray();		
		
	}	
	public function listDecidedCase($status){
		
		$whereStr = "dicision_date IS NOT NULL";
		if(is_string($status)){
			$tmpStr = " AND pay_status LIKE '".$status."'";
			
			$whereStr.= $tmpStr;
		}
		if(is_array($status)){
			
			$tmpStr = " AND (";
			foreach($status as $line){
				$tmpStr .= " pay_status LIKE '".$line."' OR";
			}
			
			$tmpStr = substr($tmpStr,0,-2)." )";		

			$whereStr .= $tmpStr;
		}
		
		$rows =$this->fetchAll($whereStr,array("pay_on ASC","dicision_date DESC"));
		if(!$rows) return false;
		return $rows->toArray();		
	}
	public function listWillpayCase(){
		$rows = self::listDecidedCase("willpay");
		if(!$rows) return false;
		return $rows;		
		
	}	
	public function listRejectCase(){
		$rows = self::listDecidedCase(array("reject","cancel"));
		if(!$rows) return false;
		return $rows;		
	}
	public function listPaidCase(){
		$rows = self::listDecidedCase("paid");
		if(!$rows) return false;
		return $rows;		
	}
	
	public function existCaseID($caseID){
		$row = $this->fetchRow("`case_id_case` LIKE '{$caseID}'");
		if(!$row) return false ;
		return true;
	}
	public function acceptCase($idCase,$normalHour,$pm6Hour,$satHour,$sunHour,$holidayHour,$dateWillPay){
		
		$dateToday = Model_DatetimeHelper::dateToday();
		$payStatus = "willpay";
		
		$data = array(
			
			"hour_allocate_normal" => $normalHour,
			"hour_allocate_6pm" => $pm6Hour,
			"hour_allocate_sat" => $satHour,
			"hour_allocate_sun"	=> $sunHour,
			"hour_allocate_pholiday" => $holidayHour,			
			"dicision_date" => $dateToday,
			"pay_on" => $dateWillPay,
			"pay_status" =>$payStatus		
			
			);
		$this->update($data,"`id_case` = ". $idCase);
	}
	
	public function rejectCase($idCase){
		
		$dateToday = Model_DatetimeHelper::dateToday();
		$payStatus = "reject";
		
		$data = array(
				"dicision_date" => $dateToday,
				"pay_status" =>$payStatus				
				);
		
		$this->update($data,"`id_case` = ". $idCase);			
	}
	public function cancelCase($idCase){
		$dateToday = Model_DatetimeHelper::dateToday();
		$payStatus = "cancel";
		
		$data = array(
				"dicision_date" => $dateToday,
				"pay_status" =>$payStatus
		);
		
		$this->update($data,"`id_case` = ". $idCase);		
		
	}
	public function delayPayment($idCase ,$payOn){
		
		$data = array(
				"pay_on" =>  $payOn
				);
		
		$this->update($data,"`id_case` = ". $idCase);	
	}
	public function processPayment($idCase){
		
		$payStatus = "paid";
		$data = array(
				"pay_status" => $payStatus
				);
		$this->update($data,"`id_case` = ". $idCase);			
	}
	public function recordChequeNo($idCase,$chequeNo){
		$data = array(
				"cheque_no" => $chequeNo
				);
		$this->update($data,"`id_case` = ". $idCase);	
		
	}
	
}
?>