<?php

?>


<html>
    <head>
        <link rel="stylesheet" href="./css/styles.css">
    </head>
    <body>
        <h1>BIBLIOTECA IAPWE</h1>
        <h3>Introduzca sus credenciales</h3>
        <form action="comprobar_login.php" method="POST">
            <input type="text" name="nombre_usuario" placeholder="Usuario" required>
            <br><br>
            <input type="password" name="password" placeholder="Contraseña" required>
            <br><br>
            <input type="submit" value="Iniciar sesión">
        </form>
        <!-- mensaje error -->
         <br>
        <a href="registro.html">Registrarse</a>
    </body>
</html>