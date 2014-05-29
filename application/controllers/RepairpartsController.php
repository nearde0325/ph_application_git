<?php 
/**

 */
class RepairpartsController extends Zend_Controller_Action
{

	public static $rCenterMapping;
	public static $rCenterMappRev;
	
	public static $rCenterEmail = array(
			
			1 => "bsxprepair@phonecollection.com.au ",
			2 => "hope.mcmahon@phonecollection.com.au",
			3 => "steven.xie@phonecollection.com.au",
			4 => "boxhill@phonecollection.com.au",
			5 => "highpointic@phonecollection.com.au",
			6 => "cranbourne@phonecollection.com.au",
			7 => "westlake@phonecollection.com.au",
			8 => "epping@phonecollection.com.au",
			9 => "werribee@phonecollection.com.au",
			10 => "northland@phonecollection.com.au",
			11 => "doncasteric@phonecollection.com.au",
			12 => "knox@phonecollection.com.au",
			13 => "southlandic@phonecollection.com.au",
			14 => "fountaingateic@phonecollection.com.au",
			15 => "parkmoreic@phonecollection.com.au",
			16 => "watergardenic@phonecollection.com.au",
			17 => "arndale@phonecollection.com.au",
			18 => "brimbank@phonecollection.com.au",
			19 => "colonnades@phonecollection.com.au",
			20 => "lu.zhan@phonecollection.com.au",
			21 => "baysideic@phonecollection.com.au"			
		);
    public function init(){
    /**
	 *
	 *
	 */   
    	self::$rCenterMapping = unserialize(ARR_RCENTER_MAPPING);
    	self::$rCenterMappRev = unserialize(ARR_RCENTER_REV_MAPPING);
	}

    public function indexAction(){
	
		//echo "shomething";	

    
	}
	public function controlPanelAction(){
		
	}
		
	public function warehouseOpeningAction(){
		
		$rProducts = new Model_DbTable_Pr_Prproducts();
		//$rStock = new Model_DbTable_Pr_Prstock();
		
		$file = getcwd()."/tmp/price.csv";
		$fl = fopen($file,"r");
		while(($lineData = fgetcsv($fl))!= FALSE){

			//line Data 0 Code 1 Des  2 3 stock\
			$codeProduct = $lineData[0];
			$titleProduct = $lineData[1];
			$priceA = $lineData[2];
			$priceB = $lineData[3];
			$cate = $lineData[4]; 
			
			//new Products 
			if(!$rProducts->ifExist($codeProduct)){
				
				$idProduct = $rProducts->addProduct($cate, $codeProduct, $titleProduct);
				$rProducts->updatePrice($idProduct, $priceA, $priceB,0);
				//$rStock->insertStockWarehouse($idProduct, $whStock);				
			}
			else{
				
				echo "Duplicated BarCode Should Happen In Opening!<br />";
				
			}
			//insert into stock db 
		}
		
		
	}
	
	
	public function shopOpeningAction(){
		
		
		$rProducts = new Model_DbTable_Pr_Prproducts();
		$rStock = new Model_DbTable_Pr_Prstock();
		
		$idShop = 1;
		if(isset($_GET['shop'])){
			$idShop = $_GET['shop'];
		}
		
		$file = getcwd()."/tmp/pstockshop.csv";
		$fl = fopen($file,"r");
		while(($lineData = fgetcsv($fl))!= FALSE){
		
			//line Data 0 Code 1 Des  2 3 stock\
			$codeProduct = $lineData[0];
			$shopStock = $lineData[3];
				
			//new Products
			if(!$rProducts->ifExist($codeProduct)){
				
				echo "ERROR OCCUR,This Code Not Exist AT All:".$codeProduct;
			}
			else{
				
				$idProduct = $rProducts->getProductID($codeProduct);
				$rStock->updateStockShop($idProduct, $shopStock, $idShop);	
				
			}
			//insert into stock db
		}		
	}
	public function warehouseGoodsReceiveAction(){

		
	}	
	public function saveWhGiAction(){
		
		$rProducts = new Model_DbTable_Pr_Prproducts();
		$rStock = new Model_DbTable_Pr_Prstock();
		$rMove = new Model_DbTable_Pr_Prstockmovement();
		$rCounter = new Model_DbTable_Pr_Counter();
		$dateToday = Model_DatetimeHelper::dateToday("");
		$rGN="";
		$productCode = "";
		$productQty = 0;
		
		if($_POST){
		
			$rGN = "RGN".$dateToday;
			$rGN .= $rCounter->runcounter("RGNCOUNTER");
			
			foreach($_POST['upload'] as $key => $value){
		
			$productCode = "";
			$productTitle = "";
			$productCate = "";
			$productQty = 0;
		
			$arrtmp = explode("|",$value);
			$productCode = strtoupper(trim($arrtmp[0]));
			$productCate = trim($arrtmp[1]);
			$productTitle = trim($arrtmp[2]);
			$qty = trim($arrtmp[3]);
		
			//if not in the datebase then add into product database
		
			if(!$rProducts->ifExist($productCode)){
						
				$idProduct = $rProducts->addProduct($productCate, $productCode,$productTitle);
				$rStock->insertStockWarehouse($idProduct, $qty);
			}
			else{
				//item already EXIST
				$idProduct = $rProducts->getProductID($productCode);
				$rStock->increaseStockWarehouse($idProduct, $qty);
			}
			
			$rMove->addMovement(1, $idProduct, $qty,$_POST['staff_name'],0,$rGN);
		}

		}
		
		$this->view->RGN = $rGN;
	}
	
	public function printBarcodeAction(){
		
		$cate = $this->_getParam('cate');
		$this->view->idCate = $cate;
		$rProducts = new Model_DbTable_Pr_Prproducts();		
		$this->view->plist = $rProducts->listProductsByCate($cate);
				
	}
	public function transferPartsToShopAction(){
		
		$idShop = 1;
		$tmpvar=$this->_getParam('shop');
		if($tmpvar != ""){
			$idShop = $tmpvar;
		}
		
		$this->view->idShop = $idShop;
		
		$rProduct = new Model_DbTable_Pr_Prproducts();
			
		$this->view->pList = $pList = $rProduct->listAllProducts();
		
		
	}
	public function saveWhTiAction(){
		
		
		$rProducts = new Model_DbTable_Pr_Prproducts();
		$rStock = new Model_DbTable_Pr_Prstock();
		$rMove = new Model_DbTable_Pr_Prstockmovement();		
		$productCode = "";
		$productQty = 0;
		
		$rCounter = new Model_DbTable_Pr_Counter();
		$dateToday = Model_DatetimeHelper::dateToday("");
		$rKT="";
		
		$this->view->idShop = 0;
		
		if($_POST){
			
			$this->view->idShop = $_POST['id_shop'];
			$rKT = "RKT".$dateToday;
			$rKT .= $rCounter->runcounter("RKTCOUNTER");
			
			foreach($_POST['upload'] as $key => $value){
			
				$productCode = "";
				$productQty = 0;
			
				$arrtmp = explode("[",$value);
			
				$productCode = $arrtmp[0];
				$qty = (int)trim(str_replace("]","",$arrtmp[1]));
			
				//It should be in the Database 
			
				if(!$rProducts->ifExist($productCode)){
					
					echo "ERROR OCCUR,This Code Not Exist AT All:".$productCode;
				}
				else{
					//item already EXIST
					$idProduct = $rProducts->getProductID($productCode);
					
					//warehouse stock -
					$rStock->descreaseStockWarehouse($idProduct, $qty);					
					//shop stock + 
					$rStock->increaseStockShop($idProduct, $qty, $_POST['id_shop']);
					//record movement 
					$rMove->addMovement(3, $idProduct, $qty,$_POST['staff_name'],$_POST['id_shop'],$rKT);
			
				}
			
			}	

			$this->view->RKT = $rKT;
			
			
		}		
		
	}
	
	public function warehouseStockMainAction(){
		
	}
	
	public function warehouseStockTakeAction(){
		
		$idCate = $this->_getParam('cate');
		$showPrice= "";
		$showPrice= $this->_getParam('show');
		$this->view->showPrice = $showPrice;
		$priceChange = false;
		
		$rCate = new Model_DbTable_Pr_Category();
		$rPriceCate = new Model_DbTable_Pr_Pricecategory();
		$this->view->rActList = $rPriceCate->listAllActiveEdit(); 
		$this->view->cList = $rCate->listCategory();
		
		
		$this->view->dateToday = $dateToday = Model_DatetimeHelper::dateToday();
		
		$rProducts = new Model_DbTable_Pr_Prproducts();
		
		if($_POST){
				
			if($_POST["password"]=="Mon80ash"){
		
				foreach($_POST["price_a"] as $key => $v){
						
					$line = $rProducts->getProduct($key);
						
					foreach($this->view->rActList as $ka => $va){
						
						$cateString = $va['id_price_category'];
						if(isset($_POST[$cateString][$key])){
							//var_dump($cateString,$key,$line);
							
							if($_POST[$cateString][(string)$key] != $line[$cateString]){
								
								$rProducts->updateSinglePrice($key, $cateString, $_POST[$cateString][$key]);
								$priceChange = true;
							}	
							
						}
					}
					
					//if($v != $line["price_a"] || $_POST["price_b"][$key] != $line["price_b"] || $_POST["price_c"][$key] != $line["price_c"]){
					//	$rProducts->updatePrice($key, $v,$_POST["price_b"][$key], $_POST["price_c"][$key],$dateToday);
					//	$priceChange = true;
					// }
					
					if(isset($_POST["act_cost"])){
					$rProducts->updateCost($key, $_POST["act_cost"][$key], $dateToday);
					}
					$rProducts->updateDes($key, $_POST["des"][$key]);
					
					
				}
				if($priceChange){
					$updates = new Model_DbTable_Updatenews();

					//if()
					$updates->addUpdate(5,"Repair Parts Price/Description Has been Updated on ".Model_DatetimeHelper::dateToday(),0);
				
				}
				echo "Price Updated!!";
			}
			else{
		
				echo "Password Wrong!!";
			}

		}		
		
		$this->view->idCate = $idCate;
		
		$this->view->pList = $rProducts->listProductsByCate($idCate);
		

		
	}
	
	public function stockListAllAction(){
	
		$idCate = 0;
		$idCate = $this->_getParam('cate');
	
		$rProducts = new Model_DbTable_Pr_Prproducts();
		
		$rCate = new Model_DbTable_Pr_Category();
		$this->view->cList = $rCate->listCategory();
		
	
		$this->view->idCate = $idCate;
	
		$this->view->pList = $rProducts->listProductsByCate($idCate);
	
		$this->view->dateToday = Model_DatetimeHelper::dateToday();
	
	}
	
	public function warehouseAdjustInAction(){
		
	}
	
