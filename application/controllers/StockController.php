<?php 
/**
 * 
 * @author Norman
 *
 */
class StockController extends Zend_Controller_Action
{
	const KCREATE_CODE = 1;
	const LOCATION_ACTION_ADD = 1;
	const LOCATION_ACTION_REMOVE = 2;
	
	protected static $arrShopMaping;
	
	
    public function init(){
    
    /**
	 *
	 *
	 */    

    	self::$arrShopMaping = unserialize(ARR_APOS_SHOP_MAPPING);
    	
	}
    /**
     * Index Page, this is not only for KA KT , nothing show at the moment 
     */
    public function indexAction(){
	
    	
	/**
	 * KA KT SECTION START
	 */    	
    	
    /**
     * Creaet Ka Kt Notes Notify
     */
	}
	public function whManageNoteAction(){
		//setting up internal layout
		$this->_helper->_layout->setLayout('layout');
		
		$sh = new Model_DbTable_Shoplocation();
		$kStatus = new Model_DbTable_Products_Stock_Transferadjuststatus();
		
		$shopList = $sh->listHead();
		$arrResult = array();
		
		foreach($shopList as $v){			
			$kList = $kStatus->listByShopHeadByDateForWh($v['name_shop_location_head'],Model_DatetimeHelper::getLastWeekMonday(),Model_DatetimeHelper::dateToday());
			$arrResult = array_merge($arrResult,$kList);
		}
			
	$this->view->resultList = $arrResult;
		
	}
	public function kaktNoticeAction(){
		
		$kStatus = new Model_DbTable_Products_Stock_Transferadjuststatus();
		$sh = new Model_DbTable_Shoplocation();
		$shopHead = $this->_getParam("shop");
		$this->view->shopHead = $shopHead;
		
		$passwordRow = $sh->getNameByHead(trim($shopHead));
		$this->view->shoppassword = Model_EncryptHelper::getSslPassword($passwordRow["password_shop"]);
		$dateBegin = Model_DatetimeHelper::getLastWeekMonday();
		$dateEnd = Model_DatetimeHelper::getThisWeekSunday();
		$kList = $kStatus->listByShopHeadByDateForWh($shopHead, $dateBegin, $dateEnd);

		$this->view->kList = $kList;

		if($_POST){
			if($_POST["driver"]=="cwddd"){
				
				$kStatus->updateDelivery($_POST["id_record"]);				 	
			}		
		}
	}
	/**
	 * Check KAKT BY SHOP
	 */
	public function checkShopKaktAction(){
		
		$shopHead = $this->_getParam("shop");
		$kNote = new Model_DbTable_Products_Stock_Transferadjuststatus();
		
		$dateBegin = Model_DatetimeHelper::getLastWeekMonday();
		$dateEnd = Model_DatetimeHelper::getThisWeekSunday();		
		
		$twoWeekBegin = "2013-01-01";
		$twoWeekEnd = Model_DatetimeHelper::adjustDays("sub", $dateBegin,1);
		
		
		$recentList = $kNote->listByShopHeadByDate($shopHead, $dateBegin, $dateEnd);
		
		$oldList = $kNote->listByShopHeadByDate($shopHead, $twoWeekBegin, $twoWeekEnd);
		
		$this->view->shopHead = $shopHead;
		$this->view->recentList = $recentList;
		$this->view->oldList = $oldList;		
	}
	/**
	 * Save  the status Change for Both Warehouse &  Shop
	 */
	public function saveAddNoteAction(){
		
		$knote = new Model_DbTable_Products_Stock_Transferadjuststatus();
		$krecord = new Model_DbTable_Products_Stock_Kaktstatusrecord();
		$sh = new Model_DbTable_Shoplocation();
		$mail = new Model_Emailshandler();	
		$mailBody = "Hi Shop , youshould see the change on the company info page 10 secs later";
		
		if($_POST){
			
			$kNumber = $_POST["note_number"];
			$shopHead = $_POST["shop_head"];
			$staffName = $_POST["staff_name"];
			
			$idKakt = $knote->addStatus($kNumber, $staffName, $shopHead);
			
			$krecord->addRecord($idKakt,self::KCREATE_CODE, $staffName);
			
			$shopEmail =$sh->getShopMailByHead($shopHead);
			
			$mail->sendNormalEmail($shopEmail,"NEW KA/KT CREATED,Please Check:".$kNumber,$mailBody);
			
			
			
			$this->_helper->redirector("wh-manage-note");
			
			
		}
		
	}
	public function saveStatusChangeAction(){

		$knote = new Model_DbTable_Products_Stock_Transferadjuststatus();
		$krecord = new Model_DbTable_Products_Stock_Kaktstatusrecord();
		$sh = new Model_DbTable_Shoplocation();
		$mail = new Model_Emailshandler();
		$redAction = "";
		$para = array();
		
		
		if($_POST){

			//Activate WH @todo send email to shop
			if(isset($_POST["btn_activation"])){
				$knote->activeTheTransfer($_POST["id_note_act"],Model_DatetimeHelper::dateToday(), $_POST["staff_name_act"]);
				$krecord->addRecord($_POST["id_note_act"],99,$_POST["staff_name_act"],$_POST["comm_act"]);
				$redAction = "wh-manage-note";
			}
			//Cancel  WH
			if(isset($_POST["btn_can"])){
				$knote->cancelTheTransfer($_POST["id_note_can"]);
				$krecord->addRecord($_POST["id_note_can"],100,$_POST["staff_name_can"]);	
				$redAction = "wh-manage-note";
			}
			//Edit WH @todo send email to shop
			if(isset($_POST["btn_edit"])){
				$knote->updateStatus($_POST["id_note_edit"],$_POST["status_code_edit"]);
				$krecord->addRecord($_POST["id_note_edit"],$_POST["status_code_edit"],$_POST["staff_name_edit"],$_POST["comment_edit"]);
				$redAction = "wh-manage-note";
			}
			//Only Add Comment Warehouse @todo send email to shop
			if(isset($_POST["btn_comment_wh"])){
				$krecord->addRecord($_POST["id_note_comm_wh"],21,$_POST["staff_name_wh"],$_POST["comm_wh"]);
				$redAction = "wh-manage-note";
			}
			/**
			 * Shop Changes to Save
			 */
			//Shop found problem @todo send email to wh jeffery
			if(isset($_POST["btn_edit_sh"])){
				$knote->updateStatus($_POST["id_note_edit_sh"],4);
				$krecord->addRecord($_POST["id_note_edit_sh"],4,$_POST["staff_name_edit_sh"],$_POST["comment_edit_sh"]);
				$redAction = "check-shop-kakt";
				$para["shop"] =$_POST["shophead_sh"];				
			}			
			//shop found no issue @todo send email to wh jeffery
			if(isset($_POST["btn_wait_act"])){
				
				$knote->updateStatus($_POST["id_note_act_sh"],2);
				$krecord->addRecord($_POST["id_note_act_sh"],2,$_POST["staff_name_act_sh"],$_POST["comm_act_sh"]);
				$redAction = "check-shop-kakt";
				$para["shop"] =$_POST["shophead_sh"];				
				
			}			
			
			//Only Add Comment Shop @todo send email to wh jeffery
			if(isset($_POST["btn_comment_sh"])){
				$krecord->addRecord($_POST["id_note_comm_sh"],31,$_POST["staff_name_sh"],$_POST["comm_sh"]);
				$redAction = "check-shop-kakt";
				$para["shop"] =$_POST["shophead_sh"]; 
			}
			
		}	
			
			
		$this->_helper->redirector($redAction,"stock",null,$para);
			

			
		
		
	}
	public function whImportKaktAction(){

		$knote = new Model_DbTable_Products_Stock_Transferadjuststatus();
		$krecord = new Model_DbTable_Products_Stock_Kaktstatusrecord();
		$sh = new Model_DbTable_Shoplocation();
		$mail = new Model_Emailshandler();
		$mailBody = "Hi Shop , youshould see the change on the company info page 10 secs later";
				
		
		$arrResult = array();
		
		if($_POST){	
			//var_dump($_POST);		
			$list = trim($_POST["list"]);
			$list = trim(substr($list,1)); 
			$arrList = explode("|", $list);
			
			foreach($arrList as $k => $v){

			
				if($k%2 == 1){
					$arrtmp = array();
					
					if(trim($v)!=""){
						
						//echo "Found KA:".trim($v);
						$arrtmp[0] = trim($v);
						
						//echo "For Shop:".$arrList[$k-1]."<br />";
						
						
						
						if(trim($arrList[$k-1])==""){ 
							//echo "ERROR you missing the shop head"."<br />"; 
							$arrtmp[1] = "MISSING SHOPHEAD"; 
							$arrtmp[2] = "NO ACTION";  }					
						else{
							
							//echo "Do the insert, sending mail"."<br />";
							
							$kNumber = trim($v);
							$shopHead = trim($arrList[$k-1]);
							$staffName = trim($_POST["staff_name"]);
								
							$idKakt = $knote->addStatus($kNumber, $staffName, $shopHead);
								
							$krecord->addRecord($idKakt,self::KCREATE_CODE, $staffName,null);
								
							$shopEmail =$sh->getShopMailByHead($shopHead);
								
							//change to shop email
							/**
							 * @todo : change to shop email
							 */
							$mail->sendNormalEmail("eco1@phonecollection.com.au","NEW KA/KT CREATED,Please Check:".$kNumber,$mailBody);
							
							$arrtmp[1] = $arrList[$k-1]; 
							$arrtmp[2] = "INSERTED | EMAILED";
							
						}
					}
					else{
						
						$arrtmp[0] = "NOT FOUND";
						$arrtmp[1] = $arrList[$k-1];
						$arrtmp[2] = "NO ACTION";						
						
						
						//echo "NO KA KT FOUND";
						//echo "For Shop:".$arrList[$k-1]." do nothing <br />";
						
					}
					
				$arrResult[] = $arrtmp;	
										
				}
				
				
				
				
			}
			
		}
		
		$this->view->arrResult = $arrResult;	
	}
	/**
	 * KA KT Interface for driver 
	 */
	public function driverAction(){
		$sh = new Model_DbTable_Shoplocation();
		$this->view->shopList = $sh->listHead();
		
		$name = $this->_getParam("name");
		$kStatus = new Model_DbTable_Products_Stock_Transferadjuststatus();
		
		$newKaKtList = $kStatus->listByStatusCode(1);
		
		$arrShop = array();
		
		foreach($newKaKtList as $key => $v){
			$arrShop[] = array($v["shop_head"],$v["date_delivery"]);
		}
		
		$this->view->stList = $arrShop;
		
		if($_POST){
			$shopName = end($_POST);		
			foreach($newKaKtList as $key => $v){
				if($shopName==$v["shop_head"]){
					$kStatus->updateDelivery($v["id"]);
				}
				
			}		
		
				
		}
		
	}
	
