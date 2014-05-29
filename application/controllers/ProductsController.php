<?php 
/**

 */
class ProductsController extends Zend_Controller_Action
{
	
	protected static $arrShopMaping; 
	
	protected  static $arrbarCodeFilter = array("INSTALL","INSTALLATION","PHONEREPAIR","CN","SERVICE","INSTALL","PARTSSALES","OFF-DUTY-OFFLINE","ON-DUTY-OFFLINE","FAULTY","VOUCHER");
	//$bcFilter = array("INSTALL","INSTALLATION","STRAP-","DELITEM","STA-","FAULTY","VOUCHER","PHONEREPAIR");
	
	
    public function init(){
    /**
	 *
	 *
	 */    
	self::$arrShopMaping = unserialize(ARR_APOS_SHOP_MAPPING);
		}

	/**
	 * Entry page of products
	 */	
    public function indexAction(){
	
		//echo "Watch Dog Controller";	    
		}
	/**
	 * @todo warehouse in function 
	 */
	public function warehouseInAction(){
		
		}
	/**	
	 * @todo warehoue in with GN code 
	 */
	public function printWarehouseInAction(){
		$smove = New Model_DbTable_Stockmovement();
		if(isset($_GET['date'])){
		$this->view->smove = $smove->getWarehouseInByDate(trim($_GET['date']));	
		}
		else{
		$this->view->smove = $smove->getWarehouseInToday();		
		}
	}	
		
	public function warehouseOutAction(){
		
		}
	public function goodsInAction(){
		
		}
		
	public function salesLastWeekMainAction(){
		}

	public function emailSalesLastWeekAction(){
		set_time_limit(0);
		$cont = file_get_contents('http://192.168.1.124/products/last-week-sales-stock-old/shop/ADPC/email/yes');
		$cont .="<br />";
		$cont .=  file_get_contents('http://192.168.1.124/products/last-week-sales-stock-old/shop/BBPC/email/yes');
		$cont .="<br />";
		$cont .=  file_get_contents('http://192.168.1.124/products/last-week-sales-stock-old/shop/BHPC/email/yes');
		$cont .="<br />";
		$cont .=  file_get_contents('http://192.168.1.124/products/last-week-sales-stock-old/shop/BSIC/email/yes');
		$cont .="<br />";
		$cont .=  file_get_contents('http://192.168.1.124/products/last-week-sales-stock-old/shop/BSPC/email/yes');
		$cont .="<br />";
		$cont .=  file_get_contents('http://192.168.1.124/products/last-week-sales-stock-old/shop/CLPC/email/yes');
		$cont .="<br />";
		$cont .=  file_get_contents('http://192.168.1.124/products/last-week-sales-stock-old/shop/EPPC/email/yes');
		$cont .="<br />";
		$cont .=  file_get_contents('http://192.168.1.124/products/last-week-sales-stock-old/shop/KCPC/email/yes');
		$cont .="<br />";
		$cont .=  file_get_contents('http://192.168.1.124/products/last-week-sales-stock-old/shop/NLPC/email/yes');
		$cont .="<br />";
		$cont .=  file_get_contents('http://192.168.1.124/products/last-week-sales-stock-old/shop/PMPC/email/yes');
		$cont .="<br />";
		$cont .=  file_get_contents('http://192.168.1.124/products/last-week-sales-stock-old/shop/SLIC/email/yes');
		$cont .="<br />";
		$cont .=  file_get_contents('http://192.168.1.124/products/last-week-sales-stock-old/shop/WBIC/email/yes');
		$cont .="<br />";
		$cont .=  file_get_contents('http://192.168.1.124/products/last-week-sales-stock-old/shop/WBPC/email/yes');
		$cont .="<br />";
		$cont .=  file_get_contents('http://192.168.1.124/products/last-week-sales-stock-old/shop/WGIC/email/yes');
		$cont .="<br />";
		$cont .=  file_get_contents('http://192.168.1.124/products/last-week-sales-stock-old/shop/WGPC/email/yes');				
		$cont .="<br />";
		$cont .=  file_get_contents('http://192.168.1.124/products/last-week-sales-stock-old/shop/WLIC/email/yes');
		$cont .="<br />";
		$cont .=  file_get_contents('http://192.168.1.124/products/last-week-sales-stock-new/shop/BSXP/email/yes');
		$cont .="<br />";
		$cont .=  file_get_contents('http://192.168.1.124/products/last-week-sales-stock-new/shop/CLIC/email/yes');
		$cont .="<br />";
		$cont .=  file_get_contents('http://192.168.1.124/products/last-week-sales-stock-new/shop/CSIC/email/yes');
		$cont .="<br />";
		$cont .=  file_get_contents('http://192.168.1.124/products/last-week-sales-stock-new/shop/DCIC/email/yes');
		$cont .="<br />";
		$cont .=  file_get_contents('http://192.168.1.124/products/last-week-sales-stock-new/shop/FGIC/email/yes');
		$cont .="<br />";
		$cont .=  file_get_contents('http://192.168.1.124/products/last-week-sales-stock-new/shop/HPIC/email/yes');
		$cont .="<br />";
		$cont .=  file_get_contents('http://192.168.1.124/products/last-week-sales-stock-new/shop/PMIC/email/yes');
		$cont .="<br />";
		$cont .=  file_get_contents('http://192.168.1.124/products/last-week-sales-stock-new/shop/CBPC/email/yes');
		$cont .="<br />";
		echo $cont;
		echo "<hr />";
		echo "ALL DONE";
		
	}	
	public function lastWeekSalesStockOldAction(){
		
		$email = $this->_getParam("email");
		
		
		$fh = new Model_Fileshandler();
		$arrFinal = array();
		
		$lastMonday = Model_DatetimeHelper::getLastWeekMonday("");
		
		$extOrder = new Model_DbTable_Products_Stock_Extraorder();
		$lastFriday = Model_DatetimeHelper::adjustDays("add", $lastMonday,4);
		
		
		
		$arrMatching = array(
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
				 "WGIC" =>"WGIC"
				);
		$shop = $this->_getParam("shop");
		
		$slFolder = SALES_LINE_FOLDER;
		$skFolder = STOCK_FOLDER;
		
		$salesOldFileName = "SALESLINEOLD".$lastMonday;
		$stockOldFileName = "R470OLD".$lastMonday;
		if($shop != ""){
		
			$resultArr = $fh->calSalesLastWeek(getcwd().$slFolder."/".$salesOldFileName,$arrMatching[$shop]);
			$resultStock = $fh->getLastWeekStockOld(getcwd().$skFolder."/".$stockOldFileName, $shop);	
			
			$eList = $extOrder->listByShop($shop,$lastFriday);
			//var_dump($eList);
			
			$whStock = $fh->getLastWeekStockOldWh(getcwd().$skFolder."/".$stockOldFileName);
			
			foreach($resultArr as $k => $v){
					
				$key = (int) array_search($v[0],$resultStock);
				$tmpArr = $v;
				$tmpArr[] = $resultStock[$key + 1];
				$key2 = (int) array_search($v[0], $whStock);
				$tmpArr[] = $whStock[$key2 +1];
				$tmpArr[] = " ";
				$arrFinal[] = $tmpArr;
		
			}
			
			foreach($eList as $k2 => $v2){
				//echo $v2["barcode"];
				//var_dump($resultStock);
				//echo "<br />";
				echo $key = (int) array_search(trim($v2["barcode"]),$resultStock);
				echo $key2 = (int) array_search(trim($v2["barcode"]), $whStock);
				$tmpArr = array($v2["barcode"],$v2["description"],$shop,"X",$resultStock[$key + 1],$whStock[$key2 +1]);
				if($v2["stop"]){
				$tmpArr[] = "X";	
				}
				else{
					$tmpArr[]= $v2["qty"];
				}
				
				$arrFinal[] = $tmpArr;
			}
			
		}
		//var_dump($arrFinal);
		
		$qty = array();
		foreach($arrFinal as $key => $row){
			$qty[$key] = $row[0];
		}
		
		array_multisort($qty,SORT_ASC,$arrFinal);
		
		$this->view->arrfinal = $arrFinal; 
		
		$lm = Model_DatetimeHelper::getLastWeekMonday();
		$ls = Model_DatetimeHelper::getLastWeekSunday();

		$fn = $shop."-WK-SALES-".$lm."-TO-".$ls;
		
		$fh->exportSalesShop($arrFinal,$fn);

		$mail = new Model_Emailshandler();
		$subject = $shop." Weekly Sales Data ".$lm." to ".$ls;
		$mailbody = "Please Download the attachment";
		$sh = new Model_DbTable_Shoplocation();
		$shopEmail = $sh->getShopMailByHead($shop);
		$receiver = $shopEmail;
		$receiver2 = "jeffrey.zhang@phonecollection.com.au";
		//$receiver2 = "eco1@phonecollection.com.au";
		if(trim($email)=="yes"){
		$mail->sendAttachEmail($receiver, $subject, $mailbody, getcwd()."/export/sales/",$fn.".xls");
		
		$mail->sendAttachEmail($receiver2, $subject, $mailbody, getcwd()."/export/sales/",$fn.".xls");
		}
		echo "EMAILDONE";
	}	
		
	public function lastWeekSalesStockNewAction(){
	
	
		$fh = new Model_Fileshandler();
		$arrFinal = array();
		$email = $this->_getParam("email");
		
	
		$lastMonday = Model_DatetimeHelper::getLastWeekMonday("");
		
		$extOrder = new Model_DbTable_Products_Stock_Extraorder();
		$lastFriday = Model_DatetimeHelper::adjustDays("add", $lastMonday,4);
		
	
		$arrMatching = array(
			"BSXP" => "BSXP",
			"CLIC" => "CLIC",
			"CSIC" => "CSIC",
			"DCIC" => "DCIC",
			"FGIC" => "FGIC",
			"HPIC" => "HPIC",
			"PMIC" => "PMIC",
			"CBPC" => "CB"	
		);
		$shop = $this->_getParam("shop");
	
		$slFolder = SALES_LINE_FOLDER;
		$skFolder = STOCK_FOLDER;
	
		$salesNewFileName = "SALESLINENEW".$lastMonday;
		$stockNewFileName = "R470NEW".$lastMonday;
		$stockOldFileName = "R470OLD".$lastMonday;
		
		if($shop != ""){
	
			$resultArr = $fh->calSalesLastWeek(getcwd().$slFolder."/".$salesNewFileName,$arrMatching[$shop]);
			$resultStock = $fh->getLastWeekStockNew(getcwd().$skFolder."/".$stockNewFileName, $shop);
			$whStock = $fh->getLastWeekStockOldWh(getcwd().$skFolder."/".$stockOldFileName);
			$eList = $extOrder->listByShop($shop,$lastFriday);
				
				
			foreach($resultArr as $k => $v){
					
				$key = (int) array_search($v[0],$resultStock);
				$tmpArr = $v;
				$tmpArr[] = $resultStock[$key + 1];
				$key2 = (int) array_search($v[0], $whStock);
				$tmpArr[] = $whStock[$key2 +1];
				$arrFinal[] = $tmpArr;
	
			}
			
			foreach($eList as $k2 => $v2){
				//echo $v2["barcode"];
				//var_dump($resultStock);
				//echo "<br />";
				echo $key = (int) array_search(trim($v2["barcode"]),$resultStock);
				echo $key2 = (int) array_search(trim($v2["barcode"]), $whStock);
				$tmpArr = array($v2["barcode"],$v2["description"],$shop,"X",$resultStock[$key + 1],$whStock[$key2 +1]);
				if($v2["stop"]){
					$tmpArr[] = "X";
				}
				else{
					$tmpArr[]= $v2["qty"];
				}
			
				$arrFinal[] = $tmpArr;
			}
			
				
		}
		//var_dump($arrFinal);
	
		$qty = array();
		foreach($arrFinal as $key => $row){
			$qty[$key] = $row[0];
		}
	
		array_multisort($qty,SORT_ASC,$arrFinal);
	
		$this->view->arrfinal = $arrFinal;
	
		$lm = Model_DatetimeHelper::getLastWeekMonday();
		$ls = Model_DatetimeHelper::getLastWeekSunday();
	
		$fn = $shop."-WK-SALES-".$lm."-TO-".$ls;
	
		$fh->exportSalesShop($arrFinal,$fn);
	
		$mail = new Model_Emailshandler();
		$subject = $shop." Weekly Sales Data ".$lm." to ".$ls;
		$mailbody = "Please Download the attachment";
		$sh = new Model_DbTable_Shoplocation();
		$shopEmail = $sh->getShopMailByHead($shop);
		
		$receiver = $shopEmail;
		$receiver2 = "jeffrey.zhang@phonecollection.com.au";
		//$receiver2 = "eco1@phonecollection.com.au";
		
		if(trim($email)=="yes"){
		$mail->sendAttachEmail($receiver, $subject, $mailbody, getcwd()."/export/sales/",$fn.".xls");
		
		$mail->sendAttachEmail($receiver2, $subject, $mailbody, getcwd()."/export/sales/",$fn.".xls");
		}
	}

	public function lastWeekDiscountOldAction(){
		
		$fh = new Model_Fileshandler();
		$policy = new Model_Policy();
		$arrFinal = array();
		$arrCase = $fh->readCaseList(getcwd()."/import/caselist.csv");
		$lastMonday = Model_DatetimeHelper::getLastWeekMonday("");
		$this->view->dateBegin = Model_DatetimeHelper::getLastWeekMonday();
		$this->view->dateEnd = Model_DatetimeHelper::getLastWeekSunday();
		
		
		$arrMatching = array(
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
				"WGIC" =>"WGIC"
		);
		$shop = $this->_getParam("shop");
		$this->view->shophead = $shop;
		
		$slFolder = SALES_LINE_FOLDER;
		$smFolder = SALES_MAN_FOLDER;
		
		$salesOldFileName = "SALESLINEOLD".$lastMonday;	
		$salesManOldFileName = "SALESMANOLD".$lastMonday;
		
		$arrFinal = array();
		if($shop != ""){
			
			$arrSalesMan = $fh->findSalesMan(getcwd().$smFolder."/".$salesManOldFileName,$arrMatching[$shop]);
			$arrFinal = $fh->findDiscountLastWeek(getcwd().$slFolder."/".$salesOldFileName,$arrMatching[$shop],$arrSalesMan);
			
		}
		
		
		$invoice = array();
		$precent = array();
		
		foreach($arrFinal as $key => $v){
			$invoice[$key] = $v[8];
		    if($v[15] > 0){ $precent[$key] = round($v[15]/($v[15] + $v[14]),2)*100; }
	        elseif($v[16] > 0){ $precent[$key] = round($v[16]/$v[14],2)*100; }   
	        else{ $precent[$key] = 0; }
		}
		
		//var_dump($precent);
		array_multisort($precent,SORT_DESC,$arrFinal);
		//$arrFinalTmp = $arrFinal;
		//var_dump(array_keys($arrFinalTmp,array(8=>"L130429003")));
		$arrFinal2 = array();
		$cot = count($arrFinal);
		
		for($i=0;$i<$cot;$i++)
		{
			$inv = $arrFinal[$i][8];
			if($inv!=""){
			foreach($arrFinal as $k2 => $v2){
				if($inv == $v2[8]){
					$arrFinal2[] = $v2;
					$arrFinal[$k2][8]= "";
					}				
				
			}
			}			
		}

				
		$this->view->arrFinal = $arrFinal2;
		
		$arrFinal3 = array();
		$oldInv = "";
		$oldArray = array();
		foreach($arrFinal2 as $k3 => $v3){
			$tmpArr = $v3;
			if($v3[8] != $oldInv){
				$oldArray = array();
				$oldArray[] = $v3;
				$oldInv = $v3[8];
				//	echo $oldInv;
			}
			else{
				$oldArray[] = $v3;
			}
			//var_dump($arrFinal2[$k3 + 1][8],$oldInv);
			echo "<br />";
			if($arrFinal2[$k3 + 1][8] != $oldInv){
				//	var_dump($oldArray);
				echo "<br />";
				$policyRes = $policy->calDiscount($oldArray,$arrCase);
				$tmpArr[] = $policyRes;
		
			}
			$arrFinal3[]= $tmpArr;
		}
		
		
		$this->view->arrFinal = $arrFinal3;
				
	}
	public function lastWeekDiscountAllAction(){
		
	}
	public function lastWeekDiscountNewAction(){

		$fh = new Model_Fileshandler();
		$arrCase = $fh->readCaseList(getcwd()."/import/caselist.csv");
		$policy = new Model_Policy();
		$arrFinal = array();
		
		$lastMonday = Model_DatetimeHelper::getLastWeekMonday("");
		$this->view->dateBegin = Model_DatetimeHelper::getLastWeekMonday();
		$this->view->dateEnd = Model_DatetimeHelper::getLastWeekSunday();
		
		$arrMatching = array(
				"BSXP" => "BSXP",
				"CLIC" => "CLIC",
				"CSIC" => "CSIC",
				"DCIC" => "DCIC",
				"FGIC" => "FGIC",
				"HPIC" => "HPIC",
				"PMIC" => "PMIC",
				"CBPC" => "CB"
		);
		
		$shop = $this->_getParam("shop");
		$this->view->shophead = $shop;
		
		$slFolder = SALES_LINE_FOLDER;
		$smFolder = SALES_MAN_FOLDER;
		
		$salesNewFileName = "SALESLINENEW".$lastMonday;
		$salesManNewFileName = "SALESMANNEW".$lastMonday;

		
		
		$arrFinal = array();
		if($shop != ""){
				
			$arrSalesMan = $fh->findSalesMan(getcwd().$smFolder."/".$salesManNewFileName,$arrMatching[$shop]);
			//var_dump($arrSalesMan);
			$arrFinal = $fh->findDiscountLastWeek(getcwd().$slFolder."/".$salesNewFileName,$arrMatching[$shop],$arrSalesMan);
				
		}		
		$invoice = array();
		$precent = array();
		
		foreach($arrFinal as $key => $v){
			$invoice[$key] = $v[8];
			if($v[15] > 0){
				$precent[$key] = round($v[15]/($v[15] + $v[14]),2)*100;
			}
			elseif($v[16] > 0){
				$precent[$key] = round($v[16]/$v[14],2)*100;
			}
			else{ $precent[$key] = 0;
			}
		}

		array_multisort($precent,SORT_DESC,$arrFinal);

		$arrFinal2 = array();
		$cot = count($arrFinal);
		
		for($i=0;$i<$cot;$i++)
		{
			$inv = $arrFinal[$i][8];
			if($inv!=""){
			foreach($arrFinal as $k2 => $v2){
				if($inv == $v2[8]){
					$arrFinal2[] = $v2;
					$arrFinal[$k2][8]= "";
				}
		
			}
		}
		
		}
		
		$arrFinal3 = array();
		$oldInv = "";
		$oldArray = array();
		foreach($arrFinal2 as $k3 => $v3){
			$tmpArr = $v3;
			if($v3[8] != $oldInv){
				$oldArray = array();
				$oldArray[] = $v3;
				$oldInv = $v3[8];
			}
			else{
				$oldArray[] = $v3;		
			}
			echo "<br />";
			if($arrFinal2[$k3 + 1][8] != $oldInv){
			echo "<br />";
				$policyRes = $policy->calDiscount($oldArray,$arrCase);
				$tmpArr[] = $policyRes;
				
			}			
			$arrFinal3[]= $tmpArr;
		}
		
		
		$this->view->arrFinal = $arrFinal3;
		
	}		
	public function saveGoodsInAction(){

		$products = new Model_DbTable_Productsva();
		$pStocks = new Model_DbTable_Productstock();
		$pMoves = new Model_DbTable_Stockmovement();
		
		$productCode = "";
		$productQty = 0;
		
		foreach($_POST['upload'] as $key => $value){
		
			$productCode = "";
			$productQty = 0;
		
			$arrtmp = explode("[",$value);
				
			$productCode = $arrtmp[0];
			$qty = (int)trim(str_replace("]","",$arrtmp[1]));
		
			//if not in the datebase then add into product database
				
			if(!$products->ifExist($productCode)){
		
				$idProduct = $products->addProduct($productCode,"");
				$pStocks->insertStockEcommerce($idProduct, $qty);
				//TI (code 3)
				$pMoves->addMovement(1, $idProduct, $qty);
			}
			else{
				//item already EXIST
				$idProduct = $products->getProductID($productCode);
				$pStocks->increaseStockEcommerce($idProduct, $qty);
				$pMoves->addMovement(1, $idProduct, $qty);
		
			}
				
				
		}
		
		}
		public function saveGoodsOutAction(){
		
			$products = new Model_DbTable_Productsva();
			$pStocks = new Model_DbTable_Productstock();
			$pMoves = new Model_DbTable_Stockmovement();
		
			$productCode = "";
			$productQty = 0;
		
			foreach($_POST['upload'] as $key => $value){
		
				$productCode = "";
				$productQty = 0;
		
				$arrtmp = explode("[",$value);
		
				$productCode = $arrtmp[0];
				$qty = (int)trim(str_replace("]","",$arrtmp[1]));
		
				//if not in the datebase then add into product database
		
				if(!$products->ifExist($productCode)){
		
					//$idProduct = $products->addProduct($productCode,"");
					//$pStocks->insertStockEcommerce($idProduct, $qty);
					//TI (code 3)
					//$pMoves->addMovement(1, $idProduct, $qty);
				}
				else{
					//item already EXIST
					$idProduct = $products->getProductID($productCode);
					$pStocks->descreaseStockEcommerce($idProduct, $qty);
					//$pStocks->increaseStockEcommerce($idProduct, $qty);
					$pMoves->addMovement(2, $idProduct, $qty);
		
				}
		
		
			}
		
		}		
	public function goodsOutAction(){

				
		}
	/**
	 * warehouse batch import products into system 
	 */

