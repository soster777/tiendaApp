<?php
include_once("../../components/config/config.php");
include_once("../../components/permisos.php");

$id_usuario = requiere_login("../../pages/login.php");

if (!isset($_POST['id_producto'], $_POST['nombre'], $_POST['categoria'], $_POST['descripcion'], $_POST['stock'], $_POST['precio'])) {
    header("Location: ../editar.php");
    exit();
}

$id_producto = intval($_POST['id_producto']);
// guardo el texto tal cual: se escapa al mostrarlo (si no, cada vez que se editaba se volvia a escapar: & -> &amp; -> &amp;amp;)
$nombre = trim($_POST['nombre']);
$categoria = intval($_POST['categoria']);
$descripcion = trim($_POST['descripcion']);
$stock = intval($_POST['stock']);
$precio = floatval($_POST['precio']);

if (!es_duenio_producto($con, $id_producto, $id_usuario)) {
    header("Location: ../editar.php");
    exit();
}

if ($nombre === '' || $precio <= 0 || $stock < 0) {
    header("Location: modificacion_prod.php?id=$id_producto&error=datos");
    exit();
}

// mismas extensiones que hay en la tabla tipos_archivos
$permitidos = ['jpg', 'jpeg', 'png', 'mp4', 'mov', 'mpeg', 'avi'];
$imagenes_obligatorias = ['jpg', 'jpeg', 'png'];

// 1) PRIMERO valido todo. Antes se guardaban los cambios y recien despues se revisaban las imagenes,
//    entonces aunque mostrara el error los cambios ya quedaban guardados
$archivos_nuevos = [];
$hayImagenNueva = false;

if (!empty($_FILES['media']['name'][0])) {
    $total_archivos = count($_FILES['media']['name']);

    for ($i = 0; $i < $total_archivos; $i++) {
        $nombreOriginal = $_FILES['media']['name'][$i];
        $extension = strtolower(pathinfo($nombreOriginal, PATHINFO_EXTENSION));

        if ($_FILES['media']['error'][$i] !== UPLOAD_ERR_OK || !in_array($extension, $permitidos)) {
            header("Location: modificacion_prod.php?id=$id_producto&error=" . urlencode("Tipo de archivo no permitido: $nombreOriginal"));
            exit();
        }

        if (in_array($extension, $imagenes_obligatorias)) {
            $hayImagenNueva = true;
        }
        $archivos_nuevos[] = $i;
    }
}

$stmt = mysqli_prepare($con, "SELECT COUNT(*) AS cantidad FROM archivos a
    JOIN tipos_archivos t ON t.id_tipo_archivo = a.fk_tipo_archivo
    WHERE a.fk_producto = ? AND t.tipo IN ('JPG', 'JPEG', 'PNG')");
mysqli_stmt_bind_param($stmt, "i", $id_producto);
mysqli_stmt_execute($stmt);
$imagenes_actuales = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))['cantidad'];

if ($imagenes_actuales == 0 && !$hayImagenNueva) {
    header("Location: modificacion_prod.php?id=$id_producto&error=no_image");
    exit();
}

// 2) si esta todo bien, guardo
$estado = ($stock > 0) ? 1 : 2;

$stmt = mysqli_prepare($con, "UPDATE productos
             SET nombre = ?, descripcion = ?, precio = ?, stock = ?, fk_categoria = ?, fk_estado_producto = ?
             WHERE id_producto = ?");
mysqli_stmt_bind_param($stmt, "ssdiiii", $nombre, $descripcion, $precio, $stock, $categoria, $estado, $id_producto);
mysqli_stmt_execute($stmt);

foreach ($archivos_nuevos as $i) {
    $nombreOriginal = $_FILES['media']['name'][$i];
    $extension = strtolower(pathinfo($nombreOriginal, PATHINFO_EXTENSION));

    $nuevoNombre = time() . "_" . preg_replace('/[^A-Za-z0-9._-]/', '_', $nombreOriginal);
    move_uploaded_file($_FILES['media']['tmp_name'][$i], "../../prod_img/" . $nuevoNombre);

    $stmt_archivo = mysqli_prepare($con, "INSERT INTO archivos (nombre, fk_tipo_archivo, fk_producto)
               VALUES (?, (SELECT id_tipo_archivo FROM tipos_archivos WHERE tipo = ?), ?)");
    mysqli_stmt_bind_param($stmt_archivo, "ssi", $nuevoNombre, $extension, $id_producto);
    mysqli_stmt_execute($stmt_archivo);
}

header("Location: ../editar.php?modificacion=ok");
exit();
