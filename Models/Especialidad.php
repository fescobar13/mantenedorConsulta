<?php 

/**
* ASA
*/
require_once "Conexion.php";

class Especialidad
{
	private $nombre;

	function __construct(){
    	$this->conn = new Conexion();
	   
  	}

  	public function set($atributo, $valor){
    	$this->$atributo = $valor;
  	}

  	public function get($atributo){
    	return $this->$atributo;
  	}

  	public function agregar(){
  		$sql ="SELECT * from especialidades where nombre = '{$this->nombre}'";
  		$result = $this->conn->muestra($sql);

  		if( !($result->num_rows > 0) ){
  			$sql ="INSERT into especialidades (nombre) values('{$this->nombre}')";
  			$this->conn->modifica($sql);
  			return $this->conn->insert_id;
  		}else{
  			$row = $result->fetch_array(MYSQLI_ASSOC);
  			return $row['id'];
  		}
  	}

  	public function getEspecialidades(){
  		$sql ="SELECT * from especialidades";
  		$result = $this->conn->muestra($sql);
		  $html="";
  		if( ($result->num_rows > 0) ){
  			while ( $row = $result->fetch_array(MYSQLI_ASSOC) ) {
  				$html.="<option value='".$row['id']."'>".$row['nombre']."</option>";
  			}
  			return $html;
  		}
  	}

    public function getEspecialidadesRaw(){
      $sql ="SELECT * from especialidades";
      $result = $this->conn->muestra($sql);
      $esp = array();
      if( ($result->num_rows > 0) ){
        while ( $row = $result->fetch_array(MYSQLI_ASSOC) ) {
          $esp[]= $row['id']."|".$row['nombre'];
        }
      }
      return $esp;
    }


}

?>