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

    public function postListAction(){
       	    	
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

    public function addHouseAction()
    {
				
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

    public function addUserAction()
    {
        // action body
    }
    
	public function deleteUserAction(){
         
		// vars
         $id_user = $_GET['id'];
         // models
         $user_model 	= new Application_Model_DbTable_User();
         $res_model 	= new Application_Model_DbTable_Reservations();
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
    	$guest_model	= new Application_Model_DbTable_Guest();
		// data    	
    	$reservation = $res_model->find($id)->current();    									
		$guests = $guest_model->select()->from('guest')
										->where("reservation_id = $id")
        								->order('date_created DESC')																							
										->query()->fetchAll();
		//data to view		
		$this->view->reservation 	= $reservation;
		$this->view->guests			= $guests;
				
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

}


