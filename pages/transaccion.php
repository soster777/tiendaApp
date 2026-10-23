<?php  
include_once("../components/config/config.php");
include_once("../components/permisos.php");

// los chequeos van antes del header, asi la redireccion funciona
$id_usuario = requiere_login("login.php");

if (!isset($_GET['id_producto'])) {
    header("Location: inicio.php");
    exit;
}

$id_producto = intval($_GET['id_producto']);

include_once("../components/header.php");

$check_trans = "
    SELECT t.* 
    FROM transacciones t
    INNER JOIN metodos_pagos_usuarios mpu ON t.fk_metodo_pago_usuario = mpu.id_metodo_pago_usuario
    WHERE t.fk_producto = $id_producto 
      AND t.fk_estado_transaccion = 1
      AND mpu.fk_usuario = $id_usuario
";
$res_trans = mysqli_query($con, $check_trans);

$trans_pendiente = $res_trans && mysqli_num_rows($res_trans) > 0 ? mysqli_fetch_assoc($res_trans) : null;

$res_producto = mysqli_query($con, "SELECT * FROM productos WHERE id_producto=$id_producto");
if (!$producto = mysqli_fetch_assoc($res_producto)) {
    print "<div class='alert alert-danger'>Producto no encontrado.</div>";
    include_once("../components/footer.php");
    exit;
}

$res_metodos_usuario = mysqli_query($con, "SELECT * FROM metodos_pagos_usuarios WHERE fk_usuario = $id_usuario");
?>

