<?php
// archivo: include/log_auditoria.php
// Registra acciones del sistema en la tabla auditoria

function log_accion($conn, $usuario_id, $accion, $modulo, $detalle) {
    $stmt = $conn->prepare("INSERT INTO auditoria (usuario_id, accion, modulo, detalle, fecha) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("isss", $usuario_id, $accion, $modulo, $detalle);
    $stmt->execute();
}
?>
