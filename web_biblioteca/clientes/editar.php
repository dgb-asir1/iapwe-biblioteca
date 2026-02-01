<?php

require "../config/conexion.php";

$id = $_GET["id"];

$consulta = "SELECT * FROM Clientes WHERE id = ?";
$sentencia = $conexion->prepare($consulta);
$sentencia->bind_param("i", $id);
$sentencia->execute();
$cliente = $sentencia->get_result()->fetch_assoc();

?>

<html>

<head>
    <link rel="stylesheet" href="../css/styles.css">
</head>

<body>
    <h1>EDITAR CLIENTES</h1>
    <form action="actualizar.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $cliente["id"]; ?>">
        <input type="text" name="nombre" value="<?php echo $cliente["nombre"]; ?>">
        <input type="text" name="apellidos" value="<?php echo $cliente["apellidos"]; ?>">
        <input type="text" name="fecha_nacimiento" value="<?php echo $cliente["fecha_nacimiento"]; ?>">
        <input type="text" name="localidad" value="<?php echo $cliente["localidad"]; ?>">
        <input type="submit" value="Actualizar cliente">
    </form>
    <!-- mensaje error -->
    <a href="../index.php">Volver al login</a>
</body>

</html>