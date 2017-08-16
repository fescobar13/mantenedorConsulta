<?php 
/**
 * Author Alex Sepúlveda
 */
require_once "Conexion.php";

class Paciente 
{
  
  private $nombre;
  private $apellido;
  private $rut;
  private $edad;
  private $telefono;  
  private $email;
  private $estado;
  private static $conn;

  function __construct(){

    if( !isset(self::$conn) ){
      self::$conn = new Conexion();
    }
    $this->telefono="";
    $this->email="";
    $this->apellido="";
    $this->edad=0;
  }

  public function set($atributo, $valor){
    $this->$atributo = $valor;
  }

  public function get($atributo){
    return $this->$atributo;
  }

  public function agregar(){
    $sql ="SELECT * from pacientes where rut='{$this->rut}'";
    $result = self::$conn->muestra($sql);

    if( !($result->num_rows > 0) ){
      $sql = "INSERT INTO pacientes (rut, nombre, apellido, edad, telefono, email, estado)
        VALUES ('{$this->rut}','{$this->nombre}','{$this->apellido}',
                '{$this->edad}','{$this->telefono}','{$this->email}',1)";

      self::$conn->modifica($sql);
      return "agrego";

    }else{ // Si el paciente existe pero esta deshabilitado
      $row = $result->fetch_array(MYSQLI_ASSOC);
      if( $row['estado'] == 0 ){
        habilitarPaciente($row['id']);
      }
      return "existe";
    }
  }

  public function habilitarPaciente($id){
    $sql ="UPDATE pacientes set estado = 1 where id =".$id."";
    self::$conn->modifica($sql);
  }

  public function deshabilitarPaciente($id){
    $sql ="UPDATE pacientes set estado = 0 where id =".$id."";
    self::$conn->modifica($sql);
  }

  public function eliminar(){
    $sql ="SELECT * from pacientes where id='{$this->id}'";
    $result = self::$conn->muestra($sql);

    if( ($result->num_rows > 0) ){
      // $sql = "DELETE from pacientes where id='{$this->id}'";
      $sql = "UPDATE pacientes set estado = 0 where id='{$this->id}'";

      self::$conn->modifica($sql);
      return "elimino";

    }else{
      return "no existe";
    }
  }

  public function modificar(){
    $sql ="SELECT * from pacientes where id ='{$this->id}'";
    $result = self::$conn->muestra($sql);

    if( ($result->num_rows > 0) ){
      $sql = "UPDATE pacientes SET nombre ='{$this->nombre}',apellido ='{$this->apellido}',
              edad ='{$this->edad}',rut ='{$this->rut}',telefono ='{$this->telefono}',
              email ='{$this->email}' where id='{$this->id}' ";
      
      self::$conn->modifica($sql);
      return "modificado";
    }else{
      return "no existe";
    }
  }

  public function buscar(){ //modificar
    $sql ="SELECT * from dim_prod_opl where rut ='".$this->proveedor->getRut()."'";
    $datos = self::$conn->muestra($sql);
    return $this->makeHtmlEditable($datos); 
  }

  public function listar(){ //eliminar
    $sql ="SELECT * from pacientes where estado=1";
    $datos = self::$conn->muestra( $sql );
    return $this->htmlListar( $datos );

  }

  public function listarSimple(){
    $sql ="SELECT * from pacientes where estado=1";
    $datos = self::$conn->muestra( $sql );
    return $this->htmlListarCombo( $datos );
  }

  private function htmlListar($datos){
    $html="";
    while( $row = $datos->fetch_array(MYSQLI_ASSOC) ){
      $html.="<tr>
          <td>".$row['rut']."</td>
          <td>".$row['nombre']."</td>
          <td>".$row['apellido']."</td>
          <td>".$row['edad']."</td>
          <td>".$row['telefono']."</td>
          <td>".$row['email']."</td>
          <td>
            <p>
              <a onclick='requestModificarPaciente(".$row['id'].")' >
              <i class='fa fa-pencil-square-o fa-2x' aria-hidden='true'></i></a>
              <a class='btn-sm btn-danger' onclick='eliminarPaciente(".$row['id'].")'>
              <i class='fa fa-trash-o fa-lg'></i></a>
            </p>
          </td>
          </tr>";
    }
    return $html;
  }

  private function htmlListarCombo( $datos='' ){
    $html="<option disabled selected value=''>Seleccione una opción</option>";
    while( $row = $datos->fetch_array(MYSQLI_ASSOC) ){
      $html.="<option value='".$row['id']."'>".$row['nombre']." ".$row['apellido']."</option>";
    }
    return $html;
  }

  public function getModificarForm(){
    $sql ="SELECT * from pacientes where id='{$this->id}'";
    $result = self::$conn->muestra($sql);

    if( ($result->num_rows > 0) ){
      return $this->htmlModificaForm($result); 
    }
  }

  public function getPacientes(){
    $sql ="SELECT * from pacientes where estado =1";
    $result = self::$conn->muestra($sql);
    $pacientes=array();
    while( $row = $result->fetch_array(MYSQLI_ASSOC) ){
      $pacientes[] = $row['id']."|".$row['rut']."|".$row['nombre']."|".$row['apellido'];
    }
    return $pacientes; 
  }

  private function htmlModificaForm($datos){
    $html="";
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
              </div><br>
              <div class='form-group'>
                <button type='button' onclick='validaFormPaciente(\"modificar\");' class='btn btn-primary top-margin'>
                  Modificar
                </button>
              </div>";
    }
    return $html;
  }

  public function delete(){
    $sql ="DELETE from dim_prod_opl 
        where rut ='{$this->proveedor->getRut()}' and id='".$this->id."' ";
    self::$conn->modifica($sql); 
  }
  
} 

?>



