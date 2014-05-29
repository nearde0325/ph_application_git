<?php
/*
 this is the class which send automatic email when:
Something Done:

Something Wrong:
1) If download failed, Email me


*/
class Model_DbTable_Repairjob  extends Zend_Db_Table_Abstract {
	
	protected $_name = 'repair_jobs';
	
	public function getJob($idJob){

		$row = $this->fetchRow('`id_job`= '.$idJob);
		if(!$row){
			return false;
		}
		return $row->toArray();
		
	}
	
	public function addJob($jobId,$shopCode,$staffName,$dateStart,$timeStart,$brand,$model,$imei,$partsSelection,$repairType,$issueDetail,$cname,$cemail,$cphone,$cmobile,$price,$invoice,$passcode,$color,$pickup,$warrTerm,$extWarr,$warrPrice,$expireDate = null){
		
		$data = array(
		'id_job' => $jobId,
		'shop_code'	=> $shopCode,
		'staff_name' => $staffName,
		'date_start' => $dateStart,
		'time_start' => $timeStart,
		'mobile_brand' => $brand,
		'mobile_model' => $model,
		'mobile_imei' => $imei,
		'parts_selection' => $partsSelection,
		'repair_type' => $repairType,
		'issue_detail' => $issueDetail,
		'customer_name' =>$cname,
		'customer_email'=>$cemail,
		'customer_phone'=>$cphone,
		'customer_mobile'=>$cmobile,
		'quot_price'=> $price,
		'invoice_no'=>$invoice,	
		'mobile_passcode'=> $passcode,
		'mobile_color' => $color,	
		'pickup_detail' => $pickup,
		'warranty_term'=> $warrTerm,
		'extended_warranty'=> $extWarr,
		'warranty_price'=> $warrPrice,
		'warranty_date'=>$expireDate																																					
		);
		
	        try{
			$this->insert($data);
		}
		catch(Exception $e){
			//print_r($e);
			
			return false;
			}
		return true;
		
		
	}
	
	public function listUnfinishedJobByShop($shopHead){
		$db = $this->getAdapter();
		//date_default_timezone_set('Australia/Melbourne');
		//$dateToday=date('Y-m-d');
		$sqlstr = "SELECT * FROM `repair_jobs`,`repair_status_record` WHERE";
		$sqlstr.= " `repair_jobs`.`id_job` = `repair_status_record`.`id_job` AND ";
		$sqlstr.= " `repair_jobs`.`shop_code` = '".$shopHead."' AND";
		$sqlstr.= " `repair_status_record`.`id_status` < 80  AND (`repair_jobs`.`id_job` NOT IN (SELECT `id_job` FROM `repair_status_record` WHERE `id_status` >=80 )) ";
		$sqlstr .= " ORDER BY `repair_jobs`.`id_job` ASC;";
		
		$stmt = $db->prepare($sqlstr);
		$stmt->execute();
		$result = $stmt->fetchAll();
		return $result;
		
		
	}
	
	public function listUnfinishedJobByShop_140516($shopHead){
		
		$db = $this->getAdapter();
		
		$sqlStr = "SELECT * FROM `repair_jobs`,`repair_status_record` WHERE  `repair_jobs`.`id_job` = `repair_status_record`.`id_job` AND  `repair_status_record`.`id_status` < 80 AND ";
		if($shopHead == "BSPC" || $shopHead == "BSIC" || $shopHead == "BSXP"  ){
		$sqlStr .= "(`repair_jobs`.`shop_code` = 'BSPC' || `repair_jobs`.`shop_code` = 'BSIC' || `repair_jobs`.`shop_code` = 'BSXP') ";	
		$sqlStr .= " ORDER BY `repair_jobs`.`shop_code` ASC , `repair_jobs`.`id_job` DESC ;";	
		}
		if($shopHead == "WBPC" || $shopHead == "WBIC" ){
			$sqlStr .= "(`repair_jobs`.`shop_code` = 'WBPC' || `repair_jobs`.`shop_code` = 'WBIC') AND (`repair_jobs`.`id_job` NOT IN (SELECT `id_job` FROM `repair_status_record` WHERE `id_status` >=80 )) ";
			$sqlStr .= " ORDER BY `repair_jobs`.`id_job` DESC ;";			
			
		}
		
		/*
		if($shopHead == "WGPC" || $shopHead == "WGIC"){
				
				
		}
		if($shopHead == "PMPC" || $shopHead == "PMIC" ){
				
				
		}
		if($shopHead == "CLPC" || $shopHead == "CLIC" ){
				
				
		}
		
		*/
		
		$stmt = $db->prepare($sqlStr);
		$stmt->execute();
		$result = $stmt->fetchAll();
		return $result;	
	}
	
