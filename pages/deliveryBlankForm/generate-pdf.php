<?php
ob_start(); // Start output buffering

require('../../TCPDF-main/tcpdf.php'); // Adjust the path to where you included TCPDF

class CustomPDF extends TCPDF
{
    public function Header()
    {
        // Do nothing, no header
    }

    public function Footer()
    {
        // Do nothing, no footer
    }
}

$posting_date = isset($_POST['posting_date']) ? $_POST['posting_date'] : '';
$delivery_date = isset($_POST['delivery_date']) ? $_POST['delivery_date'] : '';
$from_company_id = isset($_POST['company_from_id']) ? $_POST['company_from_id'] : '';
$to_company_id = isset($_POST['company_to_id']) ? $_POST['company_to_id'] : '';
$po_number = isset($_POST['po_number']) ? $_POST['po_number'] : '';
$reference_po = isset($_POST['reference_po']) ? $_POST['reference_po'] : '';
$dr_number = isset($_POST['dr_number']) ? $_POST['dr_number'] : '';

// Retrieve values from the POST request, using empty strings as default values.
$plant = isset($_POST['plant']) ? $_POST['plant'] : '';
$plantName = isset($_POST['plantName']) ? $_POST['plantName'] : '';
$attention = isset($_POST['attention']) ? $_POST['attention'] : '';

// Retrieve product table data
$quantities = isset($_POST['quantity']) ? $_POST['quantity'] : [];
$units = isset($_POST['unit']) ? $_POST['unit'] : [];
$descriptions = isset($_POST['description']) ? $_POST['description'] : [];
$remarks = isset($_POST['remarks']) ? $_POST['remarks'] : [];

// Get the number of products (number of rows)
$numProducts = count($quantities);

include('../../includes/conn.php'); // Adjust the path to where you included conn.php

// Fetch company details
$sql = "SELECT company_id, name, address, phone_number, email, plant, plant_name, attention, image FROM companies WHERE company_id IN (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $from_company_id, $to_company_id);
$stmt->execute();
$result = $stmt->get_result();

$companies = [];
while ($row = $result->fetch_assoc()) {
    $companies[$row['company_id']] = $row;
}

$conn->close(); // Close the connection after fetching data

$pdf = new CustomPDF();
$pdf->AddPage();
$pdf->SetFont('courier', '', 12);

// Company Image
$from_company_image = isset($companies[$from_company_id]['image']) ? '../../' . $companies[$from_company_id]['image'] : '';
if (!empty($from_company_image) && file_exists($from_company_image)) {
    list($originalWidth, $originalHeight) = getimagesize($from_company_image);
    $maxHeight = 30;
    $maxWidth = 50;
    $scale = min($maxWidth / $originalWidth, $maxHeight / $originalHeight);
    $width = $originalWidth * $scale;
    $height = $originalHeight * $scale;

    $pdf->Image($from_company_image, 15, 10, $width, $height);
} else {
    $pdf->Cell(0, 10, 'Image not found or invalid path.', 0, 1, 'C');
}

// Company Details
$pdf->SetFont('courier', '', 10);
$pdf->SetXY(15, 35);
$pdf->Cell(0, 10, $companies[$from_company_id]['name'], 0, 1, 'L');

$pdf->SetXY(15, 42.5);
$pdf->MultiCell(150, 10, $companies[$from_company_id]['address'], 0, 'L', false);

// Deliver To
$pdf->SetFont('courier', '', 12);
$pdf->SetXY(15, 55);
$pdf->Cell(0, 25, 'Deliver To: ', 0, 1, 'L');

// To Company Name
$pdf->SetFont('courier', 'B', 24);
$pdf->SetXY(45, 55);
$pdf->Cell(0, 25, $companies[$to_company_id]['name'], 0, 1, 'L');

// To Company Address
$pdf->SetFont('courier', '', 10);
$pdf->SetXY(15, 80);
$pdf->MultiCell(150, 10, 'Address:' . $companies[$to_company_id]['address'], 0, 'L', false);

