<?php
$servidor = "localhost";
$usuario = "root";
$contraseña = "rootmysql";
$nombre_bbdd = "iapwe-biblioteca-bbdd";

try {
    $conexion = new mysqli($servidor, $usuario, $contraseña, $nombre_bbdd);
} catch (mysqli_sql_exception $e) {
    echo "Error en la conexión con la Base de Datos";
    //$e
    $conexion = null;

    header("Location: ../error-bbdd.php");
    exit();
}
