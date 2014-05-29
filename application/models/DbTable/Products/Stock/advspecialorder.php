<?php

class Model_DbTable_Products_Stock_Advspecialorder extends Zend_Db_Table_Abstract {

	protected $_name = 'products_v1_stock_special_order_v2';
	
	public function getAdvspecialorder( $idSpecialOrder){
		
		$row = $this->fetchRow("`id_special_order` = ". $idSpecialOrder);
		if(!$row) return false;
		return $row->toArray();		
		
		}
		
	public function addAdvspecialorder( $codeProduct , $qtyOrder , $reasonOrder , $shopOrder , $staffOrder , $dateOrder){
		
		$data = array(
			
         "code_product" =>  $codeProduct ,
         "qty_order" =>  $qtyOrder ,
         "reason_order" =>  $reasonOrder ,
         "shop_order" =>  $shopOrder ,
         "staff_order" =>  $staffOrder ,
         "date_order" =>  $dateOrder ,
			);
		$this->insert($data);
		
		}
		
	public function updateAdvspecialorder(  $idSpecialOrder ,  $codeProduct , $qtyOrder , $reasonOrder , $shopOrder , $staffOrder , $dateOrder , $auditManager , $dateAuditManager){
		$data = array(
			
         "code_product" =>  $codeProduct ,
         "qty_order" =>  $qtyOrder ,
         "reason_order" =>  $reasonOrder ,
         "shop_order" =>  $shopOrder ,
         "staff_order" =>  $staffOrder ,
         "date_order" =>  $dateOrder ,
         "audit_manager" =>  $auditManager ,
         "date_audit_manager" =>  $dateAuditManager 
	
			);
			
		$this->update($data,"`id_special_order` = ". $idSpecialOrder);
		}
  	
  	public function managerAuditOrder(  $idSpecialOrder ,  $codeProduct , $qtyOrder , $reasonOrder , $shopOrder , $staffOrder , $dateOrder , $auditManager , $dateAuditManager){
			$data = array(
						
					"code_product" =>  $codeProduct ,
					"qty_order" =>  $qtyOrder ,
					"reason_order" =>  $reasonOrder ,
					"shop_order" =>  $shopOrder ,
					"staff_order" =>  $staffOrder ,
					"date_order" =>  $dateOrder ,
					"audit_manager" =>  $auditManager ,
					"date_audit_manager" =>  $dateAuditManager
		
			);
				
			$this->update($data,"`id_special_order` = ". $idSpecialOrder);
		}		
		
	public function deleteAdvspecialorder( $idSpecialOrder){
		
		$this->delete("`id_special_order` = ". $idSpecialOrder);
		
		}	
	
	public function listAll(){
		
		$rows =$this->fetchAll("1","id_special_order DESC ");
		if(!$rows) return false;
		return $rows->toArray();			
		
		}	
	public function getOrderByShopByDate($date,$shopCode){
		
		$whereStr = "`shop_order` LIKE '{$shopCode}' AND `date_order` = '{$date}' ";
		
		$rows = $this->fetchAll($whereStr,"id_special_order");
		if(empty($rows)){
			
			return false;
		}
		
		return $rows->toArray();
	}	
}


?>