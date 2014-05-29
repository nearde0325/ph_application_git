<?php
//namespace application\models\DbTable\Products\Stock;
/**
 * 
 * @author Norman
 * @name Model_DbTable_Products_Stock_Transferadjuststatus
 * 
 */
class Model_DbTable_Products_Stock_Transferadjuststatus extends Zend_Db_Table_Abstract {
	/**
	 * 
	 * @var string $_name Db table Name
	 * @var const int Default Status Value 1
	 */
	
	
	protected $_name = 'kakt_status';
	const DEFAULT_STATUS = 1;
	const ACTIVED_STATUS = 99;
	/**
	 * Get The Status by ID
	 * @param int $id
	 * @return array
	 */		
	public function getStatus($id){
		$row = $this->fetchRow("`id` = ".$id);
		if(!$row) return false;
		return $row->toArray();
	}
	/**
	 * 
	 * @param string $number The KA KT Number
	 * @return array
	 */
	public function getStatusByNumber($number){
		$row = $this->fetchRow("`note_number` LIKE '".$number."'");
		if(!$row) return false;
		return $row->toArray();
		
	}
	/**
	 * Simple insert function add a new status
	 * @param string $kNumber  The Ka Kt Number
	 * @param string $staffName  Staff Name who add in 
	 * @param string $shopHead  The Ka is for shop
	 * @return void 
	 */
	public function addStatus($kNumber,$staffName,$shopHead){
		$dateToday = Model_DatetimeHelper::dateToday();
		$data = array(
				"note_number" => $kNumber,
				"date_create" => $dateToday,
				"last_status" => self::DEFAULT_STATUS,
				"create_by"   => $staffName,
				"shop_head"   => $shopHead
				);
		$this->insert($data);
		$id = $this->getAdapter()->lastInsertId();	
		
		return $id;
	}
	/**
	 * Active the Ka Kt Note , if everything ok then active it finally
	 * @param int $id
	 * @param string $dateActive  date of active
	 * @param string $staffName  who active it
	 * @return void 
	 */
	public function activeTheTransfer($id,$dateActive,$staffName){
		$data = array(
				"date_active" => $dateActive,
				"active_by"   => $staffName,
				"last_status" => 99
				);
		$this->update($data,"`id` =".$id);			
	}
	/**
	 * 
	 * @param int $id
	 * @param date $dateCancel
	 * @param string $staffName
	 */
	public function cancelTheTransfer($id){
		
		$data = array(
				//"date_active" => $dateActive,
				//"active_by"   => $staffName,
				"last_status" => 100
		);
		$this->update($data,"`id` =".$id);	
	
	}
	public function updateStatus($id,$statusCode){
		
		$data = array(
				"last_status" => $statusCode,
		);		
		$this->update($data,"`id` =".$id);		
	}
	/**
	 * Do not think it is necessary at the moment 
	 * @param int $id
	 */

	public function deleteStatus($id){
		$this->delete("`id` =".$id);
		
	}
	/**
	 *  Update the delivery time by ID
	 * @param unknown_type $id
	 */
	public function updateDelivery($id){
		
		$data = array(
				"date_delivery" =>Model_DatetimeHelper::dateToday(),
				"time_delivery" =>Model_DatetimeHelper::timeNow()
				);
		$this->update($data,"`id`=".$id);
	
	}
	/**
	 * 
	 * @param string $shopHead
	 * @param int $statusCode
	 */
	public function countlistByShopHead($shopHead,$statusCode){
		$select = $this->_db->select()
		->from($this->_name,array('count(`id`)'))
		->where('shop_head LIKE ? ',$shopHead)
		->where('last_status = ?',$statusCode );
		//->where('1');
		$result = $this->getAdapter()->fetchAll($select);
		return $result;
		
	}
	/**
	 * List by Status Code 
	 */
	public function listByStatusCode($statusCode){
		
		$rows = $this->fetchAll("`last_status` =".$statusCode);
		if(!$rows) return false;
		return $rows->toArray();
	
	}
	/**
	 * 
	 * @param string $shopHead
	 * @param int $statusCode
	 */
	public function listByShopHead($shopHead,$statusCode){
		
		$strWhere = "`shop_head` LIKE '".$shopHead."' AND `last_status` = ".$statusCode;
		$rows = $this->fetchAll($strWhere,"date_create DESC");

		if(!$rows) return false;
		return $rows->toArray();
	}
	
