<?php 
/**
 *
 */
 class Model_DbTable_Faultyreason extends Zend_Db_Table_Abstract 
{
	/**
	 * @var string Class Table Name
	 */
	protected $_name = 'faulty_reason'; 
	
	public function getReasonByID($idReason){

		$rows = $this->fetchRow("`id_faulty_reason` = ".$idReason);
		return $rows->toArray();		
		
	}
	public function getReasonDesByID($idReason){
		
		$resultRow = $this->fetchRow("`id_faulty_reason` = ".$idReason);
		$reasonDes = $resultRow['description_faulty_reason'];		
		return $reasonDes;		
	}

				
}
?>