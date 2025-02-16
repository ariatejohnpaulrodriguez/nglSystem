<?php
include '../../includes/conn.php';

// Set headers to force download as CSV
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=products.csv');

$output = fopen('php://output', 'w');

// Define CSV column headers
fputcsv($output, ['Product ID', 'Code', 'Brand', 'Description', 'Size', 'Color', 'Price']);

// Fetch data from the database
$query = "SELECT product_id, product_code, product_brand, product_description, product_size, product_color, product_price FROM tbl_product";
$result = mysqli_query($conn, $query);

// Write each row to the CSV file
while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, $row);
}

// Close file & database connection
fclose($output);
mysqli_close($conn);
exit();
?>