<?php
//namespace application\models\DbTable\Products\Stock;

	class Model_DbTable_Products_Stock_Stocktakeweekly extends Zend_Db_Table_Abstract {
	
		protected $_name = 'products_v1_stock_take_weekly';
		protected $_adaptor = 'db_real';
		protected $_primary = 'id_take';
		
	
		public function getStocktakeweekly( $idTake){
	
			$row = $this->fetchRow("`id_take` = ". $idTake);
			if(!$row) return false;
			return $row->toArray();
	
		}
	
		public function addStocktakeweekly( $barcode , $salesWeek , $qtySys , $dateTake , $location){
	
			$data = array(
						
					"barcode" =>  $barcode ,
					"sales_week" =>  $salesWeek ,
					"qty_sys" =>  $qtySys ,
					"date_take" =>  $dateTake ,
					"location" =>  $location
	
			);
			$this->insert($data);
	
		}
	
		public function updateStocktakeweekly(  $idTake ,  $barcode , $salesWeek , $qtySys , $dateTake , $location){
			$data = array(
						
					"barcode" =>  $barcode ,
					"sales_week" =>  $salesWeek ,
					"qty_sys" =>  $qtySys ,
					"date_take" =>  $dateTake ,
					"location" =>  $location
	
			);
				
			$this->update($data,"`id_take` = ". $idTake);
		}
	
		public function deleteStocktakeweekly( $idTake){
	
			$this->delete("`id_take` = ". $idTake);
	
		}
	
		public function listAll(){
	
			$rows =$this->fetchAll("1","id_take DESC ");
			if(!$rows) return false;
			return $rows->toArray();
	
		}
	}
?>