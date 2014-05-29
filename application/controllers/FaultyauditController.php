<?php 
/**
 * Phone Collection Application Platform Faulty Product Controller 
 * this Controller mainly 
 * @package  
 * @category Controller
 * @author   Norman Dong <eco1@phonecollection.com.au>
 * @version  Initial
 * @copyright www.phonecollection.com.au
 * @link     www.phonecollection.com.au
 * @license  Commercial
 */
class FaultyauditController extends Zend_Controller_Action
{
	//Faulty Code PreFix
	const PREFIX_FAULTY_REJECT = "FRJ";
	const PREFIX_FAULTY_DISPOSE = "FDP";
	const PREFIX_FAULTY_RETURNCHINA = "FCN";
	const PREFIX_FAULTY_RETURNSUPPLY = "FSU";
	const PREFIX_FAULTY_SALEONLINE = "FSL";
	
	//Faulty Code 
	const FAULTY_STATUS_DISPOSE = 300;
	const FAULTY_STATUS_RSLIST = 41;
	const FAULTY_STATUS_RSRMA = 42;
	const FAULTY_STATUS_RSCREDIT = 43;
	
	const FAULTY_STATUS_RETURNSUPPLY = 400;
	const FAULTY_STATUS_RETURNCHINA = 500;
	const FAULTY_STATUS_SALEONLINE = 600;
	
	
    public function init(){
    /**
	 *
	 *
	 */    
	
	}
	
	public function summaryAction(){
	
	
	}
	

    public function indexAction(){
	
	$this->view->title = " Online RMA Auditing System";
    $this->view->headTitle($this->view->title, 'PREPEND');
    $faulty_product = new Model_DbTable_Faultyproduct();
        
    $this->view->count_bspc = count($faulty_product->listAllUnhandledFaultyByShop("BSPC"));
    $this->view->count_bhpc = count($faulty_product->listAllUnhandledFaultyByShop("BHPC"));
    $this->view->count_bbpc = count($faulty_product->listAllUnhandledFaultyByShop("BBPC"));
    $this->view->count_copc = count($faulty_product->listAllUnhandledFaultyByShop("COPC"));
    $this->view->count_cbpc = count($faulty_product->listAllUnhandledFaultyByShop("CBPC"));
    $this->view->count_eppc = count($faulty_product->listAllUnhandledFaultyByShop("EPPC"));
    $this->view->count_fgpc = count($faulty_product->listAllUnhandledFaultyByShop("FGPC"));
    $this->view->count_hppc = count($faulty_product->listAllUnhandledFaultyByShop("HPPC"));
    $this->view->count_kcpc = count($faulty_product->listAllUnhandledFaultyByShop("KCPC"));
    $this->view->count_nlpc = count($faulty_product->listAllUnhandledFaultyByShop("NLPC"));
    $this->view->count_pmpc = count($faulty_product->listAllUnhandledFaultyByShop("PMPC"));
    $this->view->count_wbpc = count($faulty_product->listAllUnhandledFaultyByShop("WBPC"));
    $this->view->count_bsic = count($faulty_product->listAllUnhandledFaultyByShop("BSIC"));
    $this->view->count_slic = count($faulty_product->listAllUnhandledFaultyByShop("SLIC"));
    $this->view->count_wgic = count($faulty_product->listAllUnhandledFaultyByShop("WGIC"));
    $this->view->count_wbic = count($faulty_product->listAllUnhandledFaultyByShop("WBIC"));
    $this->view->count_adpc = count($faulty_product->listAllUnhandledFaultyByShop("ADPC"));
    $this->view->count_clpc = count($faulty_product->listAllUnhandledFaultyByShop("CLPC"));
    $this->view->count_wlic = count($faulty_product->listAllUnhandledFaultyByShop("WLIC"));
	$this->view->count_wgpc = count($faulty_product->listAllUnhandledFaultyByShop("WGPC"));
    //$this->view->count_bhpc = count($faulty_product->listAllUnhandledFaultyByShop("BHPC"));
    
    if($_POST){
    	
    	$faulty_product->resetAudit($_POST['id_rma']);
    	echo '<script language = "javascript">alert("It is Done;");</script>';
    }
	
    }
    
