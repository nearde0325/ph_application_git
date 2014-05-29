<?php

class Model_DbTable_Apos_Mirror_Aposktmirror extends Zend_Db_Table_Abstract {

	protected $_name = 'apos_kt';
	
	public function getAposktmirror( $idKt){
		
		$row = $this->fetchRow("`id_kt` = ". $idKt);
		if(!$row) return false;
		return $row->toArray();		
		
		}
		
	public function addAposktmirror( $noKt , $codeProduct , $desProduct , $qty , $dateSend,$qtyOnhand,$shop){
		
		$data = array(
			
         "no_kt" =>  $noKt ,
         "code_product" =>  $codeProduct ,
         "des_product" =>  $desProduct ,
         "qty" =>  $qty ,
		 "qty_onhand" => $qtyOnhand,
         "date_send" =>  $dateSend, 
		 "shop_code" => $shop		
			);
		$this->insert($data);
		
		}
		
	public function updateAposktmirror(  $idKt ,  $noKt , $codeProduct , $desProduct , $qty , $qtyReceived , $dateSend , $dateReceived , $staff,$shop){
		$data = array(
			
         "no_kt" =>  $noKt ,
         "code_product" =>  $codeProduct ,
         "des_product" =>  $desProduct ,
         "qty" =>  $qty ,
         "qty_received" =>  $qtyReceived ,
         "date_send" =>  $dateSend ,
         "date_received" =>  $dateReceived ,
         "staff" =>  $staff, 
		 "shop_code" => $shop
			);
			
		$this->update($data,"`id_kt` = ". $idKt);
		}
		
	public function deleteAposktmirror( $idKt){
		
		$this->delete("`id_kt` = ". $idKt);
		
		}

	public function deleteKtmirrorByNo($ktNo,$shop){
		
		$this->delete("`no_kt` LIKE '{$ktNo}' AND `shop_code` LIKE '{$shop}'" );
	}	
	
	public function listAll(){
		
		$rows =$this->fetchAll("1","id_kt DESC ");
		if(!$rows) return false;
		return $rows->toArray();			
		
		}
	public function listKtByNo($noKt,$shop){
		$rows =$this->fetchAll("`no_kt` LIKE '{$noKt}' AND `shop_code` LIKE '{$shop}'","code_product ASC ");
		if(empty($rows)) return false;
		return $rows->toArray();
		
	}
	public function updateReceivedQty($noKt,$codeProduct,$qtyReceived,$shop){
		$data = array(
				"qty_received" =>  $qtyReceived
		);	

		$this->update($data,"`no_kt` LIKE  '".$noKt."' AND `code_product` LIKE '".$codeProduct."'  AND `shop_code` LIKE '{$shop}'  ");
	}	
	public function addUnExpectedItem( $noKt , $codeProduct , $desProduct,$qtyReceived,$shop){
		
		
		$data = array(
					
				"no_kt" =>  $noKt ,
				"code_product" =>  trim($codeProduct) ,
				"des_product" =>  $desProduct ,
				"qty" =>  0 ,
				"qty_onhand" => null,
				"date_send" =>  Model_DatetimeHelper::dateToday(),
				"qty_received" => $qtyReceived,
				"shop_code" => $shop
		);
		$this->insert($data);		
		
	}
	
	public function deleteUnExpectedItem($noKt,$codeProdut,$shop){
		
		$whereStr = "`no_kt` LIKE '".trim($noKt)."' AND `code_product` LIKE '".trim($codeProdut)."' AND `shop_code` LIKE '{$shop}' ";
		
		$this->delete($whereStr);
	}
	
}



?>