	/**
	 * This is the main page Link to all saving date function
	 */	
		
	public function importAction(){
		
		$this->_helper->_layout->setLayout('layout3');
		$slFolder = SALES_LINE_FOLDER;
		$skFolder = STOCK_FOLDER;
		$smFolder = SALES_MAN_FOLDER;
		
		$lastMonday = Model_DatetimeHelper::getLastWeekMonday("");
		
		$salesOldFileName = "SALESLINEOLD".$lastMonday;
		$salesNewFileName = "SALESLINENEW".$lastMonday; 
		
		$stockOldFileName = "R470OLD".$lastMonday;
		$stockNewFileName = "R470NEW".$lastMonday;
		
		$salesManOldFileName = "SALESMANOLD".$lastMonday;
		$salesManNewFileName = "SALESMANNEW".$lastMonday;
		
		
		if(file_exists(getcwd()."/".$slFolder."/".$salesOldFileName)){
			$this->view->slOldExist = true;
		}
		
		if(file_exists(getcwd()."/".$slFolder."/".$salesNewFileName)){
			$this->view->slNewExist = true;
		}		
	
		if(file_exists(getcwd().$skFolder."/".$stockOldFileName)){
			$this->view->skOldExist = true;
		}
		
		if(file_exists(getcwd().$skFolder."/".$stockNewFileName)){
			$this->view->skNewExist = true;
		}
		
		if(file_exists(getcwd().$smFolder."/".$salesManOldFileName)){
			$this->view->smOldExist = true;
		}
		
		if(file_exists(getcwd().$smFolder."/".$salesManNewFileName)){
			$this->view->smNewExist = true;
		}			

	}
	public function importChinaOrderAction(){
		
		$imFolder = "/import/china/";
		$cpatch = getcwd();
		$fileName = "";
		
		
		$fl = new Model_Fileshandler();
		$boxNo = "A";
		$spCode = "C";
		$qty = "E";
		$comment = "F";
		$recNo = "H";
		$proCode = "I";
		$cot = 0;
		$shipDetail = new Model_DbTable_Order_Shippingdetail();
		//$shipNo = $shipDetail->getShippingNo();
		$shipment = new Model_DbTable_Order_Shipment();
		
		
		
		if($_POST){
			
			if(move_uploaded_file($_FILES['order_file']['tmp_name'], $cpatch.$imFolder.$_FILES['order_file']['name'])){
				
				$fileName = $_FILES['order_file']['name'];
				
				$pos1 = strpos($fileName,"[");
				$pos2 = strpos($fileName,"]");
				
				$str = substr($fileName,$pos1+1,$pos2-$pos1-1);
				
				$arrShipment = explode("-",$str);
				$date = explode(".",$arrShipment[0]);
				$dateShip = $date[2]."-".$date[1]."-".$date[0];
				$dateEta = Model_DatetimeHelper::adjustWeeks("add", $dateShip,1);
				
				
				$nameCourier = $arrShipment[1];
				$trackingNo = $arrShipment[2];
				
				$id = $shipment->addShipment($nameCourier, $trackingNo, $dateShip, $dateEta,null,0, "");
						
				$arrOrders = $fl->readChinaOrder($cpatch.$imFolder.$fileName);
				
				foreach($arrOrders as $oLine){
					if($cot >0 && $oLine[$proCode]!="" && $oLine[$qty] !=""){
						$shipDetail->addShippingdetail($id,$oLine[$proCode],$oLine[$qty],0, $oLine[$boxNo], $oLine[$spCode], $oLine[$recNo], $oLine[$comment]);
					}
					$cot++;
				}	
						
			}	
			
		}
				
		echo "Done , You may close the window now";
			
	}
	
	/**
	 * This is is import the stock from OLD APOS  to cover the WH Stock and Total Stock for OLD 
	 */
	public function importStockOldAction(){

		$this->_helper->_layout->setLayout('layout3');
		$lastMonday = Model_DatetimeHelper::getLastWeekMonday("");
		
		$tmpFileName = "R470OLD".$lastMonday;
		$tmpFolder = STOCK_FOLDER;
		
		$pStock = new Model_DbTable_Productstock();
		$pProduct = new Model_DbTable_Productsva();
		
		$fh = new Model_Fileshandler();
		
		if($_POST){
			
			if(move_uploaded_file($_FILES["r470_old"]["tmp_name"],getcwd().$tmpFolder."/".$tmpFileName)){
				
				$arrResult = $fh->importStockR470Csv(getcwd().$tmpFolder."/".$tmpFileName);
				
				foreach($arrResult as $k => $v){
					//var_dump($v);	
					$codeProduct = $v[0];
					$titleProduct = $v[1];
					$whStock = $v[2];
					$oldShopStock = $v[3];
					if(!$pProduct->ifExist($codeProduct)){
					
						$idProduct = $pProduct->addProduct($codeProduct,$titleProduct);
						$pStock->insertStockWarehouse($idProduct, $whStock);
						$pStock->updateOldShopStock($idProduct, $oldShopStock);
					}
					else{
							
						$idProduct = $pProduct->getProductID($codeProduct);
						$pProduct->updateProductTitle($idProduct,$titleProduct);
						$pStock->updateStockWarehouse($idProduct, $whStock);
						$pStock->updateOldShopStock($idProduct, $oldShopStock);
							
					}					
				}		
			}
			
		$this->view->message ="R470 File For OLD Shop UPLOADED";
			
		}
		
		
	}
	/**
	 * This will import APOS NEW Total Stock , it will add up into the Total Stock of the shop 
	 */
	public function importStockNewAction(){
		
		$this->_helper->_layout->setLayout('layout3');
		$lastMonday = Model_DatetimeHelper::getLastWeekMonday("");
		
		$tmpFileName = "R470NEW".$lastMonday;
		$tmpFolder = STOCK_FOLDER;
		
		$pStock = new Model_DbTable_Productstock();
		$pProduct = new Model_DbTable_Productsva();
		
		$fh = new Model_Fileshandler();
		
		if($_POST){
				
			if(move_uploaded_file($_FILES["r470_new"]["tmp_name"],getcwd().$tmpFolder."/".$tmpFileName)){
		
				$arrResult = $fh->importStockR470NewCsv(getcwd().$tmpFolder."/".$tmpFileName);
		
				foreach($arrResult as $k => $v){
					//var_dump($v);
					$codeProduct = $v[0];
					$titleProduct = $v[1];
					$newShopStock = $v[2];
					if(!$pProduct->ifExist($codeProduct)){
							
						$idProduct = $pProduct->addProduct($codeProduct,$titleProduct);
						$pStock->insertStockWarehouse($idProduct, 0);
						$pStock->updateNewShopStock($idProduct, $newShopStock);
					}
					else{							
						$idProduct = $pProduct->getProductID($codeProduct);
						$pStock->updateNewShopStock($idProduct, $newShopStock);							
					}
				
				}
				
			}
				
			$this->view->message ="R470 File For OLD Shop UPLOADED";
				
		}		
	}
	
	public function importSalesManOldAction(){
		$smFolder = SALES_MAN_FOLDER;
		$lastMonday = Model_DatetimeHelper::getLastWeekMonday("");
		$salesManFile = "SALESMANOLD".$lastMonday;
		echo $salesManFile;
		
		if($_POST){
		
			if(move_uploaded_file($_FILES["sales_man_old"]["tmp_name"],getcwd()."/".$smFolder."/".$salesManFile)){
		
				$this->_helper->redirector("import");
			}
			else{
				$this->errorMessage = "File Upload Fail";
			}
		}		
		
	}
	public function importSalesManNewAction(){
		
		$smFolder = SALES_MAN_FOLDER;
		$lastMonday = Model_DatetimeHelper::getLastWeekMonday("");
		$salesManFile = "SALESMANNEW".$lastMonday;
		echo $salesManFile;
		
		if($_POST){
		
			if(move_uploaded_file($_FILES["sales_man_new"]["tmp_name"],getcwd().$smFolder."/".$salesManFile)){
		
				$this->_helper->redirector("import");
			}
			else{
				$this->errorMessage = "File Upload Fail";
			}
		}		
	}
	public function importSalesOldAction(){
		
		//import and rename file 
		$slFolder = SALES_LINE_FOLDER;
		$lastMonday = Model_DatetimeHelper::getLastWeekMonday("");
		$salesFileName = "SALESLINEOLD".$lastMonday;
		echo $salesFileName;
		if($_POST){
		
			if(move_uploaded_file($_FILES["sales_line_old"]["tmp_name"],getcwd()."/".$slFolder."/".$salesFileName)){

				$this->_helper->redirector("import");
			}
			else{
				$this->errorMessage = "File Upload Fail";
			}
		}
			
	}	
	
	public function importSalesNewAction(){

		$slFolder = SALES_LINE_FOLDER;
		$lastMonday = Model_DatetimeHelper::getLastWeekMonday("");
		$salesFileName = "SALESLINENEW".$lastMonday;
		echo $salesFileName;
		
		if($_POST){
		
			if(move_uploaded_file($_FILES["sales_line_new"]["tmp_name"],getcwd()."/".$slFolder."/".$salesFileName)){
		
				$this->_helper->redirector("import");
			}
			else{
				$this->errorMessage = "File Upload Fail";
			}
		}		
	}
	
	
	
	
	
	public function whbatchimportAction(){
		if($_POST)
		{
			
			
			
		$tmpFolder = getcwd()."/tmp/";
		$tmpfileName ="stock.csv";
		
		if(move_uploaded_file($_FILES["file_upload"]["tmp_name"], $tmpFolder.$tmpfileName)){
		
		$counter = 0;
		
		$products = new Model_DbTable_Productsva();
		$pStocks = new Model_DbTable_Productstock();

		$productCode = "";
		$productQty = 0;
		$productTitle = "";
		$fl = fopen($tmpFolder.$tmpfileName,"r");
		
		while(($lineData = fgetcsv($fl))!= FALSE){
		
		if($counter >0){
			
			$productCode = trim($lineData[0]);
			$productTitle = $lineData[2];
			
			$productTitle = str_replace("**", "", $productTitle);
			$productTitle = str_replace("#B", "", $productTitle);
			$productTitle = str_replace("#E", "", $productTitle);
			$productTitle = str_replace("#C", "", $productTitle);
			$productTitle = trim($productTitle);
			
			$productQty = trim($lineData[23]);
			
			
		if(!$products->ifExist($productCode)){
				
				$idProduct = $products->addProduct($productCode,$productTitle);
				$pStocks->insertStockWarehouse($idProduct, $productQty);
				
				
			}	
		else{
			
			$idProduct = $products->getProductID($productCode);
			$products->updateProductTitle($idProduct, $productTitle);
			$pStocks->updateStockWarehouse($idProduct, $productQty);		
			
			}
		}
		$counter ++;	

		
		}	
		
		}	
		
		}
	
	}	
	/**
	 * @todo check if the function is ready 
	 */	
	public function batchGoodsOutAction(){
		$tmpFolder = getcwd()."/tmp/";
		$tmpfileName ="tmpfile.csv"; 
		$counter = 1;
		$tableString = "";
		if($_POST){
			// get files 
			if(move_uploaded_file($_FILES["upload_file"]["tmp_name"], $tmpFolder.$tmpfileName)){
			
			//read csv files
				$fl = fopen($tmpFolder.$tmpfileName,"r");
				
				while(($lineData = fgetcsv($fl))!= FALSE){
					
					$tableString .="<tr><td>".$counter."</td>";
					$tableString .="<td>".$lineData[0]."</td>"."<td>".$lineData[1]."</td>"."<td>".$lineData[2]."</td>"."<td>".$lineData[3]."</td>"."<td>".$lineData[4]."</td></tr>";
					$counter ++;
					
				}
				
				$this->view->tablestring = $tableString;
				
			}
			
			
			//check if code exist 
			
			//if yes , change the stock 
			
			//if not only record , put product in the database , make the stock = 0 then record the stock moviement 
			
			// print out which one is not in the stock 
			
			// count . then stock in 
			
			
		}
	}	
	/**
	 * save GO function 
	 */
	public function saveBatchGoodsOutAction(){
		
		$arrIgnore = array("nothinginside"); 
		$arrProducts = array();
		$date = "";
		$counter = 1;
		$productCode="";
		$arrNewProducts = array();
		$dateSelect = "";

		$tmpFolder = getcwd()."/tmp/";
		$tmpfileName ="tmpfile.csv";
		
		$products = new Model_DbTable_Productsva();
		$pStocks = new Model_DbTable_Productstock();
		$pMoves = new Model_DbTable_Stockmovement();
		
		
		
				
		if($_POST){
			$dateSelect = $_POST['upload_date'];
			
			if(trim($_POST['ignore_line'])!=""){
			$arrIgnore = explode(",",$_POST['ignore_line']);
			}
			else{
				$arrIgnore = array("nothinginside"); 
			}
			$fl = fopen($tmpFolder.$tmpfileName,"r");
			
			while(($lineData = fgetcsv($fl))!= FALSE){			
				
				//print_r($arrIgnore);
				//echo "IF";
				//print_r(in_array($counter,$arrIgnore));
				//var_dump(array_search(0,$arrIgnore));
				if(array_search($counter,$arrIgnore)===FALSE){
				
					$arrProducts = explode(",",$lineData[1]);
					foreach($arrProducts as $key => $value){
					
						$productCode = trim($value);
						if(!$products->ifExist($productCode)){
							
							if($productCode!=""){
								
							$idProduct = $products->addProduct($productCode,"");
							$arrNewProducts[]=$idProduct;
							//$pStocks->insertStockEcommerce($idProduct, 0);
							//GO (code 2)
							$pMoves->addMovementDate(2, $idProduct,1, $dateSelect);
								}
							}
						else{
							//item already EXIST
							$idProduct = $products->getProductID($productCode);
							if($pStocks->idExist($idProduct)){
								$pStocks->descreaseStockEcommerce($idProduct,1);
							}
							$pMoves->addMovementDate(2, $idProduct,1, $dateSelect);
						
						}	
						
						
						
					}
					
					
				}
				
			echo "counter=".$counter++;
			//	$counter++;
			}	

			
			$this->view->newinproducts = $arrNewProducts;
			
			}		
			
		}	
		
