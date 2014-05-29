<?php

/**
 * Deposite Detail Class 
 * For each time of dposite of the manager , there may be several days of Bank in
 * This is using for record detail Bank in for each day
 * @author Norman
 * ADD a bug fix today on 050314
 * Another one just a test
 *
 */

class Model_Account_Deposite_Detail extends Zend_Db_Table_Abstract {
	/**
	 * 
	 * @var string $_name Table Name
	 */
	protected $_name = 'cashaccount_deposit_detail';
	/**
	 * Get Row of Detail
	 * @param unknown_type $idDetail
	 * @return boolean
	 */
	public function getDetail( $idDetail){
		
		$row = $this->fetchRow("`id_detail` = ". $idDetail);
		if(!$row) return false;
		return $row->toArray();		
		
		}
	/**
	 * Insert New Line
	 * @param unknown_type $idRecord
	 * @param unknown_type $shopCode
	 * @param unknown_type $dateBankin
	 * @param unknown_type $amtBankin
	 * @param unknown_type $actAmtBankin
	 * @param unknown_type $amtMatch
	 */	
	public function addDetail( $idRecord , $shopCode , $dateBankin , $amtBankin){
		
		$data = array(
			
         "id_record" =>  $idRecord ,
         "shop_code" =>  $shopCode ,
         "date_bankin" =>  $dateBankin ,
         "amt_bankin" =>  $amtBankin 
			);
		$this->insert($data);
		
		}
	/**
	 * Useless, only here for Copy
	 * @param unknown_type $idDetail
	 * @param unknown_type $idRecord
	 * @param unknown_type $shopCode
	 * @param unknown_type $dateBankin
	 * @param unknown_type $amtBankin
	 * @param unknown_type $actAmtBankin
	 * @param unknown_type $amtMatch
	 */	
	public function updateDetail(  $idDetail ,  $idRecord , $shopCode , $dateBankin , $amtBankin , $actAmtBankin , $amtMatch){
		$data = array(
			
         "id_record" =>  $idRecord ,
         "shop_code" =>  $shopCode ,
         "date_bankin" =>  $dateBankin ,
         "amt_bankin" =>  $amtBankin ,
         "act_amt_bankin" =>  $actAmtBankin ,
         "amt_match" =>  $amtMatch 
	
			);
			
		$this->update($data,"`id_detail` = ". $idDetail);
		}
	public function confirmDetail($idDetail,$amtMatch){
		$data = array(
				"amt_match" =>  $amtMatch
		);
			
		$this->update($data,"`id_detail` = ". $idDetail);		
		
	}	
	/**
	 * Remove one Line
	 * @param unknown_type $idDetail
	 */	
	public function deleteDetail( $idDetail){
		
		$this->delete("`id_detail` = ". $idDetail);
		
		}	
	/**
	 * List All Details , it is not useful , only for Copy 
	 */
	public function listAll(){
		
		$rows =$this->fetchAll("1","id_detail DESC ");
		if(empty($rows)) return false;
		return $rows->toArray();			
		
		}
	public function listAllBy($whereStr='1',$orderBy = null,$orderDirection = null){
		
		$rows = '';
		if(isset($orderBy)){
			$rows =$this->fetchAll($whereStr,trim($orderBy.' '.$orderDirection));
		}
		else{
			$rows =$this->fetchAll($whereStr);			
		}
		if(empty($rows)) return false;
		return $rows->toArray();		
	}	

	public function getByShopByDate($shopCode,$dateDeposite){
		
		$whereStr =  "`shop_code` LIKE '{$shopCode}' AND `date_bankin` = '{$dateDeposite}'";
		$orderStr = "id_detail";
		return self::listAllBy($whereStr,$orderStr);
		
	}
}


?>