<?php
// archivo: add_gasto.php
// Formulario para registrar gastos. Solo accesible por admin y tesorero.

session_start();
require '../include/conexion.php';
require '../include/get_assigned_courses.php';
require '../include/log_auditoria.php';
require '../include/seguridad.php';

require_login();
$usuario_id = $_SESSION['usuario_id'];
$rol = $_SESSION['rol'];

if (!in_array($rol, ['admin', 'tesorero'])) {
    die("Acceso denegado.");
}

$cursos = get_assigned_courses($conn, $usuario_id, $rol);
$curso_list = implode(",", $cursos);

$query = "SELECT id, nombre FROM cursos WHERE id IN ($curso_list) ORDER BY nombre";
$result = $conn->query($query);
$cursos_disponibles = $result->fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $curso_id = $_POST['curso_id'];
    $categoria = $_POST['categoria'];
    $monto = $_POST['monto'];
    $fecha = $_POST['fecha'];
    $descripcion = $_POST['descripcion'];

    $stmt = $conn->prepare("INSERT INTO gastos (curso_id, categoria, monto, fecha, descripcion, registrado_por) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isdssi", $curso_id, $categoria, $monto, $fecha, $descripcion, $usuario_id);
    $stmt->execute();

    log_accion($conn, $usuario_id, 'crear', 'gastos', "Registró gasto de $monto en $categoria");

    header("Location: gastos.php?ok=1");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registrar Gasto</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
  <h4>Registrar Gasto</h4>
  <form method="POST" class="card p-4 shadow-sm">
    <div class="mb-3">
      <label class="form-label">Curso</label>
      <select name="curso_id" class="form-select" required>
        <option value="">Seleccione curso</option>
        <?php foreach ($cursos_disponibles as $c): ?>
          <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nombre']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="mb-3">
      <label class="form-label">Categoría</label>
      <input type="text" name="categoria" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Monto</label>
      <input type="number" name="monto" step="0.01" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Fecha</label>
      <input type="date" name="fecha" class="form-control" value="<?= date('Y-m-d') ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Descripción</label>
      <textarea name="descripcion" class="form-control"></textarea>
    </div>
    <button class="btn btn-success">Guardar Gasto</button>
  </form>
</div>
</body>
</html>
