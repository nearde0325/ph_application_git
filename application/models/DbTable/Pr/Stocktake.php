<?php
class Model_DbTable_Pr_Stocktake extends Zend_Db_Table_Abstract {

	protected $_name = 'pr_products_stock_take';
	
public function getStocktake( $idTake){
		
		$row = $this->fetchRow("`id_take` = ". $idTake);
		if(!$row) return false;
		return $row->toArray();		
		
		}
		
	public function addStocktake( $idProduct , $codeProduct , $idShop , $dateTake , $timeUpload , $staff , $salesQty , $faultyQty , $qtySys , $qtyAct){
		
		$data = array(
			
         "id_product" =>  $idProduct ,
         "code_product" =>  $codeProduct ,
         "id_shop" =>  $idShop ,
         "date_take" =>  $dateTake ,
         "time_upload" =>  $timeUpload ,
         "staff" =>  $staff ,
         "sales_qty" =>  $salesQty ,
         "faulty_qty" =>  $faultyQty ,
         "qty_sys" =>  $qtySys ,
         "qty_act" =>  $qtyAct 
	
			);
		$this->insert($data);
		
		}
		
	public function updateStocktake(  $idTake ,  $idProduct , $codeProduct , $idShop , $dateTake , $timeUpload , $staff , $salesQty , $faultyQty , $qtySys , $qtyAct){
		$data = array(
			
         "id_product" =>  $idProduct ,
         "code_product" =>  $codeProduct ,
         "id_shop" =>  $idShop ,
         "date_take" =>  $dateTake ,
         "time_upload" =>  $timeUpload ,
         "staff" =>  $staff ,
         "sales_qty" =>  $salesQty ,
         "faulty_qty" =>  $faultyQty ,
         "qty_sys" =>  $qtySys ,
         "qty_act" =>  $qtyAct 
	
			);
			
		$this->update($data,"`id_take` = ". $idTake);
		}
	public function updateStocktakeRes($idTake,$staffName,$qtyAct){
		
		$data = array(
				"staff" =>  $staffName ,
				"time_upload" =>  Model_DatetimeHelper::timeNow() ,
				"qty_act" =>  $qtyAct
		
		);		
		
		$this->update($data,"`id_take` = ". $idTake);
	}
		
	public function deleteStocktake( $idTake){
		
		$this->delete("`id_take` = ". $idTake);
		
		}	
	
	public function listAll(){
		
		$rows =$this->fetchAll("1","id_take DESC ");
		if(!$rows) return false;
		return $rows->toArray();			
		
		}
	
	public function listByShopByDate($dateTake,$idShop){
		
		$whereStr = "`id_shop` = ".$idShop." AND `date_take` = '".$dateTake."'";
		
		$rows =$this->fetchAll($whereStr,"id_take DESC ");
		if(!$rows) return false;
		return $rows->toArray();
		
		
	}	
}
?>