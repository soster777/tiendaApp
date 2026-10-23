<?php
// funciones para chequear que el usuario este logueado y que el producto sea suyo
// las uso en el editor para que nadie pueda borrar o editar productos de otro cambiando el id en la url

// si la sesion no estaba iniciada (cuando no se incluye el header) la inicio
if (!isset($_SESSION)) {
    session_start();
}

function requiere_login($ruta_login)
{
    if (!isset($_SESSION['id_usuario'])) {
        header("Location: $ruta_login");
        exit();
    }
    return intval($_SESSION['id_usuario']);
}

function es_duenio_producto($con, $id_producto, $id_usuario)
{
    $stmt = mysqli_prepare($con, "SELECT id_producto FROM productos WHERE id_producto = ? AND fk_usuario = ?");
    mysqli_stmt_bind_param($stmt, "ii", $id_producto, $id_usuario);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    return mysqli_num_rows($res) > 0;
}
