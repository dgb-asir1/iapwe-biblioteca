<?php

session_start();
if ($_SESSION['usuario_logeado'] == false){
    header("Location: ../index.php");
}

require "../componentes/config/conexion.php";

$nombre_cliente = $_GET["nombre_cliente"];
$apellidos_cliente = $_GET["apellidos_cliente"];
$fecha_nac_cliente = $_GET["fecha_nac_cliente"];
$localidad_cliente = $_GET["localidad_cliente"];

$consulta = "INSERT INTO Clientes (nombre, apellidos, fecha_nacimiento, localidad)  VALUES(?,?,?,?)";
$sentencia = $conexion->prepare($consulta);
$sentencia->bind_param("ssss", $nombre_cliente, $apellidos_cliente, $fecha_nac_cliente, $localidad_cliente);
$sentencia->execute();

header("Location: clientes-listado.php");
