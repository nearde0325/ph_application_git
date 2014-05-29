<?php
class Model_DbTable_Products_China_Chinaorder extends Zend_Db_Table_Abstract {

	protected $_name = 'products_v1_stock_china_order';
	
	public function getChinaorder( $idOrder){
		
		$row = $this->fetchRow("`id_order` = ". $idOrder);
		if(!$row) return false;
		return $row->toArray();		
		
		}
		
	public function addChinaorder( $noOrder , $barcode , $qty , $actualWeight , $cubicWeight , $weightUse , $dateInpu , $dateEta , $shippingCost){
		
		$data = array(
			
         "no_order" =>  $noOrder ,
         "barcode" =>  $barcode ,
         "qty" =>  $qty ,
         "actual_weight" =>  $actualWeight ,
         "cubic_weight" =>  $cubicWeight ,
         "weight_use" =>  $weightUse ,
         "date_inpu" =>  $dateInpu ,
         "date_eta" =>  $dateEta ,
         "shipping_cost" =>  $shippingCost 
	
			);
		$this->insert($data);
		
		}
		
	public function updateChinaorder(  $idOrder ,  $noOrder , $barcode , $qty , $actualWeight , $cubicWeight , $weightUse , $dateInpu , $dateEta , $shippingCost){
		$data = array(
			
         "no_order" =>  $noOrder ,
         "barcode" =>  $barcode ,
         "qty" =>  $qty ,
         "actual_weight" =>  $actualWeight ,
         "cubic_weight" =>  $cubicWeight ,
         "weight_use" =>  $weightUse ,
         "date_inpu" =>  $dateInpu ,
         "date_eta" =>  $dateEta ,
         "shipping_cost" =>  $shippingCost 
	
			);
			
		$this->update($data,"`id_order` = ". $idOrder);
		}
		
	public function deleteChinaorder( $idOrder){
		
		$this->delete("`id_order` = ". $idOrder);
		
		}	
	
	public function listAll(){
		
		$rows =$this->fetchAll("1","id_order DESC ");
		if(!$rows) return false;
		return $rows->toArray();			
		
		}	
}
?>