<br><main>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <h1 class="montserrat-bold">Comprar Producto: <?= htmlspecialchars($producto['nombre']); ?></h1>

                <div class="row mt-3">
                    <div class="col-12 col-lg-6 mb-3">
                        <div class="card shadow-sm rounded-3 compra-card">
                            <div class="row g-0">
                                <div class="col-5 d-flex align-items-center justify-content-center p-2">
                                    <?php
                                    $consulta_tipos = "SELECT id_tipo_archivo FROM tipos_archivos WHERE tipo IN ('JPG','JPEG','PNG')";
                                    $resp_tipos = mysqli_query($con, $consulta_tipos);

                                    $tipos_img = [];
                                    while ($row = mysqli_fetch_assoc($resp_tipos)) {
                                        $tipos_img[] = $row["id_tipo_archivo"];
                                    }
                                    $tipos_lista = count($tipos_img) > 0 ? implode(",", $tipos_img) : "0";

                                    $consulta_img = "SELECT nombre FROM archivos 
                                                     WHERE fk_producto = $id_producto 
                                                     AND fk_tipo_archivo IN ($tipos_lista) 
                                                     ORDER BY id_archivo ASC LIMIT 1";

                                    $resp_img = mysqli_query($con, $consulta_img);
                                    $img = mysqli_fetch_assoc($resp_img);
                                    $imagen = $img ? $img["nombre"] : "no-image.png";
                                    ?>
                                    <img src="../prod_img/<?= $imagen; ?>" 
                                         class="img-fluid rounded" 
                                         style="max-height: 200px; object-fit: contain;" 
                                         alt="<?= htmlspecialchars($producto['nombre']); ?>">
                                </div>
                                <div class="col-7">
                                    <div class="card-body">
                                        <h5 class="card-title">$<?= number_format($producto['precio'], 2, ',', '.'); ?></h5>
                                        <p class="card-text"><?= htmlspecialchars($producto['descripcion']); ?></p>
                                        <p class="card-text"><strong>Stock disponible:</strong> <?= $producto['stock']; ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php
                    // mensajes que vienen de transaccion_ok.php
                    $errores = [
                        "cantidad" => "La cantidad tiene que ser al menos 1.",
                        "stock" => "No hay suficiente stock disponible.",
                        "cuotas" => "Cantidad de cuotas inválida.",
                        "metodo" => "Elegí un método de pago válido.",
                        "propio" => "No podés comprar tu propio producto."
                    ];
                    if (isset($_GET['error']) && isset($errores[$_GET['error']])) {
                        print "<div class='col-12'><div class='alert alert-danger'>" . $errores[$_GET['error']] . "</div></div>";
                    }
                    if ($id_usuario == $producto['fk_usuario']) {
                        print "<div class='col-12 col-lg-6'><div class='alert alert-info'>Este producto es tuyo, no lo podés comprar.</div></div>";
                    } elseif ($producto['stock'] <= 0) {
                        print "<div class='col-12 col-lg-6'><div class='alert alert-warning'>Este producto está agotado.</div></div>";
                    }
                    ?>

                    <?php if ($id_usuario != $producto['fk_usuario'] && $producto['stock'] > 0): ?>
                        <div class="col-12 col-lg-6 mb-3">
                            <div class="card shadow-sm rounded-3 p-3 compra-card">
                                <form action="abm_transacciones/transaccion_ok.php" method="post" class="formulario-usuario">
                                    <input type="hidden" name="id_producto" value="<?= $producto['id_producto']; ?>">

                                    <div class="mb-3">
                                        <label for="cantidad" class="form-label primero">Cantidad</label>
                                        <input type="number" class="form-control" id="cantidad" name="cantidad"
                                               value="<?= $trans_pendiente['cantidad'] ?? 1; ?>"
                                               min="1" max="<?= $producto['stock']; ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="metodo_pago" class="form-label">Método de pago</label>
                                        <select class="form-select" id="metodo_pago" name="metodo_pago" required>
                                            <option value="">--Seleccione un método guardado--</option>
                                            <?php
                                            if ($res_metodos_usuario && mysqli_num_rows($res_metodos_usuario) > 0) {
                                                mysqli_data_seek($res_metodos_usuario, 0);
                                                while ($mpu = mysqli_fetch_assoc($res_metodos_usuario)) {
                                                    $id_guardado = intval($mpu['id_metodo_pago_usuario']);
                                                    $fk_metodo = intval($mpu['fk_metodo_pago']);

                                                    $res_nom = mysqli_query($con, "SELECT metodo_pago FROM metodos_pagos WHERE id_metodo_pago = $fk_metodo LIMIT 1");
                                                    $nomfila = $res_nom ? mysqli_fetch_assoc($res_nom) : null;
                                                    $nombre_metodo = $nomfila ? $nomfila['metodo_pago'] : "Método desconocido";

                                                    $selected = ($trans_pendiente['fk_metodo_pago_usuario'] ?? 0) == $id_guardado ? "selected" : "";

                                                    // muestro el nombre que le puso el usuario y los ultimos 4 numeros si es tarjeta
                                                    $detalle = $mpu['numero_tarjeta'] ? " terminada en " . htmlspecialchars(substr($mpu['numero_tarjeta'], -4)) : "";
                                                    print "<option value='$id_guardado' data-metodo='$fk_metodo' $selected>" . htmlspecialchars($mpu['nombre']) . " - $nombre_metodo$detalle</option>";
                                                }
                                            } else {
                                                print "<option value=''>No tenés métodos guardados. Agregá uno primero.</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <div class="mb-3" id="contenedor-cuotas" style="display:none;">
                                        <label for="cuotas" class="form-label">Cantidad de cuotas</label>
                                        <select class="form-select" id="cuotas" name="cuotas">
                                            <option value="1">1 cuota</option>
                                            <option value="2">2 cuotas</option>
                                            <option value="3">3 cuotas</option>
                                        </select>
                                    </div>

                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn flex-fill">Continuar</button>
                                        <?php if ($trans_pendiente): ?>
                                            <!-- el boton manda el formulario de abajo (por POST) -->
                                            <button type="submit" form="form-cancelar" class="btn btn-danger flex-fill">Cancelar compra</button>
                                        <?php endif; ?>
                                    </div>

                                </form>
                                <?php if ($trans_pendiente): ?>
                                    <form id="form-cancelar" action="abm_transacciones/cancelar_transaccion.php" method="post">
                                        <input type="hidden" name="id" value="<?= intval($trans_pendiente['id_transaccion']); ?>">
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
// el select solo existe si se muestra el formulario (no aparece si el producto es propio o esta agotado)
const selectMetodo = document.getElementById("metodo_pago");
if (selectMetodo) {
selectMetodo.addEventListener("change", function () {
    const selected = this.options[this.selectedIndex];
    const metodoReal = selected.getAttribute("data-metodo");

    let metodosTarjeta = [1, 2];

    const contenedor = document.getElementById("contenedor-cuotas");

    if (metodosTarjeta.includes(parseInt(metodoReal))) {
        contenedor.style.display = "block";
    } else {
        contenedor.style.display = "none";
    }
});
// si vuelve a una compra pendiente con tarjeta, muestro las cuotas de entrada
selectMetodo.dispatchEvent(new Event("change"));
}
</script>

<?php include_once("../components/footer.php"); ?>