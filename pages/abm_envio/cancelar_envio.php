<?php
include_once("../../components/config/config.php");

if (!isset($_GET['id'])) {
    print "<div class='alert alert-warning'>ID de transacción no especificado.</div>";
    exit;
}

$id_trans = intval($_GET['id']);

$res_trans = mysqli_query($con, "SELECT fk_producto, cantidad FROM transacciones WHERE id_transaccion = $id_trans");
if (!$res_trans || mysqli_num_rows($res_trans) == 0) {
    print "<div class='alert alert-danger'>Transacción no encontrada.</div>";
    exit;
}

$trans = mysqli_fetch_assoc($res_trans);
$id_producto = intval($trans['fk_producto']);
$cantidad = intval($trans['cantidad']);

mysqli_query($con, "UPDATE productos SET stock = stock + $cantidad WHERE id_producto = $id_producto");

mysqli_query($con, "UPDATE transacciones SET fk_estado_transaccion = 3 WHERE id_transaccion = $id_trans");

mysqli_query($con, "UPDATE envios SET fk_estado_envio = 5 WHERE fk_transaccion = $id_trans");

header("Location: ../compras.php?cancel=ok");
exit;
?>