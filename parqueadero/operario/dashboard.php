<?php
require_once __DIR__ . '/../includes/auth.php';

// Verificar que sea operario
if ($_SESSION['rol'] !== 'operario') {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Panel Operario | ParkEasy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/../includes/header.php'; ?>
    
    <div class="container mt-4">
        <h2>Bienvenido Operario, <?= htmlspecialchars($_SESSION['nombre']) ?></h2>
        
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h4><i class="bi bi-car-front"></i> Operaciones</h4>
                    </div>
                    <div class="list-group list-group-flush">
                        <a href="reservas.php" class="list-group-item list-group-item-action">
                            <i class="bi bi-calendar-check"></i> Gestionar Reservas Activas
                        </a>
                        <a href="entradas-salidas.php" class="list-group-item list-group-item-action">
                            <i class="bi bi-stopwatch"></i> Registrar Entradas/Salidas
                        </a>
                        <a href="pagos.php" class="list-group-item list-group-item-action">
                            <i class="bi bi-cash-coin"></i> Procesar Pagos
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>