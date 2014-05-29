<?php 
/**
 */
 class Model_DbTable_Faultyproduct extends Zend_Db_Table_Abstract 
{
	/**
	 * @var string Class Table Name
	 */
	const FAULTY_STATUS_REJECT = 2;
	const FAULTY_STATUS_DISPOSE = 3;
	const FAULTY_STATUS_SUPPLIER_INIT = 4;
	const FAULTY_STATUS_SUPPLIER_RMA = 41;
	const FAULTY_STATUS_SUPPLIER_POSTBACK = 42;
	const FAULTY_STATUS_SUPPLIER_WAITCREDIT = 43;
	const FAULTY_STATUS_CHINA_INIT = 5;
	const FAULTY_STATUS_SALE = 6;
	
	
	
	protected $_name = 'faulty_product'; 
	
	public function getFaultyProduct($idFaulty){
		
		$row = $this->fetchRow('`id_faulty`='.(int)$idFaulty);
		if(!$row){
			return false;
		}
		return $row->toArray();
				
	}
	public function addFaultyProduct($productCode,$productName,$shopCode,$staffName,$qty,$reasonFaulty,$commFaulty,$idInvoice,$dateSales,$dateReturn){

		date_default_timezone_set('Australia/Melbourne');
		//$timenow = date("D Y-m-d g:i a");
		// Define Today's Date
		$yearToday = date("Y");
		$weekToday = date("W");
		$dateToday = date("Y-m-d");
		$timeNow = date("H:i");  
		
		
		$data = array(
				'product_code_faulty' =>$productCode,
				'product_name_faulty' =>$productName,
				'shopcode_faulty' =>$shopCode,
				'staff_name_faulty' =>$staffName,
				'faulty_qty'=>$qty,
				'week_of_year_faulty' =>$weekToday,
				'year_faulty' => $yearToday,
				'date_faulty' => $dateToday,
				'time_faulty' => $timeNow,
				'reason_faulty'=> $reasonFaulty,
				'comment_faulty' => $commFaulty,
				//'name_customer'=> $nameCustomer,
				'id_invoice' => $idInvoice,
				'date_sales' => $dateSales,
				'date_return' => $dateReturn,
		);
		$this->insert($data);
		$lastID = $this->getAdapter()->lastInsertId();
		
		return $lastID;
		}
		
