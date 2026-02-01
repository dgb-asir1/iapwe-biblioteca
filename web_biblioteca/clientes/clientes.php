<?php

require "../config/conexion.php";
require "../clases/cliente.php";

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



<html>

<head>
    <link rel="stylesheet" href="../css/styles.css">
</head>

<body>
    <h1>LISTADO DE CLIENTES</h1>
    <ul>

        <?php foreach ($clientes as $cliente): ?>

            <li>
                <?php echo $cliente->nombre . " " . $cliente->apellidos; ?>
                <a href="editar.php?id=<?php echo $cliente->id ?>">Editar</a>
                <a href="borrar.php?id=<?php echo $cliente->id ?>">Borrar</a>
            </li>

        <?php endforeach; ?>

    </ul>
</body>

</html>