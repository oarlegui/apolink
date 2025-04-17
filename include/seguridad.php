<?php
// archivo: include/seguridad.php

function require_login() {
    if (!isset($_SESSION['usuario_id'])) {
        header("Location: index.php");
        exit;
    }
}

function require_rol($roles_permitidos = []) {
    if (!isset($_SESSION['rol']) || !in_array($_SESSION['rol'], $roles_permitidos)) {
        redirigir_dashboard(); // 🚫 Redirige al dashboard correcto si no tiene permiso
        exit;
    }
}

function redirigir_dashboard() {
  $actual = basename($_SERVER['PHP_SELF']);
  $rol = $_SESSION['rol'] ?? null;

  switch ($rol) {
    case 'admin':
        if ($actual !== 'dashboard_admin.php') header("Location: dashboard_admin.php");
        break;
    case 'admin_curso':
        if ($actual !== 'dashboard_admin_curso.php') header("Location: dashboard_admin_curso.php");
        break;
    case 'tesorero':
        if ($actual !== 'dashboard_tesorero.php') header("Location: dashboard_tesorero.php");
        break;
    case 'apoderado':
        if ($actual !== 'dashboard_apoderado.php') header("Location: dashboard_apoderado.php");
        break;
    default:
        header("Location: index.php");
  }

  exit;
}