	public function addRepairFaulty( $productCodeFaulty , $productNameFaulty , $shopcodeFaulty , $staffNameFaulty , $faultyQty ,  $reasonFaulty , $commentFaulty , $idInvoice , $dateSales , $dateReturn , $nameStaffDis , $idRepairJob , $nameTech , $neverUse){
			
		date_default_timezone_set('Australia/Melbourne');
		//$timenow = date("D Y-m-d g:i a");
		// Define Today's Date
		$yearToday = date("Y");
		$weekToday = date("W");
		$dateToday = date("Y-m-d");
		$timeNow = date("H:i");
		
			$data = array(
						
					"product_code_faulty" =>  $productCodeFaulty ,
					"product_name_faulty" =>  $productNameFaulty ,
					"shopcode_faulty" =>  $shopcodeFaulty ,
					"staff_name_faulty" =>  $staffNameFaulty ,
					"faulty_qty" =>  $faultyQty ,
					"week_of_year_faulty" =>  $weekToday ,
					"year_faulty" =>  $yearToday ,
					"date_faulty" =>  $dateToday ,
					"time_faulty" =>  $timeNow ,
					"reason_faulty" =>  $reasonFaulty ,
					"comment_faulty" =>  $commentFaulty ,
					//"name_customer" =>  $nameCustomer ,
					"id_invoice" =>  $idInvoice ,
					"date_sales" =>  $dateSales ,
					"date_return" =>  $dateReturn ,
					"name_staff_dis" =>  $nameStaffDis ,
					"id_repair_job" =>  $idRepairJob ,
					"name_tech" =>  $nameTech ,
					"never_use" =>  $neverUse
		
			);
			$this->insert($data);
			
			$lastID = $this->getAdapter()->lastInsertId();
			
			return $lastID;
			
		
		}	
	public function updateFaultyProduct(){
		//date_default_timezone_set('Australia/Melbourne');
		
		}
	public function getAuditDate($dateBegin,$dateEnd){

		$db = $this->getAdapter();
		$sqlstr="SELECT DISTINCT `audit_date` FROM `faulty_product` WHERE `audit_date` >='".$dateBegin."' AND `audit_date` <='".$dateEnd."' ORDER BY `audit_date`;";
		$stmt = $db->prepare($sqlstr);
		$stmt->execute();
		$result = $stmt->fetchAll();
		
		if($result){
			return $result;
		}
		else{
		
			return false;
		}
		
	}	
	public function getAuditResult($dateBegin,$dateEnd){
	
		$db = $this->getAdapter();
		//$sqlstr="SELECT * FROM `faulty_product` WHERE `audit_faulty`=1 AND `audit_date` >='".$dateBegin."' AND `audit_date` <='".$dateEnd."' ORDER BY `shopcode_faulty`,`audit_date`;";
		$sqlstr ="SELECT `product_code_faulty`,`shopcode_faulty`,SUM(`faulty_qty`) FROM `faulty_product` WHERE `audit_faulty` = 1 AND `audit_date` >='".$dateBegin."' AND `audit_date` <='".$dateEnd."'  GROUP BY `product_code_faulty`,`shopcode_faulty` ORDER BY `shopcode_faulty`,`audit_date`";
		
		$stmt = $db->prepare($sqlstr);
		$stmt->execute();
		$result = $stmt->fetchAll();
	
		if($result){
			return $result;
		}
		else{
	
			return false;
		}
	
	}	
		
	public function deleteFaultyProduct($idFaulty){
		$this->delete('`id_faulty` =' . $idFaulty);
		}
	public function listFaultyProductToday(){
		
		date_default_timezone_set('Australia/Melbourne');
		$dateToday = date("Y-m-d");
		
		$rows = $this->fetchAll("`date_faulty` = '".$dateToday."'");
		return $rows->toArray();
				
		}
	public function listTotalFaultyThisWeek(){
		
		date_default_timezone_set('Australia/Melbourne');
		$weekToday = date("W");
		
		$rows = $this->fetchAll("`week_of_year_faulty` = '".$weekToday."'");
		return $rows->toArray();		
		
		}
		
	public function listFaultyCountThisWeekByShop($shopCode){
		
			date_default_timezone_set('Australia/Melbourne');
			$weekToday = date("W");
			$rows = $this->fetchAll("`week_of_year_faulty` = '".$weekToday."' AND `shopcode_faulty` LIKE '".$shopCode."'");
			return count($rows->toArray());
		}	
	public function listAllUnhandledFaultyByShop($shopname){
		date_default_timezone_set('Australia/Melbourne');
		
		$select = $this->_db->select()
		->from($this->_name,array('*'))
		->where('ISNULL(audit_faulty)')
		->where('shopcode_faulty = ?',$shopname)
		->order('date_faulty');
		$result = $this->getAdapter()->fetchAll($select);
		
		if($result){
			return $result;
		}
		else{return false;
		}
		}
	public function listAllUnhandledFaultyByShopByDate($shopCode,$dateBegin,$dateEnd){
		
			$db = $this->getAdapter();
			$sqlstr="SELECT * FROM `faulty_product` WHERE `shopcode_faulty` LIKE '%".trim($shopCode)."%' AND ISNULL(`audit_faulty`) AND  `date_faulty` >='".$dateBegin."' AND `date_faulty` <='".$dateEnd."' ORDER BY `date_faulty`;";
			$stmt = $db->prepare($sqlstr);
			$stmt->execute();
			$result = $stmt->fetchAll();
		
			if($result){
				return $result;
			}
			else{
					
				return false;
			}
		
		}		
	/*
	public function listTotalFaultyThisMonth(){
		
		date_default_timezone_set('Australia/Melbourne');
		$weekToday = date("W");
		
		$rows = $this->fetchAll("`week_of_year_faulty` = '".$weekToday."'");
		return $rows->toArray();		
		
		}
	*/
		public function auditFaultyProduct($idFaulty,$auditFaulty,$auditComment,$auditHandle,$auditSupplier){
		
			date_default_timezone_set('Australia/Melbourne');
			$dateToday = date("Y-m-d");
		
			$data = array(
					'audit_faulty' =>$auditFaulty,
					'audit_comment' =>$auditComment,
					'audit_date' => $dateToday,
					'audit_handle'=>$auditHandle
			);
			if($auditHandle==4){
				
				$data["audit_supplier"] = $auditSupplier;
				
			}
			
			$this->update($data,'`id_faulty` ='.$idFaulty);
		}

