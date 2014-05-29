<?php

class Model_DbTable_Apos_Ktdata extends Zend_Db_Table_Abstract {
	
	public function isBeenTransfer($sCode,$location){
		$whereStr = "SCODE LIKE '".$sCode."' AND TO_LOC LIKE '".$location."' AND ADJ_QTY >0";
		$row = $this->fetchRow($whereStr);
		if(!$row) return false;
		return $row->toArray();
		
	}
	public function listKT($ktNo,$location){
		
		$whereStr = "TRAN_NO LIKE '".$ktNo."' AND TO_LOC LIKE '".$location."'";
		$row = $this->fetchAll($whereStr);
		if(empty($row)) return false;
		return $row->toArray();
			
	}
	
	public function thisWeekTransfer($sCode){
		 
		$totalQty = 0;
		$thisMonday = Model_DatetimeHelper::getThisWeekMonday();
		$whereStr = "SCODE LIKE '".$sCode."' AND TRAN_NO IN ( SELECT TRAN_NO FROM [APOS1108].[dbo].[TRANSFER] WHERE DATE >= '".$thisMonday." 00:00:00' AND STATUS = ''  ) ";
		
		$rows = $this->fetchAll($whereStr);
		if(!$rows) return 0;
		foreach ($rows as $row){
			
			$totalQty +=$row['QTY'];
		}
		
		return $totalQty;
		
	}
}

?>