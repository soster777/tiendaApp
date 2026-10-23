<?php
include_once("../../components/config/config.php");
include_once("../../components/permisos.php");

$id_usuario = requiere_login("../login.php");

// llega por POST desde el boton Eliminar del perfil
if (isset($_POST['id'])) {
    $baja = intval($_POST['id']);

    // no se borra si tiene compras pendientes o envios sin terminar (lo mismo que avisa el perfil)
    $stmt = mysqli_prepare($con, "SELECT t.id_transaccion FROM transacciones t
        LEFT JOIN envios e ON e.fk_transaccion = t.id_transaccion
        WHERE t.fk_metodo_pago_usuario = ? AND (t.fk_estado_transaccion = 1 OR e.fk_estado_envio IN (1, 2))
        LIMIT 1");
    mysqli_stmt_bind_param($stmt, "i", $baja);
    mysqli_stmt_execute($stmt);
    if (mysqli_num_rows(mysqli_stmt_get_result($stmt)) > 0) {
        header("Location: ../perfil.php?error=metodo_en_uso");
        exit;
    }

    $stmt = mysqli_prepare($con, "DELETE FROM metodos_pagos_usuarios WHERE id_metodo_pago_usuario = ? AND fk_usuario = ?");
    mysqli_stmt_bind_param($stmt, "ii", $baja, $id_usuario);
    mysqli_stmt_execute($stmt);
}

header("Location: ../perfil.php?baja=ok");
exit;
