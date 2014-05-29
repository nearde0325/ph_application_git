<?php
/*



*/
class Model_DbTable_Jobcomments extends Zend_Db_Table_Abstract {
	
	protected $_name = 'repair_job_comments';
	
	public function addComment($idJob,$staffName,$content){
	
	date_default_timezone_set('Australia/Melbourne');	
	$dateToday = date("Y-m-d");
	$timeNow = date(" H:i");
	
	$data = array(
		'id_job_repair_comt' => $idJob,
		'staffname_repair_comt' => $staffName,
		'date_repair_comt' => $dateToday,
		'time_repair_comt' => $timeNow,
		'content_repair_comt' => $content		
	);
		$this->insert($data);
		
	}
	public function listCommentByJobID($idJob){
		$rows = $this->fetchAll('`id_job_repair_comt` = '.$idJob,'id_repair_comt');
		return $rows->toArray();	
	}		
}
?>