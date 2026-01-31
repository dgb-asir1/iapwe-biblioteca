<?php

    require "config/conexion.php";
    require "clases/Usuario.php";

    $nombre_usuario = $_POST["nombre_usuario"];
    $password = $_POST["password"];

    $consulta = "SELECT * FROM Usuarios WHERE nombre_usuario = ?";
    $sentencia = $conexion->prepare($consulta);
    $sentencia->bind_param("s", $nombre_usuario);
    $sentencia->execute();

    //get_result - recuperar los usuarios
    //store_result - cuenta el numero de datos

    $resultado = $sentencia->get_result();

    $usuario = $resultado->fetch_object(Usuario::class);

    if($usuario != null && hash("sha256", $password) == $usuario->password){
        header("Location: inicio.php");
    }else {
        //crear mensaje de error
        header("Location: index.php");
    }


?>