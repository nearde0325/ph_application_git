<?php
class Model_DbTable_Target_Shopfomula extends Zend_Db_Table_Abstract {

	protected $_name = 'shop_fomula';
	
	public function getShopfomula( $idFomula){
		
		$row = $this->fetchRow("`id_fomula` = ". $idFomula);
		if(!$row) return false;
		return $row->toArray();		
		
		}
	public function getShopFomulaByShopByStaff($idStaff,$shopCode){
		$row = $this->fetchRow("`shop_code` LIKE '". $shopCode."' AND `id_staff` = ".$idStaff );
		if(!$row) return false;
		return $row->toArray();		
	}	
		
	public function getShopFomulaByShop($shopCode){
		$row = $this->fetchRow("`shop_code` LIKE '". $shopCode."'");
		if(!$row) return false;
		return $row->toArray();		
	}	
		
	public function addShopfomula( $shopCode , $choice , $fixAmt , $rateA , $ratePrecent , $rateAmt , $idStaff , $rateAChoice ){
		
		$data = array(
			
         "shop_code" =>  $shopCode ,
         "choice" =>  $choice ,
         "fix_amt" =>  $fixAmt ,
         "rate_a" =>  $rateA ,
         "rate_precent" =>  $ratePrecent ,
         "rate_amt" =>  $rateAmt,
		 "rate_a_choice" =>  $rateAChoice,
		 "id_staff" => $idStaff			 
			);
		$this->insert($data);
		
		}
		
	public function updateShopfomula(  $idFomula ,  $shopCode , $choice , $fixAmt , $rateA , $ratePrecent , $rateAmt,$rateAChoice,$idStaff){
		echo "Updateing";
		$data = array(
			
         "shop_code" =>  $shopCode ,
         "choice" =>  $choice ,
         "fix_amt" =>  $fixAmt ,
         "rate_a" =>  $rateA ,
         "rate_precent" =>  $ratePrecent ,
         "rate_amt" =>  $rateAmt, 
		 "rate_a_choice" =>  $rateAChoice,
		 "id_staff" => $idStaff
			);
			
		$this->update($data,"`id_fomula` = ". $idFomula);
		}
		
	public function deleteShopfomula( $idFomula){
		
		$this->delete("`id_fomula` = ". $idFomula);
		
		}	
	
	public function listAll(){
		
		$rows =$this->fetchAll("1",array("id_staff ASC ","shop_code ASC"));
		if(!$rows) return false;
		return $rows->toArray();			
		
		}
	public function listShopByManager($idStaff){
		
		$rows =$this->fetchAll("`id_staff` = ".$idStaff,array("shop_code ASC"));
		if(!$rows) return false;
		return $rows->toArray();		
		
	}	
	public function calBonus($idStaff,$shopCode,$weekAct,$weeklyTarget){
		$fLine = self::getShopFomulaByShopByStaff($idStaff, $shopCode);
		if($weekAct > $weeklyTarget){
			//fomula 
			if($fLine["choice"]){
				if($fLine["rate_a_choice"]){
					return abs($weekAct - $fLine["rate_a"]) * $fLine["rate_precent"]/100 + $fLine["rate_amt"];	
				}
				else{
					
					return abs($weekAct - $weeklyTarget) * $fLine["rate_precent"]/100 + $fLine["rate_amt"];
				}
			}
			else{
				
				return $fLine["fix_amt"];
			}
			
		}
		else{
			
			return 0;
		}
		
		
	}		
}

?>