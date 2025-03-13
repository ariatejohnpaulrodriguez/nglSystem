<?php
include '../../../includes/conn.php'; // Include database connection
session_start(); // Start session to handle Toastr notifications

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capture data from the form
    $product_id = trim($_POST['product_id']);
    $product_code = trim($_POST['code']);
    $product_brand = trim($_POST['brand']);
    $description = trim($_POST['description']);

    // Prepare SQL query using prepared statements
    $query = "UPDATE products 
              SET code = ?, 
                  brand = ?, 
                  description = ?
              WHERE product_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssi", $product_code, $product_brand, $description, $product_id);

    // Execute query and handle feedback
    if ($stmt->execute()) {
        $_SESSION['success'] = "Product updated successfully!";
    } else {
        $_SESSION['error'] = "Error: Unable to update product. " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();

    // Redirect to the product list page with feedback
    header("Location: ../product-list.php");
    exit(); // Ensure no further code is executed
}
?>