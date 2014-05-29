<?php

class Model_DbTable_Order_Weeklyorder extends Zend_Db_Table_Abstract {

	protected $_name = 'products_v1_order_weekly';
	
	public function getWeeklyorder( $idOrder){
		
		$row = $this->fetchRow("`id_order` = ". $idOrder);
		if(!$row) return false;
		return $row->toArray();		
		
		}
		
	public function addWeeklyorder( $noOrder , $shopCode , $dateOrder , $staffOrder , $codeProduct , $qtySalesW1 , $qtySalesW2 , $stockShop , $stockWh , $statusHot , $ktAssigned , $actSend , $actReceived , $dateReceived){
		
		$data = array(
			
         "no_order" =>  $noOrder ,
         "shop_code" =>  $shopCode ,
         "date_order" =>  $dateOrder ,
         "staff_order" =>  $staffOrder ,
         "code_product" =>  $codeProduct ,
         "qty_sales_w1" =>  $qtySalesW1 ,
         "qty_sales_w2" =>  $qtySalesW2 ,
         "stock_shop" =>  $stockShop ,
         "stock_wh" =>  $stockWh ,
         "status_hot" =>  $statusHot ,
         "kt_assigned" =>  $ktAssigned ,
         "act_send" =>  $actSend ,
         "act_received" =>  $actReceived ,
         "date_received" =>  $dateReceived 
	
			);
		$this->insert($data);
		
		}
	public function initalorder( $noOrder , $shopCode , $dateOrder , $codeProduct , $qtySalesW1 , $qtySalesW2 , $stockShop , $stockWh , $statusHot,$orderQty,$maxSoldQty,$desProduct){

		$data = array(
					
				"no_order" =>  $noOrder ,
				"shop_code" =>  $shopCode ,
				"date_order" =>  $dateOrder ,
				"code_product" =>  $codeProduct ,
				"des_product" =>  $desProduct ,
				"qty_sales_w1" =>  $qtySalesW1 ,
				"qty_sales_w2" =>  $qtySalesW2 ,
				"stock_shop" =>  $stockShop ,
				"stock_wh" =>  $stockWh ,
				"status_hot" =>  $statusHot,
				"qty_order"	=> $orderQty,
				"qty_max_sold" => $maxSoldQty	
		);
		$this->insert($data);
	}
		
	public function updateWeeklyorder(  $idOrder ,  $noOrder , $shopCode , $dateOrder , $staffOrder , $codeProduct , $qtySalesW1 , $qtySalesW2 , $stockShop , $stockWh , $statusHot , $ktAssigned , $actSend , $actReceived , $dateReceived){
		$data = array(
			
         "no_order" =>  $noOrder ,
         "shop_code" =>  $shopCode ,
         "date_order" =>  $dateOrder ,
         "staff_order" =>  $staffOrder ,
         "code_product" =>  $codeProduct ,
         "qty_sales_w1" =>  $qtySalesW1 ,
         "qty_sales_w2" =>  $qtySalesW2 ,
         "stock_shop" =>  $stockShop ,
         "stock_wh" =>  $stockWh ,
         "status_hot" =>  $statusHot ,
         "kt_assigned" =>  $ktAssigned ,
         "act_send" =>  $actSend ,
         "act_received" =>  $actReceived ,
         "date_received" =>  $dateReceived 
	
			);
			
		$this->update($data,"`id_order` = ". $idOrder);
		}
		
	public function deleteWeeklyorder( $idOrder){
		
		$this->delete("`id_order` = ". $idOrder);
		
		}	
	
	public function listAll(){
		
		$rows =$this->fetchAll("1","id_order DESC ");
		if(!$rows) return false;
		return $rows->toArray();			
		
		}	
		
	public function getWithQtyOrder($shopCode,$dateOrder,$withQty = 'with'){
		
		$maxSoldLine = 0;
		$whereStr = "(`qty_order` > 0 OR (`stock_shop` + `qty_order`) < ( `qty_max_sold` - {$maxSoldLine})) AND `shop_code` LIKE '{$shopCode}' AND `date_order` = '{$dateOrder}'";
		$orderStr = "code_product ASC";
		
		if($withQty == 'wo'){
			
			$whereStr = "(`qty_order` <= 0 AND (`stock_shop` + `qty_order`) >= ( `qty_max_sold` - {$maxSoldLine})) AND `shop_code` LIKE '{$shopCode}' AND `date_order` = '{$dateOrder}'";
		
		}
		$rows = $this->fetchAll($whereStr,$orderStr);
		
		if(empty($rows)){ return false;}
		return $rows->toArray();		
	}
	public function getOrderByNo($noOrder){
		$rows = $this->fetchAll("no_order LIKE '{$noOrder}'","code_product");
		if(empty($rows)) return false;
		return $rows->toArray();				
	}	
	
	public function getOrderDate($noOrder){
		$row = $this->fetchRow("no_order LIKE '{$noOrder}'","code_product");
		if(!$row) return false;
		$row1 = $row->toArray();
		return $row1['date_order'];
	}
	
	public function getOrderByDate($date){
		
		
		$whereStr = "`date_order` >= '{$date}'";
		$select = $this->select()
			->from($this->_name,array('DISTINCT (code_product)','des_product'))
			->where($whereStr)
			->order("code_product");
		
		$rows = $this->fetchAll($select);
		
		return $rows->toArray();
	}
		
}



?>