<?php
/**
 * 
 * Backend system controller
 * @author gustavo rivero
 * @date 1/10/2013
 * @email gus.rivero.rodriguez@gmail.com
 */
class BackendController extends Zend_Controller_Action{

    public function init(){
    	    	
       	//i ask if was already loged
    	if( !Zend_Auth::getInstance()->hasIdentity() && $this->getRequest()->getActionName()!='login' ){			    	
    		$this->_redirect('/backend/login');    	
    	}
    	//set default layout for controller views    	
    	$this->_helper->_layout->setLayout('admin');
    	
    }

    public function indexAction(){
        // action body
    }

    public function loginAction(){
        // action body
    }

    public function houseListAction(){
    	
    	// action body
        $hab_model = new Application_Model_DbTable_House();
        $habitaciones = $hab_model->select()->from('house')
										->order('name ASC')															
										->query()->fetchAll();	
		$this->view->habitaciones = $habitaciones;	
		if ($this->_request->getParam('deleted') == 'ok'){
			$this->view->msg = 'La Casa fue borrada';
		}
		
    }

    public function userListAction(){
    	
        // action body
        $user_model = new Application_Model_DbTable_User();
        $users = $user_model->select()->from('user')
										->order('name DESC')															
										->query()->fetchAll();	
		$this->view->users = $users;	
		if ($this->_request->getParam('deleted') == 'ok'){
			$this->view->msg = 'Mensaje fue borrado';
		}
			
    }

    public function messageListAction(){
    	
    	// action body
        $message_model = new Application_Model_DbTable_Message();
        $messages = $message_model->select()->from('message')
										->order('date_created DESC')															
										->query()->fetchAll();	
		$this->view->messages = $messages;	
		if ($this->_request->getParam('deleted') == 'ok'){
			$this->view->msg = 'Mensaje fue borrado';
		}
		
    }

    public function seasonListAction(){
    	
    	// models    	
    	$season_model = new Application_Model_DbTable_Season();    	
		// data    	
    	$seasons = $season_model->select()->from('season')
										->order('date_modified DESC')															
										->query()->fetchAll();													
		$this->view->seasons = $seasons;	
		if ($this->_request->getParam('edited') == 'ok'){
			$this->view->msg = 'La temporada fue editada';
		}
		
    }
    
	public function editSeasonAction(){    	
    	//vars
    	$id 			= $_GET['id'];    	
    	$season_model 	= new Application_Model_DbTable_Season();
    	$season 		= $season_model->find($id)->current();
    	
        if( $this->getRequest()->getParam('edit')=='true' ){
        	//helper        	
        	require_once('../library/utils/fecha.class.php');
        	//dates manipulation
        	$start_date  		= trim( $this->_request->getParam('start_date') );    						
			$fecha_obj 			= new Fecha($start_date);		
			$start_date 		= $fecha_obj->getFecha()->format('Y-m-d');
			$season->start_date	= $start_date;    		
        	
			$end_date  			= trim( $this->_request->getParam('end_date') );    						
			$fecha_obj 			= new Fecha($end_date);		
			$end_date 			= $fecha_obj->getFecha()->format('Y-m-d');
			$season->end_date	= $end_date;    		

			$color			= $this->getRequest()->getParam('color');
			$season->color 	= $color;
			$season->save();    		  		    		
        	$this->_forward('season-list','backend','default',array("edited"=>"ok"));
        }else{
			//show season info	        	
			$this->view->season = $season;
        }
	}
    
    public function reservationListAction(){
    	    
    	// models    	
    	$res_model 		= new Application_Model_DbTable_Reservation();    	
		// data    	
    	$reservations = $res_model->select()->from('reservation')
										->order('date_created DESC')															
										->query()->fetchAll();													
		$this->view->reservations = $reservations;
		if ($this->_request->getParam('deleted') == 'ok'){
			$this->view->msg = 'La reserva fue borrada';
		}
		
    }

    public function deleteHouseAction(){
    	
         // action body
         $id_habitacion = $_GET['id'];
         $hab_model = new Application_Model_DbTable_House();
	     $id_hab = $hab_model->delete( array('id = ?' => $id_habitacion));
	     $this->_forward('house-list','backend','default',array("deleted"=>'ok'));
	     
    }

