<?php
// archivo: exportar_gastos.php
// Exporta gastos a CSV aplicando filtros por categoría y fecha. Accesible por admin, admin_curso y tesorero.

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
    $condiciones[] = "g.curso_id IN (" . implode(",", $cursos) . ")";
}

$categoria = $_GET['categoria'] ?? '';
if ($categoria) {
    $condiciones[] = "g.categoria = ?";
    $parametros[] = $categoria;
    $tipos .= "s";
}

$fecha_ini = $_GET['fecha_ini'] ?? '';
$fecha_fin = $_GET['fecha_fin'] ?? '';
if ($fecha_ini && $fecha_fin) {
    $condiciones[] = "g.fecha BETWEEN ? AND ?";
    $parametros[] = $fecha_ini;
    $parametros[] = $fecha_fin;
    $tipos .= "ss";
}

$where = $condiciones ? "WHERE " . implode(" AND ", $condiciones) : "";

$query = "SELECT g.categoria, c.nombre AS curso, g.monto, g.fecha, g.descripcion, u.nombre AS responsable
          FROM gastos g
          JOIN cursos c ON g.curso_id = c.id
          JOIN users u ON g.registrado_por = u.id
          $where
          ORDER BY g.fecha DESC";

$stmt = $conn->prepare($query);
if (!empty($parametros)) {
    $stmt->bind_param($tipos, ...$parametros);
}
$stmt->execute();
$result = $stmt->get_result();

header("Content-Type: text/csv");
header("Content-Disposition: attachment;filename=gastos.csv");

$output = fopen("php://output", "w");
fputcsv($output, ['Categoría', 'Curso', 'Monto', 'Fecha', 'Descripción', 'Responsable']);

while ($row = $result->fetch_assoc()) {
    fputcsv($output, $row);
}
fclose($output);
exit;
?>
