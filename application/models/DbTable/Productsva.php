<?php
/*
 this is the class which send automatic email when:
Something Done:

Something Wrong:
1) If download failed, Email me


*/
class Model_DbTable_Productsva extends Zend_Db_Table_Abstract {
	
	protected $_name = 'products_v1';
	
	
	public function getProduct($id){
		$row = $this->fetchRow('`id_product`='.(int)$id);
		if(!$row){
			return false;
		}
		return $row->toArray();		
		}
	
	public function ifExist($productCode){

		$select = $this->_db->select()
		->from($this->_name,array('*'))
		->where('code_product LIKE ?',$productCode);
		//->where('sp_id = ?',$idSupplyer)
		//->order('product_id');
		$result = $this->getAdapter()->fetchAll($select);
		
		if(!$result){return false;}
		
		return true;		
		}		
	/**
	 * get product ID by Code 
	 * @param string $productCode
	 * @return int Product ID
	 */	
	public function getProductID($productCode){
		
		$row = $this->fetchRow("`code_product` LIKE '{$productCode}'");
		if(!$row) return false;
		return $row['id_product'];
		
	}	
	
	public function addProduct($codeProduct,$titleProduct){
		
		date_default_timezone_set('Australia/Melbourne');
		
		$dateToday = date("Y-m-d");
		$data = array(
			"code_product" => $codeProduct,
			"title_product" =>$titleProduct,		
			"date_introduce" => $dateToday
		);
		
		$this->insert($data);
		
		$idProduct = $this->getAdapter()->lastInsertId();
		
		return $idProduct;
		
		}
	public function updateProduct($idProduct,$codeProduct,$titleProduct){
		
		$data = array(
			"code_product" => $codeProduct,
			"title_product" => $titleProduct		
				);
		$this->update($data,'`id_product` ='.$idProduct);
		
		
		}	
	public function updateProductTitle($idProduct,$titleProduct){
		$data = array(
				"title_product" => $titleProduct
		);
		$this->update($data,'`id_product` ='.$idProduct);		
		
	}
		
	public function deleteProduct($id){
		$this->delete('`id_product` =' . $id);
		}
	/**
	 * Get All products listed 
	 * @return array  $v["id_product"],$v["code_product"],$v["title_product"].$v["date_introduce"];
	 */	
	public function listAllProducts(){
		$rows =$this->fetchAll(1,'id_product');
		
		return $rows->toArray();
	}
	public function listAllProductsNew(){
		$rows =$this->fetchAll(1,'id_product DESC');
	
		return $rows->toArray();
	}

	public function listAllProductsStockPrice(){
		
		
		$select = $this->_db->select()
		->from(array('p' => $this->_name),array('p.id_product','*'))
		->where('1')
		->joinLeft(array('s' => 'products_v1_stock'),'p.id_product = s.id_product',array('s.stock_wh','s.stock_wh_date'))
		->joinLeft(array('pr' => 'products_v1_price'),'p.id_product = pr.id_product',array('pr.price_import','pr.id_import_currency','pr.update_import','pr.price_buy','pr.update_buy','pr.price_wholesale_real','pr.update_wholesale_real','pr.price_rrp','pr.update_rrp'))
		->order('code_product ASC');
		
		$result = $this->getAdapter()->fetchAll($select);
		
		if(!$result) return false;
		
		return $result;
		
	}
	
	public function listAllProductsStockDetail(){
		
		$select = $this->_db->select()
		->from(array('p' => $this->_name),array('p.id_product','*'))
		->where('1')
		->joinLeft(array('s' => 'products_v1_stock'),'p.id_product = s.id_product',array('s.stock_wh','s.stock_wh_date','s.stock_shop_old','s.stock_shop_old_date','s.stock_shop_new','s.stock_shop_new_date'))
		->order('code_product ASC');
		
		$result = $this->getAdapter()->fetchAll($select);
		
		if(!$result) return false;
		
		return $result;		
	}
	
	
	
	public function listProductsByData($date){
		$rows =$this->fetchAll("`date_introduce` ='".$date."'","id_product");
		
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
	
	public function isNewProduct($productCode){
		
		$newProductLine = 56;
		
		$thisMonday = Model_DatetimeHelper::getThisWeekMonday();
		$intDateCompare = Model_DatetimeHelper::tranferToInt(Model_DatetimeHelper::adjustDays("sub", $thisMonday, $newProductLine));
		
		if(!self::ifExist($productCode)) {return true;}
		
		$proLine = self::getProduct(self::getProductID($productCode));
		$intDateIntro = Model_DatetimeHelper::tranferToInt($proLine['date_introduce']);
		
		if($intDateIntro >= $intDateCompare){return true;}
		
		return false;
	}
}
?>