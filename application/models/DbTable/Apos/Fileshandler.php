<?php
require_once ('PHPExcel.php');
//include 'PHPExcel/Writer/Excel2007.php';

class Model_Fileshandler
{

public function importAPOSCsv($csvFileName){
	
	if(!file_exists($csvFileName)){		
		return false;
	}
	//$arrTemp = array();
	$arrResult = array();
	
	$fl = fopen($csvFileName,"r");
	
	while(($lineData = fgetcsv($fl,0)) != false){
		
		//echo $lineData[0];
		//echo ".";
		//var_dump($lineData);
		
		$arrTemp = array(
			'code_product' => trim(strval($lineData[0])),
			'title_product'=> trim(strval($lineData[2])),						
			);
		
		$arrResult[] = $arrTemp;
		
	}
	
	return $arrResult;
	
	}
/**
 *  copy to tmp csv folder , clean every month
 *  This is the sales Line data , by shop , by date , have real received amount
 *  also able to verify the cash account if export daily....
 * @param string  $csvFileName
 */

public function importSalesLineCsv($csvFileName){
	
	if(!file_exists($csvFileName)){
		return false;
	}
	
	$arrResult = array();
	$arrFinal = array();
	$cot = 0;

	$fl = fopen($csvFileName,"r");
	
	while(($lineData = fgetcsv($fl,0)) != false){
		
		if($cot>0){
			if(in_array(trim($lineData[0]),$arrResult)){
				$key = array_search(trim($lineData[0]),$arrResult);
				$arrResult[$key + 1] += $lineData[11];				
			}
			else{
				$arrResult[] = $lineData[0];
				$arrResult[] = $lineData[11];
				
			}
		}
	
	$cot++;
	}
	
	$arrFinal = array_chunk($arrResult,2);
	
	return $arrFinal;	
}
/**
 * Import R470 Csv File  For OLD APOS
 * @param string $csvFileName
 * @return array  $arrResult = array("0"=>array("code","des","warehouse Stock","total stock"))
 *
 */
public function importStockR470Csv($csvFileName){
	
	if(!file_exists($csvFileName)){
		return false;
	}	
	
	$arrResult = array();
	//$arrFinal = array();
	$cot = 0;
	
	$lineKeys = self::getR470Keys($csvFileName);
	
	
	$fl = fopen($csvFileName,"r");

	while(($lineData = fgetcsv($fl,0)) != false){
		
		if($cot>0){
			
			$arrTmp = array(
					$lineData[0],
					self::cleanProductTitle($lineData[2]),
					$lineData[$lineKeys["key_wh"]],                 //line data 23 is wh stock 
					$lineData[$lineKeys["key_total"]]-$lineData[$lineKeys["key_wh"]]    //line data 25 is total stock include warehouse
					); 
			$arrResult[] = $arrTmp;				
		}
	$cot++;
	}	
	
	fclose($fl);
	
	return $arrResult;
		
}
/**
 * Read R470 New File 
 * @param unknown_type $csvFileName
 */
public function importStockR470NewCsv($csvFileName){

	if(!file_exists($csvFileName)){
		return false;
	}

	$lineKeys = self::getR470Keys($csvFileName);
	
	$arrResult = array();
	//$arrFinal = array();
	$cot = 0;

	$fl = fopen($csvFileName,"r");

	while(($lineData = fgetcsv($fl,0)) != false){

		if($cot>0){
				
			$arrTmp = array(
					$lineData[0],
					self::cleanProductTitle($lineData[2]),
					$lineData[$lineKeys["key_total"]],
			);
			$arrResult[] = $arrTmp;
		}
		$cot++;
	}
	fclose($fl);
	
	return $arrResult;

}

public function cleanProductTitle($productTitle){
	
	$productTitle = str_replace("**", "", $productTitle);
	$productTitle = str_replace("#B", "", $productTitle);
	$productTitle = str_replace("#E", "", $productTitle);
	$productTitle = str_replace("#C", "", $productTitle);
	$productTitle = trim($productTitle);
	
	return $productTitle;
}
public function getR470Keys($csvFileName){
	
	if(!file_exists($csvFileName)){
		return false;
	}
	
	$arrResult = array();
	
	$fl = fopen($csvFileName,"r");
	
	$line = fgets($fl);
	
	$lineData = explode(",",$line);
	
	foreach($lineData as $key => $v){
		if(substr(trim($v),0,2) =="WH"){
			$arrResult["key_wh"] = $key;
		}
		if(substr(trim($v),0,5) =="Total"){
			$arrResult["key_total"] = $key;
		}			
	}
	
	fclose($fl);
	
	return $arrResult;
	
		
}

public function  calSalesLastWeek($csvFileName,$shopHead){

	if(!file_exists($csvFileName)){
		return false;
	}
	
	$cot = 0;
	$arrResult = array();
	$fl = fopen($csvFileName,"r");
	
	
	while(($lineData = fgetcsv($fl,0)) != false){

		if($cot >0 && $lineData[3]==$shopHead && trim($lineData[0]) !="CN" && trim($lineData[0]) !="PHONEREPAIR" && trim($lineData[0]) !="INSTALLATION" && trim($lineData[0]) !="PARTSSALES")
		{

			$key = array_search((string)trim($lineData[0]),$arrResult,true);

			if($key!==false){

				$arrResult[$key + 3] += (int) $lineData[11];
			}
			if($key===false){
				
				$arrResult[] =  (string)trim($lineData[0]);
				$arrResult[] =  (string)trim($lineData[2]);
				$arrResult[] =  $lineData[3];
				$arrResult[] =  (int) $lineData[11];
			}

		}
		$cot ++;
	}

	fclose($fl);
	return array_chunk($arrResult,4);
	
}
public function findDailySaleWeek($csvFileName,$shopHead,$dateBegin){
	$dateEnd = Model_DatetimeHelper::adjustDays("add",$dateBegin,6);
	$dateArr = Model_DatetimeHelper::createDateArray($dateBegin, $dateEnd,".");
	$cot = 0;
	$arrRes = array_fill(0,7,0);
	//var_dump($dateArr);
	
	$fl = fopen($csvFileName,"r");
	while(($lineData = fgetcsv($fl,0)) != false){
		if($cot >0 && trim($lineData[5]) == $shopHead){
			$tmpDate = substr(trim($lineData[9]),0,10);
			$key = array_search($tmpDate,$dateArr);
			$arrRes[$key] += $lineData[17];		
		}
		$cot++;
	}
	
	return $arrRes;
}
public function findDiscountLastWeek($csvFileName,$shopHead,$arrSalesMan){
	
	if(!file_exists($csvFileName)){
		return false;
	}
	
	$cot = 0;
	$arrResult = array();
	$fl = fopen($csvFileName,"r");
	
	$arrInv = array();
	
	while(($lineData = fgetcsv($fl,0)) != false){
	
		if(stripos($lineData[10],"faulty")=== false){
		if($cot >0 && ($lineData[15] >0 || $lineData[16] >0) && $lineData[5] == $shopHead){	
		// reach 10%
		if(($lineData[15] >0 && $lineData[15]/($lineData[15] + $lineData[14])> 0.1099) || ($lineData[16] >0 && $lineData[16]/$lineData[14] > 0.1099) )	
		$arrInv[] = (string)trim($lineData[8]);
		$arrInv[] = (string)trim($lineData[5]);	
		
		}	
		}
		$cot ++;
	}
	
	$arrRes = array();
	$cot = 0;
	//echo "INV";
	//var_dump($arrInv);
	fclose($fl);
	$fl2 = fopen($csvFileName,"r");
	while(($lineData2 = fgetcsv($fl2,0)) != false){
			
		if($cot >0 ){
			$tmpArr = array();	
			
			//if($lineData2[8] == "L130504009"){echo "I am here"; var_dump($lineData2);}
			$key = array_search((string)trim($lineData2[8]),$arrInv,true);
			
			if($key!==false){
			if((string)trim($lineData2[5])==$arrInv[$key + 1]){
				 $tmpArr = $lineData2;
				//echo "ADDED";

				$arrkeys = array_search((string)trim($lineData2[8]),$arrSalesMan,true);
				//var_dump($arrkeys);
				//foreach($arrkeys as $value){
					//if($arrSalesMan[$value + 1] == $lineData2[5]){
						$tmpArr[] = $arrSalesMan[$arrkeys + 2];			
					//}
					
				//}			

			$arrRes[] = $tmpArr;
				
			}

			}
			
		}
		$cot++;
	}	
	
	fclose($fl2);
	
	return $arrRes;
	
}


public function findSalesMan($csvFileName,$shopHead){

	if(!file_exists($csvFileName)){
		return false;
	}
	
	$cot = 0;
	$arrResult = array();
	$fl = fopen($csvFileName,"r");	
	
	$arrRes = array();
	
	while(($lineData = fgetcsv($fl,0)) != false){
		
		if($cot > 0 && $lineData[3]== $shopHead ){
			
			$key = array_search((string)trim($lineData[5]),$arrRes,true);
			
			if($key === false){
				$arrRes[] = (string)trim($lineData[5]);
				$arrRes[] = (string)trim($lineData[3]);
				$arrRes[] = (string)trim($lineData[17]);
			}
			else{
				if((string)trim($lineData[3])!=$arrRes[$key + 1]){
					
					$arrRes[] = (string)trim($lineData[5]);
					$arrRes[] = (string)trim($lineData[3]);
					$arrRes[] = (string)trim($lineData[17]);					
					
				}
			}			
		}
		$cot++;
	
	}
	
	return $arrRes;
}

public function getLastWeekStockOld($csvFileName,$shopHead){
	
	$arrMatching = array(
			"ADPC" => 3,
			"BBPC" => 4,
			"BHPC" => 5,
			"BSPC" => 6,
			"BSIC" => 7,
			"CBPC" => 8,
			"CLPC" => 9,
			"EPPC" => 12,			
			"KCPC" => 15,
			"NLPC" => 16,
			"PMPC" => 17,
			"SLIC" => 18,
			"WBPC" => 19,
			"WBIC" => 20,
			"WGIC" => 21,
			"WGPC" => 22,
			"WLIC" => 24,
	);

	if(!file_exists($csvFileName)){
		return false;
	}
	
	$cot = 0;
	$arrResult = array();
	$fl = fopen($csvFileName,"r");
		
	while(($lineData = fgetcsv($fl,0)) != false){
		
		if($cot >0){
			
			$arrResult[]= $lineData[0];
			$arrResult[]= $lineData[$arrMatching[$shopHead]];
			
			//$arrResult[] = $tmpArr;
		}
		$cot ++;
		
	}	
	fclose($fl);
	return $arrResult;
	
}
public function getLastWeekStockNew($csvFileName,$shopHead){
	
	$arrMatching = array(
			"BSXP" => 3,
			"CBPC" => 4,
			"CLIC" => 5,
			"CSIC" => 6,
			"DCIC" => 7,
			"FGIC" => 8,
			"HPIC" => 9,
			"PMIC" => 10,
	);
	if(!file_exists($csvFileName)){
		return false;
	}
	
	$cot = 0;
	$arrResult = array();
	$fl = fopen($csvFileName,"r");
	
	while(($lineData = fgetcsv($fl,0)) != false){
	
		if($cot >0){
				
			$arrResult[]= $lineData[0];
			$arrResult[]= $lineData[$arrMatching[$shopHead]];
				
			//$arrResult[] = $tmpArr;
		}
		$cot ++;
	
	}
	
	return $arrResult;	
		
}



public function exportSalesShop($resultArr,$fileName){

	$objPHPExcel = new PHPExcel();
	
	$styleArray = array(
			'borders' => array(
					'allborders' => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN,
							'color' => array('argb' => 'FF000000'),
					),
			),
	);
	
	
	$objPHPExcel->getProperties()->setCreator("Phone Collection")
	->setLastModifiedBy("Phone Collection")
	->setTitle($fileName)
	->setSubject($fileName)
	->setDescription("Sales Last Week");
	