	public function saveWhAdjustInAction(){
		
		
		$rProducts = new Model_DbTable_Pr_Prproducts();
		$rStock = new Model_DbTable_Pr_Prstock();
		$rMove = new Model_DbTable_Pr_Prstockmovement();
		$productCode = "";
		$productQty = 0;
		$idWarehouse = 0;
		$dateTody = Model_DatetimeHelper::dateToday("");
		$timeNow = Model_DatetimeHelper::timeNow("");
		$aINo = "AD".$dateTody.$timeNow;
		
		if($_POST){
		
		
		
			foreach($_POST['upload'] as $key => $value){
					
				$productCode = "";
				$productQty = 0;
					
				$arrtmp = explode("[",$value);
		
				$productCode = $arrtmp[0];
				$qty = (int)trim(str_replace("]","",$arrtmp[1]));
					
				//It should be in the Database
					
				if(!$rProducts->ifExist($productCode)){
		
					echo "ERROR OCCUR,DATA NOT SAVE,This Code Not Exist AT All:".$productCode;
				}
				else{
					//item already EXIST
					$idProduct = $rProducts->getProductID($productCode);
		
					//shop stock -
					$rStock->increaseStockWarehouse($idProduct, $qty);
					//record movement
					if($qty >0){
						$rMove->addMovement(5, $idProduct, $qty,$_POST['staff_name'],$idWarehouse,$aINo);
					}
					else{
						$rMove->addMovement(6, $idProduct, 0 - $qty,$_POST['staff_name'],$idWarehouse,$aINo);
					}
					
					$tmpStock = $rStock->getWarehouseStock($idProduct);
					$rMove->addMovement(9, $idProduct, $tmpStock,$_POST['staff_name'],$idWarehouse,$aINo);
						
				}
					
			}
		
		
		}
		
		$this->view->AINO = $aINo;
		
		
	}
	public function saveWhAdjustOutAction(){

		$rProducts = new Model_DbTable_Pr_Prproducts();
		$rStock = new Model_DbTable_Pr_Prstock();
		$rMove = new Model_DbTable_Pr_Prstockmovement();
		$productCode = "";
		$productQty = 0;
		$idWarehouse = 0;
		$dateTody = Model_DatetimeHelper::dateToday("");
		$timeNow = Model_DatetimeHelper::timeNow("");
		$aINo = "AI".$dateTody.$timeNow;
		
		if($_POST){
		
		
		
			foreach($_POST['upload'] as $key => $value){
					
				$productCode = "";
				$productQty = 0;
					
				$arrtmp = explode("[",$value);
		
				$productCode = $arrtmp[0];
				$qty = (int)trim(str_replace("]","",$arrtmp[1]));
					
				//It should be in the Database
					
				if(!$rProducts->ifExist($productCode)){
		
					echo "ERROR OCCUR,DATA NOT SAVE,This Code Not Exist AT All:".$productCode;
				}
				else{
					//item already EXIST
					$idProduct = $rProducts->getProductID($productCode);
		
					//shop stock -
					$rStock->descreaseStockWarehouse($idProduct, $qty);
					//record movement
					$rMove->addMovement(6, $idProduct, $qty,$_POST['staff_name'],$idWarehouse,$aINo);						
					$tmpStock = $rStock->getWarehouseStock($idProduct);
					$rMove->addMovement(9, $idProduct, $tmpStock,$_POST['staff_name'],$idWarehouse,$aINo);
		
				}
					
			}
		
		
		}
		
		$this->view->AINO = $aINo;
		
				
		
		
	}

	public function shopTransferListAction(){
		
	}
	public function stockAlertAction(){
		
		$idCate = 0;
		$idCate = $this->_getParam('cate');
		
		$rProducts = new Model_DbTable_Pr_Prproducts();
		$rCate = new Model_DbTable_Pr_Category();
		$this->view->cList = $rCate->listCategory();	
		
		$this->view->idCate = $idCate;
		
		$rList = $rProducts->listProductsByCate($idCate);
		
		$this->view->dateToday = Model_DatetimeHelper::dateToday();
		$dateTwoWeeks =  Model_DatetimeHelper::dateToday();
		$dateTwoWeeks = Model_DatetimeHelper::adjustDays("sub",$dateTwoWeeks,14);
		$dateOneWeek = Model_DatetimeHelper::adjustDays("sub",Model_DatetimeHelper::dateToday(),7);
		$this->view->dateTwoWeeks = $dateTwoWeeks;
		$this->view->dateOneWeek = $dateOneWeek;
	
		if($_POST){
			//disactive all
			//var_dump($_POST);
			
			foreach($rList as $key => $v){
				if(isset($_POST['active'][$v['id_product']])){
					
					$rProducts->activeProduct($v['id_product']);
				}
				else{
					$rProducts->activeProduct($v['id_product'],true);
				}
				
			}
			
		echo "Record Saved<br/>";
		}
		$rList = $rProducts->listProductsByCate($idCate);
		$this->view->pList = $rList;
		
	}
	
	//Shop Function 
	
	/*	1. Parts Consumation V
	 *  2. Transfer Back 
	 *  3. Error Correction
	 *  4. Stock Take
	 *  4.1 stock take adjustment
	 *  
	 * 
	 */
	public function partsRecordWithBorrowAction(){
		
		$idShop = $this->_getParam('shop');
		$idJob = $this->_getParam('jobid');
		$shopHead = $this->_getParam('shophead');
		$this->view->erorMessage = "";
		
		$rJob = new Model_DbTable_Repairjob();
		$rProduct = new Model_DbTable_Pr_Prproducts();
		$rCategoryModel = new Model_DbTable_Pr_Categorymodel();
		$rStatus = new Model_DbTable_Repairstatusrecord();
		$this->view->freeze = false;
		
					
		if($idJob=="" || $idShop==""){				

			$this->view->errorMessage = "ERROR OCCUR, DO NOT PROCEED,PLEASE START FROM THE STATUS PAGE";
		}
		
		else{
						
			if($idJob == "BORROWPARTS"){
				if($idShop < 10){
					$idJob = "80".$idShop.Model_DatetimeHelper::dateToday("").Model_DatetimeHelper::timeNow("");
				}
				else{
					$idJob = "8".$idShop.Model_DatetimeHelper::dateToday("").Model_DatetimeHelper::timeNow("");
				}
			}
			
			if(substr($idJob,0,1) == 8 || strtolower($idJob) == "parts"){
				
				$pList = $rProduct->listAllActive();
			}
			else{
				$lastStatus = $rStatus->getLastStatus($idJob);
				
				if($lastStatus[0]['id_status']>= 80){
					
					$this->view->freeze = true;
				}
				
				
				$catRow = $rJob->getJob($idJob);
				$idCate = $rCategoryModel->getIdCategory(trim($catRow['mobile_model']));
				$idCate = $idCate['id_category'];
				if(!$idCate || $idCate == 99){
					
					$pList = $rProduct->listAllActive();
				}
				else{
					
					$pList = $rProduct->listAllActiveCategory($idCate);
				}
				
			}
			
			
			$this->view->idshop = $idShop;
			$this->view->idjob = $idJob;	
			$this->view->shopHead = $shopHead;		
		}
		
		
		
			
		$this->view->pList = $pList;
				
	}
	public function partsRecordAction(){
	
		$idShop = $this->_getParam('shop');
		$idJob = $this->_getParam('jobid');
		$shopHead = $this->_getParam('shophead');
		$this->view->erorMessage = "";
			
		if($idJob=="" || $idShop==""){
	
			$this->view->errorMessage = "ERROR OCCUR, DO NOT PROCEED,PLEASE START FROM THE STATUS PAGE";
		}
	
		else{
			$this->view->idshop = $idShop;
			$this->view->idjob = $idJob;
			$this->view->shopHead = $shopHead;
		}
	
	
		$rProduct = new Model_DbTable_Pr_Prproducts();
			
		$this->view->pList = $pList = $rProduct->listAllActive();
	
	}	
	public function saveShopGoAction(){

		$rProducts = new Model_DbTable_Pr_Prproducts();
		$rStock = new Model_DbTable_Pr_Prstock();
		$rMove = new Model_DbTable_Pr_Prstockmovement();
		$productCode = "";
		$productQty = 0;
		$this->view->idShop = 0;
		$this->view->errorMessage = "";
		
		if($_POST){
		
				
			$this->view->idShop = $_POST['id_shop'];
				
			foreach($_POST['upload'] as $key => $value){
					
				$productCode = "";
				$productQty = 0;
					
				$arrtmp = explode("[",$value);
					
				$productCode = $arrtmp[0];
				$qty = (int)trim(str_replace("]","",$arrtmp[1]));
					
				//It should be in the Database
					
				if(!$rProducts->ifExist($productCode)){
						
					$this->view->errorMessage.= "ERROR, BarCode '".$productCode."' NOT EXIST, Record(s) Can NOT Save!!<br />";
				}
				else{
					//item already EXIST
					$idProduct = $rProducts->getProductID($productCode);
						
					//shop stock -
					$rStock->descreaseStockShop($idProduct,$qty,$_POST['id_shop']);
					//record movement
					if($qty>0){	
					$rMove->addMovement(2, $idProduct, $qty,$_POST['staff_name'],$_POST['id_shop'],$_POST['id_job']);						
					}
					else{
						$rMove->addMovement(7, $idProduct, 0 - $qty,$_POST['staff_name'],$_POST['id_shop'],$_POST['id_job']);
					}
				
				}
					
			}
				
				
		}
				
	
	
	}
	public function saveShopGoWithBorrowAction(){
		
		date_default_timezone_set("Australia/Melbourne");
	
		$rProducts = new Model_DbTable_Pr_Prproducts();
		$rStock = new Model_DbTable_Pr_Prstock();
		$rMove = new Model_DbTable_Pr_Prstockmovement();
		$loan = new Model_DbTable_Pr_Loan();
		$stDetail = new Model_DbTable_Roster_Stafflogindetail();
		$productCode = "";
		$productQty = 0;
		$this->view->idShop = 0;
		$this->view->errorMessage = "";
		$arrRes = array();
	
		if($_POST){
	
	
			$this->view->idShop = $_POST['id_shop'];
	
			foreach($_POST['upload'] as $key => $value){
					
				$productCode = "";
				$productQty = 0;
				$idBorrow = 0;
					
				$arrtmp = unserialize(gzuncompress(base64_decode(trim($value))));
					
				$productCode = $arrtmp[0];
				$qty = (int)$arrtmp[1];	
				$isBorrow = $arrtmp[2];
				
				
				$idJob = $_POST['id_job'];
				$shopFrom = $arrtmp[3];
				
				$shopTo = $_POST['id_shop'];
				
				//$idProduct =$rProducts->getProductID($productCode);
				$bCode = "";
				

				
				$idMove = 0;
				//$shortIdJob = 
				// id Product
				//YEAR/MONTH/DATE/HOUR/MIN
					
				//It should be in the Database
			//	$loan->addLoan($bCode, $idJob, $idStockmove, $shopFrom, $shopTo, $idProduct, $codeProduct, $dateLoan, $timeRecord, $idStaff)
			
					
				if(!$rProducts->ifExist($productCode)){
	
					$this->view->errorMessage.= "ERROR, BarCode '".$productCode."' NOT EXIST, Record(s) Can NOT Save!!<br />";
				}
				else{
					//item already EXIST
					$idProduct = $rProducts->getProductID($productCode);
					
					$borrowOk = false;
					
						// if it is borrow with out job id 
						
						//shop stock -
						if(substr($_POST['id_job'],0,1) != 8){
						$rStock->descreaseStockShop($idProduct,$qty,$_POST['id_shop']);
						//record movement
						if($qty>0){
							$idMove = $rMove->addMovement(2, $idProduct, $qty,$_POST['staff_name'],$_POST['id_shop'],$_POST['id_job']);
						}
						else{
							$idMove = $rMove->addMovement(7, $idProduct, 0 - $qty,$_POST['staff_name'],$_POST['id_shop'],$_POST['id_job']);
						}
						}
					
					if($isBorrow){
					
						$bCode = $loan->buildBorrowCode($shopFrom, $shopTo, $idProduct, $idJob);
					
						$stLine = $stDetail->checkPasswordCorrect(Model_EncryptHelper::hashsha(trim($_POST['att_pass'])));
						//var_dump($stLine);
						if($stLine){
							$loan->addLoan($bCode, $idJob, $idMove, $shopFrom, $shopTo, $idProduct, $productCode,Model_DatetimeHelper::dateToday(),Model_DatetimeHelper::timeNow(), $stLine['id'],$qty);
							$borrowOk = true;
							
							$arrtmp[] = $bCode;
							
							//Sending Emails 
							
							$url = "http://115.64.171.97:1080/repairparts/confirm-borrow/bcode/{$bCode}";
							
							$mail = new Model_Emailshandler();
							@$mail->sendNormalEmail(self::$rCenterEmail[$shopTo],"Confirm Borrow Part(s), Follow Link Please", $url,"order@phonecollection.com.au");
								
						}
					}
					$arrRes[] = $arrtmp;	
				}
					
			}
	
		$this->view->arrRes = $arrRes;
		}
	
	
	
	}	
	
