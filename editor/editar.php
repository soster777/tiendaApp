<?php
include_once("../components/config/config.php");
include_once("../components/permisos.php");

// el chequeo va antes del header para que la redireccion funcione
$usuario = requiere_login("../pages/login.php");

include_once("../components/header.php");
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
    if (isset($_GET['baja']) && $_GET['baja'] === "no") {
        print "<div class='alert alert-warning'>No podés eliminar ese producto porque tiene una compra o un envío en curso.</div>";
    }
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
                        <td>" . htmlspecialchars($fila['nombre']) . "</td>
                        <td>" . htmlspecialchars($categoria) . "</td>
                        <td>$" . number_format($fila['precio'], 2, ',', '.') . "</td>
                        <td>{$fila['stock']}</td>
                        <td>$estado</td>
                        <td>
                            <a href='abm_prod/modificacion_prod.php?id={$fila['id_producto']}' class='btn btn-warning btn-sm'>Modificar</a>
                            <form action='abm_prod/baja_prod.php' method='post' class='d-inline' onsubmit=\"return confirm('¿Estás seguro de que deseas eliminar este producto? Esta acción eliminará también todas sus imágenes.')\">
                                <input type='hidden' name='id' value='{$fila['id_producto']}'>
                                <button type='submit' class='btn btn-danger btn-sm'>Eliminar</button>
                            </form>
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