<?php
class Model_DbTable_Roster_Stafflogindetail extends Zend_Db_Table_Abstract {
	
	protected $_name = 'staff_detail';
	
	public function getDetail($id){
		$row = $this->fetchRow("`id` =".$id);
		if(!$row) return FALSE;
		return $row->toArray();
	}
	
	public function addDetail($name,$dob,$initLastName,$password,$lastLogin,$tokenLastLogin,$ip,$active,$nickName = null){
		
		$data = array(
				"n" => $name,
				"d" => $dob,
				"il"=> $initLastName,
				"p" => $password,
				"l" => $lastLogin,
				"t" => $tokenLastLogin,
				"i" => $ip,
				"a" => $active,
				"ni" => $nickName
				);
		$this->insert($data);
		
		return $this->getAdapter()->lastInsertId();
	}
	
	public function adminUpdate($idStaff,$name,$dob,$nickName){
		
		$data = array(
				"n" => $name,
				"d" => $dob,
				"ni" => $nickName
		);		
		
		$this->update($data,"`id` = ".$idStaff);		
	}
	
	
	public function updatePassword($id,$password){
		
		$data = array(
				"p" => $password
				);
		$this->update($data,"`id` =".$id);		
	}
	
	public function checkUnique($name,$dob){
		
		$row = $this->fetchRow("`n` LIKE '".$name."' and `d` LIKE '".$dob."'");
		if(!$row) return true;
		return false;
	}
	
	public function checkNameandDob($name,$dob){
		$row = $this->fetchRow("`n` LIKE '".$name."' and `d` LIKE '".$dob."'");
		if(!$row) return false;
		return $row["id"];		
	}	
	public function getStaffByName($name){
		$rows = $this->fetchAll("`n` LIKE '".$name."'");
		if(!$rows) return false;
		return $rows->toArray();
	}				
	/**
	 * 
	 * @param string $password
	 * @return array staff row data
	 */	
	
	public function checkPasswordCorrect($password){
		
		$row = $this->fetchRow("`p` LIKE '".$password."'");
		if(!$row) return false;
		return $row->toArray();		
		
	}
	public function checkPdUnique($password){
		
		$row = $this->fetchRow("`p` LIKE '".$password."'");
		if(!$row) return true;
		return false;		
	}
	
	public function formatName($str){
		
		$str = str_replace(" ", "", $str);
		$str = strtolower($str);
		$str = trim($str);
		return $str;		
	}
	public function formateDob($year,$month,$day){
		
		//date_default_timezone_set('Australia/Melbourne');
		
		return $intTime = strtotime(trim($year)."-".trim($month)."-".trim($day));
				
	}
	public function listStaff(){
		$rows = $this->fetchAll("1","id DESC");
		if(!$rows) return false;
		return $rows->toArray();		
	}
	public function listStaffByLastName($initalLastName,$active = null){
		$sqlstr = "`il` = ".$initalLastName." AND `a`!=9";
		
		$rows = $this->fetchAll($sqlstr);
		
		if(!$rows) return false;
		return $rows->toArray();		
		
	}
	public function listStaffByNickName(){
		
		$rows = $this->fetchAll("1","ni ASC");
		if(!$rows) return false;
		
		return $rows->toArray();		
		
	}
	public function matchMyobStaff(){
		$select = $this->_db->select();
		$select->from(array($this->_name),array('staff_detail.id'))
			   ->joinLeft('staff_info_detail','staff_detail.id = staff_info_detail.id_staff',array())
			   //->where('staff_detail.a <9')
			   ->where('staff_info_detail.active_staff = -1');
		$result = $this->getAdapter()->fetchAll($select);
		
		return $result;
	}
	public function updateNickName($id, $nickName){
		$data = array(
				"ni" =>$nickName
				);
		$this->update($data,"`id` =".$id);	
		
	}
	public function updateExpireDate($date,$id){

		$data = array(
				"expire_date" =>$date
		);
		$this->update($data,"`id` =".$id);		
	}
	
	public function shouldExpire($date){
		
		$whereStr = "`expire_date` = NULL || `expire_date` < '{$date}'";
		$rows = $this->fetchAll($whereStr)->toArray();
		
		if(empty($rows)) return false;
		
		return $rows;
	}
	
	public function noScanParts($idStaff){
	
		$data = array(
				"scan_parts" => 2 ,
		);
			
		$this->update($data,"`id` = ". $idStaff);
	}
	public function approvalScanParts($idStaff){
	
		$data = array(
				"scan_parts" => 1,
		);
		$this->update($data,"`id` = ". $idStaff);
	
	}
	public function disActive($idStaff){
		$data = array(
				"a" => 9
				);
		$this->update($data,"`id` = ". $idStaff);
		self::updateExpireDate(Model_DatetimeHelper::dateYesterday(), $idStaff);
	}

}

?>