		public function resetAudit($idFaulty){
			
			$data = array(
					'audit_faulty' =>null,
					'audit_comment' =>null,
					'audit_date' =>null,
					'audit_handle'=>null,
					'audit_supplier' => null
			);
			
			$this->update($data,'`id_faulty` ='.$idFaulty);
			
		}

		public function updateComment($idFaulty,$auditComment){
			
			$dateToday = Model_DatetimeHelper::dateToday();
			$timeNow = Model_DatetimeHelper::timeNow();
			
			$data = array(
					'audit_comment' =>$auditComment."[{$dateToday} {$timeNow}]"
					);
			
			$this->update($data,'`id_faulty` ='.$idFaulty);
		}
		
		
		public function listFaultyProductByDate($dateBegin,$dateEnd){
		
			$db = $this->getAdapter();
			$sqlstr="SELECT DISTINCT `product_code_faulty`, SUM(`faulty_qty`) FROM `faulty_product` WHERE `date_faulty` >='".$dateBegin."' AND `date_faulty` <='".$dateEnd."' GROUP BY `product_code_faulty` ORDER BY SUM(`faulty_qty`) DESC ;";
			$stmt = $db->prepare($sqlstr);
			$stmt->execute();
			$result = $stmt->fetchAll();
			 
			if($result){
				return $result;
			}
			else{
		
				return false;
			}		
		}
		public function listRejectFaultyByDate($dateBegin,$dateEnd){
		
			$db = $this->getAdapter();
			$sqlstr="SELECT DISTINCT `product_code_faulty`, SUM(`faulty_qty`) FROM `faulty_product` WHERE `audit_faulty` = 2 AND  `date_faulty` >='".$dateBegin."' AND `date_faulty` <='".$dateEnd."' GROUP BY `product_code_faulty` ORDER BY SUM(`faulty_qty`) DESC ;";
			$stmt = $db->prepare($sqlstr);
			$stmt->execute();
			$result = $stmt->fetchAll();
		
			if($result){
				return $result;
			}
			else{
		
				return false;
			}
		}		
		public function countListFaultyProductByDate($dateBegin,$dateEnd){
			
			$db = $this->getAdapter();
			$sqlstr="SELECT COUNT(DISTINCT `product_code_faulty`) FROM `faulty_product` WHERE `date_faulty` >='".$dateBegin."' AND `date_faulty` <='".$dateEnd."';";
			$stmt = $db->prepare($sqlstr);
			$stmt->execute();
			$result = $stmt->fetchAll();
			
			if($result){
				return $result;
			}
			else{
			
				return false;
			}			

			
			
			
			
			
			
		}
		public function countListFaultyShopByDate($dateBegin,$dateEnd){
				
			$db = $this->getAdapter();
			$sqlstr="SELECT COUNT(DISTINCT `shopcode_faulty`) FROM `faulty_product` WHERE `date_faulty` >='".$dateBegin."' AND `date_faulty` <='".$dateEnd."';";
			$stmt = $db->prepare($sqlstr);
			$stmt->execute();
			$result = $stmt->fetchAll();
				
			if($result){
				return $result;
			}
			else{
					
				return false;
			}
				
				
		}		
		public function sumTotalFaultyPCs($dateBegin,$dateEnd){
		
			$db = $this->getAdapter();
			$sqlstr="SELECT SUM(`faulty_qty`) FROM `faulty_product` WHERE `date_faulty` >='".$dateBegin."' AND `date_faulty` <='".$dateEnd."';";
			$stmt = $db->prepare($sqlstr);
			$stmt->execute();
			$result = $stmt->fetchAll();
				
			if($result){
				return $result;
			}
			else{
					
				return false;
			}		
		
		
		
		}	
			
