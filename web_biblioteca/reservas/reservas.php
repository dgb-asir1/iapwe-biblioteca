<?php

session_start();
if ($_SESSION['usuario_logeado'] == false) {
    header("Location: ../index.php");
    exit();
}

require "../componentes/config/conexion.php";
require "../componentes/clases/reserva.php";
require "../componentes/clases/cliente.php";


// DESPLEGABLES FORMULARIOS

function SelectLibros($conexion)
{
    $consulta = "SELECT Libros.id, Libros.titulo, Autores.autor, Reservas.libro_id, Reservas.activa FROM Libros LEFT JOIN Reservas on Libros.id = Reservas.libro_id 
            INNER JOIN Autores ON Libros.autor_id = Autores.id WHERE Reservas.id IS NULL OR Reservas.activa = 0";
    $sentencia = $conexion->prepare($consulta);
    $sentencia->execute();
    $resultado = $sentencia->get_result();
    while ($fila = $resultado->fetch_assoc()) {
        echo "<option value='" . $fila['id'] . "'>" . $fila['id'] . " - " . $fila['titulo'] . " (" . $fila['autor'] . ")" . "</option>";
    }
}

function SelectPeliculas($conexion)
{
    $consulta = "SELECT Peliculas.id, Peliculas.titulo, Peliculas.director, Reservas.pelicula_id, Reservas.activa FROM Peliculas LEFT JOIN Reservas on Peliculas.id = Reservas.pelicula_id WHERE Reservas.id IS NULL OR Reservas.activa = 0";
    $sentencia = $conexion->prepare($consulta);
    $sentencia->execute();
    $resultado = $sentencia->get_result();
    while ($fila = $resultado->fetch_assoc()) {
        echo "<option value='" . $fila['id'] . "'>" . $fila['id'] . " - " . $fila['titulo'] . " (" . $fila['director'] . ")" . "</option>";
    }
}

function SelectClientes($conexion)
{
    $consulta = "SELECT id, nombre, apellidos from Clientes";
    $sentencia = $conexion->prepare($consulta);
    $sentencia->execute();
    $resultado = $sentencia->get_result();
    while ($fila = $resultado->fetch_assoc()) {
        echo "<option value='" . $fila['id'] . "'>" . $fila['id'] . " - " . $fila['nombre'] . " " . $fila['apellidos'] . "</option>";
    }
}

function SelectClientesConReserva($conexion)
{
    $consulta = "SELECT Clientes.id, Clientes.nombre, Clientes.apellidos FROM Clientes INNER JOIN Reservas ON Clientes.id = Reservas.cliente_id GROUP BY Clientes.id";
    $sentencia = $conexion->prepare($consulta);
    $sentencia->execute();
    $resultado = $sentencia->get_result();
    while ($fila = $resultado->fetch_assoc()) {
        echo "<option value='" . $fila['id'] . "'>" . $fila['id'] . " - " . $fila['nombre'] . " " . $fila['apellidos'] . "</option>";
    }
}


// LISTADO TABLA RESERVAS

$filtroReservas = "";

if (!empty($_POST['filtrar']) && !empty($_POST['id_cliente_filtro'])) {

    $id_cliente_Filtro = $_POST['id_cliente_filtro'];

    $filtroReservas = " WHERE Clientes.id = $id_cliente_Filtro";
}

$consulta = "SELECT Reservas.*, Libros.titulo as titulo_libro, Peliculas.titulo as titulo_pelicula,
    Clientes.nombre as nombre_cliente, Clientes.apellidos as apellidos_cliente
    FROM Reservas
    LEFT JOIN Libros ON Reservas.libro_id = Libros.id
    LEFT JOIN Peliculas ON Reservas.pelicula_id = Peliculas.id
    INNER JOIN Clientes ON Reservas.cliente_id = Clientes.id
    ";
$consulta .= $filtroReservas;
$consulta .= " ORDER BY Fecha DESC, id DESC";
$resultado = $conexion->query($consulta);
$reservas = [];

while ($reserva = $resultado->fetch_object(Reserva::class)) {
    $reservas[] = $reserva;
}

// RESERVAR

if (
    (
        (!empty($_POST["reservar_libro"]) && !empty($_POST["id_libro"]))
        or
        (!empty($_POST["reservar_pelicula"]) && !empty($_POST["id_pelicula"]))
    )
    &&
    !empty($_POST["cliente"])
) {

    // PODRÍAN ESCOGER EN LOS DOS SELECT ANTES DE ENVIAR LA RESERVA, ASÍ QUE RESETEO LA ID A ""
    if (!empty($_POST["reservar_libro"])) {
        $libro_id = $_POST["id_libro"];
        $pelicula_id = NULL;
    } else if (!empty($_POST["reservar_pelicula"])) {
        $pelicula_id = $_POST["id_pelicula"];
        $libro_id = NULL;
    }

    $cliente = $_POST["cliente"];
    $fecha = date("Y-m-d");

    $consulta = "INSERT INTO Reservas (cliente_id, libro_id, pelicula_id, fecha) VALUES(?,?,?,?)";
    $sentencia = $conexion->prepare($consulta);
    $sentencia->bind_param("iiis", $cliente, $libro_id, $pelicula_id, $fecha);
    $sentencia->execute();

    header("Location: reservas.php?reserva_realizada");
    exit();
}


