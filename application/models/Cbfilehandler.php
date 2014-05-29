<?php

class Model_Cbfilehandler {

	protected $arrCbTerminalId = array(
			'ADPC' => '33829100',
			'BBPC' => '33817900',
			'BHPC' => '33820200',
			'BSIC' => '33819800',
			'BSPC' => '33825300',
			'BSXP' => '33818600',
			'CBPC' => '33817100',
			'CLIC' => '33815900',
			'CLPC' => '33821600',
			//'CSIC' => '33818900',
			'EPIC' => '33818900',
			'DCIC' => '33825200',
			'EPPC' => '33822300',
			'FGIC' => '33811500',
			'HPIC' => '33825600',
			'KCPC' => '33811600',
			'NLPC' => '33811700',
			'PMIC' => '33819300',
			'PMPC' => '33814800',
			'SLIC' => '33814100',
			'WBIC' => '33810400',
			'WBPC' => '33814700',
			'WGIC' => '33814500',
			'WGPC' => '33810900',
			'WLIC' => '33820100'
			);
	
	public function readFile($csvFileName,$shopCode){
		//array structure 
		// TID,   97, saving tottoal , Qty, cd total , Qty
		//detail 
		$cb = new Model_Commbiz_Cbizdetail(Zend_Registry::get('db_real'));
		
		if(!file_exists(getcwd()."/cb/".$csvFileName)){
			return false;
		}
		$fl = fopen(getcwd()."/cb/".$csvFileName,"r");
		
		$cot = 0;
		$lineCot1 = 0;
		$cutShop = "";
		$arrSum = array();
		$arrDetail = array();
		$arrTmp = array();
		
			while(($lineData = fgetcsv($fl,0)) != false){
				//var_dump($lineData);
				if(isset($lineData[1])){
				$key = array_search(trim($lineData[1]),$this->arrCbTerminalId,true);
				if($key!== false){
					
					$cutShop = $key;
				}
				
				if(trim($lineData[0]) == '97' && trim($lineData[1])=='PURCHASES'){
					
					$amtSave = round(trim($lineData[2]),2);
					$qtySave = trim($lineData[3]);
					$amtCrd = round(trim($lineData[4]),2);
					$qtyCrd = trim($lineData[5]);
					
					$arrSum[$cutShop] = array($amtSave,$qtySave,$amtCrd,$qtyCrd);
					$arrDetail[$cutShop] = $arrTmp;
				}
				if(trim($lineData[0])=='Sr No.'){
					
					$arrTmp = array();
				}
				if(trim($lineData[0])=='03'){
					
					$arrTmp[]= $lineData;
					
					$arrDate = explode("/",$lineData[3]);
					
					$transDate = "20".$arrDate[2]."-".$arrDate[1]."-".$arrDate[0];
					
					if($cutShop == $shopCode){
					$cb->addCbizdetail($cutShop,$lineData[1],$lineData[2], $transDate, $lineData[4], $lineData[5], $lineData[6], $lineData[7], $lineData[8], $lineData[9], $lineData[10], $lineData[11]);
					}
					}
				
				}
			
				$cot++;
			}
		//echo "<pre>";
		//var_dump($arrSum,$arrDetail);
		//echo "</pre>";
	
		return array($arrSum,$arrDetail);
	}
	
	public function handleUploadFile($zipFileName){
		
		$zip = new ZipArchive();
		$zip->open($zipFileName);
		$zip->extractTo(getcwd().'/tmp/');
		$zip->close();
		
		//rename files 
		
		$arrFiles = $this->getDirectoryList(getcwd().'/tmp/');
		
		foreach($arrFiles as $key => $fileName){
			
			//echo $fileName;
			$arrFileNames = explode("_",$fileName);
			if($arrFileNames[0]=="MRCHRPT"){
				
				$year = substr(trim($arrFileNames[2]),0,4);
				$month = substr(trim($arrFileNames[2]),4,2);
				$day =  substr(trim($arrFileNames[2]),6,2);
				
				$date = $year."-".$month."-".$day;
				$dateAct = Model_DatetimeHelper::adjustDays("sub", $date,2,"");
				
				echo $newFileName = $arrFileNames[0].$dateAct.".csv";
				copy(getcwd().'/tmp/'.$fileName,getcwd().'/cb/'.$newFileName);
				@unlink(getcwd().'/tmp/'.$fileName);
			}
			
		}
		
	}
	