	/**
	 * KA KT SECTION END 
	 */	
	public function getKtDetailAction(){
		//echo  $res = file_get_contents("http://192.168.1.126/caaudit/mini-circle");
		$ktNo = trim($this->_getParam("ktno"));
		$shop = trim($this->_getParam("shop"));
		
		$ktData = self::initKtData($shop);
		d($ktData,self::$arrShopMaping[$shop]);
		$kList = $ktData->listKT($ktNo, self::$arrShopMaping[$shop]); 
		d($kList);
		$ktMirror = new Model_DbTable_Apos_Mirror_Aposktmirror(Zend_Registry::get('db_real'));
		$proDes = new Model_DbTable_Apos_Stock_Productdes(Zend_Registry::get('db_oldapos'));
		$proStock = Model_Aposinit::initProStock($shop);
		
		$ktMirror->deleteKtmirrorByNo($ktNo,$shop);
		foreach($kList as $ktLine){
			$desLine = $proDes->getAposDes(trim($ktLine['SCODE']));
			$stockLine = $proStock->getStockBalance(trim($ktLine['SCODE']),self::$arrShopMaping[$shop]);
			$ktMirror->addAposktmirror($ktNo, trim($ktLine['SCODE']),$desLine['DESCRIPT'],(int)$ktLine['QTY'],Model_DatetimeHelper::dateToday(),$stockLine['BALANCE'],$shop);
		}
	}
	
