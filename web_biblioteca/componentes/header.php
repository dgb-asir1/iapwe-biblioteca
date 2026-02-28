<head>
    <link rel="stylesheet" href="../componentes/css/styles.css">
</head>

<body>
    <h1>BIBLIOTECA IAPWE</h1>
    <nav>
        <a href="../reservas/reservas.php" <?= basename($_SERVER['PHP_SELF']) == 'reservas.php' ? 'class="selected"' : '' ?>> RESERVAS</a>        
        <a href="../catalogo/catalogo.php" <?= basename($_SERVER['PHP_SELF']) == 'catalogo.php' ? 'class="selected"' : '' ?>>CATÁLOGO</a>
        <a href="../clientes/clientes-listado.php" <?= basename($_SERVER['PHP_SELF']) == 'clientes-listado.php' ? 'class="selected"' : '' ?>>CLIENTES</a>
    </nav>
    <br>