<?php
include_once("../components/header.php");
include_once("../components/config/config.php");
include_once("../components/paginacion.php");
?>

<main>
    <br> <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h1 class="montserrat-bold">Bienvenido a TiendApp</h1>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-12 col-md-8">
                <?php
                if (isset($_GET['envio']) && $_GET['envio'] == 'ok') print "<div class='alert alert-success mt-2'>Compra realizada con éxito</div>";
                if (isset($_GET['cancel']) && $_GET['cancel'] == 'ok') print "<div class='alert alert-success mt-2'>Compra cancelada con éxito</div>";
                ?>
            </div>
        </div>

        <?php
        // paginacion: 9 productos por pagina (3 filas de 3)
        $por_pagina = 9;
        $total_productos = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS cantidad FROM productos"))['cantidad'];
        $total_paginas = total_paginas($total_productos, $por_pagina);
        $pagina = pagina_actual();
        // si piden una pagina que no existe (?pagina=50) muestro la ultima
        if ($total_paginas > 0 && $pagina > $total_paginas) {
            $pagina = $total_paginas;
        }
        $desde = ($pagina - 1) * $por_pagina;

        $consulta_prod = "SELECT * FROM productos LIMIT $por_pagina OFFSET $desde";
        $respuesta_prod = mysqli_query($con, $consulta_prod);

        $consulta_tipos = "SELECT id_tipo_archivo FROM tipos_archivos WHERE tipo IN ('JPG','JPEG','PNG')";
        $resp_tipos = mysqli_query($con, $consulta_tipos);
        $tipos_img = [];
        while ($row = mysqli_fetch_assoc($resp_tipos)) { $tipos_img[] = $row["id_tipo_archivo"]; }
        $tipos_lista = implode(",", $tipos_img);
        ?>

        <section>
            <div class="row">
                <div class="col-12">
                    <h2 class="montserrat-bold mb-4">Todos los productos</h2>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-10"> 
                    <div class="row">
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

                                // col-lg-4 dentro de col-10 para 3 productos por fila
                                print "
                                <article class='col-lg-4 col-md-6 col-12'>
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
                                        <div class="modal-content text-start">
                                            <div class="modal-header">
                                                <h5 class="modal-title montserrat-bold"><?php echo htmlspecialchars($prod['nombre']); ?></h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <?php
                                                        $sql_archivos = "SELECT nombre, fk_tipo_archivo FROM archivos WHERE fk_producto = $producto_id";
                                                        $res_archivos = mysqli_query($con, $sql_archivos);
                                                        if (mysqli_num_rows($res_archivos) > 1): ?>
                                                            <div id="carousel<?php echo $producto_id; ?>" class="carousel slide" data-bs-ride="carousel">
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
                                                                <button class="carousel-control-prev" type="button" data-bs-target="#carousel<?php echo $producto_id; ?>" data-bs-slide="prev"><span class="carousel-control-prev-icon"></span></button>
                                                                <button class="carousel-control-next" type="button" data-bs-target="#carousel<?php echo $producto_id; ?>" data-bs-slide="next"><span class="carousel-control-next-icon"></span></button>
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
                                                            echo "<a href='login.php' class='btn btn-outline-primary w-100'>Inicia sesión</a>";
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
                            print "<div class='alert alert-warning'>No existen productos en este sitio</div>";
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php mostrar_paginacion($pagina, $total_paginas, "inicio.php?"); ?>
        </section>
    </div>
</main>

<?php include_once("../components/footer.php"); ?>