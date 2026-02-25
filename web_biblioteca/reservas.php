<?php

require "config/conexion.php";
require "clases/reserva.php";

$resultado = $conexion->query("SELECT Reservas.*, Libros.titulo as titulo_libro FROM Reservas INNER JOIN Libros on Reservas.libro_id = Libros.id");

$reservas = [];

while (true) {
    $reserva = $resultado->fetch_object(Reserva::class);

    if ($reserva == null) {
        break;
    }

    $reservas[] = $reserva;
}

?>


<?php require('./componentes/header.php') ?>

<h2>RESERVAS</h2>

<br><br>
<table class="catalogo">
    <thead>
        <tr class="cabecera">
            <td class="id">
                ID
            </td>
            <td class="titulo">
                Título
            </td>
            <td class="autor">
                Autor
            </td>
        </tr>
    </thead>
    <?php foreach ($reservas as $reserva): ?>
        <tr>
            <td class="id">
                <?php echo $reserva->id; ?>
            </td>
            <td class="titulo">
                <?php echo $reserva->titulo_libro; ?>
            </td>
            <td class="autor">
                <?php echo $reserva->fecha; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
</body>

</html>