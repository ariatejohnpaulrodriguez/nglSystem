<?php
include '../../../includes/conn.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['employee_id'])) {
    $employee_id = $_POST['employee_id'];

    // Use prepared statement for security
    $stmt = $conn->prepare("DELETE FROM employees WHERE employee_id = ?");
    $stmt->bind_param("i", $employee_id);

    // Set feedback messages based on execution
    if ($stmt->execute()) {
        $_SESSION['success'] = "Employee deleted successfully!";
    } else {
        $_SESSION['error'] = "Error: Unable to delete Employee.";
    }

    $stmt->close();
}

$conn->close();

// Redirect silently to employee-list.php
header("Location: ../employee-list.php");
exit();
?>