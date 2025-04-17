<?php
// archivo: exportar_alumnos.php
// Exporta listado de alumnos con filtro por nombre/RUT. Accesible para admin y admin_curso.

session_start();
require '../include/conexion.php';
require '../include/get_assigned_courses.php';

$usuario_id = $_SESSION['usuario_id'];
$rol = $_SESSION['rol'];

if (!in_array($rol, ['admin', 'admin_curso'])) {
    die("Acceso denegado");
}

$condiciones = [];
$parametros = [];
$tipos = '';

if ($rol === 'admin_curso') {
    $cursos = get_assigned_courses($conn, $usuario_id, $rol);
    $condiciones[] = "s.curso_id IN (" . implode(",", $cursos) . ")";
}

$buscar = $_GET['buscar'] ?? '';
if ($buscar) {
    $condiciones[] = "(s.nombre LIKE ? OR s.rut LIKE ?)";
    $parametros[] = "%$buscar%";
    $parametros[] = "%$buscar%";
    $tipos .= 'ss';
}

$where = $condiciones ? "WHERE " . implode(" AND ", $condiciones) : "";

$query = "SELECT s.nombre, s.rut, s.fecha_nac, c.nombre AS curso, col.nombre AS colegio, s.telefono, s.email
          FROM students s
          JOIN cursos c ON s.curso_id = c.id
          JOIN colegios col ON s.colegio_id = col.id
          $where
          ORDER BY s.nombre ASC";

$stmt = $conn->prepare($query);
if (!empty($parametros)) {
    $stmt->bind_param($tipos, ...$parametros);
}
$stmt->execute();
$result = $stmt->get_result();

header("Content-Type: text/csv");
header("Content-Disposition: attachment;filename=alumnos.csv");

$output = fopen("php://output", "w");
fputcsv($output, ['Nombre', 'RUT', 'Fecha Nacimiento', 'Curso', 'Colegio', 'Teléfono', 'Email']);

while ($row = $result->fetch_assoc()) {
    fputcsv($output, $row);
}
fclose($output);
exit;
?>