	$objPHPExcel->getDefaultStyle()
	->getNumberFormat()
	->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1','BARCODE');
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1','PRODUCT DES');
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1','LOCA');
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1','SALES');
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1','ONHAND');

	
	$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->applyFromArray($styleArray);	
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&C'.$fileName);
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setEvenHeader('&C'.$fileName);
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&C Page &P of &N');
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setEvenFooter('&C Page &P of &N');
	foreach($resultArr as $key => $v){
	
		//echo $key;
		$cellKeyA = (string)"A".($key + 2);
		$cellKeyB = (string)"B".($key + 2);
		$cellKeyC = (string)"C".($key + 2);
		$cellKeyD = (string)"D".($key + 2);
		$cellKeyE = (string)"E".($key + 2);
	
		$cellValueA = (string)$v[0];
		$cellValueB = (string)$v[1];
		$cellValueC = (string)$v[2];
		$cellValueD = (string)$v[3];
		$cellValueE = (string)$v[4];
		
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellKeyA,$cellValueA);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellKeyB,$cellValueB);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellKeyC,$cellValueC);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellKeyD,$cellValueD);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellKeyE,$cellValueE);
		
		$objPHPExcel->getActiveSheet()->getCell($cellKeyA)->setValueExplicit($cellValueA, PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->getStyle($cellKeyA)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		
		$objPHPExcel->getActiveSheet()->getStyle($cellKeyA.":".$cellKeyE)->applyFromArray($styleArray);

	}
	
	$objPHPExcel->getActiveSheet()->setTitle('Weekly Sales');
	
	$objPHPExcel->setActiveSheetIndex(0);
	//$rendererName = PHPExcel_Settings::PDF_RENDERER_DOMPDF;
	//$rendererLibrary = 'dompdf';
	//$rendererLibraryPath = $rendererLibrary;
	
	//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	//$objWriter->save(getcwd()."/export/sales/".$fileName.'.xlsx');
	//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF');
	//$objWriter->save(getcwd()."/export/sales/".$fileName.'.pdf');
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save(getcwd()."/export/sales/".$fileName.'.xls');
	
}

