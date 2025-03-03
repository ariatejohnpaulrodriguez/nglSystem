<?php
include '../../../includes/conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    // Use prepared statement for security
    $stmt = $conn->prepare("DELETE FROM products WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmt->close();
}

$conn->close();

// Redirect silently to product-list.php
header("Location: ../product-list.php");
exit();
?>