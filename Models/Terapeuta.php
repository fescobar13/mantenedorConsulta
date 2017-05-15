<?php 
/**
 * Author Alex Sepúlveda
 */
require_once "Conexion.php";
require_once "Especialidad.php";

class Terapeuta 
{
  
  private $nombre;
  private $apellido;
  private $rut;
  private $edad;
  private $telefono;
  private $email;
  private $especialidad;

  function __construct(){
    $this->conn = new Conexion();
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
    $sql ="SELECT * from terapeutas where rut='{$this->rut}'";
    $result = $this->conn->muestra($sql);

    if( !($result->num_rows > 0) ){
      $sql = "INSERT INTO terapeutas (rut, nombre, apellido, edad, telefono, email)
        VALUES ('{$this->rut}','{$this->nombre}','{$this->apellido}',
                '{$this->edad}','{$this->telefono}','{$this->email}')";

      $this->conn->modifica($sql);
      $idTerapeuta = $this->conn->insert_id;

      $sql = "INSERT INTO terapeuta_especialidad ( id_terapeuta, id_especialidad ) 
              values ('".$idTerapeuta."', '{$this->especialidad}' )";
     
      return "agrego";

    }else{
      return "existe";
    }


  }

  public function eliminar(){
    $sql ="SELECT * from pacientes where id='{$this->id}'";
    $result = $this->conn->muestra($sql);

    if( ($result->num_rows > 0) ){
      $sql = "DELETE from pacientes where id='{$this->id}'";

      $this->conn->modifica($sql);
      return "elimino";

    }else{
      return "no existe";
    }
  }

  public function modificar(){
    $sql ="SELECT * from pacientes where id ='{$this->id}'";
    $result = $this->conn->muestra($sql);

    if( ($result->num_rows > 0) ){
      $sql = "UPDATE pacientes SET nombre ='{$this->nombre}',apellido ='{$this->apellido}',
              edad ='{$this->edad}',rut ='{$this->rut}',telefono ='{$this->telefono}',
              email ='{$this->email}' where id='{$this->id}' ";
      
      $this->conn->modifica($sql);
      return "modificado";
    }else{
      return "no existe";
    }
  }

  public function buscar(){ //modificar
    $sql ="SELECT * from dim_prod_opl where rut ='".$this->proveedor->getRut()."'";
    $datos = $this->conn->muestra($sql);
    return $this->makeHtmlEditable($datos); 
  }

  public function listar(){ //eliminar
    $sql ="SELECT * from pacientes";
    $datos = $this->conn->muestra( $sql );
    return $this->htmlListar( $datos ); 
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
              <a href='#' onclick='requestModificarTerapeuta(".$row['id'].")' ><img src='' alt='Modificar'></a>
              <a href='#' onclick='eliminarTerapeuta(".$row['id'].")'><img src='' alt='Eliminar'></a>
            </p>
          </td>
          </tr>";
    }
    return $html;
  }

  public function getModificarForm(){
    $sql ="SELECT * from pacientes where id='{$this->id}'";
    $result = $this->conn->muestra($sql);

    if( ($result->num_rows > 0) ){
      return $this->htmlModificaForm($result); 
    }
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
                <button type='button' onclick='validaFormTerapeuta(\"modificar\");' class='btn btn-primary top-margin'>
                  Modificar
                </button>
              </div>";
    }
    return $html;
  }

  

  public function delete(){
    
    $sql ="DELETE from dim_prod_opl 
        where rut ='{$this->proveedor->getRut()}' and id='".$this->id."' ";
    $this->conn->modifica($sql); 
  }
} 

?>



