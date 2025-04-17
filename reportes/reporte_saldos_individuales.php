<?php
// archivo: reporte_saldos_individuales.php
// Exporta los saldos individuales de cada alumno. Accesible para admin, admin_curso y tesorero.

session_start();
require '../include/conexion.php';
require '../include/get_assigned_courses.php';

require_login();
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
    $condiciones[] = "s.curso_id IN (" . implode(',', $cursos) . ")";
}

$where = $condiciones ? "WHERE " . implode(" AND ", $condiciones) : "";

$query = "SELECT s.nombre, s.rut, c.nombre AS curso,
            IFNULL((SELECT SUM(monto) FROM pagos WHERE alumno_id = s.id), 0) AS pagado,
            IFNULL(s.cuota_total, 0) AS cuota
          FROM students s
          JOIN cursos c ON s.curso_id = c.id
          $where
          ORDER BY s.nombre";

$result = $conn->query($query);

header("Content-Type: text/csv");
header("Content-Disposition: attachment;filename=saldos_individuales.csv");

$output = fopen("php://output", "w");
fputcsv($output, ['Alumno', 'RUT', 'Curso', 'Pagado', 'Cuota', 'Saldo']);

while ($row = $result->fetch_assoc()) {
    $saldo = $row['cuota'] - $row['pagado'];
    fputcsv($output, [$row['nombre'], $row['rut'], $row['curso'], $row['pagado'], $row['cuota'], $saldo]);
}
fclose($output);
exit;
?>
