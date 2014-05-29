<?php
/*
 this is the class which send automatic email when:
Something Done:

Something Wrong:
1) If download failed, Email me


*/
class Model_DbTable_Pr_Prproducts extends Zend_Db_Table_Abstract {
	
	protected $_name = 'pr_products';
	
	
	public function getProduct($id){
		$row = $this->fetchRow('`id_product`='.(int)$id);
		if(!$row){
			return false;
		}
		return $row->toArray();		
		}
	public function getProductByCode($productCode){
		$idPro = self::getProductID($productCode);
		if($idPro!==false){
			return self::getProduct($idPro);
		}
	}	
	
	public function ifExist($productCode){

		$select = $this->_db->select()
		->from($this->_name,array('*'))
		->where('code_product LIKE ?',$productCode);
		$result = $this->getAdapter()->fetchAll($select);
		
		if(!$result){return false;}
		
		return true;		
		}		
	/**
	 * Get Repair Parts Product ID By It's Product Barcode
	 * @param string $productCode
	 * @return int $idProduct
	 */	
	public function getProductID($productCode){
		$select = $this->_db->select()
		->from($this->_name,array('*'))
		->where('code_product LIKE ?',$productCode);
		$result = $this->getAdapter()->fetchAll($select);
		
		if(!$result){return false;}
		
		return $result[0]['id_product'];	
		
	}
	public function isBonusProduct($productCode){
		$select = $this->_db->select()
		->from($this->_name,array('*'))
		->where('code_product LIKE ?',$productCode);
		$result = $this->getAdapter()->fetchAll($select);
		
		if(!$result){
			return false;
		}
		
		return (bool) $result[0]['w_bonus'];
	}
	
	public function getProductDes($productCode){
		$select = $this->_db->select()
		->from($this->_name,array('*'))
		->where('code_product LIKE ?',$productCode);
		$result = $this->getAdapter()->fetchAll($select);
	
		if(!$result){
			return false;
		}
	
		return $result[0]['title_product'];
	
	}		
	
	public function addProduct($idCate,$codeProduct,$titleProduct){
		
		date_default_timezone_set('Australia/Melbourne');
		
		$dateToday = date("Y-m-d");
		$data = array(
			"id_cate" => $idCate,	
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

	public function deleteProduct($id){
		$this->delete('`id_product` =' . $id);
		}
	public function listAllProducts(){
		$rows =$this->fetchAll(1,'id_product');
		
		return $rows->toArray();
	}	
	
	public function listAllActive(){
		$rows =$this->fetchAll("`active` = 1",'id_product');
		return $rows->toArray();		
	}
	public function listAllActiveCategory($idCate){
		$rows =$this->fetchAll("`active` = 1 AND `id_cate` = ".$idCate,'title_product');
		return $rows->toArray();
		
	}
	
	public function listProductsByCate($idCate){
		
		$rows =$this->fetchAll("`id_cate` = ".$idCate,"title_product");
		
		return $rows->toArray();		
		
	}
	
	public function listProductsByData($date){
		$rows =$this->fetchAll("`date_introduce` ='".$date."'","id_product");
		
		return $rows->toArray();		
		
	}
	public function listNewProducts($date){
		
		$rows =$this->fetchAll("`date_introduce` >= '".$date."'","id_product");
		
		if(!$rows){return false;}
		
		return $rows->toArray();		
		
	}
	public function updatePrice($idProduct,$priceA,$priceB,$priceC,$date){
		$data = array(
				"price_a" => $priceA,
				"price_b" => $priceB,
				"price_c" => $priceC,
				"date_introduce" => $date
		);
		$this->update($data,'`id_product` ='.$idProduct);
		
	}
	
	public function updateSinglePrice($idProduct,$priceString, $price){
		
		$data = array($priceString => $price ,"date_introduce" => Model_DatetimeHelper::dateToday());
		$this->update($data,'`id_product` ='.$idProduct);
	}
	
	public function updateCost($idProduct,$actCost,$date){
		$data = array(
				"act_cost" => $actCost,
				"date_introduce" => $date
		);
		$this->update($data,'`id_product` ='.$idProduct);		
		
	}
	
	public function updateDes($idProduct,$des){
		$data = array(
				"title_product" => $des
		);
		$this->update($data,'`id_product` ='.$idProduct);
		
	}
	public function listAllWithBonus(){
		$whereStr = "`w_bonus` = 1";
		$rows =$this->fetchAll($whereStr,"id_product");
		
		return $rows->toArray();		
	}
	public function activeProduct($idProduct,$disAct = null){
		$data = array('active'=>1);
		if($disAct){$data = array('active'=>0);}
		$this->update($data,'`id_product` ='.$idProduct);		
		
	}
}
?>