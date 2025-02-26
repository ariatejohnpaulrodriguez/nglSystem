<?php
// Include database connection
include '../../../includes/conn.php';

// Set response header to JSON
header('Content-Type: application/json');

// Log errors to a file
ini_set("log_errors", 1);
ini_set("error_log", "../../php-error.log");

try {
    // Prepare the SQL statement to include stock information
    $sql = "SELECT p.product_id, p.code, p.brand, p.description, s.current_quantity
            FROM products p
            INNER JOIN stocks s ON p.product_id = s.product_id";  // Join products and stocks tables
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        throw new Exception("Error preparing statement: " . $conn->error);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    if (!$result) {
        throw new Exception("Error executing query: " . $stmt->error);
    }

    // Fetch the products
    $products = array();
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }

    // Encode the products as JSON
    $json = json_encode($products);

    // Check for encoding errors
    if ($json === false) {
        throw new Exception("Error encoding JSON: " . json_last_error_msg());
    }

    // Output the JSON
    echo $json;

} catch (Exception $e) {
    // Log the error
    error_log("Error in get_products.php: " . $e->getMessage());

    // Send an error response
    echo json_encode(array("error" => true, "message" => "Error fetching products. Please check the logs."));
} finally {
    // Close the connection
    $conn->close();
}
?>