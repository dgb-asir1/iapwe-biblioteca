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



<?php require('./componentes/header.php') ?>

        <h2>CATÁLOGO</h2>
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