<?php
require_once('mysql.class.php');
require_once('paginacion.class.php');


class Profile{
	
	private $db;

	public function Profile($test=false){
		$this->db = new Mysql($test);		
	}

	public function getProfileData($id_profile){
		return $this->db->select("profile","*","WHERE id_profile = '$id_profile'");
	}
	
	
	public function getAllProfiles(){
		return $this->db->select("profile","*","WHERE deleted = '0' AND active = '1'");
	}
	
	
    public function getCantidadProfile($letra=''){
            if($letra == ''){
                    $query = $this->db->select("profile", "*", "WHERE deleted<>'1'");
            } else {
                    $query = $this->db->select("profile", "*", "WHERE deleted<>'1' and name LIKE '$letra%'");
            }
            $cantidad = mysql_num_rows($query);
            return $cantidad;
        }
        
    public function getListadoProfile($limite,$letra=''){
            if($letra == ''){
                    $query = $this->db->select("profile", "*", "WHERE deleted<>'1' ORDER BY name LIMIT $limite");
            } else {
                    $query = $this->db->select("profile", "*", "WHERE deleted<>'1' and name LIKE '$letra%' ORDER BY name LIMIT $limite");
            }
            return $query;
	}
	
	public function borrarProfile($id_profile) {
		$query = $this->db->update("profile", "deleted='1'"," WHERE id_profile='$profile' ");
		// TODO
		//borrar tambien los profiles asociados unicamente a este usuario;
		//borrar los vinculos de collaborator de otros usuarios a cada uno de los profiles borrados.
		
    }
        
 	public function getUsers(){
    	$query = $this->db->select("profile", "*", "WHERE deleted<>'1' AND main_profile= '1' ORDER BY name, surname");
		return $query;
    } 
   
        
	public function getXMLProfile($page=1,$letra='A',$borrar='0')
	{
            if($borrar != '0'){
                $this->borrarProfile($borrar);
            }
            if($letra=='0'){
                $letra='';
            }
            //Contamos el numero de resultados
            $numMembers = $this->getCantidadProfile($letra); 

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

            $queryUser = $this->getListadoProfile($limite,$letra);

            header("Content-Type: text/xml");
            $xml = '<?xml version="1.0" encoding="UTF-8" ?>';
            $xml .= '<members>';
            while($row = mysql_fetch_assoc($queryUser))
            {
                $categories = '';
                $xml .= '<member>';
                //id_profile
                $xml .= '<id_profile>'.$row['id_profile'].'</id_profile>';
                //id_user
                if($row['created_by']!=''){
                    $id_user=$row['created_by'];
                } else {
                    $id_user=' ';
                }
                $xml .= '<id_user>'.$id_user.'</id_user>';
                //name                
                if($row['name']!=''){
                    $name=$row['name'];
                } else {
                    $name=' ';
                }
                $xml .= '<name>'.$name.'</name>';
                //surname
                if($row['surname']!=''){
                    $surname=$row['surname'];
                } else {
                    $surname=' ';
                }
                $xml .= '<surname>'.$surname.'</surname>';
                //date_birth
                if($row['date_birth']!=''){
                    $date_birth=$row['date_birth'];
                } else {
                    $date_birth=' ';
                }
                $xml .= '<date_birth>'.$date_birth.'</date_birth>';
                //country
                if($row['country']!=''){
                    $country=$row['country'];
                } else {
                    $country=' ';
                }
                $xml .= '<country>'.$country.'</country>';
                //city
                if($row['city']!=''){
                    $city=$row['city'];
                } else {
                    $city=' ';
                }
                $xml .= '<city>'.$city.'</city>';
                //gender
                if($row['gender']=='M'){
                    $gender='Masculino';
                } else {
                    $gender='Femenino';
                }
                $xml .= '<gender>'.$gender.'</gender>';
                //date_created
                if($row['date_created']!=''){
                    $date_created=$row['date_created'];
                } else {
                    $date_created=' ';
                }
                $xml .= '<date_created>'.$date_created.'</date_created>';
                //Main Profile
                if($row['main_profile']=='1'){
                    $main_profile='SI';
                } else {
                    $main_profile='NO';
                }
                $xml .= '<main_profile>'.$main_profile.'</main_profile>';
                 //Active
                if($row['active']=='1'){
                    $active='SI';
                } else {
                    $active='NO';
                }
                $xml .= '<active>'.$active.'</active>';
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