public function exportOrderExcel($orderNo,$arrProducts){
	
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="'.$orderNo.'.xlsx"');
	header('Cache-Control: max-age=0');
	
	$objPHPExcel = new PHPExcel();

	$objPHPExcel->getProperties()->setCreator("Phone Collection")
	->setLastModifiedBy("Phone Collection")
	->setTitle($orderNo)
	->setSubject($orderNo)
	->setDescription("Phone Collection Stock Order")
	->setKeywords("order,{$orderNo}")
	->setCategory("order");
	
	$objPHPExcel->getDefaultStyle()
	->getNumberFormat()
	->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
	
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1','BARCODE');
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1','PRODUCT');
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1','QTY');
	
	foreach($arrProducts as $key => $v){
		
		//echo $key;
		$cellKeyA = (string)"A".($key + 2);
		$cellKeyB = (string)"B".($key + 2);
		$cellKeyC = (string)"C".($key + 2);
		
		
		 $cellValueA = (string)$v[0];
		 $cellValueB = (string)$v[1];
		 $cellValueC = (string)$v[2];
		
		
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellKeyA,$cellValueA);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellKeyB,$cellValueB);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellKeyC,$cellValueC);
	$objPHPExcel->getActiveSheet()->getCell($cellKeyA)->setValueExplicit($cellValueA, PHPExcel_Cell_DataType::TYPE_STRING);
	$objPHPExcel->getActiveSheet()->getStyle($cellKeyA)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
	}	
	
	$objPHPExcel->getActiveSheet()->setTitle($orderNo);
	
	$objPHPExcel->setActiveSheetIndex(0);
	
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save('php://output');
	}
	/**
	 *  Read MYOB TXT FILE
	 *    
	 * @param unknown_type $csvFileName
	 */
	public function importStaffFile($csvFileName){
		
		$stDetail = new Model_DbTable_Roster_Stafflogindetail();
		$stInfo = new Model_DbTable_Roster_Staffinfo();
		
		if(!file_exists($csvFileName)){
			return false;
		}
		
		$fl = fopen($csvFileName,"r");
		
		$cot = 0;
		$arrStId = array();
		while(($lineDate = fgetcsv($fl,1024))!=false){			

			$firstName = $lineDate[1];
			$lastName = $lineDate[0];
			$cardID = $lineDate[2];
			$cardSts = $lineDate[3];
			$recordID = $lineDate[4];
			$email = $lineDate[5];
			
			$fullName = $stDetail->formatName($firstName)." ".$stDetail->formatName($lastName);
			
			$stLine = $stDetail->getStaffByName(Model_EncryptHelper::sslPassword($fullName));
			if(!$stLine){
				echo "PeopLe NOT FOUNT :".$fullName."<br />";	
			}
			elseif(count($stLine)>1){
				echo "ERROR PEOPLE WITH SAME NAME, IGNORE:".$fullName."<br />";	
				
			}
			elseif(count($stLine)==1){
				//this is normal
				//echo "Normal<br />";
				
				$cot++;
				$arrStId[]= $stLine[0]["id"];

				$stInfoLine = $stInfo->getStaffinfo($stLine[0]["id"]);
				if(!$stInfoLine){
					$stInfo->addStaffinfo($stLine[0]["id"],trim($firstName),trim($lastName), $cardID, $recordID, $email, $cardSts);	
				}
				else{
					if($stInfoLine["id_record"] != $recordID){
						echo "ERROR PEOPLE Record ID Changed!!!---------------------<br/>";
					}
					
				}
				
				// check id in infor 
				// if found check record id 
				// if change report 
				// ifn not there  add into 
				
			}
			
			
			
		}
		
		echo $cot;
		echo "------------------------------Peopel IN DB NOT FOUND------------------------<br />";
		$staffWholeList = $stDetail->listStaff();
		
		foreach($staffWholeList as $key => $v){
			if(!in_array($v["id"],$arrStId)){
				echo Model_EncryptHelper::getSslPassword($v["n"])."<br/>";
			}
		}
		
		// get txt file 
		// get names
		// match name 
		// names match 
			//get id , remove id , match card see any change 
			// if change report error  
			// no id the card table,  add card and emails 
		// names not match 
			// record name , record card id // status 
			
			
		
		
	}	
	
	public function readStaffFile($csvFileName){
		if(!file_exists($csvFileName)){
			return false;
		}	
		
		$fl = fopen($csvFileName,"r");
		$firstLine = trim(fgets($fl));
		$arrFirstLine = explode("\t",$firstLine);
		
		$keyLastName = array_search("Co./Last Name", $arrFirstLine);
	 	$keyFirstName = array_search("First Name", $arrFirstLine);
	 	$keyCardId = array_search("Card ID", $arrFirstLine);
	 	$recordId = array_search("Record ID", $arrFirstLine);   
	 	
	
		$arrRes = array();
		
		while(($lineDate = fgetcsv($fl,1024,"\t"))!=false){	
			
			$arrRes[] = $lineDate[$recordId];
			$arrRes[] = $lineDate[$keyLastName];
			$arrRes[] = $lineDate[$keyFirstName];
			$arrRes[] = $lineDate[$keyCardId];
		
		}
		
		fclose($fl);
		return $arrRes;
		
	}

	public function readStaffEmail($csvFileName){
		
		if(!file_exists($csvFileName)){
			return false;
		}
		
		$fl = fopen($csvFileName,"r");
		$firstLine = trim(fgets($fl));
		$arrFirstLine = explode("\t",$firstLine);

		$keyLastName = array_search("Co./Last Name", $arrFirstLine);
		$keyFirstName = array_search("First Name", $arrFirstLine);
		$keyCardId = array_search("Card ID", $arrFirstLine);
		$recordId = array_search("Record ID", $arrFirstLine);
		$keyEmail = array_search("Pay Slip Email",$arrFirstLine);
		$keyDob = array_search("Date of Birth",$arrFirstLine);
		

		$arrRes = array();
		
		while(($lineDate = fgetcsv($fl,1024,"\t"))!=false){
				
			$arrRes[] = $lineDate[$recordId];
			$arrRes[] = $lineDate[$keyLastName];
			$arrRes[] = $lineDate[$keyFirstName];
			$arrRes[] = $lineDate[$keyCardId];
			$arrRes[] = $lineDate[$keyEmail];
			$arrRes[] = $lineDate[$keyDob];
		}
		
		fclose($fl);
		return $arrRes;
	}
	
	
	
	public function arr_search($needle,$hays){
		
		if($needle===false || $needle === null || $needle ==="") return false;
		
		foreach($hays as $key => $hay){
			if((string)$needle == (string)$hay){
				echo "N",$needle."|".$key;
				var_dump($hay);
				echo "<br />";
				
				return 	$key;
			}			
		}
		
		return false;
	}

	public function cashOrEftposOld($arrCsv,$shopHead,$date){

		$arrCash = array("ct"=> 0,"amt" => 0);
		$arrEft = array("ct"=> 0,"amt" => 0);
		$arrAe = array("ct"=> 0,"amt" => 0);
		$arrMix = array("ct"=> 0,"amt" => 0);
		$arrExc = array("ct"=> 0,"amt" => 0);
		//$arrRefund = array("ct"=>0,"amt" => 0);
		
		$arrInv = array();
		
		$date = str_replace("-",".",$date);
		foreach($arrCsv as $lineData){
			if($lineData[11]==$shopHead && substr($lineData[17],0,10) == $date){

				if(trim($lineData[19])=="CASH" || trim($lineData[19])=="CASH,CASH") {
					if(in_array($lineData[12],$arrInv)== false){
					$arrCash["ct"] += 1;
					$arrInv[]= $lineData[12];
					
					}
				
					$arrCash["amt"] +=$lineData[6];
				}
				elseif((trim($lineData[19])=="EFTPOS" || trim($lineData[19])=="MASTER" || trim($lineData[19])=="VISA" || trim($lineData[19])=="CHEQUE" || trim($lineData[19])=="EPS")){
				if(in_array($lineData[12],$arrInv)== false){
					$arrEft["ct"] += 1;
					$arrInv[]= $lineData[12];
					
					}
					$arrEft["amt"] +=$lineData[6];
				}
				elseif(trim($lineData[19])=="AE CARD"  ){
				if(in_array($lineData[12],$arrInv)== false){
					$arrAe["ct"] += 1;
					$arrInv[]= $lineData[12];
					
					}
					$arrAe["amt"] +=$lineData[6];
				}
				elseif( strlen(trim($lineData[19]))>0 
						//trim($lineData[19])=="CHEQUE" || trim($lineData[19])=="EFTPOS,CASH" || trim($lineData[19])=="CASH,CASH" || trim($lineData[19])=="CHEQUE,CASH" ||  trim($lineData[19])=="CASH,VISA" ||
						//trim($lineData[19])=="CASH,CHEQUE" || trim($lineData[19])=="EFTPOS,EFTPOS" || trim($lineData[19])=="CASH,EFTPOS" || trim($lineData[19])=="VISA,CASH"
						){
				if(in_array($lineData[12],$arrInv)== false){
					$arrMix["ct"] += 1;
					$arrInv[]= $lineData[12];
					
					}
					$arrMix["amt"] +=$lineData[6];
				}
				else{	
					$arrExc["ct"] += 1;
					$arrExc["amt"] +=$lineData[6];	
				}
				
				
			}
			
		}		
		

		return $arrFinal = array($arrCash,$arrEft,$arrAe,$arrMix,$arrExc);
				
	}	
	
	public function readR490File($csvFileName,$dateBegin,$dateEnd){
		
		
		set_time_limit(0);
		
		date_default_timezone_set('Australia/Melbourne');
		
		if(!file_exists($csvFileName)){
			return false;
		}	
		
		$fl = fopen($csvFileName,"r");
		$cot = 0;
		
		$arrRes = array();
		
		$dBegin = (int) date("U",strtotime($dateBegin));
		$dEnd = (int) date("U",strtotime($dateEnd)) + 86400;
		
		echo "<br/>";
		
		while(($lineData = fgetcsv($fl,1024,","))!= false){
			
			$dateCheck = (int )date("U",strtotime(str_replace(".","-",substr($lineData[17],0,10))));
			
			if($cot >0 && $dateCheck >= $dBegin && $dateCheck <= $dEnd){	
				$arrRes[]= $lineData;
			}
			 $cot++;
			//echo "<br/>";
			//ob_flush();	
		}
		
		fclose($fl);
		return $arrRes;
		
		
	}

	public function readCaseList($csvFileName){
	
		if(!file_exists($csvFileName)){
			return false;
		}
	
		$fl = fopen($csvFileName,"r");
			
		$arrRes = array();
	
		while(($lineData = fgetcsv($fl,20))!= false){
			$arrRes[] = $lineData[0];
		}
	
		fclose($fl);
			
		return $arrRes;
	
	}
	
}
?>