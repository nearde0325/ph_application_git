<?php
//namespace application\models\DbTable\Roster;

class Model_DbTable_Roster_Attnrecord extends Zend_Db_Table_Abstract {
	
	protected $_name = 'att_record';
	
	public function getrecord($id){
		$row = $this->fetchRow("`id_record` =".$id);
		if(!$row){return false;}
		return $row->toArray();
		
	}
	
	public function addRecord($staffId,$time,$ip,$shopHead,$status){
		
		
		$data = array(
				"sti" => $staffId,
				"ti"  => $time,
				"ip"  => $ip,
				"sh"  => $shopHead,
				"sts" => $status
				);
		
		$this->insert($data);
	}
	
	public function getLastStatus($staffId){
		
		$row = $this->fetchRow("`sti` =".$staffId,"ti DESC");
		if(!$row){
			return false;
		}
		return $row->toArray();		
				
	}
	
	public function getLastStatusByDate($staffId,$dateCheck){
		
		$rows = self::listRecordByStaffId($staffId, $dateCheck, $dateCheck);
		if(!$rows) return 3;
		$row = end($rows);
		return self::getLastOnOffDuty($row["sts"]);
			
	}
	
	public function createStatusOnDuty(){
		
		$afterFix = rand(3333333,9999999);
		
		do{
			$random = rand(1,9);			
		}while($random %2 == 0);
		
		$result = $random*10000000 + $afterFix;
		
		return $result;
	}
	
	public function createStatusOffDuty(){
		$afterFix = rand(2222222,7777777);
		
		do{
			$random = rand(1,9);
		
		}while($random %2 != 0);
		
		$result = $random*10000000 + $afterFix;
		
		return $result;		
				
	}
	
	public function getLastOnOffDuty($number){
		
		if(!isset($number) || $number=="" || $number == 0) return 2;
		$result = (int)floor($number/10000000);
		if($result % 2 ==0 ){
			return 2;
		} 
		
		return 1;
		
	}
	public function getOndutyByDateByShop($shopHead,$dateCheck){

		$intDateBegin = date("U",strtotime($dateCheck));
		$intDateEnd = date("U",strtotime($dateCheck))+86400;

		$whereStr = "`sh` LIKE '".Model_EncryptHelper::sslPassword($shopHead)."' AND `ti` > ".$intDateBegin." AND `ti`< ".$intDateEnd;	
		$select = $this->getAdapter()->select();
		
		$select->distinct()
			   ->from($this->_name,"sti")
			   ->where($whereStr);
		$rows = $this->getAdapter()->fetchAll($select);
		if(!$rows) return false;
		
		return $rows;
		
		
		
	}
	public function getShopHeadByStaffId($idStaff){
		
		$whereStr = "`sti` = ".$idStaff;
		$select = $this->getAdapter()->select();
		
		$select->distinct()
				->from($this->_name,"sh")
				->where($whereStr);
		
		$rows = $this->getAdapter()->fetchAll($select);
		if(!$rows) return false;
		
		return $rows;
	}
	
	public function getShopHeadByStaffIdByDate($idStaff,$dateBegin,$dateEnd){
		
		$intDateBegin = date("U",strtotime($dateBegin));
		$intDateEnd = date("U",strtotime($dateEnd))+86400;		
		
		$whereStr = "`sti` = ".$idStaff." AND `ti` > ".$intDateBegin." AND `ti`< ".$intDateEnd;	
		
		$select = $this->getAdapter()->select();
		
		$select->distinct()
		->from($this->_name,"sh")
		->where($whereStr);
		
		$rows = $this->getAdapter()->fetchAll($select);
		if(!$rows) return false;
		
		return $rows;		
		
	}
	
	public function listRecordByStaffId($id,$dateBegin,$dateEnd){

		$intDateBegin = date("U",strtotime($dateBegin));
		$intDateEnd = date("U",strtotime($dateEnd))+86400;
		
		$rows = $this->fetchAll("`sti` = ".$id." AND  `ti` >= ".$intDateBegin." AND `ti` <= ".$intDateEnd, "ti ASC" );
		
		if(!$rows){
			return false;
		}
		return $rows->toArray();		
		
	}
	public function listRecordByShopByStaffId($id,$shopHead,$dateBegin,$dateEnd){
		
		$intDateBegin = date("U",strtotime($dateBegin));
		$intDateEnd = date("U",strtotime($dateEnd))+86400;
		
		$rows = $this->fetchAll("`sti` = ".$id." AND `sh` LIKE '".$shopHead."' AND `ti` >= ".$intDateBegin." AND `ti` <= ".$intDateEnd, "ti ASC" );
		
		if(!$rows){
			return false;
		}
		return $rows->toArray();	
	
	}
	
	
	public function getRecordByShop($shopHead,$dateBegin,$dateEnd){
		
		$intDateBegin = date("U",strtotime($dateBegin));
		$intDateEnd = date("U",strtotime($dateEnd))+86400;

		$rows = $this->fetchAll("`sh` LIKE '".$shopHead."'  AND  `ti` >= ".$intDateBegin." AND `ti` <= ".$intDateEnd, "ti ASC" );
		
		if(!$rows){
			return false;
		}
		return $rows->toArray();		
	}
	
