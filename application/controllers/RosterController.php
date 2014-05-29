<?php 
/**
 * bug Fix registered Done on 030514

 */
class RosterController extends Zend_Controller_Action
{
	const COME_EARLY_NORMAL_LIMIT = 5;//5
	const COME_REALLY_EARLY_LIMIT = 30;//30
	const COME_LATE_NORMAL_LIMIT = 5;//5
	
	const LEAVE_EARLY_NORMAL_LIMIT = 5;//5
	const LEAVE_REALLY_LATE_LIMIT = 30;//30
	/**
	 *  event Code
	 */
		
    public function init(){
    /**
	 *
	 *
	 */    
	
	}

    public function indexAction(){
	
		//echo "shomething";	
 
	}
	/**
	 *  Input function to do the initial name and DOB input
	 */
	public function inputAction(){
			
		$sDetail = new Model_DbTable_Roster_Stafflogindetail();
		if($_POST){
		
		
		$firstName = $sDetail->formatName($_POST["first_name"]);
		$lastName = $sDetail->formatName($_POST["last_name"]); 
		
		$init = substr($lastName,0,1);
		$ascii = ord($init) - 96;
		
		$dob = $sDetail->formateDob($_POST["year"],$_POST["month"],$_POST["day"]);
		
		$fullName = $firstName." ".$lastName;
		
		$fullNameAfter = Model_EncryptHelper::sslPassword($fullName);
		
		$dobAfter = Model_EncryptHelper::sslPassword($dob);
		
		date_default_timezone_set('Australia/Melbourne');	
		$lastLogin = date("U"); 
		
		$ip = $_SERVER['REMOTE_ADDR'];
		
		if($sDetail->checkUnique($fullNameAfter, $dobAfter)){
			
			$sDetail->addDetail($fullNameAfter, $dobAfter,$ascii,null,$lastLogin,"token", $ip,0);
			
			$this->view->message = "Record Inserted";
		}
		else{
			
			echo '<script>
			alert("Record Already Exist!");
			</script>';
			$this->view->message = "Record NOT INSERT";
		}
		
		
		
		
		}
		
		$this->view->sList  = $sDetail->listStaff();
		$this->view->show = $this->_getParam("show");		
	}
	/**
	 * give the initial input function to manager , only see the last one input do not see the staff list
	 */
	