	public function confirmBorrowAction(){
		$loan = new Model_DbTable_Pr_Loan();
		$stDetail = new Model_DbTable_Roster_Stafflogindetail();
		
		$rStock = new Model_DbTable_Pr_Prstock();
		$rMove = new Model_DbTable_Pr_Prstockmovement();
		$rPro = new Model_DbTable_Pr_Prproducts();
		
		$bCode = $this->_getParam("bcode");
		
		if($bCode!=""){
			
		$borrowLine = $loan->getByBorrowCode($bCode);
			
		$this->view->borrowLine = $borrowLine;
		$this->view->stLine = $stDetail->getDetail($borrowLine['id_staff']);
		$this->view->rpLine = $rPro->getProduct($borrowLine['id_product']);
		
		//var_dump($borrowLine);
		
		if($_POST){ 
				$passwordLine = $stDetail->checkPasswordCorrect(Model_EncryptHelper::hashsha(trim($_POST['pwd'])));
				// you can not confirm yourself
				$idConfirm = $passwordLine['id'];
				
				$idStaffBorrow = $borrowLine['id_staff'];
					
			if($passwordLine){
				
				if($passwordLine['id'] != $idStaffBorrow ){
					
				
					$loan->confirmBorrowByCode($bCode, $passwordLine['id']);
					$rStock->increaseStockShop($borrowLine['id_product'], 0 - $borrowLine['qty_product'], $borrowLine['shop_from']);
					$rMove->addMovement(4,$borrowLine['id_product'], $borrowLine['qty_product'], "BorrowFrom",$borrowLine['shop_from'],$bCode);
					$rStock->increaseStockShop($borrowLine['id_product'], $borrowLine['qty_product'], $borrowLine['shop_to']);
					$rMove->addMovement(3,$borrowLine['id_product'], $borrowLine['qty_product'], "LendTo",$borrowLine['shop_to'],$bCode);
									
					echo "Borrow Code Confirmed, You may CLose the Page Now";
					// changing the stock  accordinglly
					
					// add move status 
					
					
				}
				else{
					echo "You can Not Confirm The Borrow Action Of Yourself,You Will Need Another One Confirm For You";
				}
				
			}
			else{
				
				echo "Password Is Incorrect";
			}
		}
		
		}	
	}
	
	public function errorFixAction(){
		
		$idShop = $this->_getParam('shop');
		$idJob = $this->_getParam('jobid');
		
		$this->view->erorMessage = "";
			
		if($idShop==""){
		
			$this->view->errorMessage = "ERROR OCCUR, DO NOT PROCEED,PLEASE START FROM THE STATUS PAGE";
		}
		
		else{
			$this->view->idshop = $idShop;
			$this->view->idjob = $idJob;
			
		}
		
	}
	
	public function saveShopEiAction(){
		
		$rProducts = new Model_DbTable_Pr_Prproducts();
		$rStock = new Model_DbTable_Pr_Prstock();
		$rMove = new Model_DbTable_Pr_Prstockmovement();
		$productCode = "";
		$productQty = 0;
		$this->view->idShop = 0;
		
		if($_POST){
		
		
			$this->view->idShop = $_POST['id_shop'];
		
			foreach($_POST['upload'] as $key => $value){
					
				$productCode = "";
				$productQty = 0;
					
				$arrtmp = explode("[",$value);
					
				$productCode = $arrtmp[0];
				$qty = (int)trim(str_replace("]","",$arrtmp[1]));
					
				//It should be in the Database
					
				if(!$rProducts->ifExist($productCode)){
		
					echo "ERROR OCCUR,DATA NOT SAVE,This Code Not Exist AT All:".$productCode;
				}
				elseif($_POST['id_job']=="JOBID" || trim($_POST['id_job'])==""){
					
					echo "ERROR OCCUR,INCORRECT JOB ID, DATA NOT SAVE";
					
				}
				else{
					//item already EXIST
					$idProduct = $rProducts->getProductID($productCode);
		
					//shop stock -
					$rStock->increaseStockShop($idProduct,$qty,$_POST['id_shop']);
					//record movement
					$rMove->addMovement(7, $idProduct, $qty,$_POST['staff_name'],$_POST['id_shop'],$_POST['id_job']);
				}
					
			}
		
		
		}		
		
		
	}
	public function transferBackAction(){
		
	}
	public function shopStockMainAction(){
		
		$idShop = $this->_getParam('shop');
		
		$this->view->idShop = $idShop;
		
	}
	public function shopStockTakeAction(){
		
		$idShop = $this->_getParam('shop');
		$idCate = $this->_getParam('cate');
		
		$rProducts = new Model_DbTable_Pr_Prproducts();
		
		$this->view->idShop = $idShop;
		$this->view->idCate = $idCate;
		
		$this->view->pList = $rProducts->listProductsByCate($idCate);

		$this->view->dateToday = Model_DatetimeHelper::dateToday();
		
	}
	public function shopAdjustInAction(){
		
		$idShop = $this->_getParam('shop');		
		$this->view->erorMessage = "";
			
		if($idShop==""){
		
			$this->view->errorMessage = "ERROR OCCUR, DO NOT PROCEEDE";
		}
		
		else{
			$this->view->idShop = $idShop;				
		}
		
		
	}
	
	public function saveShopAdjustInAction(){
		
		//adjust stock 
		//record the stock to AR
		//record how many adjusted 
		
		$rProducts = new Model_DbTable_Pr_Prproducts();
		$rStock = new Model_DbTable_Pr_Prstock();
		$rMove = new Model_DbTable_Pr_Prstockmovement();
		$productCode = "";
		$productQty = 0;
		$this->view->idShop = 0;
		$dateTody = Model_DatetimeHelper::dateToday("");
		$timeNow = Model_DatetimeHelper::timeNow("");
		$aINo = "AD".$dateTody.$timeNow;
		
		if($_POST){
		
		
			$this->view->idShop = $_POST['id_shop'];
		
			foreach($_POST['upload'] as $key => $value){
					
				$productCode = "";
				$productQty = 0;
					
				$arrtmp = explode("[",$value);

				$productCode = $arrtmp[0];
				$qty = (int)trim(str_replace("]","",$arrtmp[1]));
					
				//It should be in the Database
					
				if(!$rProducts->ifExist($productCode)){
		
					echo "ERROR OCCUR,DATA NOT SAVE,This Code Not Exist AT All:".$productCode;
				}
				else{
					//item already EXIST
					$idProduct = $rProducts->getProductID($productCode);
		
					//shop stock -
					$rStock->increaseStockShop($idProduct,$qty,$_POST['id_shop']);
					//record movement
					if($qty >0){
						$rMove->addMovement(5, $idProduct, $qty,$_POST['staff_name'],$_POST['id_shop'],$aINo);
					}
					else{
						$rMove->addMovement(6, $idProduct, 0 - $qty,$_POST['staff_name'],$_POST['id_shop'],$aINo);
					}
					$tmpStock = $rStock->getShopStock($idProduct,$_POST['id_shop']);
					$rMove->addMovement(9, $idProduct, $tmpStock,$_POST['staff_name'],$_POST['id_shop'],$aINo);
					
				}
					
			}
		
		
		}	
		
		$this->view->AINO = $aINo;
		
		
		
		
	}
	
	public function shopAdjustOutAction(){
		
		$idShop = $this->_getParam('shop');
		$this->view->erorMessage = "";
			
		if($idShop==""){
		
			$this->view->errorMessage = "ERROR OCCUR, DO NOT PROCEEDE";
		}
		
		else{
			$this->view->idShop = $idShop;
		}		
		
	}
	
	public function saveShopAdjustOutAction(){
		
		$rProducts = new Model_DbTable_Pr_Prproducts();
		$rStock = new Model_DbTable_Pr_Prstock();
		$rMove = new Model_DbTable_Pr_Prstockmovement();
		$productCode = "";
		$productQty = 0;
		$this->view->idShop = 0;
		$dateTody = Model_DatetimeHelper::dateToday("");
		$timeNow = Model_DatetimeHelper::timeNow("");
		$aINo = "AO".$dateTody.$timeNow;
		
		if($_POST){
		
		
			$this->view->idShop = $_POST['id_shop'];
		
			foreach($_POST['upload'] as $key => $value){
					
				$productCode = "";
				$productQty = 0;
					
				$arrtmp = explode("[",$value);
		
				$productCode = $arrtmp[0];
				$qty = (int)trim(str_replace("]","",$arrtmp[1]));
					
				//It should be in the Database
					
				if(!$rProducts->ifExist($productCode)){
		
					echo "ERROR OCCUR,DATA NOT SAVE,This Code Not Exist AT All:".$productCode;
				}
				else{
					//item already EXIST
					$idProduct = $rProducts->getProductID($productCode);
		
					//shop stock -
					$rStock->descreaseStockShop($idProduct,$qty,$_POST['id_shop']);
					//record movement
					$rMove->addMovement(6, $idProduct, $qty,$_POST['staff_name'],$_POST['id_shop'],$aINo);
					$tmpStock = $rStock->getShopStock($idProduct,$_POST['id_shop']);
					$rMove->addMovement(9, $idProduct, $tmpStock,$_POST['staff_name'],$_POST['id_shop'],$aINo);
						
				}
					
			}
		
		
		}	

		$this->view->AINO = $aINo;
		
	}
	
	public function shopFaultyProductAction(){
		
		$rProducts = new Model_DbTable_Pr_Prproducts();
		if($_POST){
		
		$codeFaultyProduct = $_POST['barcode_code'];
		$idShop = $_POST['id_shop'];
		$shopHead = $_POST['shop_head'];
		$desRow = $rProducts->getProductDes($codeFaultyProduct);
		
		$this->view->idShop = $idShop;
		$this->view->faultyBarcode = $codeFaultyProduct;
		$this->view->shopHead = $shopHead;
		if(!$desRow){
			$this->view->faulty_des = "<span style=\"color:red;\">Error Occur,BarCode Not Exist</span>";
		}
		else{
			$this->view->faulty_des =$desRow;
		}
			
		}
	}
	public function saveShopFoAction(){
		
		date_default_timezone_set("Australia/Melbourne");
		
		$monday = Model_DatetimeHelper::getThisWeekMonday();
		$this->view->dateToday = $dateToday = Model_DatetimeHelper::dateToday();
		$this->view->weekId = $weekId = date("W",strtotime($dateToday)) % 2;
		 
		
		$rProducts = new Model_DbTable_Pr_Prproducts();
		$fProduct = new Model_DbTable_Faultyproduct();
		
		
		
		$rStock = new Model_DbTable_Pr_Prstock();
		$rMove = new Model_DbTable_Pr_Prstockmovement();		
		$this->view->message = "";
		$this->view->shopHead = "";
		$shopCode="BSXP";
		$rmareturn="";
		if($_POST){
		
			if(!$rProducts->ifExist(trim($_POST['barcode_code']))){
			
				$this->view->message = "<span style=\"color:#f00;\">ERROR OCCUR,DATA NOT SAVE</span>,This Code Not Exist AT All:".$_POST['barcode_code'];
			}
			else{
				//item already EXIST
				$idProduct = $rProducts->getProductID(trim($_POST['barcode_code']));
			
				//shop stock -
				$rStock->descreaseStockShop($idProduct,trim($_POST['barcode_qty']),$_POST['id_shop']);
				//record movement
				$rMove->addMovement(8, $idProduct,trim($_POST['barcode_qty']),$_POST['staff_name'],$_POST['id_shop'],$_POST['faulty_comment']);
		
				$productName = $rProducts->getProductDes(trim($_POST['barcode_code']));
				
				if(trim($_POST['barcode_qty']) >0){
					
					$neverUse =  (isset($_POST['never_use']))?1:0;					
					//$rmareturn = $fProduct->addFaultyProduct(trim($_POST['barcode_code']), $productName, $_POST['shop_head'],$_POST['staff_name'],trim($_POST['barcode_qty']),40001,$_POST['faulty_comment'],"Parts Faulty","","");
					
					$fn = $fProduct->listAllUnhandledFaultyByShopByDate($_POST['shop_head'],$monday, $dateToday);
					$this->view->faultyNo = count($fn) + 1;
					$rmareturn = $fProduct->addRepairFaulty(trim($_POST['barcode_code']), $productName, $_POST['shop_head'],$_POST['staff_name'],trim($_POST['barcode_qty']),  $_POST['faulty_reason'], $_POST['faulty_comment'], "Parts Faulty","","", $_POST['staff_name_dis'],  $_POST['id_job'], $_POST['name_tech'], $neverUse);
					
					$this->view->message = "Faulty Saved,New RMA Created with RMA Number {$rmareturn} ,Return to Main Page";
					
				}
				else{
					
					$this->view->message = "Faulty REVERSED,Please Notify Warehouse To Reject/Cancel this RMA ,Return to Main Page";
					
				}
				
				
				$this->view->pName = $productName;
				$this->view->rmaID = $rmareturn;
				
				
				$this->view->shopHead = $_POST['shop_head'];
			}
		
		}
		
	}

