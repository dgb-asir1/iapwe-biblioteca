<?php

session_start();
if ($_SESSION['usuario_logeado'] == false) {
    header("Location: ../index.php");
}

require "../componentes/config/conexion.php";

$id = $_GET["id"];

try {
    $consulta = "DELETE FROM Clientes WHERE id = ?";
    $sentencia = $conexion->prepare($consulta);
    $sentencia->bind_param("i", $id);
    $sentencia->execute();
    header("Location: clientes-listado.php?cliente_eliminado");
    exit();
} catch (mysqli_sql_exception $excepcion) {
    if ($excepcion->getCode() == 1451);
    header("Location: clientes-listado.php?error_eliminando_cliente");
    exit();
}