	public function runCbAposMatching($dateToCheck,$shopCode){
		//get file 
		//get apos 
		
		//$cf = new Model_Cbfilehandler();
		$arrShopMaping = unserialize(ARR_APOS_SHOP_MAPPING);
		
		date_default_timezone_set("Australia/Melbourne");
		$fileDate = date("Ymd",strtotime($dateToCheck));
		
		$arrCFile = $this->readFile('MRCHRPT'.$fileDate.'.csv',$shopCode);

		$invOld = Model_Aposinit::initAposInvoice($shopCode);
		
		$arrInvoice = $invOld->getInvoicesByDate( $dateToCheck.' 00:00:00');
		
		
		
		$arrAposInv = array();
		foreach($arrInvoice as $inv){
			if($inv["STATUS"] != "V" ){
					
				if(trim($inv["PTYPE1"])!=""){
					switch((int)$inv["PTYPE1"]){
						case(1):{
							break;
						}
						case(6):{
							break;
						}
						case(7):{
							break;
						}
						default:{
							$arrAposInv[] = $inv["INV_NO"];
							$arrAposInv[] = $inv["TIME"];
							$arrAposInv[] = round($inv["PAID1"],2);
							break;
						}
					};
		
				}
		
				if(trim($inv["PTYPE2"])!=""){
						
					switch((int)$inv["PTYPE2"]){
						case(1):{
		
							break;
						}
						case(6):{
		
							break;
						}
						case(7):{
							break;
						}
						default:{
							$arrAposInv[] = $inv["INV_NO"];
							$arrAposInv[] = $inv["TIME"];
							$arrAposInv[] = round($inv["PAID2"],2);
							break;
						}
					};
		
				}
					
				if(trim($inv["PTYPE3"])!=""){
					switch((int)$inv["PTYPE3"]){
						case(1):{
								
							break;
						}
						case(6):{
								
							break;
						}
						case(7):{
							break;
						}
						default:{
							$arrAposInv[] = $inv["INV_NO"];
							$arrAposInv[] = $inv["TIME"];
							$arrAposInv[] = round($inv["PAID3"],2);
							break;
						}
					};
		
				}
		
			}
				
		}
		
		$arrMatch = array();
		
		
		foreach($arrCFile[0][$shopCode] as $key => $v){
			
			var_dump($key,$v);
		}
		
		foreach($arrCFile[1][$shopCode] as $key => $v){
			$arrMatch[] = substr(trim($v[4]),0,5);
			$arrMatch[] = round($v[11],2);
		}
		
		foreach($arrMatch as $key2 => $v2){
			if( $key2 %2 == 1){
		
		
				$findKeys = array_keys($arrAposInv,$v2,true);
		
				if($findKeys!==false){
					//check time
					foreach($findKeys as $find){
						$intMatchTime = date("U",strtotime($arrMatch[ $key2 -1]));
						$intAposTime = date("U",strtotime($arrAposInv[ $find -1]));
						
						if($shopCode == "ADPC" || $shopCode == "WLIC" || $shopCode == "CLPC" || $shopCode == "CLIC" ){
							$intAposTime += 1800;
						}
						
						if(abs($intMatchTime - $intAposTime) < 241 ){
							$arrAposInv[$find] = null;
							$arrAposInv[$find - 1] = null;
							$arrAposInv[$find - 2] = null;
		
							$arrMatch[$key2] = null;
							$arrMatch[$key2 -1] = null;
						}
					}
						
				}
			}
				
		}

		$arrAposInv = array_filter($arrAposInv, 'strlen');
		$arrMatch = array_filter($arrMatch, 'strlen');

		return array($arrAposInv,$arrMatch);
	}
	
	
	private function getDirectoryList ($directory)
	{
	
		// create an array to hold directory list
		$results = array();
	
		// create a handler for the directory
		$handler = opendir($directory);
	
		// open directory and walk through the filenames
		while ($file = readdir($handler)) {
	
			// if file isn't this directory or its parent, add it to the results
			if ($file != "." && $file != "..") {
				$results[] = $file;
			}
	
		}
	
		// tidy up: close the handler
		closedir($handler);
	
		// done!
		return $results;
	
	}
	
	
	
	
	
}

?>