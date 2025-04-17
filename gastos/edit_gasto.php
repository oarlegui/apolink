<?php
// archivo: edit_gasto.php
// Permite editar un gasto registrado. Solo accesible para admin y tesorero.

session_start();
require '../include/conexion.php';
require '../include/log_auditoria.php';
require '../include/seguridad.php';

require_login();
$usuario_id = $_SESSION['usuario_id'];
$rol = $_SESSION['rol'];
$id = $_GET['id'] ?? null;

if (!$id || !in_array($rol, ['admin', 'tesorero'])) {
    die("Acceso denegado.");
}

// Obtener gasto
$stmt = $conn->prepare("SELECT g.*, c.nombre AS curso FROM gastos g JOIN cursos c ON g.curso_id = c.id WHERE g.id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$gasto = $stmt->get_result()->fetch_assoc();

if (!$gasto) {
    echo "Gasto no encontrado.";
    exit;
}

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $categoria = $_POST['categoria'];
    $monto = $_POST['monto'];
    $fecha = $_POST['fecha'];
    $descripcion = $_POST['descripcion'];

    $stmt = $conn->prepare("UPDATE gastos SET categoria=?, monto=?, fecha=?, descripcion=? WHERE id=?");
    $stmt->bind_param("sdssi", $categoria, $monto, $fecha, $descripcion, $id);
    $stmt->execute();

    log_accion($conn, $usuario_id, 'editar', 'gastos', "Editó gasto ID $id en curso {$gasto['curso']}");

    header("Location: gastos.php?editado=1");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Gasto</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
  <h4>Editar Gasto</h4>
  <form method="POST" class="card p-4 shadow-sm">
    <div class="mb-3">
      <label class="form-label">Curso</label>
      <input type="text" class="form-control" value="<?= htmlspecialchars($gasto['curso']) ?>" disabled>
    </div>
    <div class="mb-3">
      <label class="form-label">Categoría</label>
      <input type="text" name="categoria" class="form-control" value="<?= htmlspecialchars($gasto['categoria']) ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Monto</label>
      <input type="number" step="0.01" name="monto" class="form-control" value="<?= $gasto['monto'] ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Fecha</label>
      <input type="date" name="fecha" class="form-control" value="<?= $gasto['fecha'] ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Descripción</label>
      <textarea name="descripcion" class="form-control"><?= htmlspecialchars($gasto['descripcion']) ?></textarea>
    </div>
    <button class="btn btn-primary">Actualizar Gasto</button>
  </form>
</div>
</body>
</html>
