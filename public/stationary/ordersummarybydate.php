<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Stationary Order Summary</title>
</head>

<body style="font-family:Arial, Helvetica, sans-serif;">
<h2> Satationary Order Summary</h2>
<?php
include('../config/config.inc.php');

$dateBegin = $_GET["date_begin"];
$dateEnd = $_GET["date_end"];

date_default_timezone_set('Australia/Melbourne');
					
		$arrShop = array("2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19","20","21","22","23","24","25","26","29");
		$arrProductList = array();
		
		foreach($arrShop as $k=> $v){
			
			$arrShopProductList = array();
			
		?>
 <table width="650" border="0" cellspacing="1" cellpadding="5" bgcolor="#333333">
  <tr>
    <td colspan="3" bgcolor="#FFFFFF">Shop Name :<?php 
	switch($v){
		case 2 : echo "ADPC";break;
		case 3 : echo "BBPC";break;
		case 4 : echo "BHPC";break;
		case 5 : echo "BSPC";break;
		case 6 : echo "BSIC";break;
		case 7 : echo "CBPC";break;
		case 8 : echo "CLPC";break;
		case 9 : echo "COPC";break;
		case 10 : echo "EPPC";break;
		case 11 : echo "FGPC";break;
		case 12 : echo "KCPC";break;
		case 13 : echo "NLPC";break;
		case 14 : echo "PMPC";break;
		case 15 : echo "SLIC";break;
		case 16 : echo "WBPC";break;
		case 17 : echo "WBIC";break;
		case 18 : echo "WLIC";break;
		case 19 : echo "WGIC";break;
		case 20 : echo "WGPC";break;
		case 21 : echo "CSIC";break;
		case 22 : echo "CLIC";break;
		case 23 : echo "DCIC";break;
		case 24 : echo "PMIC";break;
		case 25 : echo "BSXP";break;
		case 26 : echo "FGIC";break;
		case 29 : echo "HPIC";break;		
		}

	?></td>
  </tr>       			
  <tr>
    <td bgcolor="#FFFFFF" style="width:100px">OrderID</td>
    <td bgcolor="#FFFFFF">ProductName</td>
    <td bgcolor="#FFFFFF" style="width:80px">Qty</td>
  </tr>			
		<?php	
		$orders = Order::getCustomerOrdersByDate(intval($v),$dateBegin,$dateEnd);	
		//$orders = Order::getCustomerOrders(intval($v));
		//if(isset($_GET) && $_GET["week"]=="lastweek"){
		//$orders = Order::getCustomerOrdersLastWeek(intval($v));	
			//}
		
			
			foreach($orders as $k2 => $v2){
			
				$orderId = $v2['id_order'];			
		
				$products = Db::getInstance()->executeS("SELECT * FROM `"._DB_PREFIX_."order_detail` WHERE `id_order` = ".$orderId);
		
				foreach($products as $k3 => $v3){
					
			
  
	
	$theKey = array_search($v3['product_name'],$arrProductList);
	$theKeyThisShop = array_search($v3['product_name'],$arrShopProductList);
	//echo "thekey".var_dump($theKey);
	if($theKeyThisShop === false){
			$arrShopProductList[]= $v3['product_name'];
			$arrShopProductList[]= $v3['product_quantity'];
		}
	else{
		$arrShopProductList[$theKeyThisShop+1] += $v3['product_quantity'];
		}
		
	if($theKey === false){
			$arrProductList[]= $v3['product_name'];
			$arrProductList[]= $v3['product_quantity'];
		}
	else{
		$arrProductList[$theKey+1] += $v3['product_quantity'];
		}
	?>
	

					
		<?php
					}
			}
			
			$arrShopProductList = array_chunk($arrShopProductList,2);
	$qty= array();
	
	foreach($arrShopProductList as $k4 => $v4){
		$qty[$k4] = $v4[1];	
		}		
	
	array_multisort($qty,SORT_DESC,$arrShopProductList);		
			
	foreach($arrShopProductList as $k5 => $v5){
		?>
		<tr style="font-size:12px;">
    <td bgcolor="#FFFFFF" >&nbsp;</td>
    <td bgcolor="#FFFFFF"><?php echo $v5[0];?></td>
	<td bgcolor="#FFFFFF">x <?php echo $v5[1];?> [Pcs]</td>	
		<?
		}		
			
		?>		
</table><br />
<br />

		<?php

		}
?>
<h2>Total Summary</h2>
<?
$totalQty = array();
$arrProductList = array_chunk($arrProductList,2);
foreach($arrProductList as $k4 => $v4){
	$totalQty[$k4] = $v4[1];
	}
array_multisort($totalQty,SORT_DESC,$arrProductList);
?>

 <table width="650" border="0" cellspacing="1" cellpadding="5" bgcolor="#333333" style="font-size:12px">
  <tr>
    <td bgcolor="#FFFFFF" style="width:100px">All Shop</td>
    <td bgcolor="#FFFFFF">ProductName</td>
    <td bgcolor="#FFFFFF" style="width:80px">Qty</td>
  </tr>
   
<?
//var_dump($arrProductList);
foreach($arrProductList as $k6 => $v6){
?> 
  <tr>
    <td bgcolor="#FFFFFF" style="width:100px">&nbsp;</td>
    <td bgcolor="#FFFFFF"><?php echo $v6[0]?></td>
    <td bgcolor="#FFFFFF" style="width:80px">x <?php echo $v6[1]?> [Pcs]</td>
  </tr>
  
<?
}
?> 
 </table>
 
</body>
</html>