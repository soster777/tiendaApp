<?php
include_once("../../components/config/config.php");
include_once("../../components/permisos.php");

requiere_admin();

if (isset($_POST['id']) && isset($_POST['cat']) && trim($_POST['cat']) !== '') {
    $id = intval($_POST['id']);
    $nombre = trim($_POST['cat']);

    // la categoria 1 (General) no se toca
    if ($id <= 1) {
        header("Location: ../categoria.php?error=protegida");
        exit;
    }

    $stmt = mysqli_prepare($con, "UPDATE `categorias` SET `categoria` = ? WHERE `id_categoria` = ?");
    mysqli_stmt_bind_param($stmt, "si", $nombre, $id);
    mysqli_stmt_execute($stmt);

    header("Location: ../categoria.php?mod=ok");
    exit;
}

header("Location: ../categoria.php");
exit;
?>
