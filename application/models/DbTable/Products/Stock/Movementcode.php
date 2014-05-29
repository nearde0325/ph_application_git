<?php
class Model_DbTable_Products_Stock_Movementcode extends Zend_Db_Table_Abstract {
	/**
	 * 
	 * @var protected string $_name  Name of the Db Table
	 */
	protected $_name = 'kakt_status_code';

	/**
	 * Get Single Line of Code
	 * @param int $code
	 */
	public function getCodeByID($code){
		
		$row = $this->fetchRow("`code` =".$code);
	
		if(!$row) return false;
		return $row->toArray();
	}
	/**
	 * Get Explain of the Code
	 * @param int $code
	 */

	public function getDesByID($code){
		
		$row = self::getCodeByID($code);
		if(!$row) return false;
		return $row["explain"];		
	}

	}
?>
