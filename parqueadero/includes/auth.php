<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Configuración de seguridad
define('SESSION_TIMEOUT', 1800); // 30 minutos

function isAuthenticated() {
    return isset(
        $_SESSION['user_id'],
        $_SESSION['logged_in'],
        $_SESSION['rol'],
        $_SESSION['last_activity']
    ) && $_SESSION['logged_in'] === true;
}

function requireAuth() {
    // Verificar timeout
    if (isset($_SESSION['last_activity']) && 
       (time() - $_SESSION['last_activity'] > SESSION_TIMEOUT)) {
        session_unset();
        session_destroy();
        header("Location: ../login.php?error=timeout");
        exit();
    }

    // Actualizar actividad
    $_SESSION['last_activity'] = time();

    // Verificar autenticación
    if (!isAuthenticated()) {
        $_SESSION['redirect_to'] = $_SERVER['REQUEST_URI'];
        header("Location: ../login.php");
        exit();
    }
}

function requireAdmin() {
    requireAuth();
    if ($_SESSION['rol'] !== 'admin') {
        header("Location: ../acceso-denegado.php");
        exit();
    }
}

// Función checkAuth() actualizada
function checkAuth($rolRequerido = null) {
    requireAuth();
    
    if ($rolRequerido !== null) {
        $rolesPermitidos = is_array($rolRequerido) ? $rolRequerido : [$rolRequerido];
        
        if (!in_array($_SESSION['rol'], $rolesPermitidos)) {
            header("Location: ../acceso-denegado.php");
            exit();
        }
    }
}
?>