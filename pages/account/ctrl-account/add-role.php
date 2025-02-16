<?php
include '../../../includes/conn.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role = trim($_POST['role']);

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO roles (role_name) VALUES (?)");
    $stmt->bind_param("s", $role);

    // Execute and check success
    if ($stmt->execute()) {
        echo "<script>alert('Role added successfully!'); window.location.href='../role.php';</script>";
    } else {
        echo "<script>alert('Error: Unable to add president.'); window.history.back();</script>";
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    header("Location: ../add-role.php");
    exit();
}
?>