<?php
/**
 * Model Cash Account Opening 
 * 
 */
class Model_DbTable_Cashaccountopening extends Zend_Db_Table_Abstract {
	
	protected $_name = 'cashaccount_opening';
	
	/**
	 * Fetch Line of Opening by ID
	 * @param unknown_type $idCa
	 */
	public function getCAOpening($idCa){
		$rows = $this->fetchRow("`id_ca_open` = ".$idCa);
		return $rows->toArray();		
	}
	/**
	 * List Active Opening By Date inclusive
	 * @param unknown_type $shopCode
	 * @param unknown_type $dateBegin
	 * @param unknown_type $dateEnd
	 * @return Zend_Db_Table_Rowset_Abstract|boolean
	 */
	public function getCAOpeningByShopDate($shopCode,$dateBegin,$dateEnd){
	
		$rows=$this->fetchAll("`date_record_open` >= '".$dateBegin."' AND `date_record_open` <= '".$dateEnd."' AND `active` = 1 AND `shop_code_ca` = '".$shopCode."' ","id_ca_open");
	
		if($rows){
				
			return $rows;
		}
			
		return false;
	}	
		
	public function addCAOpening($shopHead,$dateRecord,$timeRecord,$dayRecord,$staffPrepare,$staff1,$staff2,$cashArray,$openBalance,$cashOut,$bankIn,$totalCashMatch,$remark,$shopOpenYest,$manualClose,$checkDvr){
		
		//insert Data
		$idCaTimeStamp =self::builderOpeningId($shopHead); 
		$idOtherOpenExist= false;
		$dataOther = array(
				"id_cashaccount_open" =>$idCaTimeStamp,
				"shop_code_ca"  =>$shopHead,
				"date_record_open" => $dateRecord,
				"time_record_open" => $timeRecord,
				"day_record_open" => $dayRecord,
				"staff_name_prepare_open" => $staffPrepare,
				"staff_on_duty1_open" => $staff1,
				"staff_on_duty2_open" => $staff2,
				"open_balance" => $openBalance,
				"amount_cash_out_open" => $cashOut,
				"amount_bank_in_open" => $bankIn,
				"total_cash_match_open" => $totalCashMatch,
				"remark_open_cash_not_match" => $remark,
				"shop_open_yesterday" => $shopOpenYest,
				"manual_input_close_cash" => $manualClose, 
				"data_encryption" => 0,
				"active" =>1,
				"check_dvr" => $checkDvr
				);
		if($cashArray){
			
		$data = array_merge($dataOther,$cashArray);
		

		
		$this->insert($data);
	
		
		$idOtherOpenExist = intval(self::otherOpeningExist($shopHead,$dateRecord));
		
		if($idOtherOpenExist){
			
			self::desActiveOpen($idOtherOpenExist);
		}
	
		
			return $this->getAdapter()->lastInsertId();
		}
		
		else{
			
			return false;
		}
				
		
	}
	
	public function getTodayActiveOpening($shopCode){
		//
		$db = $this->getAdapter();	
		date_default_timezone_set('Australia/Melbourne');	
		$dateToday=date('Y-m-d');		
		//$caClose = new Model_DbTable_Cashaccountclosing();
		$sqlstr="SELECT `date_record_open`,`open_balance`,`active` FROM `cashaccount_opening` WHERE `shop_code_ca` = '".$shopCode."' AND `active` =  1   AND `date_record_open` = '".$dateToday."' ORDER BY `date_record_open` DESC LIMIT 0,1 ;";
		//$sqlstr = "SELECT * FROM `cashaccount_closing`;";
		$stmt = $db->prepare($sqlstr);
    	$stmt->execute();
    	$result = $stmt->fetchAll();
    	
		if($result){
    		return $result;		
		}			
		else{
			
			return false;
		}
	}
	public function getActiveOpeningToday($shopCode){
	
		$rows=$this->fetchRow("`date_record_open` = '".Model_DatetimeHelper::dateToday()."' AND `active` = 1 AND `shop_code_ca` = '".$shopCode."' ","id_ca_open");
	
		if(!$rows){
	
			return false;
		}
		else{
	
			return $rows;
		}
	
	}
	/**
	 * Get The Last (Actived) Opening By Date
	 * @param string $shopCode
	 * @param string $date
	 * @return mixed false if Not Found / Array if found
	 */
	public function getActiveOpeningByDate($shopCode,$date){
	
		$rows = $this->fetchAll("`date_record_open` = '".$date."' AND `active` = 1 AND `shop_code_ca` = '".$shopCode."' ","id_ca_open");
		if(!$rows){
			return false;
		}
		else{
			return $rows->toArray();
		}
	
	}	
			