	public function restructure($resultArray){

	//build tempeorary table  

		$pm6 = "18:00:00";
		$soh = new Model_DbTable_Shopopenhour();
		
		$config = $this->getAdapter()->getConfig();
		$db = Zend_Db::factory('PDO_MYSQL', $config);
		$sql = "CREATE TEMPORARY TABLE tmp_att_record  (
 			`id_tmp` int(11) NOT NULL AUTO_INCREMENT,
  			`staff_id` int(11) NOT NULL,
  			`date` date DEFAULT NULL,
  			`od_time` time DEFAULT NULL,
  			`od_shop` varchar(10) DEFAULT NULL,
  			`fd_time` time DEFAULT NULL,
  			`fd_shop` varchar(10) DEFAULT NULL,
  			`od_round_time` int(11) DEFAULT NULL,
  			`fd_round_time` int(11) DEFAULT NULL,
  			`time_gap_late_come` int(11) DEFAULT NULL,
  			`time_gap_early_leave` int(11) DEFAULT NULL,
  			`time_gap_before_6` int(11) DEFAULT NULL,
  			`time_gap_after_6` int(11) DEFAULT NULL,
  			`work_time_before_6` int(11) DEFAULT NULL,
  			`work_time_after_6` int(11) DEFAULT NULL,
  			`with_issue` int(2) NOT NULL DEFAULT 0, 
  			PRIMARY KEY (`id_tmp`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

		$executed = $db->query($sql);		
		
		$arrCount = count($resultArray);
		
		//var_dump($resultArray);
		
		
		echo $arrCount;
		
		$arrayPair = array();
		// read pairs 
		
		//  STAFF ID 0 | DATE 1 | ON TIme 2| On Shop 3 | OFF TIME 4 | Off Shop 5 | On Time Round 6 | Off Time Round 7 | IS First 8 | is Last 9 | TIme Gap Late 10 |
		// Time Gap Early 11 |time Gap before 6 12| time Gap After 6 13| work time before 6 14| work time after 6 15| with issue 16|   
		
		for($i=0;$i<=$arrCount;$i++){
			
			$tmpArr = array_fill(0,17,"");
			//Staff ID 
			$tmpArr[0] = $resultArray[$i]["sti"];
			//is the First Pair
			//$tmpArr[1] = $resultArray
			if($i==0){$tmpArr[9] = 1;}
			//is the Last Pair
			if($arrCount-$i<2){$tmpArr[10] = 1;}
			//check status 
			$onOffStatus = self::getLastOnOffDuty($resultArray[$i]["sts"]);
			if($onOffStatus ==1 ){
			//normal, add this one into line 
			//date 
			$tmpArr[1] = date("Y-m-d",$resultArray[$i]["ti"]);
			//Opening time 
			$tmpArr[2] = date("H:i:s",$resultArray[$i]["ti"]);
			//Opening shop
			
			
			
			
				
				
				
				
				
				
				
				
				
				
			}
			if($onOffStatus == 2){
			
			//missing the on duty , record error 
				$tmpArr[16] += 1;
			// put data into off duty
				if($tmpArr[1]==""){ $tmpArr[1] = date("Y-m-d",$resultArray[$i]["ti"]);}
			// put off time 
				$tmpArr[4] = date("H:i:s",$resultArray[$i]["ti"]);
			// put off shop 	
				$tmpArr[5] = Model_EncryptHelper::getSslPassword($resultArray[$i]["sh"]);
			// already mark error, the on duty shop is empty, the off shop different from on shop , error again  
				$tmpArr[16] += 3;
			// if this is not the last close , check time gap 
					if($tmpArr[10] != 1){
				//check  next 
				$nextOnOffDuty = self::getLastOnOffDuty($resultArray[$i+1]["sts"]);
						if($nextOnOffDuty == 2 ){
					
					// did 2 off , can not calculat anything do nothing 	
						}
				
						if($nextOnOffDuty ==1){
					//that is on duty , did the correct thing 
					//check if pass 6 pm 	
					$nextOnDuty = 	date("H:i:s",$resultArray[$i+1]["ti"]);
					
							if(strtotime($nextOnDuty)<strtotime($pm6)){
						// only record  before 6 pm 
							$tmpArr[12] = strtotime($nextOnDuty) - strtotime($tmpArr[4]);
							}
							elseif (strtotime($tmpArr[4])<strtotime($pm6) && strtotime($nextOnDuty)>= strtotime($pm6) ) {
						//record before and after 6 pm time gap	
								$tmpArr[12] = strtotime($pm6)- strtotime($tmpArr[4]);
								$tmpArr[13] = strtotime($nextOnDuty) - strtotime($pm6);
							}
							else{
						// off duty >6 and next on duty > 6 , record after 6 pm 	
							$tmpArr[13] = strtotime($nextOnDuty) - strtotime($tmpArr[4]);								
							}
					
						}
				
					}
				//last off duty see if early than shoping center closing time 
					if($tmpArr[10] == 1){
					$closeTime = $soh->getShopCloseTime(Model_EncryptHelper::getSslPassword($resultArray[$i]["sh"]),date("Y-m-d",$resultArray[$i]["ti"]));
						if(strtotime($tmpArr[4])<strtotime($closeTime)){						
						$tmpArr[11] = strtotime($closeTime)- strtotime($tmpArr[4]);
					
						}
						else{
					//las log off late then closing time 
							if(strtotime($tmpArr[4])-strtotime($closeTime) > 25*60){
							//can not round 	
							//notice ??
							
							}	
							else{
							//save into round time 
							
							$tmpArr[7] = $closeTime;
							}	
							
						}	
					
					}
				
			//Conditon off state = 2 which is error 
			//direct read next 	
			//with no ON duty , will not calcuate Working time 
			}
						
		}			

		
		
		
		
	}
	
	public function getFirstOnLastOffDuty($idStaff,$dateCheck,$shopHead){
		
		$filled = false;
		$arrResult = array(0=>"",1=>"",2=>false,3=>false);		
		$list = self::listRecordByShopByStaffId($idStaff, $shopHead, $dateCheck, $dateCheck);
		
		foreach($list as $key => $v){
			if(self::getLastOnOffDuty($v["sts"])==1){
				if(!$filled){
					$arrResult[0] = $v["ti"];
					$arrResult[2] = ($v["ip"]=="0.0.0.0")?true:false;
					$arrResult[4] = ($v["ip"]=="0.0.0.1")?true:false;
					$filled = true;
				}
			}
			else{
				$arrResult[1]= $v["ti"];
				$arrResult[3] = ($v["ip"]=="0.0.0.0")?true:false;
				$arrResult[5] = ($v["ip"]=="0.0.0.1")?true:false;
			}
		}
		
		return $arrResult;
	}

	public function compareStartTime($shiftSt,$ActSt){
		
		if($ActSt == "" || $shiftSt == "") return START_NO_RECORD;		
		if($shiftSt - $ActSt > 900) return START_EARLY;
		if($shiftSt - $ActSt < -900) return START_LATE;
		return 0;
	
	}
	
	public function compareFinishTime($shiftSt,$ActSt){
		
		if($ActSt == "" || $shiftSt == "") return FINISH_NO_RECORD;
		if($shiftSt - $ActSt > 900) return FINISH_EARLY;
		if($shiftSt - $ActSt < -900) return FINISH_LATE;
		return 0;		
	}
	public function checkEarlyBird(){
		
	}
	
	public function getSuInsertRecord($idStaff,$invTime,$shopHead,$statusStr){
		$wherestr = " (`ip` LIKE '0.0.0.0' OR `ip` LIKE '0.0.0.1' ) AND ( `sti` = {$idStaff} ) AND ( `ti` = {$invTime} ) AND ( `sh` LIKE '{$shopHead}' ) ";
		
		$row = $this->fetchRow($wherestr);
		
		//d($row);
		
		if($row){
		$number = $this->getLastOnOffDuty($row['sts']);
		if(($number == 1 && $statusStr == "ONDUTY") || ($number == 2 && $statusStr == "OFFDUTY") ){
			return true;
		}
		}
		return false;
	}
	
	public function lastDayLogin($idStaff){
		
		date_default_timezone_set("Australia/Melbourne");
		
		$row = $this->fetchRow("`sti` = {$idStaff}","ti DESC");
		if(!$row){
			return false;
		}
		$intTimeNow = date("U");
		
		$dayDiff = floor(($intTimeNow - $row['ti']) /(3600*24));
		
		return array(date("Y-m-d",$row['ti']),$dayDiff);
		
	}
}

?>