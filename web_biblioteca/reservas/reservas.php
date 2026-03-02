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
$filtrarPorNombre = false;
$filtrarPorApellidos = false;


if (!empty($_POST['filtrar_reservas'])) {

    if (!empty($_POST['filtro_reservas_nombre_cliente'])) {
        $filtrarPorNombre = true;
        $nombre_para_filtrar = $_POST['filtro_reservas_nombre_cliente'];
    }
    if (!empty($_POST['filtro_reservas_apellidos_cliente'])) {
        $filtrarPorApellidos = true;
        $apellidos_para_filtrar = $_POST['filtro_reservas_apellidos_cliente'];
    }

    if ($filtrarPorNombre && $filtrarPorApellidos) {
        $filtroReservas = " WHERE Clientes.nombre LIKE '%$nombre_para_filtrar%' AND Clientes.apellidos LIKE '%$apellidos_para_filtrar%' ";
    } else if ($filtrarPorNombre) {
        $filtroReservas = " WHERE Clientes.nombre LIKE '%$nombre_para_filtrar%' ";
    } else if ($filtrarPorApellidos) {
        $filtroReservas = " WHERE Clientes.apellidos LIKE '%$apellidos_para_filtrar%' ";
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
$libro_id = null;
$pelicula_id = null;








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
    ((!empty($_POST["reservar_libro"])) || (!empty($_POST["reservar_pelicula"])))
    && !empty($_POST["id_cliente"]) 
) {
    echo "Vamos a reservar algo !!!!!!!!!!!!";

    if (($_POST["tipo_reserva"] == 'libro')) {
        $libro = ObtenerLibro($conexion, $_POST["titulo"]);
        if ($libro !== null && $libro !== false) {

            $libroEncontrado = true;

            if (ComprobarReservaLibro($conexion, $libro["id"]) > 0) {
                $libroYaReservado = true;
                header("Location: reservas.php?libro_ya_reservado");
                exit();
            } else {
                echo "el libro no esta reservado<br>";
                $libro_id = $libro["id"];
                $libroYaReservado = false;
            }
        } else {
            $libroEncontrado = false;
            header("Location: reservas.php?libro_no_encontrado");
            exit();
        }
    } else {
        $pelicula = ObtenerPelicula($conexion, $_POST["titulo"]);
        if ($pelicula !== null && $pelicula !== false) {

            $peliculaEncontrada = true;

            if (ComprobarReservaPelicula($conexion, $pelicula["id"]) > 0) {
                $peliculaYaReservada = true;
                header("Location: reservas.php?pelicula_ya_reservada");
                exit();
            } else {
                $pelicula_id = $pelicula["id"];
                $peliculaYaReservada = false;
            }
        } else {
            $peliculaEncontrada = false;
            header("Location: reservas.php?pelicula_no_encontrada");
            exit();
        }
    }

    $cliente = ObtenerCliente($conexion, $_POST["nombre_cliente"], $_POST["apellidos_cliente"]);
    if ($cliente !== null && $cliente !== false) {
        $clienteEncontrado = true;
        $cliente_id = $cliente["id"];
    } else {
        $clienteEncontrado = false;
        header("Location: reservas.php?cliente_no_encontrado");
        exit();
    }



    if (
        (($libroEncontrado && !$libroYaReservado) || ($peliculaEncontrada && !$peliculaYaReservada))
        && ($clienteEncontrado)
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


<div class="mensajeResultado">
    <?= (isset($_GET["libro_no_encontrado"])) ? "<br><span class='textoError'>Libro no encontrado</span><br><br>" : '' ?>
    <?= (isset($_GET["libro_ya_reservado"])) ? "<br><span class='textoError'>Libro ya reservado</span><br><br>" : '' ?>
    <?= (isset($_GET["pelicula_no_encontrada"])) ? "<br><span class='textoError'>Película no encontrada</span><br><br>" : '' ?>
    <?= (isset($_GET["pelicula_ya_reservada"])) ? "<br><span class='textoError'>Película ya reservada</span><br><br>" : '' ?>
    <?= (isset($_GET["cliente_no_encontrado"])) ? "<br><span class='textoError'>Cliente no encontrado</span><br><br>" : '' ?>
</div>

<form action="reservas.php" method="POST" class="form_horizontal">
    <fieldset>
        <legend>
            <h4>Reservar libro </h4>
        </legend>
        <select name="titulo_libro" class="select_titulos">
            <option value="" disabled selected>Escoger libro</option>
            <?php
            $consulta = "SELECT Libros.id, Libros.titulo, Autores.autor, Reservas.libro_id, Reservas.activa FROM Libros LEFT JOIN Reservas on Libros.id = Reservas.libro_id 
            INNER JOIN Autores ON Libros.autor_id = Autores.id WHERE Reservas.id IS NULL OR Reservas.activa = 0";
            $sentencia = $conexion->prepare($consulta);
            $sentencia->execute();
            $resultado = $sentencia->get_result();
            while ($fila = $resultado->fetch_assoc()) {
                echo "<option value='" . $fila['id'] . "'>" . $fila['id'] . " - " . $fila['titulo'] . " (" .$fila['autor'] .")" . "</option>";
            }
            ?>
        </select>
        <select name="cliente">
            <option value="" disabled selected>Escoger cliente</option>
            <?php
            $consulta = "SELECT id, nombre, apellidos from Clientes";
            $sentencia = $conexion->prepare($consulta);
            $sentencia->execute();
            $resultado = $sentencia->get_result();
            while ($fila = $resultado->fetch_assoc()) {
                echo "<option value='" . $fila['id'] . "'>" . $fila['id'] . " - " . $fila['nombre'] . " " .$fila['apellidos'] . "</option>";
            }
            ?>
        </select> 
        <input type="submit" name="reservar" value="Reservar" class="formButton">
    </fieldset>
</form>

<form action="reservas.php" method="POST" class="form_horizontal">
    <fieldset>
        <legend>
            <h4>Reservar película</h4>
        </legend>
        <select name="titulo_pelicula" class="select_titulos">
            <option value="" disabled selected>Escoger película</option>
            <?php
            $consulta = "SELECT Peliculas.id, Peliculas.titulo, Peliculas.director, Reservas.pelicula_id, Reservas.activa FROM Peliculas LEFT JOIN Reservas on Peliculas.id = Reservas.pelicula_id WHERE Reservas.id IS NULL OR Reservas.activa = 0";
            $sentencia = $conexion->prepare($consulta);
            $sentencia->execute();
            $resultado = $sentencia->get_result();
            while ($fila = $resultado->fetch_assoc()) {
                echo "<option value='" . $fila['id'] . "'>" . $fila['id'] . " - " . $fila['titulo'] . " (" .$fila['director'] .")" . "</option>";
            }
            ?>
        </select>        
        <select name="cliente">
            <option value="" disabled selected>Escoger cliente</option>
            <?php
            $consulta = "SELECT id, nombre, apellidos from Clientes";
            $sentencia = $conexion->prepare($consulta);
            $sentencia->execute();
            $resultado = $sentencia->get_result();
            while ($fila = $resultado->fetch_assoc()) {
                echo "<option value='" . $fila['id'] . "'>" . $fila['id'] . " - " . $fila['nombre'] . " " .$fila['apellidos'] . "</option>";
            }
            ?>
        </select> 
        <input type="submit" name="reservar" value="Reservar" class="formButton">
    </fieldset>
</form>

<form action="reservas.php" method="POST" class="form_horizontal">
    <fieldset>
        <legend>
            <h4>Filtrar reservas por cliente</h4>
        </legend>
        <select name="filtro_cliente">
            <option value="" disabled selected>Escoger cliente</option>
            <?php
            $consulta = "SELECT Clientes.id, Clientes.nombre, Clientes.apellidos FROM Clientes INNER JOIN Reservas ON Clientes.id = Reservas.cliente_id";
            $sentencia = $conexion->prepare($consulta);
            $sentencia->execute();
            $resultado = $sentencia->get_result();
            while ($fila = $resultado->fetch_assoc()) {
                echo "<option value='" . $fila['id'] . "'>" . $fila['id'] . " - " . $fila['nombre'] . " " .$fila['apellidos'] . "</option>";
            }
            ?>
        </select> 
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