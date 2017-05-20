<?php 

$arreglo = $_GET['array'];
$arreglo = explode(",", $arreglo);


$html="";
foreach ($arreglo as $key => $value) {	
		$aux = explode("|", $value); array_pop($aux); $aux = implode("|", $aux);
	$html.="TextBox<input id='".$aux."' type='text' name='' /><br>";
}
echo $html;

?>
