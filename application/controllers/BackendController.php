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

    public function addHabitacionAction()
    {
        // action body
       if ($_POST['camas']){
	       	//agrego la habitacion.
	       	$cant_camas 		= $_POST['camas'];
	       	$nombre 			= $_POST['nombre'];
	       	$descripcion 		= $_POST['descripcion'];
	       	$suite 				= $_POST['suite'];
	       	$precio_baja		= $_POST['precio_baja'];
	       	$precio_alta		= $_POST['precio_alta'];
	       	$precio_media		= $_POST['precio_media'];
	       	
	        if ($suite == "on") {
	        	$suite = 1;
	        } else {
	            $suite = 0;
	        }
	       	$hab_model = new Application_Model_DbTable_House();
	       	$id_hab = $hab_model->createRow(array("cant_camas" => $cant_camas,
	       										"nombre" => $nombre, 
		    								    "descripcion" => $descripcion,
	       	 									"precio_alta" => $precio_alta,
	       										"precio_baja" => $precio_baja,
	       										"precio_media" => $precio_media,
	       	 								    "suite" => $suite))
		        	  			 ->save();
	       	
	       	$this->view->msg = 'Se agreg&oacute; la habitaci&oacute;n '.$nombre. ' con '.$cant_camas. ' camas ';
       }
       
    }

    public function habitacionListAction()
    {
        // action body
        $hab_model = new Application_Model_DbTable_House();
        $habitaciones = $hab_model->select()->from('habitacion')
										->order('id_habitacion DESC')															
										->query()->fetchAll();	
		$this->view->habitaciones = $habitaciones;	
		if ($this->_request->getParam('deleted') == 'si'){
			$this->view->msg = 'La habitacion fue borrada';
		}
		
    }

    public function editHabitacionAction()
    {
        // action body
        $id_habitacion = $_GET['id'];
        $hab_model = new Application_Model_DbTable_House();
        
    	if ($_POST['camas']){
	       	//agrego la habitacion.
	       	$cant_camas = $_POST['camas'];
	       	$nombre = $_POST['nombre'];
	       	$descripcion = $_POST['descripcion'];
	       	$suite = $_POST['suite'];
	       	$precio_baja		= $_POST['precio_baja'];
       		$precio_alta		= $_POST['precio_alta'];
       		$precio_media		= $_POST['precio_media'];
       	
	       	
	        if ($suite == "on") {
	        	$suite = 1;
	        } else {
	            $suite = 0;
	        }
	       	
	       	$id_hab = $hab_model->update(array("cant_camas" => $cant_camas,
       										"nombre" => $nombre, 
	       									"precio_baja" => $precio_baja,
	       									"precio_media" => $precio_media,
	       									"precio_alta" => $precio_alta, 
	    								    "descripcion" => $descripcion, 
       	 								    "suite" => $suite)
										, array('id_habitacion = ?' => $id_habitacion));
	       	
	       	$this->view->msg = 'Se edit&oacute; la habitaci&oacute;n '.$nombre. ' con '.$cant_camas. ' camas';
       }
        
        
        $habitaciones = $hab_model->select()->from('habitacion')
										->where("id_habitacion = ?",$id_habitacion)															
										->query()->fetchAll();	
		$this->view->habitacion = $habitaciones[0];
		
    }

    public function delHabitacionAction()
    {
        // action body
         $id_habitacion = $_GET['id'];
         $hab_model = new Application_Model_DbTable_House();
	     $id_hab = $hab_model->delete( array('id_habitacion = ?' => $id_habitacion));
	     $this->_forward('habitacion-list','admin','default',array("deleted"=>'si'));
    }

    public function addReservaAction()
    {
        // action body
    	
    	
        
    }

    public function reservaListAction()
    {
     	// action body
     	//Reservas
        $res_model = new Application_Model_DbTable_Reservas();
        $reservas = $res_model->select()->from('reservas')
        								->where("deleted ='0'")
										->order('fecha_inicio DESC')															
										->query()->fetchAll();	
		$this->view->reservas = $reservas;
		//Habitaciones
		$hab_model = new Application_Model_DbTable_Habitacion();
        $habitaciones = $hab_model->select()->from('habitacion')
										->order('id_habitacion DESC')															
										->query()->fetchAll();
		foreach ($habitaciones as $habitacion){
			$id_habitacion = $habitacion['id_habitacion'];
			$hab_name[$id_habitacion] = $habitacion['nombre'];
		}
		$this->view->hab_name = $hab_name;		
		if ($this->_request->getParam('deleted') == 'si')
			$this->view->msg = 'La reserva fue borrada';		
    	
    }

    public function editReservaAction()
    {
        // action body
    }

    public function delReservaAction()
    {
        // action body
         $id_reserva = $_GET['id'];
         $res_model = new Application_Model_DbTable_Reservas();
	     $id_res = $res_model->update (array("deleted"=>'1') ,'id_reserva = '.$id_reserva);
	     $this->_forward('reserva-list','admin','default',array("deleted"=>'si'));
    }

    public function reservasResultAction()
    {
        // action body
        //obtengo los datos
		$fecha_ini = $this->getRequest()->getParam('fecha');
		$dias 	   = (int)$this->getRequest()->getParam('dias');

		//calculo la fecha final
		require_once('../library/utils/fecha.class.php');
		$fecha_obj = new Fecha($fecha_ini);
		$fecha_obj->fechaAdd($dias,0,0,0,0,0);
		$fecha_fin = $fecha_obj->getFecha()->format('Y-m-d');
										
		//Modelos
		$hab_model = new Application_Model_DbTable_Habitacion();
		$res_model = new Application_Model_DbTable_Reservas();
		
   		 /*
		 * 
		 * Antes de hacer nada me fijo si esta abierto el hostel para cada uno de los dias
		 */
		$avalaible = true;
		$temp_plugin = new Application_Plugin_TempDate();
		$fecha_obj_temp = new Fecha($fecha_ini);
		for($t=0;(($t < $dias) && $avalaible) ;$t++){
			$f_date = $fecha_obj_temp->getFecha()->format('Y-m-d');
			if($temp_plugin->getTemporadaName($f_date) == null){
				$avalaible = false;
			}
			$fecha_obj_temp->fechaAdd(1,0,0,0,0,0);
		}
		
		if ($avalaible == false){
			$this->view->msg_cerrado = 'Lo sentimos. El hostel no esta disponible para alguna de las fechas seleccionadas.';
		}
		
		
		//obtengo las habitaciones
        $habitaciones = $hab_model->select()->from('habitacion')
										->order('id_habitacion DESC')															
										->query()->fetchAll();	
		
		//Armo un array con todas las habitaciones y cuantas camas disponibles tiene.	
		foreach ($habitaciones as $habitacion){
			/*
			 * Recorro todas las fechas y calculo es la menor disponibilidad entre todos los dias para cada habitacion
			 */
			
			$max_camas = 0;
			$id_current_hab = $habitacion['id_habitacion'];
			
			//precio periodo
			$precio_periodo = 0;
			
			//obtengo fecha inicial
			$fecha_obj->fechaAdd(- (int)$dias,0,0,0,0,0);
			for($i=0;$i < $dias;$i++){
				$cant_camas_dia = 0;
	        	$current_dia = $fecha_obj->getFecha()->format('Y-m-d');
	       		$reservas = $res_model->select()->from('reservas')
											->where("fecha_inicio <= '".$current_dia."'")	
											->where("fecha_fin >'".$current_dia."'" )
											->where("id_habitacion =$id_current_hab")
											->where("deleted = '0'")
											->query()->fetchAll();
				foreach ($reservas as $reserva){
					$cant_camas_dia = $cant_camas_dia + (int)$reserva['cant_camas'];
				}
				
				if ($max_camas < $cant_camas_dia){
					$max_camas = $cant_camas_dia;
				}	
				
				//
				$disponibilidad_dia_habitacion[$current_dia][$id_current_hab] =  $habitacion['cant_camas'] - $cant_camas_dia;
				$disponibilidad_habitacion_dia[$id_current_hab][$current_dia] =  $habitacion['cant_camas'] - $cant_camas_dia;
				
				//calculo el precio para la fecha y la habitacion
				$temp_plugin = new Application_Plugin_TempDate();
				$temporada = $temp_plugin->getTemporadaName($current_dia);
				$precio_dia_habitacion[$current_dia][$id_current_hab] = $habitacion['precio_'.$temporada];
				
				$precio_periodo = $precio_periodo + $habitacion['precio_'.$temporada];
				$fecha_obj->fechaAdd(1,0,0,0,0,0);
			}		
			
			//cantidad de camas disponibles en todo el rango de fechas.
			$camas_disponibles = $habitacion['cant_camas'] - $max_camas;

			$habitaciones_result[$id_current_hab] = $habitacion; 
			//agrego al array las camas disponibles.
			$habitaciones_result[$id_current_hab]['camas_disponibles'] = $camas_disponibles;
			
			//hab_precio_periodo
			$hab_precio_periodo[$id_current_hab] = $precio_periodo;
			
		}
		
		
		$this->view->habitaciones = $habitaciones_result;
		$this->view->cant_dias = $dias;
		$this->view->fecha_ini = $fecha_ini;
		$this->view->fecha_fin = $fecha_fin;

		//
		$this->view->disponibilidad_dia_habitacion = $disponibilidad_dia_habitacion;
		$this->view->disponibilidad_habitacion_dia = $disponibilidad_habitacion_dia;
		
		$this->view->precio_dia_habitacion = $precio_dia_habitacion;
		
		$this->view->hab_precio_periodo = $hab_precio_periodo;
		
    }

    public function confirmReservaAction()
    {
        // action body
        $i = 0;
        foreach($_POST['id_habitacion'] as $id_habitacion){
        	$reserva_arr[$i]['id_habitacion'] = $id_habitacion;
        	$i++;
        } 
         $i = 0;
        foreach($_POST['cant_camas'] as $cant_camas){
        	$reserva_arr[$i]['cant_camas'] = $cant_camas;
        	$i++;
        } 
        $fecha_ini = $_POST['fecha_ini'];
        $fecha_fin = $_POST['fecha_fin'];
        $cant_dias = $_POST['cant_dias'];
        $nombre    = $_POST['nombre'];
        //calculo el precio nuevamente para 
        
        
        
        //TODO chequear todas las disponibilidades en este instante que es el anterior a crear las reservas!
        //     si no pinta volver atras.
        foreach ($reserva_arr as $reserva){
        	$res_model = new Application_Model_DbTable_Reservas();
        	$now_date = date('Y-m-d H:i:s');
        	
        	$temp_plugin = new Application_Plugin_TempDate();
//			$temporada = $temp_plugin->getTemporadaName($current_dia);
//				$precio_dia_habitacion[$current_dia][$id_current_hab] = $habitacion['precio_'.$temporada];
        	$precio = $temp_plugin->getPrecioReserva($reserva['id_habitacion'],$fecha_ini,$cant_dias,$cant_camas);
        	
				
       		$id_res = $res_model->createRow(array("cant_camas" => $reserva['cant_camas'],
       										"fecha_inicio" => $fecha_ini, 
	    								    "fecha_fin" => $fecha_fin, 
       										"cant_dias" => $cant_dias, 
       										"date_created" => $now_date, 
       										"precio" => $precio, 
       										"nombre" => $nombre, 
       										"id_habitacion" => $reserva['id_habitacion']
       	 								    ))
	        	  			 ->save();
        	
        }
        $this->view->msg = 'La(s) reserva(s) fueron creadas correctamente ( <a href="reserva-list">Ver Reservas</a> )';
    }

    public function compraListAction()
    {
        // action body
     	// action body
     	//Reservas
        $compra_model = new Application_Model_DbTable_Compra();
        $compras = $compra_model->select()->from('compra')
										->order('fecha_creacion DESC')															
										->query()->fetchAll();	
		$this->view->compras = $compras;
    }

    public function detalleCompraAction()
    {
        // action body
        $id_compra = (int) $_GET['id'];
        $compra_model = new Application_Model_DbTable_Compra();
        $compras = $compra_model->select()->from('compra')
        								  ->where("id_compra = ?",$id_compra)			
										->order('fecha_creacion DESC')															
										->query()->fetchAll();	
		$this->view->compra = $compras[0];
		
		//Reservas
		$res_model = new Application_Model_DbTable_Reservas();
		$reservas = $res_model->select()->from('reservas')
        								->where("id_compra = ?",$id_compra)		
        								->where("deleted = ?",'0')		
										->order('date_created DESC')															
										->query()->fetchAll();
		$this->view->reservas = $reservas;	
		
		//Habitaciones
		$hab_model = new Application_Model_DbTable_Habitacion();
        $habitaciones = $hab_model->select()->from('habitacion')
										->order('id_habitacion DESC')															
										->query()->fetchAll();
		foreach ($habitaciones as $habitacion){
			$id_habitacion = $habitacion['id_habitacion'];
			$hab_name[$id_habitacion] = $habitacion['nombre'];
		}
		$this->view->hab_name = $hab_name;		
    	
    }

    public function addRestrictionsAction()
    {
    	if ($_POST['temp']){
	       	//agrego la temporada.
	       	$temp = $_POST['temp'];
	       	$fecha_ini = $_POST['fecha_ini'];
	       	$fecha_fin = $_POST['fecha_fin'];
	       	
            $arr_fecha = explode("/", $fecha_ini);
            $fecha_ini = $arr_fecha[2].'-'.$arr_fecha[0].'-'.$arr_fecha[1];
            
            $arr_fecha = explode("/", $fecha_fin);
            $fecha_fin = $arr_fecha[2].'-'.$arr_fecha[0].'-'.$arr_fecha[1];
	       	
	       //Temporada
			$temp_model = new Application_Model_DbTable_Temporadas();
			
			$set1 = $temp_model->select()->from('temporadas')
        								  ->where("fecha_inicio >=?",$fecha_ini)	
        								  ->where("fecha_inicio <=?",$fecha_fin)			
										->query()->fetchAll();	
			$set2 = $temp_model->select()->from('temporadas')
        								  ->where("fecha_fin >=?",$fecha_ini)	
        								  ->where("fecha_fin <=?",$fecha_fin)			
										->query()->fetchAll();	
	       	
			if (sizeof($set1) >0 || sizeof($set2) > 0 ){
				$this->view->msg_error = 'Se solapa con otra temporada. Por favor revise las fechas e ingrese nuevamente';
			} else {
				//agrego
				$id_temp = $temp_model->createRow(array("tipo" => $temp,
       										"fecha_inicio" => $fecha_ini, 
	    								    "fecha_fin" => $fecha_fin, 
       	 								    ))
	        	  			 ->save();
	 			$this->view->msg = 'Se cre&oacute; la temporada correctamente';
			}
	       	
       } 
    }

    public function restrictionsListAction()
    {
  		//Temporada
			$temp_model = new Application_Model_DbTable_Temporadas();
			
			$this->view->temporadas = $temp_model->select()->from('temporadas')
        								->order('fecha_inicio DESC')			
										->query()->fetchAll();	
        
    }

    public function delTemporadaAction()
    {
        // action body
         $id_temporada = $_GET['id'];
         $temp_model = new Application_Model_DbTable_Temporadas();
	     $id = $temp_model->delete( array('id_temporada = ?' => $id_temporada));
	     $this->_forward('restrictions-list','admin','default',array("deleted"=>'si'));
    }

    public function administradorAction()
    {
        // action body
    }

    public function loginAction()
    {
        // action body
    }

    public function houseListAction(){
    	
    	// action body
        $hab_model = new Application_Model_DbTable_House();
        $habitaciones = $hab_model->select()->from('house')
										->order('id DESC')															
										->query()->fetchAll();	
		$this->view->habitaciones = $habitaciones;	
		if ($this->_request->getParam('deleted') == 'si'){
			$this->view->msg = 'La habitacion fue borrada';
		}
		
    }

    public function userListAction()
    {
        // action body
    }

    public function messageListAction()
    {
        // action body
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


}















