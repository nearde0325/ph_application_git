<?php 
/**

 */
class BarcodeController extends Zend_Controller_Action
{
    public function indexAction(){	
		//echo "shomething";	
	}
	public function manageManuAction(){
		
		$manu = new Model_DbTable_Barcodemanu();

		
		
		if($_POST){
			
			if(isset($_POST['sub_2']) and $_POST['sub_2']=="Add"){
				
				$manu->addManu(trim($_POST['id_add_manu']),trim($_POST['code_add_manu']),trim($_POST['des_add_manu']));
				
				
				echo "New Line ADD";
						
			}
			if(isset($_POST['sub_1']) and $_POST['sub_1']=="Modify"){
			
				
				
				foreach($_POST['row'] as $key => $value){
					
					//var_dump($value);
					
					$manu->updateManu($value["id"],$value["code"],$value["des"]);
				}				
				
				echo "Modification Saved, F5 Your Page!!";
			}			
				
		}

		$this->view->list = $manu->listManu();		
		
	}
	public function manageModelAction(){
		
		$manu = new Model_DbTable_Barcodemanu();
		$model = new Model_DbTable_Barcodemodel();
		
		$this->view->manulist = $manu->listManu();		
		$idManu = 1;
		
		if($_POST){
			
			//choose Manufacure	
			if(isset($_POST['sub_go']) and $_POST['sub_go']=="GO"){	

				$idManu = $_POST['id_manu_model'];
			}
			//modify
			if(isset($_POST['sub_1']) and $_POST['sub_1']=="Modify"){
					
			
			
				foreach($_POST['row'] as $key => $value){
						
					//var_dump($value);
						
					//$manu->updateManu($value["id"],$value["code"],$value["des"]);
					$model->updateModel($value["id"],$_POST["id_manu"],$value["code"],$value["des"]);
				}
			
			
				echo "Modification Saved, F5 Your Page!!";
			}
							
			
			//add
			
			if(isset($_POST['sub_2']) and $_POST['sub_2']=="Add"){
			
				//$manu->addManu(trim($_POST['id_add_manu']),trim($_POST['code_add_manu']),trim($_POST['des_add_manu']));
				$model->addmodel($_POST["id_add_manu"],trim($_POST['code_add_model']), trim($_POST['des_add_model']));
			
				echo "New Line ADD";
			
			}	
			
			
		}	

		
		//list Model By same Manufacture 
		$this->view->idManu = $idManu;
		$this->view->modellist = $model->listModelByManuID($idManu);
		
		
		
	}
	
	public function manageProductSubTypeAction(){
		
		$mainType = new Model_DbTable_Barcodeproducttype();
		$subType = new Model_DbTable_Barcodeproductsubtype();
		
		$this->view->mainlist = $mainType->listType();
		$idMainType = 1;
		
		if($_POST){
			//choose Main Product Type
			if(isset($_POST['sub_go']) and $_POST['sub_go']=="GO"){
			
				$idMainType = $_POST['id_main_type'];
			}			
			//Modify
			
			if(isset($_POST['sub_1']) and $_POST['sub_1']=="Modify"){
					
					
					
				foreach($_POST['row'] as $key => $value){
			
					//var_dump($value);
			
					//$manu->updateManu($value["id"],$value["code"],$value["des"]);
					$subType->updateSubType($value["id"],$_POST["id_main"],$value["code"],$value["des"]);
					//$model->updateModel($value["id"],$_POST["id_manu"],$value["code"],$value["des"]);
				}
					
					
				echo "Modification Saved, F5 Your Page!!";
			}			
			
			
			//Add
			
			if(isset($_POST['sub_2']) and $_POST['sub_2']=="Add"){
					
				$subType->addSubType(trim($_POST['id_add_main']),trim($_POST['code_add_sub_type']), trim($_POST['des_add_sub_type']));	
				echo "New Line ADD";
				$idMainType = trim($_POST['id_add_main']);
			}			
			
		}
		
		//
		
		$this->view->idMainType = $idMainType;
		$this->view->sublist = $subType->listSubTypeByMainTypeID($idMainType);
		
		
		
		
	}
	
