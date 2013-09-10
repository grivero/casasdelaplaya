<?php

/*-----------------------
No tiene tabla asociada.
-----------------------*/

require_once('class.upload.php');

class Image{


	public function Image(){
	}
	
 	public function uploadImage($image){
		//thumb-muro 83*83 R 
		//big-muro 293*212 
		//ampliada(ligthbox) 700*525  	
		//original
		$ruta="../public/uploads/images/";
		ini_set("max_execution_time",0);
		$handle->forbidden = array('application/*');
		$handle = new Upload($image);
		if ($handle->uploaded){
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
		
			
			//thumb_83x83
			$path_thumb = $ruta.'thumb/';
			$handle->image_ratio_crop	   = true;
			$handle->image_resize          = true;
			$handle->image_ratio           = true;
 		 	$handle->jpeg_quality = 100;
			$handle->image_x              = 115;
			$handle->image_y              = 115;
			if (!file_exists("$path_thumb")){
				mkdir($path_thumb,0777);
			}
			$imagename = $handle->Process($path_thumb);
			
			//big-muro max-width = 278
			$path_big = $ruta.'big-muro/';
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
			
			//tapa-album
			$path_tapa = $ruta.'album/';
			$handle->image_resize          = true;
			$handle->image_ratio           = true;
 		 	$handle->jpeg_quality = 100;
 		 	list($org_width, $org_height) = getimagesize($image['tmp_name']);
 		 	$handle->image_ratio_crop	   = true;
			$handle->image_resize          = true;
			$handle->image_ratio           = true;
 		 	$handle->jpeg_quality = 100;
			$handle->image_x              = 216;
			$handle->image_y              = 161;
			if (!file_exists("$path_tapa")){
				mkdir($path_tapa,0777);
			}
			$imagename = $handle->Process($path_tapa);
			
			
			$handle-> Clean();
		}
		return $imagename;               
		
	}
	
	public function uploadImagePortada($image){
		//thumb-muro 83*83 R 
		//big-muro 293*212 
		//ampliada(ligthbox) 700*525  	
		//original
		$ruta="../public/uploads/images/";
		ini_set("max_execution_time",0);
		$handle->forbidden = array('application/*');
		$handle = new Upload($image);
		if ($handle->uploaded){
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
			
			//thumb_83x83
			$path_thumb = $ruta.'thumb/';
			$handle->image_ratio_crop	   = true;
			$handle->image_resize          = true;
			$handle->image_ratio           = true;
 		 	$handle->jpeg_quality = 100;
			$handle->image_x              = 115;
			$handle->image_y              = 115;
			if (!file_exists("$path_thumb")){
				mkdir($path_thumb,0777);
			}
			$imagename = $handle->Process($path_thumb);
			
			//big-muro max-width = 278
			$path_big = $ruta.'big-muro/';
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
			
			//tapa-album
			$path_tapa = $ruta.'album/';
			$handle->image_resize          = true;
			$handle->image_ratio           = true;
 		 	$handle->jpeg_quality = 100;
 		 	list($org_width, $org_height) = getimagesize($image['tmp_name']);
 		 	$handle->image_ratio_crop	   = true;
			$handle->image_resize          = true;
			$handle->image_ratio           = true;
 		 	$handle->jpeg_quality = 100;
			$handle->image_x              = 216;
			$handle->image_y              = 161;
			if (!file_exists("$path_tapa")){
				mkdir($path_tapa,0777);
			}
			$imagename = $handle->Process($path_tapa);
			
			//foto-portada
			$path_portada = $ruta.'portada/';
			$handle->image_resize          = true;
			$handle->image_ratio           = true;
 		 	$handle->jpeg_quality = 100;
 		 	list($org_width, $org_height) = getimagesize($image['tmp_name']);
 		 	$handle->image_ratio_crop	   = true;
			$handle->image_resize          = true;
			$handle->image_ratio           = true;
 		 	$handle->jpeg_quality = 100;
			$handle->image_x              = 542;
			$handle->image_y              = 122;
			if (!file_exists("$path_portada")){
				mkdir($path_tapa,0777);
			}
			$imagename = $handle->Process($path_portada);
			
			
			
			$handle-> Clean();
		}
		return $imagename;            
	}
	
	public function albumUpdate($image){
		//thumb-muro 83*83 R 
		//big-muro 293*212 
		//ampliada(ligthbox) 700*525  	
		//original
		$ruta="../../public/uploads/images/";
		ini_set("max_execution_time",0);
		$handle->forbidden = array('application/*');
		$handle = new Upload($image);
		if ($handle->uploaded){
			//tapa-album
			$path_tapa = $ruta.'album/';
			$handle->image_resize          = true;
			$handle->image_ratio           = true;
 		 	$handle->jpeg_quality = 100;
 		 	list($org_width, $org_height) = getimagesize($image['tmp_name']);
 		 	$handle->image_ratio_crop	   = true;
			$handle->image_resize          = true;
			$handle->image_ratio           = true;
 		 	$handle->jpeg_quality = 100;
			$handle->image_x              = 216;
			$handle->image_y              = 161;
			if (!file_exists("$path_tapa")){
				mkdir($path_tapa,0777);
			}
			$imagename = $handle->Process($path_tapa);
			
			echo  $handle->log;
			
			//$handle-> Clean();
		}
		return $imagename;               
		
	}

	
	
}
