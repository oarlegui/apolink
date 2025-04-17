<?php
// archivo: index.php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL); 

require 'include/conexion.php';
require 'include/sesion_logger.php'; 

$ip = $_SERVER['REMOTE_ADDR'];
$check = $conn->prepare("SELECT id FROM bloqueos_ip WHERE ip = ?");
$check->bind_param("s", $ip);
$check->execute();
if ($check->get_result()->num_rows > 0) {
    die("Tu IP ha sido bloqueada del sistema.");
}


$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $colegio_id = $_POST['colegio_id'];
    $rut = $_POST['rut'];
    $clave = $_POST['password'];

    $stmt = $conn->prepare("SELECT u.*, uc.colegio_id FROM users u
                            JOIN user_colegio uc ON uc.user_id = u.id
                            WHERE u.rut = ? AND uc.colegio_id = ?");
    $stmt->bind_param("si", $rut, $colegio_id);
    $stmt->execute();
    $usuario = $stmt->get_result()->fetch_assoc();

    if ($usuario && password_verify($clave, $usuario['password'])) {
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['nombre'] = $usuario['nombre'];
        $_SESSION['rol'] = $usuario['rol'];
        $_SESSION['colegio_id'] = $colegio_id;

        // Redirigir según rol
        switch ($usuario['rol']) {
            case 'admin':
                header("Location: dashboard_admin.php");
                break;
            case 'admin_curso':
                header("Location: dashboard_admin_curso.php");
                break;
            case 'tesorero':
                header("Location: dashboard_tesorero.php");
                break;
            case 'apoderado':
                header("Location: dashboard_apoderado_v2.php");
                break;
        }
        exit;
    } else {
        $error = "Datos inválidos.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Login Apolink</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #f8f9fa; }
    .login-box { max-width: 400px; margin: auto; padding-top: 80px; }
  </style>
</head>
<body>
<div class="container login-box">
  <div class="card shadow-sm p-4">
      <!-- Logo del sistema -->
  <img src="/sistema-gestion-curso/includes/assets/images/logo_white.png" alt="Logo" style="max-height: 80px;" class="mb-3">
    <h4 class="text-center mb-4">Bienvenid@</h4>

    <?php if ($error): ?>
      <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="mb-3">
        <label class="form-label">Colegio</label>
        <input type="text" name="colegio_nombre" id="colegio_nombre" class="form-control" required>
        <input type="hidden" name="colegio_id" id="colegio_id">
      </div>
      <div class="mb-3">
        <label class="form-label">RUT</label>
        <input type="text" name="rut" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Contraseña</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <button class="btn btn-primary w-100">Ingresar</button>
    </form>
        <div class="mt-3 text-muted small" align="center">
        ¿Problemas para ingresar?<br>
        Contacte al administrador del colegio.
      </div>
      <div class="mt-4 small text-muted"  align="center">
        Power by<br>
        <img src="https://arlegui-it.cl/arlegui-it-logo.png" alt="Arlegui IT" style="height: 30px;">
      </div>
  </div>
</div>

<!-- jQuery UI Autocomplete + almacenamiento -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

<script>
$(function() {
  $('#colegio_nombre').autocomplete({
    source: 'include/buscar_colegio.php',
    minLength: 2,
    select: function(event, ui) {
      $('#colegio_nombre').val(ui.item.label);
      $('#colegio_id').val(ui.item.value);
      localStorage.setItem('ultimoColegio', ui.item.label);
    }
  });

  const ultimo = localStorage.getItem('ultimoColegio');
  if (ultimo) {
    $('#colegio_nombre').val(ultimo);
  }
});
</script>
</body>
</html>
