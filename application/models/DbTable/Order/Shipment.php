<?php

class Model_DbTable_Order_Shipment extends Zend_Db_Table_Abstract {

	protected $_name = 'orders_shipment';
	
	public function getShipment( $idShipment){
		
		$row = $this->fetchRow("`id_shipment` = ". $idShipment);
		if(!$row) return false;
		return $row->toArray();		
		
		}
		
	public function addShipment( $nameCourier , $trackingNo , $dateShip , $dateEta , $dateArrival , $received , $comm){
		
		$data = array(
			
         "name_courier" =>  $nameCourier ,
         "tracking_no" =>  $trackingNo ,
         "date_ship" =>  $dateShip ,
         "date_eta" =>  $dateEta ,
         "date_arrival" =>  $dateArrival ,
         "received" =>  $received ,
         "comm" =>  $comm 
	
			);
		$this->insert($data);
		
		return $this->getAdapter()->lastInsertId();
		
		}
		
	public function updateShipment(  $idShipment ,  $nameCourier , $trackingNo , $dateShip , $dateEta , $dateArrival , $received , $comm){
		$data = array(
			
         "name_courier" =>  $nameCourier ,
         "tracking_no" =>  $trackingNo ,
         "date_ship" =>  $dateShip ,
         "date_eta" =>  $dateEta ,
         "date_arrival" =>  $dateArrival ,
         "received" =>  $received ,
         "comm" =>  $comm 
	
			);
			
		$this->update($data,"`id_shipment` = ". $idShipment);
		}
		
	public function deleteShipment( $idShipment){
		
		$this->delete("`id_shipment` = ". $idShipment);
		
		}	
	
	public function listAll(){
		
		$rows =$this->fetchAll("1","id_shipment DESC ");
		if(!$rows) return false;
		return $rows->toArray();			
		
	}
	public function getOnTheSea(){
		
		$whereStr = "received = 0";
		$rows = $this->fetchAll($whereStr,array("date_ship ASC","id_shipment ASC"));
		if(empty($rows)) return false;
		return $rows->toArray();		
		
	}
	
}


?>