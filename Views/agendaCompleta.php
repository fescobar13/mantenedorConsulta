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
 if( isset( $_SESSION['dataAgendaCompleta'] ) && $_SESSION['dataAgendaCompleta'] !='' ){
    $dataConsultas = $_SESSION['dataAgendaCompleta'];
  }else{
    $dataConsultas="";
  }

  if( isset( $_SESSION['fechaCalendario'] ) && $_SESSION['fechaCalendario'] !='' ){
  	// echo  $_SESSION['fecha']; exit;
  	// echo $_SESSION['fechaCalendario']."<--origin<br>";
    $fechaListar = date("m/d/Y", strtotime($_SESSION['fechaCalendario']) );
  }else{
    $fechaListar="";
  }
  
?>
<body>
<div class="container-fluid">
	<?php include "header.php"; ?>
	<div class="row">
	<?php include "menu.php"; ?>
	
		<div class="col-md-6">
			<h3>Agregar Consulta</h3>
		</div>
	
		<div class="row">
			<div class="col-md-3"></div>
			<div class="col-md-5">
				<div id='waiting'>
				</div>
				<div id='divTableConsultas'>
					<table id="TableConsultas" class="table table-bordered table-striped display ">
						<thead>
							<tr>
								<th>Terapeuta</th>
								<th>Especialidad</th>
								<th>Paciente</th>
								<th>Próx Hora Disponible</th>
								<th>Agenda</th>	
							</tr>
						</thead>
						<tbody id='TableConsultasBody'>
							<?php echo $dataConsultas; ?>
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
			<div class="col-md-3">
				<div id="datepicker"></div>
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
	$('#TableConsultas').DataTable({
		destroy:true,
		pageLength: 5,
		language:espanol,
		// ordering:true,
		"order": [[ 2, "asc" ]],
		paging:true
    }); 

	$( function() {
		$( "#datepicker" ).datepicker({

			defaultDate: '<?php echo $fechaListar; ?>',
		 	onSelect:function(date,inst){
		 		dateAux = date.split("/");
		 		dateFormated = dateAux[2]+"-"+dateAux[0]+"-"+dateAux[1];
	            dataConsulta = document.getElementById("dataConsulta0").value;
	            dataConsultaSplitted = dataConsulta.split("|");
	            dataConsultaFinal= dataConsultaSplitted[0]+"|"+dateFormated+"|"+dataConsultaSplitted[2]+"|"+dataConsultaSplitted[3];
	            window.location=
	            '../Controllers/ConsultaController.php?request=dataCalendario&dataConsulta='+dataConsultaFinal;
        	},
        	minDate: -0, maxDate: "+4M"
		});
	});

	$( function() {
	  $( "#accordion" ).accordion({
	  	active:false,
	    collapsible: true
	  });
	});
</script>
</body>
</html>

