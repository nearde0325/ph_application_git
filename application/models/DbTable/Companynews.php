<?php
/*
 this is the class which send automatic email when:
Something Done:

Something Wrong:
1) If download failed, Email me


*/
class Model_DbTable_Companynews extends Zend_Db_Table_Abstract {
	
	protected $_name = 'company_news';
	
	public function getNews($id){
		
		$rows = $this->fetchRow("`id` = ".$id);
		return $rows->toArray();		
	}
	public function addNews($newsType,$title,$content,$fileName1,$fileName2,$fileName3,$staffName,$dept,$idNewsCate = NULL,$mustSee){
		$dateToday = Model_DatetimeHelper::dateToday();
		$timeNow = Model_DatetimeHelper::timeNow();
		$data = array(
		"id_news_type" =>$newsType,
		"date_news" => $dateToday,
		"time_news" => $timeNow,
		"active" => 0,
		"title_news" => $title,
		"content_news" => $content,
		"attach_1_name" => $fileName1,
		"attach_2_name" => $fileName2,
		"attach_3_name" => $fileName3,
		"staff_name" =>$staffName,
		"department" => $dept,
		"id_news_cate" => $idNewsCate,
		"must_see" => $mustSee																		
		);
		$this->insert($data);		
	}
	public function deleteNews($id){
		$this->delete('`id` ='.$id);
	}
	public function activeNews($id){
		$data = array(
			"active" => 1	
			);
		$this->update($data,'`id` ='.$id);
	}
	
	public function listNewsByType($idNewsType){
		
		$rows = $this->fetchAll("`active` = 1 AND `id_news_type` = ".$idNewsType,"date_news desc");
		if($rows){
			return $rows->toArray();
		}
		return false;
	} 
	
	public function listUnActivedByType($idNewsType){
		
		$rows = $this->fetchAll("`active` = 0 AND `id_news_type` = ".$idNewsType,"id desc");
		if($rows){
			return $rows->toArray();
		}
		return false;		
		
	}
	public function listAllNeedSeeNews($monthBegin = null){
		
		if(empty($monthBegin)){$monthBegin = 7;}
		//All Must Read News within 8 Monthes
		$dateBegin = Model_DatetimeHelper::adjustMonths("sub",Model_DatetimeHelper::dateToday(),$monthBegin);
		$whereStr = "`must_see` = 1 AND `date_news` >= {$dateBegin}";
		$rows = $this->fetchAll($whereStr,'date_news DESC');
		return (empty($rows))?false:$rows->toArray();
	}
	
}
?>