<?php
class Model_DbTable_Whbalance extends Zend_Db_Table_Abstract {

	protected $_name = 'products_v1_stock_balance_wh';
	
	public function getWhbalance( $idBal){
		
		$row = $this->fetchRow("`id_bal` = ". $idBal);
		if(!$row) return false;
		return $row->toArray();		
		
		}
		
	public function addWhbalance( $barcode , $qtyPending , $qtySys , $idMovement , $date){
		
		$data = array(
			
         "barcode" =>  $barcode ,
         "qty_pending" =>  $qtyPending ,
         "qty_sys" =>  $qtySys ,
         "id_movement" =>  $idMovement ,
         "date" =>  $date 
	
			);
		$this->insert($data);
		
		}
		
	public function updateWhbalance(  $idBal ,  $barcode , $qtyPending , $qtySys , $idMovement , $date){
		$data = array(
			
         "barcode" =>  $barcode ,
         "qty_pending" =>  $qtyPending ,
         "qty_sys" =>  $qtySys ,
         "id_movement" =>  $idMovement ,
         "date" =>  $date 
	
			);
			
		$this->update($data,"`id_bal` = ". $idBal);
		}
		
	public function deleteWhbalance( $idBal){
		
		$this->delete("`id_bal` = ". $idBal);
		
		}	
	
	public function listAll(){
		
		$rows =$this->fetchAll("1","id_bal DESC ");
		if(!$rows) return false;
		return $rows->toArray();			
		
		}	
}
?>