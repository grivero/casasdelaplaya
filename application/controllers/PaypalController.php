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
    public function indexAction()
    {
    				
    	/**
    	require '../library/paypal/paypal_adapter.php';
    	
    	// Create an instance of our PayPal NVP client
		$client = new PaypalAdapter('https://api-3t.sandbox.paypal.com/nvp');
        	
	    if ( !isset($_REQUEST['submit']) ){
		      		      		 
		      $amount 				= 100.00; 
		      $credit_card_type 	= 'Visa';
		      $credit_card_number 	= '4681538754208624';
		      $expiration_month 	= 10;
		      $expiration_year 		= 2017;
		      $cvv2 				= 666;
		 
		      $first_name 			= 'Gustavo';
		      $last_name 			= 'Rivero';
		      $address1 			= 'Mazeh 12 Pinat Lamah';
		      $address2 			= '';
		      $city 				= 'Tel Aviv';
		      $state 				= 'IL';
		      $zip 					= 12345;
		 
		      $country 			= 'US'; 
		      $currency_code 	= 'USD';
		       		 		      		      
		      // get real ip address
		      $ip_address 		= $client->getRealIpAddr();
		 
		      // Send our API request!
		      $result = $client->doDirectPayment(
		            $amount,
		            $credit_card_type,
		            $credit_card_number,
		            $expiration_month,
		            $expiration_year,
		            $cvv2,
		            $first_name,
		            $last_name,
		            $address1,
		            $address2,
		            $city,
		            $state,
		            $zip,
		            $country,
		            $currency_code,
		            $ip_address);
		 
		      // Remember to store the transaction ID! You'll need it 
		      // to lookup the transaction details. For now, let's just
		      // display the results.		      
		      echo urldecode($result->getBody());
		      
		}else{
			$amount = 1.00; 
		      $credit_card_type 	= $_REQUEST['credit_card_type'];
		      $credit_card_number 	= $_REQUEST['credit_card_number'];
		      $expiration_month 	= $_REQUEST['expiration_month'];
		      $expiration_year 		= $_REQUEST['expiration_year'];
		      $cvv2 				= $_REQUEST['cvv2'];
		 
		      $first_name 			= $_REQUEST['first_name'];
		      $last_name 			= $_REQUEST['last_name'];
		      $address1 			= $_REQUEST['address1'];
		      $address2 			= $_REQUEST['address2'];
		      $city 				= $_REQUEST['city'];
		      $state 				= $_REQUEST['state'];
		      $zip 					= $_REQUEST['zip'];
		 
		      $country 			= 'US'; // Assuming we are only accepting transactions within the United States.
		      $currency_code 	= 'USD'; // Assuming we are using the United States Dollar

		      // get real ip address
		      $ip_address 		= $client->getRealIpAddr();
		 		      		 
		      // Send our API request!
		      $result = $client->doDirectPayment(
		            $amount,
		            $credit_card_type,
		            $credit_card_number,
		            $expiration_month,
		            $expiration_year,
		            $cvv2,
		            $first_name,
		            $last_name,
		            $address1,
		            $address2,
		            $city,
		            $state,
		            $zip,
		            $country,
		            $currency_code,
		            $ip_address);
		 
		      // Remember to store the transaction ID! You'll need it 
		      // to lookup the transaction details. For now, let's just
		      // display the results.
		      echo $result->getBody();
		  
			}
			**/    	    					    	
    }

    /**
     * Paga a PayPal mediante Express Checkout
     *
     */
    public function payAction()
    {
		 		
    	$url	= 'http://www.casasdelaplayadelapedrera.com'.Zend_Controller_Front::getInstance()->getBaseUrl();
    	if( isset($_REQUEST['token']) && isset($_REQUEST['PayerID']) ){
    		
    		//vars
    		$token 			= $_REQUEST['token'];
			$payer_id 		= $_REQUEST['PayerID'];
			$currency_code 	= 'USD';					
			
			//precio a pagar
			$amount = 0.0;
			
			//busco reservas												
			//$reservas_session	= new Zend_Session_Namespace('id_pendientes');
			//lo destrabo
			//$reservas_session->unlock();
			//busco los datos necesarios
			$reservas_arr		= $this->reservas_session->ID;
			//modelo reserva pendiente
			$reservas_pendientes_model	= new Application_Model_DbTable_ReservaPendiente();	
			
			
			//calculo precios a pagar
			foreach ( $reservas_arr as $id_reserva ){
				$reserva_pendiente = $reservas_pendientes_model->find( $id_reserva )->current();
				$amount += (floatval($reserva_pendiente->precio));
			}
			$amount *= 0.1;
			$amount = round( $amount );			
		 
			// cobro
			require '../library/paypal/paypal_adapter.php';
			require '../library/utils/fecha.class.php';
			$client = new PaypalAdapter();
			$reply = $client->ecDoExpressCheckout($token, $payer_id, $amount, $currency_code);
			
			//inicializo plugin email
			$email_plugin 	= new Application_Plugin_EmailSender();
			
    		if ($reply->isSuccessful()) {
						 
				$replyData = $client->parse($reply->getBody());
				
				if( $replyData->ACK == 'Success'){
					//id transaccion
					$transaction_id	= $replyData->TRANSACTIONID;
					//token
					//$token			= $replyData->TOKEN; //por ahora no se precisa
										
					//detalles de transaccion
					$reply_transaction 	= $client->ecGetTransactionDetails($transaction_id);
					$replyData			= $client->parse($reply_transaction->getBody());
					
					//nombre comprador
					$buyer_first_name	= $replyData->FIRSTNAME;
					//apellido comprador
					$buyer_surname		= $replyData->LASTNAME;
					//nombre completo comprador
					$buyer_name			= $replyData->SHIPTONAME;				
					//email comprador
					$buyer_email		= $replyData->EMAIL;
					//ciudad comprador
					$buyer_country		= $replyData->SHIPTOCOUNTRYNAME;
					//pais comprador
					$buyer_city			= $replyData->SHIPTOCITY;
					//fecha de pago
					$date_payed			= $replyData->ORDERTIME;
					//paypal id pagador
					$payer_paypal_id	= $replyData->PAYERID;
					//monto
					$amt				= $replyData->AMT;					
												
					//modelos
					$compra_model				= new Application_Model_DbTable_Compra();
					$reservas_model				= new Application_Model_DbTable_Reservas();									
					
					$nombre_completo = $buyer_first_name.' '.$buyer_surname;
					//creo la compra
					$id_compra = $compra_model->createRow(array( 
																'nombre'				=> $buyer_first_name,
																'apellido'				=> $buyer_surname,
																'email'					=> $buyer_email,
																'pais'					=> $buyer_country,
																'ciudad'				=> $buyer_city,
																'monto'					=> $amt,
																'paypal_transaction_id'	=> $transaction_id,
																'paypal_payer_id'		=> $payer_id,
																'fecha_creacion'		=> date('Y-m-d H:i:s')
															))->save();
																		
					//paso las reservas pendientes
					foreach ( $reservas_arr as $id_reserva ){
						$reserva_pendiente = $reservas_pendientes_model->find( $id_reserva )->current();
						//creo reserva
						$id_reserva_creado = $reservas_model->createRow(array(
														'cant_camas'			=> $reserva_pendiente->cant_camas,
														'fecha_inicio'			=> $reserva_pendiente->fecha_inicio,
														'fecha_fin'				=> $reserva_pendiente->fecha_fin,
														'cant_dias'				=> $reserva_pendiente->cant_dias,
														'id_habitacion'			=> $reserva_pendiente->id_habitacion,
														'id_compra'				=> $id_compra,
														'nombre'				=> $nombre_completo,
														'precio'				=> $reserva_pendiente->precio,
														'date_created'			=> date('Y-m-d H:i:s')											
													))->save();
						if(!$fecha_inicio){
							$fecha_obj = new Fecha($reserva_pendiente->fecha_inicio);
							$fecha_inicio = $fecha_obj->getFecha()->format('d/m/Y');
						}
																			
					}
										
					//mando email					
					$mail_res		= $email_plugin->sendDepositConfirmationEmail($buyer_email, $buyer_name, $fecha_inicio, $amt);
					$mail_res2		= $email_plugin->sendDepositConfirmationEmailToClient($buyer_email, $fecha_inicio, $amt);
					
					//redirijo
					if( isset($_GET['lan']) && $_GET['lan']=='eng'){																		
						$this->_redirect($url.'?lan=eng&type=success&id_compra='.$id_compra);
					}else{	
						$this->_redirect($url.'?type=success&id_compra='.$id_compra);
					}
										
				}else{
					
					//redirijo con error					
					if( isset($_GET['lan']) && $_GET['lan']=='eng'){	
						$email_plugin->sendPayPalError($replyData->L_LONGMESSAGE0);
						$this->_redirect($url.'?lan=eng&type=error&msg='.$replyData->L_LONGMESSAGE0.'&monto='.$amount);
					}else{
						$email_plugin->sendPayPalError($replyData->L_LONGMESSAGE0);														
						$this->_redirect($url.'?type=error&msg='.$replyData->L_LONGMESSAGE0.'&monto='.$amount);
					}
										
				}
 									 										 						        
			}else{
				//redirijo con error					
				if( isset($_GET['lan']) && $_GET['lan']=='eng'){
					$email_plugin->sendPayPalError('en url - '.$url.'?lan=eng&type=error&msg=express_checkout_response_not_successful');																		
					$this->_redirect($url.'?lan=eng&type=error&msg=express_checkout_response_not_successful');
				}else{
					$email_plugin->sendPayPalError('en url - '.$url.'?lan=eng&type=error&msg=express_checkout_response_not_successful');														
					$this->_redirect($url.'?type=error&msg=express_checkout_response_not_successful');
				}								
			}

    	}else{
    		//redirijo con error					
			if( isset($_GET['lan']) && $_GET['lan']=='eng'){																		
				$this->_redirect($url.'?type=error&msg=required_params_missing&lan=eng');
			}else{														
				$this->_redirect($url.'?type=error&msg=required_params_missing');
			}    		
    	}
		
    }

    public function setexpresscheckoutAction()
    {
            		
    	if( isset($_POST['amt']) && $_POST['amt']!='' ){
    		
    		//vars
    		$amount = (float)$_POST['amt'];    		
	        require '../library/paypal/paypal_adapter.php';
	        $url	= 'http://www.casasdelaplayadelapedrera.com'.Zend_Controller_Front::getInstance()->getBaseUrl();
	        							
	        try{
	        	//consigo link para autorizar compra
	        	$client = new PaypalAdapter();
	        	$reply 	= $client->ecSetExpressCheckout($amount, $url.'/frontend/create-reserva?status=ok', $url.'/frontend/create-reserva?status=cancel', 'USD', $payment_action = 'Authorization');
	        	
		        if( $reply->isSuccessful() ){
		        			        			        			        
		        	$replyData 	= $client->parse( $reply->getBody() );

		        	if( $replyData->ACK == 'Success' ){
						$token 		= $replyData->TOKEN;											        	               	        						
						$link = urlencode( $client->api_expresscheckout_uri . '?&cmd=_express-checkout&token=' . $token );
						$output = '{ "success": "yes", "link": "'.$link.'" }';
		        	}else{
		        		$output = '{ "success": "no", "msg": "'.$replyData->L_LONGMESSAGE0.'" }';
		        	}
		        	
		        }else{
		        	$output = '{ "success": "no", "msg": "replay_not_success"  }';
		        }		       
	        }catch(Exception $e){ 
	        	$output = '{ "success": "no", "msg": "'.$e->getMessage().'"  }';
	        }
	        
    	}else{
    		$output = '{ "success": "no", "msg": "required_param_missing"  }';
    	}
    	
    	echo $output;
	            	
    }


}
