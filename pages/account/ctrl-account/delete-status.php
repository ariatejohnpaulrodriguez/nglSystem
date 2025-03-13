<?php
include '../../../includes/conn.php'; // Database connection
session_start(); // Start session for feedback messages

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['status_id'])) {
    $status_id = $_POST['status_id'];

    // Use prepared statement for security
    $stmt = $conn->prepare("DELETE FROM statuses WHERE status_id = ?");
    $stmt->bind_param("i", $status_id);

    // Set feedback messages based on execution
    if ($stmt->execute()) {
        $_SESSION['success'] = "Status deleted successfully!";
    } else {
        $_SESSION['error'] = "Error: Unable to delete status.";
    }

    $stmt->close();
}

$conn->close();

// Redirect to status list page with feedback messages
header("Location: ../status-list.php");
exit();
?>