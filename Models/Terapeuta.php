<?php 
/**
 * Author Alex Sepúlveda
 */
require_once "Conexion.php";
require_once "Especialidad.php";

class Terapeuta 
{
  private $id;
  private $nombre;
  private $apellido;
  private $rut;
  private $edad;
  private $telefono;
  private $email;
  private $especialidad;
  private $estado;
  private static $conn;

  function __construct(){
    if( !isset( self::$conn ) ){
      self::$conn = new Conexion();
    }

    $this->telefono="";
    $this->email="";
    $this->apellido="";
    $this->edad=0;
    $this->especialidad = array();
  }

  public function set($atributo, $valor){
    $this->$atributo = $valor;
  }

  public function get($atributo){
    return $this->$atributo;
  }

  private function relacionaTerapeutaEspecialidad(){
    foreach ( $this->especialidad as $esp ) {
      $sql = "INSERT INTO terapeuta_especialidad ( id_terapeuta, id_especialidad, estado ) 
              values (".$this->id.", ".$esp.", 1 )";
      self::$conn->modifica($sql);
    }
  }

  public function getEspecialidad($idEsp){
    $sql ="SELECT nombre from especialidades where id=".$idEsp."";
    $result = self::$conn->muestra($sql);
    $row = $result->fetch_array(MYSQLI_ASSOC);
    return $row['nombre'];
  }

  public function getTerapeutas($idEsp){
    $sql ="SELECT a.nombre,a.apellido,a.id from terapeutas a inner join 
          terapeuta_especialidad b on a.id=b.id_terapeuta 
          where a.estado =1 and b.id_especialidad=".$idEsp."";

    $result = self::$conn->muestra($sql);
    $terapuetas=array();
    while( $row = $result->fetch_array(MYSQLI_ASSOC) ){
      $especialidad = $this->getEspecialidad($idEsp);
      $terapuetas[] = $row['id']."|".$row['nombre']."|".$row['apellido']."|".$especialidad;
    }
    return $terapuetas; 
  }

  private function ActualizarTerapeutaEspecialidad(){
    $sql = "DELETE from terapeuta_especialidad 
            where id_terapeuta=".$this->id." and estado=1";
     self::$conn->modifica($sql);

    foreach ( $this->especialidad as $esp ) {
      $sql = "INSERT INTO terapeuta_especialidad ( id_terapeuta, id_especialidad, estado ) 
              values (".$this->id.", ".$esp.", 1 )";
      self::$conn->modifica($sql);
    }
  }

  private function lastId(){
    $sql = "SELECT LAST_INSERT_ID() id from terapeutas";
    $rs = self::$conn->muestra($sql);
    while ( $row = $rs->fetch_array(MYSQLI_ASSOC) ) {
      $id = $row['id'];
      break;
    }
    return $id;
  }

  public function agregar(){
    $sql ="SELECT * from terapeutas where rut='{$this->rut}' and estado =1";
    $result = self::$conn->muestra($sql);

    if( !($result->num_rows > 0) ){
      $sql = "INSERT INTO terapeutas (rut, nombre, apellido, edad, telefono, email,estado)
        VALUES ('{$this->rut}','{$this->nombre}','{$this->apellido}',
                '{$this->edad}','{$this->telefono}','{$this->email}',1)";


      self::$conn->modifica($sql);
      $this->id = $this->lastId();
      $this->relacionaTerapeutaEspecialidad();
     
      return "agrego";

    }else{
      return "existe";
    }
  }

  private function eliminarRelacionEspecialidad(){
    // $sql ="SELECT * from terapeuta_especialidad 
    //     where id_terapeuta ='{$this->id}' and estado =1";

    // $result = self::$conn->muestra($sql);   
    // while( $row = $result->fetch_array(MYSQLI_ASSOC) ){
      $sql = "UPDATE terapeuta_especialidad set estado = 0 where id ='{$this->id}'";
      self::$conn->modifica($sql);
    // }
  }

  public function eliminar(){
    $sql ="SELECT * from terapeutas where id='{$this->id}' and estado =1";
    $result = self::$conn->muestra( $sql );

    if( ($result->num_rows > 0) ){
      $sql = "UPDATE terapeutas set estado = 0 where id ='{$this->id}'";

      self::$conn->modifica($sql);
      $this->eliminarRelacionEspecialidad();
      return "elimino";

    }else{

      return "no existe";
    }
  }

  public function modificar(){
    $sql ="SELECT * from terapeutas where id ='{$this->id}' and estado =1";
    $result = self::$conn->muestra($sql);

    if( ($result->num_rows > 0) ){
      $sql = "UPDATE terapeutas SET nombre ='{$this->nombre}',apellido ='{$this->apellido}',
              edad ='{$this->edad}',rut ='{$this->rut}',telefono ='{$this->telefono}',
              email ='{$this->email}' where id='{$this->id}' ";
      
      self::$conn->modifica($sql);
      //Elimino e ingreso la nuevas especialidades
      $this->ActualizarTerapeutaEspecialidad();
      return "modificado";
    }else{
      return "no existe";
    }
  }

  // public function buscar(){ //modificar
  //   $sql ="SELECT * from dim_prod_opl where rut ='".$this->proveedor->getRut()."'";
  //   $datos = self::$conn->muestra($sql);
  //   return $this->makeHtmlEditable($datos); 
  // }

  public function getEspecialidadesTerapeuta($id){
    $sql = "SELECT * from terapeuta_especialidad a 
            inner join especialidades b on a.id_especialidad=b.id 
            where id_terapeuta=".$id." and a.estado=1";
    $rs = self::$conn->muestra($sql);
    $html="";
    while ( $row = $rs->fetch_array(MYSQLI_ASSOC) ) {
      $html.= $row['nombre']."<br>";
    }
    // $html = trim( $html,"," );
    return $html;
  }

