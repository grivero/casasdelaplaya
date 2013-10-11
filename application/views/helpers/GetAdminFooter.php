<?php

class Application_View_Helper_GetAdminFooter extends Zend_View_Helper_Abstract{
	
	/**
	 * 
	 * Pega contenido inferior del layout interior en el admin
	 */
	public function getAdminFooter(){
		
		return 		'</div>'.      
  				'</div>'.	
				'<hr class="noscreen">';
		
	}
			    	
	
}