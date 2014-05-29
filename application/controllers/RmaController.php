<?php 
/**

 */
class RmaController extends Zend_Controller_Action
{  
	public static $rCenterMappRev;

    public function init(){   
		self::$rCenterMappRev = serialize(ARR_RCENTER_REV_MAPPING);
	}

    public function indexAction(){
	
	$this->view->title = "Online RMA Lodgement System";
    $this->view->headTitle($this->view->title);
    
	}
	
	public function newAction(){
		
		$this->view->title = "Online RMA-New RMA";
		$this->view->headTitle($this->view->title,'PREPEND');
		
		$arrRepairCenter = self::$rCenterMappRev;
		
		
		//selection shop 
		$shopHead = $this->_getParam('shop');
		//echo $shopHead;
		if($shopHead!=""){
			
			$shops = new Model_DbTable_Shoplocation();
			$shopName = $shops->getNameByHead($shopHead);
			$this->view->rCenterKey = array_search($shopHead, $arrRepairCenter);
	
		}
		//echo "You are now login as ".$shopName['name_of_shop'];
		$this->view->shophead = $shopHead;
		if(isset($shopName)){
		$this->view->shopname = $shopName['name_of_shop'];
		}
		else{
		$this->_redirect("/rma/");
		$this->view->shopname = "<h1>Login ERROR</h1>";
		}
	}
	
	public function fillAction(){

		$this->view->title = "Online RMA-Fill In Details";
		$this->view->headTitle($this->view->title,'PREPEND');
		
		
		$shophead = $_POST['shophead'];
		$productcode = $_POST['productcode'];
		$staffname = $_POST['staffname'];
		if(trim($shophead)=="" or trim($productcode) =="" or trim($staffname) == ""){
			
			$this->_redirect("/rma/");
		}
		
		$errorflag = 0;
		
		if($shophead!=""){
			
			$this->view->shophead = $shophead;			
		}
		else{
			
			$errorflag = 1;
		}
		
		if($productcode!=""){
				
			$this->view->productcode = $productcode;
		}
		else{
				
			$errorflag = 1;
		}		
		if($staffname!=""){
				
			$this->view->staffname = $staffname;
		}
		else{
				
			$errorflag = 1;
		}
				
		}
	
	public function confirmAction(){
		
		$this->view->title = "Online RMA-Confirm Your Submition";
		$this->view->headTitle($this->view->title,'PREPEND');
		
	}
	public function uploadresultAction(){
		/*
		echo "<pre>";
		var_dump($_POST);
		echo "</pre>";
		*/
		date_default_timezone_set('Australia/Melbourne');
		
		
		$this->view->dateToday = $dateToday = Model_DatetimeHelper::dateToday();
		$this->view->weekId = $weekId = date("W",strtotime($dateToday)) % 2;
		
		
		$this->view->title = "Online RMA-Save Submition";
		$this->view->headTitle($this->view->title);
		
		$productCode = $_POST['product_code'];
		$productName = $_POST['product_name'];
		$shopCode = $_POST['shop_head'];
		$staffName = $_POST['staff_name'];
		$qty = $_POST['product_qty'];
		$reasonFaulty = $_POST['faulty_reason'];
		$commFaulty = $_POST['faulty_comment'];
		$idInvoice = "";
		if(trim($_POST['invoice_number'])==""){
			
			$this->view->rmano = "ERROR OCCUR,MUST Input the Invoice Number.";
		}
		else{

			if(!isset($_POST['invoice_number_faulty'])){$idInvoice=trim($_POST['invoice_number']);}
			else{$idInvoice=trim($_POST['invoice_number_faulty']);}
			
			
		}
		
		$dateSales = $_POST['product_sale_date'];
		$dateReturn = $_POST['customer_return_date'];		
		
		$newFaultyProduct = new Model_DbTable_Faultyproduct();
		
		$fn = $newFaultyProduct->listAllUnhandledFaultyByShopByDate($shopCode,Model_DatetimeHelper::getThisWeekMonday(), $dateToday);
		$this->view->faultyNo = count($fn) + 1;
		$rmareturn = $newFaultyProduct->addFaultyProduct($productCode, $productName, $shopCode, $staffName, $qty, $reasonFaulty, $commFaulty, $idInvoice, $dateSales, $dateReturn);
		
		
		if(isset($rmareturn)){
			$this->view->rmaID = $rmareturn;
			}
		else{
			$this->view->rmaID = "ERROR OCCUR";
			}	
		//addFaultyProduct($productCode,$productName,$shopCode,$staffName,$qty,$reasonFaulty,$commFaulty,$nameCustomer,$idInvoice,$dateSales,$dateReturn)
		
		
	}
				
	public function printsummaryAction(){
	
		$this->view->title = "Online RMA-Summary";
		$this->view->headTitle($this->view->title);
		
		$week = "this";
		$shopHead = $this->_getParam('shop');
		$week = $this->_getParam('week');
		
		$this->view->week = $week;
		
		date_default_timezone_set('Australia/Melbourne');
		
		$dateBegin = "";
		$dateEnd = "";
		$thisMonday = "";
		$thisSunday = "";
		$lastSunday = "";
		$lastMonday = "";
		
		if(intval(date("w"))==1){
			$thisMonday = date("Y-m-d",strtotime("this monday"));
		}
		else{
			$thisMonday = date("Y-m-d",strtotime("last monday"));
		}
		$thisSunday = date("Y-m-d",strtotime("+6 day",strtotime($thisMonday)));
		$lastSunday = date("Y-m-d",strtotime("-1 day",strtotime($thisMonday)));
		$lastMonday = date("Y-m-d",strtotime("-7 day",strtotime($thisMonday)));
		
		if($week == "this"){
			$dateBegin = $thisMonday;
			$dateEnd = $thisSunday;
			
		}
		elseif($week == "last"){
			$dateBegin = $lastMonday;
			$dateEnd = $lastSunday;			
			
		}
		
		$shopName ="";
		$newShopName = new Model_DbTable_Shoplocation();
		if($shopHead!=""){
			
			$shopName = $newShopName->getNameByHead($shopHead);
			$this->view->shopname = $shopName['name_of_shop'];	
		}
		
		$faultyProducts = new Model_DbTable_Faultyproduct();
		//$faultyResult = $faultyProducts->listAllUnhandledFaultyByShop($shopHead);
		$faultyResult = $faultyProducts->listAllUnhandledFaultyByShopByDate($shopHead, $dateBegin, $dateEnd);
		if($faultyResult){
			
			$this->view->faultyproducts = $faultyResult;
			
		}
		else{
			
			$this->view->faultyproducts = "There are no NEW RMA Recorder that unhandled";
		}
		
		$this->view->dateBegin = $dateBegin;
		$this->view->dateEnd = $dateEnd;
		
		
	}	
	
	public function reprintRmaAction(){
		
		$idRma = $this->_getParam("idrma");
		
		$rma = new Model_DbTable_Faultyproduct();
		$rList = $rma->getFaultyProduct($idRma);
		
		$this->view->weekId = $rList['week_of_year_faulty']%2;
		$this->view->rmaID = $rList['id_faulty'];
		$this->view->productCode = $rList['product_code_faulty'];
		$this->view->productDes = $rList['product_name_faulty'];
		$this->view->dateToday = $rList['date_faulty'];
		$this->view->shopHead = $rList['shopcode_faulty'];
		$this->view->staffName = $rList['staff_name_faulty'];
		
		//$this->view->rList = $rList;
	}

			
}
?>