<?php
include_once("../components/config/config.php");
include_once("../components/permisos.php");

// el chequeo va antes del header para que la redireccion funcione
requiere_admin();

include_once("../components/header_admin.php");
?>
<main>
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <h1 class="montserrat-bold">Bienvenido a TiendApp - ADMIN</h1>
        <p class="montserrat-regular"> En este panel se pueden ver las categorías existentes, las cuales se pueden editar y borrar, agregar nuevas categorías y ver las sugerencias que dan los otros usuarios.</p>
      </div>
    </div>
    <div class="row justify-content-center g-3 mt-2">
      <div class="col-12 col-md-5 col-lg-4">
        <a href="categoria.php" class="btn btn-success w-100 py-3">Ver categorías</a>
      </div>
      <div class="col-12 col-md-5 col-lg-4">
        <a href="sugerencias.php" class="btn btn-success w-100 py-3">Ver sugerencias</a>
      </div>
    </div>
  </div>
</main>
<?php
include_once("../components/footer.php");
?>
