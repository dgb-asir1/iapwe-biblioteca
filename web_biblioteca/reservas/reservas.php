<?php

session_start();
if ($_SESSION['usuario_logeado'] == false) {
    header("Location: ../index.php");
    exit();
}

require "../componentes/config/conexion.php";
require "../componentes/clases/reserva.php";
require "../componentes/clases/cliente.php";

// LISTADO RESERVAS
$filtroReservas = "";

if (!empty($_POST['filtrar_reservas'])) {
    if (!empty($_POST['filtro_reservas_nombre_cliente'])) {
        $nombre_para_filtrar = $_POST['filtro_reservas_nombre_cliente'];
        $filtroReservas = " WHERE Clientes.nombre LIKE '%$nombre_para_filtrar%'";
    }
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

while (true) {
    $reserva = $resultado->fetch_object(Reserva::class);

    if ($reserva == null) {
        break;
    }

    $reservas[] = $reserva;
}


// RESERVAR
$libroExiste = false;
$peliculaExiste = false;
$libroYaReservado = false;
$peliculaYaReservada = false;
$clienteExiste = false;
$libro_id = null;
$pelicula_id = null;


function ObtenerLibro($conexion, $titulo_libro)
{
    $consulta = "SELECT id FROM Libros WHERE titulo = ? ";
    $sentencia = $conexion->prepare($consulta);
    $sentencia->bind_param("s", $titulo_libro);
    $sentencia->execute();
    $libro = $sentencia->get_result()->fetch_assoc();
    return ($libro);
}

function ObtenerPelicula($conexion, $titulo_pelicula)
{
    $consulta = "SELECT id FROM Peliculas WHERE titulo = ? ";
    $sentencia = $conexion->prepare($consulta);
    $sentencia->bind_param("s", $titulo_pelicula);
    $sentencia->execute();
    $pelicula = $sentencia->get_result()->fetch_assoc();
    return ($pelicula);
}

function ComprobarReservaLibro($conexion, $id_libro)
{
    $consulta = "SELECT COUNT(*) as cantidad_reservas_activas FROM Reservas WHERE libro_id = ? AND activa = 1";
    $sentencia = $conexion->prepare($consulta);
    $sentencia->bind_param("i", $id_libro);
    $sentencia->execute();
    $resultado = $sentencia->get_result()->fetch_assoc();
    return $resultado["cantidad_reservas_activas"];
}

function ComprobarReservaPelicula($conexion, $id_pelicula)
{
    $consulta = "SELECT COUNT(*) as cantidad_reservas_activas FROM Reservas WHERE pelicula_id = ? AND activa = 1";
    $sentencia = $conexion->prepare($consulta);
    $sentencia->bind_param("i", $id_pelicula);
    $sentencia->execute();
    $resultado = $sentencia->get_result()->fetch_assoc();
    return $resultado["cantidad_reservas_activas"];
}


function ObtenerCliente($conexion, $nombre_cliente, $apellidos_cliente)
{
    $consulta = "SELECT Clientes.id FROM Clientes WHERE Clientes.nombre = ? AND Clientes.apellidos = ?";
    $sentencia = $conexion->prepare($consulta);
    $sentencia->bind_param("ss", $nombre_cliente, $apellidos_cliente);
    $sentencia->execute();
    $cliente = $sentencia->get_result()->fetch_assoc();
    return ($cliente);
}


function EfectuarReserva($conexion, $libro_id, $pelicula_id, $cliente_id)
{
    $fecha = date("Y-m-d");

    $consulta = "INSERT INTO Reservas (cliente_id, libro_id, pelicula_id, fecha) VALUES(?,?,?,?)";

    $sentencia = $conexion->prepare($consulta);

    $sentencia->bind_param("iiis", $cliente_id, $libro_id, $pelicula_id, $fecha);
    $sentencia->execute();

    // INTENTANDO EVITAR QUE SE VUELVAN A CREAR RESERVAS SALTANDO TODA LA LÓGICA
    header("Location: reservas.php");
    exit();
}

if (
    isset($_POST["reservar"])
) {
    echo "reservar is set<br>";
}
if (
    !empty($_POST["reservar"])
) {
    echo "reservar is not empty!<br>";
};

if (
    isset($_POST["titulo_libro"])
) {
    echo "titulo_libro is set<br>";
}
if (
    !empty($_POST["titulo_libro"])
) {
    echo "titulo_libro is not empty!<br>";
};
if (
    isset($_POST["nombre_cliente"])
) {
    echo "nombre_cliente is set<br>";
}
if (
    !empty($_POST["nombre_cliente"])
) {
    echo "nombre_cliente is not empty!<br>";
};

if (
    isset($_POST["reservar"]) &&  (!empty($_POST["tipo_reserva"])) && (!empty($_POST["titulo"]))
    && !empty($_POST["nombre_cliente"]) && !empty($_POST["apellidos_cliente"])
) {
    echo "Vamos a reservar algo !!!!!!!!!!!!";

    if (($_POST["tipo_reserva"] == 'libro')) {
        $libro = ObtenerLibro($conexion, $_POST["titulo"]);
        if ($libro !== null && $libro !== false) {

            $libroExiste = true;
            echo "el libro existe<br>";

            if (ComprobarReservaLibro($conexion, $libro["id"]) > 0) {
                echo "el libro ya esta reservado<br>";
                $libroYaReservado = true;
            } else {
                echo "el libro no esta reservado<br>";
                $libro_id = $libro["id"];
                $libroYaReservado = false;
            }
        } else {
            $libroExiste = false;
            echo "el libro no existe<br>";
        }
    } else {
        $pelicula = ObtenerPelicula($conexion, $_POST["titulo"]);
        if ($pelicula !== null && $pelicula !== false) {

            $peliculaExiste = true;
            echo "la pelicula existe<br>";


            if (ComprobarReservaPelicula($conexion, $pelicula["id"]) > 0) {
                echo "el pelicula ya esta reservada<br>";
                $peliculaYaReservada = true;
            } else {
                echo "la pelicula no esá reservada<br>";
                $pelicula_id = $pelicula["id"];
                $peliculaYaReservada = false;
            }
        } else {
            $peliculaExiste = false;
            echo "la pelicula no existe<br>";
        }
    }

    $cliente = ObtenerCliente($conexion, $_POST["nombre_cliente"], $_POST["apellidos_cliente"]);
    if ($cliente !== null && $cliente !== false) {
        echo "el cliente existe<br>";
        $clienteExiste = true;
        $cliente_id = $cliente["id"];
    } else {
        $clienteExiste = false;
        echo "el cliente no existe<br>";
    }



    if (
        (($libroExiste && !$libroYaReservado) || ($peliculaExiste && !$peliculaYaReservada))
        && ($clienteExiste)
    ) {
        echo "preparados para efectuar reserva<br>";
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

    header("Location: reservas.php");
    exit();
}

if (!empty($_POST['devolver'])) {
    CancelarReserva($conexion, $_POST['id_reserva_a_cancelar']);
}


?>

<!-- VISTA -->
<?php require('../componentes/header.php') ?>




<form action="reservas.php" method="POST" class="form_horizontal">
    <fieldset>
        <legend>
            <h4>Reservar libro o película</h4>
        </legend>
        <label for="tipo_reserva">Libro</label><input type="radio" name="tipo_reserva" value="libro">
        <label for="tipo_reserva">Película</label><input type="radio" name="tipo_reserva" value="pelicula">
        <label for="titulo">Título </label><input type="text" name="titulo"></input>
        <label for="nombre_cliente">Nombre cliente </label><input type="text" name="nombre_cliente"></input>
        <label for="apellidos_cliente">Apellidos cliente </label><input type="text" name="apellidos_cliente"></input>
        <input type="submit" name="reservar" value="Reservar" class="formButton">
    </fieldset>
</form>

<form action="reservas.php" method="POST" class="form_horizontal">
    <fieldset>
        <legend>
            <h4>Filtrar reservas por cliente</h4>
        </legend>
        <label for="filtro_reservas_nombre_cliente">Nombre </label><input type="text" name="filtro_reservas_nombre_cliente"></input>
        <label for="filtro_reservas_apellidos_cliente">Apellidos </label><input type="text" name="filtro_reservas_apellidos_cliente"></input>
        <input type="submit" name="filtrar_reservas" value="Filtrar" class="formButton">
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
                <?= ($reserva->id !== null) ? $reserva->id : '' ?>
            </td>
            <td class="titulo">
                <?= ($reserva->titulo_libro !== null) ? $reserva->titulo_libro : '' ?>
                <?= ($reserva->titulo_pelicula !== null) ? $reserva->titulo_pelicula : '' ?>
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
            <td class="devolver">
                <form action="reservas.php" method="POST">
                    <input type="hidden" name="id_reserva_a_cancelar" value="<?php echo $reserva->id; ?>">
                    <input type="submit" name="devolver" value="Devolver" class="tableButton">
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
</body>

</html>