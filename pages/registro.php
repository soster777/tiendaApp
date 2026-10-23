<?php
include_once("../components/header.php");
?>

<main>
    <br>
    <br>

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <h1 class="montserrat-bold">Registrate 😉</h1>

                <?php
                if (isset($_GET["datos"])) {
                    print "
                        <div class='alert alert-danger' role='alert'>
                            Todos los datos son obligatorios
                        </div>
                    ";
                }

                if (isset($_GET["clave"])) {
                    print "
                        <div class='alert alert-danger' role='alert'>
                            Las claves NO coinciden
                        </div>
                    ";
                }

                if (isset($_GET["correo"])) {
                    print "
                        <div class='alert alert-danger' role='alert'>
                            El correo ya está registrado
                        </div>
                    ";
                }
                ?>

                <form action="../log/reg.php" method="post" class="formulario-usuario" id="formRegistro">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="floatingNombre" placeholder="Nombre" name="nombre" maxlength="45" required>
                                <label for="floatingNombre"></label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control" id="floatingCorreo" placeholder="Email" name="mail" maxlength="60" required>
                                <label for="floatingCorreo"></label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6 mb-3 mb-md-0">
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" id="floatingClave" placeholder="Clave" name="contrasena" minlength="4" required>
                                <label for="floatingClave"></label>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" id="floatingClaveRep" placeholder="Repetir Clave" name="contrasena_rep" minlength="4" required>
                                <!-- este mensaje lo muestra js/registro.js si las claves no coinciden -->
                                <div class="invalid-feedback">Las claves no coinciden</div>
                                <label for="floatingClaveRep"></label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="floatingUbicacion" placeholder="Ubicación (opcional)" name="ubicacion" maxlength="80">
                                <label for="floatingUbicacion"></label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <button class="btn w-100 py-2" type="submit">Registrate</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <br>
    <br>
</main>

<script src="../js/registro.js"></script>

<?php
include_once("../components/footer.php");
?>