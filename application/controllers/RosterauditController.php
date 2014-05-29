<?php 
/**

 */
class RosterauditController extends Zend_Controller_Action
{

	protected static $arrShopMaping;
	
    public function init(){
    /**
	 *
	 *
	 */    
	self::$arrShopMaping = unserialize(ARR_APOS_SHOP_MAPPING);
	}

    public function indexAction(){
	
	}
	public function createCaseAction(){
		
		$preLoad = "";
		if($_POST){
		
			//$post Data from Listing Problem 
			$preLoad =unserialize(base64_decode($_POST["case"]));	
		}
		else{
					
			$this->view->idMgr = base64_decode(trim($this->_getParam("token")));
		}

		$stDetail = new Model_DbTable_Roster_Stafflogindetail();
		$this->view->stList = $stDetail->listStaffByNickName();
		
		$this->view->preLoad = $preLoad;
	}
	
	public function saveAttnCaseAction(){
		
		$attCase = new Model_DbTable_Roster_Attncase();
		$acRecord = new Model_DbTable_Roster_Attcaserecord();
		
		$cPath = getcwd();
		$uploadPathOn = '/upload/onduty/';
		$uploadPathOff = '/upload/offduty/';
		
		if($_POST){
			
			$caseIdCase = $_POST['case_id'];
			$idManager = $_POST['id_manager'];
			$idStaff = $_POST['staff_name'];
			
			$shopCode = $_POST['shop_code'];
			
			$shiftDate = $_POST['shift_date'];
			
			$manShiftBeginHour = $_POST['man_shift_begin_hour'];
			$manShiftBeginMin = $_POST['man_shift_begin_min'];
			$manShiftEndHour = $_POST['man_shift_end_hour'];
			$manShiftEndMin = $_POST['man_shift_end_min'];
			
			$staffShiftBeginHour = $_POST['act_shift_begin_hour'];
			$staffShiftBeginMin = $_POST['act_shift_begin_min'];
			$staffShiftEndHour = $_POST['act_shift_end_hour'];
			$staffShiftEndMin = $_POST['act_shift_end_min'];
			
			$breakFirstMin =$_POST['first_break'];
			$breakSecondMin = $_POST['second_break'];
			 
			//upload file 
			$linkImageOpen = 0;
			$linkImageClose = 0;

			
			if(isset($_FILES['footage_on_duty'])){
			$onDutyFileName = $_FILES['footage_on_duty']['name'];
			$ext = pathinfo($onDutyFileName, PATHINFO_EXTENSION);
			$newFileName = $caseIdCase."_on.".$ext;
			
			
			if(move_uploaded_file($_FILES['footage_on_duty']['tmp_name'],$cPath.$uploadPathOn.$newFileName)){
				
				$linkImageOpen = $uploadPathOn.$newFileName;
			}
			}
						
			if(isset($_FILES['footage_off_duty'])){
				$offDutyFileName = $_FILES['footage_off_duty']['name'];
				$ext = pathinfo($offDutyFileName, PATHINFO_EXTENSION);
				$newFileName = $caseIdCase."_off.".$ext;
					
					
				if(move_uploaded_file($_FILES['footage_off_duty']['tmp_name'],$cPath.$uploadPathOff.$newFileName)){
			
					$linkImageClose = $uploadPathOff.$newFileName;
				}
			}

			$offlineInvOn = $_POST['apos_invoice_on_duty'];
			$offlineInvOff = $_POST['apos_invoice_off_duty'];

			$reasonComment = $_POST['reason_missing_footage'];
			$detailExplain = $_POST['detail_explain'];

			$hourRequest = $_POST['manager_hour'];
			$hourAllocate = $_POST['system_hour'];
			
			$lodgeDate = Model_DatetimeHelper::dateToday();
			
			if($idManager >0){
			$idCase = 	$attCase->addAttncase($caseIdCase, $idManager, $idStaff, $shopCode, $shiftDate, $manShiftBeginHour, $manShiftBeginMin, $manShiftEndHour, 
					$manShiftEndMin, $staffShiftBeginHour, $staffShiftBeginMin, $staffShiftEndHour, $staffShiftEndMin, $breakFirstMin,
					 $breakSecondMin, $linkImageOpen, $linkImageClose, $offlineInvOn, $offlineInvOff, $reasonComment, $detailExplain, $hourRequest, $hourAllocate, $lodgeDate);
			
			
			$acRecord->addAttcaserecord($idCase,1,Model_DatetimeHelper::dateToday(),Model_DatetimeHelper::timeNow(),$idManager);
			}else{
				
				echo "<h1>ERROR OCCUR, CASE NOT CREATED</h1>";
				
			}
		}
	}
	public function checkRosterByNameAction(){
		
		date_default_timezone_set('Australia/Melbourne');
		
		$initial = 1;
		$initial = $this->_getParam("i");
		$staffId = $this->_getParam("std");
		
		$su = $this->_getParam("su");
		
		$this->view->issu = false;
		if($su=="unlimiteds"){
			
			$this->view->issu = true;
			
		}
		$dateToday = Model_DatetimeHelper::dateToday();
		
		$dateBegin = $dateEnd = $dateToday;
		
		if(isset($_POST["date_begin"])){
			$dateBegin = $_POST["date_begin"];
		}
		if(isset($_POST["date_end"])){
			$dateEnd = $_POST["date_end"];
		}		
		
		$this->view->showSlist = false;
		$this->view->showDetail = false;
		$this->view->sList = "";
		
		$stDetail = new Model_DbTable_Roster_Stafflogindetail();
		$attRecord = new Model_DbTable_Roster_Attnrecord();
		$ts = new Model_DbTable_Roster_Timesheet();
		
		
		if($this->_getParam("i")!=""){
			
			$this->view->showSlist = true; 
			$this->view->sList = $stDetail->listStaffByLastName($initial);
			$this->view->showDetail = false;
		}
		
		if($this->_getParam("std")!=""){
			
			$arrRlist = array();// result List
			
			$this->view->idStaff = $staffId;
			  
			$this->view->showSlist = false; // wait decide
			$nameRow = $stDetail->getDetail($staffId);
			$this->view->staffName =Model_EncryptHelper::getSslPassword($nameRow["n"]);
			$this->view->nickName = $nameRow['ni'];
			//$this->view->SList = $stDetail->listStaffByLastName($inital);
			
			//$rList = $attRecord->listRecordByStaffId($staffId,$dateBegin,$dateEnd);
			
			//$this->view->rList = $rList;
			$arrDate = Model_DatetimeHelper::createDateArray($dateBegin, $dateEnd);
			
			
			
			foreach($arrDate as $v){
				//get how many shop the today

				$shifts = $ts->listShiftByStaffIdByDate($staffId, $v, $v);
				
				$sList = $attRecord->getShopHeadByStaffIdByDate($staffId, $v, $v);
				if(!$sList){
					if($shifts){
					foreach($shifts as $sk => $sv){
					$arrTmp= array();
					$arrTmp["date"] =$v;
					$arrTmp["day"]=date("D",strtotime($v));
					$arrTmp["shophead"] =$sv["shop_head"];
					$arrTmp["shift_begin"]=$sv["shift_begin"];
					$arrTmp["shift_end"]=$sv["shift_end"];
					$arrTmp["act_begin"]="";
					$arrTmp["act_end"]="";
					$arrTmp["fake_act_begin"]=false;
					$arrTmp["fake_act_end"]=false;
					$arrTmp["status_begin"]="-";
					$arrTmp["status_end"]="-";
					$arrTmp["shift_normal_hour"] = "";
					$arrTmp["shift_6pm_hour"] = "";
					$arrTmp["shift_normal_hour"] = "";
					$arrTmp["shift_6pm_hour"] = "";
					$arrTmp["act_normal_hour"] = "";
					$arrTmp["act_6pm_hour"] = "";
					$arrTmp["act_sat_hour"] = "";
					$arrTmp["act_sun_hour"] = "";
					$arrTmp["offline_begin"]=false;
					$arrTmp["offline_end"]=false;
					$arrRlist[] = $arrTmp;
					}
					}
					else{
						$arrTmp= array();
						$arrTmp["date"] =$v;
						$arrTmp["day"]=date("D",strtotime($v));
						$arrTmp["shophead"] =" - ";
						$arrTmp["shift_begin"]="";
						$arrTmp["shift_end"]="";
						$arrTmp["act_begin"]="";
						$arrTmp["act_end"]="";
						$arrTmp["fake_act_begin"]=false;
						$arrTmp["fake_act_end"]=false;
						$arrTmp["status_begin"]="-";
						$arrTmp["status_end"]="-";
						$arrTmp["shift_normal_hour"] = "";
						$arrTmp["shift_6pm_hour"] = "";
						$arrTmp["shift_normal_hour"] = "";
						$arrTmp["shift_6pm_hour"] = "";
						$arrTmp["act_normal_hour"] = "";
						$arrTmp["act_6pm_hour"] = "";
						$arrTmp["act_sat_hour"] = "";
						$arrTmp["act_sun_hour"] = "";
						$arrTmp["offline_begin"]=false;
						$arrTmp["offline_end"]=false;
						$arrRlist[] = $arrTmp;						
						
					}
				}
				else{
					
				
				foreach($sList as $sh){
							
					$arrTmp= array();
					
					
					 
					$arrTmp["date"] =$v;  //date
					
					$arrTmp["day"]=date("D",strtotime($v)); //day
					$intDay =date("N",strtotime($v));
					$arrTmp["shophead"] = Model_EncryptHelper::getSslPassword($sh["sh"]);//shop
					
					$shopLocation = 1;
					if($arrTmp["shophead"] =="ADPC" || $arrTmp["shophead"] =="CLPC" ||$arrTmp["shophead"] =="CLIC" ||$arrTmp["shophead"] =="WLIC" ){
						$shopLocation = 2;
					}
					
					$tList = $ts->listShiftByStaffIdByDateByShop($staffId,Model_EncryptHelper::getSslPassword($sh["sh"]),$v,$v);
					if($tList){
						$arrTmp["shift_begin"] = $tList["shift_begin"];//time sheet start
						$arrTmp["shift_end"]=$tList["shift_end"];	//time sheet end					
					}
					else{
						$arrTmp["shift_begin"] = "";//time sheet start
						$arrTmp["shift_end"]= "";	//time sheet end						
					}
					$actList = $attRecord->getFirstOnLastOffDuty($staffId,$v,$sh["sh"]);
					
					$arrTmp["act_begin"]=$actList[0];//act start
					$arrTmp["act_end"]=$actList[1];//act end
					$arrTmp["fake_act_begin"] = $actList[2]; // manul inserted begin
					$arrTmp["fake_act_end"] = $actList[3]; // manul inserted end
					$arrTmp["offline_begin"]=$actList[4];
					$arrTmp["offline_end"]=$actList[5];
					//compare status
					$arrTmp["status_begin"] = $attRecord->compareStartTime($arrTmp["shift_begin"], $arrTmp["act_begin"]);
					$arrTmp["status_end"] = $attRecord->compareFinishTime($arrTmp["shift_begin"], $arrTmp["act_begin"]);
					
					//Shift
					if( $intDay<6){

						$arrShiftHour = $ts->calShiftHours($v, $arrTmp["shift_begin"], $arrTmp["shift_end"], $shopLocation);

						$arrTmp["shift_normal_hour"] = $arrShiftHour[0];
						$arrTmp["shift_6pm_hour"] = $arrShiftHour[1];
					}
					else{
						if($arrTmp["shift_begin"] == "" ||  $arrTmp["shift_end"]==""){	
							$arrTmp["shift_normal_hour"] = "";
							
						}
						else{
							$arrTmp["shift_normal_hour"] = round(($arrTmp["shift_end"]-$arrTmp["shift_begin"])/3600,2);
									
						}
						$arrTmp["shift_6pm_hour"] = "";
					}
					
					if($intDay<6){
						
						$arrActHour = $ts->calShiftHours($v, $arrTmp["act_begin"], $arrTmp["act_end"], $shopLocation);
						$arrTmp["act_normal_hour"] = $arrActHour[0];
						$arrTmp["act_6pm_hour"] = $arrActHour[1]; 
						$arrTmp["act_sat_hour"] = "";
						$arrTmp["act_sun_hour"] = "";
						
					}
					if($intDay == 6){
						
						$arrTmp["act_normal_hour"] = "";
						$arrTmp["act_6pm_hour"] = "";
						$arrTmp["act_sat_hour"] = ($arrTmp["act_begin"]=="" || $arrTmp["act_end"]=="")?"":round(($arrTmp["act_end"]-$arrTmp["act_begin"])/3600,2);
						$arrTmp["act_sun_hour"] = "";						
						
						
					}
					if($intDay == 7){
						$arrTmp["act_normal_hour"] = "";
						$arrTmp["act_6pm_hour"] = "";
						$arrTmp["act_sat_hour"] = "";
						$arrTmp["act_sun_hour"] = ($arrTmp["act_begin"]=="" || $arrTmp["act_end"]=="")?"":round(($arrTmp["act_end"]-$arrTmp["act_begin"])/3600,2);						
					}
					
					
					//act normal hour
					//after 6 pm hour
					//total saturday hour , No afer 6 pm hour 
					//total sunday hour , no after 6pm hour 
					
					
					$arrRlist[] = $arrTmp; 
				}
				
				}
				//
				
				//
			$this->view->rList = $arrRlist;
			
			//var_dump($arrRlist);
				
			}
			
			$this->view->staffId = $staffId;
			//$result = $attRecord->restructure($rList);
			$this->view->showDetail = true;	
			
			
		}
		
			
	}
	
	public function checkRosterByShopAction(){
		
	}
	
	public function earlyLateSummaryAction(){
			
		$lastMonday = Model_DatetimeHelper::getLastWeekMonday();
		$lastSunday = Model_DatetimeHelper::getLastWeekSunday();
		$intLastMonday = Model_DatetimeHelper::tranferToInt($lastMonday);
		$intLastSunday = Model_DatetimeHelper::tranferToInt($lastSunday);

		$ele = new Model_DbTable_Roster_Earlylateevent();
		$ts = new Model_DbTable_Roster_Timesheet();
		
		$rows = $ts->listShiftWithEvent($intLastMonday, $intLastSunday); 

		$arrRes = array();
		foreach($rows as $shiftRow){
			
			
			if($shiftRow["event_on_duty"]!=null){
				$eventLine = $ele->getEarlylateevent($shiftRow["event_on_duty"]);
				if($eventLine["event_code"] == 2){
					//					
					$tmpArr = $shiftRow;
					$tmpArr[] = $eventLine["date_event"];
					$tmpArr[] = $eventLine["staff_comment"];
					$tmpArr[] = 2;
					$arrRes[] = $tmpArr;
				}
				
			}
			
			if($shiftRow["event_off_duty"]!=null){
				$eventLine = $ele->getEarlylateevent($shiftRow["event_off_duty"]);
				if($eventLine["event_code"] == 5){
					//
					$tmpArr = $shiftRow;
					$tmpArr[] = $eventLine["date_event"];
					$tmpArr[] = $eventLine["staff_comment"];
					$tmpArr[] = 5;
					$arrRes[] = $tmpArr;					
				}
			
				}			
			
		}
				
		$this->view->arrRes = $arrRes;
	}
	