    public function viewHouseAction(){
        // action body
        $id = $_GET['id'];    
        $hab_model = new Application_Model_DbTable_House();
        $habitaciones = $hab_model->select()->from('house')
        								->where("id = $id")
										->limit(1)															
										->query()->fetchAll();	
		$this->view->house = $habitaciones[0];	
		
    }

    public function editHouseAction(){    	
    	//vars
    	$id 		= $_GET['id'];    	
    	$hab_model 	= new Application_Model_DbTable_House();
    	
        if( $this->getRequest()->getParam('edit')=='true' ){
        	//edit house info
        	$house_edit					= $hab_model->find($id)->current();
        	$house_edit->name			= trim( $this->_request->getParam('name') );    		
    		$house_edit->description	= trim( $this->_request->getParam('description') );
    		$house_edit->high_price		= trim( $this->_request->getParam('high_price') );
    		$house_edit->low_price		= trim( $this->_request->getParam('low_price') );
    		$house_edit->medium_price	= trim( $this->_request->getParam('medium_price') );
    		$house_edit->special_price  = trim( $this->_request->getParam('special_price') );
			$house_edit->save();    		  		    		
        	$this->_forward('view-house','backend','default',array("edited"=>"ok","id"=>$id));
        }else{
			//show house info
	        $habitaciones = $hab_model->select()->from('house')
	        								->where("id = $id")
											->limit(1)															
											->query()->fetchAll();	
			$this->view->house = $habitaciones[0];
        }
        
    }

    public function addHouseAction(){
				
    	if($this->getRequest()->getParam('add')=='true'){
	    	//vars    	    	
	    	$hab_model 	= new Application_Model_DbTable_House();    	        
	        // house info        
	        $name			= trim( $this->_request->getParam('name') );    		
	    	$description	= trim( $this->_request->getParam('description') );
	    	$high_price		= trim( $this->_request->getParam('high_price') );
	    	$low_price		= trim( $this->_request->getParam('low_price') );
	    	$medium_price	= trim( $this->_request->getParam('medium_price') );
	    	$special_price  = trim( $this->_request->getParam('special_price') );		
			$date_created 	= date('Y-m-d H:i:s');
			
			$id = $hab_model->createRow(array("name"=>$name, "description"=>$description, "high_price"=>$high_price, 
		        								"low_price"=>$low_price, "medium_price"=>$medium_price, "special_price"=>$special_price, "date_created"=>$date_created )) 	        										
			        	  			 ->save();
        	$this->_forward('house-list','backend','default',array("added"=>"ok","id"=>$id));
    	}
        
    }

    public function deleteMessageAction(){
    	
         // action body
         $id_message = $_GET['id'];
         $message_model = new Application_Model_DbTable_Message();
	     $message_model->delete( array('id = ?' => $id_message));
	     $this->_forward('message-list','backend','default',array("deleted"=>'ok'));
	     
    }

    public function editUserAction(){
    	
    	//vars
    	$id 		= $_GET['id'];    	
    	$user_model 	= new Application_Model_DbTable_User();
    	
        if( $this->getRequest()->getParam('edit')=='true' ){
        	//edit user info
        	$user_edit					= $user_model->find($id)->current();
        	$user_edit->first_name		= trim( $this->_request->getParam('first_name') );    		
    		$user_edit->last_name		= trim( $this->_request->getParam('last_name') );
    		$name = $user_edit->first_name.' '.$user_edit->last_name;
    		$user_edit->name			= $name;
    		$user_edit->civil_identifier= trim( $this->_request->getParam('civil_identifier') );
    		$user_edit->country			= trim( $this->_request->getParam('country') );
    		$user_edit->city			= trim( $this->_request->getParam('city') );
    		$user_edit->phone  			= trim( $this->_request->getParam('phone') );
    		$user_edit->email  			= trim( $this->_request->getParam('email') );
    		//date calculations
    		$date_birth  				= trim( $this->_request->getParam('date_birth') );
    		if( $date_birth!='' ){
				require_once('../library/utils/fecha.class.php');
				$fecha_obj = new Fecha($date_birth);		
				$fechaIn = $fecha_obj->getFecha()->format('Y-m-d');
				$user_edit->date_birth	= $fechaIn;
    		}
			//save changes
			$user_edit->save();    		  		    		
        	$this->_forward('view-user','backend','default',array("edited"=>"ok","id"=>$id));
        }else{
			//show house info
	        $users = $user_model->select()->from('user')
	        								->where("id = $id")
											->limit(1)															
											->query()->fetchAll();	
			$this->view->user = $users[0];
        }
    }

