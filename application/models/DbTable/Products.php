<?php 
/**
 * Phone Collection Products DbTable

 */
 class Model_DbTable_Products extends Zend_Db_Table_Abstract 
{
	/**
	 * @var string Class Table Name
	 */
	protected $_name = 'products_v0'; 
	
	public function getProduct($idProduct){
		
		$rows = $this->fetchRow("`id_product` = ".$idProduct);
		return $rows->toArray();
	}	
	public function getProductNameByCode($productCode){
		
		$rows = $this->fetchRow("`code_product` = '".$productCode."'");
		
		if(isset($rows)){
			$row = $rows->toArray();
			return $row['title_product'];
		}

		return false;
	}
	
	public function addProduct($productCode,$productTitle){
		$data = array(
				'code_product' => $productCode,
				'title_product' => $productTitle,
				);
		$this->insert($data);
							
	}
	
	public function updateProduct($idProduct,$productCode,$productTitle){
		
		$data = array(
				'code_product' => $productCode,
				'title_product' => $productTitle,				
				);
		$this->update($data,'`id_product` ='.$idProduct);
	}
	
}
?>