<?php 
/**
 * Author Alex Sepúlveda
 */
require_once "Conexion.php";

class Consulta 
{
  private $id_paciente;
  private $id_terapeuta;
  private $fecha;
  private $id_especialidad;
  private $estado;

  private static $conn;

  function __construct(){
    if( !isset(self::$conn) ){
      self::$conn = new Conexion();
    }
    $this->telefono="";
    $this->email="";
    $this->apellido="";
    $this->edad="";
    $this->edad=0;
  }

  public function set($atributo, $valor){
    $this->$atributo = $valor;
  }

  public function get($atributo){
    return $this->$atributo;
  }

  // public function agregar(){
  //   $sql ="SELECT * from pacientes where rut='{$this->rut}'";
  //   $result = self::$conn->muestra($sql);

  //   if( !($result->num_rows > 0) ){
  //     $sql = "INSERT INTO pacientes (rut, nombre, apellido, edad, telefono, email, estado)
  //       VALUES ('{$this->rut}','{$this->nombre}','{$this->apellido}',
  //               '{$this->edad}','{$this->telefono}','{$this->email}',1)";

  //     self::$conn->modifica($sql);
  //     return "agrego";

  //   }else{ // Si el paciente existe pero esta deshabilitado
  //     $row = $result->fetch_array(MYSQLI_ASSOC);
  //     if( $row['estado'] == 0 ){
  //       habilitarPaciente($row['id']);
  //     }
  //     return "existe";
  //   }
  // }

  public function agregar( $idTera=0, $fecha='', $idPaciente=0, $idEspecialidad=0){
    $sql ="SELECT * from consultas where fecha='".$fecha."' 
          and id_terapeuta=".$idTera." and estado=1";

    $result = self::$conn->muestra($sql);
    if( !($result->num_rows > 0) ){
      $sql = "INSERT INTO consultas (id_paciente, id_terapeuta, fecha, id_especialidad, estado)
            values ( ".$idPaciente.", ".$idTera.", '".$fecha."', ".$idEspecialidad.", 1 )";

      self::$conn->modifica($sql);
      return trim("agrego");
    }else{
       return trim("existe");
    }
  }

  public function habilitarConsulta($id){
    $sql ="UPDATE consultas set estado = 1 where id =".$id."";
    self::$conn->modifica($sql);
  }

  public function deshabilitarConsulta($id){
    $sql ="UPDATE consultas set estado = 0 where id =".$id."";
    self::$conn->modifica($sql);
  }

  public function eliminar(){
    $sql ="SELECT * from consultas where id='{$this->id}'";
    $result = self::$conn->muestra($sql);

    if( ($result->num_rows > 0) ){
      // $sql = "DELETE from pacientes where id='{$this->id}'";
      $sql = "UPDATE consultas set estado = 0 where id='{$this->id}'";

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

  //listar consultas dentro de los prox. 30 dias contando el actual
  public function listar($esp){ 
    $sql ="SELECT * from consultas where estado=1 and id_especialidad=".$esp."
          and fecha between '".date("Y-m-d")."' and 
          '".date("Y-m-d", strtotime( date("Y-m-d")." + 30 days" ) )."'";
    // echo $sql;
    $consultas=array();
    $datos = self::$conn->muestra( $sql );
    if( $datos->num_rows > 0  ){ //Si hay consultas en los proximos 30 dias 
      while ( $row = $datos->fetch_array(MYSQLI_ASSOC) ) {
        $consulta = new Consulta();
        $consulta->set("id_paciente",$row['id_paciente']);
        $consulta->set("id_terapeuta",$row['id_terapeuta']);
        $consulta->set("fecha",$row['fecha']);
        $consulta->set("id_especialidad",$row['id_especialidad']);
        $consulta->set("estado",$row['estado']);
        $consultas[] = $consulta;
      }
    }
    return $consultas;
  }

  public function listarConsultas(){ //eliminar
    $sql ="SELECT * from consultas where estado=1";
    $datos = self::$conn->muestra( $sql );
    return $this->htmlListar( $datos );
  }

  private function getPaciente($id=0){
    $sql ="SELECT nombre, apellido from pacientes where estado=1 and id =".$id."";
    $datos = self::$conn->muestra( $sql );
    $row = $datos->fetch_array(MYSQLI_ASSOC);
    $data=array();
    $data['nombre'] = $row['nombre'];
    $data['apellido'] = $row['apellido'];
    return $data; 
  }

  private function getTerapeuta($id=0){
    $sql ="SELECT nombre, apellido from terapeutas where estado=1 and id =".$id."";
    $datos = self::$conn->muestra( $sql );
    $row = $datos->fetch_array(MYSQLI_ASSOC);
    $data=array();
    $data['nombre'] = $row['nombre'];
    $data['apellido'] = $row['apellido'];
    return $data; 
  }

  private function getEspecialidad($id=0){
    $sql ="SELECT nombre from especialidades where id =".$id."";
    // echo $sql;
    $datos = self::$conn->muestra( $sql );
    $row = $datos->fetch_array(MYSQLI_ASSOC);
    return $row['nombre'];
  }

  private function htmlListar($datos){
    $html="";

    while( $row = $datos->fetch_array(MYSQLI_ASSOC) ){
      $dataPaciente = $this->getPaciente( $row['id_paciente'] );
      $dataTerapeuta = $this->getTerapeuta( $row['id_terapeuta'] );
      $nomPaciente= $dataPaciente['nombre'] ? $dataPaciente['nombre'] : "<span>Sin Paciente</span>";
      $apePaciente= $dataPaciente['apellido'];
      $nomTerapeuta= $dataTerapeuta['nombre'] ? $dataTerapeuta['nombre'] : "<span style=color>Sin Terapeuta</span>";
      $apeTerapeuta= $dataTerapeuta['apellido'];

      $html.="<tr>
          <td>".$row['fecha']."</td>
          <td>".$nomPaciente." ".$apePaciente."</td>
          <td>".$nomTerapeuta." ".$apeTerapeuta."</td>
          <td>".$this->getEspecialidad( $row['id_especialidad'] )."</td>
          <td>
            <p>
              <a class='btn-sm btn-danger' onclick='eliminarConsulta(".$row['id'].")'>
              <i class='fa fa-trash-o fa-lg'></i></a>
            </p>
          </td>
          </tr>";
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



