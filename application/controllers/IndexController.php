<?php 
/**

 */
class IndexController extends Zend_Controller_Action
{
	
	protected $arrTry = array("1","2");
	
    public function init(){
    	
    	
    	//$this->arrTry = array("0","1");
    /**
	 *
	 *
	 */    
	
    	
    	//$registry = Zend_Registry::getInstance();
    	//$this->_em = $registry->entitymanager;
    	
	}

    public function indexAction(){
	
		//echo "shomething";
    	date_default_timezone_set('Australia/Melbourne');
    	if($_GET){

    		$date = $_GET["date"];
    	
    		if(strpos($date,"-")){
    		echo date("U",strtotime($date))."<br />";
    		}
    		elseif(is_numeric($date)){
    		echo date("Y-m-d H:i",$date);	
    		}
    	}
	
		//echo phpinfo();

    
	}
	
	public function controlPanelAction(){
		
		$show = $this->_getParam("show");
		$menu = new Model_DbTable_Menu();
		
		if($show == "fullmenu"){
			$mList = $menu->listAll();
			$this->view->mList = $mList;
		}

	}
	
	public function sendpromotionemailAction(){
		
		$emailAccount = "promotion@lovebargain.com.au";
		$passWord = "mon80ash";
		$newEmail = new Model_Emailshandler();
		$resultMessage = $newEmail->downloadSold($emailAccount,$passWord);
		$emailFileName = $newEmail->analySubject($resultMessage[0]);
		$buyerArr = $newEmail->analyEmail($resultMessage[1]);
		
		echo "The Subject is:".$resultMessage[0]."<br />";
		echo "We find the Buyer is:".$buyerArr['buyerName']." Email is:".$buyerArr['buyerEmail']."<br />";
		echo "we Will use :".$emailFileName."<br />";
		echo "------------------------<br />";
		
		
		$newEmail->sendPromotions($buyerArr['buyerName'],$buyerArr['buyerEmail'],$emailFileName);
		//echo "0000000000000000000000000000000000000000000000<br />";
		//echo "<pre>";
		//var_dump($resultMessage);
		//echo "</pre>";
		}
	public function pinfoAction(){
		
		echo phpinfo();
	}	
		
