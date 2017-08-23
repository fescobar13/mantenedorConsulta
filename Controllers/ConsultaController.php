<?php 

require_once "../Models/Conexion.php";
require_once "../Models/Terapeuta.php";
require_once "../Models/Paciente.php";
require_once "../Models/Especialidad.php";
require_once "../Models/Consulta.php";
session_start();

// $_POST['nombre']="sdfdsfsdfdsf324234234";
// $_POST['edad']="valor por defecto";
// $_POST['apellido']="valor por defecto";
// $_POST['rut']="valor por defecto";
// $_POST['telefono']="valor por defecto";
// $_POST['email']="valor por defecto";
// $_POST['request'] = "agregar";

class ConsultaController
{
	private $horas;

	public function __construct(){
		$this->horas = array('08:00',
							'08:15',
							'08:30',
							'08:45',
							'09:00',
							'09:15',
							'09:30',
							'09:45',
							'10:00',
							'10:15',
							'10:30',
							'10:45',
							'11:00',
							'11:15',
							'11:30',
							'11:45',
							'12:00',
							'12:15',
							'12:30',
							'12:45',
							'13:00',
							'13:15',
							'13:30',
							'13:45',
							'14:00',
							'14:15',
							'14:30',
							'14:45',
							'15:00',
							'15:15',
							'15:30',
							'15:45',
							'16:00',
							'16:15',
							'16:30',
							'16:45',
							'17:00',
							'17:15',
							'17:30',
							'17:45',
							'18:00',
							'18:15',
							'18:30',
							'18:45',
							'19:00',
							'19:15',
							'19:30',
							'19:45',
							'20:00',
							'20:15',
							'20:30',
							'20:45',
							'21:00',
							'21:15',
							'21:30',
							'21:45',
							'22:00',
							'22:15',
							'22:30',
							'22:45',
							'23:00',
							'23:15',
							'23:30',
							'23:45'
							);
	}

	public function getHtmlEspecialidad( $arrEsp ){
		$options="<option selected disabled value=''>Seleccione una especialidad</option>";
		foreach ( $arrEsp as $esp ) {
			$esp = explode("|", $esp);
			$options.="<option value='".$esp[0]."'>".$esp[1]."</option>";
		}
		return $options;
	}

	// public function getHorasTomadas( $arr ){
	// 	$horasTomadas=array();
	// 	foreach ( $arr as $consulta) {
	// 		$horasTomadas[] = $consulta->get("fecha");
	// 	}
	// 	return $horasTomadas;
	// }

	public function getIdTerapeutasOcupados( $arr ){
		$idsTerapeutas=array();
		foreach ( $arr as $consulta) {
			$idsTerapeutas[] = $consulta->get("id_terapeuta");
		}
		return $idsTerapeutas; 
	}

	public function getIndexHoraActual(){
		$horaActual = date("H:i");
		for ($i=0; $i < count($this->horas); $i++) { 
			if( $horaActual <= $this->horas[$i] ){
				return $i;
			}
		}
	}

	public function getHtmlAgendaCompleta( $consultasTomadas=0 ,$terapueta=0,$idPaciente=0,$idEsp=0, $fecha=0, $desdeCalendario=false) {
		$html="";
		$correlativo = 0;
		// echo $fecha."<--";exit;
		// print_r($terapueta); exit;
		if( $desdeCalendario ){
			$fechaConsulta = $fecha;
			$fechaVista = $fecha;
			if( $fecha == date("Y-m-d") ){
				$index = $this->getIndexHoraActual();
			}else{
				$index = 0;
			}
		}else{
			$index = $this->getIndexHoraActual();
			$fechaConsulta = date('Y-m-d');
			$fechaVista = date('d-m-Y');
		}

		$paciObj = new Paciente();
		$dataPaciente = $paciObj->getPaciente( $idPaciente );
		// print_r($dataPaciente); exit;
		$dataPaciente = explode("|", $dataPaciente[0]);

		$dataTera = explode("|", $terapueta[0] );
		$i=0;
		for( $j =$index; $j < count($this->horas); $j++ ){//horas 
			$horaActual = date('Y-m-d', strtotime( $fechaConsulta ) )." ".$this->horas[$j];
			// echo $horaActual; exit;
			$coincidio=false;
			foreach ( $consultasTomadas as $consulta ) { 
				if( $dataTera[0] == $consulta->get("id_terapeuta") ){ //encontro tera
					$horaConsulta = date('Y-m-d H:i', strtotime($consulta->get("fecha")) );
					// echo "actual->>>".$horaActual."<br>";
					// echo "consulta->>>".$horaConsulta."<br><br>";
					if( $horaActual == $horaConsulta ){
						$coincidio=true;
					}
				}
			}

			if( $coincidio ){ // coincide la hora, evito que la tomen.
				continue;
			}else{
				$html.="<tr>
				<td>".$dataTera[1]." ".$dataTera[2]."</td>
				<td>".$dataTera[3]."</td>
				<td>".$dataPaciente[1]." ".$dataPaciente[2]."</td>
				<td>".date('d-m-Y', strtotime( $fechaVista." +".$i." days") )." ".$this->horas[$j]."</td>
				<td><input type='hidden' id='dataConsulta".$correlativo."' 
				value='".$dataTera[0]."|".
				date('Y-m-d', strtotime( $fechaConsulta." +".$i." days") )." ".$this->horas[$j]."|".$idPaciente."|".$idEsp."'>
				<button onclick='reservar(dataConsulta".$correlativo.")' class='btn btn-primary' >Reservar</button></td>
				</tr>";
				$correlativo++;
			}
		}
		return $html;
	}
	
