<?php



class Model_Payroll_Staffbonus extends Zend_Db_Table_Abstract {

	protected $_name = 'staff_bonus';
	
	public function getStaffbonus( $idBonus){
		
		$row = $this->fetchRow("`id_bonus` = ". $idBonus);
		if(!$row) return false;
		return $row->toArray();		
		
		}
		
	public function addStaffbonus( $idStaff , $typeBonus , $detailBonus , $amtTotalBonus , $amtCashBonus , $amtBankBonus , $amtBonusSuper , $periodBonusBegin , $periodBonusEnd , $payDate , $payType , $holdToDate , $paidBonus , $paidDate , $cancelBonus , $paidAmtCash , $paidAmtBank , $paidAmtSuper){
		
		$data = array(
			
         "id_staff" =>  $idStaff ,
         "type_bonus" =>  $typeBonus ,
         "detail_bonus" =>  $detailBonus ,
         "amt_total_bonus" =>  $amtTotalBonus ,
         "amt_cash_bonus" =>  $amtCashBonus ,
         "amt_bank_bonus" =>  $amtBankBonus ,
         "amt_bonus_super" =>  $amtBonusSuper ,
         "period_bonus_begin" =>  $periodBonusBegin ,
         "Period_bonus_end" =>  $periodBonusEnd ,
         "pay_date" =>  $payDate ,
         "pay_type" =>  $payType ,
         "hold_to_date" =>  $holdToDate ,
         "paid_bonus" =>  $paidBonus ,
         "paid_date" =>  $paidDate ,
         "cancel_bonus" =>  $cancelBonus ,
         "paid_amt_cash" =>  $paidAmtCash ,
         "paid_amt_bank" =>  $paidAmtBank ,
         "paid_amt_super" =>  $paidAmtSuper
				 
	
			);
		$this->insert($data);
		
		}
		
	public function newStaffbonus($idStaff , $typeBonus , $detailBonus , $amtTotalBonus , $amtCashBonus , $amtBankBonus , $amtBonusSuper , $periodBonusBegin , $periodBonusEnd , $payDate , $payType , $holdToDate,$holdReason,$shopCode = null){
		
		$data = array(
					
				"id_staff" =>  $idStaff ,
				"type_bonus" =>  $typeBonus ,
				"detail_bonus" =>  $detailBonus ,
				"amt_total_bonus" =>  $amtTotalBonus ,
				"amt_cash_bonus" =>  $amtCashBonus ,
				"amt_bank_bonus" =>  $amtBankBonus ,
				"amt_bonus_super" =>  $amtBonusSuper ,
				"period_bonus_begin" =>  $periodBonusBegin ,
				"Period_bonus_end" =>  $periodBonusEnd ,
				"pay_date" =>  $payDate ,
				"pay_type" =>  $payType ,
				"hold_to_date" =>  $holdToDate,
				"hold_reason" =>  $holdReason,
				"shop_code" => $shopCode
		
		);
		$this->insert($data);
				
		
	}

	public function changePayDate($idBonus,$payDate,$holdToDate){
		$data = array(
					
				"pay_date" =>  $payDate ,
				"hold_to_date" =>  $holdToDate
		);
			
		$this->update($data,"`id_bonus` = ". $idBonus);		
		
	}
	public function cancelBonus($idBonus,$comment = null){
		$data = array(
         "cancel_bonus" =>  Model_DatetimeHelper::dateToday(),
				"release_comment" => $comment
		);
			
		$this->update($data,"`id_bonus` = ". $idBonus);		
		
	}
		