	public function inputManagerAction(){
			
		$sDetail = new Model_DbTable_Roster_Stafflogindetail();
		if($_POST){
	
	
			$firstName = $sDetail->formatName($_POST["first_name"]);
			$lastName = $sDetail->formatName($_POST["last_name"]);
	
			$init = substr($lastName,0,1);
			$ascii = ord($init) - 96;
	
			$dob = $sDetail->formateDob($_POST["year"],$_POST["month"],$_POST["day"]);
	
			$fullName = $firstName." ".$lastName;
	
			$fullNameAfter = Model_EncryptHelper::sslPassword($fullName);
	
			$dobAfter = Model_EncryptHelper::sslPassword($dob);
	
			date_default_timezone_set('Australia/Melbourne');
			$lastLogin = date("U");
	
			$ip = $_SERVER['REMOTE_ADDR'];
			$nickName = trim(strtolower($_POST["nick_name"]));
	
			if($sDetail->checkUnique($fullNameAfter, $dobAfter)){
					
				$sDetail->addDetail($fullNameAfter, $dobAfter,$ascii,null,$lastLogin,"token", $ip,0,$nickName);
					
				$this->view->message = "Record Inserted: ".$fullName." , ".$_POST["year"]." , ".$_POST["month"]." , ".$_POST["day"]." (YYYY ,MM, DD)";
			}
			else{
					
				echo '<script>
				alert("Record Already Exist!");
				</script>';
				$this->view->message = "Record NOT INSERT";
			}
	
	
	
	
		}
	
		$this->view->sList  = $sDetail->listStaff();
	
	}	
	/**
	 * this one can give to staff , they setup their own password
	 */
	public function setupAction(){
		
		//echo $_SERVER['HTTP_USER_AGENT'] . "<br />";
		$sDetail = new Model_DbTable_Roster_Stafflogindetail();
		$oldPwd = new Model_System_Oldpwd();
		
		$this->view->ifMobile = true;
		$this->view->showFirst = true;
		$this->view->showSecond = false;
		$this->view->userID = 0;
		$this->view->passwordChecked = false;
		$this->view->passwordAftercheck = "";
		$this->view->fullName = "";
		$errorFlag = false;
		
		$browser = get_browser(null, true);

		//var_dump($browser);
		
		if($browser['platform'] =="Win7" || $browser['platform'] =="WinXP" || $browser['platform'] =="unknown" ){
			$this->view->ifMobile = false;	
		if($_POST){	
			//var_dump($_POST);
		if(isset($_POST["btn_setup_password"]) && $_POST["btn_setup_password"]=="Setup My Password" ){
			
			$firstName = $sDetail->formatName($_POST["first_name"]);
			$lastName = $sDetail->formatName($_POST["last_name"]);
			
			$dob = $sDetail->formateDob($_POST["year"],$_POST["month"],$_POST["day"]);
			
			$fullName = $firstName." ".$lastName;
			$this->view->fullName = $fullName;
			
			$fullNameAfter = Model_EncryptHelper::sslPassword($fullName);
			
			$dobAfter = Model_EncryptHelper::sslPassword($dob);
			
			$result = $sDetail->checkNameandDob($fullNameAfter, $dobAfter);
			if($result){
				$this->view->showFirst = false;
				$this->view->showSecond = true;
				$this->view->userID = $result;
				
			}
			
			
		}
		if(isset($_POST["btn_check_password"]) && $_POST["btn_check_password"]=="Check My Password" ){
		
			$password = $_POST["password"];
			$confirm = $_POST["password_confirm"];
			$this->view->userID = $_POST["user_id"];
			$this->view->passwordAftercheck = $_POST["password"];
			
			if($password != $confirm){
				$errorFlag = true;
				$this->view->errorMessage = "Password did not match the confirmation!!";	
				
			}			
			
			if(strlen($password)<8){
				$errorFlag = true;
				$this->view->errorMessage .= "Password Length not long enough<br/>";
			}
			
			if(!preg_match('/[A-Z]/',$password)){
				$errorFlag = true;
				$this->view->errorMessage .= "Password much contain at least one UPPER case<br/>";
			}			
			if(!preg_match('/[a-z]/',$password)){
				$errorFlag = true;
				$this->view->errorMessage .= "Password much contain at least one lower case<br/>";
			}
			if(!preg_match('/[0-9]/',$password)){
				$errorFlag = true;
				$this->view->errorMessage .= "Password much contain at least one number<br/>";
			}
			
			if(!$sDetail->checkPdUnique(Model_EncryptHelper::hashsha($password))){
				$errorFlag = true;
				$this->view->errorMessage .= "Password strength not good enough,Please use stronger password and try again <br/>";					
			}	
			if($oldPwd->matchingPwd(Model_EncryptHelper::hashsha($password))){
				
				$errorFlag = true;
				$this->view->errorMessage .= "Password strength not good enough,Please DO-NOT use any of your previous password  <br/>";
			}
			
			//if password find match say sorry , if not redirect to doneS
			
			if($errorFlag){
				
				$this->view->errorFlag = true;
				$this->view->showFirst = false;
				$this->view->showSecond = true;
			}
			else{
				
				$this->view->errorFlag = false;
				$this->view->showFirst = false;
				$this->view->showSecond = true;
				$this->view->passwordChecked = true;
				//$this->view->							
			}
		
		}		
		if(isset($_POST["btn_record_password"]) && $_POST["btn_record_password"]=="Password Checked, Now Save It" ){
				
			
			$userId = $_POST["user_id"];
			//$passwords = $_POST["password_aftercheck"];
			$passwordAfters = Model_EncryptHelper::hashsha($_POST["password_aftercheck"]);
			$sDetail->updatePassword($userId, $passwordAfters);
			//if password find match say sorry , if not redirect to done	
			$this->_helper->redirector("setup-done");
					 
		}		
		}	
			
		}
		else{
			
			$this->view->ifMobiel = true;
			$this->view->errorFlag = true;
			$this->view->errorMessage = "You are using the Mobile Phone to login in Or using Latest Version of Firefox which are not supported";	
		}
		
	}
	/**
	 * Setup Done , show the tutorial Link
	 */
	public function setupDoneAction(){
		
	}
	/**
	 * embed in the company home App page
	 */
	public function loginAction(){
		
		$this->view->errorFlag = false;
		$shopHead = $this->_getParam('shop');
		$this->view->shopHead = $shopHead;
		
		$token =(isset($_GET["token"]))?$_GET["token"]:"";
		$mail = new Model_Emailshandler();
	
		
		$sDetail = new Model_DbTable_Roster_Stafflogindetail();
		$sL = new Model_DbTable_Shoplocation();
		$tokenCheck = $sL->getToken($shopHead);
		
			$ip = $_SERVER['REMOTE_ADDR'];
		$browser = get_browser(null, true);
		
		//var_dump($browser);
		
		if(!($browser['platform'] =="Win7" || $browser['platform'] =="WinXP" || $browser['platform'] =="unknown" )){
			
			$this->view->errorFlag = true;
			
			$this->view->errorMessage = "Attempt to login on Mobile Device, the record has been send to Administrator";
			
			$mail->sendNormalEmail("eco1@phonecollection.com.au","Attempt Login On Mobile Device",$ip.$shopHead);
			
		}
		
		if(trim($token)!=(trim($tokenCheck))){
			
			$this->view->errorFlag = true;
			
			$this->view->errorMessage = "Login failed, the record has been send to Administrator";
			
			$mail->sendNormalEmail("eco1@phonecollection.com.au","Token Fail On Login",$ip.$shopHead);
		}
		
		if($_POST){
			
			$stLine = $sDetail->checkPasswordCorrect(Model_EncryptHelper::hashsha(trim($_POST["password"])));
			
			if(!$stLine){
				
				$this->view->errorFlag2 = true;
				
				//$mail->sendNormalEmail("eco1@phonecollection.com.au","Pasword Check Fail During Login",$ip.$shopHead);

				$this->view->errorMessage = "Login unsuccess, The Password Check Failed, You May Try unlimited Times";
					
			}
			else{
				
				if(date("U",$stLine['expire_date']) < date("U",Model_DatetimeHelper::dateToday())){
					$this->view->errorFlag = true;
					
					$mail->sendNormalEmail("eco1@phonecollection.com.au","Expire Staff Try Login",$ip.$shopHead);
					
					$this->view->errorMessage = "Your Password Has Been Blocked,Please Contact Head Office Immediately";					
					
				}
				else{
					$std = Model_EncryptHelper::sslPassword(trim($stLine["id"]));
					
					$arrPar = array(
							"token" => urlencode($token),
							"std" =>urlencode($std)
					);
					
					$this->_helper->redirector("on-off-duty","roster",null,$arrPar);					
					
				}
				

			}
				
		}		
		
	}
	/**
	 * Change status after login 
	 */
	public function onOffDutyAction(){
		
		$sL = new Model_DbTable_Shoplocation();	
		$token = urldecode($this->_getParam("token"));
		$std = Model_EncryptHelper::getSslPassword(urldecode($this->_getParam("std")));
		$record = new Model_DbTable_Roster_Attnrecord();
		$tSheet = new Model_DbTable_Roster_Timesheet();
		
		$mail = new Model_Emailshandler();
		$ip = $_SERVER['REMOTE_ADDR'];
		$browser = get_browser(null, true);
		$dateToday = Model_DatetimeHelper::dateToday();
		//$pv = $this->_getParam("pv");

		
		//var_dump($browser);
		
		if(!($browser['platform'] =="Win7" || $browser['platform'] =="WinXP")){
				
			$this->view->errorFlag = true;
				
			$this->view->errorMessage = "Attempt to login on Mobile Device, the record has been send to Administrator";
				
			$mail->sendNormalEmail("eco1@phonecollection.com.au","Attempt By Pass Login On Mobile",$ip."|".$token);
				
		}
		
		if($token!="" && $std!=""){
		$shopHead = $sL->getShopHeadByToken($token);
		$stDetail = new Model_DbTable_Roster_Stafflogindetail();	

			date_default_timezone_set('Australia/Melbourne');
		
		if($shopHead == "ADPC" || $shopHead == "WLIC" || $shopHead == "CLPC" ||$shopHead == "CLIC" ){

			date_default_timezone_set('Australia/Adelaide');
		}
		
		$dateBeginToday = date("U",strtotime(date("Y-m-d")));
		
	
		
		$row = $stDetail->getDetail($std);
		
		
		$result = $record->getLastStatus($std);

		$onOffDuty = date("D, d M Y H:i:s",$result["ti"]);

		$this->view->firstLoginToday = (bool)($result["ti"] < $dateBeginToday); 
		
		$this->view->onOffDuty = $onOffDuty;
		$this->view->stID = $std;
		$this->view->staffName = Model_EncryptHelper::getSslPassword($row["n"]);
		$this->view->shopHead = $shopHead;
		$birthday = Model_EncryptHelper::getSslPassword($row["d"]);
		$bCom = date("m-d",$birthday);
		$todayCom = date("m-d");
		//var_dump($bCom,$todayCom);
		$this->view->birthday = false;
		if($bCom == $todayCom){
			//echo "tre";
			$this->view->birthday = true;
		}  
		
		// if expire 
		$expireDate = $row['expire_date'];
		$dateToExp = (int)(date("U",strtotime($expireDate)) - date("U",strtotime($dateToday)))/86400;

			$this->view->aboutExpire = $dateToExp;
		
		if($_POST){
			
			$intShiftBegin = date("U" ,strtotime($dateToday." ".$_POST["shift_start_hour"].":".$_POST["shift_start_min"]));
			$intShiftEnd = date("U" ,strtotime($dateToday." ".$_POST["shift_end_hour"].":".$_POST["shift_end_min"]));
			
			$idSheet = $_POST["id_sheet"];
			$tSheet->updateTimesheet($idSheet,$std,$shopHead,$intShiftBegin, $intShiftEnd);
			
		
			$sLoc = new Model_DbTable_Shoplocation();
			$stDeail = new Model_DbTable_Roster_Stafflogindetail();
			$stLine = $stDeail->getDetail($std);
				
			$staffName = Model_EncryptHelper::getSslPassword($stLine["n"]);
			$NickName = $stLine["ni"];
				
				
			$shopMgrEmail = $sLoc->getStoreManMailByHead($shopHead);
			$shopAreaEmail = $sLoc->getAreaManMailByHead($shopHead);
			if($shopMgrEmail==""){
				$mail->sendShiftChangeEmail($shopAreaEmail, $staffName."(".$NickName.")",$_POST["shift_start_hour"].":".$_POST["shift_start_min"],$_POST["shift_end_hour"].":".$_POST["shift_end_min"]);
			}
			else{
				$mail->sendShiftChangeEmail($shopMgrEmail, $staffName."(".$NickName.")",$_POST["shift_start_hour"].":".$_POST["shift_start_min"],$_POST["shift_end_hour"].":".$_POST["shift_end_min"]);
			}
				
			echo '<script language="javascript">alert("Shift Change Email Sent To Your Manager");</script>';
		}
		
		$shiftToday = $tSheet->listShiftByStaffIdToday($std, $shopHead);
		$this->view->shiftToday = $shiftToday;
		
		}
		else{
			$this->view->errorFlag = true;
			
			$this->view->errorMessage = "Attempt to login fail, the record has been send to administrator";
			
			$mail->sendNormalEmail("eco1@phonecollection.com.au","Attempt By Pass Login ,Token Or Std Fail",$ip);			
			
			
		}
		
		//var_dump($row);
		
			$this->renderScript('/roster/on-off-duty-new.phtml');
		
	}
	/**
	 * Save the record and show the result to user
	 */
	public function onOffDutyRecordAction(){
		
		date_default_timezone_set('Australia/Melbourne');
		$mail = new Model_Emailshandler();
		$ip = $_SERVER['REMOTE_ADDR'];
		$browser = get_browser(null, true);
		$record = new Model_DbTable_Roster_Attnrecord();
		$eventRecord = new Model_DbTable_Roster_Earlylateevent();
		$timeSheet = new Model_DbTable_Roster_Timesheet();
		$eventTime = "";
		$dateToday = Model_DatetimeHelper::dateToday();
		$dateThisMonday = Model_DatetimeHelper::getThisWeekMonday();
		
		$needExplainOnDuty = true;
		$needExplainOffDuty = true;
		$needBreakOffDuty = true;
		
		//var_dump($browser);
		
		if(!($browser['platform'] =="Win7" || $browser['platform'] =="WinXP")){
		
			$this->view->errorFlag = true;
		
			$this->view->errorMessage = "Attempt to login on Mobile Device, the record has been send to Administrator";
		
			$mail->sendNormalEmail("eco1@phonecollection.com.au","Attn Login Fail",$ip);
		
		}
				
		if($_POST){

			$staffId = $_POST["std"];
			$this->view->staffId = $staffId; 
			
			$shopHead = Model_EncryptHelper::sslPassword(trim($_POST["shophead"]));
		if(trim($_POST["shophead"]) == "ADPC" || trim($_POST["shophead"]) == "WLIC" || trim($_POST["shophead"]) == "CLPC" || trim($_POST["shophead"]) == "CLIC" ){

			date_default_timezone_set('Australia/Adelaide');
			
		}
			$eventTime = date("U");
			
			$status = 1;
			$lastStatus = 1;
			

			
			
			if(isset($_POST["btn_on_duty"]) && $_POST["btn_on_duty"] =="Click Me On Duty" ){
				$status = $record->createStatusOnDuty();
				$record->addRecord($staffId, $eventTime, $ip, $shopHead, $status);
				$this->view->message = "Status Change Success,Click Link below return to Login Page Again";
				$this->view->eventTime = $eventTime;
				$lastStatus = 1;
				
			}// if on duty
			
			if(isset($_POST["btn_off_duty"]) && $_POST["btn_off_duty"] =="Click Me Off Duty" ){
				$status = $record->createStatusOffDuty();
				$record->addRecord($staffId, $eventTime, $ip, $shopHead, $status);
				$this->view->message = "Status Change Success,Click Link below return to Login Page Again";
				$this->view->eventTime = $eventTime;
				$lastStatus = 2;
				
				
					
			}

			if(isset($_POST["btn_save_shift"]) && $_POST["btn_save_shift"] =="Save" ){
								
				
				
				$shiftBeginStr = $dateToday." ".$_POST['shift_start_hour'].":".$_POST['shift_start_min'].":"."00";
				$shiftEndStr = $dateToday." ".$_POST['shift_end_hour'].":".$_POST['shift_end_min'].":"."00";
				
				$intShiftBegin = date("U",strtotime($shiftBeginStr));
				$intShiftEnd = date("U",strtotime($shiftEndStr));
				$lastStatus = $_POST["on_off_status"];
				
				// show the warning 
				if(($intShiftEnd - $intShiftBegin) > 18000){
					
					$this->view->showBreakMessage = true;
				}
				else{
					$this->view->showBreakMessage = false;
				}
				
				// change the show time for adelaide 
				
				if($_POST["shophead"]=="ADPC" || $_POST["shophead"]=="CLPC" || $_POST["shophead"]=="WLIC" || $_POST["shophead"]=="CLIC"){
					
					//$intShiftBegin += 1800;
					//$intShiftEnd += 1800;
				
				}
				//check exist 
				if(!$timeSheet->searchTimeSheet($_POST["std"],$_POST["shophead"], $intShiftBegin, $intShiftEnd)){
				
					$timeSheet->addTimesheet($_POST["std"],$_POST["shophead"], $intShiftBegin, $intShiftEnd);
				
				}
			}// if btn save 
			if(isset($_POST["btn_event_explain"]) && $_POST["btn_event_explain"] =="Save" ){
				// save the event 
				$idEvent = $eventRecord->addEarlylateevent($_POST["std"],$_POST["come_really_early_time"],START_REALLY_EARLY,"POSITIVE |".$_POST["staff_comment"]);
				$timeSheet->updateOnDutyEvent($_POST["id_timesheet"], $idEvent);
				
				$needExplainOnDuty = false;
				$lastStatus = 1;
			}// if save morning explain 
			
			if(isset($_POST["btn_break_time"]) && $_POST["btn_break_time"] =="Save" ){
				// save the event on condition 
				if(isset($_POST["ind_leave_early"])){

					$idEvent = $eventRecord->addEarlylateevent($_POST["std"], $_POST["time_leave_event"],FINISH_EARLY,"NEGATIVE |".$_POST["staff_comment"]);
					$timeSheet->updateOffDutyEvent($_POST["id_timesheet"], $idEvent);
				
				}
				if(isset($_POST["ind_leave_late"])){

					$idEvent = $eventRecord->addEarlylateevent($_POST["std"], $_POST["time_leave_event"],FINISH_REALLY_LATE,"POSITIVE |".$_POST["staff_comment"]);
					$timeSheet->updateOffDutyEvent($_POST["id_timesheet"], $idEvent);						
						
				}
				// add break time 
					$timeSheet->updateBreakTime($_POST["id_timesheet"],$_POST["break_time"]);
				
				$needExplainOffDuty = false;
				$needBreakOffDuty = false;
				$lastStatus = 2;
				
			}// if save morning explain			
			
			//var_dump($staffId, $dateToday,$_POST["shophead"]);
			
			$statusLine = $record->getFirstOnLastOffDuty($staffId, $dateToday,Model_EncryptHelper::sslPassword($_POST["shophead"]));
			$todayTimeSheet = $timeSheet->listShiftByStaffIdToday($staffId,$_POST["shophead"]);
			
			//var_dump($statusLine);
			//var_dump($todayTimeSheet);
			//var_dump($lastStatus);
			
			if($todayTimeSheet){
				$this->view->timeSheetID = $todayTimeSheet["id_sheet"];
				if($lastStatus == 1){
						$this->view->needExplainOff = false;
						$this->view->needBreakOff = false;
				// on duty ,compare with the first On duty today, this time may not be the first time  	
					$intShiftBegin = $todayTimeSheet["shift_begin"];
					$intFistOn = $statusLine[0];
					//echo "Shift Begin";
					//echo ($intFistOn - $intShiftBegin);
					
					if((($intFistOn - $intShiftBegin) < - (self::COME_EARLY_NORMAL_LIMIT * 60)) && (($intFistOn - $intShiftBegin) > - (self::COME_REALLY_EARLY_LIMIT * 60))) {
						$this->view->needExplainOn = false;			
					//record
						$idEvent = $eventRecord->addEarlylateevent($staffId,$intFistOn,START_EARLY,"POSITIVE");
						$timeSheet->updateOnDutyEvent($todayTimeSheet["id_sheet"], $idEvent);						
					}
					if(($intFistOn - $intShiftBegin) <= - (self::COME_REALLY_EARLY_LIMIT * 60)){
						// need Explain
						//echo "NEED EXP ON".$needExplainOnDuty;
						$this->view->needExplainOn = $needExplainOnDuty;
						$this->view->comeReallyEarlyTime = $intFistOn;
						$this->view->timeSheetID = $todayTimeSheet["id_sheet"];
							
					}
					if(($intFistOn - $intShiftBegin) > (self::COME_LATE_NORMAL_LIMIT * 60)){
							$countLateToday = $eventRecord->getLateEvent($staffId, $dateToday, $dateToday); 
						if(!$countLateToday){
							$idEvent = $eventRecord->addEarlylateevent($staffId,$intFistOn,START_LATE,"NEGATIVE");
							$timeSheet->updateOnDutyEvent($todayTimeSheet["id_sheet"],$idEvent);
						// attn to manager
						}
						$this->view->needExplainOn = false;
						
						$countLate = $eventRecord->getLateEvent($staffId, $dateThisMonday, $dateToday);
						
						if($countLate>2){
							$mail = new Model_Emailshandler();
							$sLoc = new Model_DbTable_Shoplocation();
							$stDeail = new Model_DbTable_Roster_Stafflogindetail();
							$stLine = $stDeail->getDetail($staffId);
							
							$staffName = Model_EncryptHelper::getSslPassword($stLine["n"]);
							$NickName = $stLine["ni"];
							
							
							$shopMgrEmail = $sLoc->getStoreManMailByHead(trim($_POST["shophead"]));
							$shopAreaEmail = $sLoc->getAreaManMailByHead(trim($_POST["shophead"]));
							if($shopMgrEmail==""){
								$mail->sendLateEmail($shopAreaEmail, $staffName."(".$NickName.")");
							}
							else{
								$mail->sendLateEmail($shopMgrEmail, $staffName."(".$NickName.")");
							}	
							
							//echo "Mail Sent";
							//$mail->sendLateEmail($managerEmail, $staffName);
						}
						
						$this->view->notifyManager = true; 
						$this->view->lateTime = $countLate;		
							
			
						}
					}
					
					
				// early , really early 

				// late really late 
				if($lastStatus == 2){
					$this->view->needExplainOn = false;
				//Last off duty time , which is just happened 
					$intShiftEnd = $todayTimeSheet["shift_end"]; 	
					$intLastOff = $statusLine[1];
					$this->view->timeLeaveEvent = $intLastOff;
					
					if(($intLastOff -$intShiftEnd) <-(self::LEAVE_EARLY_NORMAL_LIMIT * 60) ){
						
						// need explain 
						$this->view->timeSheetID = $todayTimeSheet["id_sheet"];
						$this->view->needExplainOff = $needExplainOffDuty;
						$this->view->leaveEarlyNormal = true;
						$this->view->leaveReallyLate = false;
					}
					
					if(($intLastOff -$intShiftEnd) > (self::LEAVE_REALLY_LATE_LIMIT * 60)){
						
						//need explain
						$this->view->timeSheetID = $todayTimeSheet["id_sheet"];
						$this->view->needExplainOff = $needExplainOffDuty;
						$this->view->leaveEarlyNormal = false;
						$this->view->leaveReallyLate = true;						
					}
				
				//here Ask if the break time 
				
					$this->view->needBreakOff = $needBreakOffDuty;

				}

			}// time sheet 
				
		
			
			//Does Not matter which button clicked , need to calculate 
			
			//check the recent status 
			//check today time sheet Ready 
			
			//who is triging on? post status is ON , or transfered by save status   
			
			//off is triging off post status is Off , or trnasfered by Save Status
			
			
			
			$this->view->message = "Status Change Success,Click Link below return to Login Page Again";
			$shiftRecord = $timeSheet->listShiftByStaffIdToday($_POST["std"],$_POST["shophead"]);			
			$this->view->shiftRecord = $shiftRecord;
			
			
			//echo "ONOFFBREAK";
			//var_dump($shiftRecord,$needExplainOnDuty,$needExplainOffDuty,$needBreakOffDuty);
			
			
		}//If Post 
		else{

			$this->view->errorFlag = true;
			$this->view->errorMessage = "Access this page Incorrectly";
			
		}
	}
	
