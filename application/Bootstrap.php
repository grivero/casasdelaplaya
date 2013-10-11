<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap{
		
	protected function _initAutoload(){
	
		//registramos los models
		$modelLoader = new Zend_Application_Module_Autoloader( array(
				'namespace'=>'',
				'basePath'=>APPLICATION_PATH));
					
		return $modelLoader;
	
	}
	
	/**
	 * Inicializacion de viewers helpers!
	 */
	protected function _initViewHelpers(){
		
		//	error_reporting(0);
		$this->bootstrap('layout');
		//pido el resource layout
		$layout = $this->getResource('layout');
		
		//asigno a view la vista del layout
		$view = $layout->getView(); 
								
		//defino doctype HTML5
		$view->doctype('HTML5');
		
		//defino y seteo <meta/>
		$view->headMeta()->appendHttpEquiv('Content-type', 'text/html;charset=utf-8')
									//name of metaTag - content metaTag | Seacrh engines!						 
						 ->appendName('description','Con los pies sobre la arena de la playa El Desplayado, en La Pedrera, te esperamos para que disfrutes de unas vacaciones únicas. Ubicadas en primera línea y frente a la zona de baño con vistas de 180° al mar, brindamos el confort de tu casa en la playa. Vive el sueño de estar a orillas del mar en un ambiente familiar y distendido con todos los servicios que necesitas para gozar de este paraíso natural.');
						 
		
		//defino y seteo titulo PRINCIPAL de pagina
		$view->headTitle()->setSeparator(' - ');
		$view->headTitle('Casas de la Playa - La Pedrera');							
				
		//Activamos ZendX_Jquery
		$view->setHelperPath(APPLICATION_PATH.'/views/helpers', '');
		//preparo recurso para usar jQuery
		ZendX_JQuery::enableView($view);			
		$view->jQuery()->enable();		
			
		//lunch helpers
		$view->registerHelper( new Application_View_Helper_GetAdminLayout(), 'getAdminLayout' );		
		$view->registerHelper( new Application_View_Helper_GetAdminFooter(), 'getAdminFooter' );
		
	}
	
	//Iniciador de Session
	protected function __initSession() {
	    Zend_Session::start();	   
	    
	}
	
	
	
}

