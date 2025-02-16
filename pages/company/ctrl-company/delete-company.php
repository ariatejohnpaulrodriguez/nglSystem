<?php
include '../../../includes/conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['company_id'])) {
    $company_id = $_POST['company_id'];

    // Use prepared statement for security
    $stmt = $conn->prepare("DELETE FROM companies WHERE company_id = ?");
    $stmt->bind_param("i", $company_id);
    $stmt->execute();
    $stmt->close();
}

$conn->close();

// Redirect silently to product-list.php
header("Location: ../company-list.php");
exit();
?>