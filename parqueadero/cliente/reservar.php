<?php
require_once('../includes/auth.php');
checkAuth();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once('../includes/conexion.php');
    
    // Validar y obtener datos del formulario
    $usuario_id = $_SESSION['user_id'];
    $fecha_entrada = $_POST['fecha_entrada'];
    $fecha_salida = $_POST['fecha_salida'];
    $parqueadero_id = $_POST['parqueadero_id'];

    // Validación de fechas
    if (strtotime($fecha_salida) <= strtotime($fecha_entrada)) {
        die("<div class='alert alert-danger'>Error: La fecha de salida debe ser posterior a la de entrada</div>");
    }

    // Validación de parqueadero
    if (empty($parqueadero_id)) {
        die("<div class='alert alert-danger'>Error: Debes seleccionar un parqueadero</div>");
    }

    try {
        // Consulta SQL con manejo de errores
        $stmt = $pdo->prepare("INSERT INTO reservas (usuario_id, parqueadero_id, fecha_entrada, fecha_salida, estado) VALUES (?, ?, ?, ?, 'pendiente')");
        
        $stmt->execute([$usuario_id, $parqueadero_id, $fecha_entrada, $fecha_salida]);
        
        // Redirigir después de guardar
        header("Location: mis_reservas.php?success=1");
        exit();
    } catch (PDOException $e) {
        die("<div class='alert alert-danger'>Error al guardar la reserva: " . $e->getMessage() . "</div>");
    }
}

// Obtener lista de parqueaderos para el select
require_once('../includes/conexion.php');
$parqueaderos = $pdo->query("SELECT id, nombre FROM parqueaderos")->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Reserva</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .form-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <?php include('../includes/header.php'); ?>
    
    <div class="container mt-5">
        <div class="form-container bg-white">
            <h2 class="text-center mb-4"><i class="bi bi-calendar-plus"></i> Nueva Reserva</h2>
            
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Parqueadero</label>
                    <select name="parqueadero_id" class="form-select" required>
                        <option value="">Seleccione un parqueadero</option>
                        <?php foreach ($parqueaderos as $p): ?>
                            <option value="<?= htmlspecialchars($p['id']) ?>">
                                <?= htmlspecialchars($p['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Fecha de Entrada</label>
                    <input type="datetime-local" class="form-control" name="fecha_entrada" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Fecha de Salida</label>
                    <input type="datetime-local" class="form-control" name="fecha_salida" required>
                </div>
                
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Reservar
                    </button>
                    <a href="mis_reservas.php" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Volver a mis reservas
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Validación adicional en el cliente
        document.querySelector('form').addEventListener('submit', function(e) {
            const entrada = new Date(document.querySelector('[name="fecha_entrada"]').value);
            const salida = new Date(document.querySelector('[name="fecha_salida"]').value);
            
            if (salida <= entrada) {
                e.preventDefault();
                alert('La fecha de salida debe ser posterior a la de entrada');
            }
        });
    </script>
</body>
</html>