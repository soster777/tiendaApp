<?php
include_once("../components/config/config.php");
include_once("../components/permisos.php");

// chequeos antes del header para poder redirigir
$id_usuario = requiere_login("login.php");

if (!isset($_GET['id_transaccion'])) {
    header("Location: compras.php");
    exit;
}

$id_transaccion = intval($_GET['id_transaccion']);
$trans = transaccion_del_usuario($con, $id_transaccion, $id_usuario);

// tiene que ser del usuario y seguir pendiente (si ya se eligio el envio, no se vuelve a elegir)
if (!$trans || intval($trans['fk_estado_transaccion']) !== 1) {
    header("Location: compras.php?envio=no");
    exit;
}

include_once("../components/header.php");

$stmt = mysqli_prepare($con, "SELECT ubicacion FROM usuarios WHERE id_usuario = ?");
mysqli_stmt_bind_param($stmt, "i", $id_usuario);
mysqli_stmt_execute($stmt);
$datos_usuario = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
$ubicacion = trim((string) $datos_usuario['ubicacion']);

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
                                        <h5 class="card-title">$<?= number_format($trans['total'], 2, ',', '.'); ?></h5>
                                        <p class="card-text"><strong><?= htmlspecialchars($producto['nombre']); ?></strong> x <?= intval($trans['cantidad']); ?></p>
                                        <p class="card-text"><?= htmlspecialchars($producto['descripcion']); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php
                    if (isset($_GET['error']) && $_GET['error'] === 'ubicacion') {
                        print "<div class='col-12'><div class='alert alert-warning'>Para el envío a domicilio primero cargá tu ubicación en <a href='perfil.php'>Mi perfil</a>.</div></div>";
                    }
                    if (isset($_GET['error']) && $_GET['error'] === 'stock') {
                        print "<div class='col-12'><div class='alert alert-danger'>Mientras comprabas se agotó el stock de este producto. Probá con una cantidad menor.</div></div>";
                    }
                    ?>
                    <div class="col-12 col-lg-6 mb-3">
                        <div class="card shadow-sm rounded-3 p-3 compra-card">
                            <form action="abm_envio/procesar_envio.php" method="post" class="formulario-usuario">
                                <input type="hidden" name="id_transaccion" value="<?= $id_transaccion; ?>">

                                <div class="mb-3">
                                    <label for="metodo_envio" class="form-label primero">Seleccioná el método de entrega:</label>
                                    <select class="form-select" id="metodo_envio" name="metodo_envio" required>
                                        <option value="">-- Elegir --</option>
                                        <option value="1">Retirar en el domicilio del vendedor</option>
                                        <?php if ($ubicacion !== ''): ?>
                                            <option value="2">Envío a mi domicilio registrado (<?= htmlspecialchars($ubicacion); ?>)</option>
                                        <?php else: ?>
                                            <option value="2" disabled>Envío a domicilio (cargá tu ubicación en tu perfil)</option>
                                        <?php endif; ?>
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