<?php
class Model_DbTable_Products_Price_History extends Zend_Db_Table_Abstract {

	protected $_name = 'products_v1_price_history';
	
	public function getHistory( $idHistory){
		
		$row = $this->fetchRow("`id_history` = ". $idHistory);
		if(!$row) return false;
		return $row->toArray();		
		
		}
		
	public function addHistory( $idProduct , $typePrice , $idCurrency , $dateChange , $priceHistory){
		
		$data = array(
			
         "id_product" =>  $idProduct ,
         "type_price" =>  $typePrice ,
         "id_currency" =>  $idCurrency ,
         "date_change" =>  $dateChange ,
         "price_history" =>  $priceHistory 
	
			);
		$this->insert($data);
		
		}
		
	public function updateHistory(  $idHistory ,  $idProduct , $typePrice , $idCurrency , $dateChange , $priceHistory){
		$data = array(
			
         "id_product" =>  $idProduct ,
         "type_price" =>  $typePrice ,
         "id_currency" =>  $idCurrency ,
         "date_change" =>  $dateChange ,
         "price_history" =>  $priceHistory 
	
			);
			
		$this->update($data,"`id_history` = ". $idHistory);
		}
		
	public function deleteHistory( $idHistory){
		
		$this->delete("`id_history` = ". $idHistory);
		
		}	
	
	public function listAll(){
		
		$rows =$this->fetchAll("1","id_history DESC ");
		if(!$rows) return false;
		return $rows->toArray();			
		
		}	
}
?>