	public function shopPartsSummaryAction(){
		date_default_timezone_set('Australia/Melbourne');
		
		$idShop = $this->_getParam('shop');
		$showCust = $this->_getParam("custpart");
		

		
		$dateBegin = "";
		$dateEnd = "";
		
		$thisMonday = Model_DatetimeHelper::getThisWeekMonday();
		$thisSunday = Model_DatetimeHelper::getThisWeekSunday();
		$lastSunday = Model_DatetimeHelper::getLastWeekSunday();
		$lastMonday = Model_DatetimeHelper::getLastWeekMonday();
		
		$dB = $this->_getParam("date_begin");
		$dE = $this->_getParam("date_end");
		if($dB!=""){
			
			$lastMonday = $dB;
		}
		if($dE!=""){
			$lastSunday = $dE;
		}
		
		
		
		$rMove = new Model_DbTable_Pr_Prstockmovement();
		
		//used 
		if($_POST){
			$this->view->pUsed = $rMove->getStockMoveByDateByCode(2, $_POST["date_begin"],$_POST["date_end"],$idShop);
			
			//Error Correction
			$this->view->pErr = $rMove->getStockMoveSumByDateByCode(7, $_POST["date_begin"],$_POST["date_end"],$idShop);
			
			//Faulty
			
			$this->view->pFau = $rMove->getStockMoveSumByDateByCode(8, $_POST["date_begin"],$_POST["date_end"],$idShop);			
			
			$this->view->dateBegin = $_POST["date_begin"];
			$this->view->dateEnd = $_POST["date_end"];
			
			//get files
			echo "Read File";
			$slFolder = SALES_LINE_FOLDER;
				
			$lastMondayFile = Model_DatetimeHelper::getMondayByDate($_POST["date_begin"],"");
				
			$salesOldFileName = "SALESLINEOLD".$lastMondayFile;
			$salesNewFileName = "SALESLINENEW".$lastMondayFile;
			
				
			if(file_exists(getcwd()."/".$slFolder."/".$salesOldFileName) && file_exists(getcwd()."/".$slFolder."/".$salesNewFileName) ){
			
				$tmpArray = array();
				$fl = fopen(getcwd()."/".$slFolder."/".$salesOldFileName,"r");
			
				while (($lineData = fgetcsv($fl)) != false){
					
					$tmpArray[] = $lineData[5]; // location
					$tmpArray[] = $lineData[8]; //invoice
					//$tmpArray[] = $lineData[9]; //date
					$tmpArray[] = $lineData[17]; // act money
					$tmpArray[] = $lineData[0];//barcode
					$tmpArray[] = $lineData[2]; //des
					
				}
				fclose($fl);
				$fl = fopen(getcwd()."/".$slFolder."/".$salesNewFileName,"r");
			
				while (($lineData = fgetcsv($fl)) != false){
					
					$tmpArray[] = $lineData[5]; // location
					$tmpArray[] = $lineData[8]; //invoice
					//$tmpArray[] = $lineData[9]; //date
					$tmpArray[] = $lineData[17]; // act money
					$tmpArray[] = $lineData[0];//barcode
					$tmpArray[] = $lineData[2]; //des
					
				}
				fclose($fl);
				//var_dump($tmpArray);
				$this->view->fileArray = $tmpArray;
			}
			else{
			
				$this->view->fileArray = "";
			}
			
		}
		else{
		
		$this->view->pUsed = $rMove->getStockMoveByDateByCode(2, $lastMonday, $lastSunday,$idShop);
		
		//Error Correction 
		$this->view->pErr = $rMove->getStockMoveSumByDateByCode(7, $lastMonday, $lastSunday,$idShop);
		
		//Faulty
		
		$this->view->pFau = $rMove->getStockMoveSumByDateByCode(8, $lastMonday, $lastSunday,$idShop);

			$this->view->dateBegin = $lastMonday;
			$this->view->dateEnd = $lastSunday;
			
		//get files
			echo "Read File";
			$slFolder = SALES_LINE_FOLDER;
			
			$lastMondayFile = Model_DatetimeHelper::getLastWeekMonday("");
			
			$salesOldFileName = "SALESLINEOLD".$lastMondayFile;
			$salesNewFileName = "SALESLINENEW".$lastMondayFile;

			
			if(file_exists(getcwd()."/".$slFolder."/".$salesOldFileName) && file_exists(getcwd()."/".$slFolder."/".$salesNewFileName) ){
				
				$tmpArray = array();
				$fl = fopen(getcwd()."/".$slFolder."/".$salesOldFileName,"r");
				
				while (($lineData = fgetcsv($fl)) != false){
					$tmpArray[] = $lineData[5]; // location
					$tmpArray[] = $lineData[8]; //invoice
					//$tmpArray[] = $lineData[9]; //date
					$tmpArray[] = $lineData[17]; // act money
					$tmpArray[] = $lineData[0];//barcode
					$tmpArray[] = $lineData[2]; //des
				}
				fclose($fl);
				$fl = fopen(getcwd()."/".$slFolder."/".$salesNewFileName,"r");
				
				while (($lineData = fgetcsv($fl)) != false){
					$tmpArray[] = $lineData[5]; // location
					$tmpArray[] = $lineData[8]; //invoice
					//$tmpArray[] = $lineData[9]; //date
					$tmpArray[] = $lineData[17]; // act money
					$tmpArray[] = $lineData[0];//barcode
					$tmpArray[] = $lineData[2]; //des
				}
				fclose($fl);				
				
				$this->view->fileArray = $tmpArray;
			}
			else{
				
				$this->view->fileArray = "";
			}
					
		}
		
		$this->view->idShop = $idShop;
		
		if($showCust == "yes"){
				
			$this->renderScript( 'repairparts/return-customer-part.phtml' );
		}
		
		
	}
	
	public function newBarcodeAction(){
		//new barcode in 2 days
		
		$twoDaysAgo = date("Y-m-d",strtotime("-1 day",strtotime("yesterday")));
		echo $twoDaysAgo;
		
		$rProducts = new Model_DbTable_Pr_Prproducts();
		$newProductList = $rProducts->listNewProducts($twoDaysAgo);
				
		$this->view->newProductList = $newProductList;
		
	
	}

	public function warehouseStockAlertAction(){
		
		date_default_timezone_set('Australia/Melbourne');
		
		$idCate = $this->_getParam('cate');
		$rProducts = new Model_DbTable_Pr_Prproducts();
		$this->partsList = $rProducts->listProductsByCate($idCate);
	}
	public function partMoveHistoryAction(){
		
		$rProducts = new Model_DbTable_Pr_Prproducts();
		$rMove = new Model_DbTable_Pr_Prstockmovement();
		$this->view->list = "";
			if($_GET){
			$idProduct = $rProducts->getProductID($_GET['part_code']);		
			$this->view->list = $rMove->getProductMove($idProduct);
			}
		
	}
	public function partMoveHistoryShopAction(){
		
		$shopCode = $this->_getParam("shop");
		
		$rProducts = new Model_DbTable_Pr_Prproducts();
		$rMove = new Model_DbTable_Pr_Prstockmovement();
		$this->view->list = "";
		if($_GET){
			$idProduct = $rProducts->getProductID($_GET['part_code']);
			$this->view->list = $rMove->getProductMove($idProduct,$shopCode);
		}		
		
		$this->view->shopCode = $shopCode;
	}
	
