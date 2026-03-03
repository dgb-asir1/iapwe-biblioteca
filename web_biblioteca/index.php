<?php

$mostrarError = "";
if (isset($_GET["error"]) && ($_GET["error"] == 1)) {
    $mostrarError = true;
}

?>

<head>
    <link rel="stylesheet" href="componentes/css/styles.css">
</head>

<body class="centered">
    <h1>Biblioteca IAPWE</h1>
    <form action="componentes/login/comprobar-login.php" method="POST" class="loginForm">
        <fieldset>
            <legend>Introduzca sus credenciales</legend>
            <input type="text" name="nombre_usuario" placeholder="Usuario" required>
            <br><br>
            <input type="password" name="password" placeholder="Contraseña" required>
            <br><br>
            <input type="submit" value="Iniciar sesión" class="formButton">
        </fieldset>
    </form>
    <?= ($mostrarError) ? '<p class="textoError">Credenciales inválidas</p>' : '' ?>
</body>

</html>