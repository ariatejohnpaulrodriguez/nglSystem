<?php
ob_start(); // Start output buffering

require('../../TCPDF-main/tcpdf.php'); // Adjust the path to where you included TCPDF
include('../../includes/conn.php'); // Adjust the path to where you included conn.php

class CustomPDF extends TCPDF
{
    // Override the default header
    public function Header()
    {
        // Do nothing, no header
    }

    // Override the default footer if you also want to remove it
    public function Footer()
    {
        // Do nothing, no footer
    }
}

// Check if invoice_id is set in the GET request and is a number
if (isset($_GET['invoice_id']) && is_numeric($_GET['invoice_id'])) {
    $invoiceId = $_GET['invoice_id'];

    // SQL query to retrieve invoice_id, from_company image, from_company name, from_company address, and attention
    $query = "SELECT
            invoices.invoice_id,
            from_company.image AS from_company_image,
            from_company.name AS from_company_name,
            from_company.address AS from_company_address,
            from_company.attention AS from_company_attention,
            to_company.attention AS to_company_attention,
            to_company.name AS to_company_name,
            to_company.address AS to_company_address,
            to_company.phone_number AS to_company_phone_number,
            to_company.plant AS to_company_plant,
            to_company.plant_name AS to_company_plant_name,
            delivery_receipts.dr_number,
            purchase_orders.po_number,
            posting_dates.date_value AS posting_date,
            delivery_dates.date_value AS delivery_date,
            reference_pos.reference_po AS reference_po_number
          FROM invoices
          INNER JOIN companies AS from_company ON invoices.from_company_id = from_company.company_id
          INNER JOIN companies AS to_company ON invoices.to_company_id = to_company.company_id
          LEFT JOIN delivery_receipts ON invoices.dr_id = delivery_receipts.dr_id
          LEFT JOIN purchase_orders ON invoices.po_id = purchase_orders.po_id
          LEFT JOIN dates AS posting_dates ON invoices.posting_date = posting_dates.date_id
          LEFT JOIN dates AS delivery_dates ON invoices.delivery_date = delivery_dates.date_id
          LEFT JOIN reference_pos ON invoices.reference_po_id = reference_pos.reference_po_id
          WHERE invoices.invoice_id = ?";

    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die("Error preparing SQL statement: " . $conn->error);
    }
    $stmt->bind_param("i", $invoiceId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc(); // Fetch the row

        $pdf = new CustomPDF();
        $pdf->AddPage();
        $pdf->SetFont('courier', '', 12);

        // Display From Company Image at the top left corner with fixed height of 30 and maintaining aspect ratio
        $imagePath = '../../' . $row['from_company_image']; // Adjust the path to the image directory
        if (!empty($row['from_company_image']) && file_exists($imagePath)) {
            // Get original image dimensions
            list($originalWidth, $originalHeight) = getimagesize($imagePath);
            $maxHeight = 30;
            $maxWidth = 50;
            $scale = min($maxWidth / $originalWidth, $maxHeight / $originalHeight);
            $width = $originalWidth * $scale;
            $height = $originalHeight * $scale;

            // Ensure the image scales proportionally
            $pdf->Image($imagePath, 15, 10, $width, $height); // Adjust the coordinates and size
        } else {
            $pdf->Cell(0, 10, 'Image not found or invalid path.', 0, 1, 'C'); // Display error message if image is not found
        }

        // Set font size to 10 for the company name, address, and attention
        $pdf->SetFont('courier', '', 10);

        // Display From Company Name below the image
        $pdf->SetXY(15, 35); // Fixed coordinates (x: 15, y: 35)
        $pdf->Cell(0, 10, $row['from_company_name'], 0, 1, 'L');

        // Display From Company Address below the name using MultiCell for long addresses
        $pdf->SetXY(15, 42.5);
        $pdf->MultiCell(150, 10, $row['from_company_address'], 0, 'L', false);

        // Display Attention below the address
        //$pdf->SetXY(15, 48.5);
        //$pdf->Cell(0, 10, $row['from_company_attention'], 0, 1, 'L'); // Adjust the width as needed

        // Display "Deliver To: " followed by the company name below attention
        $pdf->SetFont('courier', '', 12); // Set font size for "Deliver To: "
        $pdf->SetXY(15, 55);
        $pdf->Cell(0, 25, 'Deliver To: ', 0, 1, 'L'); // Adjust the width as needed

        // Display the "To" company name in bold and larger font size
        $pdf->SetFont('courier', 'B', 24); // Set font size and bold style for the "To" company name
        $pdf->SetXY(45, 55);
        $pdf->Cell(0, 25, $row['to_company_name'], 0, 1, 'L');

        // Display the "To" company address below the "To" company name
        $pdf->SetFont('courier', '', 10); // Set font size back to 10 for the address
        $pdf->SetXY(15, 80); // Adjust the coordinates as needed
        $pdf->MultiCell(150, 10, $row['to_company_address'], 0, 'L', false);

        // Display the "To" company attention before the phone number
        $pdf->SetXY(15, 86); // Adjust the coordinates as needed
        $pdf->Cell(0, 10, 'Attention: ' . $row['to_company_attention'], 0, 1, 'L'); // Display the attention

        // Display the "To" company phone number below the address
        $pdf->SetXY(15, 91); // Adjust the coordinates as needed
        $pdf->Cell(0, 10, 'Phone: ' . $row['to_company_phone_number'], 0, 1, 'L'); // Display the phone number

        // Display the DR number below the phone number
        $pdf->SetXY(15, 100); // Adjust the coordinates as needed
        $pdf->Cell(0, 10, 'DR #: ' . $row['dr_number'], 0, 1, 'L'); // Display the DR number

        // Add a rectangle box after the DR number
        $pdf->Rect(16, 112, 179, 6); // Adjust the coordinates (x: 35, y: 100) and size (width: 40, height: 10) as needed

        // Display the Posting Date after the rectangle box
        $pdf->SetXY(15, 120); // Adjust the coordinates as needed
        $pdf->Cell(0, 10, 'Posting Date: ' . $row['posting_date'], 0, 1, 'L'); // Display the Posting Date

        // Display the Posting Date after the rectangle box
        $pdf->SetXY(110, 120); // Adjust the coordinates as needed
        $pdf->Cell(0, 10, 'Posting Date: ' . $row['delivery_date'], 0, 1, 'L'); // Display the Posting Date

        // Display the Plant information below the delivery date
        $pdf->SetXY(15, 125); // Adjust the coordinates as needed
        $pdf->Cell(0, 10, 'Plant: ' . $row['to_company_plant'], 0, 1, 'L'); // Display the Plant information

        // Display the Plant information below the delivery date
        $pdf->SetXY(110, 125); // Adjust the coordinates as needed
        $pdf->Cell(0, 10, 'Plant Name: ' . $row['to_company_plant_name'], 0, 1, 'L'); // Display the Plant information

        // Display the Plant information below the delivery date
        $pdf->SetXY(15, 130); // Adjust the coordinates as needed
        $pdf->Cell(0, 10, 'PO #: ' . $row['po_number'], 0, 1, 'L'); // Display the Plant information

        // Add a rectangle box after the DR number
        $pdf->Rect(16, 141, 179, 6); // Adjust the coordinates (x: 35, y: 100) and size (width: 40, height: 10) as needed

        // Function to resize text if it exceeds cell width
        function resizeText($pdf, $text, $width)
        {
            $fontSize = $pdf->GetFontSizePt(); // Use GetFontSizePt method to get the font size
            $textWidth = $pdf->GetStringWidth($text);
            while ($textWidth > $width && $fontSize > 0) {
                $fontSize -= 0.1;
                $pdf->SetFontSize($fontSize);
                $textWidth = $pdf->GetStringWidth($text);
            }
        }

        // Set table header style
        $pdf->SetFont('helvetica', 'B', 6);
        $pdf->SetFillColor(0, 0, 0); // Black background
        $pdf->SetTextColor(255, 255, 255); // White text color

        // Define table headers and column widths
        $headers = ['Code', 'Brand', 'Description', 'Quantity'];
        $widths = [25, 20, 114, 20];

        // Function to print table headers
        function printTableHeaders($pdf, $headers, $widths)
        {
            foreach ($headers as $i => $header) {
                $pdf->Cell($widths[$i], 6, $header, 1, 0, 'C', 1);
            }
            $pdf->Ln(); // Move to the next line after the headers
        }

        // Set initial Y position for the table
        $pdf->SetXY(16, 154); // Adjust the coordinates as needed

        // Print table headers on the first page
        printTableHeaders($pdf, $headers, $widths);

        // Fetch invoice product details
        $queryProducts = "SELECT code, brand, description, quantity FROM invoice_products WHERE invoice_id = ?";
        $stmtProducts = $conn->prepare($queryProducts);
        $stmtProducts->bind_param("i", $invoiceId);
        $stmtProducts->execute();
        $resultProducts = $stmtProducts->get_result();

        // Set table data font
        $pdf->SetFont('helvetica', '', 6);
        $pdf->SetTextColor(0, 0, 0); // Reset text color to black for table data

        if ($resultProducts->num_rows > 0) {
            while ($product = $resultProducts->fetch_assoc()) {
                $startY = $pdf->GetY(); // Store initial Y position

                // Check if adding the next row will exceed the page height
                if ($startY + 6 > 270) { // Adjust based on your footer margin
                    $pdf->AddPage();
                    $pdf->SetXY(16, 20); // Reset Y position for new page headers
                    // Set the header style again for the new page
                    $pdf->SetFont('helvetica', 'B', 6);
                    $pdf->SetFillColor(0, 0, 0); // Black background
                    $pdf->SetTextColor(255, 255, 255); // White text color
                    // Print table headers on the new page
                    printTableHeaders($pdf, $headers, $widths);
                    $startY = $pdf->GetY(); // Update initial Y position after headers
                    // Reset text color to black for table data
                    $pdf->SetFont('helvetica', '', 6);
                    $pdf->SetTextColor(0, 0, 0);
                }

                // Align columns with calculated row height
                $pdf->SetXY(16, $startY);
                foreach (['code', 'brand', 'description', 'quantity'] as $i => $key) {
                    $cellWidth = $widths[$i];
                    $pdf->SetFont('helvetica', '', 6); // Reset font size
                    resizeText($pdf, $product[$key], $cellWidth); // Resize text if needed
                    $pdf->Cell($cellWidth, 6, $product[$key], 1, 0, 'C');
                }
                $pdf->Ln(); // Move to the next line for the next row
            }
        } else {
            $pdf->Cell(array_sum($widths), 10, 'No products found.', 1, 1, 'C');
        }


        $pdf->SetXY(15, 220);
        $pdf->SetFont('helvetica', 'B', 10); // Set bold font
        $pdf->Cell(0, 10, 'Reference PO: ' . $row['reference_po_number'], 0, 1, 'L');

        // Display "-----------Nothing Follows---------------" below Reference PO
        $pdf->SetFont('helvetica', 'B', 10); // Set bold font
        $pdf->SetXY(15, 230); // Adjust the position if needed
        $pdf->Cell(0, 10, '----------------------------Nothing Follows-----------------------------', 0, 1, 'L'); // Center-aligned

        $pdf->SetFont('helvetica', 'B', 7); // Set bold font
        $pdf->SetXY(15, 239); // Adjust the position if needed
        $pdf->Cell(0, 10, 'Customer Received the above merchandise in good order and condition', 0, 1, 'L');

        $pdf->SetFont('helvetica', 'B', 7); // Set bold font
        $pdf->SetXY(15, 242); // Adjust the position if needed
        $pdf->Cell(0, 10, 'All bills are payable on demands unless otherwise agreed upon.', 0, 1, 'L');

        $pdf->SetFont('helvetica', 'B', 7); // Set bold font
        $pdf->SetXY(15, 245); // Adjust the position if needed
        $pdf->Cell(0, 10, 'Interest at 25% per annum will be charged on all overdue accounts.', 0, 1, 'L');

        $pdf->SetFont('helvetica', 'B', 7); // Set bold font
        $pdf->SetXY(15, 248); // Adjust the position if needed
        $pdf->Cell(0, 10, 'The parties expressly submit to the jurisdiction of the courts of Makati on', 0, 1, 'L');

        $pdf->SetFont('helvetica', 'B', 7); // Set bold font
        $pdf->SetXY(15, 251); // Adjust the position if needed
        $pdf->Cell(0, 10, 'any legal action taken out of this transaction. An additional sum of equal to', 0, 1, 'L');

        $pdf->SetFont('helvetica', 'B', 7); // Set bold font
        $pdf->SetXY(15, 254); // Adjust the position if needed
        $pdf->Cell(0, 10, '25% of the amount due will be charged by the vendor for', 0, 1, 'L');

        $pdf->SetFont('helvetica', 'B', 7); // Set bold font
        $pdf->SetXY(118, 245); // Adjust the position if needed
        $pdf->Cell(0, 10, 'Received the above merchandise in good order and condition', 0, 1, 'L');

        $pdf->SetFont('helvetica', 'B', 7); // Set bold font
        $pdf->SetXY(118, 250); // Adjust the position if needed
        $pdf->Cell(0, 10, 'by:___________________________________________________', 0, 1, 'L');

        $pdf->SetFont('helvetica', 'B', 7); // Set bold font
        $pdf->SetXY(135, 253); // Adjust the position if needed
        $pdf->Cell(0, 10, 'Signature Over Printed Name', 0, 1, 'L');

        ob_end_clean(); // Clean the output buffer
        $pdf->Output('invoice.pdf', 'I'); // Output the PDF
    } else {
        echo "No data found for invoice ID: " . htmlspecialchars($invoiceId);
    }
} else {
    echo "Invalid invoice ID.";
}
?>