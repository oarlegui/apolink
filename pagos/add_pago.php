<?php
// archivo: add_pago.php
// Formulario para registrar nuevos pagos. Solo accesible para admin y tesorero.

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

// Obtener alumnos de cursos asignados
$query = "SELECT s.id, s.nombre, c.nombre AS curso 
          FROM students s 
          JOIN cursos c ON s.curso_id = c.id 
          WHERE s.curso_id IN ($curso_list) 
          ORDER BY s.nombre";
$res = $conn->query($query);
$alumnos = $res->fetch_all(MYSQLI_ASSOC);

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $alumno_id = $_POST['alumno_id'];
    $monto = $_POST['monto'];
    $fecha = $_POST['fecha_pago'];
    $metodo = $_POST['metodo'];
    $observacion = $_POST['observacion'];

    $stmt = $conn->prepare("SELECT curso_id FROM students WHERE id = ?");
    $stmt->bind_param("i", $alumno_id);
    $stmt->execute();
    $curso_id = $stmt->get_result()->fetch_assoc()['curso_id'] ?? null;

    if ($curso_id) {
        $stmt = $conn->prepare("INSERT INTO pagos (alumno_id, curso_id, monto, fecha_pago, metodo, observacion) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iidsss", $alumno_id, $curso_id, $monto, $fecha, $metodo, $observacion);
        $stmt->execute();

        log_accion($conn, $usuario_id, 'crear', 'pagos', "Registró pago de $monto para alumno ID $alumno_id");

        header("Location: pagos.php?ok=1");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registrar Pago</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
  <h4>Registrar Pago</h4>
  <form method="POST" class="card p-4 shadow-sm">
    <div class="mb-3">
      <label class="form-label">Alumno</label>
      <select name="alumno_id" class="form-select" required>
        <option value="">Seleccione alumno</option>
        <?php foreach ($alumnos as $a): ?>
          <option value="<?= $a['id'] ?>"><?= htmlspecialchars($a['nombre']) ?> (<?= $a['curso'] ?>)</option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="mb-3">
      <label class="form-label">Monto</label>
      <input type="number" name="monto" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Fecha</label>
      <input type="date" name="fecha_pago" class="form-control" value="<?= date('Y-m-d') ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Método</label>
      <select name="metodo" class="form-select" required>
        <option value="efectivo">Efectivo</option>
        <option value="transferencia">Transferencia</option>
      </select>
    </div>
    <div class="mb-3">
      <label class="form-label">Observación</label>
      <textarea name="observacion" class="form-control"></textarea>
    </div>
    <button class="btn btn-success">Guardar Pago</button>
  </form>
</div>
</body>
</html>
