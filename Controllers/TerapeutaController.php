<?php 

require_once "../Models/Conexion.php";
require_once "../Models/Terapeuta.php";
require_once "../Models/Especialidad.php";
session_start();

// $_POST['nombre']="sdfdsfsdfdsf324234234";
// $_POST['edad']="valor por defecto";
// $_POST['apellido']="valor por defecto";
// $_POST['rut']="valor por defecto";
// $_POST['telefono']="valor por defecto";
// $_POST['email']="valor por defecto";
// $_POST['request'] = "agregar";

class TerapeutaController
{
	public function converToArray($string){
		if( strstr($string, ",") ){
			$arr = explode(",", $string);
		}else{
			$arr = array(0=>$string);
		}
		return $arr;
	}
	
}

/* POST */

if( isset( $_POST['request'] ) && $_POST['request'] !='' ) {
	//Clase que valida los datos de la peticion
	$controller = new TerapeutaController();

	if( $_POST['request'] == 'agregar' ){
		
		$terapeuta = new Terapeuta();
		$terapeuta->set("nombre",$_POST['nombre']);
		$terapeuta->set("edad",$_POST['edad']);
		$terapeuta->set("apellido",$_POST['apellido']);
		$terapeuta->set("rut",$_POST['rut']);
		$terapeuta->set("telefono",$_POST['telefono']);
		$terapeuta->set("email",$_POST['email']);
		$especialidad = $controller->converToArray( $_POST['especialidad'] );
		$terapeuta->set("especialidad", $especialidad );

		$rs = $terapeuta->agregar();
		
		if( $rs == 'existe' ){
			echo "Terapeuta ya existe";
		}else if($rs == 'agrego'){
			echo "Se agrego el Terapeuta";
		}

	}else if( $_POST['request'] == 'listar' ){
		$terapeuta = new Terapeuta();
		echo $terapeuta->listar();

	}else if( $_POST['request'] == 'eliminar' ){
		$id = $_POST['id'];
		$terapeuta = new Terapeuta();
		$terapeuta->set("id", $id);
		$rs = $terapeuta->eliminar();
		if( $rs == 'elimino' ){
			echo "Terapeuta eliminado";

		}else if( $rs == 'no existe' ){
			echo "No se encontro el terapeuta";
		}
	}else if( $_POST['request'] == 'modificar' ){

		$terapeuta = new Terapeuta();
		$terapeuta->set("nombre",$_POST['nombre']);
		$terapeuta->set("edad",$_POST['edad']);
		$terapeuta->set("apellido",$_POST['apellido']);
		$terapeuta->set("rut",$_POST['rut']);
		$terapeuta->set("telefono",$_POST['telefono']);
		$terapeuta->set("email",$_POST['email']);
		$terapeuta->set("id",$_POST['id']);
		$especialidad = $controller->converToArray( $_POST['especialidad'] );
		$terapeuta->set("especialidad", $especialidad );
		$rs = $terapeuta->modificar();

		if( $rs == 'modificado' ){
			echo "Terapeuta Modificado";

		}else if( $rs == 'no existe' ){
			echo "No se encontro terapeuta";
		}
	}else if( $_POST['request'] == 'getEspecialidades' ){
		$especialidad = new Especialidad();
		echo $especialidad->getEspecialidades();
	}
}


/* GET */

if( isset($_GET['request'] ) && $_GET['request'] != '' ){

	if( $_GET['request'] == 'modificarForm'){
		$id = $_GET['id'];
		$terapeuta = new Terapeuta();
		$terapeuta->set("id",$_GET['id']);
		$rs = $terapeuta->getModificarForm();
		$_SESSION['dataForm'] = $rs;
		header("Location:../Views/modificarTerapeuta.php");
	}
}

?>

