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
                <section class="datos_cliente">
                    <span class="id_cliente">
                        ID: <?php echo $cliente->id ?>
                    </span>
                    
                    <?php echo $cliente->nombre . " " . $cliente->apellidos; ?>
                </section>
                <section class="botones_li">
                    <a href="clientes-editar.php?id=<?php echo $cliente->id ?>">Editar</a>
                    <a href="clientes-borrar.php?id=<?php echo $cliente->id ?>">Borrar</a>
                </section>
            </li>

        <?php endforeach; ?>

    </ul>
</body>

</html>