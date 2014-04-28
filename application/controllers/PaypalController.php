<?php

class PaypalController extends Zend_Controller_Action
{
	
	private $reservas_session;

    public function init()
    {
    	$this->reservas_session = new Zend_Session_Namespace('id_pendientes');
        $this->_helper->layout->disableLayout();
    }

    /**
     * Paga a PayPal mediante Payments Pro
     *
     */
    public function indexAction(){
    				    	    	    					    	
    }

    /**
     * Paga a PayPal mediante Express Checkout
     *
     */
    public function payAction(){
    	
		// url template		 		
    	$url	= 'http://www.casasdelaplayadelapedrera.com'.Zend_Controller_Front::getInstance()->getBaseUrl();
    	
    	// 3 params needed: token PayerID reservation_id
    	if( isset($_REQUEST['token']) && isset($_REQUEST['PayerID']) && isset($_REQUEST['reservation_id']) ){
    		
    		// vars from request
    		$token 			= $_REQUEST['token'];
			$payer_id 		= $_REQUEST['PayerID'];
			$reservation_id = $_REQUEST['reservation_id'];
			$currency_code 	= 'USD';
			
			// data base models
			$reservation_model	= new Application_Model_DbTable_Reservation();
			$user_model			= new Application_Model_DbTable_User();
			
			// get previously saved reservation
			$reserva = $reservation_model->find( $reservation_id )->current();
			
			//precio a pagar	PRECIO DE TEST!!!!  Float.valueOf( $reserva->cost )		
			$amount = 1.0;
			$amount = round( $amount );			
		 
			// cobro
			require '../library/paypal/paypal_adapter.php';
			require '../library/utils/fecha.class.php';
			$client = new PaypalAdapter();
			$reply = $client->ecDoExpressCheckout($token, $payer_id, $amount, $currency_code);
			
			//inicializo plugin email
			$email_plugin 	= new Application_Plugin_EmailSender();
			
    		if( $reply->isSuccessful() ){

    			// parse the response from paypal
				$replyData = $client->parse( $reply->getBody() );
				
				if( $replyData->ACK == 'Success'){
					//id transaccion
					$transaction_id	= $replyData->TRANSACTIONID;
					//token
					//$token		= $replyData->TOKEN; //por ahora no se precisa
										
					// get details from the transaction we just made
					$reply_transaction 	= $client->ecGetTransactionDetails( $transaction_id );
					// get details about the client just made the transaction
					$replyData			= $client->parse( $reply_transaction->getBody() );
					
					// nombre comprador
					$buyer_first_name	= $replyData->FIRSTNAME;
					// apellido comprador
					$buyer_surname		= $replyData->LASTNAME;
					// nombre completo comprador
					$buyer_name			= $replyData->SHIPTONAME;
					$nombre_completo 	= $buyer_first_name.' '.$buyer_surname;				
					// email comprador
					$buyer_email		= $replyData->EMAIL;
					// ciudad comprador
					$buyer_country		= $replyData->SHIPTOCOUNTRYNAME;
					// pais comprador
					$buyer_city			= $replyData->SHIPTOCITY;
					// fecha de pago
					$date_payed			= $replyData->ORDERTIME;
					// paypal id pagador
					$payer_paypal_id	= $replyData->PAYERID;
					// monto
					$amt				= $replyData->AMT;
					// today's date
					$date_created 		= date('Y-m-d H:i:s');
																																									
					//search for user in database
			    	$existentEmail = $user_model->select()
			  							   ->from('user',array('email','id'))
			  							   ->where('email = ?',$buyer_email)
			  							   ->limit(1)
			  							   ->query()->fetchAll();
			  							    		  							   
					//no user in database, create a new one  							   
			  		if( !$existentEmail ){  			
				        $id_user = $user_model->createRow(array(
				        										"first_name"	=> $buyer_first_name, 
				        										"last_name"		=> $buyer_surname, 
				        										"name"			=> $nombre_completo, 
			        											"email"			=> $buyer_email, 
			        											"country"		=> $buyer_country, 
			        											"date_created"	=> $date_created,
				        										"city"			=> $buyer_city 
				        					))->save();
			  		} else {
			  			// user already exists, get id to populate in reservation in order to confirm it
			  			$id_user = $existentEmail[0]['id'];
			  		}
			  					  							
			  		// update reservation
			  		$reserva->user_id 				= $id_user;
			  		$reserva->paypal_transaction_id = $transaction_id;
			  		$reserva->paypal_user_id 		= $payer_id;
			  		$reserva->cost 					= $amt;
			  		$reserva->payed					= 0;
			  		$reserva->save();			  																							
										
					//send email to Client AND Admin about this new transaction									
					//$mail_res		= $email_plugin->sendDepositConfirmationEmail($buyer_email, $buyer_name, $fecha_inicio, $amt);
					//$mail_res2	= $email_plugin->sendDepositConfirmationEmailToClient($buyer_email, $fecha_inicio, $amt);
					
					//redirijo
					//$this->_redirect($url.'?type=success&id_reservation='.$id_reserva);
					echo 'DE LAS NAVES!!!  reservacion: '.$id_reserva.' USUARIO: '.$id_user;
										
				}else{
								
					//$email_plugin->sendPayPalError($replyData->L_LONGMESSAGE0);														
					$this->_redirect($url.'?type=error&msg='.$replyData->L_LONGMESSAGE0.'&monto='.$amount);
												
				}
 									 										 						        
			}else{
				
				//$email_plugin->sendPayPalError('en url - '.$url.'?lan=eng&type=error&msg=express_checkout_response_not_successful');														
				$this->_redirect($url.'?type=error&msg=express_checkout_response_not_successful');
										
			}

    	}else{
    		    															
    		// PayerID and/or Token params are missing, can't do nothing
			$this->_redirect($url.'?type=error&msg=required_params_missing');
						    		
    	}
		
    }

