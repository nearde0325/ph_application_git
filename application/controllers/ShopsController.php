<?php 
/**

 */
class ShopsController extends Zend_Controller_Action
{

    public function indexAction(){
	
	}
	public function controlPanelAction(){
		
	}
	public function configFormulaAction(){
		
		$shopTarget = new Model_DbTable_Target_Shopfomula();
		
		$tList = $shopTarget->listAll();
		
		$this->view->tList = $tList;
		
		if($_POST){
			//echo 
			if($_POST["password"]=="Mon80ash"){

				foreach($_POST["id_fomula"] as $k => $v){
					$rateAChoice = $_POST["rate_a_choice"][$k];
					if(isset($_POST["rate_a_choice"][$k])){
					//	$rateAChoice = 1;
					}
					$shopTarget->updateShopfomula($v,$_POST["shop_code"][$k],$_POST["choice"][$k],$_POST["fix_amt"][$k],$_POST["rate_a"][$k],$_POST["rate_p"][$k],$_POST["rate_amt"][$k], $rateAChoice,$_POST["id_staff"][$k]);
					
				}
				//echo '
				//<script language="javascript">
				//alert("Fomula Saved!!");
				//</script>
				//';
				//ob_flush();

			//sleep(1);	
			//$this->_forward("config-fomula");	
			$this->_redirect("/shops/config-formula");	
			}
			else{
				
			echo "Password Incorrect";	
			}
		}
		
		
	}
	public function inputTargetAction(){
		$wTar = new Model_DbTable_Target_Weeklytarget();
		//$wTlist = $wTar->listAll();
		//$this->view->wtList = $wTlist;
		
		if($_POST){
			//echo
			if($_POST["password"]=="Mon80ash"){
		
				$dateBegin = $_POST['date_begin'];
				$dateEnd = $_POST['date_end'];
				$cot = 0;
				$actDateBegin = $dateBegin;
				$actDateEnd = Model_DatetimeHelper::adjustDays("add", $actDateBegin,6);
				
				while($actDateEnd <= $dateEnd){
					$target = $_POST['target'];
					foreach($_POST['shop_code'] as $key => $v){
						$wTar->addWeeklytarget($v, $actDateBegin, $actDateEnd, $target[$key]);
					}
					
					$actDateBegin = Model_DatetimeHelper::adjustWeeks("add", $actDateBegin,1);
					$actDateEnd = Model_DatetimeHelper::adjustWeeks("add", $actDateEnd,1);
					

					//echo $cot++;
				}
				
				
					
				//$this->_redirect("/shops/input-target");
			}
			else{
		
				echo "Password Incorrect";
			}		
		}
	 }
	 
	public function shopHourLimitsAction(){
		
		$shops = new Model_DbTable_Shoplocation();
		
		if($_POST){
			
			foreach($_POST['hour_limits'] as $key => $v){
				
				$shops->updateHourLimits($key, $v);
			}
			
		}
		
			$sList = $shops->listAll();
			$this->view->sList = $sList;
	}

			
}
?>