	/**
	 * =================================================================================
	 *  This part is for Roster Function , not for attendance function 
	 * ================================================================================= 
	 */
	
	
	
	/**
	 * Manager Upload Roster
	 * @todo show the manage what last been uploaded
	 */
	
	public function uploadRosterAction(){
		
		$browser = get_browser(null, true);	
		$shopHead = $this->_getParam('shop');
			
		$this->view->shopHead = $shopHead;
		$this->view->ip = $_SERVER['REMOTE_ADDR'];
		$this->view->os = $browser['platform'];
		
		$rosterLog = new Model_DbTable_Roster_Uploadlog();
		$row = $rosterLog->lastLogByShop($shopHead);
		$this->view->lastLog = $row;			
	}
	
	/**
	 * Save the upload 
	 */
	public function saveUploadAction(){
		
		$this->view->errorMessage = "";
		$this->view->goodNews = "";
		$rosterLog = new Model_DbTable_Roster_Uploadlog();
		$realshopHead = $_POST["shop_head"];
		
		$managerName = $_POST["manager_name"];
		$ip = $_POST["ip"];
		$os = $_POST["os"];
		
		$orgFileName = $_FILES["upload_file"]["name"];
		
		$arrShop = explode("_",$orgFileName);
		$shopHead =strtoupper($arrShop[0]);
		$this->view->shopHead = $shopHead;
		$this->view->realshopHead = $realshopHead;
		$savedFileName = $rosterLog->createLogFileName($orgFileName);
		$date = Model_DatetimeHelper::dateToday();
		$time = Model_DatetimeHelper::timeNow();	
		$filePath = getcwd()."/roster/".$shopHead."/";
		$arrFile = explode(".",$orgFileName);
		if($arrFile[1]!=="xls" && $arrFile[1]!=="xlsx"){
			$this->view->errorMessage = "You may uploaded the wrong type of file or there are period mark (.) in you file name,please removie it and try to upload it again";
		}
		else{		
		if(move_uploaded_file($_FILES["upload_file"]["tmp_name"],$filePath.$savedFileName)){
			$rosterLog->addLog($shopHead, $managerName, $orgFileName, $savedFileName, $ip, $os, $date, $time);
			$this->view->goodNews = "File:".$orgFileName."has been successully uploaded on".$date." ".$time."By ".$managerName;
		}
		else{
			
			$this->view->errorMessage = "File Upploaded Error,Please contact Head Office";
		}		
			
		}		
			
	}
	/**
	 * This is for staff to check the upload result 
	 * @todo  Move this one to roster auditing 
	 * 
	 */
	
