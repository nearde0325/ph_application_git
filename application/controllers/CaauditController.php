<?php 
/**

 */
class CaauditController extends Zend_Controller_Action
{

	protected $cdCashOutAndBankIn = 0;
	protected $cdCashSales = 20;
	protected $cdQtyOfCopy = 0;
	protected $cdDRCRSales = 20;
	protected $cdTotalExpense = 0;
	protected $cdOffline = 0;
	protected $cdCreditNote = 0;
	protected $cdCoupon = 0;
	
	protected $arrShopHead = array(
			"HPPC"			
			);
	
	protected $arrManagerMapping = array(
				//1=> array("COPC","BBPC","FGIC","CBPC","BSPC","BSIC","BSXP","KCPC","CSIC","DCIC","PMPC","SLIC","PMIC","EPPC","NLPC","HPIC","BHPC","ADPC","CLPC","WLIC","CLIC","WBPC","WBIC","WGIC","WGPC","HPPC"),//norman
				//110 => array("BBPC","FGIC","CBPC","BSPC","BSIC","BSXP","KCPC","CSIC","DCIC","PMPC","SLIC","PMIC","EPPC","NLPC","HPIC","BHPC","ADPC","CLPC","WLIC","CLIC","WBPC","WBIC","WGIC","WGPC","HPPC"),//Admin
				//110 => array("ADPC","BBPC"),
			    2 => array("CBPC","PMPC","PMIC","SLIC"),// hope
				3 => array("FGIC"),//adele
				4 => array("KCPC","CSIC","DCIC","ADPC","CLPC","WLIC","CLIC","BBPC","HPIC","BSPC","BSIC","BSXP"),//simon
				//5 => array("PMPC","SLIC","PMIC"), //yin
				6 => array("EPPC","NLPC","WGIC","WGPC"),  // emily 
				8 => array("HPIC"), //karen 
				//27 => array("BHPC","KCPC"),//terra
				61 => array("ADPC","CLPC","WLIC","CLIC"), //cc
				80 => array("WBPC","WBIC"),// sophia
				//88 => array("WGIC","WGPC"), // cherish
				//67 => array("CLPC","CLIC"), // matilda
				//71 => array("ADPC","WLIC"), // asheley
				//85 => array("WGIC","WGPC"), //lina
				26 => array("CSIC","BSPC","BSIC","BSXP"),
				165 => array("BSPC","BSIC","BSXP"),						//christina
				115 => array("DCIC"),// iris
				131 => array("ADPC","CLPC","WLIC","CLIC"),		//Jason	
				118 => array("CSIC"), //alisa	
				//162 => array("WGIC"), //kelly
				//173 => array("NLPC"),
				125 => array("WBIC","WBPC"),
				36 => array("EPPC","NLPC"),
				162 => array("WGPC","WGIC"), //kelly
				//36 => array("NLPC","EPPC")	//chow
				);
	
	public function init(){
    /**
	 *
	 *
	 */    
	
		$this->arrManagerMapping = unserialize(ARR_MANAGER);
	}

    public function indexAction(){
	
	$shopHead = $this->_getParam('shop');
	if($shopHead!=""){
		
		$this->view->shophead = $shopHead; 
		$this->view->errorflag = false;
		
		}
	else{
		
		$this->view->errorflag = true;
	
		}	
    
	}
	public function circleAction(){
		
		set_time_limit(0);
		ob_start();
		
		$dateBegin = Model_DatetimeHelper::getLastWeekMonday();
		$dateEnd = Model_DatetimeHelper::getLastWeekSunday();
		
		$db = $this->_getParam("date_begin");
		$de = $this->_getParam("date_end");
		
		if($db!=""){
			$dateBegin = $db;
		}
		if($de!=""){
			$dateEnd = $de;
		}
		
		
		
		$dateArr = Model_DatetimeHelper::createDateArray($dateBegin, $dateEnd);
		$shopArr = array("ADPC","BBPC","BHPC","BSPC","BSIC","CBPC","CLPC","EPIC","KCPC","NLPC","PMPC","SLIC","WBPC","WBIC","WLIC","WGIC","WGPC","CSIC","CLIC","DCIC","PMIC","BSXP","FGIC","HPIC"); 
		//$shopArr=array("HPIC");
		foreach($dateArr as $date){
			foreach($shopArr as $shop){
				echo $str="http://localhost/caaudit/cash-account-apos/shop/".$shop."/date/".$date;
				echo "<br />";
				
				$res = file_get_contents($str);
				//sleep(1);
				echo $res;
				ob_flush();
				flush();
				//echo $date;
			}	
		}	
		
	
	}
	public function microCircleAction(){
		set_time_limit(0);
		ob_start();		
		
		$dateBegin = Model_DatetimeHelper::getThisWeekMonday();
		//$dateBegin =Model_DatetimeHelper::adjustDays("sub",Model_DatetimeHelper::dateToday(),2);
		$dateEnd = Model_DatetimeHelper::dateYesterday();
		$dateArr = Model_DatetimeHelper::createDateArray($dateBegin, $dateEnd);
		$shopArr = array("ADPC","BBPC","BHPC","BSPC","BSIC","CBPC","CLPC","EPPC","KCPC","NLPC","PMPC","SLIC","WBPC","WBIC","WLIC","WGIC","WGPC","CSIC","CLIC","DCIC","PMIC","BSXP","FGIC","HPIC");
		
		foreach($dateArr as $date){
			foreach($shopArr as $shop){
				echo $str="http://localhost/caaudit/cash-account-apos/shop/".$shop."/date/".$date;
				echo "<br />";
		
				$res = file_get_contents($str);
				//sleep(1);
				echo $res;
				ob_flush();
				flush();
				//echo $date;
			}
		}
		
		
	}
	public function miniCircleAction(){
		
		set_time_limit(0);
		ob_start();
		
		$dateBegin = Model_DatetimeHelper::adjustDays("sub",Model_DatetimeHelper::dateToday(),2);
		//$dateBegin =Model_DatetimeHelper::adjustDays("sub",Model_DatetimeHelper::dateToday(),2);
		$dateEnd = Model_DatetimeHelper::dateYesterday();
		$dateArr = Model_DatetimeHelper::createDateArray($dateBegin, $dateEnd);
		$shopArr = array("ADPC","BBPC","BHPC","BSPC","BSIC","CBPC","CLPC","EPIC","KCPC","NLPC","PMPC","SLIC","WBPC","WBIC","WLIC","WGIC","WGPC","CSIC","CLIC","DCIC","PMIC","BSXP","FGIC","HPIC");
		
		foreach($dateArr as $date){
			foreach($shopArr as $shop){
				echo $str="http://localhost/caaudit/cash-account-apos/shop/".$shop."/date/".$date;
				echo "<br />";		
				$res = file_get_contents($str);
				//sleep(1);
				echo $res;
				ob_flush();
				flush();
				//echo $date;
			}
		}
		
		
		file_get_contents("http://192.168.1.124/caaudit/mini-circle-done");
	}
	
	public function cashAccountAposAction(){
		
		$shop = $this->_getParam("shop");
		$dateInput = $this->_getParam("date");
		$dateToCheck = ($dateInput=="")?Model_DatetimeHelper::dateYesterday():$dateInput;
		$invApos = Model_Aposinit::initAposInvoice($shop);
		
		$arrInvoice = $invApos->getInvoicesByDate($dateToCheck);
		$arrCa = $invApos->getCashAccount($arrInvoice);
		
		$remoteClose = new Model_DbTable_Cashaccount_Remote_Closing(Zend_Registry::get('db_real'));
		$remoteClose->updateSystemData($arrCa, $shop, $dateToCheck);		
		
	}
	public function shortDailyReportAllAction(){
		$caOpen = new Model_DbTable_Cashaccountopening();
		$list = $caOpen->listAllShopcode();
		if($_POST){
			
		$dateBegin = ($_POST)?trim($_POST['date_begin']):trim($_GET['date_begin']);
		$dateEnd = ($_POST)?trim($_POST['date_end']):trim($_GET['date_end']);
			
			foreach($list as $key => $v){
				
				if($v["shop_code_ca"]!="HPPC"){
					echo $res = file_get_contents("http://localhost/caaudit/short-daily-report?shopcode=".$v["shop_code_ca"]."&date_begin=".$dateBegin."&date_end=".$dateEnd);
				}
				ob_flush();
			}
			
		}
		
	}
	public function shortDailyReportAposAction(){
		
		$caOpening = new  Model_DbTable_Cashaccountopening();
		$caClosing = new  Model_DbTable_Cashaccountclosing();	

		$arrAudit = array();
		$this->view->shopCode = "";
		date_default_timezone_set('Australia/Melbourne');
		
		if($_POST || $_GET){
			
			$shopCode = ($_POST)?trim($_POST['shopcode']):trim($_GET['shopcode']);
			$this->view->shopCode = $shopCode;
			
			$dateBegin = ($_POST)?trim($_POST['date_begin']):trim($_GET['date_begin']);
			$dateEnd = ($_POST)?trim($_POST['date_end']):trim($_GET['date_end']);			
			
			$dateToCheck = new DateTime($dateBegin);

			$cot = 0;
			while($dateToCheck <= new DateTime($dateEnd)){
				
				$rowsOpen  = $caOpening->getActiveOpeningByDate($shopCode,strval($dateToCheck->format('Y-m-d')));
				$rowsClose = $caClosing->getActiveClosingByDate($shopCode,strval($dateToCheck->format('Y-m-d')));
				
				$dateYesterday = new DateTime($dateToCheck->format('Y-m-d'));
				$dateYesterday->sub(new DateInterval('P1D'));
				$strDateYesterday = strval($dateYesterday->format('Y-m-d'));
					
				
				$rowsCloseYesterday = $caClosing->getLastBusDayClosing($shopCode,strval($dateToCheck->format('Y-m-d')));
				
				$arrTmp = array(
						$shopCode,
						$rowsOpen,
						$rowsClose,
						$rowsCloseYesterday,
						$strDateYesterday
				);
				$arrAudit[] = $arrTmp;
					
				$dateToCheck->add(new DateInterval('P1D'));
				
				$cot++;
				
				
			}
			
			$this->view->arraudit = $arrAudit;
			
		}
		
		
		
	}
	
