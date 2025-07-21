// Validación de formulario de reserva
document.addEventListener('DOMContentLoaded', function() {
    const formReserva = document.getElementById('form-reserva');
    if (formReserva) {
        formReserva.addEventListener('submit', function(e) {
            const fechaEntrada = document.getElementById('fecha_entrada').value;
            const fechaSalida = document.getElementById('fecha_salida').value;
            
            if (new Date(fechaSalida) <= new Date(fechaEntrada)) {
                e.preventDefault();
                alert('La fecha de salida debe ser posterior a la de entrada');
            }
        });
    }

    // Cargar mapa con Leaflet (si existe el div #map)
    if (document.getElementById('map')) {
        const map = L.map('map').setView([4.7110, -74.0721], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

        // Ejemplo: Añadir marcadores desde una API (ajusta la URL)
        fetch('api/parqueaderos.php')
            .then(response => response.json())
            .then(data => {
                data.forEach(parq => {
                    L.marker([parq.latitud, parq.longitud])
                        .bindPopup(`<b>${parq.nombre}</b><br>Capacidad: ${parq.capacidad}`)
                        .addTo(map);
                });
            });
    }
});