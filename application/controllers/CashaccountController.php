<?php 
/**

 */
class CashaccountController extends Zend_Controller_Action
{

    public function init(){
    /**
	 *
	 *
	 */    
	
	}

    public function indexAction(){
    	
    	$dateToday = Model_DatetimeHelper::dateYesterday();
    	$date7Days = Model_DatetimeHelper::adjustDays("sub", $dateToday, 6);
    	
    	$arrDate = Model_DatetimeHelper::createDateArray($date7Days,$dateToday);
    	
    	$caOpen = new Model_DbTable_Cashaccountopening();
    	$caClose = new Model_DbTable_Cashaccountclosing();
    
    	$arrStatus = array();
	
	$shopHead = $this->_getParam('shop');
	if($shopHead!=""){
		
		$this->view->shophead = $shopHead; 
		$this->view->errorflag = false;
		
		
		foreach($arrDate as $dateToCheck){
			
			$ifOpen = false;
			$ifClose = false;
			
			$caOpenRow = $caOpen->getActiveOpeningByDate($shopHead, $dateToCheck);
			$caCloseRow = $caClose->getActiveClosingByDate($shopHead, $dateToCheck);
			
			//var_dump($caOpenRow);
			
			if(!empty($caOpenRow) ){
				
				if($caOpenRow[0]["staff_name_prepare_open"]!="System"){
					
					$ifOpen = true;
				}
				
				
			}
			
			if(!empty($caCloseRow)){
				 if( $caCloseRow[0]["staff_name_prepare_close"]!="System" ){
				$ifClose = true;
				 }
			}
			
			$arrStatus[$dateToCheck] = array($ifOpen,$ifClose);
		}
		
		$this->view->arrStatus = $arrStatus;
		
		}
	else{
		
		$this->view->errorflag = true;
	
		}	
    
	}
	
	public function openingAction(){
		$shopHead = $this->_getParam('shop');
		
		$ca = new Model_DbTable_Cashaccountclosing();
		$cashClosing = 0;
		$totalSales = 0;
		
		$dateToday = Model_DatetimeHelper::dateToday();
		$dateyesterday = Model_DatetimeHelper::dateYesterday();
		
		$missingDate = $this->_getParam("missing_date");
		
		if($missingDate !=""){
			
			$dateToday = $missingDate;
			$dateyesterday = Model_DatetimeHelper::adjustDays("sub", $missingDate, 1);
		
		}
		
		
		if($shopHead!=""){
			
			$arrClosing = $ca->getLastBusDayClosing($shopHead, $dateToday);
		
			
		if($arrClosing){
			
			$cashClosing = $arrClosing[0]['close_balance_cash_close'];
			$totalSales =  $arrClosing[0]['total_net_sales_close'];
			$cashClosingDate = $arrClosing[0]['date_record_close'];
			$this->view->syncError = $arrClosing[0]['sync_status'];
			
			if($cashClosingDate != $dateyesterday){
				
				$this->view->notFromYesterday = true;
			}
			else{
				
				$this->view->notFromYesterday = false;
			}
		}
		
		$this->view->cashclosing = $cashClosing;
		$this->view->totalSales = $totalSales;
		$this->view->dateToday = $dateToday;
		
		$this->view->shophead = $shopHead; 
		
		$this->view->errorflag = false;
		
		}
		else{
		
		$this->view->errorflag = true;
	
		}		
	}
	
	public function closingAction(){
		
		$shopHead = $this->_getParam('shop');
		
		$caOpen = new Model_DbTable_Cashaccountopening();
		$dateToday = Model_DatetimeHelper::dateToday();
		
		$missingDate = $this->_getParam("missing_date");
		
		if($missingDate !=""){
				
			$dateToday = $missingDate;
			$dateyesterday = Model_DatetimeHelper::adjustDays("sub", $missingDate, 1);
		
		}
		
		
	if($shopHead!=""){
		
		$caRow = $caOpen->getActiveOpeningByDate($shopHead, $dateToday);
		
		$openingToday = ($caRow==false)?false:$caRow[0]["open_balance"];
		
		$this->view->dateToday = $dateToday;
		$this->view->openingToday = $openingToday; 
		$this->view->shophead = $shopHead; 
		$this->view->errorflag = false;
		
		}
	else{
		
		$this->view->errorflag = true;
	
		}		
	}
	
