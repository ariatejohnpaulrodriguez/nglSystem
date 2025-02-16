<?php
include '../../../includes/conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capture data from the form
    $status_id = $_POST['status_id']; // Make sure this is passed from the form
    $status_name = $_POST['statusName'];

    // Basic validation (optional but recommended)
    if (empty($status_name)) {
        echo "Status fields must be filled out.";
        exit();
    }

    // Prepare SQL query using prepared statements
    if ($status_name) {
        $query = "UPDATE statuses 
                  SET status_name = ?
                  WHERE status_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $status_name, $status_id);
    }

    // Execute query
    if ($stmt->execute()) {
        header("Location: ../status-list.php"); // Adjust path if necessary
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>