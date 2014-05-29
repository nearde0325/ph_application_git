<?php
//namespace application\models\DbTable\Apos;

class Model_DbTable_Apos_Invprods extends Zend_Db_Table_Abstract {

	
	public function getInvoiceProducts($invNo){

		$rows = $this->fetchAll("INV_NO  LIKE '".$invNo."'");
		//d($rows);
		if(!$rows) return false;
		return $rows->toArray();
		
	}
	
	
	
	public function getRepairInvoice($invNo){
		
		$rows = $this->fetchRow("INV_NO LIKE '".$invNo."' AND (SCODE LIKE 'PHONEREPAIR' OR SCODE LIKE 'PHONE-REPAIR')");
		if(!$rows) return false;
		return $rows["AMOUNT"];	
	}
	
	public function getInvoiceDiscount($invNo){
		$totalDiscount = 0;
		
		$rows = self::getInvoiceProducts($invNo);
		if(!$rows) return false;
		foreach($rows as $k => $v){
			
			$totalDiscount +=$v["ITEM_DISCAMT"];	
		}
		
		return $totalDiscount;
		
	}
	public function getRepairTotalByDate($dateBegin,$dateEnd){
		
		$res = 0;
		$arrDate = Model_DatetimeHelper::createDateArray($dateBegin, $dateEnd,"");
		
		foreach($arrDate as $date){
			$strDate =substr($date,2);
			$strWhere = "(SCODE LIKE 'PHONEREPAIR' OR SCODE LIKE 'PHONE-REPAIR') AND INV_NO LIKE '%".$strDate."%'";
			
			$rows = $this->fetchAll($strWhere)->toArray();
			if(!empty($rows)){
				foreach($rows as $row){
			
					$res += $row["AMOUNT"];
				}
			}	
		}
		
		return $res;
	}
	
	public function getProductSalesByDate($dateBegin,$dateEnd,$BarCode){
		
		$res = 0;
		$emptyCount = 0;
		$arrDate = Model_DatetimeHelper::createDateArray($dateBegin, $dateEnd,"");
		$totalDate = count($arrDate);
		foreach($arrDate as $date){
			$strDate =substr($date,2);
			$strWhere = "SCODE = '".$BarCode."' AND INV_NO LIKE '%".$strDate."%'";
			$rows = $this->fetchAll($strWhere)->toArray();
			if(!empty($rows)){
			foreach($rows as $row){
				
				$res += $row["QTY"];
			}
			
			}
			else{
				$emptyCount++;
			}
			
		}
		$hotRate = ($totalDate - $emptyCount)/$totalDate;
		return array($res,$hotRate);	
	}
	
	
	public function getProductSalesQtyByDate($dateBegin,$dateEnd,$barCode){
		
		
		$res = 0;
		$arrDate = Model_DatetimeHelper::createDateArray($dateBegin, $dateEnd,"");
		$totalDate = count($arrDate);
		foreach($arrDate as $date){
			$strDate =substr($date,2);
			$strWhere = "SCODE = '".$barCode."' AND INV_NO LIKE '%".$strDate."%'";
			$rows = $this->fetchAll($strWhere)->toArray();
			if(!empty($rows)){
				foreach($rows as $row){
		
					$res += $row["QTY"];
				}
					
			}
		}
		return $res;		
	}
	
	public function getProductSalesQtyByDates($dateBegin,$dateEnd,$barCode){
		
		$res = 0;
		$arrDate = Model_DatetimeHelper::createDateArray($dateBegin, $dateEnd,"");
		$strDateHead =  "SCODE LIKE '".$barCode."' AND (";
		$strTmp = "";
		
		foreach($arrDate as $date){

			$strDate =substr($date,2);
			$strTmp.=" INV_NO LIKE '%".$strDate."%' OR";		
		}
		
		$strWhere = $strDateHead.substr($strTmp,0,-2)." )";
		$rows = $this->fetchAll($strWhere)->toArray();
		if(!empty($rows)){
			foreach($rows as $row){
				
				$res += $row["QTY"];
			}
				
		}
		
		return $res;
	}
	
