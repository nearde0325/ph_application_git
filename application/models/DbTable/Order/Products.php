<?php
class Model_DbTable_Order_Products extends Zend_Db_Table_Abstract {

	protected $_name = 'orders_products';
	
	public function getProducts( $idOrderProduct){
		
		$row = $this->fetchRow("`id_order_product` = ". $idOrderProduct);
		if(!$row) return false;
		return $row->toArray();		
		
		}
		
	public function addProducts( $idOrder , $idProduct , $qtyOrder , $qtyAdjust , $qtyFinal){
		
		$idOrderProduct = self::getProductInOrder($idProduct, $idOrder);
		
		if($idOrderProduct !== false){
			
			$orderProduct = self::getProducts($idOrderProduct);
			$qtyOrder += $orderProduct["qty_order"];
			$qtyAdjust += $orderProduct["qty_adjust"];
			$qtyFinal += $orderProduct["qty_final"];
			
			self::updateProductsQty($idOrderProduct, $qtyOrder, $qtyAdjust, $qtyFinal)	;
		}
		
		else{
		
		
		
		$data = array(
			
         "id_order" =>  $idOrder ,
         "id_product" =>  $idProduct ,
         "qty_order" =>  $qtyOrder ,
         "qty_adjust" =>  $qtyAdjust ,
         "qty_final" =>  $qtyFinal 
	
			);
		$this->insert($data);
		
		}
		
		
		}
		
	public function updateProducts(  $idOrderProduct ,  $idOrder , $idProduct , $qtyOrder , $qtyAdjust , $qtyFinal){
		$data = array(
			
         "id_order" =>  $idOrder ,
         "id_product" =>  $idProduct ,
         "qty_order" =>  $qtyOrder ,
         "qty_adjust" =>  $qtyAdjust ,
         "qty_final" =>  $qtyFinal 
	
			);
			
		$this->update($data,"`id_order_product` = ". $idOrderProduct);
		}
		
	public function updateProductsQty(  $idOrderProduct , $qtyOrder , $qtyAdjust , $qtyFinal){
		$data = array(
			
         "qty_order" =>  $qtyOrder ,
         "qty_adjust" =>  $qtyAdjust ,
         "qty_final" =>  $qtyFinal 
	
			);
			
		$this->update($data,"`id_order_product` = ". $idOrderProduct);
		}		
		
	public function deleteProducts( $idOrderProduct){
		
		$this->delete("`id_order_product` = ". $idOrderProduct);
		
		}	
	
	public function listAll(){
		
		$rows =$this->fetchAll("1","id_order_product DESC ");
		if(!$rows) return false;
		return $rows->toArray();			
		
		}
	public function listProductByOrderId($idOrder){
		$rows = $this->fetchAll("`id_order` = ".$idOrder);
		if(!$rows) return false;
		return $rows->toArray();		
	}

	public function getProductInOrder($idProduct,$idOrder){
		$row = $this->fetchRow("`id_order` = ".$idOrder." AND `id_product` = ".$idProduct);
		if(!$row) return false;
		return $row["id_order_product"];
	}
}
?>