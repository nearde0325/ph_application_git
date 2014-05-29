<?php

class Model_DbTable_Apos_Productdesall extends Zend_Db_Table_Abstract {
	

	public function getAposDes($sCode){
		
		$whereStr = "SCODE LIKE '".$sCode."'";
		$row = $this->fetchRow($whereStr);
		if(!$row) return false;
		return $row->toArray();
	}
	
	public function getScodeList($shortCode){
		$whereStr = "SCODE LIKE '".$shortCode."%'";
		$rows = $this->fetchAll($whereStr,'SCODE ASC')->toArray();
		if(empty($rows)) return false;
		return $rows;		 
	}
	
	public function getAll(){
		
		$rows = $this->fetchAll("SCODE IS NOT NULL");
		if(!$rows) return false;
		return $rows->toArray();
			
	}
	public function listNewProducts(){
		
		$whereStr = "DESCRIPT LIKE '**%'";
		$rows = $this->fetchAll($whereStr,"SCODE ASC");
		if(!$rows) return false;
		return $rows->toArray();		
	
	}
	public function removeStar($sCode){

		$row = self::getAposDes($sCode);
		$des = $row["DESCRIPT"];
		$des = str_replace("**","", $des);
		$des = $des."  ";
		
		$data = array(
			"DESCRIPT" => $des 	
				);
		$this->update($data,"SCODE LIKE '".$sCode."'");
		
	}
	public function isBranded($sCode){
		$row = self::getAposDes($sCode);
		if(!$row) return false;
		
		if(strpos($row["DESCRIPT"],"#B")!==false) return "yes";
		
		return "no";
		
		
	}
	
	public function matchBcode($bCode){
		
		$whereStr = "SCODE LIKE '".$bCode."' OR BCODE LIKE '".$bCode."' OR BCODE2 LIKE '".$bCode."' ";
		$row = $this->fetchRow($whereStr);
		if(!$row) return false;
		return $row->toArray();
	}
	
	
}

?>