	public function updateStaffbonus(  $idBonus ,  $idStaff , $typeBonus , $detailBonus , $amtTotalBonus , $amtCashBonus , $amtBankBonus , $amtBonusSuper , $periodBonusBegin , $periodBonusEnd , $payDate , $payType , $holdToDate , $paidBonus , $paidDate , $cancelBonus , $paidAmtCash , $paidAmtBank , $paidAmtSuper){
		$data = array(
			
         "id_staff" =>  $idStaff ,
         "type_bonus" =>  $typeBonus ,
         "detail_bonus" =>  $detailBonus ,
         "amt_total_bonus" =>  $amtTotalBonus ,
         "amt_cash_bonus" =>  $amtCashBonus ,
         "amt_bank_bonus" =>  $amtBankBonus ,
         "amt_bonus_super" =>  $amtBonusSuper ,
         "period_bonus_begin" =>  $periodBonusBegin ,
         "Period_bonus_end" =>  $periodBonusEnd ,
         "pay_date" =>  $payDate ,
         "pay_type" =>  $payType ,
         "hold_to_date" =>  $holdToDate ,
         "paid_bonus" =>  $paidBonus ,
         "paid_date" =>  $paidDate ,
         "cancel_bonus" =>  $cancelBonus ,
         "paid_amt_cash" =>  $paidAmtCash ,
         "paid_amt_bank" =>  $paidAmtBank ,
         "paid_amt_super" =>  $paidAmtSuper 
	
			);
			
		$this->update($data,"`id_bonus` = ". $idBonus);
		}
		
	public function deleteStaffbonus( $idBonus){
		
		$this->delete("`id_bonus` = ". $idBonus);
		
		}	
	
	public function listAll(){
		
		$rows =$this->fetchAll("1","id_bonus DESC ");
		if(!$rows) return false;
		return $rows->toArray();			
		
		}	
		
	public function listBonusByPayDate($dateBegin = null){
		
		if($dateBegin==""){	
			$dateBegin = Model_DatetimeHelper::dateToday();
		}
		
		$mBegin = Model_DatetimeHelper::getFirstDayOfTheMonth($dateBegin);
		$mEnd = Model_DatetimeHelper::getLastDayOfTheMonth($dateBegin);
		
		$whereStr = "`pay_date` >= '{$mBegin}' AND `pay_date` <= '{$mEnd}' AND `hold_to_date` = '0000-00-00' "; 
		
		$rows =$this->fetchAll($whereStr,"id_staff ASC ");
		if(empty($rows)) return false;
		return $rows->toArray();		
		
		
	}
	public function listReleasedBonus($idStaff){
		
		$whereStr = "`id_staff` = {$idStaff} AND `hold_to_date` = '0000-00-00' AND `type_bonus` < 4 ";
		$rows =$this->fetchAll($whereStr,"pay_date ASC ");
		if(empty($rows)) return false;
		return $rows->toArray();
				
	}
	public function listAllHoldBonus(){
		
		$whereStr = "`type_bonus` < 4 AND `hold_to_date` != '0000-00-00' AND `cancel_bonus` IS NULL ";
		
		$rows =$this->fetchAll($whereStr,"id_staff ASC ");
		if(empty($rows)) return false;
		return $rows->toArray();		
		
		
	}
	
	public function bonusPaid($idBonus,$paidDate){
		$data = array(
				"paid_date" =>  $paidDate 		
		);
			
		$this->update($data,"`id_bonus` = ". $idBonus);
		
	}	
	
	public function getStockLostByShop($shopCode,$dateBegin,$dateEnd,$stkLostType = 4){
		$whereStr = "`shop_code` LIKE '{$shopCode}' AND `period_bonus_begin` = '{$dateBegin}' AND `period_bonus_end` = '{$dateEnd}' AND `type_bonus` = {$stkLostType}";
		$rows =$this->fetchRow($whereStr);
		if(!$rows) return false;
		return $rows->toArray();		
		
	}
	public function releaseBonus($idBonus,$payDate,$releaseComment){
		
		$data = array(
				"pay_date" =>  $payDate ,		
				"hold_to_date" =>  '0000-00-00' ,
				"release_comment" => $releaseComment						
				);
		$this->update($data,"`id_bonus` = ". $idBonus);	
	}
}


?>