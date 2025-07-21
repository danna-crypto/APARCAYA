<?php
require_once('tcpdf/tcpdf.php');
$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'Factura Parqueadero', 0, 1);
$pdf->Output('factura.pdf', 'I');