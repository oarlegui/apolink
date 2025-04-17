<?php
// archivo: funciones.php
// Contiene funciones reutilizables de Apolink (validaciones, formatos, etc.)

// Validar formato básico de RUT chileno
function validar_rut($rut) {
    return preg_match('/^[0-9]{7,8}-[kK0-9]$/', $rut);
}

// Formatear número como CLP
function formatear_monto($monto) {
    return '$' . number_format($monto, 0, ',', '.');
}

// Formatear fecha a DD-MM-YYYY
function formatear_fecha($fecha_mysql) {
    return date('d-m-Y', strtotime($fecha_mysql));
}
?>