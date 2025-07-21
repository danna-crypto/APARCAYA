<?php
require_once('../includes/auth.php');
require_once('../includes/conexion.php');
require_once('../includes/funciones.php');
requireAdmin();

// Verificar que sea admin
if ($_SESSION['rol'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Panel de <?= ucfirst($_SESSION['rol']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <style>
        /* Estilos para mantener tu diseño original pero con mejoras */
        .card-custom {
            border: none;
            border-radius: 0;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .card-header-custom {
            background-color: #000;
            color: white;
            border-radius: 0;
            padding: 15px 20px;
        }
        .list-group-item-custom {
            border-left: none;
            border-right: none;
            padding: 12px 20px;
            display: flex;
            align-items: center;
        }
        .list-group-item-custom:last-child {
            border-bottom: none;
        }
        .stat-badge {
            font-size: 1rem;
            padding: 5px 10px;
            min-width: 60px;
            display: inline-block;
            text-align: center;
        }
        .icon-box {
            width: 30px;
            text-align: center;
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <?php include('../includes/header.php'); ?>
    
    <div class="container mt-4">
        <h1 class="mb-4">Bienvenido, <?= htmlspecialchars($_SESSION['nombre']) ?></h1>
        
        <div class="row">
            <!-- Sección Operativa - Manteniendo tu estilo de caja negra -->
            <div class="col-md-6 mb-4">
                <div class="card card-custom">
                    <div class="card-header card-header-custom">
                        <h3 class="mb-0"><i class="bi bi-gear icon-box"></i>Gestión Operativa</h3>
                    </div>
                    <div class="list-group list-group-flush">
                        <a href="reservas.php" class="list-group-item list-group-item-custom">
                            <i class="bi bi-calendar-check icon-box text-primary"></i>
                            Gestionar Reservas
                            <span class="badge bg-primary stat-badge ms-auto"><?= obtenerReservasHoy($pdo) ?></span>
                        </a>
                        
                        <?php if ($_SESSION['rol'] === 'admin'): ?>
                        <a href="parqueaderos.php" class="list-group-item list-group-item-custom">
                            <i class="bi bi-p-square icon-box text-success"></i>
                            Administrar Parqueaderos
                        </a>
                        <a href="/parqueadero/admin/usuarios.php" class="list-group-item list-group-item-custom">
                            <i class="bi bi-people icon-box text-info"></i>
                            Gestionar Usuarios
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Sección Estadísticas - Manteniendo tu estilo de caja negra -->
            <div class="col-md-6">
                <div class="card card-custom">
                    <div class="card-header card-header-custom">
                        <h3 class="mb-0"><i class="bi bi-graph-up icon-box"></i>Estadísticas</h3>
                    </div>
                    <div class="list-group list-group-flush">
                        <div class="list-group-item list-group-item-custom">
                            <i class="bi bi-calendar-day icon-box text-warning"></i>
                            Reservas hoy
                            <span class="badge bg-dark stat-badge ms-auto"><?= obtenerReservasHoy($pdo) ?></span>
                        </div>
                        
                        <?php if ($_SESSION['rol'] === 'admin'): ?>
                        <div class="list-group-item list-group-item-custom">
                            <i class="bi bi-currency-dollar icon-box text-success"></i>
                            Ingresos mensuales
                            <span class="badge bg-dark stat-badge ms-auto">$<?= number_format(obtenerIngresos($pdo), 2) ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include('../includes/footer.php'); ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>