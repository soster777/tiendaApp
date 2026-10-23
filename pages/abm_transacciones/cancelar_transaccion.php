<?php
include_once("../../components/config/config.php");

if (!isset($_GET['id'])) {
    print "<div class='alert alert-warning'>ID de transacción no especificado.</div>";
    exit;
}

$id_transaccion = intval($_GET['id']);

$update = "UPDATE transacciones SET fk_estado_transaccion = 3 WHERE id_transaccion = '$id_transaccion'";
if (mysqli_query($con, $update)) {
    print "<div class='alert alert-success'>Compra cancelada correctamente.</div>";
    header("Location: ../inicio.php?cancel=ok");
    exit;
} else {
    print "<div class='alert alert-danger'>Error al cancelar la compra.</div>";
}
?>