	public function partMoveHistorySummaryAction(){
		set_time_limit(0);

		$shopCode = $this->_getParam("shop");
		
		$dateBegin = $this->_getParam("date_begin");
		$dateEnd = $this->_getParam("date_end");
		
		$timeBegin = $this->_getParam("time_begin");
		$timeEnd = $this->_getParam("time_end");
		
		$this->view->showDetail = $this->_getParam("show_detail");
		
		$rProducts = new Model_DbTable_Pr_Prproducts();
		$rStock = new Model_DbTable_Pr_Prstock();
		$rMove = new Model_DbTable_Pr_Prstockmovement();


		$rPList = $rProducts->listAllProducts();
		
		$arrRes = array();
		$arrPrice = array();
		$arrStockGo = array();
		
		foreach($rPList as $key => $v){

			$tmpArr = array();
			$tmpArr["id_product"] = $v["id_product"];
			$tmpArr["code_product"] = $v["code_product"];
			$tmpArr["title_product"] =  $v["title_product"];
			$tmpArr["price_a"] = $v["price_a"];
			$arrPrice[] = $v["price_a"];
			
			$tmpArr["stock"] = $rStock->getShopStock($v["id_product"], $shopCode);
			
			// ti
			$tiList = $rMove->getProductMoveByDateTimeByCode(3,$v["id_product"], $dateBegin, $dateEnd,$shopCode,$timeBegin,$timeEnd);
			
			$strDetailTi ="";
			$qtyTi = 0;
			
			if(!empty($tiList)){
			foreach($tiList as $k2 => $v2){
				
				if($v2['qty_product']!=0){
				$strDetailTi .= "{$v2['date_move']} {$v2['time_move']} -  {$v2['id_job']} x {$v2['qty_product']} <br />";
				$qtyTi +=  	$v2['qty_product'];
				}
			}
			}
			$tmpArr["detail_ti"] = $strDetailTi;
			$tmpArr["qty_ti"] = $qtyTi;
			
			
			// to 
			$toList = $rMove->getProductMoveByDateByCode(4,$v["id_product"], $dateBegin, $dateEnd,$shopCode,$timeBegin,$timeEnd);
				
			$strDetailTo ="";
			$qtyTo = 0;
				
			if(!empty($toList)){
				foreach($toList as $k21 => $v21){
					$strDetailTo .= "{$v21['date_move']} {$v21['time_move']} -  {$v21['id_job']} x {$v21['qty_product']} <br />";
					$qtyTo +=  	$v21['qty_product'];
				}
			}
			$tmpArr["detail_to"] = $strDetailTo;
			$tmpArr["qty_to"] =  0 - $qtyTo;
			
			// go
			$goList = $rMove->getProductMoveByDateByCode(2,$v["id_product"], $dateBegin, $dateEnd,$shopCode,$timeBegin,$timeEnd);
				
			$strDetailGo ="";
			$qtyGo = 0;
				
			if(!empty($goList)){
				foreach($goList as $k22 => $v22){
					$strDetailGo .= "{$v22['date_move']} {$v22['time_move']} -  {$v22['id_job']} x {$v22['qty_product']} <br />";
					$qtyGo +=  	$v22['qty_product'];
				}
			}
			$tmpArr["detail_go"] = $strDetailGo;
			$tmpArr["qty_go"] = 0 - $qtyGo;
			$arrStockGo[] = $qtyGo;
			//ei
			$eiList = $rMove->getProductMoveByDateByCode(7,$v["id_product"], $dateBegin, $dateEnd,$shopCode,$timeBegin,$timeEnd);
				
			$strDetailEi ="";
			$qtyEi = 0;
				
			if(!empty($eiList)){
				foreach($eiList as $k23 => $v23){
					$strDetailEi .= "{$v23['date_move']} {$v23['time_move']} -  {$v23['id_job']} x {$v23['qty_product']} <br />";
					$qtyEi +=  	$v23['qty_product'];
				}
			}
			$tmpArr["detail_ei"] = $strDetailEi;
			$tmpArr["qty_ei"] = $qtyEi;
			
			// fo
			$foList = $rMove->getProductMoveByDateByCode(8,$v["id_product"], $dateBegin, $dateEnd,$shopCode,$timeBegin,$timeEnd);
				
			$strDetailFo ="";
			$qtyFo = 0;
				
			if(!empty($foList)){
				foreach($foList as $k24 => $v24){
					$strDetailFo .= "{$v24['date_move']} {$v24['time_move']} -  {$v24['id_job']} x {$v24['qty_product']} <br />";
					$qtyFo +=  	$v24['qty_product'];
				}
			}
			$tmpArr["detail_fo"] = $strDetailFo;
			$tmpArr["qty_fo"] = 0 - $qtyFo;
			
			// AI
			$aiList = $rMove->getProductMoveByDateByCode(5,$v["id_product"], $dateBegin, $dateEnd,$shopCode,$timeBegin,$timeEnd);
				
			$strDetailAi ="";
			$qtyAi = 0;
				
			if(!empty($aiList)){
				foreach($aiList as $k25 => $v25){
					$strDetailAi .= "{$v25['date_move']} {$v25['time_move']} -  {$v25['id_job']} x {$v25['qty_product']} <br />";
					$qtyAi +=  	$v25['qty_product'];
				}
			}
			$tmpArr["detail_ai"] = $strDetailAi;
			$tmpArr["qty_ai"] = $qtyAi;
						
			//AO
			
			$aoList = $rMove->getProductMoveByDateByCode(6,$v["id_product"], $dateBegin, $dateEnd,$shopCode,$timeBegin,$timeEnd);
			
			$strDetailAo ="";
			$qtyAo = 0;
			
			if(!empty($aoList)){
				foreach($aoList as $k26 => $v26){
					$strDetailAo .= "{$v26['date_move']} {$v26['time_move']} -  {$v26['id_job']} x {$v26['qty_product']} <br />";
					$qtyAo +=  	$v26['qty_product'];
				}
			}
			$tmpArr["detail_ao"] = $strDetailAo;
			$tmpArr["qty_ao"] = 0 - $qtyAo;
			
			$tmpArr["move_total"] = $qtyTi	- $qtyTo + $qtyEi - $qtyFo - $qtyGo + $qtyAi - $qtyAo;

			$tmpArr ["org_stock"] = $tmpArr["stock"] - $tmpArr["move_total"];
			
			$arrRes[] = $tmpArr;
		}
		
		array_multisort($arrPrice,SORT_DESC,$arrStockGo,SORT_ASC,$arrRes);
		$this->view->arrRes = $arrRes;
		
		//var_dump($arrRes);
		
	}
	
	
	
	
	public function shopToShopTransferAction(){
		
		$pass = $this->_getParam('transferpass');
		$this->view->pass = $pass;
		$this->view->errorMessage = "";
			
		if($pass=="qwertqwert"){
			
			$this->view->idShop = 3;
		}
		
		else{
				$this->view->errorMessage = "ERROR OCCUR, DO NOT PROCEEDE"; 
		}
	}

		
	public function saveShopToAction(){

		$rProducts = new Model_DbTable_Pr_Prproducts();
		$rStock = new Model_DbTable_Pr_Prstock();
		$rMove = new Model_DbTable_Pr_Prstockmovement();
		
		$dateToday = Model_DatetimeHelper::dateToday("");
		$timeNow = Model_DatetimeHelper::timeNow("");
		$stsNo = "STS".$dateToday.$timeNow;
		
		$productCode = "";
		$productQty = 0;
		$this->view->idShop = 0;
		$this->view->errorMessage = "";
		
		if($_POST){
		
		
			$this->view->idShop = $_POST['id_shop'];
			$this->view->idShopTo = $_POST['id_shop_to'];
		
			foreach($_POST['upload'] as $key => $value){
					
				$productCode = "";
				$productQty = 0;
					
				$arrtmp = explode("[",$value);
					
				$productCode = $arrtmp[0];
				$qty = (int)trim(str_replace("]","",$arrtmp[1]));
					
				//It should be in the Database
					
				if(!$rProducts->ifExist($productCode)){
		
					$this->view->errorMessage.= "ERROR, BarCode '".$productCode."' NOT EXIST, Record(s) Can NOT Save!!<br />";
				}
				else{
					//item already EXIST
					$idProduct = $rProducts->getProductID($productCode);
		
					//shop stock -
					$rStock->descreaseStockShop($idProduct,$qty,$_POST['id_shop']);
					$rStock->increaseStockShop($idProduct,$qty,$_POST['id_shop_to']);
					//record movement
					if($qty>0){
						$rMove->addMovement(4, $idProduct, $qty,$_POST['staff_name'],$_POST['id_shop'],$stsNo);
						$rMove->addMovement(3, $idProduct, $qty,$_POST['staff_name'],$_POST['id_shop_to'],$stsNo);
					}
					else{
						$rMove->addMovement(7, $idProduct, 0 - $qty,$_POST['staff_name'],$_POST['id_shop'],$stsNo);
						$rMove->addMovement(7, $idProduct, 0 - $qty,$_POST['staff_name'],$_POST['id_shop_to'],$stsNo);
					}
		
				}
					
			}
		
		
		}
		
	
	
	}
	
	public function checkRgnMainAction(){
		
		$rgn = new Model_DbTable_Pr_Stockmove_Rgn();
		$res = $rgn->listAllDistinct();
		$this->view->resList = $res;
	}
	public function checkRktMainAction(){
	
		$rgn = new Model_DbTable_Pr_Stockmove_Rkt();
		$res = $rgn->listAllDistinct();
		$this->view->resList = $res;
	}
	public function checkAdMainAction(){
	
		$rgn = new Model_DbTable_Pr_Stockmove_Ad();
		$res = $rgn->listAllDistinct();
		$this->view->resList = $res;
	}	

	public function checkStsMainAction(){
		$rgn = new Model_DbTable_Pr_Stockmove_Sts();
		$res = $rgn->listAllDistinct();
		$this->view->resList = $res;		
		
		
	}
	
	public function checkNotesDetailAction(){
		
		$idJob = $this->_getParam("idjob");
		$skMove = new Model_DbTable_Pr_Prstockmovement();
		$this->view->resList = $skMove->listByNoteId($idJob);
		$this->view->idJob = $idJob;
		//var_dump($this->view->resList);
	
	}
	public function checkNotesDetailStsAction(){
	
		$idJob = $this->_getParam("idjob");
		$skMove = new Model_DbTable_Pr_Prstockmovement();
		$this->view->resList = $skMove->listByNoteId($idJob);
		$this->view->idJob = $idJob;
		//var_dump($this->view->resList);
	
	}
	
	public function showPriceAction(){
		
		
	}

