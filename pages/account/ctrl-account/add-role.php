<?php
include '../../../includes/conn.php'; // Database connection
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role = trim($_POST['role']);

    // Check if role already exists
    $checkStmt = $conn->prepare("SELECT COUNT(*) FROM roles WHERE role_name = ?");
    $checkStmt->bind_param("s", $role);
    $checkStmt->execute();
    $checkStmt->bind_result($roleCount);
    $checkStmt->fetch();
    $checkStmt->close();

    if ($roleCount > 0) {
        $_SESSION['error'] = "Role already exists!";
    } else {
        // Insert new role
        $stmt = $conn->prepare("INSERT INTO roles (role_name) VALUES (?)");
        $stmt->bind_param("s", $role);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Role added successfully!";
        } else {
            $_SESSION['error'] = "Error: Unable to add role.";
        }
        $stmt->close();
    }

    $conn->close();
    header("Location: ../role.php"); // Redirect after setting session messages
    exit();
}
?>