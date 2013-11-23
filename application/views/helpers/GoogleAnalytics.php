<?php

class Application_View_Helper_GoogleAnalytics extends Zend_View_Helper_Abstract{

	/**
	 * 
	 * Retorna el src para google analytics 
	 */
	function getAnalyticsCode(){
				
	    $src =  '<script type="text/javascript">
					  var _gaq = _gaq || [];
					  _gaq.push([\'_setAccount\', \'UA-27376864-1\']);
					  _gaq.push([\'_trackPageview\']);
					
					  (function() {
					    var ga = document.createElement(\'script\'); ga.type = \'text/javascript\'; ga.async = true;
					    ga.src = (\'https:\' == document.location.protocol ? \'https://ssl\' : \'http://www\') + \'.google-analytics.com/ga.js\';
					    var s = document.getElementsByTagName(\'script\')[0]; s.parentNode.insertBefore(ga, s);
					  })();
				</script>'; 	    
		return $src;
				
	}	                        
	
	
}