    public function setexpresscheckoutAction(){
    	            		
    	//if( isset($_POST['amt']) && $_POST['amt']!='' ){
         if( true ){ // ESTO ES UNA PRUEBA
            				
    		//vars
    		//$amount = (float)$_POST['amt'];
    		$amount = 1.0;    		
	        require '../library/paypal/paypal_adapter.php';
	        $url	= 'http://www.casasdelaplayadelapedrera.com'.Zend_Controller_Front::getInstance()->getBaseUrl();
	        							
	        try{
	        	
	        	// params for temporary reservation
	        	$house_id 			= $this->getRequest()->getParam('house_id');
	        	$checkin			= $this->getRequest()->getParam('checkin');
	        	$checkout			= $this->getRequest()->getParam('checkout');
	        	// this is the total cost, the reservation is 50% of the cost
	        	$cost				= $this->getRequest()->getParam('cost');	
	        	$special_request	= $this->getRequest()->getParam('special_request');
	        	$how_many_people	= $this->getRequest()->getParam('how_many_people');	        		        			        	
	        	$date_created 		= date('Y-m-d H:i:s');
	        	
	        	//date calculations
				require_once('../library/utils/fecha.class.php');
				$fecha_obj = new Fecha($checkin);		
				$checkin = $fecha_obj->getFecha()->format('Y-m-d');
				$fecha_obj = new Fecha($checkout);
				$checkout = $fecha_obj->getFecha()->format('Y-m-d');
				
				// math calculations
				$how_many_nights = floor(abs(( strtotime($checkin) - strtotime($checkout)  )/86400));
	        	
	        	// db model
	        	$reservation_model = new Application_Model_DbTable_Reservation();
	        	// reservation
	        	$reservation_id    = $reservation_model->createRow(array(
	        										"house_id"			=> $house_id,
	        										"checkin"			=> $checkin,
	        										"checkout"			=> $checkout,
	        										"cost"				=> $cost,
	        										"special_request" 	=> $special_request,
	        										"payed"				=> "1",
	        										"date_created"		=> $date_created,
	        										"howManyPeople"		=> $how_many_people,
	        										"howManyNights"		=> $how_many_nights
	        									))->save();
	        					 	        	
	        	// get link to pay the reservation from pay pal api
	        	$client = new PaypalAdapter();
	        	$reply 	= $client->ecSetExpressCheckout($amount, $url.'/paypal/pay?status=ok&reservation_id='.$reservation_id, $url.'/frontend/create-reserva?status=cancel&reservation_id='.$reservation_id, 'USD', $payment_action = 'Authorization');
	        	
	        	// reply successful (maybe with error but connection with api successful)
		        if( $reply->isSuccessful() ){
					
					// parse into array the paypal reply data 							        			        			        			        
		        	$replyData 	= $client->parse( $reply->getBody() );
					
		        	// no errors
		        	if( $replyData->ACK == 'Success' ){

		        		// needed token
						$token 	= $replyData->TOKEN;
						// get link to pay											        	               	        						
						$link 	= urlencode( $client->api_expresscheckout_uri . '?&cmd=_express-checkout&token=' . $token );
						// create output
						$output = '{ "success": "yes", "link": "'.$link.'" }';
						
		        	}else{		        		
		        		// delete reservation    	
    					$reservation_model->delete( array('id = ?' => $reservation_id) );
		        		// response		        		
		        		$output = '{ "success": "no", "msg": "'.$replyData->L_LONGMESSAGE0.'" }';		        		
		        	}
		        	
		        }else{		        	
	        		// delete reservation    	
    				$reservation_model->delete( array('id = ?' => $reservation_id) );
	        		// response		        	 
		        	$output = '{ "success": "no", "msg": "replay_not_success"  }';		        	
		        }		      
		         
	        }catch(Exception $e){ 	        	
        		// delete reservation
        		if($reservation_id!=null && $reservation_id!="")    	
    				$reservation_model->delete( array('id = ?' => $reservation_id) );
        		// response
	        	$output = '{ "success": "no", "msg": "'.$e->getMessage().'"  }';	        	
	        }
	        
    	}else{    		
    		// nothing happened, no parameters to perform action
    		$output = '{ "success": "no", "msg": "required_param_missing"  }';    		
    	}
    	
    	// return response to ajax call
    	echo $output;
	            	
    }


}
