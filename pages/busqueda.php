<?php
include_once("../components/header.php");
include_once("../components/config/config.php");
include_once("../components/paginacion.php");

// guardo lo que escribio tal cual: se usa en una consulta preparada y se escapa al mostrarlo
// (con mysqli_real_escape_string, buscar O'Brien mostraba O\'Brien)
$buscar = isset($_GET['buscar']) ? trim($_GET['buscar']) : "";

// paginacion: 8 productos por pagina
$por_pagina = 8;
$pagina = pagina_actual();
$patron = "%" . $buscar . "%"; // con la busqueda vacia queda "%%", que trae todos los productos

// primero cuento cuantos resultados hay en total, para saber cuantas paginas hacen falta
$stmt = mysqli_prepare($con, "SELECT COUNT(*) AS cantidad FROM productos WHERE nombre LIKE ?");
mysqli_stmt_bind_param($stmt, "s", $patron);
mysqli_stmt_execute($stmt);
$total_productos = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))['cantidad'];
$total_paginas = total_paginas($total_productos, $por_pagina);
if ($total_paginas > 0 && $pagina > $total_paginas) {
    $pagina = $total_paginas;
}
$desde = ($pagina - 1) * $por_pagina;

// despues traigo solo los de esta pagina
$stmt = mysqli_prepare($con, "SELECT * FROM productos WHERE nombre LIKE ? LIMIT ? OFFSET ?");
mysqli_stmt_bind_param($stmt, "sii", $patron, $por_pagina, $desde);
mysqli_stmt_execute($stmt);
$respuesta_prod = mysqli_stmt_get_result($stmt);

// para los links de la paginacion: urlencode deja el texto buscado listo para ir en la URL
$url_paginacion = "busqueda.php?buscar=" . urlencode($buscar) . "&";

$consulta_tipos = "SELECT id_tipo_archivo FROM tipos_archivos WHERE tipo IN ('JPG','JPEG','PNG')";
$resp_tipos = mysqli_query($con, $consulta_tipos);

$tipos_img = [];
while ($row = mysqli_fetch_assoc($resp_tipos)) {
    $tipos_img[] = $row["id_tipo_archivo"];
}
$tipos_lista = implode(",", $tipos_img);
?>

