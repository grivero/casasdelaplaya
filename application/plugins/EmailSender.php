<?php

class Application_Plugin_EmailSender extends Zend_Controller_Plugin_Abstract{
	
	private $_replay_account	= 'casasdelaplaya@gmail.com';
	private $_replay_pass		= '';
	private $_communicationPort	= '465';
	private $_charset			= 'utf-8';
	private $_mailInstance;

	
	/**
	 * 
	 * Constructor 
	 */
	public function __construct(){
												
	}
	
	
	/**
	 * 
	 * mail por consultas desde las paginas contacto y contactoeng
	 * @param string $email del cliente
	 * @param string $nombre del cliente
	 * @param string $tel del cliente
	 * @param string $msj del cliente
	 * @param string $secure
	 */
	public function sendContactEmail( $email_from, $first_name, $last_name, $telephone, $msj, $apellido, $pais, $cant, $fechaIn, $fechaOut, $secure = false ){						
					     
		// Agregar horas
		//Obtenemos la fecha actual:
		$fecha=time();
		//Queremos restar 3 horas a la fecha actual:
		$horas = 3;
		// Convertimos las horas a segundos y las sumamos:
		$fecha += ($horas * 60 * 60);
		// Le damos al resultado el formato deseado:
		$fecha = date("F j, Y, g:i a", $fecha );
		 
		 // Cuerpo del Email --- PHP MAIL
		$email_message = "Informacion procesada: \n\n";
		$email_message .= "Fecha de envio: ".$fecha."\n";	
	    $email_message .= "Nombre: ".trim($first_name)."\n";
	    $email_message .= "Apellido: ".trim($last_name)."\n";
	    $email_message .= "Email: ".trim($email_from)."\n";
		$email_message .= "Pais: ".trim($pais)."\n";
	    $email_message .= "Telefono: ".trim($telephone)."\n";
		$email_message .= "Fecha Ingreso: ".trim($fechaIn)."\n";
		$email_message .= "Fecha Egreso: ".trim($fechaOut)."\n";
		$email_message .= "Cantidad personas: ".trim($cant)."\n";
	    $email_message .= "Comentarios: ".trim($msj)."\n";
	    
	    // Cuerpo del Email			--- ZEND MAIL    
/**	    $email_message = "Informacion procesada: <br/><br/>";
		$email_message .= "Fecha de envio: ".$fecha."<br/>";	
	    $email_message .= "Nombre: ".trim($first_name)."<br/>";
	    $email_message .= "Apellido: ".trim($last_name)."<br/>";
	    $email_message .= "Email: ".trim($email_from)."<br/>";
		$email_message .= "Pais: ".trim($pais)."<br/>";
	    $email_message .= "Telefono: ".trim($telephone)."<br/>";
		$email_message .= "Fecha Ingreso: ".trim($fechaIn)."<br/>";
		$email_message .= "Fecha Egreso: ".trim($fechaOut)."<br/>";
		$email_message .= "Cantidad personas: ".trim($cant)."<br/>";
	    $email_message .= "Comentarios: ".trim($msj)."<br/>";
	    
	    // Asunto y Direccion
	    $email_to 		= "casasdelaplaya@gmail.com";
	    $email_subject 	= "[Casas de la Playa - Contacto - Web]";
	    $email_name		= "Casas de la Playa - Web";
									
		//inicializo zend_mail
		$this->init();
		//$this->_mailInstance->setFrom('-f'.$email_from,$first_name);
		//seteo subject
		$this->_mailInstance->setSubject($email_subject);
		//seteo a quien se lo envio
		$this->_mailInstance->addTo($email_to,$email_name);
		//seteo el cuerpo del mensaje
		$this->_mailInstance->setBodyHtml(	$email_message, 
											$this->_charset);
		//lo envio		
		return $this->send($secure);
		**/	   
		
	    // Asunto y Direccion
	    $email_to = "casasdelaplaya@gmail.com";
	    $email_subject = "[Casas de la Playa - Web]";
			
		// Email Headers
		$headers = 'From: '.$email_from."\r\n".
		'Reply-To: '.$email_from."\r\n" .
		'X-Mailer: PHP/' . phpversion();
		@mail($email_to, $email_subject, $email_message, $headers);
		
	}
	
