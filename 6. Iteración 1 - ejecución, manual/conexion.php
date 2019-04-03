<?php

$host = "localhost";
$basededatos = "pruebas";
$usuariodb = "userprueba";
$clavedb = "123";
$tabladb = "users";

error_reporting(0);

$conexion = new mysqli($host, $usuariodb, $clavedb, $basededatos);

if ($conexion->connect_errno) {
	echo "Nuestro sitio experimenta fallos...";
	exit();
}

$user = $_POST['email'];
$pass = $_POST['pass'];

$resultado = $conexion->query("SELECT * FROM $tabladb");
  
$estado = 0;

while ($consulta = mysqli_fetch_array($resultado)) {
	if ($consulta['user']==$user && $consulta['password']==$pass){
		$estado = 1;
	}
}

mysqli_close($conexion);

if ($estado == 1){
	header('Location:canchaSintetica.html');
}else{
	header('Location:index.php');
}

?>