<?php
require_once '../../TCPDF-main/tcpdf.php'; // Change path if needed
include '../../includes/conn.php'; // Include database connection

// Create new PDF document
$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('NGL Network Services'); // Change this to your company name
$pdf->SetTitle('Product List');
$pdf->SetMargins(10, 10, 10);
$pdf->SetAutoPageBreak(TRUE, 10);
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 10);

// ======= HEADER: COMPANY DETAILS (CENTERED IN MIDDLE) ======= //
$company_name = "New Generation Link"; // Change this
$company_address = "123 Business St, City, State"; // Change this
$company_zip = "123456"; // Change this
$company_contact = "+1 234 567 890"; // Change this
$company_country = "Your Country"; // Change this
$company_email = "info@yourcompany.com"; // Change this
$transaction_date = date("F d, Y"); // Automatically fetch current date

// Centering header content without paragraph spacing
$header_html = "
<br>
<h2 style='text-align: right;'>$company_name</h2>
<p style='text-align: center; margin: 0;'>$company_address, ZIP: $company_zip</p>
<p style='text-align: center; margin: 0;'>Contact: $company_contact | $company_country</p>
<p style='text-align: center; margin: 0;'>Email: $company_email</p>
<p style='text-align: center; margin: 0;'><b>Transaction Date:</b> $transaction_date</p>
<hr>
";

// Write header to PDF
$pdf->writeHTML($header_html, true, false, true, false, '');

// ======= TABLE: PRODUCT DETAILS ======= //
// Fetch data from database
$query = "SELECT product_id, product_code, product_brand, product_description, product_quantity FROM tbl_product";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    // Table Header
    $html = '<table border="1" cellpadding="5">
                <tr style="background-color: #ccc;">
                    <th><b>Product ID</b></th>
                    <th><b>Code</b></th>
                    <th><b>Brand</b></th>
                    <th><b>Description</b></th>
                    <th><b>Quantity</b></th>
                </tr>';

    // Table Rows
    while ($row = mysqli_fetch_assoc($result)) {
        $html .= '<tr>
                    <td>' . $row['product_id'] . '</td>
                    <td>' . $row['product_code'] . '</td>
                    <td>' . $row['product_brand'] . '</td>
                    <td>' . $row['product_description'] . '</td>
                    <td>' . $row['product_quantity'] . '</td>
                  </tr>';
    }

    $html .= '</table>';
} else {
    $html = '<p>No product data available.</p>';
}

// Write product table to PDF
$pdf->writeHTML($html, true, false, true, false, '');

// ======= SIGNATURE AREA (LEFT: AUTHORIZED SIGNATURE, RIGHT: RECEIVING PERSONNEL) ======= //
$authority_name = "John Doe"; // Change this to the authority's name
$receiving_person = "Jane Smith"; // Change this to receiving personnel's name

$signature_html = "
    <br><br><br><br>
    <table width='100%'>
        <tr>
            <td style='border-bottom: 1px solid black; width: 40%; text-align: center;'></td>
            <td></td>
            <td style='border-bottom: 1px solid black; width: 40%; text-align: center;'></td>
        </tr>
        <tr>
            <td style='text-align: center;'>$authority_name</td>
            <td></td>
            <td style='text-align: center;'>$receiving_person</td>
        </tr>
        <tr>
            <td style='text-align: center;'><b>Authorized Signature</b></td>
            <td></td>
            <td style='text-align: center;'><b>Receiving Personnel</b></td>
        </tr>
    </table>
";

// Write signature area
$pdf->writeHTML($signature_html, true, false, true, false, '');

// Output PDF
$pdf->Output('Product_List.pdf', 'D');

// Close connection
mysqli_close($conn);

?>