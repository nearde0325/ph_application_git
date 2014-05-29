<?php
class Model_DbTable_Pr_Prstockmovement extends Zend_Db_Table_Abstract {

	protected $_name = 'pr_stockmove';

	public function addMovement($codeMove,$idProduct,$qty,$staffName,$idShop = 0,$jobId = NULL){
		
		$rStock = new Model_DbTable_Pr_Prstock();
		
		$stock = 0;
		if($idShop == 0){
			
			$stock = $rStock->getWarehouseStock($idProduct);
		}
		else{
			
			$stock = $rStock->getShopStock($idProduct, $idShop);
		}
		
		$data = array(
				"code_move" => $codeMove,
				"date_move" => self::cDateToday(),
				"time_move" => self::cTimeNow(),
				"id_product"=> $idProduct,
				"qty_product" => $qty,
				"staff_name" => $staffName,
				"shop_code" => $idShop,
				"id_job" =>$jobId,
				"qty_onhand" => $stock
				);
		$this->insert($data);
		
		return $this->getAdapter()->lastInsertId();
		
	}
	public function addMovementDate($codeMove,$idProduct,$qty,$dateSelect,$staffName,$idShop = 0,$jobId = NULL){
		$data = array(
				"code_move" => $codeMove,
				"date_move" => $dateSelect,
				"time_move" => self::cTimeNow(),
				"id_product"=> $idProduct,
				"qty_product" => $qty,
				"staff_name" => $staffName,
				"shop_code" => $idShop,
				"id_job" =>$jobId
		);
		$this->insert($data);		
	}
	
	public function cancelMovement($idMovement){
		
		$this->delete('`id_move` =' . $idMovement);
	}
	public function getWarehouseInToday(){
		
		$db = $this->getAdapter();
		
		$dateToday=self::cDateToday();
		
		$sqlstr="SELECT * FROM `pr_stockmove` WHERE `date_move` = '".$dateToday."' and `code_move` = 1 ORDER BY `time_move`";
		
		$stmt = $db->prepare($sqlstr);
		$stmt->execute();
		$result = $stmt->fetchAll();
		return $result;
	}
	
	public function getWarehouseInByDate($date){
		$db = $this->getAdapter();
		
		//$dateToday=self::cDateToday();
		
		$sqlstr="SELECT * FROM `pr_stockmove` WHERE `date_move` = '".$date."' and `code_move` = 1 ORDER BY `time_move`";
		
		$stmt = $db->prepare($sqlstr);
		$stmt->execute();
		$result = $stmt->fetchAll();
		return $result;		
	}
	public function getWarehouseOutToday(){
		
		$db = $this->getAdapter();
		
		$dateToday=self::cDateToday();
		
		$sqlstr="SELECT * FROM `pr_stockmove` WHERE `date_move` = '".$dateToday."' and `code_move` = 3 ORDER BY `time_move`";
		
		$stmt = $db->prepare($sqlstr);
		$stmt->execute();
		$result = $stmt->fetchAll();
		return $result;		
		
	}
	public function getWarehouseOutByDate($date){
		$db = $this->getAdapter();
		
		//$dateToday=self::cDateToday();
		
		$sqlstr="SELECT * FROM `pr_stockmove` WHERE `date_move` = '".$date."' and `code_move` = 3 ORDER BY `time_move`";
		
		$stmt = $db->prepare($sqlstr);
		$stmt->execute();
		$result = $stmt->fetchAll();
		return $result;		
		
	}
	
	public function getStockMoveByDateByCode($codeMove,$dateBegin,$dateEnd,$shopCode = null){
		$db = $this->getAdapter();
	
		//$dateToday=self::cDateToday();
		$sqlstr="SELECT COUNT(`id_product`) AS ctp , `date_move`,`id_product` FROM `pr_stockmove` WHERE `shop_code` = ".$shopCode." and `date_move`>='".$dateBegin."' and `date_move` <= '".$dateEnd."' and `code_move` = ".$codeMove." GROUP BY `id_product` ORDER BY `date_move`";
		
		if($shopCode == null){
			
			$sqlstr="SELECT COUNT(`id_product`) AS ctp , `date_move`,`id_product` FROM `pr_stockmove` WHERE `date_move`>='".$dateBegin."' and `date_move` <= '".$dateEnd."' and `code_move` = ".$codeMove." GROUP BY `id_product` ORDER BY `date_move`";
				
		}
		
		$stmt = $db->prepare($sqlstr);
		$stmt->execute();
		$result = $stmt->fetchAll();
		return $result;
	
	}
	
