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

                <form action="../log/reg.php" method="post" class="formulario-usuario">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="floatingNombre" placeholder="Nombre" name="nombre">
                                <label for="floatingNombre"></label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control" id="floatingCorreo" placeholder="Email" name="mail">
                                <label for="floatingCorreo"></label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6 mb-3 mb-md-0">
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" id="floatingClave" placeholder="Clave" name="contrasena">
                                <label for="floatingClave"></label>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" id="floatingClaveRep" placeholder="Repetir Clave" name="contrasena_rep">
                                <label for="floatingClaveRep"></label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="floatingUbicacion" placeholder="Ubicación" name="ubicacion">
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

<?php
include_once("../components/footer.php");
?>