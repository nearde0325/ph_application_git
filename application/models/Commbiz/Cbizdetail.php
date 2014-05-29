<?php


class Model_Commbiz_Cbizdetail extends Zend_Db_Table_Abstract {

	protected $_name = 'cbiz_detail';
	
	public function getCbizdetail( $idCb){
		
		$row = $this->fetchRow("`id_cb` = ". $idCb);
		if(!$row) return false;
		return $row->toArray();		
		
		}
		
	public function addCbizdetail( $shopCode , $transNo , $cardNo , $transDate , $transTime , $respCode , $posMode , $accType , $transType , $purAmt , $coAmt , $totalAmt){
		
		$data = array(
			
         "shop_code" =>  $shopCode ,
         "trans_no" =>  $transNo ,
         "card_no" =>  $cardNo ,
         "trans_date" =>  $transDate ,
         "trans_time" =>  $transTime ,
         "resp_code" =>  $respCode ,
         "pos_mode" =>  $posMode ,
         "acc_type" =>  $accType ,
         "trans_type" =>  $transType ,
         "pur_amt" =>  $purAmt ,
         "co_amt" =>  $coAmt ,
         "total_amt" =>  $totalAmt 
	
			);
		$this->insert($data);
		
		}
		
	public function updateCbizdetail(  $idCb ,  $shopCode , $transNo , $cardNo , $transDate , $transTime , $respCode , $posMode , $accType , $transType , $purAmt , $coAmt , $totalAmt){
		$data = array(
			
         "shop_code" =>  $shopCode ,
         "trans_no" =>  $transNo ,
         "card_no" =>  $cardNo ,
         "trans_date" =>  $transDate ,
         "trans_time" =>  $transTime ,
         "resp_code" =>  $respCode ,
         "pos_mode" =>  $posMode ,
         "acc_type" =>  $accType ,
         "trans_type" =>  $transType ,
         "pur_amt" =>  $purAmt ,
         "co_amt" =>  $coAmt ,
         "total_amt" =>  $totalAmt 
	
			);
			
		$this->update($data,"`id_cb` = ". $idCb);
		}
		
	public function deleteCbizdetail( $idCb){
		
		$this->delete("`id_cb` = ". $idCb);
		
		}	
	
	public function listAll(){
		
		$rows =$this->fetchAll("1","id_cb DESC ");
		if(!$rows) return false;
		return $rows->toArray();			
		
		}	
		
	public function listByShopByDate($shopCode,$dateTrans){
		
		$whereStr = "`shop_code` LIKE '".$shopCode."' AND `trans_date` = '".$dateTrans."' ";
		
		$rows =$this->fetchAll($whereStr,"id_cb DESC ");
		if(empty($rows)) return false;
		return $rows->toArray();		
		
	}
	
	public function getTotalSalesByShopByDate($shopCode,$dateTrans){
		
		$db = $this->getAdapter();
		$sqlstr="SELECT SUM(`total_amt`) AS tat FROM `".$this->_name."` WHERE `shop_code` LIKE '".$shopCode."' AND `trans_date` = '".$dateTrans."' ; ";
		$stmt = $db->prepare($sqlstr);
		$stmt->execute();
		$result = $stmt->fetchAll();
		
		return $result[0]['tat'];
	}
	
}



?>