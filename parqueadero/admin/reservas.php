<?php
require_once '../includes/auth.php';
require_once '../includes/conexion.php';
require_once '../includes/funciones.php'; // Añade esta línea

checkAuth(['admin', 'operador']); // Usa la nueva versión de checkAuth()

// Cambiar estado de reserva
if (isset($_GET['action'])) { // Paréntesis corregido aquí
    $reserva_id = $_GET['id'];
    $estado = ($_GET['action'] === 'aprobar') ? 'aceptada' : 'rechazada';

    $stmt = $pdo->prepare("UPDATE reservas SET estado = ? WHERE id = ?");
    $stmt->execute([$estado, $reserva_id]);
    header("Location: reservas.php?success=1");
    exit();
}

// Obtener reservas pendientes
$stmt = $pdo->query("SELECT r.*, u.nombre as cliente FROM reservas r JOIN usuarios u ON r.usuario_id = u.id WHERE r.estado = 'pendiente'");
$reservas = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Gestionar Reservas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <?php include('../includes/header.php'); ?>
    
    <div class="container mt-4">
        <h2><i class="bi bi-list-check"></i> Reservas Pendientes</h2>
        
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">¡Estado de reserva actualizado!</div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Cliente</th>
                        <th>Fecha Entrada</th>
                        <th>Fecha Salida</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reservas as $reserva): ?>
                    <tr>
                        <td><?= htmlspecialchars($reserva['cliente']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($reserva['fecha_entrada'])) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($reserva['fecha_salida'])) ?></td>
                        <td>
                            <a href="reservas.php?action=aprobar&id=<?= $reserva['id'] ?>" class="btn btn-success btn-sm">
                                <i class="bi bi-check-circle"></i> Aprobar
                            </a>
                            <a href="reservas.php?action=rechazar&id=<?= $reserva['id'] ?>" class="btn btn-danger btn-sm">
                                <i class="bi bi-x-circle"></i> Rechazar
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>