	public function countKtAction(){
		$token = $this->_getParam("token");
		$ktNo = $this->_getParam("ktno");
		$shop = $this->_getParam("shop");
		
		$url = "/stock/count-kt/ktno/{$ktNo}/token/{$token}/shop/{$shop}";
		if(base64_decode($token)!=$ktNo){
			
			$ktNo = "ERROR OCCUR";
		}
		
		$this->view->ktNo = $ktNo;
		
		$ktMirror = new Model_DbTable_Apos_Mirror_Aposktmirror();
		
		$kList = $ktMirror->listKtByNo($ktNo,$shop);
		
		$this->view->orgkList = $kList;
		$this->view->topLine = "Product";
		
		$arrOrg = array();
		$arrUnCount = array();
		$arrExtra = array();
		$arrShort = array();
		$arrCorrect = array();
		
		if(!$_POST){
			foreach($kList as $k=>$v){
				$arrOrg[] = $v['code_product'];
				$arrOrg[] = $v['des_product'];
				$arrOrg[] = $v['qty'];
				$arrOrg[] = $v['qty_received'];
				$arrOrg[] = $v['qty_onhand'];
			}
		}
		
		if($_POST){
			$arrOrg = unserialize(gzuncompress(base64_decode($_POST['org_list'])));
			//finallize, tell warehouse
			if(isset($_POST['btn_report'])){
				//save to KT different -> add comment
				
			}
			//save the differnce
			if(isset($_POST['btn_cancel'])){
				
				$barCodeInput =  strtoupper(trim($_POST['input_barcode']));
				if($barCodeInput!=""){
				$key = array_search($barCodeInput,$arrOrg,true);
				
				$inputQty = $_POST['input_qty'];
				
				if($inputQty == ""){
					//reset
					$arrOrg[$key + 3] = "";
				}
				if($inputQty > 0){
					$arrOrg[$key + 3] -= $inputQty;
					
				}
				if($inputQty < 0){
					//revise qty
					$arrOrg[$key + 3] -= $inputQty;
				}
				
				
				if($arrOrg[$key + 3] == 0){
					
					
					$arrOrg[$key + 3] ="";
				
					if($arrOrg[$key + 2] == 0){
						//remove it 
			
						$ktMirror->deleteUnExpectedItem($ktNo, $barCodeInput,$shop);
						$this->_redirect($url);
						//unset array
						
					}
				}
				
					
				}
				$this->view->inputBarcode = "";
				$this->view->inputQty = "";
				
			}
			
			//cancel last action
			
			 
			//update Qty,write to database
			if(isset($_POST['btn_input_text']) || (!isset($_POST['btn_cancel']) && !isset($_POST['btn_report']))) {
				$barCodeInput =  strtoupper(trim($_POST['input_barcode']));
				$input =  strtoupper(trim($_POST['input_field']));
				$inputQtyShow = trim($_POST['input_qty_show']);
				
				
				$this->view->inputQty = $inputQtyShow;
				
				
				$key = array_search($input,$arrOrg,true);
				
				if($key !== false){
						$arrOrg[$key + 3] += $inputQtyShow;
						//if it is unexpected item remove it if qty reset to zero
						if($arrOrg[$key + 2] == 0 and $arrOrg[$key + 3] == 0 ){
							
							$ktMirror->deleteUnExpectedItem($ktNo,trim($input),$shop);
							$this->_redirect($url);

						}
						else{
							
							$ktMirror->updateReceivedQty($ktNo, $input, $arrOrg[$key+3],$shop);
						}
						
				}
				else{
					//extra barcode
					$scode = file_get_contents("http://192.168.1.126/products/match-apos-bcode/bcode/".trim($input));
					
					if($scode != ""){
						$input = trim($scode);
						
						$key = array_search($input,$arrOrg,true);
						
						if($key !== false){
							$arrOrg[$key + 3] += $inputQtyShow;
							
							if($arrOrg[$key + 2] == 0 and $arrOrg[$key + 3] == 0 ){
									
								$ktMirror->deleteUnExpectedItem($ktNo,trim($input),$shop);
								$this->_redirect($url);
								
							}
							else{
									
								$ktMirror->updateReceivedQty($ktNo, $input, $arrOrg[$key+3],$shop);
							}
							
							
						}
						else{
							
							$arrOrg[] = trim($input);
							$arrOrg[] = $desProduct = file_get_contents("http://192.168.1.126/products/get-old-apos-product-description/barcode/".trim($input));
							$arrOrg[] = 0;
							$arrOrg[] = $inputQtyShow;
							$arrOrg[] = 0;
							if(trim($input)!=""){
							$ktMirror->addUnExpectedItem($ktNo, $input, $desProduct, $inputQtyShow,$shop);
							}else{
								
								echo '<script language = "javascript">window.alert("Error May  Occur, If you See This More Than 3 Times, Contact Norman!");</script>';
							}
						}
						
					}
					else{
						
						$arrOrg[] = trim($input);
						$arrOrg[] = $desProduct = file_get_contents("http://192.168.1.126/products/get-old-apos-product-description/barcode/".trim($input));
						$arrOrg[] = 0;
						$arrOrg[] = $inputQtyShow;
						$arrOrg[] = 0;
						
						if(trim($input)!=""){
						$ktMirror->addUnExpectedItem($ktNo, $input, $desProduct, $inputQtyShow,$shop);
						}
						else{
							echo '<script language = "javascript">window.alert("Error May  Occur, If you See This More Than 3 Times, Contact Norman!");</script>';
						}
						
					}
					


				}
				
				$this->view->inputBarcode = $input;
								
			}
			
			
		}
		
		//working arrOrg -> base 64 ,serial lize
		$this->view->arrOrg = base64_encode(gzcompress(serialize($arrOrg)));
		$arrOrgChunk = array_chunk($arrOrg,5);
		//working on uncount / extra/ less / correct 
		foreach($arrOrgChunk as $k2 => $v2){
			//uncount 
			if(empty($v2[3])){
				$arrUnCount[] = $v2;
			}
			else{
				//correct
				if($v2[2] == $v2[3]){
					$arrCorrect[] = $v2;
				}
				//Extra
				if($v2[2] < $v2[3]){
					$arrExtra[] = $v2;
				}
				//Less
				if($v2[2] > $v2[3] ){
					$arrShort[] = $v2;
				}				
								
			}
		}
		$this->view->arrUnCount = $arrUnCount;
		$this->view->arrCorrect = $arrCorrect;
		$this->view->arrExtra = $arrExtra;
		$this->view->arrShort = $arrShort;		
	}

