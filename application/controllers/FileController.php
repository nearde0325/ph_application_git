<?php 
/**

 */
class SalesController extends Zend_Controller_Action
{

    public function init(){
    /**
	 *
	 *
	 */    
	
	}

    public function indexAction(){
	
		echo "Watch Dog Controller";	

    
	}


		
	public function testAction(){
		echo "try something2";
		$newEmail = new Model_Emailshandler();
		echo $newEmail->buildExpireDate(1);
		echo $newEmail->buildExpireDate(2);
		}	
	

			
}
?>