<?php
// gallery/add_gallery_event.php
session_start();
require '../include/conexion.php';
require '../include/get_assigned_courses.php';

$usuario_id = $_SESSION['usuario_id'];
$rol = $_SESSION['rol'];

if (!in_array($rol, ['admin', 'admin_curso', 'apoderado'])) {
    echo "Acceso denegado.";
    exit;
}

$cursos = get_assigned_courses($conn, $usuario_id, $rol);
$stmt = $conn->prepare("SELECT id, nombre FROM cursos WHERE id IN (" . implode(",", array_fill(0, count($cursos), '?')) . ")");
$stmt->bind_param(str_repeat("i", count($cursos)), ...$cursos);
$stmt->execute();
$cursos_disp = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $curso_id = $_POST['curso_id'];
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $fecha = $_POST['fecha'];

    $portada = $_FILES['imagen_portada']['name'];
    $tmp_name = $_FILES['imagen_portada']['tmp_name'];
    $filename = time() . '_' . basename($portada);
    move_uploaded_file($tmp_name, "../uploads/eventos/" . $filename);

    $stmt = $conn->prepare("INSERT INTO galeria_eventos (curso_id, titulo, descripcion, fecha, imagen_portada, usuario_id)
                            VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssi", $curso_id, $titulo, $descripcion, $fecha, $filename, $usuario_id);
    $stmt->execute();
    $evento_id = $conn->insert_id;

    // Subida de imágenes adicionales
    for ($i = 0; $i < count($_FILES['imagenes']['name']); $i++) {
        if ($_FILES['imagenes']['error'][$i] == 0) {
            $nombre_img = $_FILES['imagenes']['name'][$i];
            $tmp_img = $_FILES['imagenes']['tmp_name'][$i];
            $nombre_final = time() . '_' . rand(100, 999) . '_' . basename($nombre_img);
            move_uploaded_file($tmp_img, "../uploads/eventos/" . $nombre_final);

            $stmt = $conn->prepare("INSERT INTO galeria_imagenes (evento_id, archivo) VALUES (?, ?)");
            $stmt->bind_param("is", $evento_id, $nombre_final);
            $stmt->execute();
        }
    }

    header("Location: gallery.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Subir Evento</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
  <h4>Subir Evento</h4>
  <form method="POST" enctype="multipart/form-data" class="card p-4 shadow-sm">
    <div class="mb-3">
      <label>Curso</label>
      <select name="curso_id" class="form-select" required>
        <option value="">Seleccione curso</option>
        <?php foreach ($cursos_disp as $c): ?>
          <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nombre']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="mb-3">
      <label>Título</label>
      <input type="text" name="titulo" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Descripción</label>
      <textarea name="descripcion" class="form-control" required></textarea>
    </div>
    <div class="mb-3">
      <label>Fecha</label>
      <input type="date" name="fecha" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Imagen de Portada</label>
      <input type="file" name="imagen_portada" accept="image/*" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Imágenes adicionales (hasta 3)</label>
      <input type="file" name="imagenes[]" accept="image/*" class="form-control" multiple>
    </div>
    <button class="btn btn-success">Guardar Evento</button>
  </form>
</div>
</body>
</html>