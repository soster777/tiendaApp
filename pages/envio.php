<?php
include_once("../components/header.php");
include_once("../components/config/config.php");

if (!isset($_SESSION['id_usuario'])) {
    print "<div class='alert alert-warning'>Debes iniciar sesión para continuar.</div>";
    include_once("../components/footer.php");
    exit;
}

if (!isset($_GET['id_transaccion'])) {
    print "<div class='alert alert-warning'>Transacción no especificada.</div>";
    include_once("../components/footer.php");
    exit;
}

$id_transaccion = intval($_GET['id_transaccion']);
$id_usuario     = intval($_SESSION['id_usuario']);

$consulta_trans = "SELECT * FROM transacciones WHERE id_transaccion = $id_transaccion";
$res_trans = mysqli_query($con, $consulta_trans);

if (!$res_trans || mysqli_num_rows($res_trans) == 0) {
    print "<div class='alert alert-danger'>Transacción no encontrada.</div>";
    include_once("../components/footer.php");
    exit;
}

$trans = mysqli_fetch_assoc($res_trans);

$consulta_mpu = "SELECT fk_usuario FROM metodos_pagos_usuarios WHERE id_metodo_pago_usuario = {$trans['fk_metodo_pago_usuario']}";
$res_mpu = mysqli_query($con, $consulta_mpu);
$mpu = mysqli_fetch_assoc($res_mpu);

if (!$mpu || $mpu['fk_usuario'] != $id_usuario) {
    print "<div class='alert alert-danger'>No tenés permiso para operar esta transacción.</div>";
    include_once("../components/footer.php");
    exit;
}

$id_producto = intval($trans['fk_producto']);
$consulta_prod = "SELECT * FROM productos WHERE id_producto = $id_producto";
$res_prod = mysqli_query($con, $consulta_prod);

if (!$res_prod || mysqli_num_rows($res_prod) == 0) {
    print "<div class='alert alert-danger'>Producto asociado no encontrado.</div>";
    include_once("../components/footer.php");
    exit;
}

$prod = mysqli_fetch_assoc($res_prod);

$producto = [
    "nombre"      => $prod['nombre'],
    "descripcion" => $prod['descripcion'],
    "precio"      => $prod['precio'],
    "stock"       => $prod['stock'],
    "id_producto" => $prod['id_producto'],
    "vendedor"    => $prod['fk_usuario'],
];

$consulta_tipos = "SELECT id_tipo_archivo FROM tipos_archivos WHERE tipo IN ('JPG','JPEG','PNG')";
$resp_tipos = mysqli_query($con, $consulta_tipos);

$tipos_img = [];
while ($row = mysqli_fetch_assoc($resp_tipos)) {
    $tipos_img[] = $row["id_tipo_archivo"];
}
$tipos_lista = count($tipos_img) ? implode(",", $tipos_img) : "0";

$consulta_img = "
    SELECT nombre 
    FROM archivos 
    WHERE fk_producto = {$producto['id_producto']}
    AND fk_tipo_archivo IN ($tipos_lista)
    ORDER BY id_archivo ASC 
    LIMIT 1
";

$resp_img = mysqli_query($con, $consulta_img);
$img = mysqli_fetch_assoc($resp_img);
$imagen = $img ? $img["nombre"] : "no-image.png";
?>

<main>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <h1 class="montserrat-bold">Seleccioná el Método de Envío</h1>
                <p class="montserrat-regular">Transacción Nº: <?= $id_transaccion; ?></p>

                <div class="row mt-3">
                    <div class="col-12 col-lg-6 mb-3">
                        <div class="card shadow-sm rounded-3 compra-card">
                            <div class="row g-0">
                                <div class="col-5 d-flex align-items-center justify-content-center p-2">
                                    <img src="../prod_img/<?= $imagen; ?>" 
                                         class="img-fluid rounded" 
                                         alt="<?= htmlspecialchars($producto['nombre']); ?>" 
                                         style="max-height: 200px; object-fit: contain;">
                                </div>
                                <div class="col-7">
                                    <div class="card-body">
                                        <h5 class="card-title">$<?= $producto['precio']; ?></h5>
                                        <p class="card-text"><?= htmlspecialchars($producto['descripcion']); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-6 mb-3">
                        <div class="card shadow-sm rounded-3 p-3 compra-card">
                            <form action="abm_envio/procesar_envio.php" method="post" class="formulario-usuario">
                                <input type="hidden" name="id_transaccion" value="<?= $id_transaccion; ?>">

                                <div class="mb-3">
                                    <label for="metodo_envio" class="form-label primero">Seleccioná el método de entrega:</label>
                                    <select class="form-select" id="metodo_envio" name="metodo_envio" required>
                                        <option value="">-- Elegir --</option>
                                        <option value="1">Retirar en el domicilio del vendedor</option>
                                        <option value="2">Envío a mi domicilio registrado</option>
                                    </select>
                                </div>

                                <button type="submit" class="btn w-100">Confirmar Envío</button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</main>

<?php include_once("../components/footer.php"); ?>