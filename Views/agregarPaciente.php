<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="description" content="Source code generated using layoutit.com">
    <meta name="author" content="LayoutIt!">

    <title>Agregar Paciente</title>
    <link rel="stylesheet" type="text/css" href="../vendor/estilo/estilo.css">
    <link rel="stylesheet" type="text/css" href="../vendor/bootstrap/css/bootstrap.min.css"> 
    <link rel="stylesheet" type="text/css" href="../vendor/jquery-ui/jquery-ui.min.css">
    <link rel="stylesheet" type="text/css" 
    href="../vendor/datatables/datatables/media/css/jquery.dataTables.min.css">
</head>
<body>
<div class="container-fluid">
	<?php include "header.php"; ?>
	<?php include "menu.php"; ?>

		<div class="col-md-6">
			<h3>Agregar Paciente</h3>
			<form role="form" id='formPaciente' class="form-inline">
				<div class="form-group">
					<label for="nombre">
						Nombre:
					</label>
					<input class="form-control right-margin caja-ancha" id="nombre" name="nombre" type="text" required/>
					<label for="rut" class="">
						Rut:
					</label>
					<input class="form-control" id="rut" name="rut"  type="text" required/>
				
				</div>

				<div class="form-group">
					<label for="apellido" class="top-margin">
						Apellido:
					</label>
					<input class="form-control right-margin" id="apellido" name="apellido" type="text" />
					<label for="edad" class="top-margin">
						Edad:
					</label>
					<input class="form-control" id="edad" name="edad" type="number" />
				</div>

				<div class="form-group">
					<label for="telefono" class="top-margin">
						Teléfono:
					</label>
					<input class="form-control right-margin" id="telefono" name="telefono" type="text" />
					<label for="email" class="top-margin">
						Email:
					</label>
					<input class="form-control caja-ancha" id="email" name="email" type="email" />
				</div><br>

				<div class="form-group">
					<button type="button" onclick="validaFormPaciente();" class="btn btn-primary top-margin">
						Agregar
					</button>
				</div>
			</form>
			<div id='respuesta'>
				
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

