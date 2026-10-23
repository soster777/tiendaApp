<?php
session_start();

if(!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin'){
    header("Location: ../../index.php");
    exit();
}

include_once("../../components/config/config.php");

if(isset($_GET['cat']) && trim($_GET['cat']) !== ''){

    $cat = htmlspecialchars(trim($_GET['cat']));

    $stmt = mysqli_prepare($con, "INSERT INTO `categorias`(`categoria`) VALUES (?)");
    mysqli_stmt_bind_param($stmt, "s", $cat);
    mysqli_stmt_execute($stmt);

    header("Location: ../categoria.php?msn=ok");
    exit;
}

header("Location: ../categoria.php");
exit;
?>
