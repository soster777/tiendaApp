<?php
include_once("../components/config/config.php");

if (isset($_POST["nombre"]) and isset($_POST["mail"]) and isset($_POST["contrasena"]) and isset($_POST["contrasena_rep"]) and isset($_POST["ubicacion"])) {

    // guardo los datos tal cual y los escapo con htmlspecialchars recien al mostrarlos
    // (la contraseña tampoco se escapa: se usa igual aca, en el login y al cambiarla)
    $nombre = trim($_POST["nombre"]);
    $mail = trim($_POST["mail"]);
    $contrasena = $_POST["contrasena"];
    $contrasena_rep = $_POST["contrasena_rep"];
    $ubicacion = trim($_POST["ubicacion"]);

    if ($nombre == "" or $mail == "" or $contrasena == "" or $contrasena_rep == "") {
        header("Location: ../pages/registro.php?datos=faltan");
        exit;
    }

    if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../pages/registro.php?datos=faltan");
        exit;
    }

    if ($contrasena == $contrasena_rep) {
        $stmt = mysqli_prepare($con, "SELECT `id_usuario` FROM `usuarios` WHERE `mail` = ?");
        mysqli_stmt_bind_param($stmt, "s", $mail);
        mysqli_stmt_execute($stmt);
        $respuesta = mysqli_stmt_get_result($stmt);

        if (mysqli_fetch_assoc($respuesta)) {
            header("Location: ../pages/registro.php?correo=no");
        } else {
            $ubicacion = ($ubicacion == "") ? null : $ubicacion;
            $stmt_reg = mysqli_prepare($con, "INSERT INTO `usuarios`(`nombre`, `mail`, `contrasena`, `ubicacion`, `fk_rol`) VALUES (?, ?, MD5(?), ?, 2)");
            mysqli_stmt_bind_param($stmt_reg, "ssss", $nombre, $mail, $contrasena, $ubicacion);
            mysqli_stmt_execute($stmt_reg);

            header("Location: ../pages/login.php?log=ok");
        }
    } else {
        header("Location: ../pages/registro.php?clave=no");
    }
} else {
    header("Location: ../pages/registro.php?datos=faltan");
}
exit;
