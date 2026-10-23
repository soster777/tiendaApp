<?php
include_once("../../components/header.php");
include_once("../../components/config/config.php");

$id_usuario = $_SESSION['id_usuario'] ?? 0;
$metodo_id = intval($_GET['id'] ?? 0);

if (!$id_usuario) {
    header("Location: ../login.php");
    exit;
}

if (!$metodo_id) {
    print "<div class='container-fluid mt-4'>
            <div class='alert alert-warning'>ID de método inválido.</div>
            <a href='../perfil.php' class='btn btn-secondary mt-3'>Volver al perfil</a>
          </div>";
    exit;
}

$sql = "SELECT * FROM metodos_pagos_usuarios 
        WHERE id_metodo_pago_usuario='$metodo_id' AND fk_usuario='$id_usuario' LIMIT 1";
$res = mysqli_query($con, $sql);
if (!$res || mysqli_num_rows($res) === 0) {
    print "<div class='container-fluid mt-4'>
            <div class='alert alert-warning'>No se encontró este método de pago.</div>
            <a href='../perfil.php' class='btn btn-secondary mt-3'>Volver al perfil</a>
          </div>";
    exit;
}

$metodo = mysqli_fetch_assoc($res);
?>


<main>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">

                <h1 class="montserrat-bold">Modificar método de pago</h1>

                <form action="mod_metodo_pago_ok.php" method="post" class="formulario-usuario">
                    <input type="hidden" name="id_metodo" value="<?= $metodo['id_metodo_pago_usuario'] ?>">

                    <div class="row mb-3">
                        <label class="form-label col-12 primero">Nombre del método</label>
                        <div class="col-12">
                            <input type="text" name="nombre" class="form-control"
                                value="<?= htmlspecialchars($metodo['nombre']) ?>" required>
                        </div>
                    </div>

                    <?php if ($metodo['fk_metodo_pago'] == 1 || $metodo['fk_metodo_pago'] == 2): ?>
                        <div class="row mb-3">
                            <label class="form-label col-12">Número de tarjeta</label>
                            <div class="col-12">
                                <input type="text" name="numero_tarjeta" class="form-control"
                                    value="<?= htmlspecialchars($metodo['numero_tarjeta']) ?>" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Vencimiento</label>
                                <input type="month" name="vencimiento" class="form-control"
                                    value="<?= substr($metodo['vencimiento'], 0, 7) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Código de seguridad</label>
                                <input type="text" name="codigo_tarjeta" class="form-control"
                                    value="<?= htmlspecialchars($metodo['codigo_tarjeta']) ?>" required>
                            </div>
                        </div>

                    <?php elseif ($metodo['fk_metodo_pago'] == 4 || $metodo['fk_metodo_pago'] == 5): ?>
                        <div class="row mb-3">
                            <label class="form-label col-12">CBU</label>
                            <div class="col-12">
                                <input type="text" name="cbu" class="form-control"
                                    value="<?= htmlspecialchars($metodo['cbu']) ?>" required>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="row mt-4">
                        <div class="col-md-6 mb-2">
                            <button type="submit" class="btn btn-success w-100">Guardar cambios</button>
                        </div>
                        <div class="col-md-6 mb-2">
                            <a href="../perfil.php" class="btn btn-secondary w-100">Cancelar</a>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>
</main>

<?php include_once("../../components/footer.php"); ?>