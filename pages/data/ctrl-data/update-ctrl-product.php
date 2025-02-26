<?php
include '../../../includes/conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capture data from the form
    $product_id = $_POST['product_id']; // Make sure this is passed from the form
    $product_code = $_POST['code'];
    $product_brand = $_POST['brand'];
    $description = $_POST['description'];

    // Prepare SQL query using prepared statements
    $query = "UPDATE products 
              SET code = ?, 
                  brand = ?, 
                  description = ?
              WHERE product_id = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssi", $product_code, $product_brand, $description, $product_id);

    // Execute query
    if ($stmt->execute()) {
        header("Location: ../product-list.php"); // Adjust path if necessary
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>