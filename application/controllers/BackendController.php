<?php

class BackendController extends Zend_Controller_Action
{

    public function init()
    {
       	//pregunto si ya esta autenticado
    	if( !Zend_Auth::getInstance()->hasIdentity() && $this->getRequest()->getActionName()!='administrador' ){			    	
    		$this->_redirect('/admin/administrador');    	
    	}    	
    	$this->_helper->_layout->setLayout('admin');
    }

    public function indexAction()
    {
        // action body
    }


}

