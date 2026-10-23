<?php
if (!isset($_SESSION)) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TiendApp</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <link rel="stylesheet" href="/tiendApp/css/styles.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>

<body id="fondo">

  <header>
    <nav class="navbar navbar-expand-lg fixed-top" style="background-color: rgba(63, 0, 105, 1);">
      <div class="container-fluid">
        <a class="navbar-brand montserrat-bold" href="/tiendApp/pages/inicio.php"><img src="/tiendApp/imgs/logo.png" alt="Logo" width="30" height="30" class="d-inline-block align-text-top"> TiendApp</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link montserrat-bold" aria-current="page" href="/tiendApp/pages/nosotros.php">Nosotros</a>
            </li>
            <li class="nav-item">
              <a class="nav-link montserrat-bold" href="/tiendApp/pages/contacto.php">Contacto</a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle montserrat-bold" href="/tiendApp/pages/inicio.php" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Categorías
              </a>
              <ul class="dropdown-menu">
                <?php
                include_once($_SERVER['DOCUMENT_ROOT'] . "/tiendApp/components/config/config.php");
                $consulta = "SELECT * FROM `categorias`";
                $respuesta = mysqli_query($con, $consulta);
                if ($respuesta) {
                  while ($fila = mysqli_fetch_assoc($respuesta)) {
                    $id = htmlspecialchars($fila['id_categoria']);
                    $nombre = htmlspecialchars($fila['categoria']);

                    print "
                                 <li>
                                     <a class='dropdown-item montserrat-bold' href='/tiendApp/pages/categoria.php?id=$id'>$nombre</a>
                                 </li>
                             ";
                  }
                }
                ?>
              </ul>
            </li>
          </ul>
          <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
            <?php
            if (!isset($_SESSION['nombre'])) {
              print "
            <li class='nav-item'><a class='nav-link montserrat-bold' href='/tiendApp/pages/login.php'>Iniciar sesión</a></li>
            <li class='nav-item'><a class='nav-link montserrat-bold' href='/tiendApp/pages/registro.php'>Registrarse</a></li>
            ";
            } else {
              $nombre_user = htmlspecialchars($_SESSION['nombre']);
              print "
            <li class='nav-item dropdown'>
              <a class='nav-link dropdown-toggle montserrat-bold' href='' role='button' data-bs-toggle='dropdown' aria-expanded='false'>
                👋 Hola, $nombre_user
              </a>
              <ul class='dropdown-menu dropdown-menu-end'>
                <li><a class='dropdown-item montserrat-bold' href='/tiendApp/pages/perfil.php'>Ver perfil</a></li>
                <li><hr class='dropdown-divider'></li>
                <li><a class='dropdown-item montserrat-bold' href='/tiendApp/editor/publicar.php'>Agregar producto</a></li>
                <li><a class='dropdown-item montserrat-bold' href='/tiendApp/editor/editar.php'>Mis productos</a></li>
                <li><hr class='dropdown-divider'></li>
                <li><a class='dropdown-item montserrat-bold' href='/tiendApp/pages/compras.php'>Mis compras</a></li>
                <li><hr class='dropdown-divider'></li>
                <li><a class='dropdown-item text-danger montserrat-bold' href='/tiendApp/log/cerrar.php'>Cerrar sesión</a></li>
              </ul>
            </li>
            ";
            }
            ?>
          </ul>
          <form class="d-flex" role="search" method="GET" action="/tiendApp/pages/busqueda.php">
            <input
              class="form-control me-2"
              type="search"
              placeholder="Buscar productos..."
              aria-label="Search"
              name="buscar" />
            <button class="btn montserrat-bold" type="submit">Buscar</button>
          </form>
        </div>
      </div>
    </nav>
  </header>