		public function listFaultyShopByDate($dateBegin,$dateEnd){
		
			$db = $this->getAdapter();
			$sqlstr="SELECT DISTINCT `shopcode_faulty`, SUM(`faulty_qty`) FROM `faulty_product` WHERE `date_faulty` >='".$dateBegin."' AND `date_faulty` <='".$dateEnd."' GROUP BY `shopcode_faulty` ORDER BY SUM(`faulty_qty`) DESC ;";
			$stmt = $db->prepare($sqlstr);
			$stmt->execute();
			$result = $stmt->fetchAll();
		
			if($result){
				return $result;
			}
			else{
		
				return false;
			}
		}
		
		public function sumFaultySingleShopByDate($shopCode,$dateBegin,$dateEnd){
		
			$db = $this->getAdapter();
			$sqlstr="SELECT SUM(`faulty_qty`) FROM `faulty_product` WHERE `date_faulty` >='".$dateBegin."' AND `date_faulty` <='".$dateEnd."' AND `shopcode_faulty` LIKE '%".$shopCode."%' ;";
			$stmt = $db->prepare($sqlstr);
			$stmt->execute();
			$result = $stmt->fetchAll();
		
			if($result){
				return $result;
			}
			else{
		
				return false;
			}
		}
				
		public function listRejectShopByDate($dateBegin,$dateEnd){
		
			$db = $this->getAdapter();
			$sqlstr="SELECT DISTINCT `shopcode_faulty`, SUM(`faulty_qty`) FROM `faulty_product` WHERE `audit_faulty` = 2 AND  `date_faulty` >='".$dateBegin."' AND `date_faulty` <='".$dateEnd."' GROUP BY `shopcode_faulty` ORDER BY SUM(`faulty_qty`) DESC ;";
			$stmt = $db->prepare($sqlstr);
			$stmt->execute();
			$result = $stmt->fetchAll();
		
			if($result){
				return $result;
			}
			else{
		
				return false;
			}
		}

		public function findFaultyProductName($productCode,$dateBegin,$dateEnd){
			
			$rows=$this->fetchAll("`product_code_faulty` LIKE '%".trim($productCode)."%' AND `date_faulty` >= '".trim($dateBegin)."' AND `date_faulty` <= '".trim($dateEnd)."' ","id_faulty");

			
			if($rows){
					
				return $rows[0]['product_name_faulty'];
					
			}
			else{
					
				return false;
			}
		}
			
