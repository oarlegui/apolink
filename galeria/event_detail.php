<?php
// gallery/event_detail.php
session_start();
require '../include/conexion.php';
require '../include/get_assigned_courses.php';

$usuario_id = $_SESSION['usuario_id'];
$rol = $_SESSION['rol'];
$id = $_GET['id'] ?? null;

if (!$id) {
    echo "Evento no especificado.";
    exit;
}

$stmt = $conn->prepare("SELECT e.*, u.nombre AS autor FROM galeria_eventos e
                        JOIN users u ON e.usuario_id = u.id
                        WHERE e.id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$evento = $stmt->get_result()->fetch_assoc();

if (!$evento) {
    echo "Evento no encontrado.";
    exit;
}

// Validar acceso al curso
$cursos = get_assigned_courses($conn, $usuario_id, $rol);
if (!in_array($evento['curso_id'], $cursos)) {
    echo "No autorizado para ver este evento.";
    exit;
}

$stmt = $conn->prepare("SELECT archivo FROM galeria_imagenes WHERE evento_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$imagenes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($evento['titulo']) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .galeria img { max-height: 200px; object-fit: cover; width: 100%; cursor: pointer; }
  </style>
</head>
<body>
<div class="container mt-4">
  <h3><?= htmlspecialchars($evento['titulo']) ?></h3>
  <p class="text-muted"><?= date('d-m-Y', strtotime($evento['fecha'])) ?> | Subido por <?= htmlspecialchars($evento['autor']) ?></p>
  <p><?= nl2br(htmlspecialchars($evento['descripcion'])) ?></p>

  <h5>Imagen Principal</h5>
  <img src="../uploads/eventos/<?= $evento['imagen_portada'] ?>" class="img-fluid mb-4 rounded">

  <?php if (!empty($imagenes)): ?>
  <h5>Galería</h5>
  <div class="row g-3 galeria">
    <?php foreach ($imagenes as $img): ?>
      <div class="col-sm-6 col-md-4">
        <img src="../uploads/eventos/<?= $img['archivo'] ?>" class="img-thumbnail" onclick="abrirModal(this.src)">
      </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</div>

<!-- Modal para ampliar -->
<div class="modal fade" id="modalImagen" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <img id="imagenAmpliada" src="" class="img-fluid rounded">
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function abrirModal(src) {
  document.getElementById('imagenAmpliada').src = src;
  new bootstrap.Modal(document.getElementById('modalImagen')).show();
}
</script>
</body>
</html>