<?php
session_start();
include_once("../../components/config/config.php");

if (!isset($_SESSION['id_usuario'])) {
    print "<div class='alert alert-warning'>Debes iniciar sesión para modificar tu perfil.</div>";
    exit;
}

$id_usuario = intval($_SESSION['id_usuario']);

if (!isset($_POST['campo'])) {
    print "<div class='alert alert-danger'>No se especificó el campo a actualizar.</div>";
    exit;
}

$campo = $_POST['campo'];

$res_usuario = mysqli_query($con, "SELECT * FROM usuarios WHERE id_usuario='$id_usuario'");
if (!$usuario = mysqli_fetch_assoc($res_usuario)) {
    print "<div class='alert alert-danger'>Usuario no encontrado.</div>";
    exit;
}

switch ($campo) {
    case 'nombre':
    case 'ubicacion':
        if (!isset($_POST['valor']) || trim($_POST['valor']) === '') {
            header("Location: ../perfil.php?modificar=$campo&error=1");
            exit;
        }
        $valor = mysqli_real_escape_string($con, trim($_POST['valor']));
        mysqli_query($con, "UPDATE usuarios SET $campo='$valor' WHERE id_usuario='$id_usuario'");
        if ($campo === 'nombre') {
            $_SESSION['nombre'] = $valor;
        }
        header("Location: ../perfil.php?ok=1");
        exit;
        break;

    case 'mail':
        if (!isset($_POST['valor']) || trim($_POST['valor']) === '') {
            header("Location: ../perfil.php?modificar=mail&error=1");
            exit;
        }
        $valor = mysqli_real_escape_string($con, trim($_POST['valor']));

        $check_mail = mysqli_query($con, "SELECT * FROM usuarios WHERE mail='$valor' AND id_usuario!='$id_usuario'");
        if (mysqli_num_rows($check_mail) > 0) {
            header("Location: ../perfil.php?modificar=mail&mail_exist=1");
            exit;
        }

        mysqli_query($con, "UPDATE usuarios SET mail='$valor' WHERE id_usuario='$id_usuario'");
        header("Location: ../perfil.php?ok=1");
        exit;
        break;

    case 'contrasena':
        if (!isset($_POST['actual'], $_POST['nuevo'], $_POST['nuevo_rep'])) {
            header("Location: ../perfil.php?modificar=contrasena&error=1");
            exit;
        }

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
        break;

    default:
        print "<div class='alert alert-danger'>Campo no válido.</div>";
        exit;
}
