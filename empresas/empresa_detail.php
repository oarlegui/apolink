<?php
// archivo: empresa_detail.php
// Muestra la información de una empresa con imágenes y clics contables en email/teléfono.

require '../include/conexion.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    echo "ID no válido.";
    exit;
}

// Incrementar contador si clic en contacto
if (isset($_GET['click']) && in_array($_GET['click'], ['tel', 'email'])) {
    $campo = $_GET['click'] === 'tel' ? 'clicks_tel' : 'clicks_email';
    $stmt = $conn->prepare("UPDATE empresas SET $campo = $campo + 1 WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: empresa_detail.php?id=" . $id);
    exit;
}

$stmt = $conn->prepare("SELECT * FROM empresas WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$empresa = $stmt->get_result()->fetch_assoc();

if (!$empresa) {
    echo "Empresa no encontrada.";
    exit;
}

$imagenes = array_filter([$empresa['imagen1'], $empresa['imagen2'], $empresa['imagen3']]);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($empresa['nombre']) ?> - Detalle</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .galeria img { height: 150px; object-fit: cover; border-radius: 10px; }
  </style>
</head>
<body>
<div class="container mt-4">
  <h3><?= htmlspecialchars($empresa['nombre']) ?></h3>
  <p class="text-muted"><?= $empresa['region'] ?> / <?= $empresa['comuna'] ?></p>
  <p><?= nl2br(htmlspecialchars($empresa['descripcion'])) ?></p>

  <div class="mb-3">
    <img src="../uploads/empresas/<?= $empresa['imagen_principal'] ?>" class="img-fluid rounded shadow-sm" alt="Imagen principal">
  </div>

  <h5>Galería</h5>
  <div class="row galeria mb-4">
    <?php foreach ($imagenes as $img): ?>
      <div class="col-md-4 mb-2">
        <img src="../uploads/empresas/<?= $img ?>" class="img-fluid shadow-sm">
      </div>
    <?php endforeach; ?>
    <?php if (empty($imagenes)): ?>
      <p class="text-muted">Sin imágenes adicionales.</p>
    <?php endif; ?>
  </div>

  <h5>Contacto</h5>
  <div class="mb-3">
    <a href="empresa_detail.php?id=<?= $id ?>&click=tel" class="btn btn-outline-primary me-2"><?= $empresa['telefono'] ?></a>
    <a href="empresa_detail.php?id=<?= $id ?>&click=email" class="btn btn-outline-secondary"><?= $empresa['email'] ?></a>
  </div>

  <small class="text-muted">Clics Tel: <?= $empresa['clicks_tel'] ?> — Email: <?= $empresa['clicks_email'] ?></small>
</div>
</body>
</html>