	public function shortDailyReportAposCommbizAction(){
		
		$caOpening = new  Model_DbTable_Cashaccountopening();
		$caClosing = new  Model_DbTable_Cashaccountclosing();	

		$arrAudit = array();
		$this->view->shopCode = "";
		date_default_timezone_set('Australia/Melbourne');
		
		if($_POST || $_GET){
			
			$shopCode = ($_POST)?trim($_POST['shopcode']):trim($_GET['shopcode']);
			$this->view->shopCode = $shopCode;
			
			$dateBegin = ($_POST)?trim($_POST['date_begin']):trim($_GET['date_begin']);
			$dateEnd = ($_POST)?trim($_POST['date_end']):trim($_GET['date_end']);			
			
			$dateToCheck = new DateTime($dateBegin);

			$cot = 0;
			while($dateToCheck <= new DateTime($dateEnd)){
				
				$rowsOpen  = $caOpening->getActiveOpeningByDate($shopCode,strval($dateToCheck->format('Y-m-d')));
				$rowsClose = $caClosing->getActiveClosingByDate($shopCode,strval($dateToCheck->format('Y-m-d')));
				
				$dateYesterday = new DateTime($dateToCheck->format('Y-m-d'));
				$dateYesterday->sub(new DateInterval('P1D'));
				$strDateYesterday = strval($dateYesterday->format('Y-m-d'));
					
				
				$rowsCloseYesterday = $caClosing->getLastBusDayClosing($shopCode,strval($dateToCheck->format('Y-m-d')));
				
				$arrTmp = array(
						$shopCode,
						$rowsOpen,
						$rowsClose,
						$rowsCloseYesterday,
						$strDateYesterday
				);
				$arrAudit[] = $arrTmp;
					
				$dateToCheck->add(new DateInterval('P1D'));
				
				$cot++;
				
				
			}
			
			$this->view->arraudit = $arrAudit;
			
		}
		
		
		
	
		
	}
	
	public function shortDailyReportAction(){
		
		$caOpening = new  Model_DbTable_Cashaccountopening();
		$caClosing = new  Model_DbTable_Cashaccountclosing();
		$fh = new Model_Fileshandler();
		
		
		$arrAudit = array();
		$this->view->shopCode = "";
		date_default_timezone_set('Australia/Melbourne');	
		
		if($_POST || $_GET){
		
		$shopCode = ($_POST)?trim($_POST['shopcode']):trim($_GET['shopcode']);
		$this->view->shopCode = $shopCode;
		
		$dateBegin = ($_POST)?trim($_POST['date_begin']):trim($_GET['date_begin']);
		$dateEnd = ($_POST)?trim($_POST['date_end']):trim($_GET['date_end']);

		
		$dateToCheck = new DateTime($dateBegin);
		$strDTC = date("Ymd",strtotime($dateBegin));
		$dateFileBegin = new DateTime("2013-04-29");
		$dateFileBegin490 = new DateTime("2013-05-13");
		
		$slFolder = SALES_LINE_FOLDER;
		
		$salesOldFileName = "SALESLINEOLD".$strDTC;
		$salesNewFileName = "SALESLINENEW".$strDTC;
		
		$salesOldOk = false;
		$salesNewOk = false;
		$arrSalesWeek = array();
		$arrPaymentMethod = array();
		
		if(file_exists(getcwd()."/".$slFolder."/".$salesOldFileName)){
			$salesOldOk = true;	
		}
		
		if(file_exists(getcwd()."/".$slFolder."/".$salesNewFileName)){
			$salesNewOk = true;
		}

		$salesOldMatchingArr =  array(
				"WBPC" => "WB",
				"BHPC" =>"BH",
				"CBPC" =>"CB",
				"NLPC" =>"NL",
				"CLPC" =>"CL",
				"WGPC" =>"WGPC",
				"WBIC" =>"WBIC",
				"BBPC" =>"BB",
				"EPPC" =>"EP",
				"WLIC" =>"WLIC",
				"KCPC" =>"KC",
				"PMPC" =>"PM",
				"BSIC" =>"BSIC",
				"BSPC" =>"BS",
				"SLIC" =>"SLIC",
				"ADPC" =>"AD",
				"WGIC" =>"WGIC",
				"BSXP" => "BSXP",
				"CLIC" => "CLIC",
				"CSIC" => "CSIC",
				"DCIC" => "DCIC",
				"FGIC" => "FGIC",
				"HPIC" => "HPIC",
				"PMIC" => "PMIC"
		);
		
		$salesNewMatchingArr = array(
				"BSXP" => "BSXP",
				"CLIC" => "CLIC",
				"CSIC" => "CSIC",
				"DCIC" => "DCIC",
				"FGIC" => "FGIC",
				"HPIC" => "HPIC",
				"PMIC" => "PMIC",
				"CBPC" =>"CB"
		);
				
		if($dateToCheck >= $dateFileBegin){
			
			if($shopCode == "BSXP" || $shopCode == "CLIC" ||  $shopCode == "DCIC" ||  $shopCode == "CSIC" ||  $shopCode == "FGIC" ||  $shopCode == "HPIC" ||  $shopCode == "PMIC"){

				$arrSalesWeek = $fh->findDailySaleWeek(getcwd()."/".$slFolder."/".$salesNewFileName, $salesNewMatchingArr[$shopCode], $dateBegin);
			
			}
			else{
				
				$arrSalesWeek = $fh->findDailySaleWeek(getcwd()."/".$slFolder."/".$salesOldFileName,$salesOldMatchingArr[$shopCode], $dateBegin);
			}
			
			
		}
		if($dateToCheck <= $dateFileBegin490){
			
			if($shopCode == "BSXP" || $shopCode == "CLIC" ||  $shopCode == "DCIC" ||  $shopCode == "CSIC" ||  $shopCode == "FGIC" ||  $shopCode == "HPIC" ||  $shopCode == "PMIC" || $shopCode == "CBPC" ){

				$arrPaymentMethod = $fh->readR490File(getcwd()."/R490NEW513.csv",$dateBegin,$dateEnd);
				//$fh->findDailySaleWeek(getcwd()."/".$slFolder."/".$salesNewFileName, $shopCode, $dateBegin);
			
			}
			else{
				
				$arrPaymentMethod = $fh->readR490File(getcwd()."/R490OLD513.csv",$dateBegin,$dateEnd);
			}
			
			
		}
		else{
			
			if($shopCode == "BSXP" || $shopCode == "CLIC" ||  $shopCode == "DCIC" ||  $shopCode == "CSIC" ||  $shopCode == "FGIC" ||  $shopCode == "HPIC" ||  $shopCode == "PMIC" || $shopCode == "CBPC" ){
			
				$arrPaymentMethod = $fh->readR490File(getcwd()."/R490NEW0526.CSV",$dateBegin,$dateEnd);
				//$fh->findDailySaleWeek(getcwd()."/".$slFolder."/".$salesNewFileName, $shopCode, $dateBegin);
					
			}
			else{
			
				$arrPaymentMethod = $fh->readR490File(getcwd()."/R490OLD0526.CSV",$dateBegin,$dateEnd);
			}			
			
		}
		
		//var_dump($arrSalesWeek);
		
		$cot = 0;
		while($dateToCheck <= new DateTime($dateEnd)){
			
			
			$rowsOpen  = $caOpening->getActiveOpeningByDate($shopCode,strval($dateToCheck->format('Y-m-d'))); 
			$rowsClose = $caClosing->getActiveClosingByDate($shopCode,strval($dateToCheck->format('Y-m-d'))); 

			$arrCashEft = $fh->cashOrEftposOld($arrPaymentMethod,$salesOldMatchingArr[$shopCode],$dateToCheck->format('Y-m-d'));
			
			//var_dump($arrCashEft);
			
			$dateYesterday = new DateTime($dateToCheck->format('Y-m-d'));
			$dateYesterday->sub(new DateInterval('P1D'));
			$strDateYesterday = strval($dateYesterday->format('Y-m-d'));
			
		
			$rowsCloseYesterday = $caClosing->getLastBusDayClosing($shopCode,strval($dateToCheck->format('Y-m-d')));

			
			
			$arrTmp = array(
					$shopCode,
					$rowsOpen,
					$rowsClose,
					$rowsCloseYesterday,
					$strDateYesterday
					);
			if($cot<8 && !empty($arrSalesWeek)){
				$arrTmp[] = $arrSalesWeek[$cot];
			}
			else{
				$arrTmp[] = "--";
			}
			$arrTmp[] = $arrCashEft;
			$arrAudit[] = $arrTmp;	
			
			$dateToCheck->add(new DateInterval('P1D'));
		
			$cot++;
		};
		
		
		$this->view->arraudit = $arrAudit;
		

		
		
		}
	}
	
