<?php
//namespace application\models\DbTable\Apos;

class Model_DbTable_Apos_Invnew extends Zend_Db_Table_Abstract {
	
	public function getInvoice($idInvoice){
		
		$row = $this->fetchRow("INV_NO LIKE '".$idInvoice."'");
		if(!$row) return false;
		return $row->toArray();
	}
	
	public function getInvoicesByDate($date){
		
		$whereStr = "DATE = '".$date."'";
		$rows = $this->fetchAll($whereStr,'TIME');
		if(!$rows) return false;
		return $rows->toArray();			
	}
	public function getCashAccount($arrInvoice){
		//$shopCode = $this->_getParam("shop");
		
		$arrShopMaping = array(
				"ADPC" =>"AD",
				"BBPC" =>"BB",
				"BHPC" =>"BH",
				"BSPC" =>"BS",
				"BSIC" =>"BSIC",
				"BSXP" =>"BSXP",
				"CBPC" =>"CB",
				"CLPC" =>"CL",
				"CLIC" =>"CLIC",
				"CSIC" =>"CSIC",
				"DCIC" =>"DCIC",
				"EPPC" =>"EP",
				"FGIC" =>"FGIC",
				"HPIC" =>"HPIC",
				"KCPC" =>"KC",
				"NLPC" =>"NL",
				"PMPC" =>"PM",
				"PMIC" =>"PMIC",
				"SLIC" =>"SLIC",
				"WBPC" =>"WB",
				"WBIC" =>"WBIC",
				"WGPC" =>"WGPC",
				"WGIC" =>"WGIC",
				"WLIC" =>"WLIC",
				);
		$arrPtype = array(
				1 => "CA",
				2 => "EF",
				3 => "EF",
				4 => "EF",
				5 => "EF",
				6 => "AE"
				);
		$cashSales = 0;
		$eftSales = 0;
		$aeSales = 0;
		$eftReceiptNo = 0;
		$aeReceiptNo = 0;
		
		
		foreach($arrInvoice as $inv){
			if($inv["STATUS"] != "V" ){
			
			if(trim($inv["PTYPE1"])!=""){
				echo "PTYPE1<br/>";
				switch((int)$inv["PTYPE1"]){
					case(1):{
						echo $inv["PAID1"];
						
						$cashSales += $inv["PAID1"];
						break;
					}
					case(6):{
						$aeSales += $inv["PAID1"];
						$aeReceiptNo ++;
						break;
					}
					case(7):{break;}
					default:{
						$eftSales += $inv["PAID1"];
						$eftReceiptNo ++;
						break;
					}	
				};
				
			}	

			if(trim($inv["PTYPE2"])!=""){
				echo "PTYPE2<br/>";
				switch((int)$inv["PTYPE2"]){
					case(1):{
						echo $cashSales += $inv["PAID2"];
						break;
					}
					case(6):{
						$aeSales += $inv["PAID2"];
						$aeReceiptNo ++;
						break;
					}
					case(7):{
						break;
					}
					default:{
						$eftSales += $inv["PAID2"];
						$eftReceiptNo ++;
						break;
					}
				};
			
			}			
			
			if(trim($inv["PTYPE3"])!=""){
				echo "PTYPE3<br/>";
				switch((int)$inv["PTYPE3"]){
					case(1):{
						echo $cashSales += $inv["PAID3"];
						break;
					}
					case(6):{
						$aeSales += $inv["PAID3"];
						$aeReceiptNo ++;
						break;
					}
					case(7):{
						break;
					}
					default:{
						$eftSales += $inv["PAID3"];
						$eftReceiptNo ++;
						break;
					}
				};
			
			}
			$change = 	$inv["PAID1"] + $inv["PAID2"] + $inv["PAID3"] - $inv["PAY_TOTAL"];	
		if($change >0){
			$cashSales -=$change;
		}
			}
		}
		
		
		$arrRes = array(
				"cash_sales" => $cashSales,
				"eft_sales" => $eftSales,
				"ae_sales" => $aeSales,
				"eft_no" => $eftReceiptNo,
				"ae_no" => $aeReceiptNo
				);
		
		return $arrRes;		
	}
	public function getInvoiceNoArray($arrInvoice){
		$arrayRes = array();
		foreach($arrInvoice as $inv){
			$invDate = date("N",strtotime($inv["DATE"]));
			$invHour = date("H",strtotime($inv["TIME"]));
			if(isset($arrayRes[(int)$invDate][(int)$invHour] )){
				$arrayRes[(int)$invDate][(int)$invHour] += 1;
			}
			else{
				$arrayRes[(int)$invDate][(int)$invHour] = 1;
			}
		}
		
		return $arrayRes;
	}
	
	public function getSaleManSales($dateBegin,$dateEnd){
		
		$select = $this->_db->select();
		$select->from($this->_name,array("SUM(AMT_TOTAL) AS SALES","SAL_CODE"))
			   ->where("DATE >= '".$dateBegin."'")
			   ->where("DATE <= '".$dateEnd."'")
			   ->group("SAL_CODE")
			   ->order("SALES DESC");	
		$res = $this->getAdapter()->fetchAll($select);
		
		return $res;
	}
	
	public function getSaleManQty($dateBegin,$dateEnd){
		$select = $this->_db->select();
		$select->from($this->_name,array("SUM(QTY_TOTAL) AS QTYS","COUNT(INV_NO) AS COUNTS","SAL_CODE","SUM(AMT_TOTAL) AS SALES"))
		->where("DATE >= '".$dateBegin."'")
		->where("DATE <= '".$dateEnd."'")
		->group("SAL_CODE")
		->order("QTYS DESC");	
					
		$res = $this->getAdapter()->fetchAll($select);
		
		return $res;		
	}
	
	public function activeInvoice(){
		$data = array(
				"STATUS" => 'V'
				);
		
		$this->update($data,"STATUS LIKE ''");
	}
	
}

?>