<?php
// archivo: exportar_apoderados.php
// Exporta listado de apoderados con nombre, RUT, email y teléfono. Solo admin y admin_curso.

session_start();
require '../include/conexion.php';

$rol = $_SESSION['rol'] ?? '';
if (!in_array($rol, ['admin', 'admin_curso'])) {
    die("Acceso denegado");
}

$query = "SELECT nombre, rut, email, telefono FROM users WHERE rol = 'apoderado' ORDER BY nombre";
$result = $conn->query($query);

header("Content-Type: text/csv");
header("Content-Disposition: attachment;filename=apoderados.csv");

$output = fopen("php://output", "w");
fputcsv($output, ['Nombre', 'RUT', 'Email', 'Teléfono']);

while ($row = $result->fetch_assoc()) {
    fputcsv($output, $row);
}
fclose($output);
exit;
?>
