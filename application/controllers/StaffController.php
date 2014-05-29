<?php 
/**
Made some cHnages
 */
class StaffController extends Zend_Controller_Action
{

    public function init(){
    /**
	 *
	 *
	 */    
	
	}

    public function indexAction(){
	
		//echo "Watch Dog Controller";	

    
	}
	
	public function sendingEmailAction(){
		set_time_limit(0);
		ob_start();
		
		date_default_timezone_set("Australia/Melbourne");
		
		
		$fh = new Model_Fileshandler();
		$res = $fh->readStaffEmail("AGFILE");
		$res = array_chunk($res,6);
		$fdCot = 0;
		$mail = new Model_Emailshandler();
		
		$tCot = 0;
		
		$mailBody = "
		Dear Staff, This is your PAYG Payment Summary for Year 2012-2013.<br />
		
		The Attached Zip File is password protected, the password is your <b>Date of Birth</b> in <b>dd-mm-yyyy</b> Format<br />
		
		If your Date of Birth is 1st, January, 1988 , please input 01-01-1988. <br />
		If your Date of Birth is 18th, December, 2000 , please input 18-12-2000. <br />
				
		Best Regards
		
		";
		
		foreach($res as $key => $value){

			//var_dump($value);
			//check email 
			$email = trim($value[4]);
			//attachment 
			$lastName = trim($value[1]);
			$lastName = ucwords(strtolower($lastName));
			$firstName = trim($value[2]);
			$firstName = ucwords(strtolower($firstName));
			$year = date("Y");
			$attachName = $lastName.$firstName.$year.".pdf";
			
			$Dob = str_replace("/","-",trim($value[5]));

			if(strlen($Dob)==9){$Dob = "0".$Dob;}
						
			$fileFd = "";
			
			if(!file_exists(getcwd()."/summ/".$attachName)){
				$fileFd = "NOTFD";
			}
			else{
				$fdCot++;
				if($email!="" && $Dob !=""){	
					$afterFix = str_replace(" ","",$lastName.$firstName);
					$commend = "zip -j -P ".$Dob." '".getcwd()."/summ/zip/".$afterFix.".zip'"." '".getcwd()."/summ/".$attachName."'";
					echo system($commend);
					
					$subject = "Dear ".$lastName." ".$firstName." Your 2012-2013 PAYG Summary is Here";
					
			
					echo "will email".$email." ->".$afterFix."<br />";	
					flush();
					ob_flush();
					
					//$mail->sendAttachEmail(trim($email), $subject, $mailBody,getcwd()."/summ/zip/",$afterFix.".zip","payroll@phonecollection.com.au");
					
					sleep(2);
			
					$tCot++;
					
					rename(getcwd()."/summ/".$attachName , getcwd()."/summ/done/".$attachName);
				
				}
			}
			
			echo $email."  -> ".$attachName." -> ".$Dob."-> ".$fileFd."<br />";	
		}
		
		echo $fdCot;
		echo "Total".$tCot;
		

		
	} 
	
	public function getSalesManAction(){
		
		$shop = $this->_getParam("shop");

		$aStaff = self::initAposStaff($shop);
	
		$sStaff = new Model_DbTable_Roster_Salesmandetail(Zend_Registry::get('db_real'));		
		
		$sList = $aStaff->getAllSalesmanShop();
		
		foreach($sList as $k => $v){
			echo $v["SAL_CODE"];
			if(!($sStaff->getSalesMan(trim($v["SAL_CODE"]), $shop))){
				
				$sStaff->addSalesmandetail($shop,trim($v["SAL_CODE"]),0);			

			}
		}
		//var_dump($sList);
	}
	
	public function getSalesManCircleAction(){
		
		$arrShop = unserialize(ARR_APOS_SHOP_MAPPING);
		foreach($arrShop as $k => $v){
			file_get_contents("http://localhost/staff/get-sales-man/shop/".$k);
			echo "DONE<br />";
		}
		
	}
	
	public function matchSalesManAction(){
		
		$sStaff = new Model_DbTable_Roster_Salesmandetail(Zend_Registry::get('db_real'));
		
		if($_POST){
				
			foreach ($_POST['id'] as $k => $v){
		
				if($v!=""){
					$sStaff->updateSalesId($k, $v);
				}
			}
		
		}
		
		$rows = $sStaff->unmatchSalesMan();
		
		$this->view->arrRes = $rows;
		

	}
	
