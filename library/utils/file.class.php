<?php
require_once('mysql.class.php');
require_once('class.upload.php');

class File {
	
	private $db;

	public function File($test=false){
		$this->db = new Mysql($test);		
	}
		
	public function getFileData($id){
		$query = $this->db->select("image", "*", "WHERE id = '$id'");
		return mysql_fetch_assoc($query);
	}
	
  	public function upload($archivo,$ruta="../public/uploads/files/"){
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