	public function listStaffSummaryAction(){

		$dateBegin = $this->_getParam("date_begin");
		$dateEnd = $this->_getParam("date_end");
		var_dump($dateBegin);
		$arrRes = array();
		$arrStaff = array();
		$arrShop = array();
		$arrDate = array();
		$invPro = "";
		
		$rJobs = new Model_DbTable_Repairjob(Zend_Registry::get('db_real'));
		
		$rMove = new Model_DbTable_Pr_Prstockmovement(Zend_Registry::get('db_real'));
		$rPart = new Model_DbTable_Pr_Prproducts(Zend_Registry::get('db_real'));
		
		$rStatus = new Model_DbTable_Repairstatusrecord(Zend_Registry::get('db_real'));
		
		$jList = $rJobs->listAllFinishedJobsByDate($dateBegin, $dateEnd);
		//echo "<pre>";
		foreach($jList as $job){
			$stJob = $rStatus->getLastStatus($job["id_job"]);
			
			if($stJob[0]["id_status"] == 90 ){
				
				$shop =  $job["shop_code"];
				
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
				if($shop == "WGIC"){
					$invPro = new Model_DbTable_Apos_Invoice_Products_Wgic(Zend_Registry::get('db_oldapos'));
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
				
				$invLine = "";
				$invLine2 = "";
				$inv1Count = "";
				$inv2Count = "";
				
				$inv1Disc = 0;
				$inv2Disc = 0;
				
				
				$inv1 = substr($job["invoice_no"],-8);
				$inv2 = "";
				$invLine = $invPro->getRepairInvoice("L1".$inv1);
				
				$inv1Count = count($invPro->getInvoiceProducts("L1".$inv1));

				$inv1Disc = $invPro->getInvoiceDiscount("L1".$inv1);
				
				if(strlen($job["invoice_no2"]) > 0){

					$inv2 = substr($job["invoice_no2"],-8);
					$invLine2 = $invPro->getRepairInvoice("L1".$inv2); 					
					$inv2Count = count($invPro->getInvoiceProducts("L1".$inv2));
					$inv2Disc = $invPro->getInvoiceDiscount("L1".$inv2); 
				}
		
				//get repair parts 

				$partsRes = $rMove->getPartsSummaryByJobId($job['id_job'],"price_b");
				//var_dump($tmprows);
				// bonus summary
				$wBonus = false;
				$maxBonusFull = 0;
				$maxBonusHalf = 0;
				
				foreach($partsRes[0] as $part){
					$id = $rPart->getProductID($part[0]);
					$row = $rPart->getProduct($id);
					if($row['w_bonus']){
						$wBonus = true;
						if($row["amt_full_bonus"] > $maxBonusFull){
							$maxBonusFull = $row["amt_full_bonus"]; 
							$maxBonusHalf = $row["amt_dis_bonus"];
						}
					}
				}
				
				$amtBonus = "N/A";
				
				$repairDis = 0;
				$salesDis = 0;
				
				if($wBonus){
					
					if($job["invoice_no2"] == "" ){
						
						if($inv1Count==1){
							$repairDis = $partsRes[1] - $invLine;
						}
						if($inv1Count>1){
							$repairDis = $partsRes[1] - $invLine;
							$salesDis = $inv1Disc;
						}	
					}
					
					else{
						
						if($inv1==$inv2){
							
							if($inv1Count==1){
								$repairDis = $partsRes[1] - $invLine;
							}
							if($inv1Count>1){
								$repairDis = $partsRes[1] - $invLine;
								$salesDis = $inv1Disc;
							}
						}						
							else{

							$repairDis = $partsRes[1] - $invLine - $invLine2;
							$salesDis = $inv1Disc + $inv2Disc;
						}	
							
						}					
					
				}
				
				$bonusAmt = 0;
				
				if($wBonus){
				if( ($repairDis < 5 && $salesDis < 5) || ($repairDis + $salesDis) < 5  ){
					
					$bonusAmt = $maxBonusFull;
					$maxBonusHalf = 0;
					
				}
				
				if(($repairDis >= 5 && $repairDis < 10 && $salesDis >= 5 && $salesDis < 10 ) || (($repairDis + $salesDis) >= 5 && ($repairDis + $salesDis) < 10)  ){
					$bonusAmt = $maxBonusHalf;
					$maxBonusFull = 0;
				}
				if(($repairDis + $salesDis) > 10){
					
					$bonusAmt = 0;
					
					$maxBonusHalf = 0;
					$maxBonusFull = 0;
					
					
				}
				
				$policyArray = $rJobs->bonusRule($job["id_job"]);
				if(!$policyArray[0] || !$policyArray[1]){
					$bonusAmt = 0;
				}
				
				}
				//WHo , what ,finish time , quote , invoices , who record 
				$tmpArr = array();
				
				$tmpArr["id_job"] = $job["id_job"];
				$tmpArr["date_start"] =  $job["date_start"];
				$tmpArr["repair_staff"] = $job["repair_staff"];
				
				$arrStaff[] =  $job["repair_staff"];
				
				$tmpArr["shop_code"] = $job["shop_code"];
				
				$arrShop[] = $job["shop_code"]; 
				
				$tmpArr["mobile_model"] = $job["mobile_model"];
				$tmpArr["quot_price"] = $job["quot_price"];
				$tmpArr["invoice_no"] = $job["invoice_no"];
				$tmpArr["invoice_qty"] =  $inv1Count;
				$tmpArr["invoice_amt"] = $invLine;
				$tmpArr["invoice_no2"] = $job["invoice_no2"];
				$tmpArr["invoice_qty2"] = $inv2Count;
				$tmpArr["invoice_dis"] = $inv1Disc;
				$tmpArr["invoice_dis2"] = $inv2Disc;
				$tmpArr["invoice_amt2"] = $invLine2;
				$tmpArr["staff_record"] = $job["staff_record"];
				$tmpArr["time_record"] = $job["time_record"];
				$tmpArr["part_sum"] = $partsRes;
				$tmpArr["w_bonus"] = $wBonus;
				$tmpArr["repair_disc"] = $repairDis;
				$tmpArr["sales_disc"] = $salesDis;
				$tmpArr["bonus_amt"] = $bonusAmt;
				$tmpArr["full_bonus_amt"] = $maxBonusFull;
				$tmpArr["dis_bonus_amt"] = $maxBonusHalf;
				
				$arrDate[] = $job["time_record"];
				
				$tmpArr['bonus_rule'] = $rJobs->bonusRule($job["id_job"]);
				 
				$arrRes[] = $tmpArr;
			}	
		}
		
		array_multisort($arrStaff,SORT_ASC,$arrShop,SORT_ASC,$arrDate,SORT_ASC,$arrRes);
		//var_dump($arrRes);
		//echo "</pre>";
		$this->view->arrRes = $arrRes;
		//d($arrRes);

	}
	
	public function lastWeekMovementAction(){

		
		$dateBegin = Model_DatetimeHelper::getLastWeekMonday();
		$dateEnd = Model_DatetimeHelper::getLastWeekSunday();
		
		$recDateBegin = $this->_getParam("date_begin");
		$recDateEnd = $this->_getParam("date_end");
		
		if($recDateBegin != ""){
			$dateBegin = $recDateBegin;
			$dateEnd = $recDateEnd;
		}
		
		$rProducts = new Model_DbTable_Pr_Prproducts();
		$rMove = new Model_DbTable_Pr_Prstockmovement();
		$rStock = new Model_DbTable_Pr_Prstock();
		
		$rPlist = $rProducts->listAllProducts();
		$arrRes = array();
		
		foreach($rPlist as $key => $v){
			
			$idProduct = $v["id_product"];
			
			$tiSum = $rMove->getProductMoveSummaryByDateByCode(3, $idProduct, $dateBegin, $dateEnd);
			$goSum = $rMove->getProductMoveSummaryByDateByCode(2, $idProduct, $dateBegin, $dateEnd); 
			$foSum = $rMove->getProductMoveSummaryByDateByCode(8, $idProduct, $dateBegin, $dateEnd);
			$whStock = $rStock->getWarehouseStock($idProduct);
			
			if($tiSum[0]['sum_qty'] > 0 || $goSum[0]['sum_qty'] > 0 || $foSum[0]['sum_qty'] > 0){
				$tmpArr = array();
				$tmpArr[] = $idProduct;
				$tmpArr[] = $v["code_product"];
				$tmpArr[] = $v["title_product"];
				$tmpArr[] = $tiSum[0]['sum_qty'];
				$tmpArr[] = $goSum[0]['sum_qty'];
				$tmpArr[] = $foSum[0]['sum_qty'];
				$tmpArr[] = $whStock;
				
				$lno = ( $tiSum[0]['sum_qty'] > $goSum[0]['sum_qty'] )?$tiSum[0]['sum_qty']:$goSum[0]['sum_qty'];
				
				if($whStock < $lno * 2){
					if($lno *2 - $whStock > 10){
						$tmpArr[] = 5 * round(($lno *2 - $whStock)/ 5); 
					}
					else{
						
						$tmpArr[] = ($lno *2 - $whStock);
					}	
				}
				else{
					$tmpArr[] = 0;
				}

				if($v["code_product"]!="SERVICE" && $v["code_product"]!="CUSTPART" ){
				
					$arrRes[] = $tmpArr;
				}
			
			}
			
		}
		
		//loop all the parts 
		$arrOrder = array();
		
		foreach($arrRes as $k2 => $v2){
			$arrOrder[$k2] = $v2[7];
		}
		
		array_multisort($arrOrder,SORT_DESC,$arrRes);
		
		$this->view->arrRes = $arrRes;
		//var_dump($arrRes);
		
		
		
	}
	
	public function partsAnalysisAction(){
		
		$idShop = $this->_getParam("shop");
		
		$dateEnd = Model_DatetimeHelper::dateYesterday();
		$dateBegin = Model_DatetimeHelper::adjustWeeks("sub", $dateEnd, 4);
		
		$rProducts = new Model_DbTable_Pr_Prproducts();
		$rMove = new Model_DbTable_Pr_Prstockmovement();
		$rStock = new Model_DbTable_Pr_Prstock();
		
		$rPlist = $rProducts->listAllProducts();
		
		$arrRes = array();
		$arrResEmpty = array();		
		
		foreach($rPlist as $k => $v){
			
			$idProduct = $v["id_product"];
				
			$goSum = $rMove->getProductMoveSummaryByDateByCode(2, $idProduct, $dateBegin, $dateEnd,$idShop);
			$foSum = $rMove->getProductMoveSummaryByDateByCode(8, $idProduct, $dateBegin, $dateEnd,$idShop);
			$tiSum = $rMove->getProductMoveSummaryByDateByCode(3, $idProduct, $dateBegin, $dateEnd,$idShop); 
			$goAll = $rMove->getProductMoveSummaryByDateByCode(2, $idProduct, $dateBegin, $dateEnd); 
			
			$shopStock = $rStock->getShopStock($idProduct, $idShop);
			$whStock = $rStock->getWarehouseStock($idProduct);
			
			if($shopStock>0 && $foSum[0]['sum_qty'] == 0 && $goSum[0]['sum_qty'] == 0 ){
				$tmpArr = array();
				$tmpArr[] = $idProduct;
				$tmpArr[] = $v["code_product"];
				$tmpArr[] = $v["title_product"];
				$tmpArr[] = $shopStock;
				$tmpArr[] = $whStock;
				$tmpArr[] = $goAll[0]['sum_qty'];
				$tmpArr[] = $tiSum[0]['sum_qty'];
				if($v["code_product"]!="SERVICE" && $v["code_product"]!="CUSTPART" && $v["code_product"]!="ERROR1"  ){
				$arrRes[] = $tmpArr;
				}
			}
			
			if($shopStock <=0 && ($foSum[0]['sum_qty'] > 0 || $goSum[0]['sum_qty'] > 0) ){
				
				$tmpArr = array();
				$tmpArr[] = $idProduct;
				$tmpArr[] = $v["code_product"];
				$tmpArr[] = $v["title_product"];
				$tmpArr[] = $goSum[0]['sum_qty'];
				$tmpArr[] = $foSum[0]['sum_qty'];
				$tmpArr[] = $whStock;
				if($v["code_product"]!="SERVICE" && $v["code_product"]!="CUSTPART" && $v["code_product"]!="ERROR1"  ){
				$arrResEmpty[] = $tmpArr;				
				}	
			}
		}
		
		$this->view->arrRes = $arrRes;
		$this->view->arrResEmpty = $arrResEmpty;
		$this->view->idShop = $idShop;
	}
	
	public function partsSalesSummaryAction(){
		
		$this->view->dateBegin = $dateBegin = '2013-10-21';
		$this->view->dateEnd = $dateEnd = '2013-10-29';
		
		$prProduct = new Model_DbTable_Pr_Prproducts();
		$prStockMove = new Model_DbTable_Pr_Prstockmovement();
		$pList = $prStockMove->getStockMoveSumByDateByCode(2, $dateBegin, $dateEnd);
		$pListFo = $prStockMove->getStockMoveSumByDateByCode(8, $dateBegin, $dateEnd);
		$pListEi = $prStockMove->getStockMoveSumByDateByCode(7, $dateBegin, $dateEnd);
		
		$arrRes = array();
		$arrMax = array();
		
		foreach($pList as $k => $v){
			$arrTmp = array();
			$pRow = $prProduct->getProduct($v['id_product']);
			$arrTmp[] = $k + 1;
			$arrTmp[] = $pRow['code_product'];
			$arrTmp[] = $pRow['title_product'];
			$arrTmp[] = $v['qtys'];
			$arrTmp[] = $pRow['act_cost'];
			$arrTmp[] = $pRow['act_cost'] * $v['qtys'];
			$arrRes[] = $arrTmp;		
			$arrMax[] = $v['qtys'];
		}
		
		array_multisort($arrMax,SORT_DESC,$arrRes);
		
		$this->view->arrRes = $arrRes;
		
		//$this->view->pList = $pList;
		//var_dump($pList);
		
	}
	
	public function manageBonusAction(){
		
		$pb = new Model_DbTable_Pr_Bonus();
		if($_POST){
			//for New
			foreach($_POST['category_new'] as $k1 => $v1){
				$visible = (isset($_POST['vis_new'][$k1]))?1:0;
				if(trim($v1)!=""){
					$pb->addBonus($v1, $_POST['full_price_new'][$k1],$_POST['dis_price_new'][$k1],$_POST['low_price_new'][$k1], $visible);
				}	
			}
			//for exist
			foreach($_POST['id_cate'] as $k2 => $v2){
				$visible = (isset($_POST['visible'][$k2]))?1:0;
				$pb->updateBonus($v2, $_POST['title_cate'][$k2],$_POST['full_price'][$k2],$_POST['dis_price'][$k2],$_POST['low_price'][$k2] ,$visible);
			}
			
			echo "Saved to DB";
		}
		
		$bList = $pb->listAll();
		$this->view->bList = $bList;
		//var_dump($bList);
	}
	
	public function repairBonusAction(){
		
		$pb = new Model_DbTable_Pr_Bonus();
		
		
		$bList = $pb->listVisible();
		$this->view->bList = $bList;		
	}
	

	public function lastWeekPartsSalesAction(){
		
		$shop = 1;
		$shop = $this->_getParam("shop");
		$idShop = "";
		
		$thisMonday = Model_DatetimeHelper::getThisWeekMonday();
		
		
		$dateOrder = Model_DatetimeHelper::getThisWeekMonday("");
		
		if($shop < 10 ){
			$idShop = "0".$shop;
		}else{
			
			$idShop = (string)$shop;
		}
		
		$partOrderNumber = "OD".$idShop.$dateOrder;
		
		
		$date2WeekBegin = Model_DatetimeHelper::adjustDays("sub",Model_DatetimeHelper::getLastWeekMonday(),7);
		$dateBegin = Model_DatetimeHelper::getLastWeekMonday();
		$dateEnd = Model_DatetimeHelper::getLastWeekSunday();
		
		
		$rProducts = new Model_DbTable_Pr_Prproducts();
		$rMove = new Model_DbTable_Pr_Prstockmovement();
		$rStock = new Model_DbTable_Pr_Prstock();
		
		$rOrder = new Model_DbTable_Pr_Order();
		$rOrder->clearOrder($dateOrder, $shop);
		
		
		$arrPlist = array();
		
		$arrRes = array();
		
		$rListGo = $rMove->getStockMoveSumByDateByCode(2, $date2WeekBegin, $dateEnd,$shop);
		$rListFo = $rMove->getStockMoveSumByDateByCode(8, $dateBegin, $dateEnd,$shop);
		$rListEi = $rMove->getStockMoveSumByDateByCode(7, $dateBegin, $dateEnd,$shop);
		

		if(!empty($rListGo)){
		foreach($rListGo as $k => $v){
			
			//$key = array_search($v['id_product'],$arrPlist);
			//if(!$key){
				
				
				$idProduct = $v['id_product']; //0
				$codeLine = $rProducts->getProduct($v['id_product']);
				$codeProduct = $codeLine['code_product']; //1
				$qtyLastWeek = round($v['qtys']/2,0);
				$qtyFo = 0;
				$qtyMax = $rMove->maxSold($idProduct, $dateEnd,4,$shop);
				$qtyOnhand = $rStock->getShopStock($idProduct, $shop);
				$qtyWh = $rStock->getWarehouseStock($idProduct);
				
				$arrTmp = array($idProduct,$codeProduct,$qtyLastWeek,$qtyFo,$qtyMax,$qtyOnhand,$qtyWh);
				if($codeProduct!="SERVICE"){
					$arrRes[] = $arrTmp;
				}
				$arrPlist[] = $idProduct;
				
			//}
			
		}
		}
		if(!empty($rListFo)){
		foreach($rListFo as $k1 => $v1){
			
			
			if(isset($v1['id_product'])){	
			$key = array_search($v1['id_product'],$arrPlist);
			if($key===false){
		
		
			$idProduct = $v1['id_product']; //0
			$codeLine = $rProducts->getProduct($v1['id_product']);
			$codeProduct = $codeLine['code_product']; //1
			$qtyLastWeek = 0;
			$qtyFo = $v1['qtys'];
			$qtyMax = $rMove->maxSold($idProduct, $dateEnd,4,$shop);
			$qtyOnhand = $rStock->getShopStock($idProduct, $shop);
			$qtyWh = $rStock->getWarehouseStock($idProduct);
		
			$arrTmp = array($idProduct,$codeProduct,$qtyLastWeek,$qtyFo,$qtyMax,$qtyOnhand,$qtyWh);
			$arrRes[] = $arrTmp;
			$arrPlist[] = $idProduct;
			}
			else{
			//update the Qty

				$arrRes[$key][3] += $v1['qtys'];
				
			}

			}
		}
		}
		
		if(!empty($rListEi)){
			foreach($rListEi as $k2 => $v2){
		
				$key = array_search($v2['id_product'],$arrPlist);
				if($key===false){
		
		
					$idProduct = $v2['id_product']; //0
					$codeLine = $rProducts->getProduct($v2['id_product']);
					$codeProduct = $codeLine['code_product']; //1
					$qtyLastWeek = 0;
					$qtyFo = 0 - $v2['qtys'];
					$qtyMax = $rMove->maxSold($idProduct, $dateEnd,4,$shop);
					$qtyOnhand = $rStock->getShopStock($idProduct, $shop);
					$qtyWh = $rStock->getWarehouseStock($idProduct);
		
					$arrTmp = array($idProduct,$codeProduct,$qtyLastWeek,$qtyFo,$qtyMax,$qtyOnhand,$qtyWh);
					$arrRes[] = $arrTmp;
					$arrPlist[] = $idProduct;
				}
				else{
					//update the Qty
		
					$arrRes[$key][3] -= $v2['qtys'];
		
				}
		
			}
		}		
		
		$this->view->arrRes = $arrRes;
		
		$arrDup = array();
		foreach($arrRes as $row){
			//$rOrder->addOrder($partOrderNumber, $shop, "",$thisMonday, $row[0], $row[1], $row[2], $qtyMaxWeek, $qtyOrder, $qtyAllocate, $isSend)	
			$qtyOrder =$rOrder->orderQty($row[2], $row[4], $row[3], $row[5], $row[6]);
			
			if(!empty($row[0]) && !in_array($row[0], $arrDup)){
			$rOrder->addOrder($partOrderNumber, $shop, $thisMonday, $row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6],$qtyOrder);
				
			$arrDup[] = $row[0];
			
			}
		}
		
		//echo "<pre>";
		//var_dump($arrRes);
		//echo "</pre>";
		
		
		$this->_redirect("/repaircenter/online-order/shop/".$shop);
	
	}
	