  public function getEspecialidadesTerapeutaRaw($id){
    $sql = "SELECT * from terapeuta_especialidad a 
            inner join especialidades b on a.id_especialidad=b.id 
            where id_terapeuta=".$id." and a.estado=1";
    $rs = self::$conn->muestra($sql);
    $esp = array();
    while ( $row = $rs->fetch_array(MYSQLI_ASSOC) ) {
      $esp[] = $row['nombre'];
    }
    return $esp;
  }
  public function listarRaw(){ //eliminar
    $sql ="SELECT * from terapeutas where estado =1";
    $datos = self::$conn->muestra( $sql );
    $terapeutas = array();
    if( $datos->num_rows > 0 ){
      while( $row = $datos->fetch_array(MYSQLI_ASSOC) ){
        // private $id;
        // private $nombre;
        // private $apellido;
        // private $rut;
        // private $edad;
        // private $telefono;
        // private $email;
        // private $especialidad;
        // private $estado;
        $teraObj = new Terapeuta();
        $teraObj->set("id", $row['id']);
        $terapeutas[] =  $teraObj;
      }
    }
    return $terapeutas;
  }

  public function listar(){ //eliminar
    $sql ="SELECT * from terapeutas where estado =1";
    $datos = self::$conn->muestra( $sql );
    
    return $this->htmlListar( $datos ); 
  }

  private function htmlListar($datos){
    $html="";
    while( $row = $datos->fetch_array(MYSQLI_ASSOC) ){
      $especialidades = $this->getEspecialidadesTerapeuta( $row['id'] );
      $html.="<tr>
          <td>".$row['rut']."</td>
          <td>".$row['nombre']."</td>
          <td>".$row['apellido']."</td>
          <td>".$row['edad']."</td>
          <td>".$row['telefono']."</td>
          <td>".$row['email']."</td>
          <td>".$especialidades."</td>
          <td>
            <p>
              <a  onclick='requestModificarTerapeuta(".$row['id'].")' ><i class='fa fa-pencil-square-o fa-2x' aria-hidden='true'></i></a>
              <a  class='btn-sm btn-danger' onclick='eliminarTerapeuta(".$row['id'].")'>
              <i class='fa fa-trash-o fa-lg'></i></a>
            </p>
          </td>
          </tr>";
    }
    return $html;
  }

  public function getModificarForm(){
    $sql ="SELECT * from terapeutas where id='{$this->id}' and estado=1";
    $result = self::$conn->muestra($sql);

    if( ($result->num_rows > 0) ){
      return $this->htmlModificaForm($result); 
    }
  }

  private function htmlModificaForm($datos){
    $html="";
    $Especialidad = new Especialidad(); 
    $especialidades = $Especialidad->getEspecialidadesRaw();
    $espTerapeuta = $this->getEspecialidadesTerapeutaRaw($this->id);
    $options="";
    foreach ( $especialidades as $especialidad ) {
      $especialidad = explode("|", $especialidad);

      // in_array(needle, haystack)

      if( in_array( $especialidad[1],  $espTerapeuta ) ){
        $options .= "<option value ='".$especialidad[0]."' selected>".$especialidad[1]."</option>";
      }else{
        $options .= "<option value ='".$especialidad[0]."'>".$especialidad[1]."</option>";
      }
    }

    while( $row = $datos->fetch_array(MYSQLI_ASSOC) ){
      $html.="<div class='form-group'>
                <label for='nombre'>
                  Nombre:
                </label>
                <input class='form-control right-margin' id='nombre' name='nombre' 
                type='text' value='".$row['nombre']."' required/>

                <input type='hidden' id='idHidden' value='".$row['id']."'>
                <label for='rut' class='top-margin'>
                  Rut:
                </label>
                <input class='form-control' id='rut' name='rut' type='text' 
                value='".$row['rut']."' required/>
              </div>
              <div class='form-group'>
                <label for='apellido' class='top-margin'>
                  Apellido:
                </label>
                <input class='form-control right-margin' id='apellido' name='apellido' 
                type='text'  value='".$row['apellido']."' />
                <label for='edad' class='top-margin'>
                  Edad:
                </label>
                <input class='form-control' id='edad' name='edad'  value='".$row['edad']."' type='number' />
              </div>
              <div class='form-group'>
                <label for='telefono' class='top-margin'>
                  Teléfono:
                </label>
                <input class='form-control right-margin' id='telefono' 
                name='telefono' type='text'  value='".$row['telefono']."' />
                <label for='email' class='top-margin'>
                  Email:
                </label>
                <input class='form-control' id='email' name='email'  value='".$row['email']."' type='email' />
              </div>
              <div class='form-group top-margin'>
                <label for='telefono' class='top-margin'>
                  Especialidad:
                </label>
                <select id='cmbEspecialidades' class='form-control' style='width:180px;' data-placeholder='Seleccione un Opción' multiple=''>
                  ".$options."
                </select>
              </div>
              <div></div>
              <div class='form-group'>
                <button type='button' onclick='validaFormTerapeuta(\"modificar\");' class='btn btn-primary top-margin'>
                  Modificar
                </button>
              </div>";
    }
    return $html;
  }

  

  // public function delete(){
    
  //   $sql ="DELETE from dim_prod_opl 
  //       where rut ='{$this->proveedor->getRut()}' and id='".$this->id."' ";
  //   self::$conn->modifica($sql); 
  // }
} 

?>



