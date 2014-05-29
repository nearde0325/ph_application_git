<?php
class Model_DbTable_Order_Followup extends Zend_Db_Table_Abstract {

	protected $_name = 'orders_followup';
	
	public function getOrder_Followup( $idFollowup){
		
		$row = $this->fetchRow("`id_followup` = ". $idFollowup);
		if(!$row) return false;
		return $row->toArray();		
		
		}
		
	public function addOrder_Followup( $idOrderProduct , $idSp , $etrDate , $eqrQty , $artDate , $arqQty){
		
		$data = array(
			
         "id_order_product" =>  $idOrderProduct ,
         "id_sp" =>  $idSp ,
         "etr_date" =>  $etrDate ,
         "eqr_qty" =>  $eqrQty ,
         "art_date" =>  $artDate ,
         "arq_qty" =>  $arqQty 
	
			);
		$this->insert($data);
		
		}
		
	public function updateOrder_Followup(  $idFollowup ,  $idOrderProduct , $idSp , $etrDate , $eqrQty , $artDate , $arqQty){
		$data = array(
			
         "id_order_product" =>  $idOrderProduct ,
         "id_sp" =>  $idSp ,
         "etr_date" =>  $etrDate ,
         "eqr_qty" =>  $eqrQty ,
         "art_date" =>  $artDate ,
         "arq_qty" =>  $arqQty 
	
			);
			
		$this->update($data,"`id_followup` = ". $idFollowup);
		}
		
	public function deleteOrder_Followup( $idFollowup){
		
		$this->delete("`id_followup` = ". $idFollowup);
		
		}	
	
	public function listAll(){
		
		$rows =$this->fetchAll("1","id_followup DESC ");
		if(!$rows) return false;
		return $rows->toArray();			
		
		}	
}

?>