	public function initKtData($shop){
	
		$proKtOld =  new Model_DbTable_Apos_Stock_Ktdataold(Zend_Registry::get('db_oldapos'));
		$proKtNew =  new Model_DbTable_Apos_Stock_Ktdatanew(Zend_Registry::get('db_apos'));
	
		$proKt = "";
		if($shop == "BSXP" || $shop == "CBPC" || $shop == "CLIC" || $shop == "CSIC" || $shop == "DCIC" || $shop == "FGIC" || $shop == "HPIC" || $shop == "PMIC" || $shop == "EPPC"){
			$proKt = $proKtNew;
		}
		else{
			$proKt = $proKtOld;
		}
	
		return $proKt;
	
	}
	
	public function addLocationAction(){
		
		$staffId = 0;
		$location = new Model_Products_Location();
		$log = new Model_Products_Locationlog();
		if($_POST){
			$arrBarcodes = explode("\n",$_POST['barcodes']);
			
			foreach($arrBarcodes as $key => $v){
				if(trim($v)!=""){
					$location->addLocation(trim(strtoupper($v)), trim(strtoupper($_POST['location_code'])), $_POST['type_location'], 0, Model_DatetimeHelper::dateToday(), Model_DatetimeHelper::dateToday()." ".Model_DatetimeHelper::timeNow(),$staffId);					
					$log->addLocationlog(trim(strtoupper($v)), trim(strtoupper($_POST['location_code'])), Model_DatetimeHelper::dateToday(), Model_DatetimeHelper::dateToday()." ".Model_DatetimeHelper::timeNow(), $staffId,self::LOCATION_ACTION_ADD);
				}				
			}
			
			//d($arrBarcodes);
			echo "<h1>Location Added</h1>";
		}
		
		$source = $this->_getParam("org");
		
		if($source == "elp"){		
			$this->_redirect("/stock/edit-location-product/location/".$_POST['location_code']);
		}	
		
	}
	
