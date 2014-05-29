<?php 
/**

 */
class SalesmonitorController extends Zend_Controller_Action
{
	protected static $extRate = 5.7;
	protected static $extRateUs = 0.937; 
	
	protected static $rCenterMapping = array(
			"ADPC" => 17,
			"BBPC" => 18,
			"BHPC" => 4,
			"BSIC" => 1,
			"BSPC" => 1,
			"BSXP" => 1,
			"CBPC" => 6,
			"CLIC" => 19,
			"CLPC" => 19,
			"CSIC" => 2,
			"DCIC" => 11,
			"EPPC" => 8,
			"FGIC" => 14,
			"HPIC" => 5,
			"KCPC" => 12,
			"NLPC" => 10,
			"PMIC" => 15,
			"PMPC" => 99,
			"SLIC" => 13,
			"WBIC" => 9,
			"WBPC" => 9,
			"WGIC" => 16,
			"WGPC" => 16,
			"WLIC" => 7,
			"WHLU" => 20
	);
	
	
	protected static $targetCycle = array(
			'0' => array('2013-12-30','2014-02-02'),
			'1' => array('2014-02-03','2014-03-02'),
			'2' => array('2014-03-03','2014-03-30'),
			'3' => array('2014-03-31','2014-04-27'),
			'4' => array('2014-04-28','2014-06-01'),
			'5' => array('2014-06-02','2014-06-29'),
			'6' => array('2014-06-30','2014-08-03'),
			'7' => array('2014-08-04','2014-08-31'),
			'8' => array('2014-09-01','2014-10-05'),
			'9' => array('2014-10-06','2014-11-02'),
			'10' => array('2014-11-03','2014-11-30'),
			'11' => array('2014-12-01','2015-01-04')
			);
	
    public function init(){
    /**
	 *
	 *
	 */
    	  
	}
		
	public function getLastWeekBarCodeAction(){
		$shopCode = $this->_getParam("shop");
		
		$dateBegin = Model_DatetimeHelper::getLastWeekMonday();
		$dateEnd = Model_DatetimeHelper::getThisWeekMonday();
		
		$arrDate = Model_DatetimeHelper::createDateArray($dateBegin, $dateEnd);
		
		
	}
	
	public function dailyStockTakeListAction(){
		
		$shopCode = $this->_getParam("shop");
		$dateToCheck = ($this->_getParam("date")=="")?Model_DatetimeHelper::dateToday():$this->_getParam("date");
		$arrBarcode = array();
		if($shopCode !=""){
			
			$stockTake = new Model_DbTable_Products_Stock_Stocktakelocal();
			
				$arrStock = $stockTake->listStocktake($dateToCheck, $shopCode);
				
				foreach($arrStock as $key => $v){
					$arrBarcode[$key] = $v["barcode"];
				}
				
				array_multisort($arrBarcode,SORT_ASC,$arrStock);
			
		if($_POST){
			
				foreach($_POST["qty_act"] as $key => $v){
					$qact = $v;
					if($v==""){
						$qact = null;	
					}
					
					$stockTake->updateActStock($key, $qact,$_POST["staff_name"]);
				}
			
			}
		}
		
		
		$this->view->shopCode = $shopCode;
		$this->view->dateToCheck = $dateToCheck;
		//var_dump($arrStock);
		$this->view->arrStock = $arrStock;

		
		
	}
	
