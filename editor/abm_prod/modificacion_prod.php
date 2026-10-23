<?php
include_once("../../components/config/config.php");
include_once("../../components/permisos.php");

// los chequeos van antes del header para que las redirecciones funcionen
$id_usuario = requiere_login("../../pages/login.php");

if (!isset($_GET['id'])) {
    header("Location: ../editar.php");
    exit();
}

$id_producto = intval($_GET['id']);

if (!es_duenio_producto($con, $id_producto, $id_usuario)) {
    header("Location: ../editar.php");
    exit();
}

$consulta = "SELECT * FROM productos WHERE id_producto = $id_producto";
$res = mysqli_query($con, $consulta);
$producto = mysqli_fetch_assoc($res);

$consulta_archivos = "SELECT * FROM archivos WHERE fk_producto = $id_producto";
$archivos = mysqli_query($con, $consulta_archivos);

$consulta_cat = "SELECT * FROM categorias";
$categorias = mysqli_query($con, $consulta_cat);

include_once("../../components/header.php");
?>

<main>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">

                <h1 class="montserrat-bold">Modificar producto</h1>

                <?php
                if (isset($_GET['ok'])) {
                    print "
                <div class='alert alert-success mt-3' role='alert'>
                    Producto actualizado correctamente 💾
                </div>
                ";
                }

                if (isset($_GET['borrado'])) {
                    print "
                <div class='alert alert-warning mt-3' role='alert'>
                    Archivo eliminado correctamente 🗑️
                </div>
                ";
                }
                ?>

                <?php
                if (isset($_GET['error'])) {
                    $error_msg = urldecode($_GET['error']);
                    $error_msg = htmlspecialchars($error_msg);

                    if ($error_msg === 'no_image') {
                        $error_msg = "Debe subir al menos una imagen (jpg, jpeg o png) ⚠️";
                    } elseif ($error_msg === 'ultima_imagen') {
                        $error_msg = "No podés borrar la última imagen: el producto tiene que tener al menos una foto ⚠️";
                    } elseif ($error_msg === 'datos') {
                        $error_msg = "Revisá los datos: el nombre es obligatorio, el precio tiene que ser mayor a 0 y el stock no puede ser negativo ⚠️";
                    } elseif ($error_msg === 'no_file') {
                        $error_msg = "Debe subir al menos un archivo válido ⚠️";
                    }

                    print "
                    <div class='alert alert-danger mt-3' role='alert'>
                        $error_msg
                    </div>
                    ";
                }
                ?>

                <form action="modificacion_prod_ok.php"
                    method="post"
                    enctype="multipart/form-data"
                    class="formulario-usuario">

                    <input type="hidden" name="id_producto" value="<?php print $id_producto; ?>">

                    <div class="row">
                        <div class="col-12">
                            <label class="form-label primero">Nombre del Producto</label>
                            <input type="text" class="form-control" name="nombre"
                                value="<?php print htmlspecialchars($producto['nombre']); ?>" required>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <label class="form-label">Categoría</label>
                            <select name="categoria" class="form-select" required>
                                <option value="">--Seleccione--</option>

                                <?php while ($c = mysqli_fetch_assoc($categorias)):
                                    $sel = ($c['id_categoria'] == $producto['fk_categoria']) ? "selected" : "";
                                ?>
                                    <option value="<?php print $c['id_categoria']; ?>" <?php print $sel; ?>>
                                        <?php print htmlspecialchars($c['categoria']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <label class="form-label">Descripción</label>
                            <textarea name="descripcion" class="form-control" rows="3"><?php print htmlspecialchars($producto['descripcion']); ?></textarea>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-6">
                            <label class="form-label">Precio</label>
                            <input type="number" class="form-control" step="0.01" name="precio"
                                value="<?php print $producto['precio']; ?>" min="1" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Stock</label>
                            <input type="number" class="form-control" name="stock"
                                value="<?php print $producto['stock']; ?>" required>
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="form-label">Archivos actuales</label>
                        <div style="display:flex; gap:15px; flex-wrap:wrap;">

                            <?php while ($a = mysqli_fetch_assoc($archivos)): ?>
                                <div style="position:relative; display:inline-block;">

                                    <?php
                                    $extension = strtolower(pathinfo($a['nombre'], PATHINFO_EXTENSION));
                                    $ruta = "../../prod_img/" . $a['nombre'];

                                    $imagenes = ['jpg', 'jpeg', 'png'];
                                    $videos = ['mp4', 'mov', 'mpeg', 'avi'];

                                    if (in_array($extension, $imagenes)) {
                                        print "<img src='$ruta' class='img-fluid rounded border' style='width:100px; height:100px; object-fit:cover;'>";
                                    } elseif (in_array($extension, $videos)) {
                                        print "<video src='$ruta' class='rounded border' style='width:100px; height:100px; object-fit:cover;' controls></video>";
                                    } else {
                                        print "<div class='text-danger'>Formato no soportado</div>";
                                    }
                                    ?>

                                    <!-- el boton manda el formulario por POST a eliminar_archivo.php (formaction) -->
                                    <button type="submit" formaction="eliminar_archivo.php" formnovalidate
                                        name="id_archivo" value="<?php print intval($a['id_archivo']); ?>"
                                        onclick="return confirm('¿Seguro que deseas eliminar este archivo?')"
                                        style="
                                        border:none;
                                        position:absolute;
                                        top:0; right:0;
                                        background:red; 
                                        color:white;
                                        border-radius:50%;
                                        width:22px; height:22px;
                                        text-align:center;
                                        line-height:22px;
                                        text-decoration:none;
                                   ">X</button>

                                </div>
                            <?php endwhile; ?>

                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <label class="form-label">Agregar nuevos archivos</label>
                            <input type="file" name="media[]" id="imagen" class="form-control" multiple>
                            <div id="preview" style="display:flex; gap:10px; flex-wrap: wrap; margin-top:10px;"></div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <button type="submit" class="btn w-100 py-2">Guardar cambios</button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</main>

<script src="../../js/media_preview.js"></script>

<?php include_once("../../components/footer.php"); ?>