    public function addUserAction(){
        
    	// vars
    	$id 			= $_GET['id'];    	
    	$user_model 	= new Application_Model_DbTable_User();
    	
        if( $this->getRequest()->getParam('add')=='true' ){
        	
        	// user info        	
        	$first_name		= trim( $this->_request->getParam('first_name') );    		
    		$last_name		= trim( $this->_request->getParam('last_name') );
    		$name 			= $first_name.' '.$last_name;    		
    		$civil_identifier= trim( $this->_request->getParam('civil_identifier') );
    		$country		= trim( $this->_request->getParam('country') );
    		$city			= trim( $this->_request->getParam('city') );
    		$phone  		= trim( $this->_request->getParam('phone') );
    		$email  		= trim( $this->_request->getParam('email') );
    		$date_created   = date('Y-m-d H:i:s');
    		
    		// date calculations
    		$date_birth  	= trim( $this->_request->getParam('date_birth') );
    		if( $date_birth!='' ){
				require_once('../library/utils/fecha.class.php');
				$fecha_obj = new Fecha($date_birth);		
				$fechaIn = $fecha_obj->getFecha()->format('Y-m-d');
				$user_edit->date_birth	= $fechaIn;
    		}			
    		
    		// save info
    		$user_model->createRow(array("first_name"=>$first_name, "last_name"=>$last_name, "name"=>$name, "email"=>$email, 
		        						"civil_identifier"=>$civil_identifier, "country"=>$country, "city"=>$city, "phone"=>$phone,
    									"date_created"=>$date_created, "date_birth"=>$date_birth ))
    					->save();
			// redirect    		  		    		
        	$this->_forward('user-list','backend','default',array("added"=>"ok","id"=>$id));
        	
        }
        
    }
    
	public function deleteUserAction(){
         
		// vars
         $id_user = $_GET['id'];
         // models
         $user_model 	= new Application_Model_DbTable_User();
         $res_model 	= new Application_Model_DbTable_Reservation();
         $post_model	= new Application_Model_DbTable_Post();
         $msg_model		= new Application_Model_DbTable_Message();
         
         // deletes
         //TODO: im not deleting the guests from reservations, and the phisical files from posts
	     $user_model->delete( array('id = ?' => $id_user) );
	     $res_model->delete( array('user_id = ?' => $id_user) );
	     $post_model->delete( array('user_id = ?' => $id_user) );
	     $msg_model->delete( array('user_id = ?' => $id_user) );
	     
	     $this->_forward('user-list','backend','default',array("deleted"=>'ok'));
	     
    }

    public function viewUserAction(){
    	
		//vars
    	$id 			= $_GET['id'];    	
    	$user_model 	= new Application_Model_DbTable_User();
    	$msg_model		= new Application_Model_DbTable_Message();
    	$res_model		= new Application_Model_DbTable_Reservation();
    	$post_model		= new Application_Model_DbTable_Post();
    	
    	//info from db
    	$user			= $user_model->find($id)->current();
    	$posts			= $post_model->select()->from('post')
        								->where("user_id = $id")
        								->order('date_created DESC')																		
										->query()->fetchAll();
		$messages		= $msg_model->select()->from('message')
        								->where("user_id = $id")
        								->order('date_created DESC')																							
										->query()->fetchAll();
    	$reservations	= $res_model->select()->from('reservation')
        								->where("user_id = $id")
        								->order('date_created DESC')																							
										->query()->fetchAll();

		//info for view										
		$this->view->user 			= $user;
		$this->view->messages		= $messages;
		$this->view->posts			= $posts;
		$this->view->reservations	= $reservations;
		
    }

