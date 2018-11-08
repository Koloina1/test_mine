<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

		public function _initUserSession()
	   	{
	       	Zend_Session::start();

			$router = new Zend_Controller_Router_Rewrite();

	        $request = new Zend_Controller_Request_Http();
	        $router->route($request);
	        $action = $request->getActionName();
	        $controller = $request->getControllerName();
	        
			if($controller != 'index' || $action != 'index')
			{

// die("tapaka");
		        // if(is_null($_SESSION['utilisateur']))
		        if(is_null($_SESSION['utilisateur']))

		        {	
		        	$frontController = Zend_Controller_Front::getInstance();
	        	    $response = new Zend_Controller_Response_Http();
	        	    $response->setRedirect('../');
	        	    $frontController->setResponse($response);
				}
				

				// $frontController = Zend_Controller_Front::getInstance();
	        	//     $response = new Zend_Controller_Response_Http();
	        	//     $response->setRedirect('../');
	        	//     $frontController->setResponse($response);
		        
				
			}
	   	}
	
}

