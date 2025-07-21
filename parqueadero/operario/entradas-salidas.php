<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/conexion.php';

// Solo operarios pueden registrar
if ($_SESSION['rol'] !== 'operario') {
    header("Location: ../acceso-denegado.php");
    exit();
}

// Procesar registro de entrada/salida
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vehiculo_id = $_POST['vehiculo_id'];
    $tipo = $_POST['tipo']; // 'entrada' o 'salida'
    
    // Insertar registro
    $stmt = $pdo->prepare("INSERT INTO registros (vehiculo_id, operario_id, tipo, fecha_hora) 
                          VALUES (?, ?, ?, NOW())");
    $stmt->execute([$vehiculo_id, $_SESSION['user_id'], $tipo]);
}
?>
<!-- Formulario para registrar movimientos -->