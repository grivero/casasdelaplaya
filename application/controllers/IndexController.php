<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */    	    	
    	//$this->_helper->_layout->setLayout('oldweb_layout');

    	// TO PRODUCTION USE ONLY
    	$this->_helper->_layout->setLayout('layout');   
    	$this->_redirect($this->baseUrl.'/frontend');
    }

    public function indexAction()
    {
        $this->view->current_page = 'index';
    }

    public function contactoAction()
    {
        $this->view->current_page = 'contacto';
    }

    public function galeriaAction()
    {
        $this->view->current_page = 'galeria';
    }

    public function serviciosAction()
    {
        $this->view->current_page = 'servicios';
    }

    public function ubicacionAction()
    {
        $this->view->current_page = 'ubicacion';
    }

    public function alojamientosAction()
    {
        $this->view->current_page = 'alojamientos';
    }


}

