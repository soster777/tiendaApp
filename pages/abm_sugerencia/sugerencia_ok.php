<?php
include_once("../../components/config/config.php");

session_start();
$id_usuario = $_SESSION['id_usuario'] ?? 0;

if (!$id_usuario) {
    header("Location: ../contacto.php");
    exit;
}

$errores = [];
$archivosValidos = ["JPG", "JPEG", "PNG"];
$comentario = $_POST['comentario'] ?? null;

if (!$comentario) {
    $errores[] = "El comentario es obligatorio.";
}

$nombreArchivo = null;
if (isset($_FILES['archivo']) && $_FILES['archivo']['tmp_name']) {

    $ext = strtoupper(pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $archivosValidos)) {
        $errores[] = "Solo se permiten archivos JPG, JPEG o PNG.";
    } else {
        $nombreArchivo = time() . "_" . preg_replace('/[^A-Za-z0-9._-]/', '_', $_FILES['archivo']['name']);
        $rutaDestino = "../../contact_imgs/" . $nombreArchivo;

        if (!move_uploaded_file($_FILES['archivo']['tmp_name'], $rutaDestino)) {
            $errores[] = "Error al subir el archivo.";
            $nombreArchivo = null;
        }
    }
}

if (empty($errores)) {
    $comentarioSQL = mysqli_real_escape_string($con, $comentario);
    $archivoSQL = $nombreArchivo ? "'" . mysqli_real_escape_string($con, $nombreArchivo) . "'" : "NULL";

    $sql = "INSERT INTO sugerencias (sugerencia, archivo, fk_usuario, fk_estado_sugerencia)
            VALUES ('$comentarioSQL', $archivoSQL, $id_usuario, 1)";
    
    mysqli_query($con, $sql);

    header("Location: ../contacto.php?dato=ok");
    exit;
} else {
    $mensaje = urlencode(implode(" | ", $errores));
    header("Location: ../contacto.php?error=$mensaje");
    exit;
}