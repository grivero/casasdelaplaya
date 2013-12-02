<?php

class Application_View_Helper_GetUserName extends Zend_View_Helper_Abstract{
	
	/**
	 * 
	 * Retorna String, nombre del profile
	 * @param $id_profile es un String o int indicando id_profile
	 */
	function getUserName( $user_id ){
		//file model
		$user_model = new Application_Model_DbTable_User();
		$user = $user_model->find($user_id)->current();				
		if($user)
         	return  $user->name;
         else
         	return 'no existe user';		
	}

}