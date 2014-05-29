<?php 
/**

 */
class RepaircenterController extends Zend_Controller_Action
{

	protected $rCenterMapping = array(
			"ADPC" => 17,
			"BBPC" => 18,
			"BHPC" => 4,
			"BSIC" => 21,
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
			"WHLU" => 20,
			"WHBN" =>3,
			"EPIC" =>8		
			);
	public static $rCenterMappingReserve = array(
			1 => array("BSXP","BSPC","BSIC"),
			2 => array("CSIC"),
			3 => array("WHBN"),
			4 => array("BHPC"),
			5 => array("HPIC"),
			6 => array("CBPC"),
			7 => array("WLIC"),
			8 => array("EPIC"),
			9 => array("WBPC","WBIC"),
		   10 => array("NLPC"),
		   11 => array("DCIC"),
		   12 => array("KCPC"),
		   13 => array("SLIC"),
		   14 => array("FGIC"),
		   15 => array("PMIC","PMPC"),
		   16 => array("WGIC","WGPC"),
		   17 => array("ADPC"),
		   18 => array("BBPC"),
		   19 => array("CLPC","CLIC"),
		   20 => array("WHLU"),
		   21 => array("BSIC")								
		);
	
    public function init(){
    /**
	 *
	 *
	 */    
	}

    public function indexAction(){
	
		echo "Repair Center Controller";	

	}
	public function mainpageAction(){

		$shopHead = $this->_getParam('shop');
		$shopName = "";
		$this->view->shophead = $shopHead;
		
		if($shopHead!=""){
				
			$shops = new Model_DbTable_Shoplocation();
			$shopName = $shops->getNameByHead($shopHead);
		
		}
		$this->view->shopname = $shopName;
		$this->view->rCenterMapping  = $this->rCenterMapping;
		
		//echo "This is main choose page";
		
	}

	public function newAction(){
		
		$shopHead = $this->_getParam('shop');
		if($shopHead == "" or strlen($shopHead)!= 4 ){
			
			$this->_redirect("/repaircenter/");
				
		}
		$shopName = "";
		$this->view->shophead = $shopHead;
		
		date_default_timezone_set('Australia/Melbourne');
		$jobIdDate = intval(date("Ymd"));
		
		$jobIdCounter = new Model_DbTable_Jobidcounter();
		
		$repairJob = new Model_DbTable_Repairjob();
		
		$newCounter = $jobIdCounter->runCounter();
		
		$newJobID = $jobIdDate*100000 + $newCounter;	
		
		$this->view->newjobid = $newJobID;		
	}
	public function confirmquoteAction(){
		
		date_default_timezone_set('Australia/Melbourne');
		$dateToday = date("Y-m-d");
		$timeNow = date(" H:i");		
		$repairJob = new Model_DbTable_Repairjob();
		$shopCode = $this->_getParam('shop');
		$newJobID = $this->_getParam('newjobid');
		$staffName = $_POST['staff_name'];
		$dateStart = $dateToday;
		$timeStart = $timeNow;
		$brand = $_POST['mobile_brand'];
		$model = $_POST['mobile_model'];
		if($model == "Not In The List"){
			$model =  $_POST['mobile_model_other'];
		}
		$imei = $_POST['mobile_imei'];
		//$partsSelection =  $_POST['parts_select'];
		$partsSelection = "parts";
		$repairType = implode(",",$_POST['repair_type']); 
		$issueDetail = $_POST['issue_detail'];
		$cname = $_POST['customer_name'];
		$cemail = $_POST['customer_email'];
		$cphone = $_POST['customer_homephone'];
		$cmobile = $_POST['customer_mobile'];
		$quoteChoice = $_POST['quot_choice'];
		$price = $_POST['quot_price'];
		$invoice = $_POST['quot_invoice'];
		$passcode = $_POST['mobile_passcode'];
		$color = $_POST['mobile_color'];
		$pickup = $_POST['pickup_detail']; 
		$warrTerm = $_POST['warr_term'];
		$extWarr = $_POST['ext_warr'];
		$warrPrice = $_POST['warr_price'];
		
		//add into DB
		$errorFlg = true;
		$errorFlg = $repairJob->addJob($newJobID, $shopCode, $staffName, $dateStart, $timeStart, $brand, $model, $imei, $partsSelection, $repairType, $issueDetail, $cname, $cemail, $cphone, $cmobile, $price, $invoice,$passcode,$color,$pickup,$warrTerm,$extWarr,$warrPrice);
		
		//INITIAL STATUS 
		
		$statusRecord = new Model_DbTable_Repairstatusrecord();
        
        $idQuoteChoice = 3;
        
        if($quoteChoice == "instant"){
        	
        	$idQuoteChoice =1;
        }
        
        $statusRecord->insertStatus($newJobID,$idQuoteChoice,$dateToday.$timeNow,$staffName);
        
		//pass to the view 

		$this->view->newjobid = $newJobID;
		$this->view->errorflag = $errorFlg;
		$this->view->shopcode = $shopCode;
		$this->view->staffname = $staffName;
		$this->view->datetime = $dateToday.$timeNow;
		$this->view->brand = $brand;
		$this->view->model = $model;
		$this->view->imei = $imei;
		$this->view->partsselection = $partsSelection;
		$this->view->repairtype = $repairType;
		$this->view->issuedetail = $issueDetail;
		$this->view->cname = $cname;
		$this->view->cemail = $cemail;
		$this->view->cphone = $cphone;
		$this->view->cmobile = $cmobile;
		$this->view->quotechoice = $quoteChoice;
		$this->view->price = $price;
		$this->view->invoice = $invoice;
		$this->view->passcode = $passcode;
		$this->view->color = $color;
		$this->view->pickupdetail = $pickup;
		$this->view->warrTerm = $warrTerm;
		$this->view->extWarr = $extWarr;
		$this->view->warrPrice = $warrPrice;
				
	}
	