    public function auditAction(){
    	
    $shopHead = $this->_getParam('shop');
    $this->view->shophead = $shopHead;
    $faulty_product = new Model_DbTable_Faultyproduct();
    $this->view->faultyproducts = $faulty_product->listAllUnhandledFaultyByShop($shopHead);
    
    
    
    }    
    public function updateauditAction(){
    	
    //var_dump($_POST);
    $faulty_product = new Model_DbTable_Faultyproduct();
    
    if(isset($_POST['audit_faulty']) and isset($_POST['audit_handle']) ){
    	
    $idFaulty = $_POST['id_faulty'];    
    $auditFaulty = $_POST['audit_faulty'];
    $auditComment = $_POST['audit_comment'];
    $auditHandle = $_POST['audit_handle'];
    $aduitSupplier = NULL;
    
    $auditSupplier = $_POST['audit_supplier'];
    $auditSupplier2 = $_POST['audit_supplier2'];
    
    if( $auditFaulty == 1 && $auditHandle ==4 ){
    	$auditSupplier = $_POST['audit_supplier'];
    }
    
    if( $auditFaulty == 2 && $auditHandle ==4 ){
    	$auditSupplier = $_POST['audit_supplier2'];
    }    
    
    $faulty_product->auditFaultyProduct($idFaulty, $auditFaulty, $auditComment,$auditHandle,$auditSupplier);
    
    $shopheads = new Model_DbTable_Shoplocation();
    $shopList = $shopheads->getNameByHead(trim($_POST['shophead']));
    $shopEmail = $shopList['shop_email'];
    $managerEmail = $shopList['shop_manager_email'];
    
    $rmaEmail = new Model_Rmaemailshandler();
    if($auditFaulty == 2){
    		$rmaEmail->rmaRejectedemail($idFaulty, $shopEmail, $managerEmail,$auditComment);
    	}	   	
    	
    }	
    else{//save the comment only 
    	$idFaulty = $_POST['id_faulty'];
    	$auditComment = $_POST['audit_comment'];
    	
    	$faulty_product->updateComment($idFaulty, $auditComment);
    }
    
	$this->_redirect("/faultyaudit/audit/shop/".$_POST['shophead']);
	
    }
	

	public function topfaultybarcodeAction(){
		
		$fProduct = new Model_DbTable_Faultyproduct();
		
		if($_POST){
			$dateBegin = $_POST['date_begin'];
			$dateEnd = $_POST['date_end'];
			$topNum = $_POST['topnum'];
			$this->view->topnum = $topNum;
			
			$this->view->fproducts = $fProduct->listFaultyProductByDate($dateBegin,$dateEnd);		
			
			
		}
		else{
			
			echo "Error Occur, Please Submit From Summary Page";
		}
	}
	public function toprejectbarcodeAction(){
	
		$fProduct = new Model_DbTable_Faultyproduct();
	
		if($_POST){
			$dateBegin = $_POST['date_begin'];
			$dateEnd = $_POST['date_end'];
			$topNum = $_POST['topnum'];
			$this->view->topnum = $topNum;
				
			$this->view->fproducts = $fProduct->listRejectFaultyByDate($dateBegin, $dateEnd);
				
				
		}
		else{
				
			echo "Error Occur, Please Submit From Summary Page";
		}
	}
	public function topfaultyshopAction(){
	
		$fProduct = new Model_DbTable_Faultyproduct();
	
		if($_POST){
			$dateBegin = $_POST['date_begin'];
			$dateEnd = $_POST['date_end'];
			//$topNum = $_POST['topnum'];
			//$this->view->topnum = $topNum;
				
			$this->view->fproducts = $fProduct->listFaultyShopByDate($dateBegin,$dateEnd);
				
				
		}
		else{
				
			echo "Error Occur, Please Submit From Summary Page";
		}
	}
	public function toprejectshopAction(){
	
		$fProduct = new Model_DbTable_Faultyproduct();
	
		if($_POST){
			$dateBegin = $_POST['date_begin'];
			$dateEnd = $_POST['date_end'];
			//$topNum = $_POST['topnum'];
			//$this->view->topnum = $topNum;
	
			$this->view->fproducts = $fProduct->listRejectShopByDate($dateBegin, $dateEnd);
	
	
		}
		else{
	
			echo "Error Occur, Please Submit From Summary Page";
		}
	}
	
