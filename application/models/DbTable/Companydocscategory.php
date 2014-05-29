<?php
/*
 this is the class which send automatic email when:
Something Done:

Something Wrong:
1) If download failed, Email me


*/
class Model_DbTable_Companydocscategory extends Zend_Db_Table_Abstract {
	
	protected $_name = 'company_docs_cate';
	
	public function getCategory($id){
		$rows = $this->fetchRow("`id_cate` = ".$id);
		return $rows->toArray();
	}
	
	public function addCategory($cateTitle){
		$data = array(
			"title_cate" =>trim($cateTitle)
				);
		$this->insert($data);		
		
	}
	
	public function listCategory(){
		
		$rows = $this->fetchAll(1);
		
		if($rows){
			
			return $rows->toArray();
		}
		return false;
	}
	public function delCategory($id){
		
		$this->delete("`id_cate` =".$id);
	}
	

	
}
?>