	public function recordsAction(){
		
		
	}
	
	public function saverecordsAction(){
		
		
	}
		
	public function showstatusAction(){
		
		$arrBuddyShop = array(
			"BSPC" => array("BSIC","BSXP"),	
			"BSIC" => array("BSPC","BSXP"),
			"BSXP" => array("BSPC","BSIC"),
			"CLPC" => array("CLIC"),
			"CLIC" => array("CLPC"),
			"PMPC" => array("PMIC"),
			"PMIC" => array("PMPC"),	
			"WBPC" => array("WBIC"),
			"WBIC" => array("WBPC"),
			"WGPC" => array("WGIC"),
			"WGIC" => array("WGPC"),
			"EPPC" => array("EPIC"),
			"EPIC" => array("EPPC")				
				);
		
		$shopHead = $this->_getParam('shop');
		$show = "";
		if($shopHead == "BSPC" || $shopHead == "BSIC" || $shopHead == "BSXP" || $shopHead == "CLPC" || $shopHead == "CLPC" || $shopHead == "PMPC"
		 ||	$shopHead == "PMIC" || $shopHead == "WBPC" || $shopHead == "WBIC" || $shopHead == "WGPC" || $shopHead == "WGIC" || $shopHead == "EPPC" || $shopHead == "EPIC" )
		{
			$show = "buddy";
		}
		$repairJob = new Model_DbTable_Repairjob();
		
		$searchResult = $repairJob->listUnfinishedJobByShop($shopHead);
		$openJobs = $repairJob->currentOpenJobList($searchResult);
		if($show == "buddy"){
			$searchResult = $repairJob->listUnfinishedJobByShopMultiShopView($shopHead, $arrBuddyShop[$shopHead]);
			$openJobs = $repairJob->currentOpenJobListMultiShop($searchResult);
		}
		$this->view->shophead = $shopHead;
		$this->view->searchresult = $searchResult;
		$this->view->rCenterMapping  = $this->rCenterMapping;
		$this->view->openJobs = $openJobs;
				
		if($show == "buddy"){
			$this->renderScript("repaircenter/showstatus_buddy.phtml");
		}
		
	}
	public function showstatusbyjobidAction(){
	
		$shopHead = $_POST['shop_head'];
		$idJob = $_POST['id_job'];
		$repairJob = new Model_DbTable_Repairjob();
		$searchResult = "";
	
		if(!is_numeric($idJob)){
			$searchResult = $repairJob->getJobIdByIMEI($idJob);
		}
		else{
			$searchResult = $repairJob->listAllJobById($idJob);
			if(!$searchResult){
				
				$searchResult = $repairJob->getJobIdByIMEI($idJob);
			}
		}
		
		
		$this->view->shophead = $shopHead;
		//var_dump($searchResult);
		$this->view->searchresult = $searchResult;
		$this->view->rCenterMapping  = $this->rCenterMapping;
	}	
	public function showallstatusAction(){
		
		$repairJob = new Model_DbTable_Repairjob();
		
		$searchResult = $repairJob->listAllUnfinishedJobs();
		//var_dump($searchResult);
		$this->view->searchresult = $searchResult;		
		
	}
	public function showallstatusbycodeAction(){
		
		$statuscode = $this->_getParam('code');
		$repairJob = new Model_DbTable_Repairjob();
		$searchResult = $repairJob->listAllUnfinishedJobs();
		//var_dump($searchResult);
		$this->view->searchresult = $searchResult;		
		$this->view->statuscode = $statuscode;
	}
	public function showallhistoryAction(){
	
		$shopHead = $this->_getParam("shop");
		
		$repairJob = new Model_DbTable_Repairjob();
		
		$searchResult =($shopHead=="ALL")?$repairJob->listAllfinishedJobs():$repairJob->listAllfinishedJobsByShop($shopHead);
		//var_dump($searchResult);
		$this->view->searchresult = $searchResult;
	
	}
		
