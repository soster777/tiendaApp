<?php
include_once("../../components/config/config.php");
include_once("../../components/permisos.php");

// solo un administrador puede darle permisos a otro usuario
requiere_admin();

// llega por POST desde el boton "Hacer administrador" de usuarios.php
if (!isset($_POST['id'])) {
    header("Location: ../usuarios.php");
    exit();
}

$id = intval($_POST['id']);

// chequeo que el usuario exista y que todavia no sea admin
$stmt = mysqli_prepare($con, "SELECT id_usuario FROM usuarios WHERE id_usuario = ? AND fk_rol <> 1");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

if (mysqli_num_rows(mysqli_stmt_get_result($stmt)) == 0) {
    header("Location: ../usuarios.php?error=1");
    exit();
}

// rol 1 = Admin (ver la tabla roles)
$stmt = mysqli_prepare($con, "UPDATE usuarios SET fk_rol = 1 WHERE id_usuario = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

header("Location: ../usuarios.php?ok=1");
exit();
