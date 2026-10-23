<?php
include_once("../../components/header_admin.php");
include_once("../../components/config/config.php");

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../../index.php");
    exit();
}

?>

<main>
    <div class="container-fluid">

        <div class="row justify-content-center">
            <div class="col-12 col-lg-6">
                <h1 class="montserrat-bold">Modificar Categoría</h1>

                <form action="mod_cat_ok.php" method="get" class="formulario-usuario">
                    <div class="row mb-2">
                        <div class="col-12">
                            <label for="cat" class="form-label primero">Modificar Categoría</label>
                            <?php
                            if (isset($_GET['id'])) {
                                $id = intval($_GET['id']);
                                $consulta = "SELECT `id_categoria`, `categoria` FROM `categorias` WHERE `id_categoria`=$id";
                                $respuesta = mysqli_query($con, $consulta);

                                while ($fila = mysqli_fetch_array($respuesta)) {
                                     print "<input type='text' name='cat' id='cat' placeholder='" . htmlspecialchars($fila['categoria']) . "' value='" . htmlspecialchars($fila['categoria'], ENT_QUOTES, 'UTF-8') . "' class='form-control mb-2' required>
                                     <input type='hidden' name='id' value='" . $fila['id_categoria'] . "'>";
                                }
                            }
                            ?>
                        </div>
                        <div class="col-12 justify-content-start">
                            <input type="submit" value="Modificar Categoría" class="btn btn-success me-2">
                            <a href="../categoria.php" class="btn btn-secondary cancelar">Cancelar</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</main>

<?php include_once("../../components/footer.php"); ?>