	/**	 
	 * @description Mail para notificar sobre una nueva Actividad que necesita aprobacion
	 * @param string $email del cliente
	 * @param string $nombre del cliente
	 * @param string $description de la nueva actividad
	 * @param string $title de la nueva actividad
	 */
	public function sendNewPostEmail( $title, $description, $name, $email ){

		// Agregar horas
		//Obtenemos la fecha actual:
		$fecha=time();
		//Queremos restar 3 horas a la fecha actual:
		$horas = 3;
		// Convertimos las horas a segundos y las sumamos:
		$fecha += ($horas * 60 * 60);
		// Le damos al resultado el formato deseado:
		$fecha = date("F j, Y, g:i a", $fecha );
		 
		 // Cuerpo del Email --- PHP MAIL
		$email_message = "Una nueva actividad se ha registrado en el sitio y requiere de aprobacion\n\n";
		$email_message = "Informacion procesada: \n\n";
		$email_message .= "Fecha de envio: ".$fecha."\n";	
	    $email_message .= "Nombre: ".trim($name)."\n";	    
	    $email_message .= "Email: ".trim($email)."\n";			    				
		$email_message .= "Titulo actividad: ".trim($title)."\n";
	    $email_message .= "Descripcion actividad: ".trim($description)."\n";	    	    
		
	    // Asunto y Direccion
	    $email_to = "casasdelaplaya@gmail.com";
	    $email_subject = "[Casas de la Playa - Nueva Actividad]";
			
		// Email Headers
		$headers = 'From: '.$email."\r\n".
		'Reply-To: '.$email."\r\n" .
		'X-Mailer: PHP/' . phpversion();
		@mail($email_to, $email_subject, $email_message, $headers);
		
	}		
	
	/**
	 * 
	 * MAil para notificar de una reserva via web
	 * @param string $email del cliente
	 * @param string $nombre del cliente
	 * @param string $fecha_inicio de la reserva
	 * @param string $cant_dias de la reserva
	 * @param string $cant_camas en la reserva
	 * @param string $nombre_habitacion
	 * @param string $monto_pago de la reserva	 
	 * @param string $secure
	 */
	public function sendDepositConfirmationEmail( $email, $nombre, $fecha_inicio, $monto_pago, $secure = false ){						
					     
		// Agregar horas
		//Obtenemos la fecha actual:
		$fecha=time();
		//Queremos restar 3 horas a la fecha actual:
		$horas = 3;
		// Convertimos las horas a segundos y las sumamos:
		$fecha += ($horas * 60 * 60);
		// Le damos al resultado el formato deseado:
		$fecha = date("F j, Y, g:i a", $fecha );
		 
	    // Cuerpo del Email
		$email_message 	= "Se ha ingresado una nueva Reserva: <br/><br/>";
		$email_message 	.= "Fecha de envio: ".$fecha."<br/>";	
	    $email_message	.= "Nombre: ".trim($nombre)."<br/>";    
	    $email_message 	.= "Email: ".trim($email)."<br/>";
	    $email_message	.= "Fecha inicio:".trim($fecha_inicio)."<br/>";	    
	    $email_message	.= "Monto de pago:".trim($monto_pago)."<br/>";	  

	     // Asunto y Direccion
	    $email_to 		= "reservas@larubiahostel.com";
	    $email_subject 	= "[La Rubia Hostel - Deposito Reserva - Web]";
	    $email_name		= "La Rubia - Web";
									
		//inicializo zend_mail
		$this->init();
		//seteo subject
		$this->_mailInstance->setSubject($email_subject);
		//seteo a quien se lo envio
		$this->_mailInstance->addTo($email_to,$email_name);
		//seteo el cuerpo del mensaje
		$this->_mailInstance->setBodyHtml(	$email_message, 
											$this->_charset);
		//lo envio		
		return $this->send($secure);
		
	}
	
