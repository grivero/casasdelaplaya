<?php


class Paginacion{

    public function Paginacion(){

    }

    public function getRangoMysql($pagenum,$last,$page_rows){
        //Arrancamos la paginación
        //Fijamos extremos
        if ($pagenum < 1) 
        { 
        $pagenum = 1; 
        } 
        elseif ($pagenum > $last) 
        { 
        $pagenum = $last; 
        } 		
        //Seteamos el rango para desplegar 
        $max = ($pagenum - 1) * $page_rows .',' .$page_rows;		
        return $max;
    }

}

