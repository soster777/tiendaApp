<?php
include_once("../../components/config/config.php");
include_once("../../components/permisos.php");

// los chequeos van antes del header para poder redirigir
$id_usuario = requiere_login("../login.php");
$metodo_id = intval($_GET['id'] ?? 0);

$stmt = mysqli_prepare($con, "SELECT * FROM metodos_pagos_usuarios WHERE id_metodo_pago_usuario = ? AND fk_usuario = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, "ii", $metodo_id, $id_usuario);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
if (mysqli_num_rows($res) === 0) {
    header("Location: ../perfil.php");
    exit;
}

include_once("../../components/header.php");
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
                                <!-- solo guardamos los ultimos 4: si lo dejan vacio se mantiene la tarjeta actual -->
                                <input type="text" name="numero_tarjeta" class="form-control"
                                    placeholder="Tarjeta terminada en <?= htmlspecialchars($metodo['numero_tarjeta']) ?> (dejar vacío para no cambiarla)"
                                    pattern="[0-9]{13,19}" inputmode="numeric">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12">
                                <label class="form-label">Vencimiento</label>
                                <input type="month" name="vencimiento" class="form-control"
                                    value="<?= substr($metodo['vencimiento'], 0, 7) ?>" required>
                            </div>
                        </div>

                    <?php elseif ($metodo['fk_metodo_pago'] == 4 || $metodo['fk_metodo_pago'] == 5): ?>
                        <div class="row mb-3">
                            <label class="form-label col-12">CBU</label>
                            <div class="col-12">
                                <input type="text" name="cbu" class="form-control"
                                    value="<?= htmlspecialchars($metodo['cbu']) ?>" required pattern="[0-9]{22}" inputmode="numeric">
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