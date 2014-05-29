<?php
/*
 this is the class which send automatic email when:
Something Done:

Something Wrong:
1) If download failed, Email me


*/
class Model_DbTable_Jobidcounter extends Zend_Db_Table_Abstract {
	
	protected $_name = 'repair_jobidcounter';
	
	public function getCounter(){
		
		$row = $this->fetchRow('`id`= 1');
		if(!$row){
			return false;
		}
		return $row->toArray();
	}
	public function updateCounter($counts){
		
		$data = array(
				'counter' => $counts
				);
							
		$this->update($data, 'id = 1');
		
	}
	public function runCounter(){
		$currentCounts = self::getCounter();
		$counts = (int)$currentCounts['counter'];
		$newCount = $counts + 1;
		self::updateCounter($newCount);
		return $newCount;
	}
	public function resetCounter(){
		$newCount = 0;
		return self::updateCounter($newCount);
	}

	
}
?>