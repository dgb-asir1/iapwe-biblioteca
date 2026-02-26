<?php

require "./config/conexion.php";
require "./clases/cliente.php";

$resultado = $conexion->query("SELECT * FROM Clientes");

$clientes = [];

while (true) {
    $cliente = $resultado->fetch_object(Cliente::class);

    if ($cliente == null) {
        break;
    }

    $clientes[] = $cliente;
}

?>



<?php require('./componentes/header.php') ?>

<h2>LISTADO DE CLIENTES</h2>
<br>

<form action="clientes-crear.php" method="GET">
    <fieldset>
        <legend>
            <h4>Crear cliente</h4>
        </legend>
        <label for="nombre_cliente">Nombre </label><input type="text" name="nombre_cliente"></input><br><br>
        <label for="apellidos_cliente">Apellidos </label><input type="text" name="apellidos_cliente"></input><br><br>
        <label for="fecha_nac_cliente">Fecha de nacimiento </label> <input type="number" name="fecha_nac_cliente" min="1900" max="3000"></input><br><br>           
        <label for="localidad_cliente">Localidad </label><input type="text" name="localidad_cliente"></input><br><br>

        <input type="submit" name="filtrar_libros" value="Filtrar">
    </fieldset>
</form>

<ul>
    <?php foreach ($clientes as $cliente): ?>

        <li>
            <section class="datos_cliente">
                <span>
                    <?php echo $cliente->id ?>
                </span>
                <?php echo $cliente->nombre . " " . $cliente->apellidos; ?>
            </section>
            <section class="botones_li">
                <a href="clientes-editar.php?id=<?php echo $cliente->id ?>">EDITAR</a>
                <a href="clientes-borrar.php?id=<?php echo $cliente->id ?>">BORRAR</a>
            </section>
        </li>

    <?php endforeach; ?>

</ul>
</body>

</html>