	public function listUploadAction(){
		
		$shopHead = $this->_getParam('shop');
		$rosterLog = new Model_DbTable_Roster_Uploadlog();
		$this->view->logList = $rosterLog->listLogByShop($shopHead);
		$this->view->shopHead = $shopHead;
		
		$shops = new Model_DbTable_Shoplocation();
		$this->view->shopList = $shops->listHead();	
		$arrManager = array("ADELE","CC","CHOW","EMILY","HOPE","LINA","SIMON","SOPHIA","STEVEN","TERRA","YIN");
		$this->view->managerList = $arrManager;
	}

	public function onlineRosterAction(){
		
		if(isset($_POST["btn_roster"])){
		
			$spList = $_POST["shop_selection"];
			$dateBegin = $_POST["date_begin"];
			$arrStr = base64_encode(serialize($spList));
			
			
			$token  = Model_DatetimeHelper::tranferToInt($dateBegin);
		
			if($dateBegin <= Model_DatetimeHelper::dateToday()){
				$this->_redirect("/roster/online-roster-confirm/shops/".$arrStr."/token/".$token."/db/".$dateBegin);
			}
			else{
				$this->_redirect("/roster/online-roster/shops/".$arrStr."/token/".$token."/db/".$dateBegin);
			}
		}
		
		$shops = $this->_getParam("shops");
		$dateBegin = $this->_getParam("db");
		$token = $this->_getParam("token");
		$val = $this->_getParam("su");
		
		
		$this->view->su = isset($val)?$val:"normal"; 
		
		$this->view->shops = $shops;
		$this->view->dateBegin = $dateBegin;	
		
		date_default_timezone_set('Australia/Melbourne');
		$dateCheck = date("Y-m-d",$token);
		
		$tSheet = new Model_DbTable_Roster_Timesheet();
		$stDetail = new Model_DbTable_Roster_Stafflogindetail();
		$this->view->stList = $stDetail->listStaffByNickName();
		$arrResult = array();

		if($dateCheck == $dateBegin){
		
			$shopList = unserialize(base64_decode($shops));
			$this->view->spList = $shopList;
			$dateEnd = Model_DatetimeHelper::adjustDays("add", $dateBegin, 6);
			$arrDate = Model_DatetimeHelper::createDateArray($dateBegin, $dateEnd);
			$this->view->arrDate = $arrDate;
			foreach($shopList as $shopKey => $shopHead){
		
				foreach($arrDate as $dateKey => $dateValue){
					//var_dump($shopHead,$dateValue);
					$arrResult[$shopKey][$dateKey] = $tSheet->listArrangedShiftByDateByShopWithFormat($shopHead, $dateValue, $dateValue);
			
				}
			}
		}//end of date begin	
	
		if($_POST){
			
			if(isset($_POST["arr_Detail"])){
			
				foreach($shopList as $shopKey => $shopHead){
			
					foreach($arrDate as $dateKey => $dateValue){
			
						//var_dump($shopHead,$dateValue);
						if(isset($_POST["arr_Detail"][$shopKey][$dateKey])){
							$arrResult[$shopKey][$dateKey] = $_POST["arr_Detail"][$shopKey][$dateKey];
						}
						else{
							$arrResult[$shopKey][$dateKey] = "";
						}
					}
			
				}	
			}//end of arr_detail
			
			// add button 
			//var_dump($arrResult);
			
			if(isset($_POST["btn_addto"])){
			
					
				$arrDailyTmp = array();
			
				for($i=1;$i<=3;$i++){
					$str = "staff_name_".$i;
					if($_POST[$str]!==""){
						$breakTime = 0;
						$totalHours = round(($_POST["shift_end_hour_".$i] - $_POST["shift_start_hour_".$i]) + ($_POST["shift_end_min_".$i] - $_POST["shift_start_min_".$i])/60,2);

						if($totalHours >=5){
							$breakTime = 30;
							$totalHours -= 0.5;
						}
					if($totalHours >=9){
							$breakTime = 60;
							$totalHours -= 1;
						}
						$arrDailyTmp[] = array($_POST[$str],$_POST["shift_start_hour_".$i],$_POST["shift_start_min_".$i],$_POST["shift_end_hour_".$i],$_POST["shift_end_min_".$i],"new".date("U").$i,$breakTime,$totalHours,0);
					}
				}
				//var_dump($arrDailyTmp);
				$counter = 0;	
				foreach($_POST["choice"] as $theShop => $dates ){
					
					foreach($dates as $theDate => $value){
						if(isset($arrDailyTmp[0][0])){
							$arrDailyTmp[0][5] .= $counter;
							$counter++;
						}
						if(isset($arrDailyTmp[1][0])){
							$arrDailyTmp[1][5] .= $counter;
							$counter++;
						}
						if(isset($arrDailyTmp[2][0])){
							$arrDailyTmp[2][5] .= $counter;
							$counter++;
						}
						if($value){
							//var_dump($arrResult[$theShop][$theDate]);
							if(empty($arrResult[$theShop][$theDate])){
								$arrResult[$theShop][$theDate] = $arrDailyTmp;
							}
							else{
																
								$arrDailyTmpRes = array();
								foreach($arrDailyTmp as $key => $tmp){

									$staffID = $tmp[0];
									$startHour = $tmp[1];
									$startmin = $tmp[2];
									
									$errFlag = false;
									
									foreach($arrResult[$theShop][$theDate] as $resKey => $v){
										if($v[0] == $staffID && ( ($v[3]*100 +$v[4]) > ($startHour*100 + $startmin) )){
											$errFlag = true;	
											echo "<script language='javascript'> alert('Duplicated Enty Entered! Will not Accept');</script>";
											//break;												
										}
									}
									if(!$errFlag){	
										$arrDailyTmpRes[] = $tmp;
									}			
								}
								
								$arrResult[$theShop][$theDate] = array_merge($arrResult[$theShop][$theDate],$arrDailyTmpRes);
								
							}
						}
			
					}
						
				}
			
			}
						
		}// End of post
		
		$this->view->arrResultLive = $arrResult;
	
	}
	public function onlineRosterLastWeekAction(){
		$shop = $this->_getParam("shop");
		
		$dateBegin = Model_DatetimeHelper::getLastWeekMonday();
		$dateEnd = Model_DatetimeHelper::getLastWeekSunday();
		
		$this->view->shops = $shop;
		$this->view->dateBegin = $dateBegin;
		$this->view->dateEnd = $dateEnd;
		
		$tSheet = new Model_DbTable_Roster_Timesheet();
		
		$arrResult = array();
		
		$arrDate = Model_DatetimeHelper::createDateArray($dateBegin, $dateEnd);
		$this->view->arrDate = $arrDate;
		foreach($arrDate as $dateKey => $dateValue){
		
			//var_dump($shopHead,$dateValue);
			$arrResult[0][$dateKey] = $tSheet->listShiftByDateByShopWithFormat($shop, $dateValue, $dateValue);
		
		}
		$this->view->arrResultLive = $arrResult;
		
	}
	public function onlineRosterThisWeekAction(){
		$shop = $this->_getParam("shop");
		
		$dateBegin = Model_DatetimeHelper::getThisWeekMonday();
		$dateEnd = Model_DatetimeHelper::getThisWeekSunday();

		$this->view->shops = $shop;
		$this->view->dateBegin = $dateBegin;
		$this->view->dateEnd = $dateEnd;
		
		$tSheet = new Model_DbTable_Roster_Timesheet();
		
		$arrResult = array();
		
		$arrDate = Model_DatetimeHelper::createDateArray($dateBegin, $dateEnd);
		$this->view->arrDate = $arrDate;
		foreach($arrDate as $dateKey => $dateValue){
		
			//var_dump($shopHead,$dateValue);
			$arrResult[0][$dateKey] = $tSheet->listShiftByDateByShopWithFormat($shop, $dateValue, $dateValue);
		
		}
		$this->view->arrResultLive = $arrResult;		
		
	}
	
	public function checkOnlineRosterAction(){
		
		$this->view->shop = $this->_getParam("shop");
	}
	
