<?php
// archivo: edit_pago.php
// Permite editar un pago existente. Solo accesible para admin y tesorero.

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

// Obtener datos del pago
$stmt = $conn->prepare("SELECT p.*, s.nombre AS alumno FROM pagos p JOIN students s ON s.id = p.alumno_id WHERE p.id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$pago = $stmt->get_result()->fetch_assoc();

if (!$pago) {
    echo "Pago no encontrado.";
    exit;
}

// Procesar actualización
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $monto = $_POST['monto'];
    $fecha = $_POST['fecha_pago'];
    $metodo = $_POST['metodo'];
    $observacion = $_POST['observacion'];

    $stmt = $conn->prepare("UPDATE pagos SET monto=?, fecha_pago=?, metodo=?, observacion=? WHERE id=?");
    $stmt->bind_param("dsssi", $monto, $fecha, $metodo, $observacion, $id);
    $stmt->execute();

    log_accion($conn, $usuario_id, 'editar', 'pagos', "Editó pago ID $id (alumno: {$pago['alumno']})");

    header("Location: pagos.php?editado=1");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Pago</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
  <h4>Editar Pago</h4>
  <form method="POST" class="card p-4 shadow-sm">
    <div class="mb-3">
      <label class="form-label">Alumno</label>
      <input type="text" class="form-control" value="<?= htmlspecialchars($pago['alumno']) ?>" disabled>
    </div>
    <div class="mb-3">
      <label class="form-label">Monto</label>
      <input type="number" name="monto" class="form-control" value="<?= $pago['monto'] ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Fecha</label>
      <input type="date" name="fecha_pago" class="form-control" value="<?= $pago['fecha_pago'] ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Método</label>
      <select name="metodo" class="form-select" required>
        <option value="efectivo" <?= $pago['metodo'] === 'efectivo' ? 'selected' : '' ?>>Efectivo</option>
        <option value="transferencia" <?= $pago['metodo'] === 'transferencia' ? 'selected' : '' ?>>Transferencia</option>
      </select>
    </div>
    <div class="mb-3">
      <label class="form-label">Observación</label>
      <textarea name="observacion" class="form-control"><?= htmlspecialchars($pago['observacion']) ?></textarea>
    </div>
    <button class="btn btn-primary">Actualizar Pago</button>
  </form>
</div>
</body>
</html>
