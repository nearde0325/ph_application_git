<?php 
/**
 * 
 * @author Norman
 *
 */
class WatchdogController extends Zend_Controller_Action
{

    public function init(){
    /**
	 *
	 *
	 */    
	
	}

    public function indexAction(){
	
		echo "Watch Dog Controller Running";

    $arrVeryImportant = array("kkkk",
				"290766988566",
				"300760953353", 
    			"290746939926",
    		    "290737949297",
    			"290736100959",
    			"290738759598",
    			"290738863028",
    			"300734109580",
    			"300729968630",
    			"300714447144",
    			"300729112442",
    			"300735706155",
    			"290737062651",
    			"290735041786",
    			"300695620711",
    			"300737196197",
    			"300717606705",
    			"300737277260",
    			"300726296624",
    			"300725957308",
    			"290726484242",
    			"300737196208",
    			"290703061698",
    			"290733687056",
    			"300735274106",
    			"300729968968",
    			"300722828808",
    			"290738864420",
    			"290737320552",
    			"290743090259",
    			"300722824570",
    		    "290737062316"
    		//"290703061698",
    						);

    $minQtyVeryImportant = 4;
    
    $arrImportant = array(
    		"290735644489",
    		"300733669397",
    		"300733669389",
    		"290735644506",
    		"300733669429",
    		"290737476834",
    		"290737476402",
    		"300735724053",
    		"290737454037",
    		"290737477307",
    		"290737483605"
    		//iPad Multi Color Listing
    
    );
    
    $minQtyImportant = 3;
    
    //min 2
    
    $arrNotVeryImportant = array(
    		"290737487141",
    		"290737471054",
    		"290737914839",
    		"300736216024",
    		"300735707953",
    		"290737479797",
    		"300743049287",
    		"300736216742",
    		"300735707897",
    		"290737488726",
    		"290737486403",
    		"290737483605",
    		"290737480956",
    		"290737454029",
    		"300734227433"
    		//iPad Single Color Listing
    
    );
    
    $minQtyNotVeryImportant = 2;
    
    
    
		

	$wemail = new Model_Watchdogemailshandler();
	$resultMsg = $wemail->downloadSold("watchdog.lovebargain", "Mon80ash");
	
	if($resultMsg){
		
	echo $ebayId = $wemail->analySubject($resultMsg[0]);	
    
	$resultQty = $wemail->analySales($resultMsg[1]);
	
	//var_dump($resultQty);
	$qtyRemain = intval($resultQty[1]);
	
	if(in_array($ebayId,$arrVeryImportant)=== true and $qtyRemain <= $minQtyVeryImportant ){
		
		$wemail->sendAlert($ebayId,"Very");
		
	}
	if(in_array($ebayId,$arrImportant)=== true and $qtyRemain <= $minQtyImportant ){
	
		$wemail->sendAlert($ebayId,"Important");
	
	}
	if(in_array($ebayId,$arrNotVeryImportant)=== true and $qtyRemain <= $minQtyNotVeryImportant ){
	
		$wemail->sendAlert($ebayId,"NotVeryImportant");
	
	}		
	
	
	
	}
	else{
		
		echo "No New Message";
	}

    }

			
}
?>