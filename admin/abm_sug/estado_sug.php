<?php
include_once("../../components/config/config.php");
include_once("../../components/permisos.php");

requiere_admin();

if (isset($_POST['id'], $_POST['estado'])) {
    $id = intval($_POST['id']);
    $estado = intval($_POST['estado']);

    // 1 = Pendiente, 2 = Aprobada, 3 = Rechazada
    if ($estado >= 1 && $estado <= 3) {
        $stmt = mysqli_prepare($con, "UPDATE sugerencias SET fk_estado_sugerencia = ? WHERE id_sugerencia = ?");
        mysqli_stmt_bind_param($stmt, "ii", $estado, $id);
        mysqli_stmt_execute($stmt);
    }
}

header("Location: ../sugerencias.php?estado=ok");
exit;
?>
