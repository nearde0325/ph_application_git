<?php
//namespace application\models\DbTable\Products\Price;

class Model_DbTable_Products_Price_Margin extends Zend_Db_Table_Abstract {

	
	protected $_name = 'products_v1_margin';
	
	public function getMarginByID($idProduct,$buyPrice,$marginType){
		//whole sale = 1
		$wholeSalePrice = $buyPrice * 1.3;
		$price = ceil( $wholeSalePrice * 2) / 2 ;
		return 	$price;
		
		
	}
	public function getMarginByCode(){
		
	}
}

?>