<?php
class Model_DbTable_Pr_Prstock extends Zend_Db_Table_Abstract {

	protected $_name = 'pr_products_stock';

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
	public function getShopStock($idProduct,$idShop){
		$ecoStock = self::getStock($idProduct);
		$shopStr = "stock_sp".$idShop;
		return $ecoStock[$shopStr];
		
	}
	
	public function insertStockWarehouse($idProduct,$whStock){
		
		$dateToday = self::cDateToday();
		
		if(!self::idExist($idProduct)){
		
			$data = array(
					"id_product" => $idProduct,
					"stock_wh" => $whStock,
					"stock_wh_date" => $dateToday
			);
		
			$this->insert($data);
		
		}
		
		else{
		
			return false;
		}
		
		return true;
	}
	
	public function insertStockShop($idProduct,$shopStock,$idShop){

		$dateToday = self::cDateToday();
		
		$strShop = "stock_sp".$idShop;
		$strShopDate = "stock_sp".$idShop."_date";
		
		
		if(!self::idExist($idProduct)){
				
			$data = array(
					"id_product" => $idProduct,
					"stock_wh" => 0,
					$strShop => $shopStock,
					"stock_wh_date" => $dateToday,
					$strShopDate => $dateToday
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
		$dateToday = self::cDateToday();
		
		$data = array(
				"stock_wh" => $whStock,
				"stock_wh_date" => $dateToday
				);
		
		$this->update($data,'`id_product` ='.$idProduct);
	}
	
	public function updateStockShop($idProduct,$shopStock,$idShop){
		$dateToday = self::cDateToday();
		
		$strShop = "stock_sp".$idShop;
		$strShopDate = "stock_sp".$idShop."_date";
		
		$data = array(
				$strShop => $shopStock,
				$strShopDate => $dateToday
		);
		
		$this->update($data,'`id_product` ='.$idProduct);		
		
	}
	
	public function updateStockShopOnDate($idProduct,$shopStock,$dateSelect,$idShop){
		//$dateToday = self::cDateToday();
	
		$strShop = "stock_sp".$idShop;
		$strShopDate = "stock_sp".$idShop."_date";
		
		$data = array(
				$strShop => $shopStock,
				$strShopDate => $dateSelect
		);
	
		$this->update($data,'`id_product` ='.$idProduct);
	
	}	
	
	public function insertStockStore(){
		//not now
		
	}
		
	public function increaseStockShop($idProduct,$qty,$idShop){
		if(!self::idExist($idProduct)){
			
			self::insertStockShop($idProduct,$qty, $idShop);
			
		}
		else{
			
			$qty += self::getShopStock($idProduct,$idShop);
			
			self::updateStockShop($idProduct, $qty, $idShop);
		}
		
	}
	public function increaseStockWarehouse($idProduct,$qty){

		if(!self::idExist($idProduct)){
				
			self::insertStockWarehouse($idProduct, $qty);
				
		}
		else{
				
			$qty += self::getWarehouseStock($idProduct);
				
			self::updateStockWarehouse($idProduct, $qty);
		}
				
	}
	public function descreaseStockWarehouse($idProduct,$qty){
	
		if(!self::idExist($idProduct)){
	
			self::insertStockWarehouse($idProduct, 0 - $qty);
	
		}
		else{
	
			$qty = self::getWarehouseStock($idProduct) - $qty;
	
			self::updateStockWarehouse($idProduct, $qty);
		}
	
	}	
	public function descreaseStockShop($idProduct,$qty,$idShop){

		if(!self::idExist($idProduct)){
				
			self::insertStockShop($idProduct,0,$idShop);
				
		}
		else{
				
			$qty = self::getShopStock($idProduct, $idShop) - $qty;
				
			self::updateStockShop($idProduct, $qty, $idShop);
		}	
		
	}
	/*
	public function descreaseStockEcommerceOnDate($idProduct,$qty,$dateSelect){
		
		if(!self::idExist($idProduct)){
		
			self::insertStockEcommerce($idProduct,0);
		
		}
		else{
		
			$qty = self::getEcoStock($idProduct) - $qty;
		
			self::updateStockEcommerceOnDate($idProduct, $qty,$dateSelect);
		}		
		
		
	}
	*/
	

	private function cDateToday(){
		
		date_default_timezone_set('Australia/Melbourne');
		
		return $dateToday = date("Y-m-d");
		
	}
	
	
	}
?>	