	public function manageSpecialcodeAction(){
		
		$mainType = new Model_DbTable_Barcodeproducttype();
		$spCode = new Model_DbTable_Barcode_Specialcode();
		
		$this->view->mainlist = $mainType->listType();
		
		//get special code 
		$idMainType = 2;
		
		if($_POST){
				
			//choose Product Type
			if(isset($_POST['sub_go']) and $_POST['sub_go']=="GO"){
		
				$idMainType = $_POST['id_maintype_sp_code'];
			}
			//modify
			if(isset($_POST['sub_1']) and $_POST['sub_1']=="Modify"){
					
					
					
				foreach($_POST['row'] as $key => $value){
		
					//var_dump($value);
		

					$spCode->updateSpecialCode($value["id"],$_POST["id_maintype"],$value["code"],$value["des"]);
		
				}
					
					
				echo "Modification Saved, F5 Your Page!!";
			}
				
				
			//add
				
			if(isset($_POST['sub_2']) and $_POST['sub_2']=="Add"){
					
				
				$spCode->addSpecialCode($_POST["id_add_main"],trim($_POST['code_add_sp_code']), trim($_POST['des_add_sp_code']));
	
					
				echo "New Line ADD";
				$idMainType = trim($_POST['id_add_main']);
					
			}
				
				
		}		
		
		$this->view->idMain = $idMainType;
		$this->view->spcodelist = $spCode->listSpecialCodeByProductTypeID($idMainType);
		
	
	}
	
	
	public function createBarcodeCaseAction(){
		
		
		//Initial case type 
		$mainType = 1;
		$subType = new Model_DbTable_Barcodeproductsubtype();		
		//manu
		$manu = new Model_DbTable_Barcodemanu();	
		//model 
		$model = new Model_DbTable_Barcodemodel();
		//color 
		$color = new Model_DbTable_Barcodecolorcodes();
		
		
		$modelList = array(array('code_model'=>"NA",'des_model'=>"NotAvailable"));
		
		
		
		// Initial Selections 
		
		$this->view->subtypeList = $subType->listSubTypeByMainTypeID(1);
		$this->view->manuList = $manu->listManu();
		$this->view->colorList = $color->listAllColors();
		
		//remember the choice 
		
		$this->view->subtypeChoice = "";
		$this->view->manuChoice = "";
		$this->view->colorChoice = "";
		$this->view->modelChoice = "";
		//barcode Des
		$this->view->barcodeCode= "";
		$this->view->barcodeDes ="";
		//initial array
		
		$arrBarcodeCode = array();
		$arrBarcodeDes = array();
		
		
		
		if($_POST){
			
			// create Model
			if(isset($_POST['sub_model']) and $_POST['sub_model']=="Model"){
				
				//Create Model List 
				$modelList = $model->listModelByManuCode(trim($_POST['manu']));
				//Remember the Choice
				$this->view->subtypeChoice = trim($_POST['sub_type']);
				$this->view->manuChoice = trim($_POST['manu']);
				if(isset($_POST['color'])){
				$this->view->colorChoice = $_POST['color'];
				}	
			}
			
			// Create Barcode 
			if(isset($_POST['sub_result']) and $_POST['sub_result']=="Result"){
				
				$modelList = $model->listModelByManuCode(trim($_POST['manu']));
				
				$this->view->subtypeChoice = trim($_POST['sub_type']);
				$this->view->manuChoice = trim($_POST['manu']);
				$this->view->colorChoice = $_POST['color'];
				$this->view->modelChoice = trim($_POST['model']);
				
				//create Barcode 
				
				$subtypeDes = $subType->getSubtypeDesByCode(trim($_POST['sub_type']));
				$manuDes = $manu->getManuDesByCode(trim($_POST['manu']));
				$modelDes = $model->getModelDesByCode(trim($_POST['model']));
				
				foreach($_POST['color'] as $key => $value){
					
					$colorDes = $color->getColorDes(trim($value));
					
					$arrBarcodeCode[] = trim($_POST['sub_type']).trim($_POST['manu']).trim($_POST['model'])."-".$value; 
					$arrBarcodeDes[] = "**".$subtypeDes." ".$manuDes." ".$modelDes." ".$colorDes; 
				}
				
				$this->view->barcodeCode = $arrBarcodeCode;
				
				$this->view->barcodeDes = $arrBarcodeDes;
				
							
			}
			
			
		}
		//load model After Post 
		$this->view->modelList = $modelList;
		
		
		
	} 
	
	public function createBarcodeDesignCaseAction(){
		
		//Initial case type 
		$mainType = 10;
		$subType = new Model_DbTable_Barcodeproductsubtype();		
		//manu
		$manu = new Model_DbTable_Barcodemanu();	
		//model 
		$model = new Model_DbTable_Barcodemodel();
		//color 
		$color = new Model_DbTable_Barcodecolorcodes();
		$counters = new Model_DbTable_Barcode_Counter();
		
		$modelList = array(array('code_model'=>"NA",'des_model'=>"NotAvailable"));
		$number = "N/A";
		
		// Initial Selections 
		
		$this->view->subtypeList = $subType->listSubTypeByMainTypeID($mainType);
		$this->view->manuList = $manu->listManu();
		$this->view->colorList = $color->listAllColors();
		
		//remember the choice 
		
		$this->view->subtypeChoice = "";
		$this->view->manuChoice = "";
		$this->view->colorChoice = "";
		$this->view->modelChoice = "";
		//barcode Des
		$this->view->barcodeCode= "";
		$this->view->barcodeDes ="";
		//inital barcode array
		
		$arrBarcodeCode = array();
		$arrBarcodeDes = array();
		
		
		
		if($_POST){
			
			$modelList = $model->listModelByManuCode(trim($_POST['manu']));
			$this->view->subtypeChoice = trim($_POST['sub_type']);
			$this->view->manuChoice = trim($_POST['manu']);
			if(isset($_POST['color'])){
				$this->view->colorChoice = $_POST['color'];
			}
			
			// create Model
			if(isset($_POST['sub_model']) and $_POST['sub_model']=="Model"){
			}
			//create Number 
			if(isset($_POST['sub_number']) and $_POST['sub_number']=="Number"){

				$this->view->modelChoice = trim($_POST['model']);
				
				
				$ccounter = $counters->searchCounterByCode(trim($_POST['sub_type']),trim($_POST['model']));
				if($ccounter){
					$number=$ccounter['counter']+1;
				}
				else{
					$number=100;
				}
			
			
			}
			// Create Barcode 
			if(isset($_POST['sub_result']) and $_POST['sub_result']=="Result"){
				
				$this->view->modelChoice = trim($_POST['model']);
				$number = trim($_POST['number']);
				//create Barcode 
				
				$subtypeDes = $subType->getSubtypeDesByCode(trim($_POST['sub_type']));
				$manuDes = $manu->getManuDesByCode(trim($_POST['manu']));
				$modelDes = $model->getModelDesByCode(trim($_POST['model']));
				
				foreach($_POST['color'] as $key => $value){
						
					$colorDes = $color->getColorDes(trim($value));
					
					if(is_numeric($number)){
						$arrBarcodeCode[]  = trim($_POST['manu']).trim($_POST['model']).trim($_POST['sub_type']).$number."-".trim($value);
						$arrBarcodeDes[] = "**".$manuDes." ".$modelDes." ".$subtypeDes." ".$colorDes;
							
					}
					
					else{
						$arrBarcodeCode[]  = trim($_POST['manu']).trim($_POST['model']).trim($_POST['sub_type'])."-".trim($value);
						$arrBarcodeDes[] = "**".$manuDes." ".$modelDes." ".$subtypeDes." ".$colorDes;
					}
					
				}
				
				$this->view->barcodeCode = $arrBarcodeCode;
				
				$this->view->barcodeDes = $arrBarcodeDes;
				
							
			}
			
			
		}
		//load model After Post 
		$this->view->modelList = $modelList;
		$this->view->number = $number;
		
	}
	
