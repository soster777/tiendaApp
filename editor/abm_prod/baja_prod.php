<?php
include_once("../../components/config/config.php");
include_once("../../components/permisos.php");

$id_usuario = requiere_login("../../pages/login.php");

if(isset($_GET['id'])){
    
    $id = intval($_GET['id']);

    if (!es_duenio_producto($con, $id, $id_usuario)) {
        header("Location: ../editar.php");
        exit();
    }

    $consulta_archivos = "SELECT nombre FROM archivos WHERE fk_producto = $id";
    $resultado = mysqli_query($con, $consulta_archivos);

    while($fila = mysqli_fetch_assoc($resultado)){
        $ruta = "../../prod_img/" . $fila['nombre'];

        if(file_exists($ruta)){
            unlink($ruta); 
        }
    }

    $delete_archivos = "DELETE FROM archivos WHERE fk_producto = $id";
    mysqli_query($con, $delete_archivos);

    $delete_producto = "DELETE FROM productos WHERE id_producto = $id";
    mysqli_query($con, $delete_producto);

    header("Location: ../editar.php?baja=ok");
    exit();
}
?>