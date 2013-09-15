<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Casas de la Playa</title>
	<link href="css/reset.css" rel="stylesheet" type="text/css" />
	<link href="css/style.css" rel="stylesheet" type="text/css" />
    <link rel="shortcut icon" href="images/deco/favicon.gif" />
    <link rel="shortcut icon" href="images/deco/favicon.ico" />

	<script type="text/javascript" src="js/jquery-1.3.2.js"></script>
	<script type="text/javascript" src="js/jquery.history.js"></script>
	<!-- We only want the thunbnails to display when javascript is disabled -->
	<script type="text/javascript">
		document.write('<style>.noscript { display: none; }</style>');
	</script>
	
	<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=false&amp;key=ABQIAAAAJN6AJhsXNVXnMnefgZMKBRRtFEaumzy3skH66oKx2Ihv8dgyChTSC-bJ2-wTX-Pfgdf1Vb5ep6SXhA" type="text/javascript"></script>
	
	<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
	<script type="text/javascript">
	function initialize() {
		//Ubication of the Casas de la Playa spot
		var latlng = new google.maps.LatLng(-34.587959, -54.122303);
		
		// Opciones del mapa
		var myOptions = {
		  zoom: 16,
		  center: latlng,	  
		  mapTypeId: google.maps.MapTypeId.SATELLITE,
		  streetViewControl: true
		};
		
		// Creo una instancia del mapa
		var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
		
		// Marcador del spot
        var marker = new google.maps.Marker({
          map: map,
          position: new google.maps.LatLng(-34.587959, -54.122303),
          draggable: false,
          title: 'Casas de la Playa'
        });

        // Circulo overlay sobre marcador
        var circle = new google.maps.Circle({
          map: map,
          radius: 30 // 3000 km
        });
		// Aado el circulo al marcador
        circle.bindTo('center', marker, 'position');
		
	}
	</script>
	
</head>

<body onload="initialize()">
	<div id="head">
    <ul id="left">
    	<li><a href="default.html">Casas de la playa</a></li>
    	<li><a href="alojamientos.html">Alojamientos</a></li>
    	<li><a href="ubicacion.php" class="menu_selected">Ubicación</a></li>
    </ul>
    <img src="images/logo/logo.png" width="163" height="68" title="Casas de la playa" name="Casas de la playa"/>
    <ul id="right">
    	<li><a href="servicios.html">Servicios</a></li>
    	<li><a href="galeria.html">Galería de fotos</a></li>
    	<li><a href="#">La pedrera</a></li>
    	<li><a href="contacto.php">Contacto</a></li>
    </ul>
    </div>
    <div id="deco_sup"></div>
    <div id="cont">	
		<div id="cont_left">
            <h1>Ubicaci&oacute;n</h1>
            <p>Ubicados frente a la zona de baño, en el Desplayado te esperamos para unas vacaciones únicas </p>
		</div>
        <div id="cont_right">
			<div id="map_canvas"></div>
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
</body>
</html>
