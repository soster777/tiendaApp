<?php
// datos de conexion, son los que trae XAMPP por defecto
// si tu MySQL tiene otra clave o puerto, cambialos aca
$servidor = "localhost";
$usuario = "root";
$clave = "";
$base_de_datos = "tiendapp";
$puerto = 3306;

try {
    $con = mysqli_connect($servidor, $usuario, $clave, $base_de_datos, $puerto);
} catch (mysqli_sql_exception $e) {
    // si no hay conexion con la base mando al usuario a la pagina de error
    // (es lo que pide la consigna: mensaje de error al fallar la extraccion de datos externos)
    if (!headers_sent()) {
        // todavia no se imprimio nada de HTML: se puede redirigir con header()
        header("Location: /tiendApp/pages/error.php");
    } else {
        // el header.php ya empezo a imprimir la pagina (incluye este archivo en el menu de categorias),
        // asi que header() ya no funciona: redirijo desde el navegador con JavaScript
        print "<script>window.location.href = '/tiendApp/pages/error.php';</script>";
    }
    exit();
}

mysqli_set_charset($con, "utf8mb4");
