<?php
include_once(APPLICATION_PATH."/configs/defines.inc.php");
require APPLICATION_PATH . "/../library/Kint.php";
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

	/**
	 * generate registry
	 * @return Zend_Registry
	 */
	protected function _initRegistry(){
		$registry = Zend_Registry::getInstance();
		return $registry;
	}
	
	protected function _initAutoload()
   {
       $moduleLoader = new Zend_Application_Module_Autoloader(array(
           'namespace' => '', 
           'basePath'  => APPLICATION_PATH));
       return $moduleLoader;
   }
   /**
    * add mssql database
    */
   
   public function _initDbRegistry()
   {
   	$this->bootstrap('multidb');
   	$multidb = $this->getPluginResource('multidb');
   	$multidb->init();
   	Zend_Registry::set('db_default', $multidb->getDb('default'));
   	Zend_Registry::set('db_apos', $multidb->getDb('apos'));
   	Zend_Registry::set('db_oldapos', $multidb->getDb('oldapos'));
   	Zend_Registry::set('db_real', $multidb->getDb('real'));
   	return $multidb->getDb();
   }
   protected function _initKint()
   {
   	$applicationConfig = $this->getOptions();
   
   	$kintConfig = !empty($applicationConfig['Kint'])
   	? $applicationConfig['Kint']
   	: array();
   
   	Kint::init($kintConfig);
   }
   
   
   /**
    * Initialize Doctrine
    * @return Doctrine_Manager
    */
/*
   public function _initDoctrine() {
   	// include and register Doctrine's class loader
   	require_once('Doctrine/Common/ClassLoader.php');
   	$classLoader = new \Doctrine\Common\ClassLoader(
   			'Doctrine',
   			APPLICATION_PATH . '/../library/'
   	);
   	$classLoader->register();
   
   	// create the Doctrine configuration
   	$config = new \Doctrine\ORM\Configuration();
   
   	// setting the cache ( to ArrayCache. Take a look at
   	// the Doctrine manual for different options ! )
   	$cache = new \Doctrine\Common\Cache\ArrayCache;
   	$config->setMetadataCacheImpl($cache);
   	$config->setQueryCacheImpl($cache);
   
   	// choosing the driver for our database schema
   	// we'll use annotations
   	$driver = $config->newDefaultAnnotationDriver(
   			APPLICATION_PATH . '/models'
   	);
   	$config->setMetadataDriverImpl($driver);
   
   	// set the proxy dir and set some options
   	$config->setProxyDir(APPLICATION_PATH . '/models/Proxies');
   	$config->setAutoGenerateProxyClasses(true);
   	$config->setProxyNamespace('App\Proxies');
   
   	// now create the entity manager and use the connection
   	// settings we defined in our application.ini
   	$connectionSettings = $this->getOption('doctrine');
   	$conn = array(
   			'driver'    => $connectionSettings['conn']['driv'],
   			'user'      => $connectionSettings['conn']['user'],
   			'password'  => $connectionSettings['conn']['pass'],
   			'dbname'    => $connectionSettings['conn']['dbname'],
   			'host'      => $connectionSettings['conn']['host']
   	);
   	$entityManager = \Doctrine\ORM\EntityManager::create($conn, $config);
   
   	// push the entity manager into our registry for later use
   	$registry = Zend_Registry::getInstance();
   	$registry->entitymanager = $entityManager;
   
   	return $entityManager;
   }
 */  
   
 /*  
	protected function _initView()
	{
		$view = new Zend_View();
		Zend_Dojo::enableView($view);
		$view->addHelperPath('Zend/Dojo/View/Helper/', 'Zend_Dojo_View_Helper'); 
		$viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer(); 
		$viewRenderer->setView($view); 
		$view->doctype('XHTML1_STRICT');
		$view->headMeta()->appendHttpEquiv('Content-Type', 'text/html;charset=utf-8');
		$view->headTitle()->setSeparator(' :: ');
		Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);
		return $view;
	}
*/	
}

