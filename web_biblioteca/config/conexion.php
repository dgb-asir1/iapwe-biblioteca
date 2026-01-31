<?php
    $servidor = "localhost";
    $usuario = "root";
    $contraseña = "rootmysql";
    $nombre_bbdd = "iapwe-biblioteca-bbdd";

    $conexion = new mysqli($servidor, $usuario, $contraseña, $nombre_bbdd);

    if($conexion->connect_error){
        echo "Error en la conexión: " . $conexion->connect_error;
    }
?>