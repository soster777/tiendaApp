<?php
include_once("../components/header_admin.php");
include_once("../components/config/config.php");

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

$res_usuario = mysqli_query($con, "SELECT * FROM usuarios WHERE id_usuario='$id_usuario'");
if (!$usuario = mysqli_fetch_assoc($res_usuario)) {
    print "<div class='alert alert-danger'>Usuario no encontrado.</div>";
    include_once("../components/footer.php");
    exit;
}

$modificar = isset($_GET['modificar']) ? $_GET['modificar'] : '';
?>

<main>
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <h1 class="montserrat-bold">Mi Perfil</h1>

                <?php if (isset($_GET['ok'])): ?>
                    <div class="alert alert-success mt-2">Datos actualizados correctamente.</div>

                <?php elseif (isset($_GET['error'])): ?>
                    <?php if ($_GET['error'] === '1'): ?>
                        <div class="alert alert-warning">Debes completar todos los campos.</div>
                    <?php elseif ($_GET['error'] === 'clave_actual'): ?>
                        <div class="alert alert-danger">La contraseña actual es incorrecta.</div>
                    <?php elseif ($_GET['error'] === 'clave_no_coincide'): ?>
                        <div class="alert alert-warning">Las contraseñas nuevas no coinciden.</div>
                    <?php else: ?>
                        <div class="alert alert-danger">Ocurrió un error al actualizar los datos.</div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-9 col-lg-7">
                <section id="datos-perfil">
                    <div class="row">
                        <div class="col-md-8">
                            <p class="montserrat-regular">
                                <strong>Nombre:</strong> <?= htmlspecialchars($usuario['nombre']); ?>
                            </p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <a href="perfil.php?modificar=nombre" class="btn btn-sm">Modificar</a>
                        </div>
                    </div>
                    <?php if ($modificar === 'nombre'): ?>
                        <form action="abm_perfil/perfil_actualizar.php" method="POST" class="mt-2">
                            <input type="hidden" name="campo" value="nombre">
                            <input type="text" name="valor" value="<?= htmlspecialchars($usuario['nombre']) ?>" class="form-control mb-2" required>
                            <button class="btn btn-success">Guardar</button>
                            <a href="perfil.php" class="btn btn-secondary ms-2">Cancelar</a>
                        </form>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-md-8">
                            <p class="montserrat-regular">
                                <strong>Correo:</strong> <?= htmlspecialchars($usuario['mail']); ?>
                            </p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <a href="perfil.php?modificar=mail" class="btn btn-sm ">Modificar</a>
                        </div>
                    </div>
                    <?php if ($modificar === 'mail'): ?>
                        <form action="abm_perfil/perfil_actualizar.php" method="POST" class="mt-2">
                            <input type="hidden" name="campo" value="mail">
                            <input type="email" name="valor" value="<?= htmlspecialchars($usuario['mail']) ?>" class="form-control mb-2" required>
                            <button class="btn btn-success">Guardar</button>
                            <a href="perfil.php" class="btn btn-secondary ms-2">Cancelar</a>
                        </form>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-md-8">
                            <p class="montserrat-regular"><strong>Contraseña:</strong> ********</p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <a href="perfil.php?modificar=contrasena" class="btn btn-sm ">Modificar</a>
                        </div>
                    </div>
                    <?php if ($modificar === 'contrasena'): ?>
                        <form action="abm_perfil/perfil_actualizar.php" method="POST" class="mt-2">
                            <input type="hidden" name="campo" value="contrasena">
                            <input type="password" name="actual" placeholder="Contraseña actual" class="form-control mb-2" required>
                            <input type="password" name="nuevo" placeholder="Nueva contraseña" class="form-control mb-2" required>
                            <input type="password" name="nuevo_rep" placeholder="Repetir nueva contraseña" class="form-control mb-2" required>
                            <button class="btn btn-success">Guardar</button>
                            <a href="perfil.php" class="btn btn-secondary ms-2">Cancelar</a>
                        </form>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-md-8">
                            <p class="montserrat-regular">
                                <strong>Ubicación:</strong> <?= htmlspecialchars($usuario['ubicacion']); ?>
                            </p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <a href="perfil.php?modificar=ubicacion" class="btn btn-sm ">Modificar</a>
                        </div>
                    </div>
                    <?php if ($modificar === 'ubicacion'): ?>
                        <form action="abm_perfil/perfil_actualizar.php" method="POST" class="mt-2">
                            <input type="hidden" name="campo" value="ubicacion">
                            <input type="text" name="valor" value="<?= htmlspecialchars($usuario['ubicacion']) ?>" class="form-control mb-2">
                            <button class="btn btn-success">Guardar</button>
                            <a href="perfil.php" class="btn btn-secondary ms-2">Cancelar</a>
                        </form>
                    <?php endif; ?>

                </section>
            </div>
            <div class="col-12 col-md-3 col-lg-5">
                <figure>
                    <img src="../imgs/perfil_admin.png" alt="perfil" class="img-fluid mx-auto d-block">
                </figure>
            </div>
        </div>
    </div>
</main>

<?php include_once("../components/footer.php"); ?>