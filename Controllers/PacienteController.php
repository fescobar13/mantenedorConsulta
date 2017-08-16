<?php 
/**
* 
*/
require_once "../Models/Conexion.php";
require_once "../Models/Paciente.php";
session_start();

// $_POST['nombre']="sdfdsfsdfdsf324234234";
// $_POST['edad']="valor por defecto";
// $_POST['apellido']="valor por defecto";
// $_POST['rut']="valor por defecto";
// $_POST['telefono']="valor por defecto";
// $_POST['email']="valor por defecto";

// $_POST['request'] = "agregar";

if( isset($_POST['request']) && $_POST['request'] !='' ) {
	//Clase que valida los datos de la peticion
	if( $_POST['request'] == 'agregar' ){

		$paciente = new Paciente(); 
		$paciente->set("nombre",$_POST['nombre']);
		$paciente->set("edad",$_POST['edad']);
		$paciente->set("apellido",$_POST['apellido']);
		$paciente->set("rut",$_POST['rut']);
		$paciente->set("telefono",$_POST['telefono']);
		$paciente->set("email",$_POST['email']);

		$rs = $paciente->agregar();

		if( $rs == 'existe' ){
			echo "Paciente ya existe";
		}else if($rs == 'agrego'){
			echo "Se agrego el Paciente";
		}

	}else if( $_POST['request'] == 'listar' ){
		$paciente = new Paciente();
		echo $paciente->listar();

	}else if( $_POST['request'] == 'listarSimple' ){
		$paciente = new Paciente();
		echo $paciente->listarSimple();

	}else if( $_POST['request'] == 'eliminar' ){
		$id = $_POST['id'];
		$paciente = new Paciente();
		$paciente->set("id", $id);
		$rs = $paciente->eliminar();
		if( $rs == 'elimino' ){
			echo "Paciente eliminado";

		}else if( $rs == 'no existe' ){
			echo "No se encontro";
		}
	}else if( $_POST['request'] == 'modificar' ){

		$paciente = new Paciente();
		$paciente->set("nombre",$_POST['nombre']);
		$paciente->set("edad",$_POST['edad']);
		$paciente->set("apellido",$_POST['apellido']);
		$paciente->set("rut",$_POST['rut']);
		$paciente->set("telefono",$_POST['telefono']);
		$paciente->set("email",$_POST['email']);
		$paciente->set("id",$_POST['id']);
		$rs = $paciente->modificar();

		if( $rs == 'modificado' ){
			echo "Paciente Modificado";

		}else if( $rs == 'no existe' ){
			echo "No se encontro paciente";
		}
	}
}

if( isset($_GET['request'] ) && $_GET['request'] != '' ){

	if( $_GET['request'] == 'modificarForm'){
		$id = $_GET['id'];
		$paciente = new Paciente();
		$paciente->set("id",$_GET['id']);
		$rs = $paciente->getModificarForm();
		$_SESSION['dataForm'] = $rs;
		header("Location:../Views/modificarPaciente.php");
	}
}

?>

