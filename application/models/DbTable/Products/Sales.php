<?php

class Model_DbTable_Products_Sales extends Zend_Db_Table_Abstract {

	protected $_name = 'products_v1_sales_stock';

	public function getSales( $idSales){

		$row = $this->fetchRow("`id_sales` = ". $idSales);
		if(!$row) return false;
		return $row->toArray();

	}
	
	public function getSalesByCode($codeProduct){
		
		$row = $this->fetchRow("`code_product` LIKE '". $codeProduct."' ");
		if(!$row) return false;
		return $row->toArray();		
	}

	public function addSales( $codeProduct , $salesW1 , $salesW2 , $salesW3 , $salesW4 , $salesW5 , $salesW6 , $salesW7 , $salesW8 , $stockWh, $stockShop){

		$data = array(
					
				"code_product" =>  $codeProduct ,
				"sales_w1" =>  $salesW1 ,
				"sales_w2" =>  $salesW2 ,
				"sales_w3" =>  $salesW3 ,
				"sales_w4" =>  $salesW4 ,
				"sales_w5" =>  $salesW5 ,
				"sales_w6" =>  $salesW6 ,
				"sales_w7" =>  $salesW7 ,
				"sales_w8" =>  $salesW8 ,
				"stock_wh" =>  $stockWh ,
				"stock_shop" => $stockShop

		);
		$this->insert($data);

	}

	public function updateSales(  $idSales ,  $codeProduct , $salesW1 , $salesW2 , $salesW3 , $salesW4 , $salesW5 , $salesW6 , $salesW7 , $salesW8 , $stockWh , $stockShop){
		$data = array(
					
				"code_product" =>  $codeProduct ,
				"sales_w1" =>  $salesW1 ,
				"sales_w2" =>  $salesW2 ,
				"sales_w3" =>  $salesW3 ,
				"sales_w4" =>  $salesW4 ,
				"sales_w5" =>  $salesW5 ,
				"sales_w6" =>  $salesW6 ,
				"sales_w7" =>  $salesW7 ,
				"sales_w8" =>  $salesW8 ,
				"stock_wh" =>  $stockWh ,
				"stock_shop" => $stockShop

		);
			
		$this->update($data,"`id_sales` = ". $idSales);
	}
	
	public function updateStock($idSales, $stockWh , $stockShop){
		
		$data = array(
				"stock_wh" =>  $stockWh ,
				"stock_shop" => $stockShop				
				);		
		$this->update($data,"`id_sales` = ". $idSales);	
	}
	
	public function updateKt($idSales,$ktWh){
		
		$data = array(
				"kt_wh" =>$ktWh
				);
	$this->update($data,"`id_sales` = ". $idSales);

			
	}
	public function deleteSales( $idSales){

		$this->delete("`id_sales` = ". $idSales);

	}
	public function deleteAll(){
		
		
		$this->getAdapter()->query('TRUNCATE TABLE '.$this->_name);
	}

	public function listAll(){

		$rows =$this->fetchAll("1","id_sales DESC ");
		if(!$rows) return false;
		return $rows->toArray();

	}
	
	public static function weightOrderAverage($salesWk1,$salesWk2,$salesWk3,$salesWk4,$salesWk5,$salesWk6,$salesWk7,$salesWk8){
		
		$woa =  128 / 255 * $salesWk1 + 64 / 255 * $salesWk2 + 32 / 255 * $salesWk3 + 16 / 255 * $salesWk4 + 8 / 255 * $salesWk5 + 4 / 255 * $salesWk6 + 2 / 255 * $salesWk7 + $salesWk8 / 255;
		return Round($woa,1);
	}
	
	public static function eightWeekNewProduct($dateIntroduce,$dateEnd){
		//dateBegin  is last week  monday -7 
		//dateEnd is last week end 
		 $intDate = Model_DatetimeHelper::tranferToInt($dateIntroduce);
		$result = 10;
		for($i=0;$i<8;$i++){
			
			$actDateEnd = Model_DatetimeHelper::tranferToInt(Model_DatetimeHelper::adjustWeeks("sub", $dateEnd, $i));
			$actDateBegin = $actDateEnd - 86400 * 6;
			
			//var_dump($i,$actDateBegin,$actDateEnd);
			
			if($intDate>=$actDateBegin && $intDate<$actDateEnd){
				
				$result = $i + 1;
			}
			
		
			
		}
		
		return $result;	
	} 
}


?>