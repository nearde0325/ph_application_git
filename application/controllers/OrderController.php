<?php
class OrderController extends Zend_Controller_Action
{
	public function indexAction(){
		
	}
	public function saveAddNewOrderAction(){
		$this->_helper->_layout->setLayout('layout3');
		
		$orders = new Model_DbTable_Order_Orderlist();
		$oProducts = new Model_DbTable_Order_Products();
		
		if($_POST){

			$idOrder = $orders->addOrderlist($_POST["order_id"],Model_DatetimeHelper::dateToday(), $_POST["staff_name"],$_POST["order_comm"],1);
			
			$arrProducts = unserialize(base64_decode(trim($_POST["id_products"])));
			
			foreach($arrProducts as $v){
				
			$oProducts->addProducts($idOrder, $v,0,0,0); // order qty, adjust qty , final qty				
			} 		
		}
		
	}

	public function saveAddExistOrderAction(){
		$this->_helper->_layout->setLayout('layout3');
	
		$orders = new Model_DbTable_Order_Orderlist();
		$oProducts = new Model_DbTable_Order_Products();
		$order = $orders->getOrderlist($_POST["id_order"]);
		$this->view->orderNo = $order["order_no"];
	
		if($_POST){
	
			//$idOrder = $orders->addOrderlist($_POST["order_id"],Model_DatetimeHelper::dateToday(), $_POST["staff_name"],$_POST["order_comm"],1);
				
			$arrProducts = unserialize(base64_decode(trim($_POST["id_products"])));
				
			foreach($arrProducts as $v){
	
				$oProducts->addProducts($_POST["id_order"], $v,0,0,0); // order qty, adjust qty , final qty
			}
		}
	
	
	}
	
	public function orderDetailAction(){
		
		$this->_helper->_layout->setLayout('layout3');
		
		$orders = new Model_DbTable_Order_Orderlist();
		$oProducts = new Model_DbTable_Order_Products();
		
		$orderNo = trim($this->_getParam("id"));
		
		$order = $orders->getOrderByNumber($orderNo);
		$this->view->order = $order;
		
		$showMessage = $this->_getParam("done");
		$this->view->showMessage = $showMessage;
		
	
		
		
		$pList = $oProducts->listProductByOrderId($order["id_order"]);
		
		$this->view->pList = $pList;
		
	}
	
	public function orderDetailPrintViewAction(){
	
		
		$orders = new Model_DbTable_Order_Orderlist();
		$oProducts = new Model_DbTable_Order_Products();
		
		$orderNo = trim($this->_getParam("id"));
		
		$order = $orders->getOrderByNumber($orderNo);
		$this->view->order = $order;		
		
		$pList = $oProducts->listProductByOrderId($order["id_order"]);
		
		$this->view->pList = $pList;		
		
	}
	
	public function orderDetailExportAction(){
		
		$this->_helper->_layout->disableLayout();
		
		$orders = new Model_DbTable_Order_Orderlist();
		$oProducts = new Model_DbTable_Order_Products();
		$products = new Model_DbTable_Productsva();
		
		
		$orderNo = trim($this->_getParam("id"));
		
		$order = $orders->getOrderByNumber($orderNo);

		$pList = $oProducts->listProductByOrderId($order["id_order"]);
		
		$arrResult = array();
		
		foreach($pList as $v){
			
			$arrtmp = array();
			$productRow = $products->getProduct($v["id_product"]);
			
			$arrtmp[] = $productRow["code_product"];
			$arrtmp[] = $productRow["title_product"];
			$arrtmp[] = $v["qty_order"];
			
			$arrResult[] = $arrtmp;
		}
		//echo "<pre>";
		//var_dump($arrResult);	
		$fl = new Model_Fileshandler();
		$fl->exportOrderExcel($orderNo, $arrResult);
		
	}
	
	
	public function addProductOrderAction(){
		
		$oProducts = new Model_DbTable_Order_Products();

		$products = new Model_DbTable_Productsva();
		
		if($_POST){
			$productCode = $_POST["product_barcode"];
			$idProduct = $products->getProductID(trim($productCode));
			$idOrder = $_POST["id_order"];
			$qty = $_POST["product_qty"];
			$oProducts->addProducts($idOrder, $idProduct, $qty, $qty,$qty);
		}
		
		$this->_redirect("/order/order-detail/done/add/id/".$_POST["order_no"]);
			
	}
	public function removeProductOrderAction(){
		
		if($_POST){
			
			//var_dump($_POST);
			$oProducts = new Model_DbTable_Order_Products();
			
			foreach($_POST["id_order_product"] as $k => $v){
				
				$oProducts->deleteProducts($v);
			}
		
		}
		
		$this->_redirect("/order/order-detail/done/remove/id/".$_POST["order_no"]);
	}
	
	public function saveOrderModifyAction(){
		
		if($_POST){
			
			//var_dump($_POST);
		
			$oProducts = new Model_DbTable_Order_Products();
				
			foreach($_POST["id_product_list"] as $k => $v){
			
				$oProducts->updateProductsQty($v, $_POST["qty".$v], $_POST["qty".$v], $_POST["qty".$v]);
			}		
			
			
		}
		
		$this->_redirect("/order/order-detail/done/save/id/".$_POST["order_no"]);
	}

	public function cancelOrderAction(){
		
		$this->_helper->_layout->setLayout('layout5');
		
		$order = new Model_DbTable_Order_Orderlist();
		if($_POST){

			$idOrder = $_POST["id_order"];
			
			$order->cancelOrder($idOrder);
			
		}
	
		//redirect 
		
	}
	
	public function lockOrderAction(){
		
		$order = new Model_DbTable_Order_Orderlist();
		if($_POST){
		
			$idOrder = $_POST["id_order"];
				
			$order->lockOrder($idOrder);
				
		}		
		$this->_redirect("/order/order-detail/done/lock/id/".$_POST["order_no"]);
	}
	
	public function orderListSummaryAction(){
		
		$this->_helper->_layout->setLayout('layout5');
		

		//how many new order not finallized 
		
		//how many finallized order not fullfilled , how many products not start to find , how many waiting 
		
		//fullfilled order serach by products , later 	
		$order = new Model_DbTable_Order_Orderlist();

		
		$this->view->orderActive = $orderActive = $order->listAllUnFinallizedOrder();
		$this->view->orderFollowUp = $orderFollowUp = $order->listAllFollowUpOrder();
		$this->view->orderFinished = $orderFinished = $order->listAllFinishedOrder();
		$this->view->orderCanceled = $orderCanceled = $order->listAllCanceledOrder();

		
		
		
	}
	public function orderListStatusAction(){
		
	}
	
	

	public function addFollowUpAction(){
		
	}
	public function updateFollowUpAction(){
		
	}
	public function addShipmentAction(){
		
	}
	public function updateShipmentAction(){
		
	}
}
?>