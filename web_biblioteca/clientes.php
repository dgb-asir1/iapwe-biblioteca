<?php

    require "config/conexion.php";
    require "clases/cliente.php";

    $resultado = $conexion->query("SELECT * FROM clientes");

    $clientes = [];

    while(true){
        $cliente = $resultado->fetch_object(Cliente::class);

        if($cliente == null) {
            break;
        }

        $clientes[] = $cliente;
    }

?>



<html>

    <ul>

        <?php foreach($clientes as $cliente): ?>

            <li> 
                <?php echo $cliente->nombre; ?>
                <a href="editar.php?id=<?php echo $cliente->ID?>">Editar |</a>
                <a href="borrar.php?id=<?php echo $cliente->ID?>">Borrar</a>
            </li>

        <?php endforeach; ?>

    </ul>

</html>