<?php
include_once("../components/config/config.php");
include_once("../components/permisos.php");
include_once("../components/paginacion.php");

// panel de productos del admin: solo pueden entrar los administradores
requiere_admin();

// paginacion: 10 productos por pagina
$por_pagina = 10;
$total_productos = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS cantidad FROM productos"))['cantidad'];
$total_paginas = total_paginas($total_productos, $por_pagina);
$pagina = pagina_actual();
if ($total_paginas > 0 && $pagina > $total_paginas) {
    $pagina = $total_paginas;
}
$desde = ($pagina - 1) * $por_pagina;

// traigo TODOS los productos (de cualquier usuario) con su categoria y quien lo publico
$consulta = "SELECT p.*, c.categoria, u.nombre AS vendedor
    FROM productos p
    JOIN categorias c ON c.id_categoria = p.fk_categoria
    JOIN usuarios u ON u.id_usuario = p.fk_usuario
    ORDER BY p.id_producto DESC
    LIMIT $por_pagina OFFSET $desde";
$productos = mysqli_query($con, $consulta);

// ids de los tipos de archivo que son imagenes, para mostrar una miniatura
$tipos_img = [];
$resp_tipos = mysqli_query($con, "SELECT id_tipo_archivo FROM tipos_archivos WHERE tipo IN ('JPG','JPEG','PNG')");
while ($row = mysqli_fetch_assoc($resp_tipos)) {
    $tipos_img[] = $row["id_tipo_archivo"];
}
$tipos_lista = implode(",", $tipos_img);

include_once("../components/header_admin.php");
?>
<main>
    <div class="container-fluid">

        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
            <h1 class="montserrat-bold mb-0">ABM - Productos</h1>
            <!-- el alta usa el mismo formulario que los usuarios (editor/publicar.php) -->
            <a href="../editor/publicar.php" class="btn btn-success">Agregar producto</a>
        </div>
        <p class="montserrat-regular mt-2">
            Acá están todos los productos de la tienda, sin importar quién los publicó.
            Podés modificarlos o eliminarlos.
        </p>

        <?php if (isset($_GET['baja']) && $_GET['baja'] === "ok"): ?>
            <div class="alert alert-success mt-2">Producto eliminado correctamente.</div>
        <?php endif; ?>
        <?php if (isset($_GET['baja']) && $_GET['baja'] === "no"): ?>
            <div class="alert alert-warning mt-2">No se puede eliminar ese producto porque tiene una compra o un envío en curso.</div>
        <?php endif; ?>
        <?php if (isset($_GET['modificacion']) && $_GET['modificacion'] === "ok"): ?>
            <div class="alert alert-success mt-2">Producto modificado correctamente.</div>
        <?php endif; ?>

        <?php if (mysqli_num_rows($productos) > 0): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Imagen</th>
                            <th>Nombre</th>
                            <th>Publicado por</th>
                            <th>Categoría</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($fila = mysqli_fetch_assoc($productos)): ?>
                            <?php
                            $id_producto = intval($fila['id_producto']);

                            // primera imagen del producto, o la imagen por defecto si no tiene
                            $res_img = mysqli_query($con, "SELECT nombre FROM archivos WHERE fk_producto = $id_producto AND fk_tipo_archivo IN ($tipos_lista) ORDER BY id_archivo ASC LIMIT 1");
                            $img = mysqli_fetch_assoc($res_img);
                            $imagen = $img ? $img['nombre'] : "no-image.png";
                            ?>
                            <tr>
                                <td><?= $id_producto ?></td>
                                <td>
                                    <img src="../prod_img/<?= htmlspecialchars($imagen) ?>" alt="<?= htmlspecialchars($fila['nombre']) ?>"
                                        style="width: 60px; height: 60px; object-fit: cover;" class="rounded">
                                </td>
                                <td><?= htmlspecialchars($fila['nombre']) ?></td>
                                <td><?= htmlspecialchars($fila['vendedor']) ?></td>
                                <td><?= htmlspecialchars($fila['categoria']) ?></td>
                                <td>$<?= number_format($fila['precio'], 2, ',', '.') ?></td>
                                <td><?= $fila['stock'] ?></td>
                                <td>
                                    <?php if ($fila['fk_estado_producto'] == 1): ?>
                                        <span class="text-success">Disponible</span>
                                    <?php else: ?>
                                        <span class="text-danger">Agotado</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-nowrap">
                                    <!-- modificar y borrar usan los mismos archivos que "Mis productos";
                                         permisos.php deja pasar al admin aunque el producto no sea suyo -->
                                    <a href="../editor/abm_prod/modificacion_prod.php?id=<?= $id_producto ?>" class="btn btn-warning btn-sm">Modificar</a>
                                    <form action="../editor/abm_prod/baja_prod.php" method="post" class="d-inline"
                                        onsubmit="return confirm('¿Seguro que querés eliminar este producto? Se borran también sus imágenes.')">
                                        <input type="hidden" name="id" value="<?= $id_producto ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <?php mostrar_paginacion($pagina, $total_paginas, "productos.php?"); ?>
        <?php else: ?>
            <div class="alert alert-warning mt-2">Todavía no hay productos cargados.</div>
        <?php endif; ?>
    </div>
</main>
<?php
include_once("../components/footer.php");
?>