	public function getHtmlConsultas( $consultasTomadas=0, $terapuetas=0, $idPaciente=0, $idEsp=0 ){
		// echo "1"; exit;
		// $horasTomadas = $this->getHorasTomadas($consultasTomadas);
		$idsTerapeutas = $this->getIdTerapeutasOcupados($consultasTomadas);
		$indexHora = $this->getIndexHoraActual();

		/* Agenda Completa*/
		// <!--<td><button onclick='getAgenda(dataConsulta".$correlativo.")' class='btn btn-success'>
		//  Ver agenda Completa</button></td>-->
		//Link Agenda Completa

		/* Agenda Completa*/

		$html="";
		$correlativo = 0;
		for( $i = 0; $i < 30; $i++ ){ //cant dias
			if( $i == 0 ){
				$index = $indexHora;
			}else{
				$index=0;
			}
			for( $j =$index; $j < count($this->horas); $j++ ){//horas 
				// echo $this->horas[$i]."<..."; exit;
				for($k =0; $k < count($terapuetas); $k++){//terapuetas 
					// echo "alooo";exit;
					$dataTera = explode("|", $terapuetas[$k] );
					if( !$consultasTomadas ==0 ){
						$horaActual = date('Y-m-d', strtotime(date('Y-m-d')." +".$i." days") )." ".$this->horas[$j];

						if( in_array( $dataTera[0], $idsTerapeutas ) ){ //tera tiene hora
							$coincidio=false;
							foreach ( $consultasTomadas as $consulta ) { 
								if( $dataTera[0] == $consulta->get("id_terapeuta") ){ //encontro tera
									$horaConsulta = date('Y-m-d H:i', strtotime($consulta->get("fecha")) );
									// echo "actual->>>".$horaActual."<br>";
									// echo "consulta->>>".$horaConsulta."<br><br>";
									if( $horaActual == $horaConsulta ){
										$coincidio=true;
									}
								}
							}

							if( $coincidio ){ // coincide la hora, evito que la tomen.
								continue;
							}else{
								$html.="<tr>
								<td>".$dataTera[1]." ".$dataTera[2]."</td>
								<td>".$dataTera[3]."</td>
								<td>".date('d-m-Y', strtotime(date('d-m-Y')." +".$i." days") )." ".$this->horas[$j]."</td>
								<td><input type='hidden' id='dataConsulta".$correlativo."' 
								value='".$dataTera[0]."|".
								date('Y-m-d', strtotime(date('Y-m-d')." +".$i." days") )." ".$this->horas[$j]."|".$idPaciente."|".$idEsp."'>
								<button onclick='reservar(dataConsulta".$correlativo.")' class='btn btn-primary' >Reservar</button></td>
								<td>
								<a href='../Controllers/ConsultaController.php?request=agendaCompleta&idTerapeuta=".$dataTera[0]."&fecha=".date('Y-m-d', strtotime(date('Y-m-d')." +".$i." days") )." ".$this->horas[$j]."&idPaciente=".$idPaciente."&idEspecialidad=".$idEsp."' class='btn btn-success'>Ver agenda Completa</a> 
		  							</td>
								
								</tr>";
								$correlativo++;
							}
													
						}else{
							$html.="<tr>
							<td>".$dataTera[1]." ".$dataTera[2]."</td>
							<td>".$dataTera[3]."</td>
							<td>".date('d-m-Y', strtotime(date('d-m-Y')." +".$i." days") )." ".$this->horas[$j]."</td>
							<td><input type='hidden' id='dataConsulta".$correlativo."' 
							value='".$dataTera[0]."|".
							date('Y-m-d', strtotime(date('Y-m-d')." +".$i." days") )." ".$this->horas[$j]."|".$idPaciente."|".$idEsp."'>
							<button onclick='reservar(dataConsulta".$correlativo.")' class='btn btn-primary' >Reservar</button></td>
							<td><a href='../Controllers/ConsultaController.php?request=agendaCompleta&idTerapeuta=".$dataTera[0]."&fecha=".date('Y-m-d', strtotime(date('Y-m-d')." +".$i." days") )." ".$this->horas[$j]."&idPaciente=".$idPaciente."&idEspecialidad=".$idEsp."' class='btn btn-success'>Ver agenda Completa</a></td>
							
							</tr>";
							$correlativo++;
						}
						
					}else{
						$html.="<tr>
						<td>".$dataTera[1]." ".$dataTera[2]."</td>
						<td>".$dataTera[3]."</td>
						<td>".date('d-m-Y', strtotime(date('d-m-Y')." +".$i." days") )." ".$this->horas[$j]."</td>
						<td><input type='hidden' id='dataConsulta".$correlativo."' 
						value='".$dataTera[0]."|".
						date('Y-m-d', strtotime(date('Y-m-d')." +".$i." days") )." ".$this->horas[$j]."|".$idPaciente."|".$idEsp."'>
						<button onclick='reservar(dataConsulta".$correlativo.")' class='btn btn-primary' >Reservar</button></td>
						<td><a href='../Controllers/ConsultaController.php?request=agendaCompleta&idTerapeuta=".$dataTera[0]."&fecha=".date('Y-m-d', strtotime(date('Y-m-d')." +".$i." days") )." ".$this->horas[$j]."&idPaciente=".$idPaciente."&idEspecialidad=".$idEsp."' class='btn btn-success'>Ver agenda Completa</a></td>
						
						</tr>";
						$correlativo++;
					}
					
				}
			}
		}
		return $html;
	}


}