<main>
    <br>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="montserrat-bold">Búsqueda</h1>
            </div>
        </div>

        <section>
            <article class="mb-4">
                <div class="row align-items-center g-0 p-4" style="background-color: rgba(0, 0, 0, 0.2); border-radius: 8px;">
                    <div class="col-12">
                        <p class="montserrat-regular mb-0">
                            <?php if ($buscar != ""): ?>
                                Resultados correspondientes a la consulta: <strong><?= htmlspecialchars($buscar); ?></strong>
                            <?php else: ?>
                                Mostrando el catálogo completo de productos disponibles en <strong>TiendApp</strong>.
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </article>

            <div class="row justify-content-center">
                <?php
                if (mysqli_num_rows($respuesta_prod) > 0) {
                    while ($prod = mysqli_fetch_assoc($respuesta_prod)) {
                        $producto_id = $prod["id_producto"];

                        $consulta_img = "SELECT nombre FROM archivos WHERE fk_producto = $producto_id AND fk_tipo_archivo IN ($tipos_lista) ORDER BY id_archivo ASC LIMIT 1";
                        $resp_img = mysqli_query($con, $consulta_img);
                        $img = mysqli_fetch_assoc($resp_img);
                        $imagen = $img ? $img["nombre"] : "no-image.png";
                                // el nombre se escapa al mostrarlo y el precio se ve con formato ($189.999,00)
                                $nombre_html = htmlspecialchars($prod['nombre'], ENT_QUOTES);
                                $precio_txt = number_format($prod['precio'], 2, ',', '.');

                        echo "
                        <article class='col-xl-3 col-lg-4 col-md-6 col-12'>
                            <div class='card mb-4 w-100 shadow-sm'>
                                <img src='../prod_img/$imagen' class='card-img-top' alt='$nombre_html' style='height: 200px; object-fit: cover;'>
                                <div class='card-body text-center'>
                                    <h5 class='card-title'>$nombre_html</h5>
                                    <p class='card-text font-weight-bold'>\$$precio_txt</p>
                                    <button type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#modalProd$producto_id'>
                                        Ver producto
                                    </button>
                                </div>
                            </div>
                        </article>";
                        ?>
                        
                        <div class="modal fade" id="modalProd<?php echo $producto_id; ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title montserrat-bold"><?php echo htmlspecialchars($prod['nombre']); ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-start"> 
                                        <div class="row">
                                            <div class="col-md-6">
                                                <?php
                                                $sql_archivos = "SELECT nombre, fk_tipo_archivo FROM archivos WHERE fk_producto = $producto_id";
                                                $res_archivos = mysqli_query($con, $sql_archivos);
                                                if (mysqli_num_rows($res_archivos) > 1): ?>
                                                    <div id="carouselSearch<?php echo $producto_id; ?>" class="carousel slide" data-bs-ride="carousel">
                                                        <div class="carousel-inner">
                                                            <?php
                                                            $active = "active";
                                                            while ($arc = mysqli_fetch_assoc($res_archivos)) {
                                                                echo '<div class="carousel-item '.$active.'">';
                                                                if (in_array($arc['fk_tipo_archivo'], [1, 2, 3])) {
                                                                    echo "<img src='../prod_img/{$arc['nombre']}' class='d-block w-100' style='max-height:300px; object-fit:contain;'>";
                                                                } else {
                                                                    echo "<video class='d-block w-100' controls style='max-height:300px;'><source src='../prod_img/{$arc['nombre']}'></video>";
                                                                }
                                                                echo '</div>';
                                                                $active = "";
                                                            } ?>
                                                        </div>
                                                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselSearch<?php echo $producto_id; ?>" data-bs-slide="prev"><span class="carousel-control-prev-icon"></span></button>
                                                        <button class="carousel-control-next" type="button" data-bs-target="#carouselSearch<?php echo $producto_id; ?>" data-bs-slide="next"><span class="carousel-control-next-icon"></span></button>
                                                    </div>
                                                <?php else: ?>
                                                    <img src="../prod_img/<?php echo $imagen; ?>" class="img-fluid" style="max-height:300px; width:100%; object-fit:contain;">
                                                <?php endif; ?>
                                            </div>

                                            <div class="col-md-6">
                                                <h4 class="text-success">$<?php echo $precio_txt; ?></h4>
                                                <p><strong>Descripción:</strong><br><?php echo htmlspecialchars($prod['descripcion']); ?></p>
                                                <p><small>Stock: <?php echo $prod['stock']; ?></small></p>
                                                <hr>
                                                <?php
                                                if (isset($_SESSION['id_usuario'])) {
                                                    if ($_SESSION['id_usuario'] != $prod['fk_usuario']) {
                                                        echo ($prod['stock'] > 0) 
                                                            ? "<a href='transaccion.php?id_producto=$producto_id' class='btn btn-success w-100'>Comprar ahora</a>"
                                                            : "<button class='btn btn-danger w-100' disabled>Agotado</button>";
                                                    } else {
                                                        echo "<p class='text-muted'>Este es tu producto</p>";
                                                    }
                                                } else {
                                                    echo "<a href='login.php' class='btn btn-outline-primary w-100'>Inicia sesión para comprar</a>";
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo "<div class='col-12'><div class='alert alert-warning'>No se encontraron productos para: <strong>" . htmlspecialchars($buscar) . "</strong></div></div>";
                }
                ?>
            </div>
            <?php mostrar_paginacion($pagina, $total_paginas, $url_paginacion); ?>
        </section>
    </div>
</main>

<?php include_once("../components/footer.php"); ?>