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
    <ul>

        <?php foreach ($clientes as $cliente): ?>

            <li>
                <?php echo $cliente->nombre . " " . $cliente->apellidos; ?>
                <a href="clientes_editar.php?id=<?php echo $cliente->id ?>">Editar</a>
                <a href="clientes_borrar.php?id=<?php echo $cliente->id ?>">Borrar</a>
            </li>

        <?php endforeach; ?>

    </ul>
</body>

</html>