<?php
// archivo: include/buscar_colegio.php
// Devuelve un listado JSON con los colegios que coincidan con la búsqueda

require 'conexion.php';

$term = $_GET['term'] ?? '';
if (!$term) {
    echo json_encode([]);
    exit;
}

$stmt = $conn->prepare("SELECT id, nombre FROM colegios WHERE nombre LIKE ? ORDER BY nombre LIMIT 10");
$like = "%$term%";
$stmt->bind_param("s", $like);
$stmt->execute();
$result = $stmt->get_result();

$colegios = [];
while ($row = $result->fetch_assoc()) {
    $colegios[] = [
        'label' => $row['nombre'],
        'value' => $row['id']
    ];
}

echo json_encode($colegios);
exit;
