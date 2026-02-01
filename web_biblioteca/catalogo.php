<?php

    require "config/conexion.php";
    require "clases/libro.php";
    require "clases/pelicula.php";

    $resultado = $conexion->query("SELECT * FROM Libros");

    $libros = [];

    while(true){
        $libro = $resultado->fetch_object(Libro::class);

        if($libro == null) {
            break;
        }

        $libros[] = $libro;
    }


    $resultado = $conexion->query("SELECT * FROM Peliculas");

    $peliculas = [];

    while(true){
        $pelicula = $resultado->fetch_object(Pelicula::class);

        if($pelicula == null) {
            break;
        }

        $peliculas[] = $pelicula;
    }

?>



<?php require('./componentes/header.php') ?>

        <h2>CATÁLOGO</h2>
        <br>
        <ul>
        <h2>Libros</h2>
        <?php foreach($libros as $libro): ?>
            <li> 
                <span>
                    <?php echo $libro->id;?>
                </span>
                <span>
                    <?php echo $libro->titulo;?>
                </span>
                <span>
                    Autor:<?php echo $libro->autor_id;?>
                </span>
                <span>
                    Género:<?php echo $libro->genero;?>
                </span>
                <span>
                    Editorial:<?php echo $libro->editorial;?>
                </span>
                <span>
                    Nº Páginas:<?php echo $libro->paginas;?>
                </span>
                <span>
                    Fecha de publicación:<?php echo $libro->fecha_publicacion;?>
                </span>                                                                                                    
                <span>
                    Precio:<?php echo $libro->precio;?>
                </span> 
            </li>
        <?php endforeach; ?>
        <h2>Películas</h2>
        <?php foreach($peliculas as $pelicula): ?>
            <li> 
                <span>
                    <?php echo $pelicula->id;?>
                </span>
                <span>
                    <?php echo $pelicula->titulo;?>
                </span>
            </li>
        <?php endforeach; ?>            
        </ul>
    </body>
</html>