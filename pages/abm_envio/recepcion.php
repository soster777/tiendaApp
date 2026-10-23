<?php
include_once("../../components/config/config.php");
include_once("../../components/permisos.php");

$id_usuario = requiere_login("../login.php");

// por POST, desde el boton de Mis compras
if (!isset($_POST['id'])) {
    header("Location: ../compras.php");
    exit;
}

$id_transaccion = intval($_POST['id']);

if (!transaccion_del_usuario($con, $id_transaccion, $id_usuario)) {
    header("Location: ../compras.php");
    exit;
}

$stmt = mysqli_prepare($con, "SELECT * FROM envios WHERE fk_transaccion = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, "i", $id_transaccion);
mysqli_stmt_execute($stmt);
$envio = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$envio) {
    header("Location: ../compras.php");
    exit;
}

// A retirar (1) pasa a Retirado (3) y En camino (2) pasa a Entregado (4)
if ($envio['fk_estado_envio'] == 1) {
    $nuevo_estado = 3;
} elseif ($envio['fk_estado_envio'] == 2) {
    $nuevo_estado = 4;
} else {
    header("Location: ../compras.php");
    exit;
}

$stmt = mysqli_prepare($con, "UPDATE envios SET fk_estado_envio = ?, fecha_entrega = NOW() WHERE id_envio = ?");
mysqli_stmt_bind_param($stmt, "ii", $nuevo_estado, $envio['id_envio']);
mysqli_stmt_execute($stmt);

header("Location: ../compras.php?recibido=ok");
exit;
?>
