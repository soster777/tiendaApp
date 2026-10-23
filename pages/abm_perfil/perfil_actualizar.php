<?php
include_once("../../components/config/config.php");
include_once("../../components/permisos.php");

$id_usuario = requiere_login("/tiendApp/pages/login.php");

if (!isset($_POST['campo'])) {
    header("Location: ../perfil.php");
    exit;
}

$campo = $_POST['campo'];

$stmt = mysqli_prepare($con, "SELECT * FROM usuarios WHERE id_usuario = ?");
mysqli_stmt_bind_param($stmt, "i", $id_usuario);
mysqli_stmt_execute($stmt);
$usuario = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
if (!$usuario) {
    header("Location: ../perfil.php?error=2");
    exit;
}

switch ($campo) {
    case 'nombre':
    case 'ubicacion':
        if (!isset($_POST['valor']) || trim($_POST['valor']) === '') {
            header("Location: ../perfil.php?modificar=$campo&error=1");
            exit;
        }
        // guardo el texto tal cual (se escapa al mostrarlo). Con la consulta preparada no hace
        // falta mysqli_real_escape_string, que era lo que hacia que el header mostrara O\'Brien
        $valor = trim($_POST['valor']);
        if ($campo === 'nombre') {
            $stmt = mysqli_prepare($con, "UPDATE usuarios SET nombre = ? WHERE id_usuario = ?");
        } else {
            $stmt = mysqli_prepare($con, "UPDATE usuarios SET ubicacion = ? WHERE id_usuario = ?");
        }
        mysqli_stmt_bind_param($stmt, "si", $valor, $id_usuario);
        mysqli_stmt_execute($stmt);
        if ($campo === 'nombre') {
            $_SESSION['nombre'] = $valor;
        }
        header("Location: ../perfil.php?ok=1");
        exit;

    case 'mail':
        if (!isset($_POST['valor']) || trim($_POST['valor']) === '') {
            header("Location: ../perfil.php?modificar=mail&error=1");
            exit;
        }
        $valor = trim($_POST['valor']);

        // antes no se validaba el formato del mail nuevo
        if (!filter_var($valor, FILTER_VALIDATE_EMAIL)) {
            header("Location: ../perfil.php?modificar=mail&error=mail");
            exit;
        }

        $stmt = mysqli_prepare($con, "SELECT id_usuario FROM usuarios WHERE mail = ? AND id_usuario != ?");
        mysqli_stmt_bind_param($stmt, "si", $valor, $id_usuario);
        mysqli_stmt_execute($stmt);
        if (mysqli_num_rows(mysqli_stmt_get_result($stmt)) > 0) {
            header("Location: ../perfil.php?modificar=mail&mail_exist=1");
            exit;
        }

        $stmt = mysqli_prepare($con, "UPDATE usuarios SET mail = ? WHERE id_usuario = ?");
        mysqli_stmt_bind_param($stmt, "si", $valor, $id_usuario);
        mysqli_stmt_execute($stmt);
        // tambien actualizo la sesion, que seguia con el mail viejo
        $_SESSION['mail'] = $valor;
        header("Location: ../perfil.php?ok=1");
        exit;

    case 'contrasena':
        if (!isset($_POST['actual'], $_POST['nuevo'], $_POST['nuevo_rep'])) {
            header("Location: ../perfil.php?modificar=contrasena&error=1");
            exit;
        }

        // la contraseña se usa tal cual en todos lados (login, registro y aca), sin htmlspecialchars:
        // asi una clave con & o < funciona igual al registrarse, al entrar y al cambiarla
        $actual = md5($_POST['actual']);
        $nuevo = $_POST['nuevo'];
        $nuevo_rep = $_POST['nuevo_rep'];

        if ($actual !== $usuario['contrasena']) {
            header("Location: ../perfil.php?modificar=contrasena&error=clave_actual");
            exit;
        }

        if ($nuevo === '' || $nuevo !== $nuevo_rep) {
            header("Location: ../perfil.php?modificar=contrasena&error=clave_no_coincide");
            exit;
        }

        $nuevo_md5 = md5($nuevo);
        $stmt = mysqli_prepare($con, "UPDATE usuarios SET contrasena = ? WHERE id_usuario = ?");
        mysqli_stmt_bind_param($stmt, "si", $nuevo_md5, $id_usuario);
        mysqli_stmt_execute($stmt);
        header("Location: ../perfil.php?ok=1");
        exit;

    default:
        header("Location: ../perfil.php?error=2");
        exit;
}