// DEVOLVER

if (!empty($_POST['cancelar'])) {

    $consulta = "UPDATE Reservas SET activa = 0 WHERE Reservas.id = ?";
    $sentencia = $conexion->prepare($consulta);
    $sentencia->bind_param("i", $_POST['id_reserva_a_cancelar']);
    $sentencia->execute();

    header("Location: reservas.php?devolucion_realizada");
    exit();
}


?>

<!-- VISTA -->
<?php require('../componentes/header.php') ?>


<div class="mensajeResultado">
    <?= (isset($_GET["reserva_realizada"])) ? "<br><span class='textoExito'>Reserva realizada.</span><br><br>" : '' ?>
    <?= (isset($_GET["devolucion_realizada"])) ? "<br><span class='textoExito'>Devolución realizada.</span><br><br>" : '' ?>
</div>

<form action="reservas.php" method="POST" class="form_horizontal">
    <fieldset>
        <legend>
            <h4>Reservar libro </h4>
        </legend>
        <select name="id_libro" class="select_titulos">
            <option value="" disabled selected>Escoger libro</option>
            <?php
            SelectLibros($conexion);
            ?>
        </select>
        <select name="cliente">
            <option value="" disabled selected>Escoger cliente</option>
            <?php
            SelectClientes($conexion);
            ?>
        </select>
        <input type="submit" name="reservar_libro" value="Reservar" class="formButton">
    </fieldset>
</form>

<form action="reservas.php" method="POST" class="form_horizontal">
    <fieldset>
        <legend>
            <h4>Reservar película</h4>
        </legend>
        <select name="id_pelicula" class="select_titulos">
            <option value="" disabled selected>Escoger película</option>
            <?php
            SelectPeliculas($conexion);
            ?>
        </select>
        <select name="cliente">
            <option value="" disabled selected>Escoger cliente</option>
            <?php
            SelectClientes($conexion);
            ?>
        </select>
        <input type="submit" name="reservar_pelicula" value="Reservar" class="formButton">
    </fieldset>
</form>

<form action="reservas.php" method="POST" class="form_horizontal">
    <fieldset>
        <legend>
            <h4>Filtrar reservas por cliente</h4>
        </legend>
        <select name="id_cliente_filtro">
            <option value="" disabled selected>Escoger cliente</option>
            <?php
            SelectClientesConReserva($conexion);
            ?>
        </select>
        <input type="submit" name="filtrar" value="filtrar" class="formButton">
    </fieldset>
</form>

<table>
    <thead>
        <tr id="cabecera">
            <td class="id">
                ID
            </td>
            <td class="titulo">
                Título
            </td>
            <td class="tipo">
                Tipo
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
            <td class="activa">
                Activa
            </td>
            <td class="cancelar">
                Devolución
            </td>
        </tr>
    </thead>
    <?php foreach ($reservas as $reserva): ?>
        <tr>
            <td class="id">
                <?= ($reserva->id !== null) ? $reserva->id : '' ?>
            </td>
            <td class="titulo">
                <?= ($reserva->titulo_libro !== null) ? $reserva->titulo_libro : '' ?>
                <?= ($reserva->titulo_pelicula !== null) ? $reserva->titulo_pelicula : '' ?>
            </td>
            <td class="tipo">
                <?= ($reserva->titulo_libro !== null) ? "<img src='../componentes/img/iconos/libro.png'>" : '' ?>
                <?= ($reserva->titulo_pelicula !== null) ? "<img src='../componentes/img/iconos/pelicula.png'>" : '' ?>
            </td>            
            <td class="nombre_cliente">
                <?= ($reserva->nombre_cliente !== null) ? $reserva->nombre_cliente : '' ?>
            </td>
            <td class="apellidos_cliente">
                <?= ($reserva->apellidos_cliente !== null) ? $reserva->apellidos_cliente : '' ?>
            </td>
            <td class="fecha">
                <?= ($reserva->fecha !== null) ? $reserva->fecha : '' ?>
            </td>
            <td class="activa">
                <?= ($reserva->activa == 1) ? "Sí" : 'No' ?>
            </td>
            <td class="cancelar">
                <form action="reservas.php" method="POST">
                    <input type="hidden" name="id_reserva_a_cancelar" value="<?php echo $reserva->id; ?>">
                    <input type="submit" name="cancelar" value="Devolver" class="tableButton">
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
</body>

</html>