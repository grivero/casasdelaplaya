<?php

class Application_Plugin_ReservationCalculator extends Zend_Controller_Plugin_Abstract{
		
	public function __construct(){				
		require_once( '../library/utils/fecha.class.php' );					
	}
	
	/*
	 * fecha format: Y-m-d
	 */
	public function getTemporadaName($fecha){
		
		$season_model = new Application_Model_DbTable_Season();
		$row = $season_model->select()->from('season')
										->where("start_date <=?",$fecha)		
										->where("end_date >=?",$fecha)														
										->query()->fetchAll();
		if (sizeof($row) == 0){
			return null;
		}else {
			return $row[0]['rate_type']; // enum('high', 'medium', 'low', 'special', 'carnival')
		}	
		
	}
	
	public function getPrecioReserva($house_id,$fecha_ini,$fecha_fin){
		
		$hab_model = new Application_Model_DbTable_House();
        $house = $hab_model->find($house_id)->current();		
		
		// var inits		
		$cant_dias = floor(abs(( strtotime($fecha_ini) - strtotime($fecha_fin)  )/86400));
		$fecha_obj = new Fecha($fecha_ini);		
		$precio = 0;
		
		// count price day to day based on season rate
		for( $i=0; $i < $cant_dias; $i++ ){
			// current date to calculate price
			$current_dia = $fecha_obj->getFecha()->format('Y-m-d');
			// get season rate
			$temporada = $this->getTemporadaName($current_dia);
			// get price from the house depending on rate
			$price_type = $temporada."_price";
			$precio = $precio + $house->$price_type;
			// add 1 day to current date
			$fecha_obj->fechaAdd(1,0,0,0,0,0);
		}
		
		return $precio;
						
	}
	
	
	
	
}