	public static function initAposStaff($shop){
		
		$aStaff = "";
		
		if($shop == "CBPC"){
			$aStaff = new Model_DbTable_Apos_Sales_Cb(Zend_Registry::get('db_apos'));
		}
		if($shop == "BSXP"){
			$aStaff = new Model_DbTable_Apos_Sales_Bsxp(Zend_Registry::get('db_apos'));
		}
		if($shop == "CLIC"){
			$aStaff = new Model_DbTable_Apos_Sales_Clic(Zend_Registry::get('db_apos'));
		}
		if($shop == "CSIC"){
			$aStaff = new Model_DbTable_Apos_Sales_Csic(Zend_Registry::get('db_apos'));
		}
		if($shop == "DCIC"){
			$aStaff = new Model_DbTable_Apos_Sales_Dcic(Zend_Registry::get('db_apos'));
		}
		if($shop == "FGIC"){
			$aStaff = new Model_DbTable_Apos_Sales_Fgic(Zend_Registry::get('db_apos'));
		}
		if($shop == "HPIC"){
			$aStaff = new Model_DbTable_Apos_Sales_Hpic(Zend_Registry::get('db_apos'));
		}
		if($shop == "PMIC"){
			$aStaff = new Model_DbTable_Apos_Sales_Pmic(Zend_Registry::get('db_apos'));
		}
		if($shop=="WBPC"){
			$aStaff = new Model_DbTable_Apos_Sales_Wb(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="BHPC"){
			$aStaff = new Model_DbTable_Apos_Sales_Bh(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="NLPC"){
			$aStaff = new Model_DbTable_Apos_Sales_Nl(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="CLPC"){
			$aStaff = new Model_DbTable_Apos_Sales_Cl(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="WGPC"){
			$aStaff = new Model_DbTable_Apos_Sales_Wgpc(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="WBIC"){
			$aStaff = new Model_DbTable_Apos_Sales_Wbic(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="BBPC"){
			$aStaff = new Model_DbTable_Apos_Sales_Bb(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="EPPC"){
			$aStaff = new Model_DbTable_Apos_Sales_Ep(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="WLIC"){
			$aStaff = new Model_DbTable_Apos_Sales_Wlic(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="KCPC"){
			$aStaff = new Model_DbTable_Apos_Sales_Kc(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="PMPC"){
			$aStaff = new Model_DbTable_Apos_Sales_Pm(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="BSIC"){
			$aStaff = new Model_DbTable_Apos_Sales_Bsic(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="BSPC"){
			$aStaff = new Model_DbTable_Apos_Sales_Bs(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="SLIC"){
			$aStaff = new Model_DbTable_Apos_Sales_Slic(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="ADPC"){
			$aStaff = new Model_DbTable_Apos_Sales_Ad(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="WGIC"){
			$aStaff = new Model_DbTable_Apos_Sales_Wgic(Zend_Registry::get('db_oldapos'));
		}
		
		return $aStaff;
	}
	
	public function initAposInvoice($shop){
		
		$invOld = "";
		
		if($shop == "CBPC"){
			$invOld = new Model_DbTable_Apos_Invoice_Cb(Zend_Registry::get('db_apos'));
			$invPro = new Model_DbTable_Apos_Invoice_Products_Cb(Zend_Registry::get('db_apos'));
		}
		if($shop == "BSXP"){
			$invOld = new Model_DbTable_Apos_Invoice_Bsxp(Zend_Registry::get('db_apos'));
			$invPro = new Model_DbTable_Apos_Invoice_Products_Bsxp(Zend_Registry::get('db_apos'));
		}
		if($shop == "CLIC"){
			$invOld = new Model_DbTable_Apos_Invoice_Clic(Zend_Registry::get('db_apos'));
			$invPro = new Model_DbTable_Apos_Invoice_Products_Clic(Zend_Registry::get('db_apos'));
		}
		if($shop == "CSIC"){
			$invOld = new Model_DbTable_Apos_Invoice_Csic(Zend_Registry::get('db_apos'));
			$invPro = new Model_DbTable_Apos_Invoice_Products_Csic(Zend_Registry::get('db_apos'));
		}
		if($shop == "DCIC"){
			$invOld = new Model_DbTable_Apos_Invoice_Dcic(Zend_Registry::get('db_apos'));
			$invPro = new Model_DbTable_Apos_Invoice_Products_Dcic(Zend_Registry::get('db_apos'));
		}
		if($shop == "FGIC"){
			$invOld = new Model_DbTable_Apos_Invoice_Fgic(Zend_Registry::get('db_apos'));
			$invPro = new Model_DbTable_Apos_Invoice_Products_Fgic(Zend_Registry::get('db_apos'));
		}
		if($shop == "HPIC"){
			$invOld = new Model_DbTable_Apos_Invoice_Hpic(Zend_Registry::get('db_apos'));
			$invPro = new Model_DbTable_Apos_Invoice_Products_Hpic(Zend_Registry::get('db_apos'));
		}
		if($shop == "PMIC"){
			$invOld = new Model_DbTable_Apos_Invoice_Pmic(Zend_Registry::get('db_apos'));
			$invPro = new Model_DbTable_Apos_Invoice_Products_Pmic(Zend_Registry::get('db_apos'));
		}
		if($shop=="WBPC"){
			$invOld = new Model_DbTable_Apos_Invoice_Wb(Zend_Registry::get('db_oldapos'));
			$invPro = new Model_DbTable_Apos_Invoice_Products_Wb(Zend_Registry::get('db_oldapos'));
		}
			
		if($shop=="BHPC"){
			$invOld = new Model_DbTable_Apos_Invoice_Bh(Zend_Registry::get('db_oldapos'));
			$invPro = new Model_DbTable_Apos_Invoice_Products_Bh(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="NLPC"){
			$invOld = new Model_DbTable_Apos_Invoice_Nl(Zend_Registry::get('db_oldapos'));
			$invPro = new Model_DbTable_Apos_Invoice_Products_Nl(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="CLPC"){
			$invOld = new Model_DbTable_Apos_Invoice_Cl(Zend_Registry::get('db_oldapos'));
			$invPro = new Model_DbTable_Apos_Invoice_Products_Cl(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="WGPC"){
			$invOld = new Model_DbTable_Apos_Invoice_Wgpc(Zend_Registry::get('db_oldapos'));
			$invPro = new Model_DbTable_Apos_Invoice_Products_Wgpc(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="WBIC"){
			$invOld = new Model_DbTable_Apos_Invoice_Wbic(Zend_Registry::get('db_oldapos'));
			$invPro = new Model_DbTable_Apos_Invoice_Products_Wbic(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="BBPC"){
			$invOld = new Model_DbTable_Apos_Invoice_Bb(Zend_Registry::get('db_oldapos'));
			$invPro = new Model_DbTable_Apos_Invoice_Products_Bb(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="EPPC"){
			$invOld = new Model_DbTable_Apos_Invoice_Ep(Zend_Registry::get('db_oldapos'));
			$invPro = new Model_DbTable_Apos_Invoice_Products_Ep(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="WLIC"){
			$invOld = new Model_DbTable_Apos_Invoice_Wlic(Zend_Registry::get('db_oldapos'));
			$invPro = new Model_DbTable_Apos_Invoice_Products_Wlic(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="KCPC"){
			$invOld = new Model_DbTable_Apos_Invoice_Kc(Zend_Registry::get('db_oldapos'));
			$invPro = new Model_DbTable_Apos_Invoice_Products_Kc(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="PMPC"){
			$invOld = new Model_DbTable_Apos_Invoice_Pm(Zend_Registry::get('db_oldapos'));
			$invPro = new Model_DbTable_Apos_Invoice_Products_Pm(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="BSIC"){
			$invOld = new Model_DbTable_Apos_Invoice_Bsic(Zend_Registry::get('db_oldapos'));
			$invPro = new Model_DbTable_Apos_Invoice_Products_Bsic(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="BSPC"){
			$invOld = new Model_DbTable_Apos_Invoice_Bs(Zend_Registry::get('db_oldapos'));
			$invPro = new Model_DbTable_Apos_Invoice_Products_Bs(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="SLIC"){
			$invOld = new Model_DbTable_Apos_Invoice_Slic(Zend_Registry::get('db_oldapos'));
			$invPro = new Model_DbTable_Apos_Invoice_Products_Slic(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="ADPC"){
			$invOld = new Model_DbTable_Apos_Invoice_Ad(Zend_Registry::get('db_oldapos'));
			$invPro = new Model_DbTable_Apos_Invoice_Products_Ad(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="WGIC"){
			$invOld = new Model_DbTable_Apos_Invoice_Wgic(Zend_Registry::get('db_oldapos'));
			$invPro = new Model_DbTable_Apos_Invoice_Products_Wgic(Zend_Registry::get('db_oldapos'));
		}
		
		return $invOld;
	}
	
	public function calStaffPerformanceAction(){
		
		if($_POST){
		
			$this->_redirect("/staff/cal-staff-performance/shop/".$_POST["shop"]."/date_begin/".$_POST["date_begin"]."/date_end/".$_POST["date_end"]);	
		}
		
		$shop = $this->_getParam("shop");
		$dateBegin =  $this->_getParam("date_begin");
		$dateEnd =  $this->_getParam("date_end");
		
		$intDateBegin = Model_DatetimeHelper::tranferToInt($dateBegin);
		$intDateEnd = Model_DatetimeHelper::tranferToInt($dateEnd);
		
		$this->view->shop = $shop;
		$this->view->dateBegin = $dateBegin;
		$this->view->dateEnd = $dateEnd;
		
		$invApos = self::initAposInvoice($shop);
		
		$ts = new Model_DbTable_Roster_Timesheet(Zend_Registry::get('db_real'));
		$sales = new Model_DbTable_Roster_Salesmandetail(Zend_Registry::get('db_real'));
		
		
		$salesList = $invApos->getSaleManSales($dateBegin,$dateEnd);
		$qtyList = $invApos->getSaleManQty($dateBegin, $dateEnd);
		//var_dump($salesList);
		$arrRes = array();
		$aveArr = array();
		$arrRes2 = array();
		$arrSph = array();
		
		foreach($salesList as $k => $v){
			
			$totalHour = 0;
			$idRow = $sales->getSalesMan($v["SAL_CODE"],$shop);
			$idStaff = $idRow["id_staff"];
			$units = 0;
			$trans = 0;
			
			$sph = 0; // sales pre hour
			$tph = 0; // transaction pre hour
			$uph = 0; // unit pre hour
			$upt = 0; // unit pre transaction
			$spt = 0; // sales pre transaction
			// bundle 
			//discount rate 
			//total discount rate
			
			
			if($idStaff > 0){
				//var_dump($idStaff, $intDateBegin, $intDateEnd,$shop);
			$totalHour = $ts->totalWorkingHours($idStaff, $intDateBegin, $intDateEnd,$shop);
			}
			
			if($totalHour >0){
			//echo $v["SAL_CODE"]." - \$".$v["SALES"]." - ".$totalHour." - ".round($v["SALES"]/$totalHour,2)."<br />";
			$sph = round($v["SALES"]/$totalHour,2);
			}
			
			foreach($qtyList as $k2 => $v2){
				if($v2["SAL_CODE"] == $v["SAL_CODE"]){
					
					$units = $v2["QTYS"];
					$trans = $v2["COUNTS"];
					if($totalHour >0){
						
						$uph = round($units/$totalHour,2);
						$tph = round($trans/$totalHour,2);
					}					
					
					$spt = round($v["SALES"]/$trans,2);
					$upt = round($units/$trans,2);
					
					
				}
				
			}

			$arrRes[] =  array($idStaff,$v["SAL_CODE"],$v["SALES"],$totalHour,$units,$trans);
			$aveArr[] = $v["SALES"];
			$arrRes2[] = array($idStaff,$v["SAL_CODE"],$sph,$uph,$tph,$spt,$upt);
			$arrSph[] = $sph;
		}
		array_multisort($aveArr,SORT_DESC,$arrRes);
		array_multisort($arrSph,SORT_DESC,$arrRes2);
		
		$this->view->arrRes = $arrRes;
		$this->view->arrRes2 = $arrRes2;
		
		//$this->view->upt = $qtyList;
		//var_dump($qtyList);
	}
	
	public function calStaffPerformanceAllAction(){
		
		$dateBegin =  $this->_getParam("date_begin");
		$dateEnd =  $this->_getParam("date_end");	
		
		$intDateBegin = Model_DatetimeHelper::tranferToInt($dateBegin);
		$intDateEnd = Model_DatetimeHelper::tranferToInt($dateEnd);
		
		$ts = new Model_DbTable_Roster_Timesheet(Zend_Registry::get('db_real'));
		$sales = new Model_DbTable_Roster_Salesmandetail(Zend_Registry::get('db_real'));
		
		
		$arrRes = array();
		$aveArr = array();
		$arrRes2 = array();
		$arrSph = array();
		
		$shopArr = array("ADPC","BBPC","BHPC","BSPC","BSIC","CBPC","CLPC","EPPC","KCPC","NLPC","PMPC","SLIC","WBPC","WBIC","WLIC","WGIC","WGPC","CSIC","CLIC","DCIC","PMIC","BSXP","FGIC","HPIC");

		
		foreach($shopArr as $shop){
			

			$invApos = self::initAposInvoice($shop);

			$salesList = $invApos->getSaleManSales($dateBegin,$dateEnd);
			$qtyList = $invApos->getSaleManQty($dateBegin, $dateEnd);
			
			foreach($salesList as $k => $v){
			
				$totalHour = 0;
				$idRow = $sales->getSalesMan($v["SAL_CODE"],$shop);
				$idStaff = $idRow["id_staff"];
				$units = 0;
				$trans = 0;
			
				$sph = 0; // sales pre hour
				$tph = 0; // transaction pre hour
				$uph = 0; // unit pre hour
				$upt = 0; // unit pre transaction
				$spt = 0; // sales pre transaction
				// bundle
				//discount rate
				//total discount rate
			
			
				if($idStaff > 0){
					//var_dump($idStaff, $intDateBegin, $intDateEnd,$shop);
					$totalHour = $ts->totalWorkingHours($idStaff, $intDateBegin, $intDateEnd,$shop);
				}
			
				//if($totalHour >0){
					//echo $v["SAL_CODE"]." - \$".$v["SALES"]." - ".$totalHour." - ".round($v["SALES"]/$totalHour,2)."<br />";
				//	$sph = round($v["SALES"]/$totalHour,2);
				//}
			
				foreach($qtyList as $k2 => $v2){
					if($v2["SAL_CODE"] == $v["SAL_CODE"]){
			
						$units = $v2["QTYS"];
						$trans = $v2["COUNTS"];
						if($totalHour >0){
			
							$uph = round($units/$totalHour,2);
							$tph = round($trans/$totalHour,2);
						}
			
						$spt = round($v["SALES"]/$trans,2);
						$upt = round($units/$trans,2);
			
			
					}
			
				}
			
				$arrRes[] =  array($idStaff,$v["SAL_CODE"],$v["SALES"],$totalHour,$units,$trans);
				$aveArr[] = $v["SALES"];
				//$arrRes2[] = array($idStaff,$v["SAL_CODE"],$sph,$uph,$tph,$spt,$upt);
				//$arrSph[] = $sph;
			}			
			
		}
		//echo "<pre>";		
		//var_dump ($arrRes);
		//echo "</pre>";		
		$arrIdStaff = array();
		$arrResAll = array();
		$arrTSales = array();
		
		foreach($arrRes as $line){
		//echo "<pre>";		
		//var_dump ($arrIdStaff);
		
		//echo $line[0];
		
		//echo "</pre>";	
			
			
			
			$idStaff = array_search($line[0], $arrIdStaff);
			
			if($idStaff=== false){
				//add new 
				$idStaff = $line[0];
				
				$arrIdStaff[$idStaff] = $idStaff;
				$arrTSales[$idStaff] =  $line[2];
				
				
				$arrResAll[$idStaff]['name'] = $line[1];
				if($line[0]==""){
					$arrResAll[$idStaff]['name']="-";
				}
				$arrResAll[$idStaff]['sales'] = $line[2];
				
				
				$arrResAll[$idStaff]['total_hour'] = $line[3];
				$arrResAll[$idStaff]['total_unit'] = $line[4];
				$arrResAll[$idStaff]['total_trans'] = $line[5];
			}
			else{
				
				//echo "-------".$line[0];
				//$arrResAll[$idStaff]['name'] = $line[1];
				$arrResAll[$idStaff]['sales'] += $line[2];
				$arrTSales[$idStaff] +=  $line[2];
				$arrResAll[$idStaff]['total_hour'] += $line[3];
				$arrResAll[$idStaff]['total_unit'] += $line[4];
				$arrResAll[$idStaff]['total_trans'] += $line[5];				
				
			}
			
		}
	
		//echo "<pre>";		
		//var_dump ($arrResAll);
		//echo "</pre>";
		
		
		array_multisort($arrTSales,SORT_DESC,$arrResAll);

		$arrResAll2 = array();
		$arrSph = array();
		
		foreach($arrResAll as $k2 => $v2){
			if( $v2['total_hour'] > 0 && $v2['total_trans'] > 0 ){

				$arrResAll2[$k2]['name'] = $v2['name'];
				$arrResAll2[$k2]['sph'] = round($v2['sales']/$v2['total_hour'],2);  
				$arrResAll2[$k2]['tph'] = round($v2['total_trans']/$v2['total_hour'],2);
				$arrResAll2[$k2]['spt'] = round($v2['sales']/$v2['total_trans'],2);
				$arrResAll2[$k2]['upt'] = round($v2['total_unit']/$v2['total_trans'],2);
				$arrResAll2[$k2]['total_hour'] = $v2['total_hour'];
				$arrResAll2[$k2]['total_trans'] = $v2['total_trans'];
				
				$arrSph[$k2] = round($v2['sales']/$v2['total_hour'],2);
			}
		}
		array_multisort($arrSph,SORT_DESC,$arrResAll2);
		
		$this->view->arrResAll = $arrResAll;
		$this->view->arrResAll2 = $arrResAll2;
		
	}
	
	public function calStaffPerformanceShopsAction(){
	
		$dateBegin =  $this->_getParam("date_begin");
		$dateEnd =  $this->_getParam("date_end");
		$arrShop = $this->_getParam("shop");
	
		$intDateBegin = Model_DatetimeHelper::tranferToInt($dateBegin);
		$intDateEnd = Model_DatetimeHelper::tranferToInt($dateEnd);
	
		$ts = new Model_DbTable_Roster_Timesheet(Zend_Registry::get('db_real'));
		$sales = new Model_DbTable_Roster_Salesmandetail(Zend_Registry::get('db_real'));
	
	
		$arrRes = array();
		$aveArr = array();
		$arrRes2 = array();
		$arrSph = array();
	
		//$shopArr = array("ADPC","BBPC","BHPC","BSPC","BSIC","CBPC","CLPC","EPPC","KCPC","NLPC","PMPC","SLIC","WBPC","WBIC","WLIC","WGIC","WGPC","CSIC","CLIC","DCIC","PMIC","BSXP","FGIC","HPIC");
	
		$shopArr = unserialize(base64_decode($arrShop));
		foreach($shopArr as $shop){
				
	
			$invApos = self::initAposInvoice($shop);
	
			$salesList = $invApos->getSaleManSales($dateBegin,$dateEnd);
			$qtyList = $invApos->getSaleManQty($dateBegin, $dateEnd);
				
			foreach($salesList as $k => $v){
					
				$totalHour = 0;
				$idRow = $sales->getSalesMan($v["SAL_CODE"],$shop);
				$idStaff = $idRow["id_staff"];
				$units = 0;
				$trans = 0;
					
				$sph = 0; // sales pre hour
				$tph = 0; // transaction pre hour
				$uph = 0; // unit pre hour
				$upt = 0; // unit pre transaction
				$spt = 0; // sales pre transaction
				// bundle
				//discount rate
				//total discount rate
					
					
				if($idStaff > 0){
					//var_dump($idStaff, $intDateBegin, $intDateEnd,$shop);
					$totalHour = $ts->totalWorkingHours($idStaff, $intDateBegin, $intDateEnd,$shop);
				}
					
				//if($totalHour >0){
				//echo $v["SAL_CODE"]." - \$".$v["SALES"]." - ".$totalHour." - ".round($v["SALES"]/$totalHour,2)."<br />";
				//	$sph = round($v["SALES"]/$totalHour,2);
				//}
					
				foreach($qtyList as $k2 => $v2){
					if($v2["SAL_CODE"] == $v["SAL_CODE"]){
							
						$units = $v2["QTYS"];
						$trans = $v2["COUNTS"];
						if($totalHour >0){
								
							$uph = round($units/$totalHour,2);
							$tph = round($trans/$totalHour,2);
						}
							
						$spt = round($v["SALES"]/$trans,2);
						$upt = round($units/$trans,2);
							
							
					}
						
				}
					
				$arrRes[] =  array($idStaff,$v["SAL_CODE"],$v["SALES"],$totalHour,$units,$trans);
				$aveArr[] = $v["SALES"];
				//$arrRes2[] = array($idStaff,$v["SAL_CODE"],$sph,$uph,$tph,$spt,$upt);
				//$arrSph[] = $sph;
			}
				
		}
		//echo "<pre>";
		//var_dump ($arrRes);
		//echo "</pre>";
		$arrIdStaff = array();
		$arrResAll = array();
		$arrTSales = array();
	
		foreach($arrRes as $line){
			//echo "<pre>";
			//var_dump ($arrIdStaff);
	
			//echo $line[0];
	
			//echo "</pre>";
				
				
				
			$idStaff = array_search($line[0], $arrIdStaff);
				
			if($idStaff=== false){
				//add new
				$idStaff = $line[0];
	
				$arrIdStaff[$idStaff] = $idStaff;
				$arrTSales[$idStaff] =  $line[2];
	
	
				$arrResAll[$idStaff]['name'] = $line[1];
				if($line[0]==""){
					$arrResAll[$idStaff]['name']="-";
				}
				$arrResAll[$idStaff]['sales'] = $line[2];
	
	
				$arrResAll[$idStaff]['total_hour'] = $line[3];
				$arrResAll[$idStaff]['total_unit'] = $line[4];
				$arrResAll[$idStaff]['total_trans'] = $line[5];
			}
			else{
	
				//echo "-------".$line[0];
				//$arrResAll[$idStaff]['name'] = $line[1];
				$arrResAll[$idStaff]['sales'] += $line[2];
				$arrTSales[$idStaff] +=  $line[2];
				$arrResAll[$idStaff]['total_hour'] += $line[3];
				$arrResAll[$idStaff]['total_unit'] += $line[4];
				$arrResAll[$idStaff]['total_trans'] += $line[5];
	
			}
				
		}
	
		//echo "<pre>";
		//var_dump ($arrResAll);
		//echo "</pre>";
	
	
		array_multisort($arrTSales,SORT_DESC,$arrResAll);
	
		$arrResAll2 = array();
		$arrSph = array();
	
		foreach($arrResAll as $k2 => $v2){
			if( $v2['total_hour'] > 0 && $v2['total_trans'] > 0 ){
	
				$arrResAll2[$k2]['name'] = $v2['name'];
				$arrResAll2[$k2]['sph'] = round($v2['sales']/$v2['total_hour'],2);
				$arrResAll2[$k2]['tph'] = round($v2['total_trans']/$v2['total_hour'],2);
				$arrResAll2[$k2]['spt'] = round($v2['sales']/$v2['total_trans'],2);
				$arrResAll2[$k2]['upt'] = round($v2['total_unit']/$v2['total_trans'],2);
				$arrResAll2[$k2]['total_hour'] = $v2['total_hour'];
				$arrResAll2[$k2]['total_trans'] = $v2['total_trans'];
	
				$arrSph[$k2] = round($v2['sales']/$v2['total_hour'],2);
			}
		}
		array_multisort($arrSph,SORT_DESC,$arrResAll2);
	
		$this->view->arrResAll = $arrResAll;
		$this->view->arrResAll2 = $arrResAll2;
	
	}
	
	
	public function listStaffBonusAction(){
		
	}
	public function addBonusAction(){
		
		$idStaff = $this->_getParam("id");
		
		$periodBegin = "";
		$periodEnd = "";
		$payDate = "";
		$payType = 1;
		
		$periodBegin = $this->_getParam("date_begin");
		$periodEnd = $this->_getParam("date_end");
		$payDate = $this->_getParam("pay_date");
		$payType = $this->_getParam("pay_type");
		

			
		$this->view->periodBegin = $periodBegin;
		$this->view->periodEnd = $periodEnd;
		$this->view->payDate = $payDate;
		$this->view->payType = $payType;
		
		
		
		$bType = new Model_Payroll_Staffbonustype();
		$stInfo = new Model_DbTable_Roster_Staffinfo();
		$bos = new Model_Payroll_Staffbonus();
		
		$stRow = $stInfo->getStaffinfo($idStaff);
		$this->view->stDetail = $stRow;
		$this->view->bTypeList = $bType->listAll();
		if($_POST){
			//var_dump($_POST);
			$payDate = (trim($_POST['hold_to_date'])=='')?$_POST['pay_date']:$_POST['hold_to_date'];
			$bos->newStaffbonus($idStaff, $_POST['bonus_type'], $_POST['detail_bonus'],$_POST['amt_total'],$_POST['amt_cash'], $_POST['amt_bank'], $_POST['amt_super'], $_POST['period_begin'], $_POST['period_end'], $payDate, $_POST['pay_type'], $_POST['hold_to_date'],$_POST['hold_reason'],$_POST['shop_code']);
			echo "For Staff ID: ".$idStaff." Bonus Added Success";
		}
	}
	public function editBonusAction(){
		
	}
	public function removeBonusAction(){
		
	}
	
	public function listAllBonusAction(){
		
		$this->view->pay = $this->_getParam("pay");
		$this->view->hr = $this->_getParam("hr");
		
		$bos = new Model_Payroll_Staffbonus();
		$date = $this->_getParam("date_begin");
		
		if($_POST){
			
			if(isset($_POST['btn_release'])){
				
				if(!empty($_POST['check_release'])){
				foreach($_POST['check_release'] as $rel){
					$bos->releaseBonus($rel, $_POST['date_release'][$rel],$_POST['release_comment'][$rel]);
				}
				}
				if(!empty($_POST['check_cancel'])){
				foreach($_POST['check_cancel'] as $k => $v){
					$bos->cancelBonus($k,$_POST['release_comment'][$k]);
				}
				}
			}elseif(isset($_POST['cancel_bt'])){
				if(!empty($_POST['check_cancel'])){
					foreach($_POST['check_cancel'] as $k => $v){
					$bos->cancelBonus($k,$_POST['cancel_reason'][$k]);
					}
				}
			}elseif(isset($_POST['save_bt'])){
				if(!empty($_POST['check_delay'])){
						foreach($_POST['check_delay'] as $rev){
							$bos->releaseBonus($rev, $_POST['date_delay'][$rev],"");
					}
				}
			}
						else{
				foreach($_POST['id_bonus'] as $key => $v){	
					$bos->bonusPaid($v, $_POST['paid_date']);
				}
			}
		}
		
		$this->view->bList = $bos->listBonusByPayDate($date);
		$this->view->hList = $bos->listAllHoldBonus();
		
		if($this->view->pay == "yes"){
			$this->renderScript("/staff/list-all-bonus-140407.phtml");

		}else if($this->view->hr == "yes"){
			$this->renderScript("/staff/list-all-bonus-140528.phtml");
		}
	}

	public function calStaffPerformancePublicAction(){
		
		if($_POST){
			$arrShop = base64_encode(serialize($_POST['shop_selection']));
			$dateBegin = $_POST['date_begin'];
			$dateEnd = $_POST['date_end'];
			
			$url = "http://192.168.1.126/staff/cal-staff-performance-shops/shop/".$arrShop."/date_begin/".$dateBegin."/date_end/".$dateEnd;
			echo $str = file_get_contents($url);			
		}
		
		//$shop = $this->_getParam("token");
		//$shopCode = base64_decode($shop);
		//$dateBegin = $this->_getParam("date_begin");
		//$dateEnd = $this->_getParam("date_end");

	}
	
}
?>