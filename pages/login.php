<?php
include_once("../components/header.php");
?>

<main>
    <br>
    <br>
    <br>
    <br>

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <h1 class="montserrat-bold">Iniciar sesión 😊</h1>

                <?php
                if (isset($_GET['log'])) {
                    print "
                        <div class='alert alert-success' role='alert'>
                            Ya podés ingresar 💕
                        </div>
                    ";
                }

                if (isset($_GET['datos'])) {
                    print "
                        <div class='alert alert-danger' role='alert'>
                            El usuario o la clave son incorrectos 🤔
                        </div>
                    ";
                }
                ?>

                <form action="../log/log.php" method="post" class="formulario-usuario">

                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="form-floating">
                                <input
                                    type="email"
                                    class="form-control"
                                    id="floatingCorreo"
                                    placeholder="Email"
                                    name="mail">
                                <label for="floatingCorreo"></label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="form-floating">
                                <input
                                    type="password"
                                    class="form-control"
                                    id="floatingClave"
                                    placeholder="Clave"
                                    name="contrasena">
                                <label for="floatingClave"></label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <button class="btn w-100 py-2" type="submit">
                                Ingresar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <br>
</main>

<?php
include_once("../components/footer.php");
?>