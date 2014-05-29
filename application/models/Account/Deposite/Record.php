<?php

class Model_Account_Deposite_Record extends Zend_Db_Table_Abstract {

	protected $_name = 'cashaccount_deposit_record';
	
	public function getRecord( $idRecord){
		
		$row = $this->fetchRow("`id_record` = ". $idRecord);
		if(!$row) return false;
		return $row->toArray();		
		
		}
		
	public function addRecord( $idManager , $dateDeposit , $totalAmtDeposit , $totalActAmt , $depositConfirmed , $confirmDate , $manualConfirmed , $manualConfirmedDate , $commentManager , $commentAuditor){
		
		$data = array(
			
         "id_manager" =>  $idManager ,
         "date_deposit" =>  $dateDeposit ,
         "total_amt_deposit" =>  $totalAmtDeposit ,
         "total_act_amt" =>  $totalActAmt ,
         "deposit_confirmed" =>  $depositConfirmed ,
         "confirm_date" =>  $confirmDate ,
         "manual_confirmed" =>  $manualConfirmed ,
         "manual_confirmed_date" =>  $manualConfirmedDate ,
         "comment_manager" =>  $commentManager ,
         "comment_auditor" =>  $commentAuditor 
	
			);
		$this->insert($data);
		
		}
	public function bankInRecord( $idManager , $dateDeposit , $totalAmtDeposit,$commentManager,$shopCode){

		$data = array(
					
				"id_manager" =>  $idManager ,
				"date_deposit" =>  $dateDeposit ,
				"total_amt_deposit" =>  $totalAmtDeposit ,
				"comment_manager" =>  $commentManager ,
				"shop_deposite" => $shopCode
		);
		$this->insert($data);		
		
		return $this->getAdapter()->lastInsertId();
	}	
		
	public function updateRecord(  $idRecord ,  $idManager , $dateDeposit , $totalAmtDeposit , $totalActAmt , $depositConfirmed , $confirmDate , $manualConfirmed , $manualConfirmedDate , $commentManager , $commentAuditor){
		$data = array(
			
         "id_manager" =>  $idManager ,
         "date_deposit" =>  $dateDeposit ,
         "total_amt_deposit" =>  $totalAmtDeposit ,
         "total_act_amt" =>  $totalActAmt ,
         "deposit_confirmed" =>  $depositConfirmed ,
         "confirm_date" =>  $confirmDate ,
         "manual_confirmed" =>  $manualConfirmed ,
         "manual_confirmed_date" =>  $manualConfirmedDate ,
         "comment_manager" =>  $commentManager ,
         "comment_auditor" =>  $commentAuditor 
	
			);
			
		$this->update($data,"`id_record` = ". $idRecord);
		}
		
	public function deleteRecord( $idRecord){
		
		$this->delete("`id_record` = ". $idRecord);
		
		}	
	
	public function listAll(){
		
		$rows =$this->fetchAll("1","id_record DESC ");
		if(!$rows) return false;
		return $rows->toArray();			
		
		}	
		
	public function matchRecord ($shop,$date,$amt){
		
		$AMT = ($amt=="")?0:$amt;
		
		$whereStr = " `shop_deposite` LIKE '{$shop}' AND `date_deposit` = '{$date}' AND ROUND(`total_amt_deposit`,2) = {$AMT}";
		$row = $this->fetchRow($whereStr);
		if(!$row) return false;
		return $row->toArray();
		
		
	}	
	
	public function confirmRecord($idRecord,$totalActAmt,$confirmDate){
		
		$data = array(
				"total_act_amt" =>  $totalActAmt ,
				"deposit_confirmed" =>  1 ,
				"confirm_date" =>  $confirmDate 
				);
		$this->update($data,"`id_record` = ". $idRecord);
		
		
	}
}


?>