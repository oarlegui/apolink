<?php
// archivo: include/sesion_logger.php
// Registra sesiones activas al iniciar sesión

function registrar_sesion_activa($conn, $usuario_id) {
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'IP_DESCONOCIDA';
    $stmt = $conn->prepare("INSERT INTO sesiones_activas (usuario_id, ip) VALUES (?, ?)");
    $stmt->bind_param("is", $usuario_id, $ip);
    $stmt->execute();
}
