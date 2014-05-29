<?php

class Model_Aposinit {
	
	public static function initProStock($shop){
	
		$proSkOld =  new Model_DbTable_Apos_Stock_Balanceold(Zend_Registry::get('db_oldapos'));
		$proSkNew =  new Model_DbTable_Apos_Stock_Balancenew(Zend_Registry::get('db_apos'));
	
		$proSk = "";
		if($shop == "BSXP" || $shop == "CBPC" || $shop == "CLIC" || $shop == "CSIC" || $shop == "DCIC" || $shop == "FGIC" || $shop == "HPIC" || $shop == "PMIC" || $shop == "EPPC" ){
			$proSk = $proSkNew;
		}
		else{
			$proSk = $proSkOld;
		}
	
		return $proSk;
	
	}
	
	public static function initProDes(){
		
		$proDes = new Model_DbTable_Apos_Stock_Productdes(Zend_Registry::get('db_oldapos'));
		return $proDes;
	}
	
public static function initAposInvoice($shop){
	
		$invOld = "";
	
		if($shop == "CBPC"){
			$invOld = new Model_DbTable_Apos_Invoice_Cb(Zend_Registry::get('db_apos'));
		}
		if($shop == "BSXP"){
			$invOld = new Model_DbTable_Apos_Invoice_Bsxp(Zend_Registry::get('db_apos'));
		}
		if($shop == "CLIC"){
			$invOld = new Model_DbTable_Apos_Invoice_Clic(Zend_Registry::get('db_apos'));
		}
		if($shop == "CSIC"){
			$invOld = new Model_DbTable_Apos_Invoice_Csic(Zend_Registry::get('db_apos'));
		}
		if($shop == "DCIC"){
			$invOld = new Model_DbTable_Apos_Invoice_Dcic(Zend_Registry::get('db_apos'));
		}
		if($shop == "FGIC"){
			$invOld = new Model_DbTable_Apos_Invoice_Fgic(Zend_Registry::get('db_apos'));
		}
		if($shop == "HPIC"){
			$invOld = new Model_DbTable_Apos_Invoice_Hpic(Zend_Registry::get('db_apos'));
		}
		if($shop == "PMIC"){
			$invOld = new Model_DbTable_Apos_Invoice_Pmic(Zend_Registry::get('db_apos'));
		}
		if($shop=="WBPC"){
			$invOld = new Model_DbTable_Apos_Invoice_Wb(Zend_Registry::get('db_oldapos'));
		}	
		if($shop=="BHPC"){
			$invOld = new Model_DbTable_Apos_Invoice_Bh(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="NLPC"){
			$invOld = new Model_DbTable_Apos_Invoice_Nl(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="CLPC"){
			$invOld = new Model_DbTable_Apos_Invoice_Cl(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="WGPC"){
			$invOld = new Model_DbTable_Apos_Invoice_Wgpc(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="WBIC"){
			$invOld = new Model_DbTable_Apos_Invoice_Wbic(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="BBPC"){
			$invOld = new Model_DbTable_Apos_Invoice_Bb(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="EPPC"){
			$invOld = new Model_DbTable_Apos_Invoice_Eppc(Zend_Registry::get('db_apos'));
		}
		if($shop=="EPIC"){
			$invOld = new Model_DbTable_Apos_Invoice_Ep(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="WLIC"){
			$invOld = new Model_DbTable_Apos_Invoice_Wlic(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="KCPC"){
			$invOld = new Model_DbTable_Apos_Invoice_Kc(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="PMPC"){
			$invOld = new Model_DbTable_Apos_Invoice_Pm(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="BSIC"){
			$invOld = new Model_DbTable_Apos_Invoice_Bsic(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="BSPC"){
			$invOld = new Model_DbTable_Apos_Invoice_Bs(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="SLIC"){
			$invOld = new Model_DbTable_Apos_Invoice_Slic(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="ADPC"){
			$invOld = new Model_DbTable_Apos_Invoice_Ad(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="WGIC"){
			$invOld = new Model_DbTable_Apos_Invoice_Wgic(Zend_Registry::get('db_oldapos'));
		}
		return $invOld;
	}
	
	public static function initAposInvPro($shop){
	
		$invOld = "";
	
		if($shop == "CBPC"){
			$invPro = new Model_DbTable_Apos_Invoice_Products_Cb(Zend_Registry::get('db_apos'));
		}
		if($shop == "BSXP"){
			$invPro = new Model_DbTable_Apos_Invoice_Products_Bsxp(Zend_Registry::get('db_apos'));
		}
		if($shop == "CLIC"){
			$invPro = new Model_DbTable_Apos_Invoice_Products_Clic(Zend_Registry::get('db_apos'));
		}
		if($shop == "CSIC"){
		
			$invPro = new Model_DbTable_Apos_Invoice_Products_Csic(Zend_Registry::get('db_apos'));
		}
		if($shop == "DCIC"){
		
			$invPro = new Model_DbTable_Apos_Invoice_Products_Dcic(Zend_Registry::get('db_apos'));
		}
		if($shop == "FGIC"){
		
			$invPro = new Model_DbTable_Apos_Invoice_Products_Fgic(Zend_Registry::get('db_apos'));
		}
		if($shop == "HPIC"){
		
			$invPro = new Model_DbTable_Apos_Invoice_Products_Hpic(Zend_Registry::get('db_apos'));
		}
		if($shop == "PMIC"){
		
			$invPro = new Model_DbTable_Apos_Invoice_Products_Pmic(Zend_Registry::get('db_apos'));
		}
		if($shop=="WBPC"){
		
			$invPro = new Model_DbTable_Apos_Invoice_Products_Wb(Zend_Registry::get('db_oldapos'));
		}
			
		if($shop=="BHPC"){
		
			$invPro = new Model_DbTable_Apos_Invoice_Products_Bh(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="NLPC"){
		
			$invPro = new Model_DbTable_Apos_Invoice_Products_Nl(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="CLPC"){
		
			$invPro = new Model_DbTable_Apos_Invoice_Products_Cl(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="WGPC"){
		
			$invPro = new Model_DbTable_Apos_Invoice_Products_Wgpc(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="WBIC"){
		
			$invPro = new Model_DbTable_Apos_Invoice_Products_Wbic(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="BBPC"){
		
			$invPro = new Model_DbTable_Apos_Invoice_Products_Bb(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="EPPC"){
		 
			$invPro = new Model_DbTable_Apos_Invoice_Products_Eppc(Zend_Registry::get('db_apos'));
		}
		if($shop=="EPIC"){
		
			$invPro = new Model_DbTable_Apos_Invoice_Products_Ep(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="WLIC"){
		
			$invPro = new Model_DbTable_Apos_Invoice_Products_Wlic(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="KCPC"){
		
			$invPro = new Model_DbTable_Apos_Invoice_Products_Kc(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="PMPC"){
		
			$invPro = new Model_DbTable_Apos_Invoice_Products_Pm(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="BSIC"){
		
			$invPro = new Model_DbTable_Apos_Invoice_Products_Bsic(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="BSPC"){
		
			$invPro = new Model_DbTable_Apos_Invoice_Products_Bs(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="SLIC"){
		
			$invPro = new Model_DbTable_Apos_Invoice_Products_Slic(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="ADPC"){
		
			$invPro = new Model_DbTable_Apos_Invoice_Products_Ad(Zend_Registry::get('db_oldapos'));
		}
		if($shop=="WGIC"){
		
			$invPro = new Model_DbTable_Apos_Invoice_Products_Wgic(Zend_Registry::get('db_oldapos'));
		}
	
		return $invPro;
	}

}

?>