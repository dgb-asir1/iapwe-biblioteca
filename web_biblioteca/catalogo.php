<?php

require "config/conexion.php";
require "clases/libro.php";
require "clases/pelicula.php";


// LISTADO LIBROS

$filtroLibros = "";

if (!empty($_GET['filtrar_libros'])) {
    if (!empty($_GET['filtro_titulo_libro'])) {
        $titulo_para_filtrar = $_GET['filtro_titulo_libro'];
        $filtroLibros = "WHERE Libros.titulo LIKE '%$titulo_para_filtrar%'";
    }
    if (!empty($_GET['filtro_genero_libro'])) {
        $genero_para_filtrar = $_GET['filtro_genero_libro'];
        $filtroLibros = "WHERE Libros.genero LIKE '%$genero_para_filtrar%'";
    }
    if (!empty($_GET['filtro_autor_libro'])) {
        $autor_para_filtrar = $_GET['filtro_autor_libro'];
        $filtroLibros = "WHERE Autores.autor LIKE '%$autor_para_filtrar%'";
    }
    if (!empty($_GET['filtro_anyo_libro'])) {
        $anyo_para_filtrar = $_GET['filtro_anyo_libro'];
        $filtroLibros = "WHERE YEAR(Libros.fecha_publicacion) = '$anyo_para_filtrar'";
    }
}



$consultaLibros = "
    SELECT Libros.*, Autores.autor AS nombre_autor 
    FROM Libros
    INNER JOIN Autores 
        ON Libros.autor_id = Autores.id
";
$consultaLibros .= $filtroLibros;
$resultado = $conexion->query($consultaLibros);
$libros = [];

while (true) {
    $libro = $resultado->fetch_object(Libro::class);

    if ($libro == null) {
        break;
    }

    $libros[] = $libro;
}


// LISTADO PELICULAS

$filtroPeliculas = "";

if (!empty($_GET['filtrar_peliculas'])) {
    if (!empty($_GET['filtro_titulo_pelicula'])) {
        $titulo_para_filtrar = $_GET['filtro_titulo_pelicula'];
        $filtroPeliculas = "WHERE Peliculas.titulo LIKE '%$titulo_para_filtrar%'";
    }
    if (!empty($_GET['filtro_genero_pelicula'])) {
        $genero_para_filtrar = $_GET['filtro_genero_pelicula'];
        $filtroPeliculas = "WHERE Peliculas.genero LIKE '%$genero_para_filtrar%'";
    }
    if (!empty($_GET['filtro_director_pelicula'])) {
        $director_para_filtrar = $_GET['filtro_director_pelicula'];
        $filtroPeliculas = "WHERE Peliculas.director LIKE '%$director_para_filtrar%'";
    }
    if (!empty($_GET['filtro_anyo_pelicula'])) {
        $anyo_para_filtrar = $_GET['filtro_anyo_pelicula'];
        $filtroPeliculas = "WHERE YEAR(fecha_estreno) = '$anyo_para_filtrar'";
    }
}

$consultaPeliculas = "
    SELECT * FROM Peliculas
";
$consultaPeliculas .= $filtroPeliculas;
echo $consultaPeliculas;
$resultado = $conexion->query($consultaPeliculas);

$peliculas = [];

while (true) {
    $pelicula = $resultado->fetch_object(Pelicula::class);

    if ($pelicula == null) {
        break;
    }

    $peliculas[] = $pelicula;
}

?>



<?php require('./componentes/header.php') ?>

<h2>CATÁLOGO</h2>
<br>

<h3>Libros</h3>
<form action="catalogo.php" method="GET">
    <fieldset>
        <legend>
            <h4>Filtrar libros</h4>
        </legend>
        <label for="filtro_titulo_libro">Título </label><input type="text" name="filtro_titulo_libro"></input><br><br>
        <label for="filtro_autor_libro">Autor </label><input type="text" name="filtro_autor_libro"></input><br><br>        
        <label for="filtro_genero_libro">Género </label><input type="text" name="filtro_genero_libro"></input><brdirector
        <label for="filtro_anyo_libro">Año </label> <input type="number" name="filtro_anyo_libro" min="-10000" max="3000"></input><br><br>
        <input type="submit" name="filtrar_libros" value="Filtrar">
    </fieldset>
</form>

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
            <td class="genero">
                Género
            </td>
            <td class="editorial">
                Editorial
            </td>
            <td class="paginas">
                Nº Páginas
            </td>
            <td class="fecha_pub">
                Fecha pub.
            </td>
            <td class="precio">
                Precio
            </td>

        </tr>
    </thead>
    <?php foreach ($libros as $libro): ?>
        <tr>
            <td class="id">
                <?php echo $libro->id; ?>
            </td>
            <td class="titulo">
                <?php echo $libro->titulo; ?>
            </td>
            <td class="autor">
                <?php echo $libro->nombre_autor; ?>
            </td>
            <td class="genero">
                <?php echo $libro->genero; ?>
            </td>
            <td class="editorial">
                <?php echo $libro->editorial; ?>
            </td>
            <td class="num_paginas">
                <?php echo $libro->paginas; ?>
            </td>
            <td class="fecha_pub">
                <?php echo $libro->fecha_publicacion; ?>
            </td>
            <td class="precio">
                <?php echo $libro->precio; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<h3>Películas</h3>
<form action="catalogo.php" method="GET">
    <fieldset>
        <legend>
            <h4>Filtrar películas</h4>
        </legend>
        <label for="filtro_titulo_pelicula">Título </label><input type="text" name="filtro_titulo_pelicula"></input><br><br>
        <label for="filtro_director_pelicula">Director </label><input type="text" name="filtro_director_pelicula"></input><br><br>        
        <label for="filtro_genero_pelicula">Género </label><input type="text" name="filtro_genero_pelicula"></input><br><br>
        <label for="filtro_anyo_pelicula">Año </label> <input type="number" name="filtro_anyo_pelicula" min="-10000" max="3000"></input><br><br>
        <input type="submit" name="filtrar_peliculas" value="Filtrar">
    </fieldset>
</form>
<table class="catalogo">
    <thead>
        <tr class="cabecera">
            <td class="id">ID</td>
            <td class="titulo">Título</td>
            <td class="fecha_estreno">Fecha estreno</td>
            <td class="director">Director</td>
            <td class="actores">Actores</td>
            <td class="genero">Género</td>
            <td class="tipo_adaptacion">Tipo adaptación</td>
            <td class="adaptacion_id">Adaptación ID</td>
        </tr>
    </thead>

    <?php foreach ($peliculas as $pelicula): ?>
        <tr>
            <td class="id">
                <?php echo $pelicula->id; ?>
            </td>
            <td class="titulo">
                <?php echo $pelicula->titulo; ?>
            </td>
            <td class="año">
                <?php echo $pelicula->fecha_estreno; ?>
            </td>
            <td class="director">
                <?php echo $pelicula->director; ?>
            </td>
            <td class="actores">
                <?php echo $pelicula->actores; ?>
            </td>
            <td class="genero">
                <?php echo $pelicula->genero; ?>
            </td>
            <td class="tipo_adaptacion">
                <?php echo $pelicula->tipo_adaptacion; ?>
            </td>
            <td class="adaptacion_id">
                <?php echo $pelicula->adaptacion_id; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

</body>

</html>