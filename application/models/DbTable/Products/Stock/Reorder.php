<?php
//namespace application\models\DbTable\Products\Stock;
class Model_DbTable_Products_Stock_Reorder extends Zend_Db_Table_Abstract {

	protected $_name = 'products_v1_stock_reorder';

	public function getReorder( $id){

		$row = $this->fetchRow("`id` = ". $id);
		if(!$row) return false;
		return $row->toArray();

	}

	public function addReorder( $barcode , $shopCode){

		$data = array(
					
				"barcode" =>  $barcode ,
				"shop_code" =>  $shopCode

		);
		$this->insert($data);

	}

	public function updateReorder(  $id ,  $barcode , $shopCode){
		$data = array(
					
				"barcode" =>  $barcode ,
				"shop_code" =>  $shopCode

		);
			
		$this->update($data,"`id` = ". $id);
	}

	public function deleteReorder( $id){

		$this->delete("`id` = ". $id);

	}

	public function listAll(){

		$rows =$this->fetchAll("1","id DESC ");
		if(!$rows) return false;
		return $rows->toArray();

	}
	
	public function getSum(){
		
		$sqlstr = "SELECT Count(`barcode`) AS cot , `barcode`  FROM `products_v1_stock_reorder` GROUP BY `barcode` ORDER BY cot DESC;";
		$db = $this->getAdapter();
		$stmt = $db->prepare($sqlstr);
		$stmt->execute();
		$result = $stmt->fetchAll();
		return $result;
		
	}
	
	public function getReorderByShop($shopCode){
		
		$sqlstr = "SELECT DISTINCT `barcode`  FROM `products_v1_stock_reorder` WHERE `shop_code` LIKE '".$shopCode."' ORDER BY `barcode`;";
		$db = $this->getAdapter();
		$stmt = $db->prepare($sqlstr);
		$stmt->execute();
		$result = $stmt->fetchAll();
		return $result;		
	}
}
	


?>