	public function createDailySummaryAction(){
		
	
	$shopHead = new Model_DbTable_Shoplocation();
	$shopList = $shopHead->listHead();
	$arrDate = array();	
	$this->view->list = $shopList;	
	
	
	date_default_timezone_set('Australia/Melbourne');
	$dateToday = Model_DatetimeHelper::dateToday();
	
	$dateYesterday = Model_DatetimeHelper::adjustDays("sub",$dateToday,1);
	
	$this->view->dateToday = $dateToday;
	$this->view->dateYesterday = $dateYesterday;
	
	
	$this->view->shopCode = "";
	
	if($_POST){
		
		$this->view->shopCode = $_POST["shopcode"];
		
		$arrDate[] = $_POST['date_begin'];
		$tmpDate = $_POST['date_begin'];
		 
		while(($tmpDate = Model_DatetimeHelper::adjustDays("add",$tmpDate,1)) < $_POST['date_end']){
			$arrDate[] = $tmpDate; 	
		}		
		$arrDate[]= $_POST['date_end'];
		}	
		
	$this->view->arrDate = $arrDate;
	
	}
	public function dailyReportManagersAction(){
		
		$shop = $this->_getParam("shop");
		$date = Model_DatetimeHelper::dateToday();
		$dateToCheck = Model_DatetimeHelper::adjustDays("sub", $date, 2);
		
		$caOpening = new  Model_DbTable_Cashaccountopening();
		$caClosing = new  Model_DbTable_Cashaccountclosing();
		
		$rowsOpen  = $caOpening->getActiveOpeningByDate($shop,$dateToCheck);
		$rowsClose = $caClosing->getActiveClosingByDate($shop,$dateToCheck);
		
		echo "<pre>";
		var_dump($rowsOpen,$rowsClose);
		echo "</pre>";
		
		$this->view->date = $dateToCheck;
		$this->view->shop = $shop;
		
		$this->view->opening = $rowsOpen;
		$this->view->closing = $rowsClose;
		
	}
	
	public function shopRankAction(){
		
		$caClose = new Model_DbTable_Cashaccountclosing();
		$shopTarget = new Model_DbTable_Target_Weeklytarget();
		$shopArr = array("ADPC","BBPC","BHPC","BSPC","BSIC","CBPC","CLPC","EPPC","EPIC","KCPC","NLPC","PMPC","SLIC","WBPC","WBIC","WLIC","WGIC","WGPC","CSIC","CLIC","DCIC","PMIC","BSXP","FGIC","HPIC");
		
		$dateBeginLastWeek = Model_DatetimeHelper::getLastWeekMonday();
		$dateEndLastWeek = Model_DatetimeHelper::getLastWeekSunday();
		
		$dateBeginWeekBefore = Model_DatetimeHelper::adjustDays("sub", $dateBeginLastWeek,7);
		$dateEndWeekBefore = Model_DatetimeHelper::adjustDays("sub", $dateEndLastWeek,7); 
		
		
		
		$arrLastWeek = array();
		$arrWeekBefore = array();
		$arrShopPrecent = array();
		
		foreach($shopArr as $k => $v){
			
			$tmpArr = array();
			$tmpArr2 = array();
			
			$tmpArr[] = $v;
			$tmpArr2[] = $v;
			
			$tmpArr[] = $sumLastWeek = $caClose->getWeeklyAPOSSummaryShop($v, $dateBeginLastWeek, $dateEndLastWeek);
			$tmpArr[] = $caClose->getWeeklyAPOSSummaryShopDays($v, $dateBeginLastWeek, $dateEndLastWeek);
			$targetRow = $shopTarget->getWeeklyTargetByShopByDate($v, $dateBeginLastWeek); 
			$tmpArr[] = $targetRow["target"];
			$tmpArr2[] = $sumWeekBefore = $caClose->getWeeklyAPOSSummaryShop($v, $dateBeginWeekBefore, $dateEndWeekBefore);
			$arrLastWeek[] = $tmpArr;
			$arrWeekBefore[] = $tmpArr2;
			
			$arrShopPrecent[$v] = Round(($sumLastWeek-$sumWeekBefore)/$sumWeekBefore * 100,2);
			
		}

		echo "<pre>";
		//var_dump($arrLastWeek,$arrWeekBefore,$arrShopPrecent);
		echo "</pre>";
		
		$rankLastWeek = array();
		
		foreach($arrLastWeek as $key => $v1){
			
			$rankLastWeek[$key] = $v1[1];	
		}
		
		array_multisort($rankLastWeek,SORT_DESC,$arrLastWeek);
		
		
		$rankWeekBefore = array();
		
		
		foreach($arrWeekBefore as $key => $v2){
			
			$rankWeekBefore[$key] = $v2[1];
			
		}
		
		array_multisort($rankWeekBefore,SORT_DESC,$arrWeekBefore);
		
	
		$arrRes = array();
		

		foreach($arrLastWeek as $k3 =>$v3){
			
			$tmpArr = array();
			
			$tmpArr[] = $k3;
			$tmpArr[] = $v3[0];
			$tmpArr[] = $v3[1];
			
			//reset()
			foreach($arrWeekBefore as $k4 => $v4){
				
				if($v4[0] == $v3[0]){

					$tmpArr[] = $k4;
					$tmpArr[] = $v4[1];
				}		
			}
			
			$tmpArr[] = $arrShopPrecent[$v3[0]];
			$tmpArr[] = $v3[2];
			$tmpArr[] = $v3[3];
			
			
			$arrRes[] = $tmpArr;
		}
		
		echo "<pre>";
		//var_dump($arrRes);
		echo "</pre>";
		
		$this->view->arrRes = $arrRes;
	}
	
	public function miniCircleDoneAction(){
		
		$mail = new Model_Emailshandler();
		$mail->sendNormalEmail("eco1@phonecollection.com.au","mini circle","OK");
	
	}
		
	public function cashaccountManagerAction(){
		
		$idStaff = 0;
		
		$this->view->errorMessage = "";
		$this->view->showSecond = false;
		$this->view->arrShop = "";
		 
		$stDetail = new Model_DbTable_Roster_Stafflogindetail();
		
		if($_POST){

			if(isset($_POST["check_password"])){

				$pwd = Model_EncryptHelper::hashsha($_POST["pwd"]);
				$idRow = $stDetail->checkPasswordCorrect($pwd);				
				$idStaff = $idRow["id"];
				
				if(isset($this->arrManagerMapping[$idStaff])){
					
					$this->view->showSecond = true;
					$this->view->idManager = $idStaff;
					$this->view->arrShop = $this->arrManagerMapping[$idStaff];
						
				}
				else{
					
					$this->view->showSecond = false;
					$this->view->errorMessage = "Password Incorrect";
				}
				
			}
			
			if(isset($_POST["select_shop"])){
				
				$idManager = base64_encode(trim($_POST["id_manager"]));
				$shop=base64_encode(trim($_POST["shopcode"]));
				$dateBegin = $_POST["date_begin"];
				$dateEnd = $_POST["date_end"];
				$url = "/caaudit/cashaccount-manager/mgr/".$idManager."/shop/".$shop."/date_begin/".$dateBegin."/date_end/".$dateEnd;
				$this->_redirect($url);
				
			}		
		}

			
			$para = $this->_getAllParams();
		
			$idManager = (isset($para["mgr"]))?$para['mgr']:'';
			$shopCode = (isset($para["shop"]))?$para['shop']:'';
			$dateBegin = (isset($para["date_begin"]))?$para['date_begin']:'';
			$dateEnd = (isset($para["date_end"]))?$para['date_end']:'';
			
			
			$caOpening = new  Model_DbTable_Cashaccountopening();
			$caClosing = new  Model_DbTable_Cashaccountclosing();
			
			$arrAudit = array();
			$this->view->shopCode = "";
			date_default_timezone_set('Australia/Melbourne');
			
			if($idManager != "" && $shopCode != "" && $dateBegin != "" && $dateEnd !="" ){
			
				$shopCode = base64_decode($shopCode);
				$this->view->shopCode = $shopCode;
				$this->view->idManager = base64_decode($idManager);
				$this->view->arrShop = $this->arrManagerMapping[base64_decode($idManager)];
				$this->view->showSecond = true;
					
			
				//$dateBegin = $date
				//$dateEnd = ($_POST)?trim($_POST['date_end']):trim($_GET['date_end']);
			
				$dateToCheck = new DateTime($dateBegin);
			
				$cot = 0;
				while($dateToCheck <= new DateTime($dateEnd)){
			
					$rowsOpen  = $caOpening->getActiveOpeningByDate($shopCode,strval($dateToCheck->format('Y-m-d')));
					$rowsClose = $caClosing->getActiveClosingByDate($shopCode,strval($dateToCheck->format('Y-m-d')));
			
					$dateYesterday = new DateTime($dateToCheck->format('Y-m-d'));
					$dateYesterday->sub(new DateInterval('P1D'));
					$strDateYesterday = strval($dateYesterday->format('Y-m-d'));
						
			
					$rowsCloseYesterday = $caClosing->getLastBusDayClosing($shopCode,strval($dateToCheck->format('Y-m-d')));
			
					$arrTmp = array(
							$shopCode,
							$rowsOpen,
							$rowsClose,
							$rowsCloseYesterday,
							$strDateYesterday
					);
					$arrAudit[] = $arrTmp;
						
					$dateToCheck->add(new DateInterval('P1D'));
			
					$cot++;
			
			
				}
			
				$this->view->arraudit = $arrAudit;
			
			}

			
		
	}
	

