<?php
include_once("../../components/config/config.php");
include_once("../../components/permisos.php");

$id_usuario = requiere_login("../login.php");

if (!isset($_POST['fk_metodo_pago'])) {
    header("Location: ../perfil.php");
    exit;
}

$fk_metodo = intval($_POST['fk_metodo_pago']);
$nombre = trim($_POST['nombre'] ?? '');
$numero = trim($_POST['numero_tarjeta'] ?? '');
$vencimiento = trim($_POST['vencimiento'] ?? '');
$cbu = trim($_POST['cbu'] ?? '');

if ($nombre === '') {
    header("Location: ../perfil.php?error=metodo_datos");
    exit;
}

$ultimos4 = null;
$fecha_venc = null;
$cbu_guardar = null;

// 1 y 2 son tarjetas: valido con expresiones regulares y guardo SOLO los ultimos 4 numeros
// (el numero completo y el codigo de seguridad no se guardan nunca)
if ($fk_metodo == 1 || $fk_metodo == 2) {
    if (!preg_match('/^[0-9]{13,19}$/', $numero) || !preg_match('/^[0-9]{4}-[0-9]{2}$/', $vencimiento)) {
        header("Location: ../perfil.php?error=metodo_datos");
        exit;
    }
    $ultimos4 = substr($numero, -4);
    $fecha_venc = $vencimiento . "-01";
} elseif ($fk_metodo == 4 || $fk_metodo == 5) {
    if (!preg_match('/^[0-9]{22}$/', $cbu)) {
        header("Location: ../perfil.php?error=metodo_datos");
        exit;
    }
    $cbu_guardar = $cbu;
}

// no se puede agregar dos veces el mismo tipo de metodo
$stmt = mysqli_prepare($con, "SELECT id_metodo_pago_usuario FROM metodos_pagos_usuarios WHERE fk_usuario = ? AND fk_metodo_pago = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, "ii", $id_usuario, $fk_metodo);
mysqli_stmt_execute($stmt);
if (mysqli_num_rows(mysqli_stmt_get_result($stmt)) > 0) {
    header("Location: ../perfil.php?metodo_repetido=1");
    exit;
}

$stmt = mysqli_prepare($con, "INSERT INTO metodos_pagos_usuarios
    (nombre, numero_tarjeta, vencimiento, codigo_tarjeta, cbu, fk_metodo_pago, fk_usuario)
    VALUES (?, ?, ?, NULL, ?, ?, ?)");
mysqli_stmt_bind_param($stmt, "ssssii", $nombre, $ultimos4, $fecha_venc, $cbu_guardar, $fk_metodo, $id_usuario);
mysqli_stmt_execute($stmt);

header("Location: ../perfil.php?metodo_ok=1");
exit;
