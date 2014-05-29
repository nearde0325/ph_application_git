<?php
//namespace application\models\DbTable\Products\Stock;
/**
 * Weekly Stock Take Diff class access table from 126 server
 * @author Norman
 *
 */

class Model_DbTable_Products_Stock_Stocktakedif extends Zend_Db_Table_Abstract {
	
	protected $_name = 'products_v1_stock_take_dif';
	protected $_adaptor = 'db_real';
	protected $_primary = 'id_take';
		
public function getStocktake( $idTake){
		
		$row = $this->fetchRow("`id_take` = ". $idTake);
		if(!$row) return false;
		return $row->toArray();		
		
		}
		
	public function addStocktake( $barcode ,$qtySys , $qtyAct , $dateTake , $timeUpload , $location , $staffName){
		
		$data = array(
			
         "barcode" =>  $barcode ,	
         "qty_sys" =>  $qtySys ,
         "qty_act" =>  $qtyAct ,
         "date_take" =>  $dateTake ,
         "time_upload" =>  $timeUpload ,
         "location" =>  $location ,
         "staff_name" =>  $staffName 
	
			);
		$this->insert($data);
		
		}
		
	public function updateStocktake(  $idTake ,  $barcode , $salesYest , $qtySys , $qtyAct , $dateTake , $timeUpload , $location , $staffName){
		$data = array(
			
         "barcode" =>  $barcode ,
		 "sales_yest" => $salesYest, 		
         "qty_sys" =>  $qtySys ,
         "qty_act" =>  $qtyAct ,
         "date_take" =>  $dateTake ,
         "time_upload" =>  $timeUpload ,
         "location" =>  $location ,
         "staff_name" =>  $staffName 
	
			);
			
		$this->update($data,"`id_take` = ". $idTake);
		}
		
	public function deleteStocktake( $idTake){
		
		$this->delete("`id_take` = ". $idTake);
		
		}

	public function updateStockSysQty($idTake,$qty){
		
		$data = array(
				"qty_sys" => $qty
				);
		$this->update($data,"`id_take` = ". $idTake);
	}		
		
	
	public function listAll(){
		
		$rows =$this->fetchAll("1","id_take DESC ");
		if(!$rows) return false;
		return $rows->toArray();			
		
		}	

	public function listStocktake($date,$location){
		
		$whereStr = "`date_take`= '".$date."' AND `location` LIKE '".$location."'";
		$rows = $this->fetchAll($whereStr,'id_take');
		return $rows->toArray();
		
	}	
	
	public function updateActStock($id,$qty,$staffName){
		$timeNow = Model_DatetimeHelper::timeNow();
		$data = array(
				'time_upload' => $timeNow,
				'qty_act' => $qty,
				'staff_name' => $staffName
				);
		
		$this->update($data, '`id_take` ='.$id);
	}
	
	public function listDiff($date,$location){
		$whereStr = "`date_take`= '".$date."' AND `location` LIKE '".$location."' AND `qty_sys` != `qty_act` AND `qty_act` IS NOT NULL"; 
		$rows = $this->fetchAll($whereStr,'id_take');
		return $rows->toArray();		
	}
	
	public function ifSubmitActual($date,$location){
		$whereStr = "`date_take`= '".$date."' AND `location` LIKE '".$location."' AND  `qty_act` IS NULL";
		$rows = $this->fetchAll($whereStr,'id_take')->toArray();
		if(empty($rows)){ return true;}
		return false;
	}
	/**
	 * delete the stock take list for that day that shop
	 * @param string $date
	 * @param string $location
	 */
	public function delStockTakeByShopByDate($date,$location){
		
		$whereStr = "`date_take` = '".$date."' AND `location` LIKE '".$location."'";
		$this->delete($whereStr); 
	}
	
}

?>