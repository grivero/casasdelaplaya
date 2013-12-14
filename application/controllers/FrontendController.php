<?php

class FrontendController extends Zend_Controller_Action
{

    private $_mailHandler = null;

    public function init()
    {
        //email plugin
		$emailSender = new Application_Plugin_EmailSender();
		$this->_mailHandler = $emailSender;
		
		//set default layout for controller views    	
    	$this->_helper->_layout->setLayout('layout');    	
    	
    }

    public function indexAction()
    {
        // action body
    }

    /**
     * Send an email message to casasdelaplaya@gmail.com
     * createas a message and user rows
     * ajax function
     */
    public function sendMessageAction()
    {

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
    		$result = $this->_mailHandler->sendContactEmail( $email, $first_name, $last_name, $telephone, $msj, $apellido, $pais, $cant, $fechaIn, $fechaOut, false );
        }catch(exception $e){	
        	$output = $e->getMessage();
        }

        if($output == '')
        	echo '{ "success": "yes" }';
        else        	        	        		    	
    		echo '{ "success": "no", "message": "'.$output.'" }';
			
    }

    public function alojamientosAction()
    {
        // action body
    }

    public function actividadesAction()
    {
    	
       	// model
    	$post_model = new Application_Model_DbTable_Post();
    	$img_model	= new Application_Model_DbTable_Image();

    	// approved posts ordered by ranking and creation date
    	$posts = $post_model->select()->from('post')
    									->where("approved = 1")
										->order('ranking DESC')
										->order('date_created DESC')															
										->query()->fetchAll();
		
		// run through posts to get their ids
		$arrPostsIds;
		$j = 0;										
		foreach ($posts as $post){
			$arrPostsIds[$j] = $post['id'];
			$j++;	
		}
		
		// if i have posts then search their images and create a map to call them
		if($arrPostsIds!=null){
			$imgs = $img_model->select()->from('image')
										->where('post_id IN (?)',$arrPostsIds)
										->query()->fetchAll();
	
			$map_img;										
			foreach ($imgs as $img){
				$map_img[ $img['post_id'] ] = $img['name'];	
			}									

			// vars to view
	    	$this->view->posts 	= $posts;
	    	$this->view->imgs	= $map_img;
		}
    	
    }

    public function addActividadAction()
 	{
 		//to clean response page
    	$this->_helper->layout->disableLayout();	    					
    }

    public function casasseisAction()
    {
        //to clean response page
    	$this->_helper->layout->disableLayout();
    }

    public function casascuatroAction()
    {
        //to clean response page
    	$this->_helper->layout->disableLayout();
    }

    public function viewActividadAction()
    {
    	
        //to clean response page
    	$this->_helper->layout->disableLayout();
    	
    	// vars
		$id = $this->getRequest()->getParam('id');
		
		// model
    	$post_model = new Application_Model_DbTable_Post();
    	$user_model = new Application_Model_DbTable_User();
    	$img_model	= new Application_Model_DbTable_Image();
    	
    	// info
    	$post 	= $post_model->find( $id )->current();    	
    	$img 	= $img_model->find( $post['image_id'] )->current();
    	
    	// if the post was already approved, then i can proceed
    	if($post['approved']=='1'){	    	
	    	$this->view->post = $post;    	
	    	$this->view->img  = $img;
    	}
    	
    }

    public function createActividadAction()
    {
        //to clean response page
    	// model
    	$post_model = new Application_Model_DbTable_Post();
    	$user_model = new Application_Model_DbTable_User();
    	$img_model	= new Application_Model_DbTable_Image();    	
    		
    	// image treatment
    	$image 	= $_FILES["image"];		
		try{
			//subo y guardo las imagenes
			require_once('../library/utils/image.class.php');
			$image_obj = new Image();
			$imageName = $image_obj->uploadImage($image);		
		}catch(Exception $e){
			Zend_Debug::dump('error: '.$e);
			$this->_redirect($this->baseUrl.'/frontend/actividades?status=fail&action=addImage&error='.$e);								       		
		}
		
		// vars										
		$date_created 	= date('Y-m-d H:i:s');
		$approver_id	= '1';
		$description	= $this->getRequest()->getParam('description');
		$title			= $this->getRequest()->getParam('title');
		$first_name 	= $this->getRequest()->getParam('first_name');
	    $last_name 		= $this->getRequest()->getParam('last_name');
	    $name			= $first_name." ".$last_name;
	    $email		 	= $this->getRequest()->getParam('email'); 
	    $telephone 		= $this->getRequest()->getParam('telephone');
	    $web			= $this->getRequest()->getParam('web');	    						

 		//search for user in database
    	$existentEmail = $user_model->select()
  							   ->from('user',array('email','id'))
  							   ->where('email = ?',$email)
  							   ->limit(1)
  							   ->query()->fetchAll();
  							    		  							   
		//no user in database, create a new one  							   
  		if( !$existentEmail )  			
	        $id_user = $user_model->createRow(array("first_name"=>$first_name, "last_name"=>$last_name, "name"=>$name, 
        										"email"=>$email, "phone"=>$telephone, "date_created"=>$date_created )) 	        										
	        	  			 ->save();
  		else
  			$id_user = $existentEmail[0]['id'];
  			  		        		
		// save img
		if ( $imageName!="" && $imageName!=NULL ){
			 				       	  				
			$image_id = $img_model->createRow(array("name" =>$imageName, "user_id" => $id_user, "date_created" => $date_created))
							 	  ->save();
							 	  
			$post_id = $post_model->createRow(array("image_id"=>$image_id, "user_id"=>$id_user, "date_created"=>$date_created,
									 			"ranking"=>"1", "approved"=>"0", "approver_id"=>$approver_id, "title"=>$title,
												"description"=>$description, "web"=>$web ))->save();

			//update image row with post_id
			$image_to_update = $img_model->find($image_id)->current();
			$image_to_update->post_id = $post_id;
			$image_to_update->save();	

			//email to notify about the new Post				               	        	        
    		$this->_mailHandler->sendNewPostEmail( $title, $description, $name, $email );
			
			//redirection
			$this->_redirect($this->baseUrl.'/frontend/actividades?status=ok&action=addPost');				
			
		}						

		//error
		$this->_redirect($this->baseUrl.'/frontend/actividades?status=fail&action=addPost');
		
    }

    public function quienessomosAction()
    {
        //to clean response page
    	$this->_helper->layout->disableLayout();
    }

    public function lapedreraAction()
    {
        //to clean response page
    	$this->_helper->layout->disableLayout();
    }
    
  	public function mapAction()
    {
        //to clean response page
    	$this->_helper->layout->disableLayout();
    }


}
