<?php
class Model_DbTable_Suppliers extends Zend_Db_Table_Abstract {

	protected $_name = 'supplier_v0';

	public function getSupplier($idSupplier){
		$row = $this->fetchRow('id_supplier ='.$idSupplier);
		if(!$row){
			return false;
		}
		else{
			
			return $row->toArray();
		}
		
	}
	public function getSupplierShortName($idSupplier){
		$rowArr = self::getSupplier($idSupplier);
		
		return $rowArr['short_title'];
		
	}
	
	public function addSupplier($supplierName,$supplierShortName,$location){
		$data = array(
			"title_supplier" =>$supplierName,
			"short_title" => $supplierShortName,
			"location_supplier" => $location				
		);
		
		$this->insert($data);
		
	}
	public function updateSupplier(){
		
		
	}
	public function updateContactsSupplier($idSupplier,$address1,$address2,$cname1,$cphone1,$cemail1,$cqq1,$cname2,$cphone2,$cemail2,$cqq2){
		$data = array(
			"address1_supplier" => $address1,
			"address2_supplier" => $address2,
			
			"contact1_name" => $cname1,
			"contact1_phone" => $cphone1,
			"contact1_email" => $cemail1,
			"contact1_qq" => $cqq1,
				
			"contact2_name" => $cname2,
			"contact2_phone" => $cphone2,
			"contact2_email" => $cemail2,
			"contact2_qq" => $cqq2					
			);
		
		$this->update($data,"`id_supplier`= ".$idSupplier);
	}
	
	public function deleteSupplier(){
	//	I don't think Any one want to delete suppliers
	}
	public function deactivateSupplier($idSupplier){
		$data = array(
			"active_supplier" => 0	
				);
		$this->update($data,"`id_supplier`= ".$idSupplier);	
		
	} 
	
	public function listAllSuppliers(){
		
		$rows = $this->fetchAll('1');
		return $rows->toArray();
		
	}
	public function listLocalSuppliers(){
		$rows = $this->fetchAll("`location_supplier` LIKE 'AUSTRALIA'");
		return $rows->toArray();
	}
	
	
	}
?>
