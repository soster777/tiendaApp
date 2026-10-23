<?php
include_once("../components/config/config.php");
include_once("../components/permisos.php");

requiere_admin();

include_once("../components/header_admin.php");
?>
<main>
  <div class="container-fluid">

    <h1 class="montserrat-bold">ABM - Categorías</h1>

    <?php if (isset($_GET['msn'])): ?>
      <div class="alert alert-success mt-2">
        La categoría se cargó correctamente.
      </div>
    <?php endif; ?>
    <?php if (isset($_GET['baja'])): ?>
      <div class="alert alert-success mt-2">
        La categoría se eliminó correctamente.
      </div>
    <?php endif; ?>
    <?php if (isset($_GET['mod'])): ?>
      <div class="alert alert-success mt-2">
        La categoría se modificó correctamente.
      </div>
    <?php endif; ?>
    <?php if (isset($_GET['error']) && $_GET['error'] === 'protegida'): ?>
      <div class="alert alert-danger mt-2">
        La categoría general no se puede modificar ni eliminar.
      </div>
    <?php endif; ?>

    <div class="row">
      <section class="col-12 col-lg-6">
        <h2 class="montserrat-bold">Agregar nueva categoría</h2>
        <form action="abm_cat/alta_cat.php" method="post" class="formulario-usuario">
          <div class="row mb-2">
            <div class="col-md-8">
              <label for="cat" class="form-label primero">Nueva Categoría</label>
              <input type="text" name="cat" id="cat" class="form-control" required>
            </div>
            <div class="col-md-4 d-flex align-items-end">
              <input type="submit" value="Cargar categoría" class="btn btn-success w-100">
            </div>
          </div>
        </form>
      </section>

      <section class="col-12 col-lg-6">
        <h2 class="montserrat-bold">Categorías existentes</h2>
        <div class="table-responsive">
          <table class="table table-striped table-hover">
            <thead>
              <tr>
                <th>#</th>
                <th>Categoría</th>
                <th colspan="2">Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $consulta = "SELECT * FROM categorias";
              $respuesta = mysqli_query($con, $consulta);
              while ($fila = mysqli_fetch_array($respuesta)):
              ?>
                <tr>
                  <td><?= $fila['id_categoria'] ?></td>
                  <td><?= htmlspecialchars($fila['categoria']) ?></td>

                  <?php if ($fila['id_categoria'] == 1): ?>
                    <td class="text-center" colspan="2">
                      <span class="badge bg-secondary">Categoría protegida</span>
                    </td>
                  <?php else: ?>
                    <td>
                      <a href="abm_cat/mod_cat.php?id=<?= $fila['id_categoria'] ?>" class="btn btn-warning btn-sm">Modificar</a>
                    </td>
                    <td>
                      <form action="abm_cat/baja_cat.php" method="post"
                        onsubmit="return confirm('¿Seguro que querés eliminar esta categoría?')">
                        <input type="hidden" name="id" value="<?= intval($fila['id_categoria']) ?>">
                        <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                      </form>
                    </td>
                  <?php endif; ?>

                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </section>
    </div>
  </div>
</main>
<?php
include_once("../components/footer.php");
?>