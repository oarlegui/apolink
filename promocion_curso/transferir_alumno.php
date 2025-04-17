<?php
// archivo: transferir_alumno.php
// Permite transferir a un alumno a otro colegio y curso. Solo accesible para admin.

session_start();
require '../include/conexion.php';
require '../include/log_auditoria.php';
require '../include/seguridad.php';

require_login();
if ($_SESSION['rol'] !== 'admin') {
    die("Acceso denegado");
}

$alumno_id = $_GET['id'] ?? null;
if (!$alumno_id) {
    die("ID de alumno no válido.");
}

// Obtener datos del alumno
$stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
$stmt->bind_param("i", $alumno_id);
$stmt->execute();
$alumno = $stmt->get_result()->fetch_assoc();

if (!$alumno) {
    echo "Alumno no encontrado.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nuevo_colegio = $_POST['nuevo_colegio_id'];
    $nuevo_curso = $_POST['nuevo_curso_id'];

    // Historial
    $stmt_hist = $conn->prepare("INSERT INTO historial_alumno (alumno_id, curso_id, anio) VALUES (?, ?, YEAR(NOW()))");
    $stmt_hist->bind_param("ii", $alumno_id, $alumno['curso_id']);
    $stmt_hist->execute();

    // Transferencia
    $stmt_trans = $conn->prepare("INSERT INTO transferencias (alumno_id, curso_origen_id, curso_destino_id, colegio_origen_id, colegio_destino_id) VALUES (?, ?, ?, ?, ?)");
    $stmt_trans->bind_param("iiiii", $alumno_id, $alumno['curso_id'], $nuevo_curso, $alumno['colegio_id'], $nuevo_colegio);
    $stmt_trans->execute();

    // Actualizar curso y colegio
    $stmt = $conn->prepare("UPDATE students SET curso_id=?, colegio_id=? WHERE id=?");
    $stmt->bind_param("iii", $nuevo_curso, $nuevo_colegio, $alumno_id);
    $stmt->execute();

    log_accion($conn, $_SESSION['usuario_id'], 'editar', 'alumnos', "Transferido alumno ID $alumno_id a colegio $nuevo_colegio / curso $nuevo_curso");

    header("Location: students.php?transferido=1");
    exit;
}

// Listar colegios y cursos
$colegios = $conn->query("SELECT id, nombre FROM colegios ORDER BY nombre")->fetch_all(MYSQLI_ASSOC);
$cursos = $conn->query("SELECT id, nombre FROM cursos ORDER BY nombre")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Transferir Alumno</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
  <h4>Transferir Alumno: <?= htmlspecialchars($alumno['nombre']) ?></h4>
  <form method="POST" class="card p-4 shadow-sm">
    <div class="mb-3">
      <label>Nuevo Colegio</label>
      <select name="nuevo_colegio_id" class="form-select" required>
        <option value="">Seleccione colegio</option>
        <?php foreach ($colegios as $c): ?>
          <option value="<?= $c[0] ?>"><?= htmlspecialchars($c[1]) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="mb-3">
      <label>Nuevo Curso</label>
      <select name="nuevo_curso_id" class="form-select" required>
        <option value="">Seleccione curso</option>
        <?php foreach ($cursos as $c): ?>
          <option value="<?= $c[0] ?>"><?= htmlspecialchars($c[1]) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <button class="btn btn-warning">Transferir Alumno</button>
  </form>
</div>
</body>
</html>
