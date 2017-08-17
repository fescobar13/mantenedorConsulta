<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="description" content="Source code generated using layoutit.com">
    <meta name="author" content="LayoutIt!">

    <title>Agregar Consulta</title>
    <link rel="stylesheet" type="text/css" href="../vendor/estilo/estilo.css">
    <link rel="stylesheet" type="text/css" href="../vendor/bootstrap/css/bootstrap.min.css"> 
    <link rel="stylesheet" type="text/css" href="../vendor/jquery-ui/jquery-ui.min.css">
    <link rel="stylesheet" type="text/css" href="../vendor/chosen/chosen.css">
    <link rel="stylesheet" type="text/css" 
    href="../vendor/datatables/datatables/media/css/jquery.dataTables.min.css">
</head>
<?php 
  session_start();

  // echo phpinfo(); exit;
  if( isset( $_SESSION['optionsPaciente'] ) && $_SESSION['optionsPaciente'] !='' ){
    $optionsPaciente = $_SESSION['optionsPaciente'];
    $optionsTerapeuta = $_SESSION['optionsTerapeuta'];
    	
  }else{
    $optionsPaciente="";
 	$optionsTerapeuta="";
  }

?>
<body onload="getDataConsulta()">
<div class="container-fluid">
	<?php include "header.php"; ?>
	<?php include "menu.php"; ?>
	<div class="row">
		<div class="col-md-6">
			<h3>Agregar Consulta <?php echo date("Y-m-d H:i:s"); ?></h3>
			
			<form role="form" id='formPaciente' class="form-inline">
				<div class="form-group">
					<label for="cmbPacientes">
						Paciente:
					</label>
					<select id='cmbPacientes' class="form-control" style="width:180px;" >
					</select>
				</div>
				<div class="form-group">
					<label for="cmbEspecialidades">
						Especialidad:
					</label>
					<select onchange="getTableConsultas(this.value)" id='cmbEspecialidades' class="form-control" style="width:180px;" >
					</select>
				</div>
				<div class="form-group">
				<a  class="btn btn-primary" href="agregarConsulta.php">Reestablecer</a>
				</div>

			</form><br>
			<div id='waiting'>
			</div>
			<div id='divTableConsultas'>
				<table id="TableConsultas" class="table table-bordered table-striped display oculto">
					<thead>
						<tr>
							<th>Terapeuta</th>
							<th>Especialidad</th>
							<th>Próx Hora Disponible</th>
							<th>Agenda</th>	
						</tr>
					</thead>
					<tbody id='TableConsultasBody'>
					</tbody>
				</table>
			</div>
			<div id='respuesta'>
			</div>
			<div id="reservado" title="Aviso">
			</div>
			<div id="existe" title="Aviso">
			</div>
		</div>
	</div>
</div>

<script src="../vendor/components/jquery/jquery.min.js"></script>
<script src="../vendor/datatables/datatables/media/js/jquery.dataTables.min.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="../vendor/chosen/chosen.jquery.min.js"></script>
<script src="../vendor/jquery-ui/jquery-ui.min.js"></script>
<script src="../vendor/js/general.js"></script>

 <script>
    $( function() {
      $( "#accordion" ).accordion({
      	active:false,
        collapsible: true
      });
    });
  </script>
</body>
</html>

