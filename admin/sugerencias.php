<?php
include_once("../components/config/config.php");
include_once("../components/permisos.php");

requiere_admin();

include_once("../components/header_admin.php");

// traigo las sugerencias con el nombre del usuario y el estado (las pendientes primero)
$consulta = "SELECT s.*, u.nombre, u.mail, e.estado
             FROM sugerencias s
             JOIN usuarios u ON u.id_usuario = s.fk_usuario
             LEFT JOIN estados_sugerencias e ON e.id_estado_sugerencia = s.fk_estado_sugerencia
             ORDER BY s.fk_estado_sugerencia ASC, s.id_sugerencia DESC";
$respuesta = mysqli_query($con, $consulta);

$estados = mysqli_query($con, "SELECT * FROM estados_sugerencias");
$lista_estados = [];
while ($e = mysqli_fetch_assoc($estados)) {
    $lista_estados[] = $e;
}
?>
<main>
  <div class="container-fluid">
    <h1 class="montserrat-bold">Sugerencias de los usuarios</h1>

    <?php if (isset($_GET['estado']) && $_GET['estado'] === 'ok'): ?>
      <div class="alert alert-success mt-2">Se actualizó el estado de la sugerencia.</div>
    <?php endif; ?>

    <?php if (mysqli_num_rows($respuesta) == 0): ?>
      <div class="alert alert-info mt-3">Todavía no hay sugerencias.</div>
    <?php endif; ?>

    <div class="row g-3 mt-1">
      <?php while ($sug = mysqli_fetch_assoc($respuesta)): ?>
        <div class="col-12 col-lg-6">
          <div class="card shadow-sm h-100">
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($sug['nombre']) ?>
                <small class="text-muted">(<?= htmlspecialchars($sug['mail']) ?>)</small>
              </h5>
              <p class="card-text"><?= nl2br(htmlspecialchars($sug['sugerencia'])) ?></p>

              <?php if ($sug['archivo']): ?>
                <a href="../contact_imgs/<?= rawurlencode($sug['archivo']) ?>" target="_blank">
                  <img src="../contact_imgs/<?= rawurlencode($sug['archivo']) ?>" alt="Imagen adjunta" class="img-fluid rounded mb-3" style="max-height: 150px;">
                </a>
              <?php endif; ?>

              <form action="abm_sug/estado_sug.php" method="post" class="d-flex gap-2 align-items-center">
                <input type="hidden" name="id" value="<?= intval($sug['id_sugerencia']) ?>">
                <select name="estado" class="form-select form-select-sm w-auto">
                  <?php foreach ($lista_estados as $e): ?>
                    <option value="<?= intval($e['id_estado_sugerencia']) ?>" <?= $e['id_estado_sugerencia'] == $sug['fk_estado_sugerencia'] ? 'selected' : '' ?>>
                      <?= htmlspecialchars($e['estado']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <button type="submit" class="btn btn-success btn-sm">Guardar</button>
              </form>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  </div>
</main>
<?php
include_once("../components/footer.php");
?>