//To Company Attention - USE POST DATA IF FILLED, OTHERWISE USE COMPANY DATA
$displayAttention = !empty($attention) ? $attention : $companies[$to_company_id]['attention'];
$pdf->SetXY(15, 86); // Adjust the coordinates as needed
$pdf->Cell(0, 10, 'Attention: ' . $displayAttention, 0, 1, 'L'); // Display the attention

// To Company Phone
$pdf->SetXY(15, 91);
$pdf->Cell(0, 10, 'Phone: ' . $companies[$to_company_id]['phone_number'], 0, 1, 'L');

// DR Number
$pdf->SetXY(15, 100);
$pdf->Cell(0, 10, 'DR #: ' . $dr_number, 0, 1, 'L');

$pdf->Rect(16, 112, 179, 6);

//Date Fields
$pdf->SetXY(15, 120);
$pdf->Cell(0, 10, 'Posting Date: ' . $posting_date, 0, 1, 'L');

//Delivery Date
$pdf->SetXY(110, 120);
$pdf->Cell(0, 10, 'Delivery Date: ' . $delivery_date, 0, 1, 'L');

//Plant Fields - USE POST DATA IF FILLED, OTHERWISE USE COMPANY DATA
$displayPlant = !empty($plant) ? $plant : $companies[$to_company_id]['plant'];
$pdf->SetXY(15, 125);
$pdf->Cell(0, 10, 'Plant: ' . $displayPlant, 0, 1, 'L');

//Plant Name - USE POST DATA IF FILLED, OTHERWISE USE COMPANY DATA
$displayPlantName = !empty($plantName) ? $plantName : $companies[$to_company_id]['plant_name'];
$pdf->SetXY(110, 125);
$pdf->Cell(0, 10, 'Plant Name: ' . $displayPlantName, 0, 1, 'L');

//PO
$pdf->SetXY(15, 130);
$pdf->Cell(0, 10, 'PO #: ' . $po_number, 0, 1, 'L');

$pdf->Rect(16, 141, 179, 6);

// Function to resize text if it exceeds cell width
function resizeText($pdf, $text, $width)
{
    $fontSize = $pdf->GetFontSizePt();
    $textWidth = $pdf->GetStringWidth($text);
    while ($textWidth > $width && $fontSize > 0) {
        $fontSize -= 0.1;
        $pdf->SetFontSize($fontSize);
        $textWidth = $pdf->GetStringWidth($text);
    }
}

// Set table header style
$pdf->SetFont('helvetica', 'B', 6);
$pdf->SetFillColor(0, 0, 0);
$pdf->SetTextColor(255, 255, 255);

// Define table headers and column widths
$headers = ['Quantity', 'Unit', 'Description', 'Remarks'];
$widths = [10, 10, 149, 10];
//$widths = [25, 20, 114, 20];

// Function to print table headers
function printTableHeaders($pdf, $headers, $widths)
{
    foreach ($headers as $i => $header) {
        $pdf->Cell($widths[$i], 6, $header, 1, 0, 'C', 1);
    }
    $pdf->Ln();
}

// Set initial Y position for the table
$pdf->SetXY(16, 154);

// Print table headers on the first page
printTableHeaders($pdf, $headers, $widths);

// Product data from the form
$pdf->SetFont('helvetica', '', 6);
$pdf->SetTextColor(0, 0, 0);