	public function viewdetailAction(){
		
		$jobid = $this->_getParam('jobid');
		$repairJob = new Model_DbTable_Repairjob();
		$this->view->jobdetail = $repairJob->getJob($jobid);

		
	}
	public function viewdetailstatusAction(){
		
		$jobid = $this->_getParam('jobid');
		
		if(isset($_GET['jid'])){
			$jobid = $_GET['jid'];
		}
		$repairJob = new Model_DbTable_Repairjob();
		$this->view->jobdetail = $repairJob->getJob($jobid);
		
		$statusRecords = new Model_DbTable_Repairstatusrecord();
		$recordsList = $statusRecords->getStatusList($jobid);
		
		$this->view->list = $recordsList;
		
		$commentRecords = new Model_DbTable_Jobcomments();
		$commentList = $commentRecords->listCommentByJobID($jobid);		
		$this->view->commlist = $commentList;
		
	}
	public function viewhistoryAction(){
		$idJob = $this->_getParam('jobid');
		$statusRecords = new Model_DbTable_Repairstatusrecord();
		$recordsList = $statusRecords->getStatusList($idJob);
		
		$this->view->list = $recordsList;
		
	}
	public function changestatusAction(){
		
		$idJob = $this->_getParam('jobid');
		
		$this->view->resultmessage ="";
		date_default_timezone_set('Australia/Melbourne');
		$dateTime = date("Y-m-d H:i");
		
		$statusRecord = new Model_DbTable_Repairstatusrecord();
		
		$this->view->currentstatus = $statusRecord->getLastStatus($idJob);
			
		if($_POST){
			
			if($_POST['staff_name']==""){
				
				$this->view->resultmessage = "<span style=\"color:#FF0000;\">You Must input your Name, Record did not Save</span>";
			}
			else{
				//$statusRecord->insertStatus($idJob, $idStatus, $timeRecord, $staffRecord)
				$statusRecord->insertStatus($_POST['job_id'],$_POST['id_status'],$dateTime,$_POST['staff_name']);
				$this->view->resultmessage = "<span  style=\"color:#009900;\">Status Successfully Saved! You can Not Close this Page</span>";
			}
			
		}
					
	}
	public function changestatusshopAction(){
	
		$idJob = $this->_getParam('jobid');
	
		$this->view->resultmessage ="";
		date_default_timezone_set('Australia/Melbourne');
		$dateTime = date("Y-m-d H:i");
	
		$statusRecord = new Model_DbTable_Repairstatusrecord();
		$rJob = new Model_DbTable_Repairjob();
		
		$repairStaff = $rJob->getRepairStaff($idJob);
		
		if($repairStaff==0 || $repairStaff==null || $repairStaff==""){
			
			$this->view->repairStaffRecord = true;
		}
		else{
			$this->view->repairStaffRecord = false;
		}
		
		$this->view->currentstatus = $statusRecord->getLastStatus($idJob);
			
		if($_POST){
				
			if($_POST['staff_name']==""){
	
				$this->view->resultmessage = "<span style=\"color:#FF0000;\">You Must input your Name, Record did not Save</span>";
			}
			else{
				//$statusRecord->insertStatus($idJob, $idStatus, $timeRecord, $staffRecord)
				$statusRecord->insertStatus($_POST['job_id'],$_POST['id_status'],$dateTime,$_POST['staff_name']);
				$this->view->resultmessage = "<span  style=\"color:#009900;\">Status Successfully Saved! You can Not Close this Page</span>";
			}
				
		}
			
	}	
	public function changequoteAction(){
		
		$idJob = $this->_getParam('jobid');
		$repairJob = new Model_DbTable_Repairjob();
		$this->view->currentprice = $repairJob->getQuotePrice($idJob);
		$secondQuote = 0;
		if($repairJob->ifFirstInvoiced($idJob)){$secondQuote = 1;}
		$this->view->idjob = $idJob;
		echo $secondQuote; 
		
		if($_POST){
						
			echo $secondQuote;
			$repairJob->updateQuotePrice($_POST['id_job'],$_POST['quote_price'],$secondQuote);
		}
		
	}
	public function updateinvoiceAction(){

		$idJob = $this->_getParam('jobid');
		$repairJob = new Model_DbTable_Repairjob();
		$this->view->currentinvoice = $repairJob->getInvoice($idJob);
		$this->view->idjob = $idJob;
		$this->view->ifQuoteChanged = 0;
		$this->view->errorMessage = "";
		$this->view->successMessage = "";
		
		$ifQuotChanged = $repairJob->ifQuoteChange($idJob);
		
		if($ifQuotChanged){$this->view->ifQuoteChanged = 1;}
		
		if($ifQuotChanged){
			if($_POST){
				if(strtoupper(substr(trim($_POST['invoice_no']),0,2))=="L1" || strtoupper(substr(trim($_POST['invoice_no2']),0,2))=="L1" ){
				
				$repairJob->updateInvoice($_POST['id_job'],$_POST['invoice_no']);
				$repairJob->updateInvoice2($_POST['id_job'],$_POST['invoice_no2']);
				$this->view->successMessage = "Invoice Added, You may now close the page";
				}
				else{
					$this->view->errorMessage = "The Invoice you input is not Full Invoice ID,Record will not Save, you need to do it again";
				}
			}			
		}
		else{
		if($_POST){
			
			if(strtoupper(substr(trim($_POST['invoice_no']),0,2))=="L1" ){
			
				$repairJob->updateInvoice($_POST['id_job'],$_POST['invoice_no']);
				$this->view->successMessage = "Invoice Added, You may now close the page";
			}
			else{
				$this->view->errorMessage = "The Invoice you input is not Full Invoice ID,Record will not Save, you need to do it again";
			}
			
		}
		}
	
		
		
		
	}
		
