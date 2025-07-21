<?php
require_once '../includes/auth.php';
require_once '../includes/conexion.php';
checkAuth();

if ($_SESSION['rol'] !== 'operador') {
    header("Location: ../index.php");
    exit();
}

// Generar factura en PDF
if (isset($_GET['generar'])) {
    require_once('../libs/tcpdf/tcpdf.php');

    $pdf = new TCPDF();
    $pdf->AddPage();
    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->Cell(0, 10, 'Factura Parqueadero', 0, 1);
    $pdf->Output('factura.pdf', 'I');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Generar Facturas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Facturas</h2>
        <a href="facturas.php?generar=1" class="btn btn-primary">Generar Factura PDF</a>
    </div>
</body>
</html>