	public function showWeeklyStockTakeListAction(){

		
		$shopCode = $this->_getParam("shop");
		$dateToCheck = ($this->_getParam("date")=="")?Model_DatetimeHelper::dateToday():$this->_getParam("date");
		
		if($shopCode !=""){
				
			$stockTakeWeeklyLocal = new Model_DbTable_Products_Stock_Stocktakeweeklylocal();
				
			$arrStock = $stockTakeWeeklyLocal->listStocktake($dateToCheck, $shopCode);
			
			foreach($arrStock as $key => $v){
				$arrBarcode[$key] = $v["barcode"];
			}
			
			array_multisort($arrBarcode,SORT_ASC,$arrStock);
				
			
				
			if($_POST){
					

					
			}
		}
		
		
		$this->view->shopCode = $shopCode;
		$this->view->dateToCheck = $dateToCheck;
		//var_dump($arrStock);
		$this->view->arrStock = $arrStock;		
		
	}
	
	
	public function weeklyStockTakeListAction(){
		
		$shopCode = $this->_getParam("shop");
		
		$arrShopMaping = array(
				"ADPC" =>"AD",
				"BBPC" =>"BB",
				"BHPC" =>"BH",
				"BSPC" =>"BS",
				"BSIC" =>"BSIC",
				"BSXP" =>"BSXP",
				"CBPC" =>"CB",
				"CLPC" =>"CL",
				"CLIC" =>"CLIC",
				"CSIC" =>"CSIC",
				"DCIC" =>"DCIC",
				"EPPC" =>"EP",
				"FGIC" =>"FGIC",
				"HPIC" =>"HPIC",
				"KCPC" =>"KC",
				"NLPC" =>"NL",
				"PMPC" =>"PM",
				"PMIC" =>"PMIC",
				"SLIC" =>"SLIC",
				"WBPC" =>"WB",
				"WBIC" =>"WBIC",
				"WGPC" =>"WGPC",
				"WGIC" =>"WGIC",
				"WLIC" =>"WLIC",
				);
		
		$fl = fopen("highvalue.csv","r");
		$strHigh = fread($fl,filesize("highvalue.csv"));
		$arrHighTmp = explode("\n",$strHigh);
		array_map("trim",$arrHighTmp);
		$arrHigh = array_merge($arrHighTmp,$arrHighTmp);
		echo $countArrHight = count($arrHigh);
		
		
		$dateToCheck = ($this->_getParam("date")=="")?Model_DatetimeHelper::dateToday():$this->_getParam("date");
		
		$dateBegin = Model_DatetimeHelper::adjustDays("sub", $dateToCheck, 6);
		$arrDate = Model_DatetimeHelper::createDateArray($dateBegin, $dateToCheck);
		
		$proSkOld =  new Model_DbTable_Apos_Stock_Balanceold(Zend_Registry::get('db_oldapos'));
		$proSkNew =  new Model_DbTable_Apos_Stock_Balancenew(Zend_Registry::get('db_apos'));
		
		$proSk = "";
		if($shopCode == "BSXP" || $shopCode == "CBPC" || $shopCode == "CLIC" || $shopCode == "CSIC" || $shopCode == "DCIC" || $shopCode == "FGIC" || $shopCode == "HPIC" || $shopCode == "PMIC" ){
		$proSk = $proSkNew;	
		}
		else{
		$proSk = $proSkOld;
		}
		
		
		
		$arrFinal = array();
		
		if($shopCode !=""){
			
			$stockTake = new Model_DbTable_Products_Stock_Stocktake(Zend_Registry::get('db_real'));
			foreach ($arrDate as $date){
				
				$arrStock = $stockTake->listStocktake($date, $shopCode);
				//var_dump($arrStock);
				if(!empty($arrStock) ){
				foreach($arrStock as $key => $v){
					$pos = array_search($v["barcode"],$arrFinal,true);
					if($pos === false){
						$arrFinal[]= $v["barcode"];
						$arrFinal[]= $v["sales_yest"];
						$arrFinal[]= $v["qty_sys"];
					}
					else{
						$arrFinal[$pos + 1] += $v["sales_yest"];
						$arrFinal[$pos + 2] = $v["qty_sys"];	
					}
					
				}
				}
				
				
			}
				
			echo $qtyArr = count($arrFinal)/3;
			$qtyLeft = 100 - $qtyArr;
			$keyStep = mt_rand(1, 9);
			$keyStart = mt_rand(0,30);
			
			while($qtyLeft>0 && $keyStart <$countArrHight){
				
				$result = array_search($arrHigh[$keyStart],$arrFinal,true);

				if($result === false){
					$arrFinal[] = $arrHigh[$keyStart];	
					$arrFinal[] = 0;
					//stock
					$skLine = $proSk->getStockBalance(trim($arrHigh[$keyStart]),$arrShopMaping[$shopCode]);
					if($skLine!==false){
						$arrFinal[] = (int)trim($skLine["BALANCE"]);
					}
					else{
						$arrFinal[] ="N/A";
					}
					$qtyLeft--;
					
				}
				
				$keyStart += $keyStep;
			}
		}
		
		$stockTakeWeekly = new Model_DbTable_Products_Stock_Stocktakeweekly(Zend_Registry::get('db_real'));
		$result  = array_chunk($arrFinal,3);
		
		foreach($result as $k2 => $v){
			$stockTakeWeekly->addStocktakeweekly($v[0], $v[1], $v[2], $dateToCheck,$shopCode);			
		}
		
	}
	
	
	public function genSalesOldAposAction(){
		
		$dateToCheck = Model_DatetimeHelper::dateYesterday();
		$proSkOld =  new Model_DbTable_Apos_Stock_Balanceold(Zend_Registry::get('db_oldapos'));
		$stockTake = new Model_DbTable_Products_Stock_Stocktake(Zend_Registry::get('db_real'));

		$invWb = new Model_DbTable_Apos_Invoice_Wb(Zend_Registry::get('db_oldapos'));
		$proWb = new Model_DbTable_Apos_Invoice_Products_Wb(Zend_Registry::get('db_oldapos'));
		
		$invBh = new Model_DbTable_Apos_Invoice_Bh(Zend_Registry::get('db_oldapos'));
		$proBh = new Model_DbTable_Apos_Invoice_Products_Bh(Zend_Registry::get('db_oldapos'));
		
		$invNl = new Model_DbTable_Apos_Invoice_Nl(Zend_Registry::get('db_oldapos'));
		$proNl = new Model_DbTable_Apos_Invoice_Products_Nl(Zend_Registry::get('db_oldapos'));
		
		$invCl = new Model_DbTable_Apos_Invoice_Cl(Zend_Registry::get('db_oldapos'));
		$proCl = new Model_DbTable_Apos_Invoice_Products_Cl(Zend_Registry::get('db_oldapos'));
		
		$invWgpc = new Model_DbTable_Apos_Invoice_Wgpc(Zend_Registry::get('db_oldapos'));
		$proWgpc = new Model_DbTable_Apos_Invoice_Products_Wgpc(Zend_Registry::get('db_oldapos'));
		
		$invWbic = new Model_DbTable_Apos_Invoice_Wbic(Zend_Registry::get('db_oldapos'));
		$proWbic = new Model_DbTable_Apos_Invoice_Products_Wbic(Zend_Registry::get('db_oldapos'));
		
		$invBb = new Model_DbTable_Apos_Invoice_Bb(Zend_Registry::get('db_oldapos'));
		$proBb = new Model_DbTable_Apos_Invoice_Products_Bb(Zend_Registry::get('db_oldapos'));
		
		$invEp = new Model_DbTable_Apos_Invoice_Ep(Zend_Registry::get('db_oldapos'));
		$proEp = new Model_DbTable_Apos_Invoice_Products_Ep(Zend_Registry::get('db_oldapos'));
		
		$invWlic = new Model_DbTable_Apos_Invoice_Wlic(Zend_Registry::get('db_oldapos'));
		$proWlic = new Model_DbTable_Apos_Invoice_Products_Wlic(Zend_Registry::get('db_oldapos'));
		
		$invKc = new Model_DbTable_Apos_Invoice_Kc(Zend_Registry::get('db_oldapos'));
		$proKc = new Model_DbTable_Apos_Invoice_Products_Kc(Zend_Registry::get('db_oldapos'));
		
		$invPm = new Model_DbTable_Apos_Invoice_Pm(Zend_Registry::get('db_oldapos'));
		$proPm = new Model_DbTable_Apos_Invoice_Products_Pm(Zend_Registry::get('db_oldapos'));
		
		$invBsic = new Model_DbTable_Apos_Invoice_Bsic(Zend_Registry::get('db_oldapos'));
		$proBsic = new Model_DbTable_Apos_Invoice_Products_Bsic(Zend_Registry::get('db_oldapos'));
		
		$invBs = new Model_DbTable_Apos_Invoice_Bs(Zend_Registry::get('db_oldapos'));
		$proBs = new Model_DbTable_Apos_Invoice_Products_Bs(Zend_Registry::get('db_oldapos'));
		
		$invSlic = new Model_DbTable_Apos_Invoice_Slic(Zend_Registry::get('db_oldapos'));
		$proSlic = new Model_DbTable_Apos_Invoice_Products_Slic(Zend_Registry::get('db_oldapos'));
		
		$invAd = new Model_DbTable_Apos_Invoice_Ad(Zend_Registry::get('db_oldapos'));
		$proAd = new Model_DbTable_Apos_Invoice_Products_Ad(Zend_Registry::get('db_oldapos'));
		
		$invWgic = new Model_DbTable_Apos_Invoice_Wgic(Zend_Registry::get('db_oldapos'));
		$proWgic = new Model_DbTable_Apos_Invoice_Products_Wgic(Zend_Registry::get('db_oldapos'));
		
		//
		
		$rowsWb = $invWb->getInvoicesByDate($dateToCheck);
		$rowsBh = $invBh->getInvoicesByDate($dateToCheck);
		$rowsNl = $invNl->getInvoicesByDate($dateToCheck);
		$rowsCl = $invCl->getInvoicesByDate($dateToCheck);
		$rowsWgpc = $invWgpc->getInvoicesByDate($dateToCheck);
		$rowsWbic = $invWbic->getInvoicesByDate($dateToCheck);
		$rowsBb = $invBb->getInvoicesByDate($dateToCheck);
		$rowsEp = $invEp->getInvoicesByDate($dateToCheck);
		$rowsWlic = $invWlic->getInvoicesByDate($dateToCheck);
		$rowsKc = $invKc->getInvoicesByDate($dateToCheck);
		$rowsPm = $invPm->getInvoicesByDate($dateToCheck);
		$rowsBsic = $invBsic->getInvoicesByDate($dateToCheck);
		$rowsBs = $invBs->getInvoicesByDate($dateToCheck);
		$rowsSlic = $invSlic->getInvoicesByDate($dateToCheck);
		$rowsAd = $invAd->getInvoicesByDate($dateToCheck);
		$rowsWgic = $invWgic->getInvoicesByDate($dateToCheck);
		
		
		//WB
		
		$arrResult = array();
		
		foreach($rowsWb as  $vaWb){
		
			$invNo = $vaWb["INV_NO"];
		
			$prList = $proWb->getInvoiceProducts($invNo);
		
			foreach($prList as $k2 => $v){
		
		
				$key = array_search(trim($v["SCODE"]),$arrResult,true);
				if($key===false){
					$arrResult[] = trim($v["SCODE"]);
					$arrResult[] = (int)trim($v["QTY"]);
		
					$skLine = $proSkOld->getStockBalance(trim($v["SCODE"]), 'WB');
					if($skLine!==false){
						$arrResult[] = (int)trim($skLine["BALANCE"]);
					}
					else{
						$arrResult[] ="N/A";
					}
				}
				else{
					$arrResult[$key + 1] += (int)trim($v["QTY"]);
				}
					
			}
		
		}
		
		$arrFinal = array_chunk($arrResult,3);
		
		$cot = 0;
		
		foreach($arrFinal as $k2 => $v2){
			if($v2[0]!="INSTALLATION" && $v2[0]!="PHONEREPAIR" && $v2[0]!="CN" && $v2[0]!="SERVICE" && $v2[0]!="INSTALL" && $v2[0]!="PARTSSALES"   ){
				if($cot <41){
				$stockTake->addStocktake($v2[0],$v2[1],$v2[2],null,Model_DatetimeHelper::dateToday(),null,'WBPC',null);
				$cot++;
				}
				
			}
		}
		//BH
		
		$arrResult = array();
		
		foreach($rowsBh as  $vaBh){
		
			$invNo = $vaBh["INV_NO"];
		
			$prList = $proBh->getInvoiceProducts($invNo);
		
			foreach($prList as $k2 => $v){
		
		
				$key = array_search(trim($v["SCODE"]),$arrResult,true);
				if($key===false){
					$arrResult[] = trim($v["SCODE"]);
					$arrResult[] = (int)trim($v["QTY"]);
		
					$skLine = $proSkOld->getStockBalance(trim($v["SCODE"]), 'BH');
					if($skLine!==false){
						$arrResult[] = (int)trim($skLine["BALANCE"]);
					}
					else{
						$arrResult[] ="N/A";
					}
				}
				else{
					$arrResult[$key + 1] += (int)trim($v["QTY"]);
				}
					
			}
		
		}
		
		$arrFinal = array_chunk($arrResult,3);
		
		$cot = 0;
		
		foreach($arrFinal as $k2 => $v2){
			if($v2[0]!="INSTALLATION" && $v2[0]!="PHONEREPAIR" && $v2[0]!="CN" && $v2[0]!="SERVICE" && $v2[0]!="INSTALL" && $v2[0]!="PARTSSALES"   ){
				if($cot <41){
				$stockTake->addStocktake($v2[0],$v2[1],$v2[2],null,Model_DatetimeHelper::dateToday(),null,'BHPC',null);
				$cot++;
				}
				
			}
		}		
		//NL
		
		$arrResult = array();
		
		foreach($rowsNl as  $vaNl){
		
			$invNo = $vaNl["INV_NO"];
		
			$prList = $proNl->getInvoiceProducts($invNo);
		
			foreach($prList as $k2 => $v){
		
		
				$key = array_search(trim($v["SCODE"]),$arrResult,true);
				if($key===false){
					$arrResult[] = trim($v["SCODE"]);
					$arrResult[] = (int)trim($v["QTY"]);
		
					$skLine = $proSkOld->getStockBalance(trim($v["SCODE"]), 'NL');
					if($skLine!==false){
						$arrResult[] = (int)trim($skLine["BALANCE"]);
					}
					else{
						$arrResult[] ="N/A";
					}
				}
				else{
					$arrResult[$key + 1] += (int)trim($v["QTY"]);
				}
					
			}
		
		}
		
		$arrFinal = array_chunk($arrResult,3);
		
		foreach($arrFinal as $k2 => $v2){
			if($v2[0]!="INSTALLATION" && $v2[0]!="PHONEREPAIR" && $v2[0]!="CN" && $v2[0]!="SERVICE"  ){
			$stockTake->addStocktake($v2[0],$v2[1],$v2[2],null,Model_DatetimeHelper::dateToday(),null,'NLPC',null);
			}
			}
		//CL
		
		$arrResult = array();
		
		foreach($rowsCl as  $vaCl){
		
			$invNo = $vaCl["INV_NO"];
		
			$prList = $proCl->getInvoiceProducts($invNo);
		
			foreach($prList as $k2 => $v){
		
		
				$key = array_search(trim($v["SCODE"]),$arrResult,true);
				if($key===false){
					$arrResult[] = trim($v["SCODE"]);
					$arrResult[] = (int)trim($v["QTY"]);
		
					$skLine = $proSkOld->getStockBalance(trim($v["SCODE"]), 'CL');
					if($skLine!==false){
						$arrResult[] = (int)trim($skLine["BALANCE"]);
					}
					else{
						$arrResult[] ="N/A";
					}
				}
				else{
					$arrResult[$key + 1] += (int)trim($v["QTY"]);
				}
					
			}
		
		}
		
		$arrFinal = array_chunk($arrResult,3);
		
		$cot = 0;
		
		foreach($arrFinal as $k2 => $v2){
			if($v2[0]!="INSTALLATION" && $v2[0]!="PHONEREPAIR" && $v2[0]!="CN" && $v2[0]!="SERVICE" && $v2[0]!="INSTALL" && $v2[0]!="PARTSSALES"   ){
				if($cot <41){
				$stockTake->addStocktake($v2[0],$v2[1],$v2[2],null,Model_DatetimeHelper::dateToday(),null,'CLPC',null);
				$cot++;
				}
				
			}
		}
		//WGPC
		
		$arrResult = array();
		
		foreach($rowsWgpc as  $vaWgpc){
		
			$invNo = $vaWgpc["INV_NO"];
		
			$prList = $proWgpc->getInvoiceProducts($invNo);
		
			foreach($prList as $k2 => $v){
		
		
				$key = array_search(trim($v["SCODE"]),$arrResult,true);
				if($key===false){
					$arrResult[] = trim($v["SCODE"]);
					$arrResult[] = (int)trim($v["QTY"]);
		
					$skLine = $proSkOld->getStockBalance(trim($v["SCODE"]), 'WGPC');
					if($skLine!==false){
						$arrResult[] = (int)trim($skLine["BALANCE"]);
					}
					else{
						$arrResult[] ="N/A";
					}
				}
				else{
					$arrResult[$key + 1] += (int)trim($v["QTY"]);
				}
					
			}
		
		}
		
		$arrFinal = array_chunk($arrResult,3);
		
		$cot = 0;
		
		foreach($arrFinal as $k2 => $v2){
			if($v2[0]!="INSTALLATION" && $v2[0]!="PHONEREPAIR" && $v2[0]!="CN" && $v2[0]!="SERVICE" && $v2[0]!="INSTALL" && $v2[0]!="PARTSSALES"   ){
				if($cot <41){
				$stockTake->addStocktake($v2[0],$v2[1],$v2[2],null,Model_DatetimeHelper::dateToday(),null,'WBIC',null);
				$cot++;
				}
				
			}
		}
		//WBIC
		
		$arrResult = array();
		
		foreach($rowsWbic as  $vaWbic){
		
			$invNo = $vaWbic["INV_NO"];
		
			$prList = $proWbic->getInvoiceProducts($invNo);
		
			foreach($prList as $k2 => $v){
		
		
				$key = array_search(trim($v["SCODE"]),$arrResult,true);
				if($key===false){
					$arrResult[] = trim($v["SCODE"]);
					$arrResult[] = (int)trim($v["QTY"]);
		
					$skLine = $proSkOld->getStockBalance(trim($v["SCODE"]), 'WBIC');
					if($skLine!==false){
						$arrResult[] = (int)trim($skLine["BALANCE"]);
					}
					else{
						$arrResult[] ="N/A";
					}
				}
				else{
					$arrResult[$key + 1] += (int)trim($v["QTY"]);
				}
					
			}
		
		}
		
		$arrFinal = array_chunk($arrResult,3);
		
		$cot = 0;
		
		foreach($arrFinal as $k2 => $v2){
			if($v2[0]!="INSTALLATION" && $v2[0]!="PHONEREPAIR" && $v2[0]!="CN" && $v2[0]!="SERVICE" && $v2[0]!="INSTALL" && $v2[0]!="PARTSSALES"   ){
				if($cot <41){
				$stockTake->addStocktake($v2[0],$v2[1],$v2[2],null,Model_DatetimeHelper::dateToday(),null,'WBIC',null);
				$cot++;
				}
				
			}
		}
		//BB
		
		$arrResult = array();
		
		foreach($rowsBb as  $vaBb){
		
			$invNo = $vaBb["INV_NO"];
		
			$prList = $proBb->getInvoiceProducts($invNo);
		
			foreach($prList as $k2 => $v){
		
		
				$key = array_search(trim($v["SCODE"]),$arrResult,true);
				if($key===false){
					$arrResult[] = trim($v["SCODE"]);
					$arrResult[] = (int)trim($v["QTY"]);
		
					$skLine = $proSkOld->getStockBalance(trim($v["SCODE"]), 'BB');
					if($skLine!==false){
						$arrResult[] = (int)trim($skLine["BALANCE"]);
					}
					else{
						$arrResult[] ="N/A";
					}
				}
				else{
					$arrResult[$key + 1] += (int)trim($v["QTY"]);
				}
					
			}
		
		}
		
		$arrFinal = array_chunk($arrResult,3);
		
		$cot = 0;
		
		foreach($arrFinal as $k2 => $v2){
			if($v2[0]!="INSTALLATION" && $v2[0]!="PHONEREPAIR" && $v2[0]!="CN" && $v2[0]!="SERVICE" && $v2[0]!="INSTALL" && $v2[0]!="PARTSSALES"   ){
				if($cot <41){
				$stockTake->addStocktake($v2[0],$v2[1],$v2[2],null,Model_DatetimeHelper::dateToday(),null,'BBPC',null);
				$cot++;
				}
				
			}
		}
		//EP
		
		$arrResult = array();
		
		foreach($rowsEp as  $vaEp){
		
			$invNo = $vaEp["INV_NO"];
		
			$prList = $proEp->getInvoiceProducts($invNo);
		
			foreach($prList as $k2 => $v){
		
		
				$key = array_search(trim($v["SCODE"]),$arrResult,true);
				if($key===false){
					$arrResult[] = trim($v["SCODE"]);
					$arrResult[] = (int)trim($v["QTY"]);
		
					$skLine = $proSkOld->getStockBalance(trim($v["SCODE"]), 'EP');
					if($skLine!==false){
						$arrResult[] = (int)trim($skLine["BALANCE"]);
					}
					else{
						$arrResult[] ="N/A";
					}
				}
				else{
					$arrResult[$key + 1] += (int)trim($v["QTY"]);
				}
					
			}
		
		}
		
		$arrFinal = array_chunk($arrResult,3);
		
		$cot = 0;
		
		foreach($arrFinal as $k2 => $v2){
			if($v2[0]!="INSTALLATION" && $v2[0]!="PHONEREPAIR" && $v2[0]!="CN" && $v2[0]!="SERVICE" && $v2[0]!="INSTALL" && $v2[0]!="PARTSSALES"   ){
				if($cot <41){
				$stockTake->addStocktake($v2[0],$v2[1],$v2[2],null,Model_DatetimeHelper::dateToday(),null,'EPPC',null);
				$cot++;
				}
				
			}
		}
		//WLIC
		
		$arrResult = array();
		
		foreach($rowsWlic as  $vaWlic){
		
			$invNo = $vaWlic["INV_NO"];
		
			$prList = $proWlic->getInvoiceProducts($invNo);
		
			foreach($prList as $k2 => $v){
		
		
				$key = array_search(trim($v["SCODE"]),$arrResult,true);
				if($key===false){
					$arrResult[] = trim($v["SCODE"]);
					$arrResult[] = (int)trim($v["QTY"]);
		
					$skLine = $proSkOld->getStockBalance(trim($v["SCODE"]), 'WLIC');
					if($skLine!==false){
						$arrResult[] = (int)trim($skLine["BALANCE"]);
					}
					else{
						$arrResult[] ="N/A";
					}
				}
				else{
					$arrResult[$key + 1] += (int)trim($v["QTY"]);
				}
					
			}
		
		}
		
		$arrFinal = array_chunk($arrResult,3);
		
		$cot = 0;
		
		foreach($arrFinal as $k2 => $v2){
			if($v2[0]!="INSTALLATION" && $v2[0]!="PHONEREPAIR" && $v2[0]!="CN" && $v2[0]!="SERVICE" && $v2[0]!="INSTALL" && $v2[0]!="PARTSSALES"   ){
				if($cot <41){
				$stockTake->addStocktake($v2[0],$v2[1],$v2[2],null,Model_DatetimeHelper::dateToday(),null,'WLIC',null);
				$cot++;
				}
				
			}
		}
		//KC
		
		$arrResult = array();
		
		foreach($rowsKc as  $vaKc){
		
			$invNo = $vaKc["INV_NO"];
		
			$prList = $proKc->getInvoiceProducts($invNo);
		
			foreach($prList as $k2 => $v){
		
		
				$key = array_search(trim($v["SCODE"]),$arrResult,true);
				if($key===false){
					$arrResult[] = trim($v["SCODE"]);
					$arrResult[] = (int)trim($v["QTY"]);
		
					$skLine = $proSkOld->getStockBalance(trim($v["SCODE"]), 'KC');
					if($skLine!==false){
						$arrResult[] = (int)trim($skLine["BALANCE"]);
					}
					else{
						$arrResult[] ="N/A";
					}
				}
				else{
					$arrResult[$key + 1] += (int)trim($v["QTY"]);
				}
					
			}
		
		}
		
		$arrFinal = array_chunk($arrResult,3);
		
		$cot = 0;
		
		foreach($arrFinal as $k2 => $v2){
			if($v2[0]!="INSTALLATION" && $v2[0]!="PHONEREPAIR" && $v2[0]!="CN" && $v2[0]!="SERVICE" && $v2[0]!="INSTALL" && $v2[0]!="PARTSSALES"   ){
				if($cot <41){
				$stockTake->addStocktake($v2[0],$v2[1],$v2[2],null,Model_DatetimeHelper::dateToday(),null,'KCPC',null);
				$cot++;
				}
				
			}
		}
		//PM
		
		$arrResult = array();
		
		foreach($rowsPm as  $vaPm){
		
			$invNo = $vaPm["INV_NO"];
		
			$prList = $proPm->getInvoiceProducts($invNo);
		
			foreach($prList as $k2 => $v){
		
		
				$key = array_search(trim($v["SCODE"]),$arrResult,true);
				if($key===false){
					$arrResult[] = trim($v["SCODE"]);
					$arrResult[] = (int)trim($v["QTY"]);
		
					$skLine = $proSkOld->getStockBalance(trim($v["SCODE"]), 'PM');
					if($skLine!==false){
						$arrResult[] = (int)trim($skLine["BALANCE"]);
					}
					else{
						$arrResult[] ="N/A";
					}
				}
				else{
					$arrResult[$key + 1] += (int)trim($v["QTY"]);
				}
					
			}
		
		}
		
		$arrFinal = array_chunk($arrResult,3);
		
		$cot = 0;
		
		foreach($arrFinal as $k2 => $v2){
			if($v2[0]!="INSTALLATION" && $v2[0]!="PHONEREPAIR" && $v2[0]!="CN" && $v2[0]!="SERVICE" && $v2[0]!="INSTALL" && $v2[0]!="PARTSSALES"   ){
				if($cot <41){
				$stockTake->addStocktake($v2[0],$v2[1],$v2[2],null,Model_DatetimeHelper::dateToday(),null,'PMPC',null);
				$cot++;
				}
				
			}
		}
		
		//BSIC
		
		$arrResult = array();
		
		foreach($rowsBsic as  $vaBsic){
		
			$invNo = $vaBsic["INV_NO"];
		
			$prList = $proBsic->getInvoiceProducts($invNo);
		
			foreach($prList as $k2 => $v){
		
		
				$key = array_search(trim($v["SCODE"]),$arrResult,true);
				if($key===false){
					$arrResult[] = trim($v["SCODE"]);
					$arrResult[] = (int)trim($v["QTY"]);
		
					$skLine = $proSkOld->getStockBalance(trim($v["SCODE"]), 'BSIC');
					if($skLine!==false){
						$arrResult[] = (int)trim($skLine["BALANCE"]);
					}
					else{
						$arrResult[] ="N/A";
					}
				}
				else{
					$arrResult[$key + 1] += (int)trim($v["QTY"]);
				}
					
			}
		
		}
		
		$arrFinal = array_chunk($arrResult,3);
		
		$cot = 0;
		
		foreach($arrFinal as $k2 => $v2){
			if($v2[0]!="INSTALLATION" && $v2[0]!="PHONEREPAIR" && $v2[0]!="CN" && $v2[0]!="SERVICE" && $v2[0]!="INSTALL" && $v2[0]!="PARTSSALES"   ){
				if($cot <41){
				$stockTake->addStocktake($v2[0],$v2[1],$v2[2],null,Model_DatetimeHelper::dateToday(),null,'BSIC',null);
				$cot++;
				}
				
			}
		}
		//BS
		
		
		$arrResult = array();
		
		foreach($rowsBs as  $vaBs){
		
			$invNo = $vaBs["INV_NO"];
		
			$prList = $proBs->getInvoiceProducts($invNo);
		
			foreach($prList as $k2 => $v){
		
		
				$key = array_search(trim($v["SCODE"]),$arrResult,true);
				if($key===false){
					$arrResult[] = trim($v["SCODE"]);
					$arrResult[] = (int)trim($v["QTY"]);
		
					$skLine = $proSkOld->getStockBalance(trim($v["SCODE"]), 'BS');
					if($skLine!==false){
						$arrResult[] = (int)trim($skLine["BALANCE"]);
					}
					else{
						$arrResult[] ="N/A";
					}
				}
				else{
					$arrResult[$key + 1] += (int)trim($v["QTY"]);
				}
					
			}
		
		}
		
		$arrFinal = array_chunk($arrResult,3);
		
		$cot = 0;
		
		foreach($arrFinal as $k2 => $v2){
			if($v2[0]!="INSTALLATION" && $v2[0]!="PHONEREPAIR" && $v2[0]!="CN" && $v2[0]!="SERVICE" && $v2[0]!="INSTALL" && $v2[0]!="PARTSSALES"   ){
				if($cot <41){
				$stockTake->addStocktake($v2[0],$v2[1],$v2[2],null,Model_DatetimeHelper::dateToday(),null,'BSPC',null);
				$cot++;
				}
				
			}
		}
		//SLIC
		
		$arrResult = array();
		
		foreach($rowsSlic as  $vaSlic){
		
			$invNo = $vaSlic["INV_NO"];
		
			$prList = $proSlic->getInvoiceProducts($invNo);
		
			foreach($prList as $k2 => $v){
		
		
				$key = array_search(trim($v["SCODE"]),$arrResult,true);
				if($key===false){
					$arrResult[] = trim($v["SCODE"]);
					$arrResult[] = (int)trim($v["QTY"]);
		
					$skLine = $proSkOld->getStockBalance(trim($v["SCODE"]), 'SLIC');
					if($skLine!==false){
						$arrResult[] = (int)trim($skLine["BALANCE"]);
					}
					else{
						$arrResult[] ="N/A";
					}
				}
				else{
					$arrResult[$key + 1] += (int)trim($v["QTY"]);
				}
					
			}
		
		}
		
		$arrFinal = array_chunk($arrResult,3);
		
		$cot = 0;
		
		foreach($arrFinal as $k2 => $v2){
			if($v2[0]!="INSTALLATION" && $v2[0]!="PHONEREPAIR" && $v2[0]!="CN" && $v2[0]!="SERVICE" && $v2[0]!="INSTALL" && $v2[0]!="PARTSSALES"   ){
				if($cot <41){
				$stockTake->addStocktake($v2[0],$v2[1],$v2[2],null,Model_DatetimeHelper::dateToday(),null,'SLIC',null);
				$cot++;
				}
				
			}
		}
		//AD
		
		$arrResult = array();
		
		foreach($rowsAd as  $vaAd){
		
			$invNo = $vaAd["INV_NO"];
		
			$prList = $proAd->getInvoiceProducts($invNo);
		
			foreach($prList as $k2 => $v){
		
		
				$key = array_search(trim($v["SCODE"]),$arrResult,true);
				if($key===false){
					$arrResult[] = trim($v["SCODE"]);
					$arrResult[] = (int)trim($v["QTY"]);
		
					$skLine = $proSkOld->getStockBalance(trim($v["SCODE"]), 'AD');
					if($skLine!==false){
						$arrResult[] = (int)trim($skLine["BALANCE"]);
					}
					else{
						$arrResult[] ="N/A";
					}
				}
				else{
					$arrResult[$key + 1] += (int)trim($v["QTY"]);
				}
					
			}
		
		}
		
		$arrFinal = array_chunk($arrResult,3);
		
		$cot = 0;
		
		foreach($arrFinal as $k2 => $v2){
			if($v2[0]!="INSTALLATION" && $v2[0]!="PHONEREPAIR" && $v2[0]!="CN" && $v2[0]!="SERVICE" && $v2[0]!="INSTALL" && $v2[0]!="PARTSSALES"   ){
				if($cot <41){
				$stockTake->addStocktake($v2[0],$v2[1],$v2[2],null,Model_DatetimeHelper::dateToday(),null,'ADPC',null);
				$cot++;
				}
				
			}
		}
		//WGIC
		
		
		$arrResult = array();
		
		foreach($rowsWgic as  $vaWgic){
		
			$invNo = $vaWgic["INV_NO"];
		
			$prList = $proWgic->getInvoiceProducts($invNo);
		
			foreach($prList as $k2 => $v){
		
		
				$key = array_search(trim($v["SCODE"]),$arrResult,true);
				if($key===false){
					$arrResult[] = trim($v["SCODE"]);
					$arrResult[] = (int)trim($v["QTY"]);
		
					$skLine = $proSkOld->getStockBalance(trim($v["SCODE"]), 'WGIC');
					if($skLine!==false){
						$arrResult[] = (int)trim($skLine["BALANCE"]);
					}
					else{
						$arrResult[] ="N/A";
					}
				}
				else{
					$arrResult[$key + 1] += (int)trim($v["QTY"]);
				}
					
			}
		
		}
		
		$arrFinal = array_chunk($arrResult,3);
		
		$cot = 0;
		
		foreach($arrFinal as $k2 => $v2){
			if($v2[0]!="INSTALLATION" && $v2[0]!="PHONEREPAIR" && $v2[0]!="CN" && $v2[0]!="SERVICE" && $v2[0]!="INSTALL" && $v2[0]!="PARTSSALES"   ){
				if($cot <41){
				$stockTake->addStocktake($v2[0],$v2[1],$v2[2],null,Model_DatetimeHelper::dateToday(),null,'WGIC',null);
				$cot++;
				}
				
			}
		}
			
		
	}
	
