<?php
include_once("../../components/config/config.php");
include_once("../../components/permisos.php");

$id_usuario = requiere_login("../../pages/login.php");

if (
    isset(
        $_POST['id_producto'],
        $_POST['nombre'],
        $_POST['categoria'],
        $_POST['descripcion'],
        $_POST['stock'],
        $_POST['precio']
    )
) {

    $id_producto = intval($_POST['id_producto']);
    $nombre = htmlspecialchars($_POST['nombre']);
    $categoria = intval($_POST['categoria']);
    $descripcion = htmlspecialchars($_POST['descripcion']);
    $stock = intval($_POST['stock']);
    $precio = floatval($_POST['precio']);

    if (!es_duenio_producto($con, $id_producto, $id_usuario)) {
        header("Location: ../editar.php");
        exit();
    }

    $estado = ($stock > 0) ? 1 : 2;

    $stmt = mysqli_prepare($con, "UPDATE productos 
                 SET nombre = ?, descripcion = ?, precio = ?, stock = ?, fk_categoria = ?, fk_estado_producto = ?
                 WHERE id_producto = ?");
    mysqli_stmt_bind_param($stmt, "ssdiiii", $nombre, $descripcion, $precio, $stock, $categoria, $estado, $id_producto);
    mysqli_stmt_execute($stmt);

    $permitidos = ['jpg', 'jpeg', 'png', 'mp4', 'mov', 'mpeg', 'avi'];
    $imagenes_obligatorias = ['jpg', 'jpeg', 'png'];

    $errores = [];
    $hayArchivoValido = false;
    $hayImagen = false;

    $check_imagenes = mysqli_query(
        $con,
        "SELECT id_archivo FROM archivos 
         WHERE fk_producto = $id_producto 
         AND (nombre LIKE '%.jpg' OR nombre LIKE '%.jpeg' OR nombre LIKE '%.png')"
    );
    if (mysqli_num_rows($check_imagenes) > 0) {
        $hayArchivoValido = true;
        $hayImagen = true;
    }

    if (!empty($_FILES['media']['name'][0])) {
        $total_archivos = count($_FILES['media']['name']);

        for ($i = 0; $i < $total_archivos; $i++) {
            $nombreOriginal = $_FILES['media']['name'][$i];
            $tmpPath = $_FILES['media']['tmp_name'][$i];
            $extension = strtolower(pathinfo($nombreOriginal, PATHINFO_EXTENSION));

            if (!in_array($extension, $permitidos)) {

                $errores[] = "Tipo de archivo no permitido: $nombreOriginal";
                continue;
            }

            $hayArchivoValido = true;
            if (in_array($extension, $imagenes_obligatorias)) {
                $hayImagen = true;
            }

            $nuevoNombre = time() . "_" . preg_replace('/[^A-Za-z0-9._-]/', '_', $nombreOriginal);
            $destino = "../../prod_img/" . $nuevoNombre;
            move_uploaded_file($tmpPath, $destino);

            $stmt_archivo = mysqli_prepare($con, "INSERT INTO archivos (nombre, fk_tipo_archivo, fk_producto)
                       VALUES (?, (SELECT id_tipo_archivo FROM tipos_archivos WHERE tipo = ?), ?)");
            mysqli_stmt_bind_param($stmt_archivo, "ssi", $nuevoNombre, $extension, $id_producto);
            mysqli_stmt_execute($stmt_archivo);
        }
    }

    if (!$hayArchivoValido) {
        $mensaje = !empty($errores) ? urlencode(implode(" | ", $errores)) : "no_file";
        header("Location: modificacion_prod.php?id=$id_producto&error=$mensaje");
        exit();
    }

    if (!$hayImagen) {
        header("Location: modificacion_prod.php?id=$id_producto&error=no_image");
        exit();
    }

    header("Location: ../editar.php?modificacion=ok");
    exit();
} else {
    print "Faltan datos obligatorios.";
}