	public function editLocationProductAction(){
		
		if($_POST){
			
			$this->_redirect("/stock/edit-location-product/location/".trim($_POST['code_location']));
		}
		$this->view->location = $location = trim($this->_getParam("location"));
		
		$loc = new Model_Products_Location();
		$log = new Model_Products_Locationlog();		
		
		$pList = $loc->getProductsByLocation($location);
		$arrRes = array();
		
		foreach($pList as $k => $v){
			$tmpArr = $v;
			$tmpArr['des'] =  file_get_contents("http://192.168.1.126/products/get-old-apos-product-description/barcode/".$v['code_product']);
			$tmpArr['sk'] =  file_get_contents("http://192.168.1.126/products/get-current-stock/barcode/".$v['code_product']);
			$arrRes[] = $tmpArr;
		}
		
		$this->view->pList = $arrRes;
		//d($arrRes);
		//
	}
	// this is for app
	public function editLocationProductApiAction(){
			
	}
	public function editProductLocationAction(){
		
		if($_POST){
			$this->_redirect("/stock/edit-product-location/barcode/".trim($_POST['code_product']));
		}
		
		$loc = new Model_Products_Location();
		$locList = array();
		
		$barcode = trim($this->_getParam("barcode"));
		
		$arrPlistLocations = array();
		
		if(trim($barcode)!="" ){				
			
				$locList = $loc->getLocationsByProduct($barcode);
		
				$this->view->codeProduct = $barcode;
				$this->view->desProduct = file_get_contents("http://192.168.1.126/products/get-old-apos-product-description/barcode/".$barcode);
				$this->view->skProduct =  file_get_contents("http://192.168.1.126/products/get-current-stock/barcode/".$barcode);
		// find similr product
		if(strpos($barcode,"-")!==false){
			
			$partBarcode = substr($barcode,0,strrpos($barcode,"-"));
			
			//echo $partBarcode;
			$strPlist = file_get_contents("http://192.168.1.126/products/finder-by-short-code/shortcode/".$partBarcode);
			$arrPlist = unserialize(base64_decode($strPlist));
			
			//d($arrPlist);
			
			$arrSlist = array();
			
			foreach($arrPlist as $key => $v){
					
				$barCode = $v['SCODE'];
				$des = $v['DESCRIPT'];
				$stock = file_get_contents("http://192.168.1.126/products/get-current-stock/barcode/".$barCode);
				$locations = $loc->getLocationsByProduct($barCode);
				
				$arrSlist[] = array($barCode,$des,$stock,$locations);
			}
			$this->view->arrSlist = $arrSlist;
			//d($arrSlist);
		}
		
		}
		
		$this->view->locList = $locList;	
		//Kint::trace();
	}
	
