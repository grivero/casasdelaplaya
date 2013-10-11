<?php

class FrontendController extends Zend_Controller_Action{
	private $_mailHandler = null;
	
    public function init(){
        //email plugin
		$emailSender = new Application_Plugin_EmailSender();
		$this->_mailHandler = $emailSender;
    }

    public function indexAction(){
        // action body
    }

    /**
     * 
     * Send an email message to casasdelaplaya@gmail.com
     * createas a message and user rows
     * ajax function
     */
    public function sendMessageAction(){            	

    	//to clean response page
    	$this->_helper->layout->disableLayout();
    	
		// var calls 
	    $first_name 	= $this->getRequest()->getParam('first_name');
	    $last_name 		= $this->getRequest()->getParam('last_name');
	    $name			= $first_name." ".$last_name;
	    $email		 	= $this->getRequest()->getParam('email'); 
	    $telephone 		= $this->getRequest()->getParam('telephone');
	    $msj 			= $this->getRequest()->getParam('comments');
		$fechaIn 		= $this->getRequest()->getParam('fIngreso');
		$fechaOut 		= $this->getRequest()->getParam('fEgreso');
		$pais 			= $this->getRequest()->getParam('pais'); 
		$cant 			= $this->getRequest()->getParam('cant');
		
		//date calculations
		require_once('../library/utils/fecha.class.php');
		$fecha_obj = new Fecha($fechaIn);		
		$fechaIn = $fecha_obj->getFecha()->format('Y-m-d');
		$fecha_obj = new Fecha($fechaOut);
		$fechaOut = $fecha_obj->getFecha()->format('Y-m-d');
				
		//call models
        $users 			= new Application_Model_DbTable_User();     
        $messages 		= new Application_Model_DbTable_Message();        	
		
		//aux vars
		$output 		= '';
		$date_created 	= date('Y-m-d H:i:s');

		//search for user in database
    	$existentEmail = $users->select()
  							   ->from('user',array('email','id'))
  							   ->where('email = ?',$email)
  							   ->limit(1)
  							   ->query()->fetchAll();
  							   
 		try{  							   
			//no user in database, create a new one  							   
	  		if( !$existentEmail )  			
		        $id_user = $users->createRow(array("first_name"=>$first_name, "last_name"=>$last_name, "name"=>$name, 
	        										"email"=>$email, "country"=>$pais, "phone"=>$telephone, "date_created"=>$date_created )) 	        										
		        	  			 ->save();
	  		else
	  			$id_user = $existentEmail[0]['id'];
	  		
	  		//create message record  		
	        $messages->createRow(array("user_id"=>$id_user, "first_name"=>$first_name, "last_name"=>$last_name, 
	        							"email"=>$email, "checkin"=>$fechaIn, "checkout"=>$fechaOut, "country"=>$pais, 
	        							"howManyPeople"=>$cant, "content"=>$msj, "date_created"=>$date_created ))
	          		->save(); 
	  
			// send email	               	        	        	
    		//$result = $this->_mailHandler->sendContactEmail( $email, $first_name, $last_name, $telephone, $msj, $apellido, $pais, $cant, $fechaIn, $fechaOut, false );
        }catch(exception $e){	
        	$output = $e->getMessage();
        }

        if($output == '')
        	echo '{ "success": "yes" }';
        else        	        	        		    	
    		echo '{ "success": "no", "message": "'.$output.'" }';
			
    }


}