// Loop through the products
for ($i = 0; $i < $numProducts; $i++) {

    $quantity = isset($quantities[$i]) ? htmlspecialchars($quantities[$i]) : ''; //Sanitize Input
    $unit = isset($units[$i]) ? htmlspecialchars($units[$i]) : ''; //Sanitize Input
    $description = isset($descriptions[$i]) ? htmlspecialchars($descriptions[$i]) : ''; //Sanitize Input
    $remarks = isset($remarks[$i]) ? htmlspecialchars($remarks[$i]) : ''; //Sanitize Input

    $startY = $pdf->GetY(); // Store initial Y position

    //Check if adding the next row will exceed the page height
    if ($startY + 6 > 270) {
        $pdf->AddPage();
        $pdf->SetXY(16, 20);
        $pdf->SetFont('helvetica', 'B', 6);
        $pdf->SetFillColor(0, 0, 0);
        $pdf->SetTextColor(255, 255, 255);
        printTableHeaders($pdf, $headers, $widths);
        $startY = $pdf->GetY();
        $pdf->SetFont('helvetica', '', 6);
        $pdf->SetTextColor(0, 0, 0);
    }

    // Align columns with calculated row height
    $pdf->SetXY(16, $startY);
    $x = 16;

    $pdf->Cell($widths[0], 6, $quantity, 1, 0, 'C');
    $x += $widths[0];

    $pdf->Cell($widths[1], 6, $unit, 1, 0, 'C');
    $x += $widths[1];

    //Print the descriptions using CELL instead of MULTICELL, which forces one-line output
    $pdf->Cell($widths[2], 6, $description, 1, 0, 'L');
    $x += $widths[2];

    $pdf->Cell($widths[3], 6, $remarks, 1, 1, 'C');
    $x += $widths[3];
}

// Function to ensure enough space before adding content
function checkAndAddPageBreak($pdf, $requiredSpace)
{
    if ($pdf->GetY() + $requiredSpace > 270) {
        $pdf->AddPage();
        $pdf->SetXY(15, 20);
    }
}

// Reference PO
checkAndAddPageBreak($pdf, 50);

$pdf->SetXY(15, 220);
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(0, 10, 'Reference PO: ' . $reference_po, 0, 1, 'L');

// Nothing Follows
$pdf->SetFont('helvetica', 'B', 10);
$pdf->SetXY(15, 230);
$pdf->Cell(0, 10, '----------------------------Nothing Follows-----------------------------', 0, 1, 'L');

$pdf->SetFont('helvetica', 'B', 7);
$pdf->SetXY(15, 239);
$pdf->Cell(0, 10, 'Customer Received the above merchandise in good order and condition', 0, 1, 'L');

$pdf->SetFont('helvetica', 'B', 7);
$pdf->SetXY(15, 242);
$pdf->Cell(0, 10, 'All bills are payable on demands unless otherwise agreed upon.', 0, 1, 'L');

$pdf->SetFont('helvetica', 'B', 7);
$pdf->SetXY(15, 245);
$pdf->Cell(0, 10, 'Interest at 25% per annum will be charged on all overdue accounts.', 0, 1, 'L');

$pdf->SetFont('helvetica', 'B', 7);
$pdf->SetXY(15, 248);
$pdf->Cell(0, 10, 'The parties expressly submit to the jurisdiction of the courts of Makati on', 0, 1, 'L');

$pdf->SetFont('helvetica', 'B', 7);
$pdf->SetXY(15, 251);
$pdf->Cell(0, 10, 'any legal action taken out of this transaction. An additional sum of equal to', 0, 1, 'L');

$pdf->SetFont('helvetica', 'B', 7);
$pdf->SetXY(15, 254);
$pdf->Cell(0, 10, '25% of the amount due will be charged by the vendor for', 0, 1, 'L');

$pdf->SetFont('helvetica', 'B', 7);
$pdf->SetXY(118, 245);
$pdf->Cell(0, 10, 'Received the above merchandise in good order and condition', 0, 1, 'L');

$pdf->SetFont('helvetica', 'B', 7);
$pdf->SetXY(118, 250);
$pdf->Cell(0, 10, 'by:___________________________________________________', 0, 1, 'L');

$pdf->SetFont('helvetica', 'B', 7);
$pdf->SetXY(135, 253);
$pdf->Cell(0, 10, 'Signature Over Printed Name', 0, 1, 'L');

ob_end_clean();
$pdf->Output('Delivery Form.pdf', 'I');
?>