	//Battery Barcode
	public function createBarcodeBatAction(){
		
		$idMainType = 2;
		//Get Sub type 
		$subType = new Model_DbTable_Barcodeproductsubtype();
		//cond
		$cond = new Model_DbTable_Barcode_Condition();
		//manu
		$manu = new Model_DbTable_Barcodemanu();
		//model
		$model = new Model_DbTable_Barcodemodel();
		//get special code 
		$spCode = new Model_DbTable_Barcode_Specialcode();
		//get number 
		$counters = new Model_DbTable_Barcode_Counter();
		
		
		$modelList = array(array('code_model'=>"NA",'des_model'=>"NotAvailable"));
		//$spCodeList = array(array('code_special_code'=>"NA",'des_special_code'=>"NotAvailable")); 

		$number = "N/A";
		
		$arrBarcodeCode = array();
		$arrBarcodeDes = array();
		
		
		
		//Initial Selections 
		$this->view->subtypeList = $subType->listSubTypeByMainTypeID($idMainType);
		$this->view->condList = $cond->listAllConds();
		$this->view->manuList = $manu->listManu();
		$this->view->modelList = $modelList;
		$this->view->spcodeList = $spCode->listSpecialCodeByProductTypeID($idMainType);
		
		//Remember the Choice
		
		$this->view->subtypeChoice = "";
		$this->view->condChoice = "";
		$this->view->manuChoice = "";
		$this->view->spcodeChoice = "";
		$this->view->modelChoice = "";
		//barcode Des
		$this->view->barcodeCode= "";
		$this->view->barcodeDes ="";
		
		if($_POST){
		// will do whatever has been posted
			$modelList = $model->listModelByManuCode(trim($_POST['manu']));

			$this->view->subtypeChoice = trim($_POST['sub_type']);
			$this->view->manuChoice = trim($_POST['manu']);
			$this->view->condChoice = trim($_POST['cond']);			
			$this->view->spcodeChoice = trim($_POST['spcode']);
			
			
		//create Model
			if(isset($_POST['sub_model']) and $_POST['sub_model']=="Model"){
				//do nothing
			}
		//creat Number
			if(isset($_POST['sub_number']) and $_POST['sub_number']=="Number"){
				//if special code not exist , do nothing 
				//else record number 
				$this->view->modelChoice = trim($_POST['model']);
				if(trim($_POST['spcode'])!="NA"){
				
				
				$ccounter = $counters->searchCounterByCodeSpCode(trim($_POST['sub_type']),trim($_POST['spcode']));
				if($ccounter){
					$number=$ccounter['counter']+1;
				}
				else{
					$number=100;
				}
				
				}
				
			}
		//creat result 
			if(isset($_POST['sub_result']) and $_POST['sub_result']=="Result"){
				
				$this->view->modelChoice = trim($_POST['model']);
				$number = trim($_POST['number']);
				//create Barcode
				
				$subtypeDes = $subType->getSubtypeDesByCode(trim($_POST['sub_type']));
				
				$condDes = $cond->getCondDesByCode($_POST['cond']);
				$manuDes = $manu->getManuDesByCode(trim($_POST['manu']));
				$modelDes = $model->getModelDesByCode(trim($_POST['model']));
				$speCodeDes = $spCode->getSpecialDesByCode(trim($_POST['spcode']),$idMainType);
				//var_dump($speCodeDes);
				//put number in it 
				if($_POST['spcode']!="NA" and is_numeric($number)){
					$arrBarcodeCode[]  = trim($_POST['sub_type']).trim($_POST['cond']).trim($_POST['manu'])."-".trim($_POST['model'])."-".trim($_POST['spcode'])."-".$number;
					$arrBarcodeDes[] = "**".$subtypeDes." ".$condDes." for ".$manuDes." ".$modelDes." ".$speCodeDes." ".$number;
						
				}
				elseif($_POST['spcode']!="NA" and !is_numeric($number)){
					$arrBarcodeCode[]  = trim($_POST['sub_type']).trim($_POST['cond']).trim($_POST['manu'])."-".trim($_POST['model'])."-".trim($_POST['spcode']);
					$arrBarcodeDes[] = "**".$subtypeDes." ".$condDes." for ".$manuDes." ".$modelDes." ".$speCodeDes;
						
				}					
				else{
					$arrBarcodeCode[]  = trim($_POST['sub_type']).trim($_POST['cond']).trim($_POST['manu'])."-".trim($_POST['model']);
					$arrBarcodeDes[] = "**".$subtypeDes." ".$condDes." for ".$manuDes." ".$modelDes;
				}
				
				$this->view->barcodeCode = $arrBarcodeCode;
				
				$this->view->barcodeDes = $arrBarcodeDes;
								
			}
			
		}
		
		$this->view->modelList = $modelList;
		$this->view->number = $number;
		

	}
	//Charger Barcode
	public function createBarcodeChargerAction(){
		$idMainType = 3;
		
		//Get Sub type
		$subType = new Model_DbTable_Barcodeproductsubtype();
		//cond
		$cond = new Model_DbTable_Barcode_Condition();
		//manu
		$manu = new Model_DbTable_Barcodemanu();
		//model
		$model = new Model_DbTable_Barcodemodel();
		
		$color = new Model_DbTable_Barcodecolorcodes();
		$counters = new Model_DbTable_Barcode_Counter();
		
		$modelList = array(array('code_model'=>"NA",'des_model'=>"NotAvailable"));
		
		$number = "N/A";
		
		$arrBarcodeCode = array();
		$arrBarcodeDes = array();
		
		//Initial Selections
		
		$this->view->subtypeList = $subType->listSubTypeByMainTypeID($idMainType);
		$this->view->condList = $cond->listAllConds();
		$this->view->manuList = $manu->listManu();
		$this->view->modelList = $modelList;
		$this->view->colorList = $color->listAllColors();
		
		//Remember the Choice
		
		$this->view->subtypeChoice = "";
		$this->view->condChoice = "";
		$this->view->manuChoice = "";
		$this->view->modelChoice = "";
		$this->view->colorChoice = "";
		
		//barcode Des
		$this->view->barcodeCode= "";
		$this->view->barcodeDes ="";
		
		if($_POST){
			
			$modelList = $model->listModelByManuCode(trim($_POST['manu']));
			$this->view->subtypeChoice = trim($_POST['sub_type']);
			$this->view->condChoice = trim($_POST['cond']);
			$this->view->manuChoice = trim($_POST['manu']);
			$this->view->modelChoice = trim($_POST['model']);
			$number = trim($_POST['number']);
			
			if(isset($_POST['color'])){
				$this->view->colorChoice = $_POST['color'];
			}
			
			//create Model
			if(isset($_POST['sub_model']) and $_POST['sub_model']=="Model"){
				//do nothing
			}
			//create Number
			if(isset($_POST['sub_number']) and $_POST['sub_number']=="Number"){
			
				$ccounter = $counters->searchCounterByCode(trim($_POST['sub_type']),trim($_POST['model']));
				if($ccounter){
					$number=$ccounter['counter']+1;
				}
				else{
					$number=100;
				}
					
					
			}
			
			// Create Barcode
			if(isset($_POST['sub_result']) and $_POST['sub_result']=="Result"){
			

				//create Barcode
			
				$subtypeDes = $subType->getSubtypeDesByCode(trim($_POST['sub_type']));
				$condDes = $cond->getCondDesByCode(trim($_POST['cond']));
				
				$manuDes = $manu->getManuDesByCode(trim($_POST['manu']));
				$modelDes = $model->getModelDesByCode(trim($_POST['model']));
			
				if(isset($_POST['color'])){
				foreach($_POST['color'] as $key => $value){
			
					$colorDes = $color->getColorDes(trim($value));
						
					if(is_numeric($number)){
						$arrBarcodeCode[]  ="CH".trim($_POST['sub_type']).trim($_POST['cond']).trim($_POST['manu']).trim($_POST['model'])."-".$number."-".trim($value);
						$arrBarcodeDes[] = "**Charger ".$condDes." ".$subtypeDes." for ".$manuDes." ".$modelDes." ".$colorDes;
							
					}
						
					else{
						$arrBarcodeCode[]  ="CH".trim($_POST['sub_type']).trim($_POST['cond']).trim($_POST['manu']).trim($_POST['model'])."-".trim($value);
						$arrBarcodeDes[] = "**Charger ".$condDes." ".$subtypeDes." for ".$manuDes." ".$modelDes." ".$colorDes;
					}
						
				}
				
				}
				else{
					
					if(is_numeric($number)){
						$arrBarcodeCode[]  ="CH".trim($_POST['sub_type']).trim($_POST['cond']).trim($_POST['manu']).trim($_POST['model'])."-".$number;
						$arrBarcodeDes[] = "**Charger ".$condDes." ".$subtypeDes." for ".$manuDes." ".$modelDes;
							
					}
					
					else{
						$arrBarcodeCode[]  ="CH".trim($_POST['sub_type']).trim($_POST['cond']).trim($_POST['manu']).trim($_POST['model']);
						$arrBarcodeDes[] = "**Charger ".$condDes." ".$subtypeDes." for ".$manuDes." ".$modelDes;
					}					
					
				}
			
				$this->view->barcodeCode = $arrBarcodeCode;
			
				$this->view->barcodeDes = $arrBarcodeDes;
			
					
			}
			
			
				
			
			
		}
				
		$this->view->modelList = $modelList;
		$this->view->number = $number;
		
		
		
	}
	public function createBarcodeDatacableAction(){
		$idMainType = 4;
		$codeSubType = "DC";
		//Get Sub type
		$subType = new Model_DbTable_Barcodeproductsubtype();
		//cond
		$cond = new Model_DbTable_Barcode_Condition();
		//manu
		$manu = new Model_DbTable_Barcodemanu();
		//model
		$model = new Model_DbTable_Barcodemodel();
		
		$color = new Model_DbTable_Barcodecolorcodes();
		$counters = new Model_DbTable_Barcode_Counter();
		
		$modelList = array(array('code_model'=>"NA",'des_model'=>"NotAvailable"));
		
		$number = "N/A";
		
		$arrBarcodeCode = array();
		$arrBarcodeDes = array();
		
		//Initial Selections
			
		$this->view->condList = $cond->listAllConds();
		$this->view->manuList = $manu->listManu();
		$this->view->modelList = $modelList;
		$this->view->colorList = $color->listAllColors();
		
		//Remember the Choice
		
		
		$this->view->condChoice = "";
		$this->view->manuChoice = "";
		$this->view->modelChoice = "";
		$this->view->colorChoice = "";
		$this->view->subtypeChoice = $codeSubType;
		
		//barcode Des
		$this->view->barcodeCode= "";
		$this->view->barcodeDes ="";		
		
		if($_POST){
				
			$modelList = $model->listModelByManuCode(trim($_POST['manu']));
			$this->view->condChoice = trim($_POST['cond']);
			$this->view->manuChoice = trim($_POST['manu']);
			$this->view->modelChoice = trim($_POST['model']);
			$number = trim($_POST['number']);
			
			if(isset($_POST['color'])){
				$this->view->colorChoice = $_POST['color'];
			}
				
			//create Model
			if(isset($_POST['sub_model']) and $_POST['sub_model']=="Model"){
				//do nothing
			}			
			
			//create Number
			if(isset($_POST['sub_number']) and $_POST['sub_number']=="Number"){
					
				$ccounter = $counters->searchCounterByCode($codeSubType,trim($_POST['model']));
				if($ccounter){
					$number=$ccounter['counter']+1;
				}
				else{
					$number=100;
				}
					
					
			}
			// Create Barcode
			if(isset($_POST['sub_result']) and $_POST['sub_result']=="Result"){
					
			
				//create Barcode
					
				$condDes = $cond->getCondDesByCode(trim($_POST['cond']));
			
				$manuDes = $manu->getManuDesByCode(trim($_POST['manu']));
				$modelDes = $model->getModelDesByCode(trim($_POST['model']));
					
				foreach($_POST['color'] as $key => $value){
						
					$colorDes = $color->getColorDes(trim($value));
			
					if(is_numeric($number)){
						$arrBarcodeCode[]  ="DC".trim($_POST['cond']).trim($_POST['manu']).trim($_POST['model'])."-".$number."-".trim($value);
						$arrBarcodeDes[] = "**Data Cable ".$condDes." for ".$manuDes." ".$modelDes." ".$colorDes;
							
					}
			
					else{
						$arrBarcodeCode[]  ="DC".trim($_POST['cond']).trim($_POST['manu']).trim($_POST['model'])."-".trim($value);
						$arrBarcodeDes[] = "**Data Cable ".$condDes." for ".$manuDes." ".$modelDes." ".$colorDes;
					}
			
				}
					
				$this->view->barcodeCode = $arrBarcodeCode;
					
				$this->view->barcodeDes = $arrBarcodeDes;
					
					
			}
		
		}	
		
		
		$this->view->modelList = $modelList;
		$this->view->number = $number;
		
	}
	
