<?php
include '../../../includes/conn.php'; // Include database connection
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $status = trim($_POST['status']);

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO statuses (status_name) VALUES (?)");
    $stmt->bind_param("s", $status);

    // Execute and set session messages
    if ($stmt->execute()) {
        $_SESSION['success'] = "Status added successfully!";
    } else {
        $_SESSION['error'] = "Error: Unable to add Status.";
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();

    // Redirect to the status page
    header("Location: ../status.php");
    exit();
} else {
    $_SESSION['error'] = "Invalid request method.";
    header("Location: ../add-status.php");
    exit();
}
?>