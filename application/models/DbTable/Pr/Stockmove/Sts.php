<?php
class Model_DbTable_Pr_Stockmove_Sts extends Zend_Db_Table_Abstract {
	
	protected $_name = 'pr_stockmove_sts';
	protected $_primary  = 'id_job';
	protected $_sequence = false;
	
	public function listAll(){
		
		$rows =$this->fetchAll("1","date_move DESC ");
		if(!$rows) return false;
		return $rows->toArray();			
		
		}
	public function listAllDistinct(){
		
		$select = $this->_db->select();
		$select->distinct()
			->from($this->_name,"id_job")
			->order("date_move DESC");
		$stmt = $this->_db->query($select);
		$result = $stmt->fetchAll();	   
		return $result;
	}	
}

?>