	public function saveWarehouseInAction(){
		
		$products = new Model_DbTable_Productsva();
		$pStocks = new Model_DbTable_Productstock();
		$pMoves = new Model_DbTable_Stockmovement();
		
		$productCode = "";
		$productQty = 0;
		
		foreach($_POST['upload'] as $key => $value){

			$productCode = "";
			$productQty = 0;
								
			$arrtmp = explode("[",$value);
			
			$productCode = $arrtmp[0];
			$qty = (int)trim(str_replace("]","",$arrtmp[1])); 
		
			//if not in the datebase then add into product database 
			
			if(!$products->ifExist($productCode)){
				
				$idProduct = $products->addProduct($productCode,"");
				$pStocks->insertStockEcommerce($idProduct, $qty);				
				//TI (code 3)
				$pMoves->addMovement(3, $idProduct, $qty);
			}
			else{
			//item already EXIST
				 	$idProduct = $products->getProductID($productCode);	
					$pStocks->increaseStockEcommerce($idProduct, $qty);
					$pMoves->addMovement(3, $idProduct, $qty);
				
			}
			
			
		}

		
		
		}	

		public function saveWarehouseOutAction(){
		
			$products = new Model_DbTable_Productsva();
			$pStocks = new Model_DbTable_Productstock();
			$pMoves = new Model_DbTable_Stockmovement();
		
			$productCode = "";
			$productQty = 0;
		
			foreach($_POST['upload'] as $key => $value){
		
				$productCode = "";
				$productQty = 0;
		
				$arrtmp = explode("[",$value);
					
				$productCode = $arrtmp[0];
				$qty = (int)trim(str_replace("]","",$arrtmp[1]));
		
				//if not in the datebase then add into product database
					
				if(!$products->ifExist($productCode)){
		
					$idProduct = $products->addProduct($productCode,"");
					$pStocks->insertStockEcommerce($idProduct, 0);
					//TO (code 4)
					$pMoves->addMovement(4, $idProduct, $qty);
				}
				else{
					//item already EXIST
					$idProduct = $products->getProductID($productCode);
					$pStocks->descreaseStockEcommerce($idProduct, $qty);
					$pMoves->addMovement(4, $idProduct, $qty);
		
				}
					
					
			}
		
		
		
		}	
		/***********************************************************************
		 * Product and price block 
		 * start 21th March
		 ***********************************************************************/
		/**
		 * list product with price on website?
		 */
		public function listProductsAction(){
			$this->_helper->_layout->setLayout('layout5');			
			$products = new Model_DbTable_Productsva();
			$this->view->pList = $products->listAllProductsStockDetail();
			
		}
		public function saveProductOrderAction(){
			
			$orders = new Model_DbTable_Order_Orderlist();
			$sys = new Model_DbTable_Systemconfig();
			
			$this->_helper->_layout->setLayout('layout3');
			$this->view->strArrProducts = "";
			$this->view->orderList = "";
			$this->view->addNew = true ;
			$this->view->newOrder = "";
			
			if($_POST){
			
				$arrProductId = $_POST["id_product"];
				$strArrProducts = base64_encode(serialize($arrProductId));	
				$this->view->strArrProducts = $strArrProducts;
				
				if(isset($_POST["btn_new_order"])){

					$this->view->newOrder = $sys->runOrderCounter();
					//do nothing at the moment
				}	
				if(isset($_POST["btn_add_to_order"])){
					
					$oList = $orders->listAllUnFinallizedOrder();
					$this->view->addNew = false ;
					$this->view->orderList = $orders->listAllUnFinallizedOrder();
					
					
					
					
					
				}				
			}			
		}
		/**
		 * list product with price option
		 */
		public function listProductsPriceAction(){
			if(isset($_GET["show"]) && $_GET["show"]=="yes"){
			
			$this->_helper->_layout->setLayout('layout3');
			}
			else{
				
				
				$this->_helper->_layout->setLayout('layout_ext');
			}
			
			$this->view->pList = array();
			
			if($_GET){
				if($_GET["token"]=="031788"){
					
					$products = new Model_DbTable_Productsva();
					$pPrices = new Model_DbTable_Products_Price_Prices(); 
					$pList = $products->listAllProductsStockPrice();
			
					$this->view->pList = $pList;
				}
			}
			

		}
		
		public function saveWholeSalePriceAction(){
			
			$pPrice = new Model_DbTable_Products_Price_Prices();
			
			if($_POST){
				
				foreach($_POST as $key =>$value){
					
					if(substr($key,0,3)=="wsp"){
						
						echo $idProduct = substr($key,3);
						echo $priceWholesaleReal = $value;	
						if(!$pPrice->getPrice($idProduct)){
							$pPrice->addPrice($idProduct,null,null,null,0,Model_DatetimeHelper::dateToday());							
						}						
						$pPrice->updateWholeSalePriceReal($idProduct, $priceWholesaleReal,Model_DatetimeHelper::dateToday());
						$pPrice->updateImportPrice($idProduct,$_POST["imp"][$idProduct],2,Model_DatetimeHelper::dateToday());
						$pPrice->updateBuyPrice($idProduct,$_POST["wbp"][$idProduct],Model_DatetimeHelper::dateToday());
					}
					
				}
				
			}
			
			$this->_redirect("/products/list-products-price?token=031788&show=yes");	
		}
		
		
		public function listBarcodeAction(){
			
			$products = new Model_DbTable_Productsva();
			$this->view->productslist = $products->listAllProducts();			
		}
		
		/**
		 * temperoary funcion to import the price 
		 */
		public function importpricetmpAction(){
			
			$products = new Model_DbTable_Productsva();
			$pPrice = new Model_DbTable_Products_Price_Prices();
			$margin = new Model_DbTable_Products_Price_Margin();
			
			$fn = "pc2.csv";
			
			$fl = fopen($fn,"r");
			
			while(($ld=fgetcsv($fl,0))!= FALSE){
				
				//echo $ld[0];
				$exist = $products->ifExist($ld[0]);
				if($exist){

					$idProduct = $products->getProductID(trim($ld[0]));
					$havePrice = $pPrice->getPrice($idProduct);
					if(!$havePrice){
						$pPrice->addPrice($idProduct,NULL,NULL,NULL,0,Model_DatetimeHelper::dateToday());
						//$pPrice->addPrice($idProduct,NULL , $idImportCurrency = NULL ,$updateImport = NULL  , $priceBuy , $updateBuy , $priceWholesale = NULL  , $updateWholesale = NULL  , $priceRrp = NULL  , $updateRrp = NULL )
		
					}
					
					$pPrice->updateImportPrice($idProduct, $ld[1],$ld[2],Model_DatetimeHelper::dateToday()); //2 means CNY
					//$priceWholesale = $margin->getMarginByID($idProduct,$ld[2],1);
					//$pPrice->updateWholeSalePrice($idProduct, $priceWholesale,Model_DatetimeHelper::dateToday());
				
				}
				
				
			}

		}

		public function sendDiscountManagerAction(){
			
			$arrInv5= array(); 
			$arrInv3= array();
			$arrRes5 = array();
			$arrRes3 = array();
			
			foreach($_POST["accept"] as $key => $v){
				if($_POST["level"][$v] == 5){
					$arrInv5[] = $_POST["id_invoice"][$v];
				}
			}
			foreach($_POST["accept"] as $key => $v){
				if($_POST["level"][$v] == 3){
					$arrInv3[] = $_POST["id_invoice"][$v];
				}
			}
			
			foreach($_POST["id_invoice"] as $k2 => $v2){
				if(in_array($v2,$arrInv5)){
					$tmpArr =  array();
					$tmpArr[] = $v2;
					$tmpArr[] = $_POST["barcode"][$k2];
					$tmpArr[] = $_POST["des"][$k2];
					$tmpArr[] = $_POST["qty"][$k2];
					$tmpArr[] = $_POST["rrp"][$k2];
					$tmpArr[] = $_POST["dis"][$k2];
					$tmpArr[] = $_POST["dis_amt"][$k2];
					$tmpArr[] = $_POST["final"][$k2];
					$tmpArr[] = $_POST["sales"][$k2];
					$tmpArr[] = $_POST["remark"][$k2];
					if(isset($_POST["level"][$k2])){ $tmpArr[] = $_POST["level"][$k2];}
					$arrRes5[] = $tmpArr;
				}
				if(in_array($v2,$arrInv3)){
					$tmpArr =  array();
					$tmpArr[] = $v2;
					$tmpArr[] = $_POST["barcode"][$k2];
					$tmpArr[] = $_POST["des"][$k2];
					$tmpArr[] = $_POST["qty"][$k2];
					$tmpArr[] = $_POST["rrp"][$k2];
					$tmpArr[] = $_POST["dis"][$k2];
					$tmpArr[] = $_POST["dis_amt"][$k2];
					$tmpArr[] = $_POST["final"][$k2];
					$tmpArr[] = $_POST["sales"][$k2];
					$tmpArr[] = $_POST["remark"][$k2];
					if(isset($_POST["level"][$k2])){
						$tmpArr[] = $_POST["level"][$k2];
					}
					$arrRes3[] = $tmpArr;
				}
				
				
			}

			$mailBodyTable= '<style type="text/css">
hr.hr4 {
    background-color: #FFFFFF;
    border-bottom: 1px solid #FF0000;
    border-top: 1px dashed #FF0000;
    color: #FFFFFF;
    height: 4px;
}
</style>
<body style="font-family:Arial; font-size:12px;">
			<h1>'.$_POST["shop_head"].'  Date: '.$_POST["date_begin"].' to '.$_POST["date_end"].'</h1> 
<table width="1300" border="0" cellspacing="1" cellpadding="5" style="font-family:Arial; font-size:12px;" >
  <tr>
    <th scope="col">Invoice</th>
    <th scope="col">Barcode</th>
    <th scope="col">Description</th>
    <th scope="col">Qty</th>
    <th scope="col">RRP</th>
    <th scope="col">%Discount</th>
    <th scope="col">$Discount</th>
    <th scope="col">Final</th>
    <th scope="col">Sales</th>
    <th scope="col">Remark</th>
    <th scope="col">Level</th>
    <th scope="col">Fill Explaine Here</th>
  </tr> ';
			$oldInv = "";
			$newInv = "";
			foreach($arrRes5 as $k3 => $v3){
				$newInv = $v3[0];
				if($newInv!=$oldInv){
					$oldInv = $newInv;
					$mailBodyTable .='<tr>
    <td colspan="12"><hr class="hr4" /></td>
  </tr>';			
				}
				$strLevel = '<td>&nbsp;</td>';
				
				if(isset($v3[10])){
					
					if($v3[10] == 5){
						$strLevel = '<td style="background-color:#FF8080;">&nbsp;</td>';
					}
				}
				$mailBodyTable .='
				<tr>
    <td>'.$v3[0].'</td>
    <td>'.$v3[1].'</td>
    <td>'.$v3[2].'</td>
    <td>'.$v3[3].'</td>
    <td>'.$v3[4].'</td>
    <td>'.$v3[5].'</td>
    <td>'.$v3[6].'</td>
    <td>'.$v3[7].'</td>
    <td>'.$v3[8].'</td>
    <td>'.$v3[9].'</td>
    '.$strLevel.'
    <td>&nbsp;</td>
  </tr>';
				
			}
			
			$oldInv = "";
			$newInv = "";
			
			foreach($arrRes3 as $k4 => $v4){
				
				$newInv = $v4[0];
				
				if($newInv!=$oldInv){
					$oldInv = $newInv;
					$mailBodyTable .='<tr>
					<td colspan="12"><hr class="hr4" /></td>
					</tr>';
				}
				$strLevel = '<td>&nbsp;</td>';
			
				if(isset($v4[10])){
					if($v4[10] == 3){
						$strLevel = '<td style="background-color:#DEB363;">&nbsp;</td>';
					}
				}
				$mailBodyTable .='
				<tr>
				<td>'.$v4[0].'</td>
				<td>'.$v4[1].'</td>
				<td>'.$v4[2].'</td>
				<td>'.$v4[3].'</td>
				<td>'.$v4[4].'</td>
				<td>'.$v4[5].'</td>
				<td>'.$v4[6].'</td>
				<td>'.$v4[7].'</td>
				<td>'.$v4[8].'</td>
				<td>'.$v4[9].'</td>
				'.$strLevel.'
				<td>&nbsp;</td>
				</tr>';
			
			}

	$mailBodyTable.="</table>";

	//	echo $mailBodyTable;

	$mail = new Model_Emailshandler();
	
	$mail->sendNormalEmail($_POST["manager_email"],"Checking Discount for Your Store",$_POST["mail_comment"]."<br/>".$mailBodyTable,"office@phonecollection.com.au");
	

	echo "Email Sent, You may Now Close the window";
		}

		
	public function whCompareAction(){
		
		$shop = "WBPC";
		if($_POST){
			
			$this->_redirect("/products/wh-compare/shop/".$_POST["shop_code"]);
		}
		
		$shop = $this->_getParam("shop");
		$shopCode = $shop;
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
		
		$stock = "";
		$invPro = "";
		
		$stockOld =  new Model_DbTable_Apos_Stock_Balanceold(Zend_Registry::get('db_oldapos'));
		$proDes = new Model_DbTable_Apos_Stock_Productdes(Zend_Registry::get('db_oldapos'));
		$sysCfg = new Model_DbTable_Systemconfig();
		
		$whiteList = $sysCfg->gV("WHITE_LIST");
		$blackList = $sysCfg->gV("BLACK_LIST");
		
		$arrWhite = array_map("trim",explode(",",$whiteList));
		$arrBlack = array_map("trim",explode(",",$blackList));
		
		
		if($shopCode == "BSXP" || $shopCode == "CBPC" || $shopCode == "CLIC" || $shopCode == "CSIC" || $shopCode == "DCIC" || $shopCode == "FGIC" || $shopCode == "HPIC" || $shopCode == "PMIC" ){
			
			$stock = new Model_DbTable_Apos_Stock_Balancenew(Zend_Registry::get('db_apos'));
		}
		else{
			$stock = new Model_DbTable_Apos_Stock_Balanceold(Zend_Registry::get('db_oldapos'));
		}
		
		
		if($shop == "CBPC"){
		$invPro = new Model_DbTable_Apos_Invoice_Products_Cb(Zend_Registry::get('db_apos'));
		}
		if($shop == "BSXP"){
		$invPro = new Model_DbTable_Apos_Invoice_Products_Bsxp(Zend_Registry::get('db_apos'));
		}	
		if($shop == "CLIC"){
		$invPro = new Model_DbTable_Apos_Invoice_Products_Clic(Zend_Registry::get('db_apos'));
		}
		if($shop == "CSIC"){
		$invPro = new Model_DbTable_Apos_Invoice_Products_Csic(Zend_Registry::get('db_apos'));
		}
		if($shop == "DCIC"){
		$invPro = new Model_DbTable_Apos_Invoice_Products_Dcic(Zend_Registry::get('db_apos'));
		}
		if($shop == "FGIC"){
		$invPro = new Model_DbTable_Apos_Invoice_Products_Fgic(Zend_Registry::get('db_apos'));
		}
		if($shop == "HPIC"){
		$invPro = new Model_DbTable_Apos_Invoice_Products_Hpic(Zend_Registry::get('db_apos'));
		}
		if($shop == "PMIC"){
		$invPro = new Model_DbTable_Apos_Invoice_Products_Pmic(Zend_Registry::get('db_apos'));
		}
		if($shop == "WBPC"){
		$invPro = new Model_DbTable_Apos_Invoice_Products_Wb(Zend_Registry::get('db_oldapos'));
		}
		if($shop == "BHPC"){
		$invPro = new Model_DbTable_Apos_Invoice_Products_Bh(Zend_Registry::get('db_oldapos'));
		}
		if($shop == "NLPC"){
		$invPro = new Model_DbTable_Apos_Invoice_Products_Nl(Zend_Registry::get('db_oldapos'));
		}
		if($shop == "CLPC"){
		$invPro = new Model_DbTable_Apos_Invoice_Products_Cl(Zend_Registry::get('db_oldapos'));
		}
		if($shop == "WGPC"){
		$invPro = new Model_DbTable_Apos_Invoice_Products_Wgpc(Zend_Registry::get('db_oldapos'));
		}
		if($shop == "WBIC"){
		$invPro = new Model_DbTable_Apos_Invoice_Products_Wbic(Zend_Registry::get('db_oldapos'));
		}
		if($shop == "BBPC"){
		$invPro = new Model_DbTable_Apos_Invoice_Products_Bb(Zend_Registry::get('db_oldapos'));
		}
		if($shop == "EPPC"){
		$invPro = new Model_DbTable_Apos_Invoice_Products_Ep(Zend_Registry::get('db_oldapos'));
		}
		if($shop == "WLIC"){
		$invPro = new Model_DbTable_Apos_Invoice_Products_Wlic(Zend_Registry::get('db_oldapos'));
		}
		if($shop == "KCPC"){
		$invPro = new Model_DbTable_Apos_Invoice_Products_Kc(Zend_Registry::get('db_oldapos'));
		}
		if($shop == "PMPC"){
		$invPro = new Model_DbTable_Apos_Invoice_Products_Pm(Zend_Registry::get('db_oldapos'));
		}
		if($shop == "BSIC"){
		$invPro = new Model_DbTable_Apos_Invoice_Products_Bsic(Zend_Registry::get('db_oldapos'));
		}
		if($shop == "BSPC"){
		$invPro = new Model_DbTable_Apos_Invoice_Products_Bs(Zend_Registry::get('db_oldapos'));
		}
		if($shop == "SLIC"){
		$invPro = new Model_DbTable_Apos_Invoice_Products_Slic(Zend_Registry::get('db_oldapos'));
		}
		if($shop == "ADPC"){
		$invPro = new Model_DbTable_Apos_Invoice_Products_Ad(Zend_Registry::get('db_oldapos'));
		}
		if($shop == "WBIC"){
		$invPro = new Model_DbTable_Apos_Invoice_Products_Wbic(Zend_Registry::get('db_oldapos'));
		}
		
		
		//$invPd = new Model_DbTable_Apos_Invoice_Products_Ep(Zend_Registry::get('db_oldapos'));
		
		$skListHave = $stockOld -> listStockWhHave();
		
		$arrRes = array();
		
		foreach($skListHave as $list){
			
			$qty = $stock->getStockBalance($list["SCODE"],$arrShopMaping[$shop]);
			//$productSold = $invPro->ifProductSold($list["SCODE"]);
			if($qty <=0 && strpos($list["SCODE"],"STA-") === false && strpos($list["SCODE"],"CREAT") === false  ){
				$ifShow = true;
				$des = $proDes->getAposDes($list["SCODE"]);
				
				// implement white black list 
				foreach($arrBlack as $blackKeyWord){
					if($blackKeyWord!=""){
					if(strpos($des["DESCRIPT"],$blackKeyWord)!== false){
						$ifShow = false;
					}	
					}
				}
				foreach($arrWhite as $whiteKeyWord){
					if($whiteKeyWord!=""){
					if(strpos($des["DESCRIPT"],$whiteKeyWord)!== false){
						$ifShow = true;
					}
					}
				}
				
				if($ifShow){
				
				$tmpArr = array();
				$tmpArr[] = $list["SCODE"];
				$tmpArr[] = $des["DESCRIPT"];
				$tmpArr[] = $list["BALANCE"];
				$arrRes[] = $tmpArr;
				
				}
			}
				
		} 
		
		//var_dump($arrRes);
		
		$this->view->arrRes = $arrRes;
		$this->view->shopCode = $shopCode;
		
	}
	public function whCompareBothEmptyAction(){
		
		$shop = "WBPC";
		set_time_limit(0);
		if($_POST){
				
			$this->_redirect("/products/wh-compare-both-empty/shop/".$_POST["shop_code"]);
		}
		
		$shop = $this->_getParam("shop");
		
		$shopCode = $shop;
		
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
		
		$stock = "";
		$invPro = "";
		
		$stockOld =  new Model_DbTable_Apos_Stock_Balanceold(Zend_Registry::get('db_oldapos'));
		$proDes = new Model_DbTable_Apos_Stock_Productdes(Zend_Registry::get('db_oldapos'));
		
		$sysCfg = new Model_DbTable_Systemconfig();
		
		$whiteList = $sysCfg->gV("WHITE_LIST");
		$blackList = $sysCfg->gV("BLACK_LIST");
		
		$arrWhite = array_map("trim",explode(",",$whiteList));
		$arrBlack = array_map("trim",explode(",",$blackList));
		
		
		if($shopCode == "BSXP" || $shopCode == "CBPC" || $shopCode == "CLIC" || $shopCode == "CSIC" || $shopCode == "DCIC" || $shopCode == "FGIC" || $shopCode == "HPIC" || $shopCode == "PMIC" ){
				
			$stock = new Model_DbTable_Apos_Stock_Balancenew(Zend_Registry::get('db_apos'));
		}
		else{
			$stock = new Model_DbTable_Apos_Stock_Balanceold(Zend_Registry::get('db_oldapos'));
		}
		
		
		if($shop == "CBPC"){
			$invPro = new Model_DbTable_Apos_Invoice_Products_Cb(Zend_Registry::get('db_apos'));
		}
		if($shop == "BSXP"){
			$invPro = new Model_DbTable_Apos_Invoice_Products_Bsxp(Zend_Registry::get('db_apos'));
		}
		if($shop == "CLIC"){
			$invPro = new Model_DbTable_Apos_Invoice_Products_Clic(Zend_Registry::get('db_apos'));
		}
		if($shop == "CSIC"){
			$invPro = new Model_DbTable_Apos_Invoice_Products_Csic(Zend_Registry::get('db_apos'));
		}
		if($shop == "DCIC"){
			$invPro = new Model_DbTable_Apos_Invoice_Products_Dcic(Zend_Registry::get('db_apos'));
		}
		if($shop == "FGIC"){
			$invPro = new Model_DbTable_Apos_Invoice_Products_Fgic(Zend_Registry::get('db_apos'));
		}
		if($shop == "HPIC"){
			$invPro = new Model_DbTable_Apos_Invoice_Products_Hpic(Zend_Registry::get('db_apos'));
		}
		if($shop == "PMIC"){
			$invPro = new Model_DbTable_Apos_Invoice_Products_Pmic(Zend_Registry::get('db_apos'));
		}
		if($shop == "WBPC"){
			$invPro = new Model_DbTable_Apos_Invoice_Products_Wb(Zend_Registry::get('db_oldapos'));
		}
		if($shop == "BHPC"){
			$invPro = new Model_DbTable_Apos_Invoice_Products_Bh(Zend_Registry::get('db_oldapos'));
		}
		if($shop == "NLPC"){
			$invPro = new Model_DbTable_Apos_Invoice_Products_Nl(Zend_Registry::get('db_oldapos'));
		}
		if($shop == "CLPC"){
			$invPro = new Model_DbTable_Apos_Invoice_Products_Cl(Zend_Registry::get('db_oldapos'));
		}
		if($shop == "WGPC"){
			$invPro = new Model_DbTable_Apos_Invoice_Products_Wgpc(Zend_Registry::get('db_oldapos'));
		}
		if($shop == "WBIC"){
			$invPro = new Model_DbTable_Apos_Invoice_Products_Wbic(Zend_Registry::get('db_oldapos'));
		}
		if($shop == "BBPC"){
			$invPro = new Model_DbTable_Apos_Invoice_Products_Bb(Zend_Registry::get('db_oldapos'));
		}
		if($shop == "EPPC"){
			$invPro = new Model_DbTable_Apos_Invoice_Products_Ep(Zend_Registry::get('db_oldapos'));
		}
		if($shop == "WLIC"){
			$invPro = new Model_DbTable_Apos_Invoice_Products_Wlic(Zend_Registry::get('db_oldapos'));
		}
		if($shop == "KCPC"){
			$invPro = new Model_DbTable_Apos_Invoice_Products_Kc(Zend_Registry::get('db_oldapos'));
		}
		if($shop == "PMPC"){
			$invPro = new Model_DbTable_Apos_Invoice_Products_Pm(Zend_Registry::get('db_oldapos'));
		}
		if($shop == "BSIC"){
			$invPro = new Model_DbTable_Apos_Invoice_Products_Bsic(Zend_Registry::get('db_oldapos'));
		}
		if($shop == "BSPC"){
			$invPro = new Model_DbTable_Apos_Invoice_Products_Bs(Zend_Registry::get('db_oldapos'));
		}
		if($shop == "SLIC"){
			$invPro = new Model_DbTable_Apos_Invoice_Products_Slic(Zend_Registry::get('db_oldapos'));
		}
		if($shop == "ADPC"){
			$invPro = new Model_DbTable_Apos_Invoice_Products_Ad(Zend_Registry::get('db_oldapos'));
		}
		if($shop == "WBIC"){
			$invPro = new Model_DbTable_Apos_Invoice_Products_Wbic(Zend_Registry::get('db_oldapos'));
		}
		
		$skListEmpty = $stockOld -> listStockWhEmpty();
		
		$arrRes = array();
		
		foreach($skListEmpty as $list){
				
			$qty = $stock->getStockBalance($list["SCODE"],$arrShopMaping[$shop]);
			
			$productSold = $invPro->ifProductSold($list["SCODE"]);
			
			if($qty <=0 && strpos($list["SCODE"],"STA-") === false && strpos($list["SCODE"],"CREAT") === false  ){
				$ifShow = true;
				$des = $proDes->getAposDes($list["SCODE"]);
		
				// implement white black list
				foreach($arrBlack as $blackKeyWord){
					if($blackKeyWord!=""){
						if(strpos($des["DESCRIPT"],$blackKeyWord)!== false){
							$ifShow = false;
						}
					}
				}
				foreach($arrWhite as $whiteKeyWord){
					if($whiteKeyWord!=""){
						if(strpos($des["DESCRIPT"],$whiteKeyWord)!== false){
							$ifShow = true;
						}
					}
				}
		
				if($ifShow){
		
					$tmpArr = array();
					$tmpArr[] = $list["SCODE"];
					$tmpArr[] = $des["DESCRIPT"];
					$tmpArr[] = $list["BALANCE"];
					$tmpArr[] = ($productSold)?$productSold:"N/A";
					$arrRes[] = $tmpArr;
		
				}
			}
		
		}
		
		$this->view->arrRes = $arrRes;
		$this->view->shopCode = $shopCode;
		
		
	}

