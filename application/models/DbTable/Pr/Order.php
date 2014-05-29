<?php
class Model_DbTable_Pr_Order extends Zend_Db_Table_Abstract {

	protected $_name = 'pr_order';

	public function getOrder( $idOrder){

		$row = $this->fetchRow("`id_order` = ". $idOrder);
		if(!$row) return false;
		return $row->toArray();

	}

	public function addOrder( $partOrderNumber , $idShop , $dateOrder , $idProduct , $codeProduct , $qtyLastWeek , $qtyFoLastWeek , $qtyMaxWeek , $qtyOnhand , $qtyWh , $qtyOrder = 0 , $qtyAllocate = 0 , $isSend = 0 ){

		$data = array(
					
				"part_order_number" =>  $partOrderNumber ,
				"id_shop" =>  $idShop ,
				"date_order" =>  $dateOrder ,
				"id_product" =>  $idProduct ,
				"code_product" =>  $codeProduct ,
				"qty_last_week" =>  $qtyLastWeek ,
				"qty_fo_last_week" =>  $qtyFoLastWeek ,
				"qty_max_week" =>  $qtyMaxWeek ,
				"qty_onhand" =>  $qtyOnhand ,
				"qty_wh" =>  $qtyWh ,
				"qty_order" =>  $qtyOrder ,
				"qty_allocate" =>  $qtyAllocate ,
				"is_send" =>  $isSend
		);
		$this->insert($data);
		
		return $this->getAdapter()->lastInsertId();
	}

	public function updateOrder(  $idOrder ,  $partOrderNumber , $idShop , $staffName , $dateOrder , $idProduct , $codeProduct , $qtyLastWeek , $qtyFoLastWeek , $qtyMaxWeek , $qtyOnhand , $qtyWh , $qtyOrder , $qtyAllocate , $isSend){
		$data = array(
					
				"part_order_number" =>  $partOrderNumber ,
				"id_shop" =>  $idShop ,
				"staff_name" =>  $staffName ,
				"date_order" =>  $dateOrder ,
				"id_product" =>  $idProduct ,
				"code_product" =>  $codeProduct ,
				"qty_last_week" =>  $qtyLastWeek ,
				"qty_fo_last_week" =>  $qtyFoLastWeek ,
				"qty_max_week" =>  $qtyMaxWeek ,
				"qty_onhand" =>  $qtyOnhand ,
				"qty_wh" =>  $qtyWh ,
				"qty_order" =>  $qtyOrder ,
				"qty_allocate" =>  $qtyAllocate ,
				"is_send" =>  $isSend

		);
			
		$this->update($data,"`id_order` = ". $idOrder);
	}

	public function deleteOrder( $idOrder){

		$this->delete("`id_order` = ". $idOrder);

	}

	public function listAll(){

		$rows =$this->fetchAll("1","id_order DESC ");
		if(!$rows) return false;
		return $rows->toArray();

	}
	
	public function getOrderByShopByDate($idShop,$dateOrder){
		
		$whereStr = "`id_shop` = {$idShop} AND `date_order` = '{$dateOrder}'";

		$rows =$this->fetchAll($whereStr,"code_product ASC ");
		if(empty($rows)) return false;
		return $rows->toArray();		
		
	}
	public function stockTakeDone($idShop,$dateOrder){
		
		$idDone = true;
		
		$stId = null;
		
		$oList = self::getOrderByShopByDate($idShop, $dateOrder);
		foreach($oList as $key => $v){
			if($v['staff_name_stocktake']!= null){
				$stId = $v['staff_name_stocktake'];
			}
			if($v['qty_onhand_cf']== null){
				$idDone = false;
			}
			
		}
		
		return array($idDone,$stId);
		
	}
	public function updateOrderQty($idOrder,$qtyOrder,$reason,$confirm = null){

		$data = array(
					
				"qty_order_actual" =>  $qtyOrder ,
				"reason" => $reason,
				"is_confirm" => $confirm
		
		);
		$this->update($data,"`id_order` = ". $idOrder);
	}
	public static function orderQty($lastWeek,$maxSold,$faulty,$onHand,$wh){
		
		$faulty = ($faulty > 0 )?$faulty:0;
		$onHand = ($onHand > 0 )?$onHand:0;
		$lastWeek = ($lastWeek > 0)?$lastWeek:0;
		$maxSold = ($maxSold > 0)?$maxSold:0;
		
		$alertParam = 1.5;
		
		if($wh <= 0){
			return 0;
		}
		else{
			
			if(($onHand >= ceil($lastWeek * $alertParam)) && ($onHand >= $maxSold) ){
				
				return 0;
			}
			else{

				return ceil($lastWeek * $alertParam) - $onHand;
				
			}
		}
		
	}
	public function confirmOrder($orderPartNumber){
		$data = array(
				"is_confirm" => 1
		);
		$this->update($data,"`part_order_number` LIKE '".$orderPartNumber."'");
	}
	public function isOrderConfirmed($orderPartNumber){
		
		$whereStr = "`part_order_number` LIKE '".$orderPartNumber."'";
		$row = $this->fetchRow($whereStr);
		
		if(!$row) return false;
		return $row['is_confirm'];
		
	}
	
