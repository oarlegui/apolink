<?php
// archivo: calendar.php
// Muestra los eventos del curso en forma de lista cronológica.
// Admin y admin_curso pueden agregar y editar.

session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

require '../include/conexion.php';
require '../include/get_assigned_courses.php';
require '../include/seguridad.php';

require_login();
$usuario_id = $_SESSION['usuario_id'];
$rol = $_SESSION['rol'];

$cursos = get_assigned_courses($conn, $usuario_id, $rol);
$curso_list = implode(",", $cursos);

$query = ($rol === 'admin') ?
    "SELECT e.*, c.nombre AS curso FROM eventos e JOIN cursos c ON e.curso_id = c.id ORDER BY e.fecha ASC"
  : "SELECT e.*, c.nombre AS curso FROM eventos e JOIN cursos c ON e.curso_id = c.id WHERE e.curso_id IN ($curso_list) ORDER BY e.fecha ASC";

$eventos = $conn->query($query)->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Calendario de Eventos</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .event-box { border-left: 5px solid #0d6efd; padding: 10px; margin-bottom: 10px; }
  </style>
</head>
<body>
<div class="container mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Calendario de Eventos</h4>
    <?php if (in_array($rol, ['admin', 'admin_curso'])): ?>
      <a href="add_calendar_event.php" class="btn btn-primary">+ Agregar Evento</a>
    <?php endif; ?>
  </div>

  <?php if (empty($eventos)): ?>
    <div class="alert alert-info">No hay eventos registrados.</div>
  <?php endif; ?>

  <?php foreach ($eventos as $e): ?>
    <div class="event-box bg-light shadow-sm">
      <div class="d-flex justify-content-between">
        <div>
          <strong><?= date('d-m-Y', strtotime($e['fecha'])) ?></strong> — <?= htmlspecialchars($e['titulo']) ?><br>
          <small class="text-muted"><?= $e['curso'] ?></small>
        </div>
        <?php if (in_array($rol, ['admin', 'admin_curso'])): ?>
          <a href="edit_calendar_event.php?id=<?= $e['id'] ?>" class="btn btn-sm btn-outline-secondary">Editar</a>
        <?php endif; ?>
      </div>
      <p class="mt-2 mb-0"><?= nl2br(htmlspecialchars($e['descripcion'])) ?></p>
    </div>
  <?php endforeach; ?>
</div>
</body>
</html>
