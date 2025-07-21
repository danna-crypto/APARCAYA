<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/conexion.php';

// Solo administradores pueden crear usuarios
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: usuarios.php");
    exit();
}

// Obtener y validar datos
$nombre = trim($_POST['nombre']);
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$rol = $_POST['rol'];

// Validaciones básicas
if (empty($nombre) || empty($email) || !in_array($rol, ['admin', 'operario', 'cliente'])) {
    $_SESSION['error'] = "Datos inválidos";
    header("Location: usuarios.php");
    exit();
}

try {
    // Generar contraseña temporal más segura
    $password_temp = bin2hex(random_bytes(4)); // 8 caracteres aleatorios
    $password_hash = password_hash($password_temp, PASSWORD_BCRYPT);

    // Insertar en la base de datos
    $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, password, rol) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nombre, $email, $password_hash, $rol]);

    // Configurar y enviar email
    $asunto = "Tu cuenta en ParkEasy ha sido creada";
    $mensaje = "
    <html>
    <head>
        <title>Bienvenido a ParkEasy</title>
        <style>
            body { font-family: Arial, sans-serif; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background-color: #007bff; color: white; padding: 10px; text-align: center; }
            .content { padding: 20px; }
            .credentials { background-color: #f8f9fa; padding: 15px; border-radius: 5px; }
            .footer { margin-top: 20px; font-size: 12px; color: #6c757d; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>ParkEasy - Gestión de Parqueaderos</h2>
            </div>
            <div class='content'>
                <p>Hola $nombre,</p>
                <p>El administrador ha creado una cuenta para ti en nuestro sistema.</p>
                
                <div class='credentials'>
                    <p><strong>Tus credenciales de acceso:</strong></p>
                    <p>Email: $email</p>
                    <p>Contraseña temporal: <strong>$password_temp</strong></p>
                    <p>Rol: " . ucfirst($rol) . "</p>
                </div>
                
                <p>Por seguridad, te recomendamos cambiar tu contraseña después de iniciar sesión.</p>
                <a href='http://" . $_SERVER['HTTP_HOST'] . "/login.php' style='background-color: #007bff; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; display: inline-block; margin-top: 10px;'>Iniciar Sesión</a>
            </div>
            <div class='footer'>
                <p>Este es un mensaje automático, por favor no respondas a este correo.</p>
                <p>&copy; " . date('Y') . " ParkEasy. Todos los derechos reservados.</p>
            </div>
        </div>
    </body>
    </html>
    ";

    // Cabeceras para email HTML
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";
    $headers .= "From: no-reply@parkeasy.com\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();

    // Intentar enviar el email
    if (mail($email, $asunto, $mensaje, $headers)) {
        $_SESSION['exito'] = "Usuario creado exitosamente. Las credenciales se han enviado al email proporcionado.";
    } else {
        // Fallback si el envío de email falla
        $_SESSION['exito'] = "Usuario creado exitosamente. Contraseña temporal: $password_temp (No se pudo enviar el email)";
    }
    
} catch (PDOException $e) {
    // Manejar error de duplicado de email
    if ($e->errorInfo[1] === 1062) {
        $_SESSION['error'] = "El email ya está registrado";
    } else {
        $_SESSION['error'] = "Error al crear usuario: " . $e->getMessage();
    }
}

header("Location: usuarios.php");
exit();
?>