<?php
require_once('class.upload.php');

class Image{

	public function Image(){
	}
		
	//img de view-actividad (ligthbox) 	758*544  	
	//img de listas de actividades  	184*256 
 	public function uploadImage($image){
		
		$ruta = "../public/uploads/";
		ini_set("max_execution_time",0);
		$handle->forbidden 	= array('application/*');
		$handle 			= new Upload($image);
		
		if( $handle->uploaded ){
			
			$path1 = $ruta;	
			if (!file_exists("$path1")){
				mkdir($path1,0777);
			}
			//original  <<<<<---------------------------- VER SI PUEDO BORRAR
			$path_ori1 = $ruta.'original/';
			if (!file_exists("$path_ori1")){
				mkdir($path_ori1,0777);
			}
			$imagename =	$handle->Process($path_ori1);
											
			// big-muro (height: 184px; width: 256px;)
			$path_big = $ruta.'big-post/';
			$handle->image_resize          	= true;
			$handle->image_ratio           = true;
 		 	$handle->jpeg_quality 			= 100;
 		 	list($org_width, $org_height) = getimagesize($image['tmp_name']);
 		 	$handle->image_ratio_crop	   = true;
			$handle->image_resize          = true;
			$handle->image_ratio           = true;
 		 	$handle->jpeg_quality 			= 100;
			$handle->image_x              = 256;
			$handle->image_y              = 184;
			if (!file_exists("$path_tapa")){
				mkdir($path_tapa,0777);
			}
			$imagename = $handle->Process($path_big);
			
			// ampliada (width ="758" height ="544")  
			$path_ampliada = $ruta.'ampliada/';
			$handle->image_resize          	= true;
			$handle->image_ratio           = true;
 		 	$handle->jpeg_quality 			= 100;
 		 	list($org_width, $org_height) = getimagesize($image['tmp_name']);
 		 	$handle->image_ratio_crop	   = true;
			$handle->image_resize          = true;
			$handle->image_ratio           = true;
 		 	$handle->jpeg_quality 			= 100;
			$handle->image_x              = 658;
			$handle->image_y              = 444;
			if (!file_exists("$path_tapa")){
				mkdir($path_tapa,0777);
			}
			$imagename = $handle->Process($path_ampliada);					
			
			$handle->Clean();
			
		}
		
		return $imagename;               
		
	}
	
		
}
