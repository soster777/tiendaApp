<?php
include_once("../../components/config/config.php");
include_once("../../components/permisos.php");

$id_usuario = requiere_login("../../pages/login.php");

// llega por POST desde el boton Eliminar de "Mis productos"
if (isset($_POST['id'])) {

    $id = intval($_POST['id']);

    if (!es_duenio_producto($con, $id, $id_usuario)) {
        header("Location: ../editar.php");
        exit();
    }

    // si alguien lo esta comprando o tiene un envio en camino, no se puede borrar todavia
    $stmt = mysqli_prepare($con, "SELECT t.id_transaccion FROM transacciones t
        LEFT JOIN envios e ON e.fk_transaccion = t.id_transaccion
        WHERE t.fk_producto = ? AND (t.fk_estado_transaccion = 1 OR e.fk_estado_envio IN (1, 2)) LIMIT 1");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    if (mysqli_num_rows(mysqli_stmt_get_result($stmt)) > 0) {
        header("Location: ../editar.php?baja=no");
        exit();
    }

    $stmt = mysqli_prepare($con, "SELECT nombre FROM archivos WHERE fk_producto = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    while ($fila = mysqli_fetch_assoc($resultado)) {
        $ruta = "../../prod_img/" . $fila['nombre'];
        if (file_exists($ruta)) {
            unlink($ruta);
        }
    }

    $stmt = mysqli_prepare($con, "DELETE FROM archivos WHERE fk_producto = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    $stmt = mysqli_prepare($con, "DELETE FROM productos WHERE id_producto = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    header("Location: ../editar.php?baja=ok");
    exit();
}

header("Location: ../editar.php");
exit();
?>
