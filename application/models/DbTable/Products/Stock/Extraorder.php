<?php
class Model_DbTable_Products_Stock_Extraorder extends Zend_Db_Table_Abstract {

	protected $_name = 'products_v1_stock_extra_order';
	
	public function getExtraorder( $idExtOrder){
		
		$row = $this->fetchRow("`id_ext_order` = ". $idExtOrder);
		if(!$row) return false;
		return $row->toArray();		
		
		}
		
	public function addExtraorder( $barcode , $description , $shopCode , $date , $qtyWarehouse, $qtyOrder = null , $stop = null , $comment = null,$maxSold = null,$orderOption = null,$idStaff = null){
		
		$data = array(
			
         "barcode" =>  $barcode ,
         "description" =>  $description ,
         "shop_code" =>  $shopCode ,
         "date" =>  $date ,
         "qty" =>  $qtyOrder ,
         "stop" =>  $stop ,
         "comment" =>  $comment,
		 "max_sold" =>$maxSold,
		 "order_option" => $orderOption,
		 "qty_wh" => $qtyWarehouse,
		 "id_staff" => $idStaff		 
			);
		$this->insert($data);
		
		}
		
	public function updateExtraorder(  $idExtOrder ,  $barcode , $description , $shopCode , $date , $qty , $stop , $comment){
		$data = array(
			
         "barcode" =>  $barcode ,
         "description" =>  $description ,
         "shop_code" =>  $shopCode ,
         "date" =>  $date ,
         "qty" =>  $qty ,
         "stop" =>  $stop ,
         "comment" =>  $comment 
	
			);
		
	
			
		$this->update($data,"`id_ext_order` = ". $idExtOrder);
		}
		
		
		public function updateExtOption($idExtOrder,$orderOption,$qty,$idStaff){
		
			$data = array(
						
		"order_option" => $orderOption,
		 "qty" => $qty,
		 "id_staff" => $idStaff	
			
			);
			
			
				
			$this->update($data,"`id_ext_order` = ". $idExtOrder);
			
		
		
		}
		
		
		
		public function saveExtraorder(  $idExtOrder , $qty , $stop , $comment){
			$data = array(
					"qty" =>  $qty ,
					"stop" =>  $stop ,
					"comment" =>  $comment
		
			);
				
			$this->update($data,"`id_ext_order` = ". $idExtOrder);
		}		
	public function deleteExtraorder( $idExtOrder){
		
		$this->delete("`id_ext_order` = ". $idExtOrder);
		
		}	
	
	public function listAll(){
		
		$rows =$this->fetchAll("1","id_ext_order DESC ");
		if(!$rows) return false;
		return $rows->toArray();			
		
		}
	public function listByShop($shopCode,$date= null){
		
		$whereStr ="`shop_code` LIKE '".$shopCode."'";
		if($date!=""){
		$whereStr ="`shop_code` LIKE '".$shopCode."' AND `date` = '".$date."'"; 
		}
		$rows =$this->fetchAll($whereStr,"barcode ASC ");
		if(!$rows) return false;
		return $rows->toArray();
		
	}

	public function isStopSale($barcode,$shopCode){
		
		$whereStr ="`shop_code` LIKE '".$shopCode."' AND `barcode` LIKE '".$barcode."' AND `stop` = 1";  
		
		$row =$this->fetchRow($whereStr);
		if(!$row) return false;
		return true;		
	}
	public function getStopSale($shopCode){
		
		$whereStr ="`shop_code` LIKE '".$shopCode."' AND `stop` = 1";
		
		$rows =$this->fetchAll($whereStr,"barcode ASC ");
		if(!$rows) return false;
		return $rows->toArray();		
		
	}
	public function getExtraOrderWithQty($shopCode,$date,$withQty = 'with'){
		$whereStr = "`qty` > 0 AND `shop_code` LIKE '{$shopCode}' AND `date` = '{$date}'";
		
		if($withQty == 'wo'){
			
			$whereStr = "`qty` <= 0 AND `shop_code` LIKE '{$shopCode}' AND `date` = '{$date}'";
		}
		
		$orderStr = "barcode ASC";
		
		$rows =$this->fetchAll($whereStr,$orderStr);
		if(empty($rows)) return false;
		return $rows->toArray();
	}
	
