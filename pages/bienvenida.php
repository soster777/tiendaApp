<?php
include_once("../components/config/config.php");
include_once("../components/permisos.php");

// pagina de bienvenida: solo pueden entrar los usuarios logueados (lo pide la consigna del final).
// el chequeo va antes del header para que la redireccion al login funcione
$id_usuario = requiere_login("login.php");

// si entra un admin lo mando a su panel, que es su pagina de bienvenida
if ($_SESSION['rol'] === 'admin') {
    header("Location: ../admin/index.php");
    exit();
}

// cuantos productos tiene publicados
$stmt = mysqli_prepare($con, "SELECT COUNT(*) AS cantidad FROM productos WHERE fk_usuario = ?");
mysqli_stmt_bind_param($stmt, "i", $id_usuario);
mysqli_stmt_execute($stmt);
$cant_productos = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))['cantidad'];

// cuantas compras hizo (la compra es de quien eligio el metodo de pago, igual que en permisos.php)
$stmt = mysqli_prepare($con, "SELECT COUNT(*) AS cantidad FROM transacciones t
    JOIN metodos_pagos_usuarios mpu ON mpu.id_metodo_pago_usuario = t.fk_metodo_pago_usuario
    WHERE mpu.fk_usuario = ?");
mysqli_stmt_bind_param($stmt, "i", $id_usuario);
mysqli_stmt_execute($stmt);
$cant_compras = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))['cantidad'];

include_once("../components/header.php");

$nombre = htmlspecialchars($_SESSION['nombre']);
?>

<main>
    <br>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="montserrat-bold">Hola, <?= $nombre ?></h1>
                <p class="montserrat-regular">Bienvenida/o a tu cuenta de TiendApp. Desde acá podés ir a todo lo que necesitás.</p>
            </div>
        </div>

        <div class="row g-3 mt-2">
            <div class="col-12 col-md-6">
                <article class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h2 class="h5 montserrat-bold">Tus productos</h2>
                        <p class="card-text">Tenés <strong><?= $cant_productos ?></strong> producto(s) publicado(s).</p>
                        <a href="../editor/publicar.php" class="btn btn-success btn-sm">Publicar producto</a>
                        <a href="../editor/editar.php" class="btn btn-outline-primary btn-sm">Ver mis productos</a>
                    </div>
                </article>
            </div>
            <div class="col-12 col-md-6">
                <article class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h2 class="h5 montserrat-bold">Tus compras</h2>
                        <p class="card-text">Hiciste <strong><?= $cant_compras ?></strong> compra(s).</p>
                        <a href="inicio.php" class="btn btn-success btn-sm">Ver productos</a>
                        <a href="compras.php" class="btn btn-outline-primary btn-sm">Ver mis compras</a>
                    </div>
                </article>
            </div>
            <div class="col-12 col-md-6">
                <article class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h2 class="h5 montserrat-bold">Tus datos</h2>
                        <p class="card-text">Cambiá tu nombre, mail, ubicación, contraseña y métodos de pago.</p>
                        <a href="perfil.php" class="btn btn-outline-primary btn-sm">Ir a mi perfil</a>
                    </div>
                </article>
            </div>
            <div class="col-12 col-md-6">
                <article class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h2 class="h5 montserrat-bold">¿Tenés una idea?</h2>
                        <p class="card-text">Mandanos una sugerencia para mejorar la tienda.</p>
                        <a href="contacto.php" class="btn btn-outline-primary btn-sm">Ir a contacto</a>
                    </div>
                </article>
            </div>
        </div>
    </div>
    <br>
</main>

<?php
include_once("../components/footer.php");
?>
