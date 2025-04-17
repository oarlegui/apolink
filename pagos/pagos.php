<?php
// archivo: pagos.php
// Listado de pagos con filtros por alumno, fecha y método. Accesible según el rol del usuario.

session_start();
require '../include/conexion.php';
require '../include/get_assigned_courses.php';
require '../include/seguridad.php';

require_login();
$usuario_id = $_SESSION['usuario_id'];
$rol = $_SESSION['rol'];

$condiciones = [];
$parametros = [];
$tipos = '';

// Cursos asignados si aplica
if (in_array($rol, ['admin_curso', 'tesorero'])) {
    $cursos = get_assigned_courses($conn, $usuario_id, $rol);
    if (!empty($cursos)) {
        $condiciones[] = "p.curso_id IN (" . implode(",", $cursos) . ")";
    }
}

// Filtro por alumno o RUT
$buscar = $_GET['buscar'] ?? '';
if ($buscar) {
    $condiciones[] = "(s.nombre LIKE ? OR s.rut LIKE ?)";
    $parametros[] = "%$buscar%";
    $parametros[] = "%$buscar%";
    $tipos .= 'ss';
}

// Método de pago
$metodo = $_GET['metodo'] ?? '';
if ($metodo) {
    $condiciones[] = "p.metodo = ?";
    $parametros[] = $metodo;
    $tipos .= 's';
}

// Rango de fechas
$fecha_ini = $_GET['fecha_ini'] ?? '';
$fecha_fin = $_GET['fecha_fin'] ?? '';
if ($fecha_ini && $fecha_fin) {
    $condiciones[] = "p.fecha_pago BETWEEN ? AND ?";
    $parametros[] = $fecha_ini;
    $parametros[] = $fecha_fin;
    $tipos .= 'ss';
}

$where = $condiciones ? "WHERE " . implode(" AND ", $condiciones) : "";

$query = "SELECT p.*, s.nombre AS alumno, s.rut, c.nombre AS curso
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
$pagos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Pagos</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
  <h4>Listado de Pagos</h4>

  <form method="GET" class="row g-2 mb-4">
    <div class="col-md-3">
      <input type="text" name="buscar" class="form-control" placeholder="Buscar alumno o RUT" value="<?= htmlspecialchars($buscar) ?>">
    </div>
    <div class="col-md-2">
      <select name="metodo" class="form-select">
        <option value="">Método</option>
        <option value="efectivo" <?= $metodo === 'efectivo' ? 'selected' : '' ?>>Efectivo</option>
        <option value="transferencia" <?= $metodo === 'transferencia' ? 'selected' : '' ?>>Transferencia</option>
      </select>
    </div>
    <div class="col-md-2">
      <input type="date" name="fecha_ini" class="form-control" value="<?= htmlspecialchars($fecha_ini) ?>">
    </div>
    <div class="col-md-2">
      <input type="date" name="fecha_fin" class="form-control" value="<?= htmlspecialchars($fecha_fin) ?>">
    </div>
    <div class="col-md-3">
      <button class="btn btn-primary w-100">Filtrar</button>
    </div>
  </form>

  <table class="table table-bordered table-hover">
    <thead class="table-light">
      <tr>
        <th>Alumno</th>
        <th>Curso</th>
        <th>Monto</th>
        <th>Método</th>
        <th>Fecha</th>
        <th>Observación</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($pagos as $p): ?>
        <tr>
          <td><?= htmlspecialchars($p['alumno']) ?></td>
          <td><?= htmlspecialchars($p['curso']) ?></td>
          <td>$<?= number_format($p['monto'], 0, ',', '.') ?></td>
          <td><?= ucfirst($p['metodo']) ?></td>
          <td><?= date('d-m-Y', strtotime($p['fecha_pago'])) ?></td>
          <td><?= htmlspecialchars($p['observacion']) ?></td>
          <td>
            <?php if (in_array($rol, ['admin', 'tesorero'])): ?>
              <a href="edit_pago.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-primary">Editar</a>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (empty($pagos)): ?>
        <tr>
          <td colspan="7" class="text-center text-muted">No se encontraron pagos.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
</body>
</html>
