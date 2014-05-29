<?php
/*
 this is the class which send automatic email when:
Something Done:

Something Wrong:
1) If download failed, Email me


*/
class Model_DbTable_Pr_Counter extends Zend_Db_Table_Abstract {
	
	protected $_name = 'pr_products_counter';
	
	
	public function getCounter($field){
		
		$row = $this->fetchRow("`field_counter` LIKE '".$field."'");
		if(!$row){
			return false;
		
		}
		return $row->toArray();			
		}
		
	
		
	public function updateCounter($id,$count){
		
		$data = array(
				"no_counter" =>$count
				);
		$this->update($data,"`id_counter` =".$id);
		
	}
	
	public function runcounter($field){
		
		$row = self::getCounter($field);
		
		$id = $row['id_counter'];
		$count = $row['no_counter'];
		$count++;
		self::updateCounter($id, $count);
		
		return $count;
				
	}

		
}
?>