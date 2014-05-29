<?php
/**
 * Phone Repair Product Borrow Record Class
 * 
 * @author Norman
 *
 */
class Model_DbTable_Pr_Loan extends Zend_Db_Table_Abstract {
	/**
	 * 
	 * @var string $_name table Name
	 */
	protected $_name = 'pr_products_loan';
	
	/**
	 * Get Borrow Row By Self increase ID
	 * @param int $id
	 */
	public function getLoan( $id){
		
		$row = $this->fetchRow("`id` = ". $id);
		if(!$row) return false;
		return $row->toArray();		
		
		}
	/**
	 * Insert New Borrow Record
	 * @param string $bCode Borrow Code
	 * @param big int $idJob
	 * @param big int $idStockmove Parts Movement Record ID
	 * @param int $shopFrom
	 * @param int $shopTo
	 * @param big int $idProduct
	 * @param string $codeProduct
	 * @param date $dateLoan
	 * @param time $timeRecord
	 * @param int $idStaff
	 */	
	public function addLoan( $bCode , $idJob ,$idStockmove, $shopFrom , $shopTo , $idProduct , $codeProduct , $dateLoan , $timeRecord , $idStaff,$qtyProduct){
		
		$data = array(
			
         "b_code" =>  $bCode ,
		 "id_job" => $idJob,
		 "id_stockmove" => $idStockmove,				
         "shop_from" =>  $shopFrom ,
         "shop_to" =>  $shopTo ,
         "id_product" =>  $idProduct ,
         "code_product" =>  $codeProduct ,
         "date_loan" =>  $dateLoan ,
         "time_record" =>  $timeRecord ,
         "id_staff" =>  $idStaff,
		 "qty_product" => $qtyProduct 
			);
		$this->insert($data);
		
		return $this->getAdapter()->lastInsertId();
		}
	/**
	 * Useless function for copy
	 * @param unknown_type $id
	 * @param unknown_type $bCode
	 * @param unknown_type $idJob
	 * @param unknown_type $idStockmove
	 * @param unknown_type $shopFrom
	 * @param unknown_type $shopTo
	 * @param unknown_type $idProduct
	 * @param unknown_type $codeProduct
	 * @param unknown_type $dateLoan
	 * @param unknown_type $timeRecord
	 * @param unknown_type $confirmed
	 * @param unknown_type $timeConfirm
	 * @param unknown_type $idStaff
	 */	
	public function updateLoan(  $id ,  $bCode , $idJob ,$idStockmove, $shopFrom , $shopTo , $idProduct , $codeProduct , $dateLoan , $timeRecord , $confirmed , $timeConfirm , $idStaff){
		$data = array(
			
         "b_code" =>  $bCode ,
		 "id_job" => $idJob,
		 "id_stockmove" => $idStockmove,
         "shop_from" =>  $shopFrom ,
         "shop_to" =>  $shopTo ,
         "id_product" =>  $idProduct ,
         "code_product" =>  $codeProduct ,
         "date_loan" =>  $dateLoan ,
         "time_record" =>  $timeRecord ,
         "confirmed" =>  $confirmed ,
         "time_confirm" =>  $timeConfirm ,
         "id_staff" =>  $idStaff 
	
			);
			
		$this->update($data,"`id` = ". $id);
		}
	/**
	 * Confirm the Borrow Status , before confirm , only dudect the stock of Shop To
	 * if confirmed , should also - stock from Shop From and + stock In Shop To , so the shop To
	 * stock will be and should be 0 qty
	 * @todo When warehouse confirm , adjust the stock
	 * @param unknown_type $id
	 */
	public function confirmBorrow ($id){
		
		$data = array(
				"confirmed" =>  1 ,
				"time_confirm" => Model_DatetimeHelper::dateToday()." ".Model_DatetimeHelper::timeNow() ,
		);		
		$this->update($data,"`id` = ". $id);
	}
	/**
	 * Remove Borrow Record, should not use
	 * @param unknown_type $id
	 */	
		