	public function saveopeningAction(){
		date_default_timezone_set('Australia/Melbourne');
		$dateToday =trim($_POST['date_record']);
		
		$folder =realpath(APPLICATION_PATH."/../public/ca/")."/".trim($_POST['shop_code'])."/";		
		
$fileName =$_POST['shop_code']."-opening-".$dateToday.".html";
$fl =fopen($folder.$fileName,"w+");
//$str = print_r($_POST,true);

$caOpen = new Model_DbTable_Cashaccountopening();


$strMethod ="";
$cashOut = 0;
$bankIn = 0;
if($_POST['cash_out_method'] == 1){

	$strMethod = " Cash Out";
	$cashOut =$_POST['cashout_amount'];
}
elseif($_POST['cash_out_method'] == 2){

	$strMethod = " Bank In";
	$bankIn =$_POST['cashout_amount'];
}
elseif($_POST['cash_out_method'] == 3){

	$strMethod = "Unnecessary";
}

$openCloseMatch = "Not Match";
$openCloseMatchInt = 1;

if(isset($_POST['cash_in_the_till_match']) && $_POST['cash_in_the_till_match'] == 1 ){
	
	$openCloseMatch = "Opening Match Yesterday Closing";
	$openCloseMatchInt = 2;
}


$strOpen  = " ShopCode: ".$_POST['shop_code']." Date: ".$_POST['date_record']." Time: ".$_POST['time_record']." Day Of Week: ".$_POST['day_record']." <br />";
$strOpen .= " Staff Prepare: ".$_POST['staff_name']." Staff On Duty: ".$_POST['staff_on_duty1']." Staff On Duty: ".$_POST['staff_on_duty2']."<br />";
$strOpen .= "<h3>Cash Counting Opening</h3>";
$strOpen .= " Qty of Note $100: [".$_POST['qty_note_100']."] Amount of Note $100: ".$_POST['amount_note_100']."<br />";
$strOpen .= " Qty of Note $50: [".$_POST['qty_note_50']."] Amount of Note $50: ".$_POST['amount_note_50']."<br />";
$strOpen .= " Qty of Note $20: [".$_POST['qty_note_20']."] Amount of Note $20: ".$_POST['amount_note_20']."<br />";
$strOpen .= " Qty of Note $10: [".$_POST['qty_note_10']."] Amount of Note $10: ".$_POST['amount_note_10']."<br />";
$strOpen .= " Qty of Note $5: [".$_POST['qty_note_5']."] Amount of Note $5: ".$_POST['amount_note_5']."<br />";
$strOpen .= " Qty of coin $2: [".$_POST['qty_coin_2']."] Amount of coin $2: ".$_POST['amount_coin_2']."<br />";
$strOpen .= " Qty of coin $1: [".$_POST['qty_coin_1']."] Amount of coin $1: ".$_POST['amount_coin_1']."<br />";
$strOpen .= " Qty of coin 50 Cent: [".$_POST['qty_coin_05']."] Amount of coin 50 Cent: ".$_POST['amount_coin_05']."<br />";
$strOpen .= " Qty of coin 20 Cent: [".$_POST['qty_coin_02']."] Amount of coin 20 Cent: ".$_POST['amount_coin_02']."<br />";
$strOpen .= " Qty of coin 10 Cent: [".$_POST['qty_coin_01']."] Amount of coin 10 Cent: ".$_POST['amount_coin_01']."<br />";
$strOpen .= " Qty of coin 5 Cent: [".$_POST['qty_coin_005']."] Amount of coin 5 Cent: ".$_POST['amount_coin_005']."<br />";
$strOpen .= "--------------------------<br/>";
$strOpen .= "Total Cash Opening : ".$_POST['total_open_cash']."<br />";
$strOpen .= "Cash Out Or Bank In Amount : ".$_POST['cashout_amount']."<br />";
$strOpen .= "Cash Handling Method :".$strMethod."<br />";
$strOpen .= "Closeing Cash Balance Yesterday :".$_POST['close_balance_yesterday']."<br />";
$strOpen .= "Opening Cash In Till :".$_POST['opening_cash_in_till']."<br />";
$strOpen .= "Match Status: ".$openCloseMatch."<br/>";
$strOpen .= "Remark :<br />".$_POST['open_balance_not_match']."<br />";
$strOpen .= "Yesterday Shop Open Status".$_POST["r_shop_open"];
$strOpen .= "Manual Input Closing Yesterday".$_POST["manual_input_cash_closing"];


fwrite($fl,$strOpen);
fclose($fl);

$cashArray = $caOpen->buildCashCountingArr(
		$_POST['qty_note_100'],$_POST['amount_note_100'],
		$_POST['qty_note_50'],$_POST['amount_note_50'],
		$_POST['qty_note_20'],$_POST['amount_note_20'],
		$_POST['qty_note_10'],$_POST['amount_note_10'],
		$_POST['qty_note_5'],$_POST['amount_note_5'],
		$_POST['qty_coin_2'],$_POST['amount_coin_2'],
		$_POST['qty_coin_1'],$_POST['amount_coin_1'],
		$_POST['qty_coin_05'],$_POST['amount_coin_05'],
		$_POST['qty_coin_02'],$_POST['amount_coin_02'],
		$_POST['qty_coin_01'],$_POST['amount_coin_01'],
		$_POST['qty_coin_005'],$_POST['amount_coin_005']		
		);

// now save to DB
//print_r($cashArray);
$checkDvr = $_POST["check_dvr"];
//if(isset($_POST["check_dvr"])){
//	$checkDvr = 1;
//}


if($dateToday != Model_DatetimeHelper::dateToday()){
	//see if opening already done 
	
	$openRow = $caOpen->getActiveOpeningByDate(trim($_POST['shop_code']),trim($_POST['date_record']));
	if(!empty($openRow) && $openRow[0]["staff_name_prepare_open"] != "System"){
		
		$this->view->caopenresult = "<h1>Data WIll Not Be Saved</h1> The opening Form has already been Filled, you do not need to do it again <br /> You may close this window now";

	}
	else{
		$idOpen = $caOpen->addCAOpening(trim($_POST['shop_code']),trim($_POST['date_record']), trim($_POST['time_record']),trim($_POST['day_record']),
				trim($_POST['staff_name']),trim($_POST['staff_on_duty1']),trim($_POST['staff_on_duty2']),
				$cashArray, $_POST['total_open_cash']-$_POST['cashout_amount'], $cashOut, $bankIn, $openCloseMatchInt, $_POST['open_balance_not_match'],$_POST["r_shop_open"],$_POST["manual_input_cash_closing"],$checkDvr);
		$caOpen->markMissed($idOpen);
		
		$this->view->caopenresult = "<h1>Thanks very much for your Fill</h1> The Data Has Been Saved, You may able to print out summary after fill in Closing Form <br /> You may close this window now";
		
		
	}

//check if conflict 


}else{
	$idOpen = $caOpen->addCAOpening(trim($_POST['shop_code']),trim($_POST['date_record']), trim($_POST['time_record']),trim($_POST['day_record']),
			trim($_POST['staff_name']),trim($_POST['staff_on_duty1']),trim($_POST['staff_on_duty2']),
			$cashArray, $_POST['total_open_cash']-$_POST['cashout_amount'], $cashOut, $bankIn, $openCloseMatchInt, $_POST['open_balance_not_match'],$_POST["r_shop_open"],$_POST["manual_input_cash_closing"],$checkDvr);
	$this->view->caopenresult = "<h1>Thanks very much for your Fill</h1> The Data Has Been Saved, You may able to print out summary after fill in Closing Form <br /> You may close this window now";
		
	
	
	
}

	}
		
		
	public function saveclosingAction(){
		date_default_timezone_set('Australia/Melbourne');
		
		$date = $_POST['date_record'];
		$folder = getcwd()."/ca/".trim($_POST['shop_code'])."/";
		$caClose =  new Model_DbTable_Cashaccountclosing();
		$caOpen =  new Model_DbTable_Cashaccountopening();
		$openBalanceToday = 0;
		//$cashArrClose = array();
		
		
		//$openBalanceArr = $caOpen->getTodayActiveOpening(trim($_POST['shop_code']));
		
		if(isset($_POST["open_balance_normal"])){
			
			$openBalanceToday = $_POST["open_balance_normal"];	
			
		}
		else{
			$openBalanceToday = $_POST["manual_cash_opening"];	 
			
		}
				
		$fileName =$_POST['shop_code']."-closing-".$date.".html";
		$netSalesDiff = 0;
		$expenseArr = $caClose->buildExpenseClosingArr(
				$_POST['name_expense_1'],$_POST['sub_expense_1'],$_POST['staff_name_expense_1'],
				$_POST['name_expense_2'],$_POST['sub_expense_2'],$_POST['staff_name_expense_2'],
				$_POST['name_expense_3'],$_POST['sub_expense_3'],$_POST['staff_name_expense_3'],
				$_POST['name_expense_4'],$_POST['sub_expense_4'],$_POST['staff_name_expense_4'],
				$_POST['name_expense_5'],$_POST['sub_expense_5'],$_POST['staff_name_expense_5'],
				$_POST['name_expense_6'],$_POST['sub_expense_6'],$_POST['staff_name_expense_6']);
		
		$cashArrClose = $caClose->buildCashCountingClosingArr(
		$_POST['qty_note_100'],$_POST['amount_note_100'],
		$_POST['qty_note_50'],$_POST['amount_note_50'],
		$_POST['qty_note_20'],$_POST['amount_note_20'],
		$_POST['qty_note_10'],$_POST['amount_note_10'],
		$_POST['qty_note_5'],$_POST['amount_note_5'],
		$_POST['qty_coin_2'],$_POST['amount_coin_2'],
		$_POST['qty_coin_1'],$_POST['amount_coin_1'],
		$_POST['qty_coin_05'],$_POST['amount_coin_05'],
		$_POST['qty_coin_02'],$_POST['amount_coin_02'],
		$_POST['qty_coin_01'],$_POST['amount_coin_01'],
		$_POST['qty_coin_005'],$_POST['amount_coin_005']		
		);
		
		//var_dump($cashArrClose);
		
		$fl =fopen($folder.$fileName,"w+");
//$str = print_r($_POST,true);

if(isset($_POST['ind_inconsistency']) && $_POST['ind_inconsistency'] == 0 ){
	
	$netSalesDiff = 1;
}		
		
		
$strClose  = " ShopCode: ".$_POST['shop_code']." Date: ".$_POST['date_record']." Time: ".$_POST['time_record']." Day Of Week: ".$_POST['day_record']." <br />";
$strClose .= " Staff Prepare: ".$_POST['staff_name']." Staff On Duty: ".$_POST['staff_on_duty1']." Staff On Duty: ".$_POST['staff_on_duty2']."<br />";
$strClose .= "<h3>Expense Record</h3>";
$strClose .= "1)".$_POST['name_expense_1']." Amount:".$_POST['sub_expense_1']." StaffName: ".$_POST['staff_name_expense_1']."<br />";
$strClose .= "2)".$_POST['name_expense_2']." Amount:".$_POST['sub_expense_2']." StaffName: ".$_POST['staff_name_expense_2']."<br />";
$strClose .= "3)".$_POST['name_expense_3']." Amount:".$_POST['sub_expense_3']." StaffName: ".$_POST['staff_name_expense_3']."<br />";
$strClose .= "4)".$_POST['name_expense_4']." Amount:".$_POST['sub_expense_4']." StaffName: ".$_POST['staff_name_expense_4']."<br />";
$strClose .= "5)".$_POST['name_expense_5']." Amount:".$_POST['sub_expense_5']." StaffName: ".$_POST['staff_name_expense_5']."<br />";
$strClose .= "6)".$_POST['name_expense_6']." Amount:".$_POST['sub_expense_6']." StaffName: ".$_POST['staff_name_expense_6']."<br />";
$strClose .= "Total:".$_POST['sub_expense_total']." Approved By".$_POST['approved_manager']."<br />";
$strClose .= "<h3>Cash Counting Closing</h3>";
$strClose .= " Qty of Note $100: [".$_POST['qty_note_100']."] Amount of Note $100: ".$_POST['amount_note_100']."<br />";
$strClose .= " Qty of Note $50: [".$_POST['qty_note_50']."] Amount of Note $50: ".$_POST['amount_note_50']."<br />";
$strClose .= " Qty of Note $20: [".$_POST['qty_note_20']."] Amount of Note $20: ".$_POST['amount_note_20']."<br />";
$strClose .= " Qty of Note $10: [".$_POST['qty_note_10']."] Amount of Note $10: ".$_POST['amount_note_10']."<br />";
$strClose .= " Qty of Note $5: [".$_POST['qty_note_5']."] Amount of Note $5: ".$_POST['amount_note_5']."<br />";
$strClose .= " Qty of coin $2: [".$_POST['qty_coin_2']."] Amount of coin $2: ".$_POST['amount_coin_2']."<br />";
$strClose .= " Qty of coin $1: [".$_POST['qty_coin_1']."] Amount of coin $1: ".$_POST['amount_coin_1']."<br />";
$strClose .= " Qty of coin 50 Cent: [".$_POST['qty_coin_05']."] Amount of coin 50 Cent: ".$_POST['amount_coin_05']."<br />";
$strClose .= " Qty of coin 20 Cent: [".$_POST['qty_coin_02']."] Amount of coin 20 Cent: ".$_POST['amount_coin_02']."<br />";
$strClose .= " Qty of coin 10 Cent: [".$_POST['qty_coin_01']."] Amount of coin 10 Cent: ".$_POST['amount_coin_01']."<br />";
$strClose .= " Qty of coin 5 Cent: [".$_POST['qty_coin_005']."] Amount of coin 5 Cent: ".$_POST['amount_coin_005']."<br />";
$strClose .= "--------------------------<br/>";
$strClose .= "Total Closing Cash".$_POST['close_balance']."<br />";
$strClose .= "<h3>Electronic Cash</h3>";
$strClose .= "Qty of Merchant Copy".$_POST['qty_merchant_copy']."<br />";
$strClose .= "Credit Card / Debit Amount ".$_POST['dbcr_cash']."<br />";
$strClose .= "Amex Credit".$_POST['amex_cash']."<br />";
$strClose .= "Offline Voucher".$_POST['offline_voucher']."<br />";
$strClose .= "Total Electronic Cash".$_POST['total_electronic_cash']."<br />";
$strClose .= "--------------------------<br/>";
$strClose .= "Credit Note".$_POST['credit_note']."<br />";
$strClose .= "Coupon Note".$_POST['coupon_note']."<br />";
$strClose .= "--------------------------<br/>";
$strClose .= "<h3>Summary </h3>";
$strClose .= "--------------------------<br/>";
$strClose .= "Cash Sales:".$_POST['total_cash_sales']."<br />";
$strClose .= "Electronic Cash:".$_POST['total_electronic_sales']."<br />";
$strClose .= "Total Expense:".$_POST['total_expense_today']."<br />";
$strClose .= "Total Net Sales:".$_POST['total_net_sales']."<br />";
$strClose .= "APOS:".$_POST['apos_total']."<br />";
$strClose .= "Cash Discrepancy:".$_POST['cash_discrepancy']."<br />";
$strClose .= "indcator of Inconsistency:".$netSalesDiff."<br />";
$strClose .= "Remark:".$_POST['remark_cont']."<br />";



		fwrite($fl,$strClose);
		fclose($fl);
//save into DB
/*
($shopHead,$dateRecord,$timeRecord,$dayRecord,
$staffPreapre,$staff1,$staff2,
$expenseArr,$expenseTotal,$managerName,
$cashInTillClose,
$cashArrClose,$openBalanceRecord,$closeCashBalance,$totalCashSales,
$qtyMerchantCopy,$drcrAmount,$amexAmount,$offlineAmount,$totalElectronicCash,
 $creditNoteAmount,$couponNoteAmount,
 $totalNetSalesClose,$aposTotal,$cashDif,$indInconsistency,$remarkClose)		

*/		$shouldAdd = true;

		if($date != Model_DatetimeHelper::dateToday()){
			//see if opening already done
		
			$closeRow = $caClose->getActiveClosingByDate(trim($_POST['shop_code']),trim($_POST['date_record']));
			if(!empty($closeRow) && $closeRow[0]["staff_name_prepare_close"] != "System"){
				
				$shouldAdd = false;
				$this->view->caopenresult = "<h1>Data WILL Not Be Saved</h1> The Closing Form has already been Filled, you do not need to do it again <br /> You may close this window now";
		
			}
		}
			
		if($shouldAdd){
		
		$caClose->addCAClosing(		
		trim($_POST['shop_code']),trim($_POST['date_record']), trim($_POST['time_record']),trim($_POST['day_record']),
		trim($_POST['staff_name']),trim($_POST['staff_on_duty1']),trim($_POST['staff_on_duty2']),
		 $expenseArr, $_POST['sub_expense_total'], $_POST['approved_manager'],
		 $_POST['close_balance'], 
		$cashArrClose,$openBalanceToday, $_POST['close_balance'],$_POST['total_cash_sales'], 
		$_POST['qty_merchant_copy'],$_POST['dbcr_cash'], $_POST['amex_cash'],$_POST['offline_voucher'],$_POST['total_electronic_sales'],
		$_POST['credit_note'],$_POST['coupon_note'],
		$_POST['total_net_sales'],$_POST['apos_total'],$_POST['cash_discrepancy'],$netSalesDiff, $_POST['remark_cont'],$_POST["sync_status"]);
		
		
		$this->view->cacloseresult = "<h1>Thanks very much for your Fill</h1> <a href=\"/cashaccount/printsummary/shop/".$_POST['shop_code']."\" target=\"blank\" >Click Here To Print Today Summary</a>";
		$params = array('shop'=>$_POST['shop_code']);
		$this->_helper->redirector("printsummary","cashaccount",NULL,$params);	
		}
			
		}	
			
