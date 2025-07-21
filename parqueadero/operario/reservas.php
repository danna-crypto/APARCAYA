<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/conexion.php';

// Solo operarios y admin pueden ver
if ($_SESSION['rol'] === 'cliente') {
    header("Location: ../acceso-denegado.php");
    exit();
}

// Obtener reservas activas (solo para el día actual)
$fecha_hoy = date('Y-m-d');
$stmt = $pdo->prepare("SELECT * FROM reservas WHERE fecha = ? AND estado = 'confirmada'");
$stmt->execute([$fecha_hoy]);
$reservas = $stmt->fetchAll();
?>
<!-- HTML para mostrar las reservas -->