	public function importCommbizFileAction(){
		
		$cbFile = new Model_Cbfilehandler();
		$caClose = new Model_DbTable_Cashaccountclosing(Zend_Registry::get('db_real'));
		
			$dateBegin = Model_DatetimeHelper::getLastWeekMonday();
			$dateEnd = Model_DatetimeHelper::getLastWeekSunday();
			
			$db = $this->_getParam("date_begin");
			$de = $this->_getParam("date_end");
			
			if($db!=""){
				$dateBegin = $db;
				$dateEnd = $de;				
			}

			
		$arrDate = Model_DatetimeHelper::createDateArray($dateBegin, $dateEnd);
		
		if($_POST){
			
			if(move_uploaded_file($_FILES['zip_file']['tmp_name'],$_FILES['zip_file']['name'])){

				$cbFile->handleUploadFile($_FILES['zip_file']['name']);
				
			}
			
		}
		
		$cbFile->handleUploadFile('1.zip');
		
		$shopArr = array("ADPC","BBPC","BHPC","BSPC","BSIC","CBPC","CLPC","EPPC","EPIC","KCPC","NLPC","PMPC","SLIC","WBPC","WBIC","WLIC","WGIC","WGPC","CSIC","CLIC","DCIC","PMIC","BSXP","FGIC","HPIC");
		foreach($shopArr as $shop){
			foreach($arrDate as $dateToCheck){
				
				$idCas = $caClose->getCAClosingByShopDate($shop,$dateToCheck,$dateToCheck);
				
				foreach($idCas as $idRow){
						
					$arrRes = $cbFile->runCbAposMatching($dateToCheck,$shop);
					$idCa = $idRow['id_ca_close'];
					$caClose->updateCommbiz($arrRes, $idCa);
				}				
			}
			
		}
		
		//var_dump($arrRes);
		
	}
	public function confirmBankinCashoutSummaryAction(){
		//working on last week or select week 
		
		$arrMissing = array();
		$this->view->showDetail = false;
		
		$showDetail = $this->_getParam("showdetail");
		
		if($showDetail == "yes"){
			
			$this->view->showDetail = true;
			
		}
		
		$shopArr = array("ADPC","BBPC","BHPC","BSPC","BSIC","CBPC","CLPC","EPPC","EPIC","KCPC","NLPC","PMPC","SLIC","WBPC","WBIC","WLIC","WGIC","WGPC","CSIC","CLIC","DCIC","PMIC","BSXP","FGIC","HPIC");
		sort($shopArr,SORT_ASC);
		
		$dateBegin = Model_DatetimeHelper::getLastWeekMonday();
		$dateEnd = Model_DatetimeHelper::getLastWeekSunday();
		
		$dateBeginAcc = $this->_getParam("date_begin");
		
		if($dateBeginAcc!=""){
			$dateBegin = $dateBeginAcc;
			$dateEnd = Model_DatetimeHelper::adjustDays("add", $dateBegin, 6);	
		}
		
		$this->view->dateArr = $dateArr = Model_DatetimeHelper::createDateArray($dateBegin, $dateEnd);
		$this->view->dateBegin = $dateBegin;
		
		$caClose = new Model_DbTable_Cashaccountclosing();
		$caOpen = new Model_DbTable_Cashaccountopening();
		
		
		$arrCaList = array();
		
		foreach($shopArr as $shopCode){
			
			$arrTmp = array();
			$totalBankIn = 0;
			$totalCashOut = 0;
			$totalEftInput = 0;
			
			$totalAposCash = 0;
			$totalAposEft = 0;
			
			$totalConfirmedBankIn = 0;
			$totalConfirmedCashOut = 0;
			
			$totalCbEft = 0;
			$totalAposSales = 0;
			
			$totalExpense = 0;
			$totalAe =0;
			$totalOffline = 0;
			$totalPassConfirm = 0;
			
			foreach($dateArr as $dateToCheck){
				
				$staffInputBi = "";
				$staffInputCo = "";
				$confirmBiStatus = "";
				$confirmCoStatus = "";
				$cfBiAmt = "";
				$cfCoAmt = "";
				
				$staffInputEft = "";
				$aposCashAmt = "";
				$aposEftAmt = "";
				$aposSalesTotalDaily = "";
				$cbEftAmt = "";
				$dailyExpense = "";
				$passConfirm = "";
				
				
				
				$closeLine = $caClose->getActiveClosingByDate($shopCode, $dateToCheck);
				$openLine = $caOpen->getActiveOpeningByDate($shopCode, Model_DatetimeHelper::adjustDays("add", $dateToCheck,1));
				
				
				if(isset($openLine[0])){
					
				$staffInputBi = $openLine[0]["amount_bank_in_open"];
				$staffInputCo = $openLine[0]["amount_cash_out_open"];
				$confirmBiStatus = $openLine[0]["bankin_confirm"];
				$confirmCoStatus = $openLine[0]["cashout_confirm"];
				$cfBiAmt = $openLine[0]["bankin_cf_amt"];
				$cfCoAmt = $openLine[0]["cashout_cf_amt"];
				
				}else{
					$arrMissing[] = array($shopCode,$dateToCheck,"Opening");
				}
				
				if(isset($closeLine[0])){
					
				$staffInputEft = $closeLine[0]["total_electronic_cash_close"];
				$aposCashAmt = $closeLine[0]["apos_sys_sales_cash"];
				$aposEftAmt = $closeLine[0]["apos_sys_sales_eft"];
				$aposSalesTotalDaily = $closeLine[0]["apos_sys_sales_total"];
				$cbEftAmt = $closeLine[0]["cb_eft_total"];
				$dailyExpense = $closeLine[0]["sub_expense_total"];
				$passConfirm = $closeLine[0]["pass_confirm"];
				
				}else{
					
					$arrMissing[] = array($shopCode,$dateToCheck,"Closing");
				}
				
				
				$arrTmp[$dateToCheck] = array($staffInputBi,$confirmBiStatus,$cfBiAmt,$staffInputCo,$confirmCoStatus,$cfCoAmt,$aposCashAmt,$aposEftAmt,$aposSalesTotalDaily,$cbEftAmt,$passConfirm);
				
				$totalBankIn +=$staffInputBi;
				$totalCashOut += $staffInputCo;
				
				$totalConfirmedBankIn += $cfBiAmt;
				$totalConfirmedCashOut += $cfCoAmt;
				
				$totalAposCash +=$aposCashAmt;
				$totalAposEft += $aposEftAmt;
				
				$totalAposSales += $aposSalesTotalDaily;
				$totalCbEft += $cbEftAmt;
				
				$totalEftInput += $staffInputEft;
				$totalExpense += $dailyExpense;
				$totalPassConfirm += $passConfirm;
				

			}
			
			$arrCaList[$shopCode] = array(
					
				"detail" => $arrTmp,
				"bankin_total" => $totalBankIn,
				"cashout_total" => $totalCashOut,
				"eft_total" => $totalEftInput,	
				"confirmed_bankin" => $totalConfirmedBankIn,
				"confirmed_cashout" => $totalConfirmedCashOut,				
				"total_cb_eft" => $totalCbEft,
				"total_apos_cash" => $totalAposCash,
				"total_apos_eft" => $totalAposEft,		
				"total_apos_sales" => $totalAposSales,
				"total_expense" => $totalExpense,
				"total_passconfirm" => $totalPassConfirm			
				);
		}
		
		//var_dump($arrCaList);
		
		$this->view->arrCaList = $arrCaList;
		$this->view->arrMissing = $arrMissing;
		
		if($_POST){
			
			if($_POST["status"]!=0){
				
				$caResult = new Model_DbTable_Cashaccount_Auditresult();
				$caResult->addAuditresult($_POST["shop_code"],$_POST["date"],$_POST["status"],$_POST["comment"],$_POST["staff_name"],Model_DatetimeHelper::dateToday()." ".Model_DatetimeHelper::timeNow());
				
			}
			
			
		}		
		
	}
	
