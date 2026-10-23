<?php
session_start();
include_once("../../components/config/config.php");

if (!isset($_SESSION['id_usuario'])) {
    print "<div class='alert alert-warning'>Debes iniciar sesión para continuar.</div>";
    exit;
}

if (!isset($_GET['id'])) {
    print "<div class='alert alert-danger'>Transacción no especificada.</div>";
    exit;
}

$id_transaccion = intval($_GET['id']);
$id_usuario = intval($_SESSION['id_usuario']);

$check_trans = "
    SELECT * 
    FROM transacciones 
    WHERE id_transaccion = $id_transaccion
    LIMIT 1
";
$res_trans = mysqli_query($con, $check_trans);

if (!$res_trans || mysqli_num_rows($res_trans) == 0) {
    print "<div class='alert alert-danger'>Transacción no encontrada.</div>";
    exit;
}

$trans = mysqli_fetch_assoc($res_trans);

$mpu_id = intval($trans['fk_metodo_pago_usuario']);
$res_mpu = mysqli_query($con, "SELECT * FROM metodos_pagos_usuarios WHERE id_metodo_pago_usuario = $mpu_id AND fk_usuario = $id_usuario");

if (!$res_mpu || mysqli_num_rows($res_mpu) == 0) {
    print "<div class='alert alert-danger'>No tenés permiso para modificar esta transacción.</div>";
    exit;
}

$res_envio = mysqli_query($con, "SELECT * FROM envios WHERE fk_transaccion = $id_transaccion LIMIT 1");

if (!$res_envio || mysqli_num_rows($res_envio) == 0) {
    print "<div class='alert alert-danger'>No se encontró un envío asociado a esta transacción.</div>";
    exit;
}

$envio = mysqli_fetch_assoc($res_envio);

$nuevo_estado = 0;
if ($envio['fk_estado_envio'] == 1) {
    $nuevo_estado = 3;
} elseif ($envio['fk_estado_envio'] == 2) {
    $nuevo_estado = 4;
} else {
    print "<div class='alert alert-info'>El envío ya fue recibido.</div>";
    exit;
}

$update_envio = "
    UPDATE envios
    SET fk_estado_envio = $nuevo_estado,
        fecha_entrega = NOW()
    WHERE id_envio = {$envio['id_envio']}
";

if (!mysqli_query($con, $update_envio)) {
    print "<div class='alert alert-danger'>Error al actualizar el envío: " . mysqli_error($con) . "</div>";
    exit;
}

header("Location: ../compras.php?recibido=ok");
exit;
?>