<?php
// archivo: gastos.php
// Muestra un listado de gastos con filtros por categoría y fecha. Solo accesible por admin, admin_curso y tesorero.

session_start();
require '../include/conexion.php';
require '../include/get_assigned_courses.php';
require '../include/seguridad.php';

require_login();
$usuario_id = $_SESSION['usuario_id'];
$rol = $_SESSION['rol'];

if (!in_array($rol, ['admin', 'admin_curso', 'tesorero'])) {
    die("Acceso denegado.");
}

$condiciones = [];
$parametros = [];
$tipos = '';

// Filtrar por cursos asignados
if (in_array($rol, ['admin_curso', 'tesorero'])) {
    $cursos = get_assigned_courses($conn, $usuario_id, $rol);
    $condiciones[] = "g.curso_id IN (" . implode(",", $cursos) . ")";
}

// Filtros
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

$query = "SELECT g.*, c.nombre AS curso, u.nombre AS responsable
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
$gastos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Gastos</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
  <h4>Listado de Gastos</h4>
  <form method="GET" class="row g-2 mb-4">
    <div class="col-md-3">
      <input type="text" name="categoria" class="form-control" placeholder="Categoría" value="<?= htmlspecialchars($categoria) ?>">
    </div>
    <div class="col-md-3">
      <input type="date" name="fecha_ini" class="form-control" value="<?= htmlspecialchars($fecha_ini) ?>">
    </div>
    <div class="col-md-3">
      <input type="date" name="fecha_fin" class="form-control" value="<?= htmlspecialchars($fecha_fin) ?>">
    </div>
    <div class="col-md-3">
      <button class="btn btn-primary w-100">Filtrar</button>
    </div>
  </form>

  <table class="table table-bordered table-hover">
    <thead class="table-light">
      <tr>
        <th>Categoría</th>
        <th>Curso</th>
        <th>Monto</th>
        <th>Fecha</th>
        <th>Descripción</th>
        <th>Responsable</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($gastos as $g): ?>
        <tr>
          <td><?= htmlspecialchars($g['categoria']) ?></td>
          <td><?= htmlspecialchars($g['curso']) ?></td>
          <td>$<?= number_format($g['monto'], 0, ',', '.') ?></td>
          <td><?= date('d-m-Y', strtotime($g['fecha'])) ?></td>
          <td><?= htmlspecialchars($g['descripcion']) ?></td>
          <td><?= htmlspecialchars($g['responsable']) ?></td>
          <td>
            <?php if (in_array($rol, ['admin', 'tesorero'])): ?>
              <a href="edit_gasto.php?id=<?= $g['id'] ?>" class="btn btn-sm btn-primary">Editar</a>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (empty($gastos)): ?>
        <tr>
          <td colspan="7" class="text-center text-muted">No se encontraron gastos.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
</body>
</html>
