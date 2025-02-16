<?php
include '../../../includes/conn.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $status = trim($_POST['status']);

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO statuses (status_name) VALUES (?)");
    $stmt->bind_param("s", $status);

    // Execute and check success
    if ($stmt->execute()) {
        echo "<script>alert('Status added successfully!'); window.location.href='../status.php';</script>";
    } else {
        echo "<script>alert('Error: Unable to add Status.'); window.history.back();</script>";
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    header("Location: ../add-status.php");
    exit();
}
?>