<?php
class Model_DbTable_Order_Shippingdetail extends Zend_Db_Table_Abstract {

	protected $_name = 'products_v1_ship_detail';
	
	public function getShippingdetail( $idShip){
		
		$row = $this->fetchRow("`id_ship` = ". $idShip);
		if(!$row) return false;
		return $row->toArray();		
		
		}
		
	public function addShippingdetail( $idShipNo , $codeProduct , $qtyShip , $qtyActArrival , $boxNo , $supplyCode , $receiptNo , $comment){
		
		$data = array(
			
         "id_ship_no" =>  $idShipNo ,
         "code_product" =>  $codeProduct ,
         "qty_ship" =>  $qtyShip ,
         "qty_act_arrival" =>  $qtyActArrival ,
         "box_no" =>  $boxNo ,
         "supply_code" =>  $supplyCode ,
         "receipt_no" =>  $receiptNo ,
         "comment" =>  $comment 
	
			);
		$this->insert($data);
		
		}
		
	public function updateShippingdetail(  $idShip ,  $idShipNo , $codeProduct , $qtyShip , $qtyActArrival , $boxNo , $supplyCode , $receiptNo , $comment){
		$data = array(
			
         "id_ship_no" =>  $idShipNo ,
         "code_product" =>  $codeProduct ,
         "qty_ship" =>  $qtyShip ,
         "qty_act_arrival" =>  $qtyActArrival ,
         "box_no" =>  $boxNo ,
         "supply_code" =>  $supplyCode ,
         "receipt_no" =>  $receiptNo ,
         "comment" =>  $comment 
	
			);
			
		$this->update($data,"`id_ship` = ". $idShip);
		}
		
	public function deleteShippingdetail( $idShip){
		
		$this->delete("`id_ship` = ". $idShip);
		
		}	
	
	public function listAll(){
		
		$rows =$this->fetchAll("1","id_ship DESC ");
		if(!$rows) return false;
		return $rows->toArray();			
		
		}
	public function getShippingQty($productCode,$arrShippingOrders){
		$arrRes = array("total"=>0);
		
		foreach($arrShippingOrders as $order){
			
			$qty = self::getShippingQtyOrder($productCode, $order);
			$arrRes[$order] = $qty;
			$arrRes["total"] += $qty;
		}
		return $arrRes;
		
	}	
	public function getShippingQtyOrder($productCode,$orderID){
		
		$whereStr = "`code_product` LIKE '".$productCode."' AND `id_ship_no` = ".$orderID;
		$row = $this->fetchRow($whereStr);
		if(!$row) return false;
		return $row['qty_ship'];
		
	}
	public function getShippingNo(){
		
		$row = $this->fetchRow("1","id_ship_no DESC");
		return $row['id_ship_no'] + 1;
		
	}
}

?>