	public function createBarcodeHandsfreeAction(){
		$idMainType = 5;
		$codeSubType = "HF";
		//Get Sub type
		$subType = new Model_DbTable_Barcodeproductsubtype();
		//cond
		$cond = new Model_DbTable_Barcode_Condition();
		//manu
		$manu = new Model_DbTable_Barcodemanu();
		//model
		$model = new Model_DbTable_Barcodemodel();
		
		$color = new Model_DbTable_Barcodecolorcodes();
		$counters = new Model_DbTable_Barcode_Counter();
		
		$modelList = array(array('code_model'=>"NA",'des_model'=>"NotAvailable"));
		
		$number = "N/A";
		
		$arrBarcodeCode = array();
		$arrBarcodeDes = array();
		
		//Initial Selections
			
		$this->view->condList = $cond->listAllConds();
		$this->view->manuList = $manu->listManu();
		$this->view->modelList = $modelList;
		$this->view->colorList = $color->listAllColors();
		
		//Remember the Choice
		
		
		$this->view->condChoice = "";
		$this->view->manuChoice = "";
		$this->view->modelChoice = "";
		$this->view->colorChoice = "";
		$this->view->subtypeChoice = $codeSubType;
		
		//barcode Des
		$this->view->barcodeCode= "";
		$this->view->barcodeDes ="";
		
		if($_POST){
		
			$modelList = $model->listModelByManuCode(trim($_POST['manu']));
			$this->view->condChoice = trim($_POST['cond']);
			$this->view->manuChoice = trim($_POST['manu']);
			$this->view->modelChoice = trim($_POST['model']);
			$number = trim($_POST['number']);
				
			if(isset($_POST['color'])){
				$this->view->colorChoice = $_POST['color'];
			}
		
			//create Model
			if(isset($_POST['sub_model']) and $_POST['sub_model']=="Model"){
				//do nothing
			}
				
			//create Number
			if(isset($_POST['sub_number']) and $_POST['sub_number']=="Number"){
					
				$ccounter = $counters->searchCounterByCode($codeSubType,trim($_POST['model']));
				if($ccounter){
					$number=$ccounter['counter']+1;
				}
				else{
					$number=100;
				}
					
					
			}
			// Create Barcode
			if(isset($_POST['sub_result']) and $_POST['sub_result']=="Result"){
					
					
				//create Barcode
					
				$condDes = $cond->getCondDesByCode(trim($_POST['cond']));
					
				$manuDes = $manu->getManuDesByCode(trim($_POST['manu']));
				$modelDes = $model->getModelDesByCode(trim($_POST['model']));
					
				foreach($_POST['color'] as $key => $value){
		
					$colorDes = $color->getColorDes(trim($value));
						
					if(is_numeric($number)){
						$arrBarcodeCode[]  ="HF".trim($_POST['cond']).trim($_POST['manu']).trim($_POST['model'])."-".$number."-".trim($value);
						$arrBarcodeDes[] = "**Hands Free ".$condDes." for ".$manuDes." ".$modelDes." ".$colorDes;
							
					}
						
					else{
						$arrBarcodeCode[]  ="HF".trim($_POST['cond']).trim($_POST['manu']).trim($_POST['model'])."-".trim($value);
						$arrBarcodeDes[] = "**Hands Free ".$condDes." for ".$manuDes." ".$modelDes." ".$colorDes;
					}
						
				}
					
				$this->view->barcodeCode = $arrBarcodeCode;
					
				$this->view->barcodeDes = $arrBarcodeDes;
					
					
			}
		
		}
		
		
		$this->view->modelList = $modelList;
		$this->view->number = $number;
				
	}
	

