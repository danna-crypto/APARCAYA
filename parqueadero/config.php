<?php
// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'parqueaderos_db');

// Configuración de correo (para notificaciones)
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_USER', 'tucorreo@gmail.com');
define('SMTP_PASSWORD', 'tucontraseña');

// Otras configuraciones
define('SITE_URL', 'http://localhost/parqueaderos');
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>