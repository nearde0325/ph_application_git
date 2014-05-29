<?php
class Model_DbTable_Shopmovement extends Zend_Db_Table_Abstract {

	protected $_name = 'products_v1_stock_movement_shop';
	
	public function getShopmovement( $idMovement){
		
		$row = $this->fetchRow("`id_movement` = ". $idMovement);
		if(!$row) return false;
		return $row->toArray();		
		
		}
		
	public function addShopmovement( $barcode , $shopCode , $idOperator , $codeOperator , $qtyAdjust , $qtyPending , $qtySys , $date , $time , $staff){
		
		$data = array(
			
         "barcode" =>  $barcode ,
         "shop_code" =>  $shopCode ,
         "id_operator" =>  $idOperator ,
         "code_operator" =>  $codeOperator ,
         "qty_adjust" =>  $qtyAdjust ,
         "qty_pending" =>  $qtyPending ,
         "qty_sys" =>  $qtySys ,
         "date" =>  $date ,
         "time" =>  $time ,
         "staff" =>  $staff 
	
			);
		$this->insert($data);
		
		}
		
	public function updateShopmovement(  $idMovement ,  $barcode , $shopCode , $idOperator , $codeOperator , $qtyAdjust , $qtyPending , $qtySys , $date , $time , $staff){
		$data = array(
			
         "barcode" =>  $barcode ,
         "shop_code" =>  $shopCode ,
         "id_operator" =>  $idOperator ,
         "code_operator" =>  $codeOperator ,
         "qty_adjust" =>  $qtyAdjust ,
         "qty_pending" =>  $qtyPending ,
         "qty_sys" =>  $qtySys ,
         "date" =>  $date ,
         "time" =>  $time ,
         "staff" =>  $staff 
	
			);
			
		$this->update($data,"`id_movement` = ". $idMovement);
		}
		
	public function deleteShopmovement( $idMovement){
		
		$this->delete("`id_movement` = ". $idMovement);
		
		}	
	
	public function listAll(){
		
		$rows =$this->fetchAll("1","id_movement DESC ");
		if(!$rows) return false;
		return $rows->toArray();			
		
		}	
}
?>