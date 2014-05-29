<?php
/**
 * This is Product Price Db Table 
 * It using for log the product latest price,together with the upload time 
 * Which means it only record the last and correct price 
 * For price change history, recorded in the price history table
 * 
 * @author Norman
 *
 */
class Model_DbTable_Products_Price_Prices extends Zend_Db_Table_Abstract {

	protected $_name = 'products_v1_price';
	/**
	 * get one line price 
	 * @param int $idProduct
	 */
	public function getPrice( $idProduct){
		
		$row = $this->fetchRow("`id_product` = ". $idProduct);
		if(!$row) return false;
		return $row->toArray();		
		
		}
	/**
	 * Add Price line into the Db
	 * product ID and buyprice  can not be empty 
	 * only import will indicate what currency 
	 * other using AUD
	 * 
	 * @param int $idProduct
	 * @param float $priceImport
	 * @param int  $idImportCurrency
	 * @param date $updateImport
	 * @param float $priceBuy
	 * @param date $updateBuy
	 * @param float $priceWholesale
	 * @param date $updateWholesale
	 * @param float $priceRrp
	 * @param date $updateRrp
	 */	
		
	public function addPrice($idProduct, $priceImport = NULL , $idImportCurrency = NULL ,$updateImport = NULL  , $priceBuy , $updateBuy , $priceWholesale = NULL  , $updateWholesale = NULL  , $priceRrp = NULL  , $updateRrp = NULL ){
		
		$data = array(

		 "id_product" => $idProduct,				
         "price_import" =>  $priceImport ,
		 "id_import_currency" => $idImportCurrency,
         "update_import" =>  $updateImport ,
         "price_buy" =>  $priceBuy ,
         "update_buy" =>  $updateBuy ,
         "price_wholesale" =>  $priceWholesale ,
         "update_wholesale" =>  $updateWholesale ,
         "price_rrp" =>  $priceRrp ,
         "update_rrp" =>  $updateRrp 
	
			);
		$this->insert($data);
		
		}
	/**
	 * Will Not use this function at all
	 * Will NOT update whole price lot at once 
	 * @param unknown_type $idProduct
	 * @param unknown_type $priceImport
	 * @param unknown_type $updateImport
	 * @param unknown_type $priceBuy
	 * @param unknown_type $updateBuy
	 * @param unknown_type $priceWholesale
	 * @param unknown_type $updateWholesale
	 * @param unknown_type $priceRrp
	 * @param unknown_type $updateRrp
	 */	
	public function updatePrice(  $idProduct ,  $priceImport , $updateImport , $priceBuy , $updateBuy , $priceWholesale , $updateWholesale , $priceRrp , $updateRrp){
		$data = array(
			
         "price_import" =>  $priceImport ,
         "update_import" =>  $updateImport ,
         "price_buy" =>  $priceBuy ,
         "update_buy" =>  $updateBuy ,
         "price_wholesale" =>  $priceWholesale ,
         "update_wholesale" =>  $updateWholesale ,
         "price_rrp" =>  $priceRrp ,
         "update_rrp" =>  $updateRrp 
	
			);
			
		$this->update($data,"`id_product` = ". $idProduct);
		}
	
	/*******************************************
	 * Price update block 
	 * import price 
	 * buy price
	 * wholesale price
	 * rrp
	 * 
	 ******************************************/	
		
			
	public function updateImportPrice($idProduct, $priceImport, $idImportCurrency, $updateImport){
		
		$data = array(	
				"price_import" =>  $priceImport ,
				"update_import" =>  $updateImport ,
				"id_import_currency" => $idImportCurrency
		);		
		$this->update($data,"`id_product` = ". $idProduct);		
	}
	public function updateBuyPrice($idProduct,  $priceBuy , $updateBuy){
		$data = array(
				"price_buy" =>  $priceBuy ,
				"update_buy" =>  $updateBuy
		);
		$this->update($data,"`id_product` = ". $idProduct);		
	}
	public function updateWholeSalePrice($idProduct, $priceWholesale , $updateWholesale ){
		$data = array(
					
				"price_wholesale" =>  $priceWholesale ,
				"update_wholesale" =>  $updateWholesale
		
		);		
		$this->update($data,"`id_product` = ". $idProduct);		
	}
	public function updateWholeSalePriceReal($idProduct, $priceWholesaleReal , $updateWholesaleReal ){
		$data = array(
					
				"price_wholesale_real" =>  $priceWholesaleReal,
				"update_wholesale_real" =>  $updateWholesaleReal
		
		);
		$this->update($data,"`id_product` = ". $idProduct);		
		
	}
	public function updateRRP($idProduct, $priceRrp , $updateRrp){
		$data = array(
				"price_rrp" =>  $priceRrp ,
				"update_rrp" =>  $updateRrp
		);		
		$this->update($data,"`id_product` = ". $idProduct);		
	}		
		
	public function deletePrice( $idProduct){
		
		$this->delete("`id_product` = ". $idProduct);
		
		}	
	
	public function listAll(){
		
		$rows =$this->fetchAll("1","id_product DESC ");
		if(!$rows) return false;
		return $rows->toArray();			
		
		}	
}

?>