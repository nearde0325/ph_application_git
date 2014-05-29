<?php
/*
 this is the class which send automatic email when:
Something Done:

Something Wrong:
1) If download failed, Email me


*/
class Model_DbTable_Companydocuments extends Zend_Db_Table_Abstract {
	
	protected $_name = 'company_docs';
	
	public function getDoc($id){
		$rows = $this->fetchRow("`id_docs` = ".$id);
		return $rows->toArray();
	}
	
	public function addDoc($idCate,$title,$attachmentName,$mustSee){
		
		$data = array(
			"id_cate" => $idCate,
			"title" => $title,
			"attach_1_name" => $attachmentName,
			"must_see" => $mustSee,
			"date_doc" => Model_DatetimeHelper::dateToday()							
			);
		$this->insert($data);
		
	}
	
	public function listDocByCategory($idCate){
		
		$rows = $this->fetchAll("`id_cate` =".$idCate,"id_docs desc");
		return $rows->toArray();
			
	}
	public function delDoc($id){
		
		$this->delete("`id_docs` =".$id);
	}
	
	public function listAllNeedSeeDocs($monthBegin = null){
		if(empty($monthBegin)){
			$monthBegin = 7;
		}
		$dateBegin = Model_DatetimeHelper::adjustMonths("sub",Model_DatetimeHelper::dateToday(),$monthBegin);
		$whereStr = "`must_see` = 1 AND `date_doc` >= {$dateBegin}";
		$rows = $this->fetchAll($whereStr,'date_doc DESC');
		return (empty($rows))?false:$rows->toArray();		
	}
	

	
}
?>