	public function whCompareEmptyAction(){
		
		set_time_limit(0);
		ob_start();
		
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
		
		$stock = "";
		
		$dateEnd = '2013-07-14';
		
		$dateBegin = Model_DatetimeHelper::adjustWeeks("sub", $dateEnd, 4);
		
		
		$stock = new Model_DbTable_Apos_Stock_Balanceold(Zend_Registry::get('db_oldapos'));
		$invPd = new Model_DbTable_Apos_Invoice_Products_Ep(Zend_Registry::get('db_oldapos'));
		
		$skListHave = $stock -> listStockWhEmpty();
		
		$arrRes = array();
		$fName = "data.csv";
		
		
		foreach($skListHave as $list){
				
			$qty = $stock->getStockBalance($list["SCODE"],$arrShopMaping[$shop]);
			$productSold = $invPd->ifProductSold($list["SCODE"]);
			if($qty > 0 && $productSold ){
		
				$tmpArr = array();
				$tmpArr[] = $list["SCODE"];
				$tmpArr[] = $list["BALANCE"];
				
				

				

				
				
				$barCode = $list["SCODE"];
				
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
				
				unset($invPro);
				
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
				//echo "<pre>";
				//var_dump($arrRes);
				//echo "</pre>";
				$tNo = count($arrRes);
				
				$totalCount = 0;
				$totalRate = 0;
				
				foreach($arrRes as $key=> $value){
					$totalCount += $value[0];
					$totalRate += $value[1];
				}
				
				
				echo "Average Count:".$totalCount / $tNo ." Average Rate:".round($totalRate / $tNo,4) * 100 ."%<br/>";
				
				flush();
				ob_flush();
			
				$tmpArr[] = $totalCount / $tNo;
				$tmpArr[] = round($totalRate / $tNo,4) * 100;
				$fl = fopen($fName,"a");
				fputcsv($fl, $tmpArr);
				fclose($fl);
				
				
				
				$arrRes[] = $tmpArr;
				
				
			}
		
		}
		
		//var_dump($arrRes);
		
		$this->view->arrRes = $arrRes;
		
	}
	
	public function scanFilterAction(){
		
		$sysCfg = new Model_DbTable_Systemconfig();
		
		$whiteList = $sysCfg->gV("WHITE_LIST");
		$blackList = $sysCfg->gV("BLACK_LIST");
		
		$this->view->whiteList = $whiteList;
		$this->view->blackList = $blackList;
		
		if($_POST){
			$sysCfg->upV("WHITE_LIST",trim($_POST["white_list"]));
			$sysCfg->upV("BLACK_LIST",trim($_POST["black_list"]));
		
			echo "Value Updated,refresh your firefox to see result";
		}
		
		
	}
	
	public function soldOutCircleAction(){
		
		set_time_limit(0);
		ob_start();
		
		
		$shopArr = array("ADPC","BBPC","BHPC","BSPC","BSIC","CBPC","CLPC","EPPC","KCPC","NLPC","PMPC","SLIC","WBPC","WBIC","WLIC","WGIC","WGPC","CSIC","CLIC","DCIC","PMIC","BSXP","FGIC","HPIC");
		//$shopArr=array("HPIC");
		foreach($shopArr as $shop){
			echo $str="http://192.168.1.126/products/shop-recent-sold-out/shop/".$shop;
			echo "<br />";
		
			$res = file_get_contents($str);
			//sleep(1);
			echo $res;
			ob_flush();
			flush();
			//echo $date;
		}

		
	}
	
	public function shopRecentSoldOutAction(){
		
		date_default_timezone_set("Australia/Melbourne");		
		
		$shop = "WBPC";
		$shop = $this->_getParam("shop");
		$shopCode = $shop;
		
		$arrSoldOut = array();
		$arrReOrder = array();
		
		$bcFilter = array("INSTALL","INSTALLATION","STRAP-","DELITEM","STA-","FAULTY","VOUCHER","PHONEREPAIR","CN");
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

		$dateToday = Model_DatetimeHelper::dateToday();
		$dateMonday = Model_DatetimeHelper::getThisWeekMonday();
		$dateMonth = Model_DatetimeHelper::adjustWeeks("sub", $dateMonday,4);
		$dateEnd = Model_DatetimeHelper::getLastWeekSunday(); 
		
		$intDateMonth = Model_DatetimeHelper::tranferToInt($dateMonth);
		$intDateEnd = Model_DatetimeHelper::tranferToInt($dateEnd); 
		
		
		$extOrder = new Model_DbTable_Products_Stock_Extraorder(Zend_Registry::get('db_real'));
			
		$proDes = new Model_DbTable_Apos_Stock_Productdes(Zend_Registry::get('db_oldapos'));
		$stockOld =  new Model_DbTable_Apos_Stock_Balanceold(Zend_Registry::get('db_oldapos'));
		
		$stock = "";
		$invPro= "";
		$invShop = "";
		
		$sysCfg = new Model_DbTable_Systemconfig();
		
		$whiteList = $sysCfg->gV("WHITE_LIST");
		$blackList = $sysCfg->gV("BLACK_LIST");
		
		$arrWhite = array_map("trim",explode(",",$whiteList));
		$arrBlack = array_map("trim",explode(",",$blackList));

		if($shopCode == "BSXP" || $shopCode == "CBPC" || $shopCode == "CLIC" || $shopCode == "CSIC" || $shopCode == "DCIC" || $shopCode == "FGIC" || $shopCode == "HPIC" || $shopCode == "PMIC" ){
		
			$stock = new Model_DbTable_Apos_Stock_Balancenew(Zend_Registry::get('db_apos'));
		}
		else{
			$stock = new Model_DbTable_Apos_Stock_Balanceold(Zend_Registry::get('db_oldapos'));
		}
		
		$invPro = Model_Aposinit::initAposInvPro($shopCode);
		//check the back order request is fullfilled
		
		$eOlist = $extOrder->listByShop($shopCode);
		foreach($eOlist as $extOrderRow){
			$stockLine = $stock->getStockBalance($extOrderRow['barcode'], $arrShopMaping[$shopCode]);
			
			if($stockLine['BALANCE']>0 && $extOrderRow['order_option']== ""){
				$extOrder->deleteExtraorder($extOrderRow['id_ext_order']);	
			}
			
			if($stockLine['BALANCE']>0 && $extOrderRow['order_fullfill']=="" &&  $extOrderRow['order_option']!=""){
				$extOrder->fullfillOrder($extOrderRow['id_ext_order']);
			}
			
			if($stockLine['BALANCE']<0 && $extOrderRow['order_option']==""){
				
				// update non handled warehouse stock 
				$whStockLine = $stockOld->getStockBalance($extOrderRow['barcode'],'WH');
				$extOrder->updateWhBalance($extOrderRow['id_ext_order'],$whStockLine['BALANCE']);
				
			}
		}
		
		//		
		$pList = $invPro->getProductSalesList($dateMonth, $dateEnd, $invPro::$_tbNames);

		foreach($pList as $sCode){
			$shopStock = $stock->getStockBalance($sCode['SCODE'], $arrShopMaping[$shopCode]);
			$shopStock = $shopStock['BALANCE'];
			$whStock = $stock->getStockBalance($sCode['SCODE'],'WH');
			$whStock = $whStock['BALANCE'];
			$ifShow = true;
			
			foreach($bcFilter as $keyword){
			
				if(strpos($sCode['SCODE'],$keyword)!== false){
					$ifShow = false;
				}
			}
			
			
			if($shopStock <= 0 && $ifShow ){
				
				$desline = $proDes->getAposDes($sCode['SCODE']);
				$des = $desline["DESCRIPT"];
				
				if($extOrder->shouldPutIn($sCode['SCODE'], $shopCode)){
					$extOrder->addExtraorder($sCode['SCODE'], $des, $shopCode, $dateToday, $whStock);	
				}
				
			}
			
		}
						
	}
	