	public function printsummaryAction(){
		
		$shopHead = $this->_getParam('shop');
		$folder = getcwd()."/ca/";
		$templateFilenName= "casummary.html";
		$shopFolder = $shopHead."/";
		
		
		date_default_timezone_set('Australia/Melbourne');
		$dateToday=date('Y-m-d');
		
		$summaryFileName = $shopHead."-".$dateToday."-sum.html";
		
		
		$caOpen =  new Model_DbTable_Cashaccountopening();
		$caClose = new Model_DbTable_Cashaccountclosing();
		
		$rowsOpen = $caOpen->getActiveOpeningToday($shopHead);
		$rowsClose = $caClose->getActiveClosingToday($shopHead);
		$strFile = "";
		//echo "<pre>";
		//var_dump($rowsOpen,$rowsClose);
		//echo "</pre>";
		if($rowsOpen && $rowsClose){
		
			
		$shopCode = $rowsOpen['shop_code_ca'];
		$dateRecord = $rowsOpen['date_record_open'];
		$dayRecord = $rowsOpen['day_record_open'];
		$totalOpenCash = $rowsOpen['open_balance'] +  $rowsOpen['amount_cash_out_open'] + $rowsOpen['amount_bank_in_open'] ;
		$cashOut = $rowsOpen['amount_cash_out_open'] ;
		$cashBankIn =  $rowsOpen['amount_bank_in_open'] ;
		$openCashInTill = $rowsOpen['open_balance'] ;
		$totalExpense =  $rowsClose['sub_expense_total'] ;
		$totalCloseCash =  $rowsClose['close_balance_cash_close'] ;
		$totalCashSales =   $rowsClose['total_cash_sales_close'] ;
		$cashOutTomorrow =  $rowsClose['total_cash_sales_close'] - $rowsClose['sub_expense_total'];
		$qtyMCopy = $rowsClose['qty_merchant_copy_close']; 
		$dbcrAmt = $rowsClose['drcr_electronic_cash_close'];
		$amexAmt = $rowsClose['amex_electronic_cash_close']; 
		$offLine = $rowsClose['offline_voucher_close']; 
		$totalElecCash = $rowsClose['total_electronic_cash_close']; 
		$creditN = $rowsClose['credit_note'] ;
		$couponN = $rowsClose['coupon_note'];
		$totalNetSales = $rowsClose['total_net_sales_close'] ;
		$aPosRecord = $rowsClose['apos_total'] ;
		$remarkOpen = $rowsOpen['remark_open_cash_not_match'];
		$remarkClose= $rowsClose['remark_close'];
		$pExp1 = $rowsClose['product_expense_1'];
		$aExp1 = $rowsClose['sub_expense_1'];
		$nExp1 = $rowsClose['staff_name_expense_1'];
		$pExp2 = $rowsClose['product_expense_2'];
		$aExp2 = $rowsClose['sub_expense_2'];
		$nExp2 = $rowsClose['staff_name_expense_2'];
		$pExp3 = $rowsClose['product_expense_3'];
		$aExp3 = $rowsClose['sub_expense_3'];
		$nExp3 = $rowsClose['staff_name_expense_3'];
		$pExp4 = $rowsClose['product_expense_4'];
		$aExp4 = $rowsClose['sub_expense_4'];
		$nExp4 = $rowsClose['staff_name_expense_4'];
		$pExp5 = $rowsClose['product_expense_5'];
		$aExp5 = $rowsClose['sub_expense_5'];
		$nExp5 = $rowsClose['staff_name_expense_5'];
		$pExp6 = $rowsClose['product_expense_6'];
		$aExp6 = $rowsClose['sub_expense_6'];
		$nExp6 = $rowsClose['staff_name_expense_6'];				
		$expMgr= $rowsClose['expense_approved_manager'];
		$openId= $rowsOpen['id_cashaccount_open'];
		$closeId = $rowsClose['id_cashaccount_close'];
		$openStaff = $rowsOpen['staff_name_prepare_open'];
		$closeStaff = $rowsClose['staff_name_prepare_close'];
		
		//read file 
		$fl = fopen($folder.$templateFilenName,"r");
		$strFile = fread($fl, filesize($folder.$templateFilenName));
		fclose($fl);
		// Begin Replace
		
		$strFile = str_replace("{_shopcode_}", $shopCode,$strFile);
		$strFile = str_replace("{_date_}", $dateRecord,$strFile);
		$strFile = str_replace("{_day_}", $dayRecord,$strFile);
		$strFile = str_replace("{_toc_}", $totalOpenCash,$strFile);
		$strFile = str_replace("{_cashout_}", $cashOut,$strFile);
		$strFile = str_replace("{_bank_}", $cashBankIn,$strFile);
		$strFile = str_replace("{_ocit_}", $openCashInTill,$strFile);
		$strFile = str_replace("{_exp_}", $totalExpense,$strFile);
		$strFile = str_replace("{_tcc_}", $totalCloseCash,$strFile);
		$strFile = str_replace("{_tcs_}", $totalCashSales,$strFile);
		$strFile = str_replace("{_ftco_}", $cashOutTomorrow,$strFile);
		$strFile = str_replace("{_qmc_}", $qtyMCopy,$strFile);
		$strFile = str_replace("{_drcr_}", $dbcrAmt,$strFile);
		$strFile = str_replace("{_amex_}", $amexAmt,$strFile);
		$strFile = str_replace("{_offline_}", $offLine,$strFile);
		$strFile = str_replace("{_tes_}", $totalElecCash,$strFile);
		$strFile = str_replace("{_crn_}", $creditN,$strFile);
		$strFile = str_replace("{_con_}", $couponN,$strFile);
		$strFile = str_replace("{_tns_}", $totalNetSales,$strFile);
		$strFile = str_replace("{_apos_}", $aPosRecord,$strFile);
		$strFile = str_replace("{_remarkopen_}", $remarkOpen,$strFile);	
		$strFile = str_replace("{_remarkclose_}", $remarkClose,$strFile);
		$strFile = str_replace("{_subexp1_}", $pExp1,$strFile);			
		$strFile = str_replace("{_amtexp1_}", $aExp1,$strFile);
		$strFile = str_replace("{_nameexp1_}", $nExp1,$strFile);				
		$strFile = str_replace("{_subexp2_}", $pExp2,$strFile);
		$strFile = str_replace("{_amtexp2_}", $aExp2,$strFile);
		$strFile = str_replace("{_nameexp2_}", $nExp2,$strFile);		
		$strFile = str_replace("{_subexp3_}", $pExp3,$strFile);
		$strFile = str_replace("{_amtexp3_}", $aExp3,$strFile);
		$strFile = str_replace("{_nameexp3_}", $nExp3,$strFile);
		$strFile = str_replace("{_subexp4_}", $pExp4,$strFile);
		$strFile = str_replace("{_amtexp4_}", $aExp4,$strFile);
		$strFile = str_replace("{_nameexp4_}", $nExp4,$strFile);
		$strFile = str_replace("{_subexp5_}", $pExp5,$strFile);
		$strFile = str_replace("{_amtexp5_}", $aExp5,$strFile);
		$strFile = str_replace("{_nameexp5_}", $nExp5,$strFile);						
		$strFile = str_replace("{_subexp6_}", $pExp6,$strFile);
		$strFile = str_replace("{_amtexp6_}", $aExp6,$strFile);
		$strFile = str_replace("{_nameexp6_}",$nExp6,$strFile);
		$strFile = str_replace("{_magexp_}", $expMgr,$strFile);
		$strFile = str_replace("{_openid_}", $openId,$strFile);
		$strFile = str_replace("{_closeid_}",$closeId,$strFile);
		$strFile = str_replace("{_opsn_}", $openStaff,$strFile);
		$strFile = str_replace("{_clsn_}", $closeStaff,$strFile);
		//replace file 

		// save file
		$fl2 = fopen($folder.$shopFolder.$summaryFileName,"w+");
		fputs($fl2,$strFile);
		fclose($fl2);
		// out put
		}
		else{
			$strFile = false;
		}
		$this->view->output = $strFile;
			
	}
	
