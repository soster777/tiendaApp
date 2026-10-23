<?php
include_once("../components/config/config.php");
include_once("../components/permisos.php");

// si no hay sesion va al login (antes mostraba "Undefined array key")
$id_usuario = requiere_login("login.php");

include_once("../components/header.php");

$res_usuario = mysqli_query($con, "SELECT * FROM usuarios WHERE id_usuario = $id_usuario");
if (!$usuario = mysqli_fetch_assoc($res_usuario)) {
    print "<div class='alert alert-danger'>Usuario no encontrado.</div>";
    include_once("../components/footer.php");
    exit;
}

$modificar = isset($_GET['modificar']) ? $_GET['modificar'] : '';
?>

<main>
    <div class="container py-4"> <div class="row mb-4"> <div class="col-12">
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
                    <?php elseif ($_GET['error'] === 'mail'): ?>
                        <div class="alert alert-warning">El mail no tiene un formato válido.</div>
                    <?php elseif ($_GET['error'] === 'metodo_datos'): ?>
                        <div class="alert alert-warning">Revisá los datos del método de pago (número de tarjeta, vencimiento o CBU).</div>
                    <?php elseif ($_GET['error'] === 'metodo_en_uso'): ?>
                        <div class="alert alert-warning">No podés eliminar ese método porque tiene una compra o un envío pendiente.</div>
                    <?php else: ?>
                        <div class="alert alert-danger">Ocurrió un error al actualizar los datos.</div>
                    <?php endif; ?>

                <?php elseif (isset($_GET['mail_exist'])): ?>
                    <div class="alert alert-warning">El correo ingresado ya está en uso por otro usuario.</div>

                <?php endif; ?>

                <?php if (isset($_GET['metodo_ok'])): ?>
                    <div class="alert alert-success mt-2">Método de pago agregado correctamente.</div>
                <?php endif; ?>

                <?php if (isset($_GET['metodo_repetido'])): ?>
                    <div class="alert alert-danger mt-2">Ya tienes un método de este tipo registrado.</div>
                <?php endif; ?>

                <?php if (isset($_GET['metodo_modificado'])): ?>
                    <div class="alert alert-success mt-2">Método de pago actualizado correctamente.</div>
                <?php endif; ?>

                <?php if (isset($_GET['baja']) && $_GET['baja'] === 'ok'): ?>
                    <div class="alert alert-success mt-2">Método de pago eliminado correctamente.</div>
                <?php endif; ?>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-lg-7 mb-4"> <section id="datos-perfil" class="p-4 border rounded shadow-sm"> <div class="row mb-3"> <div class="col-md-8">
                            <p class="montserrat-regular">
                                <strong>Nombre:</strong> <?= htmlspecialchars($usuario['nombre']); ?>
                            </p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <a href="perfil.php?modificar=nombre" class="btn btn-sm">Modificar</a>
                        </div>
                    </div>

                    <?php if ($modificar === 'nombre'): ?>
                        <form action="abm_perfil/perfil_actualizar.php" method="POST" class="mt-2 p-3 bg-light rounded">
                            <input type="hidden" name="campo" value="nombre">
                            <input type="text" name="valor" value="<?= htmlspecialchars($usuario['nombre']) ?>" class="form-control mb-2" required>
                            <button class="btn btn-success">Guardar</button>
                            <a href="perfil.php" class="btn btn-secondary ms-2">Cancelar</a>
                        </form>
                    <?php endif; ?>

                    <div class="row mb-3">
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
                        <form action="abm_perfil/perfil_actualizar.php" method="POST" class="mt-2 p-3 bg-light rounded">
                            <input type="hidden" name="campo" value="mail">
                            <input type="email" name="valor" value="<?= htmlspecialchars($usuario['mail']) ?>" class="form-control mb-2" required>
                            <button class="btn btn-success">Guardar</button>
                            <a href="perfil.php" class="btn btn-secondary ms-2">Cancelar</a>
                        </form>
                    <?php endif; ?>

                    <div class="row mb-3">
                        <div class="col-md-8">
                            <p class="montserrat-regular"><strong>Contraseña:</strong> ********</p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <a href="perfil.php?modificar=contrasena" class="btn btn-sm ">Modificar</a>
                        </div>
                    </div>

                    <?php if ($modificar === 'contrasena'): ?>
                        <form action="abm_perfil/perfil_actualizar.php" method="POST" class="mt-2 p-3 bg-light rounded">
                            <input type="hidden" name="campo" value="contrasena">
                            <input type="password" name="actual" placeholder="Contraseña actual" class="form-control mb-2" required>
                            <input type="password" name="nuevo" placeholder="Nueva contraseña" class="form-control mb-2" required>
                            <input type="password" name="nuevo_rep" placeholder="Repetir nueva contraseña" class="form-control mb-2" required>
                            <button class="btn btn-success">Guardar</button>
                            <a href="perfil.php" class="btn btn-secondary ms-2">Cancelar</a>
                        </form>
                    <?php endif; ?>

                    <div class="row mb-3">
                        <div class="col-md-8">
                            <p class="montserrat-regular">
                                <strong>Ubicación:</strong> <?= htmlspecialchars($usuario['ubicacion'] ?? ''); ?>
                            </p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <a href="perfil.php?modificar=ubicacion" class="btn btn-sm ">Modificar</a>
                        </div>
                    </div>

                    <?php if ($modificar === 'ubicacion'): ?>
                        <form action="abm_perfil/perfil_actualizar.php" method="POST" class="mt-2 p-3 bg-light rounded">
                            <input type="hidden" name="campo" value="ubicacion">
                            <input type="text" name="valor" value="<?= htmlspecialchars($usuario['ubicacion'] ?? '') ?>" class="form-control mb-2" required>
                            <button class="btn btn-success">Guardar</button>
                            <a href="perfil.php" class="btn btn-secondary ms-2">Cancelar</a>
                        </form>
                    <?php endif; ?>
                </section>
            </div>

            <div class="col-12 col-lg-5">
                <section id="metodos-pago" class="p-4 border rounded shadow-sm"> <h2 class="montserrat-bold mb-4">Mis métodos de pago</h2>

                    <?php
                    $sql_guardados = "SELECT * FROM metodos_pagos_usuarios WHERE fk_usuario = '$id_usuario'";
                    $res_guardados = mysqli_query($con, $sql_guardados);

                    $sql_tipos = "SELECT * FROM metodos_pagos";
                    $res_tipos = mysqli_query($con, $sql_tipos);

                    $total_metodos = mysqli_num_rows($res_guardados);
                    ?>

                    <?php if ($total_metodos === 0): ?>
                        <div class="alert alert-info">Todavía no agregaste ningún método de pago.</div>
                    <?php endif; ?>

                    <?php while ($mp = mysqli_fetch_assoc($res_guardados)): ?>
                        <?php
                        mysqli_data_seek($res_tipos, 0);
                        $nombre_metodo = "Método desconocido";

                        while ($tipo = mysqli_fetch_assoc($res_tipos)) {
                            if ($tipo['id_metodo_pago'] == $mp['fk_metodo_pago']) {
                                $nombre_metodo = $tipo['metodo_pago'];
                                break;
                            }
                        }

                        $es_efectivo = ($nombre_metodo === 'Efectivo');
                        ?>

                        <div class="card shadow-sm mb-4"> <div class="card-body p-4"> <h5 class="card-title"><?= htmlspecialchars($nombre_metodo); ?></h5>

                                <p class="mb-1"><?= htmlspecialchars($mp['nombre']); ?></p>

                                <?php if ($mp['numero_tarjeta']): ?>
                                    <!-- solo se guardan los ultimos 4 numeros -->
                                    <p class="mb-1"><strong>Tarjeta:</strong> **** **** **** <?= htmlspecialchars(substr($mp['numero_tarjeta'], -4)); ?></p>
                                    <p class="mb-3"><strong>Vencimiento:</strong> <?= date("m/Y", strtotime($mp['vencimiento'])); ?></p>
                                <?php endif; ?>

                                <?php if ($mp['cbu']): ?>
                                    <p class="mb-3"><strong>CBU:</strong> <?= htmlspecialchars($mp['cbu']); ?></p>
                                <?php endif; ?>

                                <?php if (!$es_efectivo): ?>
                                    <a href="abm_met_pago/mod_metodo_pago.php?id=<?= $mp['id_metodo_pago_usuario']; ?>" class="btn btn-sm">Modificar</a>
                                <?php endif; ?>

                                <?php
                                $id_mpu = intval($mp['id_metodo_pago_usuario']);

                                $sql_pendiente = "
                                    SELECT id_transaccion 
                                    FROM transacciones 
                                    WHERE fk_metodo_pago_usuario = $id_mpu
                                    AND fk_estado_transaccion = 1
                                    LIMIT 1
                                ";
                                $res_pend = mysqli_query($con, $sql_pendiente);
                                $tiene_pendiente = ($res_pend && mysqli_num_rows($res_pend) > 0);

                                $sql_envios = "
                                    SELECT e.id_envio 
                                    FROM envios e
                                    INNER JOIN transacciones t ON t.id_transaccion = e.fk_transaccion
                                    WHERE t.fk_metodo_pago_usuario = $id_mpu
                                    AND e.fk_estado_envio IN (1,2)
                                    LIMIT 1
                                ";
                                $res_env = mysqli_query($con, $sql_envios);
                                $tiene_envio_pendiente = ($res_env && mysqli_num_rows($res_env) > 0);
                                ?>

                                <?php if ($tiene_pendiente): ?>

                                    <div class="alert alert-warning mt-3 p-3"> No podés eliminar este método porque tiene una compra pendiente.
                                    </div>

                                <?php elseif ($tiene_envio_pendiente): ?>

                                    <div class="alert alert-warning mt-3 p-3">
                                        No podés eliminar este método porque tiene un envío pendiente.
                                    </div>

                                <?php else: ?>

                                    <form action="abm_met_pago/baja_metodo_pago.php" method="post" class="d-inline"
                                        onsubmit="return confirm('¿Estás seguro de que querés eliminar este método?');">
                                        <input type="hidden" name="id" value="<?= intval($mp['id_metodo_pago_usuario']); ?>">
                                        <button type="submit" class="btn btn-sm btn-danger ms-2">Eliminar</button>
                                    </form>

                                <?php endif; ?>
                            </div>
                        </div>

                    <?php endwhile; ?>

                    <?php if ($total_metodos < 5): ?>
                        <div class="mt-4">
                            <a href="metodo_pago.php" class="btn w-100">Agregar método de pago</a>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info mt-4 text-center p-3">
                            Ya tenés los 5 métodos de pago permitidos. No podés agregar más.
                        </div>
                    <?php endif; ?>

                </section>
            </div>

        </div>
    </div>
</main>

<?php include_once("../components/footer.php"); ?>