	public function addcommentAction(){
		
		$idJob = $this->_getParam('jobid');
		$this->view->jobID = $idJob;
		$commentJob = new Model_DbTable_Jobcomments();
		
		if($_POST){
			$commentJob->addComment($_POST['id_job_repair_comt'],$_POST['staffname_repair_comt'],$_POST['content_repair_comt']);
			echo "comment ADD";
			}
		
		}
	public function checkmissingmobileAction(){
		//get Today 
		//get severn days 
		//check status 
		date_default_timezone_set('Australia/Melbourne');
		
		$dateToday = date_create(date("Y-m-d"));
		$dateToday2 =  date_create(date("Y-m-d"));
		
		date_sub($dateToday, date_interval_create_from_date_string('7 days'));
		date_sub($dateToday2, date_interval_create_from_date_string('6 days'));
		
		$dateBegin = date_format($dateToday, 'Y-m-d');
		$dateFirst = date_format($dateToday2, 'Y-m-d'); 
		
		$repairJobs = new Model_DbTable_Repairjob();
		$repairStatus = new Model_DbTable_Repairstatusrecord();
		
		$jobList = $repairJobs->getJobLaterThan($dateBegin);
		
		$receiver = "office2@phonecollection.com.au";
		$subject = "OutStanding Job Alert For Wednesday";
		$body="";
		
		
		foreach($jobList as $k => $v){
			
			if(strtotime($v['date_start'])== strtotime($dateFirst)){
				 if(strtotime($v['date_start']." ".$v['time_start']) > strtotime($dateFirst." 17:00:00")){
					//
					 
					 $statusNow = $repairStatus->getLastStatus($v['id_job']);
					 //print_r($statusNow);
					 if($statusNow[0]['id_status'] < 9){
						  echo $v['id_job'].$v['date_start'].$v['time_start']."<br />";
						  //send emails						  
						  $body .= $v['id_job'].$v['date_start'].$v['time_start']."<br />";
						  
						 }
					 
					 }	
				 
				}
			elseif(strtotime($v['date_start'])> strtotime($dateFirst)){
				
									 $statusNow = $repairStatus->getLastStatus($v['id_job']);
					 
					 if($statusNow < 9){
						  echo $v['id_job'].$v['date_start'].$v['time_start']."<br />";
						  $body .= $v['id_job'].$v['date_start'].$v['time_start']."<br />";
						 }
					 
				}	
			
			}
		//body done 
		if(strlen($body)){
		
			$body = "This is a Quick Reminder That Some Out Standing Job Hasn't be DONE,Please Address it/them:<br />".$body;
		//send email
			$newMail = new Model_Emailshandler();
			$newMail->sendNormalEmail($receiver,$subject,$body);
		
		}
		
			
		}
	public function checkmissingmobilefridayAction(){
		
		date_default_timezone_set('Australia/Melbourne');
		
		$dateToday = date_create(date("Y-m-d"));
		$dateToday2 =  date_create(date("Y-m-d"));	
		
		date_sub($dateToday, date_interval_create_from_date_string('4 days'));
		date_sub($dateToday2, date_interval_create_from_date_string('3 days'));	
		
		$dateBegin = date_format($dateToday, 'Y-m-d');
		$dateFirst = date_format($dateToday2, 'Y-m-d'); 

		$repairJobs = new Model_DbTable_Repairjob();
		$repairStatus = new Model_DbTable_Repairstatusrecord();

		$jobList = $repairJobs->getJobLaterThan($dateBegin);

		$receiver = "office2@phonecollection.com.au";
		$subject = "OutStanding Job Alert For Friday";
		$body="";
		
				
		foreach($jobList as $k => $v){
			
			if(strtotime($v['date_start'])== strtotime($dateFirst)){
				 if(strtotime($v['date_start']." ".$v['time_start']) > strtotime($dateFirst." 9:00:00")){
					//
					 
					 $statusNow = $repairStatus->getLastStatus($v['id_job']);
					 //print_r($statusNow);
					 if($statusNow[0]['id_status'] < 9){
						 
						  echo $v['id_job'].$v['date_start'].$v['time_start']."<br />";
						  $body .= $v['id_job'].$v['date_start'].$v['time_start']."<br />";
						 }
					 
					 }	
				 
				}
			elseif(strtotime($v['date_start'])> strtotime($dateFirst)){
				
									 $statusNow = $repairStatus->getLastStatus($v['id_job']);
					 
					 if($statusNow < 9){
						 
						  echo $v['id_job'].$v['date_start'].$v['time_start']."<br />";
						  $body .= $v['id_job'].$v['date_start'].$v['time_start']."<br />";
						 }
					 
				}	
			
			}						
				if(strlen($body)){
		
			$body = "This is a Quick Reminder That Some Out Standing Job Hasn't be DONE,Please Address it/them:<br />".$body;
		//send email
			$newMail = new Model_Emailshandler();
			$newMail->sendNormalEmail($receiver,$subject,$body);
		
		}
		
		
		
		}	

		public function reOpenJobWarrantyAction(){
			$dateToday = Model_DatetimeHelper::dateToday();
			$timeNow = Model_DatetimeHelper::timeNow();
			
			$idJob = $this->_getParam("jobid");
			if($_POST){

				$staffRecord = $_POST["staff_name"];
				$comment = $_POST["comment"];
				
				$rJobSt = new Model_DbTable_Repairstatusrecord();
				$rJobSt->insertStatus($idJob,7,$dateToday." ".$timeNow, $staffRecord);
				
				echo "Done,Job Has Been Re-Opened For Warranty, You may close the window now";
			}
		}
		public function addRepairStaffAction(){
			
			$idJob = $this->_getParam("jobid");
			//$this->view->idJob =
			$sDetail = new Model_DbTable_Roster_Stafflogindetail();
			$rJob = new Model_DbTable_Repairjob();
			$jobRow = $rJob->getJob($idJob);
			$this->view->showForm = true;
			if($jobRow["repair_staff"] != 0 && $jobRow["repair_staff"] != null){
				$this->view->showForm = false;
			}
			
			if($_POST){
					
				$idStaffRow = $sDetail->checkPasswordCorrect(Model_EncryptHelper::hashsha(trim($_POST["password"])));
				
				if($idStaffRow){
					$rJob->updateRepairStaff($idJob, $idStaffRow["id"]);	
					echo "Record Uploaded You may close the window Now";
				}
				else{
					echo "Password Incorrect";
				}
				
			}
			
		}
		public function partsPriceAction(){
			$pv = "new"; // $this->_getParam("pv");
			
			date_default_timezone_set('Australia/Melbourne');
			
			
			$token = $this->_getParam("token");
			$this->view->error = true;
			
			if(base64_decode($token) == date("Ymdh")){
				$this->view->error = false;
			}
			
			
			
			if($pv == "new"){
				
				$this->renderScript('/repaircenter/parts-price-new.phtml');
			}
			
		}
		public function genStockTakeWeeklyAction(){
			// monday morning 
			// get last 7 days prot
			
			$arrUseList = array();
			$arrFaultyList = array();
			$arrRes = array();
			
			$shopCode = $this->_getParam("shop");
			
			$rStockMove = new Model_DbTable_Pr_Prstockmovement();
			$rProducts = new Model_DbTable_Pr_Prproducts();
			$rStock = new Model_DbTable_Pr_Prstock();
			
			$rSk = new Model_DbTable_Pr_Stocktake();
			
			$dateEnd  = Model_DatetimeHelper::dateYesterday();
			$dateBegin = Model_DatetimeHelper::adjustDays("sub", $dateEnd, 6);
			
			if($shopCode!=""){
			
			$rUseList = $rStockMove->getStockMoveSumByDateByCode(2, $dateBegin, $dateEnd, $shopCode);
			$rFautyList = $rStockMove->getStockMoveSumByDateByCode(8,$dateBegin,$dateEnd,$shopCode);
			
			foreach($rFautyList as $f){
				$arrFaultyList[]= $f["id_product"];
			}
			
			foreach($rUseList as $l){
				
				$key = false;
				$fQty = 0;
				$arrTmp = array();
				
				$arrTmp[] = $l["id_product"];
				$arrTmp[] = $l["qtys"];
				
				echo "ID:";
				echo $idProduct = $l["id_product"];
				echo "<br/>";
				if(in_array($idProduct,$arrFaultyList)){
					echo "Find";
					echo $key = array_search($idProduct,$arrFaultyList,true);	
					$arrFaultyList[$key] = " ";
					echo "<br/>";
				}
				if($key!== false){
					echo "Qty:";
				echo $fQty = $rFautyList[$key]["qtys"];
				echo "<br/>";
				}
				
				$arrTmp[] = $fQty;
				
				$arrRes[] = $arrTmp;
			}
			var_dump($arrFaultyList);
			foreach($arrFaultyList as $key => $v){
				
				if(trim($v)!==""){
					$arrTmp = array();
					$arrTmp[] = $rFautyList[$key]["id_product"];
					$arrTmp[] = 0;
					$arrTmp[] = $rFautyList[$key]["qtys"];
					
					$arrRes[] = $arrTmp;
				}
		
			}
			
			$dateTake = Model_DatetimeHelper::dateToday();
			
			foreach($arrRes as $row){
				$idProduct = $row[0];
				$pLine = $rProducts->getProduct($idProduct);
				$codeProduct = $pLine["code_product"];
				$skQty = $rStock->getShopStock($idProduct, $shopCode);
				
				$rSk->addStocktake($idProduct, $codeProduct,$shopCode, $dateTake,null,null,$row[1],$row[2],$skQty,null);
			}
			
			
			echo "<pre>";
			var_dump($arrRes);			
			echo "</pre>";
			
			echo "<pre>";
			//var_dump($rUseList,$rFautyList,$arrFaultyList);
			echo "</pre>";
			
			}
			
			
		}
		public function showStockTakeWeeklyAction(){
			
			$dateToCheck = Model_DatetimeHelper::dateToday();
			$idShop = $this->_getParam("shop");
			
			$skTake = new Model_DbTable_Pr_Stocktake();
			$sList = $skTake->listByShopByDate($dateToCheck, $idShop);

			$this->view->sList = $sList;
			
			if($_POST){

				$staffName = $_POST["staff_name"];
				$time = Model_DatetimeHelper::timeNow();
				foreach($_POST["qty_act"] as $k => $v){
					
					$skTake->updateStocktakeRes($k, $staffName, $v);
				}
				
			}
			
		}
		
