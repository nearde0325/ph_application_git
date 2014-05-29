<?php
//namespace application\models\DbTable\Products\Stock;

class Model_DbTable_Products_Stock_Tableblocal extends Zend_Db_Table_Abstract {
	
	protected $_name = 'products_v1_stock_take_b';
	//protected $_adaptor = 'db_real';
	//protected $_primary = 'id_take';
		
	public function getTableb( $idTake){
		
		$row = $this->fetchRow("`id_take` = ". $idTake);
		if(!$row) return false;
		return $row->toArray();		
		
		}
		
	public function addTableb( $barcode , $shopHead){
		
		$data = array(
			
         "barcode" =>  $barcode ,
         "shop_head" =>  $shopHead 
	
			);
		$this->insert($data);
		
		}
		
	public function updateTableb(  $idTake ,  $barcode , $shopHead){
		$data = array(
			
         "barcode" =>  $barcode ,
         "shop_head" =>  $shopHead 
	
			);
			
		$this->update($data,"`id_take` = ". $idTake);
		}
		
	public function deleteTableb( $idTake){
		
		$this->delete("`id_take` = ". $idTake);
		
		}	
	
	public function listAll(){
		
		$rows =$this->fetchAll("1","id_take DESC ");
		if(!$rows) return false;
		return $rows->toArray();			
		
		}
		
	public function listByShop($shopHead){
		
		$rows = $this->fetchAll("`shop_head` LIKE '".$shopHead ."'","barcode");
		if(!$rows) return false;
		return $rows->toArray();
		
	}	
	public function deleteAll($shopHead){
		
		$this->delete("`shop_head` LIKE '".$shopHead ."'");
	}	
	
}

?>