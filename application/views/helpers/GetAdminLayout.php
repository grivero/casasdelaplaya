<?php

class Application_View_Helper_GetAdminLayout extends Zend_View_Helper_Abstract{
	
	public function getAdminLayout( $section ){
		
		//baseUrl		
		$baseUrl = 	$this->view->baseUrl();

   		$sup_content =  
			'<div id="tray" class="box">'.            
				'<p class="f-left box">'.
   				'<strong>Administracion Sitio Casas de la Playa</strong></p>'.
   				'<p class="f-right">Usuario: <strong><a href="#">gianco hard worker stylen</a></strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong><a href="'.$baseUrl.'/authentication/logout" id="logout">Log out</a></strong></p>'.    	
			'</div>'.
   			'<hr class="noscreen" />'.				
				'<div id="menu" class="box">'.
					'<ul class="box f-right">'.
						'<li><a href="http://casasdelaplayadelapedrera.com/" target="_blank"><span><strong>Ir al Sitio &raquo;</strong></span></a></li>'.
					'</ul>'.
					'<ul class="box">';
   		
   		$mid_content = '';
   		if($section == 'Casas'){
	   		$mid_content =	'<li id="menu-active" ><a href="javascript:;"><span>Casas</span></a></li>'.     
							'<li><a href="'.$baseUrl.'/backend/message-list"><span>Mensajes</span></a></li>'.                		
							'<li><a href="'.$baseUrl.'/backend/post-list"><span>Posts</span></a></li>'.
				            '<li><a href="'.$baseUrl.'/backend/reservation-list"><span>Reservas</span></a></li>'.
				            '<li><a href="'.$baseUrl.'/backend/season-list"><span>Temporadas</span></a></li>'.
				            '<li><a href="'.$baseUrl.'/backend/user-list"><span>Usuarios</span></a></li>'.        	
					  	'</ul>'.
					'</div>'.			
					'<hr class="noscreen" />'.			    
				    '<div class="fix"></div>'.
	   				'<div class="fix"></div>'.		
					'<div id="cols" class="box">'.	
						'<div id="aside" class="box">'.
							'<div class="padding box">'.
								'<!-- Logo (Max. width = 200px) -->'.
			 					'<p id="logo"><a href="#"><img src="'.$baseUrl.'/img/logo/logo.png" alt="Our logo" title="Visit Site" /></a></p>'.
							'</div>'.
							'<ul class="box">'.
	   							'<li id="submenu-active"><a href="'.$baseUrl.'/backend/house-list">Casas</a></li>'.
								'<ul>'.
                            		'<li><a href="'.$baseUrl.'/backend/add-house">Agregar</a></li>'.
                            		'<li><a href="'.$baseUrl.'/backend/house-list">Listado Casas</a></li>'.						
								'</ul>'.     
								'<li><a href="'.$baseUrl.'/backend/message-list">Mensajes</a></li>'.                		
								'<li><a href="'.$baseUrl.'/backend/post-list">Posts</a></li>'.
                        		'<li><a href="'.$baseUrl.'/backend/reservation-list">Reservas</a></li>'.
                        		'<li><a href="'.$baseUrl.'/backend/season-list">Temporadas</a></li>'.
                        		'<li><a href="'.$baseUrl.'/backend/user-list">Usuarios</a></li>'.      
							'</ul>'.
						'</div>'.
						'<hr class="noscreen" />'.						
					  	'<div id="content" class="box">'.
				        	'<h1>Casas</h1>';	   		
   		}else if($section == 'Mensajes'){
   				$mid_content =	'<li><a href="'.$baseUrl.'/backend/house-list"><span>Casas</span></a></li>'.     
							'<li id="menu-active"><a href="javascript:;"><span>Mensajes</span></a></li>'.                		
							'<li><a href="'.$baseUrl.'/backend/post-list"><span>Posts</span></a></li>'.
				            '<li><a href="'.$baseUrl.'/backend/reservation-list"><span>Reservas</span></a></li>'.
				            '<li><a href="'.$baseUrl.'/backend/season-list"><span>Temporadas</span></a></li>'.
				            '<li><a href="'.$baseUrl.'/backend/user-list"><span>Usuarios</span></a></li>'.        	
					  	'</ul>'.
					'</div>'.			
					'<hr class="noscreen" />'.			    
				    '<div class="fix"></div>'.
	   				'<div class="fix"></div>'.		
					'<div id="cols" class="box">'.	
						'<div id="aside" class="box">'.
							'<div class="padding box">'.
								'<!-- Logo (Max. width = 200px) -->'.
			 					'<p id="logo"><a href="#"><img src="'.$baseUrl.'/img/logo/logo.png" alt="Our logo" title="Visit Site" /></a></p>'.
							'</div>'.
							'<ul class="box">'.
	   							'<li><a href="'.$baseUrl.'/backend/house-list">Casas</a></li>'.								     
								'<li id="submenu-active"><a href="javascript:;">Mensajes</a></li>'.
   									'<ul>'.                            			
                            			'<li><a href="'.$baseUrl.'/backend/message-list">Listado Mensajes</a></li>'.						
									'</ul>'.                		
								'<li><a href="'.$baseUrl.'/backend/post-list">Posts</a></li>'.
                        		'<li><a href="'.$baseUrl.'/backend/reservation-list">Reservas</a></li>'.
                        		'<li><a href="'.$baseUrl.'/backend/season-list">Temporadas</a></li>'.
                        		'<li><a href="'.$baseUrl.'/backend/user-list">Usuarios</a></li>'.      
							'</ul>'.
						'</div>'.
						'<hr class="noscreen" />'.						
					  	'<div id="content" class="box">'.
				        	'<h1>Mensajes</h1>';	
   		}else if($section == 'Posts'){
   				$mid_content =	'<li id="menu-active" ><a href="javascript:;"><span>Casas</span></a></li>'.     
							'<li><a href="'.$baseUrl.'/backend/message-list"><span>Mensajes</span></a></li>'.                		
							'<li><a href="'.$baseUrl.'/backend/post-list"><span>Posts</span></a></li>'.
				            '<li><a href="'.$baseUrl.'/backend/reservation-list"><span>Reservas</span></a></li>'.
				            '<li><a href="'.$baseUrl.'/backend/season-list"><span>Temporadas</span></a></li>'.
				            '<li><a href="'.$baseUrl.'/backend/user-list"><span>Usuarios</span></a></li>'.        	
					  	'</ul>'.
					'</div>'.			
					'<hr class="noscreen" />'.			    
				    '<div class="fix"></div>'.
	   				'<div class="fix"></div>'.		
					'<div id="cols" class="box">'.	
						'<div id="aside" class="box">'.
							'<div class="padding box">'.
								'<!-- Logo (Max. width = 200px) -->'.
			 					'<p id="logo"><a href="#"><img src="'.$baseUrl.'/img/logo/logo.png" alt="Our logo" title="Visit Site" /></a></p>'.
							'</div>'.
							'<ul class="box">'.
	   							'<li id="submenu-active"><a href="'.$baseUrl.'/backend/house-list">Casas</a></li>'.
								'<ul>'.
                            		'<li><a href="'.$baseUrl.'/backend/add-house">Agregar</a></li>'.
                            		'<li><a href="javascript:;">Listado Casas</a></li>'.						
								'</ul>'.     
								'<li><a href="'.$baseUrl.'/backend/message-list">Mensajes</a></li>'.                		
								'<li><a href="'.$baseUrl.'/backend/post-list">Posts</a></li>'.
                        		'<li><a href="'.$baseUrl.'/backend/reservation-list">Reservas</a></li>'.
                        		'<li><a href="'.$baseUrl.'/backend/season-list">Temporadas</a></li>'.
                        		'<li><a href="'.$baseUrl.'/backend/user-list">Usuarios</a></li>'.      
							'</ul>'.
						'</div>'.
						'<hr class="noscreen" />'.						
					  	'<div id="content" class="box">'.
				        	'<h1>Casas</h1>';
   		}else if($section == 'Reservas'){
   				$mid_content =	'<li id="menu-active" ><a href="javascript:;"><span>Casas</span></a></li>'.     
							'<li><a href="'.$baseUrl.'/backend/message-list"><span>Mensajes</span></a></li>'.                		
							'<li><a href="'.$baseUrl.'/backend/post-list"><span>Posts</span></a></li>'.
				            '<li><a href="'.$baseUrl.'/backend/reservation-list"><span>Reservas</span></a></li>'.
				            '<li><a href="'.$baseUrl.'/backend/season-list"><span>Temporadas</span></a></li>'.
				            '<li><a href="'.$baseUrl.'/backend/user-list"><span>Usuarios</span></a></li>'.        	
					  	'</ul>'.
					'</div>'.			
					'<hr class="noscreen" />'.			    
				    '<div class="fix"></div>'.
	   				'<div class="fix"></div>'.		
					'<div id="cols" class="box">'.	
						'<div id="aside" class="box">'.
							'<div class="padding box">'.
								'<!-- Logo (Max. width = 200px) -->'.
			 					'<p id="logo"><a href="#"><img src="'.$baseUrl.'/img/logo/logo.png" alt="Our logo" title="Visit Site" /></a></p>'.
							'</div>'.
							'<ul class="box">'.
	   							'<li id="submenu-active"><a href="'.$baseUrl.'/backend/house-list">Casas</a></li>'.
								'<ul>'.
                            		'<li><a href="'.$baseUrl.'/backend/add-house">Agregar</a></li>'.
                            		'<li><a href="javascript:;">Listado Casas</a></li>'.						
								'</ul>'.     
								'<li><a href="'.$baseUrl.'/backend/message-list">Mensajes</a></li>'.                		
								'<li><a href="'.$baseUrl.'/backend/post-list">Posts</a></li>'.
                        		'<li><a href="'.$baseUrl.'/backend/reservation-list">Reservas</a></li>'.
                        		'<li><a href="'.$baseUrl.'/backend/season-list">Temporadas</a></li>'.
                        		'<li><a href="'.$baseUrl.'/backend/user-list">Usuarios</a></li>'.      
							'</ul>'.
						'</div>'.
						'<hr class="noscreen" />'.						
					  	'<div id="content" class="box">'.
				        	'<h1>Casas</h1>';
   		}else if($section == 'Temporadas'){
   				$mid_content =	'<li id="menu-active" ><a href="javascript:;"><span>Casas</span></a></li>'.     
							'<li><a href="'.$baseUrl.'/backend/message-list"><span>Mensajes</span></a></li>'.                		
							'<li><a href="'.$baseUrl.'/backend/post-list"><span>Posts</span></a></li>'.
				            '<li><a href="'.$baseUrl.'/backend/reservation-list"><span>Reservas</span></a></li>'.
				            '<li><a href="'.$baseUrl.'/backend/season-list"><span>Temporadas</span></a></li>'.
				            '<li><a href="'.$baseUrl.'/backend/user-list"><span>Usuarios</span></a></li>'.        	
					  	'</ul>'.
					'</div>'.			
					'<hr class="noscreen" />'.			    
				    '<div class="fix"></div>'.
	   				'<div class="fix"></div>'.		
					'<div id="cols" class="box">'.	
						'<div id="aside" class="box">'.
							'<div class="padding box">'.
								'<!-- Logo (Max. width = 200px) -->'.
			 					'<p id="logo"><a href="#"><img src="'.$baseUrl.'/img/logo/logo.png" alt="Our logo" title="Visit Site" /></a></p>'.
							'</div>'.
							'<ul class="box">'.
	   							'<li id="submenu-active"><a href="'.$baseUrl.'/backend/house-list">Casas</a></li>'.
								'<ul>'.
                            		'<li><a href="'.$baseUrl.'/backend/add-house">Agregar</a></li>'.
                            		'<li><a href="javascript:;">Listado Casas</a></li>'.						
								'</ul>'.     
								'<li><a href="'.$baseUrl.'/backend/message-list">Mensajes</a></li>'.                		
								'<li><a href="'.$baseUrl.'/backend/post-list">Posts</a></li>'.
                        		'<li><a href="'.$baseUrl.'/backend/reservation-list">Reservas</a></li>'.
                        		'<li><a href="'.$baseUrl.'/backend/season-list">Temporadas</a></li>'.
                        		'<li><a href="'.$baseUrl.'/backend/user-list">Usuarios</a></li>'.      
							'</ul>'.
						'</div>'.
						'<hr class="noscreen" />'.						
					  	'<div id="content" class="box">'.
				        	'<h1>Casas</h1>';
   		}else if($section == 'Usuarios'){
   				$mid_content =	'<li><a href="'.$baseUrl.'/backend/house-list"><span>Casas</span></a></li>'.     
							'<li><a href="'.$baseUrl.'/backend/message-list"><span>Mensajes</span></a></li>'.                		
							'<li><a href="'.$baseUrl.'/backend/post-list"><span>Posts</span></a></li>'.
				            '<li><a href="'.$baseUrl.'/backend/reservation-list"><span>Reservas</span></a></li>'.
				            '<li><a href="'.$baseUrl.'/backend/season-list"><span>Temporadas</span></a></li>'.
				            '<li id="menu-active"><a href="javascript:;"><span>Usuarios</span></a></li>'.        	
					  	'</ul>'.
					'</div>'.			
					'<hr class="noscreen" />'.			    
				    '<div class="fix"></div>'.
	   				'<div class="fix"></div>'.		
					'<div id="cols" class="box">'.	
						'<div id="aside" class="box">'.
							'<div class="padding box">'.
								'<!-- Logo (Max. width = 200px) -->'.
			 					'<p id="logo"><a href="#"><img src="'.$baseUrl.'/img/logo/logo.png" alt="Our logo" title="Visit Site" /></a></p>'.
							'</div>'.
							'<ul class="box">'.
	   							'<li id="submenu-active"><a href="'.$baseUrl.'/backend/house-list">Casas</a></li>'.								     
								'<li><a href="'.$baseUrl.'/backend/message-list">Mensajes</a></li>'.                		
								'<li><a href="'.$baseUrl.'/backend/post-list">Posts</a></li>'.
                        		'<li><a href="'.$baseUrl.'/backend/reservation-list">Reservas</a></li>'.
                        		'<li><a href="'.$baseUrl.'/backend/season-list">Temporadas</a></li>'.
                        		'<li><a href="'.$baseUrl.'/backend/user-list">Usuarios</a></li>'.
   								'<ul>'.
                            		'<li><a href="'.$baseUrl.'/backend/add-user">Agregar</a></li>'.
                            		'<li><a href="'.$baseUrl.'/backend/user-list">Listado Usuarios</a></li>'.						
								'</ul>'.      
							'</ul>'.
						'</div>'.
						'<hr class="noscreen" />'.						
					  	'<div id="content" class="box">'.
				        	'<h1>Usuarios</h1>';
   			}else{
   				$mid_content =	'<li><a href="'.$baseUrl.'/backend/house-list"><span>Casas</span></a></li>'.     
							'<li><a href="'.$baseUrl.'/backend/message-list"><span>Mensajes</span></a></li>'.                		
							'<li><a href="'.$baseUrl.'/backend/post-list"><span>Posts</span></a></li>'.
				            '<li><a href="'.$baseUrl.'/backend/reservation-list"><span>Reservas</span></a></li>'.
				            '<li><a href="'.$baseUrl.'/backend/season-list"><span>Temporadas</span></a></li>'.
				            '<li><a href="'.$baseUrl.'/backend/user-list"><span>Usuarios</span></a></li>'.        	
					  	'</ul>'.
					'</div>'.			
					'<hr class="noscreen" />'.			    
				    '<div class="fix"></div>'.
	   				'<div class="fix"></div>'.		
					'<div id="cols" class="box">'.	
						'<div id="aside" class="box">'.
							'<div class="padding box">'.
								'<!-- Logo (Max. width = 200px) -->'.
			 					'<p id="logo"><a href="#"><img src="'.$baseUrl.'/img/logo/logo.png" alt="Our logo" title="Visit Site" /></a></p>'.
							'</div>'.
							'<ul class="box">'.
	   							'<li><a href="'.$baseUrl.'/backend/house-list">Casas</a></li>'.								
								'<li><a href="'.$baseUrl.'/backend/message-list">Mensajes</a></li>'.                		
								'<li><a href="'.$baseUrl.'/backend/post-list">Posts</a></li>'.
                        		'<li><a href="'.$baseUrl.'/backend/reservation-list">Reservas</a></li>'.
                        		'<li><a href="'.$baseUrl.'/backend/season-list">Temporadas</a></li>'.
                        		'<li><a href="'.$baseUrl.'/backend/user-list">Usuarios</a></li>'.      
							'</ul>'.
						'</div>'.
						'<hr class="noscreen" />'.						
					  	'<div id="content" class="box">';
   			}
   			
			return $sup_content.$mid_content;   				
   				
   		}
   				   
	
}

