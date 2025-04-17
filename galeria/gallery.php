<?php
// gallery/gallery.php
session_start();
require '../include/conexion.php';
require '../include/get_assigned_courses.php';

$usuario_id = $_SESSION['usuario_id'];
$rol = $_SESSION['rol'];

$cursos = get_assigned_courses($conn, $usuario_id, $rol);
$placeholders = implode(',', array_fill(0, count($cursos), '?'));
$types = str_repeat('i', count($cursos));

$stmt = $conn->prepare("SELECT e.id, e.titulo, e.descripcion, e.fecha, e.imagen_portada, u.nombre AS autor
                        FROM galeria_eventos e
                        JOIN users u ON e.usuario_id = u.id
                        WHERE e.curso_id IN ($placeholders)
                        ORDER BY e.fecha DESC");
$stmt->bind_param($types, ...$cursos);
$stmt->execute();
$eventos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Galería de Eventos</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .evento-card img { height: 200px; object-fit: cover; }
  </style>
</head>
<body>
<div class="container mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Galería de Eventos</h3>
    <?php if (in_array($rol, ['admin', 'admin_curso', 'apoderado'])): ?>
      <a href="add_gallery_event.php" class="btn btn-success btn-sm">+ Subir Evento</a>
    <?php endif; ?>
  </div>
  <div class="row g-3">
    <?php foreach ($eventos as $ev): ?>
    <div class="col-md-4">
      <div class="card evento-card shadow-sm">
        <img src="../uploads/eventos/<?= $ev['imagen_portada'] ?>" class="card-img-top" alt="Evento">
        <div class="card-body">
          <h5 class="card-title"><?= htmlspecialchars($ev['titulo']) ?></h5>
          <p class="card-text text-muted mb-1"><?= date('d-m-Y', strtotime($ev['fecha'])) ?></p>
          <p class="card-text"><small>Subido por <?= htmlspecialchars($ev['autor']) ?></small></p>
          <a href="event_detail.php?id=<?= $ev['id'] ?>" class="btn btn-outline-primary btn-sm">Ver más</a>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
    <?php if (empty($eventos)): ?>
      <div class="col-12 text-muted">No hay eventos para mostrar.</div>
    <?php endif; ?>
  </div>
</div>
</body>
</html>