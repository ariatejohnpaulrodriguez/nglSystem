<?php
include '../../../includes/conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capture data from the form
    $product_code = $_POST['code'];
    $product_brand = $_POST['brand'];
    $description = $_POST['description'];
    $quantity = $_POST['quantity'];

    // Prepare SQL query to insert into the database
    $query = "INSERT INTO products (code, brand, description, quantity) 
              VALUES ('$product_code', '$product_brand', '$description', '$quantity')";

    // Execute query
    if (mysqli_query($conn, $query)) {
        header("Location: ../add-product-form.php");
        exit(); // Ensure no further code is executed after the redirect
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>