	public function getUnhandleExtraOrderWithQty($shopCode,$withQty = 'with'){
		
		$whereStr = "(`order_option` = 1 OR `order_option` = 2) AND `qty_wh` >0  AND `order_fullfill` IS NULL AND `shop_code` LIKE '{$shopCode}'";
		
		if($withQty == 'wo'){
				
			$whereStr = "(`order_option` = 1 OR `order_option` = 2) AND `qty_wh` <=0  AND `order_fullfill` IS NULL  AND `shop_code` LIKE '{$shopCode}' ";
		}
		
		$orderStr = "barcode ASC";

		$rows =$this->fetchAll($whereStr,$orderStr)->toArray();
		if(empty($rows)) return false;
		return $rows;
		
		
		
	}
	
	public function getOrderByBarcodeShop($barcode,$shopCode){
		
		$whereStr ="`shop_code` LIKE '".$shopCode."' AND `barcode` LIKE '".$barcode."'";
		
		$rows =$this->fetchAll($whereStr,"barcode ASC ");
		if(empty($rows)) return false;
		return $rows->toArray();
		
	}
	
	public function shouldPutIn($barcode,$shopCode){
		
		$rows = self::getOrderByBarcodeShop($barcode, $shopCode);
		if(!$rows) return true;
		$shouldBe = true;
		foreach($rows as $key => $v){
			if($v['stop']== 1) {$shouldBe = false;}
			if($v['never_will_send']== 1){$shouldBe = false;}
			if($v['order_fullfill'] == ""){$shouldBe = false;}
			if($v['order_option']==3){$shouldBe = false;}	
		}
		
		return $shouldBe;
	}
	
	public function getUnhandledOrder ($shopCode){
		
		$whereStr ="`shop_code` LIKE '".$shopCode."' AND `order_option` IS NULL";
		
		$rows =$this->fetchAll($whereStr,"date ASC ");
		if(empty($rows)) return false;
		return $rows->toArray();
		
	}
	public function getStopList ($shopCode){
		
		$whereStr ="`shop_code` LIKE '".$shopCode."' AND ( `stop` = 1 OR `order_option` = 3)";
		
		$rows =$this->fetchAll($whereStr,"date ASC ");
		if(empty($rows)) return false;
		return $rows->toArray();
		
	}
	public function getHandledOrder ($shopCode){
		
		$whereStr ="`shop_code` LIKE '".$shopCode."' AND  `order_option` != 3 AND `order_option` IS NOT NULL AND `order_fullfill` IS NULL ";
		
		$rows =$this->fetchAll($whereStr,"date ASC ");
		if(empty($rows)) return false;
		return $rows->toArray();
		
	}
	
	public function getOrderByOption($orderOption,$shopCode = null){
		
		$whereStr ="`order_option` = {$orderOption}  AND `order_fullfill` IS NULL ";
		if($shopCode != ""){
			$whereStr ="`shop_code` LIKE '".$shopCode."' AND `order_option` = {$orderOption} AND `order_fullfill` IS NULL ";
		}
		$rows =$this->fetchAll($whereStr,"date ASC ");
		if(empty($rows)) return false;
		return $rows->toArray();		
	}
	
	
	
	public function fullfillOrder($idExtOrder){
		$data = array(
			'order_fullfill' => 1,
			'order_fullfill_date' => Model_DatetimeHelper::dateToday()
				);
				$this->update($data,"`id_ext_order` = ". $idExtOrder);
	}
	
	public function getFullfilledOrder ($shopCode){
		
		$whereStr ="`shop_code` LIKE '".$shopCode."' AND  `order_fullfill` = 1 ";
		
		$rows =$this->fetchAll($whereStr,"date DESC ");
		if(empty($rows)) return false;
		return $rows->toArray();		
		
	}
	public function updateWhBalance($idExtOrder,$qtyWarehouse){
		$data = array(
						 "qty_wh" => $qtyWarehouse,
		);
		$this->update($data,"`id_ext_order` = ". $idExtOrder);		
	}
}
?>