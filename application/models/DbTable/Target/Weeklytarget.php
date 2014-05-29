<?php
class Model_DbTable_Target_Weeklytarget extends Zend_Db_Table_Abstract {

	protected $_name = 'shop_weekly_target';
	
	public function getWeeklytarget( $idTarget){
		
		$row = $this->fetchRow("`id_target` = ". $idTarget);
		if(!$row) return false;
		return $row->toArray();		
		
		}
	public function getWeeklytargetByShop( $shopCode){
		
			$row = $this->fetchRow("`shop_code` LIKE '". $shopCode."'", "id_target ASC");
			if(!$row) return false;
			return $row->toArray();
		
		}
	public function getWeeklyTargetByShopByDate($shopCode,$dateToCheck){

		$row = $this->fetchRow("`shop_code` LIKE '". $shopCode."' AND `date_begin` <= '".$dateToCheck."' AND `date_end` >= '".$dateToCheck."' ", "id_target ASC");
		if(!$row) return false;
		return $row->toArray();		
	}			
		
	public function addWeeklytarget( $shopCode , $dateBegin , $dateEnd , $target){
		
		$data = array(
			
         "shop_code" =>  $shopCode ,
         "date_begin" =>  $dateBegin ,
         "date_end" =>  $dateEnd ,
         "target" =>  $target 
	
			);
		$this->insert($data);
		
		}
		
	public function updateWeeklytarget(  $idTarget ,  $shopCode , $dateBegin , $dateEnd , $target){
		$data = array(
			
         "shop_code" =>  $shopCode ,
         "date_begin" =>  $dateBegin ,
         "date_end" =>  $dateEnd ,
         "target" =>  $target 
	
			);
			
		$this->update($data,"`id_target` = ". $idTarget);
		}
		
	public function deleteWeeklytarget( $idTarget){
		
		$this->delete("`id_target` = ". $idTarget);
		
		}	
	
	public function listAll(){
		
		$rows =$this->fetchAll("1","shop_code");
		if(!$rows) return false;
		return $rows->toArray();			
		
		}	
}

?>