	public function pickupSlipOrderAction(){
		
		$pon = $this->_getParam("pon");
		$rOrder = new Model_DbTable_Pr_Order();
		
		if($_POST){
			foreach($_POST['del'] as $order){
				$rOrder->deleteOrder($order);	
			}			
		}
		$this->view->pon = $pon;
		$this->view->oList = $rOrder->getByPONConfirmed($pon);
		
		$this->view->dateTime = Model_DatetimeHelper::dateToday()." ".Model_DatetimeHelper::timeNow();
			
	}
	
	public function orderDispatchAction(){
		

		$pon = $this->_getParam("pon");
		$idShop = (int)substr($pon,2,2);
		$dateOrder = substr($pon,4,4)."-".substr($pon,8,2)."-".substr($pon,10,2);
		$rOrder = new Model_DbTable_Pr_Order();
		$rProduct = new Model_DbTable_Pr_Prproducts();
		$rOrderAsKt = new Model_DbTable_Products_Stock_Transferadjuststatus();
		$rKaKtRecord = new Model_DbTable_Products_Stock_Kaktstatusrecord();
		$mail = new Model_Emailshandler();
		
		$this->view->pList = $pList = $rProduct->listAllProducts();
		$this->view->pon = $pon;
		$this->view->dateTime = Model_DatetimeHelper::dateToday()." ".Model_DatetimeHelper::timeNow();
		$arrAllo = array();
		if($_POST){
			$arrAllo = unserialize(base64_decode($_POST["arr_allocate_order"]));
			
			if(isset($_POST["btn_add"])){
				
				$idPro = $_POST["add_part_code"];
				$qty = $_POST["qty_add"];
				if($idPro!="" && is_numeric($qty) && $qty >0){
					if(isset($arrAllo[$idPro])){
						$arrAllo[$idPro] += $qty;
					}
					else{
						$arrAllo[$idPro] = $qty;
					}
				}
				
				
			}
			
			if(isset($_POST["btn_modify"]) || isset($_POST["btn_confirm"])){
				
				foreach($_POST["qty_alloc"] as $id => $qtyAllo){
					if($rOrder->isInOrder($pon,$id)){
						//update the qty 
						$rOrder->updateAllocateByPON($pon, $id, $qtyAllo);
					}
					else{
						//echo "New Insert";
						$pRow = $rProduct->getProduct($id);
						$rOrder->addOrder($pon, $idShop, $dateOrder, $id, $pRow['code_product'],0, 0, 0, 0, 0,0,$qtyAllo);
					}
					
					if(isset($_POST["id_modify"][$id])){
						
						$arrAllo[$id] = $qtyAllo;
					}
					//modify
					
				}
				
				if(isset($_POST["btn_confirm"])){
					echo "Order Confirmed, Email Sent,Order Listed In KAKT List, you may close the page now<br />";
					
					$subject = "Parts Order Ready For Dipatch,Please Confirm It";
					$mailBody = "copy the link below to your browser to confirm your order<br /> http://115.64.171.97:1080/repaircenter/order-confirm/pon/{$pon}";
					
					$rOrderAsKt->addStatus(trim($pon),"Repair Order",self::$rCenterMappRev[$idShop]);
					$idKa = $rOrderAsKt->getStatusByNumber(trim($pon));
					//var_dump($idKa);
					$rKaKtRecord->addRecord($idKa["id"],1,"Phone Repair");									
					$shopHead = self::$rCenterMappRev[$idShop];
					$sh = new Model_DbTable_Shoplocation();
					$shopMail = $sh->getShopMailByHead($shopHead);
					$mail->sendNormalEmail($shopMail,$subject,$mailBody);
				}				
				
			}
		
		}
		$this->view->arrAllo = $arrAllo;
		$this->view->strArrAllo = base64_encode(serialize($arrAllo));
		
		$this->view->oList = $rOrder->getByPON($pon);
		
		//var_dump($arrAllo);
		
	}
	public function orderActivationAction(){
		
		$pon = $this->_getParam("pon");
		$this->view->orderNo = $pon;
			
		$rOrder = new Model_DbTable_Pr_Order();
		$rProduct = new Model_DbTable_Pr_Prproducts();
		$rMove = new Model_DbTable_Pr_Prstockmovement();
		$rStock = new Model_DbTable_Pr_Prstock();
		$rOrderAsKt = new Model_DbTable_Products_Stock_Transferadjuststatus();
		$kaktRecord = new Model_DbTable_Products_Stock_Kaktstatusrecord();
				
		$oList = $rOrder->getByPON($pon);
		$moveRow = $rMove->getPartsRecordByJobId($pon);
		
	if(!empty($moveRow)){
			$this->view->error = true;
		}
		
		if($_POST){
		
		foreach($oList as $key => $v){
	
		if(!empty($moveRow)){
			echo "Order Already Actived, Do not Need to do it again";
			break;
		}	
		if($v["qty_allocate"]>0){
		$rStock->descreaseStockWarehouse($v["id_product"], $v["qty_allocate"]);
		//shop stock +
		$rStock->increaseStockShop($v["id_product"], $v["qty_allocate"],$v["id_shop"]);
		//record movement
		$rMove->addMovement(3,$v["id_product"], $v["qty_allocate"],"repair Parts Order",$v['id_shop'],trim($pon));
		}	
		}
			
		$idKa = $rOrderAsKt->getStatusByNumber(trim($pon));
		$rOrderAsKt->activeTheTransfer($idKa["id"],Model_DatetimeHelper::dateToday(),'Phone Repair');
		$kaktRecord->addRecord($idKa["id"],99,"repair part");
		$rOrder->OrderSent(trim($pon));
			
		echo " You may Close the Window Now";
		//active kakt
		}
		
		$this->view->oList = $oList;
		
	}
	
	public function recordCustomerPartsAction(){
		
		$rjob = new Model_DbTable_Repairjob();
		$arrIdJob = array();
		
		if($_POST){
			
			$arrIdJob = unserialize(base64_decode($_POST["str_jobid"]));
			
			
			if(isset($_POST['btn_save'])){
				
				$this->view->showBarcode = true;
				
				$rjob->custPartRecord($_POST["last_id_job"],$_POST['is_correct'],$_POST['text_incorrect']);
			}
			else{
				
				$arrIdJob[] = $_POST["id_job"];
				//var_dump($arrIdJob);
				
				$this->view->showBarcode = false;
				$this->view->lastJobID = $_POST["id_job"];
			}
		
			
		}else{
			
			$this->view->showBarcode = true;
		}
		
		
		$this->view->strJobID = base64_encode(serialize($arrIdJob));
		$this->view->arrIdJob = $arrIdJob;
		
	}
	
	public function recordCustomerPartsExpressAction(){
	
		$rjob = new Model_DbTable_Repairjob();
		$arrIdJob = array();
	
		if($_POST){
				
			$arrIdJob = unserialize(base64_decode($_POST["str_jobid"]));
				
			$rjob->custPartRecord($_POST["id_job"],1,"");
			$arrIdJob[] = $_POST["id_job"];
			$this->view->lastJobID = $_POST["id_job"];						
		}
	
		$this->view->strJobID = base64_encode(serialize($arrIdJob));
		$this->view->arrIdJob = $arrIdJob;
	
	}	
	
	
	