	public function onlineRosterNextWeekAction(){
		$shop = $this->_getParam("shop");
		
		$dateBegin = Model_DatetimeHelper::adjustDays("add",Model_DatetimeHelper::getThisWeekMonday(),7);
		$dateEnd = Model_DatetimeHelper::adjustDays("add",Model_DatetimeHelper::getThisWeekSunday(),7);
		
		$this->view->shops = $shop;
		$this->view->dateBegin = $dateBegin;
		$this->view->dateEnd = $dateEnd;
		
		$tSheet = new Model_DbTable_Roster_Timesheet();
		
		$arrResult = array();
		
		$arrDate = Model_DatetimeHelper::createDateArray($dateBegin, $dateEnd);
		$this->view->arrDate = $arrDate;
		foreach($arrDate as $dateKey => $dateValue){
		
			//var_dump($shopHead,$dateValue);
			$arrResult[0][$dateKey] = $tSheet->listShiftByDateByShopWithFormat($shop, $dateValue, $dateValue);
		
		}
		$this->view->arrResultLive = $arrResult;		
		
		
	}
	public function onlineRosterConfirmAction(){
		
		
		$shops = $this->_getParam("shops");
		$dateBegin = $this->_getParam("db");
		$token = $this->_getParam("token");
		
		$val = $this->_getParam("su");
		$this->view->su = isset($val)?$val:"normal";
		$this->view->trigger = "";
		
		
		
		$this->view->shops = $shops;
		$this->view->dateBegin = $dateBegin;
		
		
		date_default_timezone_set('Australia/Melbourne');
		$dateCheck = date("Y-m-d",$token);
		
		$tSheet = new Model_DbTable_Roster_Timesheet();
		$stDetail = new Model_DbTable_Roster_Stafflogindetail();
		$this->view->stList = $stDetail->listStaffByNickName();
		$arrResult = array();
				
		
		if($dateCheck == $dateBegin){
				
			$shopList = unserialize(base64_decode($shops));
			$this->view->spList = $shopList;
			$dateEnd = Model_DatetimeHelper::adjustDays("add", $dateBegin, 6);
			$arrDate = Model_DatetimeHelper::createDateArray($dateBegin, $dateEnd);
			$this->view->arrDate = $arrDate;
			foreach($shopList as $shopKey => $shopHead){

				foreach($arrDate as $dateKey => $dateValue){
				
					//var_dump($shopHead,$dateValue);
					$arrResult[$shopKey][$dateKey] = $tSheet->listShiftByDateByShopWithFormat($shopHead, $dateValue, $dateValue); 					
					//var_dump($arrResult[$shopKey][$dateKey]);
				}	
								
			}		
				
		}//end of dateBegin		
		//var_dump($arrResult);
		
		if($_POST){
						
			//$arrResult = isset($_POST["arr_Detail"])?$_POST["arr_Detail"]:$arrResult;
			d($_POST);
			if($_POST['trigger']!=""){
				
				$this->view->trigger = $_POST['trigger'];
			}

			if(isset($_POST["arr_Detail"])){
				
				foreach($shopList as $shopKey => $shopHead){
				
					foreach($arrDate as $dateKey => $dateValue){
				
						//var_dump($shopHead,$dateValue);
						if(isset($_POST["arr_Detail"][$shopKey][$dateKey])){
							$arrResult[$shopKey][$dateKey] = $_POST["arr_Detail"][$shopKey][$dateKey];
						}
						else{
							$arrResult[$shopKey][$dateKey] = "";
						}	
					}
				
				}				

				//$arrResult = array_merge($arrResult,$_POST["arr_Detail"]);
						
			}
			
			//var_dump($_POST["arr_Detail"],$arrResult);
			

			if(isset($_POST["btn_addto"])){

				//$arrResult = isset($_POST["arr_Detail"])?$_POST["arr_Detail"]:$arrResult;
			
				$arrDailyTmp = array();
				
						$i = 1;
						$totalHours = round(($_POST["shift_end_hour_".$i] - $_POST["shift_start_hour_".$i]) + ($_POST["shift_end_min_".$i] - $_POST["shift_start_min_".$i] - $_POST["break_time"])/60,2);
						$arrDailyTmp[] = array($_POST["staff_name_".$i],$_POST["shift_start_hour_".$i],$_POST["shift_start_min_".$i],$_POST["shift_end_hour_".$i],$_POST["shift_end_min_".$i],"new".date("U"),$_POST["break_time"],$totalHours,0);	
			
				$counter = 0;		
				foreach($_POST["choice"] as $theShop => $dates ){
								
							foreach($dates as $theDate => $value){
								$arrDailyTmp[0][5] .= $counter;
								$counter++; 
								if($value){
						
									if($arrResult[$theShop][$theDate]==""){
										
										$arrResult[$theShop][$theDate] = $arrDailyTmp;
									}
									else{
											
										$arrResult[$theShop][$theDate] = array_merge($arrResult[$theShop][$theDate],$arrDailyTmp);
											
									}
								}
						
							}
								
						}

			}
				
				

		}

		$this->view->arrResultLive = $arrResult; 
		//$this->view->arrResult = base64_encode(serialize($arrResult));	
		//echo "+++++++++++++++++++++++++++++++++++++++++++";
		//echo "<pre>";
		//var_dump( get_defined_vars() );
		//echo "</pre>";
	} 
	public function saveConfirmRosterAction(){
		
		//echo "<pre>";//
		//var_dump($_POST);//
		//echo "</pre>";//
		$dateToday = Model_DatetimeHelper::dateToday();
		
		$arrResult = $_POST["arr_Detail"];
		$arrShops = unserialize(base64_decode($_POST["shops"]));
		var_dump($arrShops);//
		$dateBegin = $_POST["date_begin"];
		$dateEnd = Model_DatetimeHelper::adjustDays("add", $dateBegin, 6);
		$arrDate = Model_DatetimeHelper::createDateArray($dateBegin, $dateEnd);
		$ts = new Model_DbTable_Roster_Timesheet();
		$lock = null;
		if(isset($_POST['btn_confirm_notify'])){
			
			$lock = 1;
		}
		
		foreach($arrShops as $shopKey => $shopValue){
			foreach($arrDate as $dateKey => $dateValue){
				echo $cot = count($arrResult[$shopKey][$dateKey]);//
				for($i=0;$i<$cot;$i++){
					if(is_numeric($arrResult[$shopKey][$dateKey][$i][5])){
						//echo "exist";//
						$strDateBegin  = $dateValue." ".$arrResult[$shopKey][$dateKey][$i][1].":".$arrResult[$shopKey][$dateKey][$i][2];
						$strDateEnd  = $dateValue." ".$arrResult[$shopKey][$dateKey][$i][3].":".$arrResult[$shopKey][$dateKey][$i][4];
						$intBreakTime = $arrResult[$shopKey][$dateKey][$i][6]*60;
						
						$intConfirmShiftBegin =($shopValue == "CLPC" || $shopValue == "CLIC" || $shopValue == "ADPC" || $shopValue == "WLIC" )?Model_DatetimeHelper::tranferToIntAde($strDateBegin) :Model_DatetimeHelper::tranferToInt($strDateBegin);
						$intConfirmShiftEnd = ($shopValue == "CLPC" || $shopValue == "CLIC" || $shopValue == "ADPC" || $shopValue == "WLIC" )?Model_DatetimeHelper::tranferToIntAde($strDateEnd) :Model_DatetimeHelper::tranferToInt($strDateEnd);
						
						if($dateValue > $dateToday){
							$ts->managerConfirmArrangeTimesheet($arrResult[$shopKey][$dateKey][$i][5], $intConfirmShiftBegin, $intConfirmShiftEnd, $intBreakTime);
						}
						else{							
							$ts->managerConfirmTimeSheet($arrResult[$shopKey][$dateKey][$i][5], $intConfirmShiftBegin, $intConfirmShiftEnd, $intBreakTime,$lock);
						}
						
						//echo $shopValue;
					}
					if(substr($arrResult[$shopKey][$dateKey][$i][5],0,3)=="new"){
						//echo "new";//
						
						$strDateBegin  = $dateValue." ".$arrResult[$shopKey][$dateKey][$i][1].":".$arrResult[$shopKey][$dateKey][$i][2];
						$strDateEnd  = $dateValue." ".$arrResult[$shopKey][$dateKey][$i][3].":".$arrResult[$shopKey][$dateKey][$i][4];
						$intBreakTime = $arrResult[$shopKey][$dateKey][$i][6]*60;
						
						$intConfirmShiftBegin =($shopValue == "CLPC" || $shopValue == "CLIC" || $shopValue == "ADPC" || $shopValue == "WLIC" )?Model_DatetimeHelper::tranferToIntAde($strDateBegin) :Model_DatetimeHelper::tranferToInt($strDateBegin);
						$intConfirmShiftEnd = ($shopValue == "CLPC" || $shopValue == "CLIC" || $shopValue == "ADPC" || $shopValue == "WLIC" )?Model_DatetimeHelper::tranferToIntAde($strDateEnd) :Model_DatetimeHelper::tranferToInt($strDateEnd);
						
						if($dateValue > $dateToday){
							$ts->managerArrangeTimesheet($arrResult[$shopKey][$dateKey][$i][0], $shopValue, $intConfirmShiftBegin, $intConfirmShiftEnd, $intBreakTime);
						}
						else{
							$ts->managerAddTimesheet($arrResult[$shopKey][$dateKey][$i][0], $shopValue, $intConfirmShiftBegin, $intConfirmShiftEnd, $intBreakTime,$lock);
						}
						
						
						
						//echo $dateValue;
						//echo $shopValue;
					}
				}
				
			}	
		}

		
		
	}
	public function saveArrangeRosterAction(){

		$arrResult = $_POST["arr_Detail"];
		$arrShops = unserialize(base64_decode($_POST["shops"]));
		
		$dateBegin = $_POST["date_begin"];
		$dateEnd = Model_DatetimeHelper::adjustDays("add", $dateBegin, 6);
		$arrDate = Model_DatetimeHelper::createDateArray($dateBegin, $dateEnd);
		$ts = new Model_DbTable_Roster_Timesheet();
		
		
		$appr = -1;
		if(isset($_POST['su_final_arrange_app'])) {$appr = 1; }
		if(isset($_POST['su_final_arrange_rej'])) {
			$appr = -2;
		}	
		
		
		$overBudget = isset($_POST['reason']);
		
		foreach($arrShops as $shopKey => $shopValue){
			foreach($arrDate as $dateKey => $dateValue){
				if(isset($arrResult[$shopKey][$dateKey])){
				echo $cot = count($arrResult[$shopKey][$dateKey]);//
				
				for($i=0;$i<$cot;$i++){
				if(is_numeric($arrResult[$shopKey][$dateKey][$i][5])){
					//echo "exist";//
					$strDateBegin  = $dateValue." ".$arrResult[$shopKey][$dateKey][$i][1].":".$arrResult[$shopKey][$dateKey][$i][2];
					$strDateEnd  = $dateValue." ".$arrResult[$shopKey][$dateKey][$i][3].":".$arrResult[$shopKey][$dateKey][$i][4];
					$intBreakTime = $arrResult[$shopKey][$dateKey][$i][6]*60;
				
					$intConfirmShiftBegin =($shopValue == "CLPC" || $shopValue == "CLIC" || $shopValue == "ADPC" || $shopValue == "WLIC" )?Model_DatetimeHelper::tranferToIntAde($strDateBegin) :Model_DatetimeHelper::tranferToInt($strDateBegin);
					$intConfirmShiftEnd = ($shopValue == "CLPC" || $shopValue == "CLIC" || $shopValue == "ADPC" || $shopValue == "WLIC" )?Model_DatetimeHelper::tranferToIntAde($strDateEnd) :Model_DatetimeHelper::tranferToInt($strDateEnd);
					if($overBudget){
						$ts->managerConfirmArrangeTimeSheet($arrResult[$shopKey][$dateKey][$i][5], $intConfirmShiftBegin, $intConfirmShiftEnd, $intBreakTime,$appr);						 
					}
					else{
						
						$ts->managerConfirmArrangeTimeSheet($arrResult[$shopKey][$dateKey][$i][5], $intConfirmShiftBegin, $intConfirmShiftEnd, $intBreakTime);						
					}
					
					//echo $shopValue;
				}
				if(substr($arrResult[$shopKey][$dateKey][$i][5],0,3)=="new"){
					//echo "new";//
				
					$strDateBegin  = $dateValue." ".$arrResult[$shopKey][$dateKey][$i][1].":".$arrResult[$shopKey][$dateKey][$i][2];
					$strDateEnd  = $dateValue." ".$arrResult[$shopKey][$dateKey][$i][3].":".$arrResult[$shopKey][$dateKey][$i][4];
					$intBreakTime = $arrResult[$shopKey][$dateKey][$i][6]*60;
				
					$intConfirmShiftBegin =($shopValue == "CLPC" || $shopValue == "CLIC" || $shopValue == "ADPC" || $shopValue == "WLIC" )?Model_DatetimeHelper::tranferToIntAde($strDateBegin) :Model_DatetimeHelper::tranferToInt($strDateBegin);
					$intConfirmShiftEnd = ($shopValue == "CLPC" || $shopValue == "CLIC" || $shopValue == "ADPC" || $shopValue == "WLIC" )?Model_DatetimeHelper::tranferToIntAde($strDateEnd) :Model_DatetimeHelper::tranferToInt($strDateEnd);
				
					
					if($overBudget){
						$ts->managerArrangeTimesheet($arrResult[$shopKey][$dateKey][$i][0], $shopValue, $intConfirmShiftBegin, $intConfirmShiftEnd, $intBreakTime,$appr);							
					}
					else{
						
						$ts->managerArrangeTimesheet($arrResult[$shopKey][$dateKey][$i][0], $shopValue, $intConfirmShiftBegin, $intConfirmShiftEnd, $intBreakTime);
						
					}

				}
				
				}
		
			}
			
			}
		}		
		
		if($overBudget){
			
			//$arrShops = unserialize(base64_decode($_POST['shops']));
			d($_POST);
			
			$subject = "Over Budge Request Approval";
			
			$mailBody = "";	
			
			$mail = new Model_Emailshandler();
			$shopHead = new Model_DbTable_Shoplocation();
			
			foreach ($_POST['reason'] as $key => $v){
				$btHour = $_POST['week_budget_hour'][$key];
				$toHour = $_POST['week_total_hour'][$key];
				$ext = $toHour - $btHour;
				
				$mailBody = "SHOP {$arrShops[$key]} Budget Hours is {$btHour} and Allocated Hours is {$toHour} , Exceed Hours {$ext}, For the Following Reason [{$v}] , On Week Begin {$dateBegin},Please Go to Page to Approval It.";
				
				$mailBodyApp = "SHOP {$arrShops[$key]} Budget Hours is {$btHour} and Allocated Hours is {$toHour} , Exceed Hours {$ext},On Week Begin {$dateBegin},Has Been Approved";
				$mailBodyRej = "SHOP {$arrShops[$key]} Budget Hours is {$btHour} and Allocated Hours is {$toHour} , Exceed Hours {$ext} , On Week Begin {$dateBegin},Has Been Rejected, You must Fix It";
				
				if($appr == -1){
					$mail->sendNormalEmail("eco1@phonecollection.com.au", $subject, $mailBody);
					echo "Email Sent To HQ";
				}
				
				$shopEmail = $shopHead->getShopMailByHead($arrShops[$key]);
				
				if($appr == 1){
					
					$mail->sendNormalEmail($shopEmail,"Your Over Budget Request Has Been Approved", $mailBodyApp);
					echo "Email Sent To Shop".$shopEmail;
				}
				
				if($appr == -2){
					
					$mail->sendNormalEmail($shopEmail,"Your Over Budget Request Has Been Rejected", $mailBodyRej);
					echo "Email Sent To Shop".$shopEmail;
				}
				
			}
			
		
		}
		
		
	}	
	public function shopSelectionAction(){
		
		$loginSuccess = false;
		
		$arrManagerID = array(1,110,2,3,4,27,61,80,115,131,156,165,162,36,125);
		$arrShopList = unserialize(ARR_MANAGER);
		
		$stDetail = new Model_DbTable_Roster_Stafflogindetail();
		
		if($_POST){
			
			$pwd = $_POST["password"];
			
			$stLine = $stDetail->checkPasswordCorrect(Model_EncryptHelper::hashsha(trim($pwd)));
			//var_dump($stLine);
			if($stLine){
				if(isset($arrShopList[$stLine["id"]])){
				
				$this->view->spList = $arrShopList[$stLine["id"]];		
				$this->view->idMgr = base64_encode(trim($stLine["id"]));
				$loginSuccess = true;
				}
				else{
					$loginSuccess = false;
					echo '<script language="javascript"> window.alert("According Your Password, You are Not a Manager, Please try TYPE IN password again!")</script>';
				}
			}
			else{
				$loginSuccess = false;
				echo '<script language="javascript"> window.alert("Incorrect Password, Please try your password again")</script>';
				
			}
			
		}
		
		$this->view->loginSuccess = $loginSuccess;
		
		if(1){
			
			$this->renderScript("/roster/shop-selection-140320.phtml");
		}
	}
	public function newStaffAction(){
		
		$sDetail = new Model_DbTable_Roster_Stafflogindetail();
		$newStaff = $sDetail->matchMyobStaff();
		//var_dump($newStaff);
		$arrFinal = array();
		foreach($newStaff as $v){
			$idStaff = $v["id"];
			$arrFinal[] = $sDetail->getDetail($idStaff);
			
		}
		
		$this->view->arrStaff = $arrFinal;
	}	

