<?php
session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'include/conexion.php';
require 'include/seguridad.php';
require_login();
require_rol(['tesorero']);

$usuario_id = $_SESSION['usuario_id'];

// 1. Cursos asignados
$stmt1 = $conn->prepare("SELECT curso_id FROM user_curso WHERE user_id = ?");
$stmt1->bind_param("i", $usuario_id);
$stmt1->execute();
$curso_ids = array_column($stmt1->get_result()->fetch_all(MYSQLI_NUM), 0);
$stmt1->close();

if (empty($curso_ids)) {
    die("No tiene cursos asignados");
}
$curso_list = implode(',', $curso_ids);

// 2. Totales pagos y gastos
$stmt2 = $conn->prepare("
  SELECT 
    (SELECT IFNULL(SUM(monto), 0) FROM pagos WHERE curso_id IN ($curso_list)) AS total_pagos,
    (SELECT IFNULL(SUM(monto), 0) FROM gastos WHERE curso_id IN ($curso_list)) AS total_gastos
");
$stmt2->execute();
$totales = $stmt2->get_result()->fetch_assoc();
$stmt2->close();

$total_pagos = $totales['total_pagos'];
$total_gastos = $totales['total_gastos'];
$saldo = $total_pagos - $total_gastos;

// 3. Datos mensuales pagos
$pagos_mes = array_fill(1, 12, 0);
$stmt3 = $conn->prepare("
  SELECT MONTH(fecha_pago) AS mes, SUM(monto) AS total
  FROM pagos
  WHERE curso_id IN ($curso_list) AND YEAR(fecha_pago) = YEAR(NOW())
  GROUP BY mes
");
$stmt3->execute();
$result3 = $stmt3->get_result();
while ($row = $result3->fetch_assoc()) {
    $pagos_mes[(int)$row['mes']] = (int)$row['total'];
}
$stmt3->close();

// 4. Datos mensuales gastos
$gastos_mes = array_fill(1, 12, 0);
$stmt4 = $conn->prepare("
  SELECT MONTH(fecha) AS mes, SUM(monto) AS total
  FROM gastos
  WHERE curso_id IN ($curso_list) AND YEAR(fecha) = YEAR(NOW())
  GROUP BY mes
");
$stmt4->execute();
$result4 = $stmt4->get_result();
while ($row = $result4->fetch_assoc()) {
    $gastos_mes[(int)$row['mes']] = (int)$row['total'];
}
$stmt4->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Tesorero - Apolink</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="container mt-4">
  <h4>💼 Panel del Tesorero</h4>

  <!-- Tarjetas resumen -->
  <div class="row my-4">
    <div class="col-md-4">
      <div class="card text-center shadow-sm"><div class="card-body">
        <h6 class="text-muted">Pagos Registrados</h6>
        <strong>$<?= number_format($total_pagos, 0, ',', '.') ?></strong>
      </div></div>
    </div>
    <div class="col-md-4">
      <div class="card text-center shadow-sm"><div class="card-body">
        <h6 class="text-muted">Gastos Realizados</h6>
        <strong>$<?= number_format($total_gastos, 0, ',', '.') ?></strong>
      </div></div>
    </div>
    <div class="col-md-4">
      <div class="card text-center shadow-sm"><div class="card-body">
        <h6 class="text-muted">Saldo Disponible</h6>
        <strong>$<?= number_format($saldo, 0, ',', '.') ?></strong>
      </div></div>
    </div>
  </div>

  <!-- Gráfico mensual -->
  <div class="card shadow-sm p-4 mb-4">
    <h6>📈 Ingresos vs Egresos Mensuales</h6>
    <canvas id="graficoMensual"></canvas>
  </div>

  <!-- Tabla cuotas pendientes -->
  <div class="card shadow-sm p-4 mb-5">
    <h6>👨‍🎓 Alumnos con saldo pendiente</h6>
    <div class="table-responsive">
      <table class="table table-bordered table-hover mt-3">
        <thead class="table-light">
          <tr>
            <th>Alumno</th>
            <th>Curso</th>
            <th>Total Cuota</th>
            <th>Pagado</th>
            <th>Pendiente</th>
          </tr>
        </thead>
        <tbody>
        <?php
        $query = "
          SELECT s.nombre, s.cuota_total, c.nombre AS curso,
                 IFNULL(SUM(p.monto), 0) AS pagado
          FROM students s
          JOIN cursos c ON s.curso_id = c.id
          LEFT JOIN pagos p ON p.alumno_id = s.id
          WHERE s.curso_id IN ($curso_list)
          GROUP BY s.id
          HAVING (s.cuota_total - pagado) > 0
          ORDER BY s.nombre
        ";
        $resultado = $conn->query($query);
        while ($fila = $resultado->fetch_assoc()):
          $pendiente = $fila['cuota_total'] - $fila['pagado'];
        ?>
        <tr>
          <td><?= htmlspecialchars($fila['nombre']) ?></td>
          <td><?= htmlspecialchars($fila['curso']) ?></td>
          <td>$<?= number_format($fila['cuota_total'], 0, ',', '.') ?></td>
          <td>$<?= number_format($fila['pagado'], 0, ',', '.') ?></td>
          <td><strong class="text-danger">$<?= number_format($pendiente, 0, ',', '.') ?></strong></td>
        </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
const ctx = document.getElementById('graficoMensual').getContext('2d');
new Chart(ctx, {
  type: 'bar',
  data: {
    labels: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
    datasets: [
      {
        label: 'Pagos',
        data: <?= json_encode(array_values($pagos_mes)) ?>,
        backgroundColor: '#198754'
      },
      {
        label: 'Gastos',
        data: <?= json_encode(array_values($gastos_mes)) ?>,
        backgroundColor: '#DC3545'
      }
    ]
  },
  options: {
    responsive: true,
    plugins: {
      legend: { position: 'bottom' }
    }
  }
});
</script>
</body>
</html>