	public function faultybarcodedetailAction(){
	
	$productCode = trim($_GET['product_code']);
	$dateBegin =  trim($_GET['date_begin']);
	$dateEnd = trim($_GET['date_end']);
	
	$fProduct = new Model_DbTable_Faultyproduct();
	$this->view->fproductrow = $fProduct->singleBarCodeFaultyDetail($productCode, $dateBegin, $dateEnd);
	
	}
	public function faultyshopdetailAction(){
		$shopCode = trim($_GET['shop_code']);
		$dateBegin =  trim($_GET['date_begin']);
		$dateEnd = trim($_GET['date_end']);
		
		$fProduct = new Model_DbTable_Faultyproduct();
		$this->view->fproductrow = $fProduct->singleShopFaultyDetail($shopCode, $dateBegin, $dateEnd);		
	}
	public function exportresultAction(){
		
		//generate the start date and End date 
		//choose shop 
		$fProduct = new Model_DbTable_Faultyproduct();
		
		$arrFileHeader = array("<SCODE>","<MODEL>","<LOC>","<C_QTY>","<ADJ_QTY>");
		
		if($_POST){
		$afterFix= rand(100,999);
			
		$resultRow = $fProduct->getAuditResult($_POST['date_begin'], $_POST['date_end']);
		//var_dump($resultRow);
			//create file 	
		$fileName = "AEX".trim($_POST['date_begin'])."-".trim($_POST['date_end']).$afterFix.".csv";
		$fl=fopen(getcwd()."/rmaexport/".$fileName,"w");
		fputcsv($fl,$arrFileHeader);
		foreach($resultRow as $key => $value){
			$tmpArr = array();
			$tmpArr[0] = trim(strtoupper($value['product_code_faulty']));
			$tmpArr[1] = " ";
			switch($value['shopcode_faulty']){
				case('ADPC'):$tmpArr[2]="AD";break;
				case('BBPC'):$tmpArr[2]="BB";break;
				case('BHPC'):$tmpArr[2]="BH";break;
				case('BSPC'):$tmpArr[2]="BS";break;
				case('BSIC'):$tmpArr[2]="BSIC";break;
				case('CBPC'):$tmpArr[2]="CB";break;
				case('CLPC'):$tmpArr[2]="CL";break;
				case('COPC'):$tmpArr[2]="CO";break;
				case('EPPC'):$tmpArr[2]="EP";break;
				case('FGPC'):$tmpArr[2]="FG";break;
				case('HPPC'):$tmpArr[2]="HP";break;
				case('KCPC'):$tmpArr[2]="KC";break;
				case('NLPC'):$tmpArr[2]="NL";break;
				case('PMPC'):$tmpArr[2]="PM";break;
				case('SLIC'):$tmpArr[2]="SLIC";break;
				case('WBPC'):$tmpArr[2]="WB";break;
				case('WBIC'):$tmpArr[2]="WBIC";break;
				case('WLIC'):$tmpArr[2]="WLIC";break;
				case('WGIC'):$tmpArr[2]="WGIC";break;
				case('CSIC'):$tmpArr[2]="CSIC";break;
				case('CLIC'):$tmpArr[2]="CLIC";break;
				case('DCIC'):$tmpArr[2]="DCIC";break;
				case('PMIC'):$tmpArr[2]="PMIC";break;
				case('BSXP'):$tmpArr[2]="BSXP";break;
				case('FGIC'):$tmpArr[2]="FGIC";break;
				case('WGPC'):$tmpArr[2]="WGPC";break;
			}
			
			$tmpArr[3] = " ";
			$tmpArr[4] = "-".$value["SUM(`faulty_qty`)"].".000";//Qty
			
			fputcsv($fl, $tmpArr);
			
		}
		fclose($fl);
		
		}
	
	}
	
