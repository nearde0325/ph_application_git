<?php

class Model_DbTable_Pr_Pricecategory extends Zend_Db_Table_Abstract {

	protected $_name = 'pr_price_category';
	
	public function getPricecategory( $idPriceCategory){
		
		$row = $this->fetchRow("`id_price_category` = ". $idPriceCategory);
		if(!$row) return false;
		return $row->toArray();		
		
		}
		
	public function addPricecategory( $titleCategory , $commentCategory,$active = 0){
		
		$data = array(
			
         "title_category" =>  $titleCategory ,
         "comment_category" =>  $commentCategory, 
		 "active" => $active		
	
			);
		$this->insert($data);
		
		}
		
	public function updatePricecategory(  $idPriceCategory ,  $titleCategory , $commentCategory,$active,$activeEdit,$color,$order){
		$data = array(
			
         "title_category" =>  $titleCategory ,
         "comment_category" =>  $commentCategory,
		 "active" => $active,
				"active_edit" => $activeEdit,
				"bk_color" => $color,
				"order" => $order
			);
			
		$this->update($data,"`id_price_category` = '". $idPriceCategory."'");
		}
		
	public function deletePricecategory( $idPriceCategory){
		
		$this->delete("`id_price_category` = ". $idPriceCategory);
		
		}	
	
	public function listAll(){
		
		$rows =$this->fetchAll("1","id_price_category ASC ");
		if(!$rows) return false;
		return $rows->toArray();			
		
		}
	public function listAllActive(){
		
			$rows =$this->fetchAll("`active`= 1 AND `order` > 0 ","order ASC ");
			if(!$rows) return false;
			return $rows->toArray();
	}
	public function listAllActiveEdit(){
	
		$rows =$this->fetchAll("`active_edit`= 1","id_price_category  ASC ");
		if(!$rows) return false;
		return $rows->toArray();
	}		
}


?>