	public function getStockMoveSumByDateByCode($codeMove,$dateBegin,$dateEnd,$shopCode = null){
		$db = $this->getAdapter();
	
		//$dateToday=self::cDateToday();
	
		$sqlstr="SELECT COUNT(`id_product`) AS ctp ,SUM(`qty_product`) AS qtys ,`date_move`,`id_product` FROM `pr_stockmove` WHERE `shop_code` = ".$shopCode." and `date_move`>='".$dateBegin."' and `date_move` <= '".$dateEnd."' and `code_move` = ".$codeMove." GROUP BY `id_product` ORDER BY `date_move`";
		if($shopCode == null){

			$sqlstr="SELECT COUNT(`id_product`) AS ctp ,SUM(`qty_product`) AS qtys ,`date_move`,`id_product` FROM `pr_stockmove` WHERE `date_move`>='".$dateBegin."' and `date_move` <= '".$dateEnd."' and `code_move` = ".$codeMove." GROUP BY `id_product` ORDER BY `date_move`";
				
		}
		$stmt = $db->prepare($sqlstr);
		$stmt->execute();
		$result = $stmt->fetchAll();
		return $result;
	
	}
		
	
	public function getProductMoveByDateByCode($codeMove,$idProduct,$dateBegin,$dateEnd,$shopCode = null){
		
		
		$db = $this->getAdapter();
		$sqlstr="SELECT * FROM `pr_stockmove` WHERE `shop_code` = ".$shopCode." and `date_move`>='".$dateBegin."' and `date_move` <= '".$dateEnd."' and `code_move` = ".$codeMove." and `id_product` = ".$idProduct."  ORDER BY `date_move`";
		
		if($shopCode == ""){
			
			$sqlstr="SELECT * FROM `pr_stockmove` WHERE `date_move`>='".$dateBegin."' and `date_move` <= '".$dateEnd."' and `code_move` = ".$codeMove." and `id_product` = ".$idProduct."  ORDER BY `date_move`";
			
		}
		$stmt = $db->prepare($sqlstr);
		$stmt->execute();
		$result = $stmt->fetchAll();
		return $result;		
		
	}
	
	public function getProductMoveByDateTimeByCode($codeMove,$idProduct,$dateBegin,$dateEnd,$shopCode = null,$timeBegin = null ,$timeEnd = null){
		
		if($timeBegin == ""){$timeBegin = '00:00:00';}
		if($timeEnd == ""){
			$timeEnd = '23:59:59';
		}
		
		$db = $this->getAdapter();
		$sqlstr="SELECT * FROM `pr_stockmove` WHERE `shop_code` = ".$shopCode." and (`date_move`>'".$dateBegin."' or( `date_move`='".$dateBegin."' and  `time_move`>='".$timeBegin."') ) and (`date_move` < '".$dateEnd."' or (`date_move` = '".$dateEnd."' and `time_move` <= '".$timeEnd."') ) and `code_move` = ".$codeMove." and `id_product` = ".$idProduct."  ORDER BY `date_move`";
	
		if($shopCode == ""){
				
			$sqlstr="SELECT * FROM `pr_stockmove` WHERE (`date_move`>'".$dateBegin."' or( `date_move`='".$dateBegin."' and  `time_move`>='".$timeBegin."') ) and (`date_move` < '".$dateEnd."' or (`date_move` = '".$dateEnd."' and `time_move` <= '".$timeEnd."') ) and `code_move` = ".$codeMove." and `id_product` = ".$idProduct."  ORDER BY `date_move`";
		
		}
		$stmt = $db->prepare($sqlstr);
		$stmt->execute();
		$result = $stmt->fetchAll();
		return $result;
	
	}
	
	public function getProductMoveSummaryByDateByCode($codeMove,$idProduct,$dateBegin,$dateEnd,$shopCode = null){
		
		$db = $this->getAdapter();
		$sqlstr="SELECT SUM(`qty_product`) as sum_qty FROM `pr_stockmove` WHERE `shop_code` = ".$shopCode." and `date_move`>='".$dateBegin."' and `date_move` <= '".$dateEnd."' and `code_move` = ".$codeMove." and `id_product` = ".$idProduct."  ORDER BY `date_move`";
		
		if($shopCode == ""){
				
			$sqlstr="SELECT SUM(`qty_product`) as sum_qty FROM `pr_stockmove` WHERE `date_move`>='".$dateBegin."' and `date_move` <= '".$dateEnd."' and `code_move` = ".$codeMove." and `id_product` = ".$idProduct."  ORDER BY `date_move`";
				
		}
		$stmt = $db->prepare($sqlstr);
		$stmt->execute();
		$result = $stmt->fetchAll();
		return $result;		
		
		
	}
	public function getPartsRecordByJobId($idJob){
			
		$rows =$this->fetchAll("`id_job` LIKE '".$idJob."'","date_move");
		
		return $rows->toArray();
		
	}
	
