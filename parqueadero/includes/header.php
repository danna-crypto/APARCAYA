<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?? 'ParkEasy' ?></title>
    <!-- Bootstrap CSS + Íconos (CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <!-- Logo con ícono de Bootstrap (sin imagen local) -->
                <i class="bi bi-p-square-fill me-2"></i> <!-- Ícono de parking -->
                ParkEasy
            </a>
            <!-- En includes/header.php -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
            <div class="navbar-nav ms-auto">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <span class="navbar-text me-3">¡Hola, <?= htmlspecialchars($_SESSION['nombre']) ?>!</span>
                    <a class="nav-link" href="logout.php">Cerrar Sesión</a>
                <?php else: ?>
                    <a class="nav-link" href="login.php">Iniciar Sesión</a>
                    <a class="nav-link" href="registro.php">Registrarse</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <div class="container mt-4">