	public function genSalesAposByDateAction(){

		$shop = $this->_getParam("shop");
		$dateToCheck = Model_DatetimeHelper::dateYesterday();
		$dateBegin = Model_DatetimeHelper::adjustDays("sub", $dateToCheck,1);
		
		$arrShopMaping = array(
				"ADPC" =>"AD",
				"BBPC" =>"BB",
				"BHPC" =>"BH",
				"BSPC" =>"BS",
				"BSIC" =>"BSIC",
				"BSXP" =>"BSXP",
				"CBPC" =>"CB",
				"CLPC" =>"CL",
				"CLIC" =>"CLIC",
				"CSIC" =>"CSIC",
				"DCIC" =>"DCIC",
				"EPPC" =>"EP",
				"FGIC" =>"FGIC",
				"HPIC" =>"HPIC",
				"KCPC" =>"KC",
				"NLPC" =>"NL",
				"PMPC" =>"PM",
				"PMIC" =>"PMIC",
				"SLIC" =>"SLIC",
				"WBPC" =>"WB",
				"WBIC" =>"WBIC",
				"WGPC" =>"WGPC",
				"WGIC" =>"WGIC",
				"WLIC" =>"WLIC",
		);
		
		
		
		//get Product Stock
		
		$proSkOld =  new Model_DbTable_Apos_Stock_Balanceold(Zend_Registry::get('db_oldapos'));
		$proSkNew =  new Model_DbTable_Apos_Stock_Balancenew(Zend_Registry::get('db_apos'));
		
		$proSk = "";
		if($shop == "BSXP" || $shop == "CBPC" || $shop == "CLIC" || $shop == "CSIC" || $shop == "DCIC" || $shop == "FGIC" || $shop == "HPIC" || $shop == "PMIC" ){
			$proSk = $proSkNew;
		}
		else{
			$proSk = $proSkOld;
		}
		
		//
		$stockTake = new Model_DbTable_Products_Stock_Stocktake(Zend_Registry::get('db_real'));
		
		//init the object
		
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
		
		$rowsDateBegin = $invOld->getInvoicesByDate($dateBegin); 
		$rowsDateCheck = $invOld->getInvoicesByDate($dateToCheck);

		$arrResult = array();
		
		foreach($rowsDateBegin as  $va){
		
			$invNo = $va["INV_NO"];
		
			$prList = $invPro->getInvoiceProducts($invNo);
		
			foreach($prList as $k2 => $v){
		
		
				$key = array_search(trim($v["SCODE"]),$arrResult,true);
				if($key===false){
					$arrResult[] = trim($v["SCODE"]);
					$arrResult[] = (int)trim($v["QTY"]);
		
					$skLine = $proSk->getStockBalance(trim($v["SCODE"]),$arrShopMaping[$shop]);
					if($skLine!==false){
						$arrResult[] = (int)trim($skLine["BALANCE"]);
					}
					else{
						$arrResult[] ="N/A";
					}
				}
				else{
					$arrResult[$key + 1] += (int)trim($v["QTY"]);
				}
					
			}
		
		}
		
		foreach($rowsDateCheck as  $vb){
		
			$invNo = $vb["INV_NO"];
		
			$prList = $invPro->getInvoiceProducts($invNo);
		
			foreach($prList as $k3 => $v){
		
		
				$key = array_search(trim($v["SCODE"]),$arrResult,true);
				if($key===false){
					$arrResult[] = trim($v["SCODE"]);
					$arrResult[] = (int)trim($v["QTY"]);
		
					$skLine = $proSk->getStockBalance(trim($v["SCODE"]),$arrShopMaping[$shop]);
					if($skLine!==false){
						$arrResult[] = (int)trim($skLine["BALANCE"]);
					}
					else{
						$arrResult[] ="N/A";
					}
				}
				else{
					$arrResult[$key + 1] += (int)trim($v["QTY"]);
				}
					
			}
		
		}		
		
		$arrFinal = array_chunk($arrResult,3);
		
		foreach($arrFinal as $k2 => $v2){
		
			$stockTake->addStocktake($v2[0],$v2[1],$v2[2],null,Model_DatetimeHelper::dateToday(),null,$shop,null);
		}		
		

		
		
	}
	
	
	public function genSalesNewAposAction(){
		
		$dateToCheck = Model_DatetimeHelper::dateYesterday();
		
		$proSkNew = new Model_DbTable_Apos_Stock_Balancenew(Zend_Registry::get('db_apos'));
		$stockTake = new Model_DbTable_Products_Stock_Stocktake(Zend_Registry::get('db_real'));		
		
		
		//init the object
		
		$invCb = new Model_DbTable_Apos_Invoice_Cb(Zend_Registry::get('db_apos'));
		$proCb = new Model_DbTable_Apos_Invoice_Products_Cb(Zend_Registry::get('db_apos'));
		
		$invBsxp = new Model_DbTable_Apos_Invoice_Bsxp(Zend_Registry::get('db_apos'));
		$proBsxp = new Model_DbTable_Apos_Invoice_Products_Bsxp(Zend_Registry::get('db_apos'));
		
		$invClic = new Model_DbTable_Apos_Invoice_Clic(Zend_Registry::get('db_apos'));
		$proClic = new Model_DbTable_Apos_Invoice_Products_Clic(Zend_Registry::get('db_apos'));
		
		$invCsic = new Model_DbTable_Apos_Invoice_Csic(Zend_Registry::get('db_apos'));
		$proCsic = new Model_DbTable_Apos_Invoice_Products_Csic(Zend_Registry::get('db_apos'));
		
		$invDcic = new Model_DbTable_Apos_Invoice_Dcic(Zend_Registry::get('db_apos'));
		$proDcic = new Model_DbTable_Apos_Invoice_Products_Dcic(Zend_Registry::get('db_apos'));
		
		$invFgic = new Model_DbTable_Apos_Invoice_Fgic(Zend_Registry::get('db_apos'));
		$proFgic = new Model_DbTable_Apos_Invoice_Products_Fgic(Zend_Registry::get('db_apos'));
		
		$invHpic = new Model_DbTable_Apos_Invoice_Hpic(Zend_Registry::get('db_apos'));
		$proHpic = new Model_DbTable_Apos_Invoice_Products_Hpic(Zend_Registry::get('db_apos'));
		
		$invPmic = new Model_DbTable_Apos_Invoice_Pmic(Zend_Registry::get('db_apos'));
		$proPmic = new Model_DbTable_Apos_Invoice_Products_Pmic(Zend_Registry::get('db_apos'));

		//
		$rowsCb = $invCb->getInvoicesByDate($dateToCheck);
		$rowsBsxp = $invBsxp->getInvoicesByDate($dateToCheck);
		$rowsClic = $invClic->getInvoicesByDate($dateToCheck);
		$rowsCsic = $invCsic->getInvoicesByDate($dateToCheck);
		$rowsDcic = $invDcic->getInvoicesByDate($dateToCheck);
		$rowsFgic = $invFgic->getInvoicesByDate($dateToCheck);
		$rowsHpic = $invHpic->getInvoicesByDate($dateToCheck);
		$rowsPmic = $invPmic->getInvoicesByDate($dateToCheck);
		
		// CB
		
		$arrResult = array();
		
		foreach($rowsCb as  $vaCb){
		
			$invNo = $vaCb["INV_NO"];
				
			$prList = $proCb->getInvoiceProducts($invNo);
				
			foreach($prList as $k2 => $v){
		
		
				$key = array_search(trim($v["SCODE"]),$arrResult,true);
				if($key===false){
					$arrResult[] = trim($v["SCODE"]);
					$arrResult[] = (int)trim($v["QTY"]);
						
					$skLine = $proSkNew->getStockBalance(trim($v["SCODE"]), 'CB');
					if($skLine!==false){
						$arrResult[] = (int)trim($skLine["BALANCE"]);
					}
					else{
						$arrResult[] ="N/A";
					}
				}
				else{
					$arrResult[$key + 1] += (int)trim($v["QTY"]);
				}
					
			}
				
		}
		
		$arrFinal = array_chunk($arrResult,3);
		
		foreach($arrFinal as $k2 => $v2){
			if($v2[0]!="INSTALLATION" && $v2[0]!="PHONEREPAIR" && $v2[0]!="CN" && $v2[0]!="SERVICE" && $v2[0]!="INSTALL" && $v2[0]!="PARTSSALES"   ){	
			$stockTake->addStocktake($v2[0],$v2[1],$v2[2],null,Model_DatetimeHelper::dateToday(),null,'CBPC',null);
			}
		}		
		
		//BSXP
		
		$arrResult = array();
		
		foreach($rowsBsxp as  $vaBsxp){
		
			$invNo = $vaBsxp["INV_NO"];
		
			$prList = $proBsxp->getInvoiceProducts($invNo);
		
			foreach($prList as $k2 => $v){
		
		
				$key = array_search(trim($v["SCODE"]),$arrResult,true);
				if($key===false){
					$arrResult[] = trim($v["SCODE"]);
					$arrResult[] = (int)trim($v["QTY"]);
		
					$skLine = $proSkNew->getStockBalance(trim($v["SCODE"]), 'BSXP');
					if($skLine!==false){
						$arrResult[] = (int)trim($skLine["BALANCE"]);
					}
					else{
						$arrResult[] ="N/A";
					}
				}
				else{
					$arrResult[$key + 1] += (int)trim($v["QTY"]);
				}
					
			}
		
		}
		
		$arrFinal = array_chunk($arrResult,3);
		
		$cot = 0;
		
		foreach($arrFinal as $k2 => $v2){
			if($v2[0]!="INSTALLATION" && $v2[0]!="PHONEREPAIR" && $v2[0]!="CN" && $v2[0]!="SERVICE" && $v2[0]!="INSTALL" && $v2[0]!="PARTSSALES"   ){
				if($cot <41){
				$stockTake->addStocktake($v2[0],$v2[1],$v2[2],null,Model_DatetimeHelper::dateToday(),null,'BSXP',null);
				$cot++;
				}
				
			}
		}		
		//CLIC
		$arrResult = array();
		
		foreach($rowsClic as  $vaClic){
		
			$invNo = $vaClic["INV_NO"];
		
			$prList = $proClic->getInvoiceProducts($invNo);
		
			foreach($prList as $k2 => $v){
		
		
				$key = array_search(trim($v["SCODE"]),$arrResult,true);
				if($key===false){
					$arrResult[] = trim($v["SCODE"]);
					$arrResult[] = (int)trim($v["QTY"]);
		
					$skLine = $proSkNew->getStockBalance(trim($v["SCODE"]), 'CLIC');
					if($skLine!==false){
						$arrResult[] = (int)trim($skLine["BALANCE"]);
					}
					else{
						$arrResult[] ="N/A";
					}
				}
				else{
					$arrResult[$key + 1] += (int)trim($v["QTY"]);
				}
					
			}
		
		}
		
		$arrFinal = array_chunk($arrResult,3);
		
	$cot = 0;
		
		foreach($arrFinal as $k2 => $v2){
			if($v2[0]!="INSTALLATION" && $v2[0]!="PHONEREPAIR" && $v2[0]!="CN" && $v2[0]!="SERVICE" && $v2[0]!="INSTALL" && $v2[0]!="PARTSSALES"   ){
				if($cot <41){
				$stockTake->addStocktake($v2[0],$v2[1],$v2[2],null,Model_DatetimeHelper::dateToday(),null,'CLIC',null);
				$cot++;
				}
				
			}
		}
		
		//CSIC
		$arrResult = array();
		
		foreach($rowsCsic as  $vaCsic){
		
			$invNo = $vaCsic["INV_NO"];
		
			$prList = $proCsic->getInvoiceProducts($invNo);
		
			foreach($prList as $k2 => $v){
		
		
				$key = array_search(trim($v["SCODE"]),$arrResult,true);
				if($key===false){
					$arrResult[] = trim($v["SCODE"]);
					$arrResult[] = (int)trim($v["QTY"]);
		
					$skLine = $proSkNew->getStockBalance(trim($v["SCODE"]), 'CSIC');
					if($skLine!==false){
						$arrResult[] = (int)trim($skLine["BALANCE"]);
					}
					else{
						$arrResult[] ="N/A";
					}
				}
				else{
					$arrResult[$key + 1] += (int)trim($v["QTY"]);
				}
					
			}
		
		}
		
		$arrFinal = array_chunk($arrResult,3);
		
	$cot = 0;
		
		foreach($arrFinal as $k2 => $v2){
			if($v2[0]!="INSTALLATION" && $v2[0]!="PHONEREPAIR" && $v2[0]!="CN" && $v2[0]!="SERVICE" && $v2[0]!="INSTALL" && $v2[0]!="PARTSSALES"   ){
				if($cot <41){
				$stockTake->addStocktake($v2[0],$v2[1],$v2[2],null,Model_DatetimeHelper::dateToday(),null,'CSIC',null);
				$cot++;
				}
				
			}
		}
		
		//DCIC
		$arrResult = array();
		
		foreach($rowsDcic as  $vaDcic){
		
			$invNo = $vaDcic["INV_NO"];
		
			$prList = $proDcic->getInvoiceProducts($invNo);
		
			foreach($prList as $k2 => $v){
		
		
				$key = array_search(trim($v["SCODE"]),$arrResult,true);
				if($key===false){
					$arrResult[] = trim($v["SCODE"]);
					$arrResult[] = (int)trim($v["QTY"]);
		
					$skLine = $proSkNew->getStockBalance(trim($v["SCODE"]), 'DCIC');
					if($skLine!==false){
						$arrResult[] = (int)trim($skLine["BALANCE"]);
					}
					else{
						$arrResult[] ="N/A";
					}
				}
				else{
					$arrResult[$key + 1] += (int)trim($v["QTY"]);
				}
					
			}
		
		}
		
		$arrFinal = array_chunk($arrResult,3);
		
	$cot = 0;
		
		foreach($arrFinal as $k2 => $v2){
			if($v2[0]!="INSTALLATION" && $v2[0]!="PHONEREPAIR" && $v2[0]!="CN" && $v2[0]!="SERVICE" && $v2[0]!="INSTALL" && $v2[0]!="PARTSSALES"   ){
				if($cot <41){
				$stockTake->addStocktake($v2[0],$v2[1],$v2[2],null,Model_DatetimeHelper::dateToday(),null,'DCIC',null);
				$cot++;
				}
				
			}
		}
		
		//FGIC
		$arrResult = array();
		
		foreach($rowsFgic as  $vaFgic){
		
			$invNo = $vaFgic["INV_NO"];
		
			$prList = $proFgic->getInvoiceProducts($invNo);
		
			foreach($prList as $k2 => $v){
		
		
				$key = array_search(trim($v["SCODE"]),$arrResult,true);
				if($key===false){
					$arrResult[] = trim($v["SCODE"]);
					$arrResult[] = (int)trim($v["QTY"]);
		
					$skLine = $proSkNew->getStockBalance(trim($v["SCODE"]), 'FGIC');
					if($skLine!==false){
						$arrResult[] = (int)trim($skLine["BALANCE"]);
					}
					else{
						$arrResult[] ="N/A";
					}
				}
				else{
					$arrResult[$key + 1] += (int)trim($v["QTY"]);
				}
					
			}
		
		}
		
		$arrFinal = array_chunk($arrResult,3);
		
	$cot = 0;
		
		foreach($arrFinal as $k2 => $v2){
			if($v2[0]!="INSTALLATION" && $v2[0]!="PHONEREPAIR" && $v2[0]!="CN" && $v2[0]!="SERVICE" && $v2[0]!="INSTALL" && $v2[0]!="PARTSSALES"   ){
				if($cot <41){
				$stockTake->addStocktake($v2[0],$v2[1],$v2[2],null,Model_DatetimeHelper::dateToday(),null,'FGIC',null);
				$cot++;
				}
				
			}
		}
		
		//HPIC
		$arrResult = array();
		
		foreach($rowsHpic as  $vaHpic){
		
			$invNo = $vaHpic["INV_NO"];
		
			$prList = $proHpic->getInvoiceProducts($invNo);
		
			foreach($prList as $k2 => $v){
		
		
				$key = array_search(trim($v["SCODE"]),$arrResult,true);
				if($key===false){
					$arrResult[] = trim($v["SCODE"]);
					$arrResult[] = (int)trim($v["QTY"]);
		
					$skLine = $proSkNew->getStockBalance(trim($v["SCODE"]), 'HPIC');
					if($skLine!==false){
						$arrResult[] = (int)trim($skLine["BALANCE"]);
					}
					else{
						$arrResult[] ="N/A";
					}
				}
				else{
					$arrResult[$key + 1] += (int)trim($v["QTY"]);
				}
					
			}
		
		}
		
		$arrFinal = array_chunk($arrResult,3);
		
		$cot = 0;
		
		foreach($arrFinal as $k2 => $v2){
			if($v2[0]!="INSTALLATION" && $v2[0]!="PHONEREPAIR" && $v2[0]!="CN" && $v2[0]!="SERVICE" && $v2[0]!="INSTALL" && $v2[0]!="PARTSSALES"   ){
				if($cot <41){
				$stockTake->addStocktake($v2[0],$v2[1],$v2[2],null,Model_DatetimeHelper::dateToday(),null,'HPIC',null);
				$cot++;
				}
				
			}
		}
		//PMIC
		$arrResult = array();
		
		foreach($rowsPmic as  $vaPmic){
		
			$invNo = $vaPmic["INV_NO"];
		
			$prList = $proPmic->getInvoiceProducts($invNo);
		
			foreach($prList as $k2 => $v){
		
		
				$key = array_search(trim($v["SCODE"]),$arrResult,true);
				if($key===false){
					$arrResult[] = trim($v["SCODE"]);
					$arrResult[] = (int)trim($v["QTY"]);
		
					$skLine = $proSkNew->getStockBalance(trim($v["SCODE"]), 'PMIC');
					if($skLine!==false){
						$arrResult[] = (int)trim($skLine["BALANCE"]);
					}
					else{
						$arrResult[] ="N/A";
					}
				}
				else{
					$arrResult[$key + 1] += (int)trim($v["QTY"]);
				}
					
			}
		
		}
		
		$arrFinal = array_chunk($arrResult,3);
		
		$cot = 0;
		
		foreach($arrFinal as $k2 => $v2){
			if($v2[0]!="INSTALLATION" && $v2[0]!="PHONEREPAIR" && $v2[0]!="CN" && $v2[0]!="SERVICE" && $v2[0]!="INSTALL" && $v2[0]!="PARTSSALES"   ){
				if($cot <41){
				$stockTake->addStocktake($v2[0],$v2[1],$v2[2],null,Model_DatetimeHelper::dateToday(),null,'PMIC',null);
				$cot++;
				}
				
			}
		}
				
		
	}
	

	
	public function index2Action(){
		
		//$dateToCheck = Model_DatetimeHelper::dateYesterday();
		$dateToCheck = '2013-06-03';
		
		
		$proSkNew = new Model_DbTable_Apos_Stock_Balancenew(Zend_Registry::get('db_apos'));
		$stockTake = new Model_DbTable_Products_Stock_Stocktake(Zend_Registry::get('db_real'));
		
		
		$invBsxp = new Model_DbTable_Apos_Invoice_Bsxp(Zend_Registry::get('db_apos'));
		$proBsxp = new Model_DbTable_Apos_Invoice_Products_Bsxp(Zend_Registry::get('db_apos'));
		$rowsBsxp = $invBsxp->getInvoicesByDate($dateToCheck);
		
		
		$arrResult = array();
		
		
		
		
		foreach($rowsBsxp as  $vaBsxp){
		
			$invNo = $vaBsxp["INV_NO"];
		
			$prList = $proBsxp->getInvoiceProducts($invNo);
		
			foreach($prList as $k2 => $v){
		
		
				$key = array_search(trim($v["SCODE"]),$arrResult,true);
				if($key===false){
					$arrResult[] = trim($v["SCODE"]);
					$arrResult[] = (int)trim($v["QTY"]);
		
					$skLine = $proSkNew->getStockBalance(trim($v["SCODE"]), 'BSXP');
					if($skLine!==false){
						$arrResult[] = (int)trim($skLine["BALANCE"]);
					}
					else{
						$arrResult[] ="N/A";
					}
				}
				else{
					$arrResult[$key + 1] += (int)trim($v["QTY"]);
				}
					
			}
		
		}
		
		$arrFinal = array_chunk($arrResult,3);
		
		foreach($arrFinal as $k2 => $v2){
		
			$stockTake->addStocktake($v2[0],$v2[1],$v2[2],null,'2013-06-04',null,'BSXP',null);
		}
	}
	