	public function exportPartsPriceAction(){
		//export 
		$option = $this->_getParam("all");
		
		$rProduct = new Model_DbTable_Pr_Prproducts();
		$rList = $rProduct->listAllWithBonus();
		
		if($option == "yes"){
			
			$rList = $rProduct->listAllProducts();
		}
		
		if($option == "active"){
			
			$rList = $rProduct->listAllActive();
		}
		
		$dateToday = Model_DatetimeHelper::dateToday();
		$timeNow = Model_DatetimeHelper::timeNow("-");
		$fileName = "PRPRICE-".$dateToday."-".$timeNow;
		
		$arrPartPrice = array();
		
		foreach($rList as $key => $v){
			 $arrTmp = array();
			 $arrTmp[] = $v["id_product"];
			 $arrTmp[] = $v["code_product"];
			 $arrTmp[] = $v["title_product"];
			 $arrTmp[] = $v["price_a"];
			 $arrTmp[] = $v["price_b"];
			 $arrTmp[] = $v["price_c"];
			 $arrTmp[] = $v["price_d"];
			 $arrTmp[] = $v["price_e"];
			 $arrTmp[] = $v["price_f"];
			 $arrTmp[] = $v["price_g"];
			 $arrTmp[] = $v["price_h"];
			 $arrTmp[] = $v["price_i"];
			 $arrTmp[] = $v["price_j"];
			 $arrTmp[] = $v["price_k"];
			 $arrTmp[] = $v["price_l"];
			 $arrTmp[] = $v["price_m"];
			 $arrTmp[] = $v["price_n"];
			 $arrTmp[] = $v["price_o"];
			 $arrTmp[] = $v["price_p"];
			 $arrTmp[] = $v["price_q"];
			 $arrTmp[] = $v["price_r"];
			 $arrTmp[] = $v["price_s"];
			 $arrTmp[] = $v["price_t"];
			 $arrTmp[] = $v["act_cost"];
			 $arrTmp[] = $v["amt_full_bonus"];
			 $arrTmp[] = $v["amt_dis_bonus"];
			 $arrTmp[] = $v["active"];
			 $arrPartPrice[] = $arrTmp;
		}
		
		$fl = new Model_Fileshandler();
		$fl->exportPartsPriceExcel($fileName, $arrPartPrice);
		
		
	}
	
	public function shopBorrowSummaryAction(){
		
	}
	
	public function thisWeekReturnAction(){
		
		$this->view->dateBegin = $dateBegin = Model_DatetimeHelper::getLastWeekMonday();
		$this->view->dateEnd = $dateEnd = Model_DatetimeHelper::getLastWeekSunday();
		
		$isFirst = $this->_getParam("day");
		$arrShop = array();
		if($isFirst ==1 ){
			$arrShop = array("BBPC","CSIC","EPPC","HPIC","NLPC","WBIC","WBPC","WGPC","WGIC");
		}
		else{
			$arrShop = array("BHPC","BSIC","BSPC","BSXP","DCIC","FGIC","KCPC","PMIC","PMPC","SLIC","CBPC");
		}	
		sort($arrShop,SORT_ASC);
		
		$fp = new Model_DbTable_Faultyproduct();
		$rMove = new Model_DbTable_Pr_Prstockmovement();
		$rPro = new Model_DbTable_Pr_Prproducts();
		$rJob = new Model_DbTable_Repairjob();
		
		
		
		$arrList = array();
		foreach($arrShop as $shop){
			//echo $shop;
			$fList = $fp->listAllUnhandledFaultyByShopByDate($shop, $dateBegin, $dateEnd);
			//var_dump($fList);
			$rList = $rMove->getStockMoveByDateByCode(2, $dateBegin,$dateEnd,$this->rCenterMapping[$shop]);
			$arrJobIdList = array();
			if(in_array($shop,self::$rCenterMappRev)){
			foreach($rList as $partrow){
				$rPDetailRow = $rPro->getProduct($partrow['id_product']);
				//var_dump($partrow);
				if($rPDetailRow['w_bonus']){
					
					$rProMove = $rMove->getProductMoveByDateByCode(2,$partrow['id_product'], $dateBegin,$dateEnd,$this->rCenterMapping[$shop]);
					//var_dump($rProMove);
					
					foreach($rProMove as $k => $v){
						
						if(is_numeric($v["id_job"])){
						$rJobRow = $rJob->getJob($v["id_job"]);
						//var_dump($rJobRow);
						if($rJobRow["cust_part"]==0){
							//echo $v["id_job"]."<br/>";
							$arrJobIdList[] = $v["id_job"];
						}
						}
						
					}
					
				}
			}
			}
			$arrJobIdList = array_unique($arrJobIdList);
			
			$tmpArr = array($shop,($fList)?Count($fList):0,Count($arrJobIdList));
			$arrList[] = $tmpArr;
		}
		
		//var_dump($arrList);
		$this->view->arrList = $arrList;
	}
	/*
	 * Manage Price Category Action
	 */
		public function managePriceCategoryAction(){
		
		$prCate = new Model_DbTable_Pr_Pricecategory();
		
		if($_POST){
			//var_dump($_POST);
			foreach($_POST['id_price_category'] as $k => $v){
				$active = (isset($_POST['active'][$k]))?1:0;
				$activeEdit = (isset($_POST['active_edit'][$k]))?1:0;
				$prCate->updatePricecategory($k,$_POST['title_category'][$k],$_POST['comment_category'][$k],$active,$activeEdit,$_POST['bk_color'][$k],$_POST['order'][$k]);
			}
		}
		
		$this->view->pList = $prCate->listAll();
		//var_dump($this->view->pList);
	
	}
	
	public function printBorrowPageAction(){
		
		$idJob = $this->_getParam("jid");
		if($idJob!=""){
		$prLoan = new Model_DbTable_Pr_Loan();
		$this->view->borrowCodes = $prLoan->getByJobId($idJob);
		//var_dump($this->view->borrowCodes);
		}
	}
	
	public function partsStockTakeSummaryShopAction(){
		
		$idShop = $this->_getParam("shop");
		$email = $this->_getParam("email");
		
		$sh = new Model_DbTable_Shoplocation();
		
		$shopHead = self::$rCenterMappRev[$idShop];
		$emailAddr = $sh->getStoreManMailByHead($shopHead);
		$this->view->emailAddr = $emailAddr;
		
		$dateOrder = Model_DatetimeHelper::getThisWeekMonday();
		$dateReceive = $this->_getParam("date");
		
		if($dateReceive!=""){
				
			$dateOrder = $dateReceive;
		}

		$prOrder = new Model_DbTable_Pr_Order();
		$stInfo = new Model_DbTable_Roster_Stafflogindetail();
		$pList = array();
		
		$stExist = $prOrder->stocktakeExist($dateOrder, $idShop);
		
		if(!$stExist){
			// not exist
			$pList['Message']="Stock Take Not Generated, Or , No Parts Consumption Last Week."; 
		}
		else{
			// exist 
			
			//finish?
			$ifFinish = $prOrder->stockTakeDone($idShop,$dateOrder);
			
			
			if($ifFinish[0]){
				$pList = $prOrder->listDiffByDateByShop($dateOrder, $idShop);
				if($pList == false){
					$stLine = $stInfo->getDetail($ifFinish[1]);
					$pList['Message'] = "Stock Take Finish WithOut Any Difference By {$stLine['ni']}";
				}
			}
			
			// not finish 
			
			else{
				$pList['Message'] = "Stock Take Still Pending for Finish";
			}
			
		}
		
		
		$this->view->prDiff = $pList;
		
		$this->view->email = $email;
	}
	
	public function partsStockTakeSummaryAction(){
		
		
		$dateOrder = Model_DatetimeHelper::getThisWeekMonday();
		$dateReceive = $this->_getParam("date");
		
		if($dateReceive!=""){
			
			$dateOrder = $dateReceive;
		}
		
		$prOrder = new Model_DbTable_Pr_Order();
		$rStock = new Model_DbTable_Pr_Prstock();
		$rMove = new Model_DbTable_Pr_Prstockmovement();
		
		$this->view->prDiff = $prOrder->listAllDiffByDate($dateOrder);
		$idShop = 0;
		$adNo = "";
		if($_POST){
			//create KA for each shop 
			foreach($prOrder as $prLine){
				
				if($prLine['id_shop'] != $idShop){
					$adNo = "AD".Model_DatetimeHelper::dateToday("").Model_DatetimeHelper::timeNow("");
					$idShop = $prLine['id_shop']; 
				}
				
				$diff = $prLine['qty_onhand_cf'] - $prLine['qty_onhand'];
				
				$rStock->increaseStockShop($prLine['id_product'],$diff,$prLine['id_shop']);
				
				if($diff >0){
					$rMove->addMovement(5, $prLine['id_product'], $diff,'Parts StockTake',$prLine['id_shop'],$adNo);
				}
				else{
					$rMove->addMovement(6, $prLine['id_product'], $diff,'Parts StockTake',$prLine['id_shop'],$adNo);
				}
				
				$tmpStock = $rStock->getShopStock($prLine['id_product'],$prLine['id_shop']);
				$rMove->addMovement(9, $prLine['id_product'], $tmpStock,'Parts ST Result',$prLine['id_shop'],$adNo);
				
				/*
				$rStock->increaseStockShop($idProduct,$qty,$_POST['id_shop']);
				//record movement
				if($qty >0){
					$rMove->addMovement(5, $idProduct, $qty,$_POST['staff_name'],$_POST['id_shop'],$aINo);
				}
				else{
					$rMove->addMovement(6, $idProduct, 0 - $qty,$_POST['staff_name'],$_POST['id_shop'],$aINo);
				}
				$tmpStock = $rStock->getShopStock($idProduct,$_POST['id_shop']);
				$rMove->addMovement(9, $idProduct, $tmpStock,$_POST['staff_name'],$_POST['id_shop'],$aINo);
				
				*/
				
				
			}
			
		}
			
	}
	
	public function stockTakeAdjustAction(){
		
		$rStock = new Model_DbTable_Pr_Prstock();
		$rMove = new Model_DbTable_Pr_Prstockmovement();
		$rPro = new Model_DbTable_Pr_Prproducts();
		
		$dateTody = Model_DatetimeHelper::dateToday("");
		$timeNow = Model_DatetimeHelper::timeNow("");
		$adNo = "AD".$dateTody.$timeNow;
		 
		
		foreach($_POST['code_product'] as $key => $v){
			
			$diff = $_POST['qty_diff'][$key];
			$prLine = $rPro->getProductByCode($v);
			
			$rStock->increaseStockShop($prLine['id_product'],$diff,$_POST['shop_code']);
			
			if($diff >0){
				$rMove->addMovement(5, $prLine['id_product'], $diff,'Parts StockTake',$_POST['shop_code'],$adNo);
			}
			else{
				$rMove->addMovement(6, $prLine['id_product'], 0 - $diff,'Parts StockTake',$_POST['shop_code'],$adNo);
			}
			
			$tmpStock = $rStock->getShopStock($prLine['id_product'],$_POST['shop_code']);
			$rMove->addMovement(9, $prLine['id_product'], $tmpStock,'Parts ST Result',$_POST['shop_code'],$adNo);
				
		}	
		
		echo "<h1>{$adNo}</h1>";
		
	}
	
	

	
	public function nousecsicAction(){
		/*
		$dateTody = Model_DatetimeHelper::dateToday("");
		$timeNow = Model_DatetimeHelper::timeNow("");
		$adNo = "AD".$dateTody.$timeNow;
		
		
		$rPro = new Model_DbTable_Pr_Prproducts();
		$rStock = new Model_DbTable_Pr_Prstock();
		$rMove = new Model_DbTable_Pr_Prstockmovement();

		$fl = fopen("csic.csv","r");
		
		while(($lineData = fgetcsv($fl,1000))!=false){
			
			$idPro = $rPro->getProductID(trim($lineData[0]));
			
			$rStock->increaseStockShop($idPro,0 - (int)trim($lineData[1]),2);
			$rStock->increaseStockWarehouse($idPro,trim($lineData[1]));
			
			$rMove->addMovement(4, $idPro, (int)trim($lineData[1]), "Norman-CSIC RETURN",2,$adNo);
			$rMove->addMovement(5, $idPro, (int)trim($lineData[1]), "Norman-CSIC RETURN",0,$adNo);
				
		}
		
		fclose($fl);
		
		//to 
		//ad
		//adjust stock 
		 
		*/
	}
	
	public function snapshotAction(){
		
		$shop = $this->_getParam("shop");
		$staffName = $this->_getParam("staff");
		$reason = $this->_getParam("reason");
		
		$prPro = new Model_DbTable_Pr_Prproducts();
		$prStk = new Model_DbTable_Pr_Prstock();
		$prMove = new Model_DbTable_Pr_Prstockmovement();
		
		$pList = $prPro->listAllProducts();
		foreach($pList as $k => $v){
			$prMove->addMovement(9, $v['id_product'],0,"System Snapshot By ".$staffName,$shop,"For :".$reason);
			
		}
		echo "Done";
	}

	

}
?>