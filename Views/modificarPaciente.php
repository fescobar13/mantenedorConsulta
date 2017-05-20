<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="description" content="Source code generated using layoutit.com">
    <meta name="author" content="LayoutIt!">

    <title>Modificar Paciente</title>
    <link rel="stylesheet" type="text/css" href="../vendor/estilo/estilo.css">
    <link rel="stylesheet" type="text/css" href="../vendor/bootstrap/css/bootstrap.min.css"> 
    <link rel="stylesheet" type="text/css" href="../vendor/jquery-ui/jquery-ui.min.css">
    <link rel="stylesheet" type="text/css" 
    href="../vendor/datatables/datatables/media/css/jquery.dataTables.min.css">
</head>
<?php 
  session_start();

  if( isset( $_SESSION['dataForm'] ) && $_SESSION['dataForm'] !='' ){
    $dataForm = $_SESSION['dataForm'];
  }else{
    $dataForm="";
  }

?>
<body>
<div class="container-fluid">
	<?php include "header.php"; ?>
	<?php include "menu.php"; ?>

		<div class="col-md-6">
			<h3>Modificar Paciente</h3>
			<form role="form" id='formPaciente' class="form-inline">
				  <?php echo $dataForm; ?>
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

