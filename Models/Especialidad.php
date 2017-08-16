<?php 

/**
* ASA
*/
require_once "Conexion.php";
require_once "Terapeuta.php";

class Especialidad
{
  private $id;
	private $nombre;
  private $terapeutas;
  private static $conn;

	public function __construct(){
    if( !isset( self::$conn ) ){
      self::$conn = new Conexion();
    }
    
    $this->terapeutas = array();
	}

  	public function set( $atributo, $valor ){
    	$this->$atributo = $valor;
  	}

  	public function get($atributo){
    	return $this->$atributo;
  	}

  	public function agregar(){
  		$sql ="SELECT * from especialidades where nombre = '{$this->nombre}'";
  		$result = self::$conn->muestra($sql);

  		if( !($result->num_rows > 0) ){
  			$sql ="INSERT into especialidades (nombre) values('{$this->nombre}')";
  			self::$conn->modifica($sql);
  			return self::$conn->insert_id;
  		}else{
  			$row = $result->fetch_array(MYSQLI_ASSOC);
  			return $row['id'];
  		}
  	}

  	public function getEspecialidades($tipo=''){
  		$sql ="SELECT * from especialidades";
  		$result = self::$conn->muestra($sql);
		  $html=""; 
  		if( ($result->num_rows > 0) ){
        if( $tipo=='encabezado' ){
          $html="<option value='' disabled selected > Seleccione una Especialidad</option>";
        }
        
  			while ( $row = $result->fetch_array(MYSQLI_ASSOC) ) {
  				$html.="<option value='".$row['id']."'>".$row['nombre']."</option>";
  			}
  			return $html;
  		}
  	}



    public function setTerapeuta($terapeutas){
      
      if( count($terapeutas) > 1 ){
        foreach ( $terapeutas as $terapeuta ) {
          $this->terapeutas[] = $terapeuta;
        }
      }else{
        $this->terapeutas[] = $arr[0];
      }
    }

    public function getTerapeutas(){
      $sql ="SELECT c.nombre nomEspecialidad, a.id idTerapeuta,  
            a.nombre nomTerapeuta, a.apellido apeTerapeuta from terapeutas a 
            inner join terapeuta_especialidad b on a.id=b.id_terapeuta 
            inner join especialidades c on b.id_especialidad = c.id
            where c.id = {$this->id}";

      $result = self::$conn->muestra($sql);
      $terapeutas = array();
      // $especialidad = new Especialidad();
      while ( $row = $result->fetch_array(MYSQLI_ASSOC) ) {
        $terapeuta = new Terapeuta();
        $terapeuta->set("id", $row['idTerapeuta']);
        $terapeuta->set("nombre", $row['nomTerapeuta']);
        $terapeuta->set("apellido", $row['apeTerapeuta']);
        // $terapeuta->set("especialidad",  );
        $terapeutas[] = $terapeuta;

      }
      return $terapeutas;
 
    }

    public function getEspecialidadesRaw(){
      $sql ="SELECT * from especialidades";
      $result = self::$conn->muestra($sql);
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