	public function confirmBcInputAction(){
	
		date_default_timezone_set("Australia/Melbourne");
		
		$shopArr = array("ADPC","BBPC","BHPC","BSPC","BSIC","CBPC","CLPC","EPPC","EPIC","KCPC","NLPC","PMPC","SLIC","WBPC","WBIC","WLIC","WGIC","WGPC","CSIC","CLIC","DCIC","PMIC","BSXP","FGIC","HPIC");
		sort($shopArr,SORT_ASC);
		
		$this->view->arrShop = $shopArr;
		$this->view->token = $this->_getParam("token");
		
		$dateBegin = Model_DatetimeHelper::getLastWeekMonday();
		
		$dateBeginAcc = $this->_getParam("date_begin");
		if($dateBeginAcc!=""){	
			$dateBegin = $dateBeginAcc;		
		}
		
		$dateEnd = Model_DatetimeHelper::dateYesterday();

		$arrDate = Model_DatetimeHelper::createDateArray($dateBegin, $dateEnd);
		
		$actDateBegin = Model_DatetimeHelper::adjustDays("add", $dateBegin,1);
		$actDateEnd =  $dateEnd;
		$this->view->arrActDate = $arrActDate = Model_DatetimeHelper::createDateArray($actDateBegin, $actDateEnd);
		
		$caOpen = new Model_DbTable_Cashaccountopening();
		$caClose = new Model_DbTable_Cashaccountclosing();
		
		$arrNotConfirmed = array();
		$arrConfirmed = array();
		$arrNotExist = array();
		
		foreach($shopArr as $shop){
			foreach($arrActDate as $date){
				$isBC = $caOpen->isBankInCashCout($shop, $date);
					if(!$isBC){
						$arrNotExist[$shop] = $shop;
					}
					
					if($isBC == 1){
						if(!$caOpen->IsBankInConfirmed($shop, $date)){
							$arrNotConfirmed[$shop] = $shop;
						}
					}
					if($isBC == 2){
						if(!$caOpen->IsCashOutConfirmed($shop, $date)){
							$arrNotConfirmed[$shop] = $shop;
						}
					}

			}
			
		}
		
		array_unique($arrNotConfirmed,SORT_STRING);
		array_unique($arrNotExist);
		
		$this->view->arrNotConfirmed = $arrNotConfirmed;
		$this->view->arrNotExist = $arrNotExist;
		
		//var_dump($arrNotConfirmed,$arrNotExist);
		
		$arrOpenDetail = array();
		$arrCloseDetail = array();
		
		if($_POST){
			// shop selection 
			if(isset($_POST['btn_save_result'])){
				
				foreach($_POST["id_open"] as $shopCa => $detailCa){
					foreach($detailCa as $dateCa => $detailLine){
						
						$biAmt = null;
						$biConfirm =null;
						$biDate = null;
						$coAmt = null;
						$coConfirm = null;
						$coDate = null;
						
						
						$idCaOpen = $detailLine;
						if($idCaOpen == ""){
						// create CA and inpu those numbers	
							$timeRecord = Model_DatetimeHelper::timeNow();
							$dayRecord = date("l",strtotime($dateCa));
							$staffPrepare = $staff1 = "System";
							$staff2 = null;
							$cashArray = array_fill(0,22,0);
							$cashArray = $caOpen->buildCashCountingArr(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
							$openBalance = 0;
							
							$cashOut = 0;
							$bankIn = 0;
							
							
							$totalCashMatch = 1;
							$remark = "System Generated, Not Staff key In";
							$shopOpenYest = 0;
							$manualClose = 0;
							$checkDvr = 0;
							$idOpen = $caOpen->addCAOpening($shopCa, $dateCa, $timeRecord, $dayRecord, $staffPrepare, $staff1, $staff2, $cashArray, $openBalance, $cashOut, $bankIn, $totalCashMatch, $remark, $shopOpenYest, $manualClose, $checkDvr);
							
							
							if(trim($_POST['cf_bi_amt'][$shopCa][$dateCa])=="-" || $_POST['cf_bi_amt'][$shopCa][$dateCa] == 0 ){

								$biAmt = null;
								$biConfirm =null;
								$biDate = null;								
								
							}else{
								$biAmt = $_POST['cf_bi_amt'][$shopCa][$dateCa];
								$biConfirm = 3;
								$biDate = Model_DatetimeHelper::dateToday();
							}
							if(trim($_POST['cf_co_amt'][$shopCa][$dateCa])=="-" || $_POST['cf_co_amt'][$shopCa][$dateCa] == 0 ){
							
								$coAmt = null;
								$coConfirm =null;
								$coDate = null;
							
							}else{
								$coAmt = $_POST['cf_co_amt'][$shopCa][$dateCa];
								$coConfirm = 3;
								$coDate = Model_DatetimeHelper::dateToday();
							}							
							
							$caOpen->updateBiCoConfirmed($idOpen, $biConfirm, $biAmt, $biDate, $coConfirm, $coAmt, $coDate,$_POST['comm_bc'][$shopCa][$dateCa]);
							
						}
						else{
							//forbank 
							
							
							
							
							//1. -
							if(trim($_POST['cf_bi_amt'][$shopCa][$dateCa])=="-" || trim($_POST['cf_bi_amt'][$shopCa][$dateCa])=="0" ){
								$biConfirm = null;
								$biAmt = null;
								$biDate = null;
							}
							//2. first time confirm with cb
							if(isset($_POST['cf_bi'][$shopCa][$dateCa]) && $_POST['cf_bi'][$shopCa][$dateCa]==1){
								
								$biConfirm = 1;
								$biAmt = $_POST['cf_bi_amt'][$shopCa][$dateCa];
								$biDate = Model_DatetimeHelper::dateToday(); 
								
								//echo "Today BI CONFIRMED";
							}
							//3. see if amount change 
							
							if(trim($_POST['cf_bi_amt'][$shopCa][$dateCa])!="-"){
								
								if($_POST['bi_input'][$shopCa][$dateCa] ==  0  && $_POST['cf_bi_amt'][$shopCa][$dateCa] != $_POST['bi_input'][$shopCa][$dateCa] ){

									$biConfirm = 3;
									$biAmt = $_POST['cf_bi_amt'][$shopCa][$dateCa];
									$biDate = Model_DatetimeHelper::dateToday();									
									
								}
								if($_POST['bi_input'][$shopCa][$dateCa] >  0  && $_POST['cf_bi_amt'][$shopCa][$dateCa] != $_POST['bi_input'][$shopCa][$dateCa] ){

									$biConfirm = 2;
									$biAmt = $_POST['cf_bi_amt'][$shopCa][$dateCa];
									$biDate = Model_DatetimeHelper::dateToday();										
								}
								
								if($_POST['bi_input'][$shopCa][$dateCa] >  0  && $_POST['cf_bi_amt'][$shopCa][$dateCa] == $_POST['bi_input'][$shopCa][$dateCa] && isset($_POST['cf_bi_hidden'][$shopCa][$dateCa]) ){
								
									$biConfirm = 1;
									$biAmt = $_POST['cf_bi_amt'][$shopCa][$dateCa];
									$biDate = Model_DatetimeHelper::dateToday();
								}
					
							}
							
							//forcashout 
							
							//1. -
							if(trim($_POST['cf_co_amt'][$shopCa][$dateCa])=="-" || trim($_POST['cf_co_amt'][$shopCa][$dateCa])=="0" ){
								$coConfirm = null;
								$coAmt = null;
								$coDate = null;
							}
							//2. first time confirm with cb
							if(isset($_POST['cf_co'][$shopCa][$dateCa]) && $_POST['cf_co'][$shopCa][$dateCa]==1){
							
								$coConfirm = 1;
								$coAmt = $_POST['cf_co_amt'][$shopCa][$dateCa];
								$coDate = Model_DatetimeHelper::dateToday();
							}
							//3. see if amount change
								
							if(trim($_POST['cf_co_amt'][$shopCa][$dateCa])!="-"){
							
								if($_POST['co_input'][$shopCa][$dateCa] ==  0  && $_POST['cf_co_amt'][$shopCa][$dateCa] != $_POST['co_input'][$shopCa][$dateCa] ){
							
									$coConfirm = 3;
									$coAmt = $_POST['cf_co_amt'][$shopCa][$dateCa];
									$coDate = Model_DatetimeHelper::dateToday();
										
								}
								if($_POST['co_input'][$shopCa][$dateCa] >  0  && $_POST['cf_co_amt'][$shopCa][$dateCa] != $_POST['co_input'][$shopCa][$dateCa] ){
							
									$coConfirm = 2;
									$coAmt = $_POST['cf_co_amt'][$shopCa][$dateCa];
									$coDate = Model_DatetimeHelper::dateToday();
								}
								
								if($_POST['co_input'][$shopCa][$dateCa] >  0  && $_POST['cf_co_amt'][$shopCa][$dateCa] == $_POST['co_input'][$shopCa][$dateCa]  && isset($_POST['cf_co_hidden'][$shopCa][$dateCa]) ){
										
									$coConfirm = 1;
									$coAmt = $_POST['cf_co_amt'][$shopCa][$dateCa];
									$coDate = Model_DatetimeHelper::dateToday();
								}
						
							
							
							}
							
						//var_dump($idCaOpen, $biConfirm, $biAmt, $biDate, $coConfirm, $coAmt, $coDate);	
						
						//var_dump($_POST);
				
						
						
						
							
						$caOpen->updateBiCoConfirmed($idCaOpen, $biConfirm, $biAmt, $biDate, $coConfirm, $coAmt, $coDate,$_POST['comm_bc'][$shopCa][$dateCa]);
						//$caOpen->updateBiCoConfirmed($idCaOpen, null,null,null,null,null,null);
						}
						
						$idCaClose = $_POST["id_close"][$shopCa][$dateCa];

						if($idCaClose == ""){
							//there is no closeing crate closeing 
							
							
						}
						else{
							if(isset($_POST['cf_exp'][$shopCa][$dateCa]) && $_POST['cf_exp'][$shopCa][$dateCa]==1 ){
								$expConfirm = 1;
								$caClose->updateExpense($idCaClose, $expConfirm, $_POST['cf_exp_amt'][$shopCa][$dateCa], Model_DatetimeHelper::dateToday());
							}
							else{
								if($_POST['cf_exp_amt'][$shopCa][$dateCa] != $_POST['exp_input'][$shopCa][$dateCa] ){
								$expConfirm = 2;
								$caClose->updateExpense($idCaClose, $expConfirm, $_POST['cf_exp_amt'][$shopCa][$dateCa], Model_DatetimeHelper::dateToday());
								}
								//$caClose->updateExpense($idCaClose, null,null,null);
								
							}
								
							
						}
					}	
				}
				echo '<script language="javascript">alert("Record Added");</script>';
			
			}
			/*
			if(isset($_POST['btn_save_result'])){
				
				//var_dump($_POST);
				if(!empty($_POST['bc_cf'])){
				foreach($_POST['bc_cf'] as $key =>$v){
					
					$idOpen = $key;
					$isConfirmed = $v;
					$isBankIn = $_POST['is_bi'][$idOpen];
					$amount = 0;
					if($isConfirmed == 1){
						$amount =  $_POST['confirmed_amt'][$idOpen];
					}
					if($isConfirmed == 2){
						$amount =  $_POST['edit_amt'][$idOpen];
					}

					$caOpen->updateBcConfirmed($idOpen, $isBankIn, $isConfirmed, $amount);
						
				}
				}
				//$caOpen->addCAOpening($shopHead, $dateRecord, $timeRecord, $dayRecord, $staffPrepare, $staff1, $staff2, $cashArray, $openBalance, $cashOut, $bankIn, $totalCashMatch, $remark, $shopOpenYest, $manualClose, $checkDvr)
				if(!empty($_POST['edit_amt_bi'])){
				foreach($_POST['edit_amt_bi'] as $bKey => $bv){
					
					if($bv!=""){
						
						$arrCa = explode("|",$bKey);
						$shopHead = $arrCa[2];
						$dateRecord = $arrCa[1];
						$timeRecord = Model_DatetimeHelper::timeNow();
						$dayRecord = date("l",strtotime($dateRecord));
						$staffPrepare = $staff1 = $arrCa[0];
						$staff2 = null;
						$cashArray = array_fill(0,22,0);
						$cashArray = $caOpen->buildCashCountingArr(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
						$openBalance = 0;
						$cashOut = 0;
						$bankIn = $bv;
						$totalCashMatch = 1;
						$remark = "System Generated, Not Staff key In";
						$shopOpenYest = 0;
						$manualClose = 0;
						$checkDvr = 0;
						$idOpen = $caOpen->addCAOpening($shopHead, $dateRecord, $timeRecord, $dayRecord, $staffPrepare, $staff1, $staff2, $cashArray, $openBalance, $cashOut, $bankIn, $totalCashMatch, $remark, $shopOpenYest, $manualClose, $checkDvr);
						$caOpen->updateBcConfirmed($idOpen,1,2, $bv);
							
					}
				}
				}
				if(!empty($_POST['edit_amt_co'])){
				foreach($_POST['edit_amt_co'] as $cKey => $cv){
					if($cv!=""){
					
						$arrCa = explode("|",$cKey);
						$shopHead = $arrCa[2];
						$dateRecord = $arrCa[1];
						$timeRecord = Model_DatetimeHelper::timeNow();
						$dayRecord = date("l",strtotime($dateRecord));
						$staffPrepare = $staff1 = $arrCa[0];
						$staff2 = null;
						$cashArray = $caOpen->buildCashCountingArr(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
						$openBalance = 0;
						$cashOut = $cv;
						$bankIn = 0;
						$totalCashMatch = 1;
						$remark = "System Generated, Not Staff key In";
						$shopOpenYest = 0;
						$manualClose = 0;
						$checkDvr = 0;
						$idOpen = $caOpen->addCAOpening($shopHead, $dateRecord, $timeRecord, $dayRecord, $staffPrepare, $staff1, $staff2, $cashArray, $openBalance, $cashOut, $bankIn, $totalCashMatch, $remark, $shopOpenYest, $manualClose, $checkDvr);
						$caOpen->updateBcConfirmed($idOpen,0,2, $cv);
							
					}					
				}
				}
				
			echo '<script language="javascript">alert("Record Added");</script>';
				
			}
			*/
			
			
			$this->view->selectedShop = $shopSelect = $_POST['shop'];
			foreach($arrActDate as $dateToCheck){
				$resLine = $caOpen->getActiveOpeningByDate($shopSelect, $dateToCheck);
				$closeLine = $caClose->getActiveClosingByDate($shopSelect,$dateToCheck);
				$arrOpenDetail[$dateToCheck] = (isset($resLine[0]))?$resLine[0]:false; 	
				$arrCloseDetail[$dateToCheck] = (isset($closeLine[0]))?$closeLine[0]:false;
			}
			
			$this->view->arrOpenDetail = $arrOpenDetail; 
			$this->view->arrCloseDetail = $arrCloseDetail;
			
			//save and redirect 
			//var_dump($arrOpenDetail);
			
			
		}
		
		
		
	}
	
	public function eftTotalAction(){
		
	
		$dateBegin = Model_DatetimeHelper::getLastWeekMonday();
		$dateEnd = Model_DatetimeHelper::getLastWeekSunday();
		//$dateBegin = '2014-03-03';
		//$dateEnd = '2014-03-09';
		$db = $this->_getParam("date_begin");
		$de = $this->_getParam("date_end");
			
		if($db!=""){
			$dateBegin = $db;
			$dateEnd = $de;
		}
		
		var_dump($dateBegin);
		$cbDetail = new Model_Commbiz_Cbizdetail();
		$caClose = new Model_DbTable_Cashaccountclosing();
		$shopArr = array("ADPC","BBPC","BHPC","BSPC","BSIC","CBPC","CLPC","EPPC","KCPC","NLPC","PMPC","SLIC","WBPC","WBIC","WLIC","WGIC","WGPC","CSIC","CLIC","DCIC","PMIC","BSXP","FGIC","HPIC");
		sort($shopArr,SORT_ASC);
		
		$arrDate = Model_DatetimeHelper::createDateArray($dateBegin, $dateEnd);
		
		foreach($arrDate as $dateToCheck){
			
			foreach($shopArr as $shopCode){
			
			//echo $dateToCheck;
			
			//$cbList = $cbDetail->listByShopByDate($shopCode, $dateToCheck);
			$eftTotalDaily = $cbDetail->getTotalSalesByShopByDate($shopCode, $dateToCheck);
			
			$caCloseRow = $caClose->getActiveClosingByDate($shopCode, $dateToCheck);
			
			if(!empty($caCloseRow)){
				
				$idCaClo = $caCloseRow[0]["id_ca_close"];
				
				$caClose->updateCommbizTotal($eftTotalDaily, $idCaClo);
				
			}
			
			
			
			
			
			//echo count($cbList);
			
			//var_dump($cbList);

			
			}
		}
		
		
	}
	
	public function listAllUnmatchAction(){
		$shopArr = array("ADPC","BBPC","BHPC","BSPC","BSIC","CBPC","CLPC","EPPC","KCPC","NLPC","PMPC","SLIC","WBPC","WBIC","WLIC","WGIC","WGPC","CSIC","CLIC","DCIC","PMIC","BSXP","FGIC","HPIC");
		sort($shopArr);
		
		$dateBegin = Model_DatetimeHelper::getLastWeekMonday();
		$getDate = $this->_getParam("date_begin");
		if($getDate!=""){
			$dateBegin = $getDate;
		}
		$dateEnd = Model_DatetimeHelper::adjustDays("add", $dateBegin, 6);
		
		$arrDate = Model_DatetimeHelper::createDateArray($dateBegin, $dateEnd);
		$caClose = new Model_DbTable_Cashaccountclosing();
		$arrRes = array();
		
		foreach($arrDate as $dateToCheck){
			
			foreach($shopArr as $shop){
				$caLine = $caClose->getActiveClosingByDate($shop, $dateToCheck);
				$arrRes[$dateToCheck][$shop] = array($caLine[0]['cb_um_inv'],$caLine[0]['cb_um_eft']);
				
			}
			
		}
		
		$this->view->arrRes = $arrRes;
		$this->view->arrDate = $arrDate;
		$this->view->arrShop = $shopArr;
		
		//var_dump($arrRes);
		
	}
	
	public function aposSalesTotalAction(){
		
		$shopArr = array("ADPC","BBPC","BHPC","BSPC","BSIC","CBPC","CLPC","EPPC","KCPC","NLPC","PMPC","SLIC","WBPC","WBIC","WLIC","WGIC","WGPC","CLIC","DCIC","PMIC","BSXP","FGIC","HPIC");
		sort($shopArr);
	$dateSelect = $this->_getParam("date");
	$token = $this->_getParam("token");
	
	$date_begin = $this->_getParam("date_begin");
	$date_end = $this->_getParam("date_end");
	$email = $this->_getParam("email");
	
	
	
	$dateMonthAgo = Model_DatetimeHelper::adjustMonths("sub", Model_DatetimeHelper::dateToday(), 1);
	
	if($dateSelect !=""){
		
		$dateMonthAgo = Model_DatetimeHelper::adjustMonths("sub",$dateSelect, 1);
	}
	$dateBegin = Model_DatetimeHelper::getFirstDayOfTheMonth($dateMonthAgo);
	$dateEnd = Model_DatetimeHelper::getLastDayOfTheMonth($dateMonthAgo);
	
	if($date_begin!="" && $date_end !=""){
		
		$dateBegin = $date_begin;
		$dateEnd = $date_end;
	}
	if($email == "yes"){
		$str = file_get_contents("http://192.168.1.126/caaudit/circle/date_begin".$dateBegin."/date_end/".$dateEnd);
	}
	
	$this->view->email = $email;
	
	$arrDate = Model_DatetimeHelper::createDateArray($dateBegin, $dateEnd);
	$caClose = new Model_DbTable_Cashaccountclosing();
	$arrRes = array();
	$arrDetails = array();
	
	foreach($shopArr as $shop){
		$total = 0;
		foreach($arrDate as $date){
			$caLine = $caClose->getActiveClosingByDate($shop, $date);
			$total += $caLine[0]['apos_sys_sales_total'];	
			
			$arrDetails[$date][$shop] = $caLine[0]['apos_sys_sales_total'];
			
		}	
		
		$arrRes[] = array($shop,$total);
	}
	
	if($token!= '1357911'){
		
		$arrRes = array();
	}
	$this->view->arrShop = $shopArr;
	$this->view->arrRes = $arrRes;
	$this->view->arrDetails = $arrDetails;
	
	$this->view->dateBegin = $dateBegin;
	$this->view->dateEnd = $dateEnd;
	}
	
	public function importBankStatementAction(){
		
		$bs = new Model_Etl_Bankstatement();
		$bankRecord = new Model_Account_Deposite_Record();
		$bankDetail = new Model_Account_Deposite_Detail();
		$caOpen = new Model_DbTable_Cashaccountopening();
		
		
		
		
		$arrRes = array();
		$arrFinal = array();
		$arrFound = array();
		$arrnotFound = array();
		$arrShops = array();
		$arrDates = array();
		
		
		
		if($_POST){
			$stId = $_POST['state_id'];
			
			$fileName = "resfile".Model_DatetimeHelper::dateToday("");
			if(move_uploaded_file($_FILES['upload_file']['tmp_name'],getcwd()."/".$fileName)){
				
				$fl =fopen(getcwd()."/".$fileName,"r");
				while(($lineDate = fgetcsv($fl))!=false){
					$arrDate = explode("/", $lineDate[0]);
					$date = $arrDate[2]."-".$arrDate[1]."-".$arrDate[0];
					$amt = floatval($lineDate[1]);
					$shopCode  = $bs->mappingShop($lineDate[2], $stId);
					if(strpos($lineDate[2],"Cash Dep Branch")!==false){
					$arrFinal[] = array($shopCode,$date,$amt,$lineDate[2]);
					}	
				}
			}
			//merge the array
			$arrCombine = array();
			foreach($arrFinal as $k0 => $v0){
				if($v0[0]!=""){
					if(isset($arrCombine[$v0[0]][$v0[1]])){
						$arrCombine[$v0[0]][$v0[1]]['amt'] += $v0[2]; 
						$arrCombine[$v0[0]][$v0[1]]['amt_detail'] .= ", $".$v0[2]; 
					}
					else{
						$arrCombine[$v0[0]][$v0[1]]['amt'] = $v0[2];
						$arrCombine[$v0[0]][$v0[1]]['string'] = $v0[3];
						$arrCombine[$v0[0]][$v0[1]]['amt_detail'] = "$".$v0[2];
					}		
				}
				else{
					$arrCombine[$v0[3]][$v0[1]]['amt'] = $v0[2];
					$arrCombine[$v0[3]][$v0[1]]['string'] = $v0[3];
					$arrCombine[$v0[0]][$v0[1]]['amt_detail'] = "$".$v0[2];
					
				}
			}
			$arrFinal = array();
			$arrDateSort = array();
			foreach($arrCombine as $k1 => $v1){
					
				foreach($v1 as $k11 => $v11){
					$shopCode = (strlen($k1)==4)?$k1:"";
					$arrFinal[] = array($shopCode,$k11,$v11['amt'],$v11['string'],$v11['amt_detail']);
				}
			}
			//echo "<pre>";
			//var_dump($arrFinal);
			//echo "</pre>";
			
			foreach($arrFinal as $k2 => $v2){
			
					$result = $bankRecord->matchRecord($v2[0],$v2[1],$v2[2]);
					if(!$result){
						
						//echo "CAN NOT FIND".$v2[0]."<br />";
						$arrnotFound[] = $v2;
						$arrShops[] = $v2[0];
						$arrDates[] = $v2[1];
					}
					else{
						$arrFound[] = $v2;
						//echo "FIND".$v2[0]."<br />";
						$id = $result['id_record'];
						$bankRecord->confirmRecord($id, $v2[2], Model_DatetimeHelper::dateToday());
						$dList = $bankDetail->listAllBy("`id_record` = ".$id);
						foreach($dList as $row){
							$bankDetail->confirmDetail($row['id_detail'], 1);
							
							// only 1,2 , no 3 (filled needed) 
							$openRow = $caOpen->getActiveOpeningByDate($row['shop_code'],$row['date_bankin']);
							$shouldBankin = $openRow[0]['amount_bank_in_open'];
							$cf = 1;
							if(abs($shouldBankin - $row['amt_bankin']) > 0.04 ){
								
								$cf = 2;
							}
							
							$caOpen->updateBiCoConfirmed($openRow[0]['id_ca_open'], $cf, $row['amt_bankin'], Model_DatetimeHelper::dateToday(), null,null,null);
							
						}
						
						//get all record and sum amount 
						//if amount is difffernct 
					}
					
			}
			
			
			//$fl = fopen(realpath( $_ENV['TMPDIR']).$_FILES['upload_file']['tmp_name'],"r");
			
			//while(($lineDate = fgetcsv($fl))!=false ){
				
			//	$arrRes[] = $lineDate;	
				
			//}
			
			//var_dump($arrRes);
			/*
			foreach($arrStr as $key => $v){
			if(strpos($v,"Cash Dep Branch")!==false){
			$arrRes[] = str_replace("\r","",$v.$arrStr[$key +1]." ".$arrStr[$key +2]." ".$arrStr[$key +3]);	
				
			}
			
			}
			foreach($arrRes as $k2 => $v2){
				
				$arrFinal[] = $bs->matchShop($v2,$stId);
					
					
			}
			*/
			array_multisort($arrDates,SORT_ASC,$arrShops,SORT_ASC,$arrnotFound);
			$this->view->arrFound = $arrFound;
			$this->view->arrNotFound = $arrnotFound;
			
			//echo "<pre>";
			//var_dump($arrFinal);
			//var_dump($arrRes);
			//echo "</pre>";
			
		}
		
	}
	
	public function unmatchReceiptAction(){
		
		$dateBegin = $this->_getParam("date_begin");
		$dateEnd = $this->_getParam("date_end");
		$arrDate = Model_DatetimeHelper::createDateArray($dateBegin, $dateEnd);
		$caClose = new Model_DbTable_Cashaccountclosing(Zend_Registry::get('db_real'));
		$shopArr = array("ADPC","BBPC","BHPC","BSPC","BSIC","CBPC","CLPC","EPPC","KCPC","NLPC","PMPC","SLIC","WBPC","WBIC","WLIC","WGIC","WGPC","CSIC","CLIC","DCIC","PMIC","BSXP","FGIC","HPIC");
		sort($shopArr,SORT_ASC);
		
		$timeZone = 0;
		
	
		
		$arrRes = array();
		// Umatch Receipt Amount , Time , Found in umatch EFT invoice ? , Time? Found in Cash Inovie ? Time 
		
		foreach($shopArr as $shopCode){
			
			$invApos = Model_Aposinit::initAposInvoice($shopCode);
			
			$timeZone = 0;
			
			if($shopCode == "ADPC" || $shopCode == "WLIC" || $shopCode == "CLPC" || $shopCode == "CLIC" ){
				$timeZone = 30;
			}
			
			
		foreach($arrDate as $dateToCheck){

			$activeClose = $caClose->getActiveClosingByDate($shopCode, $dateToCheck);
			
			// check umatcheft exist 
			if($activeClose){
				
				$arrUmEft = unserialize(base64_decode($activeClose[0]['cb_um_eft']));
				$arrUmInv = unserialize(base64_decode($activeClose[0]['cb_um_inv']));
				if(!empty($arrUmEft)){
					
					//var_dump($arrUmEft);
					var_dump($arrUmInv);
					$arrUmEft = array_chunk($arrUmEft, 2);
					foreach($arrUmEft as $umEftRow){
						if($umEftRow[1]>0){
							//where I should pay attention to 
							//1. looking into Umatch invoice , find exactly amount 
							$fInEftInv = false;
							$arrKeys = array_keys($arrUmInv,$umEftRow[1],true);
							if(!empty($arrKeys) && count($arrKeys) == 1){
								//single entry found 
								$tmpArr = array($shopCode,$dateToCheck,$activeClose[0]['staff_name_prepare_close'],$umEftRow[1],$umEftRow[0],"Single",$arrUmInv[$arrKeys[0] - 2],$arrUmInv[$arrKeys[0] - 1],0,null,null,$activeClose[0]['total_net_sales_close'],$activeClose[0]['apos_total'],$activeClose[0]['remark_close']);
								$arrRes[] = $tmpArr;	
								$fInEftInv = true;							
							}
							else{
								$keyUse = -999;
								$timeDiff = 9999;
								if(!empty($arrKeys)){
								foreach($arrKeys as $kEft){
									$timeInv = explode(":",$arrUmInv[$kEft -1]);
									$timeEft = explode(":",$umEftRow[0]);
									if(abs((int)$timeInv[0]*60 + (int)$timeInv[1] - (int)$timeEft[0]*60 - (int)$timeEft[1] + $timeZone) < $timeDiff){
										
										$timeDiff = abs((int)$timeInv[0]*60 + (int)$timeInv[1] - (int)$timeEft[0]*60 - (int)$timeEft[1] + $timeZone);
										$keyUse = $kEft;		
									}
									
									// if time diff is match using , now now 
								}
								
								$tmpArr = array($shopCode,$dateToCheck,$activeClose[0]['staff_name_prepare_close'],$umEftRow[1],$umEftRow[0],"Multi",$arrUmInv[$keyUse - 2],$arrUmInv[$keyUse - 1],0,null,null,$activeClose[0]['total_net_sales_close'],$activeClose[0]['apos_total'],$activeClose[0]['remark_close']);
								$arrRes[] = $tmpArr;
								$fInEftInv = true;
								
								$arrUmInv[$keyUse] = null;
								$arrUmInv[$keyUse - 1] = null;
								$arrUmInv[$keyUse - 2] = null;
								}
									
							}
							//1.1 looking into Cash invoice that day, within 5 mins , exactly same amount 
							$fInCashInv = false;
							//2. not now , find ummatch invoice , yesterday and tomorrow 
							if($fInEftInv == false){
								
								$arrInv = $invApos->getInvoicesByDate($dateToCheck);
								echo "<pre>";
								var_dump($arrInv);
								echo "</pre>";
								$timeDiff = 9999;
								$tmpArr = array();
								foreach($arrInv as $invRow){
									
									if($invRow["INV_NO"]=="L140120025"){
										
										echo "------------------------------";
										echo floatval($invRow["PAY_TOTAL"]) - $umEftRow[1];
										echo abs((int)$timeInv[0]*60 + (int)$timeInv[1] - (int)$timeEft[0]*60 - (int)$timeEft[1] + $timeZone);
										
									}
									if(abs(floatval($invRow["PAY_TOTAL"]) - $umEftRow[1])< 1 && $invRow["PTYPE1"] == "1" ){
										// check if time with 5 mins
										$timeInv = explode(":",$invRow["TIME"]);
										$timeEft = explode(":",$umEftRow[0]);
									
										if(abs((int)$timeInv[0]*60 + (int)$timeInv[1] - (int)$timeEft[0]*60 - (int)$timeEft[1] + $timeZone) < 15){
											if(abs((int)$timeInv[0]*60 + (int)$timeInv[1] - (int)$timeEft[0]*60 - (int)$timeEft[1] + $timeZone ) < $timeDiff )
												$timeDiff = abs((int)$timeInv[0]*60 + (int)$timeInv[1] - (int)$timeEft[0]*60 - (int)$timeEft[1] + $timeZone);
									
											$tmpArr = array($shopCode,$dateToCheck,$activeClose[0]['staff_name_prepare_close'],$umEftRow[1],$umEftRow[0],"NOTHING",null,null,"MAYBE",$invRow["INV_NO"]."(".$invRow["PAY_TOTAL"].")",$invRow["TIME"],$activeClose[0]['total_net_sales_close'],$activeClose[0]['apos_total'],$activeClose[0]['remark_close']);
											$fInCashInv = true;
											

											

												
										}
									
									}
									
									if(abs(floatval($invRow["PAY_TOTAL"]) - $umEftRow[1])< 0.05 && $invRow["PTYPE1"] == "1" ){
										// check if time with 5 mins
										$timeInv = explode(":",$invRow["TIME"]);
										$timeEft = explode(":",$umEftRow[0]);
										
										if(abs((int)$timeInv[0]*60 + (int)$timeInv[1] - (int)$timeEft[0]*60 - (int)$timeEft[1] + $timeZone) < 15){
											if(abs((int)$timeInv[0]*60 + (int)$timeInv[1] - (int)$timeEft[0]*60 - (int)$timeEft[1] + $timeZone ) < $timeDiff )
												$timeDiff = abs((int)$timeInv[0]*60 + (int)$timeInv[1] - (int)$timeEft[0]*60 - (int)$timeEft[1] + $timeZone);
												
											$tmpArr = array($shopCode,$dateToCheck,$activeClose[0]['staff_name_prepare_close'],$umEftRow[1],$umEftRow[0],"NOTHING",null,null,"YES",$invRow["INV_NO"],$invRow["TIME"],$activeClose[0]['total_net_sales_close'],$activeClose[0]['apos_total'],$activeClose[0]['remark_close']);
											$fInCashInv = true;
											
												
																					
										}
										
									}
									
								}
								if(!empty($tmpArr)){
								$arrRes[] = $tmpArr;
								
								if($fInCashInv){
								$strumCash = "";
								$strumCash = $activeClose[0]['cb_um_cash_inv'].$tmpArr[9]."(".$tmpArr[10].")<br />";
								$caClose->updteUmCashInv($strumCash,$activeClose[0]['id_ca_close']);	
								}
								
								}
								
								
							}
							
							
							

							if($fInEftInv == false && $fInCashInv == false){
								
								
								$tmpArr = array($shopCode,$dateToCheck,$activeClose[0]['staff_name_prepare_close'],$umEftRow[1],$umEftRow[0],"NOTHING",null,null,"NOTHING",null,null,$activeClose[0]['total_net_sales_close'],$activeClose[0]['apos_total'],$activeClose[0]['remark_close']);
								$arrRes[] = $tmpArr;
			
								
								
							}
							
							
							
						}
					}
				}
				
			}
		}
		}
		
		var_dump($arrRes);
		$this->view->arrRes = $arrRes;
		
	}
}
?>