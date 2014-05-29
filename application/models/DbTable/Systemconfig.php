<?php
/**
 * 
 * @author Norman
 * System configuration Class Mainly for access the system configure
 *
 */
class Model_DbTable_Systemconfig extends Zend_Db_Table_Abstract {
	
	protected $_name = 'system_config';
	protected static $tName = 'system_config'; 
	/**
	 * 
	 * @param string $key
	 * @uses DEFAULT_LIMITS = 1
	 * 		 DEFAULT_LIMITS_2 = 2
	 */
	public function gV($key){
		
		$strWhere = "`key_cfg` LIKE '".$key."'";
		$row = $this->fetchRow($strWhere);
		if(!$row) return false;
		$result = $row->toArray();
		return $row["value"];
		
	}
	public function runOrderCounter(){
		
		date_default_timezone_set('Australia/Melbourne');
		
		$result = self::gV("order_counter");
		$result ++;
		$this->update(array("value"=>$result),"`key_cfg` LIKE 'order_counter'");
		
		$preFix = "OD";
		$number=date("ymd")*10000;
		$number = $number + $result;
		return $preFix.$number;
				
	}
	public function upV($key,$value){
		
		$strWhere = "`key_cfg` LIKE '".$key."'";
		
		$data = array(
			'value' => $value 	
				);
				
		$this->update($data, $strWhere);		
		
	}

	
}
?>