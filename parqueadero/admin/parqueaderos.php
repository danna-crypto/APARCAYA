<?php
require_once('../includes/header.php');
require_once('../includes/auth.php');
checkAuth();

// Solo para administradores/operadores
if ($_SESSION['rol'] !== 'operador' && $_SESSION['rol'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

require_once('../includes/conexion.php');

// Añadir nuevo parqueadero
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $direccion = $_POST['direccion'];
    $capacidad = $_POST['capacidad'];
    
    $stmt = $pdo->prepare("INSERT INTO parqueaderos (nombre, direccion, capacidad) VALUES (?, ?, ?)");
    $stmt->execute([$nombre, $direccion, $capacidad]);
}
?>

<div class="container mt-4">
    <h2><i class="bi bi-p-square"></i> Registrar Parqueadero</h2>
    
    <form method="POST" class="mb-5">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Nombre</label>
                <input type="text" class="form-control" name="nombre" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Dirección</label>
                <input type="text" class="form-control" name="direccion" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Capacidad</label>
                <input type="number" class="form-control" name="capacidad" required>
            </div>
            <div class="col-md-12">
                <button type="submit" class="btn btn-success mt-3">
                    <i class="bi bi-save"></i> Guardar
                </button>
            </div>
        </div>
    </form>

    <h3 class="mt-5">Parqueaderos Registrados</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Dirección</th>
                <th>Capacidad</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $parqueaderos = $pdo->query("SELECT * FROM parqueaderos")->fetchAll();
            foreach ($parqueaderos as $p): ?>
            <tr>
                <td><?= htmlspecialchars($p['nombre']) ?></td>
                <td><?= htmlspecialchars($p['direccion']) ?></td>
                <td><?= $p['capacidad'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include('../includes/footer.php'); ?>