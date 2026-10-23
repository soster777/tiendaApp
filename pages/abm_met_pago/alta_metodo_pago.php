<?php
session_start();
include_once("../../components/config/config.php");

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login.php");
    exit;
}

$id_usuario = $_SESSION['id_usuario'];
$fk_metodo = intval($_POST['fk_metodo_pago']);

$nombre = mysqli_real_escape_string($con, $_POST['nombre'] ?? '');
$numero = mysqli_real_escape_string($con, $_POST['numero_tarjeta'] ?? '');
$vencimiento = mysqli_real_escape_string($con, $_POST['vencimiento'] ?? '');
$codigo = mysqli_real_escape_string($con, $_POST['codigo_tarjeta'] ?? '');
$cbu = mysqli_real_escape_string($con, $_POST['cbu'] ?? '');

if ($vencimiento) {
    $vencimiento .= "-01";
}

$sql_check = "
    SELECT id_metodo_pago_usuario 
    FROM metodos_pagos_usuarios
    WHERE fk_usuario = '$id_usuario'
    AND fk_metodo_pago = '$fk_metodo'
    LIMIT 1
";

$res_check = mysqli_query($con, $sql_check);

if (mysqli_num_rows($res_check) > 0) {
    header("Location: ../perfil.php?metodo_repetido=1");
    exit;
}

$sql = "
    INSERT INTO metodos_pagos_usuarios 
    (nombre, numero_tarjeta, vencimiento, codigo_tarjeta, cbu, fk_metodo_pago, fk_usuario) 
    VALUES (
        " . ($nombre ? "'$nombre'" : "NULL") . ",
        " . ($numero ? "'$numero'" : "NULL") . ",
        " . ($vencimiento ? "'$vencimiento'" : "NULL") . ",
        " . ($codigo ? "'$codigo'" : "NULL") . ",
        " . ($cbu ? "'$cbu'" : "NULL") . ",
        '$fk_metodo',
        '$id_usuario'
    )
";

mysqli_query($con, $sql);

header("Location: ../perfil.php?metodo_ok=1");
exit;
