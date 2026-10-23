<?php
include_once("../components/header.php");
include_once("../components/config/config.php");

if (!isset($_SESSION['id_usuario'])) {
    print '<div class="container-fluid"><div class="row justify-content-center mt-4"><div class="col-12 col-md-8 col-lg-6">
        <div class="alert alert-warning text-center">Debes iniciar sesión para agregar métodos de pago.</div>
    </div></div></div>';
    include_once("../components/footer.php");
    exit;
}

$id_usuario = $_SESSION['id_usuario'];
$fk_metodo = intval($_POST['fk_metodo_pago'] ?? 0);
?>

<br><main>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">

                <?php if (!$fk_metodo): ?>
                    <h1 class="montserrat-bold mb-4 text-center">Agregar método de pago</h1>

                    <form action="" method="post" class="formulario-usuario">
                        <div class="row mb-3">
                            <div class="col-12">
                                <label class="form-label primero">Seleccioná un método:</label>
                                <select name="fk_metodo_pago" class="form-select" required>
                                    <option value="" disabled selected>Elegí un método</option>
                                    <?php
                                    $sql = "SELECT * FROM metodos_pagos ORDER BY id_metodo_pago";
                                    $res = mysqli_query($con, $sql);
                                    while ($row = mysqli_fetch_assoc($res)):
                                    ?>
                                        <option value="<?= $row['id_metodo_pago'] ?>"><?= htmlspecialchars($row['metodo_pago']) ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 text-center">
                                <button type="submit" class="btn w-100 py-2">Continuar</button>
                            </div>
                        </div>
                    </form>

                <?php else: ?>
                    <?php
                    $sql_check = "
                        SELECT id_metodo_pago_usuario FROM metodos_pagos_usuarios
                        WHERE fk_usuario = '$id_usuario'
                        AND fk_metodo_pago = '$fk_metodo'
                        LIMIT 1
                    ";
                    $res_check = mysqli_query($con, $sql_check);

                    if (mysqli_num_rows($res_check) > 0):
                    ?>
                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-warning" style="margin-top: 60px;">
                                    Ya tenés agregado este método de pago.
                                </div>
                                <div class="text-center mt-3">
                                    <a href="perfil.php" class="btn btn-secondary">Volver al perfil</a>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>

                        <h1 class="montserrat-bold mb-4 text-center">Completar datos del método de pago</h1>

                        <form action="abm_met_pago/alta_metodo_pago.php" method="post" class="formulario-usuario">
                            <?php if ($fk_metodo == 3): ?>
                                <input type="hidden" name="nombre" value="Efectivo">
                                <p class="montserrat-regular">No se requieren datos adicionales</p>
                            <?php else: ?>
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <label class="form-label primero">Nombre del método</label>
                                        <input type="text" name="nombre" class="form-control" required>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if ($fk_metodo == 1 || $fk_metodo == 2): ?>
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <label class="form-label">Número de tarjeta</label>
                                        <input type="text" name="numero_tarjeta" class="form-control" required max="9999999999">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <?php $minimo_fecha = date('Y-m'); ?>
                                        <label class="form-label">Vencimiento</label>
                                        <input type="month" name="vencimiento" class="form-control" required min="<?= $minimo_fecha ?>">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Código de seguridad</label>
                                        <input type="number" name="codigo_tarjeta" class="form-control" required max="9999">
                                    </div>
                                </div>
                            <?php elseif ($fk_metodo == 4 || $fk_metodo == 5): ?>
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <label class="form-label">CBU</label>
                                        <input type="number" name="cbu" class="form-control" required max="9999999999">
                                    </div>
                                </div>
                            <?php endif; ?>

                            <input type="hidden" name="fk_metodo_pago" value="<?= $fk_metodo ?>">

                            <div class="row">
                                <div class="col-12 text-center">
                                    <button type="submit" class="btn btn-success w-100 py-2">Guardar método</button>
                                </div>
                            </div>
                        </form>

                    <?php endif; ?>
                <?php endif; ?>

            </div>
        </div>
    </div>
</main>

<?php include_once("../components/footer.php"); ?>