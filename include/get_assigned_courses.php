<?php
// archivo: get_assigned_courses.php
// Este archivo retorna un array con los cursos asignados al usuario actual según su rol.
// Roles soportados: admin, admin_curso, tesorero, apoderado

function get_assigned_courses($conn, $usuario_id, $rol) {
    // Admin global: tiene acceso a todos los cursos
    if ($rol === 'admin') {
        $result = $conn->query("SELECT id FROM cursos");
        return array_column($result->fetch_all(MYSQLI_ASSOC), 'id');
    }

    // Admin de curso o tesorero: cursos asignados explícitamente
    if (in_array($rol, ['admin_curso', 'tesorero'])) {
        $stmt = $conn->prepare("SELECT curso_id FROM curso_usuario WHERE user_id = ?");
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return array_column($result->fetch_all(MYSQLI_ASSOC), 'curso_id');
    }

    // Apoderado: cursos donde tiene hijos asignados (opcional, si aplica)
    if ($rol === 'apoderado') {
        $stmt = $conn->prepare("SELECT DISTINCT s.curso_id
                                FROM alumno_apoderado aa
                                JOIN students s ON aa.alumno_id = s.id
                                WHERE aa.apoderado_id = ?");
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return array_column($result->fetch_all(MYSQLI_ASSOC), 'curso_id');
    }

    // En caso de rol no reconocido
    return [];
}
?>