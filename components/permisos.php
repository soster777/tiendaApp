<?php
// funciones para chequear permisos. Este archivo se incluye SIEMPRE antes que el header,
// porque si el header ya imprimio el HTML despues no se puede redirigir con header()

// si la sesion no estaba iniciada la inicio
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

function requiere_admin()
{
    if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
        header("Location: /tiendApp/index.php");
        exit();
    }
    return intval($_SESSION['id_usuario']);
}

// true si el que esta logueado es administrador
function es_admin()
{
    return isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin';
}

// el admin puede modificar y borrar CUALQUIER producto desde su panel (lo pide la consigna del final);
// un usuario comun solo los suyos
function puede_editar_producto($con, $id_producto, $id_usuario)
{
    if (es_admin()) {
        return true;
    }
    return es_duenio_producto($con, $id_producto, $id_usuario);
}

// a donde vuelve despues de modificar o borrar un producto:
// el admin a la lista de todos los productos, el usuario a "Mis productos"
function lista_de_productos()
{
    if (es_admin()) {
        return "/tiendApp/admin/productos.php";
    }
    return "/tiendApp/editor/editar.php";
}

function es_duenio_producto($con, $id_producto, $id_usuario)
{
    $stmt = mysqli_prepare($con, "SELECT id_producto FROM productos WHERE id_producto = ? AND fk_usuario = ?");
    mysqli_stmt_bind_param($stmt, "ii", $id_producto, $id_usuario);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    return mysqli_num_rows($res) > 0;
}

// devuelve la compra si es del usuario (la compra es de quien eligio el metodo de pago), o null si no
function transaccion_del_usuario($con, $id_transaccion, $id_usuario)
{
    $stmt = mysqli_prepare($con, "SELECT t.* FROM transacciones t
        JOIN metodos_pagos_usuarios mpu ON mpu.id_metodo_pago_usuario = t.fk_metodo_pago_usuario
        WHERE t.id_transaccion = ? AND mpu.fk_usuario = ?");
    mysqli_stmt_bind_param($stmt, "ii", $id_transaccion, $id_usuario);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($res);
}
