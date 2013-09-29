<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Casas de la Playa</title>
	<link href="css/oldweb_css/reset.css" rel="stylesheet" type="text/css" />
	<link href="css/oldweb_css/style.css" rel="stylesheet" type="text/css" />
    <link rel="shortcut icon" href="img/deco/favicon.gif" />
    <link rel="shortcut icon" href="img/deco/favicon.ico" />
	<script type="text/javascript" src="js/jQuery/jquery-1.5.1.min.js"></script>
	<script type="text/javascript" src="js/jQuery/jquery-ui-1.8.13.custom.min.js"></script>
	<link href="js/oldweb_js/jQuery/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css" />
	
	<script type="text/javascript">
			$(function() {
				$( "#fI" ).datepicker();
				$( "#fE" ).datepicker();
			});
	</script>

	<?php if ($_GET['sent']=='ok') { ?>
		<script type="text/javascript">
			$(function() {
				$( "#dialog" ).dialog();
			});
		</script>
	<? } ?>
	
	
</head>

<body>
	<div id="head">
    <ul id="left">
    	<li><a href="default.html">Casas de la playa</a></li>
    	<li><a href="alojamientos.html">Alojamientos</a></li>
    	<li><a href="ubicacion.php">Ubicación</a></li>
    </ul>
    <img src="img/logo/logo.png" width="163" height="68" title="Casas de la playa" name="Casas de la playa"/>
    <ul id="right">
    	<li><a href="servicios.html">Servicios</a></li>
    	<li><a href="galeria.html">Galería de fotos</a></li>
    	<li><a href="#">La pedrera</a></li>
    	<li><a href="contacto.php" class="menu_selected">Contacto</a></li>
    </ul>
    </div>
    <div id="deco_sup"></div>
    <div id="cont">	
		<div id="cont_left">
            <h1>Contacto</h1>
            <p><span>Tel:</span><br />Desde Uruguay: 098 805 930<br />Internacional: (00598) 98 805 930</p>           
			<p><span>Tel:</span><br />Desde Uruguay: 099 343 490<br />Internacional: (00598) 99 343 490</p> 
			
			<br/><br/><br/><br/><br/><br/>						
			
			<h1 style="margin-bottom: 10px; font-size: 20px;">Cancelaci&oacute;n</h1>			 
			<p style="margin-bottom: 2px;"><span>1)</span>Hasta 15 d&iacute;as antes del ingreso - 10%</p>           
			<p style="margin-bottom: 2px;"><span>2)</span>Entre 14 y 10 d&iacute;as antes del ingreso - 15%</p>           
			<p style="margin-bottom: 2px;"><span>3)</span>Entre 9 y 5 d&iacute;as antes del ingreso - 30%</p>           
			<p style="margin-bottom: 2px;"><span>4)</span>Entre 4 d&iacute;as y el d&iacute;a del ingreso - 50%</p>           
		</div>
        <div id="cont_right">
        	<h2>Formulario</h2>
        	<div id="img_contacto"></div>
            <form name="contactform" method="post" action="send_form_email.php" >
				<table width="450px" style="float:right;">
					
					<tr>
						 <td width="172" align="right" valign="top">
						  <label for="first_name">Nombre *</label>
						 </td>
						 <td width="266" align="right" valign="top">
						  <input  type="text" name="first_name" maxlength="50" size="30">
					  </td>
				  </tr>
					
					<tr>
						 <td align="right" valign="top"">
						  <label for="last_name">Apellido *</label>
						 </td>
						 <td align="right" valign="top">
						  <input  type="text" name="last_name" maxlength="50" size="30">
					  </td>
				  </tr>
					
					<tr>
						 <td align="right" valign="top"">
						  <label for="pais">Pa&iacute;s </label>
						 </td>
						 <td align="right" valign="top">
						  <input  type="text" name="pais" maxlength="50" size="30">
					  </td>
				  </tr>
					
					<tr>
						 <td align="right" valign="top">
						  <label for="email">Email *</label>
						 </td>
						 <td align="right" valign="top">
						  <input  type="text" name="email" maxlength="80" size="30">
					  </td>				 
				  </tr>
					
					<tr>
						 <td align="right" valign="top">
						  <label for="email">Tel&eacute;fono</label>
						 </td>
						 <td align="right" valign="top">
						  <input  type="text" name="telephone" maxlength="80" size="30">
					  </td>				 
				  </tr>
					
					<tr>
						 <td align="right" valign="top">
						  <label for="fIngreso">Fecha ingreso*</label>
						 </td>
						 <td align="right" valign="top">
						  <input  type="text" name="fIngreso" id="fI" maxlength="30" size="30">
					  </td>
				  </tr>
					
					<tr>
						 <td align="right" valign="top">
						  <label for="fEgreso">Fecha egreso*</label>
						 </td>
						 <td align="right" valign="top">
						  <input  type="text" name="fEgreso" id="fE" maxlength="30" size="30">
					  </td>
				  </tr>
					
					<tr>
						 <td align="right" valign="top">
						  <label for="cant">Cantidad de personas</label>
						 </td>
						 <td align="right" valign="top">
						  <input  type="text" name="cant" maxlength="30" size="30">
					  </td>
				  </tr>
					
					<tr>
						 <td align="right" valign="top">
						  <label for="comments">Consulta *</label>
						 </td>
						 <td align="right" valign="top">
						  <textarea  name="comments" maxlength="1000" cols="25" rows="6"></textarea>
					  </td>				 
				  </tr>
					
					<tr>
						 <td colspan="2" align="right" style="text-align:center">
						  <input type="submit" value="Enviar mail" oncomplete="alert('Exito!');" class="btn_enviar" > 
						 </td>
					</tr>
					
				</table>
	  </form>
	</div>	
					
</div>
    <div id="deco_bot"></div>
    <div id="navigator">
        <ul>
            <li><span>+</span><a href="default.html">Casas de la playa</a></li>
            <li><span>+</span><a href="alojamientos.html">Alojamientos</a></li>
            <li><span>+</span><a href="ubicacion.php">Ubicación</a></li>
            <li><span>+</span><a href="servicios.html">Servicios</a></li>
            <li><span>+</span><a href="galeria.html">Galería de fotos</a></li>
            <li><span>+</span><a href="#">La pedrera</a></li>
            <li><span>+</span><a href="contacto.php">Contacto</a></li>
            <li><span>+</span><a href="#">Facebook</a></li>
        </ul>
    </div>
    <div id="footer">
        <p>© Casa de la Playa todos los derechos reservados.</p>
    </div>
	<div id="dialog" title="Email enviado" style="display: none;">
		<p>Email enviado con exito, nos comunicaremos con usted lo antes posible.</p></br>
		<p>Muchas gracias, Casas de la Playa</p>
	</div>
	</body>
</html>
