<?php
include_once("../components/header_admin.php");
?>
<main>
  <div class="container-fluid">
    <?php
    include_once("../components/config/config.php");
    if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
      header("Location: ../index.php");
      exit();
    }
    ?>
    <div class="row">
      <div class="col-12">
        <h1 class="montserrat-bold">Bienvenido a TiendApp - ADMIN</h1>
        <p class="montserrat-regular"> En este panel se pueden ver las categorías existentes, las cuales se pueden editar y borrar, agregar nuevas categorías y ver las sugerencias que dan los otros usuarios.</p>
      </div>
    </div>
    <div class="row justify-content-center">
      <div class="col-12 col-md-6 col-lg-4">
        <figure>
          <img src="../imgs/admin.png" alt="admin" class="img-fluid">
        </figure>
      </div>
    </div>
  </div>
</main>
<?php
include_once("../components/footer.php");
?>