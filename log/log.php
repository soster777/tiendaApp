<?php
session_start();
include_once("../components/config/config.php");

if (isset($_POST["mail"]) && isset($_POST["contrasena"])) {

    $mail = trim($_POST["mail"]);
    $contrasena = $_POST["contrasena"];

    // consulta preparada, asi no se puede meter SQL desde el form
    $stmt = mysqli_prepare($con, "SELECT * FROM `usuarios` WHERE `mail` = ? AND `contrasena` = MD5(?)");
    mysqli_stmt_bind_param($stmt, "ss", $mail, $contrasena);
    mysqli_stmt_execute($stmt);
    $respuesta = mysqli_stmt_get_result($stmt);
    $fila = mysqli_fetch_assoc($respuesta);

    if ($fila) {
        $_SESSION['id_usuario'] = $fila['id_usuario'];
        $_SESSION['nombre'] = $fila['nombre'];
        $_SESSION['mail'] = $fila['mail'];
        $_SESSION['rol'] = ($fila['fk_rol'] == 1) ? 'admin' : 'usuario';

        if ($fila['fk_rol'] == 1) {
            header("Location: ../admin/index.php");
        } else {
            header("Location: ../pages/inicio.php");
        }
        exit;
    } else {
        header("Location: ../pages/login.php?datos=no");
        exit;
    }
}

header("Location: ../pages/login.php");
exit;
