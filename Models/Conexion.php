<?php 
/**
* 
*/
class Conexion 
{
	private $connMysql;

	public function __construct()
	{
		$this->conn = new mysqli("127.0.0.1","root","","horas_medicas");
	}

	public function modifica($sql){
		$this->conn->query($sql);
	}

	public function muestra($sql){
		$datos = $this->conn->query($sql);
		return $datos;
	}
}

?>