		public function singleBarCodeTopShop($productCode,$dateBegin,$dateEnd){
			
			$db = $this->getAdapter();
			$sqlstr="SELECT DISTINCT `shopcode_faulty`, SUM(`faulty_qty`) FROM `faulty_product` WHERE `product_code_faulty` LIKE '%".trim($productCode)."%'  AND  `date_faulty` >='".$dateBegin."' AND `date_faulty` <='".$dateEnd."' GROUP BY `shopcode_faulty` ORDER BY SUM(`faulty_qty`) DESC LIMIT 0,1 ;";
			$stmt = $db->prepare($sqlstr);
			$stmt->execute();
			$result = $stmt->fetchAll();
			
			if($result){
				return $result;
			}
			else{
			
				return false;
			}			
			
		} 
		public function singleBarCodeByDate($productCode,$dateBegin,$dateEnd){
				
			$db = $this->getAdapter();
			$sqlstr="SELECT SUM(`faulty_qty`) FROM `faulty_product` WHERE `product_code_faulty` LIKE '%".trim($productCode)."%'  AND  `date_faulty` >='".$dateBegin."' AND `date_faulty` <='".$dateEnd."'  LIMIT 0,1 ;";
			$stmt = $db->prepare($sqlstr);
			$stmt->execute();
			$result = $stmt->fetchAll();
				
			if($result){
				return $result;
			}
			else{
					
				return false;
			}
				
		}
		public function singleBarCodeRejectDetail($productCode,$dateBegin,$dateEnd){
			$db = $this->getAdapter();
			$sqlstr="SELECT * FROM `faulty_product` WHERE `product_code_faulty` LIKE '%".trim($productCode)."%'  AND `audit_faulty` = 2 AND  `date_faulty` >='".$dateBegin."' AND `date_faulty` <='".$dateEnd."' ORDER BY `date_faulty`;";
			$stmt = $db->prepare($sqlstr);
			$stmt->execute();
			$result = $stmt->fetchAll();
			
			if($result){
				return $result;
			}
			else{
					
				return false;
			}			
		
		}	
		public function singleBarCodeFaultyDetail($productCode,$dateBegin,$dateEnd){
			$db = $this->getAdapter();
			$sqlstr="SELECT * FROM `faulty_product` WHERE `product_code_faulty` LIKE '%".trim($productCode)."%'  AND  `date_faulty` >='".$dateBegin."' AND `date_faulty` <='".$dateEnd."'  ORDER BY `shopcode_faulty`;";
			$stmt = $db->prepare($sqlstr);
			$stmt->execute();
			$result = $stmt->fetchAll();
				
			if($result){
				return $result;
			}
			else{
					
				return false;
			}
		
		}
		public function singleShopRejectDetail($shopCode,$dateBegin,$dateEnd){
			
			$db = $this->getAdapter();
			$sqlstr="SELECT * FROM `faulty_product` WHERE `shopcode_faulty` LIKE '%".trim($shopCode)."%'  AND `audit_faulty` = 2 AND  `date_faulty` >='".$dateBegin."' AND `date_faulty` <='".$dateEnd."' ORDER BY `date_faulty`;";
			$stmt = $db->prepare($sqlstr);
			$stmt->execute();
			$result = $stmt->fetchAll();
				
			if($result){
				return $result;
			}
			else{
					
				return false;
			}			
			
		}
		public function singleShopFaultyDetail($shopCode,$dateBegin,$dateEnd){
				
			$db = $this->getAdapter();
			$sqlstr="SELECT * FROM `faulty_product` WHERE `shopcode_faulty` LIKE '%".trim($shopCode)."%' AND  `date_faulty` >='".$dateBegin."' AND `date_faulty` <='".$dateEnd."' ORDER BY `product_code_faulty`;";
			$stmt = $db->prepare($sqlstr);
			$stmt->execute();
			$result = $stmt->fetchAll();
		
			if($result){
				return $result;
			}
			else{
					
				return false;
			}
				
		}	
	//faulty handling 
	  public function listAllByHandleStatus($statusCode,$shopCode = "ALL",$sort = null){

	  	$db = $this->getAdapter();
	  	$strSort = '`product_code_faulty`';

	  	if($sort != ''){
	  		
	  		$strSort = $sort;
	  		
	  	}
	  		
	  	$sqlstr="SELECT * FROM `faulty_product` WHERE `audit_handle` = ".trim($statusCode)." AND `shopcode_faulty` LIKE '".$shopCode."'  ORDER BY {$strSort};";
	  	
	  	if($shopCode=="ALL"){

	  		$sqlstr="SELECT * FROM `faulty_product` WHERE `audit_handle` = ".trim($statusCode)." ORDER BY {$strSort};";
	  	}
	  	
	  	$stmt = $db->prepare($sqlstr);
	  	$stmt->execute();
	  	$result = $stmt->fetchAll();
	  	
	  	if($result){
	  		return $result;
	  	}
	  	else{
	  			
	  		return false;
	  	}	  	
	  	
	  }	
	  public function listStatusBySupplier($statusCode,$idSupplier){
	  	
	  	$db = $this->getAdapter();
	  	
	  	$sqlstr="SELECT * FROM `faulty_product` WHERE `audit_handle` = ".trim($statusCode)." AND `audit_supplier` = ".$idSupplier."  ORDER BY `product_code_faulty`;";
	  	
	  	
	  	$stmt = $db->prepare($sqlstr);
	  	$stmt->execute();
	  	$result = $stmt->fetchAll();
	  	
	  	if($result){
	  		return $result;
	  	}
	  	else{
	  	
	  		return false;
	  	}	  	
	  	
	  	
	  	
	  	
	  }
	  
