<?php
// pagina que se muestra cuando no se pueden traer los datos de la base
// (por ejemplo si MySQL esta apagado en XAMPP).
// OJO: esta pagina NO incluye config.php ni header.php, porque los dos usan la base:
// si la base esta caida y los incluyera, se quedaria redirigiendo a si misma para siempre.
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TiendApp - Error</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="/tiendApp/css/styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>

<body id="fondo">
    <main>
        <div class="container py-5">
            <div class="row justify-content-center">
                <!-- el text-center va en un div aparte: styles.css le agrega el cartel de portada
                     a los h1 que estan directo adentro de un .col-12.text-center -->
                <div class="col-12 col-md-8 col-lg-6">
                    <div class="text-center">
                        <img src="/tiendApp/imgs/logo.png" alt="Logo de TiendApp" width="80" height="80" class="mb-4">
                        <h1 class="montserrat-bold">No pudimos cargar la información</h1>
                        <p class="montserrat-regular mt-3">
                            En este momento no podemos conectarnos con la base de datos de TiendApp,
                            así que no se pueden mostrar los productos ni las cuentas.
                        </p>
                        <p class="montserrat-regular">
                            Probá de nuevo en unos minutos. Si el problema sigue, avisanos desde el formulario de contacto.
                        </p>
                        <a href="/tiendApp/pages/inicio.php" class="btn btn-success mt-3 px-4">Reintentar</a>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>

</html>
