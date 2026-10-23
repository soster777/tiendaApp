<?php
session_start();
include_once("../../components/config/config.php");

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login.php");
    exit;
}

$id_usuario = $_SESSION['id_usuario'];

$id_metodo = intval($_POST['id_metodo']);
$nombre = mysqli_real_escape_string($con, $_POST['nombre'] ?? '');
$numero = mysqli_real_escape_string($con, $_POST['numero_tarjeta'] ?? null);
$venc = mysqli_real_escape_string($con, $_POST['vencimiento'] ?? null);
$cod = mysqli_real_escape_string($con, $_POST['codigo_tarjeta'] ?? null);
$cbu = mysqli_real_escape_string($con, $_POST['cbu'] ?? null);

if ($venc) {
    $venc .= "-01";
}

$sql_check = "SELECT id_metodo_pago_usuario FROM metodos_pagos_usuarios 
              WHERE id_metodo_pago_usuario='$id_metodo' 
              AND fk_usuario='$id_usuario' LIMIT 1";
$res_check = mysqli_query($con, $sql_check);

if (mysqli_num_rows($res_check) === 0) {
    header("Location: perfil.php?error=metodo_no_existe");
    exit;
}

$sql = "UPDATE metodos_pagos_usuarios SET
        nombre = " . ($nombre ? "'$nombre'" : "NULL") . ",
        numero_tarjeta = " . ($numero ? "'$numero'" : "NULL") . ",
        vencimiento = " . ($venc ? "'$venc'" : "NULL") . ",
        codigo_tarjeta = " . ($cod ? "'$cod'" : "NULL") . ",
        cbu = " . ($cbu ? "'$cbu'" : "NULL") . "
        WHERE id_metodo_pago_usuario = '$id_metodo'
        AND fk_usuario = '$id_usuario'";

mysqli_query($con, $sql);

header("Location: ../perfil.php?metodo_modificado=1");
exit;
