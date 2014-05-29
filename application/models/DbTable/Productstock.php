<?php
class Model_DbTable_Productstock extends Zend_Db_Table_Abstract {

	protected $_name = 'products_v1_stock';

	public function getStock($idProduct){
		
		$row = $this->fetchRow('`id_product`='.(int)$idProduct);
		if(!$row){
			return false;
		}
		return $row->toArray();
				
	}
	
	public function idExist($idProduct){
		
		if(self::getStock($idProduct)){
			
			return true;
		}
		
			return false;
	}
	public function getWarehouseStock($idProduct){
		$whStock = self::getStock($idProduct);
		return $whStock['stock_wh'];
	}
	public function getEcoStock($idProduct){
		$ecoStock = self::getStock($idProduct);
		return $ecoStock['stock_ec'];
		
	}
	
	public function insertStockWarehouse($idProduct,$whStock){
		
		$dateToday = Model_DatetimeHelper::dateToday();
		
		if(!self::idExist($idProduct)){
		
			$data = array(
					"id_product" => $idProduct,
					"stock_wh" => $whStock,
					"stock_ec" => 0,
					"stock_wh_date" => $dateToday,
					"stock_ec_date" => $dateToday
			);
		
			$this->insert($data);
		
		}
		
		else{
		
			return false;
		}
		
		return true;
	}
	
	public function insertStockEcommerce($idProduct,$ecoStock){

		$dateToday = Model_DatetimeHelper::dateToday();
		
		if(!self::idExist($idProduct)){
				
			$data = array(
					"id_product" => $idProduct,
					"stock_wh" => 0,
					"stock_ec" => $ecoStock,
					"stock_wh_date" => $dateToday,
					"stock_ec_date" => $dateToday
			);
		
			$this->insert($data);
		
		}
		
		else{
				
			return false;
		}
		
		return true;		
		
	}
	
	public function updateStockWarehouse($idProduct,$whStock){
		//not now 	
		$dateToday = Model_DatetimeHelper::dateToday();
		
		$data = array(
				"stock_wh" => $whStock,
				"stock_wh_date" => $dateToday
				);
		
		$this->update($data,'`id_product` ='.$idProduct);
	}
	
	public function updateStockEcommerce($idProduct,$ecoStock){
		$dateToday = Model_DatetimeHelper::dateToday();
		
		$data = array(
				"stock_ec" => $ecoStock,
				"stock_ec_date" => $dateToday
		);
		
		$this->update($data,'`id_product` ='.$idProduct);		
		
	}
	
	public function updateStockEcommerceOnDate($idProduct,$ecoStock,$dateSelect){
		//$dateToday = self::cDateToday();
	
		$data = array(
				"stock_ec" => $ecoStock,
				"stock_ec_date" => $dateSelect
		);
	
		$this->update($data,'`id_product` ='.$idProduct);
	
	}	
	
	public function insertStockStore(){
		//not now
		
	}
		
	public function increaseStockEcommerce($idProduct,$qty){
		if(!self::idExist($idProduct)){
			
			self::insertStockEcommerce($idProduct, $qty);
			
		}
		else{
			
			$qty += self::getEcoStock($idProduct);
			
			self::updateStockEcommerce($idProduct, $qty);	
		}
		
	}
	public function increaseStockWarehouse($idProduct,$qty){

		if(!self::idExist($idProduct)){
				
			self::increaseStockWarehouse($idProduct, $qty);
				
		}
		else{
				
			$qty += self::getWarehouseStock($idProduct);
				
			self::updateStockWarehouse($idProduct, $qty);
		}
				
	}
	public function descreaseStockEcommerce($idProduct,$qty){

		if(!self::idExist($idProduct)){
				
			self::insertStockEcommerce($idProduct,0);
				
		}
		else{
				
			$qty = self::getEcoStock($idProduct) - $qty;
				
			self::updateStockEcommerce($idProduct, $qty);
		}	
		
	}
	public function descreaseStockEcommerceOnDate($idProduct,$qty,$dateSelect){
		
		if(!self::idExist($idProduct)){
		
			self::insertStockEcommerce($idProduct,0);
		
		}
		else{
		
			$qty = self::getEcoStock($idProduct) - $qty;
		
			self::updateStockEcommerceOnDate($idProduct, $qty,$dateSelect);
		}		
		
		
	}
	
	public function descreaseStockWarehouse($idProduct,$qty){
		
		if(!self::idExist($idProduct)){
		
			self::increaseStockWarehouse($idProduct, $qty);
		
		}
		else{
		
			$qty = self::getWarehouseStock($idProduct) - $qty;
		
			self::updateStockWarehouse($idProduct, $qty);
		}		
	}
	
	public function updateOldShopStock($idProduct,$qty){
		
		$dateToday = Model_DatetimeHelper::dateToday();
		
		$data = array(
				"stock_shop_old" => $qty,
				"stock_shop_old_date" => $dateToday
		);
		
		$this->update($data,'`id_product` ='.$idProduct);
	
	}
	
	public function updateNewShopStock($idProduct,$qty){
		
		$dateToday = Model_DatetimeHelper::dateToday();
		
		$data = array(
				"stock_shop_new" => $qty,
				"stock_shop_new_date" => $dateToday
		);
		
		$this->update($data,'`id_product` ='.$idProduct);		
	}
	
	}
?>	