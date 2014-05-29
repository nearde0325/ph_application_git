<?php
class Model_DbTable_Roster_Attcaserecord extends Zend_Db_Table_Abstract {

	protected $_name = 'att_case_status_record';
	
	public function getAttcaserecord( $idRecord){
		
		$row = $this->fetchRow("`id_record` = ". $idRecord);
		if(!$row) return false;
		return $row->toArray();		
		
		}
		
	public function addAttcaserecord( $idCase , $idStatus , $dateChange , $timeChange , $staffName , $comment = null  , $linkImgUpload = null){
		
		$data = array(
			
         "id_case" =>  $idCase ,
         "id_status" =>  $idStatus ,
         "date_change" =>  $dateChange ,
         "time_change" =>  $timeChange ,
         "staff_name" =>  $staffName ,
         "comment" =>  $comment ,
         "link_img_upload" =>  $linkImgUpload 
	
			);
		$this->insert($data);
		
		}
		
	public function updateAttcaserecord(  $idRecord ,  $idCase , $idStatus , $dateChange , $timeChange , $staffName , $comment , $linkImgUpload){
		$data = array(
			
         "id_case" =>  $idCase ,
         "id_status" =>  $idStatus ,
         "date_change" =>  $dateChange ,
         "time_change" =>  $timeChange ,
         "staff_name" =>  $staffName ,
         "comment" =>  $comment ,
         "link_img_upload" =>  $linkImgUpload 
			);
			
		$this->update($data,"`id_record` = ". $idRecord);
		}
		
	public function deleteAttcaserecord( $idRecord){
		
		$this->delete("`id_record` = ". $idRecord);
		
		}	
	
	public function listAll(){
		
		$rows =$this->fetchAll("1","id_record DESC ");
		if(!$rows) return false;
		return $rows->toArray();			
		
		}
	public function getCaseHistory($idCase){
		
		$whereStr = "`id_case` =".$idCase;
		$rows = $this->fetchAll($whereStr,"date_change ASC");
		if(empty($rows)) return false;
		return $rows->toArray();
	}
	public function lastStatus($idCase){
		
		$whereStr = "`id_case` =".$idCase;
		$rows = $this->fetchRow($whereStr,array("date_change DESC","time_change DESC"));
		if(empty($rows)) return false;
		return $rows->toArray();		
	}
	public function rejectComment($idCase){
		
		$whereStr = "`id_case` =".$idCase." AND (`id_status` = 90 OR `id_status` = 92)";
		$rows = $this->fetchRow($whereStr,"date_change DESC");
		if(empty($rows)) return false;
		
		$row = $rows->toArray();
		return $row["comment"];		
	}		
}
?>