    public function viewReservationAction(){
    	
    	// vars
    	$id 			= $_GET['id'];
    	// models    	
    	$res_model 		= new Application_Model_DbTable_Reservation();
    	$user_model		= new Application_Model_DbTable_User();
    	$guest_model	= new Application_Model_DbTable_Guest();
    	$house_model	= new Application_Model_DbTable_House();
    	
		// data    	
    	$reservation 	= $res_model->find($id)->current();    									
		$guests 		= $guest_model->select()->from('guest')
										->where("reservation_id = $id")
        								->order('date_created DESC')																							
										->query()->fetchAll();
		$user 			= $user_model->find($reservation['user_id'])->current();
		$house			= $house_model->find($reservation['house_id'])->current();
		
		//data to view		
		$this->view->reservation 	= $reservation;
		$this->view->guests			= $guests;
		$this->view->user			= $user;
		$this->view->house			= $house;
				
    }

	public function addReservationAction(){
		
    	// models    	
    	$user_model = new Application_Model_DbTable_User();
    	$house_model= new Application_Model_DbTable_House();
    	$res_model	= new Application_Model_DbTable_Reservation();
    	
    	if( $this->getRequest()->getParam('add')!='true' ){
    		
	    	// houses and users to select in page    	 		    	
    		$users 	= $user_model->fetchAll()->toArray();
    		$houses = $house_model->fetchAll()->toArray();
    		
			//data to view		
			$this->view->users 	= $users;
			$this->view->houses	= $houses;
											
    	}else{
    		
    		// vars to save
    		$checkin			= trim( $this->getRequest()->getParam('checkin') );
    		$checkout			= trim( $this->getRequest()->getParam('checkout') );
    		$user_id			= trim( $this->getRequest()->getParam('user_id') );
    		$house_id			= trim( $this->getRequest()->getParam('house_id') );
    		$payed				= trim( $this->getRequest()->getParam('payed') );
    		$special_request 	= trim( $this->getRequest()->getParam('special_request') );
    		$cost				= trim( $this->getRequest()->getParam('cost') );
    		$howManyPeople		= trim( $this->getRequest()->getParam('howManyPeople') );
    		$date_created 		= date('Y-m-d H:i:s');
    		
    		// date manipulations    		
			require_once('../library/utils/fecha.class.php');
			$fecha 		= new Fecha($checkin);		
			$checkin 	= $fecha->getFecha()->format('Y-m-d');
			$fecha		= new Fecha($checkout);
			$checkout 	= $fecha->getFecha()->format('Y-m-d');
    		
    		// save
    		$id = $res_model->createRow(array("checkout"=>$checkout, "checkin"=>$checkin, "payed"=>$payed, 
		        							  "cost"=>$cost, "special_request"=>$special_request, "user_id"=>$user_id, 
		        							  "house_id"=>$house_id, "howManyPeople"=>$howManyPeople, "date_created"=>$date_created )) 	        										
			        	  			 ->save();
    		// redirect
    		$this->_forward('reservation-list','backend','default',array("added"=>"ok","id"=>$id));
    		
    	}
    	
    }
    
	public function editReservationAction(){
    	
		// models    	
    	$user_model = new Application_Model_DbTable_User();
    	$house_model= new Application_Model_DbTable_House();
    	$res_model	= new Application_Model_DbTable_Reservation();
    	$guest_model= new Application_Model_DbTable_Guest();    	
    	// vars
    	$id 			= $_GET['id'];    	
    	// for edit
    	$reservation 				= $res_model->find($id)->current();
    	$this->view->reservation 	= $reservation;
    	
    	if($this->getRequest()->getParam('edit')=='true'){
    		
    	}else{
    		    	    	
			// data    	    	    									
			$guests 		= $guest_model->select()->from('guest')
											->where("reservation_id = $id")
	        								->order('date_created DESC')																							
											->query()->fetchAll();
			$user 			= $user_model->find($reservation['user_id'])->current();
			$house			= $house_model->find($reservation['house_id'])->current();
			$houses 		= $house_model->fetchAll()->toArray();
			
			//data to view					
			$this->view->guests			= $guests;
			$this->view->user			= $user;
			$this->view->house			= $house;
			$this->view->houses			= $houses;
			
    	}
    	    	
    }

