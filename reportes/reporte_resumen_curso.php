<?php
// archivo: reporte_resumen_curso.php
// Muestra ingresos, egresos y saldo por curso. Accesible para admin y admin_curso.

session_start();
require '../include/conexion.php';
require '../include/get_assigned_courses.php';

require_login();
$usuario_id = $_SESSION['usuario_id'];
$rol = $_SESSION['rol'];

$condiciones = '';
if ($rol === 'admin_curso') {
    $cursos = get_assigned_courses($conn, $usuario_id, $rol);
    $condiciones = "WHERE c.id IN (" . implode(',', $cursos) . ")";
}

$query = "SELECT c.nombre AS curso,
            (SELECT IFNULL(SUM(p.monto), 0) FROM pagos p WHERE p.curso_id = c.id) AS ingresos,
            (SELECT IFNULL(SUM(g.monto), 0) FROM gastos g WHERE g.curso_id = c.id) AS egresos
          FROM cursos c
          $condiciones
          ORDER BY c.nombre";

$result = $conn->query($query);

header("Content-Type: text/csv");
header("Content-Disposition: attachment;filename=resumen_curso.csv");

$output = fopen("php://output", "w");
fputcsv($output, ['Curso', 'Ingresos', 'Egresos', 'Saldo']);

while ($row = $result->fetch_assoc()) {
    $saldo = $row['ingresos'] - $row['egresos'];
    fputcsv($output, [$row['curso'], $row['ingresos'], $row['egresos'], $saldo]);
}
fclose($output);
exit;
?>