	public function getProductSalesInvoiceByDates($dateBegin,$dateEnd,$barCode){
	
		$arrRes = array();
		$arrDate = Model_DatetimeHelper::createDateArray($dateBegin, $dateEnd,"");
		$strDateHead =  "SCODE LIKE '".$barCode."' AND (";
		$strTmp = "";
	
		foreach($arrDate as $date){
	
			$strDate =substr($date,2);
			$strTmp.=" INV_NO LIKE '%".$strDate."%' OR";
		}
	
		$strWhere = $strDateHead.substr($strTmp,0,-2)." )";
		$rows = $this->fetchAll($strWhere)->toArray();
		if(!empty($rows)){
			foreach($rows as $row){
	
				$arrRes[] = $row['INV_NO'];
			}
	
		}
	
		return $arrRes;
	}
	
		
	public function ifProductSold($BarCode,$dateBegin = null){
		
		$result = false;
		
		$year="13";
		$date ="130101000"; 
		
		//$whereStr1 = "SCODE = '".$BarCode."' AND INV_NO LIKE 'C".$year."%' AND INV_NO > 'C".$date."'";
		
		//$rows = $this->fetchRow($whereStr1);
		//if($rows) {$result = true;}
		$whereStr2 = "SCODE = '".$BarCode."'";
		//$whereStr2 = "SCODE = '".$BarCode."' AND INV_NO LIKE 'L".$year."%' AND INV_NO > 'L".$date."'"; 
		
		$rows2 = $this->fetchRow($whereStr2,array("INV_NO DESC"));
		if($rows2) {
			$result = $rows2["INV_NO"];
		}
		
		return $result;
	}
	
	public function maxSold($BarCode,$dateBegin,$dateEnd){
		
		//date Bein is a monday // date end is a sunday , 3 weeks
		
		date_default_timezone_set("Australia/Melbourne");
		
		$maxQty = 0;
		
		for($i = 0;$i<3;$i++){
			echo $weekBegin = Model_DatetimeHelper::adjustDays("add", $dateBegin,$i*7);
			echo $weekEnd = Model_DatetimeHelper::adjustDays("add", $dateBegin,$i*7+6); 
			$qty = self::getProductSalesByDate($weekBegin, $weekEnd, $BarCode);
			
			if($qty[0] > $maxQty){
				$maxQty = $qty[0];
			}
			
		} 	
		
		return $maxQty;
	}
	
	public function maxSoldWeeks($sCode,$dateEnd,$qtyWeeks){
		
		date_default_timezone_set("Australia/Melbourne");
		
		$maxQty = 0;
		for($i=0;$i<$qtyWeeks;$i++){
			$weekEnd = Model_DatetimeHelper::adjustDays("sub", $dateEnd, $i*7);
			$weekBegin = Model_DatetimeHelper::adjustDays("sub",$dateEnd,($i*7 + 6));
			$qty = self::getProductSalesQtyByDates($weekBegin, $weekEnd, $sCode);
			
			if($qty > $maxQty){
				$maxQty = $qty;
			}
						
		}
		return $maxQty;	
		
	}
	