	public function getByPON($partOrderNumber){

		$rows =$this->fetchAll("`part_order_number` LIKE '".trim($partOrderNumber)."'",array("qty_order DESC","code_product ASC"));
		if(empty($rows)) return false;
		return $rows->toArray();
	}
	public function getByPONConfirmed($pon){
		
		$rows =$this->fetchAll("`part_order_number` LIKE '".trim($pon)."' AND `is_confirm` = 1",array("qty_order DESC","code_product ASC"));
		if(empty($rows)) return false;
		return $rows->toArray();		
		
	}
	public function getByPONSent($pon){
	
		$rows =$this->fetchAll("`part_order_number` LIKE '".trim($pon)."' AND `is_send` = 1",array("qty_order DESC","code_product ASC"));
		if(empty($rows)) return false;
		return $rows->toArray();
	
	}	
	public function isInOrder($partOrderNumber,$idProduct){

		$rows =$this->fetchRow("`part_order_number` LIKE '".trim($partOrderNumber)."' AND `id_product` = {$idProduct}");
		if(!$rows) return false;
		return true;
		
	}
	
	public function updateAllocateByPON($partOrderNumber,$idProduct,$qtyAllo){
		$data = array(
				"qty_allocate" => $qtyAllo,
		);
		$this->update($data,"`part_order_number` LIKE '".$partOrderNumber."' AND `id_product` = {$idProduct} ");
	}
	public function updateStaffName($partOrderNumber,$staffName){
		
		$data = array(
				"staff_name" => $staffName,
		);		
		$this->update($data,"`part_order_number` LIKE '".$partOrderNumber."'");			
	
		$rows = self::getByPON($partOrderNumber);
		foreach($rows as $key =>$v){
			if($v['staff_name_stocktake']==""){
				self::updateStockTakeStaff($v['id_order'], $staffName);	
			}
		}
	
	}
	public function OrderSent($partOrderNumber){
		
		$data = array(
				"is_send" => 1
		);
		$this->update($data,"`part_order_number` LIKE '".$partOrderNumber."'");		
		
	}
	public function qtyOnOrder($codeProduct){
		
		$date = Model_DatetimeHelper::getThisWeekMonday();
		
		$whereStr = " `code_product` LIKE '".trim($codeProduct)."' AND `date_order` = '".$date."' AND `is_send` = 0";
		$rows = $this->fetchAll($whereStr);
		if(empty($rows)) return 0;
		
		$totalQty = 0;
		foreach($rows as $key => $v){
			
			$totalQty +=$v['qty_allocate'];
		}
		
		return $totalQty;
	}
	
	public function updateStockTake($idOrder,$confirmStock,$staffnameStock,$comment = null){
		$data = array(
			"qty_onhand_cf" => $confirmStock,
			"staff_name_stocktake" => $staffnameStock,	
			"comment" => $comment		
				);
		
		$this->update($data,"`id_order` = ". $idOrder);
	}
	public function updateStockTakeStaff($idOrder,$staffnameStock){
		$data = array(
				"staff_name_stocktake" => $staffnameStock
		);
		
		$this->update($data,"`id_order` = ". $idOrder);	
	}
	
	public function listAllDiffByDate($dateOrder){
		
		$whereStr = "`qty_onhand` != `qty_onhand_cf` AND `date_order` = '{$dateOrder}'";
		
		$rows =$this->fetchAll($whereStr,"id_shop ASC ");
		if(empty($rows)) return false;
		return $rows->toArray();		
	}
	
	public function listDiffByDateByShop($dateOrder,$idShop){
		
		$whereStr = "`qty_onhand` != `qty_onhand_cf` AND `date_order` = '{$dateOrder}' AND `id_shop` = {$idShop}";
		
		$rows =$this->fetchAll($whereStr,"id_shop ASC ");
		if(empty($rows)) return false;
		return $rows->toArray();		
	}
	
	public function stocktakeFinished($dateOrder,$idShop){
		
		$whereStr = "`qty_onhand_cf` IS NULL AND `date_order` = '{$dateOrder}' AND `id_shop` = {$idShop}";

		$rows =$this->fetchAll($whereStr,"id_order ASC ")->toArray();
		if(empty($rows)) return true;
		return false;
	}
	
	public function stocktakeExist($dateOrder,$idShop){
		
		$whereStr = "`date_order` = '{$dateOrder}' AND `id_shop` = {$idShop}";
		
		$rows =$this->fetchAll($whereStr,"id_order ASC ")->toArray();
		if(empty($rows)) return false;
		return true;	
	}
	
	
	public function clearOrder($dateOrder,$idShop){
		
		$whereStr = "`date_order` = '{$dateOrder}' AND `id_shop` = {$idShop}";
		$this->delete($whereStr);
	}
	
}
?>