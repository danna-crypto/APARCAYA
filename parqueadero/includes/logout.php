<?php
// Inicia la sesión (si no está iniciada)
session_start();

// Destruye todas las variables de sesión
session_unset();

// Destruye la sesión
session_destroy();

// Redirige a la página principal (index.php o login.php)
header("Location: http://localhost/parqueadero/index.php"); // Asegúrate de que la ruta sea correcta
exit(); // Termina el script
?>