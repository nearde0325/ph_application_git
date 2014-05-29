<?php


class CustomerController extends Zend_Controller_Action
{
	public function indexAction(){
		
	}
	public function inqueryAction(){
		
		$this->view->shopHead = $this->_getParam("shop");
		
	}
	public function saveInqueryAction(){
		
		$shopHead = $_POST["shop_head"];
		$dateToday = Model_DatetimeHelper::dateToday();
		$timeNow = Model_DatetimeHelper::timeNow();
		$inq = new Model_DbTable_Customer_Inq();
		$idInq = 0;
		
		if($_POST){

			$idInq = $inq->addInq($_POST["shop_head"],$_POST["content"],$dateToday,$timeNow,$_POST["mymenu"]);			
		}
		
		$this->view->idInq = $idInq;
		$this->view->shopHead = $shopHead;
		
		//$this->_redirect("/customer/inquery/shop/".trim($_POST["shop_head"]));
		
	}
	public function saveStaffAction(){
		
		$shopHead = $this->_getParam("shop");
		$this->view->shopHead = $shopHead;
		$this->view->errormessage = "";
		
		$idInq = 0;
		
		$inq = new Model_DbTable_Customer_Inq();
		$attn = new Model_DbTable_Roster_Stafflogindetail();
		
		if($_POST){
			
			$idInq = $_POST["inq_id"];
			$idStaff =$attn->checkPasswordCorrect(Model_EncryptHelper::hashsha($_POST["password"]));

			if($idStaff){
				$inq->updateStaffName($idInq, $idStaff["id"]);
				$this->_redirect("/customer/inquery/shop/".$shopHead);
			}
			else{
				$this->view->errormessage = "Password incorrect!!<br />";
			}
			
		}
		$this->view->idInq = $idInq;
		
	}
	public function sendingInqAction(){
		
		$email = $this->_getParam("email");
		
		$custInq = new Model_DbTable_Customer_Inq();
		$stDetail = new Model_DbTable_Roster_Stafflogindetail();
		$dateLastMonday = Model_DatetimeHelper::getLastWeekMonday();
		$dateToday = Model_DatetimeHelper::dateToday();
		
		$dateBegin = $this->_getParam("date");
		//$dateEnd = "";
		if($dateBegin!=""){
			
			$dateLastMonday = $dateBegin;
			$dateToday = Model_DatetimeHelper::adjustDays("add", $dateLastMonday,6);
		}
	
		$shopRanks = $custInq->shopRankDate($dateLastMonday, $dateToday);  
		$detail = $custInq->listInqByDate($dateLastMonday, $dateToday);  
		
		//var_dump($shopRanks,$detail);
		
		$sh = new Model_DbTable_Shoplocation();
		$sList = $sh->listHeadArray();
		
		
		
		$strRank = "<table width='300' border = '1' cellpandding='10' ><tr><td>Shop</td><td>Qty</td></tr>";
		
		foreach($shopRanks as $shop){
			$strRank .="<tr>";
			$strRank .="<td>".$shop["shop_head"]."</td><td>".$shop["count"]."</td>";
			$strRank .="<tr>";
			//echo "<pre>";
			//var_dump($sList);
			//echo "</pre>";	
			$key = array_search($shop["shop_head"],$sList);
			$sList[$key] = "";
		}
		
		foreach($sList as $shopNo){
			
			if($shopNo!=""){
			$strRank .="<tr>";
			$strRank .="<td bgcolor ='red'>".$shopNo."</td><td>0</td>";
			$strRank .="<tr>";			
			}
		}
		
		
		
		$strRank .="</table>";
		
		$this->view->shopRank = $strRank;
		
		$strDetail = "<table width='500' border = '1' cellpandding='10' ><tr><td>Shop</td><td>Inq</td><td>date</td><td>Staff</td></tr>";
		foreach($detail as $det){
			$strDetail .="<tr>";
			$strDetail  .="<td>".$det["shop_head"]."</td>";
			$strCustInq = $det["content_inq"];
			$strCustInq = str_replace("Brand+Model+Product Type","", $strCustInq);
			$strCustInq = str_replace("[Or Price Review]","", $strCustInq);
			$strCustInq = str_replace("Barcode + Prefer Price","", $strCustInq);
			
			$strDetail .="<td>".trim($strCustInq)."</td>";
			$strDetail .="<td>".$det["date_inq"]."</td>";
			$StFullName="N/A";
			$stNickName="N/A";
			if($det["id_staff"]!=""){
			
			//var_dump($det["id_staff"]);	
			$stName = $stDetail->getDetail((int)$det["id_staff"]);
			//$StName = $stDetail->getDetail($det["id_stff"]);
			$StFullName = ucwords(Model_EncryptHelper::getSslpassword($stName["n"]));
			$stNickName = ucwords($stName["ni"]);
			}
			$strDetail .="<td>".$stNickName."(".$StFullName.")"."</td>";
			$strDetail  .="<tr>";			
			
		} 
		$strDetail  .="</table>";	
		
		$this->view->shopDetail = $strDetail;
		
		if($email == "yes"){
			
			$mail = new Model_Emailshandler();
			$subject = "Customer Inquiry From:".$dateLastMonday." TO ".$dateToday;
			$mailbody = $strDetail.$strRank;			
			$mail->sendNormalEmail("admin@phonecollection.com.au", $subject, $mailbody);
			$mail->sendNormalEmail("office@phonecollection.com.au", $subject, $mailbody);
			$mail->sendNormalEmail("jeffrey.zhang@phonecollection.com.au", $subject, $mailbody);
			$mail->sendNormalEmail("phonecollection.au@gmail.com", $subject, $mailbody);
			
		}
	}
	
}

?>