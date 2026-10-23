<?php
include_once("../components/header.php");
include_once("../components/config/config.php");
$id_usuario = $_SESSION['id_usuario'] ?? 0;
?>

<main>
    <br> 
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="montserrat-bold">Contacto</h1>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12">
                <h2 class="montserrat-bold">Formulario a completar</h2>
            </div>
        </div>
        
        <br>

        <?php
        if (isset($_GET['dato']) && $_GET['dato'] === 'ok') {
            print '<div class="alert alert-success text-center">¡Gracias! Tu sugerencia fue enviada correctamente. 😊</div>';
        }
        if (isset($_GET['error'])) {
            $errorMensaje = htmlspecialchars($_GET['error']);
            print '<div class="alert alert-danger text-center">Ocurrió un error: ' . $errorMensaje . '</div>';
        }
        ?>

        <section>
            <div class="row align-items-center g-0 p-4" style="background-color: rgba(0, 0, 0, 0.2); border-radius: 8px;">
                <div class="col-12 col-md-6 col-xl-9">
                    <h3 class="montserrat-bold mb-3">Sugerencias y Propuestas</h3>
                    <p class="montserrat-regular">Esta sección ha sido diseñada para optimizar la comunicación con nuestros usuarios. A través de este formulario, usted podrá remitir sus comentarios, observaciones o propuestas técnicas fundamentales para la mejora continua.</p>
                    
                 <?php if ($id_usuario == 0): ?>
    <div class="alert alert-dark mt-3" style="background: rgba(255, 255, 255, 0.1); border: 1px dashed rgba(255, 255, 255, 0.3);">
        <p class="montserrat-regular text-white mb-0">
            Para poder escribir un mensaje o enviar una propuesta, es necesario 
            <a href="../login.php" class="text-white fw-bold">Iniciar sesión</a> 
            o 
            <a href="../registro.php" class="text-white fw-bold">Registrarse</a>. 
            Si tienes dudas, puedes ir a 
            <a href="contacto.php" class="text-white fw-bold">Contactos</a>.
        </p>
    </div>
<?php else: ?>

                        <form action="abm_sugerencia/sugerencia_ok.php" method="post" enctype="multipart/form-data" class="formulario-usuario">
                            <div class="row">
                                <div class="col-12">
                                    <label for="comentario" class="form-label primero">Comentario/Sugerencia (Obligatorio)</label>
                                    <textarea name="comentario" id="comentario" class="form-control" placeholder="Escriba aquí su propuesta..." required></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <label for="archivo" class="form-label">Subir imagen respecto a la sugerencia (Opcional)</label>
                                    <input type="file" name="archivo" id="archivo" class="form-control" accept=".jpg,.jpeg,.png">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <input type="submit" class="btn btn-primary mt-3" value="Enviar Sugerencia">
                                </div>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>

                <div class="col-md-6 col-xl-3 d-none d-md-block text-center">
                    <img src="../imgs/contacto.png" alt="imagen del formulario" class="img-fluid d-block mx-auto">
                </div>
            </div>
            <br>
        </section>
    </div>
</main>

<?php
include_once("../components/footer.php");
?>