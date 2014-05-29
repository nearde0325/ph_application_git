<?php 
/**

 */
class SupplierController extends Zend_Controller_Action
{

    public function init(){
    /**
	 *
	 *
	 */    
	
	}

    public function indexAction(){
	
		echo "<h2>Supplier Names </h2>";
		$suppliers = new Model_DbTable_Suppliers();
		$rows = $suppliers->listAllSuppliers();
		
		$this->view->supplierlist = $rows;
		

    
	}
	public function addSupplierAction(){
		
		$suppliers = new Model_DbTable_Suppliers();
		
		if($this->getRequest()->isPost())
		{
				//saving the data , echo "saved"
			$suppliers->addSupplier($_POST['name_supplier'],$_POST['short_name_supplier'],$_POST['location_supplier']);
			echo "DONE";
		}
		
		
	}
	public function updateSupplierDetailAction(){
		
	}
	public function hideSupplierAction(){
		
	}
	public function listSampleAction(){
	 $samples = new Model_DbTable_Sampleproducts();
	 $this->view->samplelist = $samples->listUndecideSamples();	
	}
	
	public function newSampleAction(){
		
	}	
	public function saveNewSampleStepAAction(){
		
		$staffName = $_POST['staff_name'];
		$idSupplier = $_POST['id_supplier'];
		$chineseName = $_POST['chinese_name'];
		$barcodeSupplier = $_POST['barcode_supplier'];
		
		
		$samples = new Model_DbTable_Sampleproducts();
		$barcodeNoColor = $samples->createNewBarcode($idSupplier);
		$arrBarcode = array();
		$arrColor = $_POST['color_code'];
		
		foreach($arrColor as $key => $value){
			$newbarCode = "SAP-".$barcodeNoColor."-".$value;
			$arrBarcode[] = $newbarCode;

			$samples->addSample($newbarCode, $chineseName, $idSupplier, $staffName, $barcodeSupplier);
		
		}
		
		
		
		$this->view->barcodenocolor = $barcodeNoColor;
		$this->view->arrBarcode = $arrBarcode;
		$this->view->colorcode = $arrColor;
		
		
		
		
		
	}
	public function saveNewSampleStepBAction(){
		
	}	
	public function uploadPhotosAction(){
		
	}
	public function updateBuyingPriceAction(){
		
	}
	public function savethumbsAction(){
		
		echo $barCode = $this->_getParam('id');
		
		$samples = new  Model_DbTable_Sampleproducts();
		$samples->updateSampleThumb($barCode);
		
	}
	public function saveImgsAction(){
		
		$barCode = $this->_getParam('id');
		$thumbdir = getcwd()."/im/thumbs_sample/";
		$zipdir = getcwd()."/im/zip_sample/";
		$thumbFileName = $barCode.".jpg";
		$zipFileName = $barCode.".zip";
		
		$samples = new  Model_DbTable_Sampleproducts();
		
		
		
		if($this->getRequest()->isPost()){
			if(isset($_POST['is_thumb'])){
				if(!move_uploaded_file($_FILES['thumb_img']['tmp_name'],$thumbdir.$thumbFileName)){
					echo "Somthing is wrong";
				}
				else{
					$samples->updateSampleThumb($barCode);
					echo "Thumb File Upload Done, Close";
				}
				
			}	
			if(isset($_POST['is_zip'])){
				if(!move_uploaded_file($_FILES['img_zip']['tmp_name'],$zipdir.$zipFileName)){
					echo "Somthing is wrong";
				}
				else{
					$samples->updateSampleZip($barCode);
					echo "Zip File Upload Done, Close";
					
				}			
			
			}			
			
		}
		
	}
	public function testAction(){
		
		$samples = new Model_DbTable_Sampleproducts();
		echo $samples->getSampleID("SAP-3-121204-6-13");
		//echo $barcodeNoColor = $samples->createNewBarcode($idSupplier);
	}
	
}
?>