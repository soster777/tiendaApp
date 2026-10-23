<?php
session_start();
include_once("../../components/config/config.php");

if (!isset($_SESSION['id_usuario'])) {
    print "<div class='alert alert-warning'>Debes iniciar sesión para continuar.</div>";
    exit;
}

if (!isset($_POST['id_transaccion'], $_POST['metodo_envio'])) {
    print "<div class='alert alert-danger'>Faltan datos obligatorios.</div>";
    exit;
}

$id_transaccion = intval($_POST['id_transaccion']);
$metodo_envio   = intval($_POST['metodo_envio']);

$check_trans = "
    SELECT * 
    FROM transacciones 
    WHERE id_transaccion = $id_transaccion
    LIMIT 1
";
$res_trans = mysqli_query($con, $check_trans);

if (!$res_trans || mysqli_num_rows($res_trans) == 0) {
    print "<div class='alert alert-danger'>Transacción no válida.</div>";
    exit;
}

$trans = mysqli_fetch_assoc($res_trans);

$update_trans = "
    UPDATE transacciones
    SET fk_estado_transaccion = 2
    WHERE id_transaccion = $id_transaccion
";
mysqli_query($con, $update_trans);

$id_producto = $trans['fk_producto'];
$cantidad_comprada = $trans['cantidad'];

$res_stock = mysqli_query($con, "SELECT stock FROM productos WHERE id_producto = $id_producto");
$stock_actual = intval(mysqli_fetch_assoc($res_stock)['stock']);

$nuevo_stock = max(0, $stock_actual - $cantidad_comprada);
$fk_estado_producto = ($nuevo_stock == 0) ? 2 : 1;

$update_stock = "
    UPDATE productos
    SET stock = $nuevo_stock,
        fk_estado_producto = $fk_estado_producto
    WHERE id_producto = $id_producto
";
mysqli_query($con, $update_stock);

if ($metodo_envio == 1) {
    $estado_envio = 1;
    $fecha_envio = "NULL";
} else {
    $estado_envio = 2;
    $fecha_envio = "NOW()";
}

$insert_envio = "
    INSERT INTO envios (fecha_envio, fecha_entrega, fk_estado_envio, fk_transaccion)
    VALUES ($fecha_envio, NULL, $estado_envio, $id_transaccion)
";

if (!mysqli_query($con, $insert_envio)) {
    print "<div class='alert alert-danger'>Error al registrar el envío: " . mysqli_error($con) . "</div>";
    exit;
}

header("Location: ../inicio.php?envio=ok");
exit;
?>