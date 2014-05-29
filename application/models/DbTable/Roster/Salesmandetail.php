<?php
class Model_DbTable_Roster_Salesmandetail extends Zend_Db_Table_Abstract {

	protected $_name = 'staff_salesman_detail';
	
	public function getSalesmandetail( $id){
		
		$row = $this->fetchRow("`id` = ". $id);
		if(!$row) return false;
		return $row->toArray();		
		
		}
		
	public function addSalesmandetail( $shopCode , $salCode , $idStaff){
		
		$data = array(
			
         "shop_code" =>  $shopCode ,
         "sal_code" =>  $salCode ,
         "id_staff" =>  $idStaff 
	
			);
		$this->insert($data);
		
		}
		
	public function updateSalesmandetail(  $id ,  $shopCode , $salCode , $idStaff){
		$data = array(
			
         "shop_code" =>  $shopCode ,
         "sal_code" =>  $salCode ,
         "id_staff" =>  $idStaff 
	
			);
			
		$this->update($data,"`id` = ". $id);
		}
		
	public function updateSalesId($id,$idStaff){
		
		$data = array(
				"id_staff" =>  $idStaff
		);
			
		$this->update($data,"`id` = ". $id);		
	}	
		
	public function deleteSalesmandetail( $id){
		
		$this->delete("`id` = ". $id);
		
		}	
	
	public function listAll(){
		
		$rows =$this->fetchAll("1","id DESC ");
		if(!$rows) return false;
		return $rows->toArray();			
		
		}
	public function getSalesMan($salCode,$shopCode){
		
		$whereStr = "`sal_code` = '".$salCode."' AND `shop_code` = '".$shopCode."'";
		$row = $this->fetchRow($whereStr);
		if(!$row) return false;
		return $row->toArray();		
			
	}	

	public function unmatchSalesMan(){
		
		$whereStr = "`sal_code` != 'STAFF' AND `id_staff` = 0";
		$rows = $this->fetchAll($whereStr,"sal_code")->toArray();
		if(empty($rows)) return false;
		return $rows;
	}
}

?>