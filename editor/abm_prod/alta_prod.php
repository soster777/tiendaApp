<?php
include_once("../../components/config/config.php");
include_once("../../components/permisos.php");

$id_usuario = requiere_login("../../pages/login.php");

$errores = [];

$archivosValidos = ["JPG", "JPEG", "PNG", "MP4", "WEBM", "OGG"];
$imagenesObligatorias = ["JPG", "JPEG", "PNG"];

if (
    isset($_POST['nombre'], $_POST['categoria'], $_POST['descripcion'], $_POST['stock'], $_POST['precio']) &&
    isset($_FILES['media'])
) {

    $nombre = htmlspecialchars($_POST['nombre']);
    $categoria = intval($_POST['categoria']);
    $descripcion = htmlspecialchars($_POST['descripcion']);
    $stock = intval($_POST['stock']);
    $precio = floatval($_POST['precio']);
    // el usuario lo saco de la sesion y no del form, asi no se puede publicar a nombre de otro
    $usuario = $id_usuario;

    $hayArchivoValido = false;
    $hayImagen = false;

    foreach ($_FILES['media']['name'] as $i => $name) {

        if ($_FILES['media']['error'][$i] !== UPLOAD_ERR_OK) {
            $errores[] = "Error al subir el archivo: $name";
            continue;
        }

        $ext = strtoupper(pathinfo($name, PATHINFO_EXTENSION));

        if (!in_array($ext, $archivosValidos)) {
            $errores[] = "Tipo de archivo no permitido: $name";
            continue;
        }

        $hayArchivoValido = true;
        if (in_array($ext, $imagenesObligatorias)) {
            $hayImagen = true;
        }
    }

    if (!$hayArchivoValido) {
        $mensaje = !empty($errores) ? urlencode(implode(" | ", $errores)) : "noimg";
        header("Location: ../publicar.php?error=$mensaje");
        exit;
    }

    if (!$hayImagen) {
        $mensaje = "Debe subir al menos una imagen (JPG, JPEG o PNG).";
        header("Location: ../publicar.php?error=" . urlencode($mensaje));
        exit;
    }

    $stmt = mysqli_prepare($con, "INSERT INTO productos (nombre, descripcion, precio, stock, fecha_publicacion, fk_categoria, fk_usuario, fk_estado_producto)
                 VALUES (?, ?, ?, ?, NOW(), ?, ?, 1)");
    mysqli_stmt_bind_param($stmt, "ssdiii", $nombre, $descripcion, $precio, $stock, $categoria, $usuario);
    mysqli_stmt_execute($stmt);
    $id_producto = mysqli_insert_id($con);

    $tipos_query = mysqli_query($con, "SELECT id_tipo_archivo, tipo FROM tipos_archivos");
    $tipos_archivos = [];
    while ($row = mysqli_fetch_assoc($tipos_query)) {
        $tipos_archivos[strtoupper($row['tipo'])] = $row['id_tipo_archivo'];
    }

    foreach ($_FILES['media']['tmp_name'] as $i => $tmp) {

        $nombreOriginal = $_FILES['media']['name'][$i];
        $ext = strtoupper(pathinfo($nombreOriginal, PATHINFO_EXTENSION));

        if (!in_array($ext, $archivosValidos)) {
            continue;
        }

        // limpio el nombre para que no tenga caracteres raros ni rutas tipo ../
        $nombreArchivo = time() . "_" . preg_replace('/[^A-Za-z0-9._-]/', '_', $nombreOriginal);
        move_uploaded_file($tmp, "../../prod_img/$nombreArchivo");

        $tipo_archivo = $tipos_archivos[$ext];
        $stmt_archivo = mysqli_prepare($con, "INSERT INTO archivos (nombre, fk_tipo_archivo, fk_producto) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt_archivo, "sii", $nombreArchivo, $tipo_archivo, $id_producto);
        mysqli_stmt_execute($stmt_archivo);
    }

    header("Location: ../publicar.php?dato=ok");
    exit;
} else {
    print "Faltan datos obligatorios.";
}
