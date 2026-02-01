<?php

    require "config/conexion.php";
    require "clases/libro.php";

    $resultado = $conexion->query("SELECT * FROM Libros");

    $libros = [];

    while(true){
        $libro = $resultado->fetch_object(Libro::class);

        if($libro == null) {
            break;
        }

        $libros[] = $libro;
    }

?>



<html>
    <head>
        <link rel="stylesheet" href="./css/styles.css">
    </head>
    <body>
        <h1>CATÁLOGO</h1>
        <br>
        <ul>

            <?php foreach($libros as $libro): ?>

                <li> 
                    <?php echo $libro->titulo; ?>
                </li>

            <?php endforeach; ?>

        </ul>
    </body>
</html>