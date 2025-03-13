<?php
include '../../../includes/conn.php'; // Include database connection
session_start(); // Start session to handle Toastr notifications

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['company_id'])) {
    $company_id = $_POST['company_id'];

    // Use prepared statement for security
    $stmt = $conn->prepare("DELETE FROM companies WHERE company_id = ?");
    $stmt->bind_param("i", $company_id);

    // Execute query and handle feedback
    if ($stmt->execute()) {
        $_SESSION['success'] = "Company deleted successfully!";
    } else {
        $_SESSION['error'] = "Error: Unable to delete company. " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();

// Redirect to company list with feedback
header("Location: ../company-list.php");
exit();
?>