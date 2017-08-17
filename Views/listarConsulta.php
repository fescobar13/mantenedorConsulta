<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="description" content="Source code generated using layoutit.com">
    <meta name="author" content="LayoutIt!">

    <title>Listado Paciente</title>
    <link rel="stylesheet" type="text/css" href="../vendor/estilo/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="../vendor/estilo/estilo.css">
    <link rel="stylesheet" type="text/css" href="../vendor/bootstrap/css/bootstrap.min.css"> 
    <link rel="stylesheet" type="text/css" href="../vendor/jquery-ui/jquery-ui.min.css">
    <link rel="stylesheet" type="text/css" 
    href="../vendor/datatables/datatables/media/css/jquery.dataTables.min.css">
</head>
<body onload="listarConsulta();">

<div class="container-fluid">
	<?php include "header.php"; ?>
	<?php include "menu.php"; ?>

		<div class="col-md-7">
			<h3>Listado de Consultas</h3>
			<table class="table table-bordered table-striped display" id="consultaTable">
				<thead>
					<tr>
						<th>Fecha</th>
						<th>Paciente</th>
						<th>Terapeuta</th>
						<th>Tipo Consulta</th>
						<th></th>
					</tr>
				</thead>
				<tbody id='consultaBody'>
				</tbody>
			</table>
			<div id='respuesta'>
			</div>
			<!-- <div id="eliminado" title="Aviso">
			</div> -->
			<div id="confirmacion" title="Confirmación">
			</div>
		</div>
	</div>
</div>

<script src="../vendor/components/jquery/jquery.min.js"></script>
<script src="../vendor/datatables/datatables/media/js/jquery.dataTables.min.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
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

