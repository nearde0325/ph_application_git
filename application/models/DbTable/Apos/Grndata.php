<?php

class Model_DbTable_Apos_Grndata extends Zend_Db_Table_Abstract {
	
	public function getGrnNo($sCode){
		
		$whereStr = "SCODE LIKE '".$sCode."'";
		$row = $this->fetchRow($whereStr,"GRN_NO ASC");
		if(!$row) return false;
		return $row["GRN_NO"];
	}
}

?>