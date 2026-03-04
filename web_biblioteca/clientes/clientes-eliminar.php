<?php

session_start();
if ($_SESSION['usuario_logeado'] == false){
    header("Location: ../index.php");
}

require "../componentes/config/conexion.php";

$id = $_GET["id"];

$consulta = "DELETE FROM Clientes WHERE id = ?";
$sentencia = $conexion->prepare($consulta);
$sentencia->bind_param("i", $id);
$sentencia->execute();

header("Location: clientes-listado.php?cliente_eliminado");
