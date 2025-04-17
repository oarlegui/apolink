<?php
// archivo: empresas.php
// Muestra un listado público de empresas con buscador y paginación.
ini_set('display_errors', 1);
error_reporting(E_ALL);

require '../include/conexion.php';

$buscar = $_GET['buscar'] ?? '';
$page = $_GET['page'] ?? 1;
$pageSize = 10;
$offset = ($page - 1) * $pageSize;

$condiciones = [];
$parametros = [];
$tipos = '';

if ($buscar) {
    $condiciones[] = "(nombre LIKE ? OR descripcion LIKE ?)";
    $parametros[] = "%$buscar%";
    $parametros[] = "%$buscar%";
    $tipos .= "ss";
}

$where = $condiciones ? "WHERE " . implode(" AND ", $condiciones) : "";

$stmt_total = $conn->prepare("SELECT COUNT(*) as total FROM empresas $where");
if ($parametros) $stmt_total->bind_param($tipos, ...$parametros);
$stmt_total->execute();
$total = $stmt_total->get_result()->fetch_assoc()['total'];
$totalPages = ceil($total / $pageSize);

$query = "SELECT * FROM empresas $where ORDER BY nombre LIMIT $offset, $pageSize";
$stmt = $conn->prepare($query);
if ($parametros) $stmt->bind_param($tipos, ...$parametros);
$stmt->execute();
$empresas = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Empresas y Servicios</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .empresa-card { border-left: 4px solid #0d6efd; padding: 10px; margin-bottom: 15px; background-color: #f8f9fa; }
    .empresa-card:hover { background-color: #e9ecef; cursor: pointer; }
  </style>
</head>
<body>
<div class="container mt-4">
  <h4>Empresas que ofrecen servicios útiles para el curso</h4>
  <form class="mb-4" method="GET">
    <input type="text" name="buscar" class="form-control" placeholder="Buscar por nombre o descripción" value="<?= htmlspecialchars($buscar) ?>">
  </form>

  <?php foreach ($empresas as $e): ?>
    <a href="empresa_detail.php?id=<?= $e['id'] ?>" class="text-decoration-none text-dark">
      <div class="empresa-card shadow-sm rounded p-3">
        <div class="d-flex justify-content-between align-items-center">
          <h5 class="mb-0"><?= htmlspecialchars($e['nombre']) ?></h5>
          <img src="../uploads/empresas/<?= $e['imagen_principal'] ?>" height="40">
        </div>
        <small class="text-muted"><?= $e['region'] ?> - <?= $e['comuna'] ?></small>
        <p class="mb-0 mt-2"><?= substr(htmlspecialchars($e['descripcion']), 0, 120) ?>...</p>
      </div>
    </a>
  <?php endforeach; ?>

  <!-- Paginación -->
  <?php if ($totalPages > 1): ?>
    <nav>
      <ul class="pagination justify-content-center">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
          <li class="page-item <?= $i == $page ? 'active' : '' ?>">
            <a class="page-link" href="?buscar=<?= urlencode($buscar) ?>&page=<?= $i ?>"><?= $i ?></a>
          </li>
        <?php endfor; ?>
      </ul>
    </nav>
  <?php endif; ?>
</div>
</body>
</html>
