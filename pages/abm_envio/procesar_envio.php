<?php
include_once("../../components/config/config.php");
include_once("../../components/permisos.php");

$id_usuario = requiere_login("../login.php");

if (!isset($_POST['id_transaccion'], $_POST['metodo_envio'])) {
    header("Location: ../compras.php");
    exit;
}

$id_transaccion = intval($_POST['id_transaccion']);
$metodo_envio   = intval($_POST['metodo_envio']);

$trans = transaccion_del_usuario($con, $id_transaccion, $id_usuario);

// la compra tiene que ser del usuario y seguir pendiente
// asi si se reenvia el formulario no se descuenta el stock dos veces ni se crea otro envio
if (!$trans || intval($trans['fk_estado_transaccion']) !== 1) {
    header("Location: ../compras.php?envio=no");
    exit;
}

// envio a domicilio: el usuario tiene que tener una ubicacion cargada
if ($metodo_envio != 1) {
    $stmt = mysqli_prepare($con, "SELECT ubicacion FROM usuarios WHERE id_usuario = ?");
    mysqli_stmt_bind_param($stmt, "i", $id_usuario);
    mysqli_stmt_execute($stmt);
    $usuario = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    if (!$usuario || trim((string) $usuario['ubicacion']) === '') {
        header("Location: ../envio.php?id_transaccion=$id_transaccion&error=ubicacion");
        exit;
    }
}

$id_producto = intval($trans['fk_producto']);
$cantidad_comprada = intval($trans['cantidad']);

// descuento el stock solo si alcanza. Lo hago en una sola consulta para que si dos personas
// compran la ultima unidad al mismo tiempo, solo una lo logre (antes con max(0, ...) se vendia de mas)
$stmt = mysqli_prepare($con, "UPDATE productos SET stock = stock - ? WHERE id_producto = ? AND stock >= ?");
mysqli_stmt_bind_param($stmt, "iii", $cantidad_comprada, $id_producto, $cantidad_comprada);
mysqli_stmt_execute($stmt);

if (mysqli_affected_rows($con) == 0) {
    header("Location: ../envio.php?id_transaccion=$id_transaccion&error=stock");
    exit;
}

// si quedo en 0 lo marco como agotado
$stmt = mysqli_prepare($con, "UPDATE productos SET fk_estado_producto = 2 WHERE id_producto = ? AND stock = 0");
mysqli_stmt_bind_param($stmt, "i", $id_producto);
mysqli_stmt_execute($stmt);

$stmt = mysqli_prepare($con, "UPDATE transacciones SET fk_estado_transaccion = 2 WHERE id_transaccion = ?");
mysqli_stmt_bind_param($stmt, "i", $id_transaccion);
mysqli_stmt_execute($stmt);

// 1 = retiro en el local (queda "A retirar"), 2 = envio a domicilio (queda "En camino")
if ($metodo_envio == 1) {
    $stmt = mysqli_prepare($con, "INSERT INTO envios (fecha_envio, fecha_entrega, fk_estado_envio, fk_transaccion) VALUES (NULL, NULL, 1, ?)");
} else {
    $stmt = mysqli_prepare($con, "INSERT INTO envios (fecha_envio, fecha_entrega, fk_estado_envio, fk_transaccion) VALUES (NOW(), NULL, 2, ?)");
}
mysqli_stmt_bind_param($stmt, "i", $id_transaccion);
mysqli_stmt_execute($stmt);

header("Location: ../inicio.php?envio=ok");
exit;
?>
