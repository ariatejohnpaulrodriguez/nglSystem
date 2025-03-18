<?php
include '../../../includes/conn.php'; // Include database connection
session_start(); // Start session to handle Toastr notifications

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capture data from the form
    $product_code = trim($_POST['code']);
    $product_brand = trim($_POST['brand']);
    $description = trim($_POST['description']);
    $unit = trim($_POST['unit']);

    // Prepare SQL query to insert into the database
    $query = "INSERT INTO products (code, brand, description, unit_id) 
              VALUES ('$product_code', '$product_brand', '$description', '$unit')";

    // Execute query and handle feedback
    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = "Product added successfully!";
    } else {
        $_SESSION['error'] = "Error: Unable to add product. " . mysqli_error($conn);
    }

    // Redirect to the product form with feedback
    header("Location: ../add-product-form.php");
    exit(); // Ensure no further code is executed after the redirect
}
?>