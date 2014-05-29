<?php
class Model_DbTable_Order_Orderlist extends Zend_Db_Table_Abstract {

	protected $_name = 'orders_v1';
	
	public function getOrderlist( $idOrder){
		
		$row = $this->fetchRow("`id_order` = ". $idOrder);
		if(!$row) return false;
		return $row->toArray();		
		
		}
		
	public function addOrderlist( $orderNo , $orderCreateDate , $staffName , $orderComment , $orderStatus){
		
		$data = array(
			
         "order_no" =>  $orderNo ,
         "order_create_date" =>  $orderCreateDate ,
         "staff_name" =>  $staffName ,
         "order_comment" =>  $orderComment ,
         "order_status" =>  $orderStatus 
	
			);
		$this->insert($data);
		$orderID = $this->getAdapter()->lastInsertId();
		return $orderID;
		}
		
	public function updateOrderlist(  $idOrder ,  $orderNo , $orderCreateDate , $staffName , $orderComment , $orderStatus){
		$data = array(
			
         "order_no" =>  $orderNo ,
         "order_create_date" =>  $orderCreateDate ,
         "staff_name" =>  $staffName ,
         "order_comment" =>  $orderComment ,
         "order_status" =>  $orderStatus 
	
			);
			
		$this->update($data,"`id_order` = ". $idOrder);
		}
		
	public function deleteOrderlist( $idOrder){
		
		$this->delete("`id_order` = ". $idOrder);
		
		}	
	
	public function listAll(){
		
		$rows =$this->fetchAll("1","id_order DESC ");
		if(!$rows) return false;
		return $rows->toArray();			
		
		}	
		
	/**
	 * Extra Function
	 * 
	 */
	public function listAllUnFinallizedOrder(){
		
		$rows =$this->fetchAll("`order_status` < 10 ","id_order DESC ");
		if(!$rows) return false;
		return $rows->toArray();		
	}
	public function listAllFollowUpOrder(){
	
		$rows =$this->fetchAll("`order_status` >= 10 AND `order_status` < 99  ","id_order DESC ");
		if(!$rows) return false;
		return $rows->toArray();
	}	
	public function listAllFinishedOrder(){

		$rows =$this->fetchAll("`order_status` = 99  ","id_order DESC ");
		if(!$rows) return false;
		return $rows->toArray();		
		
	}
	public function listAllCanceledOrder(){
	
		$rows =$this->fetchAll("`order_status` = 100  ","id_order DESC ");
		if(!$rows) return false;
		return $rows->toArray();
	
	}	
	
	
	public function getOrderByNumber($orderNo){
		$row = $this->fetchRow("`order_no` LIKE '".$orderNo."'");
		if(!$row) return false;
		return $row->toArray();		
	}
	
	public function cancelOrder($idOrder){
		
		$data = array(
				"order_status" =>  ORDER_STATUS_CANCEL
		);
		$this->update($data,"`id_order` = ". $idOrder);
	}
	public function lockOrder($idOrder){
		
		$data = array(
				"order_status" =>  ORDER_STATUS_FINALLIZED
		);
		$this->update($data,"`id_order` = ". $idOrder);		
	}		
	
	
}

?>