<!DOCTYPE html>
<html>
<head>
	<title>UI</title>
	<link rel="stylesheet" type="text/css" href="../../Recursos/test/css/plugins/chosen/chosen.css">
	<script type="text/javascript" src="../../Recursos/jquery-3.1.1.min.js"></script>
	<script type="text/javascript" src="../../Recursos/test/js/plugins/chosen/chosen.jquery.js"></script>
</head>
<body>

<form action="" method="post">
Soyuncombo
<select class="cmbAlex"  id="cmbAlex" name="cmbAlex[]" style="width:180px;" data-placeholder="Seleccione un Opción" multiple="">
	<!-- <option value="" disabled="">Seleccione un Opción</option> -->
	<option value="920|ancho_de_caja|11">Valor 1</option>
	<option value="two">Valor 2</option>
	<option value="three">Valor 3</option>
	<option value="four">Valor 4</option>
	<option value="five">Valor 5</option>
	<option value="six">Valor 6</option>
	<option value="seven">Valor 7</option>
</select>
<input type="button" name="" onclick="traeCampos();" value="Completar!">
</form>
<div id="demo">
	
</div>

<script type="text/javascript">
	$(".cmbAlex").chosen({
		no_results_text:"Nada se encontró!",
		// max_selected_options:4
	});

	$('#cmbAlex').on('change', function(event, params) {
	    // can now use params.selected and params.deselected
	    if(params.deselected){
	    	var explotado = params.deselected.split("|");
	    	var idAquitar = ""+explotado[0]+"|"+explotado[1]+"";
	    	var remover = document.getElementById(idAquitar);
	    	$(remover).remove();
	    }
    
  	});

function traeCampos(){
	var arr = $("#cmbAlex").chosen().val();
	// alert(arr);
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			document.getElementById("demo").innerHTML =
			this.responseText;
		}
	};
	xhttp.open("GET", "recibeChosen.php?array="+arr, true);
	xhttp.send();

}

</script>
</body>
</html>