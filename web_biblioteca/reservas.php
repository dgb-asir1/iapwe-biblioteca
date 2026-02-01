<?php

require "config/conexion.php";
require "clases/reserva.php";

$resultado = $conexion->query("SELECT * FROM Reservas");

$reservas = [];

while (true) {
    $reserva = $resultado->fetch_object(Reserva::class);

    if ($reserva == null) {
        break;
    }

    $reservas[] = $reserva;
}

?>



<html>

<head>
    <link rel="stylesheet" href="./css/styles.css">
</head>

<body>
    <h1>RESERVAS</h1>
    <br>
    <ul>
        <?php foreach ($reservas as $reserva): ?>

            <li>
                <?php echo $reserva->id; ?>
                <?php echo $reserva->cliente_id; ?>
                <?php echo $reserva->libro_id; ?>
                <?php echo $reserva->fecha; ?>
            </li>

        <?php endforeach; ?>

    </ul>
</body>

</html>