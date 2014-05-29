<?php

class Model_DbTable_Apos_Grnnote extends Zend_Db_Table_Abstract {
	
	public function getGrnDate($GrnNo){
		
		$whereStr ="GRN_NO = '".$GrnNo."'";
		$row = $this->fetchRow($whereStr,"GRN_NO ASC");

		if(!$row) {return false;}
		
		return $row["DATE"];
	}
	
}

?>