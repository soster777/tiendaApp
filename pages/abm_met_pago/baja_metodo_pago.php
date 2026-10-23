<?php
session_start();
include_once("../../components/config/config.php");

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login.php");
    exit();
}

$baja;

if (isset($_GET['id'])) {
    $baja = intval($_GET['id']);
    $id_usuario = intval($_SESSION['id_usuario']);

    $consulta = "DELETE FROM `metodos_pagos_usuarios` WHERE `id_metodo_pago_usuario` = $baja AND `fk_usuario` = $id_usuario";

    mysqli_query($con, $consulta);

    header("Location: ../perfil.php?baja=ok");
    exit;
}
?>