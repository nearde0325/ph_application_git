<?php

class Model_Products_Detail extends Zend_Db_Table_Abstract {

	protected $_name = 'products_v1_detail';
	
	public function getDetail( $idProduct){
		
		$row = $this->fetchRow("`id_product` = ". $idProduct);
		if(!$row) return false;
		return $row->toArray();		
		
		}
	public function getDetailByCode($codeProduct){
		
		if(trim($codeProduct)!=""){
			
			$row = $this->fetchRow("`code_product` LIKE '{$codeProduct}' ");
			if(!$row) return false;
			return $row->toArray();			
		}
		
		return false;
	}	
	
	public function getDetailByShortCode($shortCode){

		if(trim($shortCode)!=""){		
			$row = $this->fetchRow("`code_product` LIKE '{$shortCode}%' ");
			if(!$row) return false;
			return $row->toArray();
		}
		
		return false;
		
	}
		
	public function addDetail( $idProduct ,$codeProduct ,$aposTitle ,$mobileBrand , $mobileModel , $category , $shortDescriptionProduct , $chineseTitleProduct , $descriptionProduct , $webpageDescription , $feature , $sizeMm , $weightKg , $introduced , $imgUrl , $imgIndiUrl,$imgZip , $nameSupplier , $barcodeSupplier){
		
		$data = array(		
		 "id_product" => $idProduct,					
         "code_product" =>  $codeProduct ,
		 "apos_title_product" => $aposTitle,		
         "mobile_brand" =>  $mobileBrand ,
         "mobile_model" =>  $mobileModel ,
         "category" =>  $category ,
         "short_description_product" =>  $shortDescriptionProduct ,
         "chinese_title_product" =>  $chineseTitleProduct ,
         "description_product" =>  $descriptionProduct ,
         "webpage_description" =>  $webpageDescription ,
         "feature" =>  $feature ,
         "size_mm" =>  $sizeMm ,
         "weight_kg" =>  $weightKg ,
         "introduced" =>  $introduced ,
         "img_url" =>  $imgUrl ,
		 "img_individual_url" =>  $imgIndiUrl ,
         "img_zip" =>  $imgZip ,
         "name_supplier" =>  $nameSupplier ,
         "barcode_supplier" =>  $barcodeSupplier 
			);
		$this->insert($data);
		}
		
	public function updateDetail(  $idProduct ,  $codeProduct , $aposTitle, $mobileBrand , $mobileModel , $category , $shortDescriptionProduct , $chineseTitleProduct , $descriptionProduct , $webpageDescription , $feature , $sizeMm , $weightKg , $introduced , $imgUrl , $imgIndiUrl, $imgZip , $nameSupplier , $barcodeSupplier){
		
		$data = array(
         "code_product" =>  $codeProduct ,
		 "apos_title_product" => $aposTitle,		
         "mobile_brand" =>  $mobileBrand ,
         "mobile_model" =>  $mobileModel ,
         "category" =>  $category ,
         "short_description_product" =>  $shortDescriptionProduct ,
         "chinese_title_product" =>  $chineseTitleProduct ,
         "description_product" =>  $descriptionProduct ,
         "webpage_description" =>  $webpageDescription ,
         "feature" =>  $feature ,
         "size_mm" =>  $sizeMm ,
         "weight_kg" =>  $weightKg ,
         "introduced" =>  $introduced ,
         "img_url" =>  $imgUrl ,
		 "img_individual_url" =>  $imgIndiUrl ,
         "img_zip" =>  $imgZip ,
         "name_supplier" =>  $nameSupplier ,
         "barcode_supplier" =>  $barcodeSupplier 
		);
			
		$this->update($data,"`id_product` = ". $idProduct);
		}
		
	public function deleteDetail( $idProduct){
		
		$this->delete("`id_product` = ". $idProduct);
		
		}	
	
	public function listAll(){
		
		$rows =$this->fetchAll("1","id_product DESC ");
		if(!$rows) return false;
		return $rows->toArray();			
		
		}	
	public function importDetail($idProduct,$codeProduct,$des){
		
		if(!self::getDetail($idProduct)){
		$data = array(
				"id_product" => $idProduct,
				"code_product" =>  $codeProduct ,
				"description_product" =>  $des
		);
		$this->insert($data);		
		}
	}
	
	public function getProductsBymanuBymodel($mobileBrand , $mobileModel){
		
		$whereStr = "`mobile_brand` LIKE '{$mobileBrand}' AND `mobile_model` LIKE '{$mobileModel}'";
		$rows = $this->fetchAll($whereStr,"code_product")->toArray();
		if(empty($rows)) {return false;}
		return $rows;
		
	}
	
}



?>