	public function listUnfinishedJobByShopMultiShopView($shopHead,$arrBuddyShop){
		
		$arrRes = array();
		$arrRes[0] = self::listUnfinishedJobByShop($shopHead);
		
		foreach ($arrBuddyShop as $shop){
			$arrRes[$shop] = self::listUnfinishedJobByShop($shop);
		}
		return $arrRes;
	}
	
	
	public function listUnfinishedJobById($idJob){
		$db = $this->getAdapter();
		//date_default_timezone_set('Australia/Melbourne');
		//$dateToday=date('Y-m-d');
		$sqlstr = "SELECT * FROM `repair_jobs`,`repair_status_record` WHERE";
		$sqlstr.= " `repair_jobs`.`id_job` = `repair_status_record`.`id_job` AND ";
		$sqlstr.= " `repair_jobs`.`id_job` = '".$idJob."' AND";
		$sqlstr.= " `repair_status_record`.`id_status` < 80 ;";
	
		$stmt = $db->prepare($sqlstr);
		$stmt->execute();
		$result = $stmt->fetchAll();
		return $result;
	
	
	}
	public function listAllJobById($idJob){
		$db = $this->getAdapter();
		//date_default_timezone_set('Australia/Melbourne');
		//$dateToday=date('Y-m-d');
		$sqlstr = "SELECT * FROM `repair_jobs`,`repair_status_record` WHERE";
		$sqlstr.= " `repair_jobs`.`id_job` = `repair_status_record`.`id_job` AND ";
		$sqlstr.= " `repair_jobs`.`id_job` = '".$idJob."' ;";
		//$sqlstr.= " `repair_status_record`.`id_status` < 80 ;";
	
		$stmt = $db->prepare($sqlstr);
		$stmt->execute();
		$result = $stmt->fetchAll();
		return $result;
	
	
	}		
	
	public function listAllUnfinishedJobs(){
		
		$db = $this->getAdapter();
		//date_default_timezone_set('Australia/Melbourne');
		//$dateToday=date('Y-m-d');
		$sqlstr = "SELECT * FROM `repair_jobs`,`repair_status_record` WHERE";
		$sqlstr.= " `repair_jobs`.`id_job` = `repair_status_record`.`id_job` AND ";
		$sqlstr.= " `repair_status_record`.`id_status` < 80 ;";
		
		$stmt = $db->prepare($sqlstr);
		$stmt->execute();
		$result = $stmt->fetchAll();
		return $result;		
		
	}
	
	public function listAllfinishedJobs(){
	
		$db = $this->getAdapter();
		//date_default_timezone_set('Australia/Melbourne');
		//$dateToday=date('Y-m-d');
		$sqlstr = "SELECT * FROM `repair_jobs`,`repair_status_record` WHERE";
		$sqlstr.= " `repair_jobs`.`id_job` = `repair_status_record`.`id_job` AND ";
		$sqlstr.= " `repair_status_record`.`id_status` > 80 ;";
	
		$stmt = $db->prepare($sqlstr);
		$stmt->execute();
		$result = $stmt->fetchAll();
		return $result;
	
	}	
	
	public function listAllFinishedJobsByDate($dateBegin,$dateEnd){
		
		$db = $this->getAdapter();
		//date_default_timezone_set('Australia/Melbourne');
		//$dateToday=date('Y-m-d');
		$sqlstr = "SELECT * FROM `repair_jobs`,`repair_status_record` WHERE";
		$sqlstr.= " `repair_jobs`.`id_job` = `repair_status_record`.`id_job` AND ";
		$sqlstr.= " `repair_status_record`.`time_record` > '".$dateBegin." 00:00:00' AND `repair_status_record`.`time_record` < '".$dateEnd." 23:59:00' AND ";
		$sqlstr.= " `repair_status_record`.`id_status` = 90 ;";
		
		$stmt = $db->prepare($sqlstr);
		$stmt->execute();
		$result = $stmt->fetchAll();
		return $result;		
		
	}
	
	
	public function listAllfinishedJobsByShop($shopHead,$peroid = 3){
	
		$db = $this->getAdapter();
		
		$dateBegin = Model_DatetimeHelper::getFirstDayOfTheMonth(Model_DatetimeHelper::dateToday());
		$dateActBegin = Model_DatetimeHelper::adjustMonths("sub", $dateBegin, $peroid);
		
		
		$sqlstr = "SELECT * FROM `repair_jobs`,`repair_status_record` WHERE";
		$sqlstr.= " `repair_jobs`.`id_job` = `repair_status_record`.`id_job` AND `repair_jobs`.`shop_code` LIKE '".$shopHead."' AND ";
		$sqlstr.= " `repair_jobs`.`date_start` >= '".$dateActBegin."' AND ";
		$sqlstr.= " `repair_status_record`.`id_status` >= 80 ";
		$sqlstr .= " ORDER BY `repair_status_record`.`time_record` DESC ; ";
	
		$stmt = $db->prepare($sqlstr);
		$stmt->execute();
		$result = $stmt->fetchAll();
		return $result;
	
	}	
	public function getQuotePrice($idjob){
		$row = self::getJob($idjob);
		return $row['quot_price'];
		
	}
	public function updateQuotePrice($idjob,$price,$ifsecondQuote){
		date_default_timezone_set('Australia/Melbourne');
		$dateToday=date('Y-m-d');
		//$data = array();
		$data = array(
				'quot_price' => $price
		);		
		
		if($ifsecondQuote){
			$data['quot_change_date'] = $dateToday;
			$data['quot_changed'] = 1 ;			
		}
			
		$this->update($data,'`id_job` ='.$idjob);	
	}
	