	public function shopAnalysisAction(){
		
		set_time_limit(0);
		
		date_default_timezone_set("Australia/Melbourne");
		
		$shop = "WBPC";
		$noStockFileName1 = "NsNs1";
		
		
		$bcFilter = array("INSTALL","INSTALLATION","STRAP-","DELITEM","STA-","FAULTY","VOUCHER","PHONEREPAIR");
		
		set_time_limit(0);
		
		if($_POST){
				
			$this->_redirect("/products/shop-analysis/shop/".$_POST["shop_code"]);
		}
		
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
		
		
		$dateToday = Model_DatetimeHelper::dateToday();
		$dateMonth = Model_DatetimeHelper::adjustMonths("sub",Model_DatetimeHelper::dateToday(), 1);
		$intDateMonth = Model_DatetimeHelper::tranferToInt($dateMonth);
		
		$shop = $this->_getParam("shop");
		
		$shopCode = $shop;
		
		$arrNeverSent = array();
		$arrNeverSold = array();
		$arrlongTimeNoSale = array();
		$arrNoStockNoSale = array();
		$arrNoStockNoSaleWh = array();
		$arrSoldOut = array();
		
		$proDes = new Model_DbTable_Apos_Stock_Productdes(Zend_Registry::get('db_oldapos'));
		$stockOld =  new Model_DbTable_Apos_Stock_Balanceold(Zend_Registry::get('db_oldapos'));
		
		$stock = "";
		$invPro= "";
		$invShop = "";
		
		$sysCfg = new Model_DbTable_Systemconfig();
		
		$whiteList = $sysCfg->gV("WHITE_LIST");
		$blackList = $sysCfg->gV("BLACK_LIST");
		
		$arrWhite = array_map("trim",explode(",",$whiteList));
		$arrBlack = array_map("trim",explode(",",$blackList));
		
		
		if($shopCode == "BSXP" || $shopCode == "CBPC" || $shopCode == "CLIC" || $shopCode == "CSIC" || $shopCode == "DCIC" || $shopCode == "FGIC" || $shopCode == "HPIC" || $shopCode == "PMIC" ){
		
			$stock = new Model_DbTable_Apos_Stock_Balancenew(Zend_Registry::get('db_apos'));
		}
		else{
			$stock = new Model_DbTable_Apos_Stock_Balanceold(Zend_Registry::get('db_oldapos'));
		}
		
		
		if($shop == "CBPC"){
			$invPro = new Model_DbTable_Apos_Invoice_Products_Cb(Zend_Registry::get('db_apos'));
			$invShop = new Model_DbTable_Apos_Invoice_Cb(Zend_Registry::get('db_apos'));
			
		}
		if($shop == "BSXP"){
			$invPro = new Model_DbTable_Apos_Invoice_Products_Bsxp(Zend_Registry::get('db_apos'));
			$invShop = new Model_DbTable_Apos_Invoice_Bsxp(Zend_Registry::get('db_apos'));
		}
		if($shop == "CLIC"){
			$invPro = new Model_DbTable_Apos_Invoice_Products_Clic(Zend_Registry::get('db_apos'));
			$invShop = new Model_DbTable_Apos_Invoice_Clic(Zend_Registry::get('db_apos'));
		}
		if($shop == "CSIC"){
			$invPro = new Model_DbTable_Apos_Invoice_Products_Csic(Zend_Registry::get('db_apos'));
			$invShop = new Model_DbTable_Apos_Invoice_Csic(Zend_Registry::get('db_apos'));
		}
		if($shop == "DCIC"){
			$invPro = new Model_DbTable_Apos_Invoice_Products_Dcic(Zend_Registry::get('db_apos'));
			$invShop = new Model_DbTable_Apos_Invoice_Dcic(Zend_Registry::get('db_apos'));
		}
		if($shop == "FGIC"){
			$invPro = new Model_DbTable_Apos_Invoice_Products_Fgic(Zend_Registry::get('db_apos'));
			$invShop = new Model_DbTable_Apos_Invoice_Fgic(Zend_Registry::get('db_apos'));
		}
		if($shop == "HPIC"){
			$invPro = new Model_DbTable_Apos_Invoice_Products_Hpic(Zend_Registry::get('db_apos'));
			$invShop = new Model_DbTable_Apos_Invoice_Hpic(Zend_Registry::get('db_apos'));
		}
		if($shop == "PMIC"){
			$invPro = new Model_DbTable_Apos_Invoice_Products_Pmic(Zend_Registry::get('db_apos'));
			$invShop = new Model_DbTable_Apos_Invoice_Pmic(Zend_Registry::get('db_apos'));
		}
		if($shop == "WBPC"){
			$invPro = new Model_DbTable_Apos_Invoice_Products_Wb(Zend_Registry::get('db_oldapos'));
			$invShop = new Model_DbTable_Apos_Invoice_Wb(Zend_Registry::get('db_oldapos'));
		}
		if($shop == "BHPC"){
			$invPro = new Model_DbTable_Apos_Invoice_Products_Bh(Zend_Registry::get('db_oldapos'));
			$invShop = new Model_DbTable_Apos_Invoice_Bh(Zend_Registry::get('db_oldapos'));
		}
		if($shop == "NLPC"){
			$invPro = new Model_DbTable_Apos_Invoice_Products_Nl(Zend_Registry::get('db_oldapos'));
			$invShop = new Model_DbTable_Apos_Invoice_Nl(Zend_Registry::get('db_oldapos'));
		}
		if($shop == "CLPC"){
			$invPro = new Model_DbTable_Apos_Invoice_Products_Cl(Zend_Registry::get('db_oldapos'));
			$invShop = new Model_DbTable_Apos_Invoice_Cl(Zend_Registry::get('db_oldapos'));
		}
		if($shop == "WGPC"){
			$invPro = new Model_DbTable_Apos_Invoice_Products_Wgpc(Zend_Registry::get('db_oldapos'));
			$invShop = new Model_DbTable_Apos_Invoice_Wgpc(Zend_Registry::get('db_oldapos'));
		}
		if($shop == "WGIC"){
			$invPro = new Model_DbTable_Apos_Invoice_Products_Wgic(Zend_Registry::get('db_oldapos'));
			$invShop = new Model_DbTable_Apos_Invoice_Wgic(Zend_Registry::get('db_oldapos'));
		}
		if($shop == "WBIC"){
			$invPro = new Model_DbTable_Apos_Invoice_Products_Wbic(Zend_Registry::get('db_oldapos'));
			$invShop = new Model_DbTable_Apos_Invoice_Wbic(Zend_Registry::get('db_oldapos'));
		}
		if($shop == "BBPC"){
			$invPro = new Model_DbTable_Apos_Invoice_Products_Bb(Zend_Registry::get('db_oldapos'));
			$invShop = new Model_DbTable_Apos_Invoice_Bb(Zend_Registry::get('db_oldapos'));
		}
		if($shop == "EPPC"){
			$invPro = new Model_DbTable_Apos_Invoice_Products_Ep(Zend_Registry::get('db_oldapos'));
			$invShop = new Model_DbTable_Apos_Invoice_Ep(Zend_Registry::get('db_oldapos'));
		}
		if($shop == "WLIC"){
			$invPro = new Model_DbTable_Apos_Invoice_Products_Wlic(Zend_Registry::get('db_oldapos'));
			$invShop = new Model_DbTable_Apos_Invoice_Wlic(Zend_Registry::get('db_oldapos'));
		}
		if($shop == "KCPC"){
			$invPro = new Model_DbTable_Apos_Invoice_Products_Kc(Zend_Registry::get('db_oldapos'));
			$invShop = new Model_DbTable_Apos_Invoice_Kc(Zend_Registry::get('db_oldapos'));
		}
		if($shop == "PMPC"){
			$invPro = new Model_DbTable_Apos_Invoice_Products_Pm(Zend_Registry::get('db_oldapos'));
			$invShop = new Model_DbTable_Apos_Invoice_Pm(Zend_Registry::get('db_oldapos'));
		}
		if($shop == "BSIC"){
			$invPro = new Model_DbTable_Apos_Invoice_Products_Bsic(Zend_Registry::get('db_oldapos'));
			$invShop = new Model_DbTable_Apos_Invoice_Bsic(Zend_Registry::get('db_oldapos'));
		}
		if($shop == "BSPC"){
			$invPro = new Model_DbTable_Apos_Invoice_Products_Bs(Zend_Registry::get('db_oldapos'));
			$invShop = new Model_DbTable_Apos_Invoice_Bs(Zend_Registry::get('db_oldapos'));
		}
		if($shop == "SLIC"){
			$invPro = new Model_DbTable_Apos_Invoice_Products_Slic(Zend_Registry::get('db_oldapos'));
			$invShop = new Model_DbTable_Apos_Invoice_Slic(Zend_Registry::get('db_oldapos'));
		}
		if($shop == "ADPC"){
			$invPro = new Model_DbTable_Apos_Invoice_Products_Ad(Zend_Registry::get('db_oldapos'));
			$invShop = new Model_DbTable_Apos_Invoice_Ad(Zend_Registry::get('db_oldapos'));
		}
		if($shop == "WBIC"){
			$invPro = new Model_DbTable_Apos_Invoice_Products_Wbic(Zend_Registry::get('db_oldapos'));
			$invShop = new Model_DbTable_Apos_Invoice_Wbic(Zend_Registry::get('db_oldapos'));
		}
		
		$bList = $proDes->getAll();
		
		foreach($bList as $barcode){
			//barcode
			$bcode = $barcode["SCODE"];
			$ifShow = true;
			
			foreach($bcFilter as $keyword){
				
				if(strpos($bcode,$keyword)!== false){
					$ifShow = false;
				}
			}
			
			if($ifShow){
				
			$desline = $proDes->getAposDes($bcode);
			$des = $desline["DESCRIPT"];
			//if sold 
			$invID = $invPro->ifProductSold($bcode);
			$itemStockLine = $stock->getStockBalance($bcode,$arrShopMaping[$shop]);
			$itemStock = $itemStockLine["BALANCE"];
			$whStockLine = $stockOld->getStockBalance($bcode,"WH");
			$whStock = $whStockLine["BALANCE"];
			//var_dump($invID);
			
			if($invID){
			//sold	
				$dateLine = $invShop->getInvoice($invID);
				$salesDate = $dateLine["DATE"];
				$intSaleDate = date("U",strtotime($salesDate));
				$strDate = date("Y-m-d",$intSaleDate);
				
				if($intSaleDate >= $intDateMonth ){
					//recent sold out
					if($itemStock <= 0){
						//barcode , description , invoice , warehouse stock 
						$arrSoldOut[] = array($bcode,$des,$invID,(int)$whStock,$strDate);
						
					}
					
				}else{
					//sale month ago 
					
					
					if($itemStock > 0){
						// long time no sale
						
						$arrlongTimeNoSale[] = array($bcode,$des,$invID,(int)$itemStock,(int)$whStock,$strDate);
						
					}
					else{
						// no stock no sale
						if((int)$whStock >0){
						$arrNoStockNoSale[] = array($bcode,$des,$invID,(int)$whStock,$strDate);
						}
						else{
							$arrNoStockNoSaleWh[] = array($bcode,$des,$invID,(int)$whStock,$strDate);
						}
					}
				}
				
			}
			else{
			//never sale -> get stock 
				
				if($itemStock > 0){
					//send nver sale 
					$arrNeverSold[] = array($bcode,$des,(int)$itemStock,(int)$whStock);
				}
				else{
					//never send 
					$arrNeverSent[] = array($bcode,$des,(int)$whStock);
					
				}				
			}
			
			
			//echo "<br/>";
			
			}
		}
		
		
		
		//Never Send
		//Send Never Sold
		//Date >1 month Qty > 0  long time no sale
		//date >1 month Qty <=0  No stock No sale -> warehouse stock 
		//date <1 month Qty <=0  Recent Sold out -> warehouse stock 
		
		$k1 = array();
		$k2 = array();
		
		foreach($arrSoldOut as $key => $line){
			
			$k1[$key] = $line[4];
			$k2[$key] = $line[3]; 
		}
		array_multisort($k1,SORT_ASC,$k2,SORT_DESC,$arrSoldOut);
		// no stock no sale
		
		$k1 = array();
		$k2 = array();
		
		foreach($arrNoStockNoSale as $key => $line){
				
			$k1[$key] = $line[4];
			$k2[$key] = $line[3];
		}		
		array_multisort($k1,SORT_ASC,$k2,SORT_DESC,$arrNoStockNoSale);
		
		$k1 = array();
		$k2 = array();
		$k3 = array();
		
		foreach($arrlongTimeNoSale as $key => $line){
			$k1[$key] = $line[5];
			$k2[$key] = $line[4];
			$k3[$key] = $line[3];
			
		}
		
		array_multisort($k1,SORT_ASC,$k2,SORT_DESC,$k3,SORT_DESC,$arrlongTimeNoSale);
		
		$k1 = array();
		$k2 = array();
		
		foreach($arrNeverSold as $key => $line){
		
			$k1[$key] = $line[3];
			$k2[$key] = $line[2];
		}
		array_multisort($k1,SORT_DESC,$k2,SORT_DESC,$arrNeverSold);
		
		
		$k1 = array();
		foreach($arrNeverSent as $key => $line){
		
			$k1[$key] = $line[2];
		}		
		
		array_multisort($k1,SORT_DESC,$arrNeverSent);
		
		$this->view->arrNeverSent = $arrNeverSent;
		$this->view->arrNeverSold = $arrNeverSold;
		$this->view->arrLongTimeNoSale = $arrlongTimeNoSale;
		$this->view->arrNoStockNoSale = $arrNoStockNoSale;
		$this->view->arrSoldOut = $arrSoldOut;
		$this->view->shopCode = $shopCode;
		
		
	}
	
	public function oldDoubleStarAction(){
		
		date_default_timezone_set("Australia/Melbourne");
		
		$dateBegin = Model_DatetimeHelper::getFirstDayOfTheMonth($dateToday);
		$dateBegin = Model_DatetimeHelper::adjustMonths("sub", $dateBegin, 1);
		//$dateBegin = "2014-01-01";
		$this->view->dateBegin = $dateBegin;
		
		$products = new Model_DbTable_Apos_Stock_Productdes(Zend_Registry::get('db_oldapos'));
		$productsNew = new Model_DbTable_Apos_Stock_Productdesnew(Zend_Registry::get('db_apos'));
		$grn = new Model_DbTable_Apos_Stock_Grnold(Zend_Registry::get('db_oldapos'));
		$grnData = new Model_DbTable_Apos_Stock_Grndataold(Zend_Registry::get('db_oldapos'));
		
		$pList = $products->listNewProducts();
		$arrRes = array();
		
		foreach($pList as $product){
			
			//echo $product["DESCRIPT"];
			$sCode = $product["SCODE"];
			$grnNo = $grnData->getGrnNo($sCode);
			$grnDate = $grn->getGrnDate($grnNo);
			
			if(date("U",strtotime($grnDate)) < date("U",strtotime($dateBegin))  ){
				
				$tmpArr = array();
				$tmpArr[] = $sCode;
				$tmpArr[] = $product["DESCRIPT"];
				$tmpArr[] = date("Y-m-d",strtotime($grnDate));
				//echo $sCode."  ".$product["DESCRIPT"]." ".$grnDate."<br />";
				$arrRes[] = $tmpArr;
			}
			
		}
		$arrDate = array();
		
		foreach($arrRes as $k => $v){
			$arrDate[$k] = $v[2];
		}
		array_multisort($arrDate,SORT_ASC,$arrRes);
		
		$this->view->arrRes = $arrRes;
		
		//reset($arrRes);
		
		foreach($arrRes as $k2 => $v2){
			if($v2[2]!="1970-01-01"){	
				$productsNew->removeStar($v2[0]);
				$products->removeStar($v2[0]);
			}
		}
		//$productsNew->removeStar("APIPADMLC001-15");
		
		//get all list with **
		
		//check the first GN of the item 
		
		//check the date of first GN see if < date begin 
		
		//Yes list it 
		
		
		
	}
	
	public function extraOrderAction(){
		
		$shopCode = $this->_getParam("shop");
		
		$extOrder = new Model_DbTable_Products_Stock_Extraorder();
		$stInfo = new Model_DbTable_Roster_Stafflogindetail();
		

		
		if($_POST){
			$stId = $stInfo->checkPasswordCorrect(Model_EncryptHelper::hashsha($_POST['pwd']));
			if($stId){
			foreach($_POST['order_option'] as $k =>$v){
							
				$extOrder->updateExtOption($k, $v, $_POST['order_qty'][$k], $stId['id']);
			}
			
			}
		}
		
		$this->view->uList = $uList = $extOrder->getUnhandledOrder($shopCode);
		$this->view->sList = $sList = $extOrder->getStopList($shopCode);
		$this->view->hList = $hList = $extOrder->getHandledOrder($shopCode);
		$this->view->fList = $fList = $extOrder->getFullfilledOrder($shopCode);
		
		//var_dump($sList,$hList);
		
		$this->renderScript("/products/extra-order-new.phtml");
		
	}
	
	public function extraOrderNotFinishAction(){
		
		$this->view->email = $this->_getParam("email");
		$extOrder = new Model_DbTable_Products_Stock_Extraorder();
		
		$arrRes = array();
		foreach(self::$arrShopMaping as $shopCode => $v){
			$uOrderList = $extOrder->getUnhandledOrder($shopCode);
			$arrRes[$shopCode] = count($uOrderList);
		}
		
		//d($arrRes);
		
		$this->view->arrRes = $arrRes;
		
	}
	
	public function getUnhandledCountAction(){
		
		$this->_helper->layout()->disableLayout();
		
		$shopCode = $this->_getParam("shop");
		$extOrder = new Model_DbTable_Products_Stock_Extraorder();	
		echo count($extOrder->getUnhandledOrder($shopCode));
	}
	
	
	public function extraOrdeOldrAction(){
		
		$shopCode = $this->_getParam("shop");
		//$date = $this->_getParam("date");
		
		$monday = Model_DatetimeHelper::getThisWeekMonday();
		$sunday = Model_DatetimeHelper::getThisWeekSunday();
		
		$dateRecord = Model_DatetimeHelper::dateToday();
		$timeRecord = Model_DatetimeHelper::timeNow();
		
		$date = Model_DatetimeHelper::adjustDays("add", $monday, 4);
		
		
		$extOrder = new Model_DbTable_Products_Stock_Extraorder();		 
		$spOrder = new Model_DbTable_Products_Stock_Specialorder();
		
		if($_POST){
			foreach($_POST["id_ext_order"] as $k => $v){
				
				$stop = 0;
				if(isset($_POST["stop"][$k])){
					$stop = 1;
				}
				
				$extOrder->saveExtraorder($v, $_POST["qty"][$k],$stop, $_POST["comment"][$k]);
			}

			if($_POST["id_sp_order"] == 0 && trim($_POST["special_order"])!="" ){
				
				$spOrder->addSpecialorder($shopCode, $dateRecord, $timeRecord,$_POST["staff_name"], $_POST["special_order"]);
				
			}
			if($_POST["id_sp_order"] > 0 && trim($_POST["special_order"])!="" ){
				//$spOrder->addSpecialorder($shopCode, $dateRecord, $timeRecord,$_POST["staff_name"], $_POST["special_order"]);
				$spOrder->updateSpecialorder($_POST["id_sp_order"], $shopCode, $dateRecord, $timeRecord,$_POST["staff_name"], $_POST["special_order"]);
			}			
			
		}
			
		$sList = $extOrder->listByShop($shopCode, $date);
		
		$soLine = $spOrder->getSpecialorderByDate($shopCode, $monday, $sunday);
		
		
		$this->view->sList = $sList;
		$this->view->shop = $shopCode;
		$this->view->soLine = $soLine;
		
		
	}
	
	public function checkMissingPictureAction(){
		
		set_time_limit(0);
		ob_start();
		$pro = new Model_DbTable_Apos_Stock_Productdes(Zend_Registry::get('db_oldapos'));
		$pList = $pro->getAll();
		foreach($pList as $product){
			//echo $product["SCODE"]."<br />";
			$url = "http://192.168.1.124/product_img/home_img/".trim($product["SCODE"])."_0.jpg";
			$result = Model_Fileshandler::checkimg($url);
			//var_dump($result);
			if(!$result){
				echo $product["SCODE"]."|".$product["DESCRIPT"]."<br />";
				ob_flush();
			}			
		}
	}

