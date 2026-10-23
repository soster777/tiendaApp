<?php
include_once("../../components/config/config.php");
include_once("../../components/permisos.php");

requiere_admin();

// por POST desde el formulario de categoria.php
if (isset($_POST['cat']) && trim($_POST['cat']) !== '') {

    // guardo el texto tal cual y lo escapo con htmlspecialchars recien al mostrarlo
    // (si lo escapo al guardar, "Tom & Jerry" se termina viendo como "Tom &amp; Jerry")
    $cat = trim($_POST['cat']);

    $stmt = mysqli_prepare($con, "INSERT INTO `categorias`(`categoria`) VALUES (?)");
    mysqli_stmt_bind_param($stmt, "s", $cat);
    mysqli_stmt_execute($stmt);

    header("Location: ../categoria.php?msn=ok");
    exit;
}

header("Location: ../categoria.php");
exit;
?>