	public function deleteReservationAction(){
		
		// vars
    	$id 			= $_GET['id'];
    	// models    	
    	$res_model 		= new Application_Model_DbTable_Reservation();
    	$guest_model	= new Application_Model_DbTable_Guest();
		// deletes    	
    	$res_model->delete( array('id = ?' => $id) );
	    $guest_model->delete( array('reservation_id = ?' => $id) );
	    // redirection
	    $this->_forward('reservation-list','backend','default',array("deleted"=>'ok'));
	     
    }

	public function postListAction(){
		
		// model
    	$post_model = new Application_Model_DbTable_Post();

    	if( $this->getRequest()->getParam('approved')=="1" ){
    		
    		// vars to view
    		$posts = $post_model->select()->from('post')
    									->where('approved = 1')
										->order('ranking DESC')															
										->query()->fetchAll();
										
    	}else if( $this->getRequest()->getParam('approved')=="0" ){
    		
    		// vars to view
    		$posts = $post_model->select()->from('post')
    									->where('approved = 0')
										->order('ranking DESC')															
										->query()->fetchAll();
										
    	}else{
    		
			// vars to view
    		$posts = $post_model->select()->from('post')
										->order('ranking DESC')															
										->query()->fetchAll(); 
										   		
    	}
    	
    	$this->view->posts = $posts;
    	
    }
    
    public function addPostAction(){
    	
    	// model
    	$post_model = new Application_Model_DbTable_Post();
    	$user_model = new Application_Model_DbTable_User();
    	$img_model	= new Application_Model_DbTable_Image();

    	if( $this->getRequest()->getParam('add')!='true' ){
    		
	    	//get all users
	    	$users 	= $user_model->fetchAll()->toArray();    		    		
			//data to view		
			$this->view->users 	= $users;
					
    	}else if( $this->getRequest()->getParam('add')=='true' ){
    		
    		// image treatment
    		$image 	= $_FILES["image"];		
			try{
				//subo y guardo las imagenes
				require_once('../library/utils/image.class.php');
				$image_obj = new Image();
				$imageName = $image_obj->uploadImage($image);		
			}catch(Exception $e){
				Zend_Debug::dump('error: '.$e);
				$this->_redirect($this->baseUrl.'/backend/add-post?status=fail&error='.$e);					       		
			}
			
			// vars
			$user_id 		= $this->getRequest()->getParam('user_id');
			$ranking		= $this->getRequest()->getParam('ranking');
			$description	= $this->getRequest()->getParam('description');
			$apporved		= $this->getRequest()->getParam('approved');
			$title			= $this->getRequest()->getParam('title');			
			$date_created 	= date('Y-m-d H:i:s');
			$approver_id	= '1'; 	
			
			// save img
			if ( $imageName!="" && $imageName!=NULL ){
				 				       	  				
				$image_id = $img_model->createRow(array("name" =>$imageName, "user_id" => $user_id, "date_created" => $date_created))
								 	  ->save();
								 	  
				$post_id = $post_model->createRow(array("image_id"=>$image_id, "user_id"=>$user_id, "date_created"=>$date_created,
										 			"ranking"=>$ranking, "approved"=>$apporved, "approver_id"=>$approver_id,
													"description"=>$description, "title"=>$title ))->save();

				//update image row with post_id
				$image_to_update = $img_model->find($image_id)->current();
				$image_to_update->post_id = $post_id;
				$image_to_update->save();	

				//redirection
				$this->_redirect($this->baseUrl.'/backend/post-list');				
				
			}						

			//error
			$this->_redirect($this->baseUrl.'/backend/add-post?status=fail');			
    		
    	}
				    	
    }
    
	public function editPostAction(){
    	
    }
    
	public function viewPostAction(){
    	
		// vars
		$id = $this->getRequest()->getParam('id');
		
		// model
    	$post_model = new Application_Model_DbTable_Post();
    	$user_model = new Application_Model_DbTable_User();
    	$img_model	= new Application_Model_DbTable_Image();
    	
    	// info
    	$post 	= $post_model->find( $id )->current();
    	$user 	= $user_model->find( $post['user_id'] )->current();
    	$img 	= $img_model->find( $post['image_id'] )->current();
    	
    	// to view
    	$this->view->post = $post;
    	$this->view->user = $user;
    	$this->view->img  = $img;
    	
    }

}

