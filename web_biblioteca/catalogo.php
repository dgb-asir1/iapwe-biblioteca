<?php

require "config/conexion.php";
require "clases/libro.php";
require "clases/pelicula.php";

$resultado = $conexion->query("
    SELECT Libros.*, Autores.autor AS nombre_autor 
    FROM Libros
    INNER JOIN Autores 
        ON Libros.autor_id = Autores.id
");
$libros = [];

while (true) {
    $libro = $resultado->fetch_object(Libro::class);

    if ($libro == null) {
        break;
    }

    $libros[] = $libro;
}


$resultado = $conexion->query("SELECT * FROM Peliculas");

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
<table class="catalogo">
    <thead>
        <tr class="cabecera">
            <td class="id">ID</td>
            <td class="titulo">Título</td>
            <td class="autor">Autor</td>
            <td class="genero">Género</td>
            <td class="editorial">Editorial</td>
            <td class="paginas">Nº Páginas</td>
            <td class="fecha_pub">Fecha pub.</td>
            <td class="precio">Precio</td>
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