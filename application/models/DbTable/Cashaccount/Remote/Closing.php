<?php
/*
 this is the class which send automatic email when:
Something Done:

Something Wrong:
1) If download failed, Email me


*/
class Model_DbTable_Cashaccount_Remote_Closing extends Zend_Db_Table_Abstract {
	
	protected $_name = 'cashaccount_closing';
	protected $_adaptor = 'real';
	protected $_primary = 'id_ca_close';
	
	public function getCAClosing($idCa){
		
		$rows = $this->fetchRow("`id_ca_close` = ".$idCa);
		return $rows->toArray();		
		
	}
	
	public function getCAClosingByShopDate($shopCode,$dateBegin,$dateEnd){
		
		$rows=$this->fetchAll("`date_record_close` >= '".$dateBegin."' AND `date_record_close` <= '".$dateEnd."' AND `active` = 1 AND `shop_code_ca` = '".$shopCode."' ","id_ca_close");
		
		if($rows){
			
			return $rows;
		}
			
			return false;		
	}
	
	public function addCAClosing($shopHead,$dateRecord,$timeRecord,$dayRecord,$staffPreapre,$staff1,$staff2,$expenseArr,$expenseTotal,
								 $managerName,$cashInTillClose,$cashArrClose,$openBalanceRecord,$closeCashBalance,$totalCashSales,
								 $qtyMerchantCopy,$drcrAmount,$amexAmount,$offlineAmount,$totalElectronicCash,
								 $creditNoteAmount,$couponNoteAmount,$totalNetSalesClose,$aposTotal,$cashDif,$indInconsistency,$remarkClose){
		$idCloseTimeStamp = self::builderClosingId($shopHead);
		
		$dataBasic = array(				
				"id_cashaccount_close" => $idCloseTimeStamp,
				"shop_code_ca" => $shopHead,
				"date_record_close" =>$dateRecord,
				"time_record_close" => $timeRecord,
				"day_record_close" =>$dayRecord,
				"staff_name_prepare_close" => $staffPreapre,
				"staff_on_duty1_close" => $staff1,
				"staff_on_duty2_close" => $staff2,
				"sub_expense_total" => $expenseTotal,
				"expense_approved_manager" => $managerName
				);
		$dataSales = array(
				"cash_in_till_close" => $cashInTillClose,
				"open_balance_close" => $openBalanceRecord,
				"close_balance_cash_close" => $closeCashBalance,
				"total_cash_sales_close" => $totalCashSales,				
				);
		$dataElectronicSales = array(
				"qty_merchant_copy_close" => $qtyMerchantCopy,
				"drcr_electronic_cash_close" => $drcrAmount,
				"amex_electronic_cash_close" => $amexAmount,
				"offline_voucher_close" => $offlineAmount,
				"total_electronic_cash_close" => $totalElectronicCash
				);
		$dataCoupon = array(
				"credit_note" => $creditNoteAmount,
				"coupon_note" => $couponNoteAmount
				);
		$dataSummary = array(
				"total_net_sales_close" => $totalNetSalesClose,
				"apos_total" => $aposTotal,
				"cash_discrepancy_close"=> $cashDif,
				"ind_inconsistency_close"=> $indInconsistency,
				"remark_close" => $remarkClose,
				"data_encryption" => 0,
				"active" => 1				
				);
		
		$data = array_merge($dataBasic,$expenseArr,$cashArrClose,$dataSales,$dataElectronicSales,$dataCoupon,$dataSummary);
		
		$this->insert($data);
		
		$idOtherCloseExist = intval(self::otherClosingExist($shopHead));
		if($idOtherCloseExist){
			
			self::desActiveClose($idOtherCloseExist);
		}
		
		
	}
	
	
	public function buildExpenseClosingArr(
			$product1,$subExpen1,$staffName1,
			$product2,$subExpen2,$staffName2,
			$product3,$subExpen3,$staffName3,
			$product4,$subExpen4,$staffName4,
			$product5,$subExpen5,$staffName5,
			$product6,$subExpen6,$staffName6
			){
		
			$arrExp = array(
					'product_expense_1' => $product1,
					'sub_expense_1' => $subExpen1,
					'staff_name_expense_1'=>$staffName1,
					'product_expense_2' => $product2,
					'sub_expense_2' => $subExpen2,
					'staff_name_expense_2'=>$staffName2,
					'product_expense_3' => $product3,
					'sub_expense_3' => $subExpen3,
					'staff_name_expense_3'=>$staffName3,										
					'product_expense_4' => $product4,
					'sub_expense_4' => $subExpen4,
					'staff_name_expense_4'=>$staffName4,					
					'product_expense_5' => $product5,
					'sub_expense_5' => $subExpen5,
					'staff_name_expense_5'=>$staffName5,					
					'product_expense_6' => $product6,
					'sub_expense_6' => $subExpen6,
					'staff_name_expense_6'=>$staffName6					
					);
			return $arrExp;
		
	}
		
