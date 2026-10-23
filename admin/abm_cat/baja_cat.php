<?php
include_once("../../components/config/config.php");
include_once("../../components/permisos.php");

requiere_admin();

// por POST desde el boton Eliminar. La categoria 1 es la generica, no se puede borrar
// porque ahi paso los productos de la categoria que se borra
if (isset($_POST['id']) && intval($_POST['id']) > 1) {
    $baja = intval($_POST['id']);

    $stmt = mysqli_prepare($con, "UPDATE productos SET fk_categoria = 1 WHERE fk_categoria = ?");
    mysqli_stmt_bind_param($stmt, "i", $baja);
    mysqli_stmt_execute($stmt);

    $stmt = mysqli_prepare($con, "DELETE FROM categorias WHERE id_categoria = ?");
    mysqli_stmt_bind_param($stmt, "i", $baja);
    mysqli_stmt_execute($stmt);

    header("Location: ../categoria.php?baja=ok");
    exit();
}

header("Location: ../categoria.php?error=protegida");
exit();
?>
