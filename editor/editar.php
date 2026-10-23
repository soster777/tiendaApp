<?php
include_once("../components/header.php");
include_once("../components/config/config.php");
include_once("../components/permisos.php");

$usuario = requiere_login("../pages/login.php");
?>

<br> <main class="container">

    <div class="row">
        <div class="col-12">
            <h1 class="montserrat-bold">Mis Productos</h1>
        </div>
    </div>

    <?php
    if (isset($_GET['baja']) && $_GET['baja'] === "ok") {
        print "<div class='alert alert-success' role='alert'>Producto eliminado correctamente 🗑️</div>";
    }
    ?>
    <?php
    if (isset($_GET['modificacion']) && $_GET['modificacion'] === "ok") {
        print "<div class='alert alert-success' role='alert'>
        Producto modificado correctamente 💾
    </div>";
    }
    ?>

    <div class="row">
        <div class="col-12">
            <?php
            $consulta_productos = "SELECT * FROM productos WHERE fk_usuario = '$usuario'";
            $respuesta_productos = mysqli_query($con, $consulta_productos);

            if (mysqli_num_rows($respuesta_productos) > 0) {

                print "
                <div class='row'>
                    <div class='col-12'>
                        <table class='table table-striped'>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nombre</th>
                                    <th>Categoría</th>
                                    <th>Precio</th>
                                    <th>Stock</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                ";

                while ($fila = mysqli_fetch_array($respuesta_productos)) {
                    $id_categoria = $fila['fk_categoria'];

                    $consulta_categoria = "SELECT categoria FROM categorias WHERE id_categoria = '$id_categoria'";
                    $res_categoria = mysqli_query($con, $consulta_categoria);
                    $categoria = mysqli_fetch_array($res_categoria)['categoria'];

                    if ($fila['fk_estado_producto'] == 1) {
                        $estado = "<span class='text-success'>Disponible</span>";
                    } else {
                        $estado = "<span class='text-danger'>Agotado</span>";
                    }

                    print "
                    <tr>
                        <td class='primera_col'>{$fila['id_producto']}</td>
                        <td>{$fila['nombre']}</td>
                        <td>$categoria</td>
                        <td>{$fila['precio']}</td>
                        <td>{$fila['stock']}</td>
                        <td>$estado</td>
                        <td>
                            <a href='abm_prod/modificacion_prod.php?id={$fila['id_producto']}' class='btn btn-warning btn-sm'>Modificar</a>
                            <a href='abm_prod/baja_prod.php?id={$fila['id_producto']}' class='btn btn-danger btn-sm' onclick=\"return confirm('¿Estás seguro de que deseas eliminar este producto? Esta acción eliminará también todas sus imágenes?')\">Eliminar</a>
                        </td>
                    </tr>
                    ";
                }

                print "
                            </tbody>
                        </table>
                    </div>
                </div>
                ";
            } else {
                print "
                <div class='row'>
                    <div class='col-12'>
                        <div class='alert alert-warning'>
                            No has publicado ningún producto aún.
                        </div>
                    </div>
                </div>
                ";
            }
            ?>

        </div>
    </div>

</main>

<?php
include_once("../components/footer.php");
?>