	public function test2Action(){
		
		//echo base64_encode((Model_EncryptHelper::sslPassword('mvATmFL6')));
		//echo Model_EncryptHelper::sslPassword("xiaoxia chen");
		
		//$mail = new Model_Emailshandler();
		//$mail->sendNormalEmail("kmobileau@gmail.com", "Just Test", "tss");
		
		date_default_timezone_set("Australia/Melbourne");
		
		echo date("Y-m-d H:i:s",1401580800);
		
		
	}
	public function shakehandAction(){
		
	$this->_helper->layout->disableLayout();
	echo 'shake({"ABC":"DEF"});';
	}	
	public function testAction(){
		
		echo $_COOKIE["UserCookie"];
		
		echo Model_EncryptHelper::convertTo36(100);
		echo Model_EncryptHelper::convertTo36(1000);
		

		
		echo Model_DbTable_Pr_Loan::buildBorrowCode(20, 16, 367,2014011509456);
		
		echo date("Y-m-d H:i:s",1358200803
		);
		//dec2hex()
		
		
		/*
		$time_start = microtime(true);
		
		$invPro = new Model_DbTable_Apos_Invoice_Products_Bb(Zend_Registry::get('db_oldapos'));
		$stockbal = new Model_DbTable_Apos_Stock_Balanceold(Zend_Registry::get('db_oldapos'));
		
		
//		echo $invPro->isHotItem('CHACCMUSB2IN1');
		$dateTwoWeekBegin = Model_DatetimeHelper::adjustWeeks("sub",Model_DatetimeHelper::getLastWeekMonday(),1);
		$dateTwoWeekEnd = Model_DatetimeHelper::adjustWeeks("sub",Model_DatetimeHelper::getLastWeekSunday(),1);
		

		$stock = new Model_DbTable_Apos_Stock_Productdes(Zend_Registry::get('db_oldapos'));
		$sList = $stock->getAll();
		
		//var_dump($sList);
		$cot = 0;
		$okCot = 0;
		
		foreach($sList as $key => $v){
			set_time_limit(0);
			
			$barCode = trim($v['SCODE']);
			
			if(substr($v['SCODE'],0,4)!="STA-" && $cot < 4000 ){

					$sales = $invPro->getProductSalesQtyByDates(Model_DatetimeHelper::getLastWeekMonday(),Model_DatetimeHelper::getLastWeekSunday(),trim($v['SCODE']));
					$wk2sales =  $invPro->getProductSalesQtyByDates($dateTwoWeekBegin, $dateTwoWeekEnd,trim($v['SCODE']));
					//$maxSold =  $invPro->maxSoldWeeks($barCode,Model_DatetimeHelper::getLastWeekSunday(),4);
					
					$avgSales = ($sales + $wk2sales) /2;
					 
					$ifHot = $invPro->isHotItem($barCode);
					$pickup = 0;
					$onhands = $stockbal->getStockBalance($barCode,"BB");
					$onhand = $onhands['BALANCE'];
					if($ifHot == "HOT" ){
						$pickup = $onhand - ceil($avgSales*1.5) ;
					}
					if($ifHot == "NORMAL" ){
						$pickup = $onhand - floor($avgSales*1.5);
					}
					if($ifHot == "COLD" ){
						$pickup =  $onhand - ceil($avgSales);
					}
					
					$cot++;
					if($pickup > 0 ){
						
						echo $cot.",".$v['SCODE'].",".$v["DESCRIPT"].",".$onhand.",".$ifHot,",".$pickup."<br />";
						
					}
					else{
						
						//echo "OK";
						$okCot++;
					}
					
					
				
			}			
		}
		
		echo $okCot;
		
		*/
		/*
		$fl = fopen(".csv","r");
		$cot =0;
		echo "<br/><br/>";
		
		while(($lineData = fgetcsv($fl))!=false){
			
			//var_dump($lineData);
			if($cot >0){
				$barCode = trim($lineData[0]);
				
				$sales = $lineData[3];
				$wk2Sales = $invPro->getProductSalesQtyByDates($dateTwoWeekBegin, $dateTwoWeekEnd, $barCode);
				
				$sales = ($sales + $wk2Sales)/2;
				
				$max4Week = $invPro->maxSoldWeeks($barCode,Model_DatetimeHelper::getLastWeekSunday(),4);
				
				$onhand = $lineData[4];
				
				$wh = $lineData[5];
				$ifHot = $invPro->isHotItem($barCode);
				$pickup = 0;
				if($ifHot == "HOT" ){
					$pickup = ceil($sales*1.5) - $onhand;	
				}
				if($ifHot == "NORMAL" ){
					$pickup = floor($sales*1.5) - $onhand;
				}
				if($ifHot == "COLD" ){
					$pickup = ceil($sales - $onhand);
				}				
				if($pickup <0 ){
					$pickup =0;
				}
				$strPossible = "";
				
				
				if(($pickup + $onhand) < $max4Week){
					
					$strPossible = $max4Week;
				}
				
				echo $lineData[0].",".$lineData[1].",".$lineData[2].",".$lineData[3].",".$lineData[4].",".$lineData[5].",".$ifHot.",".$sales.",".$pickup.",".$strPossible."<br />";
				
			}
			$cot++;
			
		}
		
		*/
		$time_end = microtime(true);
		
		$time = $time_end - $time_start;
		
		echo "Time:".$time;
		
		/*
		
		echo date("U", strtotime("23:59"));
		
		$arr = array("a","b","c"=> 3);
		echo serialize($arr);
		
		
		
		
		$cf = new Model_Cbfilehandler();
		$arrCFile = $cf->readFile('MRCHRPT20131020.csv');
		echo "<pre>";
		var_dump($arrCFile);
		echo "</pre>";
		$invOld = new Model_DbTable_Apos_Invoice_Bb(Zend_Registry::get('db_oldapos'));
		
		$arrInvoice = $invOld->getInvoicesByDate('2013-10-18 00:00:00');
		
		
		
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
		
		
		//var_dump($arrAposInv);
		$arrMatch = array();
		
		foreach($arrCFile[1]['BBPC'] as $key => $v){
			$arrMatch[] = substr(trim($v[4]),0,5);
			$arrMatch[] = round($v[11],2);
		}
		
		echo "<pre>";
		var_dump($arrAposInv,$arrMatch);
		echo "</pre>";
		
		foreach($arrMatch as $key2 => $v2){
			if( $key2 %2 == 1){
				
				//$find = array_search($v2, $arrAposInv,true);
				
				$findKeys = array_keys($arrAposInv,$v2,true);
				
				if($findKeys!==false){
					//check time 
				foreach($findKeys as $find){	
					$intMatchTime = date("U",strtotime($arrMatch[ $key2 -1]));
					$intAposTime = date("U",strtotime($arrAposInv[ $find -1]));

					if(abs($intMatchTime - $intAposTime) < 121 ){
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
		
		//$arrAposInv = array_filter($arrAposInv, 'strlen');
		//$arrMatch = array_filter($arrMatch, 'strlen');
		echo "<pre>";
		var_dump($arrAposInv,$arrMatch);
		echo "</pre>";		
		*/
		//$invPro = new Model_DbTable_Apos_Invoice_Products_Ep(Zend_Registry::get('db_oldapos'));
		
		
		//$res = Model_DbTable_Products_Sales::eightWeekNewProduct("2013-09-02","2013-10-27");
		//var_dump($res);
		
		//$tOld = new Model_DbTable_Apos_Stock_Ktdataold(Zend_Registry::get('db_oldapos'));
		//$tList = $tOld->thisWeekTransfer('LC990APIPH4-4');
		//var_dump($tList);
		//$mail = new Model_Emailshandler();
		
		//$mail->sendNormalEmail("eco1@phonecollection.com.au","test","test");
		//$mail->sendNormalEmail("jeffrey.zhang@phonecollection.com.au","test","test");
		//date_default_timezone_set('Australia/Melbourne');
		
		//$attn = new Model_DbTable_Roster_Attnrecord();
		//$status = $attn->createStatusOffDuty();
		//$time = date("U",strtotime('2013-09-09 17:30:00'));
		//$ip = '0.0.0.0';
		
		//$attn->addRecord($staffId, $time, $ip, $shopHead, $status);
		/*
		$arrAttn = array(
				'3' => 'BSPC',
				'16' => 'BSPC',
				'26' => 'BSPC',
				'107' => 'BSPC',
				'10' => 'BSPC',
				'9' => 'CBPC',
				'2' => 'FGIC',
				'150' => 'FGIC',
				'151' => 'SLIC',
				'20' => 'PMIC',
				'125' => 'PMPC',
				'36' => 'EPPC',
				'97' => 'EPPC',
				'6' => 'NLPC',
				'162' => 'WGIC',
				'140' => 'WGPC',
				'165' => 'KCPC',
				'115' =>'DCIC',
				'118' => 'CSIC',
				'4' => 'CSIC',
				'141' => 'BBPC',
				'130' => 'BHPC',
				'125' =>'WBIC',
				'98' =>'PMPC',
				'133' => 'HPIC'
				);
		
		$arrAttn = array(
				'16' => 'BSIC',
				'26' => 'BSIC',
				'107' => 'BSIC',
				'10' => 'BSXP'
		);
		
		*/
		
		
		//foreach($arrAttn as $id => $shop){
			
		//	$attn->addRecord($id, $time, $ip, Model_EncryptHelper::sslPassword($shop), $status);
			
		//}
		
		//$rPro = new Model_DbTable_Pr_Prproducts();
		//$rPro->getProductDes($productCode)
		
		//echo $this->arrTry[0];
		//echo Model_DatetimeHelper::adjustDays("sub",date("Y-m-d"),170);
		
		//$testEntity = new Model_DbTable_Test;
		
		//$line= $testEntity->findOneBy(array('name'=>'Zaphod Beeblebrox'));
		//var_dump($line);
		
		//$testEntity->setName('Zaphod Beeblebrox');
		//$this->_em->persist($testEntity);
		//$this->_em->flush();
		
		//echo phpinfo();
		//$date = "2013-08-01";
		
		//echo Model_EncryptHelper::sslPassword("guan lin");
		//echo Model_DatetimeHelper::adjustMonths("sub", $date,1);
		
		//$fh = new Model_Fileshandler();
		//$fh->write2Page();
		
		//echo Model_EncryptHelper::sslPassword("jing jiang");
		
		//$dbcb = new Model_DbTable_Apos_Invoicesnewcb(Zend_Registry::get('db_apos'));
		
		//$rows = $dbcb->getInvoicesByDate('2013-05-21');
		
		//var_dump($rows);
		
		/*
		date_default_timezone_set('Australia/Melbourne');
		echo date("Y-m-d H:i:s",1368412200);
		echo date("U",strtotime("2013-04-29"));
		echo "<br />";
		echo date("U",strtotime("2013-05-01"));
		$var = null - null;
		var_dump($var);
		*/
		/*
		$shopHead = new Model_DbTable_Shoplocation();
		$shopHead->setPassword(1,"CC6yja");
		$shopHead->setPassword(2,"WhQTD2");
		$shopHead->setPassword(3,"2RWjHf");
		$shopHead->setPassword(4,"vDD7cJ");
		$shopHead->setPassword(5,"vFd2aL");
		$shopHead->setPassword(6,"XKEbF4");
		$shopHead->setPassword(7,"qLEFB8");
		$shopHead->setPassword(8,"D5txUC");
		$shopHead->setPassword(9,"GkafT2");
		$shopHead->setPassword(10,"7g3JFg");
		$shopHead->setPassword(11,"123456");
		$shopHead->setPassword(12,"MtfMp4");
		$shopHead->setPassword(13,"xeVwC9");
		$shopHead->setPassword(14,"Q6f8jq");
		$shopHead->setPassword(15,"jj9LEp");
		$shopHead->setPassword(16,"NZcnV5");
		$shopHead->setPassword(17,"H5UdWk");
		$shopHead->setPassword(18,"hRnKq8");
		$shopHead->setPassword(19,"nRHaf6");
		$shopHead->setPassword(20,"auEjB2");
		$shopHead->setPassword(22,"h94773");
		$shopHead->setPassword(23,"GaYjG5");
		$shopHead->setPassword(24,"wTdzz7");
		$shopHead->setPassword(25,"FjJYC3");
		$shopHead->setPassword(26,"AKpKc2");
		$shopHead->setPassword(27,"QN72ST");
		$shopHead->setPassword(29,"N7LZY0i");
		*/
		/*
		date_default_timezone_set('Australia/Melbourne');
		
		$ts = new Model_DbTable_Roster_Timesheet();
		$tList = $ts->listAll();
		
		foreach($tList as $key =>$v){


			echo "Begin:".date("Y-m-d H:i",$v["shift_begin"]);
			echo " | End:".date("Y-m-d H:i",$v["shift_end"])."<br />";
			
			
		}
		*/
		
		//$fl = new Model_Fileshandler();
	//$fl->exportExcel();
		
	//echo 	$passwordAfters = Model_EncryptHelper::hashsha("Hereigns7");
	
	//var_dump(strtotime("17:30:00"));
	//var_dump(strtotime("18:00:00"));
		
	//$a =strtotime("17:30:00");
	//$b =strtotime("18:00:00"); 
		//date_default_timezone_set('Australia/Melbourne');
		//$dateToday = Model_DatetimeHelper::dateToday();
	
	//echo date("U",strtotime($dateToday));
	
	//echo date("U",strtotime("2013-04-10"));
	
	//$b = $_GET["d"];
	
	//echo $a = strtotime($b);
	
	//echo "<br />";
	
	
	
	
	
	
		
		/*		
	$ad = new Model_DbTable_Roster_Attnrecord();
	echo $num1 =$ad->createStatusOnDuty();
	echo "<br />";
	echo $ad->getLastOnOffDuty($num1);
	echo "<br />";
	echo $num1 =$ad->createStatusOffDuty();
	echo "<br />";
	echo $ad->getLastOnOffDuty($num1);
	echo "<br />";
	echo $num1 =$ad->createStatusOnDuty();
	echo "<br />";
	echo $ad->getLastOnOffDuty($num1);
	echo "<br />";
	echo $num1 =$ad->createStatusOffDuty();
	echo "<br />";
	echo $ad->getLastOnOffDuty($num1);
	echo "<br />";
	echo $ad->getLastOnOffDuty($num1);
	echo "<br />";
	echo $num1 =$ad->createStatusOnDuty();
	echo "<br />";
	echo $ad->getLastOnOffDuty($num1);
	echo "<br />";
	echo $num1 =$ad->createStatusOffDuty();
	echo "<br />";
	echo $ad->getLastOnOffDuty($num1);
	echo "<br />";
	echo $num1 =$ad->createStatusOnDuty();
	echo "<br />";
	echo $ad->getLastOnOffDuty($num1);
	echo "<br />";
	echo $num1 =$ad->createStatusOffDuty();
	echo "<br />";
	echo $ad->getLastOnOffDuty($num1);
	echo "<br />";			

		date_default_timezone_set('Australia/Melbourne');
		echo $timeNow = time();
		echo "<br />";
		echo date("Y-m-d H:i:s T",$timeNow);
		
		date_default_timezone_set('Australia/Adelaide');
		echo $timeNow = time();
		echo "<br />";
		echo date("r",strtotime("2013-02-01"));	

		$shopHead = new Model_DbTable_Shoplocation();
		
		echo "<br />";
		echo $shopHead->sslPassword("1");
		echo "<br />".strlen($shopHead->sslPassword("1"))."<br />";
		echo $shopHead->sslPassword("a");
		echo "<br />".strlen($shopHead->sslPassword("a"))."<br />";
		echo $shopHead->sslPassword("12s");
		echo "<br />".strlen($shopHead->sslPassword("12s"))."<br />";
		echo $shopHead->sslPassword("Norman Dong");
		echo "<br />".strlen($shopHead->sslPassword("Norman Dong"))."<br />";
		echo $shopHead->sslPassword("12345678901234512345678901234512");
		echo "<br />".strlen($shopHead->sslPassword("12345678901234512345678901234512"))."<br />";
		echo $shopHead->sslPassword("normandong");
		echo "<br />".strlen($shopHead->sslPassword("normandong"))."<br />";
		

		$caClose = new Model_DbTable_Cashaccountclosing();
		
		date_default_timezone_set('Australia/Melbourne');
		$dateToday = date("Y-m-d");
		
		$result = $caClose->getLastBusDayClosing("BSPC", $dateToday);
		$result2 = $caClose->getYesterdayClosing("BSPC");
		
		var_dump($result);
		var_dump($result2);
		
		//echo phpinfo();
	
		$pdf = new Zend_Pdf();
		$page = new Zend_Pdf_Page(Zend_Pdf_page::SIZE_A4);
		
		$pageHeight = $page->getHeight();
		$pageWidth = $page->getWidth();
		
		echo 'Height = '.$pageHeight.'\n';
		echo 'Width = '.$pageWidth.'\n';
		
		
		
		
		$page->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_COURIER),12);
		$page->drawText("Tessafasssssssssssssssssssssssssdt",10,10);
		
		
		
		$pdf->pages[0] = ($page);
		$pdf->save("chomp2.pdf");
		echo $_SERVER['REMOTE_ADDR'];
		
	
		$pwdSource = "CC6yja";
		$iv="thisisnormancode";
		$pass="197979norman";
		$method = 'aes-128-cbc';
		echo $str = openssl_encrypt($pwdSource, $method, $pass,false,$iv);
		echo $pwdResult = openssl_decrypt($str, $method, $pass,false,$iv);
		
		
		
		
		//phpinfo();	
	
		//echo $_SERVER['HTTP_USER_AGENT'] . "\n\n";
		
		$browser = get_browser(null, true);
		//echo "<pre>";
		//var_dump($browser);
		//echo "</pre>";
				
		if($browser['platform_description'] =="Windows 7" || $browser['platform_description'] =="Win Xp"){
			
			echo "YES";
		}		
		else{
			echo "NO";
		}

		$caClosing = new Model_DbTable_Cashaccountclosing();
		$cr=$caClosing->getActiveClosingByDate("BSIC","2013-02-13");
		var_dump($cr);
		
		/*
		$shopHead = new Model_DbTable_Shoplocation();
		$shopHead->setToken(1);
		$shopHead->setToken(2);
		$shopHead->setToken(3);
		$shopHead->setToken(4);
		$shopHead->setToken(5);
		$shopHead->setToken(6);
		$shopHead->setToken(7);
		$shopHead->setToken(8);
		$shopHead->setToken(9);
		$shopHead->setToken(10);
		$shopHead->setToken(11);
		$shopHead->setToken(12);
		$shopHead->setToken(13);
		$shopHead->setToken(14);
		$shopHead->setToken(15);
		$shopHead->setToken(16);
		$shopHead->setToken(17);
		$shopHead->setToken(18);
		$shopHead->setToken(19);
		$shopHead->setToken(20);
		//$shopHead->setToken(21);
		$shopHead->setToken(22);
		$shopHead->setToken(23);
		$shopHead->setToken(24);
		$shopHead->setToken(25);
		$shopHead->setToken(26);
		$shopHead->setToken(27);		
			
		$shopHead = new Model_DbTable_Shoplocation();
		$shopHead->setPassword(1,"CC6yja");
		$shopHead->setPassword(2,"WhQTDB");
		$shopHead->setPassword(3,"2RWjHf");
		$shopHead->setPassword(4,"vDD7cJ");
		$shopHead->setPassword(5,"vFd2aL");
		$shopHead->setPassword(6,"XKEbFY");
		$shopHead->setPassword(7,"qLEFBH");
		$shopHead->setPassword(8,"D5txUC");
		$shopHead->setPassword(9,"GkafTV");
		$shopHead->setPassword(10,"7g3JFg");
		$shopHead->setPassword(11,"123456");
		$shopHead->setPassword(12,"MtfMpX");
		$shopHead->setPassword(13,"xeVwCJ");
		$shopHead->setPassword(14,"Q6f8jq");
		$shopHead->setPassword(15,"jj9LEp");
		$shopHead->setPassword(16,"NZcnVu");
		$shopHead->setPassword(17,"H5UdWk");
		$shopHead->setPassword(18,"hRnKqk");
		$shopHead->setPassword(19,"nRHafn");
		$shopHead->setPassword(20,"auEjB2");
		$shopHead->setPassword(22,"h94773");
		$shopHead->setPassword(23,"GaYjGe");
		$shopHead->setPassword(24,"wTdzzp");
		$shopHead->setPassword(25,"FjJYCu");
		$shopHead->setPassword(26,"AKpKcH");
		$shopHead->setPassword(27,"QN72ST");
		N7LZY0i
		$shopHead = new Model_DbTable_Shoplocation();
		$shopHead->setPassword(29,"N7LZY0i");
		$shopHead->setToken(29);
		*/	
	}

			
}
?>