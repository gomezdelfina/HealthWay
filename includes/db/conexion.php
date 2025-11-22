<?php
/**
 * Archivo de configuracion y conexion a la base de datos HealthWay.
 * Utilizado por el modulo de Administrador para la gestion de usuarios.
 */
// Define las constantes de conexion a la base de datos "HealthWay"
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root'); 
define('DB_PASSWORD', '');     
define('DB_NAME', 'HealthWay');

// Intento de conexion a la base de datos
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Verificar la conexion
if ($conn->connect_error) {
    // Error de conexion.
    die("ERROR: No se pudo conectar a la base de datos " . DB_NAME . ". " . $conn->connect_error);
}


$conn->set_charset("utf8");

?>