		public function jobSummaryAction(){
			

			$dateBegin = $this->_getParam("date_begin");
			$dateEnd = $this->_getParam("date_end");
			

			
		}
		
		public function onlinePartsPriceAction(){
			
			
			$shop = $this->_getParam("shop");
			
			$idCate = $this->_getParam('cate');
			
			$rProducts = new Model_DbTable_Pr_Prproducts();
			
			$this->view->idCate = $idCate;
			
			$this->view->pList = $rProducts->listProductsByCate($idCate);
							
		}
		public function preStockTakeAction(){
			
			$dateOrder = Model_DatetimeHelper::getThisWeekMonday();
			
			$date2WeekBegin = Model_DatetimeHelper::adjustDays("sub",Model_DatetimeHelper::getLastWeekMonday(),7);
			$dateBegin = Model_DatetimeHelper::getLastWeekMonday();
			$dateEnd = Model_DatetimeHelper::getLastWeekSunday();
				
			$arrExtOrder = array();
				
			$shop = $this->_getParam("shop");

			$rOrder = new Model_DbTable_Pr_Order();
			$rProduct = new Model_DbTable_Pr_Prproducts();
			$rMove = new Model_DbTable_Pr_Prstockmovement();
			$rStock = new Model_DbTable_Pr_Prstock();
			$rJob = new Model_DbTable_Repairjob();
			$rsRecord = new Model_DbTable_Repairstatusrecord();
			$stInfo = new Model_DbTable_Roster_Stafflogindetail();
			
			
$arrShopList = self::$rCenterMappingReserve[$shop];
			
			$resultPass = true;
			$errorMessage = "";
			
			foreach($arrShopList as $shopHead){
				//echo $shopHead;
				
				$jList = $rJob->listUnfinishedJobByShop($shopHead);
				
				foreach($jList as $k => $rJobRow){
					
					//echo $rJobRow['id_job'];
					$recordRow = $rsRecord->getLastStatus($rJobRow['id_job']);
					
					
					if((int)$recordRow[0]['id_status'] == 10){
					
						$resultPass = true;
					}
					else{
						
						if((int)$recordRow[0]['id_status'] < 80){
							
							$partsLine = $rMove->getPartsRecordByJobId($rJobRow['id_job']);
							if(empty($partsLine)) {
							
							$resultPass = false;
							//echo "ERROR 1";
							$errorMessage .="Parts Record Missing ({$rJobRow['id_job']}) <br />";
							}
							
						}
					}
					
					// get parts 
					
	
				}
				
			}
			
			$rBorrow = new Model_DbTable_Pr_Loan();
			$borrowList = $rBorrow->unconfirmedExistList($shop);
			if($borrowList){
				
				$resultPass = false;
				//echo "ERROR 2";
				foreach($borrowList as $kb => $vb){
				
					$errorMessage .="Borrorw Not Confirmed {$vb['id_job']} - [<a href='http://115.64.171.97:1080/repairparts/confirm-borrow/bcode/{$vb['b_code']}' target = '_blank' >{$vb['b_code']}</a>] <br />";
				}
			}
			
			$this->view->resultPass = $resultPass;
			$this->view->errorMessage = $errorMessage;
			

			
			
			if($_POST){
				
				$stLine = $stInfo->checkPasswordCorrect(Model_EncryptHelper::hashsha($_POST['pwd']));
				
				if(!$stLine){
					
					echo '<script language="javascript"> window.alert("Incorrect Password, Please try your password again")</script>';
				}
				else{
					if(isset($_POST['btn_not_ok'])){
						$this->view->notPass = true;
						$stInfo->noScanParts($stLine['id']);
						
						$mail = new Model_Emailshandler();
						$mail->sendNormalEmail("eco1@phonecollection.com.au", "I am not familiar", $stLine['id']);
					}
					
					if(isset($_POST['btn_begin'])){
						
						if($stLine['scan_parts']==2){
							$this->view->notPass = true;
						}else{
							$this->view->notPass = false;
							if(isset($_POST['regern_stock'])){
							
							$this->_redirect("/repairparts/last-week-parts-sales/shop/".$shop);
							}
							else{
								$this->_redirect("/repaircenter/online-order/shop/".$shop);
							}
						}	
					}
						
				}
				
			}
			
			
		}
		