	  public function countListStatusBySupplier($statusCode,$idSupplier){
	  
	  	$db = $this->getAdapter();
	  
	  	$sqlstr="SELECT count('id_faulty')  FROM `faulty_product` WHERE `audit_handle` = ".trim($statusCode)." AND `audit_supplier` = ".$idSupplier."  ORDER BY `product_code_faulty`;";
	  
	  
	  	$stmt = $db->prepare($sqlstr);
	  	$stmt->execute();
	  	$result = $stmt->fetchAll();
	  
	  	if($result){
	  		return $result;
	  	}
	  	else{
	  
	  		return false;
	  	}
	  
	  
	  
	  
	  }	  
	  
	  
	  public function updateFaultyHandleStatus($idFaulty,$statusCode){
	  		
	  		$data = array("audit_handle" => $statusCode);
	  		$this->update($data,"`id_faulty` =".(int)$idFaulty);
	  	
	  }
	  public function listAllRejectInitial($shopCode = "ALL"){
	  	
	  	$rows = self::listAllByHandleStatus(self::FAULTY_STATUS_REJECT,$shopCode);
	  	return $rows;
	  }
	  
	  public function listAllDisposeInitial($shopCode = "ALL"){
	  	$rows = self::listAllByHandleStatus(self::FAULTY_STATUS_DISPOSE,$shopCode,"`audit_date`");
	  	return $rows;
	  }
	  
	  public function listAllReturnChinaInitial($shopCode = "ALL"){
	  	
	  	$rows = self::listAllByHandleStatus(self::FAULTY_STATUS_CHINA_INIT,$shopCode,"`audit_date`");
	  	return $rows;	  	
	  }
	  public function listAllReturnSupplyInitial($shopCode = "ALL"){
	  
	  	$rows = self::listAllByHandleStatus(self::FAULTY_STATUS_SUPPLIER_INIT,$shopCode,"`audit_date`");
	  	return $rows;
	  } 
	  public function listAllReturnSupplyRma($shopCode = "ALL"){
	  	 
	  	$rows = self::listAllByHandleStatus(self::FAULTY_STATUS_SUPPLIER_RMA,$shopCode);
	  	return $rows;
	  }	 
	  public function listAllReturnSupplyPostback($shopCode = "ALL"){
	  	 
	  	$rows = self::listAllByHandleStatus(self::FAULTY_STATUS_SUPPLIER_POSTBACK,$shopCode);
	  	return $rows;
	  }
	  public function listAllReturnSupplyWaitCredit($shopCode = "ALL"){
	  	 
	  	$rows = self::listAllByHandleStatus(self::FAULTY_STATUS_SUPPLIER_WAITCREDIT,$shopCode);
	  	return $rows;
	  }	   
	  
	  public function listAllSaleOtherInitial($shopCode = "ALL"){
	  
	  	$rows = self::listAllByHandleStatus(self::FAULTY_STATUS_SALE,$shopCode);
	  	return $rows;
	  }
	  
	  	  
}
?>