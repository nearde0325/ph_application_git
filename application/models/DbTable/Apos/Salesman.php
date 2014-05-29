<?php

/** 
 * Super Class to access Sales man list from APOSs
 * @author Norman
 * 
 * 
 */

class Model_DbTable_Apos_Salesman extends Zend_Db_Table_Abstract  {
	
	public function getAllSalesmanShop(){
		
		$rows = $this->fetchAll("1=1","SAL_CODE ASC");
		
		if(!$rows) return false;
		return $rows->toArray();
	}

}

?>