	public function yesterdaySummaryAction(){
		
		date_default_timezone_set('Australia/Melbourne');

		
		
		$attRecord = new Model_DbTable_Roster_Attnrecord();
		$staffs = new Model_DbTable_Roster_Stafflogindetail();
		$skTake = new Model_DbTable_Products_Stock_Stocktakelocal();
		$skTakeWeekly = new Model_DbTable_Products_Stock_Stocktakediflocal();
		$caOpening = new Model_DbTable_Cashaccountopening();
		$caClosing = new Model_DbTable_Cashaccountclosing();
		
		
		
		
		
		$dateToCheck = Model_DatetimeHelper::dateToday();
		
		$anotherDay = $this->_getParam("date");
		if($anotherDay!=""){
			$dateToCheck = $this->_getParam("date");
		}
		$dateToCheckYesterday = Model_DatetimeHelper::adjustDays("sub", $dateToCheck,1);
		
		$this->view->dateToCheck = $dateToCheck;
		$this->view->dateToCheckYesterday = $dateToCheckYesterday;
		
		
		
		
		
		$arrShopHead = array("BBPC","BHPC","BSIC","BSPC","BSXP","CBPC","CSIC","DCIC","EPIC","EPPC","FGIC","HPIC","KCPC","NLPC","PMIC","PMPC","SLIC","WBIC","WBPC","WGIC","WGPC","ADPC","CLIC","CLPC","WLIC");
		
		$arrRes = array();
		
		foreach ($arrShopHead as $v){
			if($v == "ADPC" || $v == "CLPC" || $v == "CLIC" || $v == "WLIC"){
				
				date_default_timezone_set('Australia/Adelaide');
			}
			else{
				
				date_default_timezone_set('Australia/Melbourne');
			}
			$tmpArr = array();
			
			//Shop Name 
			
			// Attendance Record
			
			
			$tmpArr[0] = $v;
			
			//Latest Time Yesterday Closing
			
			$resultYesterday = $attRecord->getRecordByShop(Model_EncryptHelper::sslPassword(trim($v)),$dateToCheckYesterday,$dateToCheckYesterday);
			if(!$resultYesterday){
					
				$tmpArr[1]= "No Record";
			}
			else{
				$vYes=end($resultYesterday);
					
				$tmpArr[1] = date("H :i",$vYes["ti"]);
			}
			// Detai Person
				$tmpArr[2] = "";
			$tStaList = $attRecord->getOndutyByDateByShop($v, $dateToCheckYesterday);
			if(!empty($tStaList)){
				
				foreach($tStaList as $k1 =>$v1){
			
					//echo $v["sti"];
					$stDetail = $staffs->getDetail($v1["sti"]);
					$tmpArr[2] .= ucwords(Model_EncryptHelper::getSslPassword($stDetail["n"]));
					$tmpArr[2] .= "(".ucwords($stDetail["ni"]).")";
					$arrRecord = $attRecord->getFirstOnLastOffDuty($v1["sti"], $dateToCheckYesterday, Model_EncryptHelper::sslPassword($v));
					//var_dump($arrRecord);
					$begin = "[NoRecord]";
					$end = "";
			
					if( $arrRecord[0] != "" ){
						$begin = date("H:i",$arrRecord[0]);
					}
					if( $arrRecord[1] != "" ){
						$end = date("H:i",$arrRecord[1]);
					}
					$tmpArr[2] .= " {$begin} to {$end}<br/>";
				}
				}
			
			//TODay Attendance
				//Latest Time Yesterday Closing
					
				$resultToday = $attRecord->getRecordByShop(Model_EncryptHelper::sslPassword(trim($v)),$dateToCheck,$dateToCheck);
				if(!$resultToday){
						
					$tmpArr[3]= "No Record";
				}
				else{
					$vTo=$resultToday[0];
						
					$tmpArr[3] = date("H :i",$vTo["ti"]);
				}
				// Today Detail
					$tmpArr[4] = "";
					
					$tStaList2 = $attRecord->getOndutyByDateByShop($v, $dateToCheck);
					if(!empty($tStaList2)){
					
						foreach($tStaList2 as $k2 =>$v2){
								
							//echo $v["sti"];
							$stDetail = $staffs->getDetail($v2["sti"]);
							$tmpArr[4] .= ucwords(Model_EncryptHelper::getSslPassword($stDetail["n"]));
							$tmpArr[4] .= "(".ucwords($stDetail["ni"]).")";
							$arrRecord = $attRecord->getFirstOnLastOffDuty($v2["sti"], $dateToCheck, Model_EncryptHelper::sslPassword($v));
							//var_dump($arrRecord);
							$begin = "[NoRecord]";
							$end = "";
								
							if( $arrRecord[0] != "" ){
								$begin = date("H:i",$arrRecord[0]);
							}
							if( $arrRecord[1] != "" ){
								$end = date("H:i",$arrRecord[1]);
							}
							$tmpArr[4] .= " {$begin} to {$end}<br/>";
						}
					}
			
			
			
			$tmpArr[5] = "";
			
			$caClsR2 = $caClosing->getActiveClosingByDate($v,$dateToCheckYesterday);
			if(!$caClsR2) {
				$tmpArr[5] ='<img src="/im/error2.jpg" />';
			}
			else{$tmpArr[5] ='<img src="/im/clicked.jpg" />';
			}
			
			$tmpArr[6] = "";
				
			$caOpeR2 = $caOpening->getActiveOpeningByDate($v,$dateToCheck);
			if(!$caOpeR2) {
				$tmpArr[6] = '<img src="/im/error2.jpg" />';
			}
			else{$tmpArr[6] = '<img src="/im/clicked.jpg" />';
			}
				
			
			$tmpArr[7] = "[NO Record]";
			
			if($caClsR2) {
				$tmpArr[7] = $caClsR2[0]["total_net_sales_close"];
			}
			
			//echo "date".$dateToCheck;
			$arrProducts = $skTake->listStocktake($dateToCheck,$v);
			$tmpArr[8] = count($arrProducts);
			$tmpArr[9] = $ifSubmit = $skTake->ifSubmitActual($dateToCheck,$v);
			$tmpArr[10] = $ifSubmitWeekly = $skTakeWeekly->ifSubmitActual($dateToCheck, $v);
			$tmpArr[11] = ($caClsR2)?$caClsR2[0]['sync_status']:true;
			$tmpArr[12] = ($caOpeR2)?$caOpeR2[0]['check_dvr']:false;
			//var_dump($arrProducts);
			//$tmpArr = array($v,count($arrProducts),$ifSubmit);
			$arrRes[]=$tmpArr;
		
		}
		//echo "<pre>";
		//var_dump($arrRes);
		//echo "</pre>";
		
		$this->view->arrRes = $arrRes;
		
	}
	public function lastWeekSummaryAction(){
		echo $dateToCheck = Model_DatetimeHelper::dateYesterday();
		$anotherDay = $this->_getParam("date");
		if($anotherDay!=""){
			$dateToCheck = $this->_getParam("date");
		}
		$skTake = new Model_DbTable_Products_Stock_Stocktakelocal();		
		
	}
	
	public function genShopSalesOldAction(){
		
		$arrShopMaping = unserialize(ARR_APOS_SHOP_MAPPING);
		
		$shop = $this->_getParam("shop");
		echo $dateToCheck = Model_DatetimeHelper::dateYesterday();
		//$dateToCheck = '2013-06-03';
		$anotherDay = $this->_getParam("date");
		if($anotherDay!=""){
			$dateToCheck = Model_DatetimeHelper::adjustDays("sub",$this->_getParam("date"),1);
		}
		$dateLog = Model_DatetimeHelper::adjustDays("add",$dateToCheck,1);
		
		$invOld = Model_Aposinit::initAposInvoice($shop);
		$proOld = Model_Aposinit::initAposInvPro($shop);
		$rowsOld = "";
		
		$proSkOld = new Model_DbTable_Apos_Stock_Balanceold(Zend_Registry::get('db_oldapos'));
		$stockTake = new Model_DbTable_Products_Stock_Stocktake(Zend_Registry::get('db_real'));
		$stockTake->cleanListByDate($dateLog,$shop);
		
		//
		$rowsOld = $invOld->getInvoicesByDate($dateToCheck);
		
		$arrResult = array();
	
		foreach($rowsOld as  $vaOld){
	
			$invNo = $vaOld["INV_NO"];
	
			$prList = $proOld->getInvoiceProducts($invNo);
	
			foreach($prList as $k2 => $v){
	
	
				$key = array_search(trim($v["SCODE"]),$arrResult,true);
				if($key===false){
					$arrResult[] = trim($v["SCODE"]);
					$arrResult[] = (int)trim($v["QTY"]);
	
					$skLine = $proSkOld->getStockBalance(trim($v["SCODE"]), $arrShopMaping[$shop]);
					if($skLine!==false){
						$arrResult[] = (int)trim($skLine["BALANCE"]);
					}
					else{
						$arrResult[] ="N/A";
					}
				}
				else{
					$arrResult[$key + 1] += (int)trim($v["QTY"]);
				}
					
			}
	
		}
	
		$arrFinal = array_chunk($arrResult,3);
		echo count($arrFinal);
		foreach($arrFinal as $k2 => $v2){
			if($v2[0]!="INSTALLATION" && $v2[0]!="PHONEREPAIR" && $v2[0]!="CN" && $v2[0]!="SERVICE" && $v2[0]!="INSTALL" && $v2[0]!="PARTSSALES"   ){
			$stockTake->addStocktake($v2[0],$v2[1],$v2[2],null,$dateLog,null,$shop,null);
			}
		}
	}
	
	
	public function genShopSalesNewAction(){
		
		$arrShopMaping = unserialize(ARR_APOS_SHOP_MAPPING);
		
	
		$shop = $this->_getParam("shop");
		echo $dateToCheck = Model_DatetimeHelper::dateYesterday();
		//$dateToCheck = '2013-06-03';
		$anotherDay = $this->_getParam("date");
		if($anotherDay!=""){
			$dateToCheck = Model_DatetimeHelper::adjustDays("sub",$this->_getParam("date"),1);
		}
		$dateLog = Model_DatetimeHelper::adjustDays("add",$dateToCheck,1);
		
		$invNew = Model_Aposinit::initAposInvoice($shop);
		$proNew = Model_Aposinit::initAposInvPro($shop);
		$rowsNew = "";
	
		$proSkNew = new Model_DbTable_Apos_Stock_Balancenew(Zend_Registry::get('db_apos'));
		$stockTake = new Model_DbTable_Products_Stock_Stocktake(Zend_Registry::get('db_real'));
		$stockTake->cleanListByDate($dateLog,$shop);
		
		//
		$rowsNew = $invNew->getInvoicesByDate($dateToCheck);
	
		$arrResult = array();
	
		foreach($rowsNew as  $vaNew){
	
			$invNo = $vaNew["INV_NO"];
	
			$prList = $proNew->getInvoiceProducts($invNo);
	
			foreach($prList as $k2 => $v){
	
	
				$key = array_search(trim($v["SCODE"]),$arrResult,true);
				if($key===false){
					$arrResult[] = trim($v["SCODE"]);
					$arrResult[] = (int)trim($v["QTY"]);
	
					$skLine = $proSkNew->getStockBalance(trim($v["SCODE"]),$arrShopMaping[$shop]);
					if($skLine!==false){
						$arrResult[] = (int)trim($skLine["BALANCE"]);
					}
					else{
						$arrResult[] ="N/A";
					}
				}
				else{
					$arrResult[$key + 1] += (int)trim($v["QTY"]);
				}
					
			}
	
		}
	
		$arrFinal = array_chunk($arrResult,3);
		echo count($arrFinal);
		foreach($arrFinal as $k2 => $v2){
			if($v2[0]!="INSTALLATION" && $v2[0]!="PHONEREPAIR" && $v2[0]!="CN" && $v2[0]!="SERVICE" && $v2[0]!="INSTALL" && $v2[0]!="PARTSSALES"   ){
			$stockTake->addStocktake($v2[0],$v2[1],$v2[2],null,$dateLog,null,$shop,null);
			}
		}
	}

