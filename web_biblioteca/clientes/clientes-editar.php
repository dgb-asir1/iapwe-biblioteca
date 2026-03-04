<?php

session_start();
if ($_SESSION['usuario_logeado'] == false) {
    header("Location: ../index.php");
}

require "../componentes/config/conexion.php";

$id = $_GET["id"];

$consulta = "SELECT * FROM Clientes WHERE id = ?";
$sentencia = $conexion->prepare($consulta);
$sentencia->bind_param("i", $id);
$sentencia->execute();
$cliente = $sentencia->get_result()->fetch_assoc();

?>

<!-- VISTA -->
 
<html>

<?php require('../componentes/header.php') ?>

<br>

<form action="clientes-actualizar.php" method="POST" class="form_horizontal">
    <fieldset>
        <legend>
            <h4>EDITAR CLIENTE</h4>
        </legend>
        <input type="hidden" name="id" value="<?php echo $cliente["id"]; ?>">
        <label for="nombre">Nombre </label>
        <input type="text" name="nombre" value="<?php echo $cliente["nombre"]; ?>">
        <label for="nombre">Apellidos </label>
        <input type="text" name="apellidos" value="<?php echo $cliente["apellidos"]; ?>">
        <label for="nombre">Fecha de nacimiento </label>
        <input type="date" name="fecha_nacimiento" value="<?php echo $cliente["fecha_nacimiento"]; ?>">
        <label for="nombre">Localidad </label>
        <input type="text" name="localidad" value="<?php echo $cliente["localidad"]; ?>">
        <input type="submit" value="Actualizar cliente" class="formButton">
    </fieldset>
</form>

</body>

</html>