	public function createBarcodeHolderAction(){
		$idMainType = 6;
		//$codeSubType = "HF";
		//Get Sub type
		$subType = new Model_DbTable_Barcodeproductsubtype();
		//spcode 
		$spCode = new Model_DbTable_Barcode_Specialcode();
		// Number 
		$counters = new Model_DbTable_Barcode_Counter();
		
		$this->view->spcodeList = $spCode->listSpecialCodeByProductTypeID($idMainType);
		$this->view->subtypeList = $subType->listSubTypeByMainTypeID($idMainType);
		//Remember the Choice
		
		$this->view->subtypeChoice = "";

		$this->view->spcodeChoice = "";

		$number = "N/A";
		
		$arrBarcodeCode = array();
		$arrBarcodeDes = array();

		//Remember the Choice
		
		
		
		//barcode Des
		$this->view->barcodeCode= "";
		$this->view->barcodeDes ="";
		
		if($_POST){
			
			$this->view->subtypeChoice = trim($_POST['sub_type']);
			$this->view->spcodeChoice = trim($_POST['spcode']);
			$number = trim($_POST['number']);
		
			//create Number
					if(isset($_POST['sub_number']) and $_POST['sub_number']=="Number"){
				//if special code not exist , do nothing 

				if(trim($_POST['spcode'])!="NA"){
				
				
				$ccounter = $counters->searchCounterByCodeSpCode(trim($_POST['sub_type']),trim($_POST['spcode']));
				if($ccounter){
					$number=$ccounter['counter']+1;
				}
				else{
					$number=100;
				}
				
				}
				
			}
				//creat result 
			if(isset($_POST['sub_result']) and $_POST['sub_result']=="Result"){
				
				//create Barcode
				
				$subtypeDes = $subType->getSubtypeDesByCode(trim($_POST['sub_type']));
				$speCodeDes = $spCode->getSpecialDesByCode(trim($_POST['spcode']),$idMainType);

				
				if($_POST['spcode']!="NA" and is_numeric($number)){
					$arrBarcodeCode[]  = trim($_POST['sub_type'])."-".trim($_POST['spcode'])."-".$number;
					$arrBarcodeDes[] = "**".$subtypeDes." ".$speCodeDes." ".$number;
						
				}
				elseif($_POST['spcode']!="NA" and !is_numeric($number)){
					$arrBarcodeCode[]  = trim($_POST['sub_type'])."-".trim($_POST['spcode']);
					$arrBarcodeDes[] =  "**".$subtypeDes." ".$speCodeDes;
						
				}					
				else{
						//doing nothing 
				}
				
				$this->view->barcodeCode = $arrBarcodeCode;
				
				$this->view->barcodeDes = $arrBarcodeDes;
								
			}
		
		}
		
		
		$this->view->number = $number;
					
		
	}
	public function createBarcodeSpAction(){
		$idMainType = 7;
		
		//Get Sub type
		$subType = new Model_DbTable_Barcodeproductsubtype();
		//cond
		//$cond = new Model_DbTable_Barcode_Condition();
		//manu
		$manu = new Model_DbTable_Barcodemanu();
		//model
		$model = new Model_DbTable_Barcodemodel();
		
		$color = new Model_DbTable_Barcodecolorcodes();
		$counters = new Model_DbTable_Barcode_Counter();
		
		$modelList = array(array('code_model'=>"NA",'des_model'=>"NotAvailable"));
		
		$number = "N/A";
		
		$arrBarcodeCode = array();
		$arrBarcodeDes = array();
		
		//Initial Selections
		
		$this->view->subtypeList = $subType->listSubTypeByMainTypeID($idMainType);
		//$this->view->condList = $cond->listAllConds();
		$this->view->manuList = $manu->listManu();
		$this->view->modelList = $modelList;
		$this->view->colorList = $color->listAllColors();
		
		//Remember the Choice
		
		$this->view->subtypeChoice = "";
		//$this->view->condChoice = "";
		$this->view->manuChoice = "";
		$this->view->modelChoice = "";
		$this->view->colorChoice = "";
		
		//barcode Des
		$this->view->barcodeCode= "";
		$this->view->barcodeDes ="";
		
		if($_POST){
				
			$modelList = $model->listModelByManuCode(trim($_POST['manu']));
			$this->view->subtypeChoice = trim($_POST['sub_type']);
			//$this->view->condChoice = trim($_POST['cond']);
			$this->view->manuChoice = trim($_POST['manu']);
			$this->view->modelChoice = trim($_POST['model']);
			$number = trim($_POST['number']);
				
			if(isset($_POST['color'])){
				$this->view->colorChoice = $_POST['color'];
			}
				
			//create Model
			if(isset($_POST['sub_model']) and $_POST['sub_model']=="Model"){
				//do nothing
			}
			//create Number
			if(isset($_POST['sub_number']) and $_POST['sub_number']=="Number"){
					
				$ccounter = $counters->searchCounterByCode(trim($_POST['sub_type']),trim($_POST['model']));
				if($ccounter){
					$number=$ccounter['counter']+1;
				}
				else{
					$number=100;
				}
					
					
			}
				
			// Create Barcode
			if(isset($_POST['sub_result']) and $_POST['sub_result']=="Result"){
					
		
				//create Barcode
					
				$subtypeDes = $subType->getSubtypeDesByCode(trim($_POST['sub_type']));
				//$condDes = $cond->getCondDesByCode(trim($_POST['cond']));
		
				$manuDes = $manu->getManuDesByCode(trim($_POST['manu']));
				$modelDes = $model->getModelDesByCode(trim($_POST['model']));

				if(isset($_POST['color'])){
				foreach($_POST['color'] as $key => $value){
						
					$colorDes = $color->getColorDes(trim($value));
		
					if(is_numeric($number)){
						$arrBarcodeCode[]  ="SP".trim($_POST['sub_type']).trim($_POST['manu']).trim($_POST['model'])."-".$number."-".trim($value);
						$arrBarcodeDes[] = "**Screen Protector ".$subtypeDes." for ".$manuDes." ".$modelDes." ".$colorDes;
							
					}
		
					else{
						$arrBarcodeCode[]  ="SP".trim($_POST['sub_type']).trim($_POST['manu']).trim($_POST['model'])."-".trim($value);
						$arrBarcodeDes[] = "**Screen Protector ".$subtypeDes." for ".$manuDes." ".$modelDes." ".$colorDes;
					}
		
				}
				}
				else{
					
					if(is_numeric($number)){
						$arrBarcodeCode[]  ="SP".trim($_POST['sub_type']).trim($_POST['manu']).trim($_POST['model'])."-".$number;
						$arrBarcodeDes[] = "**Screen Protector ".$subtypeDes." for ".$manuDes." ".$modelDes;
							
					}
					
					else{
						$arrBarcodeCode[]  ="SP".trim($_POST['sub_type']).trim($_POST['manu']).trim($_POST['model']);
						$arrBarcodeDes[] = "**Screen Protector ".$subtypeDes." for ".$manuDes." ".$modelDes;
					}					
					
					
				}
				
				
					
				$this->view->barcodeCode = $arrBarcodeCode;
					
				$this->view->barcodeDes = $arrBarcodeDes;
					
					
			}
				
				
		
				
				
		}
		
		$this->view->modelList = $modelList;
		$this->view->number = $number;
		
		
				
	}
	public function createBarcodeOtherAction(){
		$idMainType = 8;
		//Get Sub type
		$subType = new Model_DbTable_Barcodeproductsubtype();

		//get special code
		$spCode = new Model_DbTable_Barcode_Specialcode();
		//get number
		$color = new Model_DbTable_Barcodecolorcodes();
		$counters = new Model_DbTable_Barcode_Counter();
		
		
		//$spCodeList = array(array('code_special_code'=>"NA",'des_special_code'=>"NotAvailable"));
		
		$number = "N/A";
		
		$arrBarcodeCode = array();
		$arrBarcodeDes = array();
		
		
		
		//Initial Selections
		$this->view->subtypeList = $subType->listSubTypeByMainTypeID($idMainType);
		$this->view->spcodeList = $spCode->listSpecialCodeByProductTypeID($idMainType);
		$this->view->colorList = $color->listAllColors();
		
		//Remember the Choice
		
		$this->view->subtypeChoice = "";
		$this->view->colorChoice = "";
		$this->view->spcodeChoice = "";

		//barcode Des
		$this->view->barcodeCode= "";
		$this->view->barcodeDes ="";
		
		if($_POST){

		
			$this->view->subtypeChoice = trim($_POST['sub_type']);
			$this->view->spcodeChoice = trim($_POST['spcode']);
			$number = trim($_POST['number']);
			
			if(isset($_POST['color'])){
				$this->view->colorChoice = $_POST['color'];
			}
			
			//creat Number
			if(isset($_POST['sub_number']) and $_POST['sub_number']=="Number"){
				//if special code not exist , do nothing
				if(trim($_POST['spcode'])!="NA"){
		
		
					$ccounter = $counters->searchCounterByCodeSpCode(trim($_POST['sub_type']),trim($_POST['spcode']));
					if($ccounter){
						$number=$ccounter['counter']+1;
					}
					else{
						$number=100;
					}
		
				}
		
			}
			//creat result
			if(isset($_POST['sub_result']) and $_POST['sub_result']=="Result"){
				//create Barcode
		
				$subtypeDes = $subType->getSubtypeDesByCode(trim($_POST['sub_type']));
				$speCodeDes = $spCode->getSpecialDesByCode(trim($_POST['spcode']),$idMainType);
				
				//var_dump($speCodeDes);
				//put number in it
				
				if(isset($_POST['color'])){
				foreach($_POST['color'] as $key => $value){
			
					$colorDes = $color->getColorDes(trim($value));
						
					if(is_numeric($number)){
						$arrBarcodeCode[]  ="OTH-".trim($_POST['sub_type']).trim($_POST['spcode'])."-".$number."-".trim($value);
						$arrBarcodeDes[] = "**Other ".$subtypeDes." ".$speCodeDes." ".$number." ".$colorDes;
							
					}
						
					else{
						$arrBarcodeCode[]  ="OTH-".trim($_POST['sub_type']).trim($_POST['spcode'])."-".trim($value);
						$arrBarcodeDes[] = "**Other ".$subtypeDes." ".$speCodeDes." ".$colorDes;
					}
						
				}
				
				}
				else{
					
					if(is_numeric($number)){
						$arrBarcodeCode[]  ="OTH-".trim($_POST['sub_type']).trim($_POST['spcode'])."-".$number;
						$arrBarcodeDes[] =  "**Other ".$subtypeDes." ".$speCodeDes." ".$number;
							
					}
					
					else{
						$arrBarcodeCode[]  ="OTH-".trim($_POST['sub_type']).trim($_POST['spcode']);
						$arrBarcodeDes[] = "**Other ".$subtypeDes." ".$speCodeDes;
					}					
					
				}
			
				$this->view->barcodeCode = $arrBarcodeCode;
			
				$this->view->barcodeDes = $arrBarcodeDes;
			
					
				
		}
		

	}
	
	$this->view->number = $number;
	}
	//Screen protector
	public function createBarcodeStylusAction(){
		
	}
	public function addBarcodeMoreColorAction(){
		
	}
	
	
	