	public function deleteLoan( $id){
		
		$this->delete("`id` = ". $id);
		
		}	
	/**
	 * List all borrow Record
	 * No Use
	 */
	public function listAll(){
		
		$rows =$this->fetchAll("1","id DESC ");
		if(!$rows) return false;
		return $rows->toArray();			
		
		}	
	/**
	 * List All Rows that confirm is 0 for certain Shop
	 * @param unknown_type $shopTo
	 */	
	public function listAllUnconfirmedLoan($shopTo){
		
		$whereStr = "`shop_to` = {$shopTo} AND `confirmed` = 0";
		
		$rows =$this->fetchAll($whereStr,"id ASC ");
		if(!$rows) return false;
		return $rows->toArray();		
		
	}	
	/**
	 * Another way around , check all Not confirmed borrow by shop From
	 * @param unknown_type $shopFrom
	 */
	public function listAllUnconfirmedLend($shopFrom){
		
		$whereStr = "`shop_from` = {$shopFrom} AND `confirmed` = 0";
		$rows =$this->fetchAll($whereStr,"id ASC ");
		if(!$rows) return false;
		return $rows->toArray();
		
	}
	/**
	 * Static function to Build Borrow Code
	 * Borrow code are Now 8 lengh Sting and Number Combine
	 * First 2 Shop From Shop To
	 * Another 2 for Products ID
	 * Another 3 for short version of JOB ID
	 * Another 1 for Random After fix 
	 * 
	 * @param unknown_type $shopFrom
	 * @param unknown_type $shopTo
	 * @param unknown_type $idProduct
	 * @param unknown_type $idJob
	 * 
	 * @return string of Borrow Code
	 */
	public static function buildBorrowCode($shopFrom,$shopTo,$idProduct,$idJob){
		date_default_timezone_set("Australia/Melbourne");
		$round = mt_rand(0,35);
		$shortId = $idJob - floor($idJob/100000)*100000;
		
		//echo "SID:".$shortId;
		
		$sF = Model_EncryptHelper::convertTo36($shopFrom);
		$sT = Model_EncryptHelper::convertTo36($shopTo);
		$id = Model_EncryptHelper::convertTo36($idProduct);
		$sid = Model_EncryptHelper::convertTo36($shortId);
		$afterFix = Model_EncryptHelper::convertTo36($round);  
		
		
		return  $sF.$sT.$id.$sid.$afterFix;
	}
	/**
	 * Get Row by Borrow Code ,borrow code is unique
	 * @param string $bCode
	 * @return array Borrow Line or false if nothing found
	 */
	public function getByBorrowCode($bCode){
		
		$row = $this->fetchRow("`b_code` = '". $bCode."'");
		if(!$row) return false;
		return $row->toArray();
		
	}
	/**
	 * Repair Stock Movement ID is also Unique
	 * for each movement record , there should be only 1 Type Product
	 * @param big $idStMove
	 * @return Borrow Row
	 */
	public function getByStMoveCode($idStMove){
		
		$row = $this->fetchRow("`id_stockmove` = ". $idStMove);
		if(!$row) return false;
		return $row->toArray();		
	}
	/**
	 * Get All Borrow Code 
	 * @param int $idJob
	 */
	public function getByJobId($idJob){
		
		$whereStr = "`id_job` = {$idJob}";
		$rows =$this->fetchAll($whereStr,"id ASC ");
		if(!$rows) return false;
		return $rows->toArray();
				
	}
	/**
	 * Confirm Borrow Code
	 * @param string $bCode 
	 */
	public function confirmBorrowByCode ($bCode,$idStaffConfirm){

		$data = array(
				"confirmed" =>  1 ,
				"time_confirm" => Model_DatetimeHelper::dateToday()." ".Model_DatetimeHelper::timeNow(), 
				"id_staff_confirm" => $idStaffConfirm
		);
		$this->update($data,"`b_code` LIKE '". $bCode."' ");
		
		
	}
	
	public function unconfirmedExist($idShop){
		
		$whereStr = "`confirmed` = 0 AND (`shop_from` = {$idShop} OR `shop_to` = {$idShop})";

		$row = $this->fetchRow($whereStr);
		if(!$row) return false;
		return true;
	}
	public function unconfirmedExistList($idShop){
	
		$whereStr = "`confirmed` = 0 AND (`shop_from` = {$idShop} OR `shop_to` = {$idShop})";
	
		$rows = $this->fetchAll($whereStr);
		$rows = $rows->toArray();
		if(empty($rows)) return false;
		return $rows;
	}	
	
	public function borrowedStock($idProduct,$idShop){
		
		$totalCount = 0;
		$whereStr = "`confirmed` = 0 AND `shop_to` = {$idShop} AND `id_product` = {$idProduct} ";
		
		$rows = $this->fetchAll($whereStr);
		if(!empty($rows)){
			foreach($rows as $row){
				$totalCount += $row['qty_product'];
			}
			
		}
		return $totalCount;
	}
	
	public function lendStock($idProduct,$idShop){

		$totalCount = 0;
		$whereStr = "`confirmed` = 0 AND `shop_from` = {$idShop} AND `id_product` = {$idProduct} ";
		
		$rows = $this->fetchAll($whereStr);
		if(!empty($rows)){
			foreach($rows as $row){
				$totalCount += $row['qty_product'];
			}
				
		}
		return 0 - $totalCount;
		
		
	}
	
}

?>