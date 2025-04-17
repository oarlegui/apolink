<?php
// archivo: exportar_usuarios.php
// Exporta el listado de todos los usuarios del sistema. Solo accesible por admin.

session_start();
require '../include/conexion.php';

if ($_SESSION['rol'] !== 'admin') {
    die("Acceso denegado");
}

$query = "SELECT nombre, rut, email, telefono, rol FROM users ORDER BY nombre";
$result = $conn->query($query);

header("Content-Type: text/csv");
header("Content-Disposition: attachment;filename=usuarios.csv");

$output = fopen("php://output", "w");
fputcsv($output, ['Nombre', 'RUT', 'Email', 'Teléfono', 'Rol']);

while ($row = $result->fetch_assoc()) {
    fputcsv($output, $row);
}
fclose($output);
exit;
?>