	public function isHotItem($barCode){
		
		$dateEnd = Model_DatetimeHelper::getLastWeekSunday();
		
		$totalWkQty = 0;
		
		
		for($i=0;$i<=7;$i++){
			
			$actDateEnd = Model_DatetimeHelper::adjustWeeks("sub", $dateEnd, $i);
			$actDateStart = Model_DatetimeHelper::adjustDays("sub", $actDateEnd,6);
			
			$qty = self::getProductSalesQtyByDates($actDateStart,$actDateEnd,$barCode);

			if($qty >0){
				
				$totalWkQty++; 
			}
		}
		
		
		if($totalWkQty==1){
			
			return "SNORMAL";
		}
		
		if($totalWkQty >=2 && $totalWkQty <5){
			
			return "NORMAL";
		}
		//if($totalWkQty >=5 && $totalWkQty <7){
				
		//	return "SEMIHOT";
		//}

		if($totalWkQty >=5 && $totalWkQty <=8 ){
				
			return "HOT";
		}
		
		return "VERYCOLD";
			
	}
	
	
	public function isHotItem10Wk($barCode){
	
		$dateEnd = Model_DatetimeHelper::getLastWeekSunday();
	
		$totalWkQty = 0;
	
	
		for($i=0;$i<=9;$i++){
				
			$actDateEnd = Model_DatetimeHelper::adjustWeeks("sub", $dateEnd, $i);
			$actDateStart = Model_DatetimeHelper::adjustDays("sub", $actDateEnd,6);
				
			$qty = self::getProductSalesQtyByDates($actDateStart,$actDateEnd,$barCode);
	
			if($qty >0){
	
				$totalWkQty++;
			}
		}
	
	
		if($totalWkQty<3 && $totalWkQty >0){
				
			return "COLD";
		}
	
		if($totalWkQty >=3 && $totalWkQty <7){
				
			return "NORMAL";
		}
	
		if($totalWkQty >=7 && $totalWkQty <=10 ){
	
			return "HOT";
		}
	
		return "VERYCOLD";
			
	}
	
	
	public function getProductSalesList($dateBegin,$dateEnd,$tableName){
		
		date_default_timezone_set("Australia/Melbourne");
		$db = $this->getAdapter();
		$arrDate = Model_DatetimeHelper::createDateArray($dateBegin, $dateEnd,"");
		
		$strTmp = "";
		
		foreach($arrDate as $date){
		
			$strDate =substr($date,2);
			$strTmp.=" INV_NO LIKE '%".$strDate."%' OR";
		}
		
		$strWhere = substr($strTmp,0,-2);
				
		$sql = "SELECT DISTINCT [SCODE] COLLATE Chinese_Taiwan_Stroke_CI_AS_KS AS SCODE, SUM([QTY]) AS SOLDQTY
				FROM [APOS1108].[dbo].[{$tableName}]
				WHERE ( {$strWhere} ) 
				GROUP BY [SCODE]";
		//d($sql);
		$stmt = $db->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();
		//d($db,$result);
		if(empty($result)){
			return false;
		}
		else{
			return $result;
		}
	}
	
	public function calOrderQty($lastWeekSales,$wk2Sales,$stockShop,$isHot,$maxSold,$isNew,$isSp = null){
		
		$averageSales = ($lastWeekSales + $wk2Sales) /2 ;
		$averageSales = ($averageSales < 0 ) ? 0: $averageSales;
		$targetQty = 0;
		$pickupQty = 0;
		
		if($isNew){
			$isHot = "NORMAL";
		}
		
		if($isHot == "HOT"){
			
			$targetQty = ceil($averageSales * 2); //Special in 
			
		}
		if($isHot == "NORMAL"){
			$targetQty = ceil($averageSales * 1.5) + 1;
			
		}
		if($isHot == "SNORMAL"){
			$targetQty = floor($averageSales * 1.5);
				
		}		
		if($isHot == "COLD"){
			$targetQty = ceil($averageSales);
			
		}
		if($isHot == "VERYCOLD"){
			
			$targetQty = ceil($averageSales);
			
			//this not going to happen				
		}
		$targetQty = ($targetQty > $maxSold )? $targetQty : $maxSold;
		
		if($isSp){
			if($targetQty < 3){$targetQty = 3;}
		}
		
		$pickupQty = $targetQty - $stockShop;
		
		$pickupQty = ($pickupQty <0)? 0: $pickupQty;
		
		return $pickupQty;
		
	}
	
	public function getBundleDealInvoice($dateBegin,$dateEnd,$tableName,$counter){
		
		date_default_timezone_set("Australia/Melbourne");
		$db = $this->getAdapter();
		$arrDate = Model_DatetimeHelper::createDateArray($dateBegin, $dateEnd,"");
		
		$strTmp = "";
		
		foreach($arrDate as $date){
		
			$strDate =substr($date,2);
			$strTmp.=" INV_NO LIKE '%".$strDate."%' OR";
		}
		
		$strWhere = substr($strTmp,0,-2);
		
		$sql = "SELECT DISTINCT [INV_NO]
		FROM [APOS1108].[dbo].[{$tableName}]
		WHERE ( {$strWhere} )
		GROUP BY [INV_NO]
		HAVING (COUNT([INV_NO]) > {$counter})";
		
		$stmt = $db->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();
		
		if(empty($result)){
		return false;
		}
		else{
			return $result;
		}		
		
		
	}
	
	public function getOfflineOnduty($dateBegin,$dateEnd){
		return self::getProductSalesInvoiceByDates($dateBegin, $dateEnd,"ON-DUTY-OFFLINE");	
	}
	
	public function getOfflineOffduty($dateBegin,$dateEnd){
		return self::getProductSalesInvoiceByDates($dateBegin, $dateEnd, "OFF-DUTY-OFFLINE");
	}
}

?>