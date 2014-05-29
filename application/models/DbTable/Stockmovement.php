<?php
class Model_DbTable_Stockmovement extends Zend_Db_Table_Abstract {

	protected $_name = 'products_v1_stockmove';

	public function addMovement($codeMove,$idProduct,$qty){
		
		$data = array(
				"code_move" => $codeMove,
				"date_move" => self::cDateToday(),
				"time_move" => self::cTimeNow(),
				"id_product"=> $idProduct,
				"qty_product" => $qty
				);
		$this->insert($data);
		
	}
	public function addMovementDate($codeMove,$idProduct,$qty,$dateSelect){
		$data = array(
				"code_move" => $codeMove,
				"date_move" => $dateSelect,
				"time_move" => self::cTimeNow(),
				"id_product"=> $idProduct,
				"qty_product" => $qty
		);
		$this->insert($data);		
	}
	
	public function cancelMovement($idMovement){
		
		$this->delete('`id_move` =' . $idMovement);
	}
	public function getLastMovement(){
		
	}
	public function getWarehouseInToday(){
		
		$db = $this->getAdapter();
		
		$dateToday=self::cDateToday();
		
		$sqlstr="SELECT * FROM `products_v1_stockmove` WHERE `date_move` = '".$dateToday."' and `code_move` = 3 ORDER BY `time_move`";
		
		$stmt = $db->prepare($sqlstr);
		$stmt->execute();
		$result = $stmt->fetchAll();
		return $result;
	}
	public function getWarehouseInByDate($date){
		$db = $this->getAdapter();
		
		//$dateToday=self::cDateToday();
		
		$sqlstr="SELECT * FROM `products_v1_stockmove` WHERE `date_move` = '".$date."' and `code_move` = 3 ORDER BY `time_move`";
		
		$stmt = $db->prepare($sqlstr);
		$stmt->execute();
		$result = $stmt->fetchAll();
		return $result;		
	}
	
	private function cDateToday(){
	
		date_default_timezone_set('Australia/Melbourne');
	
		return $dateToday = date("Y-m-d");
	
	}
	private function cTimeNow(){
		
		date_default_timezone_set('Australia/Melbourne');
		
		return $timeNow = date("H:i:s");
	}

	}
?>
