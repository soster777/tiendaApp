<?php
include_once("../components/config/config.php");
include_once("../components/permisos.php");
include_once("../components/paginacion.php");

// solo pueden entrar los administradores (el chequeo va antes del header)
requiere_admin();

// paginacion: 10 usuarios por pagina
$por_pagina = 10;
$total_usuarios = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS cantidad FROM usuarios"))['cantidad'];
$total_paginas = total_paginas($total_usuarios, $por_pagina);
$pagina = pagina_actual();
if ($total_paginas > 0 && $pagina > $total_paginas) {
    $pagina = $total_paginas;
}
$desde = ($pagina - 1) * $por_pagina;

// traigo los usuarios con el nombre del rol (JOIN con la tabla roles)
$consulta = "SELECT u.id_usuario, u.nombre, u.mail, u.fk_rol, r.rol
    FROM usuarios u
    JOIN roles r ON r.id_rol = u.fk_rol
    ORDER BY u.id_usuario
    LIMIT $por_pagina OFFSET $desde";
$usuarios = mysqli_query($con, $consulta);

include_once("../components/header_admin.php");
?>
<main>
    <div class="container-fluid">

        <h1 class="montserrat-bold">Gestión de usuarios</h1>
        <p class="montserrat-regular">
            Desde acá podés darle permiso de administrador a los usuarios que todavía no lo tienen.
            El cambio se aplica la próxima vez que esa persona inicie sesión.
        </p>

        <?php if (isset($_GET['ok'])): ?>
            <div class="alert alert-success mt-2">El usuario ahora es administrador.</div>
        <?php endif; ?>
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger mt-2">No se pudo cambiar el permiso de ese usuario.</div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Mail</th>
                        <th>Rol</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($fila = mysqli_fetch_assoc($usuarios)): ?>
                        <tr>
                            <td><?= $fila['id_usuario'] ?></td>
                            <td><?= htmlspecialchars($fila['nombre']) ?></td>
                            <td><?= htmlspecialchars($fila['mail']) ?></td>
                            <td>
                                <?php if ($fila['fk_rol'] == 1): ?>
                                    <span class="badge bg-success"><?= htmlspecialchars($fila['rol']) ?></span>
                                <?php else: ?>
                                    <span class="badge bg-secondary"><?= htmlspecialchars($fila['rol']) ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($fila['fk_rol'] == 1): ?>
                                    <span class="text-muted">Ya es administrador</span>
                                <?php else: ?>
                                    <!-- el cambio va por POST, asi un link suelto no puede dar permisos -->
                                    <form action="abm_usuarios/hacer_admin.php" method="post" class="d-inline"
                                        onsubmit="return confirm('¿Seguro que querés darle permiso de administrador a este usuario?')">
                                        <input type="hidden" name="id" value="<?= intval($fila['id_usuario']) ?>">
                                        <button type="submit" class="btn btn-warning btn-sm">Hacer administrador</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <?php mostrar_paginacion($pagina, $total_paginas, "usuarios.php?"); ?>
    </div>
</main>
<?php
include_once("../components/footer.php");
?>
