<?php
include '../../../includes/conn.php'; // Include database connection
session_start(); // Start session to handle Toastr notifications

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capture data from the form
    $status_id = $_POST['status_id']; // Ensure this is passed from the form
    $status_name = trim($_POST['statusName']); // Trim input to avoid unnecessary whitespaces

    // Basic validation
    if (empty($status_name)) {
        $_SESSION['error'] = "Status field must be filled out.";
        header("Location: ../status-list.php"); // Redirect back to the list page with the error
        exit();
    }

    // Prepare SQL query using prepared statements
    $query = "UPDATE statuses 
              SET status_name = ?
              WHERE status_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $status_name, $status_id);

    // Execute the query and handle feedback
    if ($stmt->execute()) {
        $_SESSION['success'] = "Status updated successfully!";
    } else {
        $_SESSION['error'] = "Error: Unable to update the status.";
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();

    // Redirect to the status list page with feedback
    header("Location: ../status-list.php");
    exit();
}
?>