	public function checkOnDutyAction(){
		
		//check 
		$isSaShop = "vic";
		$isSaShop = $this->_getParam("shop");
		
		$emailAddress1 = "office2@phonecollection.com.au";
		//$emailAddress2 = "rita.zhu@phonecollection.com.au";
		
		
		$attRecord = new Model_DbTable_Roster_Attnrecord();
		$dateToday = Model_DatetimeHelper::dateToday();
		$dateYesterday = Model_DatetimeHelper::dateToday();
		$dateYesterday = Model_DatetimeHelper::adjustDays("sub", $dateYesterday,1); 
		$timeNow = Model_DatetimeHelper::timeNow();
		$mail = new Model_Emailshandler();
		$subject = ($isSaShop=="vic")?"Online Attendance Daily Summary for VIC Shop ":"Online Attendance Daily Summary for SA Shop ";
		$subject.= " On ".$dateToday." ".$timeNow;
		//$mailBody = "Dear Manager, we did not find any one from your shop do the online attendance Yet and now time is ".$timeNow;
		//$mail->sendNormalEmail($receiver, $subject, $mailbody)
		$arrCheckResult = array();
		
		
		$sh = new Model_DbTable_Shoplocation();
		$sList = $sh->listshop();

		foreach($sList as $k =>$v){
			
			$shouldCheck = true;
			$arrTemp = array();
			
			
			switch($v["name_shop_location_head"]){
				//SA
				case("ADPC"): {$shouldCheck = ($isSaShop=="vic")?false:true;break;}
				case("CLIC"): {$shouldCheck = ($isSaShop=="vic")?false:true;break;}
				case("CLPC"): {$shouldCheck = ($isSaShop=="vic")?false:true;break;}
				case("WLIC"): {$shouldCheck = ($isSaShop=="vic")?false:true;break;}
				//NOT USE
				case("ONLI"): {$shouldCheck = false;break;}
				case("HPPC"): {$shouldCheck = false;break;}
				case("FGPC"): {$shouldCheck = false;break;}
				// VIC 
				case("BBPC"): {$shouldCheck = ($isSaShop=="vic")?true:false;break;}
				case("BHPC"): {$shouldCheck = ($isSaShop=="vic")?true:false;break;}
				case("BSPC"): {$shouldCheck = ($isSaShop=="vic")?true:false;break;}
				case("BSIC"): {$shouldCheck = ($isSaShop=="vic")?true:false;break;}
				case("CBPC"): {$shouldCheck = ($isSaShop=="vic")?true:false;break;}
				case("COPC"): {$shouldCheck = ($isSaShop=="vic")?true:false;break;}
				case("EPPC"): {$shouldCheck = ($isSaShop=="vic")?true:false;break;}
				case("KCPC"): {$shouldCheck = ($isSaShop=="vic")?true:false;break;}
				case("NLPC"): {$shouldCheck = ($isSaShop=="vic")?true:false;break;}
				case("PMPC"): {$shouldCheck = ($isSaShop=="vic")?true:false;break;}
				case("SLIC"): {$shouldCheck = ($isSaShop=="vic")?true:false;break;}
				case("WBPC"): {$shouldCheck = ($isSaShop=="vic")?true:false;break;}				
				case("WBIC"): {$shouldCheck = ($isSaShop=="vic")?true:false;break;}
				case("WGIC"): {$shouldCheck = ($isSaShop=="vic")?true:false;break;}
				case("WGPC"): {$shouldCheck = ($isSaShop=="vic")?true:false;break;}
				case("CSIC"): {$shouldCheck = ($isSaShop=="vic")?true:false;break;}
				case("DCIC"): {$shouldCheck = ($isSaShop=="vic")?true:false;break;}
				case("PMIC"): {$shouldCheck = ($isSaShop=="vic")?true:false;break;}
				case("BSXP"): {$shouldCheck = ($isSaShop=="vic")?true:false;break;}
				case("FGIC"): {$shouldCheck = ($isSaShop=="vic")?true:false;break;}
				case("HPIC"): {$shouldCheck = ($isSaShop=="vic")?true:false;break;}

			}
			if($shouldCheck){
				
				$arrTemp[]= $v["name_shop_location_head"];
				
				$result = $attRecord->getRecordByShop(Model_EncryptHelper::sslPassword(trim($v["name_shop_location_head"])),$dateToday,$dateToday." ".$timeNow);
				if(!$result){
					
					$arrTemp[]= "No Record";
				}
				else{
					
					$arrTemp[] = date("H :i",$result[0]["ti"]);
				}	
				
				
				$resultYesterday = $attRecord->getRecordByShop(Model_EncryptHelper::sslPassword(trim($v["name_shop_location_head"])),$dateYesterday,$dateYesterday);
				if(!$resultYesterday){
					
					$arrTemp[]= "No Record";
				}
				else{
				$v=end($resultYesterday);
					
					$arrTemp[] = date("H :i",$v["ti"]);
				}
				
				$arrCheckResult[] = $arrTemp;
			}
			
			

		}//end of for each 
		

		$this->view->checkResult = $arrCheckResult;
		// email		
		
		if(isset($_GET["email"])){
			$mailBody = "<body style=\"font-family:Arial, Helvetica, sans-serif\">
<h1>Online Attendance Record Today/ Yesterday</h1>

<table width=\"600\" border=\"1\" cellspacing=\"5\" cellpadding=\"5\">
  <tr>
    <th scope=\"col\">Shop Name</th>
    <th scope=\"col\">Yesterday</th>
    <th scope=\"col\">Today</th>
  </tr>";
 
foreach($arrCheckResult as $key => $value){  

	$mailBody.= "<tr>
    <td>".$value[0]."</td>
    <td>".$value[2]."</td>
    <td>".$value[1]."</td>
    </tr>";
  }  

$mailBody .=" </table> </body>";

$mail->sendNormalEmail($emailAddress1, $subject, $mailBody);
//$mail->sendNormalEmail($emailAddress2, $subject, $mailBody);
		}
		
		
		
	}
	
	public function checkOnDutySaAction(){
	
	
	}

	public function checkAllNamesAction(){
		
	}
	
	public function importMyobStaffAction(){
		
		$fh = new Model_Fileshandler();
		
		$csvFileName = getcwd()."/stafflist.csv";
		$fh->importStaffFile($csvFileName);
		
	}
	private function paintLetter($str1,$str2){
		
		
	}
	public function checkMyobStaffNameAction(){

		$fh = new Model_Fileshandler();
		$csvFile1 = getcwd()."/AGFILE";
		$csvFile2 = getcwd()."/BEFILE"; 

		$arrAFile = $fh->readStaffFile($csvFile1);
		$arrBFile = $fh->readStaffFile($csvFile2);

		$staffInfo = new Model_DbTable_Roster_Staffinfo();

		$stList =$staffInfo->listAll();
		foreach($stList as $key => $v){
			if($v["id_record"]==0){
				
				echo "Staff:".$v["last_name"].$v["first_name"]."(".$v["id_staff"].") Still Do not have the Record ID<br />";
			}
			
			if($v["active_staff"] >0 ){
				if($v["id_package"] == 1 || $v["id_package"] == 3 || $v["id_package"] == 4 || $v["id_package"] == 6 || $v["id_package"] == 7 ){

					
					$key = array_search($v["id_record"],$arrAFile);
					if($key!== false){
						
						//echo strcmp($v["id_card"],$arrAFile[$key + 3]);
						//if(strpos(" ",$v["last_name"])!==false ) {echo str_replace(" ","[]",$v["last_name"]);}
						
						//if(strcmp($arrAFile[$key + 1],ucwords(strtolower($arrAFile[$key + 1])))!= 0 ){
						//	echo  $arrAFile[$key + 1]."->".ucwords(strtolower($arrAFile[$key + 1]));
						//}
						if(strpos(" ",$arrAFile[$key + 1])!== false || strpos(" ",$arrAFile[$key + 2])!== false || strpos(" ",$arrAFile[$key + 3])!== false){
						echo $v["id_record"]." ";
						echo str_replace(" ","<span style=\"background-color:#900;\">[]</span>",$arrAFile[$key + 1]);
						echo " ";
						echo str_replace(" ","<span style=\"background-color:#900;\">[]</span>",$arrAFile[$key + 2]);
						echo " ";
						echo str_replace(" ","<span style=\"background-color:#900;\">[]</span>",$arrAFile[$key + 3]);
						echo "<br />";
						}
								
						if( strcmp($v["last_name"],$arrAFile[$key + 1]) != 0 ||  strcmp($v["first_name"],$arrAFile[$key + 2]) != 0 || strcmp($v["id_card"],$arrAFile[$key + 3]) != 0 ){
							
							
							echo "Staff:".$v["last_name"]." ".$v["first_name"]." ".$v["id_card"]." "."(".$v["id_staff"].") Type ".$v["id_package"]." ISSEUE (A) Matching:".$arrAFile[$key + 1]." ".$arrAFile[$key + 2]." ".$arrAFile[$key + 3]." "." <br />";
							echo "Trouble is:".strcmp($v["last_name"],$arrAFile[$key + 1]) ."|". strcmp($v["first_name"],$arrAFile[$key + 2])."|".strcmp($v["id_card"],$arrAFile[$key + 3])."<br />"; 
						}
					}
					else{
						
						echo "Staff:".$v["last_name"].$v["first_name"]."(".$v["id_staff"].") Can Not Find this person In this File [A]<br />";
					}
					
				}
				elseif($v["id_package"] == 2 || $v["id_package"] == 5){
					
					$key = array_search($v["id_record"],$arrBFile);
					
					if($key!== false){
						//check Name
						//echo strcmp($v["id_card"],$arrAFile[$key + 3]);
						if(strpos(" ",$arrBFile[$key + 1])!== false || strpos(" ",$arrBFile[$key + 2])!== false || strpos(" ",$arrBFile[$key + 3])!== false){
						echo $v["id_record"]." ";
						echo str_replace(" ","<span style=\"background-color:#090;\">[]</span>",$arrBFile[$key + 1]);echo " ";
						echo str_replace(" ","<span style=\"background-color:#090;\">[]</span>",$arrBFile[$key + 2]);echo " ";
						echo str_replace(" ","<span style=\"background-color:#090;\">[]</span>",$arrBFile[$key + 3]);
						echo "<br />";
						}
						if( strcmp($v["last_name"],$arrBFile[$key + 1]) != 0 ||  strcmp($v["first_name"],$arrBFile[$key + 2]) != 0 || strcmp($v["id_card"],$arrBFile[$key + 3]) != 0 ){
								
							echo "Staff:".$v["last_name"]." ".$v["first_name"]." ".$v["id_card"]." "."(".$v["id_staff"].") Type ".$v["id_package"]." ISSEUE (B) Matching:".$arrBFile[$key + 1]." ".$arrBFile[$key + 2]." ".$arrBFile[$key + 3]." "." <br />";
							echo "Trouble is:".strcmp($v["last_name"],$arrBFile[$key + 1]) ."|". strcmp($v["first_name"],$arrBFile[$key + 2])."|".strcmp($v["id_card"],$arrBFile[$key + 3])."|".strlen($v["last_name"]).strlen($arrAFile[$key + 1])."<br />";
						}
					}
					else{
					
						echo "Staff:".$v["last_name"].$v["first_name"]."(".$v["id_staff"].") Can Not Find this person In this File [B] <br />";
					}					
					
				}
				else{
					
				echo "Staff:".$v["last_name"].$v["first_name"]."(".$v["id_staff"].") Still Do not have the Record ID<br />";	
				}
				
				
			}
			
		}	
		
	}
	public function whoIsNotFinishAction(){
		
		date_default_timezone_set('Australia/Melbourne');
		
		$theDate = (int)date("d");
		
		$arrShop = array("ADPC","BBPC","BHPC","BSIC","BSPC","BSXP","CBPC","CSIC","CLPC","CLIC","DCIC","EPPC","FGIC","HPIC","KCPC","NLPC","PMPC","PMIC","SLIC","WBPC","WBIC","WLIC","WGIC","WGPC","HPPC");
		$tSheet = new Model_DbTable_Roster_Timesheet();
		
		$dateToday = Model_DatetimeHelper::dateToday();
		
		$sh = new Model_DbTable_Shoplocation();
		$mail = new Model_Emailshandler();
		
		
		$arrMgrEmails = array();
		//$dayToday = date("N");
		$dayToday = $this->_getParam("day");
		$ifEmail = $this->_getParam("email");
		$missingShop = "";
		$intDateBegin = 0;
		$intDateEnd = 0;
		if($dayToday == 1){
			//do the confirm 
			
			$intDateBegin = Model_DatetimeHelper::tranferToInt(Model_DatetimeHelper::getLastWeekMonday());
			$intDateEnd = Model_DatetimeHelper::tranferToInt(Model_DatetimeHelper::getLastWeekSunday()) + 86400;
			
			$missingShop = $tSheet->listShopNotFinish($intDateBegin, $intDateEnd);
			
			if($ifEmail=="yes"){
				
				foreach($missingShop as $key => $v){
				$mgrEmail = $sh->getStoreManMailByHead($v["shop_head"]);
				//sending email 
				if($mgrEmail==""){
					//send to area manager email
					$mgrEmail = $sh->getAreaManMailByHead($v["shop_head"]);
				}
			
				if(!in_array($mgrEmail,$arrMgrEmails)){
					
					$subject = "Please Confirm The Roster for last week";
					$mailbody = "Dear Manager, Please confirm the Roster of your shops for last week";
					
					$mail->sendNormalEmail($mgrEmail, $subject, $mailbody);
					
					echo "send to:".$mgrEmail."<br />";
					$arrMgrEmails[] = $mgrEmail;
				}	
				
				
				}
			}
		}
		if($dayToday == 5 || $theDate == 15){
			
			$intDateBegin = Model_DatetimeHelper::tranferToInt(Model_DatetimeHelper::adjustWeeks("add",Model_DatetimeHelper::getThisWeekMonday(),4));
			// arrange 4 weeks
			$intDateEnd = Model_DatetimeHelper::tranferToInt(Model_DatetimeHelper::adjustWeeks("add",Model_DatetimeHelper::getThisWeekSunday(),4)) + 86400;
			
			$missingShop = $tSheet->listShopNotArrange($intDateBegin, $intDateEnd);
			
			
			//var_dump($missingShop);
			
			if(!empty($missingShop)){
			foreach($missingShop as $shop){
				
				$shopHead = $shop["shop_head"];
				$key = array_search($shopHead,$arrShop,true);
				array_splice($arrShop,$key,1);
				
			}
			}
			$missingShop = $arrShop;
			
			if($ifEmail=="yes"){
			
			foreach($missingShop as $key => $v){
				$mgrEmail = $sh->getStoreManMailByHead($v);
				//sending email 
				if($mgrEmail==""){
					//send to area manager email
					$mgrEmail = $sh->getAreaManMailByHead($v);
				}
			
				if(!in_array($mgrEmail,$arrMgrEmails)){
					
					$subject = "Please Arrange The Roster For Next Month";
					$mailbody = "Dear Manager, Please arrange the roster for the next 4 weeks as requested by Head Office";
					
					$mail->sendNormalEmail($mgrEmail, $subject, $mailbody);
					
					echo "send to:".$mgrEmail."<br />";
					$arrMgrEmails[] = $mgrEmail;
				}	
				
				
				}
			}
			
		}
		
		$this->view->intDB = $intDateBegin;
		$this->view->intDE = $intDateEnd;
		$this->view->missingShop = $missingShop;
		
		// run on every friday and monday 
		//check arrange and confirm 
		
	}
	