	public function  buildCashCountingArr($qty100,$amt100,$qty50,$amt50,$qty20,$amt20,$qty10,$amt10,$qty5,$amt5,$qty2,$amt2,$qty1,$amt1,$qty05,$amt05,$qty02,$amt02,$qty01,$amt01,$qty005,$amt005){
		
		$arrCash = array(
				'qty_note_100_open' => $qty100,
				'amount_note_100_open' =>$amt100,
				'qty_note_50_open' => $qty50,
				'amount_note_50_open' =>$amt50,				
				'qty_note_20_open' => $qty20,
				'amount_note_20_open' =>$amt20,				
				'qty_note_10_open' => $qty10,
				'amount_note_10_open' =>$amt10,
				'qty_note_5_open' => $qty5,
				'amount_note_5_open' =>$amt5,
				'qty_coin_2_open' => $qty2,
				'amount_coin_2_open' =>$amt2,
				'qty_coin_1_open' => $qty1,
				'amount_coin_1_open' =>$amt1,
				'qty_coin_05_open' => $qty05,
				'amount_coin_05_open' =>$amt05,
				'qty_coin_02_open' => $qty02,
				'amount_coin_02_open' =>$amt02,
				'qty_coin_01_open' => $qty01,
				'amount_coin_01_open' =>$amt01,
				'qty_coin_005_open' => $qty005,
				'amount_coin_005_open' =>$amt005,
				);
		
		$ifNumber = true ;
			
			foreach ($arrCash as $key => $value){
				$ifNumber = is_numeric($value);
				
			}
		
		if($ifNumber){	
			return $arrCash;
		}
		else{
			return false;			
		}
	}
	public function checkcd_2($shopCode,$date,$cond){
		
		$row = $this->getActiveOpeningByDate($shopCode, $date);
		
		$result = $row[0]['amount_cash_out_open'] + $row[0]['amount_bank_in_open'];
		
		if($result >$cond){
			return 0;
		}
		else{
			return 1;
		}
				
	//Condition 2 of Audit, Cash Out and Bank In < 0
	
	}
	
	public function listAllShopcode(){
		//
		$db = $this->getAdapter();
		//date_default_timezone_set('Australia/Melbourne');
		//$dateToday=date('Y-m-d');
		//$caClose = new Model_DbTable_Cashaccountclosing();
		$sqlstr="SELECT DISTINCT `shop_code_ca` FROM  `cashaccount_opening` WHERE 1 ORDER BY `shop_code_ca` ;";
		//$sqlstr = "SELECT * FROM `cashaccount_closing`;";
		$stmt = $db->prepare($sqlstr);
		$stmt->execute();
		$result = $stmt->fetchAll();
		 
		if($result){
			return $result;
		}
		else{
				
			return false;
		}
	}
	
