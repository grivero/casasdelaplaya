<?php

class Application_View_Helper_GetHouseName extends Zend_View_Helper_Abstract{
	
	/**
	 * 
	 * Retorna String, nombre del profile
	 * @param $id_profile es un String o int indicando id_profile
	 */
	function getHouseName( $House_id ){
		//file model
		$House_model = new Application_Model_DbTable_House();
		$House = $House_model->find($House_id)->current();				
		if($House)
         	return  $House->name;
         else
         	return 'no existe House';		
	}

}