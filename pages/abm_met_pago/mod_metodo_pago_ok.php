<?php
include_once("../../components/config/config.php");
include_once("../../components/permisos.php");

$id_usuario = requiere_login("../login.php");

if (!isset($_POST['id_metodo'])) {
    header("Location: ../perfil.php");
    exit;
}

$id_metodo = intval($_POST['id_metodo']);
$nombre = trim($_POST['nombre'] ?? '');
$numero = trim($_POST['numero_tarjeta'] ?? '');
$venc = trim($_POST['vencimiento'] ?? '');
$cbu = trim($_POST['cbu'] ?? '');

$stmt = mysqli_prepare($con, "SELECT * FROM metodos_pagos_usuarios WHERE id_metodo_pago_usuario = ? AND fk_usuario = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, "ii", $id_metodo, $id_usuario);
mysqli_stmt_execute($stmt);
$metodo = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

// faltaba el ../ en esta redireccion
if (!$metodo || $nombre === '') {
    header("Location: ../perfil.php?error=metodo_datos");
    exit;
}

// arranco con lo que ya estaba guardado y cambio solo lo que mandaron
$ultimos4 = $metodo['numero_tarjeta'];
$fecha_venc = $metodo['vencimiento'];
$cbu_guardar = $metodo['cbu'];

if ($metodo['fk_metodo_pago'] == 1 || $metodo['fk_metodo_pago'] == 2) {
    if ($numero !== '') {
        if (!preg_match('/^[0-9]{13,19}$/', $numero)) {
            header("Location: ../perfil.php?error=metodo_datos");
            exit;
        }
        $ultimos4 = substr($numero, -4);
    }
    if (!preg_match('/^[0-9]{4}-[0-9]{2}$/', $venc)) {
        header("Location: ../perfil.php?error=metodo_datos");
        exit;
    }
    $fecha_venc = $venc . "-01";
} elseif ($metodo['fk_metodo_pago'] == 4 || $metodo['fk_metodo_pago'] == 5) {
    if (!preg_match('/^[0-9]{22}$/', $cbu)) {
        header("Location: ../perfil.php?error=metodo_datos");
        exit;
    }
    $cbu_guardar = $cbu;
}

$stmt = mysqli_prepare($con, "UPDATE metodos_pagos_usuarios
    SET nombre = ?, numero_tarjeta = ?, vencimiento = ?, codigo_tarjeta = NULL, cbu = ?
    WHERE id_metodo_pago_usuario = ? AND fk_usuario = ?");
mysqli_stmt_bind_param($stmt, "ssssii", $nombre, $ultimos4, $fecha_venc, $cbu_guardar, $id_metodo, $id_usuario);
mysqli_stmt_execute($stmt);

header("Location: ../perfil.php?metodo_modificado=1");
exit;
