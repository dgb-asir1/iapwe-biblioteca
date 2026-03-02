<head>
    <link rel="stylesheet" href="../componentes/css/styles.css">
</head>

<body>
    <h1>Biblioteca IAPWE</h1>
    <nav>
        <a href="../reservas/reservas.php" <?= basename($_SERVER['PHP_SELF']) == 'reservas.php' ? 'class="selected"' : '' ?>>
            <img src="../componentes/img/iconos/reserva.png">
            RESERVAS
        </a>
        <a href="../catalogo/catalogo.php" <?= basename($_SERVER['PHP_SELF']) == 'catalogo.php' ? 'class="selected"' : '' ?>>
            <img src="../componentes/img/iconos/catalogo.png">
            CATÁLOGO
        </a>
        <a href="../clientes/clientes-listado.php" <?= basename($_SERVER['PHP_SELF']) == 'clientes-listado.php' ? 'class="selected"' : '' ?>>
            <img src="../componentes/img/iconos/clientes.png">
            CLIENTES
        </a>
    </nav>
    <br>