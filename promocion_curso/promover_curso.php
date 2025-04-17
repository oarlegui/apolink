<?php
// archivo: promover_curso.php
// Promueve a todos los alumnos de un curso al siguiente nivel. Solo accesible para admin.

session_start();
require '../include/conexion.php';
require '../include/log_auditoria.php';
require '../include/seguridad.php';

require_login();
if ($_SESSION['rol'] !== 'admin') {
    die("Acceso denegado");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $curso_origen_id = $_POST['curso_origen_id'];
    $curso_destino_id = $_POST['curso_destino_id'];

    // Obtener alumnos actuales
    $stmt = $conn->prepare("SELECT * FROM students WHERE curso_id = ?");
    $stmt->bind_param("i", $curso_origen_id);
    $stmt->execute();
    $alumnos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    foreach ($alumnos as $alumno) {
        // Guardar historial
        $stmt_hist = $conn->prepare("INSERT INTO historial_alumno (alumno_id, curso_id, anio) VALUES (?, ?, YEAR(NOW()))");
        $stmt_hist->bind_param("ii", $alumno['id'], $curso_origen_id);
        $stmt_hist->execute();

        // Transferir alumno (salvando saldo si aplica)
        $stmt_trans = $conn->prepare("INSERT INTO transferencias (alumno_id, curso_origen_id, curso_destino_id, colegio_origen_id, colegio_destino_id) VALUES (?, ?, ?, ?, ?)");
        $stmt_trans->bind_param("iiiii", $alumno['id'], $curso_origen_id, $curso_destino_id, $alumno['colegio_id'], $alumno['colegio_id']);
        $stmt_trans->execute();

        // Actualizar curso actual
        $stmt_upd = $conn->prepare("UPDATE students SET curso_id = ? WHERE id = ?");
        $stmt_upd->bind_param("ii", $curso_destino_id, $alumno['id']);
        $stmt_upd->execute();
    }

    log_accion($conn, $_SESSION['usuario_id'], 'editar', 'cursos', "Promovió curso $curso_origen_id a $curso_destino_id con " . count($alumnos) . " alumnos.");

    header("Location: cursos.php?promovido=1");
    exit;
}

// Obtener lista de cursos
$cursos = $conn->query("SELECT id, nombre FROM cursos ORDER BY nombre")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Promover Curso</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
  <h4>Promover Alumnos de un Curso al Siguiente Año</h4>
  <form method="POST" class="card p-4 shadow-sm">
    <div class="mb-3">
      <label>Curso de Origen</label>
      <select name="curso_origen_id" class="form-select" required>
        <option value="">Seleccione curso</option>
        <?php foreach ($cursos as $c): ?>
          <option value="<?= $c[0] ?>"><?= htmlspecialchars($c[1]) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="mb-3">
      <label>Curso de Destino</label>
      <select name="curso_destino_id" class="form-select" required>
        <option value="">Seleccione curso</option>
        <?php foreach ($cursos as $c): ?>
          <option value="<?= $c[0] ?>"><?= htmlspecialchars($c[1]) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <button class="btn btn-primary">Ejecutar Promoción</button>
  </form>
</div>
</body>
</html>
