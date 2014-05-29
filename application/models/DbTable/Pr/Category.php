<?php


class Model_DbTable_Pr_Category extends Zend_Db_Table_Abstract {
	
	protected $_name = 'pr_products_category';
	
	public function getCategory($id){
		
		$row = $this->fetchRow("`id_cate` =".$id);
		if(!$row) return FALSE;
		return $row->toArray();
				
	}
	
	public function getCategoryTitle($id){
		$row = self::getCategory($id);
		if(!$row) return FALSE;
		return $row["title_cate"];				
	}
	
	public function listCategory(){
		$rows = $this->fetchAll("1");
		if(!$rows) return FALSE;
		return $rows->toArray();			
	}
	
	
}

?>