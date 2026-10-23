<?php
include_once("../../components/config/config.php");
include_once("../../components/permisos.php");

$id_usuario = requiere_login("../../pages/login.php");

if (!isset($_GET['id']) || !isset($_GET['prod'])) {
    header("Location: ../editar.php");
    exit();
}

$id_archivo = intval($_GET['id']);
$id_producto = intval($_GET['prod']);

if (!es_duenio_producto($con, $id_producto, $id_usuario)) {
    header("Location: ../editar.php");
    exit();
}

// ademas chequeo que el archivo sea de ese producto
$consulta = "SELECT nombre FROM archivos WHERE id_archivo = $id_archivo AND fk_producto = $id_producto";
$res = mysqli_query($con, $consulta);

if (mysqli_num_rows($res) == 0) {
    header("Location: modificacion_prod.php?id=$id_producto&borrado=ok");
    exit();
}

$archivo = mysqli_fetch_assoc($res);
$nombre_archivo = $archivo['nombre'];

$ruta = "../../prod_img/" . $nombre_archivo;

if (file_exists($ruta)) {
    unlink($ruta);
}

$consulta_delete = "DELETE FROM archivos WHERE id_archivo = $id_archivo";
mysqli_query($con, $consulta_delete);

header("Location: modificacion_prod.php?id=$id_producto&borrado=ok");
exit();
