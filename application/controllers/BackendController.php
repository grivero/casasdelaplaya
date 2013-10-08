<?php

class BackendController extends Zend_Controller_Action
{

    public function init()
    {
       	//i ask if was already loged
    	if( !Zend_Auth::getInstance()->hasIdentity() && $this->getRequest()->getActionName()!='login' ){			    	
    		$this->_redirect('/backend/login');    	
    	}
    	//set default layout for controller views    	
    	$this->_helper->_layout->setLayout('admin');
    }

    public function indexAction()
    {
        // action body
    }

    public function loginAction()
    {
        // action body
    }

    public function houseListAction()
    {
    	
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

    public function userListAction()
    {
        // action body
        $user_model = new Application_Model_DbTable_User();
        $users = $user_model->select()->from('user')
										->order('name DESC')															
										->query()->fetchAll();	
		$this->view->users = $users;	
		if ($this->_request->getParam('deleted') == 'ok')
			$this->view->msg = 'Mensaje fue borrado';
    }

    public function messageListAction()
    {
    	// action body
        $message_model = new Application_Model_DbTable_Message();
        $messages = $message_model->select()->from('message')
										->order('date_created DESC')															
										->query()->fetchAll();	
		$this->view->messages = $messages;	
		if ($this->_request->getParam('deleted') == 'ok')
			$this->view->msg = 'Mensaje fue borrado';
		
    }

    public function seasonListAction()
    {
        // action body
    }

    public function postListAction()
    {
        // action body
    }

    public function reservationListAction()
    {
        // action body
    }

    public function deleteHouseAction()
    {
         // action body
         $id_habitacion = $_GET['id'];
         $hab_model = new Application_Model_DbTable_House();
	     $id_hab = $hab_model->delete( array('id = ?' => $id_habitacion));
	     $this->_forward('house-list','backend','default',array("deleted"=>'ok'));
    }

    public function viewHouseAction()
    {
        // action body
        $id = $_GET['id'];    
        $hab_model = new Application_Model_DbTable_House();
        $habitaciones = $hab_model->select()->from('house')
        								->where("id = $id")
										->limit(1)															
										->query()->fetchAll();	
		$this->view->house = $habitaciones[0];	
		
    }

    public function editHouseAction()
    {
    	
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
        	$this->_forward('house-list','backend','default');
    	}
        
    }

    public function deleteMessageAction()
    {
    	
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

    public function viewUserAction()
    {
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


}