	public function removeProductLocationAction(){
		
		$idStaff = 0;
		$source = $this->_getParam("org");
		$barCode = trim($this->_getParam("barcode"));
		$location = trim($this->_getParam("location"));
		
		$loc = new Model_Products_Location();
		$locLog = new Model_Products_Locationlog();
		$loc->removeProductFromLocation($barCode, $location);
		$locLog->addLocationlog($barCode, $location,Model_DatetimeHelper::dateToday(), Model_DatetimeHelper::dateToday()." ".Model_DatetimeHelper::timeNow(), $idStaff, self::LOCATION_ACTION_REMOVE);
		
		if($source == "epl"){
			
			$this->_redirect("/stock/edit-product-location/barcode/".$barCode);
		}
		if($source == "elp"){
				
			$this->_redirect("/stock/edit-location-product/location/".$location);
		}		
		
	}
	public function addProductLocationAction(){
		
		$idStaff = 0;
		$source = $this->_getParam("org");
		$loc = new Model_Products_Location();
		$locLog = new Model_Products_Locationlog();		
		
		if($_POST){
			$loc->addLocation(trim($_POST['code_product']),trim($_POST['code_location']), $_POST['type_location'],0, Model_DatetimeHelper::dateToday(), Model_DatetimeHelper::dateToday()." ".Model_DatetimeHelper::timeNow(), $idStaff);
			$locLog->addLocationlog(trim($_POST['code_product']),trim($_POST['code_location']),Model_DatetimeHelper::dateToday(), Model_DatetimeHelper::dateToday()." ".Model_DatetimeHelper::timeNow(), $idStaff, self::LOCATION_ACTION_ADD);
			if($source == "epl"){
			
				$this->_redirect("/stock/edit-product-location/barcode/".trim($_POST['code_product']));
			}		
		
		
		}
				
	
	}
	
	public function productWithNoLocationAction(){
		
		$date = $this->_getParam("date_begin");
		
		$order = new Model_DbTable_Order_Weeklyorder();
		$loc = new Model_Products_Location();
		$oList = $order->getOrderByDate($date);
		
		//d($oList);
		$arrNoLoc = array();
		foreach($oList as $list){
			$locRow = $loc->getLocationsByProduct(trim($list['code_product']));
			if(!$locRow){
				$arrNoLoc[$list['code_product']] = $list['des_product'];
			}
		}
		//d($arrNoLoc);
		$this->view->arrNoLoc = $arrNoLoc;
	}
	
}
?>