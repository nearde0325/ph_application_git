<?php 
/**
 *
 */
 class Model_DbTable_Shoplocation extends Zend_Db_Table_Abstract 
{
	/**
	 * @var string Class Table Name
	 */
	protected $_name = 'shop_location_head'; 
	
	
	public function getLocationByID($idShopLocation){
	//head + Name of the Shop 	
	
	$rows = $this->fetchRow("`id_shop_location_head` = ".$idShopLocation);
	return $rows->toArray();
	
	}
	
	public function getNameByHead($locationHead){
		
		$rows = $this->fetchRow("`name_shop_location_head` LIKE '".$locationHead."'");
		if(isset($rows)){
			return $rows->toArray();		
		}
		else{
		return false;	
			}
	}
	public function listName(){

		$select = $this->_db->select()
		->from($this->_name,array('name_of_shop'))
		//->where('id_cusomter_label_profile = ? ',$defaultCustomerId)
		->where('1');
		$result = $this->getAdapter()->fetchAll($select);
		return $result;
		
	}
	/**
	 * List 4 Charactor shop head Only
	 * @return array FetchAll Result
	 * @example $row[0]['name_shop_location_head'] 
	 */
	public function listHead(){

		$select = $this->_db->select()
		->from($this->_name,array('name_shop_location_head'))
		//->where('id_cusomter_label_profile = ? ',$defaultCustomerId)
		->where('1');
		$result = $this->getAdapter()->fetchAll($select);
		return $result;
		
	}
	public function listHeadArray(){
		
		$res = self::listHead();
		$arrRes = array();
		foreach($res as $shop){
			//disabled 	
			if($shop['name_shop_location_head'] != "COPC" && $shop['name_shop_location_head'] != "FGPC" && $shop['name_shop_location_head'] != "HPPC" && $shop['name_shop_location_head'] != "ONLI" ){
			
				$arrRes[] = $shop['name_shop_location_head']; 
			
			}	
		}
		
		return $arrRes;
			
	}
	
	public function listShop(){
		
		$rows = $this->fetchAll("1","id_shop_location_head");
		if(!$rows) return false;
		return $rows->toArray();
		
	}
	
	public function setPassword($id,$password){
		
		
		$data = array(
				"password_shop" => self::sslPassword($password)
				);
		
		$this->update($data,"`id_shop_location_head` =".$id);
		
		
		
	}
	public function setToken($id){
		
		$row = self::getLocationByID($id);
		echo $pass = $row['password_shop'];
		echo "<br />";
		$token = hash("md5",$pass);
		echo $token;
		echo "<br />";
		
		$data = array(
				"token_shop" => $token
				);
		
		$this->update($data,"`id_shop_location_head` =".$id);
		
		
	}
	public function getToken($shopHead){
		
		$row = $this->fetchRow("`name_shop_location_head` LIKE '".$shopHead."'");
		if(!$row) return false;
		
		$token = $row->toArray();
		
		return $token["token_shop"];
		
	}
	public function getShopHeadByToken($token){
		
		$row = $this->fetchRow("`token_shop` LIKE '".$token."'");
		if(!$row) return false;
		
		$token = $row->toArray();
		
		return $token["name_shop_location_head"];		
		
		
	}
	public function retrievePassword($shopHead,$token){
		
		$row = self::getNameByHead($shopHead);
		
		if($token == $row['token_shop']){
			
			return self::getSslPassword($row['password_shop']);
			
		}
		
		return false;
		
		
	} 
	public function sslPassword($password){
		
		$pwdSource = $password;
		$iv="thisisnormancode";
		$pass="197979norman";
		$method = 'aes-128-cbc';
		if(strlen($pwdSource)>0){
			
			return openssl_encrypt($pwdSource, $method, $pass,false,$iv);
		}
		return false;
		
	}
	
	public function getSslPassword($data){
		
		$iv="thisisnormancode";
		$pass="197979norman";
		$method = 'aes-128-cbc';		
		if(strlen($data)>0){
			return $pwdResult = openssl_decrypt($data, $method, $pass,false,$iv);
			}
		return false;
			
		}
	public function getShopMailByHead($shopHead){
		$row = self::getNameByHead($shopHead);
		if(!$row) return false;
		return $row["shop_email"];
	}
	public function getStoreManMailByHead($shopHead){
		$row = self::getNameByHead($shopHead);
		if(!$row) return false;
		return $row["shop_manager_email"];		
	}	
	public function getAreaManMailByHead($shopHead){
		$row = self::getNameByHead($shopHead);
		if(!$row) return false;
		return $row["area_manager_email"];		
	}
	public function getShopLimits($shopHead){
		$row = self::getNameByHead($shopHead);
		if(!$row) return false;
		//var_dump($row);
		return $row["hour_limit"];
		
	}
	public function listAll(){
		
		$rows =$this->fetchAll("1","id_shop_location_head ASC ");
		if(!$rows) return false;
		return $rows->toArray();
	}
	public function listAllManagerIds(){
		
		$arrMgr = array();
		$arrShops = self::listAll();
		
		foreach($arrShops as $shop){
			
			$idMgr = $shop["id_shop_mgr"];
			$idAreaMgr = $shop["id_area_mgr"];
			
			if($idMgr!=""){$arrMgr[] = $idMgr;}
			if($idAreaMgr!=""){
				$arrMgr[] = $idAreaMgr;
			}
		}
		
		return array_unique(arrMgr);
		
	}
	
	public function getShopByManagerId($idManager){
		$whereStr = "`id_shop_mgr` = {$idManager} OR `id_area_mgr` = {$idManager} ";
		$rows =$this->fetchAll($whereStr,"id_shop_location_head ASC ");
		if(empty($rows)) return false;
		$arrShop = array();
		foreach($rows as $shop){
			$arrShop[] = array($shop["name_shop_location_head"],$shop["id_shop_mgr"],$shop["id_area_mgr"]);	
		}
		
		return $arrShop;
	}
	
	public function updateHourLimits($shopHead,$hourLimit){
		
		$data = array(
				"hour_limit" => $hourLimit
				); 
		$this->update($data,"`name_shop_location_head` LIKE '".$shopHead."'");		
	}
}
?>