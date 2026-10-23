<?php
include_once("../../components/config/config.php");
include_once("../../components/permisos.php");

$id_usuario = requiere_login("../login.php");

// por POST, desde el boton de Mis compras
if (!isset($_POST['id'])) {
    header("Location: ../compras.php");
    exit;
}

$id_trans = intval($_POST['id']);
$trans = transaccion_del_usuario($con, $id_trans, $id_usuario);

if (!$trans) {
    header("Location: ../compras.php?cancel=no");
    exit;
}

// solo se puede cancelar si el envio todavia no se entrego ni se cancelo
// (1 = A retirar, 2 = En camino). Asi el stock se devuelve una sola vez
$stmt = mysqli_prepare($con, "SELECT id_envio FROM envios WHERE fk_transaccion = ? AND fk_estado_envio IN (1, 2) LIMIT 1");
mysqli_stmt_bind_param($stmt, "i", $id_trans);
mysqli_stmt_execute($stmt);
$envio = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$envio) {
    header("Location: ../compras.php?cancel=no");
    exit;
}

$id_producto = intval($trans['fk_producto']);
$cantidad = intval($trans['cantidad']);

$stmt = mysqli_prepare($con, "UPDATE envios SET fk_estado_envio = 5 WHERE id_envio = ?");
mysqli_stmt_bind_param($stmt, "i", $envio['id_envio']);
mysqli_stmt_execute($stmt);

$stmt = mysqli_prepare($con, "UPDATE transacciones SET fk_estado_transaccion = 3 WHERE id_transaccion = ?");
mysqli_stmt_bind_param($stmt, "i", $id_trans);
mysqli_stmt_execute($stmt);

// devuelvo el stock y si estaba agotado vuelve a estar disponible
$stmt = mysqli_prepare($con, "UPDATE productos SET stock = stock + ?, fk_estado_producto = 1 WHERE id_producto = ?");
mysqli_stmt_bind_param($stmt, "ii", $cantidad, $id_producto);
mysqli_stmt_execute($stmt);

header("Location: ../compras.php?cancel=ok");
exit;
?>
