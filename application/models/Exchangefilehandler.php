<?php

/**
 *
 * @author Norman
 *        
 *        
 */

class Model_Exchangefilehandler {

	public function checkFileHead(){
		
		$str1 = "ebay ID,Customer Name";
		$str2 = "ebay ID,customerName";
		$arr1 = self::formatFileHead($str1);
		$arr2 = self::formatFileHead($str2);
		var_dump(self::compareArray($arr1, $arr2,array("0","1"))); 
	}
	public function readCsvFile(){
		
	}
	private function formatFileHead($strHead){		
		//str to lower 
		$strHead = strtolower($strHead);
		$strHead = str_replace(" ", "", $strHead);
		$arrHead = explode(",",$strHead);
		return $arrHead;		
	}
	private function compareArray($arrCust,$arrSys,$arrKeys){
		
		$errorFlag = false;
		//$goAhead = true;
		$errorKeys = array();
		
		foreach($arrKeys as $v){
			if($arrCust[$v]!=$arrSys[$v]){$errorFlag = true;$errorKeys[] = $v;}
		}
		
		if($errorFlag){
			//try to find what error and if able to go ahead 
			if(count($errorKeys) == 1){
				$ll = levenshtein($arrCust[$errorKeys[0]],$arrSys[$errorKeys[0]]);
				if($ll/strlen($arrSys[$errorKeys[0]]) < 0.4) {return true;}
				else{return false;}
			}
			else{
				
				return false;
			}

			
			
		}
		//compare with orginal array , give errors
		//1.only compare keys 
		//2.shift 
		//3.change chartactor
		//4.change order 
		//report  
		return true;
	}
}

?>