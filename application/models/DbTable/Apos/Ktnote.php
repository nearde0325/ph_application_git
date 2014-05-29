<?php

class Model_DbTable_Apos_Ktnote extends Zend_Db_Table_Abstract {
	
	public function getKTDate($ktNo){
		
		$whereStr ="TRAN_NO = '".$ktNo."'";
		$row = $this->fetchRow($whereStr,"TRAN_NO ASC");

		if(!$row) {return false;}
		
		return $row["DATE"];
	}
	
}

?>