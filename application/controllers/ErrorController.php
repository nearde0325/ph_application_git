<?php

class ErrorController extends Zend_Controller_Action
{

    public function errorAction()
    {
        $errors = $this->_getParam('error_handler');
        
        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
        
                // 404 error -- controller or action not found
                $this->getResponse()->setHttpResponseCode(404);
                $this->view->message = 'Page not found';
                break;
            default:
                // application error
                $this->getResponse()->setHttpResponseCode(500);
                $this->view->message = 'Application error';
                break;
        }
        
      	//var_dump($errors["exception"]);
        $exceptions = (array) $errors["exception"];
        //echo $exceptions["*message"];
        echo "<br />";
        echo "KKKK";
        /*
     	foreach($exceptions as $key2 => $v2){
     		var_dump($key2);
     		
     		if(strpos($key2,"message")!==false){ var_dump($v2);}
     		if(strpos($key2,"file")!==false){
     			var_dump($v2);
     		}
     		if(strpos($key2,"line")!==false){
     			var_dump($v2);
     		}
     		
     		
     		//var_dump($key2,$v2);
     		echo "<br />";
     	}
        
       */
      	
      	
      	
		Zend_Debug::dump($errors,$label = null, $echo = true);
		//$mail = new Model_Emailshandler();
		//$mail->sendNormalEmail("eco1@phonecollection.com.au","ERROROCCUR", $resStr);
    	
    }

    public function getLog()
    {
        $bootstrap = $this->getInvokeArg('bootstrap');
        if (!$bootstrap->hasPluginResource('Log')) {
            return false;
        }
        $log = $bootstrap->getResource('Log');
        return $log;
    
    
    
    
    
    }
    
    


}

