<?php
class Model_DbTable_Roster_Staffinfo extends Zend_Db_Table_Abstract {

	protected $_name = 'staff_info_detail';
	
	public function getStaffinfo( $idStaff){
		
		$row = $this->fetchRow("`id_staff` = ". $idStaff);
		if(!$row) return false;
		return $row->toArray();		
		
		}
		
	public function addStaffinfo( $idStaff,$firstName,$lastName, $gender,$idCard , $idRecord ,$idRecordCash ,$emailStaff , $mobileStaff, $addr,$suburb,$state,$postcode,$fileName,$activeStaff){
		
		$data = array(
		 "last_name" => $lastName,
		 "first_name" =>$firstName,			
		 "id_staff" => $idStaff,		
         "id_card" =>  $idCard ,
         "id_record" =>  $idRecord ,
		 "id_record_cash" => $idRecordCash,
         "email_staff" =>  $emailStaff,
         "active_staff" =>  $activeStaff,
		 "mobile_staff" => $mobileStaff,
		 "addr_staff" => $addr,
		 "suburb_staff" => $suburb,
		 "state_staff" => $state,
		 "postcode" => $postcode,
		 "gender" => $gender,
		 "register_date" =>Model_DatetimeHelper::dateToday()					
			);
		$this->insert($data);
		
		}
		public function updateBasicStaffinfo( $idStaff,$firstName,$lastName, $gender,$emailStaff , $mobileStaff, $addr,$suburb,$state,$postcode){
		
			$data = array(
				 "last_name" => $lastName,
				 "first_name" =>$firstName,
				 "email_staff" =>  $emailStaff,
				 "mobile_staff" => $mobileStaff,
				 "addr_staff" => $addr,
				 "suburb_staff" => $suburb,
				 "state_staff" => $state,
				 "postcode" => $postcode,
				 "gender" => $gender
			);
		$this->update($data,"`id_staff` = ". $idStaff);		
		
		}		
	public function verifyAndUpdateStaffinfo($idStaff,$firstName,$lastName,$idCard,$idRecord,$type){
		
		$data = array(
				"last_name" => $lastName,
				"first_name" =>$firstName,
				"id_card" =>  $idCard ,
				"id_record" =>  $idRecord ,
				"id_record_cash" => 0,
				"id_package" => $type,
				"active_staff" => 1		
		);		
		
		
		if($type == 2 || $type == 5){
		$data = array(
				"last_name" => $lastName,
				"first_name" =>$firstName,
				"id_card" =>  $idCard ,
				"id_record" =>  $idRecord ,
				"id_record_cash" => $idRecord,
				"id_package" => $type,
				"active_staff" => 1				
				);
		}

		$this->update($data,"`id_staff` = ". $idStaff);		
		
	}	
	public function updateStaffinfo(  $idStaff ,  $idCard , $idRecord , $emailStaff , $activeStaff){
		$data = array(
			
         "id_card" =>  $idCard ,
         "id_record" =>  $idRecord ,
         "email_staff" =>  $emailStaff ,
         "active_staff" =>  $activeStaff 
	
			);
			
		$this->update($data,"`id_staff` = ". $idStaff);
		}
		
	public function updateMyobInfor($idStaff,$idCard,$idRecord,$idRecordCash,$idPackage){
		
		$data = array(
					
				"id_card" =>  $idCard ,
				"id_record" =>  $idRecord ,
				"id_record_cash" =>  $idRecordCash,
				"id_package" =>  $idPackage
		);
			
		$this->update($data,"`id_staff` = ". $idStaff);		
		
		
	}	

	public function updatePersonalData($idStaff,$passport,$driverLicense,$visaType,$visaDate,$tfnNo,$bankBsb,$bankAccNo,$bankName,$cardNo,$medicareNo,$studentNo){
		$data = array(
			"no_passport" => Model_EncryptHelper::sslPassword(trim($passport)),	
			"driver_license" => Model_EncryptHelper::sslPassword(trim($driverLicense)),
			"visa_type" => Model_EncryptHelper::sslPassword(trim($visaType)),
			"visa_date" => Model_EncryptHelper::sslPassword(trim($visaDate)),
			"no_tfn" => Model_EncryptHelper::sslPassword(trim($tfnNo)),
			"bank_bsb" => Model_EncryptHelper::sslPassword(trim($bankBsb)),
			"bank_acc_no" => Model_EncryptHelper::sslPassword(trim($bankAccNo)),
			"bank_acc_name" => Model_EncryptHelper::sslPassword(trim($bankName)),
			"no_credit" => Model_EncryptHelper::sslPassword(trim($cardNo)),
			"no_medicare" => Model_EncryptHelper::sslPassword(trim($medicareNo)),
			"no_student" => Model_EncryptHelper::sslPassword(trim($studentNo))
				);
		$this->update($data,"`id_staff` = ". $idStaff);
	}	

	public function updateStaffType($idStaff,$type){
		
		
		$data = array( "id_package" => $type);
		$this->update($data,"`id_staff` = ". $idStaff);
	
	}	
	
	public function updatePaymentInfo($idStaff,$oldRate,$oldDate,$newRate,$newDate){
		$data = array(
			"old_rate" => $oldRate,
			"old_rate_date" => $oldDate,
			"new_rate" => $newRate,
			"new_rate_date" => $newDate			
			);
		$this->update($data,"`id_staff` = ". $idStaff);	
	}
	
	public function updateLeave($idStaff,$isLeave,$dateLeave){
		$data = array(
			"leave" => $isLeave,
			"leave_date" => $dateLeave		
				);
		$this->update($data,"`id_staff` = ". $idStaff);		
	}
	
	public function deleteStaffinfo( $idStaff){
		
		$this->delete("`id_staff` = ". $idStaff);
		
		}	
	
	public function listAll(){
		
		$rows =$this->fetchAll("1","id_staff DESC ");
		if(!$rows) return false;
		return $rows->toArray();			
		
		}	
		
	public function checkEmailExist($email){
		
		$row = $this->fetchRow("`email_staff` LIKE '". $email ."'");
		if(!$row) return false;
		return true;
		
		
	}	
	public function disActiveStaff($idStaff){
		$data = array(
				"active_staff" => 0,
		);
		$this->update($data,"`id_staff` = ". $idStaff);		
	}

	
}

?>