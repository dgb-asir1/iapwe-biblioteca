<?php

require "../config/conexion.php";

$id = $_POST["id"];
$nombre = $_POST["nombre"];
$apellidos = $_POST["apellidos"];
$fecha_nacimiento = $_POST["fecha_nacimiento"];
$localisdad = $_POST["localidad"];

$consulta = "UPDATE Clientes SET nombre = ? WHERE id = ?";
$sentencia = $conexion->prepare($consulta);
$sentencia->bind_param("si", $nombre, $id);
$sentencia->execute();

header("Location: clientes.php");
