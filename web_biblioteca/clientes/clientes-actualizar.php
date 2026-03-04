<?php

session_start();
if ($_SESSION['usuario_logeado'] == false){
    header("Location: ../index.php");
}

require "../componentes/config/conexion.php";

$id = $_POST["id"];
$nombre = $_POST["nombre"];
$apellidos = $_POST["apellidos"];
$fecha_nacimiento = $_POST["fecha_nacimiento"];
$localidad = $_POST["localidad"];

$consulta = "UPDATE Clientes SET nombre = ?, apellidos = ?, fecha_nacimiento = ?, localidad = ? WHERE id = ?";
$sentencia = $conexion->prepare($consulta);
$sentencia->bind_param("ssssi", $nombre, $apellidos, $fecha_nacimiento, $localidad, $id);
$sentencia->execute();

header("Location: clientes-listado.php?cliente_actualizado");