		public function onlineOrderAction(){
		
			$dateOrder = Model_DatetimeHelper::getThisWeekMonday();
			
			// WGshops 
			
			$date2WeekBegin = Model_DatetimeHelper::adjustDays("sub",Model_DatetimeHelper::getLastWeekMonday(),7);
			$dateBegin = Model_DatetimeHelper::getLastWeekMonday();
			$dateEnd = Model_DatetimeHelper::getLastWeekSunday();
			
			$arrExtOrder = array();
			
			$shop = $this->_getParam("shop");
		
			$rOrder = new Model_DbTable_Pr_Order();
			$rProduct = new Model_DbTable_Pr_Prproducts();
			$rMove = new Model_DbTable_Pr_Prstockmovement();
			$rStock = new Model_DbTable_Pr_Prstock();
			$rJob = new Model_DbTable_Repairjob();
			$rsRecord = new Model_DbTable_Repairstatusrecord();
			$stInfo = new Model_DbTable_Roster_Stafflogindetail();
			
			$arrShopList = self::$rCenterMappingReserve[$shop];
			
			$resultPass = true;
			$errorMessage = "";
			
			foreach($arrShopList as $shopHead){
				//echo $shopHead;
				
				$jList = $rJob->listUnfinishedJobByShop($shopHead);
				
				foreach($jList as $k => $rJobRow){
					
					//echo $rJobRow['id_job'];
					$recordRow = $rsRecord->getLastStatus($rJobRow['id_job']);
					
					
					if((int)$recordRow[0]['id_status'] == 10){
					
						$resultPass = true;
					}
					else{
						
						if((int)$recordRow[0]['id_status'] < 80){
							
							$partsLine = $rMove->getPartsRecordByJobId($rJobRow['id_job']);
							if(empty($partsLine)) {
							
							$resultPass = false;
							//echo "ERROR 1";
							$errorMessage .="Parts Record Missing ({$rJobRow['id_job']}) |";
							}
							
						}
					}
					
					// get parts 
					
	
				}
				
			}
			

			$rBorrow = new Model_DbTable_Pr_Loan();
			$borrowList = $rBorrow->unconfirmedExistList($shop);
			if($borrowList){
				
				$resultPass = false;
				//echo "ERROR 2";
				foreach($borrowList as $kb => $vb){
				
					$errorMessage .="Borrorw Not Confirmed {$vb['id_job']} - [<a href='http://115.64.171.97:1080/repairparts/confirm-borrow/bcode/{$vb['b_code']}' target = '_blank' >{$vb['b_code']}</a>] |";
				}
			}
			
			if(trim($errorMessage)!=""){
			
				$this->_redirect("/repaircenter/pre-stock-take/shop/".$shop);
			}
			
			$this->view->resultPass = $resultPass;
			$this->view->errorMessage = $errorMessage;
			
			$arrPart = array();
			
			if($_POST){
				
				d($_POST['hidden_part']);
				
				if($_POST['part_code']!=""){
					$this->view->hiddenpart = $_POST['part_code'];
				}
				if(isset($_POST['str_part'])){
				$arrPart = unserialize(gzuncompress(base64_decode($_POST['str_part'])));
				}
				// stock take page
				//submit
				
				if(isset($_POST['btn_submit'])){
					foreach($_POST['qty_onhand_cf'] as $idOrder => $qtyCf){
						$partLine = $rOrder->getOrder($idOrder) ;
						$partCodeLine = $partLine['code_product'];
					
						if(isset($arrPart[$partCodeLine])){
							
							$arrPart[$partCodeLine] = $qtyCf;
						}
					
					}					
					if(isset($arrPart[$_POST['part_code']])){
					$arrPart[$_POST['part_code']] += 1;
					}
					else{
						$arrPart[$_POST['part_code']] = 1;
					}
						
				}
				//cancel
				if(isset($_POST['btn_cancel'])){
					
					foreach($_POST['qty_onhand_cf'] as $idOrder => $qtyCf){
						$partLine = $rOrder->getOrder($idOrder) ;
						$partCodeLine = $partLine['code_product'];
							
						if(isset($arrPart[$partCodeLine])){
								
							$arrPart[$partCodeLine] = $qtyCf;
						}
							
					}
					
				
					if($_POST['hidden_part']!=""){
						
						$arrPart[$_POST['hidden_part']] -= 1;
					}
					
					$this->view->hiddenpart = "";
					
				}
				//confirm
				if(isset($_POST['btn_confirm_stocktake'])){
					
					//updat Qty
					//check password 
					
					$stLine = $stInfo->checkPasswordCorrect(Model_EncryptHelper::hashsha($_POST['pwd']));
					if($stLine){
						
						foreach($_POST['qty_onhand_cf'] as $key => $v){
							$rOrder->updateStockTake($key, $v,$stLine['id'],$_POST['comment']);
						}			
					}else{
						
						echo '<script language = "javascript" >alert("Incorrect Password!! Type Password And Submit Again");</script>';
						$this->view->arrWrongpass = $_POST['qty_onhand_cf'];
					}					
					$str = file_get_contents("http://115.64.171.97:1080/repairparts/parts-stock-take-summary-shop/email/yes/shop/".$shop);
				}
				// input order page 
				
				if(isset($_POST['btn_save_st'])){
					$res = $stInfo->checkPasswordCorrect(Model_EncryptHelper::hashsha(trim($_POST['staff_name_stocktake'])));
					if($res!==false){
					
					foreach($_POST['qty_onhand_cf'] as $k => $v){

						if(is_numeric($v)){
						$rOrder->updateStockTake($k,$v, $res['id']);
						}
					}
					
					}
					else{
						echo "<h2> Incorrect Password </h2>";
					}
					
				}
				
				//Add barcode
				if(isset($_POST["arr_ext_order"])){
				$arrExtOrder = unserialize(base64_decode($_POST["arr_ext_order"]));
				}
				
				if(isset($_POST["btn_add"])){
					$idAddPro = $_POST["add_part_code"];
					if($idAddPro!=""){	
					$proRow = $rProduct->find($idAddPro);
					$codePro = $proRow[0]["code_product"];
					$desPro = $proRow[0]["title_product"];
					
					$rListGo = $rMove->getProductMoveSummaryByDateByCode(2,$idAddPro,$date2WeekBegin, $dateEnd,$shop);
					$rListFo = $rMove->getProductMoveSummaryByDateByCode(8,$idAddPro,$dateBegin, $dateEnd,$shop);
					$rListEi = $rMove->getProductMoveSummaryByDateByCode(7,$idAddPro,$dateBegin, $dateEnd,$shop);
					
					$qtyLastWeek = round($rListGo[0]['sum_qty']/2,0);
					$maxSold = $rMove->maxSold($idAddPro, $dateEnd,4,$shop);
					$qtyFaulty = $rListFo[0]['sum_qty'] - $rListEi[0]['sum_qty'];
					
					$qtyOnhand = $rStock->getShopStock($idAddPro, $shop);
					$qtyWh = $rStock->getWarehouseStock($idAddPro);
					
					$qtyOrder = $rOrder->orderQty($qtyLastWeek, $maxSold, $qtyFaulty, $qtyOnhand, $qtyWh);
					$arrExtOrder[$idAddPro] = array($idAddPro,$codePro,$desPro,$qtyLastWeek,$maxSold,$qtyFaulty,$qtyOnhand,$qtyWh,$qtyOrder,"");
					}
				}
				// save the list 
				if(isset($_POST["btn_save"]) || isset($_POST["btn_confirm"])){
					//var_dump($_POST["qty_order"]);
					//update the Order Qty
					foreach($_POST["qty_order"] as $ko => $vo){
					$rOrder->updateOrderQty($ko, $vo,$_POST["reason"][$ko]);	
					}
					//Insert New Row
					foreach($arrExtOrder as $ke => $ve){	
						//$rOrder->addOrder($partOrderNumber, $idShop, $dateOrder, $idProduct, $codeProduct, $qtyLastWeek, $qtyFoLastWeek, $qtyMaxWeek, $qtyOnhand, $qtyWh);
						if($_POST["qty_onhand_cf_ext"][$ke]!=""){
						$idRow = $rOrder->addOrder($_POST["part_order_number"], $shop, $dateOrder, $ve[0], $ve[1], $ve[3], $ve[5], $ve[4], $ve[6], $ve[7],$_POST["qty_ext_order"][$ke]);
						$rOrder->updateStockTake($idRow,$_POST["qty_onhand_cf_ext"][$ke], null);
						}
						else{
							
							echo '  <script>
  alert("Actual On-hand Qty For Your Extra Parts Order Is Missing, The Extra Order Can Not Be Add In!!");
  </script>';
						}
					}
					
					$arrExtOrder = array();
				}
				//send 
				if(isset($_POST["btn_confirm"])){
					
					$res = $stInfo->checkPasswordCorrect(Model_EncryptHelper::hashsha(trim($_POST['staff_name'])));
					
					if($res!==false){
					$rOrder->confirmOrder($_POST["part_order_number"]);
							
					$rOrder->updateStaffName($_POST["part_order_number"],$res['id']);		
					
					$mail = new Model_Emailshandler();
					$receiver = "jeffrey.zhang@phonecollection.com.au";
					$subject = "Parts Order:{$_POST["part_order_number"]} has been confirmed have a look";
					$mailbody = "Check it in the Control Panel Or copy the link here: http://192.168.1.124/repairparts/pickup-slip-order/pon/{$_POST["part_order_number"]}";
					$mail->sendNormalEmail($receiver, $subject, $mailbody);					
					}else{

						echo "<h2> Incorrect Password </h2>";
						
					}
				}
				//del item 
				if(isset($_POST["btn_del"])){
				
					foreach($_POST['id_remove'] as $k => $v){
						unset($arrExtOrder[$k]);
					}
				}				
				
			}
			
			$this->view->strPart = base64_encode(gzcompress(serialize($arrPart)));
			$this->view->arrParts = $arrPart;
			$this->view->arrExtOrder = base64_encode(serialize($arrExtOrder));
			$this->view->pList = $pList = $rProduct->listAllProducts();
			
			$oList = $rOrder->getOrderByShopByDate($shop, $dateOrder);
			$this->view->oList = $oList;
			$this->view->idShop = $shop;
			
			$onHandConfirm = $oList[0]['qty_onhand_cf'];
			$orderDone = $rOrder->stockTakeDone($shop, $dateOrder);
			
			
			$this->view->mustScanShop = true;
			
			if($shop == 9){
				
				$this->view->mustScanShop = false;
			}
			
			if(!$orderDone[0]){
				$this->renderScript( 'repaircenter/online-order-stocktake-scan.phtml' );	
			}
			else{
				
				$this->renderScript( 'repaircenter/online-order.phtml' );
			}
			//var_dump($oList);
		}
		public function orderConfirmAction(){
			
			$pon = $this->_getParam("pon");
			$this->view->orderNo = $pon;
			
			$rOrder = new Model_DbTable_Pr_Order();
			$rProduct = new Model_DbTable_Pr_Prproducts();
			$rMove = new Model_DbTable_Pr_Prstockmovement();
			$rStock = new Model_DbTable_Pr_Prstock();
			$rOrderAsKt = new Model_DbTable_Products_Stock_Transferadjuststatus();
			$kaktRecord = new Model_DbTable_Products_Stock_Kaktstatusrecord();
			
			
			
			$oList = $rOrder->getByPON($pon);
			
			$arrParts = array();
			if($_POST){
				$arrParts = unserialize(gzuncompress(base64_decode($_POST['str_part'])));
				
				//echo "<pre>";
				//var_dump($arrParts);
				//echo "</pre>";
				
				// if cancel 
				if(isset($_POST['btn_cancel'])){
					if($_POST['hidden_part'] !="K35BCKECOCE"){
					
					$arrParts[$_POST['hidden_part']] = $arrParts[$_POST['hidden_part']] -1;

					$this->view->hiddenpart = "K35BCKECOCE";
					}
				}
				//else
				else{
					$this->view->hiddenpart = $_POST['part_code'];
					
					if(isset($arrParts[$_POST['part_code']])){
						$arrParts[$_POST['part_code']] = $arrParts[$_POST['part_code']] + 1;
					}
					else{
						
						$arrParts[$_POST['part_code']] = 1;	
					}	
				}
				
				
				 	
			}
			$this->view->strPart = base64_encode(gzcompress(serialize($arrParts)));
			
			$this->view->arrParts = $arrParts;
			
			/*
			 * Do Not Update Stock Now 
			 * 
			if($_POST){
				foreach($oList as $key => $v){
					// change stock , add movement
					//warehouse stock -
					$rStock->descreaseStockWarehouse($v["id_product"], $v["qty_allocate"]);
					//shop stock +
					$rStock->increaseStockShop($v["id_product"], $v["qty_allocate"],$v["id_shop"]);
					//record movement
					$rMove->addMovement(3,$v["id_product"], $v["qty_allocate"],"repair Parts Order",$v['id_shop'],trim($pon));

					
				}
					
				$idKa = $rOrderAsKt->getStatusByNumber(trim($pon));
				$rOrderAsKt->activeTheTransfer($idKa["id"],Model_DatetimeHelper::dateToday(),'Phone Repair');
				$kaktRecord->addRecord($idKa["id"],99,"repair part");
				$rOrder->OrderSent(trim($pon));
			
				echo "You Confirmed The Order Sent In Correct Qty, You may Close the Window Now";	
				//active kakt			
			}
			*/
			
			
			$this->view->oList = $rOrder->getByPON($pon);
			
			
			$this->renderScript( 'repaircenter/order-confirm-scan.phtml' );
			
		}
		
