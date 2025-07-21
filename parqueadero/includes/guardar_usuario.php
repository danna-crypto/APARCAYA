<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/conexion.php';

// Verificar permisos
if ($_SESSION['rol'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: usuarios.php");
    exit();
}

// Procesamiento de datos
$nombre = trim($_POST['nombre']);
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$rol = $_POST['rol'];

// Validaciones
if (empty($nombre) || empty($email) || !in_array($rol, ['admin', 'operario', 'cliente'])) {
    $_SESSION['error'] = "Datos inválidos o incompletos";
    header("Location: usuarios.php");
    exit();
}

try {
    // Generar credenciales
    $password_temp = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8);
    $password_hash = password_hash($password_temp, PASSWORD_BCRYPT);

    // Insertar en BD
    $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, password, rol) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nombre, $email, $password_hash, $rol]);

    // Configurar email
    $asunto = "Credenciales SATCURER - Parqueadero";
    $mensaje = "
    <!DOCTYPE html>
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background-color: #2c3e50; color: white; padding: 15px; text-align: center; }
            .content { padding: 20px; background-color: #f9f9f9; }
            .credentials { background-color: #e8f4fc; padding: 15px; margin: 15px 0; border-left: 4px solid #3498db; }
            .footer { margin-top: 20px; font-size: 12px; color: #7f8c8d; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>SATCURER - Sistema de Parqueadero</h2>
            </div>
            <div class='content'>
                <p>Estimado/a $nombre,</p>
                <p>Se ha creado una cuenta para usted en nuestro sistema:</p>
                
                <div class='credentials'>
                    <p><strong>Usuario:</strong> $email</p>
                    <p><strong>Contraseña temporal:</strong> $password_temp</p>
                    <p><strong>Tipo de cuenta:</strong> " . ucfirst($rol) . "</p>
                </div>
                
                <p>Acceda al sistema aquí: <a href='http://" . $_SERVER['HTTP_HOST'] . "'>SATCURER</a></p>
                <p><em>Por seguridad, cambie su contraseña después del primer inicio.</em></p>
            </div>
            <div class='footer'>
                <p>Este es un mensaje automático, por favor no responda a este correo.</p>
            </div>
        </div>
    </body>
    </html>
    ";

    // Cabeceras del email
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "From: SATCURER <no-reply@satcurer.com>\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();

    // Envío mediante MailHog
    if (mail($email, $asunto, $mensaje, $headers)) {
        $_SESSION['exito'] = "Usuario creado - Verifique MailHog para ver el email simulado";
    } else {
        $_SESSION['exito'] = "Usuario creado. Contraseña temporal: $password_temp (Error en simulación de email)";
    }

} catch (PDOException $e) {
    if ($e->errorInfo[1] == 1062) {
        $_SESSION['error'] = "Error: El correo electrónico ya está registrado";
    } else {
        $_SESSION['error'] = "Error al crear usuario: " . $e->getMessage();
    }
}

header("Location: usuarios.php");
exit();
?>