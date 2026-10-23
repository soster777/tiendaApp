<?php
include_once("../../components/config/config.php");
include_once("../../components/permisos.php");

$id_usuario = requiere_login("../../pages/login.php");

// llega por POST (el boton X del formulario de modificar)
if (!isset($_POST['id_archivo']) || !isset($_POST['id_producto'])) {
    header("Location: ../editar.php");
    exit();
}

$id_archivo = intval($_POST['id_archivo']);
$id_producto = intval($_POST['id_producto']);

if (!es_duenio_producto($con, $id_producto, $id_usuario)) {
    header("Location: ../editar.php");
    exit();
}

// chequeo que el archivo sea de ese producto y veo de que tipo es
$stmt = mysqli_prepare($con, "SELECT a.nombre, t.tipo FROM archivos a
    JOIN tipos_archivos t ON t.id_tipo_archivo = a.fk_tipo_archivo
    WHERE a.id_archivo = ? AND a.fk_producto = ?");
mysqli_stmt_bind_param($stmt, "ii", $id_archivo, $id_producto);
mysqli_stmt_execute($stmt);
$archivo = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$archivo) {
    header("Location: modificacion_prod.php?id=$id_producto");
    exit();
}

// no dejo borrar la ultima imagen: el producto tiene que tener al menos una foto
if (in_array($archivo['tipo'], ['JPG', 'JPEG', 'PNG'])) {
    $stmt = mysqli_prepare($con, "SELECT COUNT(*) AS cantidad FROM archivos a
        JOIN tipos_archivos t ON t.id_tipo_archivo = a.fk_tipo_archivo
        WHERE a.fk_producto = ? AND t.tipo IN ('JPG', 'JPEG', 'PNG')");
    mysqli_stmt_bind_param($stmt, "i", $id_producto);
    mysqli_stmt_execute($stmt);
    if (mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))['cantidad'] <= 1) {
        header("Location: modificacion_prod.php?id=$id_producto&error=ultima_imagen");
        exit();
    }
}

$ruta = "../../prod_img/" . $archivo['nombre'];
if (file_exists($ruta)) {
    unlink($ruta);
}

$stmt = mysqli_prepare($con, "DELETE FROM archivos WHERE id_archivo = ?");
mysqli_stmt_bind_param($stmt, "i", $id_archivo);
mysqli_stmt_execute($stmt);

header("Location: modificacion_prod.php?id=$id_producto&borrado=ok");
exit();
