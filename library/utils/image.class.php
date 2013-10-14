<?php
require_once('class.upload.php');

class Image{

	public function Image(){
	}
	
	//big-post 293*212 
	//ampliada(ligthbox) 700*525  	
	//original
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
			//original
			$path_ori1 = $ruta.'original/';
			if (!file_exists("$path_ori1")){
				mkdir($path_ori1,0777);
			}
			$imagename =	$handle->Process($path_ori1);
											
			//big-muro max-width = 278
			$path_big = $ruta.'big-post/';
			$handle->image_resize          = true;
			$handle->image_ratio           = true;
 		 	$handle->jpeg_quality = 100;
 		 	//Redimensiona considerando si la imagen es apaisada o no.
 		 	list($org_width, $org_height) = getimagesize($image['tmp_name']);
			if ($org_width > 278){
				$handle->image_x		= 278;
				$handle->image_y		= 278*$org_height/$org_width;
			}
			if (!file_exists("$path_big")){
				mkdir($path_big,0777);
			}
			$imagename = $handle->Process($path_big);
			
			//ampliada max-height = 525
			$path_ampliada = $ruta.'ampliada/';
			$handle->image_resize          = true;
			$handle->image_ratio           = true;
 		 	$handle->jpeg_quality = 100;
 		 	//Redimensiona considerando si la imagen es mas alta de 565.
 		 	list($org_width, $org_height) = getimagesize($image['tmp_name']);
 		 	if ($org_height > 565){
				$handle->image_y		= 565;
				$handle->image_x		= 565*$org_width/$org_height;
 		 	} else if ($org_width > 835){
				$handle->image_y		= 835;
				$handle->image_x		= 835*$org_height/$org_width;
 		 	} else {
 		 		$handle->image_y		= $org_height;
				$handle->image_x		= $org_width;
 		 	}
			if (!file_exists("$path_ampliada")){
				mkdir($path_ampliada,0777);
			}
			$imagename = $handle->Process($path_ampliada);					
			
			$handle->Clean();
			
		}
		
		return $imagename;               
		
	}
	
		
}
