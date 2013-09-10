<?php

class AuthenticationController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }

    public function loginAction()
    {
        //get user variables
    	$user	= $this->_request->getParam('user');
    	$pass	= $this->_request->getParam('pass');
    	
    	//llamo al adaptador para authentication
    	$authAdapter = $this->getAuthAdapter();
    	
    	//se los paso al adaptador para que lo comunique a la instancia
    	$authAdapter->setIdentity( $user )
    				->setCredential( $pass );
    	
    	//pido instancia de adaptador
    	$auth 	= Zend_Auth::getInstance();
    	//le pido que me autentique via el adaptador y me devuelve un ressultado del tipo Zend_Auth
    	$result = $auth->authenticate( $authAdapter );
    	
    	//si el resultado es valido (se pudo autenticar)
    	if($result->isValid()){    		
    		$identity = $authAdapter->getResultRowObject();    		
    		$authStorage = $auth->getStorage();    		
    		$authStorage->write( $identity ); 
    		$this->_redirect('admin/index');
    	}else{
    		$this->_redirect('admin/adminstrador?type=error');	
    	}	
    	
    }
    
	/**
     * 
     * Adaptador para comunicar el authenticate de zend con una tabla de la base de datos
     * @return Zend_Auth_Adapter_DbTable
     */
    private function getAuthAdapter(){    	    
    	$authAdapter = new Zend_Auth_Adapter_DbTable( Zend_Db_Table::getDefaultAdapter() );    	    	
    	$authAdapter->setTableName('admin_user')
    				->setIdentityColumn('email')
    				->setCredentialColumn('password');    
    	return $authAdapter;    	   
    }

    public function logoutAction()
    {
    	if( Zend_Auth::getInstance()->hasIdentity()){    		    	
    		Zend_Auth::getInstance()->clearIdentity();
    		Zend_Session::destroy(false);    		
    		$this->_redirect('admin/administrador');//si esta lo mando al index
    	}
    }


}





