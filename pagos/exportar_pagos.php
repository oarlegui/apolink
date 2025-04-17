<?php
// archivo: exportar_pagos.php
// Exporta pagos a CSV filtrados por alumno, método y fechas.

session_start();
require '../include/conexion.php';
require '../include/get_assigned_courses.php';

$usuario_id = $_SESSION['usuario_id'];
$rol = $_SESSION['rol'];

if (!in_array($rol, ['admin', 'admin_curso', 'tesorero'])) {
    die("Acceso denegado");
}

$condiciones = [];
$parametros = [];
$tipos = '';

if (in_array($rol, ['admin_curso', 'tesorero'])) {
    $cursos = get_assigned_courses($conn, $usuario_id, $rol);
    $condiciones[] = "p.curso_id IN (" . implode(",", $cursos) . ")";
}

$buscar = $_GET['buscar'] ?? '';
if ($buscar) {
    $condiciones[] = "(s.nombre LIKE ? OR s.rut LIKE ?)";
    $parametros[] = "%$buscar%";
    $parametros[] = "%$buscar%";
    $tipos .= 'ss';
}

$metodo = $_GET['metodo'] ?? '';
if ($metodo) {
    $condiciones[] = "p.metodo = ?";
    $parametros[] = $metodo;
    $tipos .= "s";
}

$fecha_ini = $_GET['fecha_ini'] ?? '';
$fecha_fin = $_GET['fecha_fin'] ?? '';
if ($fecha_ini && $fecha_fin) {
    $condiciones[] = "p.fecha_pago BETWEEN ? AND ?";
    $parametros[] = $fecha_ini;
    $parametros[] = $fecha_fin;
    $tipos .= "ss";
}

$where = $condiciones ? "WHERE " . implode(" AND ", $condiciones) : "";

$query = "SELECT s.nombre AS alumno, s.rut, c.nombre AS curso, p.monto, p.fecha_pago, p.metodo, p.observacion
          FROM pagos p
          JOIN students s ON p.alumno_id = s.id
          JOIN cursos c ON p.curso_id = c.id
          $where
          ORDER BY p.fecha_pago DESC";

$stmt = $conn->prepare($query);
if (!empty($parametros)) {
    $stmt->bind_param($tipos, ...$parametros);
}
$stmt->execute();
$result = $stmt->get_result();

header("Content-Type: text/csv");
header("Content-Disposition: attachment;filename=pagos.csv");

$output = fopen("php://output", "w");
fputcsv($output, ['Alumno', 'RUT', 'Curso', 'Monto', 'Fecha', 'Método', 'Observación']);

while ($row = $result->fetch_assoc()) {
    fputcsv($output, $row);
}
fclose($output);
exit;
?>
