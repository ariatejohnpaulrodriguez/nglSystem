<?php
// Include database connection
include '../../../includes/conn.php';

// Set response header to JSON
header('Content-Type: application/json');

// Log errors to a file
ini_set("log_errors", 1);
ini_set("error_log", "../../php-error.log");

try {
    // Get the product ID from the request
    $product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;

    // Validate the product ID
    if ($product_id <= 0) {
        throw new Exception("Invalid product ID.");
    }

    // Prepare the SQL statement to retrieve the stock quantity
    $sql = "SELECT current_quantity FROM stocks WHERE product_id = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        throw new Exception("Error preparing statement: " . $conn->error);
    }

    // Bind the product ID parameter
    $stmt->bind_param("i", $product_id);

    // Execute the query
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $stock_data = array('current_quantity' => intval($row['current_quantity'])); // Ensure integer
    } else {
        $stock_data = array('current_quantity' => 0); // Product not found, return 0
    }

    // Encode the stock data as JSON
    $json = json_encode($stock_data);

    // Check for encoding errors
    if ($json === false) {
        throw new Exception("Error encoding JSON: " . json_last_error_msg());
    }

    // Output the JSON
    echo $json;

} catch (Exception $e) {
    // Log the error
    error_log("Error in get_stock.php: " . $e->getMessage());

    // Send an error response
    echo json_encode(array("error" => true, "message" => "Error fetching stock. Please check the logs."));
} finally {
    // Close the connection
    if (isset($stmt))
        $stmt->close(); // Close statement if it was prepared
    $conn->close();
}
?>