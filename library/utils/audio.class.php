<?php

/*-----------------------
No tiene tabla asociada.
-----------------------*/

require_once('class.upload.php');

class Audio{

    public function Audio(){

    }
    
    public function upload($archivo,$ruta="../public/uploads/audios/"){
    	
        ini_set("max_execution_time",0);
        if (!file_exists("$ruta")){
            mkdir($ruta,0777);
        }
        $handle = new Upload($archivo);
        if ($handle->uploaded) {
            $archivename = $handle->Process($ruta);
        }						
        $handle-> Clean();
        return $archivename;
    }

}
?>