	public function newStaffVerifyAction(){
		$agFileName = "AGFILE";
		$beFileName = "BEFILE";
		
		$arrIdRecord = array();
		$fl = new Model_Fileshandler();
		$stInfo = new Model_DbTable_Roster_Staffinfo();
		$sDetail = new Model_DbTable_Roster_Stafflogindetail();
		
		if($_POST){
			
			//get files
			if(isset($_POST["bn_verify"])){
				
				if(move_uploaded_file($_FILES["ag_file"]["tmp_name"],$agFileName) && move_uploaded_file($_FILES["be_file"]["tmp_name"],$beFileName)){
					
					$arrAg = $fl->readStaffFile($agFileName);
					$arrBe = $fl->readStaffFile($beFileName);
				//	var_dump($arrBe);
									
					foreach($_POST["act"] as $k => $v){
						
						if($_POST["type"][$v] != 2 && $_POST["type"][$v] != 5 ){
							
							$key = array_search(trim($_POST["id_card"][$v]),$arrAg);
						
						if($key !== false){
							if(trim($_POST["last_name"][$v]) == trim($arrAg[$key - 2]) && trim($_POST["first_name"][$v]) == trim($arrAg[$key - 1]) ){
								$arrIdRecord[$v] = $arrAg[$key - 3];
							}
						}
						else{
							$key = array_search(trim($_POST["id_card"][$v]),$arrBe);
							
							if(trim($_POST["last_name"][$v]) == trim($arrBe[$key - 2]) && trim($_POST["first_name"][$v]) == trim($arrBe[$key - 1]) ){
								$arrIdRecord[$v] = "Type Not Match";
							}
						}
						
						}
						else{
							
							$key = array_search(trim($_POST["id_card"][$v]),$arrBe);
							
							if($key !== false){
								if(trim($_POST["last_name"][$v]) == trim($arrBe[$key - 2]) && trim($_POST["first_name"][$v]) == trim($arrBe[$key - 1]) ){
									$arrIdRecord[$v] = $arrBe[$key - 3];
								}
							else{
								$key = array_search(trim($_POST["id_card"][$v]),$arrAg);
									
								if(trim($_POST["last_name"][$v]) == trim($arrAg[$key - 2]) && trim($_POST["first_name"][$v]) == trim($arrAg[$key - 1]) ){
									$arrIdRecord[$v] = "Type Not Match";
								}								
								
								
								
							}								
						}
						
						}
					}
				}
				
			}
			
			//click the save button
			if(isset($_POST["bn_save"])){
				
				foreach($_POST["act"] as $key => $v){
					$stInfo->verifyAndUpdateStaffinfo($v,trim($_POST["first_name"][$v]),trim($_POST["last_name"][$v]),trim($_POST["id_card"][$v]),trim($_POST["id_record"][$v]),$_POST["type"][$v]);
					//$stInfo->addStaffinfo($v,trim($_POST["first_name"][$v]),trim($_POST["last_name"][$v]),trim($_POST["id_card"][$v]),trim($_POST["id_record"][$v])," ",1);
					//$stInfo->updateStaffType($v,$_POST["type"][$v]);
					$sDetail->updateNickName($v,$_POST["nick_name"][$v]);			
				}
				
				$this->_helper->redirector("new-staff-verify-done");
				
			}
		
		}
		$this->view->arrIdRecord = $arrIdRecord;
		
		
	}
	public function newStaffVerifyDoneAction(){
		
	}
	public function checkMyRosterAction(){
		
		$staff = $this->_getParam("staff");
		$idStaff = Model_EncryptHelper::getSslpassword(base64_decode($staff));
		$dateBegin = Model_DatetimeHelper::getThisWeekMonday();
		$dateEnd = Model_DatetimeHelper::adjustWeeks("add",Model_DatetimeHelper::getThisWeekSunday(),3);
		$tList = array();
		
		$ts = new Model_DbTable_Roster_Timesheet();
		
		if(is_numeric($idStaff)){
		$tList = $ts->listShiftByStaffIdByDate($idStaff, $dateBegin, $dateEnd);
		
		$arrSb = array();
		foreach($tList as $key => $v){
			$arrSb[$key] = $v["arra_shift_begin"];
		}
		
		array_multisort($arrSb,SORT_ASC,$tList);
		}
		$this->view->tList = $tList;
		
	}
	public function saveSuTimesheetAction(){
		
		//date_default_timezone_set("Australia/Melbourne");
		
		//var_dump($_POST);
		
		$ts = new Model_DbTable_Roster_Timesheet();
		
		$shiftBegin = trim($_POST["date"])." ".$_POST["shift_start_hour_1"].":".$_POST["shift_start_min_1"];
		$shiftEnd =  trim($_POST["date"])." ".$_POST["shift_end_hour_1"].":".$_POST["shift_end_min_1"];
		
		$intShiftBegin = Model_DatetimeHelper::tranferToInt($shiftBegin);
		$intShiftEnd =  Model_DatetimeHelper::tranferToInt($shiftEnd);
		
		if($_POST["shop_code"]=="ADPC" || $_POST["shop_code"]=="CLPC"|| $_POST["shop_code"]=="CLIC"|| $_POST["shop_code"]=="WLIC" ){
			
			$intShiftBegin = Model_DatetimeHelper::tranferToIntAde($shiftBegin);
			$intShiftEnd =  Model_DatetimeHelper::tranferToIntAde($shiftEnd);	
		}

		$ts->addSuTimesheet($_POST["staff_name_1"],$_POST["shop_code"], $intShiftBegin, $intShiftEnd);
		
	}
	
