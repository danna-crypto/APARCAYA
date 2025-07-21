<?php
require_once('../includes/header.php');
require_once('../includes/auth.php');
checkAuth();
require_once('../includes/conexion.php');

// Debug: Verificar el usuario_id en sesión
echo "User ID en sesión: " . $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM reservas WHERE usuario_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$reservas = $stmt->fetchAll();

echo "<pre>"; 
print_r($reservas); // Debe mostrar tus reservas
exit;
?>

<div class="container mt-4">
    <h2>Mis Reservas</h2>
    
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">¡Reserva creada correctamente!</div>
    <?php endif; ?>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Parqueadero</th>
                <th>Fecha Entrada</th>
                <th>Fecha Salida</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reservas as $reserva): ?>
            <tr>
                <td><?= htmlspecialchars($reserva['parqueadero']) ?></td>
                <td><?= date('d/m/Y H:i', strtotime($reserva['fecha_entrada'])) ?></td>
                <td><?= date('d/m/Y H:i', strtotime($reserva['fecha_salida'])) ?></td>
                <td>
                    <span class="badge bg-<?= 
                        $reserva['estado'] === 'aceptada' ? 'success' : 
                        ($reserva['estado'] === 'rechazada' ? 'danger' : 'warning') 
                    ?>">
                        <?= ucfirst($reserva['estado']) ?>
                    </span>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>