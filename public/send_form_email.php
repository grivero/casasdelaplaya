<?php
if(isset($_POST['email'])) {
       
    function died($error) {
        // your error code can go here
        echo "Lo sentimos pero hubo un error.";
        echo "Error: <br /><br />";
        echo $error."<br /><br />";
        die();
    }
	
	function clean_string($string) {
      $bad = array("content-type","bcc:","to:","cc:","href");
      return str_replace($bad,"",$string);
    }
     
    // Validacion de existensia de Data
    if(!isset($_POST['first_name']) ||
        !isset($_POST['last_name']) ||
        !isset($_POST['email']) ||
		!isset($_POST['fEgreso']) ||
        !isset($_POST['fIngreso']) ) {
        died('Falta campo obligatorio');       
    }
     
	// Llamada de variables 
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email_from = $_POST['email']; 
    $telephone = $_POST['telephone'];
    $comments = $_POST['comments'];
	$fechaIn = $_POST['fIngreso'];
	$fechaOut = $_POST['fEgreso'];
	$pais = $_POST['pais']; 
	$cant = $_POST['cant'];
	
	// Control errores 
    $error_message = "";
    $email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';
	if(!preg_match($email_exp,$email_from)) {
		$error_message .= 'Direccion de email incorrecta.<br />';
	}
	$string_exp = "/^[A-Za-z .'-]+$/";
	if(!preg_match($string_exp,$first_name)) {
		$error_message .= 'Nombre incorrecto.<br />';
	}
	if(!preg_match($string_exp,$last_name)) {
		$error_message .= 'Apellido incorrecto.<br />';
	}
	if(strlen($comments) < 2) {
		$error_message .= 'Sin comentarios.<br />';
	}
	if(strlen($fechaIn) < 2) {
		$error_message .= 'Sin comentarios.<br />';
	}
	if(strlen($fechaOut) < 2) {
		$error_message .= 'Sin comentarios.<br />';
	}
	if(strlen($error_message) > 0) {
		died($error_message);
	}
     
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
	$email_message = "Informacion procesada: \n\n";
	$email_message .= "Fecha de envio: ".$fecha."\n";	
    $email_message .= "Nombre: ".clean_string($first_name)."\n";
    $email_message .= "Apellido: ".clean_string($last_name)."\n";
    $email_message .= "Email: ".clean_string($email_from)."\n";
	$email_message .= "Pais: ".clean_string($pais)."\n";
    $email_message .= "Telefono: ".clean_string($telephone)."\n";
	$email_message .= "Fecha Ingreso: ".clean_string($fechaIn)."\n";
	$email_message .= "Fecha Egreso: ".clean_string($fechaOut)."\n";
	$email_message .= "Cantidad personas: ".clean_string($cant)."\n";
    $email_message .= "Comentarios: ".clean_string($comments)."\n";
	
    // Asunto y Direccion
    $email_to = "casasdelaplaya@gmail.com";
    $email_subject = "[Casas de la Playa - Web]";
		
	// Email Headers
	$headers = 'From: '.$email_from."\r\n".
	'Reply-To: '.$email_from."\r\n" .
	'X-Mailer: PHP/' . phpversion();
	@mail($email_to, $email_subject, $email_message, $headers);
	
	//Redireccion
	header("Location: contacto.php?sent=ok");
	  
}
?>