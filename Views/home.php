<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="description" content="Source code generated using layoutit.com">
    <meta name="author" content="LayoutIt!">

    <title></title>
    <link rel="stylesheet" type="text/css" href="../vendor/bootstrap/css/bootstrap.min.css"> 
    <link rel="stylesheet" type="text/css" href="../vendor/jquery-ui/jquery-ui.min.css">
    <link rel="stylesheet" type="text/css" 
    href="../vendor/datatables/datatables/media/css/jquery.dataTables.min.css">

  </head>
  <body>

	<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
          <div class="page-header">
              <h1>
                  <a href="#">Terapias <small>Interfaz de administración</small></a> 
              </h1>
          </div>
        </div>  
    </div>
    <?php include "menu.php"; ?>
  		
	</div>

	<script src="../vendor/components/jquery/jquery.min.js"></script>
	<script src="../vendor/datatables/datatables/media/js/jquery.dataTables.min.js"></script>
	<script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
  <script src="../vendor/jquery-ui/jquery-ui.min.js"></script>
  
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