	public function saveBarcodeAction(){
		
		$subType = new Model_DbTable_Barcodeproductsubtype();
		$products = new Model_DbTable_Productsva();
		$counter = new Model_DbTable_Barcode_Counter();
		$model = new Model_DbTable_Barcodemodel();
		$spCode = new Model_DbTable_Barcode_Specialcode();
		
		
		if($_POST){
		
		//save in barcode into database 
		
		
		foreach($_POST['barcode'] as $key => $value){
			
			$products->addProduct($value, $_POST['title'][$key]);		
		}
		
		//Save Counter 
		if(isset($_POST['number_counter']) && $_POST['number_counter']!="N/A"){
				$idSubtype = $subType->getSubtypeIDByCode($_POST['code_sub_type']);
				$idMainType = $subType->getMainTypeIDByCode($_POST['code_sub_type']);
				if(isset($_POST['code_model'])){
				$idModel = $model->getModelIDByCode($_POST['code_model']);
			
			if(intval($_POST['number_counter']) ==100){
								
				$counter->addCounter($idSubtype, $idModel, 100);
			}
			else{
				
				$counter->runCounter($idSubtype, $idModel,$_POST['number_counter']);
			}
				}
				if(isset($_POST['code_spcode'])){
					
				$idSpCode = $spCode->getSpecialByCode($_POST['code_spcode'], $idMainType);
				
				if(intval($_POST['number_counter']) ==100){
				
					$counter->addCounter($idSubtype, $idSpCode['id_special'], 100);
				}
				else{
				
					$counter->runCounter($idSubtype,$idSpCode['id_special'],$_POST['number_counter']);
				}					
					
				}
				
		}
		
		//save into product 

		//	
			
			
		echo "BarCode and Description Saved, You may now Close this page";	
		
		}
		else{
			
		echo "You should Not Directly Visit this page";
		}
		
	}
	
	public function createFileAction(){
		$folder = getcwd()."/barcoderesult/";
		
		
		$dateToday = Model_DatetimeHelper::dateToday();
		$fileName ="barcode-".$dateToday.".csv";
		
		$products = new Model_DbTable_Productsva();
		$pList = $products->listProductsByData($dateToday);
		if($pList){
			
		$fl = fopen($folder.$fileName,"a");
			
			foreach($pList as $key => $value){
								
				fputcsv($fl, $value);
			}
		fclose($fl);	
		} 
		
		
	}
	
	public function inventoryTurnoverAction(){
		// to answer 5 questions
		//setp 1  turnover rate given period , total sales / average inventory 
		//setp 2  turnover rate shop, how many shop are saling this staff  sales shop / total shop ? this number meaning ful?
		// (if something other people sale good not in the place of hot zone?)
		//step 3  how long it can last (weeks)
		// trend average weekly sales / current stock 
		//step 4  does it have a eta?
		//just need fill by china
		//step 5  is able to catch eta? how long include back order it will last 
		// trend average weekly sales / Current Stock level  
		
		
		
	}
	

}
?>