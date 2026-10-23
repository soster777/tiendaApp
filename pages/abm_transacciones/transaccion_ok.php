<?php
include_once("../../components/config/config.php");
include_once("../../components/permisos.php");

$id_usuario = requiere_login("../login.php");

if (!isset($_POST['id_producto'], $_POST['cantidad'], $_POST['metodo_pago'], $_POST['cuotas'])) {
    header("Location: ../inicio.php");
    exit;
}

$id_producto = intval($_POST['id_producto']);
$cantidad = intval($_POST['cantidad']);
$mpu_id = intval($_POST['metodo_pago']);
$cuotas = intval($_POST['cuotas']);

// la cantidad tiene que ser al menos 1 (si mandan -5 desde el navegador no pasa)
if ($cantidad < 1) {
    header("Location: ../transaccion.php?id_producto=$id_producto&error=cantidad");
    exit;
}

$cuotas_permitidas = [1, 2, 3];
if (!in_array($cuotas, $cuotas_permitidas)) {
    header("Location: ../transaccion.php?id_producto=$id_producto&error=cuotas");
    exit;
}

// el metodo de pago tiene que ser del usuario logueado
$stmt = mysqli_prepare($con, "SELECT id_metodo_pago_usuario FROM metodos_pagos_usuarios WHERE id_metodo_pago_usuario = ? AND fk_usuario = ?");
mysqli_stmt_bind_param($stmt, "ii", $mpu_id, $id_usuario);
mysqli_stmt_execute($stmt);
if (mysqli_num_rows(mysqli_stmt_get_result($stmt)) === 0) {
    header("Location: ../transaccion.php?id_producto=$id_producto&error=metodo");
    exit;
}

$stmt = mysqli_prepare($con, "SELECT stock, precio, fk_usuario FROM productos WHERE id_producto = ?");
mysqli_stmt_bind_param($stmt, "i", $id_producto);
mysqli_stmt_execute($stmt);
$producto = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
if (!$producto) {
    header("Location: ../inicio.php");
    exit;
}

// no se puede comprar un producto propio (antes solo lo frenaba que no aparecia el boton)
if (intval($producto['fk_usuario']) === $id_usuario) {
    header("Location: ../transaccion.php?id_producto=$id_producto&error=propio");
    exit;
}

if ($cantidad > $producto['stock']) {
    header("Location: ../transaccion.php?id_producto=$id_producto&error=stock");
    exit;
}

$total = $cantidad * $producto['precio'];

// si el usuario ya tenia una compra pendiente de este producto la reutilizo
// (antes el OR fk_metodo_pago_usuario = 0 podia agarrar la compra pendiente de otro usuario)
$stmt = mysqli_prepare($con, "
    SELECT t.id_transaccion
    FROM transacciones t
    JOIN metodos_pagos_usuarios mpu ON t.fk_metodo_pago_usuario = mpu.id_metodo_pago_usuario
    WHERE t.fk_producto = ? AND t.fk_estado_transaccion = 1 AND mpu.fk_usuario = ?
    ORDER BY t.fecha ASC
    LIMIT 1
");
mysqli_stmt_bind_param($stmt, "ii", $id_producto, $id_usuario);
mysqli_stmt_execute($stmt);
$trans_pendiente = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if ($trans_pendiente) {

    $id_transaccion = intval($trans_pendiente['id_transaccion']);

    $stmt = mysqli_prepare($con, "
        UPDATE transacciones
        SET cantidad = ?, total = ?, cuotas = ?, fk_metodo_pago_usuario = ?, fecha = NOW()
        WHERE id_transaccion = ?
    ");
    mysqli_stmt_bind_param($stmt, "idiii", $cantidad, $total, $cuotas, $mpu_id, $id_transaccion);
    mysqli_stmt_execute($stmt);

} else {

    $stmt = mysqli_prepare($con, "
        INSERT INTO transacciones
        (cantidad, total, fecha, cuotas, fk_producto, fk_estado_transaccion, fk_metodo_pago_usuario)
        VALUES (?, ?, NOW(), ?, ?, 1, ?)
    ");
    mysqli_stmt_bind_param($stmt, "idiii", $cantidad, $total, $cuotas, $id_producto, $mpu_id);
    mysqli_stmt_execute($stmt);
    $id_transaccion = mysqli_insert_id($con);
}

header("Location: ../envio.php?id_transaccion=" . $id_transaccion);
exit;
?>