	// Reject  2  to 200  only one step 
	public function printRejectListAction(){
		
		$faultyProduct = new Model_DbTable_Faultyproduct();
		$this->view->rejectList = $faultyProduct->listAllRejectInitial();
	
	}
	public function saveRejectListAction(){
		
		
		date_default_timezone_set('Australia/Melbourne');
		$dateToday = Model_DatetimeHelper::dateToday();
		$faultyProduct = new Model_DbTable_Faultyproduct();
		$faultyStatusRecord = new Model_DbTable_Faulty_Status();
		
		$randNo = rand(1,9);
		
		$remark = self::PREFIX_FAULTY_REJECT.$dateToday.$randNo;
		
		if($_POST){
			
			foreach($_POST["arr_action"] as $k => $v){
				//update status
				echo $v;
				echo $_POST['faulty_handle'];
				//$faultyProduct->updateFaultyHandleStatus($v,200);
				
				//record 
				$faultyStatusRecord->addRecord($v,$_POST['faulty_handle'], $dateToday,$_POST["staff_name"], $remark);
				
			}
			
		}
		
	}
	//Dispose 3 to 300 only one step
	public function printDisposeListAction(){
		
		$faultyProduct = new Model_DbTable_Faultyproduct();
		$this->view->disposeList = $faultyProduct->listAllDisposeInitial();
		//echo self::FAULTY_STATUS_DISPOSE;
	}
	public function saveDisposeListAction(){
		
		date_default_timezone_set('Australia/Melbourne');
		$dateToday = Model_DatetimeHelper::dateToday();
		$faultyProduct = new Model_DbTable_Faultyproduct();
		$faultyStatusRecord = new Model_DbTable_Faulty_Status();
		$randNo = rand(1,9);
		//$tmpId = $this->FAULTY_STATUS_DISPOSE;
		$remark = self::PREFIX_FAULTY_DISPOSE.$dateToday.$randNo;
		
		if($_POST){
				
			foreach($_POST["arr_action"] as $k => $v){
				$faultyProduct->updateFaultyHandleStatus($v,self::FAULTY_STATUS_DISPOSE);
				//record
				$faultyStatusRecord->addRecord($v,self::FAULTY_STATUS_DISPOSE,$dateToday,$_POST["staff_name"], $remark);
			}
				
		}				
		$this->view->fdpNo = $remark;
		$this->view->date = $dateToday;
	}
	// Return 4 to 400 only one step 
	public function printReturnChinaAction(){
		$faultyProduct = new Model_DbTable_Faultyproduct();
		$this->view->chinaList = $faultyProduct->listAllReturnChinaInitial();
	}
	public function saveReturnChinaAction(){
		
		date_default_timezone_set('Australia/Melbourne');
		$dateToday = Model_DatetimeHelper::dateToday();
		$faultyProduct = new Model_DbTable_Faultyproduct();
		$faultyStatusRecord = new Model_DbTable_Faulty_Status();
		$randNo = rand(1,9);
		//$tmpId = $this->FAULTY_STATUS_DISPOSE;
		$remark = self::PREFIX_FAULTY_RETURNCHINA.$dateToday.$randNo;	

		if($_POST){
		
			foreach($_POST["arr_action"] as $k => $v){
				$faultyProduct->updateFaultyHandleStatus($v,self::FAULTY_STATUS_RETURNCHINA);
				//record
				$faultyStatusRecord->addRecord($v,self::FAULTY_STATUS_RETURNCHINA,$dateToday,$_POST["staff_name"], $remark);
			}
		
		}
		$this->view->fcnNo = $remark;
		$this->view->date = $dateToday;		
	}
	// transfer to Online 
	public function printTransferOnlineAction(){
		$faultyProduct = new Model_DbTable_Faultyproduct();
		$this->view->saleList = $faultyProduct->listAllSaleOtherInitial();
	}
	public function saveTransferOnlineAction(){
		
		date_default_timezone_set('Australia/Melbourne');
		$dateToday = Model_DatetimeHelper::dateToday();
		$faultyProduct = new Model_DbTable_Faultyproduct();
		$faultyStatusRecord = new Model_DbTable_Faulty_Status();
		$randNo = rand(1,9);
		//$tmpId = $this->FAULTY_STATUS_DISPOSE;
		$remark = self::PREFIX_FAULTY_SALEONLINE.$dateToday.$randNo;
		
		if($_POST){
		
			foreach($_POST["arr_action"] as $k => $v){
				$faultyProduct->updateFaultyHandleStatus($v,self::FAULTY_STATUS_SALEONLINE);
				//record
				$faultyStatusRecord->addRecord($v,self::FAULTY_STATUS_SALEONLINE,$dateToday,$_POST["staff_name"], $remark);
			}
		
		}
		$this->view->fslNo = $remark;
		$this->view->date = $dateToday;		
		
	}
	// Return to Supplier 
	public function printReturnSupplierAction(){
		
		//choose supplier ,print list 
		$faultyProduct = new Model_DbTable_Faultyproduct();
		if($_GET["spid"]==""){
			$this->view->supplierList = $faultyProduct->listAllReturnSupplyInitial();
		}
		else{
			$this->view->supplierList = $faultyProduct->listStatusBySupplier(4,$_GET["spid"]);
		}
		//list choose seal box
	}
	public function saveReturnSupplierAction(){
		// This means flora gather all the list 
		// collect enough in the box 
		//give a list and also email to elain
		//change status to 51
		//if kept 51 by 2 days , send email to flora 
		date_default_timezone_set('Australia/Melbourne');
		$dateToday = Model_DatetimeHelper::dateToday();
		$faultyProduct = new Model_DbTable_Faultyproduct();
		$faultyStatusRecord = new Model_DbTable_Faulty_Status();
		$randNo = rand(1,9);
		//$tmpId = $this->FAULTY_STATUS_DISPOSE;
		$remark = self::PREFIX_FAULTY_RETURNSUPPLY.$dateToday.$randNo;
		
		if($_POST){
		
			foreach($_POST["arr_action"] as $k => $v){
				$faultyProduct->updateFaultyHandleStatus($v,self::FAULTY_STATUS_RSLIST);
				//record
				$faultyStatusRecord->addRecord($v,self::FAULTY_STATUS_RSLIST,$dateToday,$_POST["staff_name"], $remark);
			}
		
		}
		$this->view->fslNo = $remark;
		$this->view->date = $dateToday;
		
	}
	
	
	public function submitRmaAction(){
		//see the list  , do online or call 
		//fill in rma no , if no rma number use the one created 
		$faultyProduct = new Model_DbTable_Faultyproduct();
		if($_GET["spid"]==""){
			$this->view->supplierList = $faultyProduct->listAllReturnSupplyRma();
		}
		else{
			$this->view->supplierList = $faultyProduct->listStatusBySupplier(41,$_GET["spid"]);
		}
		//list choose seal box
		
		
	}
	public function saveSubmitRmaAction(){
		
		date_default_timezone_set('Australia/Melbourne');
		$dateToday = Model_DatetimeHelper::dateToday();
		$faultyProduct = new Model_DbTable_Faultyproduct();
		$faultyStatusRecord = new Model_DbTable_Faulty_Status();
		$randNo = rand(1,9);
		//$tmpId = $this->FAULTY_STATUS_DISPOSE;
		$remark = self::PREFIX_FAULTY_RETURNSUPPLY.$dateToday.$randNo;
		
		if($_POST){
		
			foreach($_POST["arr_action"] as $k => $v){
				$faultyProduct->updateFaultyHandleStatus($v,self::FAULTY_STATUS_RSRMA);
				$lastRemark =$faultyStatusRecord->getLastRecord($v); 
				//record
				$faultyStatusRecord->addRecord($v,self::FAULTY_STATUS_RSRMA,$dateToday,$_POST["staff_name"],$lastRemark["remark_status"],$_POST["rma_no"]);
			}
		
		}
		$this->view->fslNo = $remark;
		$this->view->date = $dateToday;
		//
	}
	
