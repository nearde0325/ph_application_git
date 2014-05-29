<?php
/*
 this is the class which send automatic email when:
Something Done:

Something Wrong:
1) If download failed, Email me


*/
class Model_DbTable_Sampleproducts extends Zend_Db_Table_Abstract {
	
	protected $_name = 'products_sample_v1';
	
	
	public function getSample($id){
		$row = $this->fetchRow('`id_product_sample`='.(int)$id);
		if(!$row){
			return false;
		}
		return $row->toArray();		
		}
	public function createNewBarcode($idSupplier){
		
		$counters = new Model_DbTable_Samplecounter();
		$newCount = $counters->runCounter();
		
		$dateToday = self::cDateToday();
		
		$barcode = $idSupplier."-".$dateToday."-".$newCount;
		
		return $barcode;
		
		}	
	public function getSamples($sampleBarcode){
		$select = $this->_db->select()
		->from($this->_name,array('*'))
		->where('barcode_sample LIKE% ?',$sampleBarcode)
		->order('id_product_sample');
		$result = $this->getAdapter()->fetchAll($select);
		
		if(!$result){
			return false;
		}
		
		return $result;				
		
	}
	
		
		
	public function getSampleID($sampleBarcode){
		$select = $this->_db->select()
		->from($this->_name,array('*'))
		->where('barcode_sample LIKE ?',$sampleBarcode)
		//->where('sp_id = ?',$idSupplyer)
		->order('id_product_sample');
		$result = $this->getAdapter()->fetchAll($select);
		
		if(!$result){return false;}
		
		return $result[0]['id_product_sample'];	
		
	}	
	
	public function addSample($sampleBarcode,$chineseName,$idSupplier,$staffName,$supplierBarcode){
		$data = array(
			"barcode_sample" => $sampleBarcode,
			"chinese_name_sample" => $chineseName,
			"id_supplier" => $idSupplier,
			"staff_name" => $staffName,
			"date_introduced" =>self::cDateToday2(),	
			"barcode_supplier" => $supplierBarcode					
				);
		$this->insert($data);
		
	}
	public function updateSampleDetail($idSample,$supplierBarcode,$chineseName,$imgurl){
		$data = array(
			"barcode_supplier" => $supplierBarcode,
			"chinese_name_sample" => $chineseName,	
				);
		if($imgurl == 1){ $data['img_url_sample'] = self::cDateToday2(); }
		
		
	}
	
	public function updatePrice($idSample,$rmbPrice,$audPrice){
		//later
	}
	
	public function updateSampleStatus($ifOnline,$ifAccept,$ifShip){
		//later
	}
	public function listUndecideSamples(){
		
		$rows = $this->fetchAll('ISNULL(`accept_sample`) = 1','id_product_sample');
		return $rows->toArray();
			
	}		

	public function deleteSample($id){
		$this->delete('`id_product_sample` =' . $id);
		}
		
	public function updateSampleThumb($barCode){
		
		$idSample = self::getSampleID($barCode);
		//$dToday = 
		$data = array(
			"img_url_sample" => self::cDateToday2()
				);
		$this->update($data,"`id_product_sample` = ".$idSample);
	}

	public function updateSampleZip($barCode){
		$idSample = self::getSampleID($barCode);
		//$dToday =
		$data = array(
				"img_zip_sample" => self::cDateToday2()
		);
		$this->update($data,"`id_product_sample` = ".$idSample);		
		
	}
		
	private function cDateToday(){
		
			date_default_timezone_set('Australia/Melbourne');
		
			return $dateToday = date("ymd");
		
		}		
	private function cDateToday2(){
		
		date_default_timezone_set('Australia/Melbourne');
		
		return $dateToday = date("Y-m-d");		
	}
}
?>