	public function  buildCashCountingClosingArr($qty100,$amt100,$qty50,$amt50,$qty20,$amt20,$qty10,$amt10,$qty5,$amt5,$qty2,$amt2,$qty1,$amt1,$qty05,$amt05,$qty02,$amt02,$qty01,$amt01,$qty005,$amt005){
	
		$arrCash = array(
				'qty_note_100_close' => $qty100,
				'amount_note_100_close' =>$amt100,
				'qty_note_50_close' => $qty50,
				'amount_note_50_close' =>$amt50,
				'qty_note_20_close' => $qty20,
				'amount_note_20_close' =>$amt20,
				'qty_note_10_close' => $qty10,
				'amount_note_10_close' =>$amt10,
				'qty_note_5_close' => $qty5,
				'amount_note_5_close' =>$amt5,
				'qty_coin_2_close' => $qty2,
				'amount_coin_2_close' =>$amt2,
				'qty_coin_1_close' => $qty1,
				'amount_coin_1_close' =>$amt1,
				'qty_coin_05_close' => $qty05,
				'amount_coin_05_close' =>$amt05,
				'qty_coin_02_close' => $qty02,
				'amount_coin_02_close' =>$amt02,
				'qty_coin_01_close' => $qty01,
				'amount_coin_01_close' =>$amt01,
				'qty_coin_005_close' => $qty005,
				'amount_coin_005_close' =>$amt005,
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
	public function getActiveClosingToday($shopCode){
		
		$rows=$this->fetchRow("`date_record_close` = '".self::cDateToday()."' AND `active` = 1 AND `shop_code_ca` = '".$shopCode."' ","id_ca_close");
		
		if(!$rows){
		
			return false;		
		}
		else{
		
			return $rows;
		}		
		
	}
	public function getActiveClosingByDate($shopCode,$date){
	
		$rows=$this->fetchAll("`date_record_close` = '".$date."' AND `active` = 1 AND `shop_code_ca` = '".$shopCode."' ","id_ca_close");
	
		if(!$rows){
	
			return false;
		}
		else{
	
			return $rows->toArray();
		}
	
	}	
	
	public function getYesterdayClosing($shopCode){
		//
		$db = $this->getAdapter();	
		date_default_timezone_set('Australia/Melbourne');	
		$dateToday=date('Y-m-d');		
		//$caClose = new Model_DbTable_Cashaccountclosing();
		$sqlstr="SELECT `date_record_close`,`close_balance_cash_close`,`active` FROM `cashaccount_closing` WHERE `shop_code_ca` = '".$shopCode."' AND `active` =  1   AND `date_record_close` < '".$dateToday."' ORDER BY `date_record_close` DESC LIMIT 0,1 ;";
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
	public function getLastBusDayClosing($shopCode,$dateToday){
		//
		$db = $this->getAdapter();
		//date_default_timezone_set('Australia/Melbourne');
		//$dateToday=date('Y-m-d');
		//$caClose = new Model_DbTable_Cashaccountclosing();
		$sqlstr="SELECT `date_record_close`,`close_balance_cash_close`,`active` FROM `cashaccount_closing` WHERE `shop_code_ca` = '".$shopCode."' AND `active` =  1   AND `date_record_close` < '".$dateToday."' ORDER BY `date_record_close` DESC LIMIT 0,1 ;";
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
	public function  checkcd_3($shopCode,$date,$cond){
	//Condition 3 for Audit , Cash Sales 	
		$row = $this->getActiveClosingByDate($shopCode, $date);
		$result = $row[0]['total_cash_sales_close'];
		if($result > $cond ){
			return 0;
		}
		return 1;		
	}
	public function checkcd_4($shopCode,$date,$cond){
	//Condition 4 of Audit, Qty of Merchant Copy	
		$row = $this->getActiveClosingByDate($shopCode, $date);
		$result = $row[0]['qty_merchant_copy_close'];
		if($result > $cond ){
			return 0;
		}
		return 1;		
	}
	public function checkcd_5($shopCode,$date,$cond){
	//Condition 5 of Audit, Debit Credit Card Sales
		$row = $this->getActiveClosingByDate($shopCode, $date);
		$result = $row[0]['drcr_electronic_cash_close'];
		if($result > $cond ){
			return 0;
		}
		return 1;
	}	
	public function checkcd_6($shopCode,$date,$cond){
	//Condition 6 of Audit, Total Expense
		$row = $this->getActiveClosingByDate($shopCode, $date);
		$result = $row[0]['sub_expense_total'];
		if($result == $cond ){
			return 0;
		}
		return 1;
	}
	public function checkcd_7($shopCode,$date,$cond){
	//Condition 7 of Audit, Offline Voucher
		$row = $this->getActiveClosingByDate($shopCode, $date);
		$result = $row[0]['offline_voucher_close'];
		if($result == $cond ){
			return 0;
		}
		return 1;
	}
	public function checkcd_8($shopCode,$date,$cond){
	//Condition 8 of Audit,Credit Note
		$row = $this->getActiveClosingByDate($shopCode, $date);
		$result = $row[0]['credit_note'];
		if($result == $cond ){
			return 0;
		}
		return 1;
	}
	public function checkcd_9($shopCode,$date,$cond){
	//Condition 9 of Audit, Coupon
		$row = $this->getActiveClosingByDate($shopCode, $date);
		$result = $row[0]['coupon_note'];
		if($result == $cond ){
			return 0;
		}
		return 1;
		
	}
	
	public function updateSystemData($arrData,$shop,$date){
	
		date_default_timezone_set('Australia/Melbourne');
		$closeExist = self::getActiveClosingByDate($shop, $date);
		
		if(!$closeExist){
			//system generate a close for it
			$shopHead = $shop;
			$dateRecord = $date;
			$timeRecord = "00:00:00";
			$dayRecord = date("N",strtotime($dateRecord));
			$staffPreapre = "System";
			$staff1 = "System";
			$staff2 = "";
			$expenseArr = self::buildExpenseClosingArr(
			NULL,0,NULL,
			NULL,0,NULL,
			NULL,0,NULL,
			NULL,0,NULL,
			NULL,0,NULL,
			NULL,0,NULL
			);
			$expenseTotal = 0;
			$managerName = "";
			$cashInTillClose = 0;
			$cashArrClose = self:: buildCashCountingClosingArr(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
			$openBalanceRecord = 0;
			
			self::addCAClosing($shopHead, $dateRecord, $timeRecord, $dayRecord, $staffPreapre, $staff1, $staff2, $expenseArr, $expenseTotal, $managerName, $cashInTillClose, $cashArrClose, 
					0,0,0,0,0,0,0,0,0,0,0,0,0,0,"System Generated, Not Fill By Staff");
			
			
		}
		$totalSales = $arrData["cash_sales"] + $arrData["eft_sales"] + $arrData["ae_sales"];
	
		$data = array(
				"apos_sys_sales_cash" =>$arrData["cash_sales"],
				"apos_sys_sales_eft" =>$arrData["eft_sales"],
				"apos_sys_sales_ae"=>$arrData["ae_sales"],
				"apos_sys_sales_total"=> $totalSales,
				"apos_sys_qty_eft"=>$arrData["eft_no"],
				"apos_sys_qty_ae" =>$arrData["ae_no"]
		);
	
		$whereStr = "`shop_code_ca` LIKE '".$shop."'  AND  `date_record_close` = '".$date."' ";
	
		$this->update($data,$whereStr);
	}
	
	
	private function cDateToday(){
	
		date_default_timezone_set('Australia/Melbourne');
	
		return $dateToday = date("Y-m-d");
	
	}
	
	private function cTimeNow(){
	
		date_default_timezone_set('Australia/Melbourne');
	
		return $timeNow = date("H:i:s");
	}
	private function builderClosingId($shopCode){
	
		date_default_timezone_set('Australia/Melbourne');
		
		$shoplocation = new Model_DbTable_Shoplocation();
		$shopId = $shoplocation->getNameByHead($shopCode);
		$idClosing = 0;
		if($shopId){
				
			$idClosing = date("YmdHis").$shopId['id_shop_location_head'];
			return $idClosing;
		}
		else{
			return false;
		}
		
		
		return $idClosing = date("YmdHis");
	}
	private function otherClosingExist($shopCode){
	
		$rows=$this->fetchAll("`date_record_close` = '".self::cDateToday()."' AND `active` = 1 AND `shop_code_ca` = '".$shopCode."' ","id_ca_close");
	
		if(count($rows)>1){
				
			return $rows[0]['id_ca_close'];
				
		}
		else{
				
			return false;
		}
	
	}
	private function desActiveClose($idCa){
		$data = array(
				"active" => 0
		);
		$this->update($data,"`id_ca_close` = ".$idCa);
	}
}
?>