	public function checkUnhandledAction(){
		//2/3/4/5/6 -> take longer then 2 weeks - email flora 
		//51 -> 1 week  elain 
		//52 -> 3 days elain
		//53 -> two weeks elain 
		//
		
	}
	public function postBackAction(){

		$faultyProduct = new Model_DbTable_Faultyproduct();
		if($_GET["spid"]==""){
			$this->view->supplierList = $faultyProduct->listAllReturnSupplyPostback();
		}
		else{
			$this->view->supplierList = $faultyProduct->listStatusBySupplier(42,$_GET["spid"]);
		}
		
		
	}
	public function savePostBackAction(){
		
		date_default_timezone_set('Australia/Melbourne');
		$dateToday = Model_DatetimeHelper::dateToday();
		$faultyProduct = new Model_DbTable_Faultyproduct();
		$faultyStatusRecord = new Model_DbTable_Faulty_Status();
		$randNo = rand(1,9);
		//$tmpId = $this->FAULTY_STATUS_DISPOSE;
		$remark = self::PREFIX_FAULTY_RETURNSUPPLY.$dateToday.$randNo;
		
		if($_POST){
		
			foreach($_POST["arr_action"] as $k => $v){
				$faultyProduct->updateFaultyHandleStatus($v,self::FAULTY_STATUS_RSCREDIT);
				//record
				$lastRemark =$faultyStatusRecord->getLastRecord($v);
				$faultyStatusRecord->addRecord($v,self::FAULTY_STATUS_RSCREDIT,$dateToday,$_POST["staff_name"],$lastRemark["remark_status"],NULL,$_POST["tracking_no"]."[".$_POST["ship_method"]."]");
			}
		
			
			$mail = new Model_Rmaemailshandler();
			$mail->faultySendBackEmail($_POST["ship_method"]);
		}
		$this->view->fslNo = $remark;
		$this->view->date = $dateToday;		
		
		
		
	}
	public function listWaitCreditAction(){
		$faultyProduct = new Model_DbTable_Faultyproduct();
		if($_GET["spid"]==""){
			$this->view->supplierList = $faultyProduct->listAllReturnSupplyWaitCredit();
		}
		else{
			$this->view->supplierList = $faultyProduct->listStatusBySupplier(43,$_GET["spid"]);
		}		
		
	}
	public function saveCreditReceivedAction(){
		
		date_default_timezone_set('Australia/Melbourne');
		$dateToday = Model_DatetimeHelper::dateToday();
		$faultyProduct = new Model_DbTable_Faultyproduct();
		$faultyStatusRecord = new Model_DbTable_Faulty_Status();
		$randNo = rand(1,9);
		//$tmpId = $this->FAULTY_STATUS_DISPOSE;
		$remark = self::PREFIX_FAULTY_RETURNSUPPLY.$dateToday.$randNo;
		
		if($_POST){
		
			foreach($_POST["arr_action"] as $k => $v){
				$faultyProduct->updateFaultyHandleStatus($v,self::FAULTY_STATUS_RETURNSUPPLY);
				//record
				$lastRemark =$faultyStatusRecord->getLastRecord($v);
				$faultyStatusRecord->addRecord($v,self::FAULTY_STATUS_RETURNSUPPLY,$dateToday,$_POST["staff_name"],$remark,NULL,NULL,$_POST["credit_note"]);
			}
		
		}
		$this->view->fslNo = $remark;
		$this->view->date = $dateToday;		
		
		
	}
	
	public function faultyStatusAction(){
		
		
	}
	
	public function listFinalStatusAction(){
		$stHead = $this->_getParam("code");
		$fStatus = new Model_DbTable_Faulty_Status();
		$this->view->list = $fStatus->listByStatusHead($stHead);
		
	}
	public function listStatusDetailAction(){
		
		$stHead = $this->_getParam("code");
		$fStatus = new Model_DbTable_Faulty_Status();
		$this->view->list = $fStatus->listByStatusHead($stHead,true);		
		
	}
	
	public function checkReturnSupplyConditionAction(){
		$cond = trim($_GET["cond"]);
		if($cond==4){
			//check every 2 weeks 
			
			
		}
		if($cond==41){
			//flora gathered the products need to give to elain
			//get all last status is 51
			//check if any date last two days
			//if yest send email to flora
		}

		if($cond==42){
			//recieve the case not do the online staff
			//get all last status is 52
			//check if any date last 3 days
			//if yest send email to flora
		}		
		
		if($cond==43){
			//online rma done, need to ship out
			//remark the tracking no 
			//give 5 days 
		}
		
	}
	//
	
}
?>