		public function orderConfirmReportAction(){
			
			//var_dump($_POST);
			$stDetail = new Model_DbTable_Roster_Stafflogindetail();
			
			$knote = new Model_DbTable_Products_Stock_Transferadjuststatus();
			$krecord = new Model_DbTable_Products_Stock_Kaktstatusrecord();
			
			$row = $stDetail->checkPasswordCorrect(Model_EncryptHelper::hashsha($_POST['pwd']));
			if($row){
				//password check pass
				$idNote = $knote->getStatusByNumber(trim($_POST['order_no']));
				
				if(trim($_POST['difference'])==""){
					$statusCode = 2;
					
					//nothing wrong 
					$knote->updateStatus($idNote['id'], $statusCode);
					$krecord->addRecord($idNote['id'], $statusCode, $row['ni'],nl2br($_POST['comment']));
					echo "You record has been Updated in the System , Warehouse will Find the Error For you, you may close the window now";
				}
				else{
					$statusCode = 4;
					//met errors
					$knote->updateStatus($idNote['id'], $statusCode);
					$krecord->addRecord($idNote['id'], $statusCode,$row['ni'],nl2br($_POST['difference'].$_POST['comment']));
					
					echo "You record has been Updated in the System , Warehouse will Active the Order For you, you may close the window now";
				}
				
				
			}
			else{
				
				echo "Password Check Fail, Please go back and Submit again";
			}
		}
		
		public function qrpAction(){
			
			
		}
		
		public function saveBorrowAction(){
			
			$pBorrow = new Model_DbTable_Pr_Loan();
			$idProduct = $_POST["id_product"];
			$codeProduct = $_POST["code_product"];
			$qtyProduct = $_POST["qty_borrowed"];
			$idShopTo = $_POST["shop_to"];
			$idShopFrom = $_POST["shop_from"];
			$staffPass = $_POST["staff_pass"];
			$dateLoan = $_POST["date_borrowed"];
			
			$timeRecord = Model_DatetimeHelper::dateToday()." ".Model_DatetimeHelper::timeNow();
			
			$stDetail = new Model_DbTable_Roster_Stafflogindetail();
			$stLine = $stDetail->checkPasswordCorrect($staffPass);
			$idStaff = $stLine["id_staff"];   
			
			//generate a borrow code
			
			
			$pBorrow->addLoan($codeProduct, $idShopFrom, $idShopTo, $idProduct, $codeProduct, $dateLoan, $timeRecord, $idStaff);
		}
		

}
?>