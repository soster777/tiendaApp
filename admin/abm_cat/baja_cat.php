<?php
session_start();

if(!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin'){
    header("Location: ../../index.php");
    exit();
}

include_once("../../components/config/config.php");

// la categoria 1 es la generica, no se puede borrar porque ahi paso los productos
if(isset($_GET['id']) && intval($_GET['id']) > 1){
    $baja = intval($_GET['id']);

    $update = "UPDATE productos SET fk_categoria = 1 WHERE fk_categoria = $baja";
    mysqli_query($con, $update);

    $consulta = "DELETE FROM categorias WHERE id_categoria = $baja";
    mysqli_query($con, $consulta);

    header("Location: ../categoria.php?baja=ok");
    exit();
}

header("Location: ../categoria.php");
exit();
?>
