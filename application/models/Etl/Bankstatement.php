<?php

class Model_Etl_Bankstatement {
	
	public function readStatementFile($fileName){
		
		
		
		//transfer file to array
		
	}
	public function matchShop($string,$stateId){
		
		$amt = self::getAmt($string);
		$date = self::getDate($string);
		$scStr = self::getShoppingCenter($string);
		$shopCode = self::mappingShop($scStr, $stateId);
		
		
		return array($date,$shopCode,$amt,$string,$stateId);
	}
	
	private static function getAmt($string)
	{
		$arrTmp = explode("$", $string);
		return trim($arrTmp[1]);
	}
	
	private function getShoppingCenter($string)
	{
		$arrTmp = explode("$", $string);
		return trim($arrTmp[0]);
	}
	private function getDate($string){
		
		return substr($string,0,6);
	}
	public function mappingShop($shopString,$stateId)
	{
		//1 IC
		//2 PC
		//3 NEW GEN 
		$shopCode = "";
		
		if($stateId == 1){
			
			
			if(strpos(trim($shopString),'Branch Doncaster STown') !== false){
				
				return "DCIC";
			}
			if(strpos(trim($shopString),'Branch Frankston') !== false){
			
				return "BSIC";
			}
			if(strpos(trim($shopString),'Branch Highpoint') !== false){
					
				return "HPIC";
			}
			if(strpos(trim($shopString),'Branch Noarlunga') !== false){
					
				return "CLIC";
			}
			if(strpos(trim($shopString),'Branch Watergardens') !== false){
					
				return "WGIC";
			}
			if(strpos(trim($shopString),'Branch West Lakes') !== false){
					
				return "WLIC";
			}
			if(strpos(trim($shopString),'Branch Cheltenham') !== false){
					
				return "SLIC";
			}
			if(strpos(trim($shopString),'Branch Werribee Plaza') !== false){
					
				return "WBIC";
			}
			if(strpos(trim($shopString),'Branch Brimbank Cntl') !== false){
					
				return "BBPC";
			}
			 	
			}

		if($stateId == 2){
			
			if(strpos(trim($shopString),'Branch Knox City') !== false){
			
				return "KCPC";
			}
			if(strpos(trim($shopString),'Branch Noarlunga') !== false){
			
				return "CLPC";
			}
			if(strpos(trim($shopString),'Branch West Lakes') !== false){
			
				return "WLIC";
			}
			if(strpos(trim($shopString),'Branch Werribee Plaza') !== false){
			
				return "WBPC";
			}
			if(strpos(trim($shopString),'Branch Epping') !== false){
			
				return "EPIC";
			}
			if(strpos(trim($shopString),'Branch Nthland Centre') !== false){
			
				return "NLPC";
			}
			if(strpos(trim($shopString),'Branch Watergardens') !== false){
			
				return "WGPC";
			}
			if(strpos(trim($shopString),'Branch Doncaster STown') !== false){
			
				return "DCIC";
			}
			if(strpos(trim($shopString),'Branch Frankston') !== false){
			
				return "BSPC";
			}
			if(strpos(trim($shopString),'Branch Kilkenny') !== false){
					
				return "ADPC";
			}
			
		}	
		
		if($stateId == 3){
			
			if(strpos(trim($shopString),'Branch Frankston') !== false){
					
				return "BSXP";
			}
			if(strpos(trim($shopString),'Cash Dep Branch Cranbourne') !== false){
					
				return "CBPC";
			}
			if(strpos(trim($shopString),'Cash Dep Branch Fountain Gate') !== false){
					
				return "FGIC";
			}
			if(strpos(trim($shopString),'Branch Epping') !== false){
					
				return "EPPC";
			}
		}
			
	}
}

?>