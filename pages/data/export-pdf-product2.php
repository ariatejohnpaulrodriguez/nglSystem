<?php
require_once '../../TCPDF-main/tcpdf.php'; // Change path if needed
include '../../includes/conn.php'; // Include database connection

$pdf = new TCPDF();
$pdf->AddPage();

// ======= CUSTOM POSITION FOR THE TITLE =======
$pdf->SetXY(10, 10);
$pdf->SetFont('helvetica', 'B', 20);
$pdf->Cell(0, 10, 'New Generation Link', 0, 1, 'C');

// ======= STREET ADDRESS BELOW TITLE =======
$pdf->SetXY(10, 15);
$pdf->SetFont('helvetica', '', 14);
$pdf->Cell(0, 10, '1234 Main Street', 0, 1, 'C');

// ======= CITY, COUNTRY, ZIP BELOW STREET ADDRESS =======
$pdf->SetFont('helvetica', '', 14);
$pdf->Cell(0, 10, 'Bacoor Cavite City', 0, 1, 'C');

$pdf->SetXY(10, 25);
$pdf->Cell(0, 10, 'Country Name', 0, 1, 'C');

$pdf->SetXY(10, 30);
$pdf->Cell(0, 10, '12345', 0, 1, 'C');

// ======= ADDITIONAL INFORMATION (Phone, Fax, Date, Invoice, Customer ID) =======
$pdf->SetXY(10, 40);
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, 'Phone: (123) 456-7890', 0, 1, 'L');

$pdf->SetXY(10, 45);
$pdf->Cell(0, 10, 'Fax: (123) 456-7891', 0, 1, 'L');

$pdf->SetXY(10, 50);
$pdf->Cell(0, 10, 'Invoice #: 1001', 0, 1, 'L');

$pdf->SetXY(10, 55);
$pdf->Cell(0, 10, 'Date: ' . date('Y-m-d'), 0, 1, 'L');

$pdf->SetXY(10, 60);
$pdf->Cell(0, 10, 'Customer ID: 1234', 0, 1, 'L');

// ======= INVOICE TITLE (Bold) =======
$pdf->SetXY(10, 70);
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 10, 'Invoice Details', 0, 1, 'C');

// ======= TABLE FOR PRODUCT DETAILS =======
$pdf->SetXY(2, 80);
$pdf->SetFont('helvetica', 'B', 7);
$pdf->Cell(30, 10, 'Product ID', 1, 0, 'C');
$pdf->Cell(30, 10, 'Code', 1, 0, 'C');
$pdf->Cell(30, 10, 'Brand', 1, 0, 'C');
$pdf->Cell(50, 10, 'Description', 1, 0, 'C');
$pdf->Cell(20, 10, 'Size', 1, 0, 'C');
$pdf->Cell(20, 10, 'Color', 1, 0, 'C');
$pdf->Cell(30, 10, 'Price', 1, 1, 'C');

// Add product data (Example data - replace with dynamic data from database)
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(30, 10, 'P001', 1, 0, 'C');
$pdf->Cell(30, 10, 'A123', 1, 0, 'C');
$pdf->Cell(30, 10, 'BrandX', 1, 0, 'C');
$pdf->Cell(50, 10, 'Product Description Example', 1, 0, 'C');
$pdf->Cell(20, 10, 'L', 1, 0, 'C');
$pdf->Cell(20, 10, 'Red', 1, 0, 'C');
$pdf->Cell(30, 10, '$100.00', 1, 1, 'C');

// Output the PDF
$pdf->Output();
?>