	public function iWentToBankTodayAction(){
		$shop = $this->_getParam("shop");
		
		
		$dateEnd = Model_DatetimeHelper::dateToday();
		$dateEndInput = $this->_getParam("date_end");
		
		if($dateEndInput!=""){
			$dateEnd = $dateEndInput;
		}
		
		$showDateDep = $this->_getParam("not_today");
		
		if($showDateDep=="yes"){
			
			$this->view->showDateDep = true;
		}
		
		
		$dateBegin = Model_DatetimeHelper::adjustDays("sub",$dateEnd,7);
		
		$arrDate = Model_DatetimeHelper::createDateArray($dateBegin, $dateEnd);
		
		$stDetail = new Model_DbTable_Roster_Stafflogindetail();
		
		
		
		$caOpen = new Model_DbTable_Cashaccountopening();
		$dDetail = new Model_Account_Deposite_Detail();
		$dRecord = new Model_Account_Deposite_Record();
		
		$arrDpRecord = array(); // date , yes/no , array( amount, amount ) 
		
		if($_POST){
				
			//check password
			//$idManager = 110;
			$pwd = Model_EncryptHelper::hashsha($_POST['manager_pwd']);
			$stLine = $stDetail->checkPasswordCorrect($pwd);
				
			$mComment = $_POST['manager_comment'];
			$dateDepo =  Model_DatetimeHelper::dateToday();
			
			if(isset($_POST['diff_date'])){

				$dateDepo = $_POST['diff_date'];
			}
			
			$idRecord = $dRecord->bankInRecord($stLine['id'],$dateDepo, $_POST['total_bkin'], $_POST['manager_comment'],$shop);
			foreach($_POST['bi_amt'] as $key => $v){
				if(isset($_POST['bi_confirm'][$key])){
						
					$dDetail->addDetail($idRecord, $shop,$key, $v);
				}
		
			}		
		}
		
		foreach($arrDate as $dateToCheck){
			
			$caLine = $caOpen->getActiveOpeningByDate($shop, $dateToCheck);
			
			$biAmt = $caLine[0]['amount_bank_in_open'];
			$coAmt = $caLine[0]['amount_cash_out_open'];
				
			$dRows = $dDetail->getByShopByDate($shop, $dateToCheck);
			
			if(!$dRows) {		
				$arrDpRecord[] = array($dateToCheck,$biAmt,$coAmt,false);
			}
			else{
				$arrDpRecord[] = array($dateToCheck,$biAmt,$coAmt,true,$dRows);
			}
			
			//see if deposited 
			
			//if Not Listed 
			
			// if deposted , still you can deposite the second time 
		}
		
		$this->view->arrDpRecord = $arrDpRecord;
		//var_dump($arrDpRecord);	
		

			
			//$dRecord->addRecord($idManager, $dateDeposit, $totalAmtDeposit, $totalActAmt, $depositConfirmed, $confirmDate, $manualConfirmed, $manualConfirmedDate, $commentManager, $commentAuditor);
			
			
			//password correct
			//$dRecord->addRecord($idManager, $dateDeposit, $totalAmtDeposit, $totalActAmt, $depositConfirmed, $confirmDate, $manualConfirmed, $manualConfirmedDate, $commentManager, $commentAuditor);
					
			//create records
			
			//$dDetail->addDetail($idRecord, $shopCode, $dateBankin, $amtBankin, $actAmtBankin, $amtMatch);
			
			
			
			
		
	}
	
	public function saveDepositRecordAction(){
		//get Last Seven Days you choose to Bank in 
		//Select Date 
		// Calculate Sum
		//remark difference
		//record
		
		
		
	}
}
?>