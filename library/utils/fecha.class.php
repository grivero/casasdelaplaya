<?

class Fecha {

	
	private $fecha; 
	private $fechaServer;
	private $time_zone;	
	
	private $nombre_mes = array(
        '01' => 'Enero',
        '02' => 'Febrero',
        '03' => 'Marzo',
        '04' => 'Abril',
        '05' => 'Mayo',
        '06' => 'Junio',
        '07' => 'Julio',
        '08' => 'Agosto',
        '09' => 'Septiembre',
        '10' => 'Octubre',
        '11' => 'Noviembre',
        '12' => 'Diciembre'
        ); 
	

	public function Fecha($fecha_set=''){
		$this->fechaServer = date('Y-m-d H:i:s');
		if ($fecha_set == '') {
			$this->fecha = new DateTime($this->fechaServer);
		} else {
			$this->fecha = new DateTime($fecha_set);
		}
		$this->setTimeZone('America/Montevideo');
	}

	public function getFecha(){
		return $this->fecha;
	}

	//format as: $fecha = date('Y-m-d H:i:s')
	public function setFecha($fecha){
		$this->fecha = new DateTime($fecha);
	}
	
	public function setTimeZone($tz){
		$this->time_zone = $tz;
		$this->fecha->setTimeZone(new DateTimeZone($tz));
	}


	public function getTimeZone(){
		return $this->time_zone;
	}

	public function getFechaServer(){
		return $this->fechaServer;
	}

	public function getNombreMes(){
		return $this->nombre_mes[$this->getFecha()->format('m')];
	}

	public function fechaAdd($dd=0, $mm=0, $yy=0, $hh=0, $mn=0, $ss=0){
		$temp_date = $this->dateAdd($this->fecha->format('Y-m-d H:i:s'),$dd,$mm,$yy,$hh,$mn,$ss);
		$this->fecha =  new DateTime($temp_date);
	}

	public function dateAdd($date, $dd=0, $mm=0, $yy=0, $hh=0, $mn=0, $ss=0){
		$date_r = getdate(strtotime($date));
		$date_result = date("m/d/Y H:i:s", mktime(($date_r["hours"]+$hh),($date_r["minutes"]+$mn),($date_r["seconds"]+$ss),($date_r["mon"]+$mm),($date_r["mday"]+$dd),($date_r["year"]+$yy)));
		return $date_result;
	}

	public function getMonSun($week_number,$year){
		$monday = date('d/m/Y', strtotime($year."W".$week_number.'1'));
		$sunday = date('d/m/Y', strtotime($year."W".$week_number.'7'));
		return $monday.' - '.$sunday;
	}
	
	public function getLunes($week_number,$year){
		$monday = date('d/m/Y', strtotime($year."W".$week_number.'1'));
		return $monday;
	}

	

	// $fotmat: formato de resultado (ejemplo:'Y-m-d')  
	// $day [1-7] , lunes = 1, ...., domingo = 7
	public function getDay($format,$week_number,$year,$day){
		$daydate = date($format, strtotime($year."W".$week_number.$day));
		return $daydate;
	}

	public function getLastMonday($format){
		while ($this->getFecha()->format('N')!=1){
			$this->fechaAdd(-1,0,0,0,0,0);
		}
		return $this->getFecha()->format($format);
	}
	//calcula la cantidad de semanas entre el lunes inmediato anterior a 				$fecha_ini y el lunes inmediato anterior a $fecha_fin
	//precondicion: 1) fecha_ini < fecha_fin
	// 				2) formato de fecha_ini y fecha_fin = 'Y-m-d'

	public function cantSemanas($fecha_ini,$fecha_fin){
	   $fecha_obj_ini = new Fecha($fecha_ini);
	   $fecha_obj_fin = new Fecha($fecha_fin);
       $monday_ini = $fecha_obj_ini->getLastMonday('Y-m-d');
	   $monday_fin = $fecha_obj_fin->getLastMonday('Y-m-d');
	   $cant_dias = $this->cantDias($monday_ini,$monday_fin);
	   return ($cant_dias / 7);		
	}

	//calcula la cantidad dias entre fecha ini y fecha fin
	//precondicion: 1) formato de fecha_ini y fecha_fin = 'Y-m-d'
	public function cantDias($fecha_ini,$fecha_fin){
		$fecha1 = new Fecha();
		$fecha1->setfecha($fecha_ini);
		$fecha2 = new Fecha();
		$fecha2->setfecha($fecha_fin);
		$ano1 = $fecha1->getFecha()->format('Y');
		$mes1 = $fecha1->getFecha()->format('m');
		$dia1 = $fecha1->getFecha()->format('d');
		$ano2 = $fecha2->getFecha()->format('Y');
		$mes2 = $fecha2->getFecha()->format('m');
		$dia2 = $fecha2->getFecha()->format('d');

		//calculo el las fechas en segundos
		$timestamp1 = mktime(0,0,0,$mes1,$dia1,$ano1);
		$timestamp2 = mktime(4,12,0,$mes2,$dia2,$ano2);

		//resto a una fecha la otra
		$segundos_diferencia = $timestamp1 - $timestamp2;
		//echo $segundos_diferencia;
		//convierto segundos en días
		$dias_diferencia = $segundos_diferencia / (60 * 60 * 24);
		//obtengo el valor absoulto de los días (quito el posible signo negativo)
		$dias_diferencia = abs($dias_diferencia);
		//quito los decimales a los días de diferencia
		$dias_diferencia =  ceil(floor($dias_diferencia)); 
		return $dias_diferencia;		
	}
	
	public function getAgeFromBirth($birth){
		
             $dateTimeBegin=strtotime($birth);
             if($birth === -1) {
               return("..begin date Invalid");
             }

             $dateTimeEnd=strtotime(date('Y-m-d'));
             if($dateTimeEnd === -1) {
               return("..end date Invalid");
             }
             return(intval(date("Y",$dateTimeEnd)) - intval(date("Y",$dateTimeBegin))); 
	}
        
	public function getEdad($fecha_nacimiento){
		//fecha actual
		$ano = date("Y");
		$mes = date("m");
		$dia = date("d");
	
		
		//fecha de nacimiento
		$fecha_nacimiento = explode("-", $fecha_nacimiento);
		$anonaz = $fecha_nacimiento[0];
		$mesnaz = $fecha_nacimiento[1];
		$dianaz = $fecha_nacimiento[2];
	    
		//si el mes es el mismo pero el día inferior aun no ha cumplido años, le quitaremos un año al actual
		if (($mesnaz == $mes) && ($dianaz > $dia)) {
	   		$ano=($ano-1);              
		}
	
		//si el mes es superior al actual tampoco habrá cumplido años, por eso le quitamos un año al actual
		if ($mesnaz > $mes) {
			$ano=($ano-1);
		}
	
		//ya no habría mas condiciones, ahora simplemente restamos los años y mostramos el resultado como su edad
		$edad=($ano-$anonaz);
		return $edad;
    }
    
    

	public function __destruct(){
	}

}



