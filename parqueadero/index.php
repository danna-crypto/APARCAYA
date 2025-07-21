<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Parqueaderos Online</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Leaflet CSS (¡IMPORTANTE!) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <!-- Tu CSS personalizado -->
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">ParkEasy</a>
            <div class="ml-auto">
                <a href="login.php" class="btn btn-light">Iniciar Sesión</a>
                <a href="registro.php" class="btn btn-outline-light">Registrarse</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1>Reserva tu Parqueadero en Línea</h1>
        <!-- ÚNICO contenedor del mapa -->
        <div id="map" style="height: 500px; width: 100%;" class="mt-4"></div>
    </div>

    <!-- Scripts al final del body -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="assets/js/scripts.js"></script>
    <script>
        // Inicializa el mapa cuando el DOM esté listo
        document.addEventListener('DOMContentLoaded', function() {
            const map = L.map('map').setView([4.7110, -74.0721], 15); // Coordenadas de Bogotá

            // Capa de OpenStreetMap
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);

            // Marcador de ejemplo
            L.marker([4.7110, -74.0721])
                .bindPopup("<b>ParkEasy Centro</b><br>Disponibilidad: 5/10")
                .addTo(map);
        });
    </script>
</body>
</html>

