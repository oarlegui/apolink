<?php
// archivo: conexion.php
// Este archivo establece la conexión con la base de datos MySQL de Apolink

$host = 'localhost';
$db_name = 'xlikqebq_apolink';
$username = 'xlikqebq_systemGes';
$password = 'FqwsLR[c_AX~';

// Crear conexión
$conn = new mysqli($host, $username, $password, $db_name);

// Verificar conexión
if ($conn->connect_error) {
    // En producción se recomienda no mostrar detalles del error
    die("Error de conexión con la base de datos.");
}

// Charset para evitar errores con tildes y caracteres especiales
$conn->set_charset("utf8mb4");
?>