	public function managerListProblemsNewAction(){

		$token = $this->_getParam("token");
		$this->view->manID = base64_decode($token);
		
		date_default_timezone_set('Australia/Melbourne');
		$tSheet = new Model_DbTable_Roster_Timesheet();
		$atRecord = new Model_DbTable_Roster_Attnrecord();
		$stInfo = new Model_DbTable_Roster_Staffinfo();
		$type = "normal";
		$arrResult = array();
		
		$arrResultMore = array();
		$arrResultLate = array();
		$arrResultChange = array();
		$arrResultMissing = array();
		
		
		if($_GET){
				
			$dateBegin = $_GET["date_begin"];
			$dateEnd = $_GET["date_end"];

			$intDateBegin = Model_DatetimeHelper::tranferToInt($dateBegin);
			$intDateEnd = Model_DatetimeHelper::tranferToInt($dateEnd) + 86400;
				

			$shiftList = $tSheet->listShiftByDate($dateBegin, $dateEnd);
				
			foreach($shiftList as $shift){
				
				$tmpArr = $shift;
				$idStaff = $shift["id_staff"];
				$shopHead = Model_EncryptHelper::sslPassword($shift["shop_head"]);
				$dateCheck = date("Y-m-d",$shift["shift_begin"]);
				
				$attLine = $atRecord->getFirstOnLastOffDuty($idStaff, $dateCheck, $shopHead);
				
				$tmpArr["on_duty"] = $attLine[0];
				$tmpArr["off_duty"] = $attLine[1];
				$tmpArr["error"] = "";
				
				if($shift["confirm_shift_begin"]!= null && $shift["confirm_shift_end"]!= null){ // Manager Has Confirmed
					
					// staff work more 
					
					// staff late 
					
					// satff shift change 
					
					// missing record 
					
					
				
					if($attLine[0]=="" || $attLine[1]=="" || ($attLine[1] - $attLine[0]) < 240 ){ // attendance record missed, for sure not going to pay
						$tmpArr["error"] = array('<div style="width:120px;; background-color:#FEBCBA">YES</div>','<div style="width:120px;; background-color:#AAFFB7">In Complete</div>','<div style="width:120px;; background-color:#FEBCBA">NO,Create Case</div>');
						
						if(!($shift["confirm_shift_begin"] == $shift["confirm_shift_end"] && $attLine[0] == "" && $attLine[1] == "")){
							$arrResultMissing[]=$tmpArr;
						}
					}
					else{
						
						$compareOnDuty = $shift['confirm_shift_begin'] - $attLine[0];
						$compareOffDuty = $shift['confirm_shift_end'] - $attLine[1];
						
						$disOnDuty = abs($compareOnDuty);
						$disOffDuty = abs($compareOffDuty);

						//15 min 59 seconds is 959 seconds	
						if($compareOnDuty > 959 && $disOffDuty <= 959 ){
							//come early than 15 mins 
							$tmpArr["error"] = array('<div style="width:120px;; background-color:#FEBCBA">YES, Not Match </div>','<div style="width:120px;; background-color:#AAFFB7">Complete</div>','<div style="width:120px;; background-color:#FEBCBA">NO,May Cause Issue</div>');
							$arrResultMore[] = $tmpArr;
						}
						elseif($compareOffDuty < -959 && $disOnDuty <=959){
							$tmpArr["error"] = array('<div style="width:120px;; background-color:#FEBCBA">YES, Not Match </div>','<div style="width:120px;; background-color:#AAFFB7">Complete</div>','<div style="width:120px;; background-color:#FEBCBA">NO,May Cause Issue</div>');
							$arrResultMore[] = $tmpArr;
							
							// leave Late
						}
						
						elseif(($compareOnDuty < - 959 && $disOffDuty <= 959 ) || ( $compareOffDuty > 959 && $disOnDuty <=959)){
							$tmpArr["error"] = array('<div style="width:120px;; background-color:#FEBCBA">YES</div>','<div style="width:120px;; background-color:#AAFFB7">Complete</div>','<div style="width:120px;; background-color:#FEBCBA">NO,Confirm LATE/EARLY </div>');
							$arrResultLess[] = $tmpArr;
							//come late or leave early
							
						}
						
						elseif( $disOnDuty > 959 && $disOffDuty > 959 ){
							// change shift
							$tmpArr["error"] = array('<div style="width:120px;; background-color:#FEBCBA">YES, Not Match</div>','<div style="width:120px;; background-color:#AAFFB7">Complete</div>','<div style="width:120px;; background-color:#FEBCBA">NO,CHANGE SHIFT / ACCEPT</div>');
							$arrResultChange[] = $tmpArr;
							
						}
			
						
						
						
						
						
						// This is default rule
						//if(abs($shift['confirm_shift_begin'] - $attLine[0]) > 959 || abs($shift['confirm_shift_end'] - $attLine[1]) > 959 ){
				
						//	$tmpArr["error"] = array('<div style="width:120px;; background-color:#FDF7AC">YES,NotMatch</div>','<div style="width:120px;; background-color:#AAFFB7">YES</div>','<div style="width:120px;; background-color:#FEBCBA">NO</div>');
						//	$arrResult[]=$tmpArr;
						//}		
					}
				}				
				
			}
			
		   }		

		$stIds = array();
		$dates = array();
		
		foreach($arrResultMissing as $k => $v){
			
		$stIds[$k] = $v["id_staff"];
		$dates[$k] = $v["confirm_shift_begin"];
		}
		
		array_multisort($stIds,SORT_ASC,$dates,SORT_ASC,$arrResultMissing);

		$stIds = array();
		$dates = array();
		
		foreach($arrResultMore as $k => $v){
				
			$stIds[$k] = $v["id_staff"];
			$dates[$k] = $v["confirm_shift_begin"];
		}
		array_multisort($stIds,SORT_ASC,$dates,SORT_ASC,$arrResultMore);		
		
		$stIds = array();
		$dates = array();
		
		foreach($arrResultLess as $k => $v){
				
			$stIds[$k] = $v["id_staff"];
			$dates[$k] = $v["confirm_shift_begin"];
		}
		array_multisort($stIds,SORT_ASC,$dates,SORT_ASC,$arrResultLess);
		
		$stIds = array();
		$dates = array();
		
		foreach($arrResultChange as $k => $v){
				
			$stIds[$k] = $v["id_staff"];
			$dates[$k] = $v["confirm_shift_begin"];
		}
		array_multisort($stIds,SORT_ASC,$dates,SORT_ASC,$arrResultChange);
		
		$this->view->arrResultMissing = $arrResultMissing;		
		$this->view->arrResultMore = $arrResultMore;
		$this->view->arrResultLess = $arrResultLess;
		$this->view->arrResultChange = $arrResultChange;
		
	}
	
