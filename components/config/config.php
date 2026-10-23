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
    die("No se pudo conectar a la base de datos. Revisá que MySQL esté prendido en XAMPP y que hayas importado database/tiendapp.sql");
}

mysqli_set_charset($con, "utf8mb4");
