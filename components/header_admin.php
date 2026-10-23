<?php if (!isset($_SESSION)) {
    session_start();
} ?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Panel Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="/tiendApp/css/styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>

<body id="fondo_admin">
    <header>
        <nav class="navbar navbar-expand-lg fixed-top" style="background-color: rgba(35, 0, 105, 1);">
            <div class="container-fluid"> 
                <a class="navbar-brand montserrat-bold" href="/tiendApp/admin/index.php">
                    <img src="/tiendApp/imgs/logo.png" alt="Logo" width="30" height="30" class="d-inline-block align-text-top"> TiendApp - ADMIN
                </a> 
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation"> 
                    <span class="navbar-toggler-icon"></span> 
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item"> 
                            <a class="nav-link montserrat-bold" aria-current="page" href="/tiendApp/admin/categoria.php">Ver categorías</a> 
                        </li>
                        <li class="nav-item"> 
                            <a class="nav-link montserrat-bold" href="/tiendApp/admin/sugerencias.php">Ver sugerencias</a> 
                        </li>
                        <li class="nav-item">
                            <a class="nav-link montserrat-bold" href="/tiendApp/admin/usuarios.php">Usuarios</a>
                        </li>
                        <li class='nav-item dropdown'> 
                            <a class='nav-link dropdown-toggle montserrat-bold' href='#' role='button' data-bs-toggle='dropdown' aria-expanded='false'> 
                                👋 Hola, <?= htmlspecialchars($_SESSION['nombre'] ?? ''); ?> 
                            </a>
                            <ul class='dropdown-menu dropdown-menu-end'>
                                <li><a class='dropdown-item montserrat-bold' href='/tiendApp/admin/perfil.php'>Ver perfil</a></li>
                                <li>
                                    <hr class='dropdown-divider'>
                                </li>
                                <li><a class='dropdown-item text-danger montserrat-bold' href='/tiendApp/log/cerrar.php'>Cerrar sesión</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>