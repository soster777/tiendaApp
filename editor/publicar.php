<?php
include_once("../components/permisos.php");

// el chequeo va antes del header para que la redireccion funcione
requiere_login("../pages/login.php");

include_once("../components/header.php");
?>

<br> <main>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">

                <h1 class="montserrat-bold">Publicar producto</h1>

                <br>

                <?php
                if (isset($_GET['dato'])) {
                    print "
                    <div class='alert alert-success mt-3' role='alert'>
                        Producto cargado exitosamente 🛒 💕
                    </div>
                    ";
                }

                if (isset($_GET['error'])) {

                    $error = htmlspecialchars($_GET['error']);

                    if ($error === 'noimg') {
                        $error = 'Debes subir al menos una imagen en formato JPG, JPEG o PNG.';
                    }

                    if ($error === 'mainimg') {
                        $error = 'Hubo un problema al subir la imagen principal. Inténtalo nuevamente.';
                    }

                    print "
                    <div class='alert alert-danger mt-3' role='alert'>
                        $error
                    </div>
                    ";
                }
                ?>

                <form action="abm_prod/alta_prod.php" method="post" enctype="multipart/form-data" class="formulario-usuario">

                    <div class="row">
                        <div class="col-12">
                            <label for="nombre" class="form-label">Nombre del Producto</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <label for="categoria" class="form-label">Categoría</label>
                            <select name="categoria" id="categoria" class="form-select" required>
                                <option value="">--Seleccione--</option>
                                <?php
                                include_once("../components/config/config.php");
                                $consulta = "SELECT * FROM categorias";
                                $respuesta = mysqli_query($con, $consulta);
                                while ($fila = mysqli_fetch_array($respuesta)) {
                                    print "<option value='$fila[id_categoria]'>$fila[categoria]</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea name="descripcion" id="descripcion" class="form-control" rows="4"></textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6 mb-3 mb-md-0">
                            <label for="stock" class="form-label">Stock</label>
                            <input type="number" class="form-control" id="stock" name="stock" min="1" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="precio" class="form-label">Precio</label>
                            <input type="number" step="0.01" class="form-control" id="precio" name="precio" min="1" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <label for="imagen" class="form-label">Imagen del Producto</label>
                            <input type="file" name="media[]" id="imagen" class="form-control" multiple required>
                            <div id="preview" style="display:flex; gap:10px; flex-wrap: wrap; margin-top:10px;"></div>
                        </div>
                    </div>

                    <input type="hidden" name="usuario" value="<?php print $_SESSION['id_usuario']; ?>">

                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn w-100 py-2">Cargar Producto</button>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>

    <br>
</main>

<script src="../js/media_preview.js"></script>

<?php
include_once("../components/footer.php");
?>