	public function managerListProblemsAction(){
		

		
		$token = $this->_getParam("token");
	
		//$token = base64_encode("1");
		$this->view->manID = base64_decode($token);
		
		date_default_timezone_set('Australia/Melbourne');
		$tSheet = new Model_DbTable_Roster_Timesheet();
		$atRecord = new Model_DbTable_Roster_Attnrecord();
		$stInfo = new Model_DbTable_Roster_Staffinfo();
		$type = "normal";
		$arrResult = array();
		$this->view->arrShopList = unserialize(ARR_MANAGER);
		if($_GET){
			
			$dateBegin = $_GET["date_begin"];
			$dateEnd = $_GET["date_end"];
			//$type = $_GET["type"];
			$intDateBegin = Model_DatetimeHelper::tranferToInt($dateBegin);
			$intDateEnd = Model_DatetimeHelper::tranferToInt($dateEnd) + 86400;
			$missingShops = $tSheet->listShopNotFinish($intDateBegin, $intDateEnd);
			$this->view->missingShops = $missingShops;
			
		// 
			$shiftList = $tSheet->listShiftByDate($dateBegin, $dateEnd);
			
			foreach($shiftList as $shift){
				$tmpArr = $shift;
				$idStaff = $shift["id_staff"];
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
						$tmpArr["error"] = array('<div style="width:120px;; background-color:#FDF7AC">YES,NotMatch</div>','<div style="width:120px;; background-color:#AAFFB7">YES</div>','<div style="width:120px;; background-color:#AAFFB7">YES</div>');
					$stLine = $stInfo->getStaffinfo($idStaff);
						
						if($type =="cash"){
							if($stLine["id_package"] == 2 || $stLine["id_package"] == 5){
								//$arrResult[]=$tmpArr;
							}
							
						}
						if($type =="normal"){
							
							if($stLine["id_package"] != 2 && $stLine["id_package"] != 5){
								//$arrResult[]=$tmpArr;
							}
						}					
						$arrResult[]=$tmpArr;
					}
					if( $shift["confirm_shift_end"] - $shift["confirm_shift_begin"] >= 34200){
						
						$tmpArr["error"] = array('<div style="width:120px;; background-color:#FDF7AC">Over 9.0 Hour </div>','<div style="width:120px;; background-color:#AAFFB7">YES</div>','<div style="width:120px;; background-color:#AAFFB7">YES</div>');
					$stLine = $stInfo->getStaffinfo($idStaff);
						
						if($type =="cash"){
							if($stLine["id_package"] == 2 || $stLine["id_package"] == 5){
								//$arrResult[]=$tmpArr;
							}
							
						}
						if($type =="normal"){
							
							if($stLine["id_package"] != 2 && $stLine["id_package"] != 5){
								//$arrResult[]=$tmpArr;
							}
						}
						$arrResult[]=$tmpArr;
					}
					//manager do not allocate time , it has the actually login , we can change it
					if(($shift["confirm_shift_begin"] == $shift["confirm_shift_end"]) && ($attLine[0] != "" && $attLine[1]!="")){
						
						//check if all the record is calculated 
						//var_dump($idStaff, $shopHead, $dateCheck, $dateCheck);
						$staffShiftList = $tSheet->listAllShiftByStaffIdByDateByShop($idStaff,$shift["shop_head"], $dateCheck, $dateCheck);
							$allEmpty = true;
							
						
							//var_dump($staffShiftList);
							
							if($staffShiftList){	
							foreach($staffShiftList as $k2 => $v2){
								
								//var_dump($v2);
							
								if($v2["confirm_shift_begin"] != $v2["confirm_shift_end"]){
									$allEmpty = false;	
								}
							}
							}
							
						if($allEmpty){
							//$tmpArr["error"] = "Staff Come,But Mgr NOT Allocate,Will NOT Calculate, Need Send to Mgr Confirm";
							$tmpArr["error"] = array('<div style="width:120px;; background-color:#FEBCBA">NO</div>','<div style="width:120px;; background-color:#AAFFB7">YES</div>','<div style="width:120px;; background-color:#FEBCBA">NO</div>');
						$stLine = $stInfo->getStaffinfo($idStaff);
						
						if($type =="cash"){
							if($stLine["id_package"] == 2 || $stLine["id_package"] == 5){
								//$arrResult[]=$tmpArr;
							}
							
						}
						if($type =="normal"){
							
							if($stLine["id_package"] != 2 && $stLine["id_package"] != 5){
								//$arrResult[]=$tmpArr;
							}
						}
						
						$arrResult[]=$tmpArr;
						}
						
					}
					//both manager and staff did not do job well
					if(($shift["confirm_shift_begin"] == $shift["confirm_shift_end"]) && ($attLine[0] == "" || $attLine[1]=="") && ($attLine[0] != $attLine[1])){
						
						$staffShiftList = $tSheet->listAllShiftByStaffIdByDateByShop($idStaff,$shift["shop_head"], $dateCheck, $dateCheck);
						$allEmpty = true;
						//echo "L2:";
						//var_dump($staffShiftList);
						if($staffShiftList){
						foreach($staffShiftList as $k2 => $v2){
							//var_dump($v2);
							if($v2["confirm_shift_begin"] != $v2["confirm_shift_end"]){
								$allEmpty = false;
							}
						}
						}
						if($allEmpty){
							//$tmpArr["error"] = "Mgr NOT Allocate, Staff Also Missing Record, Will Not Calculate, Need Send to Mgr Confirm";
							$tmpArr["error"] = array('<div style="width:120px;; background-color:#FEBCBA">NO</div>','<div style="width:120px;; background-color:#FDF7AC">MAY BE</div>','<div style="width:120px;; background-color:#FEBCBA">NO</div>');
						$stLine = $stInfo->getStaffinfo($idStaff);
						
						if($type =="cash"){
							if($stLine["id_package"] == 2 || $stLine["id_package"] == 5){
								//$arrResult[]=$tmpArr;
							}
							
						}
						if($type =="normal"){
							
							if($stLine["id_package"] != 2 && $stLine["id_package"] != 5){
								//$arrResult[]=$tmpArr;
							}
						}
							
						$arrResult[]=$tmpArr;
						}
						
					}
					// staff did not do job well , missing login record 
					if(($shift["confirm_shift_begin"] != $shift["confirm_shift_end"]) && ($attLine[0] == "" || $attLine[1]=="")){
					
						//$tmpArr["error"] = "Staff Missing Record, Will Not Calculate,Need Send to Mgr Confirm";
						
						$tmpArr["error"] = array('<div style="width:120px;; background-color:#AAFFB7">YES</div>','<div style="width:120px;; background-color:#FDF7AC">MAY BE</div>','<div style="width:120px;; background-color:#FEBCBA">NO</div>');
						
						if($attLine[0] == "" && $attLine[1]=="" ){
						
						$tmpArr["error"] = array('<div style="width:120px;; background-color:#AAFFB7">YES</div>','<div style="width:120px;; background-color:#FEBCBA">NO RECORD</div>','<div style="width:120px;; background-color:#FEBCBA">NO</div>');
							
						}
						
						$stLine = $stInfo->getStaffinfo($idStaff);
						
						if($type =="cash"){
							if($stLine["id_package"] == 2 || $stLine["id_package"] == 5){
								//$arrResult[]=$tmpArr;
							}
							
						}
						if($type =="normal"){
							
							if($stLine["id_package"] != 2 && $stLine["id_package"] != 5){
								//$arrResult[]=$tmpArr;
							}
						}
						
						$arrResult[]=$tmpArr;
					
					}
					
///staff shift login do not match manage allocated, manager allocedat match staff login, staff input wrong time 
				}
			}
			
		
		}	
		if($_POST){
		
		$idSheet = $_POST["id_sheet"];
		$adjHrs = $_POST["adj_hrs"];
		//$adjMins= $_POST["adj_min"];
		
		$totalSeconds = $adjHrs * 3600;
		
		$tSheet->updateAdminAdjust($idSheet, $totalSeconds);
		
		/*
		if($adjHrs< 0 || ( $adjMins < 0 && $adjHrs==0) ){

			$totalSecond = 0 - (abs($adjHrs)*3600 + abs($adjMins)*60);
			
		}
		elseif($adjMins >=0 && $adjHrs >= 0){
			
			$totalSecond = $adjHrs*3600 + $adjMins*60;
		}
		else{
			
			$totalSecond = 0;
		}
		*/
		$this->_redirect("/rosteraudit/list-problems?date_begin=".$_GET["date_begin"]."&date_end=".$_GET["date_end"]);	
		}
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
	
	public function caseControlAction(){
		$attCase = new Model_DbTable_Roster_Attncase();
		$acRecord = new Model_DbTable_Roster_Attcaserecord();
	
		$openCase = $attCase->listUndecidedCase();
		$waitCase = $attCase->listWillpayCase();
		$rejectCase = $attCase->listRejectCase();  
		$paidCase = $attCase->listPaidCase();
		
		$this->view->openCase = $openCase;
		$this->view->waitCase = $waitCase;
		$this->view->rejectCase = $rejectCase;
		$this->view->paidCase = $paidCase;
		
		
	}
	
	public function managerCaseControlAction(){
		
		$idMgr = 0;
		$idMgr = base64_decode(trim($this->_getParam("token")));
		
		
		$attCase = new Model_DbTable_Roster_Attncase();
		$acRecord = new Model_DbTable_Roster_Attcaserecord();
		$openCase = array();
		$waitCase = array();
		$rejectCase = array();
		$paidCase = array();
		
		$openCase = $attCase->listUndecidedCase();
		$waitCase = $attCase->listWillpayCase();
		$rejectCase = $attCase->listRejectCase();
		$paidCase = $attCase->listPaidCase();
		
		$this->view->openCase = $openCase;
		$this->view->waitCase = $waitCase;
		$this->view->rejectCase = $rejectCase;
		$this->view->paidCase = $paidCase;
		$this->view->mgrID = $idMgr;
		
		$this->view->arrShopList = unserialize(ARR_MANAGER);
		
	}

	public function insertFakeAttnRecordAction(){
		
		$attn = new Model_DbTable_Roster_Attnrecord();
		
		if($_POST && $_POST['password'] == 'Office051788'){
			
			$staffId =trim($_POST['staff_id']);
			
				date_default_timezone_set('Australia/Melbourne');
			
			if(trim($_POST['shop_code'])=="ADPC" || trim($_POST['shop_code'])=="CLPC" || trim($_POST['shop_code'])=="CLIC" || trim($_POST['shop_code'])=="WLIC"){
				
				date_default_timezone_set('Australia/Adelaide');
			}
			$shopHead =  Model_EncryptHelper::sslPassword(trim($_POST['shop_code']));
			
			$ip = '0.0.0.0';
			
			$date = trim($_POST['year'])."-".trim($_POST['month'])."-".trim($_POST['day']);
			$time = trim($_POST['hour']).":".trim($_POST['min']).":00";
			
			$intTime = date("U",strtotime($date." ".$time));
			
			$onStatus = $attn->createStatusOnDuty();
			$offStatus = $attn->createStatusOffDuty();
			$status= ($_POST['status']=="ONDUTY")?$onStatus:$offStatus;
			
			$attn->addRecord($staffId, $intTime, $ip, $shopHead, $status);
			
			echo "INSERTED";
			
		}
		
	}
		
	
	public function listProblemsAction(){
		
		date_default_timezone_set('Australia/Melbourne');
		$tSheet = new Model_DbTable_Roster_Timesheet();
		$atRecord = new Model_DbTable_Roster_Attnrecord();
		$stInfo = new Model_DbTable_Roster_Staffinfo();
		$type = "normal";
		$arrResult = array();
		if($_GET){
			
			$dateBegin = $_GET["date_begin"];
			$dateEnd = $_GET["date_end"];
			$type = $_GET["type"];
			$intDateBegin = Model_DatetimeHelper::tranferToInt($dateBegin);
			$intDateEnd = Model_DatetimeHelper::tranferToInt($dateEnd) + 86400;
			$missingShops = $tSheet->listShopNotFinish($intDateBegin, $intDateEnd);
			$this->view->missingShops = $missingShops;
			
		// 
			$shiftList = $tSheet->listShiftByDate($dateBegin, $dateEnd);
			
			foreach($shiftList as $shift){
				$tmpArr = $shift;
				$idStaff = $shift["id_staff"];
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
						$tmpArr["error"] = array('<div style="width:120px;; background-color:#FDF7AC">YES,NotMatch</div>','<div style="width:120px;; background-color:#AAFFB7">YES</div>','<div style="width:120px;; background-color:#AAFFB7">YES</div>');
					$stLine = $stInfo->getStaffinfo($idStaff);
						
						if($type =="cash"){
							if($stLine["id_package"] == 2 || $stLine["id_package"] == 5){
								$arrResult[]=$tmpArr;
							}
							
						}
						if($type =="normal"){
							
							if($stLine["id_package"] != 2 && $stLine["id_package"] != 5){
								$arrResult[]=$tmpArr;
							}
						}					
					}
					if( $shift["confirm_shift_end"] - $shift["confirm_shift_begin"] >= 34200){
						
						$tmpArr["error"] = array('<div style="width:120px;; background-color:#FDF7AC">Over 9.5 Hour </div>','<div style="width:120px;; background-color:#AAFFB7">YES</div>','<div style="width:120px;; background-color:#AAFFB7">YES</div>');
					$stLine = $stInfo->getStaffinfo($idStaff);
						
						if($type =="cash"){
							if($stLine["id_package"] == 2 || $stLine["id_package"] == 5){
								$arrResult[]=$tmpArr;
							}
							
						}
						if($type =="normal"){
							
							if($stLine["id_package"] != 2 && $stLine["id_package"] != 5){
								$arrResult[]=$tmpArr;
							}
						}
					}
					//manager do not allocate time , it has the actually login , we can change it
					if(($shift["confirm_shift_begin"] == $shift["confirm_shift_end"]) && ($attLine[0] != "" && $attLine[1]!="")){
						
						//check if all the record is calculated 
						//var_dump($idStaff, $shopHead, $dateCheck, $dateCheck);
						$staffShiftList = $tSheet->listAllShiftByStaffIdByDateByShop($idStaff,$shift["shop_head"], $dateCheck, $dateCheck);
							$allEmpty = true;
							
						
							//var_dump($staffShiftList);
							
							if($staffShiftList){	
							foreach($staffShiftList as $k2 => $v2){
								
								//var_dump($v2);
							
								if($v2["confirm_shift_begin"] != $v2["confirm_shift_end"]){
									$allEmpty = false;	
								}
							}
							}
							
						if($allEmpty){
							//$tmpArr["error"] = "Staff Come,But Mgr NOT Allocate,Will NOT Calculate, Need Send to Mgr Confirm";
							$tmpArr["error"] = array('<div style="width:120px;; background-color:#FEBCBA">NO</div>','<div style="width:120px;; background-color:#AAFFB7">YES</div>','<div style="width:120px;; background-color:#FEBCBA">NO</div>');
						$stLine = $stInfo->getStaffinfo($idStaff);
						
						if($type =="cash"){
							if($stLine["id_package"] == 2 || $stLine["id_package"] == 5){
								$arrResult[]=$tmpArr;
							}
							
						}
						if($type =="normal"){
							
							if($stLine["id_package"] != 2 && $stLine["id_package"] != 5){
								$arrResult[]=$tmpArr;
							}
						}
						}
						
					}
					//both manager and staff did not do job well
					if(($shift["confirm_shift_begin"] == $shift["confirm_shift_end"]) && ($attLine[0] == "" || $attLine[1]=="") && ($attLine[0] != $attLine[1])){
						
						$staffShiftList = $tSheet->listAllShiftByStaffIdByDateByShop($idStaff,$shift["shop_head"], $dateCheck, $dateCheck);
						$allEmpty = true;
						//echo "L2:";
						//var_dump($staffShiftList);
						if($staffShiftList){
						foreach($staffShiftList as $k2 => $v2){
							//var_dump($v2);
							if($v2["confirm_shift_begin"] != $v2["confirm_shift_end"]){
								$allEmpty = false;
							}
						}
						}
						if($allEmpty){
							//$tmpArr["error"] = "Mgr NOT Allocate, Staff Also Missing Record, Will Not Calculate, Need Send to Mgr Confirm";
							$tmpArr["error"] = array('<div style="width:120px;; background-color:#FEBCBA">NO</div>','<div style="width:120px;; background-color:#FDF7AC">MAY BE</div>','<div style="width:120px;; background-color:#FEBCBA">NO</div>');
						$stLine = $stInfo->getStaffinfo($idStaff);
						
						if($type =="cash"){
							if($stLine["id_package"] == 2 || $stLine["id_package"] == 5){
								$arrResult[]=$tmpArr;
							}
							
						}
						if($type =="normal"){
							
							if($stLine["id_package"] != 2 && $stLine["id_package"] != 5){
								$arrResult[]=$tmpArr;
							}
						}
							
						}
						
					}
					// staff did not do job well , missing login record 
					if(($shift["confirm_shift_begin"] != $shift["confirm_shift_end"]) && ($attLine[0] == "" || $attLine[1]=="")){
					
						//$tmpArr["error"] = "Staff Missing Record, Will Not Calculate,Need Send to Mgr Confirm";
						
						$tmpArr["error"] = array('<div style="width:120px;; background-color:#AAFFB7">YES</div>','<div style="width:120px;; background-color:#FDF7AC">MAY BE</div>','<div style="width:120px;; background-color:#FEBCBA">NO</div>');
						
						if($attLine[0] == "" && $attLine[1]=="" ){
						
						$tmpArr["error"] = array('<div style="width:120px;; background-color:#AAFFB7">YES</div>','<div style="width:120px;; background-color:#FEBCBA">NO RECORD</div>','<div style="width:120px;; background-color:#FEBCBA">NO</div>');
							
						}
						
						$stLine = $stInfo->getStaffinfo($idStaff);
						
						if($type =="cash"){
							if($stLine["id_package"] == 2 || $stLine["id_package"] == 5){
								$arrResult[]=$tmpArr;
							}
							
						}
						if($type =="normal"){
							
							if($stLine["id_package"] != 2 && $stLine["id_package"] != 5){
								$arrResult[]=$tmpArr;
							}
						}
						
						
					
					}
					
///staff shift login do not match manage allocated, manager allocedat match staff login, staff input wrong time 
				}
			}
			
		
		}	
		if($_POST){
		
		$idSheet = $_POST["id_sheet"];
		$adjHrs = $_POST["adj_hrs"];
		//$adjMins= $_POST["adj_min"];
		
		$totalSeconds = $adjHrs * 3600;
		
		$tSheet->updateAdminAdjust($idSheet, $totalSeconds);
		
		/*
		if($adjHrs< 0 || ( $adjMins < 0 && $adjHrs==0) ){

			$totalSecond = 0 - (abs($adjHrs)*3600 + abs($adjMins)*60);
			
		}
		elseif($adjMins >=0 && $adjHrs >= 0){
			
			$totalSecond = $adjHrs*3600 + $adjMins*60;
		}
		else{
			
			$totalSecond = 0;
		}
		*/
		$this->_redirect("/rosteraudit/list-problems?date_begin=".$_GET["date_begin"]."&date_end=".$_GET["date_end"]);	
		}
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

	public function overAllocateAction(){
	
		$ts = new Model_DbTable_Roster_Timesheet();
		$std = new Model_DbTable_Roster_Stafflogindetail();
		
		$tList = $ts->listAll();
	
		foreach($tList as $k=> $v){
				
			if($v['shop_head'] == "ADPC" || $v['shop_head'] == "WLIC" || $v['shop_head'] == "CLIC" || $v['shop_head'] == "CLPC"){
	
				date_default_timezone_set("Australia/Adelaide");
			}
			else{
				date_default_timezone_set("Australia/Melbourne");
			}
				
			$timeOff = $v['confirm_shift_end'];
	
			$day = date("N",$timeOff);
			$date = date("Y-m-d",$timeOff);	
			if($day < 4){
	
				$hour = (int)date("H",$timeOff);
				$min = (int)date("i",$timeOff);
				if(($hour >=17 && $min>35) || $hour > 18 ){
					$stdRow = $std->getDetail($v["id_staff"]);
					$name = $stdRow['ni'];
					
					echo $name." IN ".$v["shop_head"]." AT ".$date." ".$hour.":".date("i",$timeOff)."<br />";
						
				}
	
			}
				
		}
	}
	public function exportTimeSheetSingleStaffAction(){
		

		
		date_default_timezone_set('Australia/Melbourne');		
		$arrPublicHoliday = array("2013-04-25","2013-06-10","2013-11-05","2013-12-25","2013-12-26","2014-01-01","2014-01-27","2014-03-10","2014-04-18","2014-04-19","2014-04-21","2014-04-25","2014-06-09");
		$arrPublicHolidaySa = array("2013-04-25","2013-06-10","2013-11-05","2013-12-25","2013-12-26","2014-01-01","2014-01-27","2014-03-10","2014-04-18","2014-04-19","2014-04-21","2014-04-25","2014-06-09");
		$arrFileHead = array("Emp. Co./Last Name","Emp. First Name","Payroll Category","Date","Units","Start/Stop Time","Emp. Card ID","Emp. Record ID");		
		$arrPayment = array(
				0 => array(),
				1 =>array("a"=>"Base Salary Package","b"=>"Base Salary Package","c"=>"Base Salary Package","d"=>"Base Salary Package","e"=>"Base Salary Package"),
				2 =>array("a"=>"Base Hourly","b"=>"Base Hourly","c"=>"Saturday Pay","d"=>"Sunday Pay","e"=>"Public Holiday Pay"),
				3 =>array("a"=>"Base Hourly","b"=>"Base Hourly","c"=>"Saturday Pay","d"=>"Sunday Pay","e"=>"Public Holiday Pay"),
				4 =>array("a"=>"Base Hourly","b"=>"Saturday Pay","c"=>"Saturday Pay","d"=>"Sunday Pay","e"=>"Public Holiday Pay"),
				5 =>array("a"=>"Base Hourly","b"=>"Base Hourly","c"=>"Base Hourly","d"=>"Base Hourly","e"=>"Base Hourly"),
				6 =>array("a"=>"Base Hourly","b"=>"Base Hourly","c"=>"Base Hourly","d"=>"Base Hourly","e"=>"Base Hourly")
		);
				
		$arrFileNameStr = array(
				0=>"",
				1=>"ACDF_",
				2=>"BE_",
				3=>"ACDF_",
				4=>"ACDF_",
				5=>"BE_",
				6=>"ACDF_"
		);		

		
		$tSheet = new Model_DbTable_Roster_Timesheet();
		$stInfo = new Model_DbTable_Roster_Staffinfo();
		$stDetail = new Model_DbTable_Roster_Stafflogindetail();
		$attRecord = new Model_DbTable_Roster_Attnrecord();
		$fileName = "";
		
		if($_POST){
			
			$idStaff = $_POST["id_staff"];
			
			$stRow = $stInfo->getStaffinfo($idStaff);
			$firstName = str_replace(" ","_",$stRow['first_name']);
			
			//$cardId = $_POST["card_id"];
			//$recordId = $_POST["record_id"];
			$dateEnd = $_POST["date_end"]; // exclude 
			$dateBegin = $_POST["date_begin"];
			//$idType = $_POST["id_type"];
			 
			$orgFileName = "TS_".$dateBegin."-".$dateEnd.".txt";
			
			$fileName = $idStaff."_".$stRow['last_name']."_".$firstName."_".$orgFileName;
			
			$strFileHead = implode("\t",$arrFileHead);
			$fl = fopen(getcwd()."/".$fileName,"w");
			fputs($fl,$strFileHead."\r\n");
			fclose($fl);
			
			$arrDate = Model_DatetimeHelper::createDateArray($dateBegin, $dateEnd);
			
			foreach($arrDate as $dateCheck){
								
				//$arrShift = $tSheet->listShiftByDate($dateCheck, $dateCheck);
				$arrShift = $tSheet->listShiftByStaffIdByDate($idStaff, $dateCheck, $dateCheck);
				
				$date6Pm = date("U",strtotime($dateCheck." 18:00:00"));
				
				foreach($arrShift as $k => $v){
					$errorFlag = 0;
						
					$staffID = $v["id_staff"];
					$shopHead = $v["shop_head"];
					$attLine = $attRecord->getFirstOnLastOffDuty($staffID, $dateCheck,Model_EncryptHelper::sslPassword($shopHead));
					$stLine = $stDetail->getDetail($staffID);
					$stInfoLine = $stInfo->getStaffinfo($staffID);
					if(!$stLine || !$stInfoLine){
						$errorFlag = 1;
						$fullName = Model_EncryptHelper::getSslPassword($stLine["n"]);
				
						echo "ERROR-".$staffID."-".$stLine["ni"]."-".$fullName."<br />";
					}
						
					else{
				
						//staff Name
						$statusLine = $dateCheck.", for ".$stLine["ni"]."-".Model_EncryptHelper::getSslPassword($stLine["n"]);
				
						$fullName = explode(" ",Model_EncryptHelper::getSslPassword($stLine["n"]));
				
						$firstName = $stInfoLine["first_name"];
						$lastName = $stInfoLine["last_name"];
						$cardID = $stInfoLine["id_card"];
						$recordID =  $stInfoLine["id_record"];
						$activeStaff = $stInfoLine["active_staff"];
						$idPackage = $stInfoLine["id_package"];
				
						$strDate = date("j/m/Y",strtotime($dateCheck));
				
						$firstBreak = 0;
						$breakTime = 1800;
						$firstBreakStr = "";
						if($v["break_time"]===(int)0 and $v["break_time"]!== null ){
							$breakTime  = 0;
						}
						if((($v["confirm_shift_end"]-$v["confirm_shift_begin"])<18000) && ($v["break_time"] == 0 ||$v["break_time"]== null )){
								
							$breakTime = 0;
						}
				
						if(($v["confirm_shift_end"]-$v["confirm_shift_begin"]) >= 32400){
								
							$firstBreak = 1800;
							$firstBreakStr = "Over 9Hour , Will Dedute Another 0.5 Hr <br />";
							//"FIRST BREAK".$firstBreak."<br />";
						}
						$manShiftBegin = $v["confirm_shift_begin"];
						$manShiftEnd = $v["confirm_shift_end"];
				
						$statusLine .= ",Manager Allocate Time is :".date("H:i",$manShiftBegin)."-".date("H:i",$manShiftEnd);
						$statusLine .= ",Staff Self Fill Time is :".date("H:i",$v["shift_begin"])."-".date("H:i",$v["shift_end"]);
						$statusLine .= ",Break Time is:".$breakTime/60;
				
						$actBegin = $attLine[0];
						$actEnd = $attLine[1];
				
						$statusLine .=",Staff Actually Come On :";
						$statusLine .= ($actBegin=="")?"[NO COME RECORD]":date("H:i",$actBegin);
						$statusLine .=" and Actally Leave On :";
						$statusLine .=($actEnd=="")?"[NO LEAVE RECORD]":date("H:i",$actEnd);
						$statusLine .=", Total Hrs Allocated is: ".($manShiftEnd-$manShiftBegin)/3600;
						$statusLine .=", After Break ,It change to:".($manShiftEnd-$manShiftBegin-$breakTime)/3600;
				
						$afterFix = "[S]".date("H:i",$manShiftBegin)."-".date("H:i",$manShiftEnd)."[B]".round($breakTime/3660,2);
				
						if($manShiftBegin!= null && $actBegin!="" && ( $actBegin > ($manShiftBegin + 300))){
				
							$manShiftBegin = $actBegin;
							$afterFix.="[LA]".round(($actBegin-$v["confirm_shift_begin"])/3600,2);
							$statusLine .="Since come time is on ".date("H:i",$actBegin).", which is LATE, so I deduct ".round(($actBegin-$v["confirm_shift_begin"])/60,0)." mins";
							/*
							 if(($actBegin - $manShiftBegin) > 599 && ($actBegin - $manShiftBegin) <=900  ){
							$manShiftBegin += 900;
							$afterFix.="[LA]15";
				
							$statusLine .="Since come time is on ".date("H:i",$actBegin).", which is LATE, so I deduct 15 mins";
							}
								
							if(($actBegin - $manShiftBegin) > 900 ){
							if(($actBegin-$manShiftBegin)%900 <= 60){
							$manShiftBegin += (($actBegin - $manShiftBegin) - ($actBegin - $manShiftBegin)%900);
							$afterFix.="[LA]".round((($actBegin - $v["confirm_shift_begin"]) - ($actBegin - $v["confirm_shift_begin"]) % 900 ) /3600,2);
							$statusLine .= "Since come time is on ".date("H:i",$actBegin).", which is LATE, so I deduct ".(($actBegin - $v["confirm_shift_begin"]) - ($actBegin - $v["confirm_shift_begin"] ) %900 ) / 60 ." mins";
							}
							else{
							$manShiftBegin += (($actBegin - $manShiftBegin) - ($actBegin - $manShiftBegin)%900)+ 900;
							$afterFix.="[LA]".round(((($actBegin - $v["confirm_shift_begin"]) - ($actBegin - $v["confirm_shift_begin"])%900)+ 900)/3600,2);
							$statusLine .= "Since come time is on ".date("H:i",$actBegin).", which is LATE, so I deduct ".(($actBegin - $v["confirm_shift_begin"]) - ($actBegin - $v["confirm_shift_begin"] ) %900 ) / 60 ." + 15 mins";
							}
							}
							*/
								
						}
				
						if($actEnd!="" && $manShiftEnd!= null && ( ($actEnd + 300) < $manShiftEnd) ){
								
							$manShiftEnd = $actEnd;
							$afterFix .="[EL]".round(($v["confirm_shift_end"]-$actEnd)/3600,2);
							$statusLine .= ", Leave Early on  ".date("H:i",$actEnd)." so I deduct".round(($v["confirm_shift_end"]-$actEnd)/60,2)." mins";
						}
				
						$shiftBegin = $manShiftBegin;
						$shiftEnd = $manShiftEnd - $breakTime;
				
						if($actBegin=="" || $actEnd == "" || $v["confirm_shift_begin"] == null || $v["confirm_shift_end"] = null){
								
							$shiftBegin = 0;
							$shiftEnd = 0;
						}
				
				
						$adjustTime = ($v["admin_adjust"]===null)?0:$v["admin_adjust"];
						//final adjust
						$statusLine .= $firstBreakStr;
						echo $statusLine."<br />";
							
						//public holiday
						if(in_array($dateCheck,$arrPublicHoliday)){
							$hours = round(($shiftEnd-$shiftBegin + $adjustTime - $firstBreak )/3600,2);
				
							$strBegin = date("H:i",$shiftBegin);
							$strEnd = date("H:i",$shiftEnd);
							$strStartStopTime = $strBegin."-".$strEnd;
				
							//$arrFileHead = array("Emp. Co./Last Name","Emp. First Name","Payroll Category","Date","Units","Emp. Card ID","Emp. Record ID");
							if($hours > 0 && $activeStaff){
								$fl = fopen(getcwd()."/".$fileName,"a");
								$arrTmp = array($lastName,$firstName,$arrPayment[$idPackage]["e"],$strDate,$hours,$strStartStopTime.$afterFix,$cardID,$recordID);
								//$arrTmp = array($lastName,$firstName,$arrPayment[$idPackage]["e"],$strDate,$hours,$strStartStopTime.$afterFix,$recordID);
								$strTmp = implode("\t",$arrTmp);
								fputs($fl,$strTmp."\r\n");
								fclose($fl);
							}
								
						}
						elseif(date("N",strtotime($dateCheck)) == 7){
								
							$hours = round(($shiftEnd-$shiftBegin + $adjustTime - $firstBreak )/3600,2);
							$strBegin = date("H:i",$shiftBegin);
							$strEnd = date("H:i",$shiftEnd);
							$strStartStopTime = $strBegin."-".$strEnd;
								
							//$arrFileHead = array("Emp. Co./Last Name","Emp. First Name","Payroll Category","Date","Units","Emp. Card ID","Emp. Record ID");
							if($hours > 0 && $activeStaff){
								$fl = fopen(getcwd()."/".$fileName,"a");
								$arrTmp = array($lastName,$firstName,$arrPayment[$idPackage]["d"],$strDate,$hours,$strStartStopTime.$afterFix,$cardID,$recordID);
								//$arrTmp = array($lastName,$firstName,$arrPayment[$idPackage]["d"],$strDate,$hours,$strStartStopTime.$afterFix,$recordID);
								$strTmp = implode("\t",$arrTmp);
								fputs($fl,$strTmp."\r\n");
								fclose($fl);
							}
								
						}
						elseif(date("N",strtotime($dateCheck)) == 6){
							$hours = round(($shiftEnd-$shiftBegin + $adjustTime - $firstBreak )/3600,2);
							$strBegin = date("H:i",$shiftBegin);
							$strEnd = date("H:i",$shiftEnd);
							$strStartStopTime = $strBegin."-".$strEnd;
								
							//$arrFileHead = array("Emp. Co./Last Name","Emp. First Name","Payroll Category","Date","Units","Emp. Card ID","Emp. Record ID");
							if($hours > 0 && $activeStaff){
								$fl = fopen(getcwd()."/".$fileName,"a");
								$arrTmp = array($lastName,$firstName,$arrPayment[$idPackage]["c"],$strDate,$hours,$strStartStopTime.$afterFix,$cardID,$recordID);
								//$arrTmp = array($lastName,$firstName,$arrPayment[$idPackage]["c"],$strDate,$hours,$strStartStopTime.$afterFix,$recordID);
								$strTmp = implode("\t",$arrTmp);
								fputs($fl,$strTmp."\r\n");
								fclose($fl);
							}
						}
						else{
							//1-5
				
							if($v["shop_head"] == "ADPC" || $v["shop_head"] == "CLPC" || $v["shop_head"] == "WLIC" || $v["shop_head"] == "CLIC" ){
								$date6Pm = date("U",strtotime($dateCheck." 18:30:00"));
							}
							else{
									
								$date6Pm = date("U",strtotime($dateCheck." 18:00:00"));
							}
								
				
							if($shiftBegin < $date6Pm && $shiftEnd <= $date6Pm){
								// total Normal hour
								//echo "FIRST BREAK".$firstBreak."<br />";
								$hours = round(($shiftEnd-$shiftBegin + $adjustTime -$firstBreak )/3600,2);
								$strBegin = date("H:i",$shiftBegin);
								$strEnd = date("H:i",$shiftEnd);
								$strStartStopTime = $strBegin."-".$strEnd;
									
								//$arrFileHead = array("Emp. Co./Last Name","Emp. First Name","Payroll Category","Date","Units","Emp. Card ID","Emp. Record ID");
								if($hours > 0 && $activeStaff){
									$fl = fopen(getcwd()."/".$fileName,"a");
									$arrTmp = array($lastName,$firstName,$arrPayment[$idPackage]["a"],$strDate,$hours,$strStartStopTime.$afterFix,$cardID,$recordID);
									//$arrTmp = array($lastName,$firstName,$arrPayment[$idPackage]["a"],$strDate,$hours,$strStartStopTime.$afterFix,$recordID);
									$strTmp = implode("\t",$arrTmp);
									fputs($fl,$strTmp."\r\n");
									fclose($fl);
								}
									
							}
							if($shiftBegin >=$date6Pm){
				
								$hours = round(($shiftEnd-$shiftBegin + $adjustTime )/3600,2);
								$strBegin = date("H:i",$shiftBegin);
								$strEnd = date("H:i",$shiftEnd);
								$strStartStopTime = $strBegin."-".$strEnd;
									
								//$arrFileHead = array("Emp. Co./Last Name","Emp. First Name","Payroll Category","Date","Units","Emp. Card ID","Emp. Record ID");
								if($hours > 0 && $activeStaff){
									$fl = fopen(getcwd()."/".$fileName,"a");
									$arrTmp = array($lastName,$firstName,$arrPayment[$idPackage]["b"],$strDate,$hours,$strStartStopTime.$afterFix,$cardID,$recordID);
									//$arrTmp = array($lastName,$firstName,$arrPayment[$idPackage]["b"],$strDate,$hours,$strStartStopTime.$afterFix,$recordID);
									$strTmp = implode("\t",$arrTmp);
									fputs($fl,$strTmp."\r\n");
									fclose($fl);
								}
				
								//total 6 pm
				
							}
							if($shiftBegin< $date6Pm && $shiftEnd > $date6Pm){
								//before and after
								$hoursNormal = round(($date6Pm-$shiftBegin-$firstBreak)/3600,2);
								$strBegin = date("H:i",$shiftBegin);
								$strEnd = date("H:i",$shiftEnd);
								$strStartStopTime = $strBegin."-".$strEnd;
									
								//$arrFileHead = array("Emp. Co./Last Name","Emp. First Name","Payroll Category","Date","Units","Emp. Card ID","Emp. Record ID");
								if($hoursNormal > 0 && $activeStaff){
									$fl = fopen(getcwd()."/".$fileName,"a");
									$arrTmp = array($lastName,$firstName,$arrPayment[$idPackage]["a"],$strDate,$hoursNormal,$strStartStopTime.$afterFix,$cardID,$recordID);
									//$arrTmp = array($lastName,$firstName,$arrPayment[$idPackage]["a"],$strDate,$hours,$strStartStopTime.$afterFix,$recordID);
									$strTmp = implode("\t",$arrTmp);
									fputs($fl,$strTmp."\r\n");
									fclose($fl);
								}
									
								$hoursExt = round(($shiftEnd-$date6Pm)/3600,2);
								echo "Ext Hour is".$hoursExt."|";
								//$strBegin = date("H:i",$shiftBegin);
								//$strEnd = date("H:i",$shiftEnd);
								//$strStartStopTIme = $strBegin."-".$strEnd;
									
								//$arrFileHead = array("Emp. Co./Last Name","Emp. First Name","Payroll Category","Date","Units","Emp. Card ID","Emp. Record ID");
								if($hoursExt > 0 && $activeStaff){
									$fl = fopen(getcwd()."/".$fileName,"a");
									$arrTmp = array($lastName,$firstName,$arrPayment[$idPackage]["b"],$strDate,$hoursExt,$strStartStopTime.$afterFix,$cardID,$recordID);
									//$arrTmp = array($lastName,$firstName,$arrPayment[$idPackage]["b"],$strDate,$hours,$strStartStopTime.$afterFix,$recordID);
									$strTmp = implode("\t",$arrTmp);
									fputs($fl,$strTmp."\r\n");
									fclose($fl);
								}
							}
								
						}
							
							
							
					}//end else
				}
				
			}

			
			echo "<br />";
			echo "<a href=\"/{$fileName}\" target=\"_blank\" >Right Click  Here And Save AS </a>";
		}
		
		
		
	}
	
	public function exportTimeSheetAction(){

		date_default_timezone_set('Australia/Melbourne');
		
		$arrPublicHoliday = array("2013-04-25","2013-06-10","2013-11-05","2013-12-25","2013-12-26","2014-01-01","2014-01-27","2014-03-10","2014-04-18","2014-04-19","2014-04-21","2014-04-25","2014-06-09");
		$arrPublicHolidaySa = array("2013-04-25","2013-06-10","2013-11-05","2013-12-25","2013-12-26","2014-01-01","2014-01-27","2014-03-10","2014-04-18","2014-04-19","2014-04-21","2014-04-25","2014-06-09");
		$arrFileHead = array("Emp. Co./Last Name","Emp. First Name","Payroll Category","Date","Units","Start/Stop Time","Emp. Card ID","Emp. Record ID");
		//$arrFileHead = array("Emp. Co./Last Name","Emp. First Name","Payroll Category","Date","Units","Start/Stop Time","Emp. Record ID");
		$arrPayment = array(
				0 => array(),
				1 =>array("a"=>"Base Salary Package","b"=>"Base Salary Package","c"=>"Base Salary Package","d"=>"Base Salary Package","e"=>"Base Salary Package"),
				2 =>array("a"=>"Base Hourly","b"=>"Base Hourly","c"=>"Saturday Pay","d"=>"Sunday Pay","e"=>"Public Holiday Pay"),
				3 =>array("a"=>"Base Hourly","b"=>"Base Hourly","c"=>"Saturday Pay","d"=>"Sunday Pay","e"=>"Public Holiday Pay"),
				4 =>array("a"=>"Base Hourly","b"=>"Saturday Pay","c"=>"Saturday Pay","d"=>"Sunday Pay","e"=>"Public Holiday Pay"),
				5 =>array("a"=>"Base Hourly","b"=>"Base Hourly","c"=>"Base Hourly","d"=>"Base Hourly","e"=>"Base Hourly"),
				6 =>array("a"=>"Base Hourly","b"=>"Base Hourly","c"=>"Base Hourly","d"=>"Base Hourly","e"=>"Base Hourly")
				);
		$arrFileNameStr = array(
				0=>"",
				1=>"ACDF_",
				2=>"BE_",
				3=>"ACDF_",
				4=>"ACDF_",
				5=>"BE_",
				6=>"ACDF_"
				);
		
		$tSheet = new Model_DbTable_Roster_Timesheet();
		$stInfo = new Model_DbTable_Roster_Staffinfo();
		$stDetail = new Model_DbTable_Roster_Stafflogindetail();
		$attRecord = new Model_DbTable_Roster_Attnrecord();		
		
		if($_POST){
			$dateBegin = $_POST["date_begin"];
			$dateEnd = $_POST["date_end"];
			
			$orgFileName = "TS_".$dateBegin."-".$dateEnd.".txt";
			
			foreach($arrFileNameStr as $key => $value){
			if($value!=""){	
				$fileName = $value.$orgFileName;
				$strFileHead = implode("\t",$arrFileHead);
				$fl = fopen(getcwd()."/".$fileName,"w");
				fputs($fl,$strFileHead."\r\n");
				fclose($fl);
			}
			}		
			
			$arrDate = Model_DatetimeHelper::createDateArray($dateBegin, $dateEnd);
			foreach($arrDate as $dateCheck){
				
				$arrShift = $tSheet->listShiftByDate($dateCheck, $dateCheck);
				//var_dump($arrShift);
				//get 6 pm that date 
				$date6Pm = date("U",strtotime($dateCheck." 18:00:00"));
				
				foreach($arrShift as $k => $v){
					$errorFlag = 0;
					
					$staffID = $v["id_staff"];
					$shopHead = $v["shop_head"];
					$attLine = $attRecord->getFirstOnLastOffDuty($staffID, $dateCheck,Model_EncryptHelper::sslPassword($shopHead));
					$stLine = $stDetail->getDetail($staffID);
					$stInfoLine = $stInfo->getStaffinfo($staffID);
					if(!$stLine){
						$errorFlag = 1;
						$fullName = Model_EncryptHelper::getSslPassword($stLine["n"]);
						
						echo "ERROR-".$staffID."-".$stLine["ni"]."-".$fullName."<br />";
					}
					
					else{

						//staff Name 
						$statusLine = $dateCheck.", for ".$stLine["ni"]."-".Model_EncryptHelper::getSslPassword($stLine["n"]);
						
						$fullName = explode(" ",Model_EncryptHelper::getSslPassword($stLine["n"]));
						
						$errorPerson = true;
						
							$firstName = $fullName[0];
							$lastName = $fullName[1];
							$cardID = ucwords(Model_EncryptHelper::getSslPassword($stLine["n"]));
							$recordID = "000";
							$activeStaff = -1;
							$idPackage = 5;
						
						if($stInfoLine){
							
							$firstName = $stInfoLine["first_name"];
							$lastName = $stInfoLine["last_name"];
							$cardID = $stInfoLine["id_card"];
							$recordID =  $stInfoLine["id_record"];
							$activeStaff = $stInfoLine["active_staff"];
							$idPackage = $stInfoLine["id_package"];
							
						}
			

						
						$strDate = date("j/m/Y",strtotime($dateCheck));
						
						$firstBreak = 0;
						$breakTime = 1800;
						$firstBreakStr = "";
						if($v["break_time"]===(int)0 and $v["break_time"]!== null ){
							$breakTime  = 0;
						}
						if((($v["confirm_shift_end"]-$v["confirm_shift_begin"])<18000) && ($v["break_time"] == 0 ||$v["break_time"]== null )){
							
							$breakTime = 0;
						}
						
						if(($v["confirm_shift_end"]-$v["confirm_shift_begin"]) >= 32400){
							
							$firstBreak = 1800;
							$firstBreakStr = "Over 9Hour , Will Dedute Another 0.5 Hr <br />";
							//"FIRST BREAK".$firstBreak."<br />";
						}				
						$manShiftBegin = $v["confirm_shift_begin"];
						$manShiftEnd = $v["confirm_shift_end"];
						
						$statusLine .= ",Manager Allocate Time is :".date("H:i",$manShiftBegin)."-".date("H:i",$manShiftEnd);
						$statusLine .= ",Staff Self Fill Time is :".date("H:i",$v["shift_begin"])."-".date("H:i",$v["shift_end"]);
						$statusLine .= ",Break Time is:".$breakTime/60;
						
						$actBegin = $attLine[0];
						$actEnd = $attLine[1];
						
						$statusLine .=",Staff Actually Come On :";
						$statusLine .= ($actBegin=="")?"[NO COME RECORD]":date("H:i",$actBegin);
						$statusLine .=" and Actally Leave On :";
						$statusLine .=($actEnd=="")?"[NO LEAVE RECORD]":date("H:i",$actEnd);
						$statusLine .=", Total Hrs Allocated is: ".($manShiftEnd-$manShiftBegin)/3600;
						$statusLine .=", After Break ,It change to:".($manShiftEnd-$manShiftBegin-$breakTime)/3600;
						
						$afterFix = "[S]".date("H:i",$manShiftBegin)."-".date("H:i",$manShiftEnd)."[B]".round($breakTime/3660,2);

						if($manShiftBegin!= null && $actBegin!="" && ( $actBegin > ($manShiftBegin + 300))){

							$manShiftBegin = $actBegin;
							$afterFix.="[LA]".round(($actBegin-$v["confirm_shift_begin"])/3600,2);
							$statusLine .="Since come time is on ".date("H:i",$actBegin).", which is LATE, so I deduct ".round(($actBegin-$v["confirm_shift_begin"])/60,0)." mins";
							/*
							if(($actBegin - $manShiftBegin) > 599 && ($actBegin - $manShiftBegin) <=900  ){
								$manShiftBegin += 900;
								$afterFix.="[LA]15";
								
								$statusLine .="Since come time is on ".date("H:i",$actBegin).", which is LATE, so I deduct 15 mins";
							}
							
							if(($actBegin - $manShiftBegin) > 900 ){
								if(($actBegin-$manShiftBegin)%900 <= 60){
									$manShiftBegin += (($actBegin - $manShiftBegin) - ($actBegin - $manShiftBegin)%900);
									$afterFix.="[LA]".round((($actBegin - $v["confirm_shift_begin"]) - ($actBegin - $v["confirm_shift_begin"]) % 900 ) /3600,2);
									$statusLine .= "Since come time is on ".date("H:i",$actBegin).", which is LATE, so I deduct ".(($actBegin - $v["confirm_shift_begin"]) - ($actBegin - $v["confirm_shift_begin"] ) %900 ) / 60 ." mins";
								}
								else{
									$manShiftBegin += (($actBegin - $manShiftBegin) - ($actBegin - $manShiftBegin)%900)+ 900;
									$afterFix.="[LA]".round(((($actBegin - $v["confirm_shift_begin"]) - ($actBegin - $v["confirm_shift_begin"])%900)+ 900)/3600,2);
									$statusLine .= "Since come time is on ".date("H:i",$actBegin).", which is LATE, so I deduct ".(($actBegin - $v["confirm_shift_begin"]) - ($actBegin - $v["confirm_shift_begin"] ) %900 ) / 60 ." + 15 mins";
								}	
							}
							*/
							
						}
						
						if($actEnd!="" && $manShiftEnd!= null && ( ($actEnd + 300) < $manShiftEnd) ){
							
							$manShiftEnd = $actEnd;		
							$afterFix .="[EL]".round(($v["confirm_shift_end"]-$actEnd)/3600,2);	
							$statusLine .= ", Leave Early on  ".date("H:i",$actEnd)." so I deduct".round(($v["confirm_shift_end"]-$actEnd)/60,2)." mins";
						}
						
						$shiftBegin = $manShiftBegin;
						$shiftEnd = $manShiftEnd - $breakTime;
						
						if($actBegin=="" || $actEnd == "" || $v["confirm_shift_begin"] == null || $v["confirm_shift_end"] = null){
							
							$shiftBegin = 0;
							$shiftEnd = 0;
						}
						
						
						$adjustTime = ($v["admin_adjust"]===null)?0:$v["admin_adjust"];
						//final adjust 
						$statusLine .= $firstBreakStr;
						echo $statusLine."<br />";
					
					//public holiday 
						if(in_array($dateCheck,$arrPublicHoliday)){
							$hours = round(($shiftEnd-$shiftBegin + $adjustTime - $firstBreak )/3600,2);

							$strBegin = date("H:i",$shiftBegin);
							$strEnd = date("H:i",$shiftEnd);
							$strStartStopTime = $strBegin."-".$strEnd;
						
					//$arrFileHead = array("Emp. Co./Last Name","Emp. First Name","Payroll Category","Date","Units","Emp. Card ID","Emp. Record ID");	
							if($hours > 0 && $activeStaff >0 ){	
								$fl = fopen(getcwd()."/".$arrFileNameStr[$idPackage].$orgFileName,"a");
								$arrTmp = array($lastName,$firstName,$arrPayment[$idPackage]["e"],$strDate,$hours,$strStartStopTime.$afterFix,$cardID,$recordID);
								//$arrTmp = array($lastName,$firstName,$arrPayment[$idPackage]["e"],$strDate,$hours,$strStartStopTime.$afterFix,$recordID);
								$strTmp = implode("\t",$arrTmp);
								fputs($fl,$strTmp."\r\n");
								fclose($fl);
							}
							
						}
						elseif(date("N",strtotime($dateCheck)) == 7){
							
							$hours = round(($shiftEnd-$shiftBegin + $adjustTime - $firstBreak )/3600,2);
							$strBegin = date("H:i",$shiftBegin);
							$strEnd = date("H:i",$shiftEnd);
							$strStartStopTime = $strBegin."-".$strEnd;
							
							//$arrFileHead = array("Emp. Co./Last Name","Emp. First Name","Payroll Category","Date","Units","Emp. Card ID","Emp. Record ID");
							if($hours > 0 && $activeStaff >0){	
								$fl = fopen(getcwd()."/".$arrFileNameStr[$idPackage].$orgFileName,"a");
								$arrTmp = array($lastName,$firstName,$arrPayment[$idPackage]["d"],$strDate,$hours,$strStartStopTime.$afterFix,$cardID,$recordID);
								//$arrTmp = array($lastName,$firstName,$arrPayment[$idPackage]["d"],$strDate,$hours,$strStartStopTime.$afterFix,$recordID);
								$strTmp = implode("\t",$arrTmp);
								fputs($fl,$strTmp."\r\n");
								fclose($fl);
							}
							if($hours > 0 && $activeStaff== -1){
								if(!file_exists(getcwd()."/".$staffID."_".$lastName."_".str_replace(" ","_",$firstName)."_MISS_MYOB_".$orgFileName)){
									$this->writeArrHeadToFile($staffID."_".$lastName."_".str_replace(" ","_",$firstName)."_MISS_MYOB_".$orgFileName);
								}
								
								$fl = fopen(getcwd()."/".$staffID."_".$lastName."_".str_replace(" ","_",$firstName)."_MISS_MYOB_".$orgFileName,"a");
							
								$arrTmp = array($lastName,$firstName,$arrPayment[$idPackage]["d"],$strDate,$hours,$strStartStopTime.$afterFix,$cardID,$recordID);
								//$arrTmp = array($lastName,$firstName,$arrPayment[$idPackage]["d"],$strDate,$hours,$strStartStopTime.$afterFix,$recordID);
								$strTmp = implode("\t",$arrTmp);
								fputs($fl,$strTmp."\r\n");
								fclose($fl);
								
							}
							
							
							
						}
						elseif(date("N",strtotime($dateCheck)) == 6){
							$hours = round(($shiftEnd-$shiftBegin + $adjustTime - $firstBreak )/3600,2);
							$strBegin = date("H:i",$shiftBegin);
							$strEnd = date("H:i",$shiftEnd);
							$strStartStopTime = $strBegin."-".$strEnd;
							
							//$arrFileHead = array("Emp. Co./Last Name","Emp. First Name","Payroll Category","Date","Units","Emp. Card ID","Emp. Record ID");
							if($hours > 0 && $activeStaff > 0){	
								$fl = fopen(getcwd()."/".$arrFileNameStr[$idPackage].$orgFileName,"a");
								$arrTmp = array($lastName,$firstName,$arrPayment[$idPackage]["c"],$strDate,$hours,$strStartStopTime.$afterFix,$cardID,$recordID);
								//$arrTmp = array($lastName,$firstName,$arrPayment[$idPackage]["c"],$strDate,$hours,$strStartStopTime.$afterFix,$recordID);
								$strTmp = implode("\t",$arrTmp);
								fputs($fl,$strTmp."\r\n");
								fclose($fl);
							}
							if($hours > 0 && $activeStaff==-1){
								if(!file_exists(getcwd()."/".$staffID."_".$lastName."_".str_replace(" ","_",$firstName)."_MISS_MYOB_".$orgFileName)){
									$this->writeArrHeadToFile($staffID."_".$lastName."_".str_replace(" ","_",$firstName)."_MISS_MYOB_".$orgFileName);
								}
								
								$fl = fopen(getcwd()."/".$staffID."_".$lastName."_".str_replace(" ","_",$firstName)."_MISS_MYOB_".$orgFileName,"a");
								$arrTmp = array($lastName,$firstName,$arrPayment[$idPackage]["c"],$strDate,$hours,$strStartStopTime.$afterFix,$cardID,$recordID);
								//$arrTmp = array($lastName,$firstName,$arrPayment[$idPackage]["c"],$strDate,$hours,$strStartStopTime.$afterFix,$recordID);
								$strTmp = implode("\t",$arrTmp);
								fputs($fl,$strTmp."\r\n");
								fclose($fl);
							}
						}
						else{
						//1-5
						
							if($v["shop_head"] == "ADPC" || $v["shop_head"] == "CLPC" || $v["shop_head"] == "WLIC" || $v["shop_head"] == "CLIC" ){
								$date6Pm = date("U",strtotime($dateCheck." 18:30:00"));
							}
							else{
							
								$date6Pm = date("U",strtotime($dateCheck." 18:00:00"));
							}
							
								
							if($shiftBegin < $date6Pm && $shiftEnd <= $date6Pm){
								// total Normal hour
								//echo "FIRST BREAK".$firstBreak."<br />";
								$hours = round(($shiftEnd-$shiftBegin + $adjustTime -$firstBreak )/3600,2);
								$strBegin = date("H:i",$shiftBegin);
								$strEnd = date("H:i",$shiftEnd);
								$strStartStopTime = $strBegin."-".$strEnd;
									
								//$arrFileHead = array("Emp. Co./Last Name","Emp. First Name","Payroll Category","Date","Units","Emp. Card ID","Emp. Record ID");
							if($hours > 0 && $activeStaff >0){	
								$fl = fopen(getcwd()."/".$arrFileNameStr[$idPackage].$orgFileName,"a");
								$arrTmp = array($lastName,$firstName,$arrPayment[$idPackage]["a"],$strDate,$hours,$strStartStopTime.$afterFix,$cardID,$recordID);
								//$arrTmp = array($lastName,$firstName,$arrPayment[$idPackage]["a"],$strDate,$hours,$strStartStopTime.$afterFix,$recordID);
								$strTmp = implode("\t",$arrTmp);
								fputs($fl,$strTmp."\r\n");
								fclose($fl);
							}
							if($hours > 0 && $activeStaff == -1){
								if(!file_exists(getcwd()."/".$staffID."_".$lastName."_".str_replace(" ","_",$firstName)."_MISS_MYOB_".$orgFileName)){
									$this->writeArrHeadToFile($staffID."_".$lastName."_".str_replace(" ","_",$firstName)."_MISS_MYOB_".$orgFileName);
								}
								
								$fl = fopen(getcwd()."/".$staffID."_".$lastName."_".str_replace(" ","_",$firstName)."_MISS_MYOB_".$orgFileName,"a");
								$arrTmp = array($lastName,$firstName,$arrPayment[$idPackage]["a"],$strDate,$hours,$strStartStopTime.$afterFix,$cardID,$recordID);
								//$arrTmp = array($lastName,$firstName,$arrPayment[$idPackage]["a"],$strDate,$hours,$strStartStopTime.$afterFix,$recordID);
								$strTmp = implode("\t",$arrTmp);
								fputs($fl,$strTmp."\r\n");
								fclose($fl);
							}							
							}
							if($shiftBegin >=$date6Pm){
								
								$hours = round(($shiftEnd-$shiftBegin + $adjustTime )/3600,2);
								$strBegin = date("H:i",$shiftBegin);
								$strEnd = date("H:i",$shiftEnd);
								$strStartStopTime = $strBegin."-".$strEnd;
									
								//$arrFileHead = array("Emp. Co./Last Name","Emp. First Name","Payroll Category","Date","Units","Emp. Card ID","Emp. Record ID");
							if($hours > 0 && $activeStaff >0){	
								$fl = fopen(getcwd()."/".$arrFileNameStr[$idPackage].$orgFileName,"a");
								$arrTmp = array($lastName,$firstName,$arrPayment[$idPackage]["b"],$strDate,$hours,$strStartStopTime.$afterFix,$cardID,$recordID);
								//$arrTmp = array($lastName,$firstName,$arrPayment[$idPackage]["b"],$strDate,$hours,$strStartStopTime.$afterFix,$recordID);
								$strTmp = implode("\t",$arrTmp);
								fputs($fl,$strTmp."\r\n");
								fclose($fl);
							}
							if($hours > 0 && $activeStaff == -1){
								if(!file_exists(getcwd()."/".$staffID."_".$lastName."_".str_replace(" ","_",$firstName)."_MISS_MYOB_".$orgFileName)){
									$this->writeArrHeadToFile($staffID."_".$lastName."_".str_replace(" ","_",$firstName)."_MISS_MYOB_".$orgFileName);
								}
								
								$fl = fopen(getcwd()."/".$staffID."_".$lastName."_".str_replace(" ","_",$firstName)."_MISS_MYOB_".$orgFileName,"a");
								$arrTmp = array($lastName,$firstName,$arrPayment[$idPackage]["b"],$strDate,$hours,$strStartStopTime.$afterFix,$cardID,$recordID);
								//$arrTmp = array($lastName,$firstName,$arrPayment[$idPackage]["b"],$strDate,$hours,$strStartStopTime.$afterFix,$recordID);
								$strTmp = implode("\t",$arrTmp);
								fputs($fl,$strTmp."\r\n");
								fclose($fl);
							}								
								//total 6 pm
								
							}
							if($shiftBegin< $date6Pm && $shiftEnd > $date6Pm){
								//before and after 	
								$hoursNormal = round(($date6Pm-$shiftBegin-$firstBreak)/3600,2);
								$strBegin = date("H:i",$shiftBegin);
								$strEnd = date("H:i",$shiftEnd);
								$strStartStopTime = $strBegin."-".$strEnd;
									
								//$arrFileHead = array("Emp. Co./Last Name","Emp. First Name","Payroll Category","Date","Units","Emp. Card ID","Emp. Record ID");
							if($hoursNormal > 0 && $activeStaff >0){	
								$fl = fopen(getcwd()."/".$arrFileNameStr[$idPackage].$orgFileName,"a");
								$arrTmp = array($lastName,$firstName,$arrPayment[$idPackage]["a"],$strDate,$hoursNormal,$strStartStopTime.$afterFix,$cardID,$recordID);
								//$arrTmp = array($lastName,$firstName,$arrPayment[$idPackage]["a"],$strDate,$hours,$strStartStopTime.$afterFix,$recordID);
								$strTmp = implode("\t",$arrTmp);
								fputs($fl,$strTmp."\r\n");
								fclose($fl);
							}
							if($hoursNormal > 0 && $activeStaff == -1){
								if(!file_exists(getcwd()."/".$staffID."_".$lastName."_".str_replace(" ","_",$firstName)."_MISS_MYOB_".$orgFileName)){
									$this->writeArrHeadToFile($staffID."_".$lastName."_".str_replace(" ","_",$firstName)."_MISS_MYOB_".$orgFileName);
								}
								
								$fl = fopen(getcwd()."/".$staffID."_".$lastName."_".str_replace(" ","_",$firstName)."_MISS_MYOB_".$orgFileName,"a");
								$arrTmp = array($lastName,$firstName,$arrPayment[$idPackage]["a"],$strDate,$hoursNormal,$strStartStopTime.$afterFix,$cardID,$recordID);
								//$arrTmp = array($lastName,$firstName,$arrPayment[$idPackage]["a"],$strDate,$hours,$strStartStopTime.$afterFix,$recordID);
								$strTmp = implode("\t",$arrTmp);
								fputs($fl,$strTmp."\r\n");
								fclose($fl);
							}							
								$hoursExt = round(($shiftEnd-$date6Pm)/3600,2);
								echo "Ext Hour is".$hoursExt."|";
								//$strBegin = date("H:i",$shiftBegin);
								//$strEnd = date("H:i",$shiftEnd);
								//$strStartStopTIme = $strBegin."-".$strEnd;
									
								//$arrFileHead = array("Emp. Co./Last Name","Emp. First Name","Payroll Category","Date","Units","Emp. Card ID","Emp. Record ID");
							if($hoursExt > 0 && $activeStaff >0){	
								$fl = fopen(getcwd()."/".$arrFileNameStr[$idPackage].$orgFileName,"a");
								$arrTmp = array($lastName,$firstName,$arrPayment[$idPackage]["b"],$strDate,$hoursExt,$strStartStopTime.$afterFix,$cardID,$recordID);
								//$arrTmp = array($lastName,$firstName,$arrPayment[$idPackage]["b"],$strDate,$hours,$strStartStopTime.$afterFix,$recordID);
								$strTmp = implode("\t",$arrTmp);
								fputs($fl,$strTmp."\r\n");
								fclose($fl);
							}
							if($hoursExt > 0 && $activeStaff == -1){
								if(!file_exists(getcwd()."/".$staffID."_".$lastName."_".str_replace(" ","_",$firstName)."_MISS_MYOB_".$orgFileName)){
									$this->writeArrHeadToFile($staffID."_".$lastName."_".str_replace(" ","_",$firstName)."_MISS_MYOB_".$orgFileName);
								}
								
								$fl = fopen(getcwd()."/".$staffID."_".$lastName."_".str_replace(" ","_",$firstName)."_MISS_MYOB_".$orgFileName,"a");
								$arrTmp = array($lastName,$firstName,$arrPayment[$idPackage]["b"],$strDate,$hoursExt,$strStartStopTime.$afterFix,$cardID,$recordID);
								//$arrTmp = array($lastName,$firstName,$arrPayment[$idPackage]["b"],$strDate,$hours,$strStartStopTime.$afterFix,$recordID);
								$strTmp = implode("\t",$arrTmp);
								fputs($fl,$strTmp."\r\n");
								fclose($fl);
							}
							}
							
						}
					
					}//end else
				}
			}	
		}
	}

	public function saveAddCommAdminAction(){
		
		$attCaseRecord = new Model_DbTable_Roster_Attcaserecord();
		$idStatus = 10; // admin add comment 
		
		$attCaseRecord->addAttcaserecord($_POST['id_case'], $idStatus,Model_DatetimeHelper::dateToday(),Model_DatetimeHelper::timeNow(),$_POST['staff_name_admin'],$_POST['comm_admin']);
		
		$this->_redirect("/rosteraudit/case-control");
	}
	public function saveAddCommManagerAction(){
		
		$attCaseRecord = new Model_DbTable_Roster_Attcaserecord();
		$idStatus = 9; // admin add comment
		
		$attCaseRecord->addAttcaserecord($_POST['id_case'], $idStatus,Model_DatetimeHelper::dateToday(),Model_DatetimeHelper::timeNow(),$_POST['staff_name_mgr'],$_POST['comm_mgr']);
		
		$this->_redirect("/rosteraudit/manager-case-control/token/".base64_encode($_POST['id_mgr']));
	}

	public function saveRequestAction(){
		$attCaseRecord = new Model_DbTable_Roster_Attcaserecord();
		$idStatus = 4;
		$attCaseRecord->addAttcaserecord($_POST['id_case'], $idStatus,Model_DatetimeHelper::dateToday(),Model_DatetimeHelper::timeNow(),$_POST['staff_name'],$_POST['comment']);
		$this->_redirect("/rosteraudit/case-control");
	}
	
	public function saveRejectAction(){
		$attCaseRecord = new Model_DbTable_Roster_Attcaserecord();
		$attCase = new Model_DbTable_Roster_Attncase();
		$idStatus = 90;
		$attCaseRecord->addAttcaserecord($_POST['id_case'], $idStatus,Model_DatetimeHelper::dateToday(),Model_DatetimeHelper::timeNow(),$_POST['staff_name'],$_POST['comment']);				
		$attCase->rejectCase($_POST['id_case']);
		$this->_redirect("/rosteraudit/case-control");
		
	}
	public function saveAcceptAction(){
		$attCaseRecord = new Model_DbTable_Roster_Attcaserecord();
		$attCase = new Model_DbTable_Roster_Attncase();
		
		$idStatus = 80;
		$attCaseRecord->addAttcaserecord($_POST['id_case'], $idStatus,Model_DatetimeHelper::dateToday(),Model_DatetimeHelper::timeNow(),$_POST['staff_name'],$_POST['comment']);
		$attCase->acceptCase($_POST['id_case'],$_POST['normal_hour'],$_POST['pm6_hour'],$_POST['saturday_hour'],$_POST['sunday_hour'],$_POST['holiday_hour'],$_POST['date_will_pay']);
		
		$this->_redirect("/rosteraudit/case-control");
		
		//add record 
		
		// move the case into the list 
		
		//add hour into case 
		
		//add will pay date
	}
	
	public function saveDelayAction(){

		$attCaseRecord = new Model_DbTable_Roster_Attcaserecord();
		$attCase = new Model_DbTable_Roster_Attncase();
		$idStatus = 81; // admin add comment
		
		$attCaseRecord->addAttcaserecord($_POST['id_case'], $idStatus,Model_DatetimeHelper::dateToday(),Model_DatetimeHelper::timeNow(),$_POST['staff_name'],$_POST['comment']);
		$attCase->delayPayment($_POST['id_case'],$_POST['date_will_pay']);
		
		$this->_redirect("/rosteraudit/case-control");
		
	}
	
	public function saveExportAction(){

		$attCaseRecord = new Model_DbTable_Roster_Attcaserecord();
		$attCase = new Model_DbTable_Roster_Attncase();
		
		$stInfo = new Model_DbTable_Roster_Staffinfo();
		$stDetail = new Model_DbTable_Roster_Stafflogindetail();
		
		
		
		$idStatus = 82;
		$attCaseRecord->addAttcaserecord($_POST['id_case'], $idStatus,Model_DatetimeHelper::dateToday(),Model_DatetimeHelper::timeNow(),$_POST['staff_name'],"");
		$attCase->processPayment($_POST['id_case']);
		
		$dateToday = Model_DatetimeHelper::dateToday("");
		$fileName = "Shortpayment".$dateToday.".txt";
		
		$arrFileHead = array("Emp. Co./Last Name","Emp. First Name","Payroll Category","Date","Units","Start/Stop Time","Emp. Card ID","Emp. Record ID");
		//$arrFileHead = array("Emp. Co./Last Name","Emp. First Name","Payroll Category","Date","Units","Start/Stop Time","Emp. Record ID");
		
		$arrPayment = array(
				0 => array(),
				1 =>array("a"=>"Base Salary Package","b"=>"Base Salary Package","c"=>"Base Salary Package","d"=>"Base Salary Package","e"=>"Base Salary Package"),
				2 =>array("a"=>"Base Hourly","b"=>"Base Hourly","c"=>"Saturday Pay","d"=>"Sunday Pay","e"=>"Public Holiday Pay"),
				3 =>array("a"=>"Base Hourly","b"=>"Base Hourly","c"=>"Saturday Pay","d"=>"Sunday Pay","e"=>"Public Holiday Pay"),
				4 =>array("a"=>"Base Hourly","b"=>"Saturday Pay","c"=>"Saturday Pay","d"=>"Sunday Pay","e"=>"Public Holiday Pay"),
				5 =>array("a"=>"Base Hourly","b"=>"Base Hourly","c"=>"Base Hourly","d"=>"Base Hourly","e"=>"Base Hourly"),
				6 =>array("a"=>"Base Hourly","b"=>"Base Hourly","c"=>"Base Hourly","d"=>"Base Hourly","e"=>"Base Hourly")
		);
		
		$arrFileNameStr = array(
				0=>"",
				1=>"ACDF_",
				2=>"BE_",
				3=>"ACDF_",
				4=>"ACDF_",
				5=>"BE_",
				6=>"ACDF_"
		);
		
		$fileNameAF = "ACDF_".$fileName;
		$fileNameBE = "BE_".$fileName;

		if(!file_exists(getcwd()."/".$fileNameAF)){
			
			$strFileHead = implode("\t",$arrFileHead);
			$fl = fopen(getcwd()."/".$fileNameAF,"w");
			fputs($fl,$strFileHead."\r\n");
			fclose($fl);
		}
		if(!file_exists(getcwd()."/".$fileNameBE)){
			
			$strFileHead = implode("\t",$arrFileHead);
			$fl = fopen(getcwd()."/".$fileNameBE,"w");
			fputs($fl,$strFileHead."\r\n");
			fclose($fl);		
		}		
		
		
		if($_POST){
			
			$idCase = $_POST['id_case'];

			
			$caseRow = $attCase->getAttncase($idCase);

			$idStaff = $caseRow['id_staff']; 
			$caseNo = $caseRow['case_id_case'];
			$strDate = date("j/m/Y",strtotime($caseRow['shift_date']));
			$normalHour = $caseRow['hour_allocate_normal'];
			$pm6Hour = $caseRow['hour_allocate_6pm'];
			$satHour = $caseRow['hour_allocate_sat'];
			$sunHour = $caseRow['hour_allocate_sun'];
			$holidayHour = $caseRow['hour_allocate_pholiday'];
			
			
			$staffInfoLine = $stInfo->getStaffinfo($idStaff);
			
			
			$firstName = $staffInfoLine['first_name'];
			$lastName = $staffInfoLine['last_name'];
			$idPackage = $staffInfoLine['id_package'];
			$idCard = $staffInfoLine['id_card'];
			$recordID = $staffInfoLine['id_record'];
			
			if($normalHour>0){
				
				$arrTmp = array($lastName,$firstName,$arrPayment[$idPackage]["a"],$strDate,$normalHour,$caseNo,$idCard,$recordID);
				
				$strFileHead = implode("\t",$arrTmp);
				$fl = fopen(getcwd()."/".$arrFileNameStr[$idPackage].$fileName,"a");
				fputs($fl,$strFileHead."\r\n");
				fclose($fl);
			
			}
			if($pm6Hour>0){
			
				
				$arrTmp = array($lastName,$firstName,$arrPayment[$idPackage]["b"],$strDate,$pm6Hour,$caseNo,$idCard,$recordID);
				
				$strFileHead = implode("\t",$arrTmp);
				$fl = fopen(getcwd()."/".$arrFileNameStr[$idPackage].$fileName,"a");
				fputs($fl,$strFileHead."\r\n");
				fclose($fl);
				
				
			}
			if($satHour>0){
			
				$arrTmp = array($lastName,$firstName,$arrPayment[$idPackage]["c"],$strDate,$satHour,$caseNo,$idCard,$recordID);
				
				$strFileHead = implode("\t",$arrTmp);
				$fl = fopen(getcwd()."/".$arrFileNameStr[$idPackage].$fileName,"a");
				fputs($fl,$strFileHead."\r\n");
				fclose($fl);
			}
			if($sunHour>0){
			
				$arrTmp = array($lastName,$firstName,$arrPayment[$idPackage]["d"],$strDate,$sunHour,$caseNo,$idCard,$recordID);
				
				$strFileHead = implode("\t",$arrTmp);
				$fl = fopen(getcwd()."/".$arrFileNameStr[$idPackage].$fileName,"a");
				fputs($fl,$strFileHead."\r\n");
				fclose($fl);
			}
			if($holidayHour>0){
					
				$arrTmp = array($lastName,$firstName,$arrPayment[$idPackage]["e"],$strDate,$holidayHour,$caseNo,$idCard,$recordID);
			
				$strFileHead = implode("\t",$arrTmp);
				$fl = fopen(getcwd()."/".$arrFileNameStr[$idPackage].$fileName,"a");
				fputs($fl,$strFileHead."\r\n");
				fclose($fl);
			}					
			
			
			//get staff id
			//get staff infor detail 
			// get type 
			
			
		}
		
		
		//$fl = fopen(getcwd()."/".$arrFileNameStr[$idPackage].$orgFileName,"a");
		//$arrTmp = array($lastName,$firstName,$arrPayment[$idPackage]["b"],$strDate,$hoursExt,$strStartStopTime.$afterFix,$cardID,$recordID);
		//$arrTmp = array($lastName,$firstName,$arrPayment[$idPackage]["b"],$strDate,$hours,$strStartStopTime.$afterFix,$recordID);
		//$strTmp = implode("\t",$arrTmp);
		//fputs($fl,$strTmp."\r\n");
		//fclose($fl);
		
		
		
		
		//add record 
		//change status 1
		//get file 
		//append to time sheet 
		
		
		$this->_redirect("/rosteraudit/case-control");
		
	}
	public function saveChequeAction(){
		
		$attCaseRecord = new Model_DbTable_Roster_Attcaserecord();
		$attCase = new Model_DbTable_Roster_Attncase();
		
		$idStatus = 99;
		$attCaseRecord->addAttcaserecord($_POST['id_case'], $idStatus,Model_DatetimeHelper::dateToday(),Model_DatetimeHelper::timeNow(),$_POST['staff_name'],$_POST['cheque_no']);
		$attCase->recordChequeNo($_POST['id_case'],$_POST['cheque_no']);
				
		$this->_redirect("/rosteraudit/case-control");
	}
	
	public function saveCancelAction(){
		
		$attCaseRecord = new Model_DbTable_Roster_Attcaserecord();
		$attCase = new Model_DbTable_Roster_Attncase();
		
		$idStatus = 92;
		$attCaseRecord->addAttcaserecord($_POST['id_case'], $idStatus,Model_DatetimeHelper::dateToday(),Model_DatetimeHelper::timeNow(),$_POST['staff_name'],$_POST['comment']);			
		$attCase->cancelCase($_POST['id_case']);
		$this->_redirect("/rosteraudit/manager-case-control/token/".base64_encode($_POST['id_mgr']));		
	}
	public function saveUploadAction(){
		
		date_default_timezone_set("Australia/Melbourne");
		$attCaseRecord = new Model_DbTable_Roster_Attcaserecord();
		
		$timeStamp = date("U");
		$idStatus = 5;
		$linkImage = "";
		$cPath = getcwd();
		$uploadPath = '/upload/';
		
		if(isset($_FILES['upload_file'])){
			$fileName = $_FILES['upload_file']['name'];
			$ext = pathinfo($fileName, PATHINFO_EXTENSION);
			$newFileName = $_POST['id_case']."_".$timeStamp.".".$ext;
			if(move_uploaded_file($_FILES['upload_file']['tmp_name'],$cPath.$uploadPath.$newFileName)){
		
				$linkImage = $uploadPath.$newFileName;
			}
		}
		$attCaseRecord->addAttcaserecord($_POST['id_case'], $idStatus,Model_DatetimeHelper::dateToday(),Model_DatetimeHelper::timeNow(),$_POST['staff_name'],$_POST['comment'],$linkImage);
		$this->_redirect("/rosteraudit/manager-case-control/token/".base64_encode($_POST['id_mgr']));
	}	
	
	public function checkManagerShiftAction(){
		
		$intDateToday = Model_DatetimeHelper::tranferToInt(Model_DatetimeHelper::dateToday());
		
		$arrMgrList = array(2,3,4,27,80,115,11,36);
		
		$scDiff = 3600*60;
		
		$ts = new Model_DbTable_Roster_Timesheet();
		
		foreach($arrMgrList as $idMgr){
		
		$arrTs = $ts->listFutureShift($idMgr, $intDateToday);
		$errFlag = false;
		$oldBegin = 0;
		$newBegin = 0;
		
		
		foreach($arrTs as $key => $v){
			if($oldBegin >0){
				
				$newBegin = $v['arra_shift_begin'];
				
				if( ($oldBegin - $newBegin) >= $scDiff ){
					$errFlag = true;
					
					echo $idMgr."<br />";
					echo date("Y-m-d",$oldBegin);
					echo "<br/>";
					echo date("Y-m-d",$newBegin);
					echo "<br/>";
				}
				$oldBegin = $newBegin;
			}	
			else{
				
				$oldBegin = $v["arra_shift_begin"];
			}
			
			
		}
		}
		
		if($errFlag){
			$mail = new Model_Emailshandler();
			$mail->sendNormalEmail("office@phonecollection.com.au","Got To Have a Check,Shop Manager 2 Days Break In a Roll","http://192.168.1.124/rosteraudit/check-manager-shift");
			
		}
		
		//var_dump($errFlag);
	
	}
	
	public function resetPwdAction(){
		
		date_default_timezone_set("Australia/Melbourne");
		$oldPwd = new Model_System_Oldpwd();
		
		$token = $this->_getParam("token");
		$idStaff = $this->_getParam("sid");
		$stDetail = new Model_DbTable_Roster_Stafflogindetail();
		
		if($token == Model_DatetimeHelper::dateToday()){
			
			$pwd = $stDetail->getDetail($idStaff);

			$oldPwd->addOldpwd($idStaff, $pwd['p']);
			
			$newPwd = "PWDRESET".date('U');
			
			$stDetail->updatePassword($idStaff, $newPwd);
		
			echo "STAFF ID:".$idStaff."PWD RESET DONE";
		}
		
		
	}
	public function updatePersonInfoAction(){
		
		date_default_timezone_set("Australia/Melbourne");
		
		$idStaff = base64_decode($this->_getParam("stid"));
		$stInfo = new Model_DbTable_Roster_Staffinfo();
		$stDetail = new Model_DbTable_Roster_Stafflogindetail();

		$stDetailBefore =  $stDetail->getDetail($idStaff);
		
		if($_POST){
			//d($_POST);
			
			$name = Model_EncryptHelper::sslPassword(strtolower(trim(str_replace(" ","",$_POST["given_name"]))." ".trim(str_replace(" ","",$_POST["surname"]))));
			$dob = Model_EncryptHelper::sslPassword($stDetail->formateDob($_POST["year"],$_POST["month"],$_POST["day"]));
			
			$stDetail->adminUpdate($idStaff, $name, $dob, ucwords(trim($_POST['pre_name'])));
			
			$stInfo->updateBasicStaffinfo($idStaff,ucwords(strtolower(trim(str_replace(" ","",$_POST["given_name"])))),ucwords(strtolower(trim(str_replace(" ","",$_POST["surname"])))),trim($_POST['gender']),trim($_POST['email_addr']),trim($_POST['mobile_no']),trim($_POST['addr']),trim($_POST['suburb']) ,trim($_POST['state']), trim($_POST['post_code']));
			//$stInfoDetail->addStaffinfo($idStaff,trim($_POST['given_name']), trim($_POST['surname']),trim($_POST['gender']),"", 0,0, , trim($_POST['mobile_no']), trim($_POST['addr']),trim($_POST['suburb']) ,trim($_POST['state']), trim($_POST['post_code']),"", -1);
			$stInfo->updatePersonalData($idStaff, $_POST['passport_no'],$_POST['driver_license'], $_POST['visa_type'], $_POST['visa_date'],$_POST['tfn_no'],$_POST['bank_bsb'],$_POST['bank_acc_no'],$_POST['bank_acc_name'], $_POST['card_no'], $_POST['medicare_no'], $_POST['student_no']);
			$stInfo->updateMyobInfor($idStaff, trim($_POST['id_card']), trim($_POST['id_record_bank']),trim($_POST['id_record_cash']), $_POST['id_package']);	
			$stInfo->updatePaymentInfo($idStaff, $_POST['old_rate'],$_POST['old_rate_date'],$_POST['new_rate'],$_POST['new_rate_date']);
			$stInfo->updateLeave($idStaff, $_POST['is_leave'], $_POST['date_leave']);
			
			if($_POST['is_leave']==9){
				
				// check 2 db  disactive 

					$stDetail->disActive($idStaff);
					$stInfo->disActiveStaff($idStaff);
				// chem main db if active, send email	
			}
			
			echo "<div id=\"notice_1\" class = \"notice\"><h2>Staff Info Has Been Updated</h2></div>";
		}
		
		$stLine = $stInfo->getStaffinfo($idStaff);
		$stDetailLine = $stDetail->getDetail($idStaff);
		
		$this->view->assign("stLine",$stLine);
		$this->view->nickName = ucwords($stDetailLine['ni']);
		$this->view->dob = date('Y-m-d',Model_EncryptHelper::getSslPassword($stDetailLine['d']));
	}
	
	private function writeArrHeadToFile($fileName){
		
		$arrFileHead = array("Emp. Co./Last Name","Emp. First Name","Payroll Category","Date","Units","Start/Stop Time","Emp. Card ID","Emp. Record ID");
				$strFileHead = implode("\t",$arrFileHead);
				$fl = fopen(getcwd()."/".$fileName,"w");
				fputs($fl,$strFileHead."\r\n");
				fclose($fl);
		
		
	}
	public function whTimeAction(){
		
		$arrStaff = array(1,286,289,290,291,295);
		
		$dateBegin = $this->_getParam("date_begin");
		$dateEnd = $this->_getParam("date_end");
		
		$arrDate = Model_DatetimeHelper::createDateArray($dateBegin, $dateEnd);
		
		$attn = new Model_DbTable_Roster_Attnrecord();
		
		$arrRes = array();
		
		foreach($arrStaff as $idStaff){
			foreach($arrDate as $dateCheck){
				$arrRes[$idStaff][$dateCheck] = $attn->getFirstOnLastOffDuty($idStaff, $dateCheck,"QLJw5KUijVObUW1J8Vn3dw==");
			}
		}
		
		$this->view->arrRes = $arrRes;	
		$this->view->arrDate = $arrDate;
		
		//d($arrRes);
	}
	
	public function extendPasswordAction(){
		
		$dateToday = Model_DatetimeHelper::dateToday();
		$neverExpire = "2037-12-31";
		
		$stDetail = new Model_DbTable_Roster_Stafflogindetail();
		$stInfo = new Model_DbTable_Roster_Staffinfo();	
		
		if($_POST){
			if(isset($_POST['btn_7days'])){
				
				$dateToExp = Model_DatetimeHelper::adjustDays("add", $_POST['expire_date'], 7);
				$stDetail->updateExpireDate($dateToExp,$_POST['id']);
			}
			
			if(isset($_POST['btn_14days'])){
				$dateToExp = Model_DatetimeHelper::adjustDays("add", $_POST['expire_date'], 14);
				$stDetail->updateExpireDate($dateToExp,$_POST['id']);			
			}
			
			if(isset($_POST['btn_never'])){
				$stDetail->updateExpireDate($neverExpire, $_POST['id']);
			}
			
		}
		
		
		
		
		$expireList = $stDetail->shouldExpire($neverExpire);
		$expListNew = array();
		
		
		foreach($expireList as $key => $v){
			$tmpArr = $v; 
			$stInfoLine = $stInfo->getStaffinfo($v['id']);
			$tmpArr['in_myob'] = false;
			if($stInfoLine){
				if($stInfoLine['id_record']!=0){
					$tmpArr['in_myob'] = true;
				}
			}
			$expListNew[] = $tmpArr;
		}
		$this->view->expireList = $expListNew;
		//d($this->view->expireList);	
	}
	
	public function offlineAttendanceAction(){
		
		$dateBegin = $this->_getParam("date_begin");
		$dateEnd = $this->_getParam("date_end");
		$do = $this->_getParam("do");
		
		$arrRes = array();
		$attn = new Model_DbTable_Roster_Attnrecord(Zend_Registry::get('db_real'));
			
		foreach(self::$arrShopMaping as $shop => $mapping){
			
			$invPro = Model_Aposinit::initAposInvPro($shop);
			
			$invListOn = $invPro->getOfflineOnduty($dateBegin, $dateEnd);
			$invListOff = $invPro->getOfflineOffduty($dateBegin, $dateEnd);
			
			$inv = Model_Aposinit::initAposInvoice($shop);
			
			$sales = new Model_DbTable_Roster_Salesmandetail(Zend_Registry::get('db_real'));
			
			foreach($invListOn as $invoiceLine){
				
				//d($invoiceLine);
				// DATE TIME SAL_CODE
				$invDetail = $inv->getInvoice($invoiceLine);
				$salesMan = $sales->getSalesMan($invDetail['SAL_CODE'], $shop);
				$arrRes[$shop][] = array($invoiceLine,date("Y-m-d",strtotime($invDetail['DATE'])),$invDetail['TIME'],$invDetail['SAL_CODE'],"ONDUTY",$salesMan['id_staff'],$invDetail['REF']);

			}
			foreach($invListOff as $invoiceLine){
				$invDetail = $inv->getInvoice($invoiceLine);
				$salesMan = $sales->getSalesMan($invDetail['SAL_CODE'], $shop);
				$arrRes[$shop][] = array($invoiceLine,date("Y-m-d",strtotime($invDetail['DATE'])),$invDetail['TIME'],$invDetail['SAL_CODE'],"OFFDUTY",$salesMan['id_staff'],$invDetail['REF']);				
			
			}
			
		}

		//d($arrRes);	
		$arrCanNot = array();
		$arrInsert = array();
		
		foreach($arrRes as $shop => $invs){
			
			foreach($invs as $detail){
					date_default_timezone_set('Australia/Melbourne');
					
				if($shop == "ADPC" || $shop  =="CLPC" || $shop  =="CLIC" || $shop =="WLIC"){
				
					date_default_timezone_set('Australia/Adelaide');
				}	
				
				$shopHead =  Model_EncryptHelper::sslPassword($shop);
				$ip = '0.0.0.1';
				
				$invTime = date("U",strtotime($detail[1]." ".$detail[2].":00"));
				$onStatus = $attn->createStatusOnDuty();
				$offStatus = $attn->createStatusOffDuty();
				$status= ($detail[4]=="ONDUTY")?$onStatus:$offStatus;
			
				if($detail[5]>0 && trim($detail[6])==""){
					
					if(!$attn->getSuInsertRecord($detail[5], $invTime, $shopHead, $detail[4])){
						
						if($do == "yes"){
							$attn->addRecord($detail[5], $invTime, $ip, $shopHead, $status);
						}
						//$attn->get
							
							
						$arrInsert[] = array($shop,$detail[0],$detail[1],$detail[2],$detail[3],$detail[4],$detail[5],$detail[6]);						
						
					}
					

				
				}
				else{
					
					//$arrCanNot[] = array($shop,$detail[0],$detail[1],$detail[2],$detail[3],$detail[4],$detail[5],$detail[6]);
					
					
					if($detail[5]>0){
						if(!$attn->getSuInsertRecord($detail[5], $invTime, $shopHead, $detail[4])){	
							$arrCanNot[] = array($shop,$detail[0],$detail[1],$detail[2],$detail[3],$detail[4],$detail[5],$detail[6]);
						}
					}
					else{
						
						$arrCanNot[] = array($shop,$detail[0],$detail[1],$detail[2],$detail[3],$detail[4],$detail[5],$detail[6]);
					}
					
				}
			}
			
			
		}
		
		//d($arrInsert,$arrCanNot);
		$this->view->arrInsert = $arrInsert;
		$this->view->arrCanNot = $arrCanNot;
				
		
		if($_POST){
			foreach($_POST['is_select'] as $key => $v){
				
				$shop = $_POST['shop'][$key];
				
				date_default_timezone_set('Australia/Melbourne');
					
				if($shop == "ADPC" || $shop  =="CLPC" || $shop  =="CLIC" || $shop =="WLIC"){
				
					date_default_timezone_set('Australia/Adelaide');
				}
				
				
				$shopHead =  Model_EncryptHelper::sslPassword($shop);
				$ip = '0.0.0.1';
				
				$invTime = date("U",strtotime($_POST['date'][$key]." ".$_POST['time'][$key]));
				$onStatus = $attn->createStatusOnDuty();
				$offStatus = $attn->createStatusOffDuty();
				$status= ($_POST['on_off'][$key]=="ONDUTY")?$onStatus:$offStatus;
				
				$attn->addRecord($_POST['id'][$key], $invTime, $ip, $shopHead, $status);
				
			}
			
			
		}
		
	}
}
?>