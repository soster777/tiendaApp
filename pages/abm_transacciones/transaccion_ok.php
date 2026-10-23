<?php
session_start();
include_once("../../components/config/config.php");

if (!isset($_SESSION['id_usuario'])) {
    print "<div class='alert alert-warning'>Debes iniciar sesión para comprar.</div>";
    exit;
}

if (!isset($_POST['id_producto'], $_POST['cantidad'], $_POST['metodo_pago'], $_POST['cuotas'])) {
    print "<div class='alert alert-warning'>Faltan datos obligatorios.</div>";
    exit;
}

$id_producto = intval($_POST['id_producto']);
$cantidad = intval($_POST['cantidad']);
$mpu_id = intval($_POST['metodo_pago']);
$cuotas = intval($_POST['cuotas']);
$id_usuario = intval($_SESSION['id_usuario']);

$cuotas_permitidas = [1, 2, 3];
if (!in_array($cuotas, $cuotas_permitidas)) {
    print "<div class='alert alert-danger'>Cantidad de cuotas inválida.</div>";
    exit;
}

$check_mpu = "
    SELECT * 
    FROM metodos_pagos_usuarios 
    WHERE id_metodo_pago_usuario = $mpu_id 
      AND fk_usuario = $id_usuario
";
$res_mpu = mysqli_query($con, $check_mpu);
if (!$res_mpu || mysqli_num_rows($res_mpu) === 0) {
    print "<div class='alert alert-danger'>Método de pago inválido.</div>";
    exit;
}

$res_prod = mysqli_query($con, "SELECT stock, precio FROM productos WHERE id_producto = $id_producto");
$producto = mysqli_fetch_assoc($res_prod);
if (!$producto) {
    print "<div class='alert alert-danger'>Producto no encontrado.</div>";
    exit;
}

if ($cantidad > $producto['stock']) {
    print "<div class='alert alert-danger'>No hay suficiente stock disponible. Stock actual: {$producto['stock']}</div>";
    exit;
}

$total = $cantidad * $producto['precio'];

$check_trans = "
    SELECT t.* 
    FROM transacciones t
    LEFT JOIN metodos_pagos_usuarios mpu ON t.fk_metodo_pago_usuario = mpu.id_metodo_pago_usuario
    WHERE t.fk_producto = $id_producto 
      AND t.fk_estado_transaccion = 1
      AND (mpu.fk_usuario = $id_usuario OR t.fk_metodo_pago_usuario = 0)
    ORDER BY t.fecha ASC
    LIMIT 1
";
$res_trans = mysqli_query($con, $check_trans);
$trans_pendiente = $res_trans && mysqli_num_rows($res_trans) > 0 ? mysqli_fetch_assoc($res_trans) : null;

if ($trans_pendiente) {

    $id_transaccion = $trans_pendiente['id_transaccion'];

    $update_trans = "
        UPDATE transacciones
        SET cantidad = $cantidad,
            total = $total,
            cuotas = $cuotas,
            fk_metodo_pago_usuario = $mpu_id,
            fecha = NOW()
        WHERE id_transaccion = $id_transaccion
    ";
    mysqli_query($con, $update_trans);

} else {

    $insert_transaccion = "
        INSERT INTO transacciones 
        (cantidad, total, fecha, cuotas, fk_producto, fk_estado_transaccion, fk_metodo_pago_usuario) 
        VALUES ($cantidad, $total, NOW(), $cuotas, $id_producto, 1, $mpu_id)
    ";
    mysqli_query($con, $insert_transaccion);
    $id_transaccion = mysqli_insert_id($con);
}

header("Location: ../envio.php?id_transaccion=" . $id_transaccion);
exit;
?>
