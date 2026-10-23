<?php
include_once("../components/header.php");
include_once("../components/config/config.php");

$usuario = intval($_SESSION['id_usuario']);
?>

<br><main class="container">
    <h1 class="montserrat-bold text-center">Mis Compras</h1>

    <?php
    if (isset($_GET['recibido']) && $_GET['recibido'] === 'ok') {
        print "<div class='alert alert-success'>El envío se ha marcado como recibido correctamente.</div>";
    }
    if (isset($_GET['cancel']) && $_GET['cancel'] === 'ok') {
        print "<div class='alert alert-success'>El envío se ha cancelado correctamente y el stock se ha actualizado.</div>";
    }

    // Traemos las transacciones donde el usuario está involucrado a través de su método de pago
    $sql_trans = "SELECT * FROM transacciones WHERE fk_estado_transaccion IN (1,2) ORDER BY fecha DESC";
    $res_trans = mysqli_query($con, $sql_trans);

    $tiene_compras = false;

    print "<div class='table-responsive'>
            <table class='table table-striped table-hover mt-3'>
                <thead class='table-dark'>
                    <tr>
                        <th>#</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Total</th>
                        <th>Fecha de la compra</th>
                        <th>Estado compra</th>
                        <th>Estado envío</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>";

    while ($trans = mysqli_fetch_assoc($res_trans)) {

        $id_mpu = intval($trans['fk_metodo_pago_usuario']);
        // Validamos que la transacción pertenezca al usuario logueado
        $res_mpu = mysqli_query($con, "SELECT * FROM metodos_pagos_usuarios WHERE id_metodo_pago_usuario = $id_mpu AND fk_usuario = $usuario");
        
        if (!$res_mpu || mysqli_num_rows($res_mpu) == 0) continue;

        $tiene_compras = true;

        $id_producto = intval($trans['fk_producto']);
        $res_prod = mysqli_query($con, "SELECT * FROM productos WHERE id_producto = $id_producto");
        $prod = mysqli_fetch_assoc($res_prod);

        $nombre_producto = $prod ? $prod['nombre'] : "Producto eliminado";
        $precio_unitario = $prod ? $prod['precio'] : 0;

        $estado_compra = ($trans['fk_estado_transaccion'] == 1) ? "<span class='badge bg-warning text-dark'>Pendiente</span>" : "<span class='badge bg-success'>Completada</span>";

        $id_trans = intval($trans['id_transaccion']);
        $res_envio = mysqli_query($con, "SELECT * FROM envios WHERE fk_transaccion = $id_trans LIMIT 1");
        $envio = mysqli_fetch_assoc($res_envio);

        $estado_envio = "<span class='text-secondary'>Sin envío</span>";
        $acciones = "";

        // --- CAMBIO CLAVE AQUÍ ---
        // Si la compra está pendiente, lo mandamos a transaccion.php en lugar de producto.php
        if ($trans['fk_estado_transaccion'] == 1) {
            $acciones .= "<a href='transaccion.php?id_producto={$id_producto}' class='btn btn-primary btn-sm me-1'>Finalizar pago</a>";
        }

        if ($envio) {
            switch ($envio['fk_estado_envio']) {
                case 1:
                    $estado_envio = "<span class='text-warning fw-bold'>Pendiente retiro</span>";
                    $acciones .= "<a href='abm_envio/recepcion.php?id=$id_trans' class='btn btn-success btn-sm me-1'>Ya lo retiré</a>";
                    $acciones .= "<a href='abm_envio/cancelar_envio.php?id=$id_trans' class='btn btn-danger btn-sm'>Cancelar</a>";
                    break;
                case 2:
                    $estado_envio = "<span class='text-warning fw-bold'>Pendiente envío</span>";
                    $acciones .= "<a href='abm_envio/recepcion.php?id=$id_trans' class='btn btn-success btn-sm me-1'>Marcar recibido</a>";
                    $acciones .= "<a href='abm_envio/cancelar_envio.php?id=$id_trans' class='btn btn-danger btn-sm'>Cancelar</a>";
                    break;
                case 3:
                    $estado_envio = "<span class='text-success'>Retirado</span>";
                    break;
                case 4:
                    $estado_envio = "<span class='text-success'>Entregado</span>";
                    break;
            }
        }

        print "<tr>
                <td><strong>{$id_trans}</strong></td>
                <td>{$nombre_producto}</td>
                <td>{$trans['cantidad']}</td>
                <td>\${$trans['total']}</td>
                <td>{$trans['fecha']}</td>
                <td>$estado_compra</td>
                <td>$estado_envio</td>
                <td>$acciones</td>
              </tr>";
    }

    if (!$tiene_compras) {
        print "<tr><td colspan='8' class='text-center p-4'>No tenés compras registradas. <a href='inicio.php'>¡Ir a la tienda!</a></td></tr>";
    }

    print "</tbody></table></div>";
    ?>
</main>

<?php include_once("../components/footer.php"); ?>