	public function sendDepositConfirmationEmailToClient( $email_to, $fecha_inicio, $monto_pago, $secure = false ){						
					     
		// Agregar horas
		//Obtenemos la fecha actual:
		$fecha=time();
		//Queremos restar 3 horas a la fecha actual:
		$horas = 3;
		// Convertimos las horas a segundos y las sumamos:
		$fecha += ($horas * 60 * 60);
		// Le damos al resultado el formato deseado:
		$fecha = date("F j, Y, g:i a", $fecha );
		 
	    // Cuerpo del Email
		$email_message 		= "Datos de reserva en HOSTEL LA RUBIA: <br/><br/>";
		$email_message 		.= "Fecha de envio: ".$fecha."<br/>";		    
	    $email_message		.= "Fecha inicio estadia:".trim($fecha_inicio)."<br/>";	   
	    $email_message		.= "Check in: 1:00pm <br/>";
	    $email_message		.= "Email Contacto: reservas@larubiahostel.com <br/>"; 
	    $email_message		.= "Tel Contacto: +(598) 94 451 544 <br/>";
	    $email_message		.= "Monto de pago:".trim($monto_pago)."<br/>";	  

	     // Asunto y Direccion
	    $email_to 		= $email_to;
	    $email_subject 	= "La Rubia Hostel - Confrima De Reserva";
	    $email_name		= "La Rubia Hostel";
									
		//inicializo zend_mail
		$this->init();
		//seteo subject
		$this->_mailInstance->setSubject($email_subject);
		//seteo a quien se lo envio
		$this->_mailInstance->addTo($email_to,$email_name);
		//seteo el cuerpo del mensaje
		$this->_mailInstance->setBodyHtml(	$email_message, 
											$this->_charset);
		//lo envio		
		return $this->send($secure);
		
	}
	
	
	/**
	 * 
	 * mail para notificar error en paypal	 
	 * @param string $error de paypal
	 * @param string $secure
	 */
	public function sendPayPalError( $error, $secure = false ){						
					     
		// Agregar horas
		//Obtenemos la fecha actual:
		$fecha=time();
		//Queremos restar 3 horas a la fecha actual:
		$horas = 3;
		// Convertimos las horas a segundos y las sumamos:
		$fecha += ($horas * 60 * 60);
		// Le damos al resultado el formato deseado:
		$fecha = date("F j, Y, g:i a", $fecha );
		 
	    // Cuerpo del Email
		$email_message = "Hubo error con PayPal <br/><br/>";
		$email_message .= "Error: ".$error."<br/>";	
	    $email_message .= "Fecha: ".$fecha."<br/>";    	    
		
	    // Asunto y Direccion
	    $email_to 		= "gus.rivero.rodriguez@gmail.com";
	    $email_subject 	= "[La Rubia Hostel - Error en Paypal]";
	    $email_name		= "La Rubia - Error en Paypal";
									
		//inicializo zend_mail
		$this->init();
		//seteo subject
		$this->_mailInstance->setSubject($email_subject);
		//seteo a quien se lo envio
		$this->_mailInstance->addTo($email_to,$email_name);
		//seteo el cuerpo del mensaje
		$this->_mailInstance->setBodyHtml(	$email_message, 
											$this->_charset);
		//lo envio		
		return $this->send($secure);
		
	}
	
	
									/**********
									 *  FUNCIONES AUXILIARES
									 **********/
	
	/**
	 * 
	 * re-inicializacion de instancias
	 */
	private function init(){
		
		$this->_mailInstance = new Zend_Mail();
		$this->_mailInstance->setDate( Zend_Date::now(Zend_Locale::BROWSER) )
							->setFrom( $this->_replay_account, $this->_replay_name )
							->setReplyTo( $this->_replay_account );
		
	}
	
	/**
	 * 
	 * Crea una instancia del transportador smtp de Zend
	 * @return Zend_Mail_Transport_Smtp 
	 */
	private function getSmtpTransport(){

		//SMTP server configuration
 		$smtpHost = 'smtp.gmail.com';
 		$smtpConf = array(
					  'auth' => 'login',
					  'ssl' => 'ssl',
					  'port' => $this->_communicationPort,
					  'username' => $this->_replay_account,
					  'password' => $this->_replay_pass
 					);
 		$transport = new Zend_Mail_Transport_Smtp($smtpHost, $smtpConf);
 		return $transport;
		
	}
	
	/**
	 * 
	 * Funcion encargada de mandar el correo generado
	 * @param  $secure, true si quieres enviarlo de forma segura atravez de smtp, false se enviara de manera tradicional
	 * @return Boolean, true si el correo se envio con exito, false en caso contrario
	 */
	private function send( $secure ){
		
		$wasSent = true;
		if($secure)
			$transport = $this->getSmtpTransport();			
		else
			$transport = new Zend_Mail_Transport_Sendmail('-fno-reply@casasdelaplaya.com');		
		try{	
			$this->_mailInstance->send( $transport );
		}catch(Zend_Mail_Exception $e){
			$wasSent = false;
		}
		return $wasSent;	
		
	}
			
	
}