	public function getPartsRecordRowByJobId($idJob){
		$rows =$this->fetchRow("`id_job` LIKE '".$idJob."'");
		
		return $rows->toArray();
		
	}
	public function getPartsSummaryByJobId($idJob,$priceCate= null){
		
		if($priceCate == ""){
			$priceCate = "price_a";
		}
		
		$rProduct = new Model_DbTable_Pr_Prproducts(Zend_Registry::get('db_real'));
		
		$rows = self::getPartsRecordByJobId($idJob);
		
		$totalPrice = 0;
		
		//$arrRes = array();
		$arrParts = array();
		
		
		//only consider 2,7  fauty not count 
		
		foreach($rows as $k => $v){
			//use part
			if($v["code_move"] == 2){
				
				$rProductRow = $rProduct->getProduct($v["id_product"]);
				$rPCode = $rProductRow["code_product"];
				$rPrice = $rProductRow[$priceCate];
				$key = array_search($rPCode,$arrParts,true);
				
				if($key === false){
					$arrParts[] = $rPCode;
					$arrParts[] = $v["qty_product"];
					$arrParts[] = $rPrice;	
				}
				else{
					$arrParts[$key + 1] +=$v["qty_product"];
				}
			}
			//error correction
			
			if($v["code_move"] == 7){
				
				$rProductRow = $rProduct->getProduct($v["id_product"]);
				$rPCode = $rProductRow["code_product"];
				$rPrice = $rProductRow["price_a"];
				$key = array_search($rPCode,$arrParts,true);
				
				if($key === false){
					$arrParts[] = $rPCode;
					$arrParts[] = -$v["qty_product"];
					$arrParts[] = $rPrice;
				}
				else{
					$arrParts[$key + 1] -=$v["qty_product"];
				}				
				
			}
			
		}
		
		$arrParts = array_chunk($arrParts,3);
		foreach($arrParts as $k2 => $v2){
			$totalPrice += $v2[1] * $v2[2];
		}
		$totalPrice = ($totalPrice >0)?$totalPrice:0;
		
		return $arrRes = array($arrParts,$totalPrice);
		//return 2 Arrays 
		//[0] parts details  Code X Qty
		//[1] Total Price 
		
		
		
	}
	
	public function getProductMove($idProduct,$shopCode = null){
		
		$db = $this->getAdapter();
		
		$sqlstr="SELECT * FROM `pr_stockmove` WHERE  `id_product` = ".$idProduct."  ORDER BY `date_move` DESC , `time_move` DESC";		
		if($shopCode!=""){
			$sqlstr="SELECT * FROM `pr_stockmove` WHERE `shop_code` =".$shopCode." AND  `id_product` = ".$idProduct."  ORDER BY `date_move`";
		}
		
		$stmt = $db->prepare($sqlstr);
		$stmt->execute();
		$result = $stmt->fetchAll();
		return $result;		
		
	}
	public function getCountAllProductMoveByDateByCode($codeMove,$idProduct,$dateBegin,$dateEnd){
		
		$db = $this->getAdapter();
		$sqlstr="SELECT count(`id_move`) AS cid FROM `pr_stockmove` WHERE `date_move`>='".$dateBegin."' and `date_move` <= '".$dateEnd."' and `code_move` = ".$codeMove." and `id_product` = ".$idProduct."  ORDER BY `date_move`";
		
		$stmt = $db->prepare($sqlstr);
		$stmt->execute();
		$result = $stmt->fetchAll();
		return $result[0]["cid"];		
		
	}
	public function maxSold($idProduct,$dateEnd,$weekCot,$shop){
		
		$maxSoldQty = 0;
		
		for($i=0;$i<$weekCot;$i++){
			
			$actDateEnd = Model_DatetimeHelper::adjustWeeks("sub", $dateEnd, $i);
			$actDateBegin = Model_DatetimeHelper::adjustWeeks("sub",Model_DatetimeHelper::adjustDays("sub", $dateEnd,6), $i);
			
			$salesLine  = self::getProductMoveSummaryByDateByCode(2, $idProduct, $actDateBegin, $actDateEnd,$shop); 
			
			if($salesLine[0]['sum_qty'] > $maxSoldQty ){
				 $maxSoldQty = $salesLine[0]['sum_qty'];
			}
			
			//var_dump($actDateBegin,$actDateEnd,$salesLine);
		}
		
		return $maxSoldQty;
		
	}
	
	
	public function listByNoteId($idJob){
		
		$rows =$this->fetchAll("`id_job` LIKE '".$idJob."'","id_product");
		
		return $rows->toArray();
		
	}
	
	public function getLastMovement($idProduct){
		$whereStr = " `code_move` = 2 AND `id_product` = {$idProduct} ";
		$row = $this->fetchRow($whereStr,"date_move DECS");
		
		if(!$row) return false;
		return $row['date_move'];	
	}
	public function getLastMovementByShop($idProduct,$idShop){
		
		$whereStr = " `code_move` = 2 AND `id_product` = {$idProduct} AND `shop_code` = {$idShop} ";
		
		if($idShop == 3 || $idShop == 20){

			$whereStr = " ( `code_move` = 2 OR  `code_move` = 4 OR  `code_move` = 6  ) AND `id_product` = {$idProduct} AND `shop_code` = {$idShop} ";
			
		}
		
		$row = $this->fetchRow($whereStr,"date_move DECS");
		
		if(!$row) return false;
		return $row['date_move'];	
	}
	
	private function cDateToday(){
	
		date_default_timezone_set('Australia/Melbourne');
	
		return $dateToday = date("Y-m-d");
	
	}
	private function cTimeNow(){
		
		date_default_timezone_set('Australia/Melbourne');
		
		return $timeNow = date("H:i:s");
	}

	}
?>
