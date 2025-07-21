<?php
session_start();
require_once 'includes/conexion.php';

// Verificar si ya está logueado
if (isset($_SESSION['user_id'])) {
    header("Location: " . ($_SESSION['rol'] === 'admin' ? 'admin/dashboard.php' : 'cliente/dashboard.php'));
    exit();
}

// Procesar el formulario si se envió
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    if ($email && $password) {
        $stmt = $pdo->prepare("SELECT id, nombre, email, password, rol FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Regenerar ID de sesión por seguridad
            session_regenerate_id(true);
            
            // Establecer datos de sesión
            $_SESSION = [
                'user_id' => $user['id'],
                'nombre' => $user['nombre'],
                'email' => $user['email'],
                'rol' => $user['rol'],
                'logged_in' => true,
                'last_activity' => time()
            ];

            // Redirigir según rol
            switch ($user['rol']) {
                case 'admin':
                    header("Location: admin/dashboard.php");
                    break;
                case 'operario':
                    header("Location: operario/dashboard.php");
                    break;
                default: // cliente
                    header("Location: cliente/dashboard.php");
            }
            exit();
        }

        } else {
            $error = "Email o contraseña incorrectos";
        }
    } else {
        $error = "Por favor complete todos los campos";
    }

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ParkEasy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .login-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <h2 class="text-center mb-4">Iniciar Sesión</h2>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <!-- En tu archivo login.php (formulario existente) -->
<form method="POST" action="login.php">
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Contraseña" required>
    <button type="submit">Ingresar</button>
</form>
        </div>
    </div>
</body>
</html>