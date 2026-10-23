<?php
include_once("../../components/config/config.php");
include_once("../../components/permisos.php");

$id_usuario = requiere_login("../login.php");

// ahora llega por POST desde un formulario, asi un link en otra pagina no puede cancelar compras
if (!isset($_POST['id'])) {
    header("Location: ../inicio.php");
    exit;
}

$id_transaccion = intval($_POST['id']);
$trans = transaccion_del_usuario($con, $id_transaccion, $id_usuario);

// solo se cancela si la compra es del usuario y todavia esta pendiente
// (una compra completada ya desconto stock y tiene envio, eso se cancela desde Mis compras)
if (!$trans || intval($trans['fk_estado_transaccion']) !== 1) {
    header("Location: ../inicio.php?cancel=no");
    exit;
}

$stmt = mysqli_prepare($con, "UPDATE transacciones SET fk_estado_transaccion = 3 WHERE id_transaccion = ?");
mysqli_stmt_bind_param($stmt, "i", $id_transaccion);
mysqli_stmt_execute($stmt);

header("Location: ../inicio.php?cancel=ok");
exit;
?>