	public function managerCheckAttnRecordAction(){

		$shop = $this->_getParam("shop");
		$dateBegin = $this->_getParam("db");
		$dateEnd = $this->_getParam('dn');
		
		$arrShop = unserialize(base64_decode($shop));
		
		
		if($_POST){

			$dateBegin = $_POST['date_begin'];
			$dateEnd = $_POST['date_end'];
			$arrShop = $_POST['shop_selection'];
		}
		
		//$shop = array(0=>'BHPC',1=>'CBPC');
		//$dateBegin = '2013-11-10';
		//$dateEnd = '2013-11-17';
		
		
		
		$this->view->arrDate = $arrDate = Model_DatetimeHelper::createDateArray($dateBegin, $dateEnd);
		
		$this->view->arrShop = $arrShop;
		$staffs = new Model_DbTable_Roster_Stafflogindetail();
		
		$attRecord = new Model_DbTable_Roster_Attnrecord();
		
		$arrRes = array();
		
		
		foreach($arrDate as $dateToCheck){
	
			
			foreach($arrShop as $shopKey => $shopCode){
				
				$tmpArr = array();
				$tList = $attRecord->getOndutyByDateByShop($shopCode, $dateToCheck);
				foreach($tList as $k => $v1){
							
					//echo $v["sti"];
					$stDetail = $staffs->getDetail($v1["sti"]);
					$tmpArr[$k] = ucwords(Model_EncryptHelper::getSslPassword($stDetail["n"]));
					$tmpArr[$k] .= " (".ucwords($stDetail["ni"]).") ";
					$arrRecord = $attRecord->getFirstOnLastOffDuty($v1["sti"], $dateToCheck,Model_EncryptHelper::sslPassword($shopCode));
					//var_dump($arrRecord);
					$begin = "[N/A]";
					$end = "[N/A]";
						
					if( $arrRecord[0] != "" ){
						$begin = date("H:i",$arrRecord[0]);
					}
					if( $arrRecord[1] != "" ){
						$end = date("H:i",$arrRecord[1]);
					}
					$tmpArr[$k] .= " {$begin} to {$end}<br/>";
					
					
				}
				
				$arrRes[$dateToCheck][$shopCode] = $tmpArr;
			}
		}
		
		//var_dump($arrRes);
		$this->view->arrRes = $arrRes;
	}
	
	public function startHereAction(){
		$this->view->shop = $this->_getParam("shop");
	}
	
	public function registrationAction(){
		
		$errorFlag = false;
		$arrManagerID = array(1,110,2,3,4,27,61,80,115,131,156,165,162,36,125,181);
		
		
		$stDetail = new Model_DbTable_Roster_Stafflogindetail();
		$stInfoDetail = new Model_DbTable_Roster_Staffinfo();
		
		
		$shop = $_POST['shop'];
		$mgrPwd = $_POST['mgr_pwd'];
		$this->view->email = $_POST['email_addr'];
		
		if($stInfoDetail->checkEmailExist($_POST['email_addr'])){
			
			$errorFlag = true;
			echo "Email Already Exist";
		}
		$stLine = $stDetail->checkPasswordCorrect(Model_EncryptHelper::hashsha($mgrPwd));
		if($stLine){
			if(!in_array($stLine['id'], $arrManagerID)){
			$errorFlag = true;
		
			}
			else{
				
				$this->view->shopManager = base64_encode(serialize(array($shop,$stLine['id'])));
			}
		}
		
		$this->view->errorFlag = $errorFlag;
		
	}
	public function registrationDoneAction(){
		
		$stDetail = new Model_DbTable_Roster_Stafflogindetail();
		$stInfoDetail = new Model_DbTable_Roster_Staffinfo();
		$email = new Model_Emailshandler();
		
		
		if($_POST){
			$givenName = strtolower(trim(str_replace(" ","",$_POST["given_name"])));
			$surName =  strtolower(trim(str_replace(" ","",$_POST["surname"])));
			
			$name = Model_EncryptHelper::sslPassword($givenName." ".$surName);
			$dob = $stDetail->formateDob($_POST["year"],$_POST["month"],$_POST["day"]);
			$init = substr(trim($_POST["surname"]),0,1);
			$ascii = ord(strtolower($init)) - 96;
			$lastLogin = date("U");
			$ip = $_SERVER['REMOTE_ADDR'];
			$emailStaff = $_POST['email_addr'];

			
			
			// Get Infos
			// put into DB
			
			
			$idStaff = $stDetail->addDetail($name, Model_EncryptHelper::sslPassword($dob), $ascii,null, $lastLogin,'token', $ip,1,trim($_POST['pre_name']));
			
			$stDetail->updateExpireDate(Model_DatetimeHelper::adjustDays("add",Model_DatetimeHelper::dateToday(),7), $idStaff);
			
			$stInfoDetail->addStaffinfo($idStaff,ucfirst($givenName),ucfirst($surName),trim($_POST['gender']),"", 0,0, trim($emailStaff), trim($_POST['mobile_no']), trim($_POST['addr']),trim($_POST['suburb']) ,trim($_POST['state']), trim($_POST['post_code']),"", -1);
			$stInfoDetail->updatePersonalData($idStaff, $_POST['passport_no'],$_POST['driver_license'], $_POST['visa_type'], $_POST['visa_date'],$_POST['tfn_no'],$_POST['bank_bsb'],$_POST['bank_acc_no'],$_POST['bank_acc_name'], $_POST['card_no'], $_POST['medicare_no'], $_POST['student_no']);
			
			// add expire date 7+
			
			
			//$stInfoDetail->addStaffinfo($idStaff, $fistName, $lastName, $idCard, $idRecord, $emailStaff, $activeStaff);
			
		// Send email to Frannie
			$arrShopManager = unserialize(base64_decode(trim($_POST['shop_manager'])));
			
			$shopCode = $arrShopManager[0];
			
			$idMgr = $arrShopManager[1];
			$nameMgr = $stDetail->getDetail($idMgr);
			
			
		$email->sendNewStaffEmail(trim($_POST['given_name']), trim($_POST['surname']), trim($_POST['gender']), $emailStaff,trim($_POST['pre_name']),trim($_POST["year"])."-".trim($_POST["month"])."-".trim($_POST["day"]), trim($_POST['addr']),trim($_POST['suburb']) ,trim($_POST['state']), trim($_POST['post_code']),trim($_POST['mobile_no']), $shopCode, $nameMgr['ni']);
		$email->sendingWelomeLetter($emailStaff, trim($_POST['given_name']),trim($_POST['surname']));		
		// redirect to setup  page
			 			
			
		// create upload link sha  
			
		}

		
		
	}
	public function uploadIdAction(){
		
	}
	public function uploadIdDoneAction(){
		
	}
	public function confirmIdAction(){
		
	}
	public function confirmIdDoneAction(){
		
	}
	