	/**
	 *  Fall all Ka Kt for single shop from dateBegin to date End In Active + Active , no canceled
	 * @param string $shopHead
	 * @param string $dateBegin "2000-01-01"
	 * @param string $dateEnd
	 */
	public function listByShopHeadByDate($shopHead,$dateBegin,$dateEnd){
		
		//$strShop = (isset($shopHead))? " AND `shop_head` LIKE '".$shopHead."'":" ";
		$strWhere = " `date_create` >= '".$dateBegin."' AND `date_create` <= '".$dateEnd."' AND `shop_head` LIKE '".$shopHead."'  AND `last_status` <= 99 ";	
		
		$rows = $this->fetchAll($strWhere,"id DESC");
		if(!$rows) return false;
		return $rows->toArray();
	
	}
	/**
	 * Will looking for all InActived Notes , but for Actived only looking for datebegin to date end (two weeks) generally
	 * @param string $shopHead
	 * @param string $dateBegin
	 * @param string $dateEnd
	 */
	public function listByShopHeadByDateForWh($shopHead,$dateBegin,$dateEnd){
		
		$strWhere = "((`date_create` >= '".$dateBegin."' AND `date_create` <= '".$dateEnd."' AND `last_status` = 99 ) OR ( `last_status` < 99 ) ) AND `shop_head` LIKE '".$shopHead."' ";
		$rows = $this->fetchAll($strWhere,"id DESC");
		if(!$rows) return false;
		return $rows->toArray();		
	}
	
	
	/**
	 * This is the all in one search function , all other List function are based on this one
	 * @param string $noteHeader Start with KA Or KT
	 * @param bool $active  if already actived 
	 * @param string $dateBegin string type date begin "2013-01-01"
	 * @param string $dateEnd string type date End
	 * @param string $shopHead 4 C shopHead "HPPC"
	 */
	public function listKaKt($noteHeader,$active = false,$dateBegin,$dateEnd,$shopHead = null){
		
		$strActive =($active) ? " AND `last_status` = 99 ":" AND `last_status` < 99 ";
		$strShop = (isset($shopHead))? " AND `shop_head` LIKE '".$shopHead."'":" ";
		$strWhere = "`note_number` LIKE '".$noteHeader."%' ".$strActive." AND `date_create` >= '".$dateBegin."' AND `date_create` <= '".$dateEnd."' ".$strShop;  		
		$rows = $this->fetchAll($strWhere,"id DESC");
		if(!$rows) return false;
		return $rows->toArray(); 	
	}
	
	/**
	 * This function use the list KAKT function to search 
	 * 8 functions are made accordingly
	 * All Active KA KT
	 * All InActive KA KT
	 * Individual Shop Active KA KT
	 * Individual Shop InActive KA KT
	 * @param string $dateBegin
	 * @param string $dateEnd
	 */
	
	public function listAllInActivedKa($dateBegin,$dateEnd){
		
		$rows = self::listKaKt("KA",false, $dateBegin, $dateEnd);
		if(!$rows) return false;
		return $rows->toArray();		
		
	}
	

	public function listAllInActivedKt($dateBegin,$dateEnd){
		
		$rows = self::listKaKt("KT",false, $dateBegin, $dateEnd);
		if(!$rows) return false;
		return $rows->toArray();		
		
	}
	public function listAllActivedKa($dateBegin,$dateEnd){
		$rows = self::listKaKt("KA",true, $dateBegin, $dateEnd);
		if(!$rows) return false;
		return $rows->toArray();		
		
	}
	public function listAllActivedKt($dateBegin,$dateEnd){
		$rows = self::listKaKt("KT",true, $dateBegin, $dateEnd);
		if(!$rows) return false;
		return $rows->toArray();
		
	}
	
	public function listInActivedKaByShopByDate($shopHead,$dateBegin,$dateEnd){

		$rows = self::listKaKt("KA",false, $dateBegin, $dateEnd,$shopHead);
		if(!$rows) return false;
		return $rows->toArray();
		
	}
	public function listInActivedKtByShopByDate($shopHead,$dateBegin,$dateEnd){
		
		$rows = self::listKaKt("KT",false, $dateBegin, $dateEnd,$shopHead);
		if(!$rows) return false;
		return $rows->toArray();
		
	}
	public function listActivedKaByShopByDate($shopHead,$dateBegin,$dateEnd){
		
		$rows = self::listKaKt("KA",true, $dateBegin, $dateEnd,$shopHead);
		if(!$rows) return false;
		return $rows->toArray();
		
	}
	public function listActivedKtByShopByDate($shopHead,$dateBegin,$dateEnd){
		
		$rows = self::listKaKt("KT",true, $dateBegin, $dateEnd,$shopHead);
		if(!$rows) return false;
		return $rows->toArray();
	}
}

?>