	public function getInvoice($idjob){
		$row = self::getJob($idjob);
		return $row['invoice_no'];
		
	}
	public function updateInvoice($idjob,$invoiceno){
		$data = array(
				'invoice_no' => $invoiceno
		);
		$this->update($data,'`id_job` ='.$idjob);		
	}
	public function updateInvoice2($idjob,$invoiceno){
		
		$data = array(
				'invoice_no2' => $invoiceno
		);
		$this->update($data,'`id_job` ='.$idjob);		
		
	}
	public function getJobLaterThan($date){
		
		$rows = $this->fetchAll("`date_start` > '".$date."'",'id_job');		
		return $rows->toArray();		
		}
	public function ifQuoteChange($idjob){
		$row = self::getJob($idjob);
		if($row['quot_changed']){ return true;}
		
		return false;		
	}
	public function ifFirstInvoiced($idjob){
		$row = self::getJob($idjob);
		if($row['invoice_no']!="") {
			return true;
		}
		return false;		
		
	}
	public function ifSecondInvoiced($idjob){
		$row = self::getJob($idjob);
		if($row['invoice_no2']!="") {return true;}
		return false;		
	}
	public function updateRepairStaff($idJob,$staffID){
		$data = array(
				'repair_staff' => $staffID
		);
		$this->update($data,'`id_job` ='.$idJob);		
	}
	
	public function getRepairStaff($idJob){
		$row = self::getJob($idJob);
		return $row["repair_staff"];
	}
	public function custPartRecord($idJob,$isCorrect,$comment){
		$data = array(
				'cust_part' => 1,
				'cust_part_correct' => $isCorrect,
				'cust_part_comm' => $comment,
				'cust_part_date' => Model_DatetimeHelper::dateToday()
		);
		$this->update($data,'`id_job` ='.$idJob);		
	}
	public function bonusRule($idJob){
		
		$row = self::getJob($idJob);
		
		$custTest = false;
		
		$custEmail = $row['customer_email'];
		$custPhone = $row['customer_phone'];
		$custMobile = $row['customer_mobile'];
		$custPart = $row['cust_part'];
		
		$cETest = (strpos($custEmail,"@") && strlen($custEmail) >5)?true: false;
		$cPTest = (strlen($custPhone) > 7 && is_numeric($custPhone))?true: false;
		$cMTest = (strlen($custMobile) > 7 && is_numeric($custMobile))?true: false;
		
		
		
		if($cETest || $cPTest || $cMTest ){
			$custTest = true;
		}
		
		
		return array($custTest,$custPart);
	}
	
	public function getJobIdByIMEI($imei){
		
		$db = $this->getAdapter();
		//date_default_timezone_set('Australia/Melbourne');
		//$dateToday=date('Y-m-d');
		$sqlstr = "SELECT * FROM `repair_jobs`,`repair_status_record` WHERE";
		$sqlstr.= " `repair_jobs`.`id_job` = `repair_status_record`.`id_job` AND ";
		$sqlstr.= " `repair_jobs`.`mobile_imei` LIKE '".$imei."' ;";
		//$sqlstr.= " `repair_status_record`.`id_status` < 80 ;";
	
		$stmt = $db->prepare($sqlstr);
		$stmt->execute();
		$result = $stmt->fetchAll();
		return $result;
	}
	public function currentOpenJobList($searchResult){
		$arrRes = array();
		$arrId = array();
		if(empty($searchResult)) return false;
		foreach($searchResult as $k => $v){
			
			//d($arrRes);
			$brand = $v['mobile_brand'];
			$model = $v['mobile_model'];
			if(isset($arrRes[$brand][$model])){
				if(!in_array($v['id_job'],$arrId)){
				$arrRes[$brand][$model] += 1;
				$arrId[] = $v['id_job'];
				}
			}
			else{
				if(!in_array($v['id_job'],$arrId)){
				$arrRes[$brand][$model] = 1;
				$arrId[] = $v['id_job'];
				}
			}
			
		}
		
		return $arrRes;
	}
	public function currentOpenJobListMultiShop($searchResult){
		$arrRes = array();
		foreach($searchResult as $shop => $detail){
			$arrRes[$shop] = self::currentOpenJobList($detail);
		}
		
		return $arrRes;
		
	}
	

}
?>