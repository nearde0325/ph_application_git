<?php
class Model_DbTable_Pr_Bonus extends Zend_Db_Table_Abstract {

	protected $_name = 'pr_bonus';
	
	public function getBonus( $idCate){
		
		$row = $this->fetchRow("`id_cate` = ". $idCate);
		if(!$row) return false;
		return $row->toArray();		
		
		}
		
	public function addBonus( $titleCate , $fullPrice , $disPrice , $lowPrice , $visible){
		
		$data = array(
			
         "title_cate" =>  $titleCate ,
         "full_price" =>  $fullPrice ,
         "dis_price" =>  $disPrice ,
         "low_price" =>  $lowPrice ,
         "visible" =>  $visible 
	
			);
		$this->insert($data);
		
		}
		
	public function updateBonus(  $idCate ,  $titleCate , $fullPrice , $disPrice , $lowPrice , $visible){
		$data = array(
			
         "title_cate" =>  $titleCate ,
         "full_price" =>  $fullPrice ,
         "dis_price" =>  $disPrice ,
         "low_price" =>  $lowPrice ,
         "visible" =>  $visible 
	
			);
			
		$this->update($data,"`id_cate` = ". $idCate);
		}
		
	public function deleteBonus( $idCate){
		
		$this->delete("`id_cate` = ". $idCate);
		
		}	
	
	public function listAll(){
		
		$rows =$this->fetchAll("1","id_cate ASC ");
		if(!$rows) return false;
		return $rows->toArray();			
		
		}
	public function listVisible(){
		
		$rows =$this->fetchAll("`visible` = 1 ","id_cate ASC ");
		if(!$rows) return false;
		return $rows->toArray();		
	}		
}

?>