	public function topHoursAction(){
		
		$dateBegin = $this->_getParam("date");
		$weeks = $this->_getParam("week");
		$shop =  $this->_getParam("shop");

		$dayDiff = $weeks*7-1;
		
		//$dateBegin = "2013-04-01";
		$dateEnd = Model_DatetimeHelper::adjustDays("add", $dateBegin,$dayDiff);
		//$dateToCheck = $dateBegin;
		
		$arrDate = Model_DatetimeHelper::createDateArray($dateBegin, $dateEnd);
		$invOld = new Model_DbTable_Apos_Invoice_Hpic(Zend_Registry::get('db_apos'));
		
		
		if($shop == "CBPC"){
			$invOld = new Model_DbTable_Apos_Invoice_Cb(Zend_Registry::get('db_apos'));
		}
		if($shop == "BSXP"){
			$invOld = new Model_DbTable_Apos_Invoice_Bsxp(Zend_Registry::get('db_apos'));
		}
		if($shop == "CLIC"){
			$invOld = new Model_DbTable_Apos_Invoice_Clic(Zend_Registry::get('db_apos'));
		}
		if($shop == "CSIC"){
			$invOld = new Model_DbTable_Apos_Invoice_Csic(Zend_Registry::get('db_apos'));
				
		}
		if($shop == "DCIC"){
			$invOld = new Model_DbTable_Apos_Invoice_Dcic(Zend_Registry::get('db_apos'));
		}
		if($shop == "FGIC"){
			$invOld = new Model_DbTable_Apos_Invoice_Fgic(Zend_Registry::get('db_apos'));
		}
		if($shop == "HPIC"){
			$invOld = new Model_DbTable_Apos_Invoice_Hpic(Zend_Registry::get('db_apos'));
		}
		if($shop == "PMIC"){
			$invOld = new Model_DbTable_Apos_Invoice_Pmic(Zend_Registry::get('db_apos'));
		}
		if($shop=="WBPC"){
			$invOld = new Model_DbTable_Apos_Invoice_Wb(Zend_Registry::get('db_oldapos'));
		}
			
		if($shop=="BHPC"){
			$invOld = new Model_DbTable_Apos_Invoice_Bh(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="NLPC"){
			$invOld = new Model_DbTable_Apos_Invoice_Nl(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="CLPC"){
			$invOld = new Model_DbTable_Apos_Invoice_Cl(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="WGPC"){
			$invOld = new Model_DbTable_Apos_Invoice_Wgpc(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="WBIC"){
			$invOld = new Model_DbTable_Apos_Invoice_Wbic(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="BBPC"){
			$invOld = new Model_DbTable_Apos_Invoice_Bb(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="EPPC"){
			$invOld = new Model_DbTable_Apos_Invoice_Ep(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="WLIC"){
			$invOld = new Model_DbTable_Apos_Invoice_Wlic(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="KCPC"){
			$invOld = new Model_DbTable_Apos_Invoice_Kc(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="PMPC"){
			$invOld = new Model_DbTable_Apos_Invoice_Pm(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="BSIC"){
			$invOld = new Model_DbTable_Apos_Invoice_Bsic(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="BSPC"){
			$invOld = new Model_DbTable_Apos_Invoice_Bs(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="SLIC"){
			$invOld = new Model_DbTable_Apos_Invoice_Slic(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="ADPC"){
			$invOld = new Model_DbTable_Apos_Invoice_Ad(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="WGIC"){
			$invOld = new Model_DbTable_Apos_Invoice_Wgic(Zend_Registry::get('db_oldapos'));
		}
		
		$arrInvoices = array();
		foreach ($arrDate as $dateToCheck){
			
			$invArr = $invOld->getInvoicesByDate($dateToCheck);	
			$arrTime = $invOld->getInvoiceNoArray($invArr);	
			
			foreach($arrTime as $dow => $tods){

				foreach($tods as $hod => $value){
					if(isset($arrInvoices[$dow][$hod])){
						$arrInvoices[$dow][$hod] +=$value;	
					}
					else{
						$arrInvoices[$dow][$hod] = $value;
					}
					
				}	
			}
		} 
		
		$this->view->arrInvoice = $arrInvoices;
		$this->view->arrDate = $arrDate;
		$this->view->week = $weeks;
		//var_dump($arrInvoices);
	}	

	public function inventoryTurnoverAction(){
		
		
		
		$dateEnd = '2013-07-14';
		
		$dateBegin = Model_DatetimeHelper::adjustWeeks("sub", $dateEnd, 4);
		
		
		$barCode = 'SAN7100LC001-3';
		
		$arrRes = array();
		$invPro = new Model_DbTable_Apos_Invoice_Products_Cb(Zend_Registry::get('db_apos'));
		$arrRes[] = $invPro->getProductSalesByDate($dateBegin,$dateEnd,$barCode);
		$invPro = new Model_DbTable_Apos_Invoice_Products_Bsxp(Zend_Registry::get('db_apos'));
		$arrRes[] = $invPro->getProductSalesByDate($dateBegin,$dateEnd,$barCode);
		$invPro = new Model_DbTable_Apos_Invoice_Products_Clic(Zend_Registry::get('db_apos'));
		$arrRes[] = $invPro->getProductSalesByDate($dateBegin,$dateEnd,$barCode);
		$invPro = new Model_DbTable_Apos_Invoice_Products_Csic(Zend_Registry::get('db_apos'));
		$arrRes[] = $invPro->getProductSalesByDate($dateBegin,$dateEnd,$barCode);
		$invPro = new Model_DbTable_Apos_Invoice_Products_Dcic(Zend_Registry::get('db_apos'));
		$arrRes[] = $invPro->getProductSalesByDate($dateBegin,$dateEnd,$barCode);
		$invPro = new Model_DbTable_Apos_Invoice_Products_Fgic(Zend_Registry::get('db_apos'));
		$arrRes[] = $invPro->getProductSalesByDate($dateBegin,$dateEnd,$barCode);
		$invPro = new Model_DbTable_Apos_Invoice_Products_Hpic(Zend_Registry::get('db_apos'));
		$arrRes[] = $invPro->getProductSalesByDate($dateBegin,$dateEnd,$barCode);
		$invPro = new Model_DbTable_Apos_Invoice_Products_Pmic(Zend_Registry::get('db_apos'));
		$arrRes[] = $invPro->getProductSalesByDate($dateBegin,$dateEnd,$barCode);
		$invPro = new Model_DbTable_Apos_Invoice_Products_Wb(Zend_Registry::get('db_oldapos'));
		$arrRes[] = $invPro->getProductSalesByDate($dateBegin,$dateEnd,$barCode);
		$invPro = new Model_DbTable_Apos_Invoice_Products_Bh(Zend_Registry::get('db_oldapos'));
		$arrRes[] = $invPro->getProductSalesByDate($dateBegin,$dateEnd,$barCode);	
		$invPro = new Model_DbTable_Apos_Invoice_Products_Nl(Zend_Registry::get('db_oldapos'));
		$arrRes[] = $invPro->getProductSalesByDate($dateBegin,$dateEnd,$barCode);
		$invPro = new Model_DbTable_Apos_Invoice_Products_Cl(Zend_Registry::get('db_oldapos'));
		$arrRes[] = $invPro->getProductSalesByDate($dateBegin,$dateEnd,$barCode);
		$invPro = new Model_DbTable_Apos_Invoice_Products_Wgpc(Zend_Registry::get('db_oldapos'));
		$arrRes[] = $invPro->getProductSalesByDate($dateBegin,$dateEnd,$barCode);
		$invPro = new Model_DbTable_Apos_Invoice_Products_Wbic(Zend_Registry::get('db_oldapos'));
		$arrRes[] = $invPro->getProductSalesByDate($dateBegin,$dateEnd,$barCode);
		$invPro = new Model_DbTable_Apos_Invoice_Products_Bb(Zend_Registry::get('db_oldapos'));
		$arrRes[] = $invPro->getProductSalesByDate($dateBegin,$dateEnd,$barCode);
		$invPro = new Model_DbTable_Apos_Invoice_Products_Ep(Zend_Registry::get('db_oldapos'));
		$arrRes[] = $invPro->getProductSalesByDate($dateBegin,$dateEnd,$barCode);
		$invPro = new Model_DbTable_Apos_Invoice_Products_Wlic(Zend_Registry::get('db_oldapos'));
		$arrRes[] = $invPro->getProductSalesByDate($dateBegin,$dateEnd,$barCode);
		$invPro = new Model_DbTable_Apos_Invoice_Products_Kc(Zend_Registry::get('db_oldapos'));
		$arrRes[] = $invPro->getProductSalesByDate($dateBegin,$dateEnd,$barCode);
		$invPro = new Model_DbTable_Apos_Invoice_Products_Pm(Zend_Registry::get('db_oldapos'));
		$arrRes[] = $invPro->getProductSalesByDate($dateBegin,$dateEnd,$barCode);
		$invPro = new Model_DbTable_Apos_Invoice_Products_Bsic(Zend_Registry::get('db_oldapos'));
		$arrRes[] = $invPro->getProductSalesByDate($dateBegin,$dateEnd,$barCode);
		$invPro = new Model_DbTable_Apos_Invoice_Products_Bs(Zend_Registry::get('db_oldapos'));
		$arrRes[] = $invPro->getProductSalesByDate($dateBegin,$dateEnd,$barCode);
		$invPro = new Model_DbTable_Apos_Invoice_Products_Slic(Zend_Registry::get('db_oldapos'));
		$arrRes[] = $invPro->getProductSalesByDate($dateBegin,$dateEnd,$barCode);
		$invPro = new Model_DbTable_Apos_Invoice_Products_Ad(Zend_Registry::get('db_oldapos'));
		$arrRes[] = $invPro->getProductSalesByDate($dateBegin,$dateEnd,$barCode);
		$invPro = new Model_DbTable_Apos_Invoice_Products_Wbic(Zend_Registry::get('db_oldapos'));
		$arrRes[] = $invPro->getProductSalesByDate($dateBegin,$dateEnd,$barCode);
		
		// to answer 5 questions
		//setp 1  turnover rate given period , total sales / average inventory
		//setp 2  turnover rate shop, how many shop are saling this staff  sales shop / total shop ? this number meaning ful?
		// (if something other people sale good not in the place of hot zone?)
		//step 3  how long it can last (weeks)
		// trend average weekly sales / current stock
		//step 4  does it have a eta?
		//just need fill by china
		//step 5  is able to catch eta? how long include back order it will last
		// trend average weekly sales / Current Stock level
		echo "<pre>";
		var_dump($arrRes);
		echo "</pre>";
		$tNo = count($arrRes);

		$totalCount = 0;
		$totalRate = 0;
		
		foreach($arrRes as $key=> $value){
			$totalCount += $value[0];
			$totalRate += $value[1];
		}
		
		
		echo "Average Count:".$totalCount / $tNo ." Average Rate:".round($totalRate / $tNo,4) * 100 ."%";
	}
	
	public function showWeeklyStockTakeDiffAction(){
		
		$shop = $this->_getParam("shop");
		$dateToCheck = $this->_getParam("date"); 
		$this->view->shopCode = $shop;
		$dateToday = ($dateToCheck=="")?Model_DatetimeHelper::dateToday():$dateToCheck;
		$this->view->dateToCheck = $dateToday;
		$afterPost = false;
		
		$stockTake = new Model_DbTable_Products_Stock_Stocktakediflocal();
		

		$staffDetail = new Model_DbTable_Roster_Stafflogindetail();
		
		//var_dump($stLine);
		
		
		if($_POST){
				$res = $staffDetail->checkPasswordCorrect(Model_EncryptHelper::hashsha(trim($_POST["att_pwd"])));
				//var_dump($res);
			if($res!==false){	
			
				foreach($_POST["qty_act"] as $key => $v){
				$qact = $v;
					if($v==""){
					$qact = null;
					}
				
				$stockTake->updateActStock($key, $qact,$res['id']);
				}
				
				$afterPost =true;
				
			}
			else{
				echo "<h1>Incorrect Password,Result Will Not Save!!Click \"Back To Return\"</h1>";
		
			}	
		}
		

		
		$stLine = $stockTake->listStocktake($dateToday,$shop);
		
		$this->view->arrStock = $stLine;
		
		
		if($afterPost){
			
			$this->renderScript('/salesmonitor/send-weekly-stocktake-result.phtml');
		}
		
		
	}
	public function weeklyStockTakeDiffAction(){
		
		
		$shop = $this->_getParam("shop");
		$this->view->shopCode = $shop;
		
		
		
		$arrShopMaping = unserialize(ARR_APOS_SHOP_MAPPING);
		
		$dateBegin = Model_DatetimeHelper::getLastWeekMonday();
		$dateEnd = Model_DatetimeHelper::getLastWeekSunday();	
		$dateToday = Model_DatetimeHelper::dateToday();
		
		$proSk = "";
		
		
		if($shop == "CBPC" || $shop == "BSXP" || $shop == "CLIC" || $shop == "CSIC" || $shop == "DCIC" || $shop == "FGIC" || $shop == "HPIC" || $shop == "PMIC"){

			$proSk = new Model_DbTable_Apos_Stock_Balancenew(Zend_Registry::get('db_apos')); 
		}
		else{

			$proSk = new Model_DbTable_Apos_Stock_Balanceold(Zend_Registry::get('db_oldapos'));	
		}
		
		$arrSummary = array();
		
		 
		if($shop!=""){

			$stockTake = new Model_DbTable_Products_Stock_Stocktake(Zend_Registry::get('db_real'));
			$arrDate = Model_DatetimeHelper::createDateArray($dateBegin, $dateEnd);
			
			$sTDiff = new Model_DbTable_Products_Stock_Stocktakedif(Zend_Registry::get('db_real'));
			$sTDiff->delStockTakeByShopByDate($dateToday,$shop);
			
			foreach($arrDate as $dateToCheck){
				
				$difRes = $stockTake->listDiff($dateToCheck, $shop);
				foreach($difRes as $k => $v){
					$key = array_search(trim($v["barcode"]),$arrSummary,true);
					if($key === false){
							
						$arrSummary[] = trim($v["barcode"]);
					}
					
				}
				
			}
			
			var_dump($arrSummary); // out put of array summary
			
			foreach($arrSummary as $barcode){

				$balance = 0;
			$skLine = $proSk->getStockBalance(trim($barcode), $arrShopMaping[$shop]);
					if($skLine!==false){
						$balance = (int)trim($skLine["BALANCE"]);
					}				
			//$sTDiff->addStocktake($barcode, $qtySys, $qtyAct, $dateTake, $timeUpload, $location, $staffName);
				$sTDiff->addStocktake(trim($barcode),$balance,null,$dateToday,null,$shop,null);
			}
			
			
			//put array summary into list 
			//get current onhand from apos and list in the sytem
			
			
		}
		
	}
	public function checkStockTakeResultAction(){
		
		if($_POST){
				
			$this->_redirect("/salesmonitor/check-stock-take-result/shop/".$_POST["shopcode"]."/date_begin/".$_POST["date_begin"]."/date_end/".$_POST["date_end"]);	
				
		}
		
		$shop = $this->_getParam("shop");
		$this->view->shopCode = $shop;
		$dateBegin = $this->_getParam("date_begin");
		$dateEnd = $this->_getParam("date_end");
		
		$arrRes = array();
		$arrSummary = array();
		$arrList = array();
		
		if($shop!="" && $dateBegin!="" && $dateEnd !="" ){
			
		$stockTake = new Model_DbTable_Products_Stock_Stocktakelocal();
		$arrDate = Model_DatetimeHelper::createDateArray($dateBegin, $dateEnd);

		$passLevel = 3;
		
		foreach($arrDate as $dateToCheck){
			
			$difRes = $stockTake->listDiff($dateToCheck, $shop);
			$submitCheck = $stockTake->ifSubmitActual($dateToCheck, $shop);
			$dateList = $stockTake->listStocktake($dateToCheck,$shop);
			$arrList[]= $dateList;
			
			$tmpArr = array();
			$tmpArr[0] = $dateToCheck;
			$tmpArr[1] = $submitCheck;

			$barCodeArr = array();
			
			foreach($difRes as $k => $v){

				$key = array_search(trim($v["barcode"]),$arrSummary,true);
				
				
				if($key === false){
					
					$arrSummary[] = trim($v["barcode"]);
					$barCodeArr[] = trim($v["barcode"]);
					$arrSummary[] = $v["qty_act"] - $v["qty_sys"];
					$arrSummary[] = $v["date_take"];
					$arrSummary[] = $v["date_take"];
					$arrSummary[] = 1; 
					$arrSummary[] = 0;
						
				}else{
					//if(trim($v["barcode"])=="SPIPHONE5"){echo "FD";}
					
					if( ($v["qty_act"] - $v["qty_sys"]) != $arrSummary[$key + 1]){
						
						$arrSummary[$key + 3] = $v["date_take"];
						$arrSummary[$key + 4] = 1;
						$arrSummary[$key + 5] += 1;
						$arrSummary[$key + 1] = $v["qty_act"] - $v["qty_sys"];
						
					}
					else{
						
						$arrSummary[$key + 4] += 1;
					}
					
				}
				
			}
			$tmpArr[2] = $barCodeArr;	
			$arrRes[] = $tmpArr;
			
		}
		
		}
		$this->view->arrRes = $arrRes;
		$this->view->arrSummary = array_chunk($arrSummary,6);
		$this->view->arrList = $arrList;
	}
	public function exportStockTakeResultAction(){
		
		$fileName = "";
		$shop= $this->_getParam("shop");
		$date = $this->_getParam("date");
		
		$products = new Model_DbTable_Apos_Stock_Productdes(Zend_Registry::get('db_oldapos'));
		
		
		$fileName = $shop."-".$date;
		$fileNameDetail = $shop."-".$date."-Detail";
		//$fl = fopen($fileName,"a");
		$arrRes = array();	
		$stDif = new Model_DbTable_Products_Stock_Stocktakediflocal(Zend_Registry::get('db_real'));
		$res = $stDif->listDiff($date, $shop);
	
		//var_dump($res);
		foreach($res as $line){
			$desLine = $products->getAposDes($line["barcode"]);
			$arrTmp = array($line["barcode"],$line["qty_act"] - $line["qty_sys"],$desLine["APRICE"]);
			//fputcsv($fl, $arrTmp);
			$arrRes[] = $arrTmp;
		}	
		
		$fl = new Model_Fileshandler();
		$fl->exportWeeklyStResult($arrRes, $fileName);
		$fl->exportWeeklyStResultDetail($arrRes, $fileNameDetail);
		//fclose($fl);
	}
	
	public function exportStockTakeCircleAction(){
		//http://192.168.1.126/salesmonitor/export-stock-take-result/date/2013-08-19/shop/ADPC
		set_time_limit(0);
		$date = Model_DatetimeHelper::getThisWeekMonday();
		
		
		ob_start();
		
		$shopArr = array("ADPC","BBPC","BHPC","BSPC","BSIC","CBPC","CLPC","EPPC","KCPC","NLPC","PMPC","SLIC","WBPC","WBIC","WLIC","WGIC","WGPC","CSIC","CLIC","DCIC","PMIC","BSXP","FGIC","HPIC");
		

			foreach($shopArr as $shop){
				echo $str="http://localhost/salesmonitor/export-stock-take-result/shop/".$shop."/date/".$date;
				echo "<br />";
				$res = file_get_contents($str);
				//sleep(1);
				echo $res;
				ob_flush();
				flush();
				//echo $date;
			}		
	}
	
	public function dailyStockTakeNotFinishAction(){

		//clean table for all the shop 
		//$date = Model_DatetimeHelper::dateToday();
		$shopHead = $this->_getParam("shop");
		$getDate = $this->_getParam("date");
		$date = ($getDate!="")?$this->_getParam("date"):Model_DatetimeHelper::dateToday();;
		//or date today
		$skTableB = new Model_DbTable_Products_Stock_Tableblocal();
		$skTableB->deleteAll($shopHead);
		
		
		$skTake = new Model_DbTable_Products_Stock_Stocktakelocal();
		// check table A finish or not 
		$ifSubmit = $skTake->ifSubmitActual($date, $shopHead);
		
		if(!$ifSubmit){
			$list = $skTake->listStocktake($date, $shopHead);
			
			foreach($list as $key =>$value){
					$skTableB->addTableb($value["barcode"], $shopHead);
			}
		}
		
		//finish do nothing 
		
		//not finish copy to table B
		
	}
	
	public function dailyNotFinishCircleAction(){
		
		set_time_limit(0);
		ob_start();
		

		$shopArr = array("ADPC","BBPC","BHPC","BSPC","BSIC","CBPC","CLPC","EPPC","KCPC","NLPC","PMPC","SLIC","WBPC","WBIC","WLIC","WGIC","WGPC","CSIC","CLIC","DCIC","PMIC","BSXP","FGIC","HPIC");
		//$shopArr=array("HPIC");
			foreach($shopArr as $shop){
				echo $str="http://localhost/salesmonitor/daily-stock-take-not-finish/shop/".$shop;
				echo "<br />";
		
				$res = file_get_contents($str);
				//sleep(1);
				echo $res;
				ob_flush();
				flush();
				//echo $date;
			}
	}

	public function patchTablebCircleAction(){
		
		set_time_limit(0);
		ob_start();
		
		
		$shopArr = array("ADPC","BBPC","BHPC","BSPC","BSIC","CBPC","CLPC","EPPC","KCPC","NLPC","PMPC","SLIC","WBPC","WBIC","WLIC","WGIC","WGPC","CSIC","CLIC","DCIC","PMIC","BSXP","FGIC","HPIC");
		//$shopArr=array("HPIC");
		foreach($shopArr as $shop){
			echo $str="http://localhost/salesmonitor/patch-tableb/shop/".$shop;
			echo "<br />";
		
			$res = file_get_contents($str);
			//sleep(1);
			echo $res;
			ob_flush();
			flush();
			//echo $date;
		}		
		
	}
	public function fixZeroAction(){
		
		$shopHead = $shop =  $this->_getParam("shop");
		$date = Model_DatetimeHelper::dateToday();	
		
		$arrShopMaping = array(
				"ADPC" =>"AD",
				"BBPC" =>"BB",
				"BHPC" =>"BH",
				"BSPC" =>"BS",
				"BSIC" =>"BSIC",
				"BSXP" =>"BSXP",
				"CBPC" =>"CB",
				"CLPC" =>"CL",
				"CLIC" =>"CLIC",
				"CSIC" =>"CSIC",
				"DCIC" =>"DCIC",
				"EPPC" =>"EP",
				"FGIC" =>"FGIC",
				"HPIC" =>"HPIC",
				"KCPC" =>"KC",
				"NLPC" =>"NL",
				"PMPC" =>"PM",
				"PMIC" =>"PMIC",
				"SLIC" =>"SLIC",
				"WBPC" =>"WB",
				"WBIC" =>"WBIC",
				"WGPC" =>"WGPC",
				"WGIC" =>"WGIC",
				"WLIC" =>"WLIC",
		);
		
		$skTake = new Model_DbTable_Products_Stock_Stocktakelocal();
		$sList = $skTake->listStocktake($date, $shopHead);
		
		if($shop == "CBPC" || $shop == "BSXP" || $shop == "CLIC" || $shop == "CSIC" || $shop == "DCIC" || $shop == "FGIC" || $shop == "HPIC" || $shop == "PMIC"){
		
			$proSk = new Model_DbTable_Apos_Stock_Balancenew(Zend_Registry::get('db_apos'));
		}
		else{
		
			$proSk = new Model_DbTable_Apos_Stock_Balanceold(Zend_Registry::get('db_oldapos'));
		}
		
		foreach($sList as $key => $value){
			
			$bcode = $value["barcode"];
			
			$qty = $proSk->getStockBalance($bcode,$arrShopMaping[$shop]);
			
			$skTake->updateSysStock($qty["BALANCE"], $date, $shop, $bcode);
			
		}
		
		
	}
	public function patchRepairPartsAction(){
		
		$shopHead =  $this->_getParam("shop");
		
		$date = Model_DatetimeHelper::dateYesterday();
		$rMove = new Model_DbTable_Pr_Prstockmovement();
		$rPro = new Model_DbTable_Pr_Prproducts();
		$rStock = new Model_DbTable_Pr_Prstock();
		
		$skTake = new Model_DbTable_Products_Stock_Stocktakelocal();

		$arrPartsList = array();
		
		// GO , FO , EI
		
		$goList = $rMove-> getStockMoveSumByDateByCode(2, $date, $date,self::$rCenterMapping[$shopHead]);
		$foList = $rMove-> getStockMoveSumByDateByCode(8, $date, $date,self::$rCenterMapping[$shopHead]);
		$eiList = $rMove-> getStockMoveSumByDateByCode(7, $date, $date,self::$rCenterMapping[$shopHead]);
		
		foreach($goList as $goRow){
			if(!in_array($goRow['id_product'],$arrPartsList)){
				$arrPartsList[] = $goRow['id_product'];
			}
			
		}

		foreach($foList as $foRow){
			if(!in_array($foRow['id_product'],$arrPartsList)){
				$arrPartsList[] = $foRow['id_product'];
			}
				
		}
		
		foreach($eiList as $eiRow){
			if(!in_array($eiRow['id_product'],$arrPartsList)){
				$arrPartsList[] = $eiRow['id_product'];
			}
				
		}
		
		
		//
		foreach($arrPartsList as $part){
			
			$partRow = $rPro->getProduct($part);
			
			$barcode = "[".$partRow['code_product']."]";
			$stock = $rStock->getShopStock($part, self::$rCenterMapping[$shopHead]);
			$skTake->addStocktake($barcode, 0, $stock,null,Model_DatetimeHelper::dateToday(),null, $shopHead,null); 
			
			
		}
		
		
		
	}
	
	public function patchTablebAction(){
		
		$shopHead = $shop =  $this->_getParam("shop");
		$date = Model_DatetimeHelper::dateToday();
		//or date today
		$tableB = new Model_DbTable_Products_Stock_Tableblocal();
		$skTake = new Model_DbTable_Products_Stock_Stocktakelocal();
		
		$tList = $tableB->listByShop($shopHead);
		$sList = $skTake->listStocktake($date, $shopHead);
		
		$arrTaBarcode = array();
		$arrSkBarcode = array();
		
		$arrShopMaping = array(
				"ADPC" =>"AD",
				"BBPC" =>"BB",
				"BHPC" =>"BH",
				"BSPC" =>"BS",
				"BSIC" =>"BSIC",
				"BSXP" =>"BSXP",
				"CBPC" =>"CB",
				"CLPC" =>"CL",
				"CLIC" =>"CLIC",
				"CSIC" =>"CSIC",
				"DCIC" =>"DCIC",
				"EPPC" =>"EP",
				"FGIC" =>"FGIC",
				"HPIC" =>"HPIC",
				"KCPC" =>"KC",
				"NLPC" =>"NL",
				"PMPC" =>"PM",
				"PMIC" =>"PMIC",
				"SLIC" =>"SLIC",
				"WBPC" =>"WB",
				"WBIC" =>"WBIC",
				"WGPC" =>"WGPC",
				"WGIC" =>"WGIC",
				"WLIC" =>"WLIC",
		);
		
		
		
		foreach($tList as $tb){
			$arrTaBarcode[] = $tb["barcode"];	
		}
		
		foreach($sList as $sk){
			$arrSkBarcode[] = $sk["barcode"];
		}
		
		$arrRes = array_diff($arrTaBarcode, $arrSkBarcode);
		
		var_dump($arrRes);
		
		$proSk = "";
		
		
		if($shop == "CBPC" || $shop == "BSXP" || $shop == "CLIC" || $shop == "CSIC" || $shop == "DCIC" || $shop == "FGIC" || $shop == "HPIC" || $shop == "PMIC"){
		
			$proSk = new Model_DbTable_Apos_Stock_Balancenew(Zend_Registry::get('db_apos'));
		}
		else{
		
			$proSk = new Model_DbTable_Apos_Stock_Balanceold(Zend_Registry::get('db_oldapos'));
		}
		
		$skQty = $skTake->listStocktake(Model_DatetimeHelper::dateToday(), $shop);
		$maxQty = 40 - count($skQty);
		
		if($maxQty >0){
			$cot = 0;
		foreach($arrRes as $bcode){
						
			$qty = $proSk->getStockBalance($bcode,$arrShopMaping[$shop]);
			//$skTake->addStocktake($barcode, $salesYest, $qtySys, $qtyAct, $dateTake, $timeUpload, $location, $staffName);
			if($cot < $maxQty )
			{	
			$skTake->addStocktake($bcode,0,(int)$qty["BALANCE"],null,Model_DatetimeHelper::dateToday(),null,$shop,null);
			$cot++;	
			}
		}
		}
		
		//for this barcode sales is 0, otherwise it will be in today's list
	}
	
	public function calShopTargetFileMonthlyAction(){
		
		
		$shopArr = array("ADPC","BBPC","BHPC","BSPC","BSIC","CBPC","CLPC","EPPC","KCPC","NLPC","PMPC","SLIC","WBPC","WBIC","WLIC","WGIC","WGPC","CSIC","CLIC","DCIC","PMIC","BSXP","FGIC","HPIC");
		
		$fileName = $this->_getParam("target");
		
		$arrBonusList = array();
		
		if($fileName!=""){
			$fl = new Model_Fileshandler();
			$arrBonusList = $fl->readManagerTarget($fileName);
			//@unlink($fileName);
		}
		// array(array("SIMON",4,"HPIC",5000,5000,100,0));
		
		$dateBegin = $this->_getParam("date_begin");
		$dateEnd = $this->_getParam("date_end");
		
		$caClose = new Model_DbTable_Cashaccountclosing();
		
		$arrDate = Model_DatetimeHelper::createDateArray($dateBegin, $dateEnd);
		// Get Stock Lost and Carry forward Stock lost 
		$staffBonus = new Model_Payroll_Staffbonus();
		$arrShops = array();
		
		
		
		// end of stock lost 
		$weeks = count($arrDate)/7;
		//echo $weeks;
		$arrWeeks = array();
		$arrRes = array();
		for($i=0;$i<$weeks;$i++){
			$arrWeeks[$i]["date_begin"] = Model_DatetimeHelper::adjustWeeks("add", $dateBegin, $i);
			$arrWeeks[$i]["date_end"] = Model_DatetimeHelper::adjustWeeks("add", Model_DatetimeHelper::adjustDays("add",$dateBegin,6), $i);
		}
		
		foreach($arrBonusList as $bonusRow){
				
			$shop = $bonusRow[2];
			$idManager = $bonusRow[1];
			$target = $bonusRow[3];
			$baseAmt = $bonusRow[4];
			$fixAmt = $bonusRow[5];
			$preAmt = $bonusRow[6];
				
			$tmpArr = array();
			$tmpArr[] = $shop; //shop
			$monthSum = 0;
			$totalBonus = 0;
			$tmpArr[]= 0;
				
				
			foreach($arrWeeks as $k => $v){
				//
				$tmpArr[] = $weekSum = $caClose->getWeeklyAPOSSummaryShop($shop,$v["date_begin"], $v["date_end"]);
		
				$weekBonus = "N/A";
		
				$tmpArr[] = $weekBonus;
		
				$monthSum += $weekSum;
				//$totalBonus +=$weekBonus;
			}
			
			$tmpArr[] = $monthSum; // monthly total
			$tmpArr[] = $target; // weekly
			$tmpArr[] = $target; // monthly target
			if($monthSum >= $target){
				  if($fixAmt > 0 && $preAmt ==0 ){
				  	$totalBonus = $fixAmt;
				  }
				  if($fixAmt == 0 && $preAmt >0 ){
				  	
				  	$totalBonus = Round(($weekSum - $baseAmt)* $preAmt /100,2);
				  }
				  }
			
			
			$tmpArr[] = $totalBonus;
			
			
			
			$tmpArr[] = $idManager;
			
			if(!in_array($shop,$arrShops)){
				
				$curLostLine = $staffBonus->getStockLostByShop($shop, $dateBegin, $dateEnd,4);
				$carryLostLine = $staffBonus->getStockLostByShop($shop, $dateBegin, $dateEnd,5);
				

				if($carryLostLine){
				$tmpArr[] = $carryLostLine['amt_total_bonus'];
				}
				else{
					$tmpArr[] = 0;
				}
				
				if($curLostLine){
					$tmpArr[] = $curLostLine['amt_total_bonus'];
				}else{
					$tmpArr[] = 0;
				}
				
				$arrShops[] = $shop;
			}
			else{
				
				$tmpArr[]=0;
				$tmpArr[]=0;
			
			}
			
			
			// bonus
			//var_dump($tmpArr);
			$arrRes[] = $tmpArr;
				
		}
		
		
		$arrResShop = array();
		$oldShop = "";
		$shopTotalBonus = 0;
		$shopTotalCarrayLost = 0;
		$shopTotalCurrentLost = 0;
		
		foreach($arrRes as $k =>$v){
			
			$Cot = count($v);
			
			if($k!=0 ){
				
				if($oldShop!= $v[0]){
					
					// it is already another shop  summary put into db and begin the new shop 	
					$actBonus = 0;
					$carryNext = 0;
					
					if(($shopTotalCarrayLost+$shopTotalCurrentLost)>=0){
						$actBonus = $shopTotalBonus;
						$carryNext =$shopTotalCarrayLost+$shopTotalCurrentLost; 
					}
					else{
						if($shopTotalBonus >= abs($shopTotalCarrayLost+$shopTotalCurrentLost)){
							
							$actBonus = $shopTotalBonus + $shopTotalCarrayLost + $shopTotalCurrentLost;
							$carryNext = 0;
						}
						else{
							$actBonus = 0;
							$carryNext =  $shopTotalBonus + $shopTotalCarrayLost + $shopTotalCurrentLost;
							
						}
						
					}
					$arrResShop[$oldShop] = array($shopTotalBonus,$shopTotalCarrayLost,$shopTotalCurrentLost,$actBonus,$carryNext);
					
					$oldShop = $v[0];
					
					$shopTotalBonus = $v[$Cot -4];
					$shopTotalCarrayLost = $v[$Cot -2];
					$shopTotalCurrentLost = $v[$Cot -1];
					
				}else{
					$shopTotalBonus += $v[$Cot -4];
					$shopTotalCarrayLost += $v[$Cot -2];
					$shopTotalCurrentLost += $v[$Cot -1];
				}
				
			}else{
				
				$oldShop = $v[0];
				
					$shopTotalBonus = $v[$Cot -4];
					$shopTotalCarrayLost = $v[$Cot -2];
					$shopTotalCurrentLost = $v[$Cot -1];
			}	
		}
		//var_dump($arrResShop);
		
		if($dateBegin != ""){
			$this->view->arrWeek = $arrWeeks;
			$this->view->arrRes = $arrRes;
			$this->view->arrResShop = $arrResShop;
			$this->view->dateBegin = $dateBegin;
			$this->view->dateEnd = $dateEnd;
			
		}
		
		
		if($_POST){
				
			$dateBegin = $_POST["date_begin"];
			$dateEnd = $_POST["date_end"];
				
			$dateNow = date("U");
			if(move_uploaded_file($_FILES["uploads"]["tmp_name"], $dateNow.".xls")){
		
				$this->_redirect("http://192.168.1.124/salesmonitor/cal-shop-target-file-monthly/date_begin/{$dateBegin}/date_end/{$dateEnd}/target/{$dateNow}.xls");
			}
				
				
		}
		
		
	}
	
	public function calShopTargetFileAction(){
		
		$shopArr = array("ADPC","BBPC","BHPC","BSPC","BSIC","CBPC","CLPC","EPPC","KCPC","NLPC","PMPC","SLIC","WBPC","WBIC","WLIC","WGIC","WGPC","CSIC","CLIC","DCIC","PMIC","BSXP","FGIC","HPIC");
		
		$fileName = $this->_getParam("target");
		
		$arrBonusList = array();
		
		if($fileName!=""){
			$fl = new Model_Fileshandler();
			$arrBonusList = $fl->readManagerTarget($fileName);
		//@unlink($fileName);
		}
		// array(array("SIMON",4,"HPIC",5000,5000,100,0));
		
		$dateBegin = $this->_getParam("date_begin");
		$dateEnd = $this->_getParam("date_end");
		
		$caClose = new Model_DbTable_Cashaccountclosing();
		
		$arrDate = Model_DatetimeHelper::createDateArray($dateBegin, $dateEnd);
		
		
		$weeks = count($arrDate)/7;
		//echo $weeks;
		$arrWeeks = array();
		$arrRes = array();
		for($i=0;$i<$weeks;$i++){
			$arrWeeks[$i]["date_begin"] = Model_DatetimeHelper::adjustWeeks("add", $dateBegin, $i);
			$arrWeeks[$i]["date_end"] = Model_DatetimeHelper::adjustWeeks("add", Model_DatetimeHelper::adjustDays("add",$dateBegin,6), $i);
		}
		
		foreach($arrBonusList as $bonusRow){
			
			$shop = $bonusRow[2];
			$idManager = $bonusRow[1];
			$target = $bonusRow[3];
			$baseAmt = $bonusRow[4];
			$fixAmt = $bonusRow[5];
			$preAmt = $bonusRow[6];
			
			$tmpArr = array();
			$tmpArr[] = $shop; //shop
			$monthSum = 0;
			$totalBonus = 0;
			$tmpArr[]= 0;
			
			
			foreach($arrWeeks as $k => $v){
				  //
				  $tmpArr[] = $weekSum = $caClose->getWeeklyAPOSSummaryShop($shop,$v["date_begin"], $v["date_end"]);	
				  
				  $weekBonus = 0;
				  
				  if($weekSum >= $target){
				  if($fixAmt > 0 && $preAmt ==0 ){
				  	$weekBonus = $fixAmt;
				  }
				  if($fixAmt == 0 && $preAmt >0 ){
				  	
				  	$weekBonus = Round(($weekSum - $baseAmt)* $preAmt /100,2);
				  }
				  }
				  $tmpArr[] = $weekBonus;
				  
				  $monthSum += $weekSum; 	
				  $totalBonus +=$weekBonus;
			}
			$tmpArr[] = $monthSum; // monthly total
			$tmpArr[] = $target; // weekly 
			$tmpArr[] = $target*$weeks; // monthly target
			$tmpArr[] = $totalBonus;
			$tmpArr[] = $idManager;
			// bonus
			$arrRes[] = $tmpArr;
			
		}
		
		if($dateBegin != ""){
		$this->view->arrWeek = $arrWeeks;
		$this->view->arrRes = $arrRes;
		$this->view->dateBegin = $dateBegin;
		$this->view->dateEnd = $dateEnd;
		}
		
		
		if($_POST){
			
			$dateBegin = $_POST["date_begin"];
			$dateEnd = $_POST["date_end"];
			
			$dateNow = date("U");
			if(move_uploaded_file($_FILES["uploads"]["tmp_name"], $dateNow.".xls")){
				
				$this->_redirect("http://192.168.1.124/salesmonitor/cal-shop-target-file/date_begin/{$dateBegin}/date_end/{$dateEnd}/target/{$dateNow}.xls");
			}
			
			
		}
		
	}
	
	public function calShopTargetAction(){
		
		$caClose = new Model_DbTable_Cashaccountclosing();
		//$sf = new Model_DbTable_Target_Shopfomula();
		
		//$sfList = $sf->listAll();
		
		$shopArr = array("ADPC","BBPC","BHPC","BSPC","BSIC","CBPC","CLPC","EPPC","KCPC","NLPC","PMPC","SLIC","WBPC","WBIC","WLIC","WGIC","WGPC","CSIC","CLIC","DCIC","PMIC","BSXP","FGIC","HPIC");
		
		
		
		$target = new Model_DbTable_Target_Weeklytarget();
		$shopf = new Model_DbTable_Target_Shopfomula(); 
		
		$sfList = $shopf->listAll();
		
		
		$dateBegin = "2014-02-03";
		$dateEnd = "2014-03-02";
		
		$arrDate = Model_DatetimeHelper::createDateArray($dateBegin, $dateEnd);
		$weeks = count($arrDate)/7;
		//echo $weeks;
		$arrWeeks = array();
		$arrRes = array();
		for($i=0;$i<$weeks;$i++){
			$arrWeeks[$i]["date_begin"] = Model_DatetimeHelper::adjustWeeks("add", $dateBegin, $i);
			$arrWeeks[$i]["date_end"] = Model_DatetimeHelper::adjustWeeks("add", Model_DatetimeHelper::adjustDays("add",$dateBegin,6), $i); 
		}
		//var_dump($arrWeeks);
		
		foreach($sfList as $row){
			
			$shop = $row["shop_code"];
			$staffID = $row["id_staff"];
			
			$tmpArr = array();
			$tmpArr[] = $shop; //shop 
			$monthSum = 0;
			$totalBonus = 0;
			$tmpArr[]= 0;
			foreach($arrWeeks as $k => $v){
				  //
				  $tmpArr[] = $weekSum = $caClose->getWeeklyAPOSSummaryShop($shop,$v["date_begin"], $v["date_end"]);	
				  
				  $tarLine = $target->getWeeklyTargetByShopByDate($shop,$v["date_begin"]);
				 // var_dump($tarLine);
				  
				  $tmpArr[] = $weekBonus = $shopf->calBonus($staffID,$shop,$weekSum,$tarLine["target"]);
				  $monthSum += $weekSum; 	
				  $totalBonus +=$weekBonus;
			}
			$tmpArr[] = $monthSum; // monthly total
			$tmpArr[] = $tarLine["target"]; // weekly 
			$tmpArr[] = $tarLine["target"]*$weeks; // monthly target
			$tmpArr[] = $totalBonus;
			$tmpArr[] = $staffID;
			// bonus
			$arrRes[] = $tmpArr;
		}
		
		$this->view->arrWeek = $arrWeeks;
		$this->view->arrRes = $arrRes;
		$this->view->dateBegin = $dateBegin;
		$this->view->dateEnd = $dateEnd;
		
		//var_dump($arrRes);
		
		//$res = $caClose->getWeeklyAPOSSummaryShop("BHPC", "2013-07-22","2013-07-28");
		
		//var_dump($res);
	}
	
	public function calShopTargetGroupAction(){
		
		$caClose = new Model_DbTable_Cashaccountclosing();
		
		$this->view->cycle = self::$targetCycle;
		
		$arrShopGroup = array(
				1=> array("ADPC"),
				2=> array("BBPC"),
				3=> array("BHPC"),
				4=> array("BSPC","BSIC","BSXP"),
				5=> array("CBPC"),
				6=> array("CLPC","CLIC"),
				7=> array("DCIC"),
				8=> array("EPPC"),
				9=> array("FGIC"),
				10=>array("HPIC"),
				11=>array("KCPC"),
				12=>array("NLPC"),
				13=>array("PMIC","PMPC"),
				14=>array("SLIC"),
				15=>array("WBPC","WBIC"),
				16=>array("WGPC","WGIC"),
				17=>array("WLIC")
		);
		
		$this->view->group = $arrShopGroup;
		
		if($_POST){

			$idCycle = $_POST['id_cycle'];
			$arrCycle = self::$targetCycle[$idCycle];
			$idGroup = $_POST['id_group'];
			
			$dateBegin = $arrCycle[0];
			$dateEnd = $arrCycle[1];
			$arrRes = array();
			
			if($idGroup >0){
			$arrShop = $arrShopGroup[$idGroup];
			
			$sumGroup = 0;
			
			
			foreach($arrShop as $shop){
				
				$sumGroup += $caClose->getWeeklyAPOSSummaryShop($shop,$dateBegin,$dateEnd);
				
			}
			 
			d($sumGroup);
			$arrRes[] = array(1,$arrCycle,$arrShop,$sumGroup);
			}
			else{

				foreach($arrShopGroup as $key => $v){
					
					$sumGroup = 0;

					foreach($v as $shop){
						
						$sumGroup += $caClose->getWeeklyAPOSSummaryShop($shop,$dateBegin,$dateEnd);
						//d($shop,$dateBegin,$dateEnd);
					}
					$arrRes[$key] = array($key,$arrCycle,$v,$sumGroup);
				}
			}
			$this->view->arrRes = $arrRes; 
			//d($this->view->arrRes);
		}
		
	}
	
	public function calShopTargetManagerAction(){
		
		$idManager = 0;
		$idManager = $this->_getParam("mgr");
		$ifEmail = $this->_getParam("email");
		
		$arrMgrEmail = array(
				2 => "hope.mcmahon@phonecollection.com.au",//hope
				3 => "adele.ye@phonecollection.com.au",//adele
				4 => "simon.seop@phonecollection.com.au",//simon
				//6 => "emily.liang@phonecollection.com.au",//emily
				//8 => "karen.zhang@phonecollection.com.au",//karen
				//26 => "christina.huang@phonecollection.com.au",//christina
				27 => "terra.yu@phonecollection.com.au",//terra
				//80 => "sophia.lam@phonecollection.com.au",//sophia
				115 => "iris.kim@phonecollection.com.au",//iris
				125 => "catherine.ho@phonecollection.com.au",//catherine
				"all" => "phonecollection.au@gmail.com"
		);
		
		
		$caClose = new Model_DbTable_Cashaccountclosing();
		
		$target = new Model_DbTable_Target_Weeklytarget();
		$shopf = new Model_DbTable_Target_Shopfomula();
		
		//$sfList = $shopf->listAll();
		$sfList = $shopf->listShopByManager($idManager);
		
		$arrCircle = $this->whichCircle(Model_DatetimeHelper::dateYesterday());
		
		$dateBegin = $arrCircle[0];
		$dateEnd = $arrCircle[1];
		
		$arrDate = Model_DatetimeHelper::createDateArray($dateBegin, $dateEnd);
		$weeks = count($arrDate)/7;
		//echo $weeks;
		$arrWeeks = array();
		$arrRes = array();
		for($i=0;$i<$weeks;$i++){
			$arrWeeks[$i]["date_begin"] = Model_DatetimeHelper::adjustWeeks("add", $dateBegin, $i);
			$arrWeeks[$i]["date_end"] = Model_DatetimeHelper::adjustWeeks("add", Model_DatetimeHelper::adjustDays("add",$dateBegin,6), $i);
		}
		//var_dump($arrWeeks);
		
		foreach($sfList as $row){
				
			$shop = $row["shop_code"];
			$staffID = $row["id_staff"];
				
			$tmpArr = array();
			$tmpArr[] = $shop; //shop
			$monthSum = 0;
			$totalBonus = 0;
			$tmpArr[]= 0;
			foreach($arrWeeks as $k => $v){
				//
				$tmpArr[] = $weekSum = $caClose->getWeeklyAPOSSummaryShop($shop,$v["date_begin"], $v["date_end"]);
		
				$tarLine = $target->getWeeklyTargetByShopByDate($shop,$v["date_begin"]);
				// var_dump($tarLine);
		
				$tmpArr[] = $weekBonus = $shopf->calBonus($staffID,$shop,$weekSum,$tarLine["target"]);
				$monthSum += $weekSum;
				$totalBonus +=$weekBonus;
			}
			$tmpArr[] = $monthSum; // monthly total
			$tmpArr[] = $tarLine["target"]; // weekly
			$tmpArr[] = $tarLine["target"]*$weeks; // monthly target
			$tmpArr[] = $totalBonus;
			$tmpArr[] = $staffID;
			// bonus
			$arrRes[] = $tmpArr;             
		}
		
		$this->view->arrWeek = $arrWeeks;
		$this->view->arrRes = $arrRes;
		$this->view->dateBegin = $dateBegin;
		$this->view->dateEnd = $dateEnd;
		
		$this->view->email =  $arrMgrEmail[$idManager];//co1@phonecollection.com.au";
		$this->view->ifemail = $ifEmail;
		
	}
	

	public function dailyReportManagerAction(){
		
		$idManager = $this->_getParam("mgr");
		$ifEmail = $this->_getParam("email");
		
		$arrMgrEmail = array(
				 2 => "hope.mcmahon@phonecollection.com.au",//hope
				 3 => "adele.ye@phonecollection.com.au",//adele
				 4 => "simon.seop@phonecollection.com.au",//simon
				27 => "terra.yu@phonecollection.com.au",//terra
				36=> "ting.chow@phonecollection.com.au",// chow
			   115 => "iris.kim@phonecollection.com.au",//iris
			   125 => "catherine.ho@phonecollection.com.au",// catherine
			   131 => "jason.han@phonecollection.com.au",//jason
			   156 => "kevin.shi@phonecollection.com.au",//kevin
			   162 => "kelly.li@phonecollection.com.au",//kelly
			   165 => "will.li@phonecollection.com.au",//Will
			   181 => "bing.han@phonecollection.com.au",// Bing
			   204 => "john.chan@phonecollection.com.au",//john 
			   "all" => "phonecollection.au@gmail.com"
				);
		
	
		$dateToday = Model_DatetimeHelper::dateToday();

		$dateYesterday = Model_DatetimeHelper::dateYesterday();
		$date2Days = Model_DatetimeHelper::adjustDays("sub", $dateToday,8);
		
		$weekBegin = Model_DatetimeHelper::getThisWeekMonday();
		$weekEnd = Model_DatetimeHelper::getThisWeekSunday();
		
		
		$lastWeekBegin = Model_DatetimeHelper::getLastWeekMonday();
		$lastWeekEnd = Model_DatetimeHelper::getLastWeekSunday();		
		
		
		$caClose = new Model_DbTable_Cashaccountclosing();
		$sf = new Model_DbTable_Target_Shopfomula();
		$tar = new Model_DbTable_Target_Weeklytarget();
		
		
		//$idManager = 4;
		
		//$id = $this->_getParam("mid");
		//$idManager = base64_decode($id);
		$shopList = "";
		
		if($idManager == "all"){
			
			$shopList = array(
					array("shop_code"=>"CBPC","cot"=> "0" ),			
					array("shop_code"=>"PMIC","cot"=> "0"),
					array("shop_code"=>"PMPC","cot"=> "0"),
					array("shop_code"=>"SLIC","cot"=> "0"), //Hope
					
					
					array("shop_code"=>"FGIC","cot"=> "1" ),// Adele
					
					array("shop_code"=>"BSIC","cot"=> "0"), 
					array("shop_code"=>"BSPC","cot"=> "0"),
					array("shop_code"=>"BSXP","cot"=> "0"),
					array("shop_code"=>"ADPC","cot"=> "0"),
					array("shop_code"=>"BBPC","cot"=> "0"),
					array("shop_code"=>"CLIC","cot"=> "0"),
					array("shop_code"=>"CLPC","cot"=> "0"),
					array("shop_code"=>"CSIC","cot"=> "0"),
					array("shop_code"=>"DCIC","cot"=> "0"),
					array("shop_code"=>"HPIC","cot"=> "0"),
					array("shop_code"=>"WLIC","cot"=> "0"), // SIMON
					array("shop_code"=>"WGPC","cot"=> "0"),
					array("shop_code"=>"WGIC","cot"=> "0"),
					
					array("shop_code"=>"EPPC","cot"=> "1"),
					array("shop_code"=>"NLPC","cot"=> "1"), // CHOW
					
					array("shop_code"=>"BHPC","cot"=> "0"), 
					array("shop_code"=>"KCPC","cot"=> "0"),// TERRA
					
					array("shop_code"=>"WBPC","cot"=> "1"),
					array("shop_code"=>"WBIC","cot"=> "1") // SOPHIA
					
					);
		}
		else{
			$shopList = $sf->listShopByManager($idManager);
		}
		
		
		
		$arrRes = array();
		foreach($shopList as $sp){
			$tmpArr = array();

			$shop = $sp["shop_code"];
			$tmpArr["shop"] = $shop;
			
			$target = $tar->getWeeklyTargetByShopByDate($shop, $dateToday);
			//$target["target"];
			$tmpArr["target"] = $target["target"];
			
			$yesterdaySales = $caClose->getActiveClosingByDate($shop, $dateYesterday);
			//echo $yesterdaySales[0]["apos_sys_sales_total"];
			$tmpArr["yesterday"] =  $yesterdaySales[0]["apos_sys_sales_total"];
			
			$twoDaysBefore = $caClose->getActiveClosingByDate($shop, $date2Days);
			//echo $twoDaysBefore[0]["apos_sys_sales_total"];
			$tmpArr["2days"] = $twoDaysBefore[0]["apos_sys_sales_total"];
			
			$tmpArr["week_total"] = $thisWeekTotal = $caClose->getWeeklyAPOSSummaryShop($shop,$weekBegin, $weekEnd);
			$tmpArr["week_days"] = $thisWeekDays = $caClose->getWeeklyAPOSSummaryShopDays($shop,$weekBegin, $weekEnd);
			
			$tmpArr["percent"] = $percent = round($thisWeekTotal / $target["target"],2) *100;
			$tmpArr["remain"]= $remaining = ($thisWeekTotal < $target["target"])? ($target["target"] - $thisWeekTotal):0;	
			$tmpArr["daily_target"] = $dailyTarget = round($remaining / Model_DatetimeHelper::dayLeft(),0);
			$tmpArr["last_week_total"] = $lastWeekTotal = $caClose->getWeeklyAPOSSummaryShop($shop,$lastWeekBegin,$lastWeekEnd);  
			$tmpArr["last_week_sofar"] = $lastWeekSofar = $caClose->getWeeklyAPOSSummaryShop($shop,$lastWeekBegin,$date2Days);   
			$tmpArr["last_week_precent"] = ($thisWeekTotal >0)? round($lastWeekSofar/$thisWeekTotal*$percent,0):1; 
			
			if($idManager == "all"){
				$tmpArr["color"] = $sp["cot"];
			}
			else{
				$tmpArr["color"] = 0;
			}
			$arrRes[] = $tmpArr;
		}
		
		$this->view->arrRes = $arrRes;
		$this->view->email =  $arrMgrEmail[$idManager];//co1@phonecollection.com.au";
		$this->view->ifemail = $ifEmail;
		/**
		 * Staff issue 
		 */
		

		//attendance missing 
		//attendance late 
		
		
		$attRcord = new Model_DbTable_Roster_Attnrecord();
		$ts = new Model_DbTable_Roster_Timesheet();
		
		$arrMiss = array();
		
		foreach($shopList as $sp){
			
			$shop = $sp["shop_code"];
			$tList = $ts->listShiftByDateByShop($shop,$dateYesterday, $dateYesterday);
			foreach($tList as $timeSheetRow){
				
				
				$idStaff = $timeSheetRow['id_staff'];	
				$rList = $attRcord->getFirstOnLastOffDuty($idStaff, $dateYesterday, Model_EncryptHelper::sslPassword($shop));

				if($rList[0]=="" || $rList[1]==""){
					$timeOn =($rList[0]=="")?"N/A":date("H:i:s",$rList[0]);
					$timeOff =($rList[1]=="")?"N/A":date("H:i:s",$rList[1]);
					$arrMiss[] = array($idStaff,$dateYesterday,$shop,$timeOn,$timeOff);
					
				}
				
		
			}
		}
		
		//var_dump($arrMiss);
		$this->view->arrMiss = $arrMiss;
		
	}
	
	public function calCostAction(){
		set_time_limit(0);
		
		$dateEnd = Model_DatetimeHelper::getLastWeekSunday();
		$dateBegin = Model_DatetimeHelper::adjustWeeks("sub", Model_DatetimeHelper::getLastWeekMonday(),3);
		$pProduct = new Model_DbTable_Productsva(Zend_Registry::get('db_real'));
		$pPrice = new Model_DbTable_Products_Price_Prices(Zend_Registry::get('db_real'));
		
		$dateBegin = "2013-08-26";
		$dateEnd = "2013-09-22";
		
		
		
		
		$arrDate = Model_DatetimeHelper::createDateArray($dateBegin, $dateEnd); 
		
		$arrNoPro = array();
		$arrNoPrice = array();
		
		
		$shop = $this->_getParam("shop");
		$arrShopMaping = array(
				"ADPC" =>"AD",
				"BBPC" =>"BB",
				"BHPC" =>"BH",
				"BSPC" =>"BS",
				"BSIC" =>"BSIC",
				"BSXP" =>"BSXP",
				"CBPC" =>"CB",
				"CLPC" =>"CL",
				"CLIC" =>"CLIC",
				"CSIC" =>"CSIC",
				"DCIC" =>"DCIC",
				"EPPC" =>"EP",
				"FGIC" =>"FGIC",
				"HPIC" =>"HPIC",
				"KCPC" =>"KC",
				"NLPC" =>"NL",
				"PMPC" =>"PM",
				"PMIC" =>"PMIC",
				"SLIC" =>"SLIC",
				"WBPC" =>"WB",
				"WBIC" =>"WBIC",
				"WGPC" =>"WGPC",
				"WGIC" =>"WGIC",
				"WLIC" =>"WLIC",
		);
		
		$invOld = "";
		$proNew = "";

		
		//$arrCanCal = array();
		//$arrNotCal = array();
		//$arrRep = array();
		foreach($arrShopMaping as $shop => $v){
			
			
			$sumCanCalSales = 0;
			$sumCanCalCost = 0;
			$sumNotCalSales = 0;
			$sumNotCalCost = 0;
			$sumRepair = 0;
			
			
			
			
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
			
			
			foreach($arrDate as $dateToCheck){
					
				$iList = $invOld->getInvoicesByDate($dateToCheck);
			
				foreach($iList as $invRow){
					//get products
					$invNo = $invRow["INV_NO"];
					if($invRow["STATUS"] != "V" ){ // void invoice will not be count
						$proList = $invPro->getInvoiceProducts($invNo);
							
						foreach($proList as $k => $v){
			
							$barCode = trim($v["SCODE"]);
							if($barCode =="PHONEREPAIR"){
								
								$sumRepair += $v["AMOUNT"];
								
							}
							else{
								
								$idProduct = $pProduct->getProductID($barCode);
								if($idProduct){
									$priceLine = $pPrice->getPrice($idProduct);
									// get price
									//var_dump($priceLine);
									if($priceLine["price_import"] > 0 || $priceLine["price_buy"] > 0  ){
										
										$buyPrice = $priceLine["price_import"];
										
										if($priceLine["id_import_currency"] == 2){
											
											$buyPrice = $buyPrice / self::$extRate;
											
										}
										
										if($priceLine["id_import_currency"] == 3){
												
											$buyPrice = $buyPrice / self::$extRateUs;
												
										}
											
										//$buyPrice = ($priceLine["price_import"] > 0) ? $priceLine["price_import"]/$extRate : $priceLine["price_buy"];
											
										$sumCanCalSales += $v["AMOUNT"];
										$sumCanCalCost += round((int)$v["QTY"]*$buyPrice,2);
									}
									else{
											
										//echo $barCode."<br />";
										$sumNotCalSales += $v["AMOUNT"];
										$arrNoPrice[] = $barCode;
									}
										
								}
								else{
									$sumNotCalSales += $v["AMOUNT"];
									//echo $barCode."<br />";
									$arrNoPro[] = $barCode;
								}
									
								
							}
							
						}
							
					}
				}
					
			}
			
			echo $sumCanCalSales."|";
			echo $sumNotCalSales."|";
			echo $sumCanCalCost."|";
			echo $sumRepair."<br />";

		}
		
		//var_dump(array_unique($arrNoPro));
		echo "<br />";
		//var_dump(array_unique($arrNoPrice));
		$arrNoPrice = array_unique($arrNoPrice);
		array_multisort($arrNoPrice,SORT_ASC);
		
		$pro = new Model_DbTable_Productsva();
		
		foreach($arrNoPrice as $code){
			
			echo $code.",";
			
			echo $pro->getProductNameByCode($code)."<br />";
			
			
		}
		
		$this->view->arrNoPrice = array_unique($arrNoPrice);
		// get all the invoice -> get all sales 
		
		// get all barcode 
		
		// group barcode   with import price / without  price /  phone repair -> we buy price 
		
		// list barcode 
		
		// sales -> 80% -> cost no shipment  , sales -> 10 , no buy price ,sales ?% -> phone parts /pre week -> pre month
		 
	}
	
	
	public function calCostBrandAction(){
		set_time_limit(0);
	
		$dateEnd = Model_DatetimeHelper::getLastWeekSunday();
		$dateBegin = Model_DatetimeHelper::adjustWeeks("sub", Model_DatetimeHelper::getLastWeekMonday(),3);
		$pProduct = new Model_DbTable_Productsva(Zend_Registry::get('db_real'));
		$pPrice = new Model_DbTable_Products_Price_Prices(Zend_Registry::get('db_real'));
		$pDes = new Model_DbTable_Apos_Stock_Productdes(Zend_Registry::get('db_oldapos'));
		
	
		$dateBegin = "2013-08-26";
		$dateEnd = "2013-09-22";
	
	
	
	
		$arrDate = Model_DatetimeHelper::createDateArray($dateBegin, $dateEnd);
	
		$arrNoPro = array();
		$arrNoPrice = array();
	
	
		$shop = $this->_getParam("shop");
		$arrShopMaping = array(
				"ADPC" =>"AD",
				"BBPC" =>"BB",
				"BHPC" =>"BH",
				"BSPC" =>"BS",
				"BSIC" =>"BSIC",
				"BSXP" =>"BSXP",
				"CBPC" =>"CB",
				"CLPC" =>"CL",
				"CLIC" =>"CLIC",
				"CSIC" =>"CSIC",
				"DCIC" =>"DCIC",
				"EPPC" =>"EP",
				"FGIC" =>"FGIC",
				"HPIC" =>"HPIC",
				"KCPC" =>"KC",
				"NLPC" =>"NL",
				"PMPC" =>"PM",
				"PMIC" =>"PMIC",
				"SLIC" =>"SLIC",
				"WBPC" =>"WB",
				"WBIC" =>"WBIC",
				"WGPC" =>"WGPC",
				"WGIC" =>"WGIC",
				"WLIC" =>"WLIC",
		);
	
		$invOld = "";
		$proNew = "";
	
	
		//$arrCanCal = array();
		//$arrNotCal = array();
		//$arrRep = array();
		foreach($arrShopMaping as $shop => $v){
				
				
			$sumCanCalSales = 0;
			$sumCanCalSalesB = 0;
			$sumCanCalCost = 0;
			$sumCanCalCostB = 0;
			$sumNotCalSales = 0;
			$sumNotCalSalesB = 0;
			$sumNotCalCost = 0;
			$sumNotCalCostB = 0;
			$sumRepair = 0;
				
				
				
				
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
				
				
			foreach($arrDate as $dateToCheck){
					
				$iList = $invOld->getInvoicesByDate($dateToCheck);
					
				foreach($iList as $invRow){
					//get products
					$invNo = $invRow["INV_NO"];
					if($invRow["STATUS"] != "V" ){ // void invoice will not be count
						$proList = $invPro->getInvoiceProducts($invNo);
							
						foreach($proList as $k => $v){
								
							$barCode = trim($v["SCODE"]);
							$isBranded = $pDes->isBranded($barCode);
							if($barCode =="PHONEREPAIR"){
	
								$sumRepair += $v["AMOUNT"];
	
							}
							else{
	
								$idProduct = $pProduct->getProductID($barCode);
								if($idProduct){
									$priceLine = $pPrice->getPrice($idProduct);
									// get price
									//var_dump($priceLine);
									if($priceLine["price_import"] > 0 || $priceLine["price_buy"] > 0  ){
	
										$buyPrice = $priceLine["price_import"];
	
										if($priceLine["id_import_currency"] == 2){
												
											$buyPrice = $buyPrice / self::$extRate;
												
										}
	
										if($priceLine["id_import_currency"] == 3){
	
											$buyPrice = $buyPrice / self::$extRateUs;
	
										}
											
										//$buyPrice = ($priceLine["price_import"] > 0) ? $priceLine["price_import"]/$extRate : $priceLine["price_buy"];
										if($isBranded == "no"){	
										$sumCanCalSales += $v["AMOUNT"];
										$sumCanCalCost += round((int)$v["QTY"]*$buyPrice,2);
										}
										if($isBranded == "yes"){
											$sumCanCalSalesB += $v["AMOUNT"];
											$sumCanCalCostB += round((int)$v["QTY"]*$buyPrice,2);
											
										}
										
									}
									else{
											
										//echo $barCode."<br />";
										if($isBranded == "no"){
										$sumNotCalSales += $v["AMOUNT"];
										}
										
										if($isBranded == "yes"){
										$sumNotCalSalesB += $v["AMOUNT"];
										}
										
										
										$arrNoPrice[] = $barCode;
									}
	
								}
								else{
								if($isBranded == "no"){
										$sumNotCalSales += $v["AMOUNT"];
										}
										
										if($isBranded == "yes"){
										$sumNotCalSalesB += $v["AMOUNT"];
										}
									//echo $barCode."<br />";
									$arrNoPro[] = $barCode;
								}
									
	
							}
								
						}
							
					}
				}
					
			}
				
			echo $sumCanCalSales."|";
			echo $sumCanCalSalesB."|";
			echo $sumCanCalCost."|";
			echo $sumCanCalCostB."|";			
			
			echo $sumNotCalSales."|";
			
			echo $sumNotCalSalesB."|";

			echo $sumRepair."<br />";
	
		}
	
		//var_dump(array_unique($arrNoPro));
		echo "<br />";
		//var_dump(array_unique($arrNoPrice));
		$arrNoPrice = array_unique($arrNoPrice);
		array_multisort($arrNoPrice,SORT_ASC);
	
		$pro = new Model_DbTable_Productsva();
	
		foreach($arrNoPrice as $code){
				
			echo $code.",";
				
			echo $pro->getProductNameByCode($code)."<br />";
				
				
		}
	
		$this->view->arrNoPrice = array_unique($arrNoPrice);
		// get all the invoice -> get all sales
	
		// get all barcode
	
		// group barcode   with import price / without  price /  phone repair -> we buy price
	
		// list barcode
	
		// sales -> 80% -> cost no shipment  , sales -> 10 , no buy price ,sales ?% -> phone parts /pre week -> pre month
			
	}	
	
	public function calCostDetailAction(){
		set_time_limit(0);
		
		$shop = $this->_getParam("shop");
		$dateBegin = $this->_getParam("date");
		$dateEnd = Model_DatetimeHelper::adjustDays("add", $dateBegin, 6);
		
		$pProduct = new Model_DbTable_Productsva(Zend_Registry::get('db_real'));
		$pPrice = new Model_DbTable_Products_Price_Prices(Zend_Registry::get('db_real'));
		
		//$extRate = 5.59;
		
		$arrDate = Model_DatetimeHelper::createDateArray($dateBegin, $dateEnd); 
		
		$arrNoPro = array();
		$arrNoPrice = array();
		
		$invOld = "";
		$proNew = "";
		
		$arrCanCal = array();
		$arrNotCal = array();
		
		$arrShopMaping = array(
				"ADPC" =>"AD",
				"BBPC" =>"BB",
				"BHPC" =>"BH",
				"BSPC" =>"BS",
				"BSIC" =>"BSIC",
				"BSXP" =>"BSXP",
				"CBPC" =>"CB",
				"CLPC" =>"CL",
				"CLIC" =>"CLIC",
				"CSIC" =>"CSIC",
				"DCIC" =>"DCIC",
				"EPPC" =>"EP",
				"FGIC" =>"FGIC",
				"HPIC" =>"HPIC",
				"KCPC" =>"KC",
				"NLPC" =>"NL",
				"PMPC" =>"PM",
				"PMIC" =>"PMIC",
				"SLIC" =>"SLIC",
				"WBPC" =>"WB",
				"WBIC" =>"WBIC",
				"WGPC" =>"WGPC",
				"WGIC" =>"WGIC",
				"WLIC" =>"WLIC",
		);
		
		
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
			
		
		foreach($arrDate as $dateToCheck){
				
			$iList = $invOld->getInvoicesByDate($dateToCheck);
				
			foreach($iList as $invRow){
				//get products
				$invNo = $invRow["INV_NO"];
				if($invRow["STATUS"] != "V" ){ // void invoice will not be count
					$proList = $invPro->getInvoiceProducts($invNo);
						
					foreach($proList as $k => $v){
							
						$barCode = trim($v["SCODE"]);
						if($barCode =="PHONEREPAIR"){
		
							//$sumRepair += $v["AMOUNT"];
							
						}
						else{
		
							$idProduct = $pProduct->getProductID($barCode);
							if($idProduct){
								$priceLine = $pPrice->getPrice($idProduct);
								// get price
								//var_dump($priceLine);
								if($priceLine["price_import"] > 0 || $priceLine["price_buy"] > 0  ){
										
								$buyPrice = $priceLine["price_import"];
										
									if($priceLine["id_import_currency"] == 2){
											
											$buyPrice = $buyPrice / self::$extRate;
											
										}
										
										if($priceLine["id_import_currency"] == 3){
												
											$buyPrice = $buyPrice / self::$extRateUs;
												
										}
										
									//$sumCanCalSales += $v["AMOUNT"];
									//$sumCanCalCost += round((int)$v["QTY"]*$buyPrice,2);
									$key = array_search(trim($barCode),$arrCanCal,true);
									if($key!== false){
										$arrCanCal[$key + 2] +=$v["QTY"]; 
										$arrCanCal[$key + 3] +=$v["AMOUNT"];
									}
									else{
										$arrCanCal[] = $barCode; // barcode
										$arrCanCal[] = $v["PRICE"];//unit price 
										$arrCanCal[] = $v["QTY"];// qty
										$arrCanCal[] = $v["AMOUNT"];//total actual
										$arrCanCal[] = $buyPrice;
										$arrCanCal[] = $priceLine["price_import"]; 
										       	
									}
								}
								else{
										
									//echo $barCode."<br />";
									//$sumNotCalSales += $v["AMOUNT"];
									//$arrNoPrice[] = $barCode;
								}
		
							}
							else{
								//$sumNotCalSales += $v["AMOUNT"];
								//echo $barCode."<br />";
								//$arrNoPro[] = $barCode;
							}
								
		
						}
							
					}
						
				}
			}
				
		}
		
		$arrCanCal = array_chunk($arrCanCal, 6);
		
		$this->view->arrCanCal = $arrCanCal;
		
		
	}
	
	public function lastEightWeeksSalesAction(){
		
		$beginSunday = Model_DatetimeHelper::adjustWeeks("sub",Model_DatetimeHelper::getLastWeekSunday(),7);
		$beginMonday = Model_DatetimeHelper::adjustWeeks("sub",Model_DatetimeHelper::getLastWeekMonday(),7);
		
		$caClosing = new Model_DbTable_Cashaccountclosing();
		
		//$caClosing-
		
		$arrSales = array();
		$shopArr = array("ADPC","BBPC","BHPC","BSPC","BSIC","CBPC","CLPC","EPPC","EPIC","KCPC","NLPC","PMPC","SLIC","WBPC","WBIC","WLIC","WGIC","WGPC","CSIC","CLIC","DCIC","PMIC","BSXP","FGIC","HPIC");
		
			foreach($shopArr as $shopCode){
				$tmpArr = array();
				//$tmpArr[] = Model_DatetimeHelper::adjustWeeks("add",$beginMonday,$i)." to ".Model_DatetimeHelper::adjustWeeks("add",$beginSunday,$i);
				$tmpArr[] = $shopCode;
				for($i=0; $i<8; $i++){
					
					$tmpArr[] = $caClosing->getWeeklyAPOSSummaryShop($shopCode, Model_DatetimeHelper::adjustWeeks("add",$beginMonday,$i) , Model_DatetimeHelper::adjustWeeks("add",$beginSunday,$i));
				}
				$arrSales[] = $tmpArr;
			}
			
		$this->view->arrSales = $arrSales;
		$this->view->shopArr = $shopArr;
		
		$this->view->beginMonday = $beginMonday;
		$this->view->beginSunday = $beginSunday;
		
		//<>var_dump($arrSales);
		
	}
	

	public function repairRankingAction(){
	
		$shopArr = array("ADPC","BBPC","BHPC","BSPC","BSIC","CBPC","CLPC","EPPC","KCPC","NLPC","PMPC","SLIC","WBPC","WBIC","WLIC","WGIC","WGPC","CSIC","CLIC","DCIC","PMIC","BSXP","FGIC","HPIC");
		
		$lastMonday = Model_DatetimeHelper::getLastWeekMonday();
		$lastSunday = Model_DatetimeHelper::getLastWeekSunday();
		
		$beginMonday = Model_DatetimeHelper::adjustWeeks("sub", $lastMonday,1);
		$beginSunday = Model_DatetimeHelper::adjustWeeks("sub", $lastSunday,1);
		
		$caClosing = new Model_DbTable_Cashaccountclosing(Zend_Registry::get('db_real'));
		
		$invOld = "";
		$invPro = "";
		
		$arrRes = array();
		$amtRes = array();
		
		$arrTotal = array();
		
		foreach($shopArr as $shop){
			
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
			
			
			$repairAmt = $invPro->getRepairTotalByDate($lastMonday, $lastSunday);
			$totalAmt = $caClosing->getWeeklyAPOSSummaryShop($shop, $lastMonday, $lastSunday);
			
			$repairAmtWeekAgo = $invPro->getRepairTotalByDate($beginMonday, $beginSunday);
			$totalAmtWeekAgo = $caClosing->getWeeklyAPOSSummaryShop($shop, $beginMonday, $beginSunday);

			$tmpArr = array($shop,$repairAmt,$repairAmt/$totalAmt,$repairAmtWeekAgo,$repairAmtWeekAgo/$totalAmtWeekAgo);
			$arrRes[] = $tmpArr;
			$amtRes[] = $repairAmt;
			$arrTotal[] = $totalAmt;
			
			
		}
		
		array_multisort($amtRes,SORT_DESC,$arrRes);
		array_multisort($arrTotal,SORT_DESC,$shopArr);
		
		$this->view->arrRes = $arrRes;
		$this->view->shopArr = $shopArr;
		
		$this->view->strLastWeek = $lastMonday." - ".$lastSunday;
		$this->view->strWeekAgo = $beginMonday." - ".$beginSunday;
		
		//var_dump($arrRes);
	}
	
	public function calBundleDealShopAction(){
		
		$shop = $this->_getParam("shop");
		$counter = 3;
		$dateBegin = "2013-10-15";
		$dateEnd = "2013-11-14";
		
		$invPro = Model_Aposinit::initAposInvPro($shop);
		$inv = Model_Aposinit::initAposInvoice($shop);
		
		
		$invList = $invPro->getBundleDealInvoice($dateBegin, $dateEnd,$invPro::$_tbNames, $counter);
		
		//var_dump($invList);
		
		echo "<table>";
		foreach($invList as $invoice){
			
			$invLine = $inv->getInvoice($invoice["INV_NO"]);
			$salesMan = $invLine["SAL_CODE"];
			echo  "<tr><td>".$invoice["INV_NO"]."</td><td>".$shop."</td><td>".$salesMan."</td></tr>";
		}	
		echo "</table>";
		echo "Total Count:".count($invList);
		echo "<br />";
		
	}
	
	public function shopStockAndLocationAction(){
		
		$stk = Model_Aposinit::initProStock("WBIC");
		$des = Model_Aposinit::initProDes();
		$loc = new Model_Products_Location(Zend_Registry::get('db_real'));
		$stkList = $stk->listShopHave("WBIC");
		
		$arrRes = array();
		foreach($stkList as $item){
			
			$desLine = $des->getAposDes($item['SCODE']);
			$locLine = $loc->getLocationsByProduct($item['SCODE']);
			$arrLoc = "";
			if($locLine){
			foreach ($locLine as $locItem){
				
				if($locItem['type_location'] == "PL"){
				 $arrLoc = $locItem['code_location'];
				}
			}
			}
			//d($locLine);
			$arrRes[] = array($item['SCODE'],$desLine['DESCRIPT'],$arrLoc);
			
		}
		//d($arrRes);
		$this->view->arrRes = $arrRes;
		
	}
	
	
	private function whichCircle($dateToCheck){
		
		$intDate = Model_DatetimeHelper::tranferToInt($dateToCheck);
		
		foreach(self::$targetCycle as $key => $v){
			
			$intBegin = Model_DatetimeHelper::tranferToInt($v[0]);
			$intEnd = Model_DatetimeHelper::tranferToInt($v[1]);
			
			if($intDate >= $intBegin && $intDate <= $intEnd ){
				
				return $v;
				
			}
			
			
		}
		
		return false;
		
	}
	
	
}
?>