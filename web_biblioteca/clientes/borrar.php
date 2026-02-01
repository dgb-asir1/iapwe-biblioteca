<?php

    require "../config/conexion.php";

    $id = $_GET["id"];

    $consulta = "DELETE FROM Clientes WHERE id = ?";
    $sentencia = $conexion->prepare($consulta);
    $sentencia->bind_param("i", $id);
    $sentencia->execute();

    header("Location: usuarios.php");



?>