	public function overStockListAction(){
		
		set_time_limit(0);
		
		$b_time = microtime(true);
		
		$intEightWeekAgo =Model_DatetimeHelper::tranferToInt(Model_DatetimeHelper::adjustDays("sub",Model_DatetimeHelper::getLastWeekSunday(), 80));
		
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
		
		
		$shop = "HPPC";
		$shop = $this->_getParam("shop");
		
		$products = new Model_DbTable_Apos_Stock_Productdes(Zend_Registry::get('db_apos'));
		$proDes = new Model_DbTable_Apos_Stock_Productdes(Zend_Registry::get('db_oldapos'));
		
		$stock = self::initProStock($shop);
		$invPro = self::initAposInvPro($shop);
						
		$pList = $products->getAll();
		
		$arrRes = array();
		$arrRes2 = array();
		
		$arrHot = array();
		$arrNormal = array();
		$arrCold = array();
		$arrCodeSort = array();
		$arrVCold = array();
		$arrVColdSort = array();
		$arrNormalSort = array();
		$arrHotSort = array();
		
		
		
		$pros = new Model_DbTable_Productsva(Zend_Registry::get('db_real'));
		$cot = 0;
		
		foreach($pList as $pro){

			$sCode = trim($pro["SCODE"]);
			
			
			$id = $pros->getProductID(trim($sCode));
			$line = $pros->getProduct($id);
			$dateIntro = Model_DatetimeHelper::tranferToInt($line['date_introduce']);
			//if($dateIntro <= $intEightWeekAgo){ echo "Smaller";}
			//else{ echo "New P";}
			
			if(!$id){
				
				echo $sCode."<br />";
			}
			if(trim(substr($sCode,0,4)) != "STA-" && $dateIntro <= $intEightWeekAgo && $id!==false ){
				
				// if not sta and Old products
				
				$stockLine = $stock->getStockBalance($sCode, $arrShopMaping[$shop]);
				$whStock = $stock->getStockBalance($sCode,"WH");	
				$isHot = $invPro->isHotItem10Wk($sCode);
				//echo $isHot;
				
				if($isHot== "VERYCOLD" && $stockLine['BALANCE'] >0 ){
					
					$arrVCold[]= array($sCode,$stockLine['BALANCE']);
					$arrVColdSort[] = $stockLine['BALANCE'];
					
					$cot++;
				}
				
				if($isHot== "COLD" && $stockLine['BALANCE'] >0 ){
						
					$arrCold[]= array($sCode,$stockLine['BALANCE']);
					$arrColdSort[] = $stockLine['BALANCE'];
						
					//$cot++;
				}

				if($isHot== "NORMAL" && $stockLine['BALANCE'] >0 ){
				
					$arrNormal[]= array($sCode,$stockLine['BALANCE']);
					$arrNormalSort[] = $stockLine['BALANCE'];
				
					//$cot++;
				}
				
				if($isHot== "HOT" && $stockLine['BALANCE'] >0 ){
				
					$arrHot[]= array($sCode,$stockLine['BALANCE']);
					$arrHotSort[] = $stockLine['BALANCE'];
				
					//$cot++;
				}
					
			}

			
		}
		array_multisort($arrVColdSort,SORT_DESC,$arrVCold);
		array_multisort($arrColdSort,SORT_DESC,$arrCold);
		array_multisort($arrNormalSort,SORT_DESC,$arrNormal);
		array_multisort($arrHotSort,SORT_DESC,$arrHot);
		$this->view->arrRes = $arrVCold;
		
		$fnVCold = $shop."_vcold.csv";
		$fnCold =  $shop."_cold.csv";
		$fnNormal =  $shop."_normal.csv";
		$fnHot =  $shop."_hot.csv";
		
		$fl = fopen($fnVCold,"a");
		
		foreach($arrVCold as $key => $v){
			
			$proDesLine = $proDes->getAposDes($v[0]);
			fputcsv($fl,array($v[0],$proDesLine['DESCRIPT'],$v[1],$proDesLine['CAT2'],$proDesLine['CAT3']));			
		}
		fclose($fl);
		
		$fl2 = fopen($fnCold,"a");

		foreach($arrCold as $key2 => $v2){
				
			$proDesLine = $proDes->getAposDes($v2[0]);
			fputcsv($fl2,array($v2[0],$proDesLine['DESCRIPT'],$v2[1],$proDesLine['CAT2'],$proDesLine['CAT3']));
		}
		fclose($fl2);
		
		$fl3 = fopen($fnNormal,"a");
		
		foreach($arrNormal as $key3 => $v3){
		
			$proDesLine = $proDes->getAposDes($v3[0]);
			fputcsv($fl3,array($v3[0],$proDesLine['DESCRIPT'],$v3[1],$proDesLine['CAT2'],$proDesLine['CAT3']));
		}
		fclose($fl3);

		
		$fl4 = fopen($fnHot,"a");
		
		foreach($arrHot as $key4 => $v4){
		
			$proDesLine = $proDes->getAposDes($v4[0]);
			fputcsv($fl4,array($v4[0],$proDesLine['DESCRIPT'],$v4[1],$proDesLine['CAT2'],$proDesLine['CAT3']));
		}
		fclose($fl4);
				
		//var_dump($arrRes);
		
		//$pList = $invPro->
		
		$e_time = microtime(true);
		
		$dif = $e_time - $b_time;
		echo "TIME:".$dif;
		
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
	
	public function initAposInvPro($shop){
	
		$invPro = "";
	
		if($shop == "CBPC"){
			$invPro = new Model_DbTable_Apos_Invoice_Products_Cb(Zend_Registry::get('db_apos'));
		}
		if($shop == "BSXP"){
			$invPro = new Model_DbTable_Apos_Invoice_Products_Bsxp(Zend_Registry::get('db_apos'));
		}
		if($shop == "CLIC"){
			$invPro = new Model_DbTable_Apos_Invoice_Products_Clic(Zend_Registry::get('db_apos'));
		}
		if($shop == "CSIC"){
		
			$invPro = new Model_DbTable_Apos_Invoice_Products_Csic(Zend_Registry::get('db_apos'));
		}
		if($shop == "DCIC"){
		
			$invPro = new Model_DbTable_Apos_Invoice_Products_Dcic(Zend_Registry::get('db_apos'));
		}
		if($shop == "FGIC"){
		
			$invPro = new Model_DbTable_Apos_Invoice_Products_Fgic(Zend_Registry::get('db_apos'));
		}
		if($shop == "HPIC"){
		
			$invPro = new Model_DbTable_Apos_Invoice_Products_Hpic(Zend_Registry::get('db_apos'));
		}
		if($shop == "PMIC"){
		
			$invPro = new Model_DbTable_Apos_Invoice_Products_Pmic(Zend_Registry::get('db_apos'));
		}
		if($shop=="WBPC"){
		
			$invPro = new Model_DbTable_Apos_Invoice_Products_Wb(Zend_Registry::get('db_oldapos'));
		}
			
		if($shop=="BHPC"){
		
			$invPro = new Model_DbTable_Apos_Invoice_Products_Bh(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="NLPC"){
		
			$invPro = new Model_DbTable_Apos_Invoice_Products_Nl(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="CLPC"){
		
			$invPro = new Model_DbTable_Apos_Invoice_Products_Cl(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="WGPC"){
		
			$invPro = new Model_DbTable_Apos_Invoice_Products_Wgpc(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="WBIC"){
		
			$invPro = new Model_DbTable_Apos_Invoice_Products_Wbic(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="BBPC"){
		
			$invPro = new Model_DbTable_Apos_Invoice_Products_Bb(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="EPPC"){
		
			$invPro = new Model_DbTable_Apos_Invoice_Products_Ep(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="WLIC"){
		
			$invPro = new Model_DbTable_Apos_Invoice_Products_Wlic(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="KCPC"){
		
			$invPro = new Model_DbTable_Apos_Invoice_Products_Kc(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="PMPC"){
		
			$invPro = new Model_DbTable_Apos_Invoice_Products_Pm(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="BSIC"){
		
			$invPro = new Model_DbTable_Apos_Invoice_Products_Bsic(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="BSPC"){
		
			$invPro = new Model_DbTable_Apos_Invoice_Products_Bs(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="SLIC"){
		
			$invPro = new Model_DbTable_Apos_Invoice_Products_Slic(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="ADPC"){
		
			$invPro = new Model_DbTable_Apos_Invoice_Products_Ad(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="WGIC"){
		
			$invPro = new Model_DbTable_Apos_Invoice_Products_Wgic(Zend_Registry::get('db_oldapos'));
		}
	
		return $invPro;
	}
	
	public function initProStock($shop){
		
		$proSkOld =  new Model_DbTable_Apos_Stock_Balanceold(Zend_Registry::get('db_oldapos'));
		$proSkNew =  new Model_DbTable_Apos_Stock_Balancenew(Zend_Registry::get('db_apos'));
		
		$proSk = "";
		if($shop == "BSXP" || $shop == "CBPC" || $shop == "CLIC" || $shop == "CSIC" || $shop == "DCIC" || $shop == "FGIC" || $shop == "HPIC" || $shop == "PMIC" ){
			$proSk = $proSkNew;
		}
		else{
			$proSk = $proSkOld;
		}
		
		return $proSk;
		
	}
	public function initKtData($shop){
	
		$proKtOld =  new Model_DbTable_Apos_Stock_Ktdataold(Zend_Registry::get('db_oldapos'));
		$proKtNew =  new Model_DbTable_Apos_Stock_Ktdatanew(Zend_Registry::get('db_apos'));
	
		$proKt = "";
		if($shop == "BSXP" || $shop == "CBPC" || $shop == "CLIC" || $shop == "CSIC" || $shop == "DCIC" || $shop == "FGIC" || $shop == "HPIC" || $shop == "PMIC" ){
			$proKt = $proKtNew;
		}
		else{
			$proKt = $proKtOld;
		}
	
		return $proKt;
	
	}
	
	public function stopSaleListAction(){
		$shop = $this->_getParam("shop");
		
		$extOrder = new Model_DbTable_Products_Stock_Extraorder();
		$sList = $extOrder->getStopSale($shop);
		
		$this->view->sList = $sList;
		//var_dump($sList);
		
		//var_dump($shop);
		
	}
	
	public function backOrderListAction(){
		
		$shop = $this->_getParam("shop");
		
		$reOrder = new Model_DbTable_Products_Stock_Reorder();
		
		$rList = $reOrder->getReorderByShop($shop);
		$this->view->rList = $rList;
		
		//var_dump($rList);
		
	}
	
	public function activeCircleAction(){
		set_time_limit(0);
		ob_start();
		
		
		$shopArr = array("ADPC","BBPC","BHPC","BSPC","BSIC","CBPC","CLPC","EPPC","KCPC","NLPC","PMPC","SLIC","WBPC","WBIC","WLIC","WGIC","WGPC","CSIC","CLIC","DCIC","PMIC","BSXP","FGIC","HPIC");
		//$shopArr=array("HPIC");
		foreach($shopArr as $shop){
			echo $str="http://localhost/products/active-shop-invoice/shop/".$shop;
			echo "<br />";
		
			$res = file_get_contents($str);
			//sleep(1);
			echo $res;
			ob_flush();
			flush();
			//echo $date;
		}
		
	}
	
	public function activeShopInvoiceAction(){
	
		$shopHead = $this->_getParam("shop");
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
	
		$inv = "";
		$inv = self::initAposInvoice($shopHead);
		
		$inv->activeInvoice();
	
	}
	
	public function getShippingDetailAction(){
		
		$arrShippingOrder = array(1,2);
		
		$pros = new Model_DbTable_Productsva();
		$proShip = new Model_DbTable_Order_Shippingdetail();
		$pList = $pros->listAllProducts();
		
		$arrRes = array();
		
		
		foreach($pList as $product){
			
			$arrDetail["code_product"] = $product['code_product'];
			
			$arrDetail += $proShip->getShippingQty($product['code_product'], $arrShippingOrder);

			$arrRes[] = $arrDetail; 
		}
		$this->view->arrRes = $arrRes;
		//var_dump($arrRes);
	}
	
	public function lastEightWeeksProductsSalesAction(){
		
		set_time_limit(0);
				
		$time_start = microtime(true);
		
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
		
		
		$shopArr = array("ADPC","BBPC","BHPC","BSPC","BSIC","CBPC","CLPC","EPPC","KCPC","NLPC","PMPC","SLIC","WBPC","WBIC","WLIC","WGIC","WGPC","CSIC","CLIC","DCIC","PMIC","BSXP","FGIC","HPIC");

		$pro = new Model_DbTable_Productsva(Zend_Registry::get('db_real'));
		$pSales = new Model_DbTable_Products_Sales(Zend_Registry::get('db_real'));
		
		$pStock = new Model_DbTable_Apos_Stock_Balanceold(Zend_Registry::get('db_oldapos'));
		
		$pSales->deleteAll();
		$proList = $pro->listAllProductsNew();		
		
		$product = "";
		
		$dateBegin = Model_DatetimeHelper::getLastWeekMonday();
		$dateEnd = Model_DatetimeHelper::getLastWeekSunday();
		
		$actDateBegin = "";
		$actDateEnd = "";
	
		$cot = 0;
		
		foreach($proList as $product ){
			
			if(!self::bCodeFilter($product['code_product'])){
			
			$arrPSales = array();
			if($cot < 5000){
			for($i=0;$i<=7;$i++){

				$actDateBegin = Model_DatetimeHelper::adjustWeeks("sub", $dateBegin, $i);
				$actDateEnd = Model_DatetimeHelper::adjustWeeks("sub", $dateEnd, $i);
				
				$totalSales = 0;
				$totalShopStock = 0;
				
				foreach($shopArr as $shop){	
					
					$stockShop = $this->initProStock($shop);
					$shopStock = $stockShop->getStockBalance(trim($product['code_product']),$arrShopMaping[$shop]);
					$totalShopStock += ($shopStock['BALANCE'])?$shopStock['BALANCE']:0;
					$invPro = $this->initAposInvPro($shop);
					
					$sales = $invPro->getProductSalesQtyByDates($actDateBegin ,$actDateEnd,trim($product['code_product']));
					
					//var_dump($sales);
					$totalSales +=$sales;	
				}				
				
				$arrPSales[$i]=$totalSales;
			}
			//var_dump($arrPSales);
			$stockWh = 0;
			
			$arrStockWh = $pStock->getStockBalance($product['code_product'],'WH');
			$stockWh = ($arrStockWh['BALANCE'])?$arrStockWh['BALANCE']:0;
			
			$pSales->addSales($product['code_product'], $arrPSales[0], $arrPSales[1], $arrPSales[2], $arrPSales[3], $arrPSales[4], $arrPSales[5], $arrPSales[6], $arrPSales[7],$stockWh,$totalShopStock);
			
			}
			}
			//ob_start();
			echo $cot++;
			//flush();
			//ob_flush();
			
		}
		
		echo 'Total execution time in seconds: ' . (microtime(true) - $time_start);
	
	}
	
	public function updateWhShopStockAction(){
		
		set_time_limit(0);
		
		
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
		
		$shopArr = array("ADPC","BBPC","BHPC","BSPC","BSIC","CBPC","CLPC","EPPC","KCPC","NLPC","PMPC","SLIC","WBPC","WBIC","WLIC","WGIC","WGPC","CSIC","CLIC","DCIC","PMIC","BSXP","FGIC","HPIC");
		
		$pSales = new Model_DbTable_Products_Sales(Zend_Registry::get('db_real'));
		
		$pStock = new Model_DbTable_Apos_Stock_Balanceold(Zend_Registry::get('db_oldapos'));		
		
		$pList = $pSales->listAll();
		foreach($pList as $key => $v){
			
			$codeProduct = $v["code_product"];
			$idSales = $v["id_sales"];
			
			$totalShopStock = 0;
			
			foreach($shopArr as $shop){
					
				$stockShop = $this->initProStock($shop);
				$shopStock = $stockShop->getStockBalance($codeProduct,$arrShopMaping[$shop]);
				$totalShopStock += ($shopStock['BALANCE'])?$shopStock['BALANCE']:0;
			}
			
			$stockWh = 0;
				
			$arrStockWh = $pStock->getStockBalance($codeProduct,'WH');
			$stockWh = ($arrStockWh['BALANCE'])?$arrStockWh['BALANCE']:0;
			
			$pSales->updateStock($idSales, $stockWh, $totalShopStock);
					
		}
		
	}
	
	public function patchKtAction(){
		set_time_limit(0);
		$pSales = new Model_DbTable_Products_Sales(Zend_Registry::get('db_real'));
		$tOld = new Model_DbTable_Apos_Stock_Ktdataold(Zend_Registry::get('db_oldapos'));
		$tNew = new Model_DbTable_Apos_Stock_Ktdatanew(Zend_Registry::get('db_apos'));
		$pList = $pSales->listAll();	

		foreach($pList as $k => $v){
			$sCode = trim($v['code_product']);
			
			$resOld = $tOld->thisWeekTransfer($sCode);
			$resNew = $tNew->thisWeekTransfer($sCode);
			
			$pSales->updateKt($v['id_sales'],($resOld + $resNew));
			
		}
		
	}
	
	public function stockAnalysisAction(){
		
		$pSales = new Model_DbTable_Products_Sales();
		
		$sDetail = new Model_DbTable_Order_Shippingdetail();
		$shipment = new Model_DbTable_Order_Shipment();
		
		$sList = $shipment->getOnTheSea();
		
		$arrShip = array();
		foreach($sList as $key => $ship){
			
			$arrShip[$ship['id_shipment']] = $ship['date_ship'];
		}
		
		
		//$ship = new Model_DbTable_Shipment();
		
		//$arrShip = array(
		//		 1 => "2013-09-01",
		//		 2 => "2013-10-11"
		//		);
		
		
		
		$pList = $pSales->listAll();
		
		$this->view->pList = $pList;
		$this->view->arrShip = $arrShip;
	}
	
	public function shopOnlineOrderAction(){
		// sold out item 
		// special order 
		// this weeks sales
		
		$shopCode = $this->_getParam("shop");
		
		$monday = Model_DatetimeHelper::getThisWeekMonday();
		$sunday = Model_DatetimeHelper::getThisWeekSunday();
		
		$date = Model_DatetimeHelper::adjustDays("add", $monday, 4);
		
		$dateRecord = Model_DatetimeHelper::dateToday();
		$timeRecord = Model_DatetimeHelper::timeNow();
		
		$extOrder = new Model_DbTable_Products_Stock_Extraorder();
		$spOrder = new Model_DbTable_Products_Stock_Advspecialorder();
		
		$sList = $extOrder->listByShop($shopCode, $date);
		
		
		$prod = new Model_DbTable_Productsva();
		$pList = $prod->listAllProducts();
		
		$this->view->pList = $pList;
		$this->view->sList = $sList;	
		
				
	}
	
	public function weeklyOrderCircleAction(){
		set_time_limit(0);
		ob_start();
		
		
		$shopArr = array("ADPC","BBPC","BHPC","BSPC","BSIC","CBPC","CLPC","EPPC","KCPC","NLPC","PMPC","SLIC","WBPC","WBIC","WLIC","WGIC","WGPC","CSIC","CLIC","DCIC","PMIC","BSXP","FGIC","HPIC");
		//$shopArr=array("HPIC");
		foreach($shopArr as $shop){
			echo $str="http://192.168.1.126/products/generate-weeks-order/shop/".$shop;
			echo "<br />";
		
			$res = file_get_contents($str);
			//sleep(1);
			echo $res;
			ob_flush();
			flush();
			//echo $date;
		}
		
	}
	
	public function generateWeeksOrderAction(){
		
		set_time_limit(0);
		
		$shop = $this->_getParam("shop");
		$isSpecial = $this->_getParam("spod");
		if($isSpecial == "yes"){$isSpecial =true;}
		else{
			
			$isSpecial = false;
		}
		
		$preFix = "PD";
		$datePD = Model_DatetimeHelper::getThisWeekMonday("");
		$dateOrder = Model_DatetimeHelper::getThisWeekMonday();
		
		$dateBegin = Model_DatetimeHelper::getLastWeekMonday();
		$dateEnd = Model_DatetimeHelper::getLastWeekSunday();
		if($isSpecial){
		
			$preFix = "SD"; // special second order 
			$datePD = Model_DatetimeHelper::dateToday(""); //thursday order
			$dateOrder = Model_DatetimeHelper::dateToday();
			
			$dateBegin = Model_DatetimeHelper::getLastWeekMonday(); // special order
			$dateEnd = Model_DatetimeHelper::dateYesterday(); // special order
			
			
		}
		
		

		$wk2Begin = Model_DatetimeHelper::adjustWeeks("sub", $dateBegin, 1);
		$wk2End = Model_DatetimeHelper::adjustWeeks("sub", $dateEnd, 1);
		
		$weeklyOrder = new Model_DbTable_Order_Weeklyorder(Zend_Registry::get('db_real'));
		
		$stock = Model_Aposinit::initProStock($shop);
		$stockWh = new Model_DbTable_Apos_Stock_Balanceold(Zend_Registry::get('db_oldapos'));
		$pd = new Model_DbTable_Apos_Stock_Productdes(Zend_Registry::get('db_oldapos'));
		
		$pro = new Model_DbTable_Productsva(Zend_Registry::get('db_real'));
		

		$noOrder = $preFix.$shop.$datePD;
	
		$invPro = Model_Aposinit::initAposInvPro($shop);
		$pList = $invPro->getProductSalesList($dateBegin,$dateEnd,$invPro::$_tbNames);
		d(self::$arrShopMaping[$shop]);
		foreach($pList as $key => $v){
			$stockShop = 0;
			$stockWarehouse = 0;

			$sCode = trim($v['SCODE']);
			$wk2Sales = $invPro->getProductSalesQtyByDates($wk2Begin, $wk2End, $sCode);
			$isHot = $invPro->isHotItem($sCode);
			
			$stShopLine = $stock->getStockBalance($sCode, self::$arrShopMaping[$shop]);
			$stockShop = ($stShopLine)?$stShopLine['BALANCE']:0;
			
			$stWhLine = $stockWh->getStockBalance($sCode,'WH');
			$stockWarehouse = ($stWhLine)?$stWhLine['BALANCE']:0;
			
			$maxSold = $invPro->maxSoldWeeks($sCode, $dateEnd,4);
			$isNew = $pro->isNewProduct($sCode);
			$isSp = false;
			if(substr($sCode,0,2)=="SP" && substr($sCode,0,7)!="SPEAKER"){
				if($shop == "WBPC" || $shop == "WBIC" || $shop == "EPIC" || $shop == "BSIC" ){
					$isSp = true;
				}	
			}
			$orderQty = $invPro->calOrderQty((int)$v["SOLDQTY"], $wk2Sales, $stockShop, $isHot,$maxSold,$isNew,$isSp);

			if(!in_array($sCode,self::$arrbarCodeFilter)){
				
				$desProductLine =$pd->getAposDes($sCode);
				 
				$weeklyOrder->initalorder($noOrder, $shop, $dateOrder, $sCode,(int)$v["SOLDQTY"], $wk2Sales, $stockShop, $stockWarehouse, $isHot,$orderQty,$maxSold,$desProductLine['DESCRIPT']);
			}
		}
		
		$remoteUrl = "http://192.168.1.124/products/export-weeks-order/shop/".$shop;
		
		if($isSpecial){
			
			$remoteUrl = "http://192.168.1.124/products/export-weeks-order/spod/yes/shop/".$shop;
		}
		echo $res = file_get_contents($remoteUrl);
		echo "It is Done";
	}
	
	public function exportWeeksOrderAction(){
		
		$orderBegin = Model_DatetimeHelper::getLastWeekMonday();
		$orderEnd = Model_DatetimeHelper::getLastWeekSunday();
		
		$dateOrder = Model_DatetimeHelper::getThisWeekMonday(); //This monday
		
		
		$isSpecial = $this->_getParam("spod");
		if($isSpecial == 'yes'){
			$orderBegin = Model_DatetimeHelper::getLastWeekMonday();// special order 
			$orderEnd = Model_DatetimeHelper::dateYesterday();
			$dateOrder = Model_DatetimeHelper::dateToday();
		}
		set_time_limit(0);
		$shop = $this->_getParam("shop");
		
	
		// special order 
		$dateExtraOrder = Model_DatetimeHelper::adjustDays("sub",$orderEnd,2); // Last Friday
		
		$orderWeekly = new Model_DbTable_Order_Weeklyorder();
		$orderSold = new Model_DbTable_Products_Stock_Extraorder();
		
		$pro = new Model_DbTable_Productsva();
		$location = new Model_Products_Location();
		
		//get order from weekly order 
		$arrWithQtyOrderWeekly = $orderWeekly->getWithQtyOrder($shop, $dateOrder);
		$arrWithQtyOrderExtra = $orderSold->getUnhandleExtraOrderWithQty($shop);
		//$arrWithQtyOrderExtra = array();
		$arrWoQtyOrderWeekly = $orderWeekly->getWithQtyOrder($shop, $dateOrder,'wo');
		$arrWoQtyOrderExtra = $orderSold->getUnhandleExtraOrderWithQty($shop,'wo');
		//$arrWoQtyOrderExtra = array();
		
		$arrWithQtyCombine = array();
		$arrBarcodeSort = array();
		$arrWithQtyBackOrder = array();
		$arrBarcodeSortBo = array();
		$arrWoQtyCombine = array();
		$arrBarcodeWoSort = array();
		$arrWoQtyBackOrder = array();
		$arrBarcodeSortBoWo = array();
		
		
		$orderNo = "";
		
		foreach($arrWithQtyOrderWeekly as $k => $orderWeeklyLine){

			if($k==0){
				$orderNo = $orderWeeklyLine['no_order'];
			}
			// put on order 
			$tmpArr = array();
			$tmpArr[] = $orderWeeklyLine['code_product'];
			$tmpArr[] = substr($orderWeeklyLine['status_hot'],0,3);
			$tmpArr[] = trim($orderWeeklyLine['des_product']);
			$tmpArr[] = $orderWeeklyLine['qty_sales_w1'];
			$tmpArr[] = $orderWeeklyLine['qty_max_sold'];
			$tmpArr[] = $orderWeeklyLine['stock_shop'];
			$tmpArr[] = $orderWeeklyLine['stock_wh'];
			$tmpArr[] = $orderWeeklyLine['qty_order'];
			
			// introduce date , later change by first sales Date
			$tmpArr[] = $pro->isNewProduct($orderWeeklyLine['code_product']);
			//max sold , qty = 0;
			if( ($orderWeeklyLine['qty_order'] + $orderWeeklyLine['stock_shop']) < $orderWeeklyLine['qty_max_sold'] ){
				$tmpArr[] = true;
			}
			else{
				$tmpArr[] = false;
			}
			//withOrder 
			$tmpArr[] = true;
			$tmpArr[] = $location->getPLByProduct($orderWeeklyLine['code_product']);
			if($orderWeeklyLine['stock_wh'] >0){
			$arrWithQtyCombine[] = $tmpArr;
			$arrBarcodeSort[]= $orderWeeklyLine['code_product'];
			}
			else{
				$arrWithQtyBackOrder[] = $tmpArr;
				$arrBarcodeSortBo[]= $orderWeeklyLine['code_product'];				
				
			}
		}
		
		foreach($arrWithQtyOrderExtra as $k2 => $orderExtraLine){
			
			$tmpArr = array();
			$tmpArr[] = $orderExtraLine['barcode'];
			$tmpArr[] = '---';
			$tmpArr[] = trim($orderExtraLine['description']);
			$tmpArr[] = 'X';
			$tmpArr[] = $orderExtraLine['max_sold'];
			$tmpArr[] = 0;
			$tmpArr[] = "-";
			$tmpArr[] = $orderExtraLine['qty'];

			
			$tmpArr[] = $pro->isNewProduct($orderExtraLine['barcode']);
			$tmpArr[] = false;
			$tmpArr[] = true;
			$tmpArr[] = $location->getPLByProduct($orderExtraLine['barcode']);
			if($isSpecial != "yes"){	
			$arrWithQtyCombine[] = $tmpArr;
			$arrBarcodeSort[]= $orderExtraLine['barcode'];	
			}
		}
		if($isSpecial != "yes"){
			array_multisort($arrBarcodeSort,SORT_ASC,$arrWithQtyCombine);
		}
		
	foreach($arrWoQtyOrderWeekly as $k3 => $orderWeeklyLine){

			// put on order 
			$tmpArr = array();
			$tmpArr[] = $orderWeeklyLine['code_product'];
			$tmpArr[] = substr($orderWeeklyLine['status_hot'],0,3);
			$tmpArr[] = trim($orderWeeklyLine['des_product']);
			$tmpArr[] = $orderWeeklyLine['qty_sales_w1'];
			$tmpArr[] = $orderWeeklyLine['qty_max_sold'];
			$tmpArr[] = $orderWeeklyLine['stock_shop'];
			$tmpArr[] = $orderWeeklyLine['stock_wh'];
			$tmpArr[] = $orderWeeklyLine['qty_order'];
			
			// introduce date , later change by first sales Date
			$tmpArr[] = $pro->isNewProduct($orderWeeklyLine['code_product']);
			//max sold , qty = 0;
			if( ($orderWeeklyLine['qty_order'] + $orderWeeklyLine['stock_shop']) < $orderWeeklyLine['qty_max_sold'] ){
				$tmpArr[] = true;
			}
			else{
				$tmpArr[] = false;
			}
			//withOrder 
			$tmpArr[] = false;
			$tmpArr[] = $location->getPLByProduct($orderWeeklyLine['code_product']);
			if($orderWeeklyLine['stock_wh'] >0){
				$arrWoQtyCombine[] = $tmpArr;
				$arrBarcodeWoSort[]= $orderWeeklyLine['code_product'];
			}
			else{
				$arrWoQtyBackOrder[] = $tmpArr;
				$arrBarcodeSortBoWo[]= $orderWeeklyLine['code_product'];
			}
		}
		
		foreach($arrWoQtyOrderExtra as $k4 => $orderExtraLine){
				
			$tmpArr = array();
			$tmpArr[] = $orderExtraLine['barcode'];
			$tmpArr[] = '---';
			$tmpArr[] = trim($orderExtraLine['description']);
			$tmpArr[] = 'X';
			$tmpArr[] = $orderExtraLine['max_sold'];
			$tmpArr[] = 0;
			$tmpArr[] = "-";
			
			if($orderExtraLine['order_option'] == 1){
				$tmpArr[] = $orderExtraLine['qty'];
			}
			elseif($orderExtraLine['order_option'] == 2 ){
				$tmpArr[] = '?';
			}
			$tmpArr[] = $pro->isNewProduct($orderExtraLine['barcode']);
			$tmpArr[] = false;
			$tmpArr[] = false;
			$tmpArr[] = $location->getPLByProduct($orderExtraLine['barcode']);
			if($isSpecial != "yes"){
			$arrWoQtyCombine[] = $tmpArr;
			$arrBarcodeWoSort[]= $orderExtraLine['barcode'];
			}
		}

		array_multisort($arrBarcodeWoSort,SORT_ASC,$arrWoQtyCombine);
		array_multisort($arrBarcodeSortBoWo,SORT_ASC,$arrWoQtyBackOrder);
		echo "<pre>";
		
		//var_dump($arrWithQtyCombine);
		
		echo "</pre>";
		
		//$arrWithQtyAll = array_merge($arrWithQtyCombine,$arrWithQtyBackOrder);
		
		$arrRes = array_merge($arrWithQtyCombine,$arrWithQtyBackOrder,$arrWoQtyCombine,$arrWoQtyBackOrder);
		
		$arrLocationSort = array();
		$arrNoLocation = array();
		$arrPosSort = array();
		
		foreach($arrRes as $key => $v){
			if($v[7]!=0){
			if($v[11]=="" ){
				
				$arrNoLocation[] = $v;
			
			}
			else{
				
				//cal location position
				$arrLetters = str_split($v[11]);
				$first = ord($arrLetters[0]);
				$second = ($arrLetters[0]=="B" || $arrLetters[0]=="D" ||$arrLetters[0]=="F" || $arrLetters[0]=="H" || $arrLetters[0]=="J" || $arrLetters[0]=="L"
				 || $arrLetters[0]=="N")?(10-(int)$arrLetters[1]):(int)$arrLetters[1];
				$last = (int)$arrLetters[2];

				$position = $first * 10000 + $second * 100 + $last;
				
				$arrPosSort[$key] = $position;
				$arrLocationSort[$key] = $v;
			
			}	
			}		
		}
		
		array_multisort($arrPosSort,SORT_ASC,$arrLocationSort);
		$arrRes = array_merge($arrLocationSort,$arrNoLocation);
				
		d($arrRes);
		
		$fl = new Model_Fileshandler();
		
		
		
		$exportFileName = "WK_".$shop."_".$orderBegin."_".$orderEnd."_".$orderNo;
		echo "<a href=\"http://192.168.1.124/export/sales/{$exportFileName}.xls\" >{$exportFileName}.xls</a>";
		$fl->exportWeeklySalesShop($arrRes, $exportFileName);
		
		echo "<br />File DOne";
		//get order from sold out item
		//barcode , hot , des, last max , onhand , wh, qty
		
	}
	public function orderCompareKtAction(){
		
		if($_POST){
			
			//$noOd = trim($_POST['no_od']);
			$noKt = trim($_POST['no_kt']);
			$shop = trim($_POST['shop']);		

		$fileName =  date("U").".csv";
		
		$arrComp = array();
		
		if(move_uploaded_file($_FILES['upload']['tmp_name'], $fileName)){
			
			$fl = fopen($fileName,"r");
			while(($lineData = fgetcsv($fl,1024))!=false){
				
				$arrComp[] = trim($lineData[0]);
				$arrComp[] = $lineData[7];
				$arrComp[] = $lineData[2];
			}
		}
		
			
		//$pOrder  = new Model_DbTable_Order_Weeklyorder(Zend_Registry::get('db_real'));
		//$soldOrder = new Model_DbTable_Products_Stock_Extraorder(Zend_Registry::get('db_real'));
		$proDes = new Model_DbTable_Apos_Stock_Productdes(Zend_Registry::get('db_oldapos'));
		$ktData = self::initKtData($shop);
		
		//$pList = $pOrder->getOrderByNo($noOd);
		
		//$soldOutOrderDate = Model_DatetimeHelper::adjustDays("sub", $pOrder->getOrderDate($noOd),3);
		//$soList = $soldOrder->getExtraOrderWithQty($shop, $soldOutOrderDate);
		
		
		
		$arrResEmpty = array();
		$arrResPart = array();
		$arrResExtra = array();
		//var_dump($pList);
		/*
		foreach($pList as $k1 => $v1){
			if($v1['qty_order'] > 0 && $v1['stock_wh'] >0){
			$arrComp[] = trim($v1['code_product']);
			$arrComp[] = $v1['qty_order'];
			$arrComp[] = $v1['des_product'];
			}
		}
		foreach($soList as $k2 => $v2){
			
			$arrComp[] = trim($v1['barcode']);
			$arrComp[] = $v1['qty'];
			$arrComp[] = $v1['description'];
		
		}
		*/		
		$ktList = $ktData->listKT($noKt,self::$arrShopMaping[$shop]);
		
		//var_dump($ktList);
		//var_dump($arrComp);
		
		foreach($ktList as $k3 => $v3){
			//if($v3['SCODE']=='660543012085'){echo "Code";}
			$key = array_search(trim($v3['SCODE']), $arrComp);
			//echo "THe KEY";
			//var_dump($key);
			if($key!==false){
				
				if((int)$v3['QTY'] < $arrComp[$key + 1]){
					$arrTmp = array($v3['SCODE'],$v3['QTY'],$arrComp[$key + 1],$arrComp[$key + 2]);
					
					$arrResPart[]= $arrTmp;
				}
				if((int)$v3['QTY'] > $arrComp[$key + 1]){
					$arrTmp = array($v3['SCODE'],$v3['QTY'],$arrComp[$key + 1],$arrComp[$key + 2]);
					
					$arrResExtra[] = $arrTmp;
					
				}
				$arrComp[$key] = null;
				$arrComp[$key + 1] = null;
				$arrComp[$key + 2] = null;
				
			}
			else{
				
				$proLine = $proDes->getAposDes(trim($v3['SCODE']));
				$arrTmp = array($v3['SCODE'],$v3['QTY'],0,$proLine['DESCRIPT']);
				$arrResExtra[] = $arrTmp;
			}
			
		}
		
		//var_dump($arrComp);
			
		// what left is what we should sent no send 
		$arrResEmpty = array_chunk(array_filter($arrComp,'strlen'),3);
		
		//echo "<pre>";
		//var_dump($arrResExtra,$arrResPart,$arrResEmpty);
		//echo "</pre>";
		$this->view->arrResExtra = $arrResExtra;
		$this->view->arrResPart = $arrResPart;
		$this->view->arrResEmpty = $arrResEmpty;
		
		}
			
	}
	
	public function ktCompareAction(){
		
	}
	
	public function getOldAposProductDescriptionAction(){
		
		$this->_helper->layout->disableLayout();
		
		$sCode = $this->_getParam('barcode');
		$proDes = new Model_DbTable_Apos_Stock_Productdes(Zend_Registry::get('db_oldapos'));
		$desLine = $proDes->getAposDes(trim($sCode));
		if(!$desLine) echo "Item Description Not Available,Please Verify Product Barcode";
		echo trim($desLine['DESCRIPT']);
		
	}
	public function matchAposBcodeAction(){
		
		$this->_helper->layout->disableLayout();
		
		$bCode = $this->_getParam("bcode");
		$proDes = Model_Aposinit::initProDes();
		$pLine = $proDes->matchBcode(trim($bCode));
		echo trim($pLine['SCODE']);
	}
	
	public function shopAnalysisPublicAction(){
		set_time_limit(0);
		
		$shop = $this->_getParam("shop");
		$url = "http://192.168.1.126/products/shop-analysis/shop/{$shop}";

		$email = $this->_getParam("em");
		if($email=="yes"){
			
			$result = Model_System_Downloadhelper::downloadUrl($url);
			$mail = new Model_Emailshandler();
			$sh = new Model_DbTable_Shoplocation();
			$shopMail = $sh->getShopMailByHead($shop);
			$mail->sendNormalEmail($shopMail,"shop Analysis", $result);
			$mail->sendNormalEmail("eco1@phonecollection.com.au", "Subject", $result);
			echo "Email Send You may close the page";
			
		}else{
			echo $result = Model_System_Downloadhelper::downloadUrl($url);
		}
	}
	
	public function listOfBackOrderAction(){
		
		//2. list all possible order 
		$this->view->arrBackOrder = $arrBackOrder = $this->listOrderByOption(1);
		$this->view->arrPossibleOrder = $arrPossibleOrder = $this->listOrderByOption(2);
		$this->view->arrStopOrder = $arrStopOrder =  $this->listOrderByOption(3);
		//3. list all stop sale list 
		
		//4. list all warehouse < 0
	}
	
	private  function listOrderByOption($orderOption){
		//send on friday night , run on 126 , get result and send by 124
		date_default_timezone_set("Australia/Melbourne");
		
		$extOrder = new Model_DbTable_Products_Stock_Extraorder(Zend_Registry::get('db_real'));
			
		$proDes = new Model_DbTable_Apos_Stock_Productdes(Zend_Registry::get('db_oldapos'));
		$stockOld =  new Model_DbTable_Apos_Stock_Balanceold(Zend_Registry::get('db_oldapos'));
				
		//1. list all Back order
		
		$arrBackOrder = array();
		
		$boList = $extOrder->getOrderByOption($orderOption);
		
		foreach($boList as $boRow){
				
			$key = array_search($boRow['barcode'], $arrBackOrder,true);
				
			if($key!== false){
		
				$arrBackOrder[$key + 1] = $arrBackOrder[$key + 1] + 1;
				$arrBackOrder[$key + 2] .=" , ".$boRow['shop_code'];
			}
			else{
				$arrBackOrder[] = $boRow['barcode'];
				$arrBackOrder[] = 1;
				$arrBackOrder[] = $boRow['shop_code'];
			}
				
		}
		$arrBackOrder = array_chunk($arrBackOrder,3);
		$arrBoSort = array();
		foreach($arrBackOrder as $bk => $bv){
		
			$arrBoSort[$bk] = $bv[1];
			$desLine  = $proDes->getAposDes($bv[0]);
			$arrBackOrder[$bk][] = $desLine['DESCRIPT'];
			$stLine = $stockOld->getStockBalance($bv[0],'WH');
			$arrBackOrder[$bk][] = $stLine['BALANCE'];	
		}
		array_multisort($arrBoSort,SORT_DESC,$arrBackOrder);

		return $arrBackOrder;
	}
	
	public function getRrpAction(){
		
		$this->_helper->layout->disableLayout();
		
		$sCode = $this->_getParam("b_code");
		$pro = Model_Aposinit::initProDes(Zend_Registry::get('db_oldapos'));
		$PLine = $pro->getAposDes($sCode);
		
		echo $PLine['APRICE'];
		
	}
	
	public function inputProductDetailAction(){
		// it is input and edit together
		
		
		
	}
	
	public function finderPublicAction(){
		
		$shopCode = trim($this->_getParam("shop"));
		
		if($_POST){
			
			
			$url = "http://192.168.1.126/products/finder/barcode/".$_POST['bar_code']."/shop/".$shopCode;
			
			if($shopCode == ""){
				
				$url = "http://192.168.1.126/products/finder/barcode/".$_POST['bar_code'];
			}
			$arrFinder = array(Model_DatetimeHelper::dateToday(),Model_DatetimeHelper::timeNow(),$_POST['bar_code'],($shopCode == "")?"WH":$shopCode);
			$fl = fopen("finderlog.csv","a");
			fputcsv($fl,$arrFinder);
			fclose($fl);
			
			//echo $url."<br />";
			echo $result = file_get_contents($url);
			
		}
		$this->view->shop = $shopCode;
		
	}
	public function finderAction(){
		// remove form last
		$barcode = trim($this->_getParam("barcode"));
		$shopCode = trim($this->_getParam("shop"));
		
		if($shopCode ==""){
			
			$shopCode = "WH";
		}
		
		$proDetail = new Model_Products_Detail(Zend_Registry::get('db_real'));
		$proDetailLine = $proDetail->getDetailByCode($barcode);
		if($proDetailLine){
			
			$this->view->proDetail = $proDetailLine['description_product'];
		}
		else{
			$this->view->proDetail = "";
		}
		
		//transfer barcode 
		$positionDash = strrpos($barcode,"-");
		$shortBarcode = $barcode;
		if($positionDash > 0){
			$shortBarcode = substr($barcode,0,$positionDash);
		}
		//echo $shortBarcode;
		//list same barcode all colors 
		
		$proDes = Model_Aposinit::initProDes(Zend_Registry::get('db_oldapos'));
		
		$colorCode = new Model_DbTable_Barcodecolorcodes(Zend_Registry::get('db_real'));
		
		
		
		$sList = $proDes->getScodeList($shortBarcode);
		
		$arrRes = array();
		
		foreach($sList as $k => $v){
			
			$tmpArr = array();
			$tmpArr[] = $v['SCODE'];
			$tmpArr[] = $v['DESCRIPT'];
			
			$positionDash = strrpos($v['SCODE'],"-");
			
			if($positionDash > 0 ){
			
			$idColor = str_replace("-", "",substr($v['SCODE'],$positionDash));
			$colorLine = $colorCode->getCode($idColor);
			$tmpArr[] = $colorLine['des_color'];
			$tmpArr[] = $colorLine['hex_code'];
			}
			else{
				
				$tmpArr[] = NULL;
				$tmpArr[] = NULL;
				
			}
			
			
			$arrStock = array();
			foreach(self::$arrShopMaping as $shopK => $shopV){
				$stk = Model_Aposinit::initProStock($shopK);
				 $stkLine = $stk->getStockBalance($v['SCODE'], $shopV);
				 $arrStock[$shopK] = $stkLine['BALANCE'];
			}
			$stkWh = new Model_DbTable_Apos_Stock_Balanceold(Zend_Registry::get('db_oldapos'));
			$stkLineWh = $stkWh->getStockBalance($v['SCODE'],'WH');
			$arrStock['WH'] = $stkLineWh['BALANCE'];
			$tmpArr[] = $arrStock;
			$tmpArr[] = $v['APRICE'];
		
			$arrRes[] = $tmpArr;			
		}
		
		$this->view->arrRes = $arrRes;
		$this->view->shop = $shopCode;
		$this->view->barcode = $barcode;
		// get stock 
	
	}
	
	public function finderByShortCodeAction(){
		
		$this->_helper->layout()->disableLayout();
		
		$shortCode = $this->_getParam("shortcode");
		
		$proDes = Model_Aposinit::initProDes(Zend_Registry::get('db_oldapos'));
		
		$sList = $proDes->getScodeList($shortCode);
		//d($sList);
		echo $k = base64_encode(serialize($sList));
		
		//d(unserialize(base64_decode($k)));
	}
	
	public function importFromWebAction(){
		//Once Off Function
		/*
		
		set_time_limit(0);
		// once off job 
		// run on 126 
		$proDes = Model_Aposinit::initProDes();
		
		$psAtt = new Model_DbTable_Psattribute(Zend_Registry::get('db_real'));
		$psPro = new Model_DbTable_Psproduct(Zend_Registry::get('db_real'));
		$psImg = new Model_DbTable_Psimage(Zend_Registry::get('db_real'));
		$proDetail = new Model_Products_Detail(Zend_Registry::get('db_real'));
		$proV1 = new Model_DbTable_Productsva(Zend_Registry::get('db_real'));
		
		$proList = $proDes->getAll();
		
		$cot = 0;
		foreach($proList as $k => $v){

		$sCode = trim($v['SCODE']);

			if(strpos($sCode,"STRAP-") === false && strpos($sCode,"STA-") === false ){
				// not strap and not stationary 
				$attLine = $psAtt->getByReference($sCode);
				if(!$attLine){
					//echo $sCode."<br/>";
					
				}else{
					$cot++;
					$idPro = $attLine['id_product'];
					$desLine = $psPro->getPsproduct($idPro);
					$des = $desLine['description'];
					
					$proV1Line = $proV1->getProductID($sCode);
					
					if($proV1Line){
						$proDetail->importDetail($proV1Line, $sCode, $des);
					}
					
					$idAtt = $attLine['id_product_attribute'];	
					
					$idImgLine = $psImg->getPsimage($idAtt);
					//$idImg = $idImgLine['id_image'];
					$strFolder = implode("/",str_split((string)$idImgLine['id_image']));
					
					echo $url = "http://www.phonecollection.com.au/shopping/img/p/".$strFolder."/".(string)$idImgLine['id_image']."-thickbox.jpg";
					//http://www.phonecollection.com.au/shopping/img/p/6/4/0/6/6406-thickbox.jpg
					echo "<br />";
					$folder = getcwd()."/downloadimg/";
					
					$result = Model_System_Downloadhelper::downloadUrl($url);
					
					$fl = fopen($folder.$sCode."_0.JPG","w");
					fputs($fl,$result);
					fclose($fl);
					
					///product_img/home_img/SAP3200LC100-17_0.jpg
				}			
			}
		}
		
		echo $cot;
		
		//list all apos barcode 
		//find barcode in att db 
		//find product id 
		//find item description 
		//find attribut id 
		//find image id 
		//download image and rename , thickbox image with watermark ,  if file exist just ignore it 
		
		*/
	}
	public function productEditorAction(){
		
		//scan barcode first 
		//see if already with details 
		//get answer yes or no 
		// if yes it is update 
		// if not it is insert  (product, product stock, product detail)
		
		$this->view->barcode = $sCode = $this->_getParam("barcode");
	
		//not handling 
		
		$positionDash = strrpos($sCode,"-");
		$shortBarcode = $sCode;
		if($positionDash > 0){
			$shortBarcode = substr($sCode,0,$positionDash);
		}
		
		$proDetail = new Model_Products_Detail();
		$pdLine = "";
		if($positionDash >0){
			$pdLine = $proDetail->getDetailByCode($sCode);
			if(!$pdLine){	
			$pdLine = $proDetail->getDetailByShortCode($shortBarcode);		
			$this->view->isNew = true;	
			}
		}
		else{
			$pdLine = $proDetail->getDetailByCode($sCode);
		}
		
		if(!$pdLine){
			$this->view->isNew = true;
		}
		else{
			
			$this->view->pdLine = $pdLine;
		}
		
		$prova = new Model_DbTable_Productsva();
		$proStock = new Model_DbTable_Productstock();
		
		$brand = new Model_DbTable_Barcodemanu();
		$model = New Model_DbTable_Barcodemodel();
		$subType = new Model_DbTable_Barcodeproductsubtype();
		
		$this->view->blist = $bList = $brand->listManu();
		$this->view->mlist = $mList = $model->listAllModel();
		$this->view->slist = $sList = $subType->listAllSubType();
		
		//var_dump($bList);
		if($_POST){
			
			//var_dump($_POST);
			
			if(isset($_POST["scan_barcode"])){
				//first time scan barcode
				$this->_redirect("/products/product-editor/barcode/".$_POST["scan_barcode"]);
				
			}
			else{
				//already have barcode and everything , update details
				if(isset($_POST["btn_add"])){
				// add new product
					// add into new product if 
					$idProduct = $prova->getProductID($sCode);
					if(!$idProduct){					
						$idProduct = $prova->addProduct($sCode,$_POST['apos_title']);
					}
					
					if(!$proStock->idExist($idProduct)){
						$proStock->insertStockWarehouse($idProduct, $_POST['initial_stock']);
					}
					$proDetail->addDetail($idProduct, 
							$_POST['barcode'], 
							$_POST['apos_title'],
							$_POST['mobile_brand'], 
							$_POST['mobile_model'], 
							$_POST['product_category'], 
							$_POST['web_title'],
							null, // chinese title
							$_POST['long_description'],
							null, //, $webpageDescription
							$_POST['product_feature'],
							$_POST['product_demisition'],
							$_POST['product_weight'],
							$_POST['introduce_date'],
							$_POST['url_all_color'],
							$_POST['url_single_color'],
							null, // image zip
							$_POST['supplier_name'],
							$_POST['barcode_supplier']
							);	
				}
				if(isset($_POST["btn_update"])){
				// 	update product 
				
					//introduct date 
					$idProduct = $_POST['id_product'];
					
					$proDetail->updateDetail($idProduct, 
							$_POST['barcode'], 
							$_POST['apos_title'],
							$_POST['mobile_brand'], 
							$_POST['mobile_model'], 
							$_POST['product_category'], 
							$_POST['web_title'],
							null, // chinese title
							$_POST['long_description'],
							null, //, $webpageDescription
							$_POST['product_feature'],
							$_POST['product_demisition'],
							$_POST['product_weight'],
							$_POST['introduce_date'],
							$_POST['url_all_color'],
							$_POST['url_single_color'],
							null, // image zip
							$_POST['supplier_name'],
							$_POST['barcode_supplier']
							);		
				}
				
				//as long as post check file
				
				
				if(file_exists($_FILES['file_all_color']['tmp_name']) && is_uploaded_file($_FILES['file_all_color']['tmp_name'])) {
					//echo 'No upload';
					$folder = getcwd()."/product_img/home_img/";
					move_uploaded_file($_FILES['file_all_color']['tmp_name'], $folder.trim($_POST['barcode'])."_99.jpg");
					
				}
				
				if(!file_exists($_FILES['file_single_color']['tmp_name']) || !is_uploaded_file($_FILES['file_single_color']['tmp_name'])) {
					//echo 'No upload';
					$folder = getcwd()."/product_img/home_img/";
					move_uploaded_file($_FILES['file_single_color']['tmp_name'], $folder.trim($_POST['barcode'])."_0.jpg");
				}
				
				
				$this->_redirect("/products/product-editor/barcode/{$sCode}");
			}
			
			
			
		}
		
		
		
		//check barcode in the product, in product detail , if not it is brand new stock , ask apos title and stock 
		// if product exist then only add 
		
		
		
		
	}
	public function getCurrentStockAction(){
		
		$this->_helper->layout->disableLayout();
		
		$code = $this->_getParam("barcode");
		$shop = $this->_getParam("shop");
		if($shop == ""){
			
			$shop = "WH";
		}
			$stkWh = new Model_DbTable_Apos_Stock_Balanceold(Zend_Registry::get('db_oldapos'));
			$stkLineWh = $stkWh->getStockBalance($code,$shop);
			echo (int)$stkLineWh['BALANCE'];
		
	}
	
	public function newsletterOnlineAction(){
		
		$manu = $this->_getParam("manu");
		$model = $this->_getParam("model");
		$type = $this->_getParam("type");
		
		
		$manus = new Model_DbTable_Barcodemanu();
		$models = new Model_DbTable_Barcodemodel();
		$products = new Model_Products_Detail();
		$arrModelList = array();
		
		if($manu == ""){
			//main page 
			$manuList = $manus->listManu();	
			$this->view->assign("manuList",$manuList);
			
			//d($manuList);
			
			foreach($manuList as $manuName){
				$modelList = $models->listModelByManuCode($manuName['code_barcode']);
				$arrModelList[$manuName['code_barcode']] = $modelList;
				
			}
			$this->view->assign("modelList",$arrModelList);
		}

		d($manuList,$arrModelList);
		if($manu !="" && $model !="" ){
			// show types 
			//$pList = 
			
		}
		
		
	}
		
	private static function bCodeFilter($sCode){
				
		$sCode = trim($sCode);
		$sCode3 = substr($sCode,0,3);
		$sCode4 = substr($sCode,0,4);
		$sCode5 = substr($sCode,0,5);
		$sCode7 = substr($sCode,0,7);
		
		$res = false;
		
		if($sCode3 == "ONL" || $sCode4 == "STA-" || $sCode4 == "PC00" || $sCode5 == "STRAP" || $sCode5 == "CREAT" || $sCode5 == "SCI-0" || $sCode7 == "DELITEM" 
			|| $sCode7 == "BATCNOK" || $sCode7 == "BATOMOT" || $sCode7 == "BATONOK" || $sCode7 == "BATOSE-" || $sCode7 == "PCSCREA" || $sCode7 == "VIPGIFT" ||
			$sCode == "CN" ||  $sCode == "INSTALL" || $sCode == "INSTALLATION" || $sCode == "FAULTY" || $sCode == "VOUCHER" || $sCode == "PHONEREPAIR" || 
			$sCode == "SERVICE" || $sCode == "PARTSSALES"){
			
			$res = true;
			}
		
		return $res;
		
	}
	
}
?>