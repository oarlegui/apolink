<?php
// archivo: modulos.php
// Gestión de activación/desactivación de módulos por curso y colegio. Solo para admin.
ini_set('display_errors', 1);
error_reporting(E_ALL); 

session_start();
require '../include/conexion.php';
require '../include/seguridad.php';

require_login();
if ($_SESSION['rol'] !== 'admin') {
    die("Acceso denegado.");
}

// Listar colegios y cursos
$colegios = $conn->query("SELECT id, nombre FROM colegios ORDER BY nombre")->fetch_all(MYSQLI_ASSOC);
$cursos = $conn->query("SELECT id, nombre FROM cursos ORDER BY nombre")->fetch_all(MYSQLI_ASSOC);

// Módulos disponibles
$modulos = ['alumnos', 'apoderados', 'pagos', 'gastos', 'eventos', 'reportes', 'empresas'];

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $colegio_id = $_POST['colegio_id'];
    $curso_id = $_POST['curso_id'];

    foreach ($modulos as $modulo) {
        $activo = isset($_POST["modulo_$modulo"]) ? 1 : 0;

        // Insertar o actualizar
        $stmt = $conn->prepare("INSERT INTO curso_modulo (curso_id, colegio_id, modulo, activo)
            VALUES (?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE activo = VALUES(activo)");
        $stmt->bind_param("iisi", $curso_id, $colegio_id, $modulo, $activo);
        $stmt->execute();
    }

    $mensaje = "Módulos actualizados correctamente.";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Configuración de Módulos</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
  <h4>Activar o desactivar módulos por curso y colegio</h4>
  <?php if ($mensaje): ?>
    <div class="alert alert-success"><?= $mensaje ?></div>
  <?php endif; ?>
  <form method="POST" class="card p-4 shadow-sm">
    <div class="mb-3">
      <label>Colegio</label>
      <select name="colegio_id" class="form-select" required>
        <option value="">Seleccione colegio</option>
        <?php foreach ($colegios as $c): ?>
          <option value="<?= $c[0] ?>"><?= htmlspecialchars($c[1]) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="mb-3">
      <label>Curso</label>
      <select name="curso_id" class="form-select" required>
        <option value="">Seleccione curso</option>
        <?php foreach ($cursos as $c): ?>
          <option value="<?= $c[0] ?>"><?= htmlspecialchars($c[1]) ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <h6 class="mt-4">Módulos disponibles</h6>
    <?php foreach ($modulos as $mod): ?>
      <div class="form-check form-switch">
        <input class="form-check-input" type="checkbox" name="modulo_<?= $mod ?>" id="mod_<?= $mod ?>" checked>
        <label class="form-check-label" for="mod_<?= $mod ?>"><?= ucfirst($mod) ?></label>
      </div>
    <?php endforeach; ?>

    <button class="btn btn-primary mt-4">Guardar configuración</button>
  </form>
</div>
</body>
</html>
