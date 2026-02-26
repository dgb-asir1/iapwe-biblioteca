<?php

require "config/conexion.php";
require "clases/reserva.php";

$filtroReservas = "";


if (!empty($_GET['filtrar_reservas'])) {
    if (!empty($_GET['filtro_reservas_nombre_usuario'])) {
        $nombre_para_filtrar = $_GET['filtro_reservas_nombre_usuario'];
        $filtroReservas = " WHERE Clientes.nombre LIKE '%$nombre_para_filtrar%'";
    }
}

$consulta = "SELECT Reservas.*, Libros.titulo as titulo_libro,
    Clientes.nombre as nombre_cliente, Clientes.apellidos as apellidos_cliente
    FROM Reservas 
    INNER JOIN Libros on Reservas.libro_id = Libros.id 
    INNER JOIN Clientes on Reservas.cliente_id = Clientes.id";
$consulta .= $filtroReservas;

echo $consulta;

$resultado = $conexion->query($consulta);

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
<form action="reservas.php" method="GET">
    <fieldset>
        <legend>
            <h4>Filtrar reservas por usuario</h4>
        </legend>
        <label for="filtro_reservas_nombre_usuario">Introducir nombre de usuario</label><input type="text" name="filtro_reservas_nombre_usuario"></input><br><br>
        <input type="submit" name="filtrar_reservas" value="Filtrar">
    </fieldset>
</form>

<table class="catalogo">
    <thead>
        <tr class="cabecera">
            <td class="id">
                ID
            </td>
            <td class="titulo">
                Título
            </td>
            <td class="nombre_cliente">
                Nombre cliente
            </td>
            <td class="apellidos_cliente">
                Apellidos cliente
            </td>
            <td class="fecha">
                Fecha
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
            <td class="nombre_cliente">
                <?php echo $reserva->nombre_cliente; ?>
            </td>
            <td class="apellidos_cliente">
                <?php echo $reserva->apellidos_cliente; ?>
            </td>
            <td class="fecha">
                <?php echo $reserva->fecha; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
</body>

</html>