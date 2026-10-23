<?php
session_start();

if(!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin'){
    header("Location: ../../index.php");
    exit();
}

include_once("../../components/config/config.php");

if(isset($_GET['id']) && isset($_GET['cat']) && trim($_GET['cat']) !== ''){
    $id = intval($_GET['id']);
    $nombre = htmlspecialchars(trim($_GET['cat']));

    $stmt = mysqli_prepare($con, "UPDATE `categorias` SET `categoria` = ? WHERE `id_categoria` = ?");
    mysqli_stmt_bind_param($stmt, "si", $nombre, $id);
    mysqli_stmt_execute($stmt);

    header("Location: ../categoria.php?mod=ok");
    exit;
}

header("Location: ../categoria.php");
exit;
?>
