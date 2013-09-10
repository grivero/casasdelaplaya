<?php
require_once('mysql.class.php');
require_once('paginacion.class.php');


class User{
	
	private $db;

	public function User($test=false){
		$this->db = new Mysql($test);		
	}

	public function getUserData($id_user){
		return $this->db->select("user","*","WHERE id_user = '$id_user'");
	}
	
    public function getCantidadUser($letra=''){
            if($letra == ''){
                    $query = $this->db->select("user", "*", "WHERE deleted<>'1'");
            } else {
                    $query = $this->db->select("user", "*", "WHERE deleted<>'1' and email LIKE '$letra%'");
            }
            $cantidad = mysql_num_rows($query);
            return $cantidad;
        }
        
    public function getListadoUser($limite,$letra=''){
            if($letra == ''){
                    $query = $this->db->select("user", "*", "WHERE deleted<>'1' ORDER BY email LIMIT $limite");
            } else {
                    $query = $this->db->select("user", "*", "WHERE deleted<>'1' and email LIKE '$letra%' ORDER BY email LIMIT $limite");
            }
            return $query;
	}
	
	public function borrarUsuario($id_usuario) {
		$query = $this->db->update("user", "deleted='1'"," WHERE id_user='$id_usuario' ");
		// TODO
		//borrar tambien los profiles asociados unicamente a este usuario;
		//borrar los vinculos de collaborator de otros usuarios a cada uno de los profiles borrados.
		
    }
    
   
        
        
	public function getXMLUser($page=1,$letra='A',$borrar='0')
	{
            if($borrar != '0'){
                $this->borrarUsuario($borrar);
            }
            if($letra=='0'){
                $letra='';
            }
            //Contamos el numero de resultados
            $numMembers = $this->getCantidadUser($letra); 

            //Seteamos numero de resultados por pagina
            $page_rows = 20; 

            $pagenum = $page;

            //Seteamos nuestra ultima pagina
            $last = ceil($numMembers/$page_rows);
            if($last==0){
            $last=1;
            }
            $obj_pag = new Paginacion();
            $limite = $obj_pag->getRangoMysql($pagenum,$last,$page_rows);

            $rowini = ($page * $page_rows) - $page_rows + 1;
            $rowend = $page * $page_rows;
            if($numMembers<$rowend){
                $rowend=$numMembers;
            }

            $queryUser = $this->getListadoUser($limite,$letra);

            header("Content-Type: text/xml");
            $xml = '<?xml version="1.0" encoding="UTF-8" ?>';
            $xml .= '<members>';
            while($row = mysql_fetch_assoc($queryUser))
            {
                $categories = '';
                $xml .= '<member>';
                $xml .= '<id_miembro>'.$row['id_user'].'</id_miembro>';
               
                if($row['email']!=''){
                    $email=$row['email'];
                } else {
                    $email=' ';
                }
                $xml .= '<email>'.$email.'</email>';

                $xml .= '</member>';
            }
            $xml .= '<listado>';
            $xml .= '<nummembers>'.$numMembers.'</nummembers>';
            $xml .= '<rowini>'.$rowini.'</rowini>';
            $xml .= '<rowend>'.$rowend.'</rowend>';
            $xml .= '<page>'.$page.'</page>';
            $xml .= '<letra>';
            
            if($letra==''){
                $letra=0;
            }
            $xml .= $letra.'</letra>';
            $xml .= '</listado>';

            $xml .= '</members>';
            mysql_close();		
            return $xml;
        }	


}

?>