	public function checkMybonusAction(){
		
		$idStaff = Model_EncryptHelper::getSslPassword(base64_decode($this->_getParam("sid")));
		
		$bos = new Model_Payroll_Staffbonus();
		
		$bList = $bos->listReleasedBonus($idStaff);
		$this->view->bList = $bList; 
		
	}
	
	public function staffListProblemsAction(){
	
	
	
		$token = $this->_getParam("token");
	
		//$token = base64_encode("1");
		$idStaff =  base64_decode($token);
	
		date_default_timezone_set('Australia/Melbourne');
		$tSheet = new Model_DbTable_Roster_Timesheet();
		$atRecord = new Model_DbTable_Roster_Attnrecord();
		$stInfo = new Model_DbTable_Roster_Staffinfo();
		$arrResult = array();
		
		
		$dateBegin = Model_DatetimeHelper::adjustWeeks("sub",Model_DatetimeHelper::getLastWeekMonday(),1);
		$dateEnd = Model_DatetimeHelper::dateYesterday();
		
		$intDateBegin = Model_DatetimeHelper::tranferToInt($dateBegin);
		$intDateEnd = Model_DatetimeHelper::tranferToInt($dateEnd) + 86400;

		$shiftList = $tSheet->listShiftByStaffIdByDate($idStaff, $dateBegin, $dateEnd);
		//
		
				
			foreach($shiftList as $shift){
			$tmpArr = $shift;

			$shopHead = Model_EncryptHelper::sslPassword($shift["shop_head"]);
					$dateCheck = date("Y-m-d",$shift["shift_begin"]);
	
	
					$attLine = $atRecord->getFirstOnLastOffDuty($idStaff, $dateCheck, $shopHead);
					$tmpArr["on_duty"] = $attLine[0];
					$tmpArr["off_duty"] = $attLine[1];
					$tmpArr["error"] = "";
	
					if($shift["confirm_shift_begin"]!= null && $shift["confirm_shift_end"]!= null){
					//staff shift login match staff actuall login do not match manage , manager did not do change web we can change
	
					if(
							($shift["confirm_shift_begin"] != $shift["shift_begin"] || $shift["confirm_shift_end"] != $shift["shift_end"])
							&& ($attLine[0]!="" && $attLine[1]!="" )
						&& ($shift["confirm_shift_begin"] != $shift["confirm_shift_end"])
						) {
							
						//$tmpArr["error"] .= "Mgr Assign Diff Time Period,Calculate Base On Mgr,BUT Need Send to Mgr Confirm";
						$tmpArr["error"] = array('The Shift Time Manager Confirmed is Different Form Your Input, Please Notify Your Manager');
						$stLine = $stInfo->getStaffinfo($idStaff);
						$arrResult[]=$tmpArr;
						
					}
					
					//manager do not allocate time , it has the actually login , we can change it
					if(($shift["confirm_shift_begin"] == $shift["confirm_shift_end"]) && ($attLine[0] != "" && $attLine[1]!="")){
	
								/**
								 * check if all the record For that staff for that day is calculated, if yes don't worry about it 
								 * because one time sheet is emptyed by manager , she may allocate other shift to the staff
								 * if Non of Shift For that staff for that day in that shop is calculated, and the staff come only in that case
								 * list in here for staff to see
								 */
								
									$staffShiftList = $tSheet->listAllShiftByStaffIdByDateByShop($idStaff,$shift["shop_head"], $dateCheck, $dateCheck);
									$allEmpty = true;
									
								if($staffShiftList){
									foreach($staffShiftList as $k2 => $v2){
											
										if($v2["confirm_shift_begin"] != $v2["confirm_shift_end"]){
											$allEmpty = false;
										}
									}
								}
											
								if($allEmpty){
											//$tmpArr["error"] = "Staff Come,But Mgr NOT Allocate,Will NOT Calculate, Need Send to Mgr Confirm";
									$tmpArr["error"] = array('Your Manager COnfirmed That You Should Not Be That Shop That Day, Yet Your Come.');
									$stLine = $stInfo->getStaffinfo($idStaff);
									$arrResult[]=$tmpArr;
								}
	
						}
						//both manager and staff did not do job well
											if(($shift["confirm_shift_begin"] == $shift["confirm_shift_end"]) && ($attLine[0] == "" || $attLine[1]=="") && ($attLine[0] != $attLine[1])){
	
											$staffShiftList = $tSheet->listAllShiftByStaffIdByDateByShop($idStaff,$shift["shop_head"], $dateCheck, $dateCheck);
											$allEmpty = true;
						
											if($staffShiftList){
												foreach($staffShiftList as $k2 => $v2){
								
													if($v2["confirm_shift_begin"] != $v2["confirm_shift_end"]){
														$allEmpty = false;
													}
												}
											}
											if($allEmpty){
												//$tmpArr["error"] = "Mgr NOT Allocate, Staff Also Missing Record, Will Not Calculate, Need Send to Mgr Confirm";
												$tmpArr["error"] = array('Your Attendance Record was Missing For That Day, And Your Manager Confirmed That You Did Not Come That Day.');
												$stLine = $stInfo->getStaffinfo($idStaff);													
												$arrResult[]=$tmpArr;
											}
	
											}
						// staff did not do job well , missing login record
											if(($shift["confirm_shift_begin"] != $shift["confirm_shift_end"]) && ($attLine[0] == "" || $attLine[1]=="")){
													
												//$tmpArr["error"] = "Staff Missing Record, Will Not Calculate,Need Send to Mgr Confirm";
	
												$tmpArr["error"] = array('Your Attendance Record was Missing For That Day, Either On-Duty Or Off-Duty');
	
												if($attLine[0] == "" && $attLine[1]=="" ){
	
													$tmpArr["error"] = array('There is No Attendance Record For you In This Shop For That Day, Are You Sure You Came?');
														
												}
	
												$stLine = $stInfo->getStaffinfo($idStaff);
	
												$arrResult[]=$tmpArr;
													
											}
												
											///staff shift login do not match manage allocated, manager allocedat match staff login, staff input wrong time
								}
	} // End of For Each
		
	$erros = array();
	$stIds = array();
	$dates = array();
	
	foreach($arrResult as $k => $v){
	
		$erros[$k] = $v["error"];
		$stIds[$k] = $v["id_staff"];
		$dates[$k] = $v["confirm_shift_begin"];
	}
	
	array_multisort($stIds,SORT_ASC,$dates,SORT_ASC,$erros,SORT_ASC,$arrResult);
	$this->view->arrResult = $arrResult;
	
	}
	
	public function exportRosterAction(){
		
		//$dateBegin = $this->_getParam("date_begin");
		//$dateEnd = $this->_getParam("date_end");
		
		$dateBegin = $_POST['date_begin'];
		$dateEnd = $_POST['date_end'];
		$arrShop = $_POST['shop_selection'];
		
		
		$arrDate = Model_DatetimeHelper::createDateArray($dateBegin, $dateEnd);
		$stDetail = new Model_DbTable_Roster_Stafflogindetail();
		
		$shops = $this->_getParam("shops");
		
		//$arrShop = unserialize(base64_decode($shops));
		
		$ts = new Model_DbTable_Roster_Timesheet();
		
		$arrRes = array();
		
		foreach($arrShop as $shop){
			
			echo $shop."<br />";
			date_default_timezone_set('Australia/Melbourne');
			
			if($shop == "ADPC" || $shop == "CLPC" || $shop == "CLIC" || $shop == "WLIC" ){
				date_default_timezone_set('Australia/Adelaide');
			}
			
			foreach($arrDate as $dateToCheck){
				
				$arrRes[$shop][$dateToCheck] = $tsLines = $ts->listShiftByDateByShop($shop, $dateToCheck, $dateToCheck);
				echo $dateToCheck." : ";
				
				foreach($tsLines as $tsLine){
					$sdLine = $stDetail->getDetail($tsLine['id_staff']);
				echo	$showName = ($sdLine['ni']=="") ? ucwords(Model_EncryptHelper::getSslPassword($sdLine['n'])) : ucwords($sdLine['ni']).".".strtoupper(chr($sdLine['il']+96));
				//echo $tsLine['id_staff'];
				echo " ";
				echo date("H:i",$tsLine['arra_shift_begin']);
				echo " - ";
				echo date("H:i",$tsLine['arra_shift_end']);
				echo ", ";
				}
				
				echo "<br />";
					
			}
		}
		
		//d($arrRes);
		
	}
}
?>