/* POST */

if( isset( $_POST['request'] ) && $_POST['request'] !='' ) {
	//Clase que valida los datos de la peticion
	// $controller = new TerapeutaController();
	$espObj = new Especialidad();

	if( $_POST['request'] == 'eliminar' ){
		$id = $_POST['id'];
		$consulta = new Consulta();
		$consulta->set("id", $id);

		$rs = $consulta->eliminar();
		if( $rs == 'elimino' ){
			echo "Consulta eliminada";

		}else if( $rs == 'no existe' ){
			echo "No se encontro";
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

	}else if( $_POST['request'] == 'getTableConsultas' ){	
		$idEsp = $_POST['idEspecialidad'];
		$idPaciente = $_POST['idPaciente'];

		$consObj= new Consulta();
		$espObj = new Especialidad();
		$teraObj = new Terapeuta();
		$terapeutas = $teraObj->getTerapeutas($idEsp);

		$consController = new ConsultaController();
		// $especialidades = $espObj->getEspecialidadesRaw();
		$consultasTomadas = $consObj->listar( $idEsp );
		echo $consController->getHtmlConsultas( $consultasTomadas,$terapeutas,$idPaciente,$idEsp );

	}else if( $_POST['request'] == 'reservar' ){	
		$dataConsulta = $_POST['consulta'];
		$dataC = explode("|", $dataConsulta);
		$consObj= new Consulta();
		echo $consObj->agregar( $dataC[0], $dataC[1], $dataC[2], $dataC[3] );

	}else if( $_POST['request'] == 'listar' ){	
		$consulta = new Consulta();
		echo $consulta->listarConsultas();
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

	}else if( $_GET['request'] == 'DatosConsulta' ){
		$especialidad = new Especialidad();
		echo $especialidad->getEspecialidades("encabezado");
		
	}else if( $_GET['request'] == 'agendaCompleta' ){
		$idEsp = $_GET['idEspecialidad'];
		$idPaciente = $_GET['idPaciente'];
		$fecha = $_GET['fecha'];
		$idTerapeuta = $_GET['idTerapeuta'];

		$consController = new ConsultaController();
		$consObj= new Consulta();
		$teraObj = new Terapeuta();

		$consultasTomadas = $consObj->listar( $idEsp );
		$terapeuta = $teraObj->getTerapeuta( $idTerapeuta, $idEsp );

		$dataConsultas =  $consController->getHtmlAgendaCompleta( $consultasTomadas, $terapeuta, $idPaciente, $idEsp, $fecha );

		$_SESSION['dataAgendaCompleta'] = $dataConsultas;
		header("Location:../Views/agendaCompleta.php");
		
	}else if( $_GET['request'] == 'dataCalendario' ){
		$dataConsulta = $_GET['dataConsulta'];

		$dataConsulta = explode("|", $dataConsulta );
		// 7|2017-08-23 15:00|4|2 
		// echo $dataConsulta[1]; exit;

		$consController = new ConsultaController();
		$consObj= new Consulta();
		$teraObj = new Terapeuta();
		$consultasTomadas = $consObj->listar( $dataConsulta[3] );
		$terapeuta = $teraObj->getTerapeuta( $dataConsulta[0], $dataConsulta[3] );
		$dataConsultas =  
		$consController->getHtmlAgendaCompleta(
						$consultasTomadas,
						$terapeuta, 
						$dataConsulta[2], 
						$dataConsulta[3], 
						$dataConsulta[1],true);

		$_SESSION['dataAgendaCompleta'] = $dataConsultas;
		$_SESSION['fechaCalendario'] = $dataConsulta[1];

		header("Location:../Views/agendaCompleta.php");

	}
}

?>



