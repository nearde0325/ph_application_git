<?php
class Model_DbTable_Pr_Prstockmovecode extends Zend_Db_Table_Abstract {

	protected $_name = 'pr_stock_move_code';

	public function getCodeByID($idMove){
		$row = $this->fetchRow("`id_move_code` =".$idMove);
	
		if(!$row) return false;
		return $row->toArray();
	}	

	}
?>