	public function isBankInCashCout($shopCode,$date){
		
		$row = self::getActiveOpeningByDate($shopCode, $date);
		
		if(empty($row)){
			return false;	
		}
		
		if($row[0]['amount_bank_in_open'] > 0 ) { return 1;}
		if($row[0]['amount_cash_out_open'] > 0 ) {
			return 2;
		}
		
	}
	public function IsBankInConfirmed($shopCode,$date){
		$row = self::getActiveOpeningByDate($shopCode, $date);
		return $row[0]['bankin_confirm'];
	}
	public function IsCashOutConfirmed($shopCode,$date){
		$row = self::getActiveOpeningByDate($shopCode, $date);
		return $row[0]['cashout_confirm'];		
	} 
	public function updateBankInConfirmed($shopCode,$bankInAmt,$date){
		
		$row =  self::getActiveOpeningByDate($shopCode, $date);
		$idOpen = $row[0]['id_ca_open'];
		$bankInAmtStaff = $row[0]['amount_bank_in_open'];
		
		$bankInConfirm = 1; //bank in amount correct
		
		if(abs($bankInAmt - $bankInAmtStaff)>0.03){

			$bankInConfirm = 2;
		}	
				
		$data = array(
				'bankin_confirm' =>$bankInConfirm,
				'bankin_cf_amt' => $bankInAmt,
				'bankin_cf_date' => Model_DatetimeHelper::dateToday()
				);
		
		$this->update($data, "`id_ca_open` =".$idOpen);
		
	}

	
	public function updateCashOutConfirmed($shopCode,$cashOutAmt,$date){
		
		$row =  self::getActiveOpeningByDate($shopCode, $date);
		$idOpen = $row[0]['id_ca_open'];
		$cashOutAmtStaff = $row[0]['amount_cash_out_open'];
		$cashOutConfirm = 1;
		
		if(abs($cashOutAmt - $cashOutAmtStaff) > 0.03){
			
			$cashOutConfirm = 2;
		}		
		
		$data = array(
				'cashout_confirm' => $cashOutConfirm,
				'cashout_cf_amt' => $cashOutAmt,
				'cashout_cf_date' => Model_DatetimeHelper::dateToday()
				);
		$this->update($data, "`id_ca_open` =".$idOpen);
		
	}
	
	public function updateBcConfirmed($idOpen,$isBankIn,$isConfirmed,$amount){
		
		$data = array();
		if($isBankIn){
			$data = array(			
					'bankin_confirm' =>$isConfirmed,
					'bankin_cf_amt' => $amount,
					'bankin_cf_date' => Model_DatetimeHelper::dateToday()
					);
		}
		else{
			$data = array(
					'cashout_confirm' => $isConfirmed,
					'cashout_cf_amt' => $amount,
					'cashout_cf_date' => Model_DatetimeHelper::dateToday()
			);
			
		}
		$this->update($data, "`id_ca_open` = ".$idOpen);
	}
	
	public function updateBiCoConfirmed($idOpen,$biConfirm,$biAmt,$biDate,$coConfirm,$coAmt,$coDate,$comm){
		
		$data = array(
				'bankin_confirm' =>$biConfirm,
				'bankin_cf_amt' => $biAmt,
				'bankin_cf_date' => $biDate,
				'cashout_confirm' => $coConfirm,
				'cashout_cf_amt' => $coAmt,
				'cashout_cf_date' => $coDate,
				'comm_bc' => $comm
		);		
		$this->update($data, "`id_ca_open` = ".$idOpen);		
		
	}
	
	public function markMissed($idCa){
		$data = array(
				"is_missed" => 1
		);
		$this->update($data,"`id_ca_open` = ".$idCa);
	}
	
	private function builderOpeningId($shopCode){
	
		date_default_timezone_set('Australia/Melbourne');
		
		$shoplocation = new Model_DbTable_Shoplocation();
		$shopId = $shoplocation->getNameByHead($shopCode);
		$idOpening = 0;
		if($shopId){
			
			$idOpening = date("YmdHis").$shopId['id_shop_location_head'];
			return $idOpening;
		}
		else{
			return false;
		}
		
	
	}
	
	private function otherOpeningExist($shopCode,$dateRecord = null){
		
		$dateToCheck = $dateRecord;
		if(!isset($dateRecord)){
			$dateToCheck = Model_DatetimeHelper::dateToday();
			
		}
		
		$rows=$this->fetchAll("`date_record_open` = '".$dateToCheck."' AND `shop_code_ca` = '".$shopCode."' ","id_ca_open");
		
		if(count($rows)>1){
			
			return $rows[0]['id_ca_open'];
			
		}
		else{
			
			return false;
		}
		
	}
	
	private function desActiveOpen($idCa){
		$data = array(
				"active" => 0
				);
		$this->update($data,"`id_ca_open` = ".$idCa);
	}
	
	
	
	
	
}
?>