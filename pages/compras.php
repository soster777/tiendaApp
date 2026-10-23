<?php
include_once("../components/config/config.php");
include_once("../components/permisos.php");

// el chequeo va antes del header: si no hay sesion lo mando al login (antes tiraba "Undefined array key")
$usuario = requiere_login("login.php");

include_once("../components/header.php");

// traigo solo las compras del usuario, con el nombre del producto y el estado del envio
// (antes traia las compras de toda la tienda y filtraba en PHP)
$stmt = mysqli_prepare($con, "
    SELECT t.*, p.nombre AS nombre_producto, e.fk_estado_envio
    FROM transacciones t
    JOIN metodos_pagos_usuarios mpu ON mpu.id_metodo_pago_usuario = t.fk_metodo_pago_usuario
    LEFT JOIN productos p ON p.id_producto = t.fk_producto
    LEFT JOIN envios e ON e.fk_transaccion = t.id_transaccion
    WHERE mpu.fk_usuario = ? AND t.fk_estado_transaccion IN (1, 2)
    ORDER BY t.fecha DESC
");
mysqli_stmt_bind_param($stmt, "i", $usuario);
mysqli_stmt_execute($stmt);
$res_trans = mysqli_stmt_get_result($stmt);

// arma un botoncito que manda el id por POST (para no cambiar datos con un link)
function boton_post($accion, $id, $texto, $clase)
{
    return "<form action='$accion' method='post' class='d-inline'>
                <input type='hidden' name='id' value='$id'>
                <button type='submit' class='btn $clase btn-sm me-1'>$texto</button>
            </form>";
}
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
    if (isset($_GET['cancel']) && $_GET['cancel'] === 'no') {
        print "<div class='alert alert-warning'>Esa compra ya no se puede cancelar.</div>";
    }
    if (isset($_GET['envio']) && $_GET['envio'] === 'no') {
        print "<div class='alert alert-warning'>Esa compra ya fue procesada.</div>";
    }

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

    if (mysqli_num_rows($res_trans) == 0) {
        print "<tr><td colspan='8' class='text-center p-4'>No tenés compras registradas. <a href='inicio.php'>¡Ir a la tienda!</a></td></tr>";
    }

    while ($trans = mysqli_fetch_assoc($res_trans)) {

        $id_trans = intval($trans['id_transaccion']);
        $id_producto = intval($trans['fk_producto']);
        $nombre_producto = $trans['nombre_producto'] !== null ? htmlspecialchars($trans['nombre_producto']) : "Producto eliminado";

        $estado_compra = ($trans['fk_estado_transaccion'] == 1) ? "<span class='badge bg-warning text-dark'>Pendiente</span>" : "<span class='badge bg-success'>Completada</span>";

        $estado_envio = "<span class='text-secondary'>Sin envío</span>";
        $acciones = "";

        // si la compra esta pendiente lo mando a terminar el pago
        if ($trans['fk_estado_transaccion'] == 1) {
            $acciones .= "<a href='transaccion.php?id_producto={$id_producto}' class='btn btn-primary btn-sm me-1'>Finalizar pago</a>";
        }

        switch ($trans['fk_estado_envio']) {
            case 1:
                $estado_envio = "<span class='text-warning fw-bold'>Pendiente retiro</span>";
                $acciones .= boton_post("abm_envio/recepcion.php", $id_trans, "Ya lo retiré", "btn-success");
                $acciones .= boton_post("abm_envio/cancelar_envio.php", $id_trans, "Cancelar", "btn-danger");
                break;
            case 2:
                $estado_envio = "<span class='text-warning fw-bold'>Pendiente envío</span>";
                $acciones .= boton_post("abm_envio/recepcion.php", $id_trans, "Marcar recibido", "btn-success");
                $acciones .= boton_post("abm_envio/cancelar_envio.php", $id_trans, "Cancelar", "btn-danger");
                break;
            case 3:
                $estado_envio = "<span class='text-success'>Retirado</span>";
                break;
            case 4:
                $estado_envio = "<span class='text-success'>Entregado</span>";
                break;
        }

        // number_format: 189999 se ve como 189.999,00
        $total = number_format($trans['total'], 2, ',', '.');
        $fecha = date("d/m/Y", strtotime($trans["fecha"]));

        print "<tr>
                <td><strong>{$id_trans}</strong></td>
                <td>{$nombre_producto}</td>
                <td>{$trans['cantidad']}</td>
                <td>\${$total}</td>
                <td>{$fecha}</td>
                <td>$estado_compra</td>
                <td>$estado_envio</td>
                <td>$acciones</td>
              </tr>";
    }

    print "</tbody></table></div>";
    ?>
</main>

<?php include_once("../components/footer.php"); ?>
