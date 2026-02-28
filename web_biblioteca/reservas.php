<?php

require "config/conexion.php";
require "clases/reserva.php";
require "clases/cliente.php";

// LISTADO RESERVAS
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
    LEFT JOIN Libros ON Reservas.libro_id = Libros.id
    LEFT JOIN Peliculas ON Reservas.pelicula_id = Peliculas.id
    INNER JOIN Clientes ON Reservas.cliente_id = Clientes.id
    ";
$consulta .= $filtroReservas;
$consulta .= " ORDER BY Fecha DESC";
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


// RESERVAR
$libroExiste = true;
$peliculaExiste = true;
$libroYaReservado = false;
$peliculaYaReservada = false;
$clienteExiste = true;
$libro_id = null;
$pelicula_id = null;


function ObtenerCliente($conexion, $nombre_cliente, $apellidos_cliente)
{
    $consulta = "SELECT Clientes.id FROM Clientes WHERE Clientes.nombre = ? AND Clientes.apellidos = ?";
    $sentencia = $conexion->prepare($consulta);
    $sentencia->bind_param("ss", $nombre_cliente, $apellidos_cliente);
    $sentencia->execute();
    $cliente = $sentencia->get_result()->fetch_assoc();
    return ($cliente);
}

function ObtenerLibro($conexion, $titulo_libro)
{
    $consulta = "SELECT Libros.id, Reservas.id as reserva FROM Libros LEFT JOIN Reservas on Libros.id = Reservas.libro_id WHERE Libros.titulo = ? ";
    $sentencia = $conexion->prepare($consulta);
    $sentencia->bind_param("s", $titulo_libro);
    $sentencia->execute();
    $libro = $sentencia->get_result()->fetch_assoc();
    return ($libro);
}

function ObtenerPelicula($conexion, $titulo_pelicula)
{
    $consulta = "SELECT Peliculas.id FROM Peliculas WHERE Peliculas.titulo = ?";
    $sentencia = $conexion->prepare($consulta);
    $sentencia->bind_param("s", $titulo_pelicula);
    $sentencia->execute();
    $pelicula = $sentencia->get_result()->fetch_assoc();
    return ($pelicula);
}

function EfectuarReserva($conexion, $libro_id, $pelicula_id, $cliente_id)
{
    $fecha = date("Y-m-d");

    echo "INTENTANDO RESERVAR LIBRO Nº $libro_id PARA EL CLIENTE $cliente_id";

    $consulta = "INSERT INTO Reservas (cliente_id, libro_id, pelicula_id, fecha) VALUES(?,?,?,?)";

    echo "la consulta es $consulta";

    $sentencia = $conexion->prepare($consulta);

    $sentencia->bind_param("iiis", $cliente_id, $libro_id, $pelicula_id, $fecha);
    $sentencia->execute();
}

if (
    isset($_GET["reservar"]) && (!empty($_GET["titulo_libro"]) || !empty($_GET["titulo_pelicula"]))
    && !empty($_GET["nombre_cliente"]) && !empty($_GET["apellidos_cliente"])
) {

    // SI HEMOS METIDO UN LIBRO IGNORAMOS LA PELÍCULA
    if (isset($_GET["titulo_libro"])) {
        echo "hola";
        var_dump($_GET["titulo_libro"]);
        $libro = ObtenerLibro($conexion, $_GET["titulo_libro"]);
        var_dump($libro);
        if ($libro !== null) {
            $libroExiste = true;

            var_dump($libro);
            if ($libro["reserva"] !== null) {
                $libroYaReservado = true;
            } else {
                $libro_id = $libro["id"];
            }
        } else {
            $libroExiste = false;
        }
    } else {
        $pelicula = ObtenerPelicula($conexion, $_GET["titulo_pelicula"]);
        if ($pelicula !== null) {
            $peliculaExiste = true;
            $pelicula_id = $pelicula["id"];
        } else {
            $peliculaExiste = false;
        }
    }

    $cliente = ObtenerCliente($conexion, $_GET["nombre_cliente"], $_GET["apellidos_cliente"]);
    if ($cliente !== null) {

        $cliente_id = $cliente["id"];
    } else {
        $clienteExiste = false;
    }

    if (
        (($libroExiste && !$libroYaReservado) || ($peliculaExiste && !$peliculaYaReservada))
        && ($clienteExiste)
    ) {
        EfectuarReserva($conexion, $libro_id, $pelicula_id, $cliente_id);
    }
}

// DEVOLVER

function CancelarReserva($conexion, $id_reserva)
{

    $consulta = "UPDATE Reservas SET activa = 0 WHERE Reservas.id = ?";


    $sentencia = $conexion->prepare($consulta);

    $sentencia->bind_param("i", $id_reserva);
    $sentencia->execute();
}

if (!empty($_GET['devolver'])) {
    CancelarReserva($conexion, $_GET['id_reserva_a_cancelar']);
}


?>

<!-- VISTA -->
<?php require('./componentes/header.php') ?>

<h2>RESERVAS</h2>

<div class="mensajeResultado">
    <?= (!$libroExiste) ? "<br><span class='textoError'>Libro no encontrado</span><br><br>" : '' ?>
    <?= (!$peliculaExiste) ? "<br><span class='textoError'>Película no encontrado</span><br><br>" : '' ?>
    <?= ($libroYaReservado) ? "<br><span class='textoError'>Libro ya reservado.</span><br><br>" : '' ?>
    <?= ($peliculaYaReservada) ? "<br><span class='textoError'>Película ya reservada.</span><br><br>" : '' ?>
    <?= (!$clienteExiste) ? "<br><span class='textoError'>Cliente no encontrado.</span><br><br>" : '' ?>
</div>

<form action="reservas.php" method="GET">
    <fieldset>
        <legend>
            <h4>Reservar libro o película</h4>
        </legend>
        <label for="titulo_libro">Libro</label><input type="text" name="titulo_libro"></input><br><br>
        <label for="titulo_pelicula">Pelicula</label><input type="text" name="titulo_pelicula"></input><br><br>
        <label for="nombre_cliente">Nombre cliente</label><input type="text" name="nombre_cliente"></input><br><br>
        <label for="apellidos_cliente">Apellidos cliente</label><input type="text" name="apellidos_cliente"></input><br><br>
        <input type="submit" name="reservar" value="Reservar">
    </fieldset>
</form>

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
            <td class="activa">
                Activa
            </td>
            <td class="devolver">
                Devolución
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
            <td class="activa">
                <?= ($reserva->activa == 1) ? "Sí" : 'No' ?>
            </td>
            <td class="devolver">
                <form action="reservas.php" method="GET">
                    <input type="hidden" name="id_reserva_a_cancelar" value="<?php echo $reserva->id; ?>">
                    <input type="submit" name="devolver" value="Devolver">
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
</body>

</html>