<?php
/**
 * This Maily for Discount Policy
 * @author Norman
 *
 */
class Model_Policy {

	

	
	/**
	 * Discount Policy Section 
	 * @param unknown_type $arrInvoice
	 */
	public function calDiscount($arrInvoice,$arrCase){
		
		$arrSalesManTypeC = array("ADELE","HOPE","SIMON");
		$arrSalesManTypeB = array("EMILY","KAREN","LINA","SOPHIA_L","TERRA","YIN");
		
	// array -> inv date barcode 	
	//Signle Item
	$checkingRes = 1; // OK
	if(count($arrInvoice)==1){
	return 	$checkingRes = self::checkDisSigleItem($arrInvoice[0],$arrCase);
	}	
	//Bundle Deal
	elseif(count($arrInvoice)>1) {
		echo "Multi Invoice:".$arrInvoice[0][8];
		
		$arrInvoice = self::shiftArr($arrInvoice);
		
		//most expensive one no discount , vip 10%
		$firstOnePass = false;
		$allCheckPass = false;
		$bottomLine = false;
		
		$firstDisRate = self::calDiscountRate($arrInvoice[0][14],$arrInvoice[0][15],$arrInvoice[0][16]);
		//echo "DISRATE FIRST:".$firstDisRate;
		
		if($firstDisRate == 0 || self::checkVIP($arrInvoice[0][10] < 0.11)){$firstOnePass = true;}	
		$cot = 0;
		$restPass = true;
		
		
		if($firstOnePass){
			echo " first one Pass";
			foreach($arrInvoice as $key => $v){
				if($key >0){
					$sDisRate = self::calDiscountRate($v[14], $v[15], $v[16]);
					if(self::checkHashB($v[2])){
						if($sDisRate >0.3){
							$restPass = false;	
							$checkingRes = 5; 
						}
					}
					else{
						if($sDisRate >0.5){
							$restPass = false;
							$checkingRes = 5;
						}	
					}
					
					
				}
						
			}
			
		}
		else{
		// it gives discount for the first one , all the other under the authorize
		// and above the bottom line
			echo " first NOT Pass";
		
		$totalValueAfterDiscount = 0;
		$totalValueActualReceive = 0;

		
		foreach($arrInvoice as $key => $v){
			
			$totalValueActualReceive += (float) $v[17];					
			
			$lineRrp = (float) ($v[14] + $v[15]);

			if($key ==0){
				$totalValueAfterDiscount += $lineRrp; 
			}
			else{
				
				if(in_array($v[22],$arrSalesManTypeC) && self::checkHashB($v[2])){
					
					$totalValueAfterDiscount += $lineRrp*0.7;
				}
				if(in_array($v[22],$arrSalesManTypeC) && !self::checkHashB($v[2])){
						
					$totalValueAfterDiscount += $lineRrp*0.5;
				}
				if(in_array($v[22],$arrSalesManTypeB) && self::checkHashB($v[2])){
				
					$totalValueAfterDiscount += $lineRrp*0.8;
				}
				if(in_array($v[22],$arrSalesManTypeB) && !self::checkHashB($v[2])){
				
					$totalValueAfterDiscount += $lineRrp*0.7;
				}
				if(!in_array($v[22],$arrSalesManTypeB) && !in_array($v[22],$arrSalesManTypeC) && self::checkHashB($v[2])){
				
					$totalValueAfterDiscount += $lineRrp*0.9;
				}
				if(!in_array($v[22],$arrSalesManTypeB) && !in_array($v[22],$arrSalesManTypeC) && !self::checkHashB($v[2])){
				
					$totalValueAfterDiscount += $lineRrp*0.8;
				}
								
			}

			$allCheckPass = self::checkDisSigleItem($v,$arrCase);
		
			}//end of foreach
			echo "TotalActReceived:".$totalValueActualReceive."Should be:".$totalValueAfterDiscount;
			
			if($allCheckPass || ($totalValueActualReceive >= $totalValueAfterDiscount)){
				
				$checkingRes = 3;
			}
			
			else{
				
				$checkingRes = 5;
			}

		}//end of multi qty
				
		//other have no
		
		return $checkingRes;
	}
	// empty array
	else{
		
	return false;	
	}
		
	
	}
	public function checkDisSigleItem($arrInvoice,$arrCase){
		
		$arrSalesManTypeC = array("ADELE","HOPE","SIMON");
		$arrSalesManTypeB = array("EMILY","KAREN","LINA","SOPHIA_L","TERRA","YIN");
		
		echo "Single Invoice:".$arrInvoice[8].",";
		
		$salesMan = $arrInvoice[22];
		$reMark = $arrInvoice[10];
		$barCode = $arrInvoice[0];
		$itemDes = $arrInvoice[2];
		$orgPrice= $arrInvoice[14] + $arrInvoice[15];
		$itemDis = $arrInvoice[15];
		$invDis = $arrInvoice[16];
		$actReceive = $arrInvoice[17];
		
		$disRate = self::calDiscountRate($arrInvoice[14],$itemDis,$invDis);
		
		echo "Dis Rate is (".$disRate."),";
		echo ",Sales Man is ".$salesMan;
		//if #B
		if(self::checkHashB($itemDes)){
			echo " It is #B ,";
			if( (in_array($salesMan,$arrSalesManTypeC) && $disRate < 0.31) ||
					(in_array($salesMan,$arrSalesManTypeB) && $disRate < 0.21) ||
					$disRate <0.11 ||
					(self::checkVIP($reMark) && in_array($salesMan,$arrSalesManTypeC) && $disRate < 0.37 ) ||
					(self::checkVIP($reMark) && in_array($salesMan,$arrSalesManTypeB) && $disRate < 0.28 ) ||
					(self::checkVIP($reMark) && $disRate <0.21)
			){
				echo " PASS ";
				$checkingRes = 1; // OK
			}
			else{
				if(self::checkApproved($reMark) && $disRate < 0.31 ){
					$checkingRes = 3;
					echo "HAS APPROVE:".$reMark;
				} // MAY BE OK
				else{
					$checkingRes = 5;
					echo "NO PASS";
				} // NOT OK
			}
				
		}
		else{
			// not # B
			echo "It is NOT #B,";
			if( (in_array($salesMan,$arrSalesManTypeC) && $disRate < 0.51) ||
					(in_array($salesMan,$arrSalesManTypeB) && $disRate < 0.31) ||
					$disRate < 0.21 ||
					(self::checkVIP($reMark) && in_array($salesMan,$arrSalesManTypeC) && $disRate < 0.55 ) ||
					(self::checkVIP($reMark) && in_array($salesMan,$arrSalesManTypeB) && $disRate < 0.37 ) ||
					(self::checkVIP($reMark) && $disRate <0.31)
			){
				echo " PASS ";
				$checkingRes = 1; // OK
			}
			else{
				if(self::checkApproved($reMark) && $disRate < 0.51 ){
					$checkingRes = 3;
					echo "HAS APPROVE:".$reMark;
				} // MAY BE OK
				else{
					$checkingRes = 5;
					echo "NO PASS";
				} // NOT OK
			}
		
		}
		
		// if pass , just ignore
		if($checkingRes == 5){
		
			//check 20% discount
			$p20Pass = false;
			$hashCPass = false;
			$vipPass = false;
			$iPh3Pass = false;
			$spPass = false;
			
			if(in_array($barCode,$arrCase) && $disRate < 0.21){
				$p20Pass = true;	
			}
			
			//check #C , VIP, iph3

		
			if(self::checkHashC($itemDes)){
				echo " IT IS #C";
				if( $disRate <0.51 ){
		
					echo "PASS";
					$hashCPass = true;// OK
				}
				else{
					echo "NOT PASS";
				}
		
			}
			if(self::isSP($itemDes)){
				echo "It is Sp";
				if( $disRate <0.51 ){
				
					echo "PASS";
					$spPass = true;// OK
				}
				else{
					echo "NOT PASS";
				}
			}
		
			// VIP
		
			if(self::checkVIP($reMark)){
		
				echo " It is VIP";
		
				if(self::isSP($itemDes) && $actReceive >1.99 ){
					$vipPass = true;
				}
		
				if(self::isCarCharger($barCode) && $actReceive >7.99 ){
					$vipPass = true;
				}
		
				if(self::isAcCharger($barCode) && $actReceive >9.99 ){
					$vipPass = true;
				}
		
			}
		
			// iphone 3
			echo "Checking Iph3".$barCode.$orgPrice;
			if(self::isIPH3($barCode) && $orgPrice >= 10){
		
				echo "Iph3";
		
				if( round($actReceive,1)-9.91 > 0 ){
					$iPh3Pass = true;
				}
		
			}
		
			if($p20Pass || $hashCPass || $vipPass || $iPh3Pass || $spPass){
					
				$checkingRes = 1;
			}
		
		}
		
		echo "FinalRes:".$checkingRes;
		return $checkingRes;
	}
	/**
	 * 
	 * @param unknown_type $line14 orginal price
	 * @param unknown_type $line15 item discount 
	 * @param unknown_type $line16 invoice discount 
	 */
	private static function calDiscountRate($line14,$line15,$line16){
		$disRate = 0;
		
		if($line15 >0 || $line16 > 0){
			if($line15 >0 ){

				$disRate = $line15 /($line15 + $line14);
			}
			else{
				
				$disRate = $line16 / $line14 ;
			}			
		}
		else{
			
		return 0;	
		}

		return $disRate;
	}
	private static function checkHashB($itemDes){
	
		return $res = (strpos($itemDes,"#B")!==false)?true:false;
	}	
	private static function checkHashC($itemDes){
		
		return $res = (strpos($itemDes,"#C")!==false)?true:false;
	}
	private static function checkApproved($reMark){
		return $res = (strpos(strtolower($reMark),"approve")!==false)?true:false;
	}
	
	private static function checkVIP($reMark){
		return $res = (strpos(strtolower($reMark),"vip")!==false)?true:false;
	}
	
	private static function isSP($itemDes){
		return $res = (strpos(strtolower($itemDes),"protector")!==false)?true:false;
	}
	private static function isCarCharger($barCode){
		return $res = (strpos($barCode,"CHCA")!==false)?true:false;
	}
	private static function isAcCharger($barCode){
		return $res = (strpos($barCode,"CHAC")!==false)?true:false;
	}
	private static function shiftArr($arrList){
	
		$orgPriceArr = array();
		foreach($arrList as $key => $value){
				
			if($value[15] > 0){
				$orgPriceArr[$key] = $value[15] + $value[14];
			}
			else{
				$orgPriceArr[$key] = $value[14];
			}
		}		
				array_multisort($orgPriceArr,SORT_DESC,$arrList);
				return $arrList;
	}
	private static function isIPH3($barCode){
		return $res = (strpos($barCode,"IPH3")!==false)?true:false;
	}
	
	/**
	 * Other Section 
	 */
	
	

}

?>