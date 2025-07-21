<?php
require_once '../includes/header.php';
require_once '../includes/auth.php';
checkAuth();
?>
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Reservar Parqueadero</h5>
                <a href="reservar.php" class="btn btn-primary">Nueva Reserva</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Mis Reservas</h5>
                <a href="mis_reservas.php" class="btn btn-secondary">Ver Historial</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Mi Perfil</h5>
                <a href="perfil.php" class="btn btn-info">Editar Perfil</a>
            </div>
        </div>
    </div>
</div>
<?php include '../includes/footer.php'; ?>