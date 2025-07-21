<?php
session_start();
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/conexion.php';

// Verificar autenticación y rol
if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Procesar eliminación de usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar'])) {
    $id = (int)$_POST['id'];
    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->execute([$id]);
    $_SESSION['mensaje'] = "Usuario eliminado correctamente";
    header("Location: usuarios.php");
    exit();
}

// Obtener lista de usuarios
$stmt = $pdo->query("SELECT id, nombre, email, rol FROM usuarios");
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Usuarios | ParkEasy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .table-container { margin: 20px auto; max-width: 1000px; }
        .action-btn { margin-right: 5px; }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../includes/header.php'; ?>
    
    <div class="container table-container">
        <h2 class="mb-4"><i class="bi bi-people-fill"></i> Gestión de Usuarios</h2>
        
        <?php if (isset($_SESSION['mensaje'])): ?>
            <div class="alert alert-success"><?= $_SESSION['mensaje'] ?></div>
            <?php unset($_SESSION['mensaje']); ?>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td><?= htmlspecialchars($usuario['id']) ?></td>
                        <td><?= htmlspecialchars($usuario['nombre']) ?></td>
                        <td><?= htmlspecialchars($usuario['email']) ?></td>
                        <td>
                            <span class="badge bg-<?= $usuario['rol'] === 'admin' ? 'danger' : 'primary' ?>">
                                <?= htmlspecialchars($usuario['rol']) ?>
                            </span>
                        </td>
                        <td>
                            <a href="editar_usuario.php?id=<?= $usuario['id'] ?>" 
                               class="btn btn-sm btn-warning action-btn">
                               <i class="bi bi-pencil"></i> Editar
                            </a>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $usuario['id'] ?>">
                                <button type="submit" name="eliminar" class="btn btn-sm btn-danger"
                                        onclick="return confirm('¿Eliminar este usuario?')">
                                    <i class="bi bi-trash"></i> Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
    <!-- Formulario para agregar nuevo usuario -->
    <form method="POST" action="crear_usuario.php" class="row g-3 mb-4">
        <div class="col-md-4">
            <input type="text" name="nombre" placeholder="Nombre completo" required class="form-control">
        </div>
        <div class="col-md-4">
            <input type="email" name="email" placeholder="Correo electrónico" required class="form-control">
        </div>
        <div class="col-md-2">
            <select name="rol" required class="form-control">
                <option value="">Seleccionar rol</option>
                <option value="admin">Administrador</option>
                <option value="operario">Operario</option>
                <option value="cliente">Cliente</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-plus-circle"></i> Crear
            </button>
        </div>
    </form>

    <!-- Botón para volver al panel -->
    <a href="dashboard.php" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Volver al Panel
    </a>
</div>
    </div>

    <?php include __DIR__ . '/../includes/footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>