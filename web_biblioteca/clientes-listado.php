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
        <label for="fecha_nac_cliente">Fecha de nacimiento </label> <input type="date" name="fecha_nac_cliente" min="1900" max="3000"></input><br><br>           
        <label for="localidad_cliente">Localidad </label><input type="text" name="localidad_cliente"></input><br><br>

        <input type="submit" name="crear_cliente" value="crear">
    </fieldset>
</form>


<table>
<thead>
        <tr class="cabecera">
            <td class="id">
                Nª Cliente
            </td>
            <td class="nombre_cliente">
                Nombre
            </td>
            <td class="apellidos_cliente">
                Apellidos
            </td>
            <td class="editar">
                Edición
            </td>
            <td class="eliminar">
                Eliminación
            </td>
        </tr>
    </thead>
<?php foreach ($clientes as $cliente): ?>
        <tr>
            <td class="id">
                <?php echo $cliente->id; ?>
            </td>
            <td class="nombre_cliente">
                <?php echo $cliente->nombre; ?>
            </td>
            <td class="apellidos_cliente">
                <?php echo $cliente->apellidos; ?>
            </td>
            <td class="editar">
                <a href="clientes-editar.php?id=<?php echo $cliente->id ?>">EDITAR</a>
            </td>
            <td class="eliminar">
                <a href="clientes-borrar.php?id=<?php echo $cliente->id ?>">BORRAR</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

</body>

</html>