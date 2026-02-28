<?php

?>

<head>
    <link rel="stylesheet" href="componentes/css/styles.css">
</head>

<body>
    <h2>BIBLIOTECA IAPWE - ACCESO</h2>

    <br>
    <h3>Introduzca sus credenciales</h3>
    <form action="componentes/login/comprobar-login.php" method="POST">
        <input type="text" name="nombre_usuario" placeholder="Usuario" required>
        <br><br>
        <input type="password" name="password